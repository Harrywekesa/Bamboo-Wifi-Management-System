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

function count_active_users($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM users WHERE status = 'active'");
    return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}

function calculate_total_revenue($pdo) {
    $stmt = $pdo->query("SELECT SUM(amount) AS total FROM transactions WHERE status = 'approved'");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
}

function count_pending_transactions($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM transactions WHERE status = 'pending'");
    return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
}

function approve_transaction($pdo, $id) {
    $stmt = $pdo->prepare("UPDATE transactions SET status = 'approved' WHERE id = ?");
    $stmt->execute([$id]);
}

function reject_transaction($pdo, $id) {
    $stmt = $pdo->prepare("UPDATE transactions SET status = 'rejected' WHERE id = ?");
    $stmt->execute([$id]);
}

function get_active_users($pdo) {
    $stmt = $pdo->query("SELECT * FROM users WHERE status = 'active'");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_users($pdo) {
    $stmt = $pdo->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_tickets($pdo) {
    $stmt = $pdo->query("SELECT * FROM tickets ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function resolve_ticket($pdo, $id) {
    $stmt = $pdo->prepare("UPDATE tickets SET status = 'resolved' WHERE id = ?");
    $stmt->execute([$id]);
}

function edit_package($pdo, $id, $name, $price, $duration) {
    $stmt = $pdo->prepare("UPDATE packages SET name = ?, price = ?, duration = ? WHERE id = ?");
    $stmt->execute([$name, $price, $duration, $id]);
}

function get_expenses($pdo) {
    $stmt = $pdo->query("SELECT * FROM expenses ORDER BY date DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function add_expense($pdo, $name, $amount, $description) {
    $stmt = $pdo->prepare("INSERT INTO expenses (name, amount, description) VALUES (?, ?, ?)");
    $stmt->execute([$name, $amount, $description]);
}

function delete_expense($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM expenses WHERE id = ?");
    $stmt->execute([$id]);
}

function edit_expense($pdo, $id, $name, $amount, $description) {
    $stmt = $pdo->prepare("UPDATE expenses SET name = ?, amount = ?, description = ? WHERE id = ?");
    $stmt->execute([$name, $amount, $description, $id]);
}

function get_campaigns($pdo) {
    $stmt = $pdo->query("SELECT * FROM campaigns ORDER BY date DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function add_campaign($pdo, $name, $description) {
    $stmt = $pdo->prepare("INSERT INTO campaigns (name, description) VALUES (?, ?)");
    $stmt->execute([$name, $description]);
}

function delete_campaign($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM campaigns WHERE id = ?");
    $stmt->execute([$id]);
}

function edit_campaign($pdo, $id, $name, $description) {
    $stmt = $pdo->prepare("UPDATE campaigns SET name = ?, description = ? WHERE id = ?");
    $stmt->execute([$name, $description, $id]);
}

function get_sent_sms($pdo) {
    $stmt = $pdo->query("SELECT * FROM sent_sms ORDER BY date DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function send_bulk_sms($pdo, $message, $recipients) {
    $stmt = $pdo->prepare("INSERT INTO sent_sms (message, recipients_count) VALUES (?, ?)");
    $stmt->execute([$message, count($recipients)]);
}

function get_sent_emails($pdo) {
    $stmt = $pdo->query("SELECT * FROM sent_emails ORDER BY date DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function send_bulk_email($pdo, $subject, $message, $recipients) {
    $stmt = $pdo->prepare("INSERT INTO sent_emails (subject, message, recipients_count) VALUES (?, ?, ?)");
    $stmt->execute([$subject, $message, count($recipients)]);
}

function edit_email($pdo, $id, $subject, $message) {
    $stmt = $pdo->prepare("UPDATE sent_emails SET subject = ?, message = ? WHERE id = ?");
    $stmt->execute([$subject, $message, $id]);
}

function delete_email($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM sent_emails WHERE id = ?");
    $stmt->execute([$id]);
}

function get_leads($pdo) {
    $stmt = $pdo->query("SELECT * FROM leads ORDER BY date DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function approve_lead($pdo, $id) {
    $stmt = $pdo->prepare("UPDATE leads SET status = 'approved' WHERE id = ?");
    $stmt->execute([$id]);
}

function reject_lead($pdo, $id) {
    $stmt = $pdo->prepare("UPDATE leads SET status = 'rejected' WHERE id = ?");
    $stmt->execute([$id]);
}

function get_mikrotiks($pdo) {
    $stmt = $pdo->query("SELECT * FROM mikrotiks ORDER BY name ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function add_mikrotik($pdo, $name, $ip_address) {
    $stmt = $pdo->prepare("INSERT INTO mikrotiks (name, ip_address) VALUES (?, ?)");
    $stmt->execute([$name, $ip_address]);
}

function edit_mikrotik($pdo, $id, $name, $ip_address) {
    $stmt = $pdo->prepare("UPDATE mikrotiks SET name = ?, ip_address = ? WHERE id = ?");
    $stmt->execute([$name, $ip_address, $id]);
}

function delete_mikrotik($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM mikrotiks WHERE id = ?");
    $stmt->execute([$id]);
}

function get_equipments($pdo) {
    $stmt = $pdo->query("SELECT * FROM equipments ORDER BY name ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function add_equipment($pdo, $name, $details) {
    $stmt = $pdo->prepare("INSERT INTO equipments (name, details) VALUES (?, ?)");
    $stmt->execute([$name, $details]);
}

function edit_equipment($pdo, $id, $name, $details) {
    $stmt = $pdo->prepare("UPDATE equipments SET name = ?, details = ? WHERE id = ?");
    $stmt->execute([$name, $details, $id]);
}

function delete_equipment($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM equipments WHERE id = ?");
    $stmt->execute([$id]);
}
?>
