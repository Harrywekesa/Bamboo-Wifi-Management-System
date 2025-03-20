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
    <title>Bamboo Tech - Leads</title>
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container" id="mainContent">
        <div class="main-header">
            <h1>Leads Management</h1>
            <a href="index.php" class="logout-button">Logout</a>
        </div>

        <!-- Leads Table -->
        <h2>Potential Leads</h2>
        <table border="1" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $leads = get_leads($pdo);
                foreach ($leads as $lead): ?>
                    <tr>
                        <td><?= htmlspecialchars($lead['date']) ?></td>
                        <td><?= htmlspecialchars($lead['name']) ?></td>
                        <td><?= htmlspecialchars($lead['email']) ?></td>
                        <td><?= htmlspecialchars($lead['message'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($lead['status'] ?? 'pending') ?></td>
                        <td>
                            <button class="green" onclick="approveLead(<?= $lead['id'] ?>)">Approve</button>
                            <button class="red" onclick="rejectLead(<?= $lead['id'] ?>)">Reject</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>