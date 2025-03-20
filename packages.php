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
    <title>Bamboo Tech - Packages</title>
    <link rel="stylesheet" href="admin-styles.css">
    <script src="admin-script.js" defer></script>
</head>
<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container" id="mainContent">
        <div class="main-header">
            <h1>Packages Management</h1>
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
    </div>
</body>
</html>