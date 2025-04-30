<!-- Surrendered Modal -->
<div id="surrenderedModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center p-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-blue-900">Add New Surrendered Individual</h3>
            <button id="closeSurrenderedModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="surrenderedForm" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="surrenderedName" class="block text-sm font-medium text-gray-700 required">Name</label>
                    <input type="text" id="surrenderedName" name="name" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="surrenderedAlias" class="block text-sm font-medium text-gray-700">Alias</label>
                    <input type="text" id="surrenderedAlias" name="alias"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="surrenderedGender" class="block text-sm font-medium text-gray-700 required">Gender</label>
                    <select id="surrenderedGender" name="gender" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div>
                    <label for="surrenderedDateOfBirth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <input type="date" id="surrenderedDateOfBirth" name="date_of_birth"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="surrenderedFormerGroup" class="block text-sm font-medium text-gray-700 required">Former Group</label>
                    <input type="text" id="surrenderedFormerGroup" name="former_group" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="surrenderedDate" class="block text-sm font-medium text-gray-700 required">Date Surrendered</label>
                    <input type="datetime-local" id="surrenderedDate" name="date_surrendered" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="surrenderedRegion" class="block text-sm font-medium text-gray-700 required">Region</label>
                    <select id="surrenderedRegion" name="region" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Region</option>
                        <option value="4a">Region 4A (CALABARZON)</option>
                        <option value="4b">Region 4B (MIMAROPA)</option>
                        <option value="5">Region 5 (Bicol)</option>
                    </select>
                </div>
                <div>
                    <label for="surrenderedProvince" class="block text-sm font-medium text-gray-700 required">Province</label>
                    <input type="text" id="surrenderedProvince" name="province" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="surrenderedMunicipality" class="block text-sm font-medium text-gray-700 required">Municipality</label>
                    <input type="text" id="surrenderedMunicipality" name="municipality" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="surrenderedBarangay" class="block text-sm font-medium text-gray-700 required">Barangay</label>
                    <input type="text" id="surrenderedBarangay" name="barangay" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="surrenderedStatus" class="block text-sm font-medium text-gray-700 required">Status</label>
                    <select id="surrenderedStatus" name="status" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="processing">Processing</option>
                        <option value="rehabilitation">Rehabilitation</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="surrenderedRemarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                    <textarea id="surrenderedRemarks" name="remarks" rows="3"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="md:col-span-1">
                    <label for="surrenderedPhoto" class="block text-sm font-medium text-gray-700">Photo</label>
                    <input type="file" id="surrenderedPhoto" name="photo" accept="image/*"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div class="mt-2">
                        <img id="surrenderedPhotoPreview" class="photo-preview hidden" alt="Surrendered Individual Photo Preview">
                    </div>
                </div>
                <div class="md:col-span-1">
                    <label for="surrenderedDocuments" class="block text-sm font-medium text-gray-700">Documents</label>
                    <input type="file" id="surrenderedDocuments" name="documents[]" multiple
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div id="surrenderedDocumentsContainer" class="mt-2 hidden">
                        <p class="text-sm text-gray-500">Current Documents:</p>
                        <ul id="surrenderedDocumentsList" class="mt-1 text-sm text-gray-600"></ul>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 text-right">
                <button type="button" id="cancelSurrendered" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 mr-2">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Record
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the surrenderedId variable
        window.surrenderedId = null;

        // Open modal
        document.getElementById('addSurrendered')?.addEventListener('click', function() {
            document.getElementById('surrenderedModal').classList.remove('hidden');
            // Reset form and clear surrenderedId when adding new
            document.getElementById('surrenderedForm').reset();
            document.getElementById('surrenderedPhotoPreview').classList.add('hidden');
            document.querySelector('#surrenderedModal h3').textContent = 'Add New Surrendered Individual';
            window.surrenderedId = null;
        });

        // Close modal
        document.getElementById('closeSurrenderedModal')?.addEventListener('click', function() {
            document.getElementById('surrenderedModal').classList.add('hidden');
        });

        document.getElementById('cancelSurrendered')?.addEventListener('click', function() {
            document.getElementById('surrenderedModal').classList.add('hidden');
        });

        // Photo preview handler
        document.getElementById('surrenderedPhoto')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('surrenderedPhotoPreview');
                    preview.src = event.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Form submission with AJAX
        document.getElementById('surrenderedForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const url = window.surrenderedId ? `/surrendered/${window.surrenderedId}` : '/surrendered';

            if (window.surrenderedId) {
                formData.append('_method', 'PUT'); // For method spoofing
            }

            // Add CSRF token
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            // Set loading state
            const submitBtn = this.querySelector('[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';

            fetch(url, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Server error');
                    });
                }
                return response.json();
            })
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Save Record';

                if (data.success) {
                    document.getElementById('surrenderedModal').classList.add('hidden');
                    // Reload surrendered list
                    if (window.loadSurrendereds) {
                        window.loadSurrendereds();
                    }
                    alert(window.surrenderedId ? 'Record updated successfully!' : 'Record saved successfully!');
                    // Reset form and ID
                    this.reset();
                    window.surrenderedId = null;
                    document.getElementById('surrenderedPhotoPreview').classList.add('hidden');
                } else {
                    alert('Error: ' + (data.message || 'Failed to save record'));
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Save Record';
                console.error('Error:', error);
                alert('An error occurred. Please try again: ' + error.message);
            });
        });

        // Function to load surrendered for editing
        window.editSurrendered = function(id) {
            window.surrenderedId = id;

            // Set form title
            document.querySelector('#surrenderedModal h3').textContent = 'Edit Surrendered Individual';

            // Fetch record details
            fetch(`/surrendered/${id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load record details');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const record = data.data;

                    // Fill form fields
                    document.getElementById('surrenderedName').value = record.name;
                    document.getElementById('surrenderedAlias').value = record.alias || '';
                    document.getElementById('surrenderedGender').value = record.gender;
                    document.getElementById('surrenderedDateOfBirth').value = record.date_of_birth ? formatDateForInput(record.date_of_birth) : '';
                    document.getElementById('surrenderedFormerGroup').value = record.former_group;
                    document.getElementById('surrenderedDate').value = formatDateTimeForInput(record.date_surrendered);
                    document.getElementById('surrenderedRegion').value = record.region;
                    document.getElementById('surrenderedProvince').value = record.province;
                    document.getElementById('surrenderedMunicipality').value = record.municipality;
                    document.getElementById('surrenderedBarangay').value = record.barangay;
                    document.getElementById('surrenderedStatus').value = record.status;
                    document.getElementById('surrenderedRemarks').value = record.remarks || '';

                    // Show photo if available
                    const photoPreview = document.getElementById('surrenderedPhotoPreview');
                    if (record.photo_path) {
                        photoPreview.src = `/storage/${record.photo_path}`;
                        photoPreview.classList.remove('hidden');
                    } else {
                        photoPreview.classList.add('hidden');
                    }

                    // Show documents if available
                    if (record.documents && record.documents.length > 0) {
                        const container = document.getElementById('surrenderedDocumentsContainer');
                        const list = document.getElementById('surrenderedDocumentsList');
                        container.classList.remove('hidden');
                        list.innerHTML = '';

                        record.documents.forEach(doc => {
                            const li = document.createElement('li');
                            li.className = 'flex justify-between items-center py-1';
                            li.innerHTML = `
                                <span><i class="fas fa-file-alt text-blue-500 mr-1"></i> ${doc.file_name}</span>
                                <div>
                                    <a href="/surrendered-documents/${doc.id}/download" class="text-blue-500 hover:underline mx-1">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button type="button" class="text-red-500 hover:text-red-700 mx-1 delete-document" data-id="${doc.id}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            `;
                            list.appendChild(li);
                        });

                        // Add event listeners for document delete
                        list.querySelectorAll('.delete-document').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const docId = this.getAttribute('data-id');
                                if (confirm('Are you sure you want to delete this document?')) {
                                    deleteDocument(docId);
                                }
                            });
                        });
                    } else {
                        document.getElementById('surrenderedDocumentsContainer').classList.add('hidden');
                    }

                    // Show modal
                    document.getElementById('surrenderedModal').classList.remove('hidden');
                } else {
                    alert('Error: ' + (data.message || 'Failed to load record details'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while loading record details. Please try again.');
            });
        };

        // Function to delete document
        function deleteDocument(id) {
            fetch(`/surrendered-documents/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Document deleted successfully');
                    // Refresh the form with updated document list
                    if (window.surrenderedId) {
                        window.editSurrendered(window.surrenderedId);
                    }
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete document'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the document. Please try again.');
            });
        }

        // Helper function to format date for input field
        function formatDateForInput(dateString) {
            const date = new Date(dateString);
            return date.toISOString().split('T')[0];
        }

        // Helper function to format datetime for input field
        function formatDateTimeForInput(dateTimeString) {
            const date = new Date(dateTimeString);
            return date.toISOString().slice(0, 16);
        }
    });
</script>
