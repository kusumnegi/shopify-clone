<?php
$host = "localhost";
$user = "root"; // use your MySQL username
$pass = "";     // your MySQL password
$db = "myshopify";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
