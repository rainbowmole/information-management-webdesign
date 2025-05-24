<?php
include 'mysqli_connect.php';

$category = $_GET['category'] ?? '';
$id = intval($_GET['id'] ?? 0);

header('Content-Type: application/json');

$addons = [];

if ($category === 'base_foods' && $id > 0) {
    $stmt = $dbcon->prepare("SELECT name, price FROM addons WHERE basefood_id = ? AND is_available = 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $addons[] = [
            'name' => $row['name'],
            'price' => floatval($row['price'])
        ];
    }
}

echo json_encode($addons);
?>
