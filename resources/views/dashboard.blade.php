<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PNP E-Mapping System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Custom CSS for specific overrides */
        .sidebar {
            transition: all 0.3s ease;
            z-index: 100;
        }
        .sidebar-collapsed {
            width: 70px;
        }
        .sidebar-collapsed .nav-text {
            display: none;
        }
        .content {
            margin-left: 16rem;
            transition: all 0.3s ease;
        }
        .content-expanded {
            margin-left: 70px;
        }
        .section-content {
            display: none;
        }
        .section-content.active {
            display: block;
        }
        .leaflet-container {
            height: 500px;
            width: 100%;
        }
        .photo-preview {
            max-height: 150px;
            max-width: 100%;
            object-fit: contain;
        }
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e3a8a;
            border-radius: 4px;
        }
        .required:after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Navigation -->
        <div id="sidebar" class="sidebar bg-blue-900 text-white w-64 h-full fixed">
            <div class="flex items-center justify-between p-4 border-b border-blue-700">
                <div class="flex items-center">
                    <span class="text-xl font-bold">PNP E-Mapping</span>
                </div>
                <button id="toggleSidebar" class="text-white hover:text-blue-200">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <nav class="mt-4">
                <ul>
                    <li>
                        <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-blue-500 bg-blue-800 nav-link" data-section="dashboard">
                            <i class="fas fa-tachometer-alt mx-2"></i>
                            <span class="nav-text">Quicklook (Dashboard)</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent nav-link" data-section="map">
                            <i class="fas fa-map mx-2"></i>
                            <span class="nav-text">Map</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent nav-link" data-section="iso-operations">
                            <i class="fas fa-tasks mx-2"></i>
                            <span class="nav-text">ISO Operations</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent nav-link" data-section="sightings">
                            <i class="fas fa-binoculars mx-2"></i>
                            <span class="nav-text">Sightings</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent nav-link" data-section="firearms">
                            <i class="fas fa-gun mx-2"></i>
                            <span class="nav-text">Firearms</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent nav-link" data-section="ctgs">
                            <i class="fas fa-people-arrows mx-2"></i>
                            <span class="nav-text">CTGs</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent nav-link" data-section="pags">
                            <i class="fas fa-users mx-2"></i>
                            <span class="nav-text">PAGs</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent nav-link" data-section="surrendered">
                            <i class="fas fa-person-circle-question mx-2"></i>
                            <span class="nav-text">Surrendered</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="p-4 border-t border-blue-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center justify-center space-x-2 bg-red-600 hover:bg-red-700 text-white px-1 py-1 rounded-lg transition-all duration-200 absolute bottom-5 left-5" style="width: 200px;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div id="content" class="content flex-1 overflow-auto ml-64">
            <!-- User Info Banner -->
            <div class="bg-blue-800 text-white p-2 flex justify-between items-center">
                <div>
                    <span class="font-bold">{{ $user->username }}</span>
                    <span class="text-sm ml-2">{{ $user->role === 'admin' ? 'Administrator' : 'Regional User' }}</span>
                </div>
                <div>
                    @if($user->username === 'RMFB4A')
                        <span class="bg-blue-700 px-3 py-1 rounded">Region 4A (CALABARZON)</span>
                    @elseif($user->username === 'RMFB4B')
                        <span class="bg-blue-700 px-3 py-1 rounded">Region 4B (MIMAROPA)</span>
                    @elseif($user->username === 'RMFB5')
                        <span class="bg-blue-700 px-3 py-1 rounded">Region 5 (Bicol)</span>
                    @else
                        <span class="bg-blue-700 px-3 py-1 rounded">All Regions</span>
                    @endif
                </div>
            </div>

            <!-- Dashboard Section -->
            <section id="dashboard" class="section-content p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-blue-900">Dashboard - Quick Overview</h2>
                    <div class="flex space-x-2">
                        <select id="regionFilter" class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="all">All Regions</option>
                            <option value="4a">Region 4A (CALABARZON)</option>
                            <option value="4b">Region 4B (MIMAROPA)</option>
                            <option value="5">Region 5 (Bicol)</option>
                        </select>
                        <button id="refreshData" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                        <h3 class="text-gray-500 font-semibold mb-2">ISO Operations</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-3xl font-bold text-blue-800" id="isoCount">{{ count($regionData['isoOperations']) }}</span>
                            <div class="h-14 w-14 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-tasks text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">Total operations conducted</div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                        <h3 class="text-gray-500 font-semibold mb-2">Sightings Reported</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-3xl font-bold text-green-800" id="sightingsCount">{{ count($regionData['sightings']) }}</span>
                            <div class="h-14 w-14 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-binoculars text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">Total sightings recorded</div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                        <h3 class="text-gray-500 font-semibold mb-2">Firearms Collected</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-3xl font-bold text-red-800" id="firearmsCount">{{ count($regionData['firearms']) }}</span>
                            <div class="h-14 w-14 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-gun text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">Total firearms captured/recovered</div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                        <h3 class="text-gray-500 font-semibold mb-2">CTGs Tracked</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-3xl font-bold text-purple-800" id="ctgCount">{{ count($regionData['ctgs']) }}</span>
                            <div class="h-14 w-14 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-people-arrows text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">Total CTG members monitored</div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                        <h3 class="text-gray-500 font-semibold mb-2">PAGs Monitored</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-3xl font-bold text-yellow-800" id="pagCount">{{ count($regionData['pags']) }}</span>
                            <div class="h-14 w-14 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-users text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">Total PAG members tracked</div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                        <h3 class="text-gray-500 font-semibold mb-2">Surrendered Individuals</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-3xl font-bold text-indigo-800" id="surrenderedCount">{{ count($regionData['surrendered']) }}</span>
                            <div class="h-14 w-14 bg-indigo-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-person-circle-question text-indigo-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-2 text-sm text-gray-500">Total individuals surrendered</div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly ISO Operations</h3>
                        <div class="h-80">
                            <canvas id="isoChart"></canvas>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Regional Distribution</h3>
                        <div class="h-80">
                            <canvas id="regionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities Graph -->
                <div class="bg-white rounded-lg shadow border border-gray-200 p-4 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Activity Distribution by Category</h3>
                    <div class="h-80">
                        <canvas id="activityDistributionChart"></canvas>
                    </div>
                </div>

                <!-- Recent Data Table -->
                <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Activities Summary</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Region</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                </tr>
                            </thead>
                            <tbody id="recentActivityTable" class="bg-white divide-y divide-gray-200">
                                <!-- Data will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Include other sections from the original HTML here -->
            <!-- ... -->

            @include('sections.map')
            @include('sections.iso-operations')
            @include('sections.sightings')
            @include('sections.firearms')
            @include('sections.ctgs')
            @include('sections.pags')
            @include('sections.surrendered')
        </div>
    </div>

    <!-- Include modals -->
    @include('modals.iso-modal')
    @include('modals.sighting-modal')
    @include('modals.firearm-modal')
    @include('modals.ctg-modal')
    @include('modals.pag-modal')
    @include('modals.surrendered-modal')

    <!-- Recent Activities Script -->
    <script src="{{ asset('js/recent-activities.js') }}"></script>
    <script src="{{ asset('js/dashboard-init.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Global variables
            window.charts = {}; // Store chart instances

            // Initialize navigation
            initializeNavigation();

            // Default to dashboard view
            showSection('dashboard');

            // Setup region filter
            setupRegionFilter();

            // Define loadCtgs globally to fix the "not defined" error
            if (typeof window.loadCtgs !== 'function') {
                window.loadCtgs = function() {
                    console.log('Loading CTG data...');
                    const ctgTableBody = document.getElementById('ctgTableBody');
                    const loadingElement = document.getElementById('ctgTableLoading');
                    const emptyElement = document.getElementById('ctgTableEmpty');
                    const regionFilter = document.getElementById('ctgRegionFilter')?.value || 'all';
                    const rowCountElement = document.getElementById('rowCount');

                    if (!ctgTableBody) {
                        console.error('CTG table body not found');
                        return;
                    }

                    // Show loading indicator
                    if (loadingElement) loadingElement.classList.remove('hidden');

                    // Clear existing table content
                    ctgTableBody.innerHTML = '';
                    if (emptyElement) emptyElement.classList.add('hidden');

                    // Construct URL with query parameters if needed
                    let url = '/ctgs';
                    if (regionFilter !== 'all') {
                        url += `?region=${regionFilter}`;
                    }

                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Network response error: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('CTG API Response:', data);

                        // Hide loading indicator
                        if (loadingElement) loadingElement.classList.add('hidden');

                        if (data.success && data.data && data.data.length > 0) {
                            // Update row count display
                            if (rowCountElement) {
                                rowCountElement.textContent = data.data.length;
                            }

                            // Render data received from API
                            if (typeof renderCtgTable === 'function') {
                                renderCtgTable(data.data);
                            } else {
                                console.error('renderCtgTable function not found');
                            }
                        } else {
                            // Show empty message
                            if (emptyElement) emptyElement.classList.remove('hidden');
                            if (rowCountElement) {
                                rowCountElement.textContent = '0';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error loading CTG data:', error);
                        if (loadingElement) loadingElement.classList.add('hidden');
                        if (emptyElement) {
                            emptyElement.classList.remove('hidden');
                            emptyElement.textContent = `Error loading data: ${error.message}. Please try again later.`;
                        }
                        if (rowCountElement) {
                            rowCountElement.textContent = '0';
                        }
                    });
                };
            }
        });

        // Function to create or update charts
        function createChart(canvasId, type, data, options) {
            // Destroy existing chart if it exists
            if (window.charts[canvasId]) {
                window.charts[canvasId].destroy();
            }

            const ctx = document.getElementById(canvasId)?.getContext('2d');
            if (!ctx) {
                console.error(`Canvas with ID ${canvasId} not found`);
                return null;
            }

            window.charts[canvasId] = new Chart(ctx, {
                type: type,
                data: data,
                options: options
            });

            return window.charts[canvasId];
        }

        // Initialize navigation
        function initializeNavigation() {
            // Toggle sidebar
            document.getElementById('toggleSidebar').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('sidebar-collapsed');
                document.getElementById('content').classList.toggle('content-expanded');
            });

            // Navigation links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remove active class from all links
                    document.querySelectorAll('.nav-link').forEach(l => {
                        l.classList.remove('bg-blue-800');
                        l.classList.remove('border-blue-500');
                        l.classList.add('border-transparent');
                    });

                    // Add active class to clicked link
                    this.classList.add('bg-blue-800');
                    this.classList.add('border-blue-500');
                    this.classList.remove('border-transparent');

                    // Show corresponding section
                    const section = this.getAttribute('data-section');
                    showSection(section);
                });
            });
        }

        // Show section
        function showSection(sectionId) {
            document.querySelectorAll('.section-content').forEach(section => {
                section.classList.remove('active');
            });

            const activeSection = document.getElementById(sectionId);
            if (activeSection) {
                activeSection.classList.add('active');

                // Load data for the section if needed
                if (sectionId === 'ctgs' && typeof window.loadCtgs === 'function') {
                    window.loadCtgs();
                }
                // Add other section-specific initializations here
            }
        }

        // Setup region filter
        function setupRegionFilter() {
            document.getElementById('regionFilter').addEventListener('change', function() {
                // Implement region filtering logic here
                console.log('Region filter changed to:', this.value);
            });
        }
    </script>
</body>
</html>
