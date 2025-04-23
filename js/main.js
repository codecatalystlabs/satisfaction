document.addEventListener('DOMContentLoaded', function () {
    // Load filters from session
    fetch('php/filters.php')
        .then(response => response.json())
        .then(filters => {
            // Apply filters to UI elements
            // For example:
            // document.getElementById('region').value = filters.region || '';
        });

    // Event listener for filter changes
    document.getElementById('filterForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const filters = {
            region: document.getElementById('region').value,
            // Add other filters
        };

        // Save filters to session
        fetch('php/filters.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ filters })
        })
        .then(response => response.json())
        .then(() => {
            loadData();
        });
    });

    // Function to load data
    function loadData() {
        fetch('php/data.php')
            .then(response => response.json())
            .then(data => {
                // Render data to your graphs
                // For example, using Chart.js or any other library
            });
    }

    // Initial data load
    loadData();
});

document.getElementById('clearFilters').addEventListener('click', function () {
    fetch('php/clear_filters.php')
        .then(response => response.json())
        .then(() => {
            // Reset filter UI elements
            document.getElementById('region').value = '';
            // Reset other filters
            loadData();
        });
});
