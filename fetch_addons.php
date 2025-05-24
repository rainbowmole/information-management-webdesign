<?php
// fetch_addons.php
header('Content-Type: application/json');
require('mysqli_connect.php');

$basefood_id = (int) $_GET['basefood_id'];//from the menu.js it will get the basefood_id which then turned into an integer(refer to line 16)

$query = "SELECT * FROM addons WHERE basefood_id = $basefood_id AND is_available = 1";   //this is the query to get the addons of the basefood_id, since we're looking for the basefood_id 
$result = mysqli_query($dbcon, $query); //execute the query               // if the add on item is connected via basefood_id it's doable to use one table for add ons                         

$addons = [];//just to collect the add ons

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $addons[] = [
            'id' => $row['addon_id'], // make sure this matches your column name
            'name' => $row['name'],
            'price' => $row['price']
        ];
    }
}

echo json_encode($addons); //to send back the add ons to the menu.js