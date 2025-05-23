<?php
// checkout.php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

require('mysqli_connect.php');

$user_id = $_SESSION['user_id'];
$payment_method = 'Cash on Delivery'; //$payment_method = $_POST['payment_method']; // e.g., "Cash on Delivery"

// 1. Fetch cart items
$cart_sql = "SELECT * FROM cart WHERE user_id = ?";
$cart_stmt = mysqli_prepare($dbcon, $cart_sql);
mysqli_stmt_bind_param($cart_stmt, "i", $user_id);
mysqli_stmt_execute($cart_stmt);
$cart_result = mysqli_stmt_get_result($cart_stmt);

$items = [];
$total = 0;

while ($row = mysqli_fetch_assoc($cart_result)) {
    $food_id = $row['food_id'];
    $qty = $row['quantity'];

    // Get current price
    $price_sql = "SELECT price FROM basefood WHERE basefood_id = ?";
    $price_stmt = mysqli_prepare($dbcon, $price_sql);
    mysqli_stmt_bind_param($price_stmt, "i", $food_id);
    mysqli_stmt_execute($price_stmt);
    $price_result = mysqli_stmt_get_result($price_stmt);
    $price = mysqli_fetch_assoc($price_result)['price'];

    $total += ($price * $qty);

    $items[] = [
        'food_id' => $food_id,
        'quantity' => $qty,
        'price' => $price
    ];
}

// 2. Create order
$order_sql = "INSERT INTO orders (user_id, total_amount, payment_method, payment_status, order_status, created_at, updated_at)
              VALUES (?, ?, ?, 'Pending', 'Pending', NOW(), NOW())";
$order_stmt = mysqli_prepare($dbcon, $order_sql);
mysqli_stmt_bind_param($order_stmt, "ids", $user_id, $total, $payment_method);
mysqli_stmt_execute($order_stmt);
$order_id = mysqli_insert_id($dbcon);

// 3. Insert into order_items
foreach ($items as $item) {
    $oi_sql = "INSERT INTO order_items (order_id, food_id, quantity, price, created_at, updated_at)
               VALUES (?, ?, ?, ?, NOW(), NOW())";
    $oi_stmt = mysqli_prepare($dbcon, $oi_sql);
    mysqli_stmt_bind_param($oi_stmt, "iiid", $order_id, $item['food_id'], $item['quantity'], $item['price']);
    mysqli_stmt_execute($oi_stmt);
}

// 4. Clear cart
$clear_sql = "DELETE FROM cart WHERE user_id = ?";
$clear_stmt = mysqli_prepare($dbcon, $clear_sql);
mysqli_stmt_bind_param($clear_stmt, "i", $user_id);
mysqli_stmt_execute($clear_stmt);

?>