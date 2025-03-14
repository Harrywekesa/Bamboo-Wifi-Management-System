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
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="logo">Bamboo Tech</div>
            <div class="username-section">
                <span class="username">Logged in as: <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></span>
                <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
            </div>
        </div>

        <!-- Navigation Groups -->
        <div class="group">
            <h3>Dashboard</h3>
            <button onclick="alert('Dashboard')">Overview</button>
        </div>
        <div class="group">
            <h3>Users</h3>
            <button onclick="alert('Active Users')">Active Users (0)</button>
            <button onclick="alert('All Users')">Users (0)</button>
            <button onclick="alert('Tickets')">Tickets (0)</button>
            <button onclick="alert('Leads')">Leads (0)</button>
        </div>
        <div class="group">
            <h3>Finance</h3>
            <button onclick="window.location.href='#packages'">Packages (0)</button>
            <button onclick="window.location.href='#transactions'">Payments (0)</button>
            <button onclick="alert('Expenses')">Expenses (0)</button>
        </div>
        <div class="group">
            <h3>Communication</h3>
            <button onclick="alert('Campaigns')">Campaigns (0)</button>
            <button onclick="alert('SMS')">SMS (0)</button>
            <button onclick="alert('Emails')">Emails (0)</button>
        </div>
        <div class="group">
            <h3>Devices</h3>
            <button onclick="alert('Mikrotiks')">Mikrotiks (0)</button>
            <button onclick="alert('Equipments')">Equipments (0)</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container" id="mainContent">
        <!-- Main Header -->
        <div class="main-header">
            <h1>Bamboo Tech Admin Dashboard</h1>
            <a href="index.php" class="logout-button">Logout</a>
        </div>

        <!-- Add Package Form -->
        <form id="addPackageForm">
            <h2 id="packages">Add New Package</h2>
            <input type="text" id="packageName" placeholder="Package Name" required>
            <input type="number" id="packagePrice" placeholder="Price (Kes)" required>
            <input type="text" id="packageDuration" placeholder="Duration (e.g., 30 minutes)" required>
            <button type="submit" class="green">Add Package</button>
        </form>

        <!-- Package List -->
        <h2 id="packages">Current Packages</h2>
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
        <h2 id="transactions">Transaction Logs</h2>
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

    <script>
        // Toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');

            if (sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('collapsed');
                mainContent.style.marginLeft = '270px'; // Restore default margin
            } else {
                sidebar.classList.add('collapsed');
                mainContent.style.marginLeft = '60px'; // Reduce margin for collapsed sidebar
            }
        }
    </script>
</body>
</html>