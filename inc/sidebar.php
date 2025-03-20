<div class="sidebar" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <div class="logo">Bamboo Tech</div>
        <div class="username-section">
        <script src="../admin-script.js" defer></script>
            <span class="username">Logged in as: <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?></span>
            <button class="menu-toggle" onclick="toggleSidebar()">&#9776;</button>
        </div>
    </div>

    <!-- Navigation Groups -->
    <div class="group">
        <h3>Dashboard</h3>
        <button onclick="window.location.href='dashboard.php'"><i class="fas fa-chart-line"></i> Overview</button>
    </div>
    <div class="group">
        <h3>Users</h3>
        <button onclick="window.location.href='active-users.php'"><i class="fas fa-user-check"></i> Active Users</button>
        <button onclick="window.location.href='users.php'"><i class="fas fa-users"></i> All Users</button>
        <button onclick="window.location.href='tickets.php'"><i class="fas fa-ticket-alt"></i> Tickets</button>
        <button onclick="window.location.href='leads.php'"><i class="fas fa-list-ul"></i> Leads</button>
    </div>
    <div class="group">
        <h3>Finance</h3>
        <button onclick="window.location.href='packages.php'"><i class="fas fa-box"></i> Packages</button>
        <button onclick="window.location.href='payments.php'"><i class="fas fa-hand-holding-usd"></i> Payments</button>
        <button onclick="window.location.href='expenses.php'"><i class="fas fa-receipt"></i> Expenses</button>
    </div>
    <div class="group">
        <h3>Communication</h3>
        <button onclick="window.location.href='campaigns.php'"><i class="fas fa-bullhorn"></i> Campaigns</button>
        <button onclick="window.location.href='sms.php'"><i class="fas fa-comment-dots"></i> SMS</button>
        <button onclick="window.location.href='emails.php'"><i class="fas fa-envelope"></i> Emails</button>
    </div>
    <div class="group">
        <h3>Devices</h3>
        <button onclick="window.location.href='mikrotiks.php'"><i class="fas fa-server"></i> Mikrotiks</button>
        <button onclick="window.location.href='equipments.php'"><i class="fas fa-tools"></i> Equipments</button>
    </div>
</div>