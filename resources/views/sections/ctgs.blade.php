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
            <button id="exportCtgData" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center">
                <i class="fas fa-download mr-2"></i>Export Data
            </button>
            <button id="addCtg" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Add New
            </button>
        </div>
    </div>

    <!-- CTGs Table -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="flex justify-between items-center px-4 py-2 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700">CTG Records</h3>
            <div class="text-xs">
                {{ now()->format('Y-m-d H:i') }} | <span id="rowCount">0</span> records
            </div>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
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
        // Make sure renderCtgTable is defined in the global scope
        window.renderCtgTable = function(ctgData) {
            const tableBody = document.getElementById('ctgTableBody');
            if (!tableBody) {
                console.error('Table body element not found');
                return;
            }

            console.log('Starting to render table with data:', ctgData);

            // Clear existing table data
            tableBody.innerHTML = '';

            if (!Array.isArray(ctgData)) {
                console.error('Data is not an array:', ctgData);
                return;
            }

            if (ctgData.length === 0) {
                console.warn('Empty data array received');
                document.getElementById('ctgTableEmpty')?.classList.remove('hidden');
                return;
            }

            ctgData.forEach((ctg, index) => {
                try {
                    console.log(`Processing record ${index}:`, ctg);
                const row = document.createElement('tr');

                // Format date
                const formatDate = (dateString) => {
                    if (!dateString) return 'N/A';
                        try {
                    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                    return new Date(dateString).toLocaleDateString(undefined, options);
                        } catch (e) {
                            console.error('Date formatting error:', e);
                            return dateString || 'N/A';
                        }
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
                            default: return region || 'Unknown Region';
                    }
                };

                // Document link
                const documentLink = ctg.document_path
                    ? `<a href="/storage/${ctg.document_path}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                          <i class="fas fa-file-alt mr-1"></i>${ctg.document_path.split('/').pop()}
                       </a>`
                    : 'None';

                row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${ctg.name || 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${getRegionName(ctg.region)}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">${ctg.address || 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${ctg.affiliated_front || 'N/A'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(ctg.last_seen)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusBadgeClass(ctg.status)}">
                                ${ctg.status ? ctg.status.charAt(0).toUpperCase() + ctg.status.slice(1) : 'Unknown'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${documentLink}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        @if(auth()->user()->role !== 'user')
                        <button class="text-blue-600 hover:text-blue-900 edit-ctg" data-id="${ctg.id}">Edit</button>
                        @endif
                        <button class="text-red-600 hover:text-red-900 delete-ctg" data-id="${ctg.id}">Delete</button>
                    </td>
                `;

                tableBody.appendChild(row);
                } catch (error) {
                    console.error(`Error rendering row ${index}:`, error, ctg);
                }
            });

            // Add event listeners to the edit and delete buttons
            document.querySelectorAll('.edit-ctg').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    handleEditCtg(id);
                });
            });

            document.querySelectorAll('.delete-ctg').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    deleteCtg(id);
                });
            });

            console.log('Table rendering completed');
        };

        // Function to get sample data when server fails
        window.getSampleData = function() {
            return [
                {
                    id: 1,
                    name: 'Jose Santos',
                    region: '4a',
                    address: 'Unknown, possibly Cavite',
                    pob: 'Trece Martires City',
                    dob: '1985-03-15',
                    affiliated_front: 'NPA - Southern Tagalog',
                    last_seen: '2023-11-16 14:30:00',
                    status: 'active',
                    photo_path: null,
                    document_path: null,
                },
                {
                    id: 2,
                    name: 'Maria Reyes',
                    region: '4b',
                    address: 'Rural Occidental Mindoro',
                    pob: 'San Jose, Occidental Mindoro',
                    dob: '1990-07-22',
                    affiliated_front: 'NPA - Mindoro Command',
                    last_seen: '2023-12-22 19:45:00',
                    status: 'active',
                    photo_path: null,
                    document_path: null,
                },
                {
                    id: 3,
                    name: 'Pedro Bicol',
                    region: '5',
                    address: 'Rural Sorsogon',
                    pob: 'Bulan, Sorsogon',
                    dob: '1982-11-05',
                    affiliated_front: 'NPA - Bicol Regional Party Committee',
                    last_seen: '2024-01-27 11:20:00',
                    status: 'active',
                    photo_path: null,
                    document_path: 'documents/bicol-report.pdf',
                },
                {
                    id: 4,
                    name: 'Antonio Mendoza',
                    region: '4a',
                    address: 'Batangas province',
                    pob: 'Lipa City',
                    dob: '1988-05-10',
                    affiliated_front: 'NPA - Southern Tagalog',
                    last_seen: '2023-12-10 08:45:00',
                    status: 'neutralized',
                    photo_path: null,
                    document_path: null,
                },
                {
                    id: 5,
                    name: 'Elena Castro',
                    region: '4b',
                    address: 'Eastern Mindoro',
                    pob: 'Puerto Galera',
                    dob: '1992-09-18',
                    affiliated_front: 'NPA - Mindoro Command',
                    last_seen: '2023-10-05 16:30:00',
                    status: 'surrendered',
                    photo_path: null,
                    document_path: 'documents/castro-statement.docx',
                }
            ];
        };

        // Load CTGs if the current section is active
        if (document.getElementById('ctgs')?.classList.contains('active') && typeof window.loadCtgs === 'function') {
            // Call loadCtgs function if it exists and the CTGs section is active
            window.loadCtgs();
        }

        // Set up event listeners
        document.getElementById('ctgRegionFilter')?.addEventListener('change', function() {
            if (typeof window.loadCtgs === 'function') {
                window.loadCtgs();
            }
        });

        document.getElementById('exportCtgData')?.addEventListener('click', function() {
            exportCtgData();
        });

        // Function to handle exporting CTG data
        function exportCtgData() {
            // Show loading indicator
            const exportBtn = document.getElementById('exportCtgData');
            if (!exportBtn) return;

            const originalText = exportBtn.innerHTML;
            exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
            exportBtn.disabled = true;

            const regionFilter = document.getElementById('ctgRegionFilter')?.value || 'all';

            // Use the export endpoint
            let url = '/ctgs/export';
            if (regionFilter !== 'all') {
                url += `?region=${regionFilter}`;
            }

            // Create a temporary link and trigger download
            const link = document.createElement('a');
            link.href = url;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Reset button state
            setTimeout(() => {
                exportBtn.innerHTML = originalText;
                exportBtn.disabled = false;
            }, 1000);
        }

        // Function to delete a CTG
        window.deleteCtg = function(id) {
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
                        if (typeof window.loadCtgs === 'function') {
                            window.loadCtgs(); // Reload the table
                        }
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
        };

        // Function to handle editing a CTG
        window.handleEditCtg = function(id) {
            console.log('Editing CTG with ID:', id);

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
                console.error('Error retrieving CTG data:', error);
                alert('Failed to retrieve CTG data for editing: ' + error.message);
            });
        };

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

            // Show document info if available
            const documentInfo = document.getElementById('ctgDocumentInfo');
            const documentName = document.getElementById('documentName');
            if (documentInfo && documentName) {
                if (ctg.document_path) {
                    documentName.textContent = ctg.document_path.split('/').pop();
                    documentInfo.classList.remove('hidden');
                } else {
                    documentInfo.classList.add('hidden');
                }
            }

            // Show the modal
            modal.classList.remove('hidden');
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
    });
</script>
