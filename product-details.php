<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'admin/config/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$slug = $_GET['slug'] ?? '';

$product = null;
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

if (!$product) {
    echo "<h2 style='padding: 40px; text-align:center;'>Product not found</h2>";
    exit;
}

// Sizes
$enabledSizes = array_filter(array_map('trim', explode(',', $product['sizes'] ?? '')));
$allSizes = $product['type'] === 'shoes'
    ? ['5', '6', '7', '8', '9', '10', '11', '12']
    : ($product['type'] === 'cloth' ? ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Free'] : []);

// Gallery Images
$galleryImages = !empty($product['gallery_images']) ? array_filter(explode(',', $product['gallery_images'])) : [];
$additionalImages = [];
$stmt2 = $conn->prepare("SELECT image FROM product_images WHERE product_id = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$result2 = $stmt2->get_result();
while ($row = $result2->fetch_assoc()) {
    $additionalImages[] = $row['image'];
}
$stmt2->close();
$allGalleryImages = array_merge([$product['hover_image']], $galleryImages, $additionalImages);

// Stock status
$inStock = ($product['status'] == 1 || strtolower($product['status']) === 'in_stock');

// ✅ Check $_SESSION['user'] for login
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    include 'loginheader.php';
} else {
    include 'nav.php';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="icon.png">
    <title><?= htmlspecialchars($product['title']) ?> | Product Details</title>
    <link rel="stylesheet" href="assest/css/product-details.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <div id="preloader">
        <div class="loader-cart">
            <div class="cart-icon"></div>
            <p>Loading your shopping experience...</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row mt-5 pt-5 sticky-container">
            <!-- LEFT: Product Images -->
            <div class="col-lg-6 col-12 pe-lg-5 mb-4 pb-0 product-image-sticky">
                <div class="main-image-wrapper mb-4">
                    <img id="mainProductImage" src="admin/uploads/products/<?= $product['image'] ?>" alt="Main Image">
                </div>
                <?php if (!empty($allGalleryImages)): ?>
                    <div class="d-flex gap-2 flex-wrap thumbnail-wrapper">
                        <?php foreach ($allGalleryImages as $gimg): ?>
                            <img src="admin/uploads/products/<?= trim($gimg) ?>" alt="Gallery" onclick="document.getElementById('mainProductImage').src=this.src;">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- RIGHT: Product Details -->
            <div class="col-lg-6 col-12 product-details-scrollable">
                <h4 class="fw-bold"><?= htmlspecialchars($product['title']) ?></h4>
                <div class="price-section mb-3">
                    <span class="fs-4 fw-bold text-success">₹<?= number_format($product['sale_price'], 2) ?></span>
                    <del>₹<?= number_format($product['price'], 2) ?></del>
                </div>

                <p class="text-muted mb-2">
                    Product ID: <?= $product['id'] ?><br>
                    Status:
                    <span class="<?= $inStock ? 'text-success' : 'text-danger' ?>">
                        <?= $inStock ? 'Available' : 'Out of Stock' ?>
                    </span>
                </p>

                <?php if (!empty($allSizes)): ?>
                    <div class="my-3">
                        <strong>Available Sizes:</strong><br>
                        <?php foreach ($allSizes as $size): ?>
                            <button class="btn py-1 px-2 size-btn" <?= in_array($size, $enabledSizes) ? '' : 'disabled' ?>>
                                <?= $size ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="d-flex gap-3 mt-2 flex-wrap">
                    <button class="btn add-to-cart-btn" onclick="addToCart()">Add to Cart</button>
                    <button class="btn buy-now-btn" onclick="buyNow()" <?= !$inStock ? 'disabled style="opacity:0.6;cursor:not-allowed;"' : '' ?>>Buy Now</button>
                </div>

                <?php if (!empty($product['description'])): ?>
                    <div class="mt-5" style="width:100%;">
                        <h5 class="mb-3" style="font-weight:bold;color:#37357b;">Product Details</h5>
                        <div id="productDescription" class="description-trimmed"><?= $product['description'] ?></div>
                        <a style="text-decoration:none;" class="btn btn-link p-0" id="readMoreBtn">Read More....</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script>
        window.PRODUCT_DATA = {
            isLoggedIn: <?= isset($_SESSION['user']) ? 'true' : 'false' ?>,
            productId: <?= $product['id'] ?>,
            hasSizes: <?= !empty($allSizes) ? 'true' : 'false' ?>
        };
    </script>
    <script src="assest/js/product-details.js"></script>
</body>

</html>