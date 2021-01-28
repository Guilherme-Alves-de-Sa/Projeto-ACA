<?php   require_once "Main.php";

$Main = new Main();
$page = 1;
$end = 0;
$beggining = 0;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Metacritic Easy Search</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <style>
        html, body {
            min-height: 100%;
        }
        body, div, form, input, select {
            padding: 0;
            margin: 0;
            outline: none;
            font-family: Roboto, Arial, sans-serif;
            font-size: 14px;
            color: #666;
            line-height: 22px;
        }
        h1, h4 {
            margin: 15px 0 4px;
            font-weight: 400;
        }
        h4 {
            margin: 20px 0 4px;
            font-weight: 400;
        }
        span {
            color: #ff0000;
        }
        .small {
            font-size: 10px;
            line-height: 18px;
        }
        .testbox {
            display: flex;
            justify-content: center;
            align-items: center;
            height: inherit;
            padding: 3px;
        }
        form {
            width: 100%;
            padding: 20px;
            background: #fff;
            box-shadow: 0 2px 5px #ccc;
        }
        input {
            width: calc(100% - 10px);
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            vertical-align: middle;
        }
        input:hover, textarea:hover, select:hover {
            outline: none;
            border: 1px solid #095484;
            background: #e6eef7;
        }
        .title-block select, .title-block input {
            margin-bottom: 10px;
        }
        select {
            padding: 7px 0;
            border-radius: 3px;
            border: 1px solid #ccc;
            background: transparent;
        }
        select, table {
            width: 100%;
        }
        option {
            background: #fff;
        }
        .day-visited, .time-visited {
            position: relative;
        }
        input[type="date"]::-webkit-inner-spin-button {
            display: none;
        }
        input[type="time"]::-webkit-inner-spin-button {
            margin: 2px 22px 0 0;
        }
        .day-visited i, .time-visited i, input[type="date"]::-webkit-calendar-picker-indicator {
            position: absolute;
            top: 8px;
            font-size: 20px;
        }
        .day-visited i, .time-visited i {
            right: 5px;
            z-index: 1;
            color: #a9a9a9;
        }
        [type="date"]::-webkit-calendar-picker-indicator {
            right: 0;
            z-index: 2;
            opacity: 0;
        }
        .question-answer label {
            display: block;
            padding: 0 20px 10px 0;
        }
        .question-answer input {
            width: auto;
            margin-top: -2px;
        }
        th, td {
            width: 18%;
            padding: 15px 0;
            border-bottom: 1px solid #ccc;
            text-align: center;
            vertical-align: unset;
            line-height: 18px;
            font-weight: 400;
            word-break: break-all;
        }
        .first-col {
            width: 25%;
            text-align: left;
        }
        textarea {
            width: calc(100% - 6px);
        }
        .btn-block {
            margin-top: 20px;
            text-align: center;
        }
        button {
            width: 40px;
            padding: 10px;
            border: none;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            background-color: #095484;
            font-size: 16px;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background-color: #0666a3;
        }

        @media (min-width: 568px) {
            .title-block {
                display: flex;
                justify-content: space-between;
            }
            .title-block select {
                width: 30%;
                margin-bottom: 0;
            }
            .title-block input {
                width: 31%;
                margin-bottom: 0;
            }
            th, td {
                word-break: keep-all;
            }
        }
    </style>
</head>
<body>
<div class="testbox">
    <form name="actionPost" method="post">
        <h4>Table<span>*</span></h4>
        <div class="title-block">
            <select name="table">
                <option value="metacritic_games" selected>Games</option>
                <option value="metacritic_movies">Movies</option>
                <option value="metacritic_music">Albums</option>
                <option value="metacritic_tv_shows">TV Shows</option>
            </select>
        </div>
        <h4>Order</h4>
        <div class="title-block">
            <select name="order">
                <option value="DESC" selected>DESC</option>
                <option value="ASC">ASC</option>
            </select>
        </div>
        <h4>By<span></span></h4>
        <div class="title-block">
            <select name="col">
                <option value="score" selected>Score</option>
                <option value="title">Title</option>
                <option value="datePublish">Date</option>
            </select>
        </div>
        <h4>Number of Rows<span></span></h4>
        <div class="title-block">
            <select name="limit">
                <option value="100" selected>100</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div class="btn-block">
            <button style="width: 150px" type="submit">Get List</button>
        </div>

    </form>
</div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        echo '<div>';
            $table = $_POST['table'];
            $order = $_POST['order'];
            $col = $_POST['col'];
            $limit = $_POST['limit'];
            $data = $Main->select($table, $order, $col);
        echo '
<div class="testbox">
<div class="btn-block">
            <form method="post">';
            for($j = 0; $j < count($data) / $limit; $j++){
                echo '<button name='.$j.' type="submit" value='.$j.'>'.$j.'</button>';
            }
            echo '
<input hidden value="'.$limit.' " name="limit"/>
<input hidden value="'.$col.' "name="col"/>
<input hidden value="'.$table.' "name="table"/>
<input hidden value="'.$order.' "name="order"/>
</form>
        </div></div>';
            echo '<h1>' . $table . '</h1>';
            $start = 0;
            if(count($_POST) > 4){
                foreach ($_POST as $post){
                    if(strcmp($post,$limit) !== 0 && strcmp($post,$col) !== 0 &&
                    strcmp($post,$table) !== 0 && strcmp($post,$order) !== 0){
                        $start = $post;
                    }
                }
            }

            for($i = $start*$limit; $i < $limit*($start+1); $i++){
                $rows = $data[$i];
                echo
                '<table
        <tbody >
        <tr >
        <td > Photo</td >
        <td > ID</td >
        <td > URL</td >
        <td > Title</td >
        <td > Score</td >
        <td > Publish Date</td >
    </tr >
    <tr  >';
                echo "<td><img src=" . $rows['photoUrl'] . "></td>";
                foreach ($rows as $key => $value) {
                    if (strcmp($key, "photoUrl") !== 0 && strcmp($key, "summary") !== 0) {
                        echo "<td>" . $value . "</td>";
                    }
                }
                echo '</table
        </tbody>';
                echo '<div >' . $rows["summary"] . '</div>';
                echo '<br> <br>';
                if($data[$i+1] === null) break;
            }
        }

    echo '</div>';
    ?>

</body>
</html>