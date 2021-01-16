<?php


class utilitiesACA
{
    const SIGNATURE = "For educational purposes only";
    const CLASS_MOVIES = "releases movies_releases";
    const CLASS_GAMES = "releases games_releases";
    const CLASS_MUSIC = "releases albums_releases";
    const CLASS_TV = "releases tv-shows_releases";

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

    //---------------------------------- EXTRACTION ----------------------------------

    public static function extractCode($htmlFile){
        $classes = array(self::CLASS_TV=>array(), self::CLASS_MUSIC=>array(),
           self::CLASS_MOVIES=>array(), self::CLASS_GAMES=>array());


        $oDom = new DOMDocument();

        @$oDom->loadHtml($htmlFile);

        $table = $oDom->getElementsByTagName('table');

        foreach ($table as $t){
            $class = trim($t->getAttribute('class'));

            if($class === self::CLASS_GAMES || $class === self::CLASS_MOVIES || $class === self::CLASS_MUSIC || $class === self::CLASS_TV){
                $a = $t->getElementsByTagName('a');
                $i = 0;
                foreach($a as $elem) {
                    $classA = $elem->getAttribute('class');
                    if($classA === "title"){
                        $title = $elem->getAttribute('href');
                        $classes[$class][$i] = $title;
                        $i++;
                    }

                }

            }

        }//foreach

    }// extractCode

    // $url -> link to title page, in there we'll have access to all the information about this individual entity(movie, music etc)
    public static function titlePage(){

    }
}