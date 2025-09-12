<?php
session_start(); // ✅ Always at top
include '../admin/config/db.php';

$msg = "";
$action = $_POST['action'] ?? 'login';

// ✅ Default redirect if not provided
$redirect = $_GET['redirect'] ?? '../index.php';

// ✅ Prevent open redirect (only allow internal pages)
$allowedPaths = ['index.php', 'cart.php', 'product-details.php', 'setting.php', 'my-orders.php'];
if (!in_array(basename($redirect), $allowedPaths)) {
  $redirect = '../index.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($action === 'signup') {
    $name = trim($_POST['name'] ?? '');
    if (!$name || !$email || !$password) {
      $msg = "<div class='alert alert-danger'>All fields are required.</div>";
    } else {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // ✅ Check if email already exists
      $check = $conn->prepare("SELECT id FROM user WHERE email = ?");
      $check->bind_param("s", $email);
      $check->execute();
      $checkResult = $check->get_result();

      if ($checkResult->num_rows > 0) {
        $msg = "<div class='alert alert-danger'>Email already registered.</div>";
      } else {
        // ✅ Insert new user
        $stmt = $conn->prepare("INSERT INTO user (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);
        if ($stmt->execute()) {
          $msg = "<div class='alert alert-success'>Signup successful. Please log in.</div>";
          $action = 'login';
        } else {
          $msg = "<div class='alert alert-danger'>Signup failed. Try again.</div>";
        }
        $stmt->close();
      }
      $check->close();
    }
  } elseif ($action === 'login') {
    if (!$email || !$password) {
      $msg = "<div class='alert alert-danger'>Email and Password are required.</div>";
    } else {
      $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
          // ✅ Store full user data in session
          $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
          ];

          // ✅ Redirect back
          header("Location: $redirect");
          exit;
        } else {
          $msg = "<div class='alert alert-danger'>Invalid password.</div>";
        }
      } else {
        $msg = "<div class='alert alert-danger'>No user found with this email.</div>";
      }
      $stmt->close();
    }
  }
}
?>

<?php include 'nav.php'; ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $action === 'signup' ? 'Sign Up' : 'Login' ?> - Shopify</title>
  <!-- css files -->
  <link rel="stylesheet" type="text/css" href="assest/css/query.css">
  <link rel="stylesheet" type="text/css" href="assest/css/style.css">
  <link rel="stylesheet" type="text/css" href="../css/nav.css">
  <link rel="stylesheet" type="text/css" href="../css/footer.css">
  <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/7915/7915382.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
  <div id="preloader">
    <div class="loader-cart">
      <div class="cart-icon"></div>
      <p>Loading your shopping experience...</p>
    </div>
  </div>

  <div class="container pt-5 px-lg-5">
    <div class="form-box mt-5 pt-5">
      <h4 class="text-center mt-3 mb-lg-5 mb-3"><?= $action === 'signup' ? 'Sign Up' : 'Login' ?></h4>
      <?= $msg ?>

      <form method="POST" id="authForm">
        <input type="hidden" name="action" id="formAction" value="<?= $action ?>">

        <!-- Signup fields -->
        <div id="signupFields" style="<?= $action === 'signup' ? '' : 'display:none;' ?>">
          <div class="mb-3">
            <input type="text" name="name" class="login-inputs" placeholder="Full Name" value="<?= $_POST['name'] ?? '' ?>">
          </div>
        </div>

        <!-- Login fields -->
        <div class="mb-3">
          <input type="email" placeholder="Email" name="email" class="login-inputs" required value="<?= $_POST['email'] ?? '' ?>">
        </div>

        <div class="mb-3">
          <input type="password" placeholder="Password" name="password" class="login-inputs" required>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary" id="formBtn"><?= $action === 'signup' ? 'Sign Up' : 'Login' ?></button>
        </div>

        <div class="toggle-link login-signup mt-4 text-center">
          <?php if ($action === 'signup'): ?>
            Already have an account? <a href="#" onclick="switchToLogin()">Login</a>
          <?php else: ?>
            Don’t have an account? <a href="#" onclick="switchToSignup()">Sign up</a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script src="assest/js/script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>