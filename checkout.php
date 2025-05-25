<?php
// checkout.php
session_start();

// Check if user is logged in and has the role 'user'
if (!isset($_SESSION['role']) || strtolower($_SESSION['role']) !== 'user') {
    echo "<script>alert('You must be logged in as a user to proceed with checkout.'); window.location.href='login.php';</script>";
    exit();
}

include('mysqli_connect.php');

$user_id = $_SESSION['user_id']; // Must be set from login session
$payment_method = 'Cash on Delivery'; // You can also fetch this from $_POST

// 1. Check if cart is empty
$cart_check_sql = "SELECT COUNT(*) AS item_count FROM cart WHERE user_id = ?";
$cart_check_stmt = mysqli_prepare($dbcon, $cart_check_sql);
mysqli_stmt_bind_param($cart_check_stmt, "i", $user_id);
mysqli_stmt_execute($cart_check_stmt);
$cart_check_result = mysqli_stmt_get_result($cart_check_stmt);
$cart_row = mysqli_fetch_assoc($cart_check_result);

if ($cart_row['item_count'] == 0) {
    echo "<script>alert('Your cart is empty. Please add items before checking out.'); window.location.href='menu.php';</script>";
    exit();
}

// 2. Calculate total price (basefood × quantity + addon + beverage)
$total_sql = "
    SELECT 
        SUM(
            (bf.price * c.quantity) +
            IFNULL(a.price, 0) +
            IFNULL(b.price, 0)
        ) AS total_amount
    FROM cart c
    LEFT JOIN basefood bf ON c.basefood_id = bf.basefood_id
    LEFT JOIN addons a ON c.addon_id = a.addon_id
    LEFT JOIN beverage b ON c.beverage_id = b.beverage_id
    WHERE c.user_id = ?
";
$total_stmt = mysqli_prepare($dbcon, $total_sql);
mysqli_stmt_bind_param($total_stmt, "i", $user_id);
mysqli_stmt_execute($total_stmt);
$total_result = mysqli_stmt_get_result($total_stmt);
$total_row = mysqli_fetch_assoc($total_result);
$total = $total_row['total_amount'] ?? 0.00;

// 3. Insert into orders table
$order_sql = "INSERT INTO orders (user_id, total_amount, payment_method, payment_status, order_status, created_at, updated_at)
              VALUES (?, ?, ?, 'Pending', 'Pending', NOW(), NOW())";
$order_stmt = mysqli_prepare($dbcon, $order_sql);
mysqli_stmt_bind_param($order_stmt, "ids", $user_id, $total, $payment_method);
mysqli_stmt_execute($order_stmt);
$order_id = mysqli_insert_id($dbcon);

// 4. Get cart items for order_items insertion
$cart_items_sql = "SELECT * FROM cart WHERE user_id = ?";
$cart_items_stmt = mysqli_prepare($dbcon, $cart_items_sql);
mysqli_stmt_bind_param($cart_items_stmt, "i", $user_id);
mysqli_stmt_execute($cart_items_stmt);
$cart_items_result = mysqli_stmt_get_result($cart_items_stmt);

while ($item = mysqli_fetch_assoc($cart_items_result)) {
    $basefood_id = $item['basefood_id'];
    $addon_id = $item['addon_id'];
    $beverage_id = $item['beverage_id'];
    $quantity = $item['quantity'];

    // You can calculate price per item here if needed (optional)
    // For now, just insert details into order_items

    $oi_sql = "INSERT INTO order_items (order_id, food_id, addon_id, beverage_id, quantity, price, created_at, updated_at)
               VALUES (?, ?, ?, ?, ?, 0.00, NOW(), NOW())";
    $oi_stmt = mysqli_prepare($dbcon, $oi_sql);
    mysqli_stmt_bind_param($oi_stmt, "iiiii", $order_id, $basefood_id, $addon_id, $beverage_id, $quantity);
    mysqli_stmt_execute($oi_stmt);
}

// 5. Clear cart
$clear_sql = "DELETE FROM cart WHERE user_id = ?";
$clear_stmt = mysqli_prepare($dbcon, $clear_sql);
mysqli_stmt_bind_param($clear_stmt, "i", $user_id);
mysqli_stmt_execute($clear_stmt);

// 6. Redirect or confirm
echo "<script>alert('Your order has been placed successfully!'); window.location.href='orders.php';</script>";
exit();
?>