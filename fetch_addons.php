<?php
// fetch_addons.php
header('Content-Type: application/json');
require('mysqli_connect.php');

$category_id = $_GET['category_id'] ?? null;
$basefood_id = (int) $_GET['basefood_id'];//from the menu.js it will get the basefood_id which then turned into an integer

$query = "SELECT addon_id as id, category_id, name, price FROM addons WHERE basefood_id = $basefood_id AND is_available = 1";   //this is the query to get the addons of the basefood_id, since we're looking for the basefood_id 
$result = mysqli_query($dbcon, $query); //execute the query                                                      // if the add on item is connected via basefood_id it's doable to use one table for add ons                         

$addons = [];//just to collect the add ons

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $addons[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => floatval($row['price']),
        ];
    }
}

echo json_encode($addons); //to send back the add ons to the menu.js