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
$urlConsume = "https://www.metacritic.com";
$cURL = new utilitiesACA();

$website = $cURL->consumeURL($urlConsume);

$entitiesInfo = $cURL->extractFromCode($website);

var_dump($entitiesInfo);

//$musicArray = array(
//    [1]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [2]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [3]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [4]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [5]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [6]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [7]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [8]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [9]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [10]=>array(
//        "Title" => "",
//        "Score" => "",
//    )
//);
//
//$gameArray = array(
//    [1]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [2]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [3]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [4]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [5]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [6]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [7]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [8]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [9]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [10]=>array(
//        "Title" => "",
//        "Score" => "",
//    )
//);
//
//$movieArray = array(
//    [1]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [2]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [3]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [4]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [5]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [6]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [7]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [8]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [9]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [10]=>array(
//        "Title" => "",
//        "Score" => "",
//    )
//);
//
//$tvArray = array(
//    [1]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [2]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [3]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [4]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [5]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [6]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [7]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [8]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [9]=>array(
//        "Title" => "",
//        "Score" => "",
//    ),
//    [10]=>array(
//        "Title" => "",
//        "Score" => "",
//    )
//);
$db = new mySQL();
$db->install();

foreach ($entitiesInfo as $entity){

    foreach($entity as $page){
        $url = $urlConsume . $page;
        $pageHtml = $cURL->consumeURL($url);
        $pageInfo = $cURL->pageInfoExtraction($pageHtml, $page);


        $title = $pageInfo["Title"];
        $score = $pageInfo["Score"];
        $summary = $pageInfo["Summary"];


        if(strpos($page,"/tv/") !== false){
//            file_put_contents("website/tv-shows.txt", $title.PHP_EOL.$score.PHP_EOL.PHP_EOL.$summary.PHP_EOL.PHP_EOL, FILE_APPEND);
        }
        elseif (strpos($page,"/movie/") !== false ){
//            file_put_contents("website/movies.txt", $title.PHP_EOL.$score.PHP_EOL.PHP_EOL.$summary.PHP_EOL.PHP_EOL, FILE_APPEND);
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