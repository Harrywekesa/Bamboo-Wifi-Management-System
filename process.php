<?php
require 'db.php';

$action = $_POST['action'] ?? '';

if ($action === 'log_transaction') {
    $phone_number = $_POST['phone_number'] ?? '';
    $package_name = $_POST['package_name'] ?? '';
    $amount = $_POST['amount'] ?? '';

    if (!$phone_number || !$package_name || !$amount) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit;
    }

    $mpesa_code = $_POST['mpesa_code'] ?? '';
    $user_name = $_POST['user_name'] ?? '';

    log_transaction($pdo, $phone_number, $package_name, $amount, $mpesa_code, $user_name);
    echo json_encode(['success' => true, 'message' => 'Transaction logged']);
} elseif ($action === 'login_admin') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        exit;
    }

    $admin = authenticate_admin($pdo, $username, $password);
    if ($admin) {
        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect username or password']);
    }
}
?>