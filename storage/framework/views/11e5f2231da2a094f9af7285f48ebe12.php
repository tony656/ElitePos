<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo app('translator')->get('messages.create_new_sales'); ?></title>
    <?php echo $__env->make("links", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* ── CSS Variables ── */
        :root {
            --primary-dark:     #0B1E3D;
            --primary-mid:      #112952;
            --primary-light:    #1A3A6B;
            --accent:           var(--theme-primary, #F59E0B);
            --accent-dark:      #D97706;
            --accent-pale:      #FEF3C7;
            --success:          #059669;
            --success-pale:     #D1FAE5;
            --danger:           #E11D48;
            --danger-pale:      #FFE4E6;
            --info:             #7C3AED;
            --info-pale:        #EDE9FE;
            --fuchsia:          #C026D3;
            --fuchsia-pale:     #FAE8FF;
            --sky:              #0284C7;
            --sky-pale:         #E0F2FE;
            --gray-50:          #F8FAFC;
            --gray-100:         #F1F5F9;
            --gray-200:         #E2E8F0;
            --gray-300:         #CBD5E1;
            --gray-400:         #94A3B8;
            --gray-500:         #64748B;
            --gray-600:         #475569;
            --gray-700:         #334155;
            --gray-800:         #1E293B;
            --gray-900:         #0F172A;
            --white:            #FFFFFF;
            --shadow-sm:        0 1px 3px rgba(0,0,0,0.06);
            --shadow-md:        0 4px 16px rgba(0,0,0,0.08);
            --shadow-lg:        0 8px 32px rgba(0,0,0,0.12);
            --radius:           12px;
            --radius-sm:        8px;
            --radius-xs:        6px;
            --transition:       all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: #EEF2F9;
            color: var(--gray-800);
            min-height: 100vh;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--gray-100); border-radius: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }

        /* ── Main Wrapper ── */
        .main-wrap {
            max-width: 1920px;
            margin: 0 auto;
            padding: 1.25rem 1.5rem;
        }

        @media (max-width: 768px) {
            .main-wrap { padding: 0.75rem; }
        }

        /* ── Page Header ── */
        .page-header {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-light) 100%);
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -5%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .page-header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .page-header-icon {
            width: 48px;
            height: 48px;
            background: rgba(245, 158, 11, 0.15);
            border: 1.5px solid rgba(245, 158, 11, 0.3);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .page-title {
            color: var(--white);
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.3px;
        }

        .page-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
            margin: 0;
        }

        .page-header-actions {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .btn-header {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.45rem 1rem;
            border-radius: var(--radius-sm);
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.85);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            font-family: inherit;
        }

        .btn-header:hover {
            background: rgba(255, 255, 255, 0.14);
            color: var(--white);
            border-color: var(--accent);
            transform: translateY(-1px);
        }

        .btn-header i { font-size: 1rem; }

        /* ── Alerts ── */
        .alert-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.8rem 1.25rem;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.25rem;
            animation: slideDown 0.35s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: var(--success-pale);
            border-left: 4px solid var(--success);
            color: #065F46;
        }

        .alert-danger {
            background: var(--danger-pale);
            border-left: 4px solid var(--danger);
            color: #9F1239;
        }

        .alert-bar i { font-size: 1.1rem; margin-right: 0.4rem; }

        .alert-close {
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            color: inherit;
            opacity: 0.5;
            padding: 0 0.25rem;
            transition: var(--transition);
            line-height: 1;
        }

        .alert-close:hover { opacity: 1; }

        /* ── Layout Grid ── */
        .layout-grid {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 1.25rem;
            align-items: start;
        }

        @media (max-width: 1100px) {
            .layout-grid { grid-template-columns: 1fr; }
        }

        /* ── Panel ── */
        .panel {
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .panel-header {
            background: var(--primary-dark);
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: var(--white);
            font-size: 0.85rem;
            font-weight: 600;
        }

        .panel-header-left {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .panel-header i { color: var(--accent); }

        /* ── Search Section ── */
        .search-section {
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .search-row {
            display: flex;
            gap: 0.6rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-row .field {
            flex: 1;
            min-width: 120px;
        }

        .search-row .field:last-child {
            flex: 2;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .field-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-500);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .field-label i { color: var(--primary-light); font-size: 0.75rem; }

        .field-input {
            width: 100%;
            font-size: 0.85rem;
            padding: 0.45rem 0.7rem;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            background: var(--white);
            color: var(--gray-800);
            outline: none;
            transition: var(--transition);
            font-family: inherit;
        }

        .field-input:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(26, 58, 107, 0.08);
        }

        .field-input::placeholder { color: var(--gray-400); }

        select.field-input {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            padding-right: 2rem;
        }

        .shop-select-inline {
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.45rem 2rem 0.45rem 0.7rem;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            background: var(--white);
            color: var(--primary-dark);
            cursor: pointer;
            outline: none;
            min-width: 150px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%234361EE' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.6rem center;
            transition: var(--transition);
            font-family: inherit;
        }

        .shop-select-inline:hover { border-color: var(--primary-light); }
        .shop-select-inline:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(26, 58, 107, 0.08);
        }

        .shop-select-inline option {
            font-size: 0.85rem;
            padding: 0.4rem;
            color: var(--gray-800);
            background: var(--white);
        }

        /* ── Search Wrapper (with icon) ── */
        .search-wrapper {
            position: relative;
            flex: 1;
        }

        .search-wrapper .search-icon {
            position: absolute;
            left: 0.7rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            font-size: 0.9rem;
            pointer-events: none;
        }

        .search-wrapper input {
            width: 100%;
            padding: 0.45rem 0.7rem 0.45rem 2.2rem;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            background: var(--gray-50);
            font-size: 0.85rem;
            color: var(--gray-800);
            outline: none;
            transition: var(--transition);
            font-family: inherit;
        }

        .search-wrapper input:focus {
            border-color: var(--primary-light);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(26, 58, 107, 0.08);
        }

        .search-wrapper input::placeholder { color: var(--gray-400); }

        /* ── Dropdown ── */
        .dropdown {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            display: none;
            max-height: 400px;
            overflow-y: auto;
            animation: dropIn 0.16s ease;
        }

        .dropdown.open { display: block; }

        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-item {
            padding: 0.6rem 1rem;
            border-bottom: 1px solid var(--gray-100);
            cursor: pointer;
            overflow-y: auto;
            transition: var(--transition);
        }

        .dropdown-item:last-child { border-bottom: none; }
        .dropdown-item:hover { background: #EFF4FF; }

        .dropdown-item-name {
            font-weight: 600;
            color: var(--primary-dark);
            font-size: 0.86rem;
            margin-bottom: 0.15rem;
        }

        .dropdown-item-meta {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            font-size: 0.74rem;
            color: var(--gray-500);
            flex-wrap: wrap;
        }

        .dd-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.15rem 0.5rem;
            border-radius: 20px;
        }

        .dd-badge-price  { background: var(--success-pale); color: #065F46; }
        .dd-badge-offer  { background: var(--fuchsia-pale); color: var(--fuchsia); }
        .dd-badge-stock  { background: var(--sky-pale); color: var(--sky); }
        .dd-badge-debt   { background: var(--danger-pale); color: var(--danger); }

        .dd-loading, .dd-empty {
            padding: 1.25rem;
            text-align: center;
            color: var(--gray-400);
            font-size: 0.85rem;
        }

        /* ── Selected Badge ── */
        .selected-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary-dark);
            color: var(--white);
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.25rem 0.3rem 0.25rem 0.7rem;
            border-radius: 20px;
            margin-top: 0.5rem;
        }

        .selected-badge-remove {
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.12);
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            transition: var(--transition);
            flex-shrink: 0;
        }

        .selected-badge-remove:hover { background: var(--danger); }

        /* ── Cart Section ── */
        .cart-section { padding: 0; }

        .cart-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .cart-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-500);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .cart-label i { color: var(--accent); }

        .cart-badge {
            background: var(--accent);
            color: var(--primary-dark);
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.1rem 0.5rem;
            border-radius: 20px;
        }

        .btn-clear {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.25rem 0.6rem;
            border: 1.5px solid var(--gray-300);
            border-radius: var(--radius-xs);
            background: transparent;
            color: var(--gray-600);
            cursor: pointer;
            transition: var(--transition);
            font-family: inherit;
        }

        .btn-clear:hover {
            background: var(--danger);
            color: var(--white);
            border-color: var(--danger);
        }

        /* ── Offer Banner ── */
        .offer-banner {
            padding: 0.6rem 1.25rem;
            background: linear-gradient(90deg, var(--fuchsia-pale), #FFF);
            border-bottom: 1px solid #F5D0FE;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .offer-banner-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--fuchsia);
        }

        .offer-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #F5D0FE;
            border: 1px solid #F3E8FF;
            border-radius: var(--radius-xs);
            padding: 0.2rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--fuchsia);
            align-self: flex-start;
        }

        .offer-chip span { color: var(--primary-dark); font-weight: 700; }
        .offer-chip-remove {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--danger);
            font-size: 0.9rem;
            line-height: 1;
            padding: 0;
            display: inline-flex;
            align-items: center;
        }

        .offer-chip-remove:hover { color: #9F1239; }

        /* ── Cart Table ── */
        .cart-scroll {
            overflow-x: auto;
            padding: 0 4px;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
            min-width: 600px;
        }

        .cart-table thead th {
            background: var(--gray-50);
            color: var(--gray-500);
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.5rem 0.7rem;
            border-bottom: 2px solid var(--gray-200);
            white-space: nowrap;
            text-align: left;
        }

        .cart-table thead th:last-child { text-align: right; }

        .cart-table tbody td {
            padding: 0.5rem 0.7rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .cart-table tbody tr:hover td { background: #F8FAFF; }
        .cart-table tbody tr.offer-row td { background: #F0FDF4; }
        .cart-table tbody tr.offer-row:hover td { background: #DCFCE7; }
        .cart-table tbody tr.offer-row td:first-child { border-left: 3px solid var(--success); }
        .cart-table tbody tr.return-row td { background: var(--danger-pale); }
        .cart-table tbody tr.return-row:hover td { background: #FECACA; }
        .cart-table tbody tr.return-row td:first-child { border-left: 3px solid var(--danger); }

        .cart-product-name {
            font-weight: 600;
            color: var(--primary-dark);
            max-width: 160px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .mono { font-family: 'DM Mono', monospace; font-size: 0.78rem; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: 700; }

        .qty-input {
            font-size: 0.78rem;
            width: 70px;
            padding: 0.25rem 0.35rem;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-xs);
            background: var(--white);
            color: var(--gray-800);
            text-align: center;
            outline: none;
            transition: var(--transition);
            font-family: inherit;
        }

        .qty-input:focus {
            border-color: var(--primary-light);
            background: #EEF3FF;
        }

        .btn-delete {
            width: 28px;
            height: 28px;
            background: var(--danger-pale);
            color: var(--danger);
            border: none;
            border-radius: var(--radius-xs);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: var(--transition);
        }

        .btn-delete:hover {
            background: var(--danger);
            color: var(--white);
            transform: scale(1.08);
        }

        /* ── Empty Cart ── */
        .empty-cart {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
            color: var(--gray-400);
            text-align: center;
        }

        .empty-cart i {
            font-size: 2.5rem;
            margin-bottom: 0.6rem;
            opacity: 0.4;
            display: block;
        }

        .empty-cart-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray-500);
            margin-bottom: 0.25rem;
        }

        .empty-cart p { font-size: 0.8rem; }

        /* ── Sidebar Sections ── */
        .sidebar-section {
            padding: 0.85rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .sidebar-section:last-child { border-bottom: none; }

        .section-title {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--gray-400);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .section-title i { color: var(--accent); font-size: 0.8rem; }

        /* ── Customer Info Card ── */
        .customer-info {
            background: var(--gray-50);
            border: 1.5px solid var(--gray-200);
            border-left: 3px solid var(--accent);
            border-radius: var(--radius-sm);
            padding: 0.7rem 0.85rem;
            margin-top: 0.6rem;
        }

        .customer-info-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-500);
            margin-bottom: 0.5rem;
        }

        .customer-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.2rem 0;
            font-size: 0.8rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .customer-row:last-child { border-bottom: none; }

        .customer-key { color: var(--gray-500); font-weight: 500; }
        .customer-value { font-weight: 500; color: var(--gray-800); }
        .customer-value.available { color: var(--success); }
        .customer-value.debt { color: var(--danger); }

        /* ── Order Meta ── */
        .order-meta {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
            margin-top: 0.6rem;
        }

        .meta-chip {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
            background: var(--gray-100);
            border-radius: var(--radius-xs);
            padding: 0.35rem 0.6rem;
            flex: 1;
            min-width: 80px;
        }

        .meta-chip-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--gray-400);
        }

        .meta-chip-value {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary-dark);
        }

        /* ── Pay Distribution ── */
        .pay-distribution {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            background: var(--gray-50);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            padding: 0.65rem;
            margin-bottom: 0.6rem;
        }

        .pay-dist-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--gray-500);
            margin-bottom: 0.2rem;
        }

        /* ── Price Summary ── */
        .price-rows {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            margin-bottom: 0.75rem;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.84rem;
        }

        .price-row .key { color: var(--gray-600); font-weight: 500; }
        .price-row .val { font-weight: 500; color: var(--gray-700); }

        /* ── Total Card ── */
        .total-card {
            background: var(--primary-dark);
            border-radius: var(--radius-sm);
            padding: 0.85rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.85rem;
        }

        .total-card-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .total-card-amount {
            font-family: 'DM Mono', monospace;
            font-size: 1.25rem;
            color: var(--accent);
            font-weight: 500;
        }

        .total-card-currency {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.4);
            margin-right: 2px;
        }

        /* ── Submit Button ── */
        .btn-submit {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.925rem;
            font-weight: 700;
            padding: 0.75rem;
            background: var(--accent);
            color: var(--primary-dark);
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            box-shadow: 0 3px 12px rgba(245, 158, 11, 0.3);
            transition: var(--transition);
            font-family: inherit;
        }

        .btn-submit:hover {
            box-shadow: 0 5px 24px rgba(245, 158, 11, 0.4);
            transform: translateY(-2px);
        }

        .btn-submit:active { transform: translateY(0); }
        .btn-submit:disabled {
            background: var(--gray-300);
            color: var(--gray-500);
            box-shadow: none;
            cursor: not-allowed;
            transform: none;
        }

        /* ── Toast ── */
        .toast-stack {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .toast-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: var(--white);
            border-radius: var(--radius-sm);
            box-shadow: var(--shadow-lg);
            padding: 0.65rem 1rem;
            min-width: 240px;
            max-width: 360px;
            font-size: 0.85rem;
            font-weight: 500;
            opacity: 0;
            transform: translateX(16px);
            transition: opacity 0.25s, transform 0.25s;
        }

        .toast-item.show { opacity: 1; transform: translateX(0); }
        .toast-success { border-left: 4px solid var(--success); }
        .toast-error { border-left: 4px solid var(--danger); }
        .toast-success .toast-icon { color: var(--success); }
        .toast-error .toast-icon { color: var(--danger); }

        /* ── Info Box ── */
        .info-box {
            background: #EFF6FF;
            border-left: 3px solid var(--sky);
            border-radius: var(--radius-sm);
            padding: 0.7rem 0.85rem;
            font-size: 0.83rem;
            color: var(--gray-700);
            margin-bottom: 0.85rem;
        }

        .info-box i { color: var(--sky); }

        /* ── Modal Overrides ── */
        .modal-header-navy {
            background: var(--primary-dark);
            color: var(--white);
            border-bottom: none;
        }

        .modal-header-navy .btn-close { filter: invert(1) brightness(0.8); }
        .modal-header-navy i { color: var(--accent); }

        .modal-header-emerald {
            background: var(--success);
            color: var(--white);
            border-bottom: none;
        }

        .modal-header-emerald .btn-close { filter: invert(1) brightness(0.8); }

        /* ── Suspended Orders ── */
        .suspended-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.7rem 0.9rem;
            background: var(--white);
            border-left: 3px solid var(--accent);
            border-radius: var(--radius-sm);
            margin-bottom: 0.5rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .suspended-row:hover {
            box-shadow: var(--shadow-md);
            transform: translateX(3px);
        }

        .suspended-row-name {
            font-weight: 600;
            color: var(--primary-dark);
            font-size: 0.875rem;
        }

        .suspended-row-meta {
            font-size: 0.78rem;
            color: var(--gray-500);
        }

        .btn-resume {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.35rem 0.8rem;
            background: var(--primary-dark);
            color: var(--white);
            border: none;
            border-radius: var(--radius-xs);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            white-space: nowrap;
            font-family: inherit;
        }

        .btn-resume:hover {
            background: var(--primary-light);
            color: var(--white);
        }

        /* ── Responsive ── */
        @media (max-width: 1024px) {
            .page-header { flex-direction: column; align-items: stretch; }
            .page-header-actions { width: 100%; flex-wrap: wrap; }
            .btn-header { flex: 1; justify-content: center; min-width: 120px; }
            .search-row { flex-direction: column; }
            .search-row .field { width: 100%; min-width: unset; }
            .shop-select-inline { width: 100%; }
            .search-wrapper { width: 100%; }
        }

        @media (max-width: 768px) {
            .layout-grid { grid-template-columns: 1fr; }
            .page-header-left { flex-wrap: wrap; }
            .page-header-icon { width: 40px; height: 40px; font-size: 1.25rem; }
            .page-title { font-size: 1.1rem; }
            .pay-distribution { grid-template-columns: 1fr; }
            .cart-table thead { display: none; }
            .cart-table tbody tr {
                display: block;
                margin-bottom: 0.6rem;
                border: 1px solid var(--gray-200);
                border-radius: var(--radius-sm);
                padding: 0.6rem;
                background: var(--white);
            }
            .cart-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.35rem 0;
                border-bottom: 1px solid var(--gray-100);
                text-align: left !important;
            }
            .cart-table tbody td:last-child {
                border-bottom: none;
                padding-top: 0.6rem;
                border-top: 1px solid var(--gray-200);
                margin-top: 0.35rem;
            }
            .cart-table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--gray-500);
                font-size: 0.7rem;
                text-transform: uppercase;
                letter-spacing: 0.04em;
            }
            .qty-input { width: 60px; }
        }

        @media (max-width: 480px) {
            .main-wrap { padding: 0.5rem; }
            .page-header { padding: 0.75rem; }
            .panel-header { padding: 0.6rem 0.9rem; font-size: 0.8rem; }
            .search-section { padding: 0.6rem 0.9rem; }
            .sidebar-section { padding: 0.6rem 0.9rem; }
            .cart-toolbar { padding: 0.4rem 0.9rem; flex-direction: column; align-items: stretch; }
            .offer-banner { padding: 0.4rem 0.9rem; }
            .toast-stack { right: 0.5rem; left: 0.5rem; }
            .toast-item { max-width: 100%; }
        }

        /* ── Utilities ── */
        .d-flex { display: flex; }
        .align-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-1 { gap: 0.4rem; }
        .gap-2 { gap: 0.8rem; }
        .flex-wrap { flex-wrap: wrap; }
        .w-100 { width: 100%; }
        .mt-1 { margin-top: 0.5rem; }
        .mb-1 { margin-bottom: 0.5rem; }
        .text-muted { color: var(--gray-500); }
        .text-danger { color: var(--danger); }
        .text-success { color: var(--success); }
        .position-relative { position: relative; }
    

        /* =========================================================
           KESSY POS — BLUE INVENTORY / SALES PAGE THEME
           Visual refresh only: existing Blade logic and IDs retained.
        ========================================================= */
        :root {
            --primary-dark:#06477F;
            --primary-mid:#07569A;
            --primary-light:#0873C1;
            --accent:#0B68B2;
            --accent-dark:#07569A;
            --accent-pale:#E5F1FC;
            --success:#0A9B70;
            --success-pale:#E7F7F1;
            --danger:#E34255;
            --danger-pale:#FDECEF;
            --info:#7546D8;
            --info-pale:#F0EAFE;
            --fuchsia:#A13BC2;
            --fuchsia-pale:#F8EAFB;
            --sky:#087FC4;
            --sky-pale:#E8F5FD;
            --gray-50:#F7FAFE;
            --gray-100:#EFF4F9;
            --gray-200:#DFE8F1;
            --gray-300:#CBD8E5;
            --gray-400:#91A4B8;
            --gray-500:#647B91;
            --gray-600:#496176;
            --gray-700:#304B63;
            --gray-800:#1C3852;
            --gray-900:#102D47;
            --shadow-sm:0 1px 4px rgba(10,55,95,.06);
            --shadow-md:0 4px 14px rgba(10,55,95,.08);
            --shadow-lg:0 8px 24px rgba(10,55,95,.12);
            --radius:9px;
            --radius-sm:7px;
            --radius-xs:5px;
        }
        body{background:#F1F5FA;color:#183A58;font-size:13px}
        .main-wrap{max-width:1920px;padding:16px 18px}
        .page-header{background:#fff;border:1px solid #DFE8F1;border-radius:9px;box-shadow:var(--shadow-sm);padding:13px 16px;margin-bottom:12px}
        .page-header:before{display:none}
        .page-title{color:#06477F;font-size:1.25rem;font-weight:800}
        .page-subtitle{color:#647B91;font-size:.76rem;margin-top:2px}
        .page-header-icon{width:43px;height:43px;background:#E6F1FB;border:0;color:#0868B2;border-radius:8px;font-size:1.35rem}
        .btn-header{background:#0868B2;color:#fff;border:1px solid #0868B2;border-radius:6px;padding:.48rem .85rem;font-size:.75rem}
        .btn-header:hover{background:#054D88;color:#fff;border-color:#054D88;transform:translateY(-1px)}
        .layout-grid{grid-template-columns:minmax(0,1fr) 340px;gap:12px}
        .panel{border:1px solid #DFE8F1;border-radius:9px;box-shadow:0 2px 7px rgba(10,55,95,.045)}
        .panel-header{background:#0863A9;padding:10px 14px;font-size:.8rem}
        .panel-header i{color:#fff}
        .search-section{padding:11px 14px;background:#FBFDFF}
        .field-label{color:#526D86;font-size:.66rem}
        .field-label i{color:#0871BC}
        .field-input,.search-wrapper input,.shop-select-inline{border:1px solid #D5E1EC;border-radius:6px;background-color:#fff;font-size:.8rem;min-height:35px}
        .field-input:focus,.search-wrapper input:focus,.shop-select-inline:focus{border-color:#1681CB;box-shadow:0 0 0 3px rgba(8,104,178,.10)}
        .cart-toolbar{background:#F7FAFE;padding:8px 14px}
        .cart-label{color:#34536D;font-size:.7rem}
        .cart-label i{color:#0871BC}
        .cart-badge{background:#DCEEFF;color:#07569A}
        .btn-clear{border-color:#E2AAB2;color:#D63D50;background:#fff}
        .cart-table{font-size:.76rem;min-width:650px}
        .cart-table thead th{background:#0863A9;color:#fff;border-color:#0A70B9;padding:9px 9px;font-size:.64rem;letter-spacing:.035em}
        .cart-table tbody td{padding:8px 9px;border-bottom:1px solid #E7EEF5;color:#294963}
        .cart-table tbody tr:nth-child(even) td{background:#F8FBFE}
        .cart-table tbody tr:hover td{background:#EDF6FE}
        .cart-table tbody tr.offer-row td{background:#ECF9F3}
        .cart-table tbody tr.return-row td{background:#FFF0F1}
        .cart-product-name{color:#07569A;font-size:.77rem}
        .qty-input{border:1px solid #D5E1EC;border-radius:5px}
        .qty-input:focus{border-color:#0871BC;background:#F0F7FE}
        .btn-delete{border-radius:5px}
        .sidebar-section{padding:11px 13px}
        .section-title{color:#526D86;font-size:.68rem}
        .section-title i{color:#0871BC}
        .customer-info{background:#F4F9FE;border:1px solid #DCE8F3;border-left:3px solid #0871BC}
        .meta-chip{background:#F0F5FA;border:1px solid #E3EBF3}
        .meta-chip-value{color:#07569A}
        .pay-distribution{background:#F7FAFE;border-color:#DFE8F1}
        .total-card{background:linear-gradient(115deg,#06477F,#0873C1);border-radius:7px;padding:12px 13px}
        .total-card-amount{color:#fff;font-size:1.25rem;font-weight:800}
        .total-card-currency{color:rgba(255,255,255,.72)}
        .btn-submit{background:#0870B9;color:#fff;border-radius:6px;box-shadow:0 3px 10px rgba(8,104,178,.2);font-size:.86rem;padding:.68rem}
        .btn-submit:hover{background:#05558F;box-shadow:0 5px 16px rgba(8,104,178,.25)}
        .info-box{background:#EAF5FD;border-left-color:#087FC4;color:#31536E}
        .modal-header-navy{background:#0863A9}
        .modal-header-emerald{background:#0A9670}
        .btn-resume{background:#0868B2}
        .btn-resume:hover{background:#054D88}
        .dropdown{border-color:#D7E4EF}
        .dropdown-item:hover{background:#EFF7FE}
        .dropdown-item-name{color:#07569A}
        .selected-badge{background:#0863A9}
        .offer-banner{background:#FBF3FD;border-bottom-color:#F0D9F5}
        .suspended-row{border-left-color:#0871BC}
        .suspended-row-name{color:#07569A}
        .alert-success{background:#E8F7F1;border-left-color:#0A9B70}
        .alert-danger{background:#FDECEF;border-left-color:#E34255}
        .stats-strip{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;margin:0 0 12px}
        .mini-stat{display:flex;align-items:center;justify-content:space-between;gap:10px;min-height:76px;padding:11px 13px;border:1px solid #DDE8F2;border-radius:8px;background:#fff;box-shadow:0 1px 4px rgba(10,55,95,.045)}
        .mini-stat-label{font-size:.68rem;font-weight:700;color:#60788F;margin-bottom:3px}
        .mini-stat-value{font-size:1.2rem;font-weight:800;line-height:1.2;color:#07569A;font-family:'DM Mono',monospace}
        .mini-stat-note{font-size:.64rem;color:#8295A8;margin-top:3px}
        .mini-stat-icon{width:42px;height:42px;display:grid;place-items:center;border-radius:8px;background:#E5F1FC;color:#0868B2;font-size:1.2rem;flex-shrink:0}
        .mini-stat.green{background:#F1FBF7;border-color:#D8F0E6}.mini-stat.green .mini-stat-icon{background:#DDF5EB;color:#07966B}.mini-stat.green .mini-stat-value{color:#07845F}
        .mini-stat.purple{background:#F8F5FF;border-color:#EAE2FC}.mini-stat.purple .mini-stat-icon{background:#EEE7FF;color:#7546D8}.mini-stat.purple .mini-stat-value{color:#7546D8}
        .mini-stat.orange{background:#FFF8F2;border-color:#F6E7D9}.mini-stat.orange .mini-stat-icon{background:#FFEDDF;color:#E56A22}.mini-stat.orange .mini-stat-value{color:#D65A18}
        @media(max-width:1100px){.layout-grid{grid-template-columns:1fr}.stats-strip{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:600px){.main-wrap{padding:9px}.stats-strip{gap:7px}.mini-stat{min-height:67px;padding:8px}.mini-stat-value{font-size:1rem}.mini-stat-icon{width:35px;height:35px;font-size:1rem}.page-header{padding:11px}.page-title{font-size:1.05rem}}

    </style>
</head>
<body>

<?php echo $__env->make("sidenav", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<main class="main-content">
    <div class="main-wrap">

        
        <div class="page-header">
            <div class="page-header-left">
                <div class="page-header-icon">
                    <i class="bi bi-bag-plus-fill"></i>
                </div>
                <div>
                    <h1 class="page-title"><?php echo app('translator')->get('messages.create_new_sales_header'); ?></h1>
                    <p class="page-subtitle"><i class="bi bi-clock-history"></i> <?php echo e(now()->format('l, F d, Y')); ?></p>
                </div>
            </div>
            <div class="page-header-actions">
                <?php if(Auth::user()->levelStatus === 'Admin'): ?>
                <button class="btn-header" data-bs-toggle="modal" data-bs-target="#pastSalesModal">
                    <i class="bi bi-pencil-square"></i> <?php echo app('translator')->get('messages.edit_past_sales'); ?>
                </button>
                <?php endif; ?>
                <button class="btn-header" data-bs-toggle="modal" data-bs-target="#suspendedModal">
                    <i class="bi bi-pause-circle"></i> <?php echo app('translator')->get('messages.suspended_orders'); ?>
                </button>
               
            </div>
        </div>

        
        <?php if(session('success')): ?>
        <div class="alert-bar alert-success">
            <span><i class="bi bi-check-circle-fill"></i> <?php echo e(session('success')); ?></span>
            <button class="alert-close" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
        </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <div class="alert-bar alert-danger">
            <span><i class="bi bi-exclamation-triangle-fill"></i> <?php echo e(session('error')); ?></span>
            <button class="alert-close" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
        </div>
        <?php endif; ?>

        
        <div class="stats-strip">
            <div class="mini-stat">
                <div><div class="mini-stat-label"><?php echo app('translator')->get('messages.products'); ?></div><div class="mini-stat-value"><?php echo e(number_format($cart->where('offered_items', '!=', 1)->count())); ?></div><div class="mini-stat-note">Products in current order</div></div>
                <div class="mini-stat-icon"><i class="bi bi-box-seam-fill"></i></div>
            </div>
            <div class="mini-stat green">
                <div><div class="mini-stat-label"><?php echo app('translator')->get('messages.items'); ?></div><div class="mini-stat-value"><?php echo e(number_format($cart->sum('pQuantity'))); ?></div><div class="mini-stat-note">Total quantity in cart</div></div>
                <div class="mini-stat-icon"><i class="bi bi-cart-check-fill"></i></div>
            </div>
            <div class="mini-stat purple">
                <div><div class="mini-stat-label"><?php echo app('translator')->get('messages.sales_type'); ?></div><div class="mini-stat-value" style="font-size:.98rem"><?php echo e(session('orderType', 'Sell')); ?></div><div class="mini-stat-note">Current transaction</div></div>
                <div class="mini-stat-icon"><i class="bi bi-receipt-cutoff"></i></div>
            </div>
            <div class="mini-stat orange">
                <div><div class="mini-stat-label"><?php echo app('translator')->get('messages.total'); ?></div><div class="mini-stat-value" style="font-size:1rem">Tsh <?php echo e(number_format($cart->sum(function($row){ return ((float)($row->productPrice ?? 0) * (float)($row->pQuantity ?? 0)) - (float)($row->discount ?? 0) + (float)($row->discount_increase ?? 0); }))); ?></div><div class="mini-stat-note">Current cart value</div></div>
                <div class="mini-stat-icon"><i class="bi bi-cash-stack"></i></div>
            </div>
        </div>

        
        <div class="layout-grid">

            
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-header-left">
                        <i class="bi bi-grid-3x3-gap-fill"></i> <?php echo app('translator')->get('messages.select_products'); ?>
                    </div>
                    <span style="font-size:0.7rem;color:rgba(255,255,255,0.5);">
                        <i class="bi bi-info-circle"></i> <?php echo e($cart->where('offered_items', '!=', 1)->count()); ?> <?php echo app('translator')->get('messages.items_in_cart'); ?>
                    </span>
                </div>

                
                <div class="search-section">
                    <div class="search-row">
                        <div class="field" style="flex:0 0 auto; min-width:140px;">
                            <label class="field-label"><i class="bi bi-tag"></i> <?php echo app('translator')->get('messages.sales_type'); ?></label>
                            <select id="orderType" name="orderType" class="field-input">
                                <option value="Sell" <?php echo e(session('orderType', 'Sell') == 'Sell' ? 'selected' : ''); ?>><i class="bi bi-circle-fill text-success"></i> <?php echo app('translator')->get('messages.pay'); ?></option>
                                <option value="Return" <?php echo e(session('orderType', 'Sell') == 'Return' ? 'selected' : ''); ?>><i class="bi bi-circle-fill text-info"></i> <?php echo app('translator')->get('messages.return'); ?></option>
                                <option value="Debt" <?php echo e(session('orderType', 'Sell') == 'Debt' ? 'selected' : ''); ?>><i class="bi bi-circle-fill text-danger"></i> <?php echo app('translator')->get('messages.credit'); ?></option>
                                <option value="Suspended" <?php echo e(session('orderType', 'Sell') == 'Suspended' ? 'selected' : ''); ?>><i class="bi bi-circle-fill text-warning"></i> <?php echo app('translator')->get('messages.suspended'); ?></option>
                            </select>
                        </div>

                        <?php if(isset($allShops) && count($allShops) > 1): ?>
                        <div class="field" style="flex:0 0 auto; min-width:150px;">
                            <label class="field-label"><i class="bi bi-shop"></i> <?php echo app('translator')->get('messages.shop_label'); ?></label>
                            <select class="shop-select-inline" id="shopSelect" onchange="changeShop(this.value)">
                                <?php $__currentLoopData = $allShops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($shop['id']); ?>" <?php echo e((session('selected_shop_id') == $shop['id']) ? 'selected' : ''); ?>>
                                    <?php echo e($shop['name']); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        <div class="search-wrapper">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" id="productSearch" placeholder="<?php echo app('translator')->get('messages.search_products_placeholder'); ?>" autocomplete="off">
                            <div class="dropdown" id="productDropdown"></div>
                        </div>
                    </div>
                </div>

                
                <div class="cart-section">
                    <div class="cart-toolbar">
                        <div class="d-flex align-center gap-1">
                            <span class="cart-label"><i class="bi bi-cart3"></i> <?php echo app('translator')->get('messages.cart'); ?></span>
                            <span class="cart-badge" id="cartBadge">
                                <?php echo e($cart->where('offered_items', '!=', 1)->count()); ?>

                            </span>
                            <span style="font-size:0.7rem;color:var(--gray-400);">
                                <i class="bi bi-box-seam"></i> <?php echo e($cart->sum('pQuantity')); ?> <?php echo app('translator')->get('messages.items'); ?>
                            </span>
                        </div>
                        <button type="button" class="btn-clear" id="clearCartBtn">
                            <i class="bi bi-x"></i> <?php echo app('translator')->get('messages.clear_all'); ?>
                        </button>
                    </div>

                    <?php $offeredItems = $cart->where('offered_items', 1); ?>
                    <?php if($offeredItems->count() > 0): ?>
                    <div class="offer-banner">
                        <div class="offer-banner-top">
                            <span><i class="bi bi-gift-fill"></i> <?php echo app('translator')->get('messages.free_offer_items_included', ['count' => $offeredItems->count()]); ?></span>
                        </div>
                        <div id="offerList" style="display:flex;flex-wrap:wrap;gap:0.35rem;">
                            <?php $__currentLoopData = $offeredItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $oiName = DB::table('products')->where('account', getCurrentShopId())->where('product_id', $oi->productId)->value('name01') ?? __('messages.unknown');
                            ?>
                            <div class="offer-chip" data-product-id="<?php echo e($oi->productId); ?>">
                                <i class="bi bi-gift"></i>
                                <span><?php echo e($oiName); ?> &times;<?php echo e($oi->pQuantity); ?></span>
                                <button type="button" class="offer-chip-remove" title="<?php echo app('translator')->get('messages.remove_free_item'); ?>" onclick="removeOfferItem(this,'<?php echo e($oi->order_id); ?>','<?php echo e($oi->productId); ?>',<?php echo e($oi->pQuantity); ?>)">
                                    <i class="bi bi-x-circle-fill"></i>
                                </button>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="cart-scroll">
                        <?php
                            $cartProductIds = $cart->pluck('productId')->toArray();
                            $cartOffers = [];
                            if(!empty($offers) && !empty($cartProductIds)) {
                                foreach($offers as $offer) {
                                    $requiredPids = $offer->requiredItems->pluck('product_id')->toArray();
                                    $hasAll = true;
                                    foreach($requiredPids as $rpid) {
                                        if(!in_array($rpid, $cartProductIds)) { $hasAll = false; break; }
                                    }
                                    if($hasAll) {
                                        $cartOffers[$offer->id] = $offer;
                                    }
                                }
                            }
                            $cartItems = $cart->where('offered_items', '!=', 1);
                        ?>

                        <?php if($cartItems->count() > 0): ?>
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th><?php echo app('translator')->get('messages.products_col_product'); ?></th>
                                    <th class="text-center"><?php echo app('translator')->get('messages.col_qty'); ?></th>
                                    <th class="text-right"><?php echo app('translator')->get('messages.col_unit_price'); ?></th>
                                    <th class="text-right"><?php echo app('translator')->get('messages.amount'); ?></th>
                                    <th class="text-right"><?php echo app('translator')->get('messages.discount'); ?></th>
                                    <th class="text-right"><?php echo app('translator')->get('messages.total'); ?></th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $productObj = DB::table('products')->where('account', getCurrentShopId())->where('product_id', $item->productId)->first('name01');
                                    $productName = $productObj->name01 ?? 'Unknown Product';
                                    $amount        = $item->productPrice * $item->pQuantity;
                                    $discIncrease  = $item->discount_increase ?? 0;
                                    $netAdj        = ($item->discount ?? 0) - $discIncrease;
                                    $total         = $amount - $netAdj;

                                    $qualifies = false; $offerInfo = null; $freeCount = 0;
                                    foreach($cartOffers as $offer) {
                                        $reqItem = $offer->requiredItems->first();
                                        if(!$reqItem) continue;
                                        if($item->productId == $reqItem->product_id && $item->pQuantity >= $reqItem->required_quantity) {
                                            $times = floor($item->pQuantity / $reqItem->required_quantity);
                                            $qualifies = true;
                                            $freeCount = $times * $offer->offer_quantity;
                                            $offerInfo = $offer;
                                            break;
                                        }
                                    }
                                ?>
                                <tr class="<?php echo e($qualifies ? 'offer-row' : ''); ?> <?php echo e(session('orderType', 'Sell') == 'Return' ? 'return-row' : ''); ?>">
                                    <td data-label="<?php echo app('translator')->get('messages.products_col_product'); ?>">
                                        <div class="cart-product-name" title="<?php echo e($productName); ?>">
                                            <?php echo e($productName); ?>

                                        </div>
                                        <?php if($qualifies && $offerInfo): ?>
                                            <span class="dd-badge dd-badge-offer" style="margin-top:2px;">
                                                <i class="bi bi-gift-fill"></i> +<?php echo e($freeCount); ?> <?php echo app('translator')->get('messages.free'); ?>
                                            </span>
                                        <?php endif; ?>
                                        <div style="font-size:0.6rem;color:var(--gray-400);margin-top:2px;">
                                            <i class="bi bi-hash"></i> <?php echo e($item->productId); ?>

                                        </div>
                                    </td>
                                    <td data-label="<?php echo app('translator')->get('messages.col_qty'); ?>" class="text-center">
                                        <input type="number" class="qty-input" value="<?php echo e($item->pQuantity); ?>"
                                            min="1" step="1"
                                            onchange="updateCartItem('<?php echo e($item->order_id); ?>','<?php echo e($item->productId); ?>','pQuantity',this.value)">
                                    </td>
                                    <td data-label="<?php echo app('translator')->get('messages.col_unit_price'); ?>" class="text-right mono">
                                        <?php echo e(number_format($item->productPrice)); ?>

                                    </td>
                                    <td data-label="<?php echo app('translator')->get('messages.amount'); ?>" class="text-right mono">
                                        <?php echo e(number_format($amount)); ?>

                                    </td>
                                    <td data-label="<?php echo app('translator')->get('messages.discount'); ?>" class="text-center">
                                        <input type="number" class="qty-input" value="<?php echo e($netAdj); ?>"
                                            placeholder="±" style="width:65px;"
                                            onchange="updateCartItem('<?php echo e($item->order_id); ?>','<?php echo e($item->productId); ?>','adjustment',this.value)">
                                    </td>
                                    <td data-label="<?php echo app('translator')->get('messages.total'); ?>" class="text-right mono fw-bold">
                                        <?php echo e(number_format($total)); ?>

                                    </td>
                                    <td data-label="<?php echo app('translator')->get('messages.actions'); ?>" class="text-center">
                                        <form action="removeFromCart" method="post" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="itemId" value="<?php echo e($item->productId); ?>">
                                            <input type="hidden" name="orderId" value="<?php echo e($item->order_id); ?>">
                                            <input type="hidden" name="prodQuantity" value="<?php echo e($item->pQuantity); ?>">
                                            <button type="submit" class="btn-delete" title="<?php echo app('translator')->get('messages.remove'); ?>">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <div class="empty-cart">
                            <i class="bi bi-cart-x"></i>
                            <div class="empty-cart-title"><?php echo app('translator')->get('messages.cart_empty'); ?></div>
                            <p><?php echo app('translator')->get('messages.cart_empty_hint'); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-header-left"><i class="bi bi-receipt"></i> <?php echo app('translator')->get('messages.sales_summary'); ?></div>
                    <span style="font-size:0.7rem;color:rgba(255,255,255,0.5);">
                        <i class="bi bi-clock"></i> <?php echo e(now()->format('H:i')); ?>

                    </span>
                </div>

                
                <div class="sidebar-section">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.6rem;">
                        <div class="section-title" style="margin-bottom:0;"><i class="bi bi-person-circle"></i> <?php echo app('translator')->get('messages.customer'); ?></div>
                        <a href="customers" class="btn-header" style="background:var(--primary-dark);color:#fff;font-size:0.7rem;padding:0.2rem 0.6rem;border:none;">
                            <i class="bi bi-plus-lg"></i> <?php echo app('translator')->get('messages.new'); ?>
                        </a>
                    </div>

                    <form action="saveInfos" id="saveInfosForm" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="orderId" value="<?php echo e($orders->order_id ?? ''); ?>">
                        <input type="hidden" name="orderType" id="orderTypeForm1" value="<?php echo e(session('orderType', 'Sell')); ?>">
                        <input type="hidden" name="selectedCustomer" id="selectedCustomer">

                        <div class="position-relative">
                            <div class="search-wrapper">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" id="customerSearch" class="field-input" style="padding-left:2.2rem;"
                                    placeholder="<?php echo app('translator')->get('messages.search_customer_placeholder'); ?>" autocomplete="off">
                                <div class="dropdown" id="customerDropdown"></div>
                            </div>
                        </div>

                        <div id="customerBadgeWrap" style="display:<?php echo e(!empty($orders->cPhone) ? 'block' : 'none'); ?>;margin-top:0.5rem;">
                            <span class="selected-badge">
                                <span id="customerBadgeText"><?php echo e($orders->cName ?? ''); ?></span>
                                <button type="button" class="selected-badge-remove" onclick="clearCustomer()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </span>
                        </div>
                    </form>

                    <?php
                        $checkz = DB::table('customers')->where('id', $orders->cPhone ?? '')->first();
                        $odez1   = DB::table('orders')->whereIn('status', ['Debt','Partial'])
                                    ->where('cPhone', $orders->cPhone ?? '')->sum('credit');
                        $odez2   = DB::table('debts')->where('cId', $orders->cPhone ?? '')->sum('amount');
                        $odez    = +($odez1 - $odez2);
                        $totalSpent = $odez1;
                    ?>

                    <?php if(!empty($orders->cPhone)): ?>
                    <div class="customer-info" id="custInfoCard">
                        <div class="customer-info-title"><i class="bi bi-person-badge"></i> <?php echo app('translator')->get('messages.customer_info'); ?></div>
                        <div class="customer-row">
                            <span class="customer-key"><?php echo app('translator')->get('messages.name'); ?></span>
                            <span class="customer-value" id="custName"><?php echo e($checkz->name ?? '—'); ?></span>
                        </div>
                        <div class="customer-row">
                            <span class="customer-key"><?php echo app('translator')->get('messages.credit_limit'); ?></span>
                            <span class="customer-value" id="custLimit"><?php echo e(number_format($checkz->limits ?? 0)); ?></span>
                        </div>
                        <div class="customer-row">
                            <span class="customer-key"><?php echo app('translator')->get('messages.used_credit'); ?></span>
                            <span class="customer-value debt" id="custCredit"><?php echo e(number_format($odez1 ?? 0)); ?></span>
                        </div>
                        <div class="customer-row">
                            <span class="customer-key"><?php echo app('translator')->get('messages.total_paid'); ?></span>
                            <span class="customer-value" id="custPaid"><?php echo e(number_format($odez2 ?? 0)); ?></span>
                        </div>
                        <div class="customer-row">
                            <span class="customer-key"><?php echo app('translator')->get('messages.remaining_debt'); ?></span>
                            <span class="customer-value debt" id="custRemaining"><?php echo e(number_format($odez ?? 0)); ?></span>
                        </div>
                        <div class="customer-row">
                            <span class="customer-key"><i class="bi bi-check-circle-fill text-success me-1"></i><?php echo app('translator')->get('messages.available'); ?></span>
                            <span class="customer-value available" id="custAvail"><?php echo e(number_format(($checkz->limits ?? 0) - ($odez ?? 0))); ?></span>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="customer-info" id="custInfoCard" style="display:none;"></div>
                    <?php endif; ?>

                    <?php if(!empty($orders->orderName)): ?>
                    <div class="order-meta">
                        <div class="meta-chip">
                            <span class="meta-chip-label"><i class="bi bi-clipboard me-1"></i><?php echo app('translator')->get('messages.order_id'); ?></span>
                            <span class="meta-chip-value"><?php echo e($orders->orderName ?? '—'); ?></span>
                        </div>
                        <div class="meta-chip">
                            <span class="meta-chip-label"><i class="bi bi-bar-chart me-1"></i><?php echo app('translator')->get('messages.status'); ?></span>
                            <span class="meta-chip-value"><?php echo e($orders->status ?? '—'); ?></span>
                        </div>
                        <div class="meta-chip">
                            <span class="meta-chip-label"><i class="bi bi-calendar-date me-1"></i><?php echo app('translator')->get('messages.date'); ?></span>
                            <span class="meta-chip-value"><?php echo e($orders->created_at ? date('M d, Y', strtotime($orders->created_at)) : '—'); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                
                <div class="sidebar-section">
                    <div class="section-title"><i class="bi bi-person-badge"></i> <?php echo app('translator')->get('messages.seller'); ?></div>
                    
                    <form action="saveSeller" id="saveSellerForm" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="orderId" value="<?php echo e($orders->order_id ?? ''); ?>">
                        <input type="hidden" name="orderType" id="orderTypeForm2" value="<?php echo e(session('orderType', 'Sell')); ?>">

                        <div class="position-relative">
                            <div class="search-wrapper">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text" id="sellerSearch" class="field-input" style="padding-left:2.2rem;"
                                    placeholder="<?php echo app('translator')->get('messages.search_seller_placeholder'); ?>" autocomplete="off"
                                    value="<?php echo e($orders->served_by ?? ''); ?>">
                                <input type="hidden" name="selectedSeller" id="selectedSeller"
                                    value="<?php echo e($orders->served_by ?? ''); ?>">
                                <div class="dropdown" id="sellerDropdown"></div>
                            </div>
                        </div>
                        
                        <div id="sellerBadgeWrap" style="display:<?php echo e($orders->served_by ? 'block' : 'none'); ?>;margin-top:0.5rem;">
                            <span class="selected-badge">
                                <span id="sellerBadgeText"><?php echo e($orders->served_by ?? ''); ?></span>
                                <button type="button" class="selected-badge-remove" onclick="clearSeller()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>

                
                <div class="sidebar-section" style="border-bottom:none;">
                    <form action="payout" method="post" id="payoutForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="orderId"          value="<?php echo e($orders->order_id ?? ''); ?>">
                        <input type="hidden" name="served"           id="servedHidden" value="<?php echo e($orders->served_by ?? ''); ?>">
                        <input type="hidden" name="selectedCustomer" id="custHidden">
                        <input type="hidden" name="offered_items"    id="offeredItemsInput" value="">
                        <input type="hidden" name="orderType" id="orderTypeHidden" value="<?php echo e(session('orderType', 'Sell')); ?>">

                        <div class="section-title"><i class="bi bi-sliders"></i> <?php echo app('translator')->get('messages.order_settings'); ?></div>

                        <div id="payDistWrap">
                            <div class="pay-distribution">
                                <div>
                                    <div class="pay-dist-label"><i class="bi bi-cash me-1"></i><?php echo app('translator')->get('messages.paid'); ?></div>
                                    <input type="number" id="paidInput" name="paid" class="field-input" style="margin:0;">
                                </div>
                                <div>
                                    <div class="pay-dist-label"><i class="bi bi-pencil me-1"></i><?php echo app('translator')->get('messages.credit'); ?></div>
                                    <input type="number" id="creditInput" name="credit" class="field-input" style="margin:0;">
                                </div>
                                <div>
                                    <div class="pay-dist-label"><i class="bi bi-bank me-1"></i><?php echo app('translator')->get('messages.bank'); ?></div>
                                    <input type="number" id="bankInput" name="bank" class="field-input" style="margin:0;">
                                </div>
                            </div>
                        </div>

                        <div id="payMethodWrap" class="field">
                            <label class="field-label"><i class="bi bi-wallet2"></i> <?php echo app('translator')->get('messages.payment_method'); ?></label>
                            <select id="paymentType" name="paymentMethod" class="field-input">
                                <option value="Cash"><i class="bi bi-cash me-1"></i><?php echo app('translator')->get('messages.cash'); ?></option>
                                <option value="Credit"><i class="bi bi-credit-card me-1"></i><?php echo app('translator')->get('messages.credit'); ?></option>
   
                            </select>
                        </div>

                        <div id="debtNote" style="display:none;" class="field">
                            <label class="field-label"><i class="bi bi-sticky"></i> <?php echo app('translator')->get('messages.credit_note'); ?></label>
                            <textarea class="field-input" placeholder="<?php echo app('translator')->get('messages.add_a_note_placeholder'); ?>" rows="2" style="resize:none;"></textarea>
                        </div>

                        <div id="suspendNote" style="display:none;" class="field">
                            <label class="field-label"><i class="bi bi-pause-circle"></i> <?php echo app('translator')->get('messages.suspension_reason'); ?></label>
                            <textarea class="field-input" placeholder="<?php echo app('translator')->get('messages.suspension_reason_placeholder'); ?>" rows="2" style="resize:none;"></textarea>
                        </div>

                        <?php if(canUser('manage_sales_date')): ?>
                        <?php
                            $orderDate = null;
                            if (!empty($orders->created_at)) {
                                $d = \Carbon\Carbon::parse($orders->created_at);
                                if ($d->format('Y-m-d') !== date('Y-m-d')) $orderDate = $d->format('Y-m-d');
                            }
                        ?>
                        <div class="field">
                            <label class="field-label"><i class="bi bi-calendar3"></i> <?php echo app('translator')->get('messages.sale_date'); ?> <span style="font-weight:400;color:var(--gray-400);font-size:0.65rem;"><?php echo app('translator')->get('messages.optional'); ?></span></label>
                            <input type="date" name="saleDate" value="<?php echo e($orderDate ?? date('Y-m-d')); ?>" class="field-input">
                        </div>
                        <?php endif; ?>

                        
                        <?php
                            $totalAdj = ($totalD ?? 0) - ($totalDI ?? 0);
                            $grandTotal = $totalP;
                        ?>
                        <div class="price-rows" style="margin-top:0.75rem;">
                            <div class="price-row">
                                 <span class="key"><i class="bi bi-bar-chart me-1"></i><?php echo app('translator')->get('messages.subtotal'); ?></span>
                                <span class="val mono" id="sumSubtotal"><?php echo e(number_format($totalP + $totalD + $totalDI)); ?></span>
                            </div>
                            <div class="price-row">
                                 <span class="key"><i class="bi bi-tag me-1"></i><?php echo app('translator')->get('messages.discount'); ?></span>
                                <span class="val mono" id="sumDiscount"><?php echo e(number_format($totalAdj)); ?></span>
                            </div>
                            <div class="price-row" style="border-top:1px dashed var(--gray-200);padding-top:0.4rem;">
                                 <span class="key" style="font-weight:700;"><i class="bi bi-box me-1"></i><?php echo app('translator')->get('messages.items'); ?></span>
                                <span class="val mono"><?php echo e($cart->sum('pQuantity')); ?></span>
                            </div>
                        </div>

                        <div class="total-card">
                            <div>
                                <div class="total-card-label"><i class="bi bi-cash-stack me-1"></i><?php echo app('translator')->get('messages.total'); ?></div>
                                <div style="color:rgba(255,255,255,0.4);font-size:0.65rem;">
                                    <i class="bi bi-box-seam"></i> <?php echo e($cart->where('offered_items', '!=', 1)->count()); ?> <?php echo app('translator')->get('messages.products'); ?>
                                </div>
                            </div>
                            <div class="total-card-amount">
                                <span class="total-card-currency">Tsh</span><span id="grandTotalEl"><?php echo e(number_format($grandTotal)); ?></span>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit" id="submitBtn">
                            <i class="bi bi-check-circle-fill"></i> <?php echo app('translator')->get('messages.submit_sale'); ?>
                        </button>

                        <div style="display:flex;gap:0.5rem;margin-top:0.5rem;flex-wrap:wrap;">
                            <button type="button" class="btn-header" style="background:var(--gray-100);color:var(--gray-700);border-color:var(--gray-300);flex:1;justify-content:center;" onclick="suspendOrder()">
                                <i class="bi bi-pause-circle"></i> <?php echo app('translator')->get('messages.suspend'); ?>
                            </button>
                            <button type="button" class="btn-header" style="background:var(--gray-100);color:var(--gray-700);border-color:var(--gray-300);flex:1;justify-content:center;" onclick="printInvoice()">
                                <i class="bi bi-printer"></i> <?php echo app('translator')->get('messages.print'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>
</main>




<div class="modal fade" id="pastSalesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-navy">
                <h5 class="modal-title"><i class="bi bi-pencil-square"></i> <?php echo app('translator')->get('messages.edit_past_sales'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="info-box">
                    <i class="bi bi-info-circle me-1"></i>
                    <?php echo app('translator')->get('messages.past_sales_search_note'); ?>
                </div>
                <div class="position-relative" style="margin-bottom:1rem;">
                    <div class="search-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" id="pastSalesSearch" class="field-input" style="padding-left:2.2rem;" placeholder="<?php echo app('translator')->get('messages.search_by_customer_name'); ?>" autocomplete="off">
                        <div class="dropdown" id="pastSalesDropdown"></div>
                    </div>
                </div>
                <div id="pastSalesResults" style="max-height:400px;overflow-y:auto;"></div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="confirmReturnModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-navy">
                <h5 class="modal-title"><i class="bi bi-arrow-return-left"></i> <?php echo app('translator')->get('messages.confirm_return'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="margin-bottom:0.75rem;font-size:0.9rem;color:var(--gray-600);">
                    <?php echo app('translator')->get('messages.return_to_orders_note'); ?>
                </p>
                <div id="returnSaleDetails" class="customer-info" style="display:none;"></div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn-header" style="background:var(--gray-100);color:var(--gray-700);border-color:var(--gray-300);" data-bs-dismiss="modal"><?php echo app('translator')->get('messages.cancel'); ?></button>
                <button type="button" id="confirmReturnBtn" class="btn-submit" style="width:auto;padding:0.5rem 1.2rem;">
                    <i class="bi bi-check-circle-fill"></i> <?php echo app('translator')->get('messages.confirm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="suspendedModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-navy">
                <h5 class="modal-title"><i class="bi bi-pause-circle"></i> <?php echo app('translator')->get('messages.suspended_orders'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height:70vh;overflow-y:auto;">
                <?php $__empty_1 = true; $__currentLoopData = $Suspended; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="suspended-row">
                    <div>
                        <div class="suspended-row-name"><?php echo e($order->cName ?: __('messages.no_customer')); ?></div>
                        <div class="suspended-row-meta">
                            <span><i class="bi bi-cash"></i> Tsh <?php echo e(number_format($order->total_price)); ?></span>
                            <span style="margin-left:0.5rem;"><i class="bi bi-box-seam"></i> <?php echo e($order->items_count ?? 0); ?> <?php echo app('translator')->get('messages.items'); ?></span>
                            <span style="margin-left:0.5rem;"><i class="bi bi-clock"></i> <?php echo e(date('M d, H:i', strtotime($order->created_at))); ?></span>
                        </div>
                    </div>
                    <form action="resumeOrder" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="orderId" value="<?php echo e($order->order_id); ?>">
                        <button type="submit" class="btn-resume">
                            <?php echo app('translator')->get('messages.resume'); ?> <i class="bi bi-arrow-right-short"></i>
                        </button>
                    </form>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="text-align:center;padding:2.5rem 1rem;color:var(--gray-400);">
                    <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:0.5rem;"></i>
                    <?php echo app('translator')->get('messages.no_suspended_orders'); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="offerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-emerald">
                <h5 class="modal-title"><i class="bi bi-gift-fill me-2"></i><?php echo app('translator')->get('messages.special_offer'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center" style="padding:1.5rem;">
                <i class="bi bi-gift" style="font-size:3rem;color:var(--success);display:block;margin-bottom:1rem;"></i>
                <p style="font-size:0.9rem;color:var(--gray-600);margin-bottom:0.75rem;"><?php echo app('translator')->get('messages.special_offer_qualify'); ?></p>
                <div style="background:var(--success-pale);border-left:3px solid var(--success);border-radius:var(--radius-sm);padding:0.75rem;margin-bottom:1rem;font-size:0.875rem;font-weight:600;color:#065F46;">
                    <i class="bi bi-gift me-1"></i><?php echo app('translator')->get('messages.special_offer_buy_get_free', ['req' => '<span id="offerReqQty">0</span>', 'free' => '<span id="offerFreeQty">0</span>']); ?>
                </div>
                <p style="font-size:0.85rem;color:var(--gray-600);margin-bottom:1rem;"><?php echo app('translator')->get('messages.product_label'); ?> <strong id="offerProdName"></strong></p>
                <div style="text-align:left;">
                    <label class="field-label"><i class="bi bi-hash"></i> <?php echo app('translator')->get('messages.how_many_free_items'); ?></label>
                    <input type="number" id="offerAcceptQty" class="field-input" value="0" min="0" style="margin-top:0.3rem;">
                    <div class="field-hint" style="margin-top:0.3rem;font-size:0.7rem;color:var(--gray-400);">
                        <?php echo app('translator')->get('messages.maximum_free_items', ['count' => '<span id="offerMaxQty">0</span>']); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn-header" style="background:var(--gray-100);color:var(--gray-700);border-color:var(--gray-300);" data-bs-dismiss="modal" onclick="declineOffer()"><?php echo app('translator')->get('messages.no_thanks'); ?></button>
                <button type="button" class="btn-submit" onclick="acceptOffer()" style="width:auto;padding:0.5rem 1.2rem;">
                    <i class="bi bi-check-circle-fill"></i> <?php echo app('translator')->get('messages.accept_offer'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<div class="toast-stack" id="toastStack"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
// ════════════════════════════════════════════
// State
// ════════════════════════════════════════════
let selectedProductId   = null;
let selectedCustomerObj = null;
let selectedSellerName  = null;
const offersData        = <?php echo json_encode($offers ?? [], 15, 512) ?>;

function getOffer(productId) { return offersData.find(o => o.product_id === productId); }

// ════════════════════════════════════════════
// PRODUCT SEARCH
// ════════════════════════════════════════════
const productSearch = document.getElementById('productSearch');
const productDD     = document.getElementById('productDropdown');

let searchTimeout = null;

productSearch.addEventListener('input', async e => {
    const q = e.target.value.trim();
    if (q.length < 1) { productDD.classList.remove('open'); return; }

    clearTimeout(searchTimeout);
    productDD.innerHTML = '<div class="dd-loading"><i class="bi bi-hourglass-split me-1"></i> ' + <?php echo json_encode(__('messages.searching'), 15, 512) ?> + '</div>';
    productDD.classList.add('open');

    searchTimeout = setTimeout(async () => {
        try {
            const res  = await fetch(`<?php echo e(url('searchProduct')); ?>?query=${encodeURIComponent(q)}`);
            const data = await res.json();

            if (!data.length) {
                productDD.innerHTML = '<div class="dd-empty">' + <?php echo json_encode(__('messages.no_products_found'), 15, 512) ?> + '</div>';
            } else {
                productDD.innerHTML = data.map(p => {
                    const offer = getOffer(p.product_id);
                    const offerBadge = offer
                        ? `<span class="dd-badge dd-badge-offer"><i class="bi bi-gift-fill"></i> Buy ${offer.required_quantity} Get ${offer.offer_quantity}</span>`
                        : '';
                    const stockBadge = p.quantity <= 5 
                        ? `<span class="dd-badge dd-badge-debt"><i class="bi bi-exclamation-triangle"></i> ${p.quantity} Low Stock</span>` 
                        : `<span class="dd-badge dd-badge-stock"><i class="bi bi-box-seam"></i> ${p.quantity}</span>`;
                    return `<div class="dropdown-item" onclick="addProductToOrder(${p.id}, '${escHtml(p.name01)}', ${p.sPrice})">
                        <div class="dropdown-item-name">${escHtml(p.name01)} ${offerBadge}</div>
                        <div class="dropdown-item-meta">
                            <span class="dd-badge dd-badge-price">${Number(p.bPrice).toLocaleString()} Tsh</span>
                            ${stockBadge}
                        </div>
                    </div>`;
                }).join('');
            }
        } catch {
            productDD.innerHTML = '<div class="dd-empty" style="color:var(--danger);">' + <?php echo json_encode(__('messages.error_loading_products'), 15, 512) ?> + '</div>';
        }
    }, 300);
});

function addProductToOrder(id, name, price) {
    productSearch.value = name;
    productDD.classList.remove('open');

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo e(url("newOrder")); ?>';
    const orderTypeSel = document.getElementById('orderType');
    const orderType = orderTypeSel ? orderTypeSel.value : 'Sell';
    form.innerHTML = `
        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
        <input type="hidden" name="pId" value="${id}">
        <input type="hidden" name="orderType" value="${orderType}">
        <input type="hidden" name="served" value="${document.getElementById('servedHidden').value}">
    `;
    document.body.appendChild(form);
    form.submit();
}

// ════════════════════════════════════════════
// CUSTOMER SEARCH
// ════════════════════════════════════════════
const custSearch = document.getElementById('customerSearch');
const custDD     = document.getElementById('customerDropdown');

let custTimeout = null;

custSearch.addEventListener('input', async e => {
    const q = e.target.value.trim();
    if (q.length < 1) { custDD.classList.remove('open'); return; }

    clearTimeout(custTimeout);
    custDD.innerHTML = '<div class="dd-loading"><i class="bi bi-hourglass-split me-1"></i> ' + <?php echo json_encode(__('messages.searching'), 15, 512) ?> + '</div>';
    custDD.classList.add('open');

    custTimeout = setTimeout(async () => {
        try {
            const res  = await fetch(`<?php echo e(url('searchCustomers')); ?>?query=${encodeURIComponent(q)}`);
            const data = await res.json();
            if (!data.length) {
                custDD.innerHTML = '<div class="dd-empty">' + <?php echo json_encode(__('messages.no_customers_found'), 15, 512) ?> + '</div>';
            } else {
                custDD.innerHTML = data.map(c => {
                    const debtClass = c.remaining_debt > 0 ? 'dd-badge-debt' : 'dd-badge-stock';
                    return `<div class="dropdown-item" onclick="selectCustomer(${c.id}, '${escHtml(c.name)}', ${c.limits || 0})">
                        <div class="dropdown-item-name">${escHtml(c.name)}</div>
                        <div class="dropdown-item-meta">
                            <span><i class="bi bi-phone"></i> ${c.phone || 'N/A'}</span>
                            <span class="dd-badge ${debtClass}">Debt: ${Number(c.remaining_debt || 0).toLocaleString()}</span>
                            <span class="dd-badge dd-badge-price">Limit: ${Number(c.limits || 0).toLocaleString()}</span>
                        </div>
                    </div>`;
                }).join('');
            }
        } catch {
            custDD.innerHTML = '<div class="dd-empty" style="color:var(--danger);">' + <?php echo json_encode(__('messages.error'), 15, 512) ?> + '</div>';
        }
    }, 300);
});

async function selectCustomer(id, name, limit) {
    const orderType = document.getElementById('orderTypeHidden')?.value || 'Sell';
    document.getElementById('orderTypeForm1').value = orderType;
    try {
        await fetch('<?php echo e(url('setOrderType')); ?>', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Content-Type': 'application/json' },
            body: JSON.stringify({ orderType })
        });
    } catch {}

    custSearch.value = name;
    custDD.classList.remove('open');
    document.getElementById('selectedCustomer').value   = `${name}|${id}`;
    document.getElementById('custHidden').value         = `${name}|${id}`;
    document.getElementById('customerBadgeText').textContent = `${name}`;
    document.getElementById('customerBadgeWrap').style.display = 'block';

    document.getElementById('saveInfosForm').submit();

    try {
        const res  = await fetch(`<?php echo e(url('getCustomerDetails')); ?>/${id}`);
        const data = await res.json();
        showCustInfo(data.name, data.limits, data.credit, data.paid, data.remaining_debt, data.available);
        selectedCustomerObj = data;
    } catch {
        showCustInfo(name, limit, 0, 0, 0, limit);
    }
}

function showCustInfo(name, limits, credit, paid, remainingDebt, available) {
    const card = document.getElementById('custInfoCard');
    card.style.display = '';
    card.innerHTML = `
        <div class="customer-info-title"><i class="bi bi-person-badge"></i> <?php echo app('translator')->get('messages.customer_info'); ?></div>
        <div class="customer-row"><span class="customer-key"><?php echo app('translator')->get('messages.name'); ?></span><span class="customer-value">${name}</span></div>
        <div class="customer-row"><span class="customer-key"><?php echo app('translator')->get('messages.credit_limit'); ?></span><span class="customer-value">${Number(limits||0).toLocaleString()}</span></div>
        <div class="customer-row"><span class="customer-key"><?php echo app('translator')->get('messages.used_credit'); ?></span><span class="customer-value debt">${Number(credit||0).toLocaleString()}</span></div>
        <div class="customer-row"><span class="customer-key"><?php echo app('translator')->get('messages.total_paid'); ?></span><span class="customer-value">${Number(paid||0).toLocaleString()}</span></div>
        <div class="customer-row"><span class="customer-key"><?php echo app('translator')->get('messages.remaining_debt'); ?></span><span class="customer-value debt">${Number(remainingDebt||0).toLocaleString()}</span></div>
        <div class="customer-row"><span class="customer-key"><i class="bi bi-check-circle-fill text-success me-1"></i><?php echo app('translator')->get('messages.available'); ?></span><span class="customer-value available">${Number(available||0).toLocaleString()}</span></div>
    `;
    const pt = document.getElementById('paymentType');
    if (pt) {
        const creditOpt = pt.querySelector('option[value="Credit"]');
        if (creditOpt) creditOpt.disabled = !!(limits && Number(limits) <= 0);
        if (pt.value === 'Credit' && creditOpt && creditOpt.disabled) pt.value = 'Cash';
    }
}

function clearCustomer() {
    custSearch.value = '';
    document.getElementById('selectedCustomer').value = '';
    document.getElementById('custHidden').value = '';
    document.getElementById('customerBadgeWrap').style.display = 'none';
    document.getElementById('custInfoCard').style.display = 'none';
    custDD.classList.remove('open');
    selectedCustomerObj = null;
    const pt = document.getElementById('paymentType');
    if (pt) {
        const creditOpt = pt.querySelector('option[value="Credit"]');
        if (creditOpt) creditOpt.disabled = false;
    }
}

// ════════════════════════════════════════════
// SELLER SEARCH
// ════════════════════════════════════════════
const sellerSearch = document.getElementById('sellerSearch');
const sellerDD     = document.getElementById('sellerDropdown');

let sellerTimeout = null;

sellerSearch.addEventListener('input', async e => {
    const q = e.target.value.trim();
    if (q.length < 1) { sellerDD.classList.remove('open'); return; }

    clearTimeout(sellerTimeout);
    sellerDD.innerHTML = '<div class="dd-loading"><i class="bi bi-hourglass-split me-1"></i> ' + <?php echo json_encode(__('messages.searching'), 15, 512) ?> + '</div>';
    sellerDD.classList.add('open');

    sellerTimeout = setTimeout(async () => {
        try {
            const res  = await fetch(`<?php echo e(url('searchSellers')); ?>?query=${encodeURIComponent(q)}`);
            const data = await res.json();
            if (!data.length) {
                sellerDD.innerHTML = '<div class="dd-empty">' + <?php echo json_encode(__('messages.no_sellers_found'), 15, 512) ?> + '</div>';
            } else {
                sellerDD.innerHTML = data.map(s => `
                    <div class="dropdown-item" onclick="selectSeller('${escHtml(s.name)}', '${escHtml(s.levelStatus)}')">
                        <div class="dropdown-item-name">${escHtml(s.name)}</div>
                        <div class="dropdown-item-meta"><span class="dd-badge dd-badge-stock">${s.levelStatus}</span></div>
                    </div>
                `).join('');
            }
        } catch {
            sellerDD.innerHTML = '<div class="dd-empty" style="color:var(--danger);">' + <?php echo json_encode(__('messages.error'), 15, 512) ?> + '</div>';
        }
    }, 300);
});

function selectSeller(name, role) {
    const orderType = document.getElementById('orderTypeHidden')?.value || 'Sell';
    document.getElementById('orderTypeForm2').value = orderType;
    try {
        fetch('<?php echo e(url('setOrderType')); ?>', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Content-Type': 'application/json' },
            body: JSON.stringify({ orderType })
        });
    } catch {}

    sellerSearch.value = name;
    sellerDD.classList.remove('open');
    document.getElementById('selectedSeller').value = name;
    document.getElementById('servedHidden').value = name;
    document.getElementById('sellerBadgeText').textContent = `${name} · ${role}`;
    document.getElementById('sellerBadgeWrap').style.display = 'block';
    selectedSellerName = name;

    document.getElementById('saveSellerForm').submit();
}

function clearSeller() {
    sellerSearch.value = '';
    document.getElementById('servedHidden').value = '';
    document.getElementById('sellerBadgeWrap').style.display = 'none';
    sellerDD.classList.remove('open');
    selectedSellerName = null;
}

// ════════════════════════════════════════════
// ORDER TYPE
// ════════════════════════════════════════════
function applyOrderType(v) {
    const pd = document.getElementById('payDistWrap');
    const pm = document.getElementById('payMethodWrap');
    const dn = document.getElementById('debtNote');
    const sn = document.getElementById('suspendNote');
    const otHidden = document.getElementById('orderTypeHidden');

    if (pd) pd.style.display = 'block';
    if (pm) pm.style.display = 'block';
    if (dn) dn.style.display = 'none';
    if (sn) sn.style.display = 'none';
    if (otHidden) otHidden.value = v;

    if (v === 'Debt')      { if (pd) pd.style.display = 'none'; if (pm) pm.style.display = 'none'; if (dn) dn.style.display = ''; }
    if (v === 'Suspended') { if (pd) pd.style.display = 'none'; if (pm) pm.style.display = 'none'; if (sn) sn.style.display = ''; }
    if (v === 'Return')    { if (pd) pd.style.display = 'none'; }
}

document.getElementById('orderType').addEventListener('change', function() {
    const v = this.value;
    applyOrderType(v);

    fetch('<?php echo e(url('setOrderType')); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ orderType: v })
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const ot = document.getElementById('orderType');
    if (ot) applyOrderType(ot.value);
});

// ════════════════════════════════════════════
// PAY DISTRIBUTION
// ════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    const rawTotal = document.getElementById('grandTotalEl')?.textContent?.replace(/,/g, '');
    const total    = parseFloat(rawTotal) || 0;
    const paid     = document.getElementById('paidInput');
    const credit   = document.getElementById('creditInput');
    const bank     = document.getElementById('bankInput');
    if (paid) paid.value     = total;
    if (credit) credit.value = 0;
    if (bank) bank.value     = 0;
});

// ════════════════════════════════════════════
// CART UPDATE
// ════════════════════════════════════════════
function updateCartItem(orderId, productId, field, value) {
    const fd = new FormData();
    fd.append('orderId', orderId);
    fd.append('pId', productId);
    fd.append('field', field);
    fd.append('value', value);

    fetch('<?php echo e(url("updateCartItem")); ?>', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
        body: fd
    })
    .then(r => r.json())
    .then(d => { if (d.success) location.reload(); else showToast(d.error || <?php echo json_encode(__('messages.error_updating_item'), 15, 512) ?>, 'error'); })
    .catch(e => showToast(<?php echo json_encode(__('messages.network_error'), 15, 512) ?> + ': ' + e.message, 'error'));
}

document.addEventListener('DOMContentLoaded', function() {
    const clearBtn = document.getElementById('clearCartBtn');
    const cartBadge = document.getElementById('cartBadge');

    function updateClearBtn() {
        if (!clearBtn || !cartBadge) return;
        const count = parseInt(cartBadge.textContent.trim(), 10) || 0;
        clearBtn.style.display = count > 0 ? '' : 'none';
    }

    updateClearBtn();

    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            if (!confirm('Are you sure you want to clear the entire cart?')) return;
            const orderId = '<?php echo e($orders->order_id ?? ''); ?>';
            if (!orderId) return;

            const fd = new FormData();
            fd.append('orderId', orderId);

            fetch('<?php echo e(url("clearCart")); ?>', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                body: fd
            })
            .then(r => r.text())
            .then(() => location.reload())
            .catch(e => showToast(<?php echo json_encode(__('messages.network_error'), 15, 512) ?> + ': ' + e.message, 'error'));
        });
    }
});

// ════════════════════════════════════════════
// PAST SALES
// ════════════════════════════════════════════
let pendingReturnId = null;

document.getElementById('pastSalesModal').addEventListener('shown.bs.modal', () => {
    loadPastSales('');
    document.getElementById('pastSalesSearch').focus();
});

let pastSalesTimeout = null;

document.getElementById('pastSalesSearch').addEventListener('input', e => {
    clearTimeout(pastSalesTimeout);
    const q = e.target.value.trim();
    pastSalesTimeout = setTimeout(() => loadPastSales(q), 400);
});

async function loadPastSales(q) {
    const el = document.getElementById('pastSalesResults');
    el.innerHTML = '<div class="dd-loading">' + <?php echo json_encode(__('messages.searching'), 15, 512) ?> + '</div>';
    try {
        const url = q ? `<?php echo e(url('searchSales')); ?>?search=${encodeURIComponent(q)}` : `<?php echo e(url('searchSales')); ?>`;
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (!data.sales?.length) {
            el.innerHTML = '<div class="dd-empty">' + <?php echo json_encode(__('messages.no_past_sales_found'), 15, 512) ?> + '</div>';
            return;
        }

        el.innerHTML = data.sales.map(s => {
            const d      = new Date(s.created_at);
            const dt     = d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
            const stBadge = s.status === 'Debt'
                ? `<span class="dd-badge dd-badge-offer"><?php echo app('translator')->get('messages.credit'); ?></span>`
                : `<span class="dd-badge dd-badge-price"><?php echo app('translator')->get('messages.paid'); ?></span>`;
            return `<div class="dropdown-item" onclick="selectPastSale('${s.sales_id}','${escHtml(s.cName || <?php echo json_encode(__('messages.unknown_customer'), 15, 512) ?>)}',${s.totalAmount},${s.totalQuantity},'${dt}')">
                <div class="dropdown-item-name">${escHtml(s.cName || <?php echo json_encode(__('messages.unknown_customer'), 15, 512) ?>)} ${stBadge}</div>
                <div class="dropdown-item-meta">
                    <span><i class="bi bi-calendar3"></i> ${dt}</span>
                    <span><i class="bi bi-box-seam"></i> ${s.totalQuantity} <?php echo app('translator')->get('messages.items'); ?></span>
                    <span class="dd-badge dd-badge-price">${Number(s.totalAmount).toLocaleString()} Tsh</span>
                </div>
            </div>`;
        }).join('');
    } catch (error) {
        console.error('Error loading past sales:', error);
        el.innerHTML = `<div class="dd-empty" style="color:var(--danger);"><?php echo app('translator')->get('messages.error_loading_sales'); ?> ${error.message}</div>`;
    }
}

function selectPastSale(id, name, total, qty, date) {
    pendingReturnId = id;
    const det = document.getElementById('returnSaleDetails');
    det.innerHTML = `
        <div class="customer-row"><span class="customer-key"><?php echo app('translator')->get('messages.customer'); ?></span><span class="customer-value">${name}</span></div>
        <div class="customer-row"><span class="customer-key"><?php echo app('translator')->get('messages.date'); ?></span><span class="customer-value">${date}</span></div>
        <div class="customer-row"><span class="customer-key"><?php echo app('translator')->get('messages.total'); ?></span><span class="customer-value">${Number(total).toLocaleString()} Tsh</span></div>
        <div class="customer-row"><span class="customer-key"><?php echo app('translator')->get('messages.items'); ?></span><span class="customer-value">${qty}</span></div>
    `;
    det.style.display = '';
    new bootstrap.Modal(document.getElementById('confirmReturnModal')).show();
}

document.getElementById('confirmReturnBtn').addEventListener('click', async () => {
    if (!pendingReturnId) return;
    const btn = document.getElementById('confirmReturnBtn');
    btn.disabled = true; btn.innerHTML = '<i class="bi bi-hourglass-split"></i> ' + <?php echo json_encode(__('messages.processing'), 15, 512) ?>;
    try {
        const fd = new FormData(); fd.append('sales_id', pendingReturnId);
        const data = await (await fetch('<?php echo e(url("returnSaleToOrder")); ?>', {
            method:'POST', body:fd, headers:{'X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'}
        })).json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('confirmReturnModal'))?.hide();
            bootstrap.Modal.getInstance(document.getElementById('pastSalesModal'))?.hide();
            showToast(<?php echo json_encode(__('messages.sale_returned_to_orders'), 15, 512) ?>, 'success');
            setTimeout(() => location.reload(), 1200);
        } else { showToast(<?php echo json_encode(__('messages.error'), 15, 512) ?> + ': ' + data.message, 'error'); }
    } catch (e) { showToast(<?php echo json_encode(__('messages.error'), 15, 512) ?> + ': ' + e.message, 'error'); }
    btn.disabled = false; btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + <?php echo json_encode(__('messages.confirm'), 15, 512) ?>;
    pendingReturnId = null;
});

// ════════════════════════════════════════════
// OFFERS
// ════════════════════════════════════════════
let offerCtx = null;

document.querySelector('form[action="payout"]').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    if (btn && btn.dataset.submitted) {
        e.preventDefault();
        return false;
    }
    if (btn) {
        btn.dataset.submitted = 'true';
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
    }
    const items = JSON.parse(sessionStorage.getItem('offeredItems') || '[]');
    document.getElementById('offeredItemsInput').value = JSON.stringify(items);
});

async function checkOffer(productId, qty) {
    try {
        const d = await (await fetch(`<?php echo e(url('checkOffer')); ?>/${productId}/${qty}`)).json();
        if (d.has_offer) { offerCtx = {offer: d.offer, productId}; showOfferModal(d.offer); }
    } catch {}
}

function showOfferModal(o) {
    document.getElementById('offerReqQty').textContent  = o.required_quantity;
    document.getElementById('offerFreeQty').textContent = o.offer_quantity;
    document.getElementById('offerMaxQty').textContent  = o.offer_quantity;
    document.getElementById('offerAcceptQty').value     = o.offer_quantity;
    document.getElementById('offerAcceptQty').max       = o.offer_quantity;
    document.getElementById('offerProdName').textContent = o.offer_product_name;
    new bootstrap.Modal(document.getElementById('offerModal')).show();
}

function acceptOffer() {
    const qty = parseInt(document.getElementById('offerAcceptQty').value) || 0;
    if (qty > 0 && offerCtx) {
        const items = JSON.parse(sessionStorage.getItem('offeredItems') || '[]');
        items.push({ productId: offerCtx.offer.offer_product_id, productName: offerCtx.offer.offer_product_name, quantity: qty, fromProductId: offerCtx.productId });
        sessionStorage.setItem('offeredItems', JSON.stringify(items));
        showToast(`${<?php echo json_encode(__('messages.added'), 15, 512) ?>} ${qty} ${<?php echo json_encode(__('messages.free'), 15, 512) ?>} ${offerCtx.offer.offer_product_name}!`, 'success');
    }
    bootstrap.Modal.getInstance(document.getElementById('offerModal'))?.hide();
    offerCtx = null;
}
function declineOffer() { offerCtx = null; }

// ════════════════════════════════════════════
// Shop Selector
// ════════════════════════════════════════════
function changeShop(shopId) {
    const shopSelect = document.getElementById('shopSelect');
    if (shopSelect) shopSelect.disabled = true;
    
    fetch(`<?php echo e(url('changeShop')); ?>?shop_id=${shopId}`, {
        method: 'GET',
        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' }
    })
    .then(response => {
        if (response.ok) {
            window.location.reload();
        } else {
            showToast(<?php echo json_encode(__('messages.failed_change_shop'), 15, 512) ?>, 'error');
            if (shopSelect) shopSelect.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error changing shop:', error);
        showToast(<?php echo json_encode(__('messages.network_error_changing_shop'), 15, 512) ?>, 'error');
        if (shopSelect) shopSelect.disabled = false;
    });
}

// ════════════════════════════════════════════
// SUSPEND ORDER
// ════════════════════════════════════════════
function suspendOrder() {
    if (!confirm('<?php echo app('translator')->get('messages.confirm_suspend_order'); ?>')) return;
    const form = document.getElementById('payoutForm');
    const fd = new FormData(form);
    fd.set('orderType', 'Suspended');
    
    fetch('<?php echo e(url("suspendOrder")); ?>', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
        body: fd
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            showToast('<?php echo app('translator')->get('messages.order_suspended_success'); ?>', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(d.message || '<?php echo app('translator')->get('messages.error_suspending_order'); ?>', 'error');
        }
    })
    .catch(e => showToast('<?php echo app('translator')->get('messages.network_error'); ?>', 'error'));
}

// ════════════════════════════════════════════
// PRINT INVOICE
// ════════════════════════════════════════════
function printInvoice() {
    window.print();
}

// ════════════════════════════════════════════
// Close dropdowns on outside click
// ════════════════════════════════════════════
document.addEventListener('click', e => {
    if (!productSearch?.contains(e.target) && !productDD?.contains(e.target)) {
        if (productDD) productDD.classList.remove('open');
    }
    if (!custSearch?.contains(e.target) && !custDD?.contains(e.target)) {
        if (custDD) custDD.classList.remove('open');
    }
    if (!sellerSearch?.contains(e.target) && !sellerDD?.contains(e.target)) {
        if (sellerDD) sellerDD.classList.remove('open');
    }
});

// ════════════════════════════════════════════
// Toast
// ════════════════════════════════════════════
function showToast(msg, type = 'success') {
    const stack = document.getElementById('toastStack');
    const el = document.createElement('div');
    el.className = `toast-item toast-${type}`;
    const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
    el.innerHTML = `<i class="bi ${icon} toast-icon"></i><span>${msg}</span>`;
    stack.appendChild(el);
    requestAnimationFrame(() => requestAnimationFrame(() => el.classList.add('show')));
    setTimeout(() => { el.classList.remove('show'); setTimeout(() => el.remove(), 300); }, 3500);
}

// ════════════════════════════════════════════
// Util
// ════════════════════════════════════════════
function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

function removeOfferItem(btn, orderId, productId, quantity) {
    if (!confirm('<?php echo app('translator')->get('messages.remove_free_item_confirm'); ?>')) return;
    const fd = new FormData();
    fd.append('orderId', orderId);
    fd.append('productId', productId);
    fd.append('quantity', quantity);
    
    fetch('<?php echo e(url("removeOfferItem")); ?>', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
        body: fd
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            location.reload();
        } else {
            showToast(d.message || '<?php echo app('translator')->get('messages.error_removing_item'); ?>', 'error');
        }
    })
    .catch(e => showToast('<?php echo app('translator')->get('messages.network_error'); ?>', 'error'));
}

// ── Auto-select seller if logged in ──
document.addEventListener('DOMContentLoaded', function() {
    const currentUser = '<?php echo e(Auth::user()->name ?? ''); ?>';
    if (currentUser && !document.getElementById('sellerBadgeWrap')?.style?.display === 'block') {
        const sellerSearch = document.getElementById('sellerSearch');
        if (sellerSearch && !sellerSearch.value) {
            selectSeller(currentUser, '<?php echo e(Auth::user()->levelStatus ?? ''); ?>');
        }
    }

    // Play success sound
    <?php if(session('success') && session('play_sound')): ?>
        const audio = new Audio('/public/sounds/cash.mp3');
        audio.play().catch(() => {});
    <?php endif; ?>
});
</script>

<?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html><?php /**PATH C:\Users\HomePC\Music\leruma pos\resources\views/newOrder.blade.php ENDPATH**/ ?>