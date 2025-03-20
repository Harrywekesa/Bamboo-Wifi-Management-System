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
    <title>Bamboo Tech - Emails</title>
    <link rel="stylesheet" href="admin-styles.css">
    <script src="admin-script.js" defer></script>
</head>
<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container" id="mainContent">
        <div class="main-header">
            <h1>Email Management</h1>
            <a href="index.php" class="logout-button">Logout</a>
        </div>

        <!-- Send Email Form -->
        <form id="sendEmailForm" class="admin-form">
            <h2>Send Bulk Email</h2>
            <input type="email" id="emailSubject" placeholder="Subject" required>
            <textarea id="emailMessage" placeholder="Enter message here" required></textarea>
            <button type="submit" class="green">Send Email</button>
        </form>

        <!-- Sent Email Log -->
        <h2>Sent Email Log</h2>
        <table border="1" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Subject</th>
                    <th>Recipients</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sent_emails = get_sent_emails($pdo);
                foreach ($sent_emails as $email): ?>
                    <tr>
                        <td><?= htmlspecialchars($email['date']) ?></td>
                        <td><?= htmlspecialchars($email['subject']) ?></td>
                        <td><?= htmlspecialchars($email['recipients_count'] . ' users') ?></td>
                        <td>
                            <button class="blue" onclick="editEmail(<?= $email['id'] ?>)">Edit</button>
                            <button class="red" onclick="deleteEmail(<?= $email['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>