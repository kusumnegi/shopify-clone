<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
include 'config/db.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}
$id = (int) $_GET['id'];
$entry = $conn->query("SELECT * FROM footer WHERE id = $id")->fetch_assoc();
if (!$entry) {
    die("Entry not found.");
}

// Handle Update
if (isset($_POST['update_footer'])) {
    $type = $_POST['type'];
    $text = $_POST['text'];
    $link = $_POST['link'];
    $imageName = $_POST['existing_image'];

    if (!empty($_FILES['image']['name'])) {
        if (!empty($imageName) && file_exists("uploads/footer/templates/" . $imageName)) {
            unlink("uploads/footer/templates/" . $imageName);
        }
        $imageName = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/footer/templates/" . $imageName);
    }

    $stmt = $conn->prepare("UPDATE footer SET type=?, text=?, link=?, image=? WHERE id=?");
    $stmt->bind_param("ssssi", $type, $text, $link, $imageName, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: footer.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Footer Entry</title>
    <link rel="stylesheet" type="text/css" href="assest/css/dashboard.css">
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
        }

        .card {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        img.preview {
            max-height: 60px;
            margin-top: 10px;
            border: 1px solid #ccc;
            padding: 2px;
            border-radius: 4px;
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
            <div class="card p-4">
                <h3 class="card-title">Edit Footer Entry (ID #<?= $entry['id'] ?>)</h3>
                <form method="POST" enctype="multipart/form-data" class="row g-4">
                    <input type="hidden" name="update_footer" value="1">
                    <input type="hidden" name="existing_image" value="<?= $entry['image'] ?>">

                    <div class="col-md-4">
                        <label class="form-label">Type</label>
                        <input type="text" name="type" class="form-control" value="<?= $entry['type'] ?>" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Link</label>
                        <input type="text" name="link" class="form-control" value="<?= htmlspecialchars($entry['link']) ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Text</label>
                        <textarea name="text" class="form-control" rows="4" required><?= htmlspecialchars($entry['text']) ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Replace Image (optional)</label>
                        <input type="file" name="image" class="form-control">
                        <?php if (!empty($entry['image'])): ?>
                            <img src="uploads/footer/templates/<?= $entry['image'] ?>" class="preview">
                        <?php endif; ?>
                    </div>
                    <div class="col-12 text-end">
                        <a href="footer.php" class="btn btn-secondary me-2">Back</a>
                        <button class="btn btn-primary">Update Entry</button>
                    </div>
                </form>
            </div>
        </div>
</body>

</html>