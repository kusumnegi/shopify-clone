<?php
session_start();
include 'admin/config/db.php';

if (!isset($_SESSION['user'])) {
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int)$_POST['order_id'];
    $user_id = $_SESSION['user']['id'];

    // Update status to Cancelled
    $stmt = $conn->prepare("UPDATE orders SET status='Cancelled' WHERE id=? AND user_id=? AND status IN ('Pending','Shipping')");
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $stmt->close();

    header('Location: my-orders.php');
    exit;
}
