<?php
session_start();
include 'admin/config/db.php';

$userId = $_SESSION['user']['id'] ?? 0;
if (!$userId) {
    header("Location: user/");
    exit;
}

$cartId = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($cartId > 0 && in_array($action, ['increase', 'decrease'])) {
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cartId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartItem = $result->fetch_assoc();
    $stmt->close();

    if ($cartItem) {
        $quantity = (int)$cartItem['quantity'];
        if ($action === 'increase') $quantity++;
        elseif ($action === 'decrease' && $quantity > 1) $quantity--;

        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $quantity, $cartId, $userId);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: cart.php");
exit;
