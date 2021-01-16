<?php
require_once "utilitiesACA.php";
$urlConsume = "https://www.metacritic.com/";
$cURL = new utilitiesACA();

$website = $cURL->consumeURL($urlConsume);

$cURL->extractCode($website);





//$value = trim($tables[2]->nodeValue);
//file_put_contents("./website/table.txt", $value);

@mkdir ("./website/games",
    0777, //irrelevant in Windows
    true);
@mkdir ("./website/movies",
    0777, //irrelevant in Windows
    true);
@mkdir ("./website/tv-shows",
    0777, //irrelevant in Windows
    true);
@mkdir ("./website/music",
    0777, //irrelevant in Windows
    true);



// ***************************************************************************************

/*
 * span class="metascore_w medium movie mixed" - score
 * <table class="releases - tabelas
 *
 *
 */