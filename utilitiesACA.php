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

    //---------------------------------- CONSUMING ----------------------------------
}