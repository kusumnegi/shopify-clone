<?php
session_start();
include 'admin/config/db.php';

if (!isset($_SESSION['user'])) {
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = (int)$_POST['order_id'];
    $user_id = (int)$_SESSION['user']['id'];

    $sql = "UPDATE orders 
            SET status='Cancelled' 
            WHERE id=? AND user_id=? 
              AND (status IS NULL OR TRIM(LOWER(status)) IN ('pending','shipping'))";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $order_id, $user_id);

    if (!$stmt->execute()) {
        die("Cancel failed: " . $stmt->error);
    }

    $stmt->close();
    header('Location: my-orders.php');
    exit;
}
