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
</head>
<body>
<h1>list</h1>
<table><thead align="left" style="display: table-header-group"><tr><th>

        </th></tr></thead>
    <tbody>
    <?php foreach ($data as $rows) :?>
        <tr class="item_row">
            <?php
            $total = 0;
            foreach ($rows as $key => $value) : ?>
                <td> <?php echo $value ?></td>
            <?php endforeach;?>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

</body>
</html>