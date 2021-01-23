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

DEFINE("countOfArgs", $argc);
DEFINE("arrayOfArgs", $argv);

if(countOfArgs > 1){

    switch (arrayOfArgs[1]){
        case "help": help();
        break;
        case "setup": setup();
        break;
        case "select": select();
        break;
        case "selectHelp" : selectHelp();
        break;
        default: echo "Unknown argument\n";
        break;
    }

}
else help();

function help(){
    echo "'php Main.php help'  ->   shows available arguments\n";
    echo "'php Main.php setup'  ->   setup of database\n";
    echo "'php Main.php select *no more arguments*'  ->   select all from db\n";
    echo "for list of select commands, do: 'php Main.php selectHelp\n";
}

function selectHelp(){
    echo "'php Main.php setup + *argument*'\n";
    echo "arguments:\nselect all -> shows contents of all tables\n";
}

function setup()
{
    $db = new mySQL();
    $db->install();

    $urlConsume = "https://www.metacritic.com";
    $cURL = new utilitiesACA();

    $website = $cURL->consumeURL($urlConsume); // consumes metacritic home page

// extracts from the home page the links of the top movies, music, tv-shows and games
    $entitiesInfo = $cURL->extractFromCode($website);

    var_dump($entitiesInfo);

// goes through each link extracted, they're separated by category (movie, music, tv and game)
    foreach ($entitiesInfo as $entity) {

        // goes through the links top 10 of each category
        foreach ($entity as $page) {
            $url = $urlConsume . $page; // base link + path to entity page
            $pageHtml = $cURL->consumeURL($url); // consumes entity page
            $pageInfo = $cURL->pageInfoExtraction($pageHtml, $page); // extracts the information the page


            $title = $pageInfo["Title"];
            $score = $pageInfo["Score"];
            $summary = $pageInfo["Summary"];


            if (strpos($page, "/tv/") !== false) {
//            file_put_contents("website/tv-shows.txt", $title.PHP_EOL.$score.PHP_EOL.PHP_EOL.$summary.PHP_EOL.PHP_EOL, FILE_APPEND);
                $db->insertTVshows($url, $title, $score, $summary);
            } elseif (strpos($page, "/movie/") !== false) {
//            file_put_contents("website/movies.txt", $title.PHP_EOL.$score.PHP_EOL.PHP_EOL.$summary.PHP_EOL.PHP_EOL, FILE_APPEND);
                $db->insertMovies($url, $title, $score, $summary);
            } elseif (strpos($page, "/music/") !== false) {
//            file_put_contents("website/music.txt", $title.PHP_EOL.$score.PHP_EOL.PHP_EOL.$summary.PHP_EOL.PHP_EOL, FILE_APPEND);
                $db->insertMusic($url, $title, $score, $summary);
            } elseif (strpos($page, "/game/") !== false) {
//            file_put_contents("website/games.txt", $title.PHP_EOL.$score.PHP_EOL.PHP_EOL.$summary.PHP_EOL.PHP_EOL, FILE_APPEND);
                $db->insertGames($url, $title, $score, $summary);

            }
        }
    }
}

function select(){
    $db = new mySQL();
    $db->install();

    $show = null;

    if(countOfArgs > 2) {
        switch (arrayOfArgs[2]) {
            case "all":
                $show = $db->selectAll();
                break;
            case "movies":
                $show = $db->selectAll()["Movies"];
                break;
            case "tv":
                $show = $db->selectAll()["TV Shows"];
                break;
            case "games":
                $show = $db->selectAll()["Games"];
                break;
            case "music":
                $show = $db->selectAll()["Music"];
                break;
            default:
                selectHelp();
                break;
        }
    }
    else
        $show = $db->selectAll();

    if($show !== null){
        var_dump($show);
    }
}




// ***************************************************************************************

/*
 * span class="metascore_w medium movie mixed" - score
 * <table class="releases - tabelas
 *
 *
 */