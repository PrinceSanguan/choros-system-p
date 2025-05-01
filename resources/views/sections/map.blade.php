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
        <div id="mapContainer" class="h-96 w-full">
            <!-- Google Maps will be rendered here -->
        </div>
    </div>

    <!-- Map Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
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

<!-- Google Maps API Script -->
<script>
    // Load Google Maps API
    function loadGoogleMapsScript() {
        const script = document.createElement('script');
        script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyCdERgahWsAJaBY0MjZ6TCFM4k9NIughs4&callback=initMap&libraries=places";
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    }

    // Initialize map and markers
    let map;
    let markers = [];

    // Initialize map when Google Maps API is loaded
    window.initMap = function() {
        // Philippines centered map
        const philippines = { lat: 12.8797, lng: 121.7740 };

        // Create the map
        map = new google.maps.Map(document.getElementById("mapContainer"), {
            zoom: 6,
            center: philippines,
            mapTypeId: "roadmap",
            mapTypeControl: true,
            streetViewControl: false,
            fullscreenControl: true,
            zoomControl: true,
        });

        // Add markers when map is ready
        google.maps.event.addListenerOnce(map, 'idle', function() {
            loadMapData();
        });

        // Add filters change listeners
        document.getElementById('mapRegionFilter').addEventListener('change', loadMapData);
        document.getElementById('mapCategoryFilter').addEventListener('change', loadMapData);
    };

    // Function to load data based on filters
    function loadMapData() {
        clearMarkers();

        const regionFilter = document.getElementById('mapRegionFilter').value;
        const categoryFilter = document.getElementById('mapCategoryFilter').value;

        console.log(`Loading map data - Region: ${regionFilter}, Category: ${categoryFilter}`);

        // Get data from our application
        fetch('/api/map-data', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        })
        .then(response => response.json())
        .then(data => {
            addMapMarkers(data, regionFilter, categoryFilter);
        })
        .catch(error => {
            console.error('Error fetching map data:', error);
            // If API fails, use sample data
            addSampleMarkers(regionFilter, categoryFilter);
        });
    }

    // Function to add sample markers as fallback
    function addSampleMarkers(regionFilter, categoryFilter) {
        const sampleData = [
            // CTGs
            { lat: 14.0840, lng: 120.9370, type: 'ctgs', region: '4a', title: 'Jose Santos', description: 'NPA - Southern Tagalog' },
            { lat: 13.2345, lng: 121.1234, type: 'ctgs', region: '4b', title: 'Maria Reyes', description: 'NPA - Mindoro Command' },
            { lat: 13.1235, lng: 123.7640, type: 'ctgs', region: '5', title: 'Pedro Bicol', description: 'NPA - Bicol Regional Party' },

            // ISO Operations
            { lat: 14.1235, lng: 120.8765, type: 'iso', region: '4a', title: 'Operation Safe CALABARZON', description: 'Batangas City' },
            { lat: 12.9876, lng: 121.5432, type: 'iso', region: '4b', title: 'MIMAROPA Security Sweep', description: 'Puerto Princesa' },
            { lat: 13.4567, lng: 123.1234, type: 'iso', region: '5', title: 'Operation Bicol Safe', description: 'Naga City' },

            // Sightings
            { lat: 14.3456, lng: 121.1122, type: 'sightings', region: '4a', title: 'Suspected CTG Group', description: 'Cavite Province' },
            { lat: 12.7896, lng: 120.9876, type: 'sightings', region: '4b', title: 'Armed Group Sighting', description: 'Oriental Mindoro' },
            { lat: 13.5544, lng: 123.2211, type: 'sightings', region: '5', title: 'Rebel Movement', description: 'Sorsogon Province' },

            // Firearms
            { lat: 14.2233, lng: 121.0099, type: 'firearms', region: '4a', title: 'M16 Rifle', description: 'Recovered in Laguna' },
            { lat: 13.0011, lng: 121.2277, type: 'firearms', region: '4b', title: 'Carbine Rifle', description: 'Found in Occidental Mindoro' },
            { lat: 13.6677, lng: 123.3344, type: 'firearms', region: '5', title: 'Handgun', description: 'Confiscated in Albay' },

            // PAGs
            { lat: 14.4545, lng: 120.9898, type: 'pags', region: '4a', title: 'Local PAG Group', description: 'Operating in Cavite' },
            { lat: 12.8877, lng: 121.4433, type: 'pags', region: '4b', title: 'Island PAG', description: 'Active in Marinduque' },
            { lat: 13.2255, lng: 123.6677, type: 'pags', region: '5', title: 'Bicol PAG', description: 'Presence in Camarines Sur' },

            // Surrendered
            { lat: 14.3366, lng: 121.2244, type: 'surrendered', region: '4a', title: 'Former NPA Member', description: 'Surrendered in Quezon' },
            { lat: 12.9933, lng: 121.0022, type: 'surrendered', region: '4b', title: 'Ex-Rebel', description: 'Turned over in Romblon' },
            { lat: 13.7788, lng: 123.5566, type: 'surrendered', region: '5', title: 'Former Insurgent', description: 'Surrendered in Masbate' }
        ];

        // Filter the data based on selection
        let filteredData = sampleData;

        if (regionFilter !== 'all') {
            filteredData = filteredData.filter(item => item.region === regionFilter);
        }

        if (categoryFilter !== 'all') {
            filteredData = filteredData.filter(item => item.type === categoryFilter);
        }

        // Add markers for the filtered data
        filteredData.forEach(item => {
            addMarker(item);
        });
    }

    // Add a single marker to the map
    function addMarker(location) {
        // Choose marker color based on type
        let markerIcon = {
            url: getMarkerIcon(location.type),
            scaledSize: new google.maps.Size(32, 32)
        };

        const marker = new google.maps.Marker({
            position: { lat: location.lat, lng: location.lng },
            map: map,
            title: location.title,
            icon: markerIcon
        });

        // Create info window for this marker
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div class="p-2">
                    <h3 class="font-bold">${location.title}</h3>
                    <p>${location.description}</p>
                    <p class="text-xs text-gray-500">${getTypeName(location.type)}</p>
                </div>
            `
        });

        // Add click listener to open info window
        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });

        markers.push(marker);
    }

    // Get marker icon based on type
    function getMarkerIcon(type) {
        switch(type) {
            case 'ctgs':
                return 'https://maps.google.com/mapfiles/ms/icons/red-dot.png';
            case 'iso':
                return 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png';
            case 'sightings':
                return 'https://maps.google.com/mapfiles/ms/icons/green-dot.png';
            case 'firearms':
                return 'https://maps.google.com/mapfiles/ms/icons/orange-dot.png';
            case 'pags':
                return 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
            case 'surrendered':
                return 'https://maps.google.com/mapfiles/ms/icons/purple-dot.png';
            default:
                return 'https://maps.google.com/mapfiles/ms/icons/red-dot.png';
        }
    }

    // Get human-readable type name
    function getTypeName(type) {
        switch(type) {
            case 'ctgs': return 'CTG Member';
            case 'iso': return 'ISO Operation';
            case 'sightings': return 'Sighting';
            case 'firearms': return 'Firearm';
            case 'pags': return 'PAG Member';
            case 'surrendered': return 'Surrendered Individual';
            default: return type;
        }
    }

    // Function to add markers from API data
    function addMapMarkers(data, regionFilter, categoryFilter) {
        // Implementation would depend on actual data structure from API
        console.log('Adding markers from API data');

        // For now, fall back to sample data
        addSampleMarkers(regionFilter, categoryFilter);
    }

    // Clear all markers from the map
    function clearMarkers() {
        markers.forEach(marker => {
            marker.setMap(null);
        });
        markers = [];
    }

    // Load Google Maps when the map section is shown
    document.addEventListener('DOMContentLoaded', function() {
        // Listen for nav clicks to initialize map when needed
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                const targetSection = this.getAttribute('data-section');
                if (targetSection === 'map' && typeof google === 'undefined') {
                    loadGoogleMapsScript();
                }
            });
        });

        // Also initialize if map is the active section on page load
        if (document.getElementById('map').classList.contains('active') && typeof google === 'undefined') {
            loadGoogleMapsScript();
        }
    });
</script>
