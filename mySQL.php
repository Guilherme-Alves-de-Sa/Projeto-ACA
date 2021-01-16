<?php


class mySQL
{
    const CREATE_SCHEMA_MOVIES =
        "CREATE SCHEMA IF NOT EXISTS metacritic_movies;";

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

    private $mHost, $mUser, $mPass, $mPort;
    private $mLastErrorCode, $mLastErrorMsg;
    private $mErrorCodes, $mErrorMsgs;
    private $mDb; //fundamental!

    const DEFAULT_HOST = "localhost";
    const DEFAULT_USER = "root";
    const DEFAULT_PASS = "UkX!#s36>BE-w#4}";
    const DEFAULT_PORT = 3306;

    public function __construct(){
        $this->mHost = self::DEFAULT_HOST;
        $this->mUser = self::DEFAULT_USER;
        $this->mPass = self::DEFAULT_PASS;
        $this->mPort = self::DEFAULT_PORT;

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

    public function install(){
        if ($this->mDb){
            $this->mDb->query(self::CREATE_SCHEMA_MOVIES);
            $this->updateErrors();
            $this->errorFb();

            $this->mDb->query(self::CREATE_TABLE_MOVIES);
            $this->updateErrors();
            $this->errorFb();
        }//if
    }//install

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

    public function insertUrl(
        string $url,
        string $title,
        string $score,
        string $summary
    ){
        $q = "INSERT INTO metacritic_movies  VALUES (".
            "null, '$url', '$title', '$score','$summary');";

        $this->mDb->query($q);

        $this->updateErrors();
        $this->errorFb();
    }//insertUrl
}