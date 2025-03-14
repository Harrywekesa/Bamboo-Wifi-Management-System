<?php
// Database configuration
$host = 'localhost';
$dbname = 'bwms'; // Replace with your database name
$username = 'root'; // Replace with your MySQL username
$password = ''; // Replace with your MySQL password

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}

// ✅ Helper function to fetch all packages
function get_packages($pdo) {
    $stmt = $pdo->query("SELECT * FROM packages");
    return $stmt->fetchAll();
}

// ✅ Helper function to add a package
function add_package($pdo, $name, $price, $duration) {
    try {
        $stmt = $pdo->prepare("INSERT INTO packages (name, price, duration) VALUES (?, ?, ?)");
        $stmt->execute([$name, $price, $duration]);
    } catch (PDOException $e) {
        die("❌ Error adding package: " . $e->getMessage());
    }
}

// ✅ Helper function to delete a package
function delete_package($pdo, $name) {
    try {
        $stmt = $pdo->prepare("DELETE FROM packages WHERE name = ?");
        $stmt->execute([$name]);
    } catch (PDOException $e) {
        die("❌ Error deleting package: " . $e->getMessage());
    }
}

// ✅ Helper function to log a transaction
function log_transaction($pdo, $phone_number, $package_name, $amount, $mpesa_code, $user_name) {
    try {
        $stmt = $pdo->prepare("INSERT INTO transactions (phone_number, package_name, amount, mpesa_code, user_name, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$phone_number, $package_name, $amount, $mpesa_code, $user_name]);
    } catch (PDOException $e) {
        die("❌ Error logging transaction: " . $e->getMessage());
    }
}

// ✅ Helper function to fetch all transactions
function get_transactions($pdo) {
    $stmt = $pdo->query("SELECT * FROM transactions ORDER BY date DESC");
    return $stmt->fetchAll();
}

// ✅ Secure admin authentication (Updated for MySQL 8+)
function authenticate_admin($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        return $admin;
    }
    return false;
}
?>
