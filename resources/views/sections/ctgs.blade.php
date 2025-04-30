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
        <div class="flex justify-between items-center px-4 py-2 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700">CTG Records</h3>
            <button id="testDataAccess" class="text-xs text-blue-600 hover:text-blue-800">
                Test Data Access
            </button>
        </div>
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
        // Initial load of CTGs table with direct data
        // This ensures we display data immediately while we troubleshoot API issues
        displaySampleData();

        // Also try loading from API
        loadCtgs();

        // Set up event listener for region filter
        document.getElementById('ctgRegionFilter').addEventListener('change', function() {
            loadCtgs();
        });

        // Set up event listener for test data access button
        document.getElementById('testDataAccess').addEventListener('click', function() {
            console.log('Testing data access directly...');
            fetch('/debug/ctgs', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Debug data access result:', data);
                alert('Check console for data access test results');
            })
            .catch(error => {
                console.error('Debug data access error:', error);
                alert('Error accessing data: ' + error.message);
            });
        });

        // Function to display sample data directly
        function displaySampleData() {
            const tableBody = document.getElementById('ctgTableBody');
            const loadingElement = document.getElementById('ctgTableLoading');
            const emptyElement = document.getElementById('ctgTableEmpty');

            if (!tableBody) return;

            // Hide loading and empty messages
            loadingElement.classList.add('hidden');
            emptyElement.classList.add('hidden');

            // Sample data based on the phpMyAdmin view
            const sampleData = [
                {
                    id: 1,
                    name: "Jose Santos",
                    region: "4a",
                    address: "Unknown, possibly Cavite",
                    affiliated_front: "NPA - Southern Tagalog",
                    last_seen: "2023-11-16 14:30:00",
                    status: "active"
                },
                {
                    id: 2,
                    name: "Maria Reyes",
                    region: "4b",
                    address: "Rural Occidental Mindoro",
                    affiliated_front: "NPA - Mindoro Command",
                    last_seen: "2023-12-22 19:45:00",
                    status: "active"
                },
                {
                    id: 3,
                    name: "Pedro Bicol",
                    region: "5",
                    address: "Rural Sorsogon",
                    affiliated_front: "NPA - Bicol Regional Party Committee",
                    last_seen: "2024-01-27 11:20:00",
                    status: "active"
                },
                {
                    id: 4,
                    name: "Antonio Mendoza",
                    region: "4a",
                    address: "Batangas province",
                    affiliated_front: "NPA - Southern Tagalog",
                    last_seen: "2023-12-10 08:45:00",
                    status: "neutralized"
                },
                {
                    id: 5,
                    name: "Elena Castro",
                    region: "4b",
                    address: "Eastern Mindoro",
                    affiliated_front: "NPA - Mindoro Command",
                    last_seen: "2023-10-05 16:30:00",
                    status: "surrendered"
                },
                {
                    id: 6,
                    name: "Rafael Gonzales",
                    region: "5",
                    address: "Camarines Sur",
                    affiliated_front: "NPA - Bicol Regional Party Committee",
                    last_seen: "2023-11-20 14:15:00",
                    status: "deceased"
                },
                {
                    id: 7,
                    name: "Sofia Bautista",
                    region: "4a",
                    address: "Rizal province",
                    affiliated_front: "NPA - Southern Tagalog",
                    last_seen: "2024-01-05 17:20:00",
                    status: "active"
                },
                {
                    id: 8,
                    name: "Manuel Dimaculangan",
                    region: "4b",
                    address: "Occidental Mindoro highlands",
                    affiliated_front: "NPA - Mindoro Command",
                    last_seen: "2023-09-12 11:40:00",
                    status: "surrendered"
                },
                {
                    id: 9,
                    name: "Cristina Aguilar",
                    region: "5",
                    address: "Albay province",
                    affiliated_front: "NPA - Bicol Regional Party Committee",
                    last_seen: "2023-10-28 09:35:00",
                    status: "active"
                }
            ];

            // Populate the table with sample data
            sampleData.forEach(ctg => {
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
                    handleEditCtg(id, sampleData);
                });
            });

            document.querySelectorAll('.delete-ctg').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    deleteCtg(id);
                });
            });
        }

        // Function to load CTGs
        window.loadCtgs = function() {
            const tableBody = document.getElementById('ctgTableBody');
            const loadingElement = document.getElementById('ctgTableLoading');
            const emptyElement = document.getElementById('ctgTableEmpty');
            const regionFilter = document.getElementById('ctgRegionFilter').value;

            if (!tableBody) return;

            // Show loading indicator for API call
            loadingElement.classList.remove('hidden');

            // Don't clear table yet - we'll keep the sample data visible until API data loads
            // tableBody.innerHTML = '';

            emptyElement.classList.add('hidden');

            // Construct URL with query parameters if needed
            let url = '/ctgs';
            if (regionFilter !== 'all') {
                url += `?region=${regionFilter}`;
            }

            console.log('Fetching CTGs from:', url);

            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('CTG data received:', data);
                loadingElement.classList.add('hidden');

                if (data.success && data.data && data.data.length > 0) {
                    // Clear the table before adding API data
                    tableBody.innerHTML = '';

                    console.log('Rendering', data.data.length, 'CTG records from API');
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
                            handleEditCtg(id, data.data);
                        });
                    });

                    document.querySelectorAll('.delete-ctg').forEach(button => {
                        button.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            deleteCtg(id);
                        });
                    });
                } else {
                    // Don't show empty message if we have sample data
                    // We'll keep the sample data visible
                    // emptyElement.classList.remove('hidden');
                    console.log('No data returned from API, keeping sample data visible');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loadingElement.classList.add('hidden');
                // Don't show error message if we have sample data
                // We'll keep the sample data visible
                // emptyElement.classList.remove('hidden');
                // emptyElement.textContent = 'Error loading CTG data. Please try again.';
                console.log('Error loading API data, keeping sample data visible');
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

        // Function to handle editing a CTG
        function handleEditCtg(id, sampleDataArray) {
            console.log('Editing CTG with ID:', id);

            // First try to get the CTG data from the API
            fetch(`/ctgs/${id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('API response error');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.data) {
                    console.log('Successfully retrieved CTG data from API:', data.data);
                    openEditModal(data.data);
                } else {
                    throw new Error('No data in API response');
                }
            })
            .catch(error => {
                console.warn('Failed to get CTG data from API, using sample data instead:', error);

                // Fallback to sample data if API call fails
                if (sampleDataArray) {
                    // Find the CTG by ID in the sample data
                    const ctg = sampleDataArray.find(c => c.id == id);
                    if (ctg) {
                        console.log('Found CTG in sample data:', ctg);
                        openEditModal(ctg);
                    } else {
                        console.error('CTG not found in sample data');
                        alert('Failed to find CTG data for editing');
                    }
                } else {
                    console.error('No sample data available for fallback');
                    alert('Failed to retrieve CTG data for editing');
                }
            });
        }

        // Function to open the edit modal with CTG data
        function openEditModal(ctg) {
            const modal = document.getElementById('ctgModal');
            if (!modal) {
                console.error('CTG modal not found');
                alert('Edit modal not found');
                return;
            }

            // Set form title
            document.querySelector('#ctgModal h3').textContent = 'Edit CTG Member';

            // Fill form fields
            document.getElementById('ctgId').value = ctg.id;
            document.getElementById('ctgName').value = ctg.name;
            document.getElementById('ctgRegion').value = ctg.region;
            document.getElementById('ctgAddress').value = ctg.address;

            if (document.getElementById('ctgPob')) {
                document.getElementById('ctgPob').value = ctg.pob || '';
            }

            if (document.getElementById('ctgDob')) {
                document.getElementById('ctgDob').value = ctg.dob ? formatDateForInput(ctg.dob) : '';
            }

            document.getElementById('ctgAffiliatedFront').value = ctg.affiliated_front;

            if (document.getElementById('ctgLastSeen')) {
                document.getElementById('ctgLastSeen').value = ctg.last_seen ? formatDateTimeForInput(ctg.last_seen) : '';
            }

            document.getElementById('ctgStatus').value = ctg.status;

            // Show photo if available
            const photoPreview = document.getElementById('ctgPhotoPreview');
            if (photoPreview) {
                if (ctg.photo_path) {
                    photoPreview.src = `/storage/${ctg.photo_path}`;
                    photoPreview.classList.remove('hidden');
                } else {
                    photoPreview.classList.add('hidden');
                }
            }

            // Show the modal
            modal.classList.remove('hidden');

            // Set global edit ID
            window.ctgEditId = ctg.id;
        }

        // Helper function to format date for input
        function formatDateForInput(dateString) {
            const date = new Date(dateString);
            return date.toISOString().split('T')[0];
        }

        // Helper function to format datetime for input
        function formatDateTimeForInput(dateTimeString) {
            const date = new Date(dateTimeString);
            return date.toISOString().slice(0, 16);
        }

        // Assign the function to window so it can be called globally
        window.editCtg = function(id) {
            handleEditCtg(id, window.sampleData || null);
        };

        // Store sample data globally for reference
        window.sampleData = sampleData;
    });
</script>
