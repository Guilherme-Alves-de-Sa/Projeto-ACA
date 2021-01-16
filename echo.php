<?php
require_once "utilitiesACA.php";
$urlConsume = "https://git-scm.com/book/en/v2/Git-Basics-Working-with-Remotes";
$cURL = new utilitiesACA();

$website = $cURL->consumeURL($urlConsume);

mkdir ("./website",
    0777, //irrelevant in Windows
    true);

echo file_put_contents("./website/site.html", $website, FILE_APPEND);