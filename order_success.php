<?php
require 'require_login.php';
include 'admin/config/db.php';

$user = $_SESSION['user'];
$userId = $user['id'];

// Get latest order for this user
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
  echo "<h2 style='padding: 40px; text-align:center;'>No recent order found.</h2>";
  exit;
}

include 'nav.php';

// ✅ Use quantity from checkout.php (saved in orders table)
$quantity   = isset($order['quantity']) ? (int)$order['quantity'] : 1;
$price      = isset($order['price']) ? (float)$order['price'] : 0;
$totalPrice = $price * $quantity;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Order Success</title>
  <link rel="stylesheet" type="text/css" href="assest/css/order_success.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="pt-5">
    <div class="order-success-container">
      <h2 class="text-center text-success mt-5 mb-3">🎉 Order Placed Successfully!</h2>
      <p class="text-center text-muted mb-4">Thank you for shopping with us!</p>
      <hr>

      <!-- Product + Order Info -->
      <div class="d-flex flex-row align-items-start gap-3 mb-4">
        <img src="admin/uploads/products/<?= htmlspecialchars($order['product_image'] ?? 'no-image.png') ?>"
          class="product-img" alt="Product Image">
        <div>
          <h5><?= htmlspecialchars($order['product_title'] ?? 'N/A') ?></h5>
          <p><strong>Order ID:</strong> <?= $order['id'] ?></p>
          <p><strong>Size:</strong> <?= htmlspecialchars($order['size'] ?? 'N/A') ?></p>
          <p><strong>Quantity:</strong> <?= $quantity ?> Piece<?= ($quantity > 1 ? 's' : '') ?></p>
          <p><strong>Price (per piece):</strong> ₹<?= number_format($price, 2) ?></p>
          <p><strong>Total Price:</strong> ₹<?= number_format($totalPrice, 2) ?></p>
          <p><strong>Payment:</strong> <?= ucfirst($order['payment_method'] ?? 'N/A') ?></p>
        </div>
      </div>

      <!-- Delivery Info -->
      <div class="order-details">
        <p><strong>Delivery To:</strong> <?= htmlspecialchars($order['name']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
        <p><strong>Address:</strong><br>
          <?= htmlspecialchars($order['address']) ?>, <?= htmlspecialchars($order['city']) ?>,
          <?= htmlspecialchars($order['state']) ?>, <?= htmlspecialchars($order['country']) ?>
        </p>
        <p><strong>Order Date:</strong> <?= date('d M Y, h:i A', strtotime($order['order_date'] ?? 'now')) ?></p>
      </div>

      <div class="text-center mt-4">
        <a href="index.php" class="btn btn-success">Continue Shopping</a>
      </div>
    </div>
  </div>
  <?php include 'footer.php'; ?>
</body>

</html>