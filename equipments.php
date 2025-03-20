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
    <title>Bamboo Tech - Equipments</title>
    <link rel="stylesheet" href="admin-styles.css">
    <script src="admin-script.js" defer></script>
</head>
<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container" id="mainContent">
        <div class="main-header">
            <h1>Hotspot Equipment</h1>
            <a href="index.php" class="logout-button">Logout</a>
        </div>

        <!-- Add Equipment Form -->
        <form id="addEquipmentForm" class="admin-form">
            <h2>Add New Equipment</h2>
            <input type="text" id="equipmentName" placeholder="Equipment Name" required>
            <input type="text" id="equipmentDetails" placeholder="Details" required>
            <button type="submit" class="green">Add Equipment</button>
        </form>

        <!-- Equipment List -->
        <h2>Registered Equipment</h2>
        <table border="1" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $equipments = get_equipments($pdo);
                foreach ($equipments as $equipment): ?>
                    <tr>
                        <td><?= htmlspecialchars($equipment['name']) ?></td>
                        <td><?= htmlspecialchars($equipment['details'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($equipment['status'] ?? 'inactive') ?></td>
                        <td>
                            <button class="blue" onclick="editEquipment(<?= $equipment['id'] ?>)">Edit</button>
                            <button class="red" onclick="deleteEquipment(<?= $equipment['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>