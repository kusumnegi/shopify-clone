<?php
session_start();
header('Content-Type: application/json');
include 'admin/config/db.php';

// ✅ Check if user is logged in
$userId = $_SESSION['user']['id'] ?? 0;
if (!$userId) {
    echo json_encode(['status' => 'error', 'message' => 'Please login first']);
    exit;
}

// Get POST data
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$size = isset($_POST['size']) ? trim($_POST['size']) : '';

if ($productId <= 0 || $quantity <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product or quantity']);
    exit;
}

// Fetch product
$stmt = $conn->prepare("SELECT id, title, sale_price, image FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    echo json_encode(['status' => 'error', 'message' => 'Product not found']);
    exit;
}

// Check if product already in cart with same size
$stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? AND size = ?");
$stmt->bind_param("iis", $userId, $productId, $size);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($existing) {
    $newQty = $existing['quantity'] + $quantity;
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $newQty, $existing['id']);
    $stmt->execute();
    $stmt->close();
} else {
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, size, quantity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $userId, $productId, $size, $quantity);
    $stmt->execute();
    $stmt->close();
}

echo json_encode(['status' => 'success', 'message' => 'Product added to cart successfully!']);
exit;
