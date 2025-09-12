<?php
session_start();
include 'admin/config/db.php';

$userId = $_SESSION['user']['id'] ?? 0;
if (!$userId) {
    header("Location: user/");
    exit;
}

$cartId = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
if ($cartId > 0) {
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cartId, $userId);
    $stmt->execute();
    $stmt->close();
}

header("Location: cart.php");
exit;
