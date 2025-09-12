<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: index.php');
  exit;
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shopify admin</title>
  <link rel="stylesheet" type="text/css" href="assest/css/dashboard.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>

<body>
  <!-- Sidebar -->
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

    <div class="modal fade" id="logout" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Logout Confirmation</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            Are you sure you want to logout?
          </div>

          <div class="modal-footer">
            <!-- Close just closes the modal -->
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            <!-- Save changes performs logout -->
            <button type="button" class="btn btn-danger" onclick="window.location.href='logout.php'">Logout</button>
          </div>

        </div>
      </div>
    </div>


    <div class="content ">
      <div class="container-fluid p-3 home-content">
        <h2 class="mb-4" data-aos="fade-down">Welcome.......👋</h2>
        <p> Hey..!👋<br>
          Now You Can make Changes in Website
        </p>
      </div>
    </div>
  </div>

  <script>
    AOS.init();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>