<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.shop_report') }} — {{ is_string($dateParam) && str_contains($dateParam, 'to') ? $dateParam : date('F d, Y', strtotime($dateParam)) }}</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* ── Reset & Variables ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #F7F6F2;
            --surface:   #FFFFFF;
            --border:    #E4E2DA;
            --border-md: #CECCC4;
            --text:      #0B1E3D;
            --muted:     #7A7870;
            --accent:    var(--theme-primary);
            --green:     #1A6B45;
            --green-bg:  #E6F4ED;
            --red:       #B63A2F;
            --red-bg:    #FDECEA;
            --amber:     var(--theme-primary);
            --amber-bg:  #FEF3D7;
            --purple:    #1A3A6B;
            --purple-bg: #EEECFA;
            --shadow:    0 4px 12px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.04);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.08);
            --radius:    12px;
            --radius-sm: 8px;
            --font:      'DM Sans', system-ui, sans-serif;
            --mono:      'DM Mono', monospace;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
            line-height: 1.6;
            font-family: var(--font);
        }

        /* ── Layout ── */
        .layout { display: flex; min-height: 100vh; }
        .sidebar-wrap { flex-shrink: 0; }
        
        .main-content {
            flex: 1;
            min-width: 0;
            padding: 2rem 2.5rem;
            max-width: calc(100vw - 280px);
        }

        @media (max-width: 1024px) {
            .main-content { padding: 1.5rem; max-width: 100%; }
        }
        @media (max-width: 768px) {
            .main-content { padding: 1rem; }
        }

        /* ── Alerts ── */
        .alert {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 1.25rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.5rem;
            font-size: 13px;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }
        .alert-success { 
            background: var(--green-bg); 
            color: var(--green); 
            border-left: 4px solid var(--green);
        }
        .alert-danger { 
            background: var(--red-bg);   
            color: var(--red);   
            border-left: 4px solid var(--red);
        }
        .btn-close-sm {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            color: inherit;
            opacity: 0.5;
            padding: 0 4px;
            transition: var(--transition);
            line-height: 1;
        }
        .btn-close-sm:hover { opacity: 1; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Page Header ── */
        .page-header {
            background: linear-gradient(135deg, #0B1E3D 0%, #1a3a6b 100%);
            color: #fff;
            padding: 1.75rem 2rem;
            border-radius: var(--radius);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .page-header-left { position: relative; z-index: 1; }
        .page-title { 
            font-size: 26px; 
            font-weight: 700; 
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .page-title .badge-date {
            font-size: 13px;
            font-weight: 500;
            background: rgba(255,255,255,0.15);
            padding: 2px 14px;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }
        .page-sub { 
            font-size: 14px; 
            color: rgba(255,255,255,0.7); 
            margin-top: 4px;
            font-weight: 400;
        }
        .header-actions {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        /* ── Date Form ── */
        .date-form {
            display: flex;
            align-items: center;
            background: rgba(255,255,255,0.12);
            border-radius: var(--radius-sm);
            padding: 4px;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .date-form input[type=date] {
            border: none;
            outline: none;
            padding: 7px 12px;
            font-size: 13px;
            background: transparent;
            color: #fff;
            font-family: var(--font);
            min-width: 140px;
        }
        .date-form input[type=date]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }
        .date-form .date-sep {
            color: rgba(255,255,255,0.3);
            padding: 0 4px;
            font-weight: 300;
        }
        .date-form button {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 7px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-family: var(--font);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .date-form button:hover { 
            opacity: 0.85;
            transform: scale(1.02);
        }

        /* ── Icon Buttons ── */
        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.1);
            color: #fff;
            text-decoration: none;
            transition: var(--transition);
            font-family: var(--font);
            backdrop-filter: blur(4px);
        }
        .btn-icon:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.3);
            transform: translateY(-1px);
        }
        .btn-icon i { font-size: 16px; }

        /* ── Metric Cards ── */
        .metrics {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 14px;
            margin-bottom: 2rem;
        }
        @media (max-width: 1200px) { .metrics { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 768px) { .metrics { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 480px) { .metrics { grid-template-columns: 1fr; } }

        .metric {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        .metric:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        .metric::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: var(--radius) var(--radius) 0 0;
        }
        .metric.purple::after { background: var(--purple); }
        .metric.green::after  { background: var(--green); }
        .metric.red::after    { background: var(--red); }
        .metric.amber::after  { background: var(--amber); }
        .metric.blue::after   { background: #1A6B9B; }

        .metric .metric-icon {
            font-size: 20px;
            color: var(--muted);
            opacity: 0.6;
            margin-bottom: 4px;
        }
        .metric-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            margin-bottom: 6px;
        }
        .metric-value {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }
        .metric-sub {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
            font-weight: 400;
        }
        .metric .metric-value { color: var(--text); }
        .metric.purple .metric-value { color: var(--purple); }
        .metric.green .metric-value  { color: var(--green); }
        .metric.red .metric-value    { color: var(--red); }
        .metric.amber .metric-value  { color: var(--amber); }
        .metric.blue .metric-value   { color: #1A6B9B; }

        /* ── Panel ── */
        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            gap: 1rem;
            flex-wrap: wrap;
            background: #FAFAF7;
        }
        .panel-title {
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text);
        }
        .panel-title i {
            color: var(--purple);
            font-size: 20px;
        }
        .panel-title .count-badge {
            background: var(--purple-bg);
            color: var(--purple);
            font-size: 12px;
            padding: 0 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .panel-controls {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        /* ── Search & Sort ── */
        .search-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--border-md);
            border-radius: var(--radius-sm);
            padding: 6px 12px;
            background: var(--surface);
            transition: var(--transition);
        }
        .search-wrap:focus-within {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
        }
        .search-wrap i {
            color: var(--muted);
            font-size: 14px;
        }
        .search-wrap input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 13px;
            color: var(--text);
            width: 180px;
            font-family: var(--font);
        }
        .search-wrap input::placeholder { color: var(--muted); }

        .sort-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--border-md);
            border-radius: var(--radius-sm);
            padding: 6px 12px;
            background: var(--surface);
        }
        .sort-wrap i {
            color: var(--muted);
            font-size: 14px;
        }
        .sort-wrap label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
            white-space: nowrap;
        }
        .sort-wrap select {
            border: none;
            background: transparent;
            outline: none;
            font-size: 13px;
            color: var(--text);
            cursor: pointer;
            min-width: 140px;
            font-family: var(--font);
            padding: 2px 0;
        }
        .sort-wrap select:focus {
            outline: none;
        }

        /* ── Table ── */
        .tbl-scroll {
            overflow-x: auto;
            position: relative;
            padding: 0 4px 4px 4px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13px;
            min-width: 1400px;
        }

        thead {
            position: sticky;
            top: 0;
            z-index: 20;
        }

        thead th {
            background: #F0EFEA;
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 12px 14px;
            text-align: right;
            white-space: nowrap;
            border-bottom: 2px solid var(--border-md);
            position: relative;
            z-index: 20;
            transition: background 0.2s;
            cursor: default;
        }
        thead th i {
            font-size: 13px;
            margin-right: 4px;
        }
        thead th:first-child,
        thead th:nth-child(2) {
            text-align: left;
        }
        thead th:first-child {
            border-radius: 0 0 0 0;
        }
        thead th:last-child {
            border-radius: 0 0 0 0;
        }

        /* Sticky columns */
        thead th:first-child {
            position: sticky;
            left: 0;
            z-index: 30;
            background: #F0EFEA;
        }
        thead th:nth-child(2) {
            position: sticky;
            left: 48px;
            z-index: 30;
            background: #F0EFEA;
            text-align: left;
        }

        tbody td:first-child {
            position: sticky;
            left: 0;
            z-index: 10;
            background: var(--surface);
            border-right: 1px solid var(--border);
        }
        tbody td:nth-child(2) {
            position: sticky;
            left: 48px;
            z-index: 10;
            background: var(--surface);
            border-right: 1px solid var(--border);
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: var(--transition);
            cursor: pointer;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover {
            background: #F9F8F4;
        }
        tbody tr:hover td:first-child,
        tbody tr:hover td:nth-child(2) {
            background: #F9F8F4;
        }
        tbody tr.expanded {
            background: #F3F2EE;
        }
        tbody tr.expanded td:first-child,
        tbody tr.expanded td:nth-child(2) {
            background: #F3F2EE;
        }
        tbody tr.warn-row {
            background: #FFFBF0;
        }
        tbody tr.warn-row td:first-child,
        tbody tr.warn-row td:nth-child(2) {
            background: #FFFBF0;
        }
        tbody tr.warn-row:hover {
            background: #FFF5D6;
        }
        tbody tr.warn-row:hover td:first-child,
        tbody tr.warn-row:hover td:nth-child(2) {
            background: #FFF5D6;
        }

        td {
            padding: 12px 14px;
            text-align: right;
            vertical-align: middle;
            font-size: 13px;
        }
        td:first-child,
        td:nth-child(2) {
            text-align: left;
        }

        /* ── Shop Cell ── */
        .shop-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            flex-shrink: 0;
            background: var(--purple-bg);
            color: var(--purple);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: var(--transition);
        }
        .shop-row:hover .avatar {
            transform: scale(1.05);
        }
        .shop-name {
            font-weight: 600;
            color: var(--text);
        }
        .shop-loc {
            font-size: 11px;
            color: var(--muted);
        }

        /* ── Numeric values ── */
        .num { font-variant-numeric: tabular-nums; font-weight: 500; }
        .num.fw6 { font-weight: 600; }

        /* ── Badges ── */
        .badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .badge-ok   { background: var(--green-bg); color: var(--green); }
        .badge-over { background: var(--amber-bg); color: var(--amber); }
        .badge-bad  { background: var(--red-bg);   color: var(--red); }

        .txt-green { color: var(--green); font-weight: 600; }
        .txt-red   { color: var(--red); font-weight: 600; }
        .txt-amber { color: var(--amber); font-weight: 600; }
        .txt-muted { color: var(--muted); }

        .ico-ok   { color: var(--green); font-size: 18px; }
        .ico-warn { color: var(--amber); font-size: 18px; cursor: help; }

        /* ── Detail Row ── */
        .detail-row { display: none; }
        .detail-row.open { display: table-row; }
        .detail-row td {
            background: #F3F2EE;
            padding: 0;
        }
        .detail-row.warn-row td {
            background: #FFFBF0;
        }

        .detail-inner {
            padding: 1.5rem 2rem;
            border-left: 4px solid var(--purple);
            margin: 0 4px;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 1.25rem;
        }
        .detail-item label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            display: block;
            margin-bottom: 4px;
            font-weight: 600;
        }
        .detail-item span {
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
        }
        .detail-actions {
            margin-top: 1.25rem;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
        }

        /* ── Footer ── */
        tfoot td {
            background: #F0EFEA;
            border-top: 2px solid var(--border-md);
            font-size: 12px;
            font-weight: 700;
            padding: 12px 14px;
            text-align: right;
            color: var(--text);
        }
        tfoot td:first-child,
        tfoot td:nth-child(2) {
            text-align: left;
            color: var(--text);
        }
        tfoot td:first-child {
            position: sticky;
            left: 0;
            z-index: 10;
            background: #F0EFEA;
        }
        tfoot td:nth-child(2) {
            position: sticky;
            left: 48px;
            z-index: 10;
            background: #F0EFEA;
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--muted);
        }
        .empty-state i {
            font-size: 48px;
            color: var(--border-md);
            margin-bottom: 16px;
            display: block;
        }
        .empty-state h4 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text);
        }
        .empty-state p {
            font-size: 14px;
        }

        /* ── Modal ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.25s ease;
        }
        .modal-overlay.open { display: flex; }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-box {
            background: var(--surface);
            border-radius: var(--radius);
            width: 100%;
            max-width: 500px;
            margin: 1rem;
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
            animation: slideUp 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .modal-top {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-top h2 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .modal-top h2 i {
            color: var(--amber);
        }
        .modal-body { padding: 1.5rem; }
        .modal-foot {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem 1.5rem;
            background: var(--bg);
            border-radius: var(--radius-sm);
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }
        .info-item label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--muted);
            display: block;
            margin-bottom: 3px;
            font-weight: 600;
        }
        .info-item span {
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
        }
        .info-item.big span {
            font-size: 22px;
            color: var(--green);
        }
        .info-divider {
            grid-column: 1/-1;
            border: none;
            border-top: 1px solid var(--border);
            margin: 4px 0;
        }

        .field-group { margin-bottom: 1rem; }
        .field-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text);
        }
        .input-row {
            display: flex;
            border: 2px solid var(--border-md);
            border-radius: var(--radius-sm);
            overflow: hidden;
            transition: var(--transition);
        }
        .input-row:focus-within {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
        }
        .input-prefix {
            background: var(--bg);
            padding: 10px 14px;
            font-size: 14px;
            font-weight: 600;
            color: var(--muted);
            border-right: 2px solid var(--border-md);
            white-space: nowrap;
            font-family: var(--mono);
        }
        .input-row input[type=number] {
            flex: 1;
            border: none;
            outline: none;
            padding: 10px 14px;
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
            background: transparent;
            font-family: var(--font);
            min-width: 0;
        }
        .input-row input[type=number]::-webkit-inner-spin-button {
            opacity: 1;
        }

        .quick-amounts {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
        }
        .quick-btn {
            background: var(--bg);
            border: 1px solid var(--border-md);
            border-radius: var(--radius-sm);
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            color: var(--text);
            transition: var(--transition);
            font-family: var(--font);
        }
        .quick-btn:hover {
            background: var(--accent);
            color: #fff;
            border-color: var(--accent);
            transform: translateY(-1px);
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-family: var(--font);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary:hover {
            opacity: 0.85;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }
        .btn-secondary {
            background: transparent;
            color: var(--muted);
            border: 2px solid var(--border-md);
            padding: 10px 24px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            font-family: var(--font);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-secondary:hover {
            background: var(--bg);
            color: var(--text);
        }
        .btn-danger {
            background: var(--red);
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-family: var(--font);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-danger:hover {
            opacity: 0.85;
            transform: translateY(-1px);
        }

        /* ── Tooltip / Wizard ── */
        .wizard-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 10000;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.3);
            backdrop-filter: blur(2px);
        }
        .wizard-overlay.open { display: flex; }

        .wizard-tooltip {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            width: 380px;
            max-width: calc(100vw - 40px);
            z-index: 10001;
            animation: slideUp 0.3s ease;
            overflow: hidden;
        }
        .wizard-tooltip-header {
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, var(--purple) 0%, #1a3a6b 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .wizard-tooltip-title {
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .wizard-tooltip-step {
            background: rgba(255,255,255,0.2);
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 500;
        }
        .wizard-tooltip-close {
            background: none;
            border: none;
            color: white;
            font-size: 22px;
            cursor: pointer;
            opacity: 0.7;
            transition: var(--transition);
            line-height: 1;
            padding: 0 4px;
        }
        .wizard-tooltip-close:hover { opacity: 1; }
        .wizard-tooltip-body { padding: 1.5rem; }
        .wizard-tooltip-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            margin-bottom: 6px;
            font-weight: 600;
        }
        .wizard-tooltip-fullname {
            font-size: 18px;
            font-weight: 700;
            color: var(--purple);
            margin-bottom: 10px;
        }
        .wizard-tooltip-explanation {
            font-size: 14px;
            line-height: 1.6;
            color: var(--text);
            margin-bottom: 1rem;
        }
        .wizard-tooltip-calculation {
            background: var(--bg);
            border-left: 3px solid var(--green);
            padding: 0.75rem 1rem;
            border-radius: var(--radius-sm);
            font-size: 12px;
            color: var(--text);
            margin-bottom: 0.75rem;
        }
        .wizard-tooltip-calculation .calc-label {
            font-size: 9px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 3px;
            font-weight: 600;
            letter-spacing: 0.06em;
        }
        .wizard-tooltip-balance {
            background: var(--green-bg);
            border: 1px solid #B2DFC5;
            border-radius: var(--radius-sm);
            padding: 0.75rem 1rem;
            font-size: 13px;
            color: var(--text);
        }
        .wizard-tooltip-balance strong {
            color: var(--green);
        }
        .wizard-tooltip-footer {
            padding: 0.75rem 1.5rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-top: 1px solid var(--border);
        }
        .wizard-tooltip-progress {
            font-size: 12px;
            color: var(--muted);
            font-weight: 500;
        }
        .wizard-tooltip-nav {
            display: flex;
            gap: 6px;
        }
        .wizard-tooltip-btn {
            padding: 6px 14px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border-md);
            background: var(--surface);
            color: var(--text);
            transition: var(--transition);
            font-family: var(--font);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .wizard-tooltip-btn:hover {
            background: var(--bg);
            border-color: var(--border);
        }
        .wizard-tooltip-btn-primary {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
        }
        .wizard-tooltip-btn-primary:hover {
            background: #e58e0b;
            border-color: #e58e0b;
        }
        .wizard-tooltip-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        .wizard-tooltip-btn-skip {
            background: transparent;
            border: none;
            color: var(--muted);
            font-size: 12px;
            cursor: pointer;
            text-decoration: underline;
            font-family: var(--font);
            padding: 6px 10px;
        }
        .wizard-tooltip-btn-skip:hover {
            color: var(--text);
        }
        .wizard-close-hint {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(11, 30, 61, 0.9);
            color: white;
            padding: 10px 20px;
            border-radius: 24px;
            font-size: 13px;
            font-weight: 500;
            z-index: 10002;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .wizard-close-hint.show { opacity: 1; }

        /* ── Responsive tweaks ── */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: stretch;
                padding: 1.25rem;
            }
            .header-actions {
                flex-direction: column;
                width: 100%;
            }
            .date-form {
                width: 100%;
                flex-wrap: wrap;
            }
            .date-form input[type=date] {
                flex: 1;
                min-width: 100px;
                padding: 6px 10px;
            }
            .date-form button {
                flex: 0 0 auto;
            }
            .btn-icon {
                justify-content: center;
                flex: 1;
            }
            .panel-header {
                flex-direction: column;
                align-items: stretch;
            }
            .panel-controls {
                flex-direction: column;
                width: 100%;
            }
            .search-wrap, .sort-wrap {
                width: 100%;
            }
            .search-wrap input { width: 100%; }
            .sort-wrap select { min-width: 100px; }
            .detail-inner { padding: 1rem; }
            .detail-grid { grid-template-columns: 1fr 1fr; }
            .info-grid { grid-template-columns: 1fr; }
            .wizard-tooltip { width: 95%; }
        }
        @media (max-width: 480px) {
            .detail-grid { grid-template-columns: 1fr; }
            .modal-box { margin: 0.5rem; }
            .metric { padding: 1rem; }
            .metric-value { font-size: 22px; }
        }

        /* ── Scrollbar styling ── */
        .tbl-scroll::-webkit-scrollbar {
            height: 8px;
        }
        .tbl-scroll::-webkit-scrollbar-track {
            background: var(--bg);
            border-radius: 4px;
        }
        .tbl-scroll::-webkit-scrollbar-thumb {
            background: var(--border-md);
            border-radius: 4px;
        }
        .tbl-scroll::-webkit-scrollbar-thumb:hover {
            background: var(--muted);
        }

        /* ── Sticky shadow indicator ── */
        .tbl-scroll.scrolling-right thead th:first-child::after,
        .tbl-scroll.scrolling-right thead th:nth-child(2)::after,
        .tbl-scroll.scrolling-right tbody td:first-child::after,
        .tbl-scroll.scrolling-right tbody td:nth-child(2)::after {
            content: '';
            position: absolute;
            top: 0;
            right: -6px;
            bottom: 0;
            width: 6px;
            background: linear-gradient(to right, rgba(0,0,0,0.06), transparent);
            pointer-events: none;
        }
    </style>
</head>
<body>
@include('sidenav')

<main class="main-content">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">
            <span><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</span>
            <button class="btn-close-sm" onclick="this.closest('.alert').remove()">×</button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            <span><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</span>
            <button class="btn-close-sm" onclick="this.closest('.alert').remove()">×</button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-left">
            <div class="page-title">
                <i class="bi bi-bar-chart-fill"></i> {{ __('messages.shop_report') }}
                <span class="badge-date">
                    @if(is_string($dateParam) && str_contains($dateParam, 'to'))
                        {{ $dateParam }}
                    @else
                        {{ date('M d, Y', strtotime($dateParam)) }}
                    @endif
                </span>
            </div>
            <div class="page-sub">
                <i class="bi bi-info-circle"></i> {{ __('messages.overview_of_all_shop_performance') }}
            </div>
        </div>
        <div class="header-actions">
            <form method="GET" action="shopReport" id="dateFilterForm" style="display: contents;">
                <div class="date-form">
                    <input type="date" name="date_from" id="dateFromInput" value="{{ $dateFrom ?? '' }}" placeholder="{{ __('messages.from') }}">
                    <span class="date-sep"><i class="bi bi-arrow-right"></i></span>
                    <input type="date" name="date_to" id="dateToInput" value="{{ $dateTo ?? '' }}" placeholder="{{ __('messages.to') }}">
                    <button type="submit"><i class="bi bi-funnel"></i> {{ __('messages.filter') }}</button>
                </div>
            </form>
            <button class="btn-icon" onclick="exportToExcel()">
                <i class="bi bi-file-earmark-excel"></i> {{ __('messages.excel') }}
            </button>
            <button class="btn-icon" onclick="exportToPDF()">
                <i class="bi bi-file-earmark-pdf"></i> {{ __('messages.pdf') }}
            </button>
        </div>
    </div>

    {{-- Metric Cards --}}
    <div class="metrics">
        <div class="metric purple">
            <div class="metric-icon"><i class="bi bi-shop"></i></div>
            <div class="metric-label">{{ __('messages.total_shops') }}</div>
            <div class="metric-value">{{ $activeShopsCount }}</div>
            <div class="metric-sub">{{ $shopsWithSalesCount }} {{ __('messages.with_sales_today') }}</div>
        </div>
        <div class="metric red">
            <div class="metric-icon"><i class="bi bi-cash-stack"></i></div>
            <div class="metric-label">{{ __('messages.total_sales') }}</div>
            <div class="metric-value">{{ number_format($totals->total_sales) }}</div>
            <div class="metric-sub"><i class="bi bi-cash"></i> {{ number_format($totals->cash_sales) }} · <i class="bi bi-credit-card"></i> {{ number_format($totals->credit_sales) }}</div>
        </div>
        <div class="metric green">
            <div class="metric-icon"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="metric-label">{{ __('messages.total_profit') }}</div>
            <div class="metric-value">{{ number_format($totals->profit) }}</div>
            <div class="metric-sub">{{ __('messages.after_expenses_and_debt') }}</div>
        </div>
        <div class="metric amber">
            <div class="metric-icon"><i class="bi bi-bullseye"></i></div>
            <div class="metric-label">{{ __('messages.cash_expected') }}</div>
            <div class="metric-value">{{ number_format($totals->cash_amount) }}</div>
            <div class="metric-sub">{{ __('messages.submitted') }} {{ number_format($totals->cash_submitted) }}</div>
        </div>
        <div class="metric blue">
            <div class="metric-icon"><i class="bi bi-check-circle"></i></div>
            <div class="metric-label">{{ __('messages.cash_submitted') }}</div>
            <div class="metric-value">{{ number_format($totals->cash_submitted) }}</div>
            <div class="metric-sub">{{ __('messages.cash_expected') }} {{ number_format($totals->cash_amount) }}</div>
        </div>
        <div class="metric red">
            <div class="metric-icon"><i class="bi bi-exclamation-diamond"></i></div>
            <div class="metric-label">{{ __('messages.unbalanced_shops') }}</div>
            <div class="metric-value">{{ $unbalancedShopsCount }}</div>
            <div class="metric-sub">{{ $unbalancedReasonsText ?: __('messages.all_balanced') }}</div>
        </div>
    </div>

    {{-- Main Table Panel --}}
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">
                <i class="bi bi-table"></i> {{ __('messages.all_shops') }}
                <span class="count-badge">{{ $shopReports->count() }} {{ __('messages.shops') }}</span>
            </div>
            <div class="panel-controls">
                <div class="search-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" id="shopSearch" placeholder="{{ __('messages.search_shops') }}" oninput="filterShops()">
                </div>
                <div class="sort-wrap">
                    <i class="bi bi-arrow-up-down"></i>
                    <label>{{ __('messages.sort_by') }}</label>
                    <select id="sortSelect" onchange="sortShops()">
                        <option value="name_asc">{{ __('messages.name_az') }}</option>
                        <option value="name_desc">{{ __('messages.name_za') }}</option>
                        <option value="balanced">{{ __('messages.balanced') }}</option>
                        <option value="unbalanced">{{ __('messages.unbalanced') }}</option>
                        <option value="status_settled">{{ __('messages.status_settled') }}</option>
                        <option value="status_underpaid">{{ __('messages.status_underpaid') }}</option>
                        <option value="status_overpaid">{{ __('messages.status_overpaid') }}</option>
                        <option value="sales_high">{{ __('messages.sales_high_low') }}</option>
                        <option value="sales_low">{{ __('messages.sales_low_high') }}</option>
                        <option value="profit_high">{{ __('messages.profit_high_low') }}</option>
                        <option value="profit_low">{{ __('messages.profit_low_high') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="tbl-scroll" id="tableScroll">
            @if($shopReports->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h4>{{ __('messages.no_shops_found') }}</h4>
                    <p>{{ __('messages.no_active_shops_found_for') }} {{ date('F d, Y', strtotime($dateParam)) }}.</p>
                </div>
            @else
            <table id="shopTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages.shop_name') }}</th>
                        <th title="{{ __('messages.cash_sale') }}">{{ __('messages.c_sale') }}</th>
                        <th title="{{ __('messages.credit_sale') }}">{{ __('messages.cr_sale') }}</th>
                        <th title="{{ __('messages.bank_sale_colon') }}">{{ __('messages.b_s') }}</th>
                        <th title="{{ __('messages.total_sales') }}">{{ __('messages.t_sales') }}</th>
                        <th title="{{ __('messages.total_return') }}">{{ __('messages.t_ret') }}</th>
                        <th title="{{ __('messages.offered_items_colon') }}">{{ __('messages.offered') }}</th>
                        <th title="{{ __('messages.discount_colon') }}">{{ __('messages.dsc') }}</th>
                        <th title="{{ __('messages.expenses_colon') }}">{{ __('messages.exp') }}</th>
                        <th title="{{ __('messages.profit_loss_colon') }}">{{ __('messages.p_l') }}</th>
                        <th title="{{ __('messages.paid_invoices_colon') }}">{{ __('messages.p_inv') }}</th>
                        <th title="{{ __('messages.cash_receivings_colon') }}">{{ __('messages.ca_r') }}</th>
                        <th title="{{ __('messages.credit_receivings_colon') }}">{{ __('messages.cr_r') }}</th>
                        <th title="{{ __('messages.chip_deposit_colon') }}"> {{ __('messages.chip_d') }}</th>
                        <th title="{{ __('messages.chip_used_colon') }}">{{ __('messages.chip_u') }}</th>
                        <th title="{{ __('messages.cash_amount_expected_colon') }}">{{ __('messages.c_a') }}</th>
                        <th title="{{ __('messages.cash_submit_colon') }}">{{ __('messages.c_s') }}</th>
                        <th title="{{ __('messages.bank_deposit_colon') }}">{{ __('messages.b_d') }}</th>
                        
                        <th title="{{ __('messages.bank_difference_colon') }}">{{ __('messages.b_diff') }}</th>
                        <th title="{{ __('messages.difference_colon') }}">{{ __('messages.diff') }}</th>
                        <th title="{{ __('messages.main_store_difference') }}">{{ __('messages.m_s_diff') }}</th>
                        <th title="{{ __('messages.cost_worth_inventory_colon') }}">{{ __('messages.inventory') }}</th>
                        <th><i class="bi bi-info-circle"></i> {{ __('messages.status') }}</th>
                        <th>{{ __('messages.balance') }}</th>
                    </tr>
                </thead>
                <tbody id="shopTableBody">
                    @foreach ($shopReports as $index => $shop)
                        @php
                            $cashSale   = $shop->cash_sales;
                            $return     = $shop->total_return;
                            $creditSale = $shop->credit_sales;
                            $totalSale  = $shop->total_sales;
                            $cashAmount = $shop->cash_amount;
                            $cashSubmit = $shop->cash_submitted;

                            $mainDiff = ($shop->main_store - $shop->credit_receivings - $shop->total_return) < 1 && ($shop->main_store - $shop->credit_receivings - $shop->total_return) > -1;
                            $bankDiff = $shop->bank_diff < 1 && $shop->bank_diff > -1;
                            $diff = $cashAmount - $cashSubmit;

                            $profitVal = $totalSale + ($return + $return)
                                - ($shop->expenses ?? 0)
                                - ($shop->cash_receivings ?? 0)
                                - ($shop->credit_receivings ?? 0);

                            $diffClass = abs($diff) < 0.01 ? 'txt-muted' : ($diff > 0 ? 'txt-red' : 'txt-green');

                            $salesBalanced = abs(($cashSale + $creditSale) - $totalSale) < 0.01;
                            $cashBalanced  = abs($diff) < 0.01;
                            $inventory = abs($shop->cost_worth) < 0.01 && abs($shop->cost_worth) > -0.01;
                            $isBalanced    = $salesBalanced && $cashBalanced && $bankDiff && $mainDiff;

                            $isDiffZero = abs($diff) < 0.1 && abs($shop->bank_diff) < 0.1 && abs($shop->main_store - $shop->credit_receivings - $shop->total_return) < 0.1 && $inventory;
                            $statusBadge = $isDiffZero ? 'badge-ok' : ($diff > 0 ? 'badge-bad' : 'badge-over');
                            $statusText  = $isDiffZero ? __('messages.settled') : ($diff > 0 ? __('messages.underpaid') : __('messages.overpaid'));

                            $balanceIssues = [];
                            if (!$salesBalanced) {
                                $balanceIssues[] = __('messages.balance_issue_sales_mismatch', ['cash' => number_format($cashSale), 'credit' => number_format($creditSale), 'total' => number_format($totalSale)]);
                            }
                            if (!$cashBalanced) {
                                $balanceIssues[] = __('messages.balance_issue_cash_expected', ['expected' => number_format($cashAmount), 'got' => number_format($cashSubmit)]);
                            }
                            $initials = strtoupper(substr($shop->shop_name, 0, 1));
                            $parts = explode(' ', trim($shop->shop_name));
                            if (count($parts) > 1) $initials .= strtoupper(substr($parts[1], 0, 1));
                        @endphp

                        <tr class="shop-row {{ !$isBalanced ? 'warn-row' : '' }}"
                            data-name="{{ strtolower($shop->shop_name) }}"
                            data-status="{{ $isDiffZero ? 'settled' : ($diff > 0 ? 'underpaid' : 'overpaid') }}"
                            onclick="toggleDetail({{ $index }})">
                            <td class="txt-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="shop-cell">
                                    <div class="avatar">{{ $initials }}</div>
                                    <div>
                                        <div class="shop-name">{{ $shop->shop_name }}</div>
                                        <div class="shop-loc"><i class="bi bi-geo-alt"></i> {{ $shop->location }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="num">{{ number_format($cashSale) }}</td>
                            <td class="num">{{ number_format($creditSale) }}</td>
                            <td class="num">{{ number_format($shop->sales_bank ?? 0) }}</td>
                            <td class="num fw6">{{ number_format($totalSale) }}</td>
                            <td class="num txt-red">{{ number_format($shop->total_return ?? 0) }}</td>
                            <td class="num txt-amber fw6">{{ number_format($shop->total_offer ?? 0) }}</td>
                            <td class="num txt-muted">{{ number_format($shop->discount) }}</td>
                            <td class="num txt-muted">{{ number_format($shop->expenses) }}</td>
                            <td class="num {{ $profitVal >= 0 ? 'txt-green' : 'txt-red' }}">{{ number_format($profitVal) }}</td>
                            <td class="num">{{ number_format($shop->paid_invoices) }}</td>
                            <td class="num">{{ number_format($shop->cash_receivings) }}</td>
                            <td class="num">{{ number_format($shop->credit_receivings) }}</td>
                            <td class="num">{{ number_format($shop->totalChip) }}</td>
                            <td class="num">{{ number_format($shop->chip_used) }}</td>
                            <td class="num">{{ number_format($cashAmount) }}</td>
                            <td class="num">{{ number_format($cashSubmit) }}</td>
                            <td class="num">{{ number_format($shop->total_bank) }}</td>
                            
                            <td class="num">{{ number_format($shop->bank_diff) }}</td>
                            <td class="num fw6 {{ $diffClass }}">{{ number_format(abs($diff)) }}</td>
                            <td class="num">{{ number_format($shop->main_store - $shop->credit_receivings - $shop->total_return ) }}</td>
                            <td class="num">{{ number_format($shop->cost_worth ?? 0) }}</td>
                            <td><span class="badge {{ $statusBadge }}">{{ $statusText }}</span></td>
                            <td>
                                @if(!$isBalanced)
                                    <span class="ico-warn" title="{{ implode(' | ', $balanceIssues) }}"><i class="bi bi-exclamation-triangle-fill"></i></span>
                                @else
                                    <span class="ico-ok"><i class="bi bi-check-circle-fill"></i></span>
                                @endif
                            </td>
                        </tr>

                        <tr id="detail-{{ $index }}" class="detail-row {{ !$isBalanced ? 'warn-row' : '' }}">
                            <td colspan="25">
                                <div class="detail-inner">
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <label><i class="bi bi-shop"></i> {{ __('messages.shop_name') }}</label>
                                            <span>{{ $shop->shop_name }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label><i class="bi bi-geo-alt"></i> {{ __('messages.location') }}</label>
                                            <span>{{ $shop->location }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label><i class="bi bi-bullseye"></i> {{ __('messages.expected_cash') }}</label>
                                            <span>{{ number_format($shop->expected_cash) }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label><i class="bi bi-check-circle"></i> {{ __('messages.cash_submit') }}</label>
                                            <span>{{ number_format($shop->cash_submitted) }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label><i class="bi bi-arrow-left-right"></i> {{ __('messages.difference') }}</label>
                                            <span class="{{ $diffClass }}">{{ number_format($diff) }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label><i class="bi bi-currency-dollar"></i> {{ __('messages.total_debt') }}</label>
                                            <span>{{ number_format($shop->debt) }}</span>
                                        </div>
                                        <div class="detail-item">
                                            <label><i class="bi bi-scale"></i> {{ __('messages.balance_status') }}</label>
                                            <span class="{{ $isBalanced ? 'txt-green' : 'txt-red' }}">
                                                {{ $isBalanced ? __('messages.balanced') : __('messages.unbalanced') }}
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <label><i class="bi bi-arrow-left-right"></i> {{ __('messages.transactions') }}</label>
                                            <span>{{ number_format($shop->total_transactions) }}</span>
                                        </div>
                                    </div>

                                    @if(!$isBalanced && count($balanceIssues) > 0)
                                        <div style="margin-top:1rem; padding: 0.75rem 1rem; background: var(--red-bg); border: 1px solid #F5C6C2; border-radius: var(--radius-sm);">
                                            <div style="font-size: 12px; font-weight: 700; color: var(--red); margin-bottom: 6px;"><i class="bi bi-exclamation-triangle-fill"></i> {{ __('messages.balance_issues_detected') }}</div>
                                            <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: var(--red);">
                                                @foreach($balanceIssues as $issue)
                                                    <li style="margin-bottom: 4px;">{{ $issue }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="detail-actions">
                                        <button class="btn-primary" style="font-size:13px; padding:8px 20px;"
                                            onclick="event.stopPropagation(); openCashModal({{ $index }})">
                                            <i class="bi bi-cash-coin"></i> {{ __('messages.submit_cash') }}
                                        </button>
                                        @if($shop->cash_submitted > 0)
                                            <form method="POST" action="{{ url('cashDelete') }}" style="display: inline;" onsubmit="return confirm('{{ __('messages.delete_cash_submission_for') }} {{ $shop->shop_name }}?');">
                                                @csrf
                                                <input type="hidden" name="shop_id" value="{{ $shop->shop_id }}">
                                                <input type="hidden" name="date" value="{{ $dateParam }}">
                                                <button type="submit" class="btn-danger"><i class="bi bi-trash3"></i> {{ __('messages.delete') }}</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="2"><i class="bi bi-calculator"></i> {{ __('messages.totals') }}</td>
                        <td class="num">{{ number_format($totals->cash_sales) }}</td>
                        <td class="num">{{ number_format($totals->credit_sales) }}</td>
                        <td class="num fw6">{{ number_format($totals->total_sales - $totals->total_return) }}</td>
                        <td class="num">{{ number_format($totals->total_return ?? 0) }}</td>
                        <td class="num">{{ number_format($totals->offer ?? 0) }}</td>
                        <td class="num">{{ number_format($totals->discount) }}</td>
                        <td class="num">{{ number_format($totals->expenses) }}</td>
                        <td class="num {{ $totals->profit >= 0 ? 'txt-green' : 'txt-red' }}">{{ number_format($totals->profit) }}</td>
                        <td class="num">{{ number_format($totals->paid_invoices) }}</td>
                        <td class="num">{{ number_format($totals->cash_receivings) }}</td>
                        <td class="num">{{ number_format($totals->credit_receivings) }}</td>
                        <td class="num">{{ number_format($totals->totalChip) }}</td>
                        <td class="num">{{ number_format($totals->chip_used) }}</td>
                        <td class="num">{{ number_format($totals->cash_amount) }}</td>
                        <td class="num">{{ number_format($totals->cash_submitted) }}</td>
                        <td class="num">{{ number_format($totals->total_bank) }}</td>
                        <td class="num">{{ number_format($totals->sales_bank ?? 0) }}</td>
                        @php
                            $totalDiff = $totals->cash_amount - $totals->cash_submitted;
                            $totalDiffClass = abs($totalDiff) < 0.01 ? '' : ($totalDiff > 0 ? 'txt-red' : 'txt-green');
                        @endphp
                        <td class="num {{ $totalDiffClass }} fw6">{{ number_format(abs($totalDiff)) }}</td>
                        <td class="num">{{ number_format($totals->main_store) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            @endif
        </div>
    </div>

</main>

{{-- Column Tooltip Wizard --}}
<div class="wizard-overlay" id="wizardOverlay">
    <div class="wizard-tooltip" id="wizardTooltip">
        <div class="wizard-tooltip-header">
            <div class="wizard-tooltip-title">
                <span class="wizard-tooltip-step" id="tooltipStep">1/22</span>
                <span id="tooltipTitle">C.Sale</span>
            </div>
            <button class="wizard-tooltip-close" onclick="closeWizard()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="wizard-tooltip-body">
            <div class="wizard-tooltip-label"><i class="bi bi-info-circle"></i> {{ __('messages.column_explanation') }}</div>
            <div class="wizard-tooltip-fullname" id="tooltipFullName">{{ __('messages.cash_sales_wizard') }}</div>
            <div class="wizard-tooltip-explanation" id="tooltipExplanation">
                {{ __('messages.total_cash_sales_made_by_shop_today') }}
            </div>
            <div class="wizard-tooltip-calculation">
                <div class="calc-label"><i class="bi bi-calculator"></i> {{ __('messages.calculation') }}</div>
                <div id="tooltipCalculation">{{ __('messages.sum_of_all_cash_sales') }}</div>
            </div>
            <div class="wizard-tooltip-balance">
                <strong><i class="bi bi-scale"></i> {{ __('messages.balance_colon') }}</strong> <span id="tooltipBalance">{{ __('messages.part_of_total_sales') }}</span>
            </div>
        </div>
        <div class="wizard-tooltip-footer">
            <div class="wizard-tooltip-progress" id="tooltipProgress">{{ __('messages.wizard_step_progress', ['step' => 1, 'total' => 22]) }}</div>
            <div class="wizard-tooltip-nav">
                <button class="wizard-tooltip-btn-skip" onclick="closeWizard()">{{ __('messages.skip') }}</button>
                <button class="wizard-tooltip-btn" id="prevBtn" onclick="prevStep()"><i class="bi bi-chevron-left"></i> {{ __('messages.previous') }}</button>
                <button class="wizard-tooltip-btn wizard-tooltip-btn-primary" id="nextBtn" onclick="nextStep()">{{ __('messages.next') }} <i class="bi bi-chevron-right"></i></button>
            </div>
        </div>
    </div>
    <div class="wizard-close-hint" id="closeHint"><i class="bi bi-escape"></i> {{ __('messages.press_esc_or_click_outside_to_close') }}</div>
</div>

{{-- Cash Submit Modal --}}
<div class="modal-overlay" id="cashModal">
    <div class="modal-box">
        <div class="modal-top">
            <h2><i class="bi bi-cash-coin"></i> {{ __('messages.submit_cash') }}</h2>
            <button class="btn-close-sm" onclick="closeModal()">×</button>
        </div>
        <form id="cashForm" method="POST" action="{{ url('cashSubmit') }}">
            @csrf
            <div class="modal-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label><i class="bi bi-shop"></i> {{ __('messages.shop') }}</label>
                        <span id="m-shop">—</span>
                    </div>
                    <div class="info-item">
                        <label><i class="bi bi-calendar3"></i> {{ __('messages.date') }}</label>
                        @if(canUser("cash_submit_date"))
                            <input type="date" name="date" id="m-date" style="width:100%; font-weight:600; font-size:14px; color:var(--text); background:var(--surface); border:1px solid var(--border-md); border-radius:var(--radius-sm); padding:4px 8px;">
                        @else
                            <input type="hidden" name="date" id="m-date" value="{{ date('Y-m-d') }}">
                            <span style="font-weight:600;">{{ date('Y-m-d') }}</span>
                        @endif
                    </div>
                    <hr class="info-divider">
                    <div class="info-item">
                        <label><i class="bi bi-bar-chart"></i> {{ __('messages.total_sales') }}</label>
                        <span id="m-sales">0</span>
                    </div>
                    <div class="info-item">
                        <label><i class="bi bi-cash"></i> {{ __('messages.cash_sales') }}</label>
                        <span id="m-cash-sales">0</span>
                    </div>
                    <div class="info-item">
                        <label><i class="bi bi-wallet2"></i> {{ __('messages.expenses') }}</label>
                        <span id="m-exp">0</span>
                    </div>
                    <div class="info-item">
                        <label><i class="bi bi-tag"></i> {{ __('messages.discount') }}</label>
                        <span id="m-dsc">0</span>
                    </div>
                    <hr class="info-divider">
                    <div class="info-item big">
                        <label><i class="bi bi-bullseye"></i> {{ __('messages.expected_cash') }}</label>
                        <span id="m-expected">0</span>
                    </div>
                    <div class="info-item">
                        <label><i class="bi bi-coin"></i> {{ __('messages.remaining') }}</label>
                        <span id="m-remaining" style="color:var(--red);font-weight:700;">0</span>
                    </div>
                </div>

                <div class="field-group">
                    <label>{{ __('messages.modal_cash_amount_to_submit') }}</label>
                    <div class="input-row">
                        <span class="input-prefix">TSh</span>
                        <input type="number" id="cashAmount" name="submitted_cash" step="0.01" min="0.01" required placeholder="0.00">
                    </div>
                    <div class="quick-amounts">
                        <button type="button" class="quick-btn" onclick="setAmt(5000)">5,000</button>
                        <button type="button" class="quick-btn" onclick="setAmt(10000)">10,000</button>
                        <button type="button" class="quick-btn" onclick="setAmt(20000)">20,000</button>
                        <button type="button" class="quick-btn" onclick="setAmt(50000)">50,000</button>
                        <button type="button" class="quick-btn" onclick="setAmt(100000)">100,000</button>
                    </div>
                </div>

                <input type="hidden" name="shop_id" id="m-shop-id">
                <input type="hidden" name="sales" id="m-sales-hidden">
            </div>
            <div class="modal-foot">
                <button type="button" class="btn-secondary" onclick="closeModal()"><i class="bi bi-x-circle"></i> {{ __('messages.cancel') }}</button>
                <button type="submit" class="btn-primary"><i class="bi bi-check-circle"></i> {{ __('messages.submit_cash') }}</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const shopData = @json($shopReports);

    // ── Toggle detail row ──
    function toggleDetail(i) {
        const detail = document.getElementById('detail-' + i);
        const main = detail.previousElementSibling;
        const isOpen = detail.classList.contains('open');

        document.querySelectorAll('.detail-row.open').forEach(r => r.classList.remove('open'));
        document.querySelectorAll('.shop-row.expanded').forEach(r => r.classList.remove('expanded'));

        if (!isOpen) {
            detail.classList.add('open');
            main.classList.add('expanded');
            setTimeout(() => {
                detail.scrollIntoView({ block: 'center', behavior: 'smooth' });
            }, 100);
        }
    }

    // ── Search filter ──
    function filterShops() {
        const q = document.getElementById('shopSearch').value.toLowerCase();
        document.querySelectorAll('#shopTableBody .shop-row').forEach(row => {
            const match = row.getAttribute('data-name').includes(q);
            row.style.display = match ? '' : 'none';
            const next = row.nextElementSibling;
            if (next && next.classList.contains('detail-row')) {
                next.style.display = match ? '' : 'none';
            }
        });
    }

    // ── Sort shops ──
    function sortShops() {
        const sortValue = document.getElementById('sortSelect').value;
        const tbody = document.getElementById('shopTableBody');
        const rows = Array.from(tbody.querySelectorAll('.shop-row'));

        let sortKey, ascending;

        switch(sortValue) {
            case 'name_asc': sortKey = 'name'; ascending = true; break;
            case 'name_desc': sortKey = 'name'; ascending = false; break;
            case 'balanced': sortKey = 'balanced'; ascending = true; break;
            case 'unbalanced': sortKey = 'balanced'; ascending = false; break;
            case 'status_settled': sortKey = 'status'; ascending = true; break;
            case 'status_underpaid': sortKey = 'status'; ascending = false; break;
            case 'status_overpaid': sortKey = 'status'; ascending = false; break;
            case 'sales_high': sortKey = 'total_sales'; ascending = false; break;
            case 'sales_low': sortKey = 'total_sales'; ascending = true; break;
            case 'profit_high': sortKey = 'profit'; ascending = false; break;
            case 'profit_low': sortKey = 'profit'; ascending = true; break;
            default: sortKey = 'name'; ascending = true;
        }

        rows.sort((a, b) => {
            let aVal, bVal;

            if (sortKey === 'name') {
                aVal = a.getAttribute('data-name');
                bVal = b.getAttribute('data-name');
                return ascending ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
            }
            else if (sortKey === 'balanced') {
                const aBalanced = !a.classList.contains('warn-row');
                const bBalanced = !b.classList.contains('warn-row');
                if (ascending) {
                    return aBalanced === bBalanced ? 0 : aBalanced ? -1 : 1;
                } else {
                    return aBalanced === bBalanced ? 0 : aBalanced ? 1 : -1;
                }
            }
            else if (sortKey === 'status') {
                const aStatus = a.getAttribute('data-status');
                const bStatus = b.getAttribute('data-status');
                if (sortValue === 'status_settled' || sortValue === 'status_underpaid' || sortValue === 'status_overpaid') {
                    const target = sortValue.replace('status_', '');
                    return (bStatus === target ? 1 : 0) - (aStatus === target ? 1 : 0);
                }
                return 0;
            }
            else {
                const aNum = parseFloat(a.querySelector(`td:nth-child(${getColumnIndex(sortKey)})`).textContent.replace(/,/g, '')) || 0;
                const bNum = parseFloat(b.querySelector(`td:nth-child(${getColumnIndex(sortKey)})`).textContent.replace(/,/g, '')) || 0;
                return ascending ? aNum - bNum : bNum - aNum;
            }
        });

        rows.forEach(row => {
            tbody.appendChild(row);
            const detailRow = row.nextElementSibling;
            if (detailRow && detailRow.classList.contains('detail-row')) {
                tbody.appendChild(detailRow);
            }
        });

        updateRowNumbers();
    }

    function getColumnIndex(field) {
        const columnMap = {
            'cash_sales': 3,
            'credit_sales': 4,
            'total_sales': 5,
            'total_return': 6,
            'total_offer': 7,
            'discount': 8,
            'expenses': 9,
            'profit': 10,
            'paid_invoices': 11,
            'cash_receivings': 12,
            'credit_receivings': 13,
            'paid_receivings': 14,
            'totalChip': 15,
            'chip_used': 16,
            'cash_amount': 17,
            'cash_submitted': 18,
            'total_bank': 19,
            'bank_diff': 20,
            'cost_worth': 22
        };
        return columnMap[field] || 1;
    }

    function updateRowNumbers() {
        document.querySelectorAll('#shopTableBody .shop-row').forEach((row, index) => {
            row.cells[0].textContent = index + 1;
        });
    }

    // ── Cash modal ──
    function fmt(n) { return new Intl.NumberFormat().format(Math.round(n || 0)); }

    function openCashModal(i) {
        const s = shopData[i];
        document.getElementById('m-shop').textContent = s.shop_name;
        document.getElementById('m-sales').textContent = fmt(s.total_sales);
        document.getElementById('m-cash-sales').textContent = fmt(s.cash_sales);
        document.getElementById('m-exp').textContent = fmt(s.expenses);
        document.getElementById('m-dsc').textContent = fmt(s.discount);
        document.getElementById('m-expected').textContent = fmt(s.expected_cash);
        document.getElementById('m-remaining').textContent = fmt(s.cash_difference);
        document.getElementById('m-shop-id').value = s.shop_id;
        document.getElementById('m-sales-hidden').value = s.expected_cash;
        document.getElementById('cashAmount').value = s.cash_difference > 0 ? s.cash_difference : 0;
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        document.getElementById('m-date').value = `${year}-${month}-${day}`;
        document.getElementById('cashModal').classList.add('open');
    }

    function closeModal() {
        document.getElementById('cashModal').classList.remove('open');
        document.getElementById('cashAmount').value = '';
    }

    document.getElementById('cashModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    function setAmt(n) { document.getElementById('cashAmount').value = n; }

    // ── Exports ──
    function exportToExcel() {
        const dateFrom = document.getElementById('dateFromInput').value;
        const dateTo = document.getElementById('dateToInput').value;
        let url = 'exportShopReport?format=excel';
        if (dateFrom && dateTo) {
            url += '&date_from=' + dateFrom + '&date_to=' + dateTo;
        } else {
            url += '&date={{ $dateParam }}';
        }
        window.location.href = url;
    }
    function exportToPDF() {
        const dateFrom = document.getElementById('dateFromInput').value;
        const dateTo = document.getElementById('dateToInput').value;
        let url = 'exportShopReport?format=pdf';
        if (dateFrom && dateTo) {
            url += '&date_from=' + dateFrom + '&date_to=' + dateTo;
        } else {
            url += '&date={{ $dateParam }}';
        }
        window.location.href = url;
    }


    // ── Column Wizard ──
    const wizardData = [
        { title: 'C.Sale', fullName: @json(__('messages.cash_sales_wizard')), explanation: @json(__('messages.total_cash_sales_made_by_shop_today')), calculation: @json(__('messages.sum_of_all_cash_sales')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Cr.Sale', fullName: @json(__('messages.credit_sale')), explanation: @json(__('messages.total_credit_sales')), calculation: @json(__('messages.sum_of_all_cash_sales')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'T.Sales', fullName: @json(__('messages.total_sales')), explanation: @json(__('messages.total_sales_net')), calculation: @json(__('messages.calc_cash_plus_credit')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'T.Ret', fullName: @json(__('messages.total_return')), explanation: @json(__('messages.total_return_sum')), calculation: @json(__('messages.calc_sum_returns')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Offered', fullName: @json(__('messages.offered')), explanation: @json(__('messages.total_offered_items')), calculation: @json(__('messages.calc_sum_offered')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Dsc', fullName: @json(__('messages.discount')), explanation: @json(__('messages.total_discount')), calculation: @json(__('messages.calc_sum_discounts')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Exp', fullName: @json(__('messages.expenses')), explanation: @json(__('messages.total_expenses')), calculation: @json(__('messages.calc_sum_expenses')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'P/L', fullName: @json(__('messages.profit_loss')), explanation: @json(__('messages.total_profit_loss')), calculation: @json(__('messages.calc_total_sales_minus')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'P.Inv', fullName: @json(__('messages.paid_invoice')), explanation: @json(__('messages.total_paid_invoices')), calculation: @json(__('messages.calc_sum_paid_invoices')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Ca.R', fullName: @json(__('messages.cash_receivings')), explanation: @json(__('messages.total_cash_receivings')), calculation: @json(__('messages.calc_sum_cash_receivings')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Cr.R', fullName: @json(__('messages.credit_receivings')), explanation: @json(__('messages.total_credit_receivings')), calculation: @json(__('messages.calc_sum_credit_receivings')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Chip.D', fullName: @json(__('messages.chip_deposit')), explanation: @json(__('messages.total_chip_deposit')), calculation: @json(__('messages.calc_sum_chip_deposits')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Chip.U', fullName: @json(__('messages.chip_used')), explanation: @json(__('messages.total_chip_used')), calculation: @json(__('messages.calc_sum_chip_usage')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'C.A', fullName: @json(__('messages.cash_amount')), explanation: @json(__('messages.total_cash_amount_expected')), calculation: @json(__('messages.calc_sum_expected_cash')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'C.S', fullName: @json(__('messages.cash_submit')), explanation: @json(__('messages.total_cash_submitted')), calculation: @json(__('messages.calc_sum_submitted_cash')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'B.D', fullName: @json(__('messages.bank_deposit')), explanation: @json(__('messages.total_bank_deposit')), calculation: @json(__('messages.calc_sum_bank_deposits')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'B.Diff', fullName: @json(__('messages.bank_difference')), explanation: @json(__('messages.bank_difference_colon')), calculation: @json(__('messages.calc_bank_minus_cash')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Diff', fullName: @json(__('messages.difference')), explanation: @json(__('messages.difference_colon')), calculation: @json(__('messages.calc_expected_minus_submitted')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Inventory', fullName: @json(__('messages.inventory')), explanation: @json(__('messages.cost_worth_inventory_colon')), calculation: @json(__('messages.calc_sum_cost_worth')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Status', fullName: @json(__('messages.status')), explanation: @json(__('messages.calc_settled_status')), calculation: @json(__('messages.part_of_total_sales')), balance: @json(__('messages.part_of_total_sales')) },
        { title: 'Balance', fullName: @json(__('messages.balance')), explanation: @json(__('messages.calc_balanced_status')), calculation: @json(__('messages.part_of_total_sales')), balance: @json(__('messages.part_of_total_sales')) }
    ];

    let currentStep = 0;

    function updateWizard() {
        const step = wizardData[currentStep];
        document.getElementById('tooltipStep').textContent = (currentStep + 1) + '/22';
        document.getElementById('tooltipTitle').textContent = step.title;
        document.getElementById('tooltipFullName').textContent = step.fullName;
        document.getElementById('tooltipExplanation').textContent = step.explanation;
        document.getElementById('tooltipCalculation').textContent = step.calculation;
        document.getElementById('tooltipBalance').textContent = step.balance;

        const stepProgress = @json(__('messages.wizard_step_progress'))
            .replace(':step', currentStep + 1)
            .replace(':total', 22);
        document.getElementById('tooltipProgress').textContent = stepProgress;

        document.getElementById('prevBtn').style.display = currentStep === 0 ? 'none' : '';
        document.getElementById('nextBtn').style.display = currentStep === wizardData.length - 1 ? 'none' : '';
    }

    function nextStep() {
        if (currentStep < wizardData.length - 1) {
            currentStep++;
            updateWizard();
        }
    }

    function prevStep() {
        if (currentStep > 0) {
            currentStep--;
            updateWizard();
        }
    }

    function closeWizard() {
        document.getElementById('wizardOverlay').classList.remove('open');
        document.getElementById('closeHint').classList.remove('show');
    }

    document.getElementById('wizardOverlay').addEventListener('click', function(e) {
        if (e.target === this) closeWizard();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeWizard();
    });

    // ── Scroll shadow indicator for sticky columns ──
    const tableScroll = document.getElementById('tableScroll');
    if (tableScroll) {
        tableScroll.addEventListener('scroll', function() {
            if (this.scrollLeft > 10) {
                this.classList.add('scrolling-right');
            } else {
                this.classList.remove('scrolling-right');
            }
        });
    }
</script>
@include('footer')
</body>
</html>