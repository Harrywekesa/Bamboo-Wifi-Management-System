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
            <?php
            require 'db.php';
            $packages = get_packages($pdo);
            foreach ($packages as $pkg): ?>
                <div class="package">
                    <h3><?= htmlspecialchars($pkg['name']) ?></h3>
                    <p>Kes. <?= $pkg['price'] ?> valid for <?= htmlspecialchars($pkg['duration']) ?></p>
                    <button class="green" onclick="showPaymentModal('<?= strtolower(str_replace(' ', '', $pkg['name'])) ?>', <?= $pkg['price'] ?>)">Buy Now</button>
                </div>
            <?php endforeach; ?>
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

    <script>
        // Show payment modal
        function showPaymentModal(packageType, amount) {
            const modalOverlay = document.getElementById('paymentModalOverlay');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const amountDisplay = document.getElementById('amountDisplay');

            modalTitle.textContent = `Pay for ${packageType} Package`;
            modalMessage.textContent = `You are about to pay Kes. ${amount}. Please enter your M-Pesa phone number to proceed.`;
            amountDisplay.textContent = `Amount: Kes. ${amount}`;

            modalOverlay.classList.add('active');
        }

        // Process the payment
        function processPayment() {
            const mpesaNumber = document.getElementById('mpesaNumber').value.trim();
            const modalTitle = document.getElementById('modalTitle').textContent;

            if (!mpesaNumber || isNaN(parseFloat(mpesaNumber)) || mpesaNumber.length !== 12) {
                alert('Please enter a valid 12-digit M-Pesa phone number.');
                return;
            }

            const packageType = modalTitle.split(' ')[1];
            const amount = parseFloat(document.getElementById('amountDisplay').textContent.replace('Amount: Kes. ', ''));

            // Simulate payment processing
            alert(`Payment request sent to ${mpesaNumber}. Please wait while we process your payment.`);

            // Log transaction via AJAX
            fetch('process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=log_transaction&phone_number=${mpesaNumber}&package_name=${encodeURIComponent(packageType)}&amount=${amount}`
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      alert('Transaction recorded successfully!');
                  } else {
                      alert('Failed to record transaction.');
                  }
              }).catch(error => {
                  console.error('Error recording transaction:', error);
                  alert('An error occurred. Please try again.');
              });

            closePaymentModal();
        }

        // Close the payment modal
        function closePaymentModal() {
            const modalOverlay = document.getElementById('paymentModalOverlay');
            modalOverlay.classList.remove('active');
            document.getElementById('mpesaNumber').value = ''; // Clear input field
        }

        // Connect with account number
        function connectWithAccount() {
            const accountNumber = document.getElementById('accountNumber').value.trim();
            if (!accountNumber) {
                alert('Please enter your account number.');
                return;
            }

            alert(`Connecting with account number: ${accountNumber}`);
            // Add logic to authenticate the user here
        }

        // Show admin login modal
        function showAdminLogin() {
            const adminLoginModal = document.getElementById('adminLoginModal');
            adminLoginModal.classList.add('active');
        }

        // Handle admin login
        async function adminLogin(event) {
            event.preventDefault();
            const username = document.getElementById('adminUsername').value.trim();
            const password = document.getElementById('adminPassword').value.trim();

            if (!username || !password) {
                alert('Please enter both username and password.');
                return;
            }

            const response = await fetch('process.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=login_admin&username=${username}&password=${password}`
            });

            const result = await response.json();
            if (result.success) {
                alert('Login successful!');
                window.location.href = 'admin.php';
            } else {
                alert('Incorrect username or password.');
            }
        }
    </script>
</body>
</html>