<?php

class mySQL
{
    const TABLE_MOVIES = "metacritic_movies";
    const TABLE_TV_SHOWS = "metacritic_tv_shows";
    const TABLE_MUSIC = "metacritic_music";
    const TABLE_GAMES = "metacritic_games";

    const DEFAULT_SCHEMA = "metacritic";

    const CREATE_SCHEMA =
        "CREATE SCHEMA IF NOT EXISTS ".self::DEFAULT_SCHEMA.";";

    const CREATE_TABLE_MOVIES =
        "CREATE TABLE IF NOT EXISTS metacritic_movies(
        _id INT NOT NULL,
        url TEXT NOT NULL,
        title TEXT NOT NULL,
        score INT NOT NULL,
        summary TEXT NOT NULL,
        photoUrl TEXT NOT NULL,
        datePublish DATE,
        PRIMARY KEY(_id));";

    const CREATE_TABLE_GAMES=
        "CREATE TABLE IF NOT EXISTS metacritic_games(
        _id INT NOT NULL,
        url TEXT NOT NULL,
        title TEXT NOT NULL,
        score INT NOT NULL,
        summary TEXT NOT NULL,
        photoUrl TEXT NOT NULL,
        datePublish DATE,
        PRIMARY KEY(_id));";

    const CREATE_TABLE_MUSIC =
        "CREATE TABLE IF NOT EXISTS metacritic_music(
        _id INT NOT NULL,
        url TEXT NOT NULL,
        title TEXT NOT NULL,
        score INT NOT NULL,
        summary TEXT NOT NULL,
        photoUrl TEXT NOT NULL,
        datePublish DATE,
        PRIMARY KEY(_id));";

    const CREATE_TABLE_TV_SHOWS =
        "CREATE TABLE IF NOT EXISTS metacritic_tv_shows(
        _id INT NOT NULL,
        url TEXT NOT NULL,
        title TEXT NOT NULL,
        score INT NOT NULL,
        summary TEXT NOT NULL,
        photoUrl TEXT NOT NULL,
        datePublish DATE,
        PRIMARY KEY(_id));";

    private $mHost, $mUser, $mPass, $mPort, $mDataBase;
    private $mLastErrorCode, $mLastErrorMsg;
    private $mErrorCodes, $mErrorMsgs;
    private $mDb; //fundamental!
    private $count = 0;

    const DEFAULT_HOST = "localhost";
    const DEFAULT_USER = "root";
    const DEFAULT_PASS = "";
    const DEFAULT_PORT = 3306;

    public function __construct(){
        $this->mHost = self::DEFAULT_HOST;
        $this->mUser = self::DEFAULT_USER;
        $this->mPass = self::DEFAULT_PASS;
        $this->mPort = self::DEFAULT_PORT;
        $this->mDataBase = self::DEFAULT_SCHEMA;


        $this->mDb = mysqli_connect(
            $this->mHost,
            $this->mUser,
            $this->mPass,
            "",
            $this->mPort
        );
        $this->mLastErrorCode = mysqli_connect_errno();
        $this->mLastErrorMsg = mysqli_connect_error();
        $this->mErrorCodes[] = $this->mLastErrorCode;
        $this->mErrorMsgs[] = $this->mLastErrorMsg;

        $this->errorFb();
    }//__construct

    private function updateErrors(){
        $this->mLastErrorCode = mysqli_errno($this->mDb);
        $this->mLastErrorMsg = mysqli_error($this->mDb);
        $this->mErrorCodes[] = $this->mLastErrorCode;
        $this->mErrorMsgs[] = $this->mLastErrorMsg;
    }//updateError

    private function errorFb(){
        if ($this->mLastErrorCode!==0){
            $strMsg = sprintf(
                "Last error code: %d\n%s",
                $this->mLastErrorCode,
                $this->mLastErrorMsg
            );
            echo $strMsg;
        }
    }//errorFb

    public function registsExist(){
        return !$this->mDb->select_db(self::DEFAULT_SCHEMA);
    }

    public function insertMovies(
        string $id,
        string $url,
        string $title,
        int $score,
        string $summary,
        string $photoUrl,
        string $datePublish
    ){
        if(!is_int($score)) $score = 0;
        $q = "INSERT INTO metacritic_movies  VALUES ('$id', '$url', '$title', '$score', '$summary', '$photoUrl', '$datePublish');";

        $this->mDb->query($q);

        $this->updateErrors();
        $this->errorFb();
        $this->count++;
        if($this->count % 100 === 0) echo $this->count." *on movies*\n";
    }//insertUrl

    public function insertMusic(
        string $id,
        string $url,
        string $title,
        int $score,
        string $summary,
        string $photoUrl,
        string $datePublish
    ){
        if(!is_int($score)) $score = 0;
        $q = "INSERT INTO metacritic_music  VALUES ('$id', '$url', '$title', '$score', '$summary', '$photoUrl', '$datePublish');";

        $this->mDb->query($q);

        $this->updateErrors();
        $this->errorFb();
        $this->count++;
        if($this->count % 100 === 0) echo $this->count." *on music*\n";
    }//insertUrl

    public function insertGames(
        string $id,
        string $url,
        string $title,
        int $score,
        string $summary,
        string $photoUrl,
        string $datePublish
    ){
        if(!is_int($score)) $score = 0;
        $q = "INSERT INTO metacritic_games  VALUES ('$id', '$url', '$title', '$score', '$summary', '$photoUrl', '$datePublish');";

        $this->mDb->query($q);

        $this->updateErrors();
        $this->errorFb();
        $this->count++;
        if($this->count % 100 === 0) echo $this->count." *on games*\n";
    }//insertUrl

    public function insertTVshows(
        string $id,
        string $url,
        string $title,
        int $score,
        string $summary,
        string $photoUrl,
        string $datePublish
    ){
        if(!is_int($score)) $score = 0;
        $q = "INSERT INTO metacritic_tv_shows  VALUES ('$id', '$url', '$title', '$score', '$summary', '$photoUrl', '$datePublish');";

        $this->mDb->query($q);

        $this->updateErrors();
        $this->errorFb();
        $this->count++;
        if($this->count % 100 === 0) echo $this->count." *on tv shows*\n";
    }//insertUrl

    public function install(){
        if ($this->mDb){

            $this->mDb->query(self::CREATE_SCHEMA);
            $this->updateErrors();
            $this->errorFb();
            $this->mDb -> select_db($this->mDataBase);

            $this->mDb->query(self::CREATE_TABLE_MOVIES);
            $this->updateErrors();
            $this->errorFb();

            $this->mDb->query(self::CREATE_TABLE_MUSIC);
            $this->updateErrors();
            $this->errorFb();

            $this->mDb->query(self::CREATE_TABLE_TV_SHOWS);
            $this->updateErrors();
            $this->errorFb();

            $this->mDb->query(self::CREATE_TABLE_GAMES);
            $this->updateErrors();
            $this->errorFb();
        }//if
    }//install

    public function selectAll(){

        $qMovies = "SELECT * FROM ".self::TABLE_MOVIES.";";
        $qTV = "SELECT * FROM ".self::TABLE_TV_SHOWS.";";
        $qMusic = "SELECT * FROM ".self::TABLE_MUSIC.";";
        $qGames = "SELECT * FROM ".self::TABLE_GAMES.";";

        $resultMovies = $this->mDb->query($qMovies);
        $this->updateErrors();
        $this->errorFb();

        $resultTV = $this->mDb->query($qTV);
        $this->updateErrors();
        $this->errorFb();

        $resultMusic = $this->mDb->query($qMusic);
        $this->updateErrors();
        $this->errorFb();

        $resultGames = $this->mDb->query($qGames);
        $this->updateErrors();
        $this->errorFb();

        $assocMovies =
            mysqli_fetch_all(
                $resultMovies,
                MYSQLI_ASSOC
            );

        $assocTV =
            mysqli_fetch_all(
                $resultTV,
                MYSQLI_ASSOC
            );

        $assocMusic =
            mysqli_fetch_all(
                $resultMusic,
                MYSQLI_ASSOC
            );

        $assocGames =
            mysqli_fetch_all(
                $resultGames,
                MYSQLI_ASSOC
            );

        $array = [
            "Movies" => $assocMovies,
            "TV Shows" => $assocTV,
            "Music" => $assocMusic,
            "Games" => $assocGames
        ];

        return $array;
    }//selectAll

    public function selectWithOrder($argument, $table){
        $q = "SELECT * FROM ".$table." ".$argument.";";

        $res = $this->mDb->query($q);
        $this->updateErrors();
        $this->errorFb();

        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    public function selectFromMovies(){

    }

}