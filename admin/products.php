<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
include 'config/db.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $price = isset($_POST['price']) && $_POST['price'] !== '' ? (float)$_POST['price'] : null;
    $sale_price = (float)$_POST['sale_price'];
    $status = isset($_POST['status']) && in_array($_POST['status'], ['in_stock', 'out_of_stock'])
        ? trim(strtolower($_POST['status']))
        : 'in_stock';
    $type = $_POST['type'] ?? null;
    $sizes = $_POST['sizes'] ?? '';
    $description = trim($_POST['description'] ?? '');

    $targetDir = "uploads/products/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $imageName = $_FILES['image']['name'];
    $hoverImageName = $_FILES['hover_image']['name'];

    move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $imageName);
    move_uploaded_file($_FILES['hover_image']['tmp_name'], $targetDir . $hoverImageName);

    $stmt = $conn->prepare("INSERT INTO products (title, price, sale_price, image, hover_image, status, type, sizes, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sddssssss", $title, $price, $sale_price, $imageName, $hoverImageName, $status, $type, $sizes, $description);
    $stmt->execute();

    $product_id = $conn->insert_id;
    $stmt->close();

    if (!empty($_FILES['gallery_images']['name'][0])) {
        foreach ($_FILES['gallery_images']['name'] as $key => $galleryImageName) {
            if (!empty($galleryImageName)) {
                $tmpName = $_FILES['gallery_images']['tmp_name'][$key];
                $uniqueName = time() . '_' . basename($galleryImageName);
                move_uploaded_file($tmpName, $targetDir . $uniqueName);

                $stmtImg = $conn->prepare("INSERT INTO product_images (product_id, image) VALUES (?, ?)");
                $stmtImg->bind_param("is", $product_id, $uniqueName);
                $stmtImg->execute();
                $stmtImg->close();
            }
        }
    }

    echo "<script>alert('Product added successfully!');window.location='products.php';</script>";
    exit;
}

// Delete product
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM products WHERE id = $id");
    $conn->query("DELETE FROM product_images WHERE product_id = $id");
    echo "<script>window.location='products.php';</script>";
    exit;
}

$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assest/css/dashboard.css">
</head>

<body>
    <div class="flex">
        <div class="sidebar p-3">
            <h4 class="mb-4">My Dashboard</h4>
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
            <h4 class="mb-4">Add New Product</h4>
            <form method="post" enctype="multipart/form-data" class="row g-3 mb-5" id="product-form">
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Price (CAD)</label>
                    <input type="number" step="0.01" name="price" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sale Price (CAD)</label>
                    <input type="number" step="0.01" name="sale_price" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Main Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hover Image</label>
                    <input type="file" name="hover_image" class="form-control" accept="image/*" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Gallery Images (Optional)</label>
                    <div id="gallery-fields">
                        <input type="file" name="gallery_images[]" class="form-control" accept="image/*">
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary mt-2" id="add-gallery-btn">Add More</button>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Stock Status</label>
                    <select name="status" class="form-select" required>
                        <option value="" disabled selected>-- Select Status --</option>
                        <option value="in_stock">In Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Product Type (optional)</label>
                    <select name="type" class="form-select" id="product-type">
                        <option value="">-- Select Type --</option>
                        <option value="shoes">Shoes</option>
                        <option value="cloth">Cloth</option>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Available Sizes</label>
                    <div id="size-options" class="d-flex flex-wrap"></div>
                    <input type="hidden" name="sizes" id="selected-sizes">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Product Description (optional)</label>
                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </div>
            </form>

            <h5>All Products</h5>
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Images</th>
                        <th>Title</th>
                        <th>Stock</th>
                        <th>Type</th>
                        <th>Sizes</th>
                        <th>Price</th>
                        <th>Sale Price</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
                    while ($row = $result->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td>
                                <img src="uploads/products/<?= htmlspecialchars($row['image']) ?>" class="preview">
                                <img src="uploads/products/<?= htmlspecialchars($row['hover_image']) ?>" class="preview">
                                <?php
                                $gallery = $conn->query("SELECT image FROM product_images WHERE product_id = " . $row['id']);
                                while ($img = $gallery->fetch_assoc()):
                                ?>
                                    <img src="uploads/products/<?= htmlspecialchars($img['image']) ?>" class="preview mt-1">
                                <?php endwhile; ?>
                            </td>
                            <td><?= htmlspecialchars(mb_strimwidth($row['title'], 0, 30, '...')) ?></td>
                            <td>
                                <?= strtolower($row['status']) === 'out_of_stock'
                                    ? '<span class="out-of-stock">Out of Stock</span>'
                                    : '<span class="text-success fw-bold">In Stock</span>' ?>
                            </td>
                            <td><?= ucfirst($row['type']) ?: '—' ?></td>
                            <td><?= htmlspecialchars($row['sizes']) ?: '—' ?></td>
                            <td><?= $row['price'] !== null ? '$' . number_format($row['price'], 2) : '—' ?></td>
                            <td><?= number_format($row['sale_price'], 2) ?> CAD</td>
                            <td><?= $row['description'] ? htmlspecialchars(mb_strimwidth(strip_tags($row['description']), 0, 50, '...')) : '—' ?></td>
                            <td>
                                <a href="edit_products.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="products.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="assest/js/products.js"></script>
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