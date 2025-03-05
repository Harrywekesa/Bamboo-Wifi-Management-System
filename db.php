<?php
// Database configuration
$host = 'localhost';
$dbname = 'bwms'; // Replace with your database name
$username = 'root';     // Replace with your MySQL username
$password = '';         // Replace with your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Helper function to fetch all packages
function get_packages($pdo) {
    $stmt = $pdo->query("SELECT * FROM packages");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Helper function to add a package
function add_package($pdo, $name, $price, $duration) {
    $stmt = $pdo->prepare("INSERT INTO packages (name, price, duration) VALUES (?, ?, ?)");
    $stmt->execute([$name, $price, $duration]);
}

// Helper function to delete a package
function delete_package($pdo, $name) {
    $stmt = $pdo->prepare("DELETE FROM packages WHERE name = ?");
    $stmt->execute([$name]);
}

// Helper function to log a transaction
function log_transaction($pdo, $phone_number, $package_name, $amount, $mpesa_code, $user_name) {
    $stmt = $pdo->prepare("INSERT INTO transactions (phone_number, package_name, amount, mpesa_code, user_name) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$phone_number, $package_name, $amount, $mpesa_code, $user_name]);
}

// Helper function to fetch all transactions
function get_transactions($pdo) {
    $stmt = $pdo->query("SELECT * FROM transactions ORDER BY date DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Helper function to authenticate admin
function authenticate_admin($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ? AND password = PASSWORD(?)");
    $stmt->execute([$username, $password]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>