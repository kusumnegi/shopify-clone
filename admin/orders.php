<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: index.php');
  exit;
}
include 'config/db.php';

// Handle date filter
$filterDate = $_GET['date'] ?? '';

// Build query
$sql = "SELECT * FROM orders";
if (!empty($filterDate)) {
  $sql .= " WHERE DATE(order_date) = ?";
  $stmt = $conn->prepare($sql . " ORDER BY order_date DESC");
  $stmt->bind_param("s", $filterDate);
} else {
  $stmt = $conn->prepare($sql . " ORDER BY order_date DESC");
}
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Shopify Admin - Orders</title>
  <link rel="stylesheet" href="assest/css/dashboard.css">
  <link rel="stylesheet" href="assest/css/orders.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
  <div class="flex">
    <div class="sidebar p-3 ">
      <h4 class="mb-4">My Dashboard</h4>
      <a href="dashboard.php">🏠 Home</a>
      <a href="navbar.php">📁 Navbar</a>
      <a href="hero.php">🦸 Hero</a>
      <a href="Products.php">📦 Products</a>
      <a href="backstock.php">📈 Back in Stocks</a>
      <a href="products_video.php">📷 Product Video</a>
      <a href="footer.php"> 📄 Footer</a>
      <a href="orders.php">🚚 All Orders</a>
      <a href="" data-bs-toggle="modal" data-bs-target="#logout" class="text-danger">🚪 Logout</a>
    </div>

    <div class="modal fade" id="logout" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5">Logout Confirmation</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">Are you sure you want to logout?</div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-danger" onclick="window.location.href='logout.php'">Logout</button>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid p-3 home-content">
        <h3 class="mb-4">📦 Admin: All Orders</h3>

        <div class="filter-box">
          <h5 class="mb-3">📅 Filter Orders by Date</h5>
          <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
              <label for="filterDate" class="form-label">Select Date</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-calendar-event"></i></span>
                <input type="date" name="date" id="filterDate" class="form-control" value="<?= htmlspecialchars($filterDate) ?>">
              </div>
            </div>
            <div class="col-auto">
              <button type="submit" class="btn btn-primary">🔍 Apply Filter</button>
            </div>
            <?php if ($filterDate): ?>
              <div class="col-auto">
                <a href="orders.php" class="btn btn-outline-secondary">❌ Clear Filter</a>
              </div>
            <?php endif; ?>
          </form>
        </div>

        <?php if (empty($orders)): ?>
          <div class="no-orders">
            No orders found<?= $filterDate ? " for <strong>$filterDate</strong>" : '' ?>.
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white align-middle">
              <thead class="table-dark">
                <tr>
                  <th>Image</th>
                  <th>Title</th>
                  <th>Size</th>
                  <th>Order Date</th>
                  <th>Price</th>
                  <th>Payment</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($orders as $order): ?>
                  <tr class="cursor-pointer" onclick="toggleDetails(<?= $order['id'] ?>)">
                    <td><img src="uploads/products/<?= htmlspecialchars($order['product_image']) ?>" class="order-image"></td>
                    <td><?= htmlspecialchars($order['product_title']) ?></td>
                    <td><?= htmlspecialchars($order['size'] ?: '-') ?></td>
                    <td><?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></td>
                    <td>₹<?= number_format($order['price'], 2) ?></td>
                    <td><?= ucfirst($order['payment_method']) ?></td>
                  </tr>
                  <tr id="details-<?= $order['id'] ?>" class="order-details-row">
                    <td colspan="6">
                      <div class="card p-3">
                        <div class="mb-2"><strong>Customer:</strong> <?= htmlspecialchars($order['name']) ?></div>
                        <div class="mb-2"><strong>Phone:</strong> <?= htmlspecialchars($order['phone']) ?></div>
                        <div class="mb-2"><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></div>
                        <div class="mb-2"><strong>Address:</strong><br><?= htmlspecialchars($order['address']) ?>, <?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?>, <?= htmlspecialchars($order['country']) ?></div>
                        <div class="mt-2">
                          <strong>Order Status:</strong>
                          <?php if (strtolower($order['status']) === 'cancelled'): ?>
                            <div class="text-danger fw-bold mt-2">❌ Cancelled by User</div>
                          <?php else: ?>
                            <select class="form-select status-dropdown mt-2" data-order-id="<?= $order['id'] ?>">
                              <?php
                              $statuses = ['Pending', 'Shipping', 'Out for Delivery', 'Delivered'];
                              foreach ($statuses as $status) {
                                $selected = ($order['status'] === $status) ? 'selected' : '';
                                echo "<option value='$status' $selected>$status</option>";
                              }
                              ?>
                            </select>
                            <span class="text-success ms-3 status-msg" style="display:none;">✅ Updated</span>
                          <?php endif; ?>
                        </div>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    function toggleDetails(id) {
      const row = document.getElementById('details-' + id);
      row.style.display = row.style.display === 'table-row' ? 'none' : 'table-row';
    }

    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.status-dropdown').forEach(select => {
        select.addEventListener('change', function() {
          const orderId = this.getAttribute('data-order-id');
          const newStatus = this.value;
          const msgSpan = this.nextElementSibling;

          fetch('update_order_status.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: `order_id=${orderId}&status=${encodeURIComponent(newStatus)}`
            })
            .then(res => res.text())
            .then(response => {
              console.log('Server Response:', response);
              if (response.trim() === 'success') {
                msgSpan.style.display = 'inline';
                setTimeout(() => {
                  msgSpan.style.display = 'none';
                }, 1500);
              } else {
                alert('Failed to update status');
              }
            });
        });
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>