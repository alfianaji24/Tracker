<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tracker') }} - Admin Dashboard</title>

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- TailAdmin CSS -->
    <link rel='stylesheet' href='{{ asset("assets/css/style.css") }}'>

    <!-- Scripts and CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <i class='bx bx-water'></i> {{ config('app.name', 'Tracker') }}
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('dashboard') }}" class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <div class="sidebar-item-content">
                            <i class='bx bx-grid-alt'></i>
                            <span>Dashboard</span>
                        </div>
                    </a>
                </li>

                <div class="sidebar-menu-title">Transactions</div>

                <li>
                    <a href="{{ route('pelanggans.index') }}" class="sidebar-item {{ request()->routeIs('pelanggans.*') ? 'active' : '' }}">
                        <div class="sidebar-item-content">
                            <i class='bx bx-group'></i>
                            <span>Pelanggan</span>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('billings.index') }}" class="sidebar-item {{ request()->routeIs('billings.*') ? 'active' : '' }}">
                        <div class="sidebar-item-content">
                            <i class='bx bx-receipt'></i>
                            <span>Billing & Tagihan</span>
                        </div>
                    </a>
                </li>

                <div class="sidebar-menu-title">Master Data</div>

                <li>
                    <div class="sidebar-item" onclick="toggleSubmenu('masterMenu')">
                        <div class="sidebar-item-content">
                            <i class='bx bx-data'></i>
                            <span>Master</span>
                        </div>
                        <i class='bx bx-chevron-down'></i>
                    </div>
                    <ul class="sidebar-submenu {{ request()->routeIs('master.*') ? 'open' : '' }}" id="masterMenu">
                        <li><a href="{{ route('master.users.index') }}" class="sidebar-subitem {{ request()->routeIs('master.users.*') ? 'active' : '' }}">Users</a></li>
                        <li><a href="{{ route('master.roles.index') }}" class="sidebar-subitem {{ request()->routeIs('master.roles.*') ? 'active' : '' }}">Roles</a></li>
                        <li><a href="{{ route('master.permissions.index') }}" class="sidebar-subitem {{ request()->routeIs('master.permissions.*') ? 'active' : '' }}">Permissions</a></li>
                        <li><a href="{{ route('master.permission-groups.index') }}" class="sidebar-subitem {{ request()->routeIs('master.permission-groups.*') ? 'active' : '' }}">Group Permissions</a></li>
                        <li><a href="{{ route('master.tarif-air.index') }}" class="sidebar-subitem {{ request()->routeIs('master.tarif-air.*') ? 'active' : '' }}">Tarif Air</a></li>
                    </ul>
                </li>

            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-wrapper">
            <!-- Header -->
            <header class="top-header">
                <div class="header-left">
                    <button class="btn" style="background: none; border: none; font-size: 24px; cursor: pointer; padding: 0; color: var(--text-main);" onclick="toggleSidebar()">
                        <i class='bx bx-menu'></i>
                    </button>
                </div>
                <div class="header-right">
                    <div class="user-profile" onclick="toggleDropdown('userDropdown')">
                        <div style="text-align: right;">
                            <div style="font-weight: 600; font-size: 14px; color: var(--text-main);">{{ Auth::user()->name }}</div>
                            <div style="font-size: 12px; color: var(--text-muted);">{{ Auth::user()->role->name ?? 'Administrator' }}</div>
                        </div>
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <i class='bx bx-chevron-down' style="color: var(--text-muted);"></i>

                        <div class="dropdown-menu" id="userDropdown">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item"><i class='bx bx-user'></i> Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer; font-family: inherit;">
                                    <i class='bx bx-log-out'></i> Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="content-area">
                @if(session('success'))
                <div style="background-color: #d1fae5; color: #065f46; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #10B981;">
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div style="background-color: #fee2e2; color: #991b1b; padding: 16px; border-radius: 8px; margin-bottom: 24px; border-left: 4px solid #EF4444;">
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.style.marginLeft === '-280px') {
                sidebar.style.marginLeft = '0';
            } else {
                sidebar.style.marginLeft = '-280px';
            }
        }

        function toggleSubmenu(id) {
            const menu = document.getElementById(id);
            menu.classList.toggle('open');
        }

        function toggleDropdown(id) {
            const menu = document.getElementById(id);
            menu.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.closest('.user-profile')) {
                var dropdowns = document.getElementsByClassName("dropdown-menu");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>

    <!-- TailAdmin JS -->
    <script src='{{ asset("assets/js/index.js") }}'></script>
</body>

</html>
