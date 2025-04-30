<!-- Firearm Modal -->
<div id="firearmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center p-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-blue-900">Add New Firearm Record</h3>
            <button id="closeFirearmModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="firearmForm" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="firearmRegion" class="block text-sm font-medium text-gray-700 required">Region</label>
                    <select id="firearmRegion" name="region" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Region</option>
                        <option value="4a">Region 4A (CALABARZON)</option>
                        <option value="4b">Region 4B (MIMAROPA)</option>
                        <option value="5">Region 5 (Bicol)</option>
                    </select>
                </div>
                <div>
                    <label for="firearmType" class="block text-sm font-medium text-gray-700 required">Gun Brand/Type</label>
                    <input type="text" id="firearmType" name="type" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="firearmCaliber" class="block text-sm font-medium text-gray-700 required">Caliber</label>
                    <input type="text" id="firearmCaliber" name="caliber" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="firearmDate" class="block text-sm font-medium text-gray-700 required">Date & Time</label>
                    <input type="datetime-local" id="firearmDate" name="date" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="firearmLocation" class="block text-sm font-medium text-gray-700 required">Location Surrendered/Recovered</label>
                    <input type="text" id="firearmLocation" name="location" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="firearmSurrenderedBy" class="block text-sm font-medium text-gray-700">Surrendered By</label>
                    <input type="text" id="firearmSurrenderedBy" name="surrendered_by"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label for="otherWeapons" class="block text-sm font-medium text-gray-700">Other Weapon Types</label>
                    <textarea id="otherWeapons" name="weapons" rows="3"
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 text-right">
                <button type="button" id="cancelFirearm" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 mr-2">
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
        // Open modal
        document.getElementById('addFirearm')?.addEventListener('click', function() {
            document.getElementById('firearmModal').classList.remove('hidden');
        });

        // Close modal
        document.getElementById('closeFirearmModal')?.addEventListener('click', function() {
            document.getElementById('firearmModal').classList.add('hidden');
        });

        document.getElementById('cancelFirearm')?.addEventListener('click', function() {
            document.getElementById('firearmModal').classList.add('hidden');
        });

        // Form submission with AJAX (to be implemented later)
        document.getElementById('firearmForm')?.addEventListener('submit', function(e) {
            // For now, we'll just prevent default and show an alert
            e.preventDefault();
            alert('Firearm record saved successfully!');
            document.getElementById('firearmModal').classList.add('hidden');
            this.reset();
        });
    });
</script>
