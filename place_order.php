<?php
require 'require_login.php';
include 'admin/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user']['id'];

    // Collect data
    $product_id     = (int)($_POST['product_id'] ?? 0);
    $name           = trim($_POST['name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');
    $address        = trim($_POST['address'] ?? '');
    $city           = trim($_POST['city'] ?? '');
    $state          = trim($_POST['state'] ?? '');
    $country        = trim($_POST['country'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? 'cod');
    $quantity       = (int)($_POST['quantity'] ?? 1);
    $price          = (float)($_POST['price'] ?? 0);
    $product_title  = trim($_POST['product_title'] ?? '');
    $product_image  = trim($_POST['product_image'] ?? '');
    $size           = trim($_POST['size'] ?? '');

    if (!$product_id || !$name || !$email || !$phone || !$address) {
        die("⚠️ Missing required fields.");
    }

    // 🟢 Update users table (save profile info)
    $stmt = $conn->prepare("UPDATE user SET phone=?, address=?, city=?, state=?, country=? WHERE id=?");
    $stmt->bind_param("sssssi", $phone, $address, $city, $state, $country, $userId);
    $stmt->execute();
    $stmt->close();

    // Insert into orders
    $sql = "INSERT INTO orders 
            (user_id, product_id, name, email, phone, address, city, state, country, 
             payment_method, product_title, product_image, price, quantity, size, order_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iissssssssssdis",
        $userId,
        $product_id,
        $name,
        $email,
        $phone,
        $address,
        $city,
        $state,
        $country,
        $payment_method,
        $product_title,
        $product_image,
        $price,
        $quantity,
        $size
    );

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id;
        $stmt->close();
        header("Location: order_success.php?order_id=" . $order_id);
        exit;
    } else {
        die("❌ Failed to place order: " . $stmt->error);
    }
} else {
    echo "⚠️ Invalid request.";
}
