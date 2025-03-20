<?php
session_start(); // Start the session at the top

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php?error=Unauthorized"); // Redirect to the main page
    exit;
}

require 'db.php'; // Include the database handler
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bamboo Tech - Dashboard</title>
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container" id="mainContent">
        <div class="main-header">
            <h1>Dashboard Overview</h1>
            <a href="index.php" class="logout-button">Logout</a>
        </div>

        <div class="metrics">
            <div class="metric">
                <h3>Active Users</h3>
                <p><?php echo count_active_users($pdo); ?></p>
            </div>
            <div class="metric">
                <h3>Total Revenue</h3>
                <p>Kes. <?php echo calculate_total_revenue($pdo); ?></p>
            </div>
            <div class="metric">
                <h3>Pending Transactions</h3>
                <p><?php echo count_pending_transactions($pdo); ?></p>
            </div>
        </div>
    </div>
</body>
</html>