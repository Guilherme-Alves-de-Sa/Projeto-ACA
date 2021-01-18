<?php

//@mkdir ("./website/games.txt",
//    0777, //irrelevant in Windows
//    true);
//@mkdir ("./website/movies.txt",
//    0777, //irrelevant in Windows
//    true);
//@mkdir ("./website/tv-shows.txt",
//    0777, //irrelevant in Windows
//    true);
//@mkdir ("./website/music.txt",
//    0777, //irrelevant in Windows
//    true);

require_once "utilitiesACA.php";
require_once "mySQL.php";

$db = new mySQL();
$db->install();

$urlConsume = "https://www.metacritic.com";
$cURL = new utilitiesACA();

$website = $cURL->consumeURL($urlConsume); // consumes metacritic home page

// extracts from the home page the links of the top movies, music, tv-shows and games
$entitiesInfo = $cURL->extractFromCode($website);

var_dump($entitiesInfo);

// goes through each link extracted, they're separated by category (movie, music, tv and game)
foreach ($entitiesInfo as $entity){

    // goes through the links top 10 of each category
    foreach($entity as $page){
        $url = $urlConsume . $page; // base link + path to entity page
        $pageHtml = $cURL->consumeURL($url); // consumes entity page
        $pageInfo = $cURL->pageInfoExtraction($pageHtml, $page); // extracts the information the page


        $title = $pageInfo["Title"];
        $score = $pageInfo["Score"];
        $summary = $pageInfo["Summary"];


        if(strpos($page,"/tv/") !== false){
//            file_put_contents("website/tv-shows.txt", $title.PHP_EOL.$score.PHP_EOL.PHP_EOL.$summary.PHP_EOL.PHP_EOL, FILE_APPEND);
        }
        elseif (strpos($page,"/movie/") !== false ){
//            file_put_contents("website/movies.txt", $title.PHP_EOL.$score.PHP_EOL.PHP_EOL.$summary.PHP_EOL.PHP_EOL, FILE_APPEND);
                echo $url.PHP_EOL.$title.PHP_EOL.$score.PHP_EOL.$summary.PHP_EOL;
            $db->insertUrl($url, $title, $score, $summary);
        }
        elseif (strpos($page,"/music/")!== false){
//            file_put_contents("website/music.txt", $title.PHP_EOL.$score.PHP_EOL.PHP_EOL.$summary.PHP_EOL.PHP_EOL, FILE_APPEND);

        }
        elseif (strpos($page,"/game/")!== false){
//            file_put_contents("website/games.txt", $title.PHP_EOL.$score.PHP_EOL.PHP_EOL.$summary.PHP_EOL.PHP_EOL, FILE_APPEND);

        }
    }
}




// ***************************************************************************************

/*
 * span class="metascore_w medium movie mixed" - score
 * <table class="releases - tabelas
 *
 *
 */