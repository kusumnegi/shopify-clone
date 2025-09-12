<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: index.php');
  exit;
}
include 'config/db.php';
$target_dir = "uploads/backstock/";
$existing = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM back_in_stock LIMIT 1"));

// Handle form submit (update the one and only row)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $main_img = $existing['main_image'] ?? '';
  $side1_img = $existing['side_image_1'] ?? '';
  $side2_img = $existing['side_image_2'] ?? '';

  if (!empty($_FILES['main_image']['name'])) {
    $main_img = time() . '_' . $_FILES['main_image']['name'];
    move_uploaded_file($_FILES["main_image"]["tmp_name"], $target_dir . $main_img);
  }

  if (!empty($_FILES['side_image_1']['name'])) {
    $side1_img = time() . '_' . $_FILES['side_image_1']['name'];
    move_uploaded_file($_FILES["side_image_1"]["tmp_name"], $target_dir . $side1_img);
  }

  if (!empty($_FILES['side_image_2']['name'])) {
    $side2_img = time() . '_' . $_FILES['side_image_2']['name'];
    move_uploaded_file($_FILES["side_image_2"]["tmp_name"], $target_dir . $side2_img);
  }

  if ($existing) {
    $id = $existing['id'];
    $sql = "UPDATE back_in_stock SET 
                    main_image = '$main_img',
                    side_image_1 = '$side1_img',
                    side_image_2 = '$side2_img'
                WHERE id = $id";
  } else {
    $sql = "INSERT INTO back_in_stock (main_image, side_image_1, side_image_2)
                VALUES ('$main_img', '$side1_img', '$side2_img')";
  }

  mysqli_query($conn, $sql);
  header("Location: backstock.php");
  exit;
}

// Handle delete (optional)
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $result = mysqli_query($conn, "SELECT * FROM back_in_stock WHERE id = $id");
  $row = mysqli_fetch_assoc($result);

  unlink($target_dir . $row['main_image']);
  unlink($target_dir . $row['side_image_1']);
  unlink($target_dir . $row['side_image_2']);

  mysqli_query($conn, "DELETE FROM back_in_stock WHERE id = $id");
  header("Location: backstock.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Manage Back In Stock</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="assest/css/dashboard.css">
  <style>
    img.thumb {
      height: 100px;
      object-fit: cover;
    }
  </style>
</head>

<body class="">

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
      <h2 class="mb-4">Update Back In Stock Images</h2>

      <!-- Upload Form -->
      <form method="POST" enctype="multipart/form-data" class="card p-4 mb-4 shadow-sm">
        <div class="row mb-3">
          <div class="col-md-4">
            <label>Main Image</label>
            <input type="file" name="main_image" class="form-control">
          </div>
          <div class="col-md-4">
            <label>Side Image 1</label>
            <input type="file" name="side_image_1" class="form-control">
          </div>
          <div class="col-md-4">
            <label>Side Image 2</label>
            <input type="file" name="side_image_2" class="form-control">
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </form>

      <!-- Image Display -->
      <?php if ($existing): ?>
        <div class="row bg-white p-4 shadow-sm">
          <div class="col-md-8 mb-3">
            <strong>Main Image</strong><br>
            <img src="uploads/backstock/<?php echo $existing['main_image']; ?>" class="img-fluid">
          </div>
          <div class="col-md-4 d-flex flex-column gap-3">
            <div>
              <strong>Side Image 1</strong><br>
              <img src="uploads/backstock/<?php echo $existing['side_image_1']; ?>" class="img-fluid">
            </div>
            <div>
              <strong>Side Image 2</strong><br>
              <img src="uploads/backstock/<?php echo $existing['side_image_2']; ?>" class="img-fluid">
            </div>
          </div>
          <div class="mt-3">
            <a href="?delete=<?php echo $existing['id']; ?>" onclick="return confirm('Delete all images?')" class="btn btn-danger btn-sm">Delete All</a>
          </div>
        </div>
      <?php else: ?>
        <div class="alert alert-warning">No image data found.</div>
      <?php endif; ?>
    </div>

</body>

</html>