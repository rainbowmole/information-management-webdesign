<?php
//fetch_shipping_fee
session_start();
require'mysqli_connect.php';

$user_id = $_SESSION['user_id'];

$stmt = $dbcon->prepare("SELECT address FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$address = strtolower($user['address']);

$shipping_fee = 150;

$query = "SELECT shipping_fee, location_keywords FROM shipping_zones";
$result = mysqli_query($dbcon, $query);

while ($row = $result->fetch_assoc()) {
    if (empty($row['location_keywords'])) continue; // <-- skip 150 row
    $keywords = explode(',', strtolower($row['location_keywords']));
    foreach ($keywords as $keyword) {
        if (strpos($address, trim($keyword)) !== false) {
            $shipping_fee = $row['shipping_fee'];
            break 2;
        }
    }
}

header('Content-Type: application/json');
echo json_encode(['shipping_fee' => $shipping_fee]);

?>