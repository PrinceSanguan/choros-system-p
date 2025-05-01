<!-- Firearms Section -->
<section id="firearms" class="section-content p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-900">Firearms</h2>
        <div class="flex space-x-2">
            <select id="firearmsRegionFilter" class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all">All Regions</option>
                <option value="4a">Region 4A (CALABARZON)</option>
                <option value="4b">Region 4B (MIMAROPA)</option>
                <option value="5">Region 5 (Bicol)</option>
            </select>
            <button id="addFirearm" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add New
            </button>
        </div>
    </div>

    <!-- Firearms Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand/Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Caliber</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Region</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location Surrendered</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surrendered By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="firearmsTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
            <div id="firearmsCount" class="text-sm text-gray-700">Loading data...</div>
            <button id="exportFirearms" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                <i class="fas fa-download mr-1"></i>Export Data
            </button>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables to store current firearms
        let firearms = [];
        let currentFirearm = null;

        // Initialize firearms data
        fetchFirearms();

        // Setup event listeners
        document.getElementById('addFirearm').addEventListener('click', function() {
            resetFirearmForm();
            document.getElementById('firearmModal').classList.remove('hidden');
            document.querySelector('#firearmModal h3').textContent = 'Add New Firearm Record';
        });

        document.getElementById('firearmsRegionFilter').addEventListener('change', function() {
            fetchFirearms();
        });

        document.getElementById('exportFirearms').addEventListener('click', function() {
            exportToCSV();
        });

        // Form submission
        document.getElementById('firearmForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveFirearm();
        });

        // Function to fetch firearms from the server
        function fetchFirearms() {
            const region = document.getElementById('firearmsRegionFilter').value;

            // Show loading state
            document.getElementById('firearmsTableBody').innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center">Loading data...</td></tr>';

            // Construct URL with query parameters
            let url = '{{ route("firearms.index") }}';
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
                    firearms = data.data;
                    renderFirearmsTable();
                } else {
                    console.error('Failed to fetch firearms:', data.message);
                    document.getElementById('firearmsTableBody').innerHTML =
                        '<tr><td colspan="7" class="px-6 py-4 text-center text-red-500">Failed to load data. Please try again.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching firearms:', error);
                document.getElementById('firearmsTableBody').innerHTML =
                    '<tr><td colspan="7" class="px-6 py-4 text-center text-red-500">Failed to load data. Please try again.</td></tr>';
            });
        }

        // Function to render firearms table
        function renderFirearmsTable() {
            const tableBody = document.getElementById('firearmsTableBody');
            tableBody.innerHTML = '';

            if (firearms.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center">No firearms found.</td></tr>';
                document.getElementById('firearmsCount').textContent = 'No results';
                return;
            }

            // Update the firearms count
            document.getElementById('firearmsCount').textContent = `${firearms.length} firearm(s) found`;

            // Render each firearm
            firearms.forEach(firearm => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${firearm.type}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${firearm.caliber}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${getRegionName(firearm.region)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${firearm.location}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${firearm.surrendered_by || 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(firearm.date)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        @if(auth()->user()->role !== 'user')
                        <button class="text-blue-600 hover:text-blue-900 edit-firearm" data-id="${firearm.id}">Edit</button>
                        @endif
                        <button class="text-red-600 hover:text-red-900 delete-firearm" data-id="${firearm.id}">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Add event listeners to the edit and delete buttons
            document.querySelectorAll('.edit-firearm').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    editFirearm(id);
                });
            });

            document.querySelectorAll('.delete-firearm').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    confirmDeleteFirearm(id);
                });
            });
        }

        // Function to edit a firearm
        function editFirearm(id) {
            currentFirearm = firearms.find(f => f.id == id);

            if (currentFirearm) {
                // Update modal title
                document.querySelector('#firearmModal h3').textContent = 'Edit Firearm Record';

                // Fill in the form fields
                document.getElementById('firearmRegion').value = currentFirearm.region;
                document.getElementById('firearmType').value = currentFirearm.type;
                document.getElementById('firearmCaliber').value = currentFirearm.caliber;
                document.getElementById('firearmDate').value = formatDateForInput(currentFirearm.date);
                document.getElementById('firearmLocation').value = currentFirearm.location;
                document.getElementById('firearmSurrenderedBy').value = currentFirearm.surrendered_by || '';
                document.getElementById('otherWeapons').value = currentFirearm.weapons || '';

                // Show the modal
                document.getElementById('firearmModal').classList.remove('hidden');
            }
        }

        // Function to confirm deletion of a firearm
        function confirmDeleteFirearm(id) {
            if (confirm('Are you sure you want to delete this firearm record? This action cannot be undone.')) {
                deleteFirearm(id);
            }
        }

        // Function to delete a firearm
        function deleteFirearm(id) {
            fetch(`{{ url('firearms') }}/${id}`, {
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
                    fetchFirearms(); // Refresh the list
                    alert('Firearm record deleted successfully.');
                } else {
                    alert('Failed to delete firearm record: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting firearm record:', error);
                alert('Failed to delete firearm record. Please try again.');
            });
        }

        // Function to save a firearm (create or update)
        function saveFirearm() {
            // Gather form data
            const formData = {
                region: document.getElementById('firearmRegion').value,
                type: document.getElementById('firearmType').value,
                caliber: document.getElementById('firearmCaliber').value,
                date: document.getElementById('firearmDate').value,
                location: document.getElementById('firearmLocation').value,
                surrendered_by: document.getElementById('firearmSurrenderedBy').value,
                weapons: document.getElementById('otherWeapons').value
            };

            // Determine if this is a create or update operation
            const method = currentFirearm ? 'PUT' : 'POST';
            const url = currentFirearm
                ? `{{ url('firearms') }}/${currentFirearm.id}`
                : '{{ route("firearms.store") }}';

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
                    document.getElementById('firearmModal').classList.add('hidden');
                    resetFirearmForm();
                    fetchFirearms(); // Refresh the list
                    alert(currentFirearm ? 'Firearm record updated successfully.' : 'Firearm record created successfully.');
                } else {
                    alert('Failed to save firearm record: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error saving firearm record:', error);
                alert('Failed to save firearm record. Please check your input and try again.');
            });
        }

        // Function to reset the firearm form
        function resetFirearmForm() {
            document.getElementById('firearmForm').reset();
            currentFirearm = null;
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
            if (firearms.length === 0) {
                alert('No data to export.');
                return;
            }

            // Define CSV headers
            const headers = [
                'Brand/Type',
                'Caliber',
                'Region',
                'Location Surrendered',
                'Surrendered By',
                'Date',
                'Other Weapons'
            ];

            // Convert data to CSV format
            let csvContent = headers.join(',') + '\n';

            firearms.forEach(firearm => {
                const row = [
                    `"${firearm.type.replace(/"/g, '""')}"`,
                    `"${firearm.caliber.replace(/"/g, '""')}"`,
                    `"${getRegionName(firearm.region).replace(/"/g, '""')}"`,
                    `"${firearm.location.replace(/"/g, '""')}"`,
                    `"${(firearm.surrendered_by || '').replace(/"/g, '""')}"`,
                    `"${formatDate(firearm.date)}"`,
                    `"${(firearm.weapons || '').replace(/"/g, '""')}"`
                ];
                csvContent += row.join(',') + '\n';
            });

            // Create a download link
            const encodedUri = encodeURI('data:text/csv;charset=utf-8,' + csvContent);
            const link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', `firearms-${new Date().toISOString().slice(0, 10)}.csv`);
            document.body.appendChild(link);

            // Trigger download and clean up
            link.click();
            document.body.removeChild(link);
        }
    });
</script>
