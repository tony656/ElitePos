<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite POS - Sidebar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --sidebar-bg: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            --sidebar-hover: rgba(30, 47, 77, 0.8);
            --sidebar-active: #06b6d4;
            --sidebar-text: #e2e8f0;
            --sidebar-icon: #cbd5e1;
            --sidebar-width: 270px;
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            padding: 0 0.5rem;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.5);
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: #475569 transparent;
            transition: width var(--transition);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* Sidebar Collapsed */
        .sidebar.collapsed {
            width: 90px;
        }

        .sidebar.collapsed .sidebar-brand h3 {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar.collapsed .sidebar-brand-content {
            justify-content: center;
            width: 100%;
        }

        .sidebar.collapsed .sidebar-brand img {
            display: none;
        }

        .sidebar.collapsed .nav-text {
            display: none;
        }

        .sidebar.collapsed .submenu {
            display: none !important;
        }

        .sidebar.collapsed .nav-link,
        .sidebar.collapsed .nav-toggle {
            justify-content: center;
            padding: 0.75rem;
            position: relative;
        }

        .sidebar.collapsed .nav-toggle .chevron {
            position: absolute;
            right: 8px;
            bottom: 8px;
            width: 16px;
            height: 16px;
        }

        .sidebar.collapsed .chevron {
            display: flex;
        }

        /* Brand Section */
        .sidebar-brand {
            padding: 1.75rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .sidebar-brand-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }

        .sidebar-brand img {
            width: 45px;
            height: 45px;
            border-radius: 8px;
            transition: transform var(--transition);
        }

        .sidebar-brand img:hover {
            transform: scale(1.05);
        }

        .sidebar-brand h3 {
            font-size: 1.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #60a5fa, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            white-space: nowrap;
            transition: opacity var(--transition), width var(--transition);
        }

        .sidebar-brand .toggle-btn {
            width: 38px;
            height: 38px;
            border: none;
            background: rgba(6, 182, 212, 0.15);
            color: var(--sidebar-active);
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: all var(--transition);
            flex-shrink: 0;
        }

        .sidebar-brand .toggle-btn:hover {
            background: var(--sidebar-active);
            color: white;
            transform: scale(1.08);
        }

        /* Navigation */
        .nav-menu {
            list-style: none;
            flex: 1;
            padding: 1rem 0.75rem;
            overflow-y: auto;
        }

        .nav-item {
            margin-bottom: 0.6rem;
        }

        .nav-link,
        .nav-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.8rem 1rem;
            color: var(--sidebar-text);
            text-decoration: none;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: 10px;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
        }

        .nav-link::before,
        .nav-toggle::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 3px;
            height: 0;
            background: var(--sidebar-active);
            transform: translateY(-50%);
            transition: height var(--transition);
            border-radius: 0 3px 3px 0;
        }

        .nav-link:hover,
        .nav-toggle:hover {
            background: var(--sidebar-hover);
            color: var(--sidebar-active);
            padding-left: 1.2rem;
        }

        .nav-link:hover::before,
        .nav-toggle:hover::before {
            height: 20px;
        }

        .nav-link.active,
        .nav-toggle.active {
            background: rgba(6, 182, 212, 0.15);
            color: var(--sidebar-active);
            font-weight: 600;
        }

        .nav-link.active::before,
        .nav-toggle.active::before {
            height: 24px;
        }

        .nav-content {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }

        .nav-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sidebar-icon);
            font-size: 1.1rem;
            flex-shrink: 0;
            transition: all var(--transition);
        }

        .nav-link:hover .nav-icon,
        .nav-toggle:hover .nav-icon {
            color: var(--sidebar-active);
            transform: scale(1.2);
        }

        .nav-text {
            white-space: nowrap;
            overflow: hidden;
            color: white;
            text-overflow: ellipsis;
            transition: opacity var(--transition);
        }

        .chevron {
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            flex-shrink: 0;
            transition: transform var(--transition);
            color: var(--sidebar-icon);
        }

        .nav-toggle[aria-expanded="false"] .chevron {
            transform: rotate(0deg);
        }

        .nav-toggle[aria-expanded="true"] .chevron {
            transform: rotate(90deg);
        }

        /* Submenu */
        .submenu {
            list-style: none;
            overflow: hidden;
            transition: max-height var(--transition), opacity var(--transition), padding var(--transition);
            max-height: 0;
            opacity: 0;
            padding: 0;
            margin: 0;
        }

        .submenu.show {
            max-height: 500px;
            opacity: 1;
            padding: 0.5rem 0.75rem 0;
        }

        .submenu .nav-item {
            margin-bottom: 0.4rem;
        }

        .submenu .nav-link {
            padding: 0.65rem 0.75rem 0.65rem 2.8rem;
            font-size: 0.88rem;
            font-weight: 400;
            position: relative;
        }

        .submenu .nav-link::after {
            content: '';
            position: absolute;
            left: 1.3rem;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 4px;
            background: var(--sidebar-active);
            border-radius: 50%;
            opacity: 0.5;
            transition: all var(--transition);
        }

        .submenu .nav-link:hover::after {
            opacity: 1;
            left: 1.1rem;
        }

        .submenu .nav-link:hover {
            padding-left: 3rem;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 1.25rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 65px;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #60a5fa, #06b6d4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(6, 182, 212, 0.3);
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.75rem;
            color: #94a3b8;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar.collapsed .user-info {
            display: none;
        }

        .sidebar-footer-brand {
            text-align: center;
            padding: 0.75rem 0;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--sidebar-active);
            text-transform: uppercase;
            letter-spacing: 1px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .sidebar.collapsed .sidebar-footer-brand {
            font-size: 0.6rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition);
            min-height: 100vh;
            padding: 2rem;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: 90px;
        }

        /* Security Button */
        .security-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0ea5e9, #06b6d4);
            color: white;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.4);
            transition: all var(--transition);
            text-decoration: none;
            z-index: 999;
        }

        .security-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(6, 182, 212, 0.6);
            background: linear-gradient(135deg, #06b6d4, #0284c7);
        }

        /* Responsive */
        @media (max-width: 768px) {
            :root {
                --sidebar-width: 90px;
            }

            .sidebar {
                width: 90px;
            }

            .sidebar-brand h3 {
                display: none;
            }

            .nav-text {
                display: none;
            }

            .submenu {
                display: none !important;
            }

            .chevron {
                display: none;
            }

            .nav-link,
            .nav-toggle {
                justify-content: center;
                padding: 0.75rem;
            }

            .nav-link:hover,
            .nav-toggle:hover {
                padding-left: 0.75rem;
            }

            .main-content {
                margin-left: 90px;
            }
        }

        /* Demo Content */
        .demo-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .demo-header {
            color: #0f172a;
            margin-bottom: 2rem;
        }

        .demo-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #0284c7, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .demo-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebarMenu" class="sidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <div class="sidebar-brand-content">
                <img src="{{ asset('images/EliteLogoW.PNG') }}" alt="Elite Logo">
                <h3>Elite POS</h3>
            </div>
            <button class="toggle-btn" id="sidebarToggle" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <ul class="nav-menu" id="navMenu">
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="dashboard" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </div>
                </a>
            </li>

            <!-- Supplier with Dropdown -->
            @if (canUser('view_suppliers'))
            <li class="nav-item">
                <button class="nav-toggle" aria-expanded="false" aria-controls="supplier-menu">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-user-tie"></i></span>
                        <span class="nav-text">Supplier</span>
                    </div>
                    <span class="chevron"><i class="fas fa-chevron-right"></i></span>
                </button>
                <ul class="submenu" id="supplier-menu">
                    <li class="nav-item">
                        <a href="vendors" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">All Suppliers</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="deptors" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Supplier Credit</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            <!-- Customers -->
            @if (canUser('view_customers'))
            <li class="nav-item">
                <a href="customers" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-users"></i></span>
                        <span class="nav-text">Customers</span>
                    </div>
                </a>
            </li>
            @endif
            <!-- Items with Dropdown -->
            @if (canUser('view_items'))
            <li class="nav-item">
                <button class="nav-toggle" aria-expanded="false" aria-controls="items-menu">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-boxes"></i></span>
                        <span class="nav-text">Items</span>
                    </div>
                    <span class="chevron"><i class="fas fa-chevron-right"></i></span>
                </button>
                <ul class="submenu" id="items-menu">
                    @if (canUser('view_items'))
                    <li class="nav-item">
                        <a href="products" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">All Items</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (canUser('create_items'))
                    <li class="nav-item">
                        <a href="newProducts" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">New Item</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (canUser('view_receivings'))
                    <li class="nav-item">
                        <a href="restock" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Receivings</span>
                            </div>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            <!-- New Sale -->
            @if (canUser('create_sales'))
            <li class="nav-item">
                <a href="newOrder" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-shopping-cart"></i></span>
                        <span class="nav-text">Sales</span>
                    </div>
                </a>
            </li>
            @endif

            <!-- Order Lists -->
            @if (canUser('view_invoices'))
            <li class="nav-item">
                <a href="ordersList" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-list-check"></i></span>
                        <span class="nav-text">Invoices</span>
                    </div>
                </a>
            </li>
            @endif
            <!-- Reports with Dropdown -->
            @if (canUser('view_reports'))
            <li class="nav-item">
                <button class="nav-toggle" aria-expanded="false" aria-controls="report-menu">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
                        <span class="nav-text">Reports</span>
                    </div>
                    <span class="chevron"><i class="fas fa-chevron-right"></i></span>
                </button>
                <ul class="submenu" id="report-menu">
                    @if (canUser('view_full_report'))
                    <li class="nav-item">
                        <a href="fullReport" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Full Report</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (canUser('view_sales_report'))
                    <li class="nav-item">
                        <a href="sales" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Sales Report</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (canUser('view_stock_report'))
                    <li class="nav-item">
                        <a href="stock-report" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Stock Report</span>
                            </div>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            <!-- Expenses -->
            @if (canUser('view_expenses'))
            <li class="nav-item">
                <a href="expenses" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-receipt"></i></span>
                        <span class="nav-text">Expenses</span>
                    </div>
                </a>
            </li>
            @endif
            <!-- Employees -->
            @if (canUser('view_employees'))
            <li class="nav-item">
                <a href="employees" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-user-group"></i></span>
                        <span class="nav-text">Employees</span>
                    </div>
                </a>
            </li>
            @endif


            <!-- Logs -->
            @if (canUser('view_logs'))
            <li class="nav-item">
                <a href="logs" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-file-lines"></i></span>
                        <span class="nav-text">Logs</span>
                    </div>
                </a>
            </li>
            @endif

            <!-- Settings -->
            @if (canUser('view_settings'))
            <li class="nav-item">
                <a href="settings" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-cog"></i></span>
                        <span class="nav-text">Settings</span>
                    </div>
                </a>
            </li>
            @endif
            <!-- Logout -->
            <li class="nav-item">
                <a href="signout" class="nav-link" style="color: #ff6b6b;">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                        <span class="nav-text">Logout</span>
                    </div>
                </a>
            </li>
        </ul>

        <!-- User Footer -->
        <div class="sidebar-footer">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ Auth::user()->levelStatus }}</div>
            </div>
        </div>

        <div class="sidebar-footer-brand">
            {{ session('account') }}
        </div>
    </nav>

    <!-- Security Button -->
    <a href="security" class="security-btn" title="Security">
        <i class="fas fa-shield-alt"></i>
    </a>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle
            const sidebar = document.getElementById('sidebarMenu');
            const toggleBtn = document.getElementById('sidebarToggle');

            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });

            // Dropdown Toggles
            const navToggles = document.querySelectorAll('.nav-toggle');

            navToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();

                    const menuId = this.getAttribute('aria-controls');
                    const menu = document.getElementById(menuId);
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';

                    // Close all other menus
                    navToggles.forEach(otherToggle => {
                        if (otherToggle !== this) {
                            const otherId = otherToggle.getAttribute('aria-controls');
                            const otherMenu = document.getElementById(otherId);
                            otherMenu.classList.remove('show');
                            otherToggle.setAttribute('aria-expanded', 'false');
                        }
                    });

                    // Toggle current menu
                    menu.classList.toggle('show');
                    this.setAttribute('aria-expanded', !isExpanded);
                });
            });

            // Active Link Handler
            const navLinks = document.querySelectorAll('.nav-link');
            const currentPath = window.location.pathname;

            // Set active state on page load
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentPath.includes(href)) {
                    link.classList.add('active');

                    // If parent toggle exists, make it active too
                    const submenu = link.closest('.submenu');
                    if (submenu) {
                        const toggle = document.querySelector(`[aria-controls="${submenu.id}"]`);
                        if (toggle) {
                            toggle.classList.add('active');
                            submenu.classList.add('show');
                            toggle.setAttribute('aria-expanded', 'true');
                        }
                    }
                }
            });

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Remove active from all
                    navLinks.forEach(l => l.classList.remove('active'));
                    navToggles.forEach(t => t.classList.remove('active'));

                    // Add active to clicked
                    this.classList.add('active');

                    // If parent toggle exists, make it active too
                    const submenu = this.closest('.submenu');
                    if (submenu) {
                        const toggle = document.querySelector(`[aria-controls="${submenu.id}"]`);
                        if (toggle) {
                            toggle.classList.add('active');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>