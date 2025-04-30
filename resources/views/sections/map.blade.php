<!-- Map Section -->
<section id="map" class="section-content p-6 hidden">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-blue-900">Geospatial Mapping</h2>
        <div class="flex space-x-2">
            <select id="mapRegionFilter" class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all">All Regions</option>
                <option value="4a">Region 4A (CALABARZON)</option>
                <option value="4b">Region 4B (MIMAROPA)</option>
                <option value="5">Region 5 (Bicol)</option>
            </select>
            <select id="mapCategoryFilter" class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all">All Categories</option>
                <option value="iso">ISO Operations</option>
                <option value="sightings">Sightings</option>
                <option value="firearms">Firearms</option>
                <option value="ctgs">CTGs</option>
                <option value="pags">PAGs</option>
                <option value="surrendered">Surrendered</option>
            </select>
        </div>
    </div>

    <!-- Map Container -->
    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div id="mapContainer" class="h-96 w-full flex items-center justify-center bg-gray-100">
            <div class="text-center text-gray-500">
                <i class="fas fa-map text-5xl mb-2"></i>
                <p>Map API will be integrated here</p>
                <p class="text-sm">This would display actual geographical data when connected to mapping API</p>
            </div>
        </div>
    </div>

    <!-- Map Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
            <h3 class="text-gray-700 font-semibold mb-2">Hotspot Areas</h3>
            <ul class="space-y-2" id="hotspotAreas">
                <li class="flex items-center justify-between">
                    <span>Calamba, Laguna</span>
                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">High</span>
                </li>
                <li class="flex items-center justify-between">
                    <span>Puerto Galera, Oriental Mindoro</span>
                    <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded-full text-xs font-semibold">Medium</span>
                </li>
                <li class="flex items-center justify-between">
                    <span>Naga City, Camarines Sur</span>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Medium</span>
                </li>
            </ul>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
            <h3 class="text-gray-700 font-semibold mb-2">Activity Heatmap</h3>
            <div class="h-48 flex items-center justify-center">
                <p class="text-gray-500">Activity density visualization would appear here</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
            <h3 class="text-gray-700 font-semibold mb-2">Recent Activities</h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="h-2 w-2 mt-2 rounded-full bg-blue-600 mr-2"></div>
                    <div>
                        <p class="text-sm font-medium">ISO Operation - Cavite</p>
                        <p class="text-xs text-gray-500">2 hours ago</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="h-2 w-2 mt-2 rounded-full bg-green-600 mr-2"></div>
                    <div>
                        <p class="text-sm font-medium">Sighting - Marinduque</p>
                        <p class="text-xs text-gray-500">5 hours ago</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="h-2 w-2 mt-2 rounded-full bg-red-600 mr-2"></div>
                    <div>
                        <p class="text-sm font-medium">Firearm Recovery - Albay</p>
                        <p class="text-xs text-gray-500">1 day ago</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
