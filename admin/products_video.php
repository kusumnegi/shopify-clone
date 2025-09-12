<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: index.php');
  exit;
}
include 'config/db.php';

// Fetch the existing video (only one)
$video = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products_video LIMIT 1"));

// Delete only video ID
if (isset($_GET['delete_video']) && $video) {
  $conn->query("UPDATE products_video SET youtube_id = '' WHERE id = {$video['id']}");
  header("Location: products_video.php");
  exit;
}

// Delete only thumbnail image
if (isset($_GET['delete_thumb']) && $video && !empty($video['thumbnail_image'])) {
  $thumb_path = "uploads/products_video/" . $video['thumbnail_image'];
  if (file_exists($thumb_path)) unlink($thumb_path);
  $conn->query("UPDATE products_video SET thumbnail_image = '' WHERE id = {$video['id']}");
  header("Location: products_video.php");
  exit;
}

// Function to extract YouTube video ID from full URL
function extractYouTubeID($url)
{
  preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([^\s&?]+)/', $url, $matches);
  return $matches[1] ?? '';
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $video_title = trim($_POST['video_title']);
  $youtube_id = trim($_POST['youtube_id']);

  if (strlen($youtube_id) > 20) {
    $youtube_id = extractYouTubeID($youtube_id);
  }

  $target_dir = "uploads/products_video/";
  $thumbnail_image = $video['thumbnail_image'] ?? '';

  if (!empty($_FILES["thumbnail_image"]["name"])) {
    if (!empty($thumbnail_image) && file_exists($target_dir . $thumbnail_image)) {
      unlink($target_dir . $thumbnail_image);
    }
    $thumbnail_image = time() . '_' . basename($_FILES["thumbnail_image"]["name"]);
    move_uploaded_file($_FILES["thumbnail_image"]["tmp_name"], $target_dir . $thumbnail_image);
  }

  if ($video) {
    $stmt = $conn->prepare("UPDATE products_video SET video_title = ?, youtube_id = ?, thumbnail_image = ? WHERE id = ?");
    $stmt->bind_param("sssi", $video_title, $youtube_id, $thumbnail_image, $video['id']);
  } else {
    $stmt = $conn->prepare("INSERT INTO products_video (video_title, youtube_id, thumbnail_image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $video_title, $youtube_id, $thumbnail_image);
  }

  $stmt->execute();
  header("Location: products_video.php");
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
  <link rel="stylesheet" type="text/css" href="assest/css/products_video.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">


  <style>

  </style>
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
      <h2>Manage Product Video</h2>
      <form method="POST" enctype="multipart/form-data">
        <!-- Video Title -->
        <div class="mb-3">
          <label class="form-label">🎬 Video Title</label>
          <input type="text" name="video_title" value="<?= htmlspecialchars($video['video_title'] ?? '') ?>" class="form-control" placeholder="Enter video title" required>
        </div>

        <!-- YouTube ID or URL -->
        <div class="mb-3">
          <label class="form-label">📺 YouTube Video ID or URL</label>
          <input type="text" name="youtube_id" value="<?= htmlspecialchars($video['youtube_id'] ?? '') ?>" class="form-control" placeholder="e.g., dQw4w9WgXcQ or full URL" required>
        </div>

        <!-- Thumbnail -->
        <div class="mb-3">
          <label class="form-label">🖼️ Thumbnail Image (optional)</label>
          <input type="file" name="thumbnail_image" accept="image/*" class="form-control">
        </div>

        <!-- Submit Button -->
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">
            <?= $video ? '💾 Update Video' : '➕ Add Video' ?>
          </button>
        </div>
      </form>


      <?php if ($video): ?>
        <div class="flex-display">
          <div class="video-box">
            <h4>Video Preview</h4>
            <?php if (!empty($video['youtube_id'])): ?>
              <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($video['youtube_id']) ?>" allowfullscreen></iframe>
              <a class="delete-btn" href="?delete_video=1" onclick="return confirm('Delete YouTube video ID?')">Delete Video</a>
            <?php else: ?>
              <p>No YouTube video set.</p>
            <?php endif; ?>
          </div>

          <div class="thumb-box">
            <h4>Thumbnail</h4>
            <?php
            $thumbPath = 'uploads/products_video/' . $video['thumbnail_image'];
            if (!empty($video['thumbnail_image']) && file_exists($thumbPath)):
            ?>
              <img src="<?= $thumbPath ?>" alt="Thumbnail">
              <a class="delete-btn" href="?delete_thumb=1" onclick="return confirm('Delete thumbnail image?')">Delete Thumbnail</a>
            <?php else: ?>
              <p>No thumbnail uploaded.</p>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>














</body>

</html>