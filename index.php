<?php    ?>
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
            color: red;
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
            width: 150px;
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
<form
        method="get"
        enctype="application/x-www-form-urlencoded"
        action="setup.php"
>
    <input type="submit" value="SETUP SERVER">
</form>
<div class="testbox">
    <form action="/">
        <h4>Table<span>*</span></h4>
        <div class="title-block">
            <select>
                <option value="games" selected>Games</option>
                <option value="movies">Movies</option>
                <option value="music">Albums</option>
                <option value="tv">TV Shows</option>
            </select>
        </div>
        <h4>Order By</h4>
        <div class="title-block">
            <select>
                <option value="score" selected>Score</option>
                <option value="title">Title</option>
            </select>
        </div>
        <h4>Name<span>*</span></h4>
        <div class="title-block">
            <select>
                <option value="title" selected>Title</option>
                <option value="ms">Ms</option>
                <option value="miss">Miss</option>
                <option value="mrs">Mrs</option>
                <option value="mr">Mr</option>
            </select>
        </div>
        <h4>Email Address<span>*</span></h4>
        <input type="text" name="name" />
        <h4>Contact Number<span>*</span></h4>
        <input type="text" name="name"/>
        <h4>Name of Staff who served me</h4>
        <input type="text" name="name"/>
        <h4>Branch visited</h4>
        <input type="text" name="name"/>
        <h4>Feedback/Enquiry</h4>
        <p class="small">Please do not indicate your account or credit card number and banking instruction in your comments. Thank you for your time and valuable feedback.</p>
            <textarea rows="5"></textarea>
        <div class="btn-block">
            <button type="submit" href="/">Send Feedback</button>
        </div>
    </form>
</div>
</body>
</html>