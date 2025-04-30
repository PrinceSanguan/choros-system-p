<!-- Sighting Modal -->
<div id="sightingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center p-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-blue-900">Add New Sighting</h3>
            <button id="closeSightingModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="sightingForm" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="sightingRegion" class="block text-sm font-medium text-gray-700 required">Region</label>
                    <select id="sightingRegion" name="region" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Region</option>
                        <option value="4a">Region 4A (CALABARZON)</option>
                        <option value="4b">Region 4B (MIMAROPA)</option>
                        <option value="5">Region 5 (Bicol)</option>
                    </select>
                </div>
                <div>
                    <label for="sightingDate" class="block text-sm font-medium text-gray-700 required">Date & Time</label>
                    <input type="datetime-local" id="sightingDate" name="date" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="sightingLocation" class="block text-sm font-medium text-gray-700 required">Location</label>
                    <input type="text" id="sightingLocation" name="location" required
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="sightingCoordinates" class="block text-sm font-medium text-gray-700">Coordinates (Latitude, Longitude)</label>
                    <div class="flex space-x-2">
                        <input type="text" id="sightingLatitude" name="latitude" placeholder="Latitude"
                               class="mt-1 block w-1/2 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <input type="text" id="sightingLongitude" name="longitude" placeholder="Longitude"
                               class="mt-1 block w-1/2 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label for="sightingDescription" class="block text-sm font-medium text-gray-700 required">Description</label>
                    <textarea id="sightingDescription" name="description" rows="4" required
                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 text-right">
                <button type="button" id="cancelSighting" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 mr-2">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save Sighting
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Open modal
        document.getElementById('addSighting').addEventListener('click', function() {
            document.getElementById('sightingModal').classList.remove('hidden');
        });

        // Close modal
        document.getElementById('closeSightingModal').addEventListener('click', function() {
            document.getElementById('sightingModal').classList.add('hidden');
        });

        document.getElementById('cancelSighting').addEventListener('click', function() {
            document.getElementById('sightingModal').classList.add('hidden');
        });

        // Form submission with AJAX (to be implemented later)
        document.getElementById('sightingForm').addEventListener('submit', function(e) {
            // For now, we'll just prevent default and show an alert
            e.preventDefault();
            alert('Sighting saved successfully!');
            document.getElementById('sightingModal').classList.add('hidden');
            this.reset();
        });
    });
</script>
