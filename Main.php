<?php

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
        case "topMovies": topMovies();
        break;
        case "selectHelp" : selectHelp();
        break;
        case "new": testing();
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
    echo "'php Main.php select + *argument*'\n";
    echo "arguments:\nselect all -> shows contents of all tables\n";
    echo "movies OR music OR games OR tv       followed by nothing -> shows each table contents by ID\n
    \nOrder arguments:\n score";
}

function setup(){
    $urlConsumeMusic = "https://www.metacritic.com/browse/albums/release-date/new-releases/metascore";
    $urlConsumeTV = "https://www.metacritic.com/browse/tv/release-date/new-series/metascore";
    $urlConsumeMovies = "https://www.metacritic.com/browse/movies/release-date/theaters/metascore";
    $urlConsumeGames = "https://www.metacritic.com/browse/games/release-date/new-releases/pc/metascore";

    $db = new mySQL();
    $db->install();
    $cURL = new utilitiesACA();

    $websiteMusic = $cURL->consumeURL($urlConsumeMusic); // consumes metacritic home page
    $entitiesMusic = $cURL->extractByScore($websiteMusic);
    foreach ($entitiesMusic as $music){
        $db->insertMusic($music["id"], $music["url"], $music["Title"], $music["Score"], $music["Summary"], $music["Photo"]);
    }

    $websiteTV = $cURL->consumeURL($urlConsumeTV); // consumes metacritic home page
    $entitiesTV = $cURL->extractByScore($websiteTV);
    foreach ($entitiesTV as $tv){
        $db->insertTVshows($tv["id"], $tv["url"], $tv["Title"], $tv["Score"], $tv["Summary"], $tv["Photo"]);
    }

    $websiteMovies = $cURL->consumeURL($urlConsumeMovies); // consumes metacritic home page
    $entitiesMovies = $cURL->extractByScore($websiteMovies);
    foreach ($entitiesMovies as $movies){
        $db->insertMovies($movies["id"], $movies["url"], $movies["Title"], $movies["Score"], $movies["Summary"], $movies["Photo"]);
    }

    $websiteGames = $cURL->consumeURL($urlConsumeGames); // consumes metacritic home page
    $entitiesGames = $cURL->extractByScore($websiteGames);
    foreach ($entitiesGames as $games){
        $db->insertGames($games["id"], $games["url"], $games["Title"], $games["Score"], $games["Summary"], $games["Photo"]);
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
                if(countOfArgs > 3) {
                    switch (arrayOfArgs[3]) {
                        case "score":
                            $show =$db->selectWithOrder("ORDER BY score DESC", "metacritic_movies");
                        break;
                    }
                }
                else {
                    $show = $db->selectAll()["Movies"];
                }
            break;
            case "tv":
                if(countOfArgs > 3) {
                    switch (arrayOfArgs[3]) {
                        case "score":
                            $show = $db->selectWithOrder("ORDER BY score DESC", "metacritic_tv_shows");
                            break;
                    }
                }
                else {
                    $show = $db->selectAll()["TV Shows"];
                }
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

function topMovies(){
    @mkdir ("./movies",
    0777, //irrelevant in Windows
   true);

    $db = new mySQL();
    $db->install();
    $cURL = new utilitiesACA();

    $show = $db->selectWithOrder("ORDER BY score DESC", "metacritic_movies");

    for($i = 0; $i < 10; $i++){
        $file = $show[$i]["title"].".jpg";

        $photo = $cURL->consumeUrl($show[$i]["photoUrl"]);

        $replacingChars = array("'", '"');
        $badChars = array("|", "/");
        $file = str_replace($badChars, $replacingChars, $file);

        file_put_contents("./movies/".$file, $photo);
    }
}


// ***************************************************************************************

/*
 * span class="metascore_w medium movie mixed" - score
 * <table class="releases - tabelas
 *
 *
 */