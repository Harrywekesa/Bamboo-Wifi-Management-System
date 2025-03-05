// File paths
const PACKAGES_FILE = 'packages.json'; // Use relative path for local testing

// Load packages
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
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(packages),
        });

        alert('Package added successfully!');
        location.reload();
    } catch (error) {
        console.error('Error adding package:', error);
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
        location.reload();
    } catch (error) {
        console.error('Error deleting package:', error);
    }
}

// Load packages when the page loads
window.onload = loadPackages;