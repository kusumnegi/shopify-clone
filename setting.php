<?php
session_start(); // ✅ Always at top

// Redirect to login if user is not set
if (!isset($_SESSION['user']) && !isset($_SESSION['user_id'])) {
  $currentPage = basename($_SERVER['REQUEST_URI']);
  header("Location: user/?redirect=$currentPage");
  exit;
}

// ✅ Safely get user ID
$user_id = 0;
if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
  $user_id = $_SESSION['user']['id'] ?? 0;
} elseif (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
}

// Include DB
include 'admin/config/db.php';

// Fetch user data
$user = [];
if ($user_id > 0) {
  $query = "SELECT * FROM user WHERE id = ? LIMIT 1";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc() ?? [];
  $stmt->close();
}

// Logout handling
if (isset($_GET['logout'])) {
  session_destroy();
  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Settings & Privacy</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="assest/css/setting.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>

  <?php include 'loginheader.php'; ?>

  <div class="container-fluid pt-5">
    <div class="container pt-lg-5 mt-5">

      <div class="settings-list mb-4">
        <div class="settings-header">Account</div>
        <a href="edit_profile.php" class="settings-item">
          <i class="bi bi-person"></i> Personal and Account Information
        </a>
        <a href="my-orders.php" class="settings-item">
          <i class="fa-solid fa-truck-fast"></i> My Orders
        </a>
      </div>

      <div class="settings-list">
        <a href="setting.php?logout=true" class="settings-item text-danger">
          <i class="bi bi-box-arrow-right"></i> Logout
        </a>
      </div>

    </div>
  </div>

  <?php include 'footer.php'; ?>

</body>

</html>