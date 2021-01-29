<?php
    require_once("utilitiesACA.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $scrapper = new utilitiesACA();
    $html = $scrapper->consumeURL($_POST["table"]);
    $arrayAssoc = $scrapper->extractByScore($html);
    $json = json_encode($arrayAssoc, JSON_PRETTY_PRINT, JSON_FORCE_OBJECT);

    $json = stripslashes($json);

    file_put_contents("./jason.json", $json);


    header('Content-type: application/json');


    header('Content-Disposition: attachment; filename="jason.json"');


    readfile('jason.json');

}



if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $scrapper = new utilitiesACA();
    $html = $scrapper->consumeURL($_GET["table"]);
    $arrayAssoc = $scrapper->extractByScore($html);
    $arrayAssoc = json_encode($arrayAssoc);

    file_put_contents("./21.json", $arrayAssoc);

}