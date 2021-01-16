<?php
require_once "utilitiesACA.php";
$urlConsume = "https://www.metacritic.com/";
$cURL = new utilitiesACA();

$website = $cURL->consumeURL($urlConsume);

mkdir ("./website",
    0777, //irrelevant in Windows
    true);



echo file_put_contents("./website/site.html", $website);

// ***************************************************************************************
function remove_http($url) {
    $disallowed = array('http://', 'https://');
    foreach($disallowed as $d) {
        if(strpos($url, $d) === 0) {
            return str_replace($d, '', $url);
        }
    }
    return $url;
}