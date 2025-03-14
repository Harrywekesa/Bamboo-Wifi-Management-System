<?php
session_start(); // Start the session at the top

require 'db.php';

$action = $_POST['action'] ?? '';

if ($action === 'login_admin') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        exit;
    }

    $admin = authenticate_admin($pdo, $username, $password);
    if ($admin) {
        $_SESSION['admin_logged_in'] = true; // Set session variable
        $_SESSION['admin_username'] = $admin['username']; // Store admin username
        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect username or password']);
    }
} elseif ($action === 'get_packages') {
    $packages = get_packages($pdo);
    echo json_encode($packages);
} elseif ($action === 'add_package') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $duration = $_POST['duration'] ?? '';

    if (!$name || !$price || !$duration) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }

    add_package($pdo, $name, $price, $duration);
    echo json_encode(['success' => true, 'message' => 'Package added successfully']);
} elseif ($action === 'delete_package') {
    $name = $_POST['name'] ?? '';

    if (!$name) {
        echo json_encode(['success' => false, 'message' => 'Invalid package name']);
        exit;
    }

    delete_package($pdo, $name);
    echo json_encode(['success' => true, 'message' => 'Package deleted successfully']);
} elseif ($action === 'log_transaction') {
    $phone_number = $_POST['phone_number'] ?? '';
    $package_name = $_POST['package_name'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $mpesa_code = $_POST['mpesa_code'] ?? '';
    $user_name = $_POST['user_name'] ?? '';

    if (!$phone_number || !$package_name || !$amount || !$mpesa_code || !$user_name) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }

    log_transaction($pdo, $phone_number, $package_name, $amount, $mpesa_code, $user_name);
    echo json_encode(['success' => true, 'message' => 'Transaction logged successfully']);
} elseif ($action === 'get_transactions') {
    $transactions = get_transactions($pdo);
    echo json_encode($transactions);
}elseif ($action === 'get_transactions') {
    $transactions = get_transactions($pdo);
    echo json_encode($transactions);
}


?>