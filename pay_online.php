<?php
require 'require_login.php';
include 'admin/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userId = $_SESSION['user']['id'];

    // Get POST data safely
    $product_id     = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $name           = trim($_POST['name'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');
    $address        = trim($_POST['address'] ?? '');
    $city           = trim($_POST['city'] ?? '');
    $state          = trim($_POST['state'] ?? '');
    $country        = trim($_POST['country'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? 'upi');
    $quantity       = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    $size           = trim($_POST['size'] ?? '');
    $price          = isset($_POST['price']) ? (float)$_POST['price'] : 0;

    // Validate required fields
    if (!$product_id || !$name || !$email || !$phone || !$address || !$city || !$state || !$country) {
        die("⚠️ Missing required fields.");
    }

    // Fetch product info from DB
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$product || !in_array((string)$product['status'], ['1', 'in_stock'], true)) {
        die("❌ Product not available.");
    }

    $product_title = $product['title'];
    $product_image = $product['image'];
    $product_price = $price > 0 ? $price : $product['price'];

    // Calculate total price
    $total_price = $product_price * $quantity;

    // Insert order (status removed so DB default/NULL will be used)
    $insert = $conn->prepare("INSERT INTO orders 
        (user_id, product_id, name, email, phone, address, city, state, country, payment_method, product_title, product_image, price, quantity, size, order_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    $insert->bind_param(
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
        $product_price,
        $quantity,
        $size
    );

    if ($insert->execute()) {
        $order_id = $insert->insert_id; // Save inserted order ID
        $insert->close();

        // Redirect to order success page with order ID
        header("Location: order_success.php?order_id=" . $order_id);
        exit;
    } else {
        die("❌ Failed to place order: " . $insert->error);
    }
} else {
    die("⚠️ Invalid request method.");
}
