<!-- if user is not login show nav.php if logged in then show loginheader (loginheader have settings option) -->
<?php
session_start();
include 'admin/config/db.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id']);

// Include header
if ($isLoggedIn) {
  include 'loginheader.php';
} else {
  include 'nav.php';
}
?>


<!doctype html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shopify</title>
  <link rel="icon" href="icon.png" type="image/png">
  <link rel="stylesheet" type="text/css" href="assest/css/style.css">
  <link rel="stylesheet" type="text/css" href="assest/css/query.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter&family=Lato&family=Montserrat&family=Open+Sans&family=Poppins&family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<body>
  <!-- Preloader -->
  <div id="preloader">
    <div class="loader-cart">
      <div class="cart-icon"></div>
      <p>Loading your shopping experience...</p>
    </div>
  </div>


  <!-- ---------- Shopify main section started ---------- -->
  <!-- ------------ hero section started here....!  ------------ -->
  <section class="shopify-hero-section">
    <?php
    include 'admin/config/db.php';
    $heroData = [];
    $sql = "SELECT * FROM hero ORDER BY id ASC LIMIT 2";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
      $heroData[] = $row;
    }
    ?>
    <div class="shopify-hero-wrapper">
      <div class="shopify-hero-section">
        <!-- fixed left and right images -->
        <?php if (count($heroData) >= 2): ?>
          <div class="shopify-fixed-img shopify-left-img" style="background-image: url('admin/uploads/hero/<?= htmlspecialchars($heroData[0]['image']) ?>')"></div>
          <div class="shopify-fixed-img shopify-right-img" style="background-image: url('admin/uploads/hero/<?= htmlspecialchars($heroData[1]['image']) ?>')"></div>
        <?php endif; ?>
        <div class="shopify-image-black-overlay"></div>
        <div class="shopify-overlay-hero-content">
          <p class="hero-section-main-text">Industrial Design Meets Fashion.</p>
          <span>Atypical leather goods</span><br>
          <a class="btn mt-lg-4 shopify-overlay-hero-content-btn" href="#product">Shop Now</a>
        </div>
      </div>
    </div>
  </section>
  <!-- ------------ hero section ended here....!  ------------ -->
  <section class="shopify-more-content  pt-2 pb-0 mb-0">
    <main>
      <!-- ------------ shopify isolate section started here....!  ------------ -->
      <div class="container p-lg-5 text-center shopify-isolate-container">
        <p>Obsessive Attention. Intelligent Effort.</p>
        <span>Functional handbags made of luxurious materials to improve people's lives in small but mighty ways.</span>
      </div>
      <!-- ------------ shopify isolate section ended here....!  ------------ -->

      <!-- ------------  shopify products section started here....!  ------------ -->
      <div class="shopify-products container" id="product">
        <?php
        include 'admin/config/db.php';

        // Fetch all products
        $sql = "SELECT * FROM products ORDER BY id DESC";
        $result = $conn->query($sql);
        ?>

        <div class="container pt-5 pb-5 mb-2">
          <h3 class="mb-5" style="text-decoration:underline;">Our Products</h3>
          <?php
          include 'admin/config/db.php';

          // Fetch products in ascending order so newest appear at the end
          $query = "SELECT * FROM products ORDER BY id ASC";
          $result = $conn->query($query);
          ?>

          <div class="row row-cols-2 row-cols-md-2 row-cols-lg-4 g-4">
            <?php while ($row = $result->fetch_assoc()):
              $slug = strtolower(str_replace(" ", "-", $row['title']));
              $link = "product-details.php?slug={$slug}&id={$row['id']}";
            ?>
              <div class="col">
                <a href="<?= $link ?>" class="text-decoration-none text-dark">
                  <div class="card shopify-products-card h-100 border-0 shadow-sm">

                    <!-- Image hover wrapper -->
                    <div class="shopify-product-image-hover-wrapper position-relative">
                      <img src="admin/uploads/products/<?= $row['image'] ?>" class="shopify-product-main-img" alt="Main Image">
                      <img src="admin/uploads/products/<?= $row['hover_image'] ?>" class="shopify-product-hover-img" alt="Hover Image">

                      <?php if (strtolower($row['status']) === 'out_of_stock'): ?>
                        <span class="badge out_of_stock  bg-danger position-absolute top-0 start-0 m-1 p-1 m-lg-2">Out of Stock</span>
                      <?php endif; ?>
                    </div>

                    <div class="card-body py-3">
                      <h5 class="card-title products-card-title"><?= htmlspecialchars($row['title']) ?></h5>

                      <div class="price">
                        <?php if (!empty($row['sale_price'])): ?>
                          <span class="fw-semibold ">$<?= number_format($row['sale_price'], 2) ?> CAD</span>
                        <?php endif; ?>

                        <?php if (!empty($row['price'])): ?>
                          <div>
                            <small class="text-muted text-decoration-line-through price-cdn" style="color:#878787;font-size: 18px; ">
                              $<?= number_format($row['price'], 2) ?>
                            </small>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>

                  </div>
                </a>
              </div>
            <?php endwhile; ?>
          </div>

          <?php $conn->close(); ?>



        </div>
        <!-- ------------  shopify products section ended here....!  ------------ -->

        <!-- ------------  back in stock section started here....!  ------------ -->
        <div class="container shopify-product-back-in-stock  mb-5">
          <?php
          include 'admin/config/db.php';
          $sql = "SELECT * FROM back_in_stock LIMIT 1"; // Only the latest/single row
          $result = mysqli_query($conn, $sql);
          if ($row = mysqli_fetch_assoc($result)) {
            $mainImage = 'admin/uploads/backstock/' . $row['main_image'];
            $side1 = 'admin/uploads/backstock/' . $row['side_image_1'];
            $side2 = 'admin/uploads/backstock/' . $row['side_image_2'];
          ?>

            <a href="#" class="row shopify-back-in-stock-containe-row d-flex align-items-stretch">
              <div class="col-md-8">
                <div class="shopify-back-stock-zoom-hover h-100">
                  <img src="<?php echo $mainImage; ?>" class="img-fluid w-100 h-100 object-fit-cover">
                </div>
              </div>
              <div class="col-md-4 d-flex flex-column">
                <div class="flex-fill shopify-back-stock-zoom-hover mb-2">
                  <img src="<?php echo $side1; ?>" class="img-fluid w-100 h-100 object-fit-cover">
                </div>
                <div class="flex-fill shopify-back-stock-zoom-hover">
                  <img src="<?php echo $side2; ?>" class="img-fluid w-100 h-100 object-fit-cover">
                </div>
              </div>
            </a>
          <?php } else { ?>
            <p class="text-center text-muted">No back in stock images found.</p>
          <?php } ?>
        </div>

        <!-- ------------ Product Video section started here....!  ------------ -->
        <div class="container">
          <?php
          include 'admin/config/db.php';
          // Fetch video
          $video = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products_video LIMIT 1"));
          ?>
          <?php if ($video && !empty($video['youtube_id'])): ?>
            <div class="shopify-product-video-container" id="videoBox">
              <img src="<?= !empty($video['thumbnail_image']) && file_exists("admin/uploads/products_video/" . $video['thumbnail_image'])
                          ? 'admin/uploads/products_video/' . htmlspecialchars($video['thumbnail_image'])
                          : 'https://img.youtube.com/vi/' . htmlspecialchars($video['youtube_id']) . '/hqdefault.jpg' ?>"
                alt="Thumbnail">
              <div class="shopify-product-video-overlay" onclick="playVideo('<?= htmlspecialchars($video['youtube_id']) ?>')"></div>
              <div class="shopify-product-video-play-button"><i class="fa-solid fa-circle-play" style="color: #000000;"></i></div>
            </div>
          <?php else: ?>
            <p style="text-align: center; color: red;">No video found in the database.</p>
          <?php endif; ?>
        </div>
      </div>
    </main>
    <?php include 'footer.php'; ?>
  </section>
  <!-- ---------- Shopify main section ended ---------- -->

  <script type="text/javascript" src="assest/js/script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>

</html>