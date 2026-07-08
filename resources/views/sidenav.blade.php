@php
    if(empty(getUserAccounts())) {
        redirect('/signout')->send();
    }
    if(Auth::user()->status === 'banned') {
        redirect('/signout')->send();
    }
@endphp

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
        --sidebar-collapsed: 80px;
        --sidebar-mobile: 85%;
        --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --mobile-overlay: rgba(0, 0, 0, 0.6);
        --gradient-start: #F59E0B;
        --gradient-end: #D97706;
    }

    /* ===== SIDEBAR TOGGLE BUTTON ===== */
    .sidebar-brand .toggle-btn {
        width: 38px;
        height: 38px;
        border: 2px solid rgba(255, 255, 255, 0.15);
        background: rgba(245, 158, 11, 0.15);
        color: var(--sidebar-text);
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        transition: all var(--transition);
        flex-shrink: 0;
        position: relative;
        z-index: 10;
    }

    .sidebar-brand .toggle-btn:hover {
        background: var(--sidebar-active-bg);
        transform: scale(1.08);
        border-color: var(--sidebar-active-bg);
        box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
    }

    /* When sidebar is collapsed, make toggle button stand out */
    .sidebar.collapsed .toggle-btn {
        background: var(--sidebar-active-bg);
        border-color: var(--sidebar-active-bg);
        color: var(--sidebar-bg);
        box-shadow: 0 0 20px rgba(245, 158, 11, 0.3);
    }

    .sidebar.collapsed .toggle-btn:hover {
        transform: scale(1.1) rotate(90deg);
        box-shadow: 0 0 30px rgba(245, 158, 11, 0.5);
    }

    /* ===== FLOATING TOGGLE BUTTON (when collapsed) ===== */
    .sidebar.collapsed .sidebar-brand {
        padding: 1rem 0.5rem;
        justify-content: center;
    }

    .sidebar.collapsed .sidebar-brand-content {
        justify-content: center;
    }

    .sidebar.collapsed .sidebar-brand img {
        width: 40px;
        height: 32px;
    }

    .sidebar.collapsed .sidebar-brand h3 {
        display: none;
    }

    /* Tooltip for collapsed state */
    .sidebar.collapsed .toggle-btn::after {
        content: 'Expand';
        position: absolute;
        right: -70px;
        top: 50%;
        transform: translateY(-50%);
        background: var(--sidebar-bg);
        color: var(--sidebar-text);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.7rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all var(--transition);
        border: 1px solid var(--sidebar-border);
        font-weight: 500;
    }

    .sidebar.collapsed .toggle-btn:hover::after {
        opacity: 1;
        visibility: visible;
        right: -80px;
    }

    /* ===== MOBILE HAMBURGER ===== */
    .mobile-hamburger {
        display: none;
        position: fixed;
        top: 20px;
        left: 20px;
        width: 48px;
        height: 48px;
        background: var(--sidebar-active-bg);
        color: white;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        z-index: 999;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
        transition: all var(--transition);
    }

    .mobile-hamburger:hover {
        background: var(--gradient-end);
        transform: scale(1.05) rotate(5deg);
        box-shadow: 0 6px 30px rgba(245, 158, 11, 0.5);
    }



    /* ===== REST OF SIDEBAR STYLES ===== */
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

    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        padding: 0 0.5rem;
        width: var(--sidebar-width);
        height: 100vh;
        border-radius: 0 10px 10px 0;
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

    .sidebar.collapsed {
        width: var(--sidebar-collapsed);
    }

    .sidebar.collapsed .sidebar-brand h3,
    .sidebar.collapsed .nav-text,
    .sidebar.collapsed .user-info {
        display: none;
    }

    .sidebar.collapsed .nav-icon {
        font-size: 1.3rem;
    }

    .sidebar.collapsed .nav-link,
    .sidebar.collapsed .nav-toggle {
        justify-content: center;
        padding: 0.8rem 0.5rem;
    }

    .sidebar.collapsed .nav-content {
        justify-content: center;
    }

    .sidebar.collapsed .chevron {
        display: none;
    }

    .sidebar.collapsed .nav-section-header {
        text-align: center;
        font-size: 0.6rem;
        padding: 0.5rem 0.2rem;
    }

    .sidebar.collapsed .nav-section-header span {
        display: block;
        font-size: 0.5rem;
    }

    .sidebar.collapsed .sidebar-footer {
        justify-content: center;
        padding: 1rem 0.5rem;
    }

    .sidebar.collapsed .user-avatar {
        width: 36px;
        height: 36px;
        font-size: 0.9rem;
    }

    /* ===== SUBMENU IN COLLAPSED STATE ===== */
    .sidebar.collapsed .submenu {
        max-height: 0 !important;
        opacity: 0 !important;
        padding: 0 !important;
    }

    .sidebar.collapsed .submenu.show {
        max-height: 0 !important;
        opacity: 0 !important;
    }

    .sidebar-brand {
        padding: 1.75rem 1.25rem;
        border-bottom: 1px solid var(--sidebar-border);
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
        height: 35px;
        border-radius: 8px;
        transition: all var(--transition);
    }

    .sidebar.collapsed .sidebar-brand img {
        width: 35px;
        height: 28px;
    }

    .sidebar-brand h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--sidebar-text);
        white-space: nowrap;
        letter-spacing: 1px;
        transition: all var(--transition);
    }

    .nav-menu {
        list-style: none;
        flex: 1;
        padding: 1rem 0.75rem;
        overflow-y: auto;
    }

    .nav-item {
        margin-bottom: 0.4rem;
    }

    .nav-section-header {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--sidebar-text-muted);
        padding: 0.8rem 0.75rem 0.4rem;
        margin-top: 0.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        transition: all var(--transition);
    }

    .nav-section-header:first-child {
        margin-top: 0;
        padding-top: 0;
    }

    .nav-link,
    .nav-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 0.7rem 1rem;
        color: var(--sidebar-text);
        text-decoration: none;
        border: none;
        background: none;
        cursor: pointer;
        font-size: 0.9rem;
        font-weight: 500;
        border-radius: 10px;
        transition: all var(--transition);
        position: relative;
        overflow: hidden;
    }

    .nav-link:hover,
    .nav-toggle:hover {
        background: var(--sidebar-hover-bg);
        color: var(--sidebar-active-bg) !important;
        padding-left: 1.2rem;
    }

    .nav-link.active,
    .nav-toggle.active {
        background: var(--sidebar-active-bg) !important;
        color: var(--sidebar-text) !important;
        font-weight: 600;
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
        transition: all var(--transition);
    }

    .nav-badge {
        background: #ef4444;
        color: white;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 999px;
        margin-left: auto;
        flex-shrink: 0;
        line-height: 1;
        min-width: 20px;
        text-align: center;
    }

    .chevron {
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        flex-shrink: 0;
        transition: transform var(--transition);
        color: var(--sidebar-icon-muted);
    }

    .nav-toggle[aria-expanded="false"] .chevron {
        transform: rotate(0deg);
    }

    .nav-toggle[aria-expanded="true"] .chevron {
        transform: rotate(90deg);
    }

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

    .submenu .nav-link {
        padding: 0.6rem 0.75rem 0.6rem 2.8rem;
        font-size: 0.85rem;
        font-weight: 400;
        position: relative;
    }

    .submenu .nav-link::before {
        content: '';
        position: absolute;
        left: 1.8rem;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--sidebar-text-muted);
        opacity: 0.4;
        transition: all var(--transition);
    }

    .submenu .nav-link:hover::before {
        background: var(--sidebar-active-bg);
        opacity: 1;
    }

    .sidebar-footer {
        padding: 1.25rem 1.25rem;
        border-top: 1px solid var(--sidebar-border);
        margin-top: auto;
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 65px;
        transition: all var(--transition);
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
        transition: all var(--transition);
    }

    .user-info {
        flex: 1;
        min-width: 0;
        transition: all var(--transition);
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

    .main-content {
        margin-left: var(--sidebar-width);
        transition: margin-left var(--transition);
        min-height: 100vh;
        padding: 2rem;
        background: #f5f7fa;
        overflow-x: hidden;
    }

    .sidebar.collapsed ~ .main-content {
        margin-left: var(--sidebar-collapsed);
    }

    @media (max-width: 768px) {
        .mobile-hamburger {
            display: flex !important;
        }

        .sidebar {
            width: var(--sidebar-mobile);
            max-width: 350px;
            transform: translateX(-100%);
            z-index: 1001;
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar-brand .toggle-btn {
            display: none;
        }

        .sidebar.collapsed .toggle-btn::after {
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
            width: 38px;
            height: 38px;
            font-size: 1.3rem;
        }
    }

    .security-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 46px;
        height: 46px;
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
        transform: scale(1.1) rotate(10deg);
        box-shadow: 0 6px 30px rgba(245, 158, 11, 0.5);
    }

    /* Notification bell */
    .security-btn:last-child {
        bottom: 100px;
    }

    @media (max-width: 768px) {
        .security-btn {
            bottom: 20px;
            right: 20px;
            width: 48px;
            height: 48px;
            font-size: 1.3rem;
        }
        .security-btn:last-child {
            bottom: 80px;
        }
    }
</style>

<!-- Mobile Hamburger Button -->
<button class="mobile-hamburger" id="mobileHamburger" aria-label="Open menu">
    <i class="fas fa-bars"></i>
</button>

<!-- Mobile Overlay -->
<div class="mobile-overlay" id="mobileOverlay"></div>

<!-- Sidebar -->
<nav id="sidebarMenu" class="sidebar d-print-none">
    <button class="mobile-close-btn" id="mobileCloseBtn" aria-label="Close menu">
        <i class="fas fa-times"></i>
    </button>

    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="sidebar-brand-content">
            <img src="{{ asset('/public/images/leruma.png') }}" alt="Leruma Enterprise Logo">
            <h3>LERUMA Ent.</h3>
        </div>
        <button class="toggle-btn" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="fas fa-chevron-left" id="toggleIcon"></i>
        </button>
    </div>

    <!-- Emergency Access Banner -->
    @if(session('emergency_access'))
    <div style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a5a 100%); padding: 0.75rem 1.25rem; margin: 0 0.5rem; border-radius: 10px; color: white; font-size: 0.85rem; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>EMERGENCY ACCESS ACTIVE</strong><br>
            <small>Session expires: {{ \Carbon\Carbon::parse(session('emergency_expires_at'))->diffForHumans() }}</small>
        </div>
    </div>
    @endif

    <!-- Navigation Menu -->
    <ul class="nav-menu" id="navMenu">
        
        @if (canUser('view_items') || canUser('item_request') || canUser('view_item_requests'))
        <!-- ================= SECTION 1: ITEM SECTION ================= -->
        <li class="nav-section-header">
            <span>{{ __('messages.section_items') }}</span>
        </li>
        
        <!-- Items with Dropdown -->           
        <li class="nav-item">
            <button class="nav-toggle" aria-expanded="false" aria-controls="items-menu">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-boxes"></i></span>
                    <span class="nav-text">{{ __('messages.items') }}</span>
                </div>
                <span class="chevron"><i class="fas fa-chevron-right"></i></span>
            </button>
            <ul class="submenu" id="items-menu">
                @if (canUser('view_items'))
                <li class="nav-item">
                    <a href="/products" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.all_items') }}</span>
                        </div>
                    </a>
                </li>
                @endif
         
                @if (canUser('item_request'))
                <li class="nav-item">
                    <a href="/itemRequest" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.item_request') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if (canUser('view_item_requests'))
                <li class="nav-item">
                    <a href="/viewRequest" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.view_request') }}</span>
                            @php $submittedCount = getSubmittedRequestCount(); @endphp
                            @if($submittedCount > 0)
                            <span class="nav-badge">{{ $submittedCount }}</span>
                            @endif
                        </div>
                    </a>
                </li>
                @endif
              
            </ul>
        </li>
        
        <!-- Suppliers -->
        @if (canUser('view_suppliers'))
        <li class="nav-item">
            <button class="nav-toggle" aria-expanded="false" aria-controls="suppliers-menu">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-user-tie"></i></span>
                    <span class="nav-text">{{ __('messages.suppliers') }}</span>
                </div>
                <span class="chevron"><i class="fas fa-chevron-right"></i></span>
            </button>
            <ul class="submenu" id="suppliers-menu">
                <li class="nav-item">
                    <a href="/vendors" class="nav-link">
                <div class="nav-content">
                    
                    <span class="nav-text">{{ __('messages.suppliers') }}</span>
                </div>
            </a>
        </li>
                @if (canUser('manage_supplier_credit'))
                <li class="nav-item">
                    <a href="/supplier-credit" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.supplier_credit') }}</span>
                        </div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        
        @endif

        <!-- Receiving & Returns -->
        @if (canUser('view_receivings'))
        <li class="nav-item">
            <button class="nav-toggle" aria-expanded="false" aria-controls="receiving-menu">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-truck-loading"></i></span>
                    <span class="nav-text">{{ __('messages.receiving_returns') }}</span>
                </div>
                <span class="chevron"><i class="fas fa-chevron-right"></i></span>
            </button>
            <ul class="submenu" id="receiving-menu">
                @if (canUser('manage_receivings'))
                <li class="nav-item">
                    <a href="/make-receiving" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.make_receiving') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="/view-receivings" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.view_receivings') }}</span>
                            @php $pendingReceivingCount = getPendingReceivingCount(); @endphp
                            @if($pendingReceivingCount > 0)
                            <span class="nav-badge">{{ $pendingReceivingCount }}</span>
                            @endif
                        </div>
                    </a>
                </li>
                @if (canUser('make_return'))
                <li class="nav-item">
                    <a href="/make-return" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.make_return') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if (canUser('view_returns'))
                <li class="nav-item">
                    <a href="/view-returns" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.view_returns') }}</span>
                            @php $pendingReturnCount = getPendingReturnCount(); @endphp
                            @if($pendingReturnCount > 0)
                            <span class="nav-badge">{{ $pendingReturnCount }}</span>
                            @endif
                        </div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        @endif

        <!-- ================= SECTION 2: SALE SECTION ================= -->
        <li class="nav-section-header">
            <span>{{ __('messages.section_sales') }}</span>
        </li>
        
        <!-- New Sale -->
        @if (canUser('create_sales'))
        <li class="nav-item">
            <a href="/newOrder" class="nav-link">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-shopping-cart"></i></span>
                    <span class="nav-text">{{ __('messages.new_sale') }}</span>
                </div>
            </a>
        </li>
        @endif

        <!-- Invoices -->
        <li class="nav-item">
            <button class="nav-toggle" aria-expanded="false" aria-controls="invoices-menu">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-list-check"></i></span>
                    <span class="nav-text">{{ __('messages.invoices') }}</span>
                </div>
                <span class="chevron"><i class="fas fa-chevron-right"></i></span>
            </button>
            <ul class="submenu" id="invoices-menu">
                @if ( canUser('view_invoices'))
                <li class="nav-item">
                    <a href="/ordersList" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.current_shop') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                
                
                @if (canUser('view_shop_debts'))
                <li class="nav-item">
                    <a href="{{ url('/shopInvoices') }}" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.shop_debts') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if (canUser('manage_paid_invoice'))
                <li class="nav-item">
                    <a href="{{ url('/paidInvoices') }}" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.paid_invoices') }}</span>
                        </div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        

        <!-- Expenses -->
        @if (canUser('view_expenses'))
        <li class="nav-item">
            <a href="/expenses" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-icon"><i class="fas fa-receipt"></i></span>
                            <span class="nav-text">{{ __('messages.expenses') }}</span>
                        </div>
                    </a>
                </li>
                @endif

        <!-- ================= SECTION 3: REPORT SECTION ================= -->
                @if (canUser('view_reports') || canUser('view_logs') || canUser('view_receivings'))

        <li class="nav-section-header">
            <span>{{ __('messages.section_reports') }}</span>
        </li>
        
        <!-- Reports -->
        @if (canUser('view_reports'))
        <li class="nav-item">
            <button class="nav-toggle" aria-expanded="false" aria-controls="report-menu">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
                    <span class="nav-text">{{ __('messages.reports') }}</span>
                </div>
                <span class="chevron"><i class="fas fa-chevron-right"></i></span>
            </button>
            <ul class="submenu" id="report-menu">
                @if (canUser('view_shops_report'))
                <li class="nav-item">
                    <a href="/shopReport" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.shops_report') }}</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/shop-daily-item-report" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.shop_daily_item_report') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if (canUser('view_sales_report'))
                <li class="nav-item">
                    <a href="/sales" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.sales_report') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if (canUser('view_stock_report'))
                <li class="nav-item">
                    <a href="/stock-report" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.stock_report') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="/offeredProductsReport" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.offered_products') }}</span>
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        <!-- KPI Dashboard -->
        @if (canUser('view_reports'))
        <li class="nav-item">
            <a href="/kpi" class="nav-link">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                    <span class="nav-text">{{ __('messages.kpi_dashboard') }}</span>
                </div>
            </a>
        </li>
        @endif

        <!-- Logs -->
        @if (canUser('view_logs'))
        <li class="nav-item">
            <a href="/logs" class="nav-link">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-file-alt"></i></span>
                    <span class="nav-text">{{ __('messages.logs') }}</span>
                </div>
            </a>
        </li>
        @endif

        <!-- Receiving Report -->
        @if (canUser('view_receivings'))
        <li class="nav-item">
            <a href="/receiving-report" class="nav-link">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-file-invoice"></i></span>
                    <span class="nav-text">{{ __('messages.receiving_report') }}</span>
                </div>
            </a>
        </li>
        @endif
        @endif
       
        <!-- ================= SECTION 4: MAIN STORE SECTION ================= -->
         @if (canUser('view_main_store'))
        <li class="nav-section-header">
            <span>{{ __('messages.section_main_store') }}</span>
        </li>
        <!-- Items with Dropdown -->
        <li class="nav-item">
            <button class="nav-toggle" aria-expanded="false" aria-controls="main-items-menu">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-boxes"></i></span>
                    <span class="nav-text">{{ __('messages.items') }}</span>
                </div>
                <span class="chevron"><i class="fas fa-chevron-right"></i></span>
            </button>
            <ul class="submenu" id="main-items-menu">
                @if (canUser('main_view_items'))
                <li class="nav-item">
                    <a href="/products" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.all_items') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if (canUser('main_create_items'))
                <li class="nav-item">
                    <a href="/newProducts" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.new_item') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if (canUser('main_create_offers'))
                <li class="nav-item">
                    <a href="/offers" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.offer') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if (canUser('main_create_item_request'))
                <li class="nav-item">
                    <a href="/itemRequest" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.item_request') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if (canUser('main_view_item_request'))
                <li class="nav-item">
                    <a href="/viewRequest" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.view_request') }}</span>
                            @php $submittedCount = getSubmittedRequestCount(); @endphp
                            @if($submittedCount > 0)
                            <span class="nav-badge">{{ $submittedCount }}</span>
                            @endif
                        </div>
                    </a>
                </li>
                @endif
                 @if (canUser('main_view_item_reports'))
                 <li class="nav-item">
                     <a href="/items-report" class="nav-link">
                         <div class="nav-content">
                             <span class="nav-text">{{ __('messages.items_report') }}</span>
                         </div>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="/most-sold-products" class="nav-link">
                         <div class="nav-content">
                             <span class="nav-text">{{ __('messages.most_sold_products') }}</span>
                         </div>
                     </a>
                 </li>
                 @endif
            </ul>
        </li>
        <!-- Main Store Receiving & Returns -->
        @if (canUser('main_view_receiving') || canUser('main_view_returns') || canUser('main_supplier_credit') || canUser('main_make_receiving') || canUser('main_make_return') || canUser('main_view_customers'))            
        
        <li class="nav-item">
            <button class="nav-toggle" aria-expanded="false" aria-controls="main-receiving-menu">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-truck-loading"></i></span>
                    <span class="nav-text">{{ __('messages.receiving_returns_ms') }}</span>
                </div>
                <span class="chevron"><i class="fas fa-chevron-right"></i></span>
            </button>
            <ul class="submenu" id="main-receiving-menu">
                @if (canUser('main_make_receiving'))
                <li class="nav-item">
                    <a href="/main-receiving" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.make_receiving') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if (canUser('main_make_return'))
                <li class="nav-item">
                    <a href="/main-return" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.make_return_ms') }}</span>
                        </div>
                    </a>
                </li>
                @endif
                @if (canUser('main_view_receiving'))
                <li class="nav-item">
                    <a href="/main-receivings" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.view_receivings') }}</span>
                            @php $pendingReceivingCount = getPendingReceivingCount(); @endphp
                            @if($pendingReceivingCount > 0)
                            <span class="nav-badge">{{ $pendingReceivingCount }}</span>
                            @endif
                        </div>
                    </a>
                </li>
                @endif
                @if (canUser('main_view_returns'))
                <li class="nav-item">
                    <a href="/main-returns" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.view_returns') }}</span>
                            @php $pendingReturnCount = getPendingReturnCount(); @endphp
                            @if($pendingReturnCount > 0)
                            <span class="nav-badge">{{ $pendingReturnCount }}</span>
                            @endif
                        </div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        @if (canUser('view_main_store_report'))
        <li class="nav-item">
            <a href="/mainStoreReport" class="nav-link">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-file-invoice"></i></span>
                    <span class="nav-text">{{ __('messages.main_store_report') }}</span>
                </div>
            </a>
        </li>
        @endif

        @if (canUser('main_view_customers')) 

        <li class="nav-item">
                    <a href="/main-customers" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-icon"><i class="fas fa-users"></i></span>
                            <span class="nav-text">{{ __('messages.customers') }}</span>
                        </div>
                    </a>
                </li>
            
        @endif
        @if (canUser('main_supplier_credit'))
        <li class="nav-item">
            <button class="nav-toggle" aria-expanded="false" aria-controls="main-suppliers-menu">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-user-tie"></i></span>
                    <span class="nav-text">{{ __('messages.suppliers') }}</span>
                </div>
                <span class="chevron"><i class="fas fa-chevron-right"></i></span>
            </button>
            <ul class="submenu" id="main-suppliers-menu">
                <li class="nav-item">
                    <a href="/main-supplier" class="nav-link">
                <div class="nav-content">
                    
                    <span class="nav-text">{{ __('messages.suppliers') }}</span>
                </div>
            </a>
        </li>
                @if (canUser('manage_supplier_credit'))
                <li class="nav-item">
                    <a href="/main-credit" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.supplier_credit') }}</span>
                        </div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        
        @endif

        @endif
        <!-- ================= SECTION 5: SYSTEM & OTHER ================= -->
        <li class="nav-section-header">
            <span>⚙️ SYSTEM & OTHER</span>
        </li>
        
        <!-- Dashboard -->
        <li class="nav-item">
            <a href="/dashboard" class="nav-link">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
                    <span class="nav-text">{{ __('messages.dashboard') }}</span>
                </div>
            </a>
        </li>

        <!-- Banking -->
        @if (canUser('view_banking'))
        <li class="nav-item">
            <button class="nav-toggle" aria-expanded="false" aria-controls="banking-menu">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-university"></i></span>
                    <span class="nav-text">{{ __('messages.banking') }}</span>
                </div>
                <span class="chevron"><i class="fas fa-chevron-right"></i></span>
            </button>
            <ul class="submenu" id="banking-menu">
                <li class="nav-item">
                    <a href="/banking-partners" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.supplier_beneficiary') }}</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/banking-transfers" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.banking_deposit') }}</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/banking/supplier-deposit-report" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.supplier_deposit_report') }}</span>
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        <!-- Customers -->
        @if (canUser('view_customers'))
        <li class="nav-item">
            <button class="nav-toggle" aria-expanded="false" aria-controls="customers-menu">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    <span class="nav-text">{{ __('messages.customers') }}</span>
                </div>
                <span class="chevron"><i class="fas fa-chevron-right"></i></span>
            </button>
            <ul class="submenu" id="customers-menu">
                <li class="nav-item">
                    <a href="/customers" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.all_customers') }}</span>
                        </div>
                    </a>
                </li>
                @if (canUser('add_banking_chip'))
                <li class="nav-item">
                    <a href="/banking-chips" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.chip') }}</span>
                        </div>
                    </a>
                </li>
                @endif
              
                <li class="nav-item">
                    <a href="/customer-kpi" class="nav-link">
                        <div class="nav-content">
                            <span class="nav-text">{{ __('messages.customer_kpi') }}</span>
                        </div>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        <!-- Employees / Users -->
        @if (canUser('manage_employees'))
        <li class="nav-item">
            <a href="/employees" class="nav-link">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-user-group"></i></span>
                    <span class="nav-text">{{ __('messages.users') }}</span>
                </div>
            </a>
        </li>
        @endif

        <!-- Ads -->
        @if (canUser('view_ads'))
        <li class="nav-item">
            <a href="/ads" class="nav-link">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-window-maximize"></i></span>
                    <span class="nav-text">{{ __('messages.ads') }}</span>
                </div>
            </a>
        </li>
        @endif

        <!-- Settings -->
        @if (canUser('view_settings'))
        <li class="nav-item">
            <a href="/settings" class="nav-link">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-cog"></i></span>
                    <span class="nav-text">{{ __('messages.settings') }}</span>
                </div>
            </a>
        </li>
        @endif

        <!-- Account Migration (Super Admin only) -->
        @if (Auth::user()->levelStatus === 'Admin2')
        <li class="nav-item">
            <a href="/migration" class="nav-link">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-database"></i></span>
                    <span class="nav-text">{{ __('messages.account_migration') }}</span>
                </div>
            </a>
        </li>
        @endif

        <!-- Logout -->
        <li class="nav-item">
            <a href="/signout" class="nav-link logout-link">
                <div class="nav-content">
                    <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span class="nav-text">{{ __('messages.logout') }}</span>
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
                <span class="badge bg-danger ms-2" style="font-size: 0.65rem; padding: 2px 6px; border-radius: 4px;">
                    <i class="fas fa-exclamation-triangle me-1"></i>EMERGENCY
                </span>
            @endif
        </div>
    </div>
    <div class="mt-2 pt-2 border-top">
        @if(canUser('manage_language'))
        <a href="{{ route('lang.switch', ['locale' => app()->getLocale() === 'en' ? 'sw' : 'en']) }}" class="btn btn-sm btn-outline-light w-100">
            {{ __('messages.switch_lang_btn') }}
        </a>
        @endif
    </div>
</div>
</nav>

<!-- Security Button -->
@if (Auth::user()->levelStatus === 'Admin')
    <a href="/security" class="security-btn" title="Security">
        <i class="fas fa-shield-alt"></i>
    </a>

<a href="/notification" class="security-btn" title="Notifications" style="bottom: 90px;">
    <i class="fas fa-bell"></i>
</a>
@endif
<!-- Active Users Badge -->
<div class="security-btn" title="Active Users" style="bottom: 160px;">
    <span id="activeUsersCount">0</span>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebarMenu');
        const toggleBtn = document.getElementById('sidebarToggle');
        const toggleIcon = document.getElementById('toggleIcon');
        const mobileHamburger = document.getElementById('mobileHamburger');
        const mobileCloseBtn = document.getElementById('mobileCloseBtn');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const body = document.body;
        const isMobile = window.innerWidth <= 768;
        
        let isSidebarOpen = false;

        // Desktop Toggle
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!isMobile) {
                    sidebar.classList.toggle('collapsed');
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebarCollapsed', isCollapsed);
                    
                    // Update toggle icon
                    if (toggleIcon) {
                        toggleIcon.className = isCollapsed ? 'fas fa-chevron-right' : 'fas fa-chevron-left';
                    }
                }
            });
        }

        // Load saved state
        if (!isMobile && localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            if (toggleIcon) {
                toggleIcon.className = 'fas fa-chevron-right';
            }
        }

        // Mobile Hamburger Click
        function openSidebar() {
            isSidebarOpen = true;
            sidebar.classList.add('active');
            mobileOverlay.classList.add('active');
            body.classList.add('sidebar-open');
        }

        function closeSidebar() {
            isSidebarOpen = false;
            sidebar.classList.remove('active');
            mobileOverlay.classList.remove('active');
            body.classList.remove('sidebar-open');
        }

        if (mobileHamburger) mobileHamburger.addEventListener('click', openSidebar);
        if (mobileCloseBtn) mobileCloseBtn.addEventListener('click', closeSidebar);
        if (mobileOverlay) mobileOverlay.addEventListener('click', closeSidebar);

        // Dropdown Toggles
        const navToggles = document.querySelectorAll('.nav-toggle');
        navToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const menuId = this.getAttribute('aria-controls');
                const menu = document.getElementById(menuId);
                const isExpanded = this.getAttribute('aria-expanded') === 'true';

                if (!isExpanded && !isMobile) {
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
                }

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
                        submenu.classList.add('show');
                        toggle.setAttribute('aria-expanded', 'true');
                    }
                }
            }
        });

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
    });
</script>