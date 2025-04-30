@extends('layouts.app')

@section('content')
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
                    <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-blue-500 bg-blue-800" data-section="dashboard">
                        <i class="fas fa-tachometer-alt mx-2"></i>
                        <span class="nav-text">Quicklook (Dashboard)</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent" data-section="map">
                        <i class="fas fa-map mx-2"></i>
                        <span class="nav-text">Map</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent" data-section="iso-operations">
                        <i class="fas fa-tasks mx-2"></i>
                        <span class="nav-text">ISO Operations</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent" data-section="sightings">
                        <i class="fas fa-binoculars mx-2"></i>
                        <span class="nav-text">Sightings</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent" data-section="firearms">
                        <i class="fas fa-gun mx-2"></i>
                        <span class="nav-text">Firearms</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent" data-section="ctgs">
                        <i class="fas fa-people-arrows mx-2"></i>
                        <span class="nav-text">CTGs</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent" data-section="pags">
                        <i class="fas fa-users mx-2"></i>
                        <span class="nav-text">PAGs</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent" data-section="surrendered">
                        <i class="fas fa-person-circle-question mx-2"></i>
                        <span class="nav-text">Surrendered</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="p-4 border-t border-blue-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center justify-center space-x-2 bg-red-600 hover:bg-red-700 text-white px-1 py-1 rounded-lg transition-all duration-200 absolute bottom-5 left-5" style="width: 200px;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div id="content" class="content flex-1 overflow-auto">
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
                        <span class="text-3xl font-bold text-blue-800" id="isoCount">0</span>
                        <div class="h-14 w-14 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-tasks text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-gray-500">Total operations conducted</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-500 font-semibold mb-2">Sightings Reported</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-3xl font-bold text-green-800" id="sightingsCount">0</span>
                        <div class="h-14 w-14 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-binoculars text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-gray-500">Total sightings recorded</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-500 font-semibold mb-2">Firearms Collected</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-3xl font-bold text-red-800" id="firearmsCount">0</span>
                        <div class="h-14 w-14 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-gun text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-gray-500">Total firearms captured/recovered</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-500 font-semibold mb-2">CTGs Tracked</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-3xl font-bold text-purple-800" id="ctgCount">0</span>
                        <div class="h-14 w-14 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-people-arrows text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-gray-500">Total CTG members monitored</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-500 font-semibold mb-2">PAGs Monitored</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-3xl font-bold text-yellow-800" id="pagCount">0</span>
                        <div class="h-14 w-14 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-gray-500">Total PAG members tracked</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-500 font-semibold mb-2">Surrendered Individuals</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-3xl font-bold text-indigo-800" id="surrenderedCount">0</span>
                        <div class="h-14 w-14 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-person-circle-question text-indigo-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-gray-500">Total individuals surrendered</div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-700 font-semibold mb-4">Monthly ISO Operations</h3>
                    <canvas id="isoChart" height="250"></canvas>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                    <h3 class="text-gray-700 font-semibold mb-4">Regional Distribution</h3>
                    <canvas id="regionChart" height="250"></canvas>
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

        <!-- Include other sections for Map, ISO Operations, etc. -->
        @include('sections.map')
        @include('sections.iso-operations')
        @include('sections.sightings')
        @include('sections.firearms')
        @include('sections.ctgs')
        @include('sections.pags')
        @include('sections.surrendereds')
    </div>
</div>

<!-- Include modals -->
@include('modals.iso-modal')
@include('modals.sighting-modal')
@include('modals.firearm-modal')
@include('modals.ctg-modal')
@include('modals.pag-modal')
@include('modals.surrendered-modal')

@endsection

@section('scripts')
<script>
    // Sample data
    const sampleData = {
        isoOperations: [
            {
                id: 1,
                name: "Operation Safe CALABARZON",
                region: "4a",
                date: "2023-06-15T08:30",
                teamLeader: "PCol. Juan Dela Cruz",
                members: "PCol. Juan Dela Cruz, PMaj. Maria Reyes, PCpt. Carlos Santos",
                location: "Batangas City",
                coordinates: "13.7563, 121.0583",
                locationPerDay: "June 15 - Batangas City; June 16 - Lipa City"
            },
            {
                id: 2,
                name: "MIMAROPA Security Sweep",
                region: "4b",
                date: "2023-06-20T10:00",
                teamLeader: "PCol. Eduardo Lopez",
                members: "PCol. Eduardo Lopez, PMaj. Sofia Garcia, PCpt. Miguel Torres",
                location: "Puerto Princesa",
                coordinates: "9.7392, 118.7353",
                locationPerDay: "June 20 - Puerto Princesa; June 21 - El Nido"
            },
            {
                id: 3,
                name: "Operation Bicol Safe Streets",
                region: "5",
                date: "2023-06-25T07:00",
                teamLeader: "PCol. Antonio Bautista",
                members: "PCol. Antonio Bautista, PMaj. Lourdes Mendoza, PCpt. Roberto Valencia",
                location: "Naga City",
                coordinates: "13.6248, 123.1877",
                locationPerDay: "June 25 - Naga City; June 26 - Legazpi City"
            }
        ],
        sightings: [
            // Sample data for sightings
        ],
        firearms: [
            // Sample data for firearms
        ],
        ctgs: [
            // Sample data for CTGs
        ],
        pags: [
            // Sample data for PAGs
        ],
        surrendered: [
            // Sample data for surrendered
        ]
    };

    // Dashboard statistics
    const dashboardStats = {
        isoCount: sampleData.isoOperations.length,
        sightingsCount: sampleData.sightings ? sampleData.sightings.length : 0,
        firearmsCount: sampleData.firearms ? sampleData.firearms.length : 0,
        ctgCount: sampleData.ctgs ? sampleData.ctgs.length : 0,
        pagCount: sampleData.pags ? sampleData.pags.length : 0,
        surrenderedCount: sampleData.surrendered ? sampleData.surrendered.length : 0
    };

    // Helper functions
    function getRegionName(regionCode) {
        switch(regionCode) {
            case '4a': return 'Region 4A (CALABARZON)';
            case '4b': return 'Region 4B (MIMAROPA)';
            case '5': return 'Region 5 (Bicol)';
            default: return 'Unknown Region';
        }
    }

    function getIsoChartData() {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const currentMonth = new Date().getMonth();

        // Get last 6 months
        const chartLabels = [];
        for (let i = 5; i >= 0; i--) {
            const monthIndex = (currentMonth - i + 12) % 12;
            chartLabels.push(months[monthIndex]);
        }

        // Generate dummy data for the chart
        const chartData = chartLabels.map(() => Math.floor(Math.random() * 10) + 2);

        return { labels: chartLabels, data: chartData };
    }

    function getRegionChartData() {
        const regions = ['4a', '4b', '5'];
        const labels = regions.map(getRegionName);

        // Generate dummy data for the chart
        const data = regions.map(() => Math.floor(Math.random() * 20) + 5);

        return { labels, data };
    }

    // DOM manipulation functions
    function populateDashboard() {
        // Update counters
        document.getElementById('isoCount').textContent = dashboardStats.isoCount;
        document.getElementById('sightingsCount').textContent = dashboardStats.sightingsCount;
        document.getElementById('firearmsCount').textContent = dashboardStats.firearmsCount;
        document.getElementById('ctgCount').textContent = dashboardStats.ctgCount;
        document.getElementById('pagCount').textContent = dashboardStats.pagCount;
        document.getElementById('surrenderedCount').textContent = dashboardStats.surrenderedCount;

        // Create charts
        createIsoOperationsChart();
        createRegionalDistributionChart();

        // Populate recent activity table (with dummy data for now)
        const tableBody = document.getElementById('recentActivityTable');
        if (tableBody) {
            tableBody.innerHTML = '';

            // Add a few sample activities
            const activities = [
                { date: '2023-06-28', region: 'Region 4A (CALABARZON)', category: 'ISO Operation', details: 'Operation Safe CALABARZON', location: 'Batangas City' },
                { date: '2023-06-25', region: 'Region 5 (Bicol)', category: 'Sighting', details: 'Suspected CTG members seen', location: 'Naga City' },
                { date: '2023-06-20', region: 'Region 4B (MIMAROPA)', category: 'Firearm', details: 'M16 Rifle recovered', location: 'Puerto Princesa' }
            ];

            activities.forEach(activity => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.date}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.region}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.category}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${activity.details}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${activity.location}</td>
                `;
                tableBody.appendChild(row);
            });
        }
    }

    function createIsoOperationsChart() {
        const ctx = document.getElementById('isoChart');
        if (ctx) {
            const chartData = getIsoChartData();

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'ISO Operations',
                        data: chartData.data,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    }

    function createRegionalDistributionChart() {
        const ctx = document.getElementById('regionChart');
        if (ctx) {
            const chartData = getRegionChartData();

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        data: chartData.data,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(16, 185, 129, 0.7)',
                            'rgba(245, 158, 11, 0.7)'
                        ],
                        borderColor: [
                            'rgba(59, 130, 246, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(245, 158, 11, 1)'
                        ],
                        borderWidth: 1
                    }]
                }
            });
        }
    }

    // DOM event handlers
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize UI
        populateDashboard();

        // Navigation menu toggle
        const toggleSidebar = document.getElementById('toggleSidebar');
        if (toggleSidebar) {
            toggleSidebar.addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar');
                const content = document.getElementById('content');

                sidebar.classList.toggle('sidebar-collapsed');
                content.classList.toggle('content-expanded');
            });
        }

        // Navigation menu item clicks
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                // Update active menu item
                document.querySelectorAll('nav a').forEach(a => {
                    a.classList.remove('bg-blue-800', 'border-blue-500');
                    a.classList.add('border-transparent');
                });

                this.classList.add('bg-blue-800', 'border-blue-500');
                this.classList.remove('border-transparent');

                // Show the selected section
                const sectionId = this.getAttribute('data-section');
                document.querySelectorAll('.section-content').forEach(section => {
                    section.classList.add('hidden');
                });

                const selectedSection = document.getElementById(sectionId);
                if (selectedSection) {
                    selectedSection.classList.remove('hidden');
                }
            });
        });

        // Refresh data button
        const refreshBtn = document.getElementById('refreshData');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                populateDashboard();

                // Show a toast notification
                alert('Data refreshed successfully!');
            });
        }
    });
</script>
@endsection
