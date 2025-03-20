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
    <title>Bamboo Tech - Campaigns</title>
    <link rel="stylesheet" href="admin-styles.css">
    <script src="admin-script.js" defer></script>
</head>
<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container" id="mainContent">
        <div class="main-header">
            <h1>Campaign Management</h1>
            <a href="index.php" class="logout-button">Logout</a>
        </div>

        <!-- Create Campaign Form -->
        <form id="createCampaignForm" class="admin-form">
            <h2>Create New Campaign</h2>
            <input type="text" id="campaignName" placeholder="Campaign Name" required>
            <textarea id="campaignDescription" placeholder="Description" required></textarea>
            <button type="submit" class="green">Create Campaign</button>
        </form>

        <!-- Campaign List -->
        <h2>Active Campaigns</h2>
        <table border="1" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $campaigns = get_campaigns($pdo);
                foreach ($campaigns as $campaign): ?>
                    <tr>
                        <td><?= htmlspecialchars($campaign['date']) ?></td>
                        <td><?= htmlspecialchars($campaign['name']) ?></td>
                        <td><?= htmlspecialchars($campaign['description'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($campaign['status'] ?? 'inactive') ?></td>
                        <td>
                            <button class="blue" onclick="editCampaign(<?= $campaign['id'] ?>)">Edit</button>
                            <button class="red" onclick="deleteCampaign(<?= $campaign['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>