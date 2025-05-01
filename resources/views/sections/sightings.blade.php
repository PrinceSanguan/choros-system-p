<!-- Sightings Section -->
<section id="sightings" class="section-content p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-900">Sightings</h2>
        <div class="flex space-x-2">
            <select id="sightingsRegionFilter" class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all">All Regions</option>
                <option value="4a">Region 4A (CALABARZON)</option>
                <option value="4b">Region 4B (MIMAROPA)</option>
                <option value="5">Region 5 (Bicol)</option>
            </select>
            <button id="addSighting" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add New
            </button>
        </div>
    </div>

    <!-- Sightings Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Region</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coordinates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="sightingsTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
            <div id="sightingsCount" class="text-sm text-gray-700">Loading data...</div>
            <button id="exportSightings" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                <i class="fas fa-download mr-1"></i>Export Data
            </button>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables to store current sightings
        let sightings = [];
        let currentSighting = null;

        // Initialize sightings data
        fetchSightings();

        // Setup event listeners
        document.getElementById('addSighting').addEventListener('click', function() {
            resetSightingForm();
            document.getElementById('sightingModal').classList.remove('hidden');
            document.querySelector('#sightingModal h3').textContent = 'Add New Sighting';
        });

        document.getElementById('sightingsRegionFilter').addEventListener('change', function() {
            fetchSightings();
        });

        document.getElementById('exportSightings').addEventListener('click', function() {
            exportToCSV();
        });

        // Form submission
        document.getElementById('sightingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveSighting();
        });

        // Function to fetch sightings from the server
        function fetchSightings() {
            const region = document.getElementById('sightingsRegionFilter').value;

            // Show loading state
            document.getElementById('sightingsTableBody').innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center">Loading data...</td></tr>';

            // Construct URL with query parameters
            let url = '{{ route("sightings.index") }}';
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
                    sightings = data.data;
                    renderSightingsTable();
                } else {
                    console.error('Failed to fetch sightings:', data.message);
                    document.getElementById('sightingsTableBody').innerHTML =
                        '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Failed to load data. Please try again.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching sightings:', error);
                document.getElementById('sightingsTableBody').innerHTML =
                    '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Failed to load data. Please try again.</td></tr>';
            });
        }

        // Function to render sightings table
        function renderSightingsTable() {
            const tableBody = document.getElementById('sightingsTableBody');
            tableBody.innerHTML = '';

            if (sightings.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center">No sightings found.</td></tr>';
                document.getElementById('sightingsCount').textContent = 'No results';
                return;
            }

            // Update the sightings count
            document.getElementById('sightingsCount').textContent = `${sightings.length} sighting(s) found`;

            // Render each sighting
            sightings.forEach(sighting => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${sighting.location}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${getRegionName(sighting.region)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(sighting.date)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${sighting.description}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${sighting.coordinates || ''}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        @if(auth()->user()->role !== 'user')
                        <button class="text-blue-600 hover:text-blue-900 edit-sighting" data-id="${sighting.id}">Edit</button>
                        @endif
                        <button class="text-red-600 hover:text-red-900 delete-sighting" data-id="${sighting.id}">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Add event listeners to the edit and delete buttons
            document.querySelectorAll('.edit-sighting').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    editSighting(id);
                });
            });

            document.querySelectorAll('.delete-sighting').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    confirmDeleteSighting(id);
                });
            });
        }

        // Function to edit a sighting
        function editSighting(id) {
            currentSighting = sightings.find(s => s.id == id);

            if (currentSighting) {
                // Update modal title
                document.querySelector('#sightingModal h3').textContent = 'Edit Sighting';

                // Fill in the form fields
                document.getElementById('sightingRegion').value = currentSighting.region;
                document.getElementById('sightingDate').value = formatDateForInput(currentSighting.date);
                document.getElementById('sightingLocation').value = currentSighting.location;
                document.getElementById('sightingDescription').value = currentSighting.description;

                if (currentSighting.coordinates) {
                    const coords = currentSighting.coordinates.split(',');
                    if (coords.length === 2) {
                        document.getElementById('sightingLatitude').value = coords[0].trim();
                        document.getElementById('sightingLongitude').value = coords[1].trim();
                    }
                }

                // Show the modal
                document.getElementById('sightingModal').classList.remove('hidden');
            }
        }

        // Function to confirm deletion of a sighting
        function confirmDeleteSighting(id) {
            if (confirm('Are you sure you want to delete this sighting? This action cannot be undone.')) {
                deleteSighting(id);
            }
        }

        // Function to delete a sighting
        function deleteSighting(id) {
            fetch(`{{ url('sightings') }}/${id}`, {
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
                    fetchSightings(); // Refresh the list
                    alert('Sighting deleted successfully.');
                } else {
                    alert('Failed to delete sighting: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting sighting:', error);
                alert('Failed to delete sighting. Please try again.');
            });
        }

        // Function to save a sighting (create or update)
        function saveSighting() {
            // Gather form data
            const lat = document.getElementById('sightingLatitude').value.trim();
            const lng = document.getElementById('sightingLongitude').value.trim();

            const formData = {
                region: document.getElementById('sightingRegion').value,
                date: document.getElementById('sightingDate').value,
                location: document.getElementById('sightingLocation').value,
                description: document.getElementById('sightingDescription').value
            };

            // Add coordinates if both latitude and longitude are provided
            if (lat && lng) {
                formData.coordinates = `${lat}, ${lng}`;
            }

            // Determine if this is a create or update operation
            const method = currentSighting ? 'PUT' : 'POST';
            const url = currentSighting
                ? `{{ url('sightings') }}/${currentSighting.id}`
                : '{{ route("sightings.store") }}';

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
                    document.getElementById('sightingModal').classList.add('hidden');
                    resetSightingForm();
                    fetchSightings(); // Refresh the list
                    alert(currentSighting ? 'Sighting updated successfully.' : 'Sighting created successfully.');
                } else {
                    alert('Failed to save sighting: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error saving sighting:', error);
                alert('Failed to save sighting. Please check your input and try again.');
            });
        }

        // Function to reset the sighting form
        function resetSightingForm() {
            document.getElementById('sightingForm').reset();
            currentSighting = null;
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
            if (sightings.length === 0) {
                alert('No data to export.');
                return;
            }

            // Define CSV headers
            const headers = [
                'Location',
                'Region',
                'Date & Time',
                'Description',
                'Coordinates'
            ];

            // Convert data to CSV format
            let csvContent = headers.join(',') + '\n';

            sightings.forEach(sighting => {
                const row = [
                    `"${sighting.location.replace(/"/g, '""')}"`,
                    `"${getRegionName(sighting.region).replace(/"/g, '""')}"`,
                    `"${formatDate(sighting.date)}"`,
                    `"${sighting.description.replace(/"/g, '""')}"`,
                    `"${(sighting.coordinates || '').replace(/"/g, '""')}"`
                ];
                csvContent += row.join(',') + '\n';
            });

            // Create a download link
            const encodedUri = encodeURI('data:text/csv;charset=utf-8,' + csvContent);
            const link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', `sightings-${new Date().toISOString().slice(0, 10)}.csv`);
            document.body.appendChild(link);

            // Trigger download and clean up
            link.click();
            document.body.removeChild(link);
        }
    });
</script>
