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
    <title>Bamboo Tech - Mikrotiks</title>
    <link rel="stylesheet" href="admin-styles.css">
    <script src="admin-script.js" defer></script>
</head>
<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container" id="mainContent">
        <div class="main-header">
            <h1>Mikrotik Devices</h1>
            <a href="index.php" class="logout-button">Logout</a>
        </div>

        <!-- Add Mikrotik Form -->
        <form id="addMikrotikForm" class="admin-form">
            <h2>Add New Mikrotik</h2>
            <input type="text" id="mikrotikName" placeholder="Device Name" required>
            <input type="text" id="mikrotikIP" placeholder="IP Address" required>
            <button type="submit" class="green">Add Device</button>
        </form>

        <!-- Mikrotik List -->
        <h2>Connected Mikrotik Devices</h2>
        <table border="1" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>IP Address</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $mikrotiks = get_mikrotiks($pdo);
                foreach ($mikrotiks as $device): ?>
                    <tr>
                        <td><?= htmlspecialchars($device['name']) ?></td>
                        <td><?= htmlspecialchars($device['ip_address']) ?></td>
                        <td><?= htmlspecialchars($device['status'] ?? 'offline') ?></td>
                        <td>
                            <button class="blue" onclick="editMikrotik(<?= $device['id'] ?>)">Edit</button>
                            <button class="red" onclick="deleteMikrotik(<?= $device['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>