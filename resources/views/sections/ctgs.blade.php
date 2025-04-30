<!-- CTGs Section -->
<section id="ctgs" class="section-content p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-900">CTGs (Communist Terrorist Groups)</h2>
        <div class="flex space-x-2">
            <select id="ctgRegionFilter" class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all">All Regions</option>
                <option value="4a">Region 4A (CALABARZON)</option>
                <option value="4b">Region 4B (MIMAROPA)</option>
                <option value="5">Region 5 (Bicol)</option>
            </select>
            <button id="addCtg" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add New
            </button>
        </div>
    </div>

    <!-- CTGs Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Region</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Affiliated Front</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Seen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="ctgTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
        <div id="ctgTableLoading" class="px-6 py-4 text-center text-gray-500">
            <i class="fas fa-spinner fa-spin mr-2"></i> Loading data...
        </div>
        <div id="ctgTableEmpty" class="px-6 py-4 text-center text-gray-500 hidden">
            No CTG members found. Add a new CTG member to get started.
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initial load of CTGs table
        loadCtgs();

        // Set up event listener for region filter
        document.getElementById('ctgRegionFilter').addEventListener('change', function() {
            loadCtgs();
        });

        // Function to load CTGs
        window.loadCtgs = function() {
            const tableBody = document.getElementById('ctgTableBody');
            const loadingElement = document.getElementById('ctgTableLoading');
            const emptyElement = document.getElementById('ctgTableEmpty');
            const regionFilter = document.getElementById('ctgRegionFilter').value;

            if (!tableBody) return;

            tableBody.innerHTML = '';
            loadingElement.classList.remove('hidden');
            emptyElement.classList.add('hidden');

            // Construct URL with query parameters if needed
            let url = '/ctgs';
            if (regionFilter !== 'all') {
                url += `?region=${regionFilter}`;
            }

            fetch(url, {
                method: 'GET',
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
                loadingElement.classList.add('hidden');

                if (data.success && data.data && data.data.length > 0) {
                    data.data.forEach(ctg => {
                        const row = document.createElement('tr');

                        // Format date
                        const formatDate = (dateString) => {
                            if (!dateString) return 'N/A';
                            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                            return new Date(dateString).toLocaleDateString(undefined, options);
                        };

                        // Set status badge class
                        const getStatusBadgeClass = (status) => {
                            switch(status) {
                                case 'active': return 'bg-red-100 text-red-800';
                                case 'neutralized': return 'bg-yellow-100 text-yellow-800';
                                case 'surrendered': return 'bg-green-100 text-green-800';
                                case 'deceased': return 'bg-gray-100 text-gray-800';
                                default: return 'bg-blue-100 text-blue-800';
                            }
                        };

                        // Get region name
                        const getRegionName = (region) => {
                            switch(region) {
                                case '4a': return 'Region 4A (CALABARZON)';
                                case '4b': return 'Region 4B (MIMAROPA)';
                                case '5': return 'Region 5 (Bicol)';
                                default: return 'Unknown Region';
                            }
                        };

                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${ctg.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${getRegionName(ctg.region)}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">${ctg.address}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${ctg.affiliated_front}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(ctg.last_seen)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusBadgeClass(ctg.status)}">
                                    ${ctg.status.charAt(0).toUpperCase() + ctg.status.slice(1)}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button class="text-blue-600 hover:text-blue-900 edit-ctg" data-id="${ctg.id}">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <button class="text-red-600 hover:text-red-900 delete-ctg" data-id="${ctg.id}">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </td>
                        `;

                        tableBody.appendChild(row);
                    });

                    // Add event listeners to the edit and delete buttons
                    document.querySelectorAll('.edit-ctg').forEach(button => {
                        button.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            if (window.editCtg) {
                                window.editCtg(id);
                            }
                        });
                    });

                    document.querySelectorAll('.delete-ctg').forEach(button => {
                        button.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            deleteCtg(id);
                        });
                    });
                } else {
                    emptyElement.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loadingElement.classList.add('hidden');
                emptyElement.classList.remove('hidden');
                emptyElement.textContent = 'Error loading CTG data. Please try again.';
            });
        };

        // Function to delete a CTG
        function deleteCtg(id) {
            if (confirm('Are you sure you want to delete this CTG member? This action cannot be undone.')) {
                fetch(`/ctgs/${id}`, {
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
                        loadCtgs(); // Reload the table
                        alert('CTG record deleted successfully.');
                    } else {
                        alert('Failed to delete CTG record: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the CTG record.');
                });
            }
        }
    });
</script>
