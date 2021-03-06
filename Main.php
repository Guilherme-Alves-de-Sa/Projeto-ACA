<?php

require_once "utilitiesACA.php";
require_once "mySQL.php";

class Main
{

// CONSUMES THE TOP 100 OF EACH ENTITY AND INSERTS THEM INTO THE DB
    public function setup()
    {

        // DB CONNECTOR AND INSTALLATION
        $db = new mySQL();
        $db->install();

        // INITIALIZING CURL OBJECT FOR WEB CONSUMPTION
        $cURL = new utilitiesACA();

        // Functions for parameters
        $insertSetupGames = function ($pDbConnection, $pGameID, $pGameUrl, $pGameTitle, $pGameScore, $pGameSummary, $pGamePhoto, $pGameDate) {
            $pDbConnection->insertGames($pGameID, $pGameUrl, $pGameTitle, $pGameScore, $pGameSummary, $pGamePhoto, $pGameDate);
        };

        $insertSetupMovies = function ($pDbConnection, $pMoviesID, $pMoviesUrl, $pMoviesTitle, $pMoviesScore, $pMoviesSummary, $pMoviesPhoto, $pMoviesDate) {
            $pDbConnection->insertMovies($pMoviesID, $pMoviesUrl, $pMoviesTitle, $pMoviesScore, $pMoviesSummary, $pMoviesPhoto, $pMoviesDate);
        };

        $insertSetupMusic = function ($pDbConnection, $pMusicID, $pMusicUrl, $pMusicTitle, $pMusicScore, $pMusicSummary, $pMusicPhoto, $pMusicDate) {
            $pDbConnection->insertMusic($pMusicID, $pMusicUrl, $pMusicTitle, $pMusicScore, $pMusicSummary, $pMusicPhoto, $pMusicDate);
        };

        $insertSetupTvShows = function ($pDbConnection, $pTvID, $pTvUrl, $pTvTitle, $pTvScore, $pTvSummary, $pTvPhoto, $pTvDate) {
            $pDbConnection->insertTVshows($pTvID, $pTvUrl, $pTvTitle, $pTvScore, $pTvSummary, $pTvPhoto, $pTvDate);
        };

        // URL for parameters
        $urlConsumeGames = "https://www.metacritic.com/browse/games/release-date/available/pc/metascore?";
        $urlConsumeMovies = "https://www.metacritic.com/browse/movies/score/metascore/all/filtered?sort=desc&";
        $urlConsumeTV = "https://www.metacritic.com/browse/tv/score/metascore/all/filtered?sort=desc&";
        $urlConsumeMusic = "https://www.metacritic.com/browse/albums/release-date/available/metascore?";

        // CONSUMING MUSIC AND INSERTING INTO DB
        $this->generalFunctionToConsumeAndInsert($cURL, $db, $insertSetupMusic, $urlConsumeMusic);

        // CONSUMING TV SHOWS AND INSERTING INTO DB
        $this->generalFunctionToConsumeAndInsert($cURL, $db, $insertSetupTvShows, $urlConsumeTV);

        // CONSUMING MOVIES AND INSERTING INTO DB
        $this->generalFunctionToConsumeAndInsert($cURL, $db, $insertSetupMovies, $urlConsumeMovies);

        // CONSUMING GAMES AND INSERTING INTO DB
        $this->generalFunctionToConsumeAndInsert($cURL, $db, $insertSetupGames, $urlConsumeGames);

    }

    private function generalFunctionToConsumeAndInsert($cURL, $db, $pFunction, $pUrl)
    {
        $website = $cURL->consumeURL($pUrl); // consumes top 100
        $oDom = new DOMDocument();
        @$oDom->loadHtml($website);

        $li = "page last_page";
        $numPage = "page=";
        $myLi = $oDom->getElementsByTagName("li");

        foreach ($myLi as $elem) {
            if (strcmp($elem->getAttribute("class"), $li) === 0) {
                $lastPage = $elem->getElementsByTagName("a")[0]->nodeValue;
                break;
            }
        }

        for ($i = 0; $i < $lastPage; $i++) {
            $url = $pUrl . $numPage . $i;
            $website = $cURL->consumeURL($url);
            $entities = $cURL->extractByScore($website);
            foreach ($entities as $ent) {
                $pFunction($db, $ent["id"], $ent["url"], $ent["Title"], $ent["Score"], $ent["Summary"], $ent["Photo"], $ent["Date"]);
            }
        }
    }//generalFunctionToConsumeAndInsert

// CHECKING OPTIONS FOR SELECT
    public function select($table, $order, $col)
    {
        $db = new mySQL();
        $db->install();

        $argument = "ORDER BY " . $col . " " . $order;

        $dbResultAssoc = $db->selectWithOrder($argument, $table);
        return $dbResultAssoc;
    }

    public function registsExist()
    {
        $db = new mySQL();
        return $db->registsExist();
    }

}