<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: index.php');
  exit;
}
include 'config/db.php';

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['hero_image'])) {
  $position = $_POST['position'];
  $file = $_FILES['hero_image'];
  $uploadDir = 'uploads/hero/';
  $allowed = ['jpg', 'jpeg', 'png', 'webp'];

  if ($file['error'] === 0) {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowed)) {
      $newFileName = uniqid("hero_") . '.' . $ext;
      $targetPath = $uploadDir . $newFileName;

      if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        // Insert or update the hero image
        $stmt = $conn->prepare("SELECT id FROM hero WHERE position = ?");
        $stmt->bind_param("s", $position);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          $stmt = $conn->prepare("UPDATE hero SET image = ? WHERE position = ?");
          $stmt->bind_param("ss", $newFileName, $position);
        } else {
          $stmt = $conn->prepare("INSERT INTO hero (image, position) VALUES (?, ?)");
          $stmt->bind_param("ss", $newFileName, $position);
        }

        $stmt->execute();
        $msg = "Image uploaded successfully.";
      } else {
        $msg = "Failed to move uploaded file.";
      }
    } else {
      $msg = "Invalid file type.";
    }
  } else {
    $msg = "Upload error.";
  }
}

// Fetch current images
$heroImages = [];
$sql = "SELECT * FROM hero WHERE position IN ('left', 'right')";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
  $heroImages[$row['position']] = $row['image'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Manage Hero Images</title>
  <link rel="stylesheet" type="text/css" href="assest/css/dashboard.css">
  <link rel="stylesheet" type="text/css" href="assest/css/hero.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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


    <div class="content  p-5">
      <h3 class="mb-4">Manage Hero Section Images</h3>

      <?php if (!empty($msg)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>

      <div class="row">
        <?php foreach (['left', 'right'] as $pos): ?>
          <div class="col-md-6">
            <h4><?= ucfirst($pos) ?> Image</h4>
            <?php if (isset($heroImages[$pos])): ?>
              <img src="uploads/hero/<?= htmlspecialchars($heroImages[$pos]) ?>" class="img-fluid mb-2" style="max-height: 200px;">
            <?php else: ?>
              <p>No image uploaded.</p>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="mb-4">
              <div class="mb-2">
                <input type="file" name="hero_image" required>
                <input type="hidden" name="position" value="<?= $pos ?>">
              </div>
              <button type="submit" class="btn hero-upload-btn">Upload <?= ucfirst($pos) ?> Image</button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
</body>

</html>