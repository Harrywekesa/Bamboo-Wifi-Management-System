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
    <title>Bamboo Tech - Active Users</title>
    <link rel="stylesheet" href="admin-styles.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container" id="mainContent">
        <div class="main-header">
            <h1>Active Users</h1>
            <a href="index.php" class="logout-button">Logout</a>
        </div>

        <table border="1" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>MAC Address</th>
                    <th>Package</th>
                    <th>Time Remaining</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $active_users = get_active_users($pdo);
                foreach ($active_users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['mac_address']) ?></td>
                        <td><?= htmlspecialchars($user['package_name']) ?></td>
                        <td><?= htmlspecialchars($user['time_remaining']) ?> minutes</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>