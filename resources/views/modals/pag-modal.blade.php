<!-- PAG Modal -->
<div id="pagModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center p-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-blue-900">Add New PAG Member</h3>
            <button id="closePagModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="pagForm" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="pagName" class="block text-sm font-medium text-gray-700 required">Name</label>
                    <input type="text" id="pagName" name="name" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="pagRegion" class="block text-sm font-medium text-gray-700 required">Region</label>
                    <select id="pagRegion" name="region" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Region</option>
                        <option value="4a">Region 4A (CALABARZON)</option>
                        <option value="4b">Region 4B (MIMAROPA)</option>
                        <option value="5">Region 5 (Bicol)</option>
                    </select>
                </div>
                <div>
                    <label for="pagAddress" class="block text-sm font-medium text-gray-700 required">Address</label>
                    <input type="text" id="pagAddress" name="address" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="pagPob" class="block text-sm font-medium text-gray-700">Place of Birth</label>
                    <input type="text" id="pagPob" name="pob"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="pagDob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                    <input type="date" id="pagDob" name="dob"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="pagAffiliation" class="block text-sm font-medium text-gray-700 required">Affiliation</label>
                    <input type="text" id="pagAffiliation" name="affiliation" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="pagLastSeen" class="block text-sm font-medium text-gray-700">Last Seen</label>
                    <input type="datetime-local" id="pagLastSeen" name="last_seen"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="pagStatus" class="block text-sm font-medium text-gray-700 required">Status</label>
                    <select id="pagStatus" name="status" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="active">Active</option>
                        <option value="neutralized">Neutralized</option>
                        <option value="surrendered">Surrendered</option>
                        <option value="deceased">Deceased</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label for="pagPhoto" class="block text-sm font-medium text-gray-700">Photo</label>
                    <input type="file" id="pagPhoto" name="photo" accept="image/*"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div class="mt-2">
                        <img id="pagPhotoPreview" class="photo-preview hidden" alt="PAG Member Photo Preview">
                    </div>
                </div>
                <div class="md:col-span-1">
                    <label for="pagDocuments" class="block text-sm font-medium text-gray-700">Documents</label>
                    <input type="file" id="pagDocuments" name="documents[]" multiple
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div id="pagDocumentsContainer" class="mt-2 hidden">
                        <p class="text-sm text-gray-500">Current Documents:</p>
                        <ul id="pagDocumentsList" class="mt-1 text-sm text-gray-600"></ul>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 text-right">
                <button type="button" id="cancelPag" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 mr-2">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save PAG Record
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Open modal
        document.getElementById('addPag')?.addEventListener('click', function() {
            document.getElementById('pagModal').classList.remove('hidden');
        });

        // Close modal
        document.getElementById('closePagModal')?.addEventListener('click', function() {
            document.getElementById('pagModal').classList.add('hidden');
        });

        document.getElementById('cancelPag')?.addEventListener('click', function() {
            document.getElementById('pagModal').classList.add('hidden');
        });

        // Photo preview handler
        document.getElementById('pagPhoto')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('pagPhotoPreview');
                    preview.src = event.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Form submission with AJAX (to be implemented later)
        document.getElementById('pagForm')?.addEventListener('submit', function(e) {
            // For now, we'll just prevent default and show an alert
            e.preventDefault();
            alert('PAG record saved successfully!');
            document.getElementById('pagModal').classList.add('hidden');
            this.reset();
            document.getElementById('pagPhotoPreview')?.classList.add('hidden');
        });
    });
</script>
