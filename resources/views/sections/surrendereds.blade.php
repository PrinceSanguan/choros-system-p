<!-- Surrendereds Section -->
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

    <!-- Surrendereds Table -->
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
        <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
            <div id="surrenderedCount" class="text-sm text-gray-700">Loading data...</div>
            <button id="exportSurrendereds" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                <i class="fas fa-download mr-1"></i>Export Data
            </button>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables to store current surrendereds
        let surrendereds = [];

        // Initialize surrendereds data
        loadSurrendereds();

        // Setup event listeners
        document.getElementById('addSurrendered').addEventListener('click', function() {
            // Reset form and set title
            document.getElementById('surrenderedForm').reset();
            document.getElementById('surrenderedPhotoPreview').classList.add('hidden');
            document.querySelector('#surrenderedModal h3').textContent = 'Add New Surrendered Individual';
            // Clear global surrendered ID
            if (window.surrenderedId) window.surrenderedId = null;
            // Show modal
            document.getElementById('surrenderedModal').classList.remove('hidden');
        });

        document.getElementById('surrenderedRegionFilter').addEventListener('change', function() {
            loadSurrendereds();
        });

        document.getElementById('exportSurrendereds').addEventListener('click', function() {
            exportToCSV();
        });

        // Function to load surrendereds from the server
        window.loadSurrendereds = function() {
            const region = document.getElementById('surrenderedRegionFilter').value;

            // Show loading state
            document.getElementById('surrenderedTableBody').innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center">Loading data...</td></tr>';

            // Construct URL with query parameters
            let url = '{{ route("surrendered.index") }}';
            const params = new URLSearchParams();

            if (region !== 'all') {
                params.append('region', region);
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
                    surrendereds = data.data;
                    renderSurrenderedsTable();
                } else {
                    console.error('Failed to fetch surrendereds:', data.message);
                    document.getElementById('surrenderedTableBody').innerHTML =
                        '<tr><td colspan="8" class="px-6 py-4 text-center text-red-500">Failed to load data. Please try again.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching surrendereds:', error);
                document.getElementById('surrenderedTableBody').innerHTML =
                    '<tr><td colspan="8" class="px-6 py-4 text-center text-red-500">Failed to load data. Please try again.</td></tr>';
            });
        }

        // Function to render surrendereds table
        function renderSurrenderedsTable() {
            const tableBody = document.getElementById('surrenderedTableBody');
            tableBody.innerHTML = '';

            if (surrendereds.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center">No surrendered individuals found.</td></tr>';
                document.getElementById('surrenderedCount').textContent = 'No results';
                return;
            }

            // Update the surrendereds count
            document.getElementById('surrenderedCount').textContent = `${surrendereds.length} surrendered individual(s) found`;

            // Render each surrendered individual
            surrendereds.forEach(surrendered => {
                const row = document.createElement('tr');
                const statusClass = getStatusClass(surrendered.status);

                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                            ${surrendered.photo_path
                                ? `<img src="/storage/${surrendered.photo_path}" alt="${surrendered.name}" class="h-full w-full object-cover">`
                                : `<i class="fas fa-user text-gray-400"></i>`}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${surrendered.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${getRegionName(surrendered.region)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${surrendered.date_of_birth ? formatDate(surrendered.date_of_birth, true) : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${surrendered.former_group}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(surrendered.date_surrendered)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            ${formatStatus(surrendered.status)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-blue-600 hover:text-blue-900 edit-surrendered" data-id="${surrendered.id}">Edit</button>
                        <button class="text-red-600 hover:text-red-900 delete-surrendered" data-id="${surrendered.id}">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Add event listeners to the edit and delete buttons
            document.querySelectorAll('.edit-surrendered').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    if (window.editSurrendered) {
                        window.editSurrendered(id);
                    }
                });
            });

            document.querySelectorAll('.delete-surrendered').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    confirmDeleteSurrendered(id);
                });
            });
        }

        // Function to get status class for styling
        function getStatusClass(status) {
            switch(status) {
                case 'rehabilitation': return 'bg-blue-100 text-blue-800';
                case 'processing': return 'bg-yellow-100 text-yellow-800';
                case 'completed': return 'bg-green-100 text-green-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        // Function to format status for display
        function formatStatus(status) {
            switch(status) {
                case 'rehabilitation': return 'Rehabilitation';
                case 'processing': return 'Processing';
                case 'completed': return 'Completed';
                default: return capitalizeFirstLetter(status);
            }
        }

        // Function to capitalize first letter
        function capitalizeFirstLetter(string) {
            if (!string) return '';
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Function to confirm deletion of a surrendered individual
        function confirmDeleteSurrendered(id) {
            if (confirm('Are you sure you want to delete this surrendered individual? This action cannot be undone.')) {
                deleteSurrendered(id);
            }
        }

        // Function to delete a surrendered individual
        function deleteSurrendered(id) {
            fetch(`{{ url('surrendered') }}/${id}`, {
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
                    loadSurrendereds(); // Refresh the list
                    alert('Surrendered individual deleted successfully.');
                } else {
                    alert('Failed to delete surrendered individual: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting surrendered individual:', error);
                alert('Failed to delete surrendered individual. Please try again.');
            });
        }

        // Function to format date for display
        function formatDate(dateString, dateOnly = false) {
            const options = dateOnly
                ? { year: 'numeric', month: 'short', day: 'numeric' }
                : { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateString).toLocaleDateString('en-US', options);
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
            if (surrendereds.length === 0) {
                alert('No data to export.');
                return;
            }

            // Define CSV headers
            const headers = [
                'Name',
                'Alias',
                'Gender',
                'Region',
                'Province',
                'Municipality',
                'Barangay',
                'Date of Birth',
                'Former Group',
                'Date Surrendered',
                'Status',
                'Remarks'
            ];

            // Convert data to CSV format
            let csvContent = headers.join(',') + '\n';

            surrendereds.forEach(surrendered => {
                const row = [
                    `"${surrendered.name.replace(/"/g, '""')}"`,
                    `"${(surrendered.alias || '').replace(/"/g, '""')}"`,
                    `"${capitalizeFirstLetter(surrendered.gender).replace(/"/g, '""')}"`,
                    `"${getRegionName(surrendered.region).replace(/"/g, '""')}"`,
                    `"${surrendered.province.replace(/"/g, '""')}"`,
                    `"${surrendered.municipality.replace(/"/g, '""')}"`,
                    `"${surrendered.barangay.replace(/"/g, '""')}"`,
                    `"${surrendered.date_of_birth ? formatDate(surrendered.date_of_birth, true) : 'N/A'}"`,
                    `"${surrendered.former_group.replace(/"/g, '""')}"`,
                    `"${formatDate(surrendered.date_surrendered)}"`,
                    `"${formatStatus(surrendered.status)}"`,
                    `"${(surrendered.remarks || '').replace(/"/g, '""')}"`
                ];
                csvContent += row.join(',') + '\n';
            });

            // Create a download link
            const encodedUri = encodeURI('data:text/csv;charset=utf-8,' + csvContent);
            const link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', `surrendered-${new Date().toISOString().slice(0, 10)}.csv`);
            document.body.appendChild(link);

            // Trigger download and clean up
            link.click();
            document.body.removeChild(link);
        }
    });
</script>
