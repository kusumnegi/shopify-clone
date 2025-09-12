<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
include 'config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request.");
}

$id = (int)$_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id=$id");

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $price = isset($_POST['price']) && $_POST['price'] !== '' ? (float)$_POST['price'] : null;
    $sale_price = (float)$_POST['sale_price'];
    $status = in_array($_POST['status'], ['in_stock', 'out_of_stock']) ? $_POST['status'] : 'in_stock';
    $type = $_POST['type'] ?? null;
    $sizes = $_POST['sizes'] ?? '';
    $description = trim($_POST['description'] ?? '');

    $targetDir = "uploads/products/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $imageName = $product['image'];
    $hoverImageName = $product['hover_image'];

    if (!empty($_FILES['image']['name'])) {
        $imageName = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $imageName);
    }

    if (!empty($_FILES['hover_image']['name'])) {
        $hoverImageName = $_FILES['hover_image']['name'];
        move_uploaded_file($_FILES['hover_image']['tmp_name'], $targetDir . $hoverImageName);
    }

    $stmt = $conn->prepare("UPDATE products SET title=?, price=?, sale_price=?, image=?, hover_image=?, status=?, type=?, sizes=?, description=? WHERE id=?");
    $stmt->bind_param("sddssssssi", $title, $price, $sale_price, $imageName, $hoverImageName, $status, $type, $sizes, $description, $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Product updated successfully!'); window.location='products.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="assest/css/dashboard.css">
    <link rel="stylesheet" href="assest/css/edit_products.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="flex">
        <div class="sidebar">
            <h4>My Dashboard</h4>
            <a href="dashboard.php">🏠 Home</a>
            <a href="navbar.php">📁 Navbar</a>
            <a href="hero.php">🦸 Hero</a>
            <a href="products.php">📦 Products</a>
            <a href="backstock.php">📈 Back in Stocks</a>
            <a href="products_video.php">📷 Product Video</a>
            <a href="footer.php">📄 Footer</a>
            <a href="orders.php">🚚 All Orders</a>
            <a href="" data-bs-toggle="modal" data-bs-target="#logout" class="text-danger">🚪 Logout</a>
        </div>

        <div class="content">
            <h2>Edit Product</h2>
            <form method="post" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Price (CAD)</label>
                    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sale Price (CAD)</label>
                    <input type="number" step="0.01" name="sale_price" value="<?= htmlspecialchars($product['sale_price']) ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Main Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>" class="preview">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hover Image</label>
                    <input type="file" name="hover_image" class="form-control" accept="image/*">
                    <img src="uploads/products/<?= htmlspecialchars($product['hover_image']) ?>" class="preview">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Stock Status</label>
                    <select name="status" class="form-select" required>
                        <option value="in_stock" <?= $product['status'] === 'in_stock' ? 'selected' : '' ?>>In Stock</option>
                        <option value="out_of_stock" <?= $product['status'] === 'out_of_stock' ? 'selected' : '' ?>>Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Product Type (optional)</label>
                    <select name="type" class="form-select" id="product-type" onchange="showSizes()">
                        <option value="">-- Select Type --</option>
                        <option value="shoes" <?= $product['type'] === 'shoes' ? 'selected' : '' ?>>Shoes</option>
                        <option value="cloth" <?= $product['type'] === 'cloth' ? 'selected' : '' ?>>Cloth</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Available Sizes</label>
                    <div id="size-options" class="d-flex flex-wrap"></div>
                    <input type="hidden" name="sizes" id="selected-sizes" value="<?= htmlspecialchars($product['sizes']) ?>">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Product Description</label>
                    <textarea name="description" id="description" class="form-control"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success">Update Product</button>
                    <a href="products.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logout" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Logout Confirmation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" onclick="window.location.href='logout.php'">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <script src="assest/js/edit_products.js"></script>
    <script src="https://cdn.tiny.cloud/1/ytk6kttln99z51d0dpgz2sqxnpo5hmgzgybsetzt09ok9uvy/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#description',
            height: 300,
            menubar: false,
            plugins: ['advlist autolink lists link image charmap preview anchor', 'searchreplace visualblocks code fullscreen', 'insertdatetime media table code help wordcount'],
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code preview',
            branding: false
        });
    </script>
</body>

</html>
<?php $conn->close(); ?>