<!-- Admin Sidebar Partial -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --sidebar-bg: #0B1E3D;
            --sidebar-hover-bg: rgba(245, 158, 11, 0.15);
            --sidebar-active-bg: #F59E0B;
            --sidebar-text: #ffffff;
            --sidebar-text-muted: rgba(255, 255, 255, 0.6);
            --sidebar-icon: #ffffff;
            --sidebar-icon-muted: rgba(255, 255, 255, 0.5);
            --sidebar-border: rgba(255, 255, 255, 0.08);
            --sidebar-width: 270px;
            --sidebar-collapsed: 90px;
            --sidebar-mobile: 85%;
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --mobile-overlay: rgba(0, 0, 0, 0.6);
            --gradient-start: #F59E0B;
            --gradient-end: #D97706;
        }

        /* Mobile Hamburger Button */
        .mobile-hamburger {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            width: 44px;
            height: 44px;
            background: var(--sidebar-active-bg);
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            transition: all var(--transition);
        }

        .mobile-hamburger:hover {
            background: #D97706;
            transform: scale(1.05);
        }

        /* Mobile Close Button */
        .mobile-close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            z-index: 1002;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all var(--transition);
        }

        .mobile-close-btn:hover {
            background: rgba(245, 158, 11, 0.3);
            transform: rotate(90deg);
            color: var(--sidebar-active-bg);
        }

        @media (max-width: 768px) {
            .sidebar.active .mobile-close-btn {
                display: flex !important;
            }
        }

        /* Mobile Overlay */
        .mobile-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--mobile-overlay);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: opacity var(--transition), visibility var(--transition);
        }

        .mobile-overlay.active {
            opacity: 1;
            visibility: visible;
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
            z-index: 1050;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: var(--sidebar-active-bg) transparent;
            transition: all var(--transition);
            transform: translateX(0);
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.2);
        }

        .sidebar.mobile-slide-in {
            animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            box-shadow: 5px 0 30px rgba(0, 0, 0, 0.3);
        }

        .sidebar.mobile-slide-out {
            animation: slideOut 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(-100%);
                opacity: 0;
            }
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--sidebar-active-bg);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #D97706;
        }

        /* Sidebar Collapsed */
        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
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
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            position: relative;
        }

        .sidebar-brand-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }

        .sidebar-brand img {
            width: 45px;
            height: 35px;
            border-radius: 8px;
            transition: transform var(--transition);
        }

        .sidebar-brand img:hover {
            transform: scale(1.05);
        }

        .sidebar-brand h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--sidebar-text);
            white-space: nowrap;
            transition: opacity var(--transition), width var(--transition);
            letter-spacing: 1px;
        }

        .sidebar-brand .toggle-btn {
            width: 38px;
            height: 38px;
            border: none;
            background: rgba(245, 158, 11, 0.15);
            color: var(--sidebar-text);
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
            background: var(--sidebar-active-bg);
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

        /* Main NAV LINK & TOGGLE STYLES */
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
            border-radius: 12px;
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
            background: var(--sidebar-active-bg);
            transform: translateY(-50%);
            transition: height var(--transition);
            border-radius: 0 3px 3px 0;
        }

        /* Hover STATE */
        .nav-link:hover,
        .nav-toggle:hover {
            background: var(--sidebar-hover-bg);
            color: var(--sidebar-active-bg) !important;
            padding-left: 1.2rem;
        }

        .nav-link:hover .nav-text,
        .nav-toggle:hover .nav-text {
            color: var(--sidebar-active-bg) !important;
        }

        .nav-link:hover .nav-icon,
        .nav-toggle:hover .nav-icon {
            color: var(--sidebar-active-bg) !important;
        }

        .nav-link:hover .chevron,
        .nav-toggle:hover .chevron {
            color: var(--sidebar-active-bg) !important;
        }

        .nav-link:hover::before,
        .nav-toggle:hover::before {
            height: 20px;
        }

        /* ACTIVE STATE */
        .nav-link.active,
        .nav-toggle.active {
            background: var(--sidebar-active-bg) !important;
            color: var(--sidebar-text) !important;
            font-weight: 600;
        }

        .nav-link.active .nav-text,
        .nav-toggle.active .nav-text {
            color: var(--sidebar-text) !important;
        }

        .nav-link.active .nav-icon,
        .nav-toggle.active .nav-icon {
            color: var(--sidebar-text) !important;
        }

        .nav-link.active .chevron,
        .nav-toggle.active .chevron {
            color: var(--sidebar-text) !important;
        }

        .nav-link.active::before,
        .nav-toggle.active::before {
            height: 24px;
            background: var(--sidebar-text) !important;
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

        .nav-text {
            white-space: nowrap;
            overflow: hidden;
            color: var(--sidebar-text);
            text-overflow: ellipsis;
            transition: color var(--transition);
        }

        .chevron {
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            flex-shrink: 0;
            transition: transform var(--transition), color var(--transition);
            color: var(--sidebar-icon-muted);
        }

        .nav-toggle[aria-expanded="false"] .chevron {
            transform: rotate(0deg);
        }

        .nav-toggle[aria-expanded="true"] .chevron {
            transform: rotate(90deg);
        }

        /* SUBMENU STYLES */
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
            background: var(--sidebar-icon-muted);
            border-radius: 50%;
            opacity: 0.5;
            transition: all var(--transition);
        }

        /* Submenu hover */
        .submenu .nav-link:hover {
            padding-left: 3rem;
            color: var(--sidebar-active-bg) !important;
        }

        .submenu .nav-link:hover .nav-text {
            color: var(--sidebar-active-bg) !important;
        }

        .submenu .nav-link:hover::after {
            opacity: 1;
            left: 1.1rem;
            background: var(--sidebar-active-bg) !important;
        }

        /* Submenu active */
        .submenu .nav-link.active {
            background: var(--sidebar-active-bg) !important;
            color: var(--sidebar-text) !important;
        }

        .submenu .nav-link.active .nav-text {
            color: var(--sidebar-text) !important;
        }

        .submenu .nav-link.active::after {
            background: var(--sidebar-text) !important;
            opacity: 1;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 1.25rem 1.25rem;
            border-top: 1px solid var(--sidebar-border);
            margin-top: auto;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 65px;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
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
            color: var(--sidebar-text);
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--sidebar-text-muted);
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
            color: var(--gradient-start);
            text-transform: uppercase;
            letter-spacing: 1px;
            border-top: 1px solid var(--sidebar-border);
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .sidebar.collapsed .sidebar-footer-brand {
            font-size: 0.6rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition), padding var(--transition);
            min-height: 100vh;
            padding: 2rem;
            background: #f5f7fa;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed);
        }

        /* Security Button */
        .security-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
            transition: all var(--transition);
            text-decoration: none;
            z-index: 999;
        }

        .security-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(245, 158, 11, 0.6);
            background: linear-gradient(135deg, #D97706, #B45309);
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .mobile-hamburger {
                display: flex !important;
            }

            .sidebar {
                width: var(--sidebar-mobile);
                max-width: 350px;
                transform: translateX(-100%);
                z-index: 1001;
                box-shadow: 5px 0 30px rgba(0, 0, 0, 0.3);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-brand .toggle-btn {
                display: none;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 1rem;
                padding-top: 80px;
            }

            .security-btn {
                bottom: 20px;
                right: 20px;
                width: 48px;
                height: 48px;
                font-size: 1.3rem;
            }

            .sidebar.active .sidebar-brand {
                padding-right: 3.5rem;
            }

            .sidebar-brand {
                padding: 1rem 0.75rem;
            }

            .nav-menu {
                padding: 0.75rem 0.5rem;
            }

            .nav-item {
                margin-bottom: 0.4rem;
            }

            .sidebar-footer {
                padding: 0.75rem;
                min-height: 55px;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 90%;
            }

            .mobile-hamburger {
                top: 15px;
                left: 15px;
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }

            .main-content {
                padding: 0.75rem;
                padding-top: 70px;
            }

            .sidebar-brand {
                padding: 0.75rem 0.5rem;
            }

            .sidebar-brand img {
                width: 35px;
                height: 28px;
            }

            .nav-menu {
                padding: 0.5rem 0.4rem;
            }

            .nav-link,
            .nav-toggle {
                padding: 0.6rem 0.75rem;
            }

            .sidebar-footer {
                padding: 0.6rem;
            }

            .user-avatar {
                width: 36px;
                height: 36px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 380px) {
            .sidebar {
                width: 85%;
            }

            .sidebar-brand {
                padding: 0.6rem 0.4rem;
            }

            .sidebar-brand h3 {
                display: none;
            }

            .nav-menu {
                padding: 0.4rem 0.3rem;
            }

            .nav-link,
            .nav-toggle {
                padding: 0.5rem 0.5rem;
                border-radius: 8px;
            }

            .sidebar-footer {
                padding: 0.5rem 0.4rem;
                gap: 8px;
            }

            .user-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }
        }

        body.sidebar-open {
            overflow: hidden;
        }

        /* Additional polish */
        .sidebar-footer-brand:hover {
            color: var(--gradient-start);
            cursor: pointer;
        }

        .logout-link {
            color: #ff6b6b !important;
        }

        .logout-link:hover {
            background: rgba(255, 107, 107, 0.15) !important;
            color: #ff6b6b !important;
        }

        /* Emergency Banner Animation */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.85; }
        }
    </style>
    <script>
  AOS.init();
</script>
    <!-- Mobile Hamburger Button -->
    <button class="mobile-hamburger" id="mobileHamburger" aria-label="Open menu">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Sidebar -->
    <nav id="sidebarMenu" class="sidebar d-print-none">
        <!-- Close Button (Mobile) -->
        <button class="mobile-close-btn" id="mobileCloseBtn" aria-label="Close menu">
            <i class="fas fa-times"></i>
        </button>

        <!-- Brand -->
        <div class="sidebar-brand">
            <div class="sidebar-brand-content">
                <img src="{{ asset('/public/images/leruma.png') }}" alt="Elite Logo">
                <h3>LERUMA POS</h3>
            </div>
            <button class="toggle-btn" id="sidebarToggle" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Emergency Access Banner (shown only during emergency session) -->
        @if(session('emergency_access'))
        <div style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a5a 100%); padding: 0.75rem 1.25rem; margin: 0 0.5rem; border-radius: 10px; color: white; font-size: 0.85rem; display: flex; align-items: center; gap: 8px; animation: pulse 2s infinite;">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>EMERGENCY ACCESS ACTIVE</strong><br>
                <small>Session expires: {{ \Carbon\Carbon::parse(session('emergency_expires_at'))->diffForHumans() }}</small>
            </div>
        </div>
        @endif

        <!-- Navigation Menu -->
        <ul class="nav-menu" id="navMenu">
            <!-- Dashboard -->
            <li class="nav-item" data-aos="fade-left">
                <a href="/admin/dashboard" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </div>
                </a>
            </li>

            <!-- Supplier with Dropdown -->
            @if (canUser('view_suppliers'))
            <li class="nav-item" data-aos="fade-left" data-aos-delay="100">
                <button class="nav-toggle" aria-expanded="false" aria-controls="supplier-menu">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-user-tie"></i></span>
                        <span class="nav-text">Supplier</span>
                    </div>
                    <span class="chevron"><i class="fas fa-chevron-right"></i></span>
                </button>
                <ul class="submenu" id="supplier-menu">
                    <li class="nav-item">
                        <a href="/admin/vendors" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">All Suppliers</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/supplier-credit" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Supplier Credit</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            <!-- Banking with Dropdown -->
            @if (canUser('view_banking'))
            <li class="nav-item" data-aos="fade-left" data-aos-delay="200">
                <button class="nav-toggle" aria-expanded="false" aria-controls="banking-menu">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-university"></i></span>
                        <span class="nav-text">Banking</span>
                    </div>
                    <span class="chevron"><i class="fas fa-chevron-right"></i></span>
                </button>
                <ul class="submenu" id="banking-menu">
                    <li class="nav-item">
                        <a href="/admin/banking-partners" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Banking Partners</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/banking-transfers" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Banking Deposit</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/banking/supplier-deposit-report" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Supplier Deposit Report</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            <!-- Customers with Dropdown -->
            @if (canUser('view_customers'))
            <li class="nav-item" data-aos="fade-left" data-aos-delay="300">
                <button class="nav-toggle" aria-expanded="false" aria-controls="customers-menu">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-users"></i></span>
                        <span class="nav-text">Customers</span>
                    </div>
                    <span class="chevron"><i class="fas fa-chevron-right"></i></span>
                </button>
                <ul class="submenu" id="customers-menu">
                    <li class="nav-item">
                        <a href="/admin/customers" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">All Customers</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/customer-kpi" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Customer KPI</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            
            <!-- Items with Dropdown -->
            @if (canUser('view_items'))
            <li class="nav-item" data-aos="fade-left" data-aos-delay="400">
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
                        <a href="/admin/products" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">All Items</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (canUser('create_items'))
                    <li class="nav-item">
                        <a href="/admin/newProducts" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">New Item</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="/admin/itemRequest" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Item Request</span>
                            </div>
                        </a>
                    </li>
                     <li class="nav-item">
                        <a href="/admin/viewRequest" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">View Request</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/items-report" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Items Report</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            <!-- Receiving with Dropdown -->
            @if (canUser('view_receivings'))
            <li class="nav-item" data-aos="fade-left" data-aos-delay="500">
                <button class="nav-toggle" aria-expanded="false" aria-controls="receiving-menu">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-truck-loading"></i></span>
                        <span class="nav-text">Receiving & Returns</span>
                    </div>
                    <span class="chevron"><i class="fas fa-chevron-right"></i></span>
                </button>
                <ul class="submenu" id="receiving-menu">
                    <li class="nav-item">
                        <a href="/admin/make-receiving" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Make Receiving</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/view-receivings" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">View Receivings</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/make-return" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Make Return</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/view-returns" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">View Returns</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/receiving-report" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Report</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            <!-- New Sale -->
            @if (canUser('create_sales'))
            <li class="nav-item" data-aos="fade-left" data-aos-delay="600">
                <a href="/admin/newOrder" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-shopping-cart"></i></span>
                        <span class="nav-text">Sales</span>
                    </div>
                </a>
            </li>
            @endif

            <!-- Order Lists -->
            @if (canUser('view_invoices'))
            <li class="nav-item" data-aos="fade-left" data-aos-delay="700">
                <button class="nav-toggle" aria-expanded="false" aria-controls="invoices-menu">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-list-check"></i></span>
                        <span class="nav-text">Invoices</span>
                    </div>
                    <span class="chevron"><i class="fas fa-chevron-right"></i></span>
                </button>
                <ul class="submenu" id="invoices-menu">
                    <li class="nav-item">
                        <a href="/admin/ordersList" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Current Shop</span>
                            </div>
                        </a>
                    </li>
                    @if (canUser('view_all_chips'))
                    <li class="nav-item">
                        <a href="/admin/banking-chips" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Chip</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (canUser('view_shop_debts'))
                    <li class="nav-item">
                        <a href="{{ url('/admin/shopInvoices') }}" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Shop Debts</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin/paidInvoices') }}" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Paid Invoices</span>
                            </div>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            <!-- Reports with Dropdown -->
            @if (canUser('view_reports'))
            <li class="nav-item" data-aos="fade-left" data-aos-delay="800">
                <button class="nav-toggle" aria-expanded="false" aria-controls="report-menu">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
                        <span class="nav-text">Reports</span>
                    </div>
                    <span class="chevron"><i class="fas fa-chevron-right"></i></span>
                </button>
                <ul class="submenu" id="report-menu">
                    @if (canUser('view_shops_report'))
                    <li class="nav-item">
                        <a href="/admin/shopReport" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Shops Report</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (canUser('view_full_report'))
                    <li class="nav-item">
                        <a href="/admin/fullReport" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Full Report</span>
                            </div>
                        </a>
                    </li>
                    @endif
                     @if (canUser('view_shops_report'))
                    <li class="nav-item">
                        <a href="/admin/mainStoreReport" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Main Store Report</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (canUser('view_sales_report'))
                    <li class="nav-item">
                        <a href="/admin/sales" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Sales Report</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (canUser('view_stock_report'))
                    <li class="nav-item">
                        <a href="/admin/stock-report" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Stock Report</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @if (canUser('view_reports'))
                    <li class="nav-item">
                        <a href="/admin/offeredProductsReport" class="nav-link">
                            <div class="nav-content">
                                <span class="nav-text">Offered Products</span>
                            </div>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            <!-- Expenses -->
            @if (canUser('view_expenses'))
            <li class="nav-item" data-aos="fade-left" data-aos-delay="100">
                <a href="/admin/expenses" class="nav-link">
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
                <a href="/admin/employees" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-user-group"></i></span>
                        <span class="nav-text">Users</span>
                    </div>
                </a>
            </li>
            @endif

            <!-- KPI Dashboard -->
            @if (canUser('view_reports'))
            <li class="nav-item">
                <a href="/admin/kpi" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                        <span class="nav-text">KPI Dashboard</span>
                    </div>
                </a>
            </li>
            @endif

            <!-- Ads -->
            @if (canUser('view_ads'))
            <li class="nav-item">
                <a href="/admin/ads" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-window-maximize"></i></span>
                        <span class="nav-text">Ads</span>
                    </div>
                </a>
            </li>
            @endif

            <!-- Logs -->
            @if (canUser('view_logs'))
            <li class="nav-item">
                <a href="/admin/logs" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-file-alt"></i></span>
                        <span class="nav-text">Logs</span>
                    </div>
                </a>
            </li>
            @endif

            <!-- Settings -->
            @if (canUser('view_settings'))
            <li class="nav-item">
                <a href="/admin/settings" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-cog"></i></span>
                        <span class="nav-text">Settings</span>
                    </div>
                </a>
            </li>
            @endif

            <!-- Account Migration (Admin only) -->
            @if (Auth::user()->levelStatus === 'Admin2')
            <li class="nav-item">
                <a href="/admin/migration" class="nav-link">
                    <div class="nav-content">
                        <span class="nav-icon"><i class="fas fa-database"></i></span>
                        <span class="nav-text">Account Migration</span>
                    </div>
                </a>
            </li>
            @endif

            <!-- Logout -->
            <li class="nav-item">
                <a href="/admin/signout" class="nav-link logout-link">
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
                <div class="user-role">
                    {{ Auth::user()->levelStatus }}
                    @if(session('emergency_access'))
                        <span class="badge bg-danger ms-2" style="font-size: 0.65rem; padding: 2px 6px; border-radius: 4px; vertical-align: middle;">
                            <i class="fas fa-exclamation-triangle me-1"></i>EMERGENCY
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Security Button -->
    <a href="/admin/security" class="security-btn" title="Security">
        <i class="fas fa-shield-alt"></i>
    </a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const sidebar = document.getElementById('sidebarMenu');
            const toggleBtn = document.getElementById('sidebarToggle');
            const mobileHamburger = document.getElementById('mobileHamburger');
            const mobileCloseBtn = document.getElementById('mobileCloseBtn');
            const mobileOverlay = document.getElementById('mobileOverlay');
            const body = document.body;
            const isMobile = window.innerWidth <= 768;
            
            let isSidebarOpen = false;

            // Desktop Toggle
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!isMobile) {
                    sidebar.classList.toggle('collapsed');
                }
            });

            // Mobile Hamburger Click
            function openSidebar() {
                isSidebarOpen = true;
                sidebar.classList.add('active');
                sidebar.classList.remove('mobile-slide-out');
                sidebar.classList.add('mobile-slide-in');
                mobileOverlay.classList.add('active');
                body.classList.add('sidebar-open');
                document.addEventListener('keydown', handleEscapeKey);
            }

            function closeSidebar() {
                isSidebarOpen = false;
                sidebar.classList.remove('mobile-slide-in');
                sidebar.classList.add('mobile-slide-out');
                setTimeout(() => {
                    sidebar.classList.remove('active');
                    mobileOverlay.classList.remove('active');
                    body.classList.remove('sidebar-open');
                }, 300);
                document.removeEventListener('keydown', handleEscapeKey);
            }

            function handleEscapeKey(e) {
                if (e.key === 'Escape' && isSidebarOpen) {
                    closeSidebar();
                }
            }

            mobileHamburger.addEventListener('click', openSidebar);
            mobileCloseBtn.addEventListener('click', closeSidebar);
            mobileOverlay.addEventListener('click', closeSidebar);

            // Dropdown Toggles
            const navToggles = document.querySelectorAll('.nav-toggle');
            navToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const menuId = this.getAttribute('aria-controls');
                    const menu = document.getElementById(menuId);
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';

                    navToggles.forEach(otherToggle => {
                        if (otherToggle !== this) {
                            const otherId = otherToggle.getAttribute('aria-controls');
                            const otherMenu = document.getElementById(otherId);
                            if (otherMenu) {
                                otherMenu.classList.remove('show');
                                otherToggle.setAttribute('aria-expanded', 'false');
                            }
                        }
                    });

                    if (menu) {
                        menu.classList.toggle('show');
                        this.setAttribute('aria-expanded', !isExpanded);
                    }
                });
            });

            // Active Link Handler
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && window.location.pathname === href) {
                    link.classList.add('active');

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

            // Handle nav link clicks - only close sidebar on mobile
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (isMobile && !this.classList.contains('nav-toggle')) {
                        setTimeout(closeSidebar, 100);
                    }
                });
            });

            // Click outside to close dropdowns (desktop only)
            if (!isMobile) {
                document.addEventListener('click', function(e) {
                    const isToggle = e.target.closest('.nav-toggle');
                    const isSubmenu = e.target.closest('.submenu');
                    const isNavMenu = e.target.closest('.nav-menu');
                    
                    if (!isToggle && !isSubmenu && !isNavMenu) {
                        navToggles.forEach(toggle => {
                            const menuId = toggle.getAttribute('aria-controls');
                            const menu = document.getElementById(menuId);
                            if (menu) {
                                menu.classList.remove('show');
                                toggle.setAttribute('aria-expanded', 'false');
                            }
                        });
                    }
                });
            }

            // Handle window resize
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    const newIsMobile = window.innerWidth <= 768;
                    if (isMobile !== newIsMobile) {
                        location.reload();
                    }
                }, 250);
            });

            // Store sidebar state in localStorage
            if (!isMobile) {
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                }

                sidebar.addEventListener('transitionend', function() {
                    if (!isMobile) {
                        localStorage.setItem('sidebarCollapsed', 
                            sidebar.classList.contains('collapsed')
                        );
                    }
                });
            }
        });
    </script>