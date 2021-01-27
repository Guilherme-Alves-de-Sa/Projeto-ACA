<?php
require_once "Main.php";
$classMain = new Main();
$data = $classMain->select();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>list</title>
    <style>
        table, th, td {
            border: 2px solid black;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
<h1>list</h1>
<table
    <tbody >
    <?php foreach ($data as $rows) :?>
        <tr class = "item_row">
            <td>Photo</td>
            <td>ID</td>
            <td>URL</td>
            <td>Title</td>
            <td>Score</td>
            <td>Summary</td>
        </tr>
        <tr class="item_row">
            <?php
            echo "<td><img src=".$rows['photoUrl']."></td>";
            ?>
            <?php
            foreach ($rows as $key => $value) : ?>
            <?php
                if(strcmp($key, "photoUrl") !== 0) {
                    echo "<td>" . $value . "</td>";
                }
                  ?>
            <?php endforeach;?>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

</body>
</html>