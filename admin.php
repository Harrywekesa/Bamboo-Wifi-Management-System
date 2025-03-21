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
    <title>Bamboo Tech Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="admin-styles.css"> <!-- Separate CSS for admin dashboard -->
    <script src="admin-script.js"></script>
    <script src="admin-script.js" defer></script>
</head>
<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container" id="mainContent">
        <!-- Main Header -->
        <div class="main-header">
            <h1>Bamboo Tech Admin Dashboard</h1>
            <a href="index.php" class="logout-button">Logout</a>
        </div>

        <!-- Add Package Form -->
        <form id="addPackageForm" class="admin-form">
            <h2>Add New Package</h2>
            <input type="text" id="packageName" placeholder="Package Name" required>
            <input type="number" id="packagePrice" placeholder="Price (Kes)" required>
            <input type="text" id="packageDuration" placeholder="Duration (e.g., 30 minutes)" required>
            <button type="submit" class="green">Add Package</button>
        </form>

        <!-- Package List -->
        <h2>Current Packages</h2>
        <div id="packageList" class="package-grid">
            <?php
            $packages = get_packages($pdo);
            foreach ($packages as $pkg): ?>
                <div class="package">
                    <h3><?= htmlspecialchars($pkg['name']) ?></h3>
                    <p>Kes. <?= $pkg['price'] ?> valid for <?= htmlspecialchars($pkg['duration']) ?></p>
                    <button class="red" onclick="deletePackage(<?= $pkg['id'] ?>)">Delete</button>
                    <button class="blue" onclick="editPackage(<?= $pkg['id'] ?>)">Edit</button>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Transactions Table -->
        <h2>Transaction Logs</h2>
        <table border="1" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Phone Number</th>
                    <th>Package</th>
                    <th>Amount</th>
                    <th>M-Pesa Code</th>
                    <th>User Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $transactions = get_transactions($pdo);
                foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?= htmlspecialchars($transaction['date']) ?></td>
                        <td><?= htmlspecialchars($transaction['phone_number']) ?></td>
                        <td><?= htmlspecialchars($transaction['package_name']) ?></td>
                        <td>Kes. <?= htmlspecialchars($transaction['amount']) ?></td>
                        <td><?= htmlspecialchars($transaction['mpesa_code'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($transaction['user_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($transaction['status'] ?? 'pending') ?></td>
                        <td>
                            <?php if ($transaction['status'] === 'pending'): ?>
                                <button class="green" onclick="approveTransaction(<?= $transaction['id'] ?>)">Approve</button>
                                <button class="red" onclick="rejectTransaction(<?= $transaction['id'] ?>)">Reject</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tbody id="transactionTableBody"> <!-- Ensure this ID exists -->
        </table>
    </div>
</body>
</html>