<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
include 'config/db.php';

$message = '';

$sql = "SELECT * FROM navbar WHERE id = 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $navbar = $result->fetch_assoc();
    $logo = $navbar['logo'] ?? '';
    $navLinks = json_decode($navbar['nav_links'], true) ?? [];
    $headerText = $navbar['header_text'] ?? '';
} else {
    $logo = '';
    $navLinks = [];
    $headerText = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "SELECT logo FROM navbar WHERE id = 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $logo = $row['logo'] ?? '';
    }

    if (isset($_POST['delete_logo']) && $_POST['delete_logo'] == '1') {
        if ($logo && file_exists('uploads/navbar/' . $logo)) {
            unlink('uploads/navbar/' . $logo);
        }
        $logo = '';
    }

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $uploadDir = 'uploads/navbar/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $uniqueName = 'logo_' . time() . '.' . $ext;
        $logoPath = $uploadDir . $uniqueName;
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath)) {
            if ($logo && file_exists($uploadDir . $logo)) {
                unlink($uploadDir . $logo);
            }
            $logo = $uniqueName;
        } else {
            $message = "<div class='alert alert-danger'>Failed to upload logo image.</div>";
        }
    }

    $navLinks = [];
    if (!empty($_POST['nav_name']) && is_array($_POST['nav_name'])) {
        for ($i = 0; $i < count($_POST['nav_name']); $i++) {
            $name = trim($_POST['nav_name'][$i]);
            $url = trim($_POST['nav_url'][$i]);

            $submenus = [];
            if (isset($_POST['sub_name'][$i]) && is_array($_POST['sub_name'][$i])) {
                for ($j = 0; $j < count($_POST['sub_name'][$i]); $j++) {
                    $submenuName = trim($_POST['sub_name'][$i][$j]);
                    $submenuUrl = trim($_POST['sub_url'][$i][$j]);
                    if ($submenuName !== '' && $submenuUrl !== '') {
                        $submenus[] = [
                            'name' => $submenuName,
                            'url' => $submenuUrl
                        ];
                    }
                }
            }

            if ($name !== '' && $url !== '') {
                $navItem = ['name' => $name, 'url' => $url];
                if (!empty($submenus)) {
                    $navItem['submenus'] = $submenus;
                }
                $navLinks[] = $navItem;
            }
        }
    }

    $navLinksJson = json_encode($navLinks, JSON_PRETTY_PRINT);
    $headerText = isset($_POST['header_text']) ? trim($_POST['header_text']) : '';

    $sql = "INSERT INTO navbar (id, logo, nav_links, header_text) VALUES (1, ?, ?, ?)
            ON DUPLICATE KEY UPDATE logo = VALUES(logo), nav_links = VALUES(nav_links), header_text = VALUES(header_text)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $logo, $navLinksJson, $headerText);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Navbar updated successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Database update failed: " . htmlspecialchars($stmt->error) . "</div>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin - Manage Navbar</title>
    <link rel="stylesheet" href="assest/css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />

</head>

<body>

    <div class="flex ">
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

        <div class="content">
            <h4>Manage Navbar</h4>
            <?php if (!empty($message)) echo $message; ?>
            <form action="navbar.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="header_text" class="form-label">Header Text</label>
                    <textarea class="form-control" id="header_text" name="header_text" rows="3" placeholder="Enter header text here"><?= htmlspecialchars($headerText ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="logo" class="form-label">Upload Logo</label>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*" />
                    <?php if ($logo): ?>
                        <img src="uploads/navbar/<?= htmlspecialchars($logo) ?>" alt="Logo Preview" class="logo-preview" />
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="delete_logo" name="delete_logo" value="1">
                            <label class="form-check-label" for="delete_logo">Delete current logo</label>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Navbar Links</label>
                    <div id="nav-links-container">
                        <?php foreach ($navLinks as $i => $link): ?>
                            <div class="nav-link-row">
                                <div class="d-flex align-items-center mb-2">
                                    <input type="text" class="form-control me-2" name="nav_name[]" placeholder="Link Name" value="<?= htmlspecialchars($link['name']) ?>" required />
                                    <input type="url" class="form-control me-2" name="nav_url[]" placeholder="Link URL" value="<?= htmlspecialchars($link['url']) ?>" required />
                                    <button type="button" class="btn btn-danger btn-sm delete-link-btn">&times;</button>
                                </div>
                                <div class="submenu-wrapper">
                                    <?php if (!empty($link['submenus'])): ?>
                                        <?php foreach ($link['submenus'] as $sub): ?>
                                            <div class="d-flex submenu-item">
                                                <input type="text" class="form-control me-2" name="sub_name[<?= $i ?>][]" placeholder="Submenu Name" value="<?= htmlspecialchars($sub['name']) ?>">
                                                <input type="url" class="form-control me-2" name="sub_url[<?= $i ?>][]" placeholder="Submenu URL" value="<?= htmlspecialchars($sub['url']) ?>">
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-submenu">&times;</button>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary add-submenu mt-2" data-index="<?= $i ?>">+ Add Submenu</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn btn-secondary mt-3" id="add-link-btn">+ Add New Link</button>
                </div>

                <button type="submit" class="btn btn-outline-success hero-upload-btn">Save Changes</button>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="assest/js/navbar.js"></script>

</body>

</html>