<?php
require 'require_login.php'; // handles session_start
include 'admin/config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user']) || !is_array($_SESSION['user'])) {
  echo "⚠️ User not logged in.";
  exit;
}

$user   = $_SESSION['user'];
$userId = isset($user['id']) ? (int)$user['id'] : 0;

// Validate product ID
$productId    = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$selectedSize = isset($_GET['size']) ? trim($_GET['size']) : '';
$quantity     = isset($_GET['qty']) ? (int)$_GET['qty'] : 1;

if ($productId <= 0) {
  echo "⚠️ Product not found.";
  exit;
}

// Fetch product details
$product = null;
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  $product = $result->fetch_assoc();
}
$stmt->close();

if (!$product) {
  echo "❌ Invalid product.";
  exit;
}

// Prefill data
$orderData = [
  'phone'   => '',
  'address' => '',
  'city'    => '',
  'state'   => '',
  'country' => ''
];

// 1. Check last order of this user
$stmt = $conn->prepare("SELECT phone, address, city, state, country 
                        FROM orders 
                        WHERE user_id = ? 
                        ORDER BY id DESC LIMIT 1");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
  $lastOrder = $res->fetch_assoc();
  foreach ($orderData as $key => $_) {
    if (!empty($lastOrder[$key])) {
      $orderData[$key] = $lastOrder[$key];
    }
  }
} else {
  // 2. If no order found, fetch from `user` table
  $stmt2 = $conn->prepare("SELECT phone, address, city, state, country FROM user WHERE id = ? LIMIT 1");
  $stmt2->bind_param("i", $userId);
  $stmt2->execute();
  $res2 = $stmt2->get_result();
  if ($res2->num_rows > 0) {
    $userRow = $res2->fetch_assoc();
    foreach ($orderData as $key => $_) {
      if (!empty($userRow[$key])) {
        $orderData[$key] = $userRow[$key];
      }
    }
  }
  $stmt2->close();
}
$stmt->close();

// price & total
$price      = (float)$product['price'];
$totalPrice = $price * $quantity;

include 'loginheader.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .checkout-container {
      max-width: 960px;
      margin: auto;
      padding: 40px 20px;
    }

    .product-summary img {
      height: 80px;
      object-fit: cover;
    }
  </style>
</head>

<body>

  <div class="container checkout-container pt-lg-5 px-3">
    <h2 class="text-center mt-5 pt-lg-5 mb-4">🧾 Checkout</h2>

    <form method="POST" id="checkoutForm" action="place_order.php">
      <div class="row g-4">
        <!-- Delivery Info -->
        <div class="col-md-6">
          <h5>Delivery Info</h5>
          <div class="mb-2">
            <label>Name</label>
            <input type="text" name="name" class="form-control"
              value="<?= htmlspecialchars($user['name']) ?>" required>
          </div>
          <div class="mb-2">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
              value="<?= htmlspecialchars($user['email']) ?>" required>
          </div>
          <div class="mb-2">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control"
              value="<?= htmlspecialchars($orderData['phone']) ?>" required
              pattern="\d{10}" maxlength="10" title="Enter 10-digit number"
              oninput="this.value = this.value.replace(/[^0-9]/g, '')">
          </div>
          <div class="mb-2">
            <label>Address</label>
            <textarea name="address" class="form-control" required><?= htmlspecialchars($orderData['address']) ?></textarea>
          </div>
          <div class="mb-2">
            <label>City</label>
            <input type="text" name="city" class="form-control"
              value="<?= htmlspecialchars($orderData['city']) ?>" required>
          </div>
          <div class="mb-2">
            <label>State</label>
            <input type="text" name="state" class="form-control"
              value="<?= htmlspecialchars($orderData['state']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Country</label>
            <input type="text" name="country" class="form-control"
              value="<?= htmlspecialchars($orderData['country']) ?>" required>
          </div>
        </div>

        <!-- Product Summary -->
        <div class="col-md-6">
          <div class="product-summary">
            <h5>Product Summary</h5>
            <div class="card p-3 mb-3">
              <div class="d-flex">
                <img src="admin/uploads/products/<?= htmlspecialchars($product['image']) ?>"
                  class="me-3 rounded" width="100">
                <div>
                  <h6><?= htmlspecialchars($product['title']) ?></h6>
                  <p class="mb-1"><strong>Price (per piece):</strong> ₹<?= number_format($price, 2) ?></p>
                  <p class="mb-1"><strong>Quantity:</strong> <?= $quantity ?> Piece<?= ($quantity > 1 ? 's' : '') ?></p>
                  <p class="mb-1"><strong>Total Price:</strong> ₹<?= number_format($totalPrice, 2) ?></p>
                  <p class="mb-1"><strong>Status:</strong> <?= htmlspecialchars($product['status']) ?></p>

                  <!-- Size Selection -->
                  <?php if (!empty($product['sizes'])): ?>
                    <div class="mb-2">
                      <label>Select Size</label>
                      <select name="size" class="form-control" required>
                        <option value="">Choose size</option>
                        <?php
                        $sizes = explode(',', $product['sizes']);
                        foreach ($sizes as $sizeOption) {
                          $selected = ($sizeOption == $selectedSize) ? 'selected' : '';
                          echo "<option value='" . htmlspecialchars($sizeOption) . "' $selected>" . htmlspecialchars($sizeOption) . "</option>";
                        }
                        ?>
                      </select>
                    </div>
                  <?php else: ?>
                    <input type="hidden" name="size" value="">
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label><strong>Payment Method</strong></label><br>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" value="cod" checked>
                <label class="form-check-label">Cash on Delivery</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" value="upi">
                <label class="form-check-label">UPI</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" value="netbanking">
                <label class="form-check-label">Net Banking</label>
              </div>
            </div>

            <!-- Hidden inputs -->
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <input type="hidden" name="price" value="<?= $price ?>">
            <input type="hidden" name="product_title" value="<?= htmlspecialchars($product['title']) ?>">
            <input type="hidden" name="product_image" value="<?= htmlspecialchars($product['image']) ?>">
            <input type="hidden" name="quantity" value="<?= $quantity ?>">
            <input type="hidden" name="total_price" value="<?= $totalPrice ?>">

            <button type="submit" class="btn btn-primary w-100 mt-3" onclick="handlePayment(event)">Place Order</button>
          </div>
        </div>
      </div>
    </form>
  </div>

  <?php include 'footer.php'; ?>
  <script src="assest/js/checkout.js"></script>
</body>

</html>