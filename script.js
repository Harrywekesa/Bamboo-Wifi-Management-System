// File paths
const PACKAGES_FILE = 'packages.json';

// Load packages from file
async function loadPackages() {
    try {
        const response = await fetch(PACKAGES_FILE);
        if (!response.ok) throw new Error('Failed to load packages');
        const packages = await response.json();
        renderPackages(packages);
    } catch (error) {
        console.error('Error loading packages:', error);
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

// Show admin login modal
function showAdminLogin() {
    const adminLoginModal = document.getElementById('adminLoginModal');
    adminLoginModal.classList.add('active');
}

// Handle admin login
function adminLogin() {
    const enteredPassword = document.getElementById('adminPassword').value;
    const correctPassword = 'admin123'; // Hardcoded admin password
    if (enteredPassword === correctPassword) {
        alert('Login successful!');
        window.location.href = 'admin.html'; // Redirect to admin dashboard
    } else {
        alert('Incorrect password!');
    }
}

// Process the payment (placeholder logic)
function processPayment() {
    const mpesaNumber = document.getElementById('mpesaNumber').value;
    if (mpesaNumber.trim() === '') {
        alert('Please enter a valid M-Pesa phone number.');
        return;
    }

    // Simulate payment processing
    alert(`Payment request sent to ${mpesaNumber}. Please wait while we process your payment.`);
    closePaymentModal();
}

// Close the payment modal
function closePaymentModal() {
    const modalOverlay = document.getElementById('paymentModalOverlay');
    modalOverlay.classList.remove('active');
}

// Connect with account number
function connectWithAccount() {
    const accountNumber = document.getElementById('accountNumber').value;
    if (accountNumber) {
        alert(`Connecting with account number: ${accountNumber}`);
        // Add logic to authenticate the user here
    } else {
        alert('Please enter your account number.');
    }
}

// Load packages when the page loads
window.onload = loadPackages;