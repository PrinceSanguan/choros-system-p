<!-- PAGs Section -->
<section id="pags" class="section-content p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-900">PAGs (Private Armed Groups)</h2>
        <div class="flex space-x-2">
            <select id="pagRegionFilter" class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all">All Regions</option>
                <option value="4a">Region 4A (CALABARZON)</option>
                <option value="4b">Region 4B (MIMAROPA)</option>
                <option value="5">Region 5 (Bicol)</option>
            </select>
            <button id="addPag" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add New
            </button>
        </div>
    </div>

    <!-- PAGs Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Region</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Affiliation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Seen</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="pagTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
            <div id="pagCount" class="text-sm text-gray-700">Loading data...</div>
            <button id="exportPags" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                <i class="fas fa-download mr-1"></i>Export Data
            </button>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables to store current PAGs
        let pags = [];
        let currentPag = null;

        // Initialize PAGs data
        fetchPags();

        // Setup event listeners
        document.getElementById('addPag').addEventListener('click', function() {
            resetPagForm();
            document.getElementById('pagModal').classList.remove('hidden');
            document.querySelector('#pagModal h3').textContent = 'Add New PAG Member';
        });

        document.getElementById('pagRegionFilter').addEventListener('change', function() {
            fetchPags();
        });

        document.getElementById('exportPags').addEventListener('click', function() {
            exportToCSV();
        });

        // Form submission
        document.getElementById('pagForm').addEventListener('submit', function(e) {
            e.preventDefault();
            savePag();
        });

        // Function to fetch PAGs from the server
        function fetchPags() {
            const region = document.getElementById('pagRegionFilter').value;

            // Show loading state
            document.getElementById('pagTableBody').innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center">Loading data...</td></tr>';

            // Construct URL with query parameters
            let url = '{{ route("pags.index") }}';
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
                    pags = data.data;
                    renderPagsTable();
                } else {
                    console.error('Failed to fetch PAGs:', data.message);
                    document.getElementById('pagTableBody').innerHTML =
                        '<tr><td colspan="7" class="px-6 py-4 text-center text-red-500">Failed to load data. Please try again.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching PAGs:', error);
                document.getElementById('pagTableBody').innerHTML =
                    '<tr><td colspan="7" class="px-6 py-4 text-center text-red-500">Failed to load data. Please try again.</td></tr>';
            });
        }

        // Function to render PAGs table
        function renderPagsTable() {
            const tableBody = document.getElementById('pagTableBody');
            tableBody.innerHTML = '';

            if (pags.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center">No PAGs found.</td></tr>';
                document.getElementById('pagCount').textContent = 'No results';
                return;
            }

            // Update the PAGs count
            document.getElementById('pagCount').textContent = `${pags.length} PAG member(s) found`;

            // Render each PAG
            pags.forEach(pag => {
                const row = document.createElement('tr');
                const statusClass = getStatusClass(pag.status);

                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${pag.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${getRegionName(pag.region)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${pag.address}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${pag.affiliation}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${pag.last_seen ? formatDate(pag.last_seen) : 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            ${capitalizeFirstLetter(pag.status)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-blue-600 hover:text-blue-900 edit-pag" data-id="${pag.id}">Edit</button>
                        <button class="text-red-600 hover:text-red-900 delete-pag" data-id="${pag.id}">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Add event listeners to the edit and delete buttons
            document.querySelectorAll('.edit-pag').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    editPag(id);
                });
            });

            document.querySelectorAll('.delete-pag').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    confirmDeletePag(id);
                });
            });
        }

        // Function to get status class for styling
        function getStatusClass(status) {
            switch(status) {
                case 'active': return 'bg-green-100 text-green-800';
                case 'neutralized': return 'bg-red-100 text-red-800';
                case 'surrendered': return 'bg-yellow-100 text-yellow-800';
                case 'deceased': return 'bg-gray-100 text-gray-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        // Function to capitalize first letter
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Function to edit a PAG
        function editPag(id) {
            currentPag = pags.find(p => p.id == id);

            if (currentPag) {
                // Update modal title
                document.querySelector('#pagModal h3').textContent = 'Edit PAG Member';

                // Fill in the form fields
                document.getElementById('pagName').value = currentPag.name;
                document.getElementById('pagRegion').value = currentPag.region;
                document.getElementById('pagAddress').value = currentPag.address;
                document.getElementById('pagPob').value = currentPag.pob || '';
                document.getElementById('pagDob').value = currentPag.dob ? formatDateForInput(currentPag.dob, true) : '';
                document.getElementById('pagAffiliation').value = currentPag.affiliation;
                document.getElementById('pagLastSeen').value = currentPag.last_seen ? formatDateForInput(currentPag.last_seen) : '';
                document.getElementById('pagStatus').value = currentPag.status;

                // Show current photo if exists
                const photoPreview = document.getElementById('pagPhotoPreview');
                if (currentPag.photo_path) {
                    photoPreview.src = `/storage/${currentPag.photo_path}`;
                    photoPreview.classList.remove('hidden');
                } else {
                    photoPreview.classList.add('hidden');
                }

                // Show current documents if exist
                const documentsContainer = document.getElementById('pagDocumentsContainer');
                const documentsList = document.getElementById('pagDocumentsList');

                if (documentsContainer && documentsList) {
                    if (currentPag.documents && currentPag.documents.length > 0) {
                        documentsContainer.classList.remove('hidden');
                        documentsList.innerHTML = '';

                        currentPag.documents.forEach(doc => {
                            const li = document.createElement('li');
                            li.className = 'flex items-center py-1';
                            li.innerHTML = `
                                <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                                <a href="/pag-documents/${doc.id}/download" target="_blank" class="text-blue-600 hover:underline mr-2">
                                    ${doc.file_name || doc.file_path.split('/').pop()}
                                </a>
                                <button type="button" class="delete-document text-red-500 hover:text-red-700" data-id="${doc.id}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            `;
                            documentsList.appendChild(li);
                        });

                        // Add event listeners for document deletion
                        documentsList.querySelectorAll('.delete-document').forEach(button => {
                            button.addEventListener('click', function() {
                                const docId = this.getAttribute('data-id');
                                if (confirm('Are you sure you want to delete this document? This action cannot be undone.')) {
                                    deleteDocument(docId);
                                }
                            });
                        });
                    } else {
                        documentsContainer.classList.add('hidden');
                    }
                }

                // Show the modal
                document.getElementById('pagModal').classList.remove('hidden');
            }
        }

        // Function to confirm deletion of a PAG
        function confirmDeletePag(id) {
            if (confirm('Are you sure you want to delete this PAG member? This action cannot be undone.')) {
                deletePag(id);
            }
        }

        // Function to delete a PAG
        function deletePag(id) {
            fetch(`{{ url('pags') }}/${id}`, {
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
                    fetchPags(); // Refresh the list
                    alert('PAG member deleted successfully.');
                } else {
                    alert('Failed to delete PAG member: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting PAG member:', error);
                alert('Failed to delete PAG member. Please try again.');
            });
        }

        // Function to save a PAG (create or update)
        function savePag() {
            // Create FormData object from form
            const form = document.getElementById('pagForm');
            const formData = new FormData(form);

            // Add the ID to formData if we're updating
            if (currentPag) {
                formData.append('_method', 'PUT'); // For method spoofing
            }

            // Determine the URL based on whether we're creating or updating
            const url = currentPag
                ? `{{ url('pags') }}/${currentPag.id}`
                : '{{ route("pags.store") }}';

            // Set loading state
            form.classList.add('opacity-50', 'pointer-events-none');

            // Send request to server
            fetch(url, {
                method: 'POST', // Always POST for FormData, use _method for spoofing
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                form.classList.remove('opacity-50', 'pointer-events-none');

                if (data.success) {
                    document.getElementById('pagModal').classList.add('hidden');
                    resetPagForm();
                    fetchPags(); // Refresh the list
                    alert(currentPag ? 'PAG member updated successfully.' : 'PAG member created successfully.');
                } else {
                    alert('Failed to save PAG member: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error saving PAG member:', error);
                alert('Failed to save PAG member. Please check your input and try again.');
                form.classList.remove('opacity-50', 'pointer-events-none');
            });
        }

        // Function to reset the PAG form
        function resetPagForm() {
            document.getElementById('pagForm').reset();
            document.getElementById('pagPhotoPreview')?.classList.add('hidden');
            currentPag = null;
        }

        // Function to format date for display
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        // Function to format date for input field
        function formatDateForInput(dateString, dateOnly = false) {
            const date = new Date(dateString);
            return dateOnly
                ? date.toISOString().slice(0, 10) // Format: YYYY-MM-DD
                : date.toISOString().slice(0, 16); // Format: YYYY-MM-DDThh:mm
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
            if (pags.length === 0) {
                alert('No data to export.');
                return;
            }

            // Define CSV headers
            const headers = [
                'Name',
                'Region',
                'Address',
                'Affiliation',
                'Last Seen',
                'Status'
            ];

            // Convert data to CSV format
            let csvContent = headers.join(',') + '\n';

            pags.forEach(pag => {
                const row = [
                    `"${pag.name.replace(/"/g, '""')}"`,
                    `"${getRegionName(pag.region).replace(/"/g, '""')}"`,
                    `"${pag.address.replace(/"/g, '""')}"`,
                    `"${pag.affiliation.replace(/"/g, '""')}"`,
                    `"${pag.last_seen ? formatDate(pag.last_seen) : 'N/A'}"`,
                    `"${capitalizeFirstLetter(pag.status)}"`
                ];
                csvContent += row.join(',') + '\n';
            });

            // Create a download link
            const encodedUri = encodeURI('data:text/csv;charset=utf-8,' + csvContent);
            const link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', `pags-${new Date().toISOString().slice(0, 10)}.csv`);
            document.body.appendChild(link);

            // Trigger download and clean up
            link.click();
            document.body.removeChild(link);
        }

        // Function to delete a document
        function deleteDocument(docId) {
            fetch(`{{ url('pag-documents') }}/${docId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Document deleted successfully.');
                    // Refresh the current PAG data to update the documents list
                    if (currentPag) {
                        fetch(`{{ url('pags') }}/${currentPag.id}`, {
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
                                // Update the current PAG data
                                currentPag = data.data;
                                // Refresh the documents display
                                const documentsContainer = document.getElementById('pagDocumentsContainer');
                                const documentsList = document.getElementById('pagDocumentsList');

                                if (documentsContainer && documentsList) {
                                    if (currentPag.documents && currentPag.documents.length > 0) {
                                        documentsContainer.classList.remove('hidden');
                                        documentsList.innerHTML = '';

                                        currentPag.documents.forEach(doc => {
                                            const li = document.createElement('li');
                                            li.className = 'flex items-center py-1';
                                            li.innerHTML = `
                                                <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                                                <a href="/pag-documents/${doc.id}/download" target="_blank" class="text-blue-600 hover:underline mr-2">
                                                    ${doc.file_name || doc.file_path.split('/').pop()}
                                                </a>
                                                <button type="button" class="delete-document text-red-500 hover:text-red-700" data-id="${doc.id}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            `;
                                            documentsList.appendChild(li);
                                        });

                                        // Add event listeners for document deletion
                                        documentsList.querySelectorAll('.delete-document').forEach(button => {
                                            button.addEventListener('click', function() {
                                                const docId = this.getAttribute('data-id');
                                                if (confirm('Are you sure you want to delete this document? This action cannot be undone.')) {
                                                    deleteDocument(docId);
                                                }
                                            });
                                        });
                                    } else {
                                        documentsContainer.classList.add('hidden');
                                    }
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching updated PAG data:', error);
                        });
                    }
                } else {
                    alert('Failed to delete document: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting document:', error);
                alert('Failed to delete document. Please try again.');
            });
        }
    });
</script>
