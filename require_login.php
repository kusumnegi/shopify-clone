<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fix session if user is stored as a JSON string
if (isset($_SESSION['user']) && is_string($_SESSION['user'])) {
    $decoded = json_decode($_SESSION['user'], true);
    if (json_last_error() === JSON_ERROR_NONE && isset($decoded['id'])) {
        $_SESSION['user'] = $decoded;
    } else {
        // Session corrupted, clear it
        unset($_SESSION['user']);
    }
}

// Now safely check if user is valid
if (!isset($_SESSION['user']['id'])) {
    // Redirect to login with original page
    $currentUrl = $_SERVER['REQUEST_URI'];
    header("Location: /shopify/user/index.php?redirect=" . urlencode($currentUrl));
    exit;
}
