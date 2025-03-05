<?php
session_start(); // Start the session at the top

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php?error=Unauthorized"); // Redirect to the main page
    exit;
}

require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bamboo Tech Admin</title>
    <link rel="stylesheet" href="styles.css">
    <script src="admin-script.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bamboo Tech Admin Dashboard</h1>
            <a href="index.php" class="green">Logout</a>
        </div>

        <!-- Add Package Form -->
        <form id="addPackageForm">
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
                    <button class="red" onclick="deletePackage('<?= $pkg['name'] ?>')">Delete</button>
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
                </tr>
            </thead>
            <tbody>
                <?php
                $transactions = get_transactions($pdo);
                foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?= $transaction['date'] ?></td>
                        <td><?= htmlspecialchars($transaction['phone_number']) ?></td>
                        <td><?= htmlspecialchars($transaction['package_name']) ?></td>
                        <td>Kes. <?= $transaction['amount'] ?></td>
                        <td><?= htmlspecialchars($transaction['mpesa_code'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($transaction['user_name'] ?? 'N/A') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>