<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/db.php';

$message = '';
$message_type = 'alert-warning'; // default alert type
$show_signup_tab = false; // default: don't switch tab

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
  $password = isset($_POST["password"]) ? trim($_POST["password"]) : '';
  $is_login = isset($_POST['login']);
  $is_signup = isset($_POST['signup']);

  if ($is_login) {
    // LOGIN PROCESS
    if ($email && $password) {
      $stmt = $conn->prepare("SELECT * FROM members WHERE email = ?");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
          $_SESSION['user'] = $user['email'];
          echo "<script>window.location.href='dashboard.php';</script>";
          exit;
        } else {
          $message = "Invalid password.";
        }
      } else {
        $message = "User not found.";
      }
    } else {
      $message = "Please enter email and password.";
    }
  } elseif ($is_signup) {
    // SIGNUP PROCESS
    if (strlen($password) < 4) {
      $message = "Password must be at least 4 characters.";
      $show_signup_tab = true;
    } elseif (!$email) {
      $message = "Email is required.";
      $show_signup_tab = true;
    } else {
      // Check if email already exists
      $check = $conn->prepare("SELECT id FROM members WHERE email = ?");
      $check->bind_param("s", $email);
      $check->execute();
      $check->store_result();

      if ($check->num_rows > 0) {
        $message = "Email already registered. Please enter another email.";
        $message_type = 'alert-danger'; // red background
        $show_signup_tab = true; // show signup tab
      } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO members (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashed);

        if ($stmt->execute()) {
          $message = "Account created successfully. Please login.";
          $message_type = 'alert-warning';
          $show_signup_tab = false;
        } else {
          $message = "Something went wrong. Please try again.";
          $message_type = 'alert-warning';
          $show_signup_tab = true;
        }
      }
      $check->close();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Login & Signup - Amazon Clone</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #f8f9fa;
    }

    .auth-box {
      max-width: 400px;
      margin: 50px auto;
      padding: 30px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body>
  <div class="auth-box">
    <h4 class="text-center mb-4">Welcome to Amazon Clone</h4>

    <?php if ($message): ?>
      <div class="alert <?= $message_type ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <ul class="nav nav-tabs mb-3" id="authTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link <?= !$show_signup_tab ? 'active' : '' ?>" id="login-tab" data-bs-toggle="tab" data-bs-target="#login">Login</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link <?= $show_signup_tab ? 'active' : '' ?>" id="signup-tab" data-bs-toggle="tab" data-bs-target="#signup">Sign Up</button>
      </li>
    </ul>

    <div class="tab-content">
      <!-- Login Form -->
      <div class="tab-pane fade <?= !$show_signup_tab ? 'show active' : '' ?>" id="login">
        <form method="POST" action="">
          <input type="hidden" name="login" value="1" />
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required />
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required />
          </div>
          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
      </div>

      <!-- Signup Form -->
      <div class="tab-pane fade <?= $show_signup_tab ? 'show active' : '' ?>" id="signup">
        <form method="POST" action="">
          <input type="hidden" name="signup" value="1" />
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required />
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required />
          </div>
          <button type="submit" class="btn btn-success w-100">Sign Up</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Ensure correct tab is active on page load
    <?php if ($show_signup_tab): ?>
      let signupTab = new bootstrap.Tab(document.querySelector('#signup-tab'));
      signupTab.show();
    <?php endif; ?>
  </script>
</body>

</html>