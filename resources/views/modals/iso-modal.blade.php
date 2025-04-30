<!-- ISO Operation Modal -->
<div id="isoModal" class="fixed inset-0 hidden bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl">
        <div class="px-6 py-4 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Add New ISO Operation</h3>
                <button type="button" id="closeIsoModal" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <form id="isoOperationForm">
            <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Operation Name -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="isoOperationName" class="block text-sm font-medium text-gray-700">Operation Name</label>
                        <input type="text" id="isoOperationName" name="name" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Region -->
                    <div>
                        <label for="isoRegion" class="block text-sm font-medium text-gray-700">Region</label>
                        <select id="isoRegion" name="region" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Region</option>
                            <option value="4a">Region 4A (CALABARZON)</option>
                            <option value="4b">Region 4B (MIMAROPA)</option>
                            <option value="5">Region 5 (Bicol)</option>
                        </select>
                    </div>

                    <!-- Date & Time -->
                    <div>
                        <label for="isoDate" class="block text-sm font-medium text-gray-700">Date & Time</label>
                        <input type="datetime-local" id="isoDate" name="date" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Team Leader -->
                    <div>
                        <label for="isoTeamLeader" class="block text-sm font-medium text-gray-700">Team Leader</label>
                        <input type="text" id="isoTeamLeader" name="team_leader" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Team Members -->
                    <div>
                        <label for="isoTeamMembers" class="block text-sm font-medium text-gray-700">Team Members</label>
                        <textarea id="isoTeamMembers" name="members" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter team members, separated by commas"></textarea>
                    </div>

                    <!-- Location -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="isoLocation" class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" id="isoLocation" name="location" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Coordinates -->
                    <div>
                        <label for="isoLatitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                        <input type="text" id="isoLatitude" name="latitude"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="e.g. 14.5995">
                    </div>

                    <div>
                        <label for="isoLongitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                        <input type="text" id="isoLongitude" name="longitude"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="e.g. 120.9842">
                    </div>

                    <!-- Location per Day -->
                    <div class="col-span-1 md:col-span-2">
                        <label for="isoLocationPerDay" class="block text-sm font-medium text-gray-700">Location per Day</label>
                        <textarea id="isoLocationPerDay" name="location_per_day" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Day 1: Location A&#10;Day 2: Location B&#10;Day 3: Location C"></textarea>
                    </div>
                </div>

                <!-- Validation Errors -->
                <div id="isoValidationErrors" class="mt-4 hidden">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700" id="isoErrorMessage">
                                    Please fix the errors below before submitting.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t text-right space-x-3">
                <button type="button" id="cancelIsoButton" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </button>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Save Operation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set up event listeners for the modal
        document.getElementById('closeIsoModal').addEventListener('click', function() {
            document.getElementById('isoModal').classList.add('hidden');
        });

        document.getElementById('cancelIsoButton').addEventListener('click', function() {
            document.getElementById('isoModal').classList.add('hidden');
        });

        // Close modal when clicking outside
        document.getElementById('isoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        // Form validation function
        function validateIsoForm() {
            const requiredFields = [
                { id: 'isoOperationName', name: 'Operation Name' },
                { id: 'isoRegion', name: 'Region' },
                { id: 'isoDate', name: 'Date & Time' },
                { id: 'isoTeamLeader', name: 'Team Leader' },
                { id: 'isoLocation', name: 'Location' }
            ];

            let isValid = true;
            let errorMessage = '';

            requiredFields.forEach(field => {
                const element = document.getElementById(field.id);
                if (!element.value.trim()) {
                    isValid = false;
                    errorMessage += `${field.name} is required.<br>`;
                    element.classList.add('border-red-500');
                    element.addEventListener('input', function() {
                        if (this.value.trim()) {
                            this.classList.remove('border-red-500');
                        }
                    }, { once: true });
                } else {
                    element.classList.remove('border-red-500');
                }
            });

            // Validate coordinates - both must be present if one is provided
            const lat = document.getElementById('isoLatitude').value.trim();
            const lng = document.getElementById('isoLongitude').value.trim();

            if ((lat && !lng) || (!lat && lng)) {
                isValid = false;
                errorMessage += 'Both Latitude and Longitude must be provided if one is entered.<br>';
                if (!lng) document.getElementById('isoLongitude').classList.add('border-red-500');
                if (!lat) document.getElementById('isoLatitude').classList.add('border-red-500');
            } else {
                document.getElementById('isoLatitude').classList.remove('border-red-500');
                document.getElementById('isoLongitude').classList.remove('border-red-500');
            }

            // Display validation errors if any
            const validationErrorsDiv = document.getElementById('isoValidationErrors');
            const errorMessageElement = document.getElementById('isoErrorMessage');

            if (!isValid) {
                errorMessageElement.innerHTML = errorMessage;
                validationErrorsDiv.classList.remove('hidden');
            } else {
                validationErrorsDiv.classList.add('hidden');
            }

            return isValid;
        }

        // Add form validation before submission
        document.getElementById('isoOperationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            if (validateIsoForm()) {
                // Let the event listener in the iso-operations.blade.php handle the submission
            }
        });
    });
</script>
