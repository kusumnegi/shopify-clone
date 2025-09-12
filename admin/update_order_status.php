<?php
include 'config/db.php'; // Update the path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;
    $status = trim($_POST['status'] ?? '');

    // Allowed statuses (no 'Cancelled')
    $allowed_statuses = ['Pending', 'Shipping', 'Out for Delivery', 'Delivered'];

    if ($order_id > 0 && in_array($status, $allowed_statuses)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'db_error';
        }
        $stmt->close();
    } else {
        echo 'invalid_input';
    }
} else {
    echo 'invalid_request';
}
