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

    try {
        const response = await fetch('process.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=add_package&name=${encodeURIComponent(name)}&price=${price}&duration=${encodeURIComponent(duration)}`
        });

        const result = await response.json();
        if (result.success) {
            alert('Package added successfully!');
            location.reload(); // Reload the page to reflect changes
        } else {
            alert('Failed to add package.');
        }
    } catch (error) {
        console.error('Error adding package:', error);
        alert('An error occurred. Please try again.');
    }
});

// Delete package
async function deletePackage(name) {
    if (!confirm(`Are you sure you want to delete "${name}"?`)) return;

    try {
        const response = await fetch('process.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=delete_package&name=${encodeURIComponent(name)}`
        });

        const result = await response.json();
        if (result.success) {
            alert('Package deleted successfully!');
            location.reload(); // Reload the page to reflect changes
        } else {
            alert('Failed to delete package.');
        }
    } catch (error) {
        console.error('Error deleting package:', error);
        alert('An error occurred. Please try again.');
    }
}

// Load transactions from PHP (via process.php)
async function loadTransactions() {
    try {
        const response = await fetch('process.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=get_transactions'
        });

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
    const transactionTableBody = document.querySelector('tbody');
    transactionTableBody.innerHTML = ''; // Clear existing rows

    transactions.forEach(transaction => {
        const row = `
            <tr>
                <td>${new Date(transaction.date).toLocaleString()}</td>
                <td>${transaction.phone_number}</td>
                <td>${transaction.package_name}</td>
                <td>Kes. ${transaction.amount}</td>
                <td>${transaction.mpesa_code || 'N/A'}</td>
                <td>${transaction.user_name || 'N/A'}</td>
            </tr>
        `;
        transactionTableBody.insertAdjacentHTML('beforeend', row);
    });
}

// Load transactions from PHP (via process.php)
async function loadTransactions() {
    try {
        const response = await fetch('process.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=get_transactions'
        });

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
    const transactionTableBody = document.querySelector('tbody');
    transactionTableBody.innerHTML = ''; // Clear existing rows

    transactions.forEach(transaction => {
        const row = `
            <tr>
                <td>${new Date(transaction.date).toLocaleString()}</td>
                <td>${transaction.phone_number}</td>
                <td>${transaction.package_name}</td>
                <td>Kes. ${transaction.amount}</td>
                <td>${transaction.mpesa_code || 'N/A'}</td>
                <td>${transaction.user_name || 'N/A'}</td>
            </tr>
        `;
        transactionTableBody.insertAdjacentHTML('beforeend', row);
    });
}

// Toggle sidebar visibility
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    if (sidebar.classList.contains('collapsed')) {
        // Expand the sidebar
        sidebar.classList.remove('collapsed');
        mainContent.style.marginLeft = '270px'; // Restore default margin
    } else {
        // Collapse the sidebar
        sidebar.classList.add('collapsed');
        mainContent.style.marginLeft = '60px'; // Reduce margin for collapsed sidebar
    }
}

// Load packages and transactions when the page loads
window.onload = () => {
    loadPackages();
    loadTransactions();
};