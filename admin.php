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
    <?php include("inc/head.php");?>
    <link rel="stylesheet" href="styles.css">
    <script src="admin-script.js" defer></script>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #FFFFFF;
            color: #333333;
            min-height: 100vh;
            margin: 0;
            transition: padding-left 0.3s ease; /* Smooth transition for sidebar collapse */
        }

        /* Main Content Container */
        .container {
            margin-left: 270px; /* Default margin for sidebar */
            padding: 20px;
            transition: margin-left 0.3s ease; /* Smooth transition for sidebar collapse */
        }

        /* Sidebar Styling */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px; /* Default width */
            background-color: #3E844C;
            color: white;
            padding-top: 60px; /* Adjust for header */
            overflow-y: auto;
            transition: width 0.3s ease; /* Smooth transition for collapse */
        }

        /* Collapsed Sidebar */
        .sidebar.collapsed {
            width: 60px; /* Reduced width when collapsed */
        }

        /* Sidebar Header */
        .sidebar-header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
            background-color: #28A745;
        }

        /* Admin Logo */
        .sidebar-header .logo {
            font-size: 20px;
            font-weight: bold;
        }

        /* Admin Username */
        .sidebar-header .username {
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        /* Menu Toggle Button */
        .menu-toggle {
            cursor: pointer;
            font-size: 24px;
            color: white;
        }

        /* Groups and Buttons */
        .group {
            margin-bottom: 15px;
        }
        .group h3 {
            font-size: 16px;
            margin-bottom: 10px;
            padding-left: 10px;
        }
        .sidebar button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #28A745;
            border: none;
            color: white;
            font-size: 14px;
            text-align: left;
            padding-left: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .sidebar button:hover {
            background-color: #218838;
        }

        /* Collapsed Sidebar Buttons */
        .sidebar.collapsed button {
            padding-left: 10px;
            text-align: center;
            font-size: 12px;
        }

        /* Main Header */
        .main-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background-color: #3E844C;
            color: white;
        }

        /* Logout Button */
        .logout-button {
            background-color: #DC3545;
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 14px;
            cursor: pointer;
        }
        .logout-button:hover {
            background-color: #B02A37;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 60px; /* Always collapsed on smaller screens */
            }
            .container {
                margin-left: 60px; /* Adjust for collapsed sidebar */
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="logo">Bamboo Technologies</div>
            <div class="username">
                Logged in as: <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>
            </div>
            <div class="menu-toggle" onclick="toggleSidebar()">&#9776;</div> <!-- Hamburger menu icon -->
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