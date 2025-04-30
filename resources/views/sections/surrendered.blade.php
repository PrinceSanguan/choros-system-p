<!-- Surrendered Section -->
<section id="surrendered" class="section-content p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-900">Surrendered Individuals</h2>
        <div class="flex space-x-2">
            <select id="surrenderedRegionFilter" class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all">All Regions</option>
                <option value="4a">Region 4A (CALABARZON)</option>
                <option value="4b">Region 4B (MIMAROPA)</option>
                <option value="5">Region 5 (Bicol)</option>
            </select>
            <button id="addSurrendered" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add New
            </button>
        </div>
    </div>

    <!-- Surrendered Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Region</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Birth</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Former Group</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Surrendered</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="surrenderedTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Make loadSurrendereds function available globally
        window.loadSurrendereds = function() {
            // Fetch data from server based on selected region
            const region = document.getElementById('surrenderedRegionFilter')?.value || 'all';

            // Fetch API call to get surrendered data
            let url = '/surrendered';
            if (region !== 'all') {
                url += `?region=${region}`;
            }

            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const tableBody = document.getElementById('surrenderedTableBody');
                    tableBody.innerHTML = '';

                    if (data.data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center">No records found</td></tr>';
                        return;
                    }

                    // Populate table with data
                    data.data.forEach(item => {
                        const row = document.createElement('tr');

                        // Format date
                        const formatDate = (dateString) => {
                            const options = { year: 'numeric', month: 'short', day: 'numeric' };
                            return new Date(dateString).toLocaleDateString(undefined, options);
                        };

                        // Get status badge class
                        const getStatusClass = (status) => {
                            switch(status) {
                                case 'rehabilitation': return 'bg-blue-100 text-blue-800';
                                case 'processing': return 'bg-yellow-100 text-yellow-800';
                                case 'completed': return 'bg-green-100 text-green-800';
                                default: return 'bg-gray-100 text-gray-800';
                            }
                        };

                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                    ${item.photo_path
                                        ? `<img src="/storage/${item.photo_path}" alt="${item.name}" class="h-full w-full object-cover">`
                                        : `<i class="fas fa-user text-gray-400"></i>`}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">${item.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap">Region ${item.region.toUpperCase()}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${item.date_of_birth ? formatDate(item.date_of_birth) : 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${item.former_group}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${formatDate(item.date_surrendered)}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(item.status)}">
                                    ${item.status.charAt(0).toUpperCase() + item.status.slice(1)}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-blue-600 hover:text-blue-900 mr-3 edit-btn" data-id="${item.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="text-red-600 hover:text-red-900 delete-btn" data-id="${item.id}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        `;

                        tableBody.appendChild(row);
                    });

                    // Add event listeners for edit buttons
                    document.querySelectorAll('.edit-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            if (window.editSurrendered) {
                                window.editSurrendered(this.dataset.id);
                            }
                        });
                    });

                    // Add event listeners for delete buttons
                    document.querySelectorAll('.delete-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            if (confirm('Are you sure you want to delete this record? This action cannot be undone.')) {
                                const id = this.dataset.id;

                                fetch(`/surrendered/${id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        alert('Record deleted successfully');
                                        loadSurrendereds(); // Reload the table
                                    } else {
                                        alert('Error: ' + (data.message || 'Failed to delete record'));
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('An error occurred while deleting the record.');
                                });
                            }
                        });
                    });
                } else {
                    console.error('Failed to load data:', data.message);
                    document.getElementById('surrenderedTableBody').innerHTML =
                        '<tr><td colspan="8" class="px-6 py-4 text-center text-red-500">Failed to load data</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('surrenderedTableBody').innerHTML =
                    '<tr><td colspan="8" class="px-6 py-4 text-center text-red-500">Error loading data</td></tr>';
            });
        };

        // Initial load
        window.loadSurrendereds();

        // Set up event listener for region filter
        document.getElementById('surrenderedRegionFilter')?.addEventListener('change', function() {
            window.loadSurrendereds();
        });

        // Set up event listener for Add New button
        document.getElementById('addSurrendered')?.addEventListener('click', function() {
            // This will be handled by the modal's own event listener
        });
    });
</script>
