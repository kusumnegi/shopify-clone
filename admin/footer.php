<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: index.php');
  exit;
}
include 'config/db.php';

// Handle Add Template
if (isset($_POST['add_template'])) {
  $type = $_POST['template_type'];
  $text = $_POST['template_text'];
  $imageName = '';

  $existing = $conn->query("SELECT * FROM footer WHERE type='$type' LIMIT 1")->fetch_assoc();
  if ($existing) {
    if (!empty($existing['image']) && file_exists("uploads/footer/templates/" . $existing['image'])) {
      unlink("uploads/footer/templates/" . $existing['image']);
    }
    $conn->query("DELETE FROM footer WHERE id = " . (int)$existing['id']);
  }

  if (!empty($_FILES['template_image']['name'])) {
    $imageName = basename($_FILES['template_image']['name']);
    move_uploaded_file($_FILES['template_image']['tmp_name'], "uploads/footer/templates/" . $imageName);
  }

  $stmt = $conn->prepare("INSERT INTO footer (type, text, image) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $type, $text, $imageName);
  $stmt->execute();
  $stmt->close();
}

// Handle Add Multiple Links
if (isset($_POST['add_multiple_links'])) {
  $type = $_POST['link_type'];
  $texts = $_POST['link_text'];
  $urls = $_POST['link_url'];

  foreach ($texts as $index => $text) {
    $link = $urls[$index];
    $stmt = $conn->prepare("INSERT INTO footer (type, text, link) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $type, $text, $link);
    $stmt->execute();
    $stmt->close();
  }
}

// Handle Add Mission
if (isset($_POST['add_mission'])) {
  $text = $_POST['mission_text'];
  $conn->query("DELETE FROM footer WHERE type='mission'");
  $stmt = $conn->prepare("INSERT INTO footer (type, text) VALUES ('mission', ?)");
  $stmt->bind_param("s", $text);
  $stmt->execute();
  $stmt->close();
}

// Handle Add Social
if (isset($_POST['add_social'])) {
  $icon_class = $_POST['social_icon'];
  $url = $_POST['social_url'];

  $stmt = $conn->prepare("INSERT INTO footer (type, text, link) VALUES ('social', ?, ?)");
  $stmt->bind_param("ss", $icon_class, $url);
  $stmt->execute();
  $stmt->close();
}

// Handle Delete
if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  $conn->query("DELETE FROM footer WHERE id = $id");
  header("Location: footer.php");
  exit;
}

$footerData = $conn->query("SELECT * FROM footer ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Footer Management</title>
  <link rel="stylesheet" type="text/css" href="assest/css/footer.css">
  <link rel="stylesheet" type="text/css" href="assest/css/dashboard.css">
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


    <div class="content ">
      <h2 class="mb-4">Footer Management</h2>

      <!-- Template Form -->
      <form method="POST" enctype="multipart/form-data" class="mb-5 row g-3">
        <input type="hidden" name="add_template" value="1">
        <h4>Add Template</h4>
        <div class="col-md-3">
          <select name="template_type" class="form-select" required>
            <option value="template1">Template 1</option>
            <option value="template2">Template 2</option>
          </select>
        </div>
        <div class="col-md-3">
          <input type="file" name="template_image" class="form-control" required>
        </div>
        <div class="col-md-4">
          <input type="text" name="template_text" class="form-control" placeholder="Template Text" required>
        </div>
        <div class="col-md-2">
          <button class="btn btn-primary w-100">Add Template</button>
        </div>
      </form>

      <!-- Mission Form -->
      <form method="POST" class="mb-5">
        <input type="hidden" name="add_mission" value="1">
        <h4>Update Mission</h4>
        <textarea name="mission_text" class="form-control mb-2" rows="3" placeholder="Mission Text" required></textarea>
        <button class="btn btn-warning">Update Mission</button>
      </form>

      <!-- Multiple Links Form -->
      <form method="POST" class="mb-5 row g-3" id="multiple-links-form">
        <input type="hidden" name="add_multiple_links" value="1">
        <h4>Add Multiple Links</h4>
        <div class="col-md-4">
          <select name="link_type" class="form-select" required>
            <option value="quick_link">Quick Link</option>
            <option value="info_link">Info Link</option>
          </select>
        </div>
        <div id="link-fields" class="col-12">
          <div class="row mb-2">
            <div class="col-md-5">
              <input type="text" name="link_text[]" class="form-control" placeholder="Link Text" required>
            </div>
            <div class="col-md-5">
              <input type="text" name="link_url[]" class="form-control" placeholder="Link URL" required>
            </div>
            <div class="col-md-2">
              <button type="button" class="btn btn-danger remove-link">Remove</button>
            </div>
          </div>
        </div>
        <div class="col-12 mb-2">
          <button type="button" class="btn btn-secondary" id="add-more-link">+ Add More</button>
        </div>
        <button class="btn btn-success">Add Links</button>
      </form>

      <!-- Social Form -->
      <form method="POST" class="mb-5 row g-3">
        <input type="hidden" name="add_social" value="1">
        <h4>Add Social Icon</h4>
        <div class="col-md-6">
          <input type="text" name="social_icon" class="form-control" placeholder="FontAwesome Class" required>
        </div>
        <div class="col-md-6">
          <input type="text" name="social_url" class="form-control" placeholder="Social URL" required>
        </div>
        <div class="col-12">
          <button class="btn btn-dark">Add Social Icon</button>
        </div>
      </form>

      <!-- Table of Entries -->
      <h4 class="mb-3">All Footer Entries</h4>
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Type</th>
              <th>Text</th>
              <th>Image</th>
              <th>Link</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $serial = 1; ?>
            <?php while ($row = $footerData->fetch_assoc()): ?>
              <tr>
                <td><?= $serial++ ?></td>
                <td><?= $row['type'] ?></td>
                <td class="truncate" title="<?= htmlspecialchars($row['text']) ?>"><?= htmlspecialchars(mb_strimwidth($row['text'], 0, 50, '...')) ?></td>
                <td>
                  <?php if ($row['image']): ?>
                    <img src="uploads/footer/templates/<?= $row['image'] ?>" alt="img">
                  <?php endif; ?>
                </td>
                <td class="truncate" title="<?= htmlspecialchars($row['link']) ?>"><?= htmlspecialchars(mb_strimwidth($row['link'], 0, 40, '...')) ?></td>
                <td>
                  <a href="footer_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                  <a href="footer.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <script src="js/footer.js"></script>
</body>

</html>