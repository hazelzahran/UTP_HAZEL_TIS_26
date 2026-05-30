<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WowoClean - Sistem Manajemen Limbah B3</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #001f3f;
            --secondary: #00d4ff;
            --success: #00c9a7;
            --danger: #ff4757;
            --warning: #ffa502;
            --dark-bg: #0a1428;
            --card-bg: #1a2a4a;
            --border-color: #2d3e5f;
            --text-light: #e9ecef;
            --text-muted: #a8b5c8;
            }

            body.light-mode {
                --primary: #f8fafc;
                --secondary: #0d6efd;
                --success: #0ca678;
                --danger: #d9480f;
                --warning: #f59f00;
                --dark-bg: #f0f4f8;
                --card-bg: #ffffff;
                --border-color: #e6eaf0;
                --text-light: #06232b;
                --text-muted: #6c757d;
                background: linear-gradient(135deg, var(--dark-bg) 0%, #ffffff 100%);
                color: var(--text-light);
            }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--dark-bg) 0%, #0f1f35 100%);
            color: var(--text-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        /* Sidebar Navigation */
        .sidebar {
            background: linear-gradient(180deg, var(--primary) 0%, #001a2e 100%);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 260px;
            overflow-y: auto;
            padding: 20px 0;
            border-right: 1px solid var(--border-color);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 20px;
            gap: 12px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .sidebar-header .logo-icon {
            font-size: 28px;
            color: var(--secondary);
        }

        .sidebar-header .logo-text {
            color: var(--text-light);
            font-weight: 700;
            font-size: 18px;
        }

        .sidebar.collapsed .logo-text {
            display: none;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu li {
            margin: 5px 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            gap: 12px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(0, 212, 255, 0.1);
            color: var(--secondary);
            border-left-color: var(--secondary);
        }

        .sidebar-menu a i {
            min-width: 24px;
            text-align: center;
            font-size: 18px;
        }

        .sidebar.collapsed .sidebar-menu a span {
            display: none;
        }

        /* Top Navigation */
        .topbar {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 15px 30px;
            margin-left: 260px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: margin-left 0.3s ease;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .topbar.collapsed-sidebar {
            margin-left: 80px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .toggle-sidebar-btn {
            background: none;
            border: none;
            color: var(--secondary);
            cursor: pointer;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .toggle-sidebar-btn:hover {
            color: var(--text-light);
            transform: rotate(90deg);
        }

        .search-box {
            display: flex;
            align-items: center;
            background: rgba(0, 212, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 8px 15px;
            gap: 10px;
            width: 300px;
        }

        .search-box input {
            background: none;
            border: none;
            color: var(--text-light);
            outline: none;
            width: 100%;
        }

        .search-box input::placeholder {
            color: var(--text-muted);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 8px 15px;
            background: rgba(0, 212, 255, 0.05);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-menu:hover {
            background: rgba(0, 212, 255, 0.1);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary), #00a8d8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--primary);
            font-size: 16px;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }

        .main-content.collapsed-sidebar {
            margin-left: 80px;
        }

        /* Breadcrumb */
        .breadcrumb-nav {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .breadcrumb-nav a {
            color: var(--secondary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .breadcrumb-nav a:hover {
            color: var(--text-light);
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-light);
        }

        .page-title small {
            display: block;
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 5px;
        }

        /* Dashboard Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--secondary), transparent);
        }

        .stat-card:hover {
            border-color: var(--secondary);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 212, 255, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .stat-icon.primary {
            background: rgba(0, 212, 255, 0.15);
            color: var(--secondary);
        }

        .stat-icon.success {
            background: rgba(0, 201, 167, 0.15);
            color: var(--success);
        }

        .stat-icon.warning {
            background: rgba(255, 165, 2, 0.15);
            color: var(--warning);
        }

        .stat-icon.danger {
            background: rgba(255, 71, 87, 0.15);
            color: var(--danger);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-muted);
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--secondary), #00a8d8);
            border: none;
            color: var(--primary);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 212, 255, 0.3);
            color: var(--primary);
            text-decoration: none;
        }

        .btn-secondary-custom {
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid var(--secondary);
            color: var(--secondary);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: rgba(0, 212, 255, 0.2);
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn-danger-custom {
            background: rgba(255, 71, 87, 0.1);
            border: 1px solid var(--danger);
            color: var(--danger);
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-danger-custom:hover {
            background: var(--danger);
            color: white;
        }

        /* Tables */
        .table-container {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            margin-top: 20px;
        }

        .table {
            color: var(--text-light);
            margin-bottom: 0;
        }

        .table thead {
            background: rgba(0, 212, 255, 0.05);
            border-bottom: 2px solid var(--border-color);
        }

        .table th {
            color: var(--secondary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding: 15px;
            border: none;
        }

        .table td {
            padding: 15px;
            border: none;
            border-bottom: 1px solid var(--border-color);
        }

        .table tbody tr:hover {
            background: rgba(0, 212, 255, 0.05);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-active {
            background: rgba(0, 201, 167, 0.15);
            color: var(--success);
        }

        .badge-maintenance {
            background: rgba(255, 165, 2, 0.15);
            color: var(--warning);
        }

        .badge-full {
            background: rgba(255, 71, 87, 0.15);
            color: var(--danger);
        }

        .badge-archived {
            background: rgba(168, 181, 200, 0.15);
            color: var(--text-muted);
        }

        /* Modal */
        .modal-content {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 20px;
        }

        .modal-header .modal-title {
            color: var(--text-light);
            font-weight: 700;
        }

        .modal-header .btn-close {
            background: rgba(255, 255, 255, 0.1);
            filter: invert(1);
        }

        .modal-body {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            color: var(--text-light);
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            background: rgba(0, 212, 255, 0.05);
            border: 1px solid var(--border-color);
            color: var(--text-light);
            padding: 10px 15px;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--secondary);
            background: rgba(0, 212, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: var(--text-muted);
        }

        .form-group select option {
            background: var(--primary);
            color: var(--text-light);
        }

        /* Pagination */
        .pagination-custom {
            display: flex;
            gap: 5px;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination-custom a,
        .pagination-custom span {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-light);
            text-decoration: none;
        }

        .pagination-custom a:hover {
            border-color: var(--secondary);
            background: rgba(0, 212, 255, 0.1);
            color: var(--secondary);
        }

        .pagination-custom .active {
            background: var(--secondary);
            color: var(--primary);
            border-color: var(--secondary);
        }

        /* Loading */
        .skeleton-loader {
            background: linear-gradient(90deg, var(--border-color), rgba(255, 255, 255, 0.05), var(--border-color));
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 8px;
            min-height: 20px;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        }

        .toast {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-left: 3px solid var(--secondary);
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease;
        }

        .toast.success {
            border-left-color: var(--success);
        }

        .toast.error {
            border-left-color: var(--danger);
        }

        .toast.warning {
            border-left-color: var(--warning);
        }

        .toast-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .toast-icon.success {
            color: var(--success);
        }

        .toast-icon.error {
            color: var(--danger);
        }

        .toast-icon.warning {
            color: var(--warning);
        }

        .toast-close {
            margin-left: auto;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 16px;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }

            .topbar,
            .main-content {
                margin-left: 80px;
            }

            .sidebar-header .logo-text,
            .sidebar-menu a span {
                display: none;
            }

            .search-box {
                width: 200px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                display: none;
            }

            .topbar,
            .main-content {
                margin-left: 0;
            }

            .search-box {
                display: none;
            }

            .topbar-right {
                gap: 15px;
            }
        }

        /* Chart Container */
        .chart-container {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 19px;
            top: 50px;
            bottom: -50px;
            width: 2px;
            background: var(--border-color);
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-dot {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--secondary);
            border: 3px solid var(--card-bg);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            z-index: 1;
        }

        .timeline-content {
            flex: 1;
            background: rgba(0, 212, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
        }

        .timeline-content h5 {
            color: var(--text-light);
            margin-bottom: 5px;
        }

        .timeline-content p {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 8px;
        }

        .timeline-content .meta {
            display: flex;
            gap: 15px;
            font-size: 12px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon"><i class="fas fa-leaf"></i></div>
            <div class="logo-text">WowoClean</div>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="#dashboard" class="nav-link active" onclick="navigateTo('dashboard', event)">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#containers" class="nav-link" onclick="navigateTo('containers', event)">
                    <i class="fas fa-boxes"></i>
                    <span>Manajemen Kontainer</span>
                </a>
            </li>
            <li>
                <a href="#tracking-logs" class="nav-link" onclick="navigateTo('tracking-logs', event)">
                    <i class="fas fa-map-location-dot"></i>
                    <span>Tracking Logs</span>
                </a>
            </li>
            <li>
                <a href="#profile" class="nav-link" onclick="navigateTo('profile', event)">
                    <i class="fas fa-user-circle"></i>
                    <span>Profil</span>
                </a>
            </li>
            <li>
                <a href="#documentation" class="nav-link" onclick="openSwagger()">
                    <i class="fas fa-book"></i>
                    <span>API Docs</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Top Navigation -->
    <div class="topbar" id="topbar">
        <div class="topbar-left">
            <button class="toggle-sidebar-btn" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="globalSearch" placeholder="Cari kontainer...">
            </div>
        </div>
        <div class="topbar-right">
            <button class="btn-secondary-custom btn-sm" onclick="toggleTheme()">
                <i class="fas fa-moon"></i>
            </button>
            <div class="user-menu" onclick="toggleUserMenu()">
                <div class="user-avatar" id="userAvatar">A</div>
                <div>
                    <div style="font-size: 14px; font-weight: 600;" id="userName">Admin</div>
                    <div style="font-size: 12px; color: var(--text-muted);" id="userRole">Administrator</div>
                </div>
                <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-muted);"></i>
            </div>
            <div id="userMenuDropdown" style="display: none; position: absolute; top: 70px; right: 20px; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; z-index: 9999;">
                <a href="#" onclick="navigateTo('profile'); return false;" style="display: block; padding: 10px 15px; color: var(--text-light); text-decoration: none; border-bottom: 1px solid var(--border-color);">
                    <i class="fas fa-user"></i> Profil
                </a>
                <a href="#" onclick="logout(); return false;" style="display: block; padding: 10px 15px; color: var(--danger); text-decoration: none;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Dashboard Page -->
        <div id="dashboardPage" class="page-content">
            <div class="breadcrumb-nav">
                <a href="#"><i class="fas fa-home"></i></a>
                <span>/</span>
                <span>Dashboard</span>
            </div>
            <div class="page-header">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-chart-line"></i> Dashboard
                        <small>Sistem Manajemen Limbah B3</small>
                    </h1>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid" id="statsContainer">
                <div class="stat-card">
                    <div class="stat-icon primary"><i class="fas fa-cube"></i></div>
                    <div class="stat-value" id="totalContainers">0</div>
                    <div class="stat-label">Total Kontainer</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-value" id="activeContainers">0</div>
                    <div class="stat-label">Kontainer Aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon warning"><i class="fas fa-tools"></i></div>
                    <div class="stat-value" id="maintenanceContainers">0</div>
                    <div class="stat-label">Dalam Perawatan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon danger"><i class="fas fa-archive"></i></div>
                    <div class="stat-value" id="archivedContainers">0</div>
                    <div class="stat-label">Kontainer Archived</div>
                </div>
            </div>

            <!-- Charts Row -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;">
                <div class="chart-container">
                    <h5 style="color: var(--text-light); margin-bottom: 20px;">
                        <i class="fas fa-pie-chart"></i> Distribusi Status Kontainer
                    </h5>
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="chart-container">
                    <h5 style="color: var(--text-light); margin-bottom: 20px;">
                        <i class="fas fa-bar-chart"></i> Aktivitas Tracking (30 Hari)
                    </h5>
                    <canvas id="activityChart"></canvas>
                </div>
            </div>

            <!-- Recent Activity -->
            <div style="margin-top: 30px;">
                <h5 style="color: var(--text-light); margin-bottom: 20px;">
                    <i class="fas fa-history"></i> Aktivitas Terbaru
                </h5>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Container</th>
                                <th>Lokasi</th>
                                <th>Operator</th>
                                <th>Status Perjalanan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody id="recentLogsBody">
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 40px;">
                                    <div style="font-size: 24px; margin-bottom: 10px;"><i class="fas fa-inbox"></i></div>
                                    <div>Tidak ada data aktivitas</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Containers Management Page -->
        <div id="containersPage" class="page-content" style="display: none;">
            <div class="breadcrumb-nav">
                <a href="#" onclick="navigateTo('dashboard'); return false;"><i class="fas fa-home"></i></a>
                <span>/</span>
                <span>Manajemen Kontainer</span>
            </div>
            <div class="page-header">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-boxes"></i> Manajemen Kontainer
                        <small>Kelola semua kontainer limbah B3</small>
                    </h1>
                </div>
                <div id="adminOnlyActions">
                    <button class="btn-primary-custom" onclick="openAddContainerModal()">
                        <i class="fas fa-plus"></i> Tambah Kontainer
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px;">
                <div class="form-group" style="margin: 0;">
                    <input type="text" id="searchInput" placeholder="Cari kode atau lokasi..." onkeyup="debounceSearch()">
                </div>
                <div class="form-group" style="margin: 0;">
                    <select id="statusFilter" onchange="loadContainers()">
                        <option value="">Semua Status</option>
                        <option value="Active">Aktif</option>
                        <option value="Maintenance">Perawatan</option>
                        <option value="Full">Penuh</option>
                        <option value="Archived">Archived</option>
                    </select>
                </div>
                <div class="form-group" style="margin: 0;">
                    <select id="jenisFilter" onchange="loadContainers()">
                        <option value="">Semua Jenis</option>
                        <option value="Chemical">Chemical</option>
                        <option value="Medical">Medical</option>
                        <option value="Electronic">Electronic</option>
                        <option value="Radioactive">Radioactive</option>
                    </select>
                </div>
            </div>

            <!-- Containers Table -->
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode Container</th>
                            <th>Jenis Limbah</th>
                            <th>Kapasitas (kg)</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Logs</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="containersTableBody">
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px;">
                                <div class="skeleton-loader" style="width: 100%; height: 20px;"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-custom" id="paginationContainer"></div>
        </div>

        <!-- Tracking Logs Page -->
        <div id="trackingLogsPage" class="page-content" style="display: none;">
            <div class="breadcrumb-nav">
                <a href="#" onclick="navigateTo('dashboard'); return false;"><i class="fas fa-home"></i></a>
                <span>/</span>
                <span>Tracking Logs</span>
            </div>
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-map-location-dot"></i> Tracking Logs
                    <small>Histori perjalanan kontainer limbah</small>
                </h1>
            </div>
            <div class="timeline" id="trackingLogsTimeline">
                <div style="text-align: center; color: var(--text-muted); padding: 40px;">
                    <i class="fas fa-inbox" style="font-size: 40px; margin-bottom: 10px; display: block;"></i>
                    Memuat data tracking...
                </div>
            </div>
        </div>

        <!-- Profile Page -->
        <div id="profilePage" class="page-content" style="display: none;">
            <div class="breadcrumb-nav">
                <a href="#" onclick="navigateTo('dashboard'); return false;"><i class="fas fa-home"></i></a>
                <span>/</span>
                <span>Profil</span>
            </div>
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-user-circle"></i> Profil Saya
                    <small>Informasi akun pribadi</small>
                </h1>
            </div>
            <div style="max-width: 600px;">
                <div class="stat-card">
                    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                        <div class="user-avatar" style="width: 80px; height: 80px; font-size: 32px;" id="profileAvatar">A</div>
                        <div>
                            <div style="font-size: 22px; font-weight: 700; color: var(--text-light); margin-bottom: 5px;" id="profileName">Admin</div>
                            <div style="font-size: 14px; color: var(--text-muted); margin-bottom: 5px;" id="profileEmail">admin@wowoclean.com</div>
                            <div style="display: inline-block; padding: 5px 12px; background: rgba(0, 201, 167, 0.15); color: var(--success); border-radius: 20px; font-size: 12px;" id="profileRole">Administrator</div>
                        </div>
                    </div>
                    <hr style="border-color: var(--border-color); margin: 20px 0;">
                    <div style="display: grid; gap: 15px;">
                        <div>
                            <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">Email</div>
                            <div id="profileEmailDetail" style="color: var(--text-light); font-weight: 500;">admin@wowoclean.com</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">Role</div>
                            <div id="profileRoleDetail" style="color: var(--text-light); font-weight: 500;">Administrator</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">Member Sejak</div>
                            <div id="profileCreatedAt" style="color: var(--text-light); font-weight: 500;">2026-05-29</div>
                        </div>
                    </div>
                    <button class="btn-danger-custom" onclick="logout()" style="margin-top: 20px; width: 100%;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </div>
        </div>

        <!-- Unauthorized Page -->
        <div id="unauthorizedPage" class="page-content" style="display: none; text-align: center; padding: 60px 20px;">
            <div style="font-size: 80px; color: var(--danger); margin-bottom: 20px;"><i class="fas fa-lock"></i></div>
            <h1 class="page-title" style="margin-bottom: 10px;">403 - Akses Ditolak</h1>
            <p style="color: var(--text-muted); margin-bottom: 30px;">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
            <button class="btn-primary-custom" onclick="navigateTo('dashboard')">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </button>
        </div>
    </div>

    <!-- Add/Edit Container Modal -->
    <div class="modal fade" id="containerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="containerModalTitle">Tambah Kontainer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="containerForm">
                        <div class="form-group">
                            <label>Kode Kontainer</label>
                            <input type="text" id="kodeContainer" required>
                        </div>
                        <div class="form-group">
                            <label>Jenis Limbah</label>
                            <select id="jenisLimbah" required>
                                <option value="">Pilih Jenis</option>
                                <option value="Chemical">Chemical</option>
                                <option value="Medical">Medical</option>
                                <option value="Electronic">Electronic</option>
                                <option value="Radioactive">Radioactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kapasitas (kg)</label>
                            <input type="number" id="kapasitas" min="10" max="10000" required>
                        </div>
                        <div class="form-group">
                            <label>Lokasi</label>
                            <input type="text" id="lokasi" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select id="status">
                                <option value="Active">Aktif</option>
                                <option value="Maintenance">Perawatan</option>
                                <option value="Full">Penuh</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="background: rgba(0, 212, 255, 0.05); border-top: 1px solid var(--border-color); padding: 15px;">
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-primary-custom" onclick="saveContainer()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tracking Log Modal -->
    <div class="modal fade" id="trackingLogModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tracking Log</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="trackingLogForm">
                        <input type="hidden" id="trackingContainerId">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" id="trackingTanggal" required>
                        </div>
                        <div class="form-group">
                            <label>Lokasi</label>
                            <input type="text" id="trackingLokasi" required>
                        </div>
                        <div class="form-group">
                            <label>Status Perjalanan</label>
                            <select id="trackingStatus" required>
                                <option value="" disabled selected>Pilih Status</option>
                                <option value="Received">Diterima</option>
                                <option value="In Transit">Dalam Perjalanan</option>
                                <option value="Processing">Sedang Diproses</option>
                                <option value="Full">Penuh</option>
                                <option value="Maintenance">Perawatan</option>
                                <option value="Inspection">Inspeksi</option>
                                <option value="Monitoring">Monitoring</option>
                                <option value="Repaired">Diperbaiki</option>
                                <option value="Completed">Selesai</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Operator</label>
                            <input type="text" id="trackingOperator" required>
                        </div>
                        <div class="form-group">
                            <label>Catatan</label>
                            <textarea id="trackingCatatan" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="background: rgba(0, 212, 255, 0.05); border-top: 1px solid var(--border-color); padding: 15px;">
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-primary-custom" onclick="saveTrackingLog()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Penghapusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="text-align: center;">
                    <div style="font-size: 48px; color: var(--danger); margin-bottom: 15px;"><i class="fas fa-exclamation-triangle"></i></div>
                    <p style="color: var(--text-light); margin-bottom: 10px;">Anda yakin ingin menghapus kontainer ini?</p>
                    <p style="color: var(--text-muted); font-size: 14px;">Aksi ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer" style="background: rgba(0, 212, 255, 0.05); border-top: 1px solid var(--border-color); padding: 15px;">
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-danger-custom" onclick="confirmDelete()">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.2/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="/js/app.js?v=2"></script>
</body>
</html>
