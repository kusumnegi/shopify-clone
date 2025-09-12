<?php
require 'require_login.php';
include 'loginheader.php';
include 'admin/config/db.php';

// Get logged-in user ID
$user = $_SESSION['user'] ?? null;
$userId = isset($user['id']) ? (int)$user['id'] : 0;

if (!$userId) {
    die("User not logged in.");
}

// Fetch cart items for the logged-in user using prepared statement
$cart = [];
$stmt = $conn->prepare("
    SELECT c.id AS cart_id, c.product_id, c.size, c.quantity, 
           p.title, p.image, p.price, p.sale_price, p.status
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$cart = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assest/css/cart.css">
</head>

<body>
    <div class="container py-5">
        <h2 class="mb-4 mt-5 pt-5 text-center">🛒 Your Cart</h2>

        <?php if (empty($cart)): ?>
            <div class="cart-empty text-center py-5 text-muted">Your cart is currently empty.</div>
        <?php else: ?>

            <!-- Desktop View -->
            <div class="desktop-view">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Size</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $grandTotal = 0; ?>
                        <?php foreach ($cart as $index => $item): ?>
                            <?php
                            // Use sale_price if available, otherwise fallback to price
                            $displayPrice = !empty($item['sale_price']) ? $item['sale_price'] : $item['price'];
                            $total = $displayPrice * $item['quantity'];
                            $grandTotal += $total;
                            $inStock = ($item['status'] == 1 || strtolower($item['status']) === 'in_stock');
                            ?>
                            <tr>
                                <td><img src="admin/uploads/products/<?= htmlspecialchars($item['image']) ?>" class="cart-img img-fluid"></td>
                                <td>
                                    <a href="product-details.php?id=<?= $item['product_id'] ?>" class="text-decoration-none">
                                        <span class="truncate" title="<?= htmlspecialchars($item['title']) ?>">
                                            <?= htmlspecialchars($item['title']) ?>
                                        </span>
                                    </a>
                                    <?php if (!$inStock): ?>
                                        <div class="text-danger small mt-1">Out of Stock</div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($item['size']) ?></td>
                                <td>
                                    <form action="update_cart.php" method="POST" class="d-flex justify-content-center align-items-center">
                                        <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                        <button type="submit" name="action" value="decrease" class="btn btn-sm btn-outline-secondary">−</button>
                                        <input type="text" name="quantity" value="<?= $item['quantity'] ?>" readonly class="form-control mx-1 text-center" style="width: 50px;">
                                        <button type="submit" name="action" value="increase" class="btn btn-sm btn-outline-secondary">+</button>
                                    </form>
                                </td>
                                <td>₹<?= number_format($displayPrice, 2) ?></td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center working-btn">
                                        <form method="POST" action="remove_from_cart.php" onsubmit="return confirm('Remove this item?');">
                                            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                        </form>
                                        <?php if ($inStock): ?>
                                            <a href="checkout.php?id=<?= $item['product_id'] ?>&size=<?= urlencode($item['size']) ?>&qty=<?= $item['quantity'] ?>" class="btn btn-sm btn-success">Checkout</a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-secondary w-100" style="height: fit-content !important; width: fit-content !important; padding: 5px 8px;" disabled>Checkout</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">
                                <div class="d-flex justify-content-end">
                                    <div class="px-4 py-2 border rounded bg-light shadow-sm" style="font-size: 22px; color: #333; font-weight: bold;">
                                        Grand Total: ₹<?= number_format($grandTotal, 2) ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Mobile View -->
            <div class="mobile-view">
                <?php foreach ($cart as $index => $item): ?>
                    <?php
                    $inStock = ($item['status'] == 1 || strtolower($item['status']) === 'in_stock');
                    $displayPrice = !empty($item['sale_price']) ? $item['sale_price'] : $item['price'];
                    ?>
                    <div class="card mb-3 shadow-sm">
                        <div class="row g-0">
                            <div class="col-4 d-flex align-items-center justify-content-center">
                                <img src="admin/uploads/products/<?= htmlspecialchars($item['image']) ?>" class="img-fluid cart-img rounded-start">
                            </div>
                            <div class="col-8">
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1 truncate" title="<?= htmlspecialchars($item['title']) ?>"><?= htmlspecialchars($item['title']) ?></h6>
                                    <?php if (!$inStock): ?>
                                        <p class="text-danger small mb-1" style="font-size:16px;">Out of Stock</p>
                                    <?php endif; ?>
                                    <p class="mb-1 font-size"><strong>Size:</strong> <?= htmlspecialchars($item['size']) ?></p>
                                    <p class="mb-1 font-size"><strong>Price:</strong> ₹<?= number_format($displayPrice, 2) ?></p>

                                    <form action="update_cart.php" method="POST" class="d-flex align-items-center justify-content-start mb-2 mt-2">
                                        <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                        <button type="submit" name="action" value="decrease" class="btn btn-sm btn-outline-secondary px-2">−</button>
                                        <input type="text" name="quantity" value="<?= $item['quantity'] ?>" readonly class="form-control mx-2 text-center" style="min-height: 20px; height: fit-content; padding: 4px;">
                                        <button type="submit" name="action" value="increase" class="btn btn-sm btn-outline-secondary px-2">+</button>
                                    </form>

                                    <div class="d-flex working-btn mt-4 gap-2">
                                        <form method="POST" action="remove_from_cart.php" onsubmit="return confirm('Remove this item?');" class="w-100">
                                            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger w-100" style="font-size: 14px;">Remove</button>
                                        </form>
                                        <?php if ($inStock): ?>
                                            <a href="checkout.php?id=<?= $item['product_id'] ?>&size=<?= urlencode($item['size']) ?>&qty=<?= $item['quantity'] ?>" class="btn btn-sm btn-success w-100" style="font-size: 14px;">Checkout</a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-secondary w-100" style=" font-size: 13px; height: fit-content!important; padding: 6px 4px;" disabled>Checkout</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="text-end fw-bold mt-3 px-3 py-2 border rounded bg-light shadow-sm" style="font-size: 16px; color: #333;">
                    Grand Total: ₹<?= number_format($grandTotal, 2) ?>
                </div>
            </div>

        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>