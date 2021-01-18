<?php


class mySQL
{
    const CREATE_SCHEMA =
        "CREATE SCHEMA IF NOT EXISTS metacritic;";

    const CREATE_TABLE_MOVIES =
        "CREATE TABLE IF NOT EXISTS metacritic_movies(
        _id INT NOT NULL AUTO_INCREMENT,
        url TEXT NOT NULL,
        title TEXT NOT NULL,
        score INT NOT NULL,
        summary TEXT NOT NULL,
        PRIMARY KEY(_id));";

    const CREATE_TABLE_GAMES=
        "CREATE TABLE IF NOT EXISTS metacritic_games(
        _id INT NOT NULL AUTO_INCREMENT,
        url TEXT NOT NULL,
        title TEXT NOT NULL,
        score INT NOT NULL,
        summary TEXT NOT NULL,
        PRIMARY KEY(_id));";

    const CREATE_TABLE_MUSIC =
        "CREATE TABLE IF NOT EXISTS metacritic_music(
        _id INT NOT NULL AUTO_INCREMENT,
        url TEXT NOT NULL,
        title TEXT NOT NULL,
        score INT NOT NULL,
        summary TEXT NOT NULL,
        PRIMARY KEY(_id));";

    const CREATE_TABLE_TV_SHOWS =
        "CREATE TABLE IF NOT EXISTS metacritic_tv_shows(
        _id INT NOT NULL AUTO_INCREMENT,
        url TEXT NOT NULL,
        title TEXT NOT NULL,
        score INT NOT NULL,
        summary TEXT NOT NULL,
        PRIMARY KEY(_id));";

    private $mHost, $mUser, $mPass, $mPort, $mDataBase;
    private $mLastErrorCode, $mLastErrorMsg;
    private $mErrorCodes, $mErrorMsgs;
    private $mDb; //fundamental!

    const DEFAULT_HOST = "localhost";
    const DEFAULT_USER = "root";
    const DEFAULT_PASS = "UkX!#s36>BE-w#4}";
    const DEFAULT_PORT = 3306;
    const DEFAULT_SCHEMA = "metacritic";

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

    public function insertMovies(
        string $url,
        string $title,
        string $score,
        string $summary
    ){
        $q = "INSERT INTO metacritic_movies  VALUES (".
            "null, '$url', '$title', '$score', '$summary');";

        $this->mDb->query($q);

        $this->updateErrors();
        $this->errorFb();
    }//insertUrl

    public function insertMusic(
        string $url,
        string $title,
        string $score,
        string $summary
    ){
        $q = "INSERT INTO metacritic_music  VALUES (".
            "null, '$url', '$title', '$score', '$summary');";

        $this->mDb->query($q);

        $this->updateErrors();
        $this->errorFb();
    }//insertUrl

    public function insertGames(
        string $url,
        string $title,
        string $score,
        string $summary
    ){
        $q = "INSERT INTO metacritic_games  VALUES (".
            "null, '$url', '$title', '$score', '$summary');";

        $this->mDb->query($q);

        $this->updateErrors();
        $this->errorFb();
    }//insertUrl

    public function insertTVshows(
        string $url,
        string $title,
        string $score,
        string $summary
    ){
        $q = "INSERT INTO metacritic_tv_shows  VALUES (".
            "null, '$url', '$title', '$score', '$summary');";

        $this->mDb->query($q);

        $this->updateErrors();
        $this->errorFb();
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

}