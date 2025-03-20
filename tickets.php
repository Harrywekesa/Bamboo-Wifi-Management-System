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
    <title>Bamboo Tech - Tickets</title>
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container" id="mainContent">
        <div class="main-header">
            <h1>Support Tickets</h1>
            <a href="index.php" class="logout-button">Logout</a>
        </div>

        <table border="1" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Ticket ID</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $tickets = get_tickets($pdo);
                foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?= htmlspecialchars($ticket['username']) ?></td>
                        <td><?= htmlspecialchars($ticket['id']) ?></td>
                        <td><?= htmlspecialchars($ticket['subject']) ?></td>
                        <td><?= htmlspecialchars($ticket['status']) ?></td>
                        <td>
                            <button class="green" onclick="resolveTicket(<?= $ticket['id'] ?>)">Resolve</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        async function resolveTicket(id) {
            if (!confirm('Are you sure you want to resolve this ticket?')) return;

            try {
                const response = await fetch('process.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=resolve_ticket&id=${id}`
                });

                const result = await response.json();
                if (result.success) {
                    alert('Ticket resolved successfully!');
                    location.reload();
                } else {
                    alert('Failed to resolve ticket.');
                }
            } catch (error) {
                console.error('Error resolving ticket:', error);
                alert('An error occurred. Please try again.');
            }
        }
    </script>
</body>
</html>