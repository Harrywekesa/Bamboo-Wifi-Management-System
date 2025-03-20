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
    <title>Bamboo Tech - Payments</title>
    <link rel="stylesheet" href="admin-styles.css">
    <script src="admin-script.js" defer></script>
</head>
<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container" id="mainContent">
        <div class="main-header">
            <h1>Payments Management</h1>
            <a href="index.php" class="logout-button">Logout</a>
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
        </table>
    </div>
</body>
</html>