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
    <title>Bamboo Tech - Expenses</title>
    <link rel="stylesheet" href="admin-styles.css">
    <script src="admin-script.js" defer></script>
</head>
<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="container" id="mainContent">
        <div class="main-header">
            <h1>Expenses Management</h1>
            <a href="index.php" class="logout-button">Logout</a>
        </div>

        <!-- Add Expense Form -->
        <form id="addExpenseForm" class="admin-form">
            <h2>Add New Expense</h2>
            <input type="text" id="expenseName" placeholder="Expense Name" required>
            <input type="number" id="expenseAmount" placeholder="Amount (Kes)" required>
            <textarea id="expenseDescription" placeholder="Description" required></textarea>
            <button type="submit" class="green">Add Expense</button>
        </form>

        <!-- Expense List -->
        <h2>Logged Expenses</h2>
        <table border="1" style="width: 100%; margin-top: 20px;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $expenses = get_expenses($pdo);
                foreach ($expenses as $expense): ?>
                    <tr>
                        <td><?= htmlspecialchars($expense['date']) ?></td>
                        <td><?= htmlspecialchars($expense['name']) ?></td>
                        <td>Kes. <?= htmlspecialchars($expense['amount']) ?></td>
                        <td><?= htmlspecialchars($expense['description'] ?? 'N/A') ?></td>
                        <td>
                            <button class="blue" onclick="editExpense(<?= $expense['id'] ?>)">Edit</button>
                            <button class="red" onclick="deleteExpense(<?= $expense['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>