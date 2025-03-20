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
    <title>Bamboo Tech - SMS</title>
    <link rel="stylesheet" href="admin-styles.css">
    <script src="admin-script.js" defer></script>
</head>
<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container" id="mainContent">
        <div class="main-header">
            <h1>SMS Management</h1>
            <a href="index.php" class="logout-button">Logout</a>
        </div>

        <!-- Send SMS Form -->
        <form id="sendSMSForm" class="admin-form">
            <h2>Send Bulk SMS</h2>
            <textarea id="smsMessage" placeholder="Enter message here" required></textarea>
            <button type="submit" class="green">Send SMS</button>
        </form>

        <!-- Sent SMS Log -->
        <h2>Sent SMS Log</h2>
        <table border="1" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Message</th>
                    <th>Recipients</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sent_sms = get_sent_sms($pdo);
                foreach ($sent_sms as $sms): ?>
                    <tr>
                        <td><?= htmlspecialchars($sms['date']) ?></td>
                        <td><?= htmlspecialchars($sms['message'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($sms['recipients_count'] . ' users') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>