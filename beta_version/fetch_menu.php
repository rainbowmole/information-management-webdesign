<?php
include 'mysqli_connect.php';

header('Content-Type: application/json');

$type = $_GET['type'] ?? 'base';

switch ($type) {
    case 'base':
        $query = "SELECT basefood_id AS id, name, image_url, description, price FROM base_foods WHERE is_available=1";
        break;
    case 'side':
        $query = "SELECT addon_id AS id, name, image_url, description, price FROM addons WHERE is_available=1 AND category='side'";
        break;
    case 'beverage':
        $query = "SELECT addon_id AS id, name, image_url, description, price FROM addons WHERE is_available=1 AND category='beverage'";
        break;
    default:
        echo json_encode([]);
        exit;
}

$result = mysqli_query($dbcon, $query);
$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
?>