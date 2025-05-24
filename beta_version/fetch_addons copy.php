<?php
// fetch_addons.php
header('Content-Type: application/json');
require('mysqli_connect.php');

$basefood_id = (int) $_GET['basefood_id'];//from the menu.js it will get the basefood_id which then turned into an integer(refer to line 16)
$table = mysqli_real_escape_string($dbcon, $_GET['table']);//same with the basefood_id, it comes from the menu.js(refer to line 16)

$allowed_tables = ['lugaw_addons', 'pares_addons', 'lomi_addons', 'mami_addons', 'addons']; //this is the list of allowed tables to be accessed, pwede pa atang gawin dynamic this pero yan na muna
if (!in_array($table, $allowed_tables)) {                       //you can probably use just one table for this
    http_response_code(400);
    echo json_encode(['error' => 'Invalid addon table']);
    exit();
}

$query = "SELECT * FROM $table WHERE basefood_id = $basefood_id AND is_available = 1";   //this is the query to get the addons of the basefood_id, since we're looking for the basefood_id 
$result = mysqli_query($dbcon, $query); //execute the query               // if the add on item is connected via basefood_id it's doable to use one table for add ons                         

$addons = [];//just to collect the add ons
while ($row = mysqli_fetch_assoc($result)) {
    foreach ($row as $key => $val) {
        if (str_ends_with($key, '_id')) {
            $id_field = $key;
            break;
        }
    }

    $addons[] = [
        'id' => $row[$id_field],
        'name' => $row['name'],
        'price' => $row['price']
    ];
}

echo json_encode($addons); //to send back the add ons to the menu.js