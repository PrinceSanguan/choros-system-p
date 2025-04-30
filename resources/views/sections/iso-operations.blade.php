<!-- ISO Operations Section -->
<section id="iso-operations" class="section-content p-6 hidden">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-900">ISO Operations</h2>
        <div class="flex space-x-2">
            <div class="relative">
                <input type="text" id="isoSearchInput" placeholder="Search operations..."
                    class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10">
                <button id="isoSearchButton" class="absolute right-2 top-2 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <select id="isoRegionFilter" class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all">All Regions</option>
                <option value="4a">Region 4A (CALABARZON)</option>
                <option value="4b">Region 4B (MIMAROPA)</option>
                <option value="5">Region 5 (Bicol)</option>
            </select>
            <button id="addIsoOperation" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add New
            </button>
        </div>
    </div>

    <!-- ISO Operations Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Operation Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Region</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team Leader</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="isoTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
            <div id="isoOperationsCount" class="text-sm text-gray-700">Loading data...</div>
            <button id="exportIsoOperations" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                <i class="fas fa-download mr-1"></i>Export Data
            </button>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables to store current ISO operations
        let isoOperations = [];
        let currentOperation = null;

        // Initialize ISO operations data
        fetchISOOperations();

        // Setup event listeners
        document.getElementById('addIsoOperation').addEventListener('click', function() {
            resetIsoForm();
            document.getElementById('isoModal').classList.remove('hidden');
            document.querySelector('#isoModal h3').textContent = 'Add New ISO Operation';
        });

        document.getElementById('isoRegionFilter').addEventListener('change', function() {
            fetchISOOperations();
        });

        document.getElementById('isoSearchButton').addEventListener('click', function() {
            fetchISOOperations();
        });

        document.getElementById('isoSearchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                fetchISOOperations();
            }
        });

        document.getElementById('exportIsoOperations').addEventListener('click', function() {
            exportToCSV();
        });

        // Form submission
        document.getElementById('isoOperationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveISOOperation();
        });

        // Function to fetch ISO operations from the server
        function fetchISOOperations() {
            const region = document.getElementById('isoRegionFilter').value;
            const search = document.getElementById('isoSearchInput').value;

            // Show loading state
            document.getElementById('isoTableBody').innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center">Loading data...</td></tr>';

            // Construct URL with query parameters
            let url = '{{ route("iso-operations.index") }}';
            const params = new URLSearchParams();

            if (region !== 'all') {
                params.append('region', region);
            }

            if (search) {
                params.append('search', search);
            }

            // Add parameters to URL if any exist
            if (params.toString()) {
                url += '?' + params.toString();
            }

            // Fetch data from server
            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    isoOperations = data.data;
                    renderISOOperationsTable();
                } else {
                    console.error('Failed to fetch ISO operations:', data.message);
                    document.getElementById('isoTableBody').innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Failed to load data. Please try again.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching ISO operations:', error);
                document.getElementById('isoTableBody').innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Failed to load data. Please try again.</td></tr>';
            });
        }

        // Function to render ISO operations table
        function renderISOOperationsTable() {
            const tableBody = document.getElementById('isoTableBody');
            tableBody.innerHTML = '';

            if (isoOperations.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center">No ISO operations found.</td></tr>';
                document.getElementById('isoOperationsCount').textContent = 'No results';
                return;
            }

            // Update the operations count
            document.getElementById('isoOperationsCount').textContent = `${isoOperations.length} operation(s) found`;

            // Render each operation
            isoOperations.forEach(operation => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${operation.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${getRegionName(operation.region)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(operation.date)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${operation.team_leader}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${operation.location}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-blue-600 hover:text-blue-900 edit-operation" data-id="${operation.id}">Edit</button>
                        <button class="text-red-600 hover:text-red-900 delete-operation" data-id="${operation.id}">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Add event listeners to the edit and delete buttons
            document.querySelectorAll('.edit-operation').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    editISOOperation(id);
                });
            });

            document.querySelectorAll('.delete-operation').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    confirmDeleteISOOperation(id);
                });
            });
        }

        // Function to edit an ISO operation
        function editISOOperation(id) {
            currentOperation = isoOperations.find(op => op.id == id);

            if (currentOperation) {
                // Update modal title
                document.querySelector('#isoModal h3').textContent = 'Edit ISO Operation';

                // Fill in the form fields
                document.getElementById('isoOperationName').value = currentOperation.name;
                document.getElementById('isoRegion').value = currentOperation.region;
                document.getElementById('isoDate').value = formatDateForInput(currentOperation.date);
                document.getElementById('isoTeamLeader').value = currentOperation.team_leader;
                document.getElementById('isoTeamMembers').value = currentOperation.members || '';
                document.getElementById('isoLocation').value = currentOperation.location;

                if (currentOperation.coordinates) {
                    const coords = currentOperation.coordinates.split(',');
                    if (coords.length === 2) {
                        document.getElementById('isoLatitude').value = coords[0].trim();
                        document.getElementById('isoLongitude').value = coords[1].trim();
                    }
                }

                document.getElementById('isoLocationPerDay').value = currentOperation.location_per_day || '';

                // Show the modal
                document.getElementById('isoModal').classList.remove('hidden');
            }
        }

        // Function to confirm deletion of an ISO operation
        function confirmDeleteISOOperation(id) {
            if (confirm('Are you sure you want to delete this ISO operation? This action cannot be undone.')) {
                deleteISOOperation(id);
            }
        }

        // Function to delete an ISO operation
        function deleteISOOperation(id) {
            fetch(`{{ url('iso-operations') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fetchISOOperations(); // Refresh the list
                    alert('ISO operation deleted successfully.');
                } else {
                    alert('Failed to delete ISO operation: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting ISO operation:', error);
                alert('Failed to delete ISO operation. Please try again.');
            });
        }

        // Function to save an ISO operation (create or update)
        function saveISOOperation() {
            // Gather form data
            const formData = {
                name: document.getElementById('isoOperationName').value,
                region: document.getElementById('isoRegion').value,
                date: document.getElementById('isoDate').value,
                team_leader: document.getElementById('isoTeamLeader').value,
                members: document.getElementById('isoTeamMembers').value,
                location: document.getElementById('isoLocation').value,
                location_per_day: document.getElementById('isoLocationPerDay').value
            };

            // Add coordinates if both latitude and longitude are provided
            const lat = document.getElementById('isoLatitude').value.trim();
            const lng = document.getElementById('isoLongitude').value.trim();
            if (lat && lng) {
                formData.coordinates = `${lat}, ${lng}`;
            }

            // Determine if this is a create or update operation
            const method = currentOperation ? 'PUT' : 'POST';
            const url = currentOperation
                ? `{{ url('iso-operations') }}/${currentOperation.id}`
                : '{{ route("iso-operations.store") }}';

            // Send request to server
            fetch(url, {
                method: method,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin',
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('isoModal').classList.add('hidden');
                    resetIsoForm();
                    fetchISOOperations(); // Refresh the list
                    alert(currentOperation ? 'ISO operation updated successfully.' : 'ISO operation created successfully.');
                } else {
                    alert('Failed to save ISO operation: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error saving ISO operation:', error);
                alert('Failed to save ISO operation. Please check your input and try again.');
            });
        }

        // Function to reset the ISO operation form
        function resetIsoForm() {
            document.getElementById('isoOperationForm').reset();
            currentOperation = null;
        }

        // Function to format date for display
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        // Function to format date for input field
        function formatDateForInput(dateString) {
            const date = new Date(dateString);
            return date.toISOString().slice(0, 16); // Format: YYYY-MM-DDThh:mm
        }

        // Function to get region name from code
        function getRegionName(regionCode) {
            switch(regionCode) {
                case '4a': return 'Region 4A (CALABARZON)';
                case '4b': return 'Region 4B (MIMAROPA)';
                case '5': return 'Region 5 (Bicol)';
                default: return 'Unknown Region';
            }
        }

        // Function to export data to CSV
        function exportToCSV() {
            if (isoOperations.length === 0) {
                alert('No data to export.');
                return;
            }

            // Define CSV headers
            const headers = [
                'Operation Name',
                'Region',
                'Date',
                'Team Leader',
                'Members',
                'Location',
                'Coordinates',
                'Location Per Day'
            ];

            // Convert data to CSV format
            let csvContent = headers.join(',') + '\n';

            isoOperations.forEach(operation => {
                const row = [
                    `"${operation.name.replace(/"/g, '""')}"`,
                    `"${getRegionName(operation.region).replace(/"/g, '""')}"`,
                    `"${formatDate(operation.date)}"`,
                    `"${operation.team_leader.replace(/"/g, '""')}"`,
                    `"${(operation.members || '').replace(/"/g, '""')}"`,
                    `"${operation.location.replace(/"/g, '""')}"`,
                    `"${(operation.coordinates || '').replace(/"/g, '""')}"`,
                    `"${(operation.location_per_day || '').replace(/"/g, '""')}"`
                ];
                csvContent += row.join(',') + '\n';
            });

            // Create a download link
            const encodedUri = encodeURI('data:text/csv;charset=utf-8,' + csvContent);
            const link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', `iso-operations-${new Date().toISOString().slice(0, 10)}.csv`);
            document.body.appendChild(link);

            // Trigger download and clean up
            link.click();
            document.body.removeChild(link);
        }
    });
</script>
