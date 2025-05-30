<?php
include("mysqli_connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $new_status = $_POST['new_status'];

    // Whitelist allowed statuses
    $allowed_statuses = ['Pending','Preparing','On the Way','Delivered','Cancelled'];
    if (!in_array($new_status, $allowed_statuses)) {
        die("Invalid status.");
    }

    $stmt = $dbcon->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: admin.php"); // redirect back
exit;
?>