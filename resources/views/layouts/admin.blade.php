<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PNP E-Mapping System - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
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
                        <a href="{{ route('dashboard') }}" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent">
                            <i class="fas fa-tachometer-alt mx-2"></i>
                            <span class="nav-text">Quicklook (Dashboard)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}#map" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent">
                            <i class="fas fa-map mx-2"></i>
                            <span class="nav-text">Map</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}#iso-operations" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent">
                            <i class="fas fa-tasks mx-2"></i>
                            <span class="nav-text">ISO Operations</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}#sightings" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent">
                            <i class="fas fa-binoculars mx-2"></i>
                            <span class="nav-text">Sightings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}#firearms" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent">
                            <i class="fas fa-gun mx-2"></i>
                            <span class="nav-text">Firearms</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}#ctgs" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent">
                            <i class="fas fa-people-arrows mx-2"></i>
                            <span class="nav-text">CTGs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}#pags" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent">
                            <i class="fas fa-users mx-2"></i>
                            <span class="nav-text">PAGs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}#surrendered" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-transparent">
                            <i class="fas fa-person-circle-question mx-2"></i>
                            <span class="nav-text">Surrendered</span>
                        </a>
                    </li>
                    @if(auth()->user()->isAdmin())
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 hover:bg-blue-800 border-l-4 border-blue-500 bg-blue-800">
                            <i class="fas fa-users-cog mx-2"></i>
                            <span class="nav-text">User Management</span>
                        </a>
                    </li>
                    @endif
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
                    <span class="font-bold">{{ auth()->user()->username }}</span>
                    <span class="text-sm ml-2">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Regional User' }}</span>
                </div>
                <div>
                    @if(auth()->user()->username === 'RMFB4A')
                        <span class="bg-blue-700 px-3 py-1 rounded">Region 4A (CALABARZON)</span>
                    @elseif(auth()->user()->username === 'RMFB4B')
                        <span class="bg-blue-700 px-3 py-1 rounded">Region 4B (MIMAROPA)</span>
                    @elseif(auth()->user()->username === 'RMFB5')
                        <span class="bg-blue-700 px-3 py-1 rounded">Region 5 (Bicol)</span>
                    @else
                        <span class="bg-blue-700 px-3 py-1 rounded">All Regions</span>
                    @endif
                </div>
            </div>

            <!-- Content Container -->
            <div class="p-6">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        // Sidebar toggle functionality
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('sidebar-collapsed');
            document.getElementById('content').classList.toggle('content-expanded');
        });

        // Initialize DataTables
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#usersTable')) {
                $('#usersTable').DataTable().destroy();
            }
            $('#usersTable').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search users..."
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
