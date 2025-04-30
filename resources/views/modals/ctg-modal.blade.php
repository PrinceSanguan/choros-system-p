<!-- CTG Modal -->
<div id="ctgModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center p-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-blue-900">Add New CTG Member</h3>
            <button id="closeCtgModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="ctgForm" class="p-6" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="ctgId" name="ctg_id" value="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="ctgName" class="block text-sm font-medium text-gray-700 required">Name</label>
                    <input type="text" id="ctgName" name="name" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="ctgRegion" class="block text-sm font-medium text-gray-700 required">Region</label>
                    <select id="ctgRegion" name="region" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Region</option>
                        <option value="4a">Region 4A (CALABARZON)</option>
                        <option value="4b">Region 4B (MIMAROPA)</option>
                        <option value="5">Region 5 (Bicol)</option>
                    </select>
                </div>
                <div>
                    <label for="ctgAddress" class="block text-sm font-medium text-gray-700 required">Address</label>
                    <input type="text" id="ctgAddress" name="address" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="ctgPob" class="block text-sm font-medium text-gray-700">Place of Birth</label>
                    <input type="text" id="ctgPob" name="pob"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="ctgDob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <input type="date" id="ctgDob" name="dob"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="ctgAffiliatedFront" class="block text-sm font-medium text-gray-700 required">Affiliated Front/Group</label>
                    <input type="text" id="ctgAffiliatedFront" name="affiliated_front" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="ctgLastSeen" class="block text-sm font-medium text-gray-700">Last Seen</label>
                    <input type="datetime-local" id="ctgLastSeen" name="last_seen"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="ctgStatus" class="block text-sm font-medium text-gray-700 required">Status</label>
                    <select id="ctgStatus" name="status" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="active">Active</option>
                        <option value="neutralized">Neutralized</option>
                        <option value="surrendered">Surrendered</option>
                        <option value="deceased">Deceased</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label for="ctgPhoto" class="block text-sm font-medium text-gray-700">Photo</label>
                    <input type="file" id="ctgPhoto" name="photo" accept="image/*"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div class="mt-2">
                        <img id="ctgPhotoPreview" class="photo-preview hidden max-h-40 max-w-full object-contain" alt="CTG Member Photo Preview">
                    </div>
                </div>
                <div class="md:col-span-1">
                    <label for="ctgDocuments" class="block text-sm font-medium text-gray-700">Documents</label>
                    <input type="file" id="ctgDocuments" name="documents[]" multiple
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div id="ctgDocumentsContainer" class="mt-2 hidden">
                        <p class="text-sm text-gray-500">Current Documents:</p>
                        <ul id="ctgDocumentsList" class="mt-1 text-sm text-gray-600"></ul>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 text-right">
                <button type="button" id="cancelCtg" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 mr-2">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save CTG Record
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Global variable to track if we're in edit mode
        window.ctgEditId = null;

        // Open modal for adding new
        document.getElementById('addCtg')?.addEventListener('click', function() {
            resetCtgForm();
            document.getElementById('ctgModal').classList.remove('hidden');
        });

        // Close modal
        document.getElementById('closeCtgModal')?.addEventListener('click', function() {
            document.getElementById('ctgModal').classList.add('hidden');
        });

        document.getElementById('cancelCtg')?.addEventListener('click', function() {
            document.getElementById('ctgModal').classList.add('hidden');
        });

        // Photo preview handler
        document.getElementById('ctgPhoto')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('ctgPhotoPreview');
                    preview.src = event.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Form submission with AJAX
        document.getElementById('ctgForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            let url = '';
            let method = 'POST';

            if (window.ctgEditId) {
                url = `/ctgs/${window.ctgEditId}`;
                method = 'POST';
                formData.append('_method', 'PUT'); // For method spoofing
            } else {
                url = '/ctgs';
            }

            // Add CSRF token
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content);

            // Set loading state
            const submitBtn = this.querySelector('[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';

            fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Save CTG Record';

                if (data.success) {
                    document.getElementById('ctgModal').classList.add('hidden');
                    alert(window.ctgEditId ? 'CTG record updated successfully!' : 'CTG record saved successfully!');

                    // Reset form and edit ID
                    resetCtgForm();

                    // Reload the CTG table if it exists
                    if (typeof loadCtgs === 'function') {
                        loadCtgs();
                    } else if (typeof populateCTGsTable === 'function') {
                        populateCTGsTable();
                    } else {
                        // Otherwise, reload the page
                        window.location.reload();
                    }
                } else {
                    alert('Error: ' + (data.message || 'Failed to save record'));
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Save CTG Record';
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });

        // Function to edit CTG (to be called from the CTG table)
        window.editCtg = function(id) {
            window.ctgEditId = id;

            // Set form title
            document.querySelector('#ctgModal h3').textContent = 'Edit CTG Member';

            // Fetch CTG details
            fetch(`/ctgs/${id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const ctg = data.data;

                    // Fill form fields
                    document.getElementById('ctgId').value = ctg.id;
                    document.getElementById('ctgName').value = ctg.name;
                    document.getElementById('ctgRegion').value = ctg.region;
                    document.getElementById('ctgAddress').value = ctg.address;
                    document.getElementById('ctgPob').value = ctg.pob || '';
                    document.getElementById('ctgDob').value = ctg.dob ? formatDateForInput(ctg.dob) : '';
                    document.getElementById('ctgAffiliatedFront').value = ctg.affiliated_front;
                    document.getElementById('ctgLastSeen').value = ctg.last_seen ? formatDateTimeForInput(ctg.last_seen) : '';
                    document.getElementById('ctgStatus').value = ctg.status;

                    // Show photo if available
                    const photoPreview = document.getElementById('ctgPhotoPreview');
                    if (ctg.photo_path) {
                        photoPreview.src = `/storage/${ctg.photo_path}`;
                        photoPreview.classList.remove('hidden');
                    } else {
                        photoPreview.classList.add('hidden');
                    }

                    // Show documents if available
                    const docContainer = document.getElementById('ctgDocumentsContainer');
                    const docList = document.getElementById('ctgDocumentsList');

                    if (ctg.documents && ctg.documents.length > 0) {
                        docContainer.classList.remove('hidden');
                        docList.innerHTML = '';

                        ctg.documents.forEach(doc => {
                            const li = document.createElement('li');
                            li.className = 'flex justify-between items-center py-1';
                            li.innerHTML = `
                                <span><i class="fas fa-file-alt text-blue-500 mr-1"></i> ${doc.file_name || doc.file_path.split('/').pop()}</span>
                                <div>
                                    <a href="/ctg-documents/${doc.id}/download" class="text-blue-500 hover:underline mx-1" target="_blank">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button type="button" class="text-red-500 hover:text-red-700 mx-1 delete-document" data-id="${doc.id}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            `;
                            docList.appendChild(li);
                        });

                        // Add event listeners for document deletion
                        docList.querySelectorAll('.delete-document').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const docId = this.getAttribute('data-id');
                                deleteCtgDocument(docId);
                            });
                        });
                    } else {
                        docContainer.classList.add('hidden');
                    }

                    // Show modal
                    document.getElementById('ctgModal').classList.remove('hidden');
                } else {
                    alert('Error: ' + (data.message || 'Failed to load CTG details'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while loading CTG details. Please try again.');
            });
        };

        // Function to delete CTG document
        function deleteCtgDocument(id) {
            if (confirm('Are you sure you want to delete this document? This action cannot be undone.')) {
                fetch(`/ctg-documents/${id}`, {
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
                        alert('Document deleted successfully');
                        // Refresh the documents list
                        if (window.ctgEditId) {
                            window.editCtg(window.ctgEditId);
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
        }

        // Function to reset form
        function resetCtgForm() {
            document.getElementById('ctgForm').reset();
            document.getElementById('ctgPhotoPreview').classList.add('hidden');
            document.getElementById('ctgDocumentsContainer').classList.add('hidden');
            document.querySelector('#ctgModal h3').textContent = 'Add New CTG Member';
            window.ctgEditId = null;
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
