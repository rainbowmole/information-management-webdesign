<?php
session_start();
error_log("Order processing started at " . date('Y-m-d H:i:s') . " for user: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'none'));
header('Content-Type: application/json');
require 'mysqli_connect.php'; // your connection file

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['cart']) || !isset($input['total_amount'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if ($userId === null) {
    echo json_encode(['success' => false, 'error' => 'User not logged in']);
    exit;
}

$cart = $input['cart'];
$totalAmount = floatval($input['total_amount']);

// For simplicity, payment_method and status hardcoded
$paymentMethod = 'Cash on Delivery';
$paymentStatus = 'Pending';
$orderStatus = 'Pending';

// Start transaction
mysqli_begin_transaction($dbcon);

try {
    // Insert order
    $stmt = $dbcon->prepare("INSERT INTO orders (user_id, total_amount, payment_method, payment_status, order_status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("idsss", $userId, $totalAmount, $paymentMethod, $paymentStatus, $orderStatus);
    $stmt->execute();

    $orderId = $stmt->insert_id;
    $stmt->close();

    // Insert order items
    foreach ($cart as $item) {
        $categoryId = intval($item['category_id']);
        $quantity = intval($item['qty']);
        $price = floatval($item['price']);
        $basefoodId = null;
        $beverageId = null;

        // Decide if basefood or beverage depending on category_id
        if ($categoryId === 1) {
            $basefoodId = intval($item['id']);
        } elseif ($categoryId === 3) {
            $beverageId = intval($item['id']);
        } else {
            // Handle other categories if needed or skip
        }

        $stmt = $dbcon->prepare("INSERT INTO order_items (order_id, basefood_id, beverage_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiid", $orderId, $basefoodId, $beverageId, $quantity, $price);
        $stmt->execute();

        $orderItemId = $stmt->insert_id;
        $stmt->close();

        // Insert addons if exist
        if (isset($item['addons']) && is_array($item['addons'])) {
            foreach ($item['addons'] as $addon) {
                $addonId = intval($addon['id']);
                $addonPrice = floatval($addon['price']);

                $stmt = $dbcon->prepare("INSERT INTO order_item_addons (order_item_id, addon_id, price) VALUES (?, ?, ?)");
                $stmt->bind_param("iid", $orderItemId, $addonId, $addonPrice);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    mysqli_commit($dbcon);

    echo json_encode(['success' => true, 'order_id' => $orderId]);
} catch (Exception $e) {
    mysqli_rollback($dbcon);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}