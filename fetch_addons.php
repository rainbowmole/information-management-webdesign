<?php
// fetch_addons.php
header('Content-Type: application/json');
require('mysqli_connect.php');

$basefood_id = (int) $_GET['basefood_id'];
$table = mysqli_real_escape_string($dbcon, $_GET['table']);

$allowed_tables = ['lugaw_addons', 'pares_addons', 'lomi_addons', 'mami_addons'];
if (!in_array($table, $allowed_tables)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid addon table']);
    exit();
}

$query = "SELECT * FROM $table WHERE basefood_id = $basefood_id AND is_available = 1";
$result = mysqli_query($dbcon, $query);

$addons = [];
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

echo json_encode($addons);