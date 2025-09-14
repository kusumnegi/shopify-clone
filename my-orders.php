<?php
session_start();
include 'admin/config/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: user/index.php');
    exit();
}

$user_id = (int) $_SESSION['user']['id'];

// Fetch orders; treat empty/null status as 'Pending'
$sql = "SELECT *,
        CASE WHEN status IS NULL OR TRIM(status) = '' THEN 'Pending' ELSE TRIM(status) END AS display_status
        FROM orders
        WHERE user_id = ?
        ORDER BY FIELD(
            CASE WHEN status IS NULL OR TRIM(status) = '' THEN 'Pending' ELSE TRIM(status) END,
            'Out for Delivery','Shipping','Pending','Delivered','Cancelled'
        ), order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

include 'loginheader.php';

/* Normalize status to canonical lowercase values used by the user filter. */
function normalize_status($s)
{
    $s = trim((string)$s);
    if ($s === '') return 'pending';
    $s = strtolower($s);
    $s = preg_replace('/[-_]+/', ' ', $s);
    $s = preg_replace('/\s+/', ' ', $s);
    return $s;
}

/* Payment display */
function payment_display($method)
{
    $m = strtolower(trim((string)$method));
    $paid = ['net banking', 'netbanking', 'paytm', 'upi', 'card', 'credit card', 'debit card', 'razorpay', 'stripe', 'gpay', 'phonepe', 'google pay', 'amazon pay'];
    return in_array($m, $paid, true) ? 'Paid' : 'Cash on Delivery';
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>My Orders</title>
    <link rel="stylesheet" type="text/css" href="assest/css/my_orders.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5 mt-5">
        <h2 class="text-center mt-lg-5 mb-3">📦 My Orders</h2>

        <div class="filter-container d-flex flex-wrap gap-3 justify-content-between align-items-center mb-4">
            <div>
                <label for="statusFilter" class="me-2">Status</label>
                <select id="statusFilter" class="form-select d-inline-block" style="width:180px;">
                    <option value="all">All</option>
                    <option value="out for delivery">Out for Delivery</option>
                    <option value="shipping">Shipping</option>
                    <option value="pending">Pending</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div>
                <label for="dateSort" class="me-2">Sort</label>
                <select id="dateSort" class="form-select d-inline-block" style="width:180px;">
                    <option value="desc">Newest first</option>
                    <option value="asc">Oldest first</option>
                </select>
            </div>
        </div>

        <div id="ordersContainer">
            <?php if (empty($orders)): ?>
                <div class="alert alert-warning text-center">You haven't placed any orders yet.</div>
            <?php else: ?>
                <?php foreach ($orders as $order):
                    $display_status = $order['display_status'];
                    $normStatus = normalize_status($display_status);
                    $payment = payment_display($order['payment_method'] ?? '');
                ?>
                    <div class="order-item mb-3 p-2 border rounded" data-status="<?= htmlspecialchars($normStatus) ?>" data-date="<?= (int) strtotime($order['order_date']) ?>">
                        <div class="row g-3">
                            <div class="col-auto">
                                <a href="product-details.php?id=<?= (int)$order['product_id'] ?>&slug=<?= urlencode($order['product_title']) ?>">
                                    <img src="admin/uploads/products/<?= htmlspecialchars($order['product_image'] ?? 'no-image.png') ?>" alt="" class="product-img" style="width:80px; height:auto;">
                                </a>
                            </div>

                            <div class="col">
                                <a href="product-details.php?id=<?= (int)$order['product_id'] ?>&slug=<?= urlencode($order['product_title']) ?>" class="product-title text-decoration-none text-dark fw-semibold">
                                    <?= htmlspecialchars($order['product_title']) ?>
                                </a>

                                <div class="mt-1">
                                    <!-- Price and Size -->
                                    <div class="pri-odr-sts">Price / unit: ₹<?= number_format((float)$order['price'], 2) ?></div>
                                    <div class="pri-odr-sts">Size: <?= htmlspecialchars($order['size'] ?? '') ?></div>

                                    <!-- Payment and Order Date -->
                                    <div class="pri-odr-sts">Payment: <strong><?= $payment ?></strong> (<?= htmlspecialchars($order['payment_method'] ?? 'COD') ?>)</div>
                                    <div class="pri-odr-sts">Ordered on: <?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></div>

                                    <?php
                                    $statusClass = 'status-' . str_replace(' ', '-', $normStatus);
                                    $canCancel = in_array($normStatus, ['pending', 'shipping'], true);
                                    ?>
                                    <div class="mt-1">
                                        <span class="order-status <?= htmlspecialchars($statusClass) ?>">Status: <?= htmlspecialchars($display_status) ?></span>
                                    </div>

                                    <div class="mt-2">
                                        <?php if ($canCancel): ?>
                                            <form method="POST" action="cancel_order.php" onsubmit="return confirm('Cancel this order?');" style="display:inline">
                                                <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
                                                <button class="btn btn-danger btn-sm">Cancel Order</button>
                                            </form>
                                        <?php elseif ($normStatus === 'cancelled'): ?>
                                            <span class="text-danger fw-bold">Cancelled</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="assest/js/my-orders.js"></script>
</body>

</html>