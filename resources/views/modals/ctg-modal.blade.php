<!-- CTG Modal -->
<div id="ctgModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Add CTG Member</h3>
                <button id="closeCtgModal" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <form id="ctgForm" enctype="multipart/form-data">
                <input type="hidden" id="ctgId" name="id" value="">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="ctgName" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" id="ctgName" name="name" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="ctgRegion" class="block text-sm font-medium text-gray-700 mb-1">Region *</label>
                        <select id="ctgRegion" name="region" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select Region</option>
                            <option value="4a">Region 4A (CALABARZON)</option>
                            <option value="4b">Region 4B (MIMAROPA)</option>
                            <option value="5">Region 5 (Bicol)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="ctgAddress" class="block text-sm font-medium text-gray-700 mb-1">Last Known Address</label>
                        <input type="text" id="ctgAddress" name="address" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="ctgPob" class="block text-sm font-medium text-gray-700 mb-1">Place of Birth</label>
                        <input type="text" id="ctgPob" name="pob" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="ctgDob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" id="ctgDob" name="dob" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label for="ctgAffiliatedFront" class="block text-sm font-medium text-gray-700 mb-1">Affiliated Front</label>
                        <input type="text" id="ctgAffiliatedFront" name="affiliated_front" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="ctgLastSeen" class="block text-sm font-medium text-gray-700 mb-1">Last Seen Date/Time</label>
                        <input type="datetime-local" id="ctgLastSeen" name="last_seen" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="ctgStatus" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select id="ctgStatus" name="status" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select Status</option>
                            <option value="active">Active</option>
                            <option value="neutralized">Neutralized</option>
                            <option value="surrendered">Surrendered</option>
                            <option value="deceased">Deceased</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="ctgPhoto" class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                        <input type="file" id="ctgPhoto" name="photo" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" accept="image/*">
                        <img id="ctgPhotoPreview" class="mt-2 hidden max-h-32 object-contain" alt="Photo preview">
                    </div>

                    <div class="md:col-span-2">
                        <label for="ctgDocument" class="block text-sm font-medium text-gray-700 mb-1">Documents (PDF/DOCX)</label>
                        <input type="file" id="ctgDocument" name="document" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" accept=".pdf,.docx">
                        <div id="ctgDocumentInfo" class="mt-2 hidden">
                            <p class="text-sm text-gray-600">Current document: <span id="documentName" class="font-medium"></span></p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" id="cancelCtgModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 focus:outline-none">
                        Cancel
                    </button>
                    <button type="submit" id="saveCtg" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none flex items-center">
                        <i class="fas fa-save mr-2"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Open modal when Add New button is clicked
        document.getElementById('addCtg')?.addEventListener('click', function() {
            document.getElementById('ctgForm').reset();
            document.getElementById('ctgId').value = '';
            document.getElementById('ctgPhotoPreview').classList.add('hidden');
            document.getElementById('ctgDocumentInfo').classList.add('hidden');
            document.querySelector('#ctgModal h3').textContent = 'Add CTG Member';
            document.getElementById('ctgModal').classList.remove('hidden');
        });

        // Close modal
        document.getElementById('closeCtgModal')?.addEventListener('click', function() {
            document.getElementById('ctgModal').classList.add('hidden');
        });

        document.getElementById('cancelCtgModal')?.addEventListener('click', function() {
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

        // Document file handler
        document.getElementById('ctgDocument')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('documentName').textContent = file.name;
                document.getElementById('ctgDocumentInfo').classList.remove('hidden');
            } else {
                document.getElementById('ctgDocumentInfo').classList.add('hidden');
            }
        });

        // Form submission
        document.getElementById('ctgForm')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const isEdit = document.getElementById('ctgId').value !== '';
            const method = isEdit ? 'PUT' : 'POST';
            const url = isEdit ? `/ctgs/${document.getElementById('ctgId').value}` : '/ctgs';

            // If it's an edit operation, we need to use the _method field for Laravel
            if (isEdit) {
                formData.append('_method', 'PUT');
            }

            fetch(url, {
                method: 'POST', // Always POST for FormData
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(isEdit ? 'CTG record updated successfully.' : 'CTG record created successfully.');
                    document.getElementById('ctgModal').classList.add('hidden');
                    if (typeof window.loadCtgs === 'function') {
                        window.loadCtgs(); // Reload the table
                    }
                } else {
                    alert('Failed to save CTG record: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the CTG record.');
            });
        });
    });
</script>
