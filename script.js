// File paths
const PACKAGES_FILE = 'packages.json'; // Adjust path as needed

// Load packages from file
async function loadPackages() {
    try {
        const response = await fetch(PACKAGES_FILE);
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

// Process the payment (placeholder logic)
function processPayment() {
    const mpesaNumber = document.getElementById('mpesaNumber').value.trim();
    const modalTitle = document.getElementById('modalTitle').textContent;

    if (!mpesaNumber || isNaN(parseFloat(mpesaNumber)) || mpesaNumber.length !== 12) {
        alert('Please enter a valid 12-digit M-Pesa phone number.');
        return;
    }

    // Simulate payment processing
    alert(`Payment request sent to ${mpesaNumber}. Please wait while we process your payment.`);
    saveTransaction({
        date: new Date().toISOString(),
        phoneNumber: mpesaNumber,
        package: modalTitle.split(' ')[1], // Extract package name from title
        amount: parseFloat(document.getElementById('amountDisplay').textContent.replace('Amount: Kes. ', '')),
    });

    closePaymentModal();
}

// Save transaction to file
async function saveTransaction(transaction) {
    try {
        const TRANSACTIONS_FILE = 'transactions.json'; // Adjust path as needed
        let transactions = [];

        const response = await fetch(TRANSACTIONS_FILE);
        if (response.ok) {
            transactions = await response.json();
        }

        // Prompt for M-Pesa Code and User Name
        const mpesaCode = prompt('Enter the M-Pesa Code:');
        const userName = prompt('Enter the User Name:');

        if (!mpesaCode || !userName) {
            alert('M-Pesa Code and User Name are required.');
            return;
        }

        // Add M-Pesa Code and User Name to the transaction
        transaction.mpesaCode = mpesaCode;
        transaction.userName = userName;

        transactions.push(transaction);

        await fetch(TRANSACTIONS_FILE, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(transactions),
        });
    } catch (error) {
        console.error('Error saving transaction:', error);
        alert('Failed to record transaction. Check console for details.');
    }
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

    // Simulate connection logic
    alert(`Connecting with account number: ${accountNumber}`);
    // Add logic to authenticate the user here
}

// Show admin login modal
function showAdminLogin() {
    const adminLoginModal = document.getElementById('adminLoginModal');
    adminLoginModal.classList.add('active');
}

// Handle admin login
function adminLogin() {
    const enteredPassword = document.getElementById('adminPassword').value.trim();
    const correctPassword = 'admin123'; // Hardcoded admin password

    if (enteredPassword === correctPassword) {
        alert('Login successful!');
        window.location.href = 'admin.html'; // Redirect to admin dashboard
    } else {
        alert('Incorrect password!');
    }
}

// Load packages when the page loads
window.onload = loadPackages;