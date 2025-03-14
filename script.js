// Load packages from PHP (via process.php)
async function loadPackages() {
    try {
        const response = await fetch('process.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=get_packages'
        });

        if (!response.ok) throw new Error('Failed to load packages');
        const packages = await response.json();
        renderPackages(packages);
    } catch (error) {
        console.error('Error loading packages:', error);
        alert('Failed to load packages. Check console for details.');
    }
}

// Render packages dynamically
function renderPackages(packages) {
    const packageGrid = document.querySelector('.package-grid');
    packageGrid.innerHTML = ''; // Clear existing packages

    packages.forEach(pkg => {
        const packageCard = `
            <div class="package">
                <h3>${pkg.name}</h3>
                <p>Kes. ${pkg.price} valid for ${pkg.duration}</p>
                <button class="green" onclick="showPaymentModal('${pkg.name.toLowerCase().replace(' ', '')}', ${pkg.price})">Buy Now</button>
            </div>
        `;
        packageGrid.insertAdjacentHTML('beforeend', packageCard);
    });
}

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
async function processPayment() {
    const mpesaNumber = document.getElementById('mpesaNumber').value.trim();
    const modalTitle = document.getElementById('modalTitle').textContent;

    if (!mpesaNumber || isNaN(parseFloat(mpesaNumber)) || mpesaNumber.length !== 10) {
        //alert('Please enter a valid 10-digit M-Pesa phone number.');
        return;
    }

    const packageType = modalTitle.split(' ')[1];
    const amount = parseFloat(document.getElementById('amountDisplay').textContent.replace('Amount: Kes. ', ''));

    // Initiate STK Push
    fetch('process_payment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `phone_number=${mpesaNumber}&package_name=${encodeURIComponent(packageType)}&amount=${amount}`
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              alert('Payment request sent successfully! Please confirm on your phone.');
              checkTransactionStatus(data.checkout_request_id); // Poll for transaction status
          } else {
              alert('Failed to send payment request.');
          }
      }).catch(error => {
          console.error('Error during payment:', error);
          alert('An error occurred. Please try again.');
      });

    closePaymentModal();
}

// Check transaction status periodically
function checkTransactionStatus(checkoutRequestId) {
    const intervalId = setInterval(async () => {
        try {
            const response = await fetch('verify_transaction.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `checkout_request_id=${checkoutRequestId}`
            });

            const result = await response.json();
            if (result.success) {
                clearInterval(intervalId);
                alert('Transaction verified successfully!');
            } else if (result.message.includes('Failed')) {
                clearInterval(intervalId);
                alert('Transaction failed. Please try again.');
            }
        } catch (error) {
            console.error('Error verifying transaction:', error);
        }
    }, 5000); // Poll every 5 seconds
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

    try {
        const response = await fetch('process.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=login_admin&username=${username}&password=${password}`
        });

        if (!response.ok) {
            alert('Server error during login. Please check console for details.');
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();
        if (result.success) {
            alert('Login successful!');
            window.location.href = 'admin.php'; // Redirect to admin dashboard
        } else {
            alert(result.message || 'Incorrect username or password.');
        }
    } catch (error) {
        console.error('Error during login:', error);
        alert('An error occurred. Please try again.');
    }
}

// Load packages from PHP
async function loadPackages() {
    try {
        const response = await fetch('process.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=get_packages'
        });

        if (!response.ok) {
            alert('Failed to load packages. Please check console for details.');
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const packages = await response.json();
        renderPackages(packages);
    } catch (error) {
        console.error('Error loading packages:', error);
        alert('Failed to load packages. Check console for details.');
    }
}

// Load packages when the page loads
window.onload = loadPackages;