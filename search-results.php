<?php
include 'admin/config/db.php';

$q = $_GET['q'] ?? '';
$q = trim($q);

$products = [];
$notFound = false;

if ($q !== '') {
  // ✅ Normalize query (remove spaces + lowercase)
  $normalizedQ = strtolower(str_replace(' ', '', $q));
  $likeQ = "%" . $normalizedQ . "%";
  $startQ = $normalizedQ . "%";

  $stmt = $conn->prepare("
        SELECT * FROM products 
        WHERE LOWER(REPLACE(title, ' ', '')) LIKE ? 
        ORDER BY 
            CASE 
                WHEN LOWER(REPLACE(title, ' ', '')) LIKE ? THEN 1
                WHEN LOWER(REPLACE(title, ' ', '')) LIKE ? THEN 2
                ELSE 3
            END,
            LENGTH(title) ASC
    ");
  $stmt->bind_param("sss", $likeQ, $startQ, $likeQ);
  $stmt->execute();
  $result = $stmt->get_result();
  $products = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  // ✅ Agar products empty hain -> random products lao
  if (empty($products)) {
    $notFound = true;
    $randomSql = "SELECT * FROM products ORDER BY RAND() LIMIT 8";
    $randomResult = $conn->query($randomSql);
    $products = $randomResult->fetch_all(MYSQLI_ASSOC);
  }
} else {
  // Agar query empty hai -> sab products
  $sql = "SELECT * FROM products ORDER BY id DESC";
  $result = $conn->query($sql);
  $products = $result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include 'admin/config/db.php';

// Agar user login hai to loginheader.php, warna nav.php
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
  include 'loginheader.php';
} else {
  include 'nav.php';
}
?>search-results.css

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="assest/css/search-results.css">
<div class="shopify-products container pt-5 mt-5" id="product">
  <div class="container pt-5 pb-5 mb-2">
    <h3 class="mb-5" style="text-decoration:underline;">
      <?php if ($notFound): ?>
        Search results for "<?= htmlspecialchars($q) ?>" not found. Here are some recommendations:
      <?php elseif ($q !== ''): ?>
        Search results for: <?= htmlspecialchars($q) ?>
      <?php else: ?>
        Our Products
      <?php endif; ?>
    </h3>

    <div class="row row-cols-2 row-cols-md-2 row-cols-lg-4 g-4">
      <?php foreach ($products as $row):
        $slug = strtolower(str_replace(" ", "-", $row['title']));
        $link = "product-details.php?slug={$slug}&id={$row['id']}";
      ?>
        <div class="col">
          <a href="<?= $link ?>" class="text-decoration-none text-dark">
            <div class="card shopify-products-card h-100 border-0 shadow-sm">

              <!-- Image hover wrapper -->
              <div class="shopify-product-image-hover-wrapper position-relative">
                <img src="admin/uploads/products/<?= $row['image'] ?>"
                  class="shopify-product-main-img"
                  alt="<?= htmlspecialchars($row['title']) ?>">
                <img src="admin/uploads/products/<?= $row['hover_image'] ?>"
                  class="shopify-product-hover-img"
                  alt="<?= htmlspecialchars($row['title']) ?>">

                <?php if (strtolower($row['status']) === 'out_of_stock'): ?>
                  <span class="badge bg-danger position-absolute top-0 start-0 m-1 p-1 m-lg-2">
                    Out of Stock
                  </span>
                <?php endif; ?>
              </div>

              <div class="card-body py-3">
                <h5 class="card-title products-card-title">
                  <?= htmlspecialchars($row['title']) ?>
                </h5>

                <div class="price">
                  <?php if (!empty($row['sale_price'])): ?>
                    <span class="fw-semibold">
                      $<?= number_format($row['sale_price'], 2) ?> CAD
                    </span>
                  <?php endif; ?>

                  <?php if (!empty($row['price'])): ?>
                    <div>
                      <small class="text-muted text-decoration-line-through" style="font-size:18px;">
                        $<?= number_format($row['price'], 2) ?>
                      </small>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>