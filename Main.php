<?php

require_once "utilitiesACA.php";
require_once "mySQL.php";


DEFINE("countOfArgs", $argc);
DEFINE("arrayOfArgs", $argv);





// CHECKING SECOND ARGUMENT, AFTER PHP FILE
if(countOfArgs > 1){

    switch (arrayOfArgs[1]){
        case "help": help();
        break;
        case "setup": setup();
        break;
        case "select": select();
        break;
        case "topMoviesPhotos": topMoviesPhotos();
        break;
        case "topMusicPhotos": topMusicPhotos();
        break;
        case "topGamesPhotos": topGamesPhotos();
        break;
        case "topTVPhotos": topTvShowsPhotos();
        break;
        case "selectHelp" : selectHelp();
        break;
        default: echo "Unknown argument\n";
        break;
    }

}
else help();

// COMMAND LINE HELP
function help(){
    echo "'php Main.php help'  ->   shows available arguments\n";
    echo "'php Main.php setup'  ->   setup of database\n";
    echo "'php Main.php select *no more arguments*'  ->   select all from db\n";
    echo "for list of select commands, do: 'php Main.php selectHelp\n";
}

// COMMAND LINE ARGUMENTS FOR SELECT
function selectHelp(){
    echo "'php Main.php select + *argument*'\n";
    echo "arguments:\nselect all -> shows contents of all tables\n";
    echo "movies OR music OR games OR tv       followed by nothing -> shows each table contents by ID\n
    \nOrder arguments:\n score";
}

// CONSUMES THE TOP 100 OF EACH ENTITY AND INSERTS THEM INTO THE DB
function setup(){

    // Functions for parameters
    $insertSetupGames = function ($pDbConnection, $pGameID, $pGameUrl, $pGameTitle, $pGameScore, $pGameSummary, $pGamePhoto){
        $pDbConnection->insertGames($pGameID, $pGameUrl, $pGameTitle, $pGameScore, $pGameSummary, $pGamePhoto);
    };

    $insertSetupMovies = function ($pDbConnection, $pMoviesID, $pMoviesUrl, $pMoviesTitle, $pMoviesScore, $pMoviesSummary, $pMoviesPhoto){
        $pDbConnection->insertMovies($pMoviesID, $pMoviesUrl, $pMoviesTitle, $pMoviesScore, $pMoviesSummary, $pMoviesPhoto);
    };

    $insertSetupMusic = function ($pDbConnection, $pMusicID, $pMusicUrl, $pMusicTitle, $pMusicScore, $pMusicSummary, $pMusicPhoto){
        $pDbConnection->insertMusic($pMusicID, $pMusicUrl, $pMusicTitle, $pMusicScore, $pMusicSummary, $pMusicPhoto);
    };

    $insertSetupTvShows = function ($pDbConnection, $pTvID, $pTvUrl, $pTvTitle, $pTvScore, $pTvSummary, $pTvPhoto){
        $pDbConnection->insertTVshows($pTvID, $pTvUrl, $pTvTitle, $pTvScore, $pTvSummary, $pTvPhoto);
    };

    // URL for parameters
    $urlConsumeGames = "https://www.metacritic.com/browse/games/release-date/available/pc/metascore?";
    $urlConsumeMovies = "https://www.metacritic.com/browse/movies/score/metascore/all/filtered?sort=desc&";
    $urlConsumeTV = "https://www.metacritic.com/browse/tv/score/metascore/all/filtered?sort=desc&";
    $urlConsumeMusic = "https://www.metacritic.com/browse/albums/release-date/available/metascore?";


    // DB CONNECTOR AND INSTALLATION
    $db = new mySQL();
    $db->install();
    // INITIALIZING CURL OBJECT FOR WEB CONSUMPTION
    $cURL = new utilitiesACA();

    // CONSUMING MUSIC AND INSERTING INTO DB
    generalFunctionToConsumeAndInsert($cURL, $db, $insertSetupMusic, $urlConsumeMusic);

    // CONSUMING TV SHOWS AND INSERTING INTO DB
    generalFunctionToConsumeAndInsert($cURL, $db, $insertSetupTvShows, $urlConsumeTV);

    // CONSUMING MOVIES AND INSERTING INTO DB
    generalFunctionToConsumeAndInsert($cURL, $db, $insertSetupMovies, $urlConsumeMovies);

    // CONSUMING GAMES AND INSERTING INTO DB
    generalFunctionToConsumeAndInsert($cURL, $db, $insertSetupGames, $urlConsumeGames);

}

function generalFunctionToConsumeAndInsert($cURL, $db, $pFunction, $pUrl){
    $website = $cURL->consumeURL($pUrl); // consumes top 100
    $oDom = new DOMDocument();
    @$oDom->loadHtml($website);

    $li = "page last_page";
    $numPage = "page=";
    $myLi = $oDom->getElementsByTagName("li");

    foreach($myLi as $elem){
       if(strcmp($elem->getAttribute("class"), $li) === 0){
           $lastPage = $elem->getElementsByTagName("a")[0]->nodeValue;
            break;
        }
    }

    for($i = 0; $i < $lastPage; $i++){
        $url = $pUrl.$numPage.$i;
        $website = $cURL->consumeURL($url);
        $entities = $cURL->extractByScore($website);
        foreach ($entities as $ent) {
            $pFunction($db, $ent["id"], $ent["url"], $ent["Title"], $ent["Score"], $ent["Summary"], $ent["Photo"]);
        }
    }
}//generalFunctionToConsumeAndInsert

// CHECKING OPTIONS FOR SELECT
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

// INSERTING TOP 10 MOVIE PHOTOS INTO FOLDER
function topMoviesPhotos(){
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

// INSERTING TOP 10 MUSIC PHOTOS INTO FOLDER
function topMusicPhotos(){
    @mkdir ("./music",
        0777, //irrelevant in Windows
        true);

    $db = new mySQL();
    $db->install();
    $cURL = new utilitiesACA();

    $show = $db->selectWithOrder("ORDER BY score DESC", "metacritic_music");

    for($i = 0; $i < 10; $i++){
        $file = $show[$i]["title"].".jpg";

        $photo = $cURL->consumeUrl($show[$i]["photoUrl"]);

        $replacingChars = array("'", '"');
        $badChars = array("|", "/");
        $file = str_replace($badChars, $replacingChars, $file);

        file_put_contents("./music/".$file, $photo);

    }
}

// INSERTING TOP 10 GAMES PHOTOS INTO FOLDER
function topGamesPhotos(){
    @mkdir ("./games",
        0777, //irrelevant in Windows
        true);

    $db = new mySQL();
    $db->install();
    $cURL = new utilitiesACA();

    $show = $db->selectWithOrder("ORDER BY score DESC", "metacritic_games");

    for($i = 0; $i < 10; $i++){
        $file = $show[$i]["title"].".jpg";

        $photo = $cURL->consumeUrl($show[$i]["photoUrl"]);

        $replacingChars = array("'", '"', "_");
        $badChars = array("|", "/", ":");
        $file = str_replace($badChars, $replacingChars, $file);

        file_put_contents("./games/".$file, $photo);

    }
}

// INSERTING TOP 10 TV SHOWS' PHOTOS INTO FOLDER
function topTvShowsPhotos(){
    @mkdir ("./tv",
        0777, //irrelevant in Windows
        true);

    $db = new mySQL();
    $db->install();
    $cURL = new utilitiesACA();

    $show = $db->selectWithOrder("ORDER BY score DESC", "metacritic_tv_shows");

    for($i = 0; $i < 10; $i++){
        $file = $show[$i]["title"].".jpg";

        $photo = $cURL->consumeUrl($show[$i]["photoUrl"]);

        $replacingChars = array("'", '"');
        $badChars = array("|", "/");
        $file = str_replace($badChars, $replacingChars, $file);

        file_put_contents("./tv/".$file, $photo);

    }
}