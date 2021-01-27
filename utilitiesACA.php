<?php


class utilitiesACA
{
    const SIGNATURE = "For educational purposes only";

    //---------------------------------- CURL START ----------------------------------

    public static function consumeURL($url){

        // initializes the cURL Resource
        $curlResource= curl_init($url);

        // Array with all the cURL Options
        $curlOptions = array(
            CURLOPT_BINARYTRANSFER => true, //makes sure we want all the data (deprecated)
            CURLOPT_ENCODING => "", //handles the encoded data automatically
            CURLOPT_HTTPGET => true, //explicits the usage of HTTP Get method
            CURLOPT_SSL_VERIFYPEER => true, //enables SSL certification
            CURLOPT_USERAGENT      => self::SIGNATURE, //sets user agent for HTTP Header
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_RETURNTRANSFER => true,     // returns the web page
            CURLOPT_HEADER         => false,    // doesn't return the header
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        if($curlResource){
            
            // sets all options using the $curlOptions array
            curl_setopt_array($curlResource, $curlOptions);
            
            // variable with the operation's result
            $result = curl_exec($curlResource);

            return $result;
        }
        return false;
    }


    //---------------------------------- EXTRACTION OF ENTITY PAGE ----------------------------------

    public static function pageInfoExtraction($htmlFile){
        $oDom = new DOMDocument();
        @$oDom->loadHtml($htmlFile);

        // TITLE

        $title = $oDom->getElementsByTagName("h1")[0]->nodeValue;

        // METASCORE

        $a = $oDom->getElementsByTagName("a");
        foreach ($a as $elem){
            $att = $elem->getAttribute("class");
            if($att === "metascore_anchor"){
                $span = $elem->getElementsByTagName("span");
                $score = $span[0]->nodeValue;
                break;
            }
        }

        // REST

        $spanElems = $oDom->getElementsByTagName("span");

        $summary = "No Summary"; // in case there is no summary available on the entity's page

        foreach ($spanElems as $span){
            // apanhar Summary
               if($span->getAttribute("class") === "label" && $span->nodeValue === "Summary:"){
                   if($span->nextSibling->nextSibling->getAttribute("class")!=="inline_expand_collapse inline_collapsed"){
                       $summary = $span->nextSibling->nextSibling->nodeValue;
                       break;
                   }
               }
               if($span->getAttribute("class") === "blurb blurb_expanded"){
                   $summary = $span->nodeValue;
                   $summary = stripos($summary, "Expand");
                   break;
               }
        }

        // sets of data to insert in DB
        $info = [
            "Title" => $title,
            "Score" => $score,
            "Summary" => trim($summary)
        ];

        // string treatment to make sure they can be inserted into the DB (looking for ' and ")
        $badChars = array("'", '"');
        $replacingChars = array("|","/");
        foreach($info as $key => $value){
            $info[$key] = str_replace($badChars, $replacingChars, "$value");
        }

        return $info;
    }

    //---------------------------------- EXTRACTION OF Top 100 by Score ----------------------------------

    public static function extractByScore($htmlFile)
    {
        $entities = array();

        $oDom = new DOMDocument();
        @$oDom->loadHtml($htmlFile);

        $collectionOfDivs = $oDom->getElementsByTagName('div');

        foreach ($collectionOfDivs as $div) {
            if (strcmp($div->getAttribute("class"),"browse_list_wrapper one browse-list-large") === 0) {
                $div1To5 = $div;
            }
            if (strcmp($div->getAttribute("class"),"browse_list_wrapper two browse-list-large") === 0) {
                $div6To10 = $div;
            }
            if (strcmp($div->getAttribute("class"),"browse_list_wrapper three browse-list-large") === 0) {
                $div11To15 = $div;
            }
            if (strcmp($div->getAttribute("class"),"browse_list_wrapper four browse-list-large") === 0) {
                $div16To100 = $div;
            }
        }

        $divElements = [$div1To5, $div6To10, $div11To15, $div16To100];

        foreach ($divElements as $div) {
            if($div !== null){
            $collectionOfTr = $div->getElementsByTagName("tr");
            foreach ($collectionOfTr as $tr) {
                if (strcmp($tr->getAttribute("class"),"spacer") !== 0) {


                    @$title = $tr->getElementsByTagName("h3")[0]->nodeValue;

                    $photoUrl = $tr->getElementsByTagName("img")[0]->getAttribute("src");

                    $url = $tr->getElementsByTagName("a")[0]->getAttribute("href");

                    $divSearch = $tr->getElementsByTagName("div");
                    foreach ($divSearch as $elem) {
                        $class = $elem->getAttribute("class");
                        if (strpos($class, "metascore_w large") === 0) {
                            $score = $elem->nodeValue;
                            break;
                        }
                    }

                    // DATE
                    foreach ($divSearch as $elem) {
                        $class = $elem->getAttribute("class");
                        if (strpos($class, "clamp-details") === 0) {
                            $var = $elem->getElementsByTagName("span");
                            if(strcmp($var[0]->nodeValue, "Platform:") === 0)
                                $dateString = $var[2]->nodeValue;
                            else
                                $dateString = $var[0]->nodeValue;
                            break;
                        }
                    }
                    if(strcmp($dateString,"TBA") === 0) {
                        $dateString = "January 1, 0000";
                    }
                    $myDateTime = DateTime::createFromFormat('F d, Y', $dateString);
                    $myDateTime = date_format($myDateTime,"Y/m/d");

                    foreach ($divSearch as $elem) {
                        $class = $elem->getAttribute("class");
                        if (strcmp($class,"summary") === 0) {
                            $summary = $elem->nodeValue;
                            break;
                        }
                    }

                    $id = $tr->getElementsByTagName("input")[0]->getAttribute("id");



                    $info = [
                        "id" => $id,
                        "url" => $url,
                        "Title" => $title,
                        "Score" => $score,
                        "Summary" => trim($summary),
                        "Photo" => $photoUrl,
                        "Date" => $myDateTime
                    ];

                    $badChars = array("'", '"');
                    $replacingChars = array("|", "/");
                    foreach ($info as $key => $value) {
                        if(strcmp($key, "Date")!==0)
                        $info[$key] = str_replace($badChars, $replacingChars, "$value");
                    }
                    $entities[$id] = $info;
                }
            }
        }
    }

        return $entities;

    }

}