<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: user/");
  exit;
}
include 'admin/config/db.php';

$user_id = $_SESSION['user']['id'];
$query = "SELECT * FROM user WHERE id = $user_id LIMIT 1";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name    = mysqli_real_escape_string($conn, $_POST['name']);
  $email   = mysqli_real_escape_string($conn, $_POST['email']);
  $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $city    = mysqli_real_escape_string($conn, $_POST['city']);
  $state   = mysqli_real_escape_string($conn, $_POST['state']);
  $country = mysqli_real_escape_string($conn, $_POST['country']);

  $updateQuery = "UPDATE user SET 
        name='$name', email='$email', phone='$phone', 
        address='$address', city='$city', state='$state', 
        country='$country' 
        WHERE id=$user_id";

  if (mysqli_query($conn, $updateQuery)) {
    $_SESSION['user']['name'] = $name;
    $success = "Profile updated successfully!";
    $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id = $user_id"));
  } else {
    $error = "Update failed. Try again!";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <link rel="stylesheet" type="text/css" href="assest/css/edit_profile.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  <div class="container py-5">
    <div class="card card-custom p-4">
      <div class="form-header">Edit Profile Information</div>

      <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
      <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($user['address']) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($user['city']) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">State</label>
            <input type="text" name="state" class="form-control" value="<?= htmlspecialchars($user['state']) ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($user['country']) ?>">
          </div>
        </div>

        <div class="mt-4 d-flex justify-content-between">
          <a href="setting.php" class="btn btn-outline-secondary">← Back to Settings</a>
          <button type="submit" class="btn btn-primary px-4">Update Profile</button>
        </div>
      </form>
    </div>
  </div>

</body>

</html>