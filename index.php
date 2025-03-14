<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bamboo Tech</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Bamboo Tech</h1>
            <p>Connect to the internet by purchasing a package below.</p>
            <button class="green" onclick="showAdminLogin()">Admin</button>
        </div>

        <!-- Package Grid -->
        <div class="package-grid">
            <!-- Packages will be dynamically loaded via JavaScript -->
        </div>

        <!-- Already Have a Package? -->
        <div class="account-section">
            <h3>Already Have an Active Package?</h3>
            <p>Enter your account number to connect:</p>
            <input type="text" id="accountNumber" placeholder="Account Number">
            <button class="green" onclick="connectWithAccount()">Connect</button>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>For inquiries, contact us at <a href="tel:0715406550">0715406550</a>.</p>
        </div>
    </div>

    <!-- Admin Login Modal -->
    <div class="admin-login" id="adminLoginModal">
        <h2>Admin Login</h2>
        <form id="adminLoginForm">
            <input type="text" id="adminUsername" placeholder="Username" required>
            <input type="password" id="adminPassword" placeholder="Password" required>
            <button class="green" onclick="adminLogin(event)">Login</button>
        </form>
    </div>

    <!-- Payment Modal -->
    <div class="modal-overlay" id="paymentModalOverlay">
        <div class="modal">
            <h2 id="modalTitle">Pay for Your Package</h2>
            <p id="modalMessage">Please enter your M-Pesa phone number to proceed.</p>
            <input type="text" id="mpesaNumber" placeholder="M-Pesa Phone Number" required>
            <p id="amountDisplay"></p>
            <button class="green" onclick="processPayment()">Pay Now</button>
            <button class="red" onclick="closePaymentModal()">Cancel</button>
        </div>
    </div>
</body>
</html>