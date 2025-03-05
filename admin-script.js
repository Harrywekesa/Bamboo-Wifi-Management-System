// File paths
const PACKAGES_FILE = 'packages.json'; // Adjust path as needed
const TRANSACTIONS_FILE = 'transactions.json'; // Adjust path as needed

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
    const packageList = document.getElementById('packageList');
    packageList.innerHTML = ''; // Clear existing packages

    packages.forEach(pkg => {
        const packageCard = `
            <div class="package">
                <h3>${pkg.name}</h3>
                <p>Kes. ${pkg.price} valid for ${pkg.duration}</p>
                <button class="red" onclick="deletePackage('${pkg.name}')">Delete</button>
            </div>
        `;
        packageList.insertAdjacentHTML('beforeend', packageCard);
    });
}

// Add package
document.getElementById('addPackageForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const name = document.getElementById('packageName').value.trim();
    const price = parseFloat(document.getElementById('packagePrice').value);
    const duration = document.getElementById('packageDuration').value.trim();

    if (!name || isNaN(price) || !duration) {
        alert('Please fill in all fields correctly.');
        return;
    }

    const newPackage = { name, price, duration };

    try {
        let packages = [];
        const response = await fetch(PACKAGES_FILE);
        if (response.ok) {
            packages = await response.json();
        }
        packages.push(newPackage);

        await fetch(PACKAGES_FILE, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(packages),
        });

        alert('Package added successfully!');
        location.reload(); // Reload the page to reflect changes
    } catch (error) {
        console.error('Error adding package:', error);
        alert('Failed to add package. Check console for details.');
    }
});

// Delete package
async function deletePackage(name) {
    if (!confirm(`Are you sure you want to delete "${name}"?`)) return;

    try {
        let packages = [];
        const response = await fetch(PACKAGES_FILE);
        if (response.ok) {
            packages = await response.json();
        }

        const updatedPackages = packages.filter(pkg => pkg.name !== name);

        await fetch(PACKAGES_FILE, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(updatedPackages),
        });

        alert('Package deleted successfully!');
        location.reload(); // Reload the page to reflect changes
    } catch (error) {
        console.error('Error deleting package:', error);
        alert('Failed to delete package. Check console for details.');
    }
}

// Load transactions
async function loadTransactions() {
    try {
        const response = await fetch(TRANSACTIONS_FILE);
        if (!response.ok) throw new Error('Failed to load transactions');
        const transactions = await response.json();
        renderTransactions(transactions);
    } catch (error) {
        console.error('Error loading transactions:', error);
        alert('Failed to load transactions. Check console for details.');
    }
}

// Render transactions dynamically
function renderTransactions(transactions) {
    const transactionTableBody = document.getElementById('transactionTableBody');
    transactionTableBody.innerHTML = ''; // Clear existing rows

    transactions.forEach(transaction => {
        const row = `
            <tr>
                <td>${new Date(transaction.date).toLocaleString()}</td>
                <td>${transaction.phoneNumber}</td>
                <td>${transaction.package}</td>
                <td>Kes. ${transaction.amount}</td>
            </tr>
        `;
        transactionTableBody.insertAdjacentHTML('beforeend', row);
    });
}

// Load packages and transactions when the page loads
window.onload = () => {
    loadPackages();
    loadTransactions();
};