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
            <button id="exportSurrenderedData" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 flex items-center">
                <i class="fas fa-download mr-2"></i>Export Data
            </button>
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
                                @if(auth()->user()->role !== 'user')
                                <button class="text-blue-600 hover:text-blue-900 mr-3 edit-btn" data-id="${item.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                @endif
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

        // Set up event listener for export button
        document.getElementById('exportSurrenderedData')?.addEventListener('click', function() {
            exportSurrenderedData();
        });

        // Function to export surrendered data
        function exportSurrenderedData() {
            // Show loading indicator
            const exportBtn = document.getElementById('exportSurrenderedData');
            const originalText = exportBtn.innerHTML;
            exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
            exportBtn.disabled = true;

            try {
                // Get visible table data
                const table = document.querySelector('#surrenderedTableBody').closest('table');
                const headers = [];
                const rows = [];

                // Extract headers (excluding Photo and Actions columns)
                const headerRow = table.querySelector('thead tr');
                headerRow.querySelectorAll('th').forEach(th => {
                    const headerText = th.textContent.trim();
                    if (headerText.toUpperCase() !== 'ACTIONS' && headerText.toUpperCase() !== 'PHOTO') {
                        headers.push(headerText);
                    }
                });

                // Extract visible row data
                table.querySelectorAll('tbody tr').forEach(row => {
                    if (row.style.display !== 'none') {
                        const rowData = [];
                        let cellIndex = 0;

                        row.querySelectorAll('td').forEach((cell, index) => {
                            // Skip photo column (first) and actions column (last)
                            if (index > 0 && index < row.querySelectorAll('td').length - 1) {
                                // For status column with span
                                if (cell.querySelector('span')) {
                                    rowData.push(cell.querySelector('span').textContent.trim());
                                } else {
                                    rowData.push(cell.textContent.trim());
                                }
                                cellIndex++;
                            }
                        });

                        if (cellIndex > 0) {
                            rows.push(rowData);
                        }
                    }
                });

                // Create CSV content
                let csvContent = "data:text/csv;charset=utf-8,";

                // Add header row
                csvContent += headers.join(',') + '\r\n';

                // Add data rows with proper CSV escaping
                rows.forEach(row => {
                    const formattedRow = row.map(cell => {
                        // Check if cell contains commas, quotes, or newlines
                        if (cell.includes(',') || cell.includes('"') || cell.includes('\n') || cell.includes('\r')) {
                            // Escape quotes by doubling them and wrap in quotes
                            return '"' + cell.replace(/"/g, '""') + '"';
                        }
                        return cell;
                    });
                    csvContent += formattedRow.join(',') + '\r\n';
                });

                // Create download link
                const regionFilter = document.getElementById('surrenderedRegionFilter').value;
                const regionText = regionFilter !== 'all'
                    ? document.getElementById('surrenderedRegionFilter').options[document.getElementById('surrenderedRegionFilter').selectedIndex].text
                    : 'All Regions';

                const encodedUri = encodeURI(csvContent);
                const date = new Date().toISOString().split('T')[0];
                const filename = `Surrendered_Data_${regionText.replace(/[^a-z0-9]/gi, '_')}_${date}.csv`;

                // Create and trigger download link
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", filename);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                console.log("CSV file exported successfully");
            } catch (error) {
                console.error("Error exporting CSV:", error);
                alert("Failed to export data: " + error.message);
            } finally {
                // Reset button state
                exportBtn.innerHTML = originalText;
                exportBtn.disabled = false;
            }
        }
    });
</script>
