<!-- Styles -->
<link rel="icon" type="icon" href="icon.png">
<link rel="stylesheet" type="text/css" href="assest/css/nav.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<!-- Header -->
<header class="shopify-header fixed-top shadow-sm">
  <?php
  include_once __DIR__ . '/admin/config/db.php';

  $sql = "SELECT logo, nav_links, header_text FROM navbar WHERE id = 1 LIMIT 1";
  $result = $conn->query($sql);
  $logo = '';
  $navLinks = [];
  $headerText = '';

  if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $logo = $row['logo'] ?? '';
    $navLinks = json_decode($row['nav_links'], true) ?? [];
    $headerText = $row['header_text'] ?? '';
  }

  $logoUrl = 'admin/uploads/navbar/';
  $logoPath = realpath(__DIR__ . '/admin/uploads/navbar/' . $logo);
  $logoExists = $logo && $logoPath && file_exists($logoPath);

  $conn->close();
  ?>

  <div class="shopify-header-text p-lg-3 p-1"><?= htmlspecialchars($headerText) ?></div>

  <nav class="navbar navbar-expand-lg p-0 shadow-sm">
    <div class="container-fluid d-flex align-items-center flex-wrap">
      <!-- Logo -->
      <a class="navbar-brand fw-bold m-0" href="index.php">
        <?php if ($logoExists): ?>
          <img src="<?= htmlspecialchars($logoUrl . $logo) ?>" alt="Logo" class="brand-logo">
        <?php else: ?>
          <span>No Logo</span>
        <?php endif; ?>
      </a>

      <!-- NAV LINKS -->
      <div class="collapse navbar-collapse align-items-center flex-grow-1" id="navbarEcom">
        <ul class="navbar-nav mb-2 ms-auto mb-lg-0 d-flex align-items-center">
          <?php foreach ($navLinks as $link): ?>
            <?php $hasSubmenu = isset($link['submenus']) && is_array($link['submenus']) && count($link['submenus']) > 0; ?>
            <?php if ($hasSubmenu): ?>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="<?= htmlspecialchars($link['url']) ?>" id="dropdown<?= md5($link['name']) ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <?= htmlspecialchars($link['name']) ?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdown<?= md5($link['name']) ?>">
                  <?php foreach ($link['submenus'] as $submenu): ?>
                    <li><a class="dropdown-item" href="<?= htmlspecialchars($submenu['url']) ?>"><?= htmlspecialchars($submenu['name']) ?></a></li>
                  <?php endforeach; ?>
                </ul>
              </li>
            <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="<?= htmlspecialchars($link['url']) ?>"><?= htmlspecialchars($link['name']) ?></a></li>
            <?php endif; ?>
          <?php endforeach; ?>

          <!-- 👇 Mobile-only user icon -->
          <li class="nav-item d-lg-none">
            <a class="nav-link" href="user/"><i class="fa-solid fa-user"></i> Login</a>
          </li>
        </ul>
      </div>

      <!-- Right-side icons -->
      <div class="shopify-navbar-item d-flex align-items-center ms-auto">
        <!-- Mobile -->
        <div class="d-lg-none ms-1 d-flex align-items-center">
          <div class="shopify-navbar-icons position-relative" id="searchWrapperMobile">
            <div class="search-box" id="searchBoxMobile" style="display:none; position:relative;">
              <input type="text" class="p-1 form-control" placeholder="Search..." />
            </div>
            <a href="#" id="searchToggleMobile"><i class="fa-solid fa-magnifying-glass"></i></a>
            <a href="cart.php" class="d-inline d-lg-none"><i class="fa-solid fa-cart-shopping"></i></a>
          </div>
        </div>

        <!-- Navbar toggler -->
        <button class="navbar-toggler custom-toggler d-lg-none p-0 ms-2 no-outline" type="button" data-bs-toggle="collapse" data-bs-target="#navbarEcom" aria-controls="navbarEcom" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fa-solid fa-bars"></i>
        </button>

        <!-- Desktop -->
        <div class="shopify-navbar-users-icons d-none d-lg-flex align-items-center ms-3">
          <div class="icons d-flex align-items-center position-relative" id="searchWrapperDesktop">
            <div class="search-box" id="searchBoxDesktop" style="display:none; position:relative;">
              <input type="text" class="form-control" placeholder="Search..." />
            </div>
            <a href="#" class="ms-2" id="searchToggleDesktop"><i class="fa-solid fa-magnifying-glass"></i></a>
          </div>
          <a href="user/" class="ms-3"><i class="fa-solid fa-user"></i></a>
          <a href="cart.php" class="ms-3"><i class="fa-solid fa-cart-shopping"></i></a>
        </div>
      </div>
    </div>
  </nav>
</header>

<script src="assest/js/navbar.js"></script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>