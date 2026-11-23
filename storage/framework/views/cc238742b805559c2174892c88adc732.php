<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo app('translator')->get('messages.items_report'); ?></title>
    <?php echo $__env->make('links', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --navy:       #0B1E3D;
            --navy-mid:   #112952;
            --navy-light: #1A3A6B;
            --amber:      var(--theme-primary);
            --amber:      var(--theme-primary);
            --amber-pale: #FEF3C7;
            --emerald:    #059669;
            --emerald-pale: #D1FAE5;
            --rose:       #E11D48;
            --rose-pale:  #FFE4E6;
            --slate-50:   #F8FAFC;
            --slate-100:  #F1F5F9;
            --slate-200:  #E2E8F0;
            --slate-400:  #94A3B8;
            --slate-600:  #475569;
            --slate-800:  #1E293B;
            --violet:     #7C3AED;
            --violet-pale: #EDE9FE;
            --violet:     #7C3AED;
            --violet-pale: #EDE9FE;
            --white:      #FFFFFF;
            --font: 'Sora', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --r: 8px; --r-lg: 12px; --r-xl: 16px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            background: #ECF0F8;
            color: var(--slate-800);
            min-height: 100vh;
        }

        .wrap { padding: 1.25rem 1.5rem 2rem; }

        /* ── Page header ── */
        .pg-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            background: var(--navy);
            border-radius: var(--r-xl);
            padding: 1rem 1.5rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.25);
        }

        .pg-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--white);
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .pg-title-icon {
            width: 40px;
            height: 40px;
            background: rgba(245,158,11,0.2);
            border: 1px solid rgba(245,158,11,0.35);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--amber);
            font-size: 1.1rem;
        }

        .pg-title span { color: var(--amber); }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.5rem 1rem;
            border-radius: var(--r);
            border: 1px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.07);
            color: var(--white);
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-back:hover {
            background: rgba(255,255,255,0.15);
            color: var(--white);
        }

        /* ── API Comparison Panel ── */
        .api-compare-panel {
            background: var(--white);
            border: 2px solid var(--slate-200);
            border-radius: var(--r-lg);
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            transition: all 0.3s ease;
        }

        .api-compare-panel.has-mismatch {
            border-color: var(--rose);
            background: var(--rose-pale);
        }

        .api-compare-panel.has-match {
            border-color: var(--emerald);
            background: var(--emerald-pale);
        }

        .api-compare-panel.loading {
            border-color: var(--violet);
            background: var(--violet-pale);
        }

        .api-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .api-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy);
        }

        .api-title i {
            font-size: 1.2rem;
            color: var(--violet);
        }

        .api-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
        }

        .api-status.idle {
            background: var(--slate-100);
            color: var(--slate-500);
        }

        .api-status.loading {
            background: var(--violet-pale);
            color: var(--violet);
        }

        .api-status.success {
            background: var(--emerald-pale);
            color: var(--emerald);
        }

        .api-status.error {
            background: var(--rose-pale);
            color: var(--rose);
        }

        .api-status.mismatch {
            background: var(--amber-pale);
            color: #92400E;
        }

        .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 0.4rem;
        }

        .status-dot.idle { background: var(--slate-300); }
        .status-dot.loading { background: var(--violet); animation: pulse-dot 1s ease-in-out infinite; }
        .status-dot.success { background: var(--emerald); }
        .status-dot.error { background: var(--rose); }
        .status-dot.mismatch { background: #92400E; }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .api-controls {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
            margin-top: 0.75rem;
        }

        .api-date-input {
            font-size: 0.82rem;
            padding: 0.4rem 0.7rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }

        .api-date-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        .api-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1.5px solid var(--slate-200);
        }

        .api-summary-item {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .api-summary-item .label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--slate-400);
        }

        .api-summary-item .value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--navy);
        }

        .api-summary-item .value.mismatch { color: var(--rose); }
        .api-summary-item .value.match { color: var(--emerald); }

        .abtn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.48rem 0.9rem;
            border-radius: 7px;
            border: 1.5px solid;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            white-space: nowrap;
        }

        .abtn-outline {
            background: var(--white);
            border-color: var(--slate-200);
            color: var(--slate-700);
        }

        .abtn-outline:hover {
            background: var(--slate-50);
            border-color: var(--navy-light);
            color: var(--navy);
        }

        .abtn-primary {
            background: var(--amber);
            border-color: var(--amber);
            color: var(--navy);
        }

        .abtn-primary:hover {
            background: #FBBF24;
            transform: translateY(-1px);
            color: var(--navy);
        }

        .abtn-violet {
            background: var(--violet);
            border-color: var(--violet);
            color: var(--white);
        }

        .abtn-violet:hover {
            background: #6D28D9;
            transform: translateY(-1px);
            color: var(--white);
        }

        /* ── Mismatch Table ── */
        .mismatch-table-wrap {
            margin-top: 1rem;
            max-height: 350px;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid var(--slate-200);
            border-radius: var(--r);
        }

        .mismatch-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        .mismatch-table thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.5rem 0.7rem;
            border-bottom: 2px solid var(--slate-200);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .mismatch-table tbody td {
            padding: 0.5rem 0.7rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
        }

        .mismatch-table tbody tr:hover td {
            background: #F8FAFF;
        }

        .diff-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
        }

        .diff-badge.mismatch {
            background: var(--rose-pale);
            color: var(--rose);
        }

        .diff-badge.match {
            background: var(--emerald-pale);
            color: var(--emerald);
        }

        .diff-badge.partial {
            background: var(--amber-pale);
            color: #92400E;
        }

        .diff-badge.not-found {
            background: #FEF3C7;
            color: #92400E;
        }

        .no-mismatch-msg {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--slate-400);
        }

        .no-mismatch-msg i {
            font-size: 2rem;
            display: block;
            margin-bottom: 0.5rem;
            color: var(--emerald);
        }

        /* ── Comparison Layout ── */
        .comparison-header {
            background: var(--white);
            border: 2px solid var(--slate-200);
            border-radius: var(--r-lg);
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
        }

        .comparison-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 0.25rem;
        }

        .comparison-title i {
            font-size: 1.2rem;
            color: var(--violet);
        }

        .comparison-subtitle {
            font-size: 0.85rem;
            color: var(--slate-500);
            margin-bottom: 1rem;
        }

        .comparison-controls {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .comparison-date-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .comparison-date-group label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--slate-500);
        }

        .comparison-date-group label i {
            margin-right: 0.3rem;
        }

        .comparison-date-input {
            font-size: 0.82rem;
            padding: 0.45rem 0.75rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            outline: none;
            min-width: 160px;
        }

        .comparison-date-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        .comparison-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .comparison-card {
            background: var(--white);
            border-radius: var(--r-lg);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            border: 1.5px solid var(--slate-200);
        }

        .comparison-card-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: var(--slate-100);
            color: var(--slate-600);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .comparison-card-success .comparison-card-icon {
            background: var(--emerald-pale);
            color: var(--emerald);
        }

        .comparison-card-danger .comparison-card-icon {
            background: var(--rose-pale);
            color: var(--rose);
        }

        .comparison-card-purple .comparison-card-icon {
            background: var(--violet-pale);
            color: var(--violet);
        }

        .comparison-card-warning .comparison-card-icon {
            background: #FEF3C7;
            color: #92400E;
        }

        .comparison-card-content {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .comparison-card-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--slate-400);
        }

        .comparison-card-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--navy);
        }

        .comparison-card-sub {
            font-size: 0.75rem;
            color: var(--slate-400);
        }

        .comparison-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .comparison-tabs {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .comparison-tab {
            padding: 0.4rem 0.85rem;
            border-radius: 7px;
            border: 1.5px solid var(--slate-200);
            background: var(--white);
            color: var(--slate-600);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }

        .comparison-tab:hover {
            background: var(--slate-50);
        }

        .comparison-tab.active {
            background: var(--navy);
            border-color: var(--navy);
            color: var(--white);
        }

        .comparison-tools {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 0.7rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate-400);
            font-size: 0.85rem;
        }

        .search-box input {
            padding: 0.45rem 0.75rem 0.45rem 2.1rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            font-size: 0.82rem;
            outline: none;
            min-width: 240px;
        }

        .search-box input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        .comparison-table-panel {
            border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg);
            overflow: hidden;
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        .comparison-table thead th {
            background: var(--navy);
            color: var(--white);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.6rem 0.7rem;
            border-bottom: 2px solid var(--navy-light);
        }

        .comparison-table thead th.group-header {
            text-align: center;
            border-left: 1px solid rgba(255,255,255,0.15);
        }

        .comparison-table thead th.sub-header {
            background: var(--navy-light);
            color: var(--white);
            font-size: 0.7rem;
            padding: 0.4rem 0.7rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .comparison-table tbody td {
            padding: 0.55rem 0.7rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
        }

        .comparison-table tbody tr:hover td {
            background: #F8FAFF;
        }

        .comparison-table .mono {
            font-family: var(--mono);
            font-size: 0.8rem;
        }

        .comparison-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .comparison-badge.match {
            background: var(--emerald-pale);
            color: var(--emerald);
        }

        .comparison-badge.mismatch {
            background: var(--rose-pale);
            color: var(--rose);
        }

        .comparison-badge.missing {
            background: #FEF3C7;
            color: #92400E;
        }

        .comparison-table .diff-positive {
            color: var(--rose);
            font-weight: 600;
        }

        .comparison-table .diff-negative {
            color: var(--emerald);
            font-weight: 600;
        }

        .comparison-table .diff-zero {
            color: var(--slate-400);
        }

        /* ── API Comparison Panel ── */
        .api-compare-panel {
            background: var(--white);
            border: 2px solid var(--slate-200);
            border-radius: var(--r-lg);
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            transition: all 0.3s ease;
        }

        .api-compare-panel.has-mismatch {
            border-color: var(--rose);
            background: var(--rose-pale);
        }

        .api-compare-panel.has-match {
            border-color: var(--emerald);
            background: var(--emerald-pale);
        }

        .api-compare-panel.loading {
            border-color: var(--violet);
            background: var(--violet-pale);
        }

        .api-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .api-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1rem;
            font-weight: 700;
            color: var(--navy);
        }

        .api-title i {
            font-size: 1.2rem;
            color: var(--violet);
        }

        .api-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
        }

        .api-status.idle {
            background: var(--slate-100);
            color: var(--slate-500);
        }

        .api-status.loading {
            background: var(--violet-pale);
            color: var(--violet);
        }

        .api-status.success {
            background: var(--emerald-pale);
            color: var(--emerald);
        }

        .api-status.error {
            background: var(--rose-pale);
            color: var(--rose);
        }

        .api-status.mismatch {
            background: var(--amber-pale);
            color: #92400E;
        }

        .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 0.4rem;
        }

        .status-dot.idle { background: var(--slate-300); }
        .status-dot.loading { background: var(--violet); animation: pulse-dot 1s ease-in-out infinite; }
        .status-dot.success { background: var(--emerald); }
        .status-dot.error { background: var(--rose); }
        .status-dot.mismatch { background: #92400E; }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .api-controls {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            align-items: center;
            margin-top: 0.75rem;
        }

        .api-date-input {
            font-size: 0.82rem;
            padding: 0.4rem 0.7rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }

        .api-date-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        .api-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1.5px solid var(--slate-200);
        }

        .api-summary-item {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .api-summary-item .label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--slate-400);
        }

        .api-summary-item .value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--navy);
        }

        .api-summary-item .value.mismatch { color: var(--rose); }
        .api-summary-item .value.match { color: var(--emerald); }

        .abtn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.48rem 0.9rem;
            border-radius: 7px;
            border: 1.5px solid;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
            white-space: nowrap;
        }

        .abtn-outline {
            background: var(--white);
            border-color: var(--slate-200);
            color: var(--slate-700);
        }

        .abtn-outline:hover {
            background: var(--slate-50);
            border-color: var(--navy-light);
            color: var(--navy);
        }

        .abtn-primary {
            background: var(--amber);
            border-color: var(--amber);
            color: var(--navy);
        }

        .abtn-primary:hover {
            background: #FBBF24;
            transform: translateY(-1px);
            color: var(--navy);
        }

        .abtn-violet {
            background: var(--violet);
            border-color: var(--violet);
            color: var(--white);
        }

        .abtn-violet:hover {
            background: #6D28D9;
            transform: translateY(-1px);
            color: var(--white);
        }

        /* ── Mismatch Table ── */
        .mismatch-table-wrap {
            margin-top: 1rem;
            max-height: 350px;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid var(--slate-200);
            border-radius: var(--r);
        }

        .mismatch-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        .mismatch-table thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.5rem 0.7rem;
            border-bottom: 2px solid var(--slate-200);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .mismatch-table tbody td {
            padding: 0.5rem 0.7rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
        }

        .mismatch-table tbody tr:hover td {
            background: #F8FAFF;
        }

        .diff-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
        }

        .diff-badge.mismatch {
            background: var(--rose-pale);
            color: var(--rose);
        }

        .diff-badge.match {
            background: var(--emerald-pale);
            color: var(--emerald);
        }

        .diff-badge.partial {
            background: var(--amber-pale);
            color: #92400E;
        }

        .no-mismatch-msg {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--slate-400);
        }

        .no-mismatch-msg i {
            font-size: 2rem;
            display: block;
            margin-bottom: 0.5rem;
            color: var(--emerald);
        }

        /* ── Filter bar ── */
        .filter-bar {
            background: var(--slate-100);
            border-bottom: 1px solid var(--slate-200);
            padding: 0.8rem 1.25rem;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
            border-radius: var(--r-lg) var(--r-lg) 0 0;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .filter-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--slate-600);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            white-space: nowrap;
        }

        .filter-input {
            font-size: 0.825rem;
            padding: 0.38rem 0.65rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 7px;
            background: var(--white);
            color: var(--slate-800);
            transition: border-color 0.18s, box-shadow 0.18s;
            outline: none;
            min-width: 0;
        }

        .filter-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }

        select.filter-input { cursor: pointer; }

        /* ── Summary bar ── */
        .summary-bar {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 0.7rem 1.25rem;
            background: linear-gradient(90deg, #EBF0FA 0%, #F1F5F9 100%);
            border-bottom: 1px solid var(--slate-200);
            flex-wrap: wrap;
        }

        .summary-stat {
            display: flex;
            flex-direction: column;
            gap: 0.1rem;
        }

        .summary-stat-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--slate-400);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-stat-value {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--navy);
        }

        .summary-stat-value.amber { color: var(--amber); }
        .summary-stat-value.emerald { color: var(--emerald); }
        .summary-stat-value.rose { color: var(--rose); }
        .summary-stat-value.violet { color: var(--violet); }
        .summary-stat-value.violet { color: var(--violet); }

        .summary-divider {
            width: 1px;
            height: 32px;
            background: var(--slate-200);
        }

        /* ── Table ── */
        .table-scroll { overflow-x: auto; }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.825rem;
        }

        table.report-table thead th {
            background: var(--slate-100);
            color: var(--slate-600);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 0.65rem 0.75rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 5;
        }

        table.report-table tbody td {
            padding: 0.55rem 0.75rem;
            border-bottom: 1px solid var(--slate-200);
            vertical-align: middle;
            color: var(--slate-800);
        }

        table.report-table tbody tr { transition: background 0.12s; }
        table.report-table tbody tr:hover td { background: #EFF4FF; }

        .product-name { font-weight: 600; color: var(--navy); }
        .mono { font-family: var(--mono); font-size: 0.8rem; }

        .api-compare-badge {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            margin-left: 0.5rem;
            background: var(--rose);
            color: white;
        }

        .api-compare-badge.match {
            background: var(--emerald);
        }
        .mono { font-family: var(--mono); font-size: 0.8rem; }

        .api-compare-badge {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            margin-left: 0.5rem;
            background: var(--rose);
            color: white;
        }

        .api-compare-badge.match {
            background: var(--emerald);
        }

        .api-compare-badge.not-found {
            background: #F59E0B;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 3.5rem 1rem;
            color: var(--slate-400);
        }
        .empty-state i { font-size: 2.5rem; margin-bottom: 0.75rem; display: block; }
        .empty-state p { font-size: 0.9rem; margin: 0; }

        /* ── Card panel ── */
        .card-panel {
            background: var(--white);
            border-radius: 0 0 var(--r-lg) var(--r-lg);
            box-shadow: 0 2px 16px rgba(11,30,61,0.08);
            overflow: hidden;
        }

        @media(max-width:768px) {
            .wrap { padding: 1rem; }
            .pg-header { padding: 0.75rem 1rem; }
            .filter-bar { padding: 0.6rem 1rem; }
            .summary-bar { padding: 0.5rem 1rem; gap: 0.75rem; }
            .api-summary { grid-template-columns: 1fr 1fr; }
            .api-summary { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>
<?php echo $__env->make('sidenav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <main class="main-content">
        <div class="wrap">
            <?php if(session('success')): ?>
                <div class="alert alert-success d-flex justify-content-between">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                <div class="alert alert-danger d-flex justify-content-between">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success d-flex justify-content-between">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                <div class="alert alert-danger d-flex justify-content-between">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            
            <div class="pg-header">
                <div class="pg-title">
                    <div class="pg-title-icon"><i class="bi bi-box-seam"></i></div>
                    <?php echo app('translator')->get('messages.items'); ?> <span><?php echo app('translator')->get('messages.report'); ?></span>
                </div>
                <div>
                    <a href="<?php echo e(url('items-report')); ?>" class="btn-back">
                        <i class="bi bi-arrow-left"></i> <?php echo app('translator')->get('messages.back_to_items'); ?>
                    </a>
                </div>
            </div>

            
            <div class="card-panel" style="margin-bottom:1.25rem;">
                <div style="padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <div style="width:38px;height:38px;border-radius:10px;background:var(--violet-pale);color:var(--violet);display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
                            <i class="bi bi-cloud-download"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;color:var(--navy);font-size:0.95rem;">Sync Stock from MainStore</div>
                            <div style="font-size:0.78rem;color:var(--slate-500);">Fetch opening stock, closing stock, receiving, and quantity remaining for a date range.</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.6rem;flex-wrap:wrap;">
                        <div>
                            <label style="font-size:0.72rem;font-weight:600;color:var(--slate-500);display:block;margin-bottom:0.2rem;">API From</label>
                            <input type="date" id="syncFromDate" class="filter-input" value="<?php echo e($fromDate ?? date('Y-m-d')); ?>">
                        </div>
                        <div>
                            <label style="font-size:0.72rem;font-weight:600;color:var(--slate-500);display:block;margin-bottom:0.2rem;">API To</label>
                            <input type="date" id="syncToDate" class="filter-input" value="<?php echo e($toDate ?? date('Y-m-d')); ?>">
                        </div>
                        <div>
                            <label style="font-size:0.72rem;font-weight:600;color:var(--slate-500);display:block;margin-bottom:0.2rem;">Apply to local date</label>
                            <input type="date" id="syncLocalDate" class="filter-input" value="<?php echo e($selectedDate ?? date('Y-m-d')); ?>">
                        </div>
                        <div>
                            <label style="font-size:0.72rem;font-weight:600;color:var(--slate-500);display:block;margin-bottom:0.2rem;">Shop</label>
                            <select id="syncShop" class="filter-input">
    <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            if (is_object($shop)) {
                $shopId = $shop->id ?? null;
            } elseif (is_array($shop)) {
                $shopId = $shop['id'] ?? ($shop['account'] ?? null);
            } else {
                $shopId = $shop; // scalar
            }
        ?>
        <option value="<?php echo e($shopId); ?>"><?php echo e(is_array($shop) ? ($shop['name'] ?? 'MainStore') : (is_object($shop) && isset($shop->name) ? $shop->name : 'MainStore')); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>
                        </div>
                        <button class="abtn abtn-violet" id="syncMainStoreBtn" style="margin-top:1.1rem;">
                            <i class="bi bi-arrow-repeat"></i> Sync
                        </button>
                    </div>
                </div>
                <div id="syncResult" style="padding:0 1.25rem 1rem;"></div>
            </div>

            
            <div class="card-panel" style="margin-bottom:1.25rem;display:none;">
                <div class="table-scroll">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Unit</th>
                                <th>Received</th>
                                <th>Sold</th>
                                <th>Returned</th>
                                <th>Remaining</th>
                                <th>Opening Stock</th>
                                <th>Closing Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($reportRows) || $reportRows->isEmpty()): ?>
                            <tr>
                                <td colspan="11">
                                    <div class="empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p>No report data found for the selected filters.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php $__currentLoopData = $reportRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $openingStock = $row['openingStock'] ?? 0;
                                        $closingStock = $row['closingStock'] ?? 0;
                                        $remainingQty = $row['remainingQty'] ?? 0;
                                        $receivedQty = $row['mainQty'] ?? 0;
                                    ?>
                                    <tr
                                        data-product-name="<?php echo e($row['productName'] ?? ''); ?>"
                                        data-product-id="<?php echo e($row['productId'] ?? ''); ?>"
                                        data-unit="<?php echo e($row['category'] ?? ''); ?>"
                                        data-category="<?php echo e((string) ($row['category'] ?? '')); ?>"
                                        data-opening-stock="<?php echo e($openingStock); ?>"
                                        data-closing-stock="<?php echo e($closingStock); ?>"
                                        data-received-qty="<?php echo e($receivedQty); ?>"
                                        data-sold-qty="<?php echo e($row['soldQty'] ?? 0); ?>"
                                        data-qty="<?php echo e($remainingQty); ?>"
                                        data-product-code="<?php echo e($row['productId'] ?? ''); ?>"
                                        data-shop-id="<?php echo e($accountFilter); ?>">
                                        <td style="color:var(--slate-400);font-size:0.78rem;"><?php echo e($loop->iteration); ?></td>
                                        <td><span class="mono"><?php echo e($row['productId'] ?? ''); ?></span></td>
                                        <td><span class="product-name"><?php echo e($row['productName'] ?? 'Unknown'); ?></span></td>
                                        <td><span class="mono"><?php echo e($row['category'] ?? ''); ?></span></td>
                                        <td><span class="mono"><?php echo e(number_format($row['mainQty'] ?? 0)); ?></span></td>
                                        <td><span class="mono"><?php echo e(number_format($row['soldQty'] ?? 0)); ?></span></td>
                                        <td><span class="mono"><?php echo e(number_format($row['returnedQty'] ?? 0)); ?></span></td>
                                        <td><span class="mono" style="font-weight:600;"><?php echo e(number_format($closingStock)); ?></span></td>
                                        <td><input type="number" class="stock-input" data-field="opening_stock" value="<?php echo e($openingStock); ?>" style="width:90px;padding:0.25rem 0.4rem;border:1px solid var(--slate-200);border-radius:6px;font-size:0.8rem;"></td>
                                        <td><input type="number" class="stock-input" data-field="closing_stock" value="<?php echo e($closingStock); ?>" style="width:90px;padding:0.25rem 0.4rem;border:1px solid var(--slate-200);border-radius:6px;font-size:0.8rem;"></td>
                                        <td><button class="abtn abtn-outline save-row-btn" data-product-id="<?php echo e($row['productId'] ?? ''); ?>" data-category="<?php echo e($row['category'] ?? ''); ?>" data-snapshot-date="<?php echo e($toDate ?? $selectedDate ?? date('Y-m-d')); ?>" style="padding:0.3rem 0.6rem;font-size:0.78rem;">Save</button></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if($reportRows->hasPages()): ?>
                <div class="pagination" style="margin-top:1rem;display:flex;justify-content:center;gap:0.5rem;flex-wrap:wrap;">
                    <?php echo e($reportRows->links()); ?>

                </div>
            <?php endif; ?>

            
            <div class="comparison-header">
                <div class="comparison-title">
                    <i class="bi bi-arrow-repeat"></i>
                    <span>Stock Comparison / Reconciliation</span>
                </div>
                <div class="comparison-subtitle">Compare stock between Main Store and Backup Store to ensure data accuracy.</div>
                <div class="comparison-controls">
                    <div class="comparison-date-group">
                        <label><i class="bi bi-calendar-check"></i> Main Store Date (Yesterday)</label>
                        <input type="date" class="comparison-date-input" id="mainStoreDate" value="<?php echo e(date('Y-m-d', strtotime('-1 days'))); ?>">
                    </div>
                    <div class="comparison-date-group">
                        <label><i class="bi bi-calendar-check"></i> Backup Store Date (Today)</label>
                        <input type="date" class="comparison-date-input" id="backupStoreDate"  value="<?php echo e($backupDate ?? date('Y-m-d')); ?>">
                    </div>
                    <button class="abtn abtn-primary" id="apiCompareBtn" onclick="runApiComparison()">
                        <i class="bi bi-arrow-repeat"></i> Compare Stock
                    </button>
                    <button class="abtn abtn-outline" onclick="refreshLocalReport()" title="Reload local report using Backup Store Date">
                        <i class="bi bi-arrow-clockwise"></i> Refresh Report
                    </button>
                </div>
            </div>

            
            <div class="comparison-summary" id="comparisonSummary" style="display:none;">
                <div class="comparison-card">
                    <div class="comparison-card-icon"><i class="bi bi-box"></i></div>
                    <div class="comparison-card-content">
                        <span class="comparison-card-label">Total Products</span>
                        <span class="comparison-card-value" id="totalProducts">0</span>
                    </div>
                </div>
                <div class="comparison-card comparison-card-success">
                    <div class="comparison-card-icon"><i class="bi bi-check-circle"></i></div>
                    <div class="comparison-card-content">
                        <span class="comparison-card-label">Matching Items</span>
                        <span class="comparison-card-value" id="matchingItems">0</span>
                        <span class="comparison-card-sub" id="matchingPercent">0%</span>
                    </div>
                </div>
                <div class="comparison-card comparison-card-danger">
                    <div class="comparison-card-icon"><i class="bi bi-x-circle"></i></div>
                    <div class="comparison-card-content">
                        <span class="comparison-card-label">Mismatched Items</span>
                        <span class="comparison-card-value" id="mismatchedItems">0</span>
                        <span class="comparison-card-sub" id="mismatchedPercent">0%</span>
                    </div>
                </div>
                <div class="comparison-card comparison-card-purple">
                    <div class="comparison-card-icon"><i class="bi bi-percent"></i></div>
                    <div class="comparison-card-content">
                        <span class="comparison-card-label">Match Rate</span>
                        <span class="comparison-card-value" id="matchRate">0%</span>
                    </div>
                </div>
                <div class="comparison-card comparison-card-warning">
                    <div class="comparison-card-icon"><i class="bi bi-exclamation-triangle"></i></div>
                    <div class="comparison-card-content">
                        <span class="comparison-card-label">Total Difference</span>
                        <span class="comparison-card-value" id="totalDifference">0</span>
                    </div>
                </div>
            </div>

            
            <div class="comparison-toolbar" id="comparisonToolbar" style="display:none;">
                <div class="comparison-tabs">
                    <button class="comparison-tab active" data-filter="all" onclick="filterTable('all')">All (<span id="tabAll">0</span>)</button>
                    <button class="comparison-tab" data-filter="matching" onclick="filterTable('matching')">Matching (<span id="tabMatching">0</span>)</button>
                    <button class="comparison-tab" data-filter="mismatched" onclick="filterTable('mismatched')">Mismatched (<span id="tabMismatched">0</span>)</button>
                    <button class="comparison-tab" data-filter="missing-main" onclick="filterTable('missing-main')">Missing in Main (<span id="tabMissingMain">0</span>)</button>
                    <button class="comparison-tab" data-filter="missing-backup" onclick="filterTable('missing-backup')">Missing in Backup (<span id="tabMissingBackup">0</span>)</button>
                </div>
                <div class="comparison-tabs" style="margin-top:0.5rem;">
                    <button class="comparison-tab active" data-unit-filter="all" onclick="filterByUnit('all')">All Units</button>
                    <button class="comparison-tab" data-unit-filter="CTN" onclick="filterByUnit('CTN')">CTN</button>
                    <button class="comparison-tab" data-unit-filter="PCS" onclick="filterByUnit('PCS')">PCS</button>
                </div>
                <div class="comparison-tools">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="comparisonSearch" placeholder="Search by product code or name..." oninput="searchTable()">
                    </div>
                    <button class="abtn abtn-outline" onclick="exportComparison()">
                        <i class="bi bi-download"></i> Export
                    </button>
                </div>
            </div>

            
            <div class="card-panel comparison-table-panel" id="comparisonTablePanel">
                <div class="table-scroll">
                    
                    <table class="comparison-table" id="comparisonTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th colspan="5" class="group-header">Main Store (<span id="mainStoreDateLabel"></span>)</th>
                                <th colspan="5" class="group-header">Backup Store (<span id="backupStoreDateLabel"></span>)</th>
                                <th colspan="5" class="group-header">Difference</th>
                                <th>Status</th>
                            </tr>
                            <tr class="sub-header">
                                <th></th>
                                <th></th>
                                <th>Opening</th>
                                <th>Purchases</th>
                                <th>Sold</th>
                                <th>Closing</th>
                                <th>Qty</th>
                                <th>Opening</th>
                                <th>Purchases</th>
                                <th>Sold</th>
                                <th>Closing</th>
                                <th>Qty</th>
                                <th>Opening</th>
                                <th>Purchases</th>
                                <th>Sold</th>
                                <th>Closing</th>
                                <th>Qty</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="comparisonTableBody">
                        </tbody>
                    </table>
                </div>
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
                <div class="info-box mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    <?php echo app('translator')->get('messages.search_a_past_sale'); ?>
                </div>
                <div style="position:relative;margin-bottom:1rem;">
                    <div class="sbox">
                        <i class="bi bi-search sbox-icon"></i>
                        <input type="text" id="pastSalesSearch" class="sbox-input" placeholder="<?php echo app('translator')->get('messages.search_by_customer_name'); ?>" autocomplete="off">
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
                <p style="margin-bottom:0.75rem;font-size:0.9rem;color:var(--slate-600);">
                    <?php echo app('translator')->get('messages.returning_this_sale_to_orders'); ?>
                </p>
                <div id="returnSaleDetails" class="cust-info" style="display:none;"></div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo app('translator')->get('messages.cancel'); ?></button>
                <button type="button" id="confirmReturnBtn" class="submit-btn" style="width:auto;padding:0.5rem 1.2rem;">
                    <i class="bi bi-check-circle-fill"></i> <?php echo app('translator')->get('messages.confirm'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// ── STOCK COMPARISON / RECONCILIATION ────────────────────────────────────────
// This script block powers the "Stock Comparison / Reconciliation" panel below.
// It compares the local database (Backup Store) against the MainStore API.
//
// Key pieces, in reading order:
//   1. API_PROXY_URL / comparisonData / currentFilter / currentUnitFilter  (state)
//   2. detectUnit / cleanProductName / updateApiStatus                      (helpers)
//   3. refreshLocalReport + backupStoreDate change handler                  (page reload with a new date)
//   4. runApiComparison()                                                  (main flow: fetch API, match local rows, build comparisonData)
//   5. renderComparisonTable() + filterTable / filterByUnit / searchTable   (drawing + filters)
//   6. exportComparison()                                                  (CSV download)
//
// To change which numbers feed the comparison, edit the localProducts block and
// the comparisonData.push() block inside runApiComparison().
// To change what each row looks like, edit the row.innerHTML template in
// renderComparisonTable() (cell order must match the <thead> of #comparisonTable).

// Laravel proxy endpoint that talks to the MainStore API (keeps the token server-side)
const API_PROXY_URL = '/main-store/proxy';

// Rows shown in the comparison table; rebuilt by runApiComparison() each run
let comparisonData = [];
// Active status tab ('all', 'matching', 'mismatched', 'missing-main', 'missing-backup')
let currentFilter = 'all';
// Active unit tab ('all', 'CTN', 'PCS')
let currentUnitFilter = 'all';

// Guess the unit (CTN / PCS) from a product name; used when the product has no unit set
function detectUnit(name) {
    const lower = (name || '').toLowerCase();
    if (lower.includes('carton') || lower.includes('ctn')) return 'CTN';
    if (lower.includes(' pc') || lower.includes(' pcs') || lower.includes('/pc') || lower.includes('-pc')) return 'PCS';
    return 'pcs';
}

// Strip a trailing "- CTN" / "- PCS" style suffix and collapse extra spaces
function cleanProductName(name) {
    if (!name) return '';
    let cleaned = name.trim();
    cleaned = cleaned.replace(/\s*-\s*(ctn|carton|cartons|ctns|pc|pcs)\b.*/i, '').trim();
    cleaned = cleaned.replace(/\s+/g, ' ');
    return cleaned;
}

// Paint the status pill (idle / loading / success / error / mismatch) next to the title
function updateApiStatus(status, message) {
    const statusEl = document.getElementById('apiStatus');
    const statusText = document.getElementById('apiStatusText');

    if (!statusEl || !statusText) {
        return;
    }

    statusEl.className = 'api-status ' + status;
    const dot = statusEl.querySelector('.status-dot');
    if (dot) {
        dot.className = 'status-dot ' + status;
    }
    statusText.textContent = message;
}

async function saveRowStock(btn) {
    const productId = btn.getAttribute('data-product-id');
    const category = btn.getAttribute('data-category') || '';
    const snapshotDate = btn.getAttribute('data-snapshot-date');
    const row = btn.closest('tr');
    const openingInput = row.querySelector('.stock-input[data-field="opening_stock"]');
    const closingInput = row.querySelector('.stock-input[data-field="closing_stock"]');

    if (!productId || !snapshotDate || !openingInput || !closingInput) {
        alert('Missing required data');
        return;
    }

    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = 'Saving...';

    try {
        const response = await fetch('/items-report/update-stock', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                product_id: productId,
                category: category,
                snapshot_date: snapshotDate,
                opening_stock: parseInt(openingInput.value || 0),
                closing_stock: parseInt(closingInput.value || 0)
            })
        });

        const data = await response.json();
        if (data.success) {
            btn.innerHTML = 'Saved';
            btn.classList.remove('abtn-outline');
            btn.classList.add('abtn-primary');
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('abtn-primary');
                btn.classList.add('abtn-outline');
                btn.disabled = false;
            }, 1500);
        } else {
            throw new Error('Save failed');
        }
    } catch (e) {
        alert('Failed to save: ' + e.message);
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('save-row-btn')) {
        saveRowStock(e.target);
    }
});

async function syncStockFromMainStore() {
    const fromDate = document.getElementById('syncFromDate').value;
    const toDate = document.getElementById('syncToDate').value;
    const localDate = document.getElementById('syncLocalDate').value;
    const shop = document.getElementById('syncShop').value;
    const resultBox = document.getElementById('syncResult');
    const btn = document.getElementById('syncMainStoreBtn');

    if (!fromDate || !toDate || !localDate) {
        resultBox.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> Please select API from, to, and local date.</div>';
        return;
    }

    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Syncing...';
    resultBox.innerHTML = '<div class="alert" style="background:var(--violet-pale);color:var(--violet);border-left:3px solid var(--violet);"><i class="bi bi-arrow-repeat"></i> Syncing from MainStore...</div>';

    try {
        const response = await fetch('/items-report/sync-from-mainstore', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                from_date: fromDate,
                to_date: toDate,
                local_date: localDate,
                shop: shop
            })
        });

        const data = await response.json();

        if (data.success) {
            const results = (data.results || []).slice(0, 80);
            const list = results.map(r => '<li>' + r + '</li>').join('');
            resultBox.innerHTML = '<div class="alert alert-success"><strong>' + data.message + '</strong></div>' +
                '<div style="background:var(--white);border:1px solid var(--slate-200);border-radius:var(--r);padding:0.75rem;max-height:260px;overflow:auto;">' +
                '<ul style="margin:0;padding-left:1.2rem;font-size:12px;color:var(--slate-700);">' + list + '</ul>' +
                '</div>';
            setTimeout(function () {
                location.reload();
            }, 1200);
        } else {
            resultBox.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> ' + (data.error || 'Sync failed') + '</div>';
        }
    } catch (e) {
        resultBox.innerHTML = '<div class="alert alert-danger"><i class="bi bi-exclamation-circle"></i> ' + e.message + '</div>';
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'syncMainStoreBtn') {
        syncStockFromMainStore();
    }
});

// Changing the Backup Store Date reloads the page so the local report — and
// therefore the comparison — is recalculated for that date
document.getElementById('backupStoreDate').addEventListener('change', function() {
    const backupDate = this.value;
    if (!backupDate) {
        return;
    }
    const url = new URL(window.location);
    url.searchParams.set('backup_date', backupDate);
    window.location.href = url.toString();
});

// Reload the local report for the selected Backup Store Date without re-running the
// comparison straight away (use "Compare Stock" afterwards)
function refreshLocalReport() {
    const backupDate = document.getElementById('backupStoreDate').value;
    if (!backupDate) {
        alert('Please select a Backup Store Date');
        return;
    }
    window.location.href = window.location.pathname + '?backup_date=' + backupDate;
}

        /**
         * runApiComparison
         * Flow: fetch MainStore stock report for the "Main Store Date" -> build lookup maps
         * from the API items -> walk the LOCAL report rows and match each one to the API
         * -> render the comparison table (every local product gets its own row).
         *
         * Table layout: # | Product Name |
         *   Main Store (Opening, Closing, Qty)   <- values from the MainStore API
         *   Backup Store (Opening, Closing, Qty) <- values from the local database
         *   Difference (Opening, Closing, Qty)   <- API minus local
         *   Status                               <- MATCH / DIFFERENT / MISSING-MAIN
         */
        async function runApiComparison() {
            const mainDate = document.getElementById('mainStoreDate').value;
            const backupDate = document.getElementById('backupStoreDate').value;
            
            if (!mainDate || !backupDate) {
                alert('Please select both dates');
                return;
            }

            const btn = document.getElementById('apiCompareBtn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Comparing...';

            updateApiStatus('loading', 'Fetching stock report from MainStore...');
            
            // Grab the product ids of the local report rows; they are sent to the proxy
            // so the API response is pre-filtered server-side to only these products.
            const localProductIds = [];
            const rows = document.querySelectorAll('table.report-table tbody tr[data-product-id]');
            rows.forEach((row) => {
                const productId = row.dataset.productId || '';
                if (productId) {
                    localProductIds.push(productId);
                }
            });
            
            let isCancelled = false;
            
            try {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => {
                    if (!isCancelled) {
                        controller.abort('Request timed out after 60 seconds');
                    }
                }, 60000);
                
                // POST to the Laravel proxy which talks to the MainStore API and hides the token
                const proxyResponse = await fetch(API_PROXY_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        action: 'stock_report',
                        snapshot_date: mainDate,
                        local_product_ids: localProductIds
                    }),
                    signal: controller.signal
                });
                
                clearTimeout(timeoutId);
                
                if (isCancelled) {
                    return;
                }
                
                if (!proxyResponse.ok) {
                    let errorText = '';
                    try {
                        const errorData = await proxyResponse.json();
                        errorText = JSON.stringify(errorData);
                    } catch (e) {
                        errorText = await proxyResponse.text();
                    }
                    console.error('Proxy error response:', proxyResponse.status, errorText);
                    throw new Error('Proxy request failed: ' + proxyResponse.status + ' - ' + errorText);
                }
                
                const reportData = await proxyResponse.json();
      
                
                // The API can wrap the items in different shapes — pick whichever is present
                let apiItems = [];
                if (reportData.data && Array.isArray(reportData.data.items)) {
                    apiItems = reportData.data.items;
                } else if (Array.isArray(reportData)) {
                    apiItems = reportData;
                } else if (reportData.items && Array.isArray(reportData.items)) {
                    apiItems = reportData.items;
                } else if (reportData.stock && Array.isArray(reportData.stock)) {
                    apiItems = reportData.stock;
                }
                
                // Nothing came back from the API: reset all comparison widgets and stop
                if (apiItems.length === 0) {
                    updateApiStatus('error', 'No data returned from API');
                    document.getElementById('comparisonSummary').style.display = 'grid';
                    document.getElementById('totalProducts').textContent = '0';
                    document.getElementById('matchingItems').textContent = '0';
                    document.getElementById('mismatchedItems').textContent = '0';
                    document.getElementById('matchRate').textContent = '0%';
                    document.getElementById('totalDifference').textContent = '0';
                    document.getElementById('comparisonToolbar').style.display = 'none';
                    document.getElementById('comparisonTablePanel').style.display = 'none';
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    return;
                }

        // ── LOCAL DATA ────────────────────────────────────────────────────────────
        // Read every row of the local report table (the one rendered by the controller).
        // These rows are the backbone of the comparison: the table is driven by them,
        // not by the API response.
        // Each row element carries its numbers in data-* attributes (see the Blade
        // <tr> above): data-product-id, data-product-name, data-unit, data-category,
        // data-opening-stock, data-closing-stock, data-received-qty, data-qty.
        const localProducts = [];
        const localRows = document.querySelectorAll('table.report-table tbody tr[data-product-id]');
        localRows.forEach((row, index) => {
            const productId = row.dataset.productId || '';
            if (!productId) {
                return;
            }
            // Fall back to name-based unit detection when the product has no unit set
            const name = row.dataset.productName || '';
            const rowUnit = (row.dataset.unit || '').toString().trim().toUpperCase();
            const rowCategory = (row.dataset.category || '').toString().trim();
            const rowShopId = (row.dataset.shopId || '').toString().trim();
            localProducts.push({
                productId: productId,
                name: name,
                unit: rowUnit || detectUnit(name).toUpperCase(),
                category: rowCategory,
                shopId: rowShopId,
                opening: parseInt(row.dataset.openingStock || 0),
                closing: parseInt(row.dataset.closingStock || 0),
                received: parseInt(row.dataset.receivedQty || 0),
                sold: parseInt(row.dataset.soldQty || 0),
                qty: parseInt(row.dataset.closingStock || 0),
                code: row.dataset.productCode || productId,
                rowIndex: index
            });
        });

        // No local rows at all (wrong date/shop): reset every comparison widget and stop
        if (localProducts.length === 0) {
            updateApiStatus('error', 'No local report rows found for the selected date/shop.');
            document.getElementById('comparisonSummary').style.display = 'grid';
            document.getElementById('totalProducts').textContent = '0';
            document.getElementById('matchingItems').textContent = '0';
            document.getElementById('mismatchedItems').textContent = '0';
            document.getElementById('matchRate').textContent = '0%';
            document.getElementById('totalDifference').textContent = '0';
            document.getElementById('tabAll').textContent = '0';
            document.getElementById('tabMatching').textContent = '0';
            document.getElementById('tabMismatched').textContent = '0';
            document.getElementById('tabMissingMain').textContent = '0';
            document.getElementById('tabMissingBackup').textContent = '0';
            document.getElementById('comparisonToolbar').style.display = 'none';
            document.getElementById('comparisonTablePanel').style.display = 'none';
            return;
        }

        // Debug helper: prints the first few local rows so you can verify the data-*
        // values coming from the Blade template (check the browser console)
        localProducts.slice(0, 3).forEach((product, idx) => {
            console.log('Local row ' + idx + ':', {
                productId: product.productId,
                name: product.name,
                category: product.category,
                opening: product.opening,
                closing: product.closing,
                received: product.received,
                qty: product.qty,
                code: product.code
            });
        });

        // ── NAME MATCHING HELPERS ────────────────────────────────────────────────
        // Local and MainStore names rarely match exactly (different case, "X - CTN"
        // suffixes, extra spaces). These helpers build comparable keys for matching.

        // Lowercases, collapses spaces and strips unit suffixes like "- CTN", "- PCS"
        function normalizeProductName(name) {
            if (!name) return '';
            let normalized = name.toLowerCase().trim();
            normalized = normalized.replace(/\s+/g, ' ');
            normalized = normalized.replace(/\s*-\s*(ctn|carton|cartons|ctns|pc|pcs)\b.*/i, '').trim();
            return normalized;
        }

        // Only lowercases and trims — keeps the full name intact but strips unit suffixes
        // like "- CTN", "- PC", "- PCS" since the API doesn't include them
        function exactProductName(name) {
            if (!name) return '';
            let n = name.toLowerCase().trim();
            n = n.replace(/\s+/g, ' ');
            n = n.replace(/\s*-\s*(ctn|carton|cartons|ctns|pc|pcs)\b.*/i, '').trim();
            return n;
        }

        // The MainStore item id; local products store it in products.product_id,
        // so matching on id is the most reliable link between the two systems
        function getApiItemId(item) {
            return (item.item_id || item.id || '').toString().trim();
        }

        // The MainStore item name (the API sometimes uses a different field name)
        function getApiItemName(item) {
            return (item.item_name || item.name || item.product_name || item.Item_Name || item.product || '').trim();
        }

        // Drop API rows without a product name — they cannot be matched to anything
        const filteredApiItems = apiItems.filter(item => {
            const rawName = getApiItemName(item);
            return rawName !== '';
        });

        console.log('API items count:', apiItems.length);
        console.log('Filtered API items count:', filteredApiItems.length);
        console.log('Local products count:', localProducts.length);

        // ── API LOOKUP MAPS ──────────────────────────────────────────────────────
        // Index the API items by id and by id|category so a local row can
        // find its MainStore counterpart in a single lookup instead of a full scan.
        // Matching on id alone merges same-id items across categories (CTN vs PC);
        // matching on id|category keeps them separate.
        const apiById = {};
        const apiByIdCategory = {};
        filteredApiItems.forEach((item) => {
            const itemId = getApiItemId(item);
            const itemCat = (item.category || item.item_category || item.product_category || '').toString().trim().toUpperCase();
            if (!itemId) {
                return;
            }
            // Store all API items per id
            if (!apiById[itemId]) {
                apiById[itemId] = [];
            }
            apiById[itemId].push(item);
            if (itemCat) {
                const key = itemId + '|' + itemCat;
                if (!apiByIdCategory[key]) {
                    apiByIdCategory[key] = item;
                }
            }
        });

        // ── MATCH A LOCAL ROW TO ITS API ITEM ────────────────────────────────────
        // Match by MainStore item id AND category — keeps same-id items separate
        // (e.g. KISKID BABY DIAPER L10 - CTN vs - PC) instead of merging them.
        // If category is null/empty on the local item, fall back to id-only matching.
        // Tracks consumed API items so same-id variants each get their own row.
        const matchedApiKeys = new Set();

        function findApiItemForLocal(local) {
            if (!local.productId) {
                return null;
            }
            const localCategory = (local.category || '').toString().trim().toUpperCase();

            // Try id+category first (most precise) — only when category is present
            if (localCategory) {
                const catKey = local.productId + '|' + localCategory;
                const catItem = apiByIdCategory[catKey];
                if (catItem && !matchedApiKeys.has(catKey)) {
                    const idItems = apiById[local.productId];
                    const idx = idItems ? idItems.indexOf(catItem) : -1;
                    if (idx >= 0) {
                        matchedApiKeys.add(local.productId + '|idx_' + idx);
                    }
                    matchedApiKeys.add(catKey);
                    return catItem;
                }
            }

            // Fall back to id-only match (used when category is null/empty, or when
            // id+category match was already consumed by another variant)
            const idItems = apiById[local.productId];
            if (!idItems || !idItems.length) {
                return null;
            }
            // Distribute among multiple API items for the same id
            for (let i = 0; i < idItems.length; i++) {
                const idxKey = local.productId + '|idx_' + i;
                if (!matchedApiKeys.has(idxKey)) {
                    matchedApiKeys.add(idxKey);
                    return idItems[i];
                }
            }
            // All id-items already consumed: return first as fallback
            return idItems[0];
        }

        // ── BUILD THE COMPARISON ─────────────────────────────────────────────────
        // Walk every local product, find its API twin and compute the differences.
        // Differences are always "API value minus local value": positive means the
        // MainStore is higher, negative means the local database is higher.
        comparisonData = [];
        localProducts.forEach((local, idx) => {
            const apiItem = findApiItemForLocal(local);

            // MainStore values (0 when there is no API match)
            const apiOpening = apiItem ? parseInt(apiItem.opening_stock || 0) : 0;
            const apiClosing = apiItem ? parseInt(apiItem.closing_stock || 0) : 0;
            const apiQty = apiItem ? parseInt(apiItem.closing_stock || 0) : 0;
            // MainStore purchases for the day: the API labels this "added" (also try the
            // other field names it has used) — falls back to 0 when absent
            const apiPurchases = apiItem ? parseInt(
                apiItem.added ||
                apiItem.purchases ||
                apiItem.purchased_qty ||
                apiItem.purchase_qty ||
                apiItem.received_qty ||
                apiItem.received ||
                0
            ) : 0;
            const apiSold = apiItem ? parseInt(
                apiItem.sales ||
                apiItem.sold ||
                apiItem.sold_qty ||
                apiItem.soldQty ||
                0
            ) : 0;

            // Local (Backup Store) values
            const localOpening = local.opening;
            const localClosing = local.closing;
            const localQty = local.qty;
            // Local purchases = receivings only for mainstore (id 7).
            // For all other shops, receivings are treated as sold (received = sold),
            // so purchases = 0 and sold = received qty.
            const isMainStore = local.shopId == '7';
            const localPurchases = isMainStore ? local.received : 0;
            const localSold = isMainStore ? local.sold : local.received;

            // Opening/Closing/Qty differences (API - local)
            const openingDiff = apiOpening - localOpening;
            const closingDiff = apiClosing - localClosing;
            const qtyDiff = apiQty - localQty;
            // Purchases difference (MainStore - local)
            const purchasesDiff = apiPurchases - localPurchases;
            const soldDiff = apiSold - localSold;
            const totalDiff = Math.abs(openingDiff) + Math.abs(closingDiff) + Math.abs(qtyDiff);

            // Row status — one of:
            //   MATCH        -> found in the API and every difference is <= 1
            //   DIFFERENT    -> found in the API but at least one difference is > 1
            //   MISSING-MAIN -> in the local database but not in the MainStore API
            //   MISSING-BACKUP -> kept for the old API-driven flow (currently unused)
            let status = 'MATCH';
            if (!apiItem) {
                status = 'MISSING-MAIN';
            } else if (totalDiff > 1) {
                status = 'DIFFERENT';
            }

            // One entry per local product; renderComparisonTable() reads this array
            comparisonData.push({
                code: local.code,
                name: local.name,
                category: local.category,
                localOpening: localOpening,
                localClosing: localClosing,
                localQty: localQty,
                localPurchases: localPurchases,
                localSold: localSold,
                apiOpening: apiOpening,
                apiClosing: apiClosing,
                apiQty: apiQty,
                apiPurchases: apiPurchases,
                apiSold: apiSold,
                openingDiff: openingDiff,
                closingDiff: closingDiff,
                qtyDiff: qtyDiff,
                purchasesDiff: purchasesDiff,
                soldDiff: soldDiff,
                status: status
            });

            if (idx < 10) {
                console.log('Compared:', local.name, 'status:', status, 'apiMatch:', !!apiItem);
            }
        });

        // Draw the table with the freshly built data (no filter, no search)
        renderComparisonTable();

        console.log('Comparison data length:', comparisonData.length);
        console.log('Comparison data sample:', comparisonData.slice(0, 3));

        // ── SUMMARY CARDS + FILTER TAB COUNTS ────────────────────────────────────
        const totalProducts = comparisonData.length;
        const matching = comparisonData.filter(item => item.status === 'MATCH').length;
        const mismatched = comparisonData.filter(item => item.status === 'DIFFERENT').length;
        const missingMain = comparisonData.filter(item => item.status === 'MISSING-MAIN').length;
        const missingBackup = comparisonData.filter(item => item.status === 'MISSING-BACKUP').length;
        // Sum of the absolute differences across all three columns
        const totalDifference = comparisonData.reduce((sum, item) => sum + Math.abs(item.openingDiff) + Math.abs(item.closingDiff) + Math.abs(item.qtyDiff), 0);
        const matchRate = totalProducts > 0 ? ((matching / totalProducts) * 100).toFixed(1) : 0;

        document.getElementById('totalProducts').textContent = totalProducts;
        document.getElementById('matchingItems').textContent = matching;
        document.getElementById('mismatchedItems').textContent = mismatched;
        document.getElementById('matchRate').textContent = matchRate + '%';
        document.getElementById('totalDifference').textContent = totalDifference;
        document.getElementById('matchingPercent').textContent = '(' + matchRate + '%)';
        document.getElementById('mismatchedPercent').textContent = '(' + (totalProducts > 0 ? ((mismatched / totalProducts) * 100).toFixed(1) : 0) + '%)';

        document.getElementById('tabAll').textContent = totalProducts;
        document.getElementById('tabMatching').textContent = matching;
        document.getElementById('tabMismatched').textContent = mismatched;
        document.getElementById('tabMissingMain').textContent = missingMain;
        document.getElementById('tabMissingBackup').textContent = missingBackup;

        // Reveal the results panel and stamp the chosen dates into the table headers
        document.getElementById('comparisonSummary').style.display = 'grid';
        document.getElementById('comparisonToolbar').style.display = 'flex';
        document.getElementById('comparisonTablePanel').style.display = 'block';
        document.getElementById('mainStoreDateLabel').textContent = mainDate;
        document.getElementById('backupStoreDateLabel').textContent = backupDate;

        updateApiStatus('success', 'Comparison completed: ' + totalProducts + ' products');

    // Aborts and HTTP failures land here
    } catch (error) {
        if (error.name === 'AbortError') {
            console.error('API Comparison aborted:', error.reason || 'Request was cancelled');
            if (!isCancelled) {
                updateApiStatus('error', 'Request timed out or was cancelled. Please try again.');
            }
        } else {
            console.error('API Comparison Error:', error);
            updateApiStatus('error', 'Error: ' + error.message);
        }
    } finally {
        // Always restore the button so it can be clicked again
        btn.disabled = false;
        btn.innerHTML = originalText;
        isCancelled = true;
    }
}

    // ── RENDER THE COMPARISON TABLE ─────────────────────────────────────────────
    // Reads the global comparisonData array (built by runApiComparison) and writes
    // one <tr> per row. Optionally narrowed by status filter, unit filter and search.
    //   filter values: all | matching | mismatched | missing-main | missing-backup
    // Column order per row: #, Name,
    //   apiOpening, apiPurchases, apiClosing, apiQty,       (Main Store / API)
    //   localOpening, localPurchases, localClosing, localQty, (Backup Store / local)
    //   openingDiff, purchasesDiff, closingDiff, qtyDiff,   (API - local)
    //   status badge
    function renderComparisonTable(filter = 'all', searchTerm = '') {
        const tbody = document.getElementById('comparisonTableBody');
        tbody.innerHTML = '';

        console.log('renderComparisonTable called with filter:', filter, 'searchTerm:', searchTerm);
        console.log('comparisonData length:', comparisonData.length);

        let filteredData = comparisonData;

        // Filter by status (the tabs above the table)
        if (filter === 'matching') {
            filteredData = filteredData.filter(item => item.status === 'MATCH');
        } else if (filter === 'mismatched') {
            filteredData = filteredData.filter(item => item.status === 'DIFFERENT');
        } else if (filter === 'missing-backup') {
            filteredData = filteredData.filter(item => item.status === 'MISSING-BACKUP');
        } else if (filter === 'missing-main') {
            filteredData = filteredData.filter(item => item.status === 'MISSING-MAIN');
        }

        // Filter by unit (CTN / PCS tabs)
        if (currentUnitFilter && currentUnitFilter !== 'all') {
            filteredData = filteredData.filter(item => item.category === currentUnitFilter);
        }

        // Text search on product name or code
        if (searchTerm) {
            const term = searchTerm.toLowerCase();
            filteredData = filteredData.filter(item =>
                item.name.toLowerCase().includes(term) ||
                item.code.toLowerCase().includes(term)
            );
        }

        // Draw one <tr> per filtered row
        filteredData.forEach((item, index) => {
            const row = document.createElement('tr');
            row.dataset.status = item.status;

            if (index < 5) {
                console.log('Rendering row ' + index + ':', {
                    name: item.name,
                    localOpening: item.localOpening,
                    localPurchases: item.localPurchases,
                    localClosing: item.localClosing,
                    localQty: item.localQty,
                    apiOpening: item.apiOpening,
                    apiPurchases: item.apiPurchases,
                    apiClosing: item.apiClosing,
                    apiQty: item.apiQty,
                    status: item.status
                });
            }

            // Status pill: green MATCH / red DIFFERENT / amber MISSING
            let statusBadge = '';
            if (item.status === 'MATCH') {
                statusBadge = '<span class="comparison-badge match">MATCH</span>';
            } else if (item.status === 'DIFFERENT') {
                statusBadge = '<span class="comparison-badge mismatch">DIFFERENT</span>';
            } else {
                statusBadge = '<span class="comparison-badge missing">MISSING</span>';
            }

            // Difference colouring: red when the API is higher, green when local is
            // higher, grey when equal
            const diffClass = (val) => {
                if (val > 0) return 'diff-positive';
                if (val < 0) return 'diff-negative';
                return 'diff-zero';
            };

            // Row markup — cell order must match the <thead> above
            row.innerHTML = `
                <td style="color:var(--slate-400);font-size:0.78rem;">${index + 1}</td>
                <td><span class="product-name">${escapeHtml(item.name)}</span></td>
                <td><span class="mono">${item.apiOpening}</span></td>
                <td><span class="mono" style="color:var(--emerald);font-weight:600;">${item.apiPurchases}</span></td>
                <td><span class="mono" style="color:var(--rose);font-weight:600;">${item.apiSold}</span></td>
                <td><span class="mono">${item.apiClosing}</span></td>
                <td><span class="mono" style="font-weight:600;">${item.apiQty}</span></td>
                <td><span class="mono">${item.localOpening}</span></td>
                <td><span class="mono" style="color:var(--emerald);font-weight:600;">${item.localPurchases}</span></td>
                <td><span class="mono" style="color:var(--rose);font-weight:600;">${item.localSold}</span></td>
                <td><span class="mono">${item.localClosing}</span></td>
                <td><span class="mono" style="font-weight:600;">${item.localQty}</span></td>
                <td><span class="mono ${diffClass(item.openingDiff)}">${item.openingDiff > 0 ? '+' : ''}${item.openingDiff}</span></td>
                <td><span class="mono ${diffClass(item.purchasesDiff)}">${item.purchasesDiff > 0 ? '+' : ''}${item.purchasesDiff}</span></td>
                <td><span class="mono ${diffClass(item.soldDiff)}">${item.soldDiff > 0 ? '+' : ''}${item.soldDiff}</span></td>
                <td><span class="mono ${diffClass(item.closingDiff)}">${item.closingDiff > 0 ? '+' : ''}${item.closingDiff}</span></td>
                <td><span class="mono ${diffClass(item.qtyDiff)}">${item.qtyDiff > 0 ? '+' : ''}${item.qtyDiff}</span></td>
                <td>${statusBadge}</td>

            `;
            tbody.appendChild(row);
        });
    }

    // Status tab clicked: remember the filter, highlight the tab, re-render
    function filterTable(filter) {
        currentFilter = filter;
        document.querySelectorAll('.comparison-tab[data-filter]').forEach(tab => {
            tab.classList.remove('active');
            if (tab.dataset.filter === filter) {
                tab.classList.add('active');
            }
        });
        const searchTerm = document.getElementById('comparisonSearch').value;
        renderComparisonTable(filter, searchTerm);
    }

    // Unit tab clicked (All / CTN / PCS): re-render keeping the current status filter
    function filterByUnit(unit) {
        currentUnitFilter = unit;
        document.querySelectorAll('.comparison-tab[data-unit-filter]').forEach(tab => {
            tab.classList.remove('active');
            if (tab.dataset.unitFilter === category) {
                tab.classList.add('active');
            }
        });
        const searchTerm = document.getElementById('comparisonSearch').value;
        renderComparisonTable(currentFilter, searchTerm);
    }

    // Search box: re-render with the current status + unit filters
    function searchTable() {
        const searchTerm = document.getElementById('comparisonSearch').value;
        renderComparisonTable(currentFilter, searchTerm);
    }

    // Download the full comparisonData array as CSV (exports all rows, not the filtered view)
    function exportComparison() {
        if (comparisonData.length === 0) {
            alert('No data to export');
            return;
        }

        let csv = 'Product Code,Product Name,Unit,Local Opening,Local Purchases,Local Sold,Local Closing,Local Qty,API Opening,API Purchases,API Sold,API Closing,API Qty,Opening Diff,Purchases Diff,Sold Diff,Closing Diff,Qty Diff,Status\n';

        comparisonData.forEach(item => {
            csv += `"${item.code}","${item.name}","${item.category}",${item.localOpening},${item.localPurchases},${item.localSold},${item.localClosing},${item.localQty},${item.apiOpening},${item.apiPurchases},${item.apiSold},${item.apiClosing},${item.apiQty},${item.openingDiff},${item.purchasesDiff},${item.soldDiff},${item.closingDiff},${item.qtyDiff},${item.status}\n`;
        });

        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'stock-comparison-' + new Date().toISOString().split('T')[0] + '.csv';
        a.click();
        window.URL.revokeObjectURL(url);
    }

function viewComparisonItem(code, name) {
    alert('View details for: ' + name + ' (' + code + ')');
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Past Sales Functions
let pendingReturnId = null;

document.getElementById('pastSalesModal').addEventListener('shown.bs.modal', function () {
    loadPastSales('');
    document.getElementById('pastSalesSearch').focus();
});

document.getElementById('pastSalesSearch').addEventListener('input', function (e) {
    loadPastSales(e.target.value.trim());
});

async function loadPastSales(q) {
    var el = document.getElementById('pastSalesResults');
    el.innerHTML = '<div class="dd-loading"><?php echo app('translator')->get('messages.searching'); ?></div>';
    try {
        var url = q ? '<?php echo e(url('searchSales')); ?>?search=' + encodeURIComponent(q) : '<?php echo e(url('searchSales')); ?>';
        var response = await fetch(url);
        if (!response.ok) throw new Error('HTTP error! status: ' + response.status);
        var data = await response.json();
        if (!data.sales || !data.sales.length) {
            el.innerHTML = '<div class="dd-empty"><?php echo app('translator')->get('messages.no_past_sales_found'); ?></div>';
            return;
        }
        el.innerHTML = data.sales.map(function (s) {
            var d = new Date(s.created_at);
            var dt = d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
            var stBadge = s.status === 'Debt'
                ? '<span class="dd-badge dd-badge-offer"><?php echo app('translator')->get('messages.credit'); ?></span>'
                : '<span class="dd-badge dd-badge-price"><?php echo app('translator')->get('messages.paid'); ?></span>';
            return '<div class="dd-item" onclick="selectPastSale(\'' + s.sales_id + '\',\'' + escHtml(s.cName || 'Unknown') + '\',' + s.totalAmount + ',' + s.totalQuantity + ',\'' + dt + '\')">' +
                '<div class="dd-item-name">' + escHtml(s.cName || '<?php echo app('translator')->get('messages.unknown_customer'); ?>') + ' ' + stBadge + '</div>' +
                '<div class="dd-item-meta">' +
                    '<span><i class="bi bi-calendar3"></i> ' + dt + '</span>' +
                    '<span><i class="bi bi-box-seam"></i> ' + s.totalQuantity + ' <?php echo app('translator')->get('messages.order_plural'); ?></span>' +
                    '<span class="dd-badge dd-badge-price">' + Number(s.totalAmount).toLocaleString() + ' Tsh</span>' +
                '</div>' +
            '</div>';
        }).join('');
    } catch (error) {
        console.error('Error loading past sales:', error);
        el.innerHTML = '<div class="dd-empty" style="color:var(--rose);"><?php echo app('translator')->get('messages.error_loading_sales'); ?> ' + error.message + '</div>';
    }
}

function selectPastSale(id, name, total, qty, date) {
    pendingReturnId = id;
    var det = document.getElementById('returnSaleDetails');
    det.innerHTML =
        '<div class="cust-row"><span class="cust-key"><?php echo app('translator')->get('messages.customer'); ?></span><span class="cust-val">' + name + '</span></div>' +
        '<div class="cust-row"><span class="cust-key"><?php echo app('translator')->get('messages.date'); ?></span><span class="cust-val">' + date + '</span></div>' +
        '<div class="cust-row"><span class="cust-key"><?php echo app('translator')->get('messages.total'); ?></span><span class="cust-val">' + Number(total).toLocaleString() + ' Tsh</span></div>' +
        '<div class="cust-row"><span class="cust-key"><?php echo app('translator')->get('messages.items'); ?></span><span class="cust-val">' + qty + '</span></div>';
    det.style.display = '';
    new bootstrap.Modal(document.getElementById('confirmReturnModal')).show();
}

document.getElementById('confirmReturnBtn').addEventListener('click', async function () {
    if (!pendingReturnId) return;
    var btn = document.getElementById('confirmReturnBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> <?php echo app('translator')->get('messages.processing'); ?>';
    try {
        var fd = new FormData();
        fd.append('sales_id', pendingReturnId);
        var data = await (await fetch('<?php echo e(url('returnSaleToOrder')); ?>', {
            method: 'POST',
            body: fd,
            headers: {'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'}
        })).json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('confirmReturnModal'))?.hide();
            bootstrap.Modal.getInstance(document.getElementById('pastSalesModal'))?.hide();
            window.location.href = '<?php echo e(url('newOrder')); ?>';
        } else {
            alert('Error: ' + data.message);
        }
    } catch (e) {
        alert('Error: ' + e.message);
    }
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> <?php echo app('translator')->get('messages.confirm'); ?>';
    pendingReturnId = null;
});

function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}
</script>
</body>
</html><?php /**PATH C:\Users\HomePC\Music\leruma pos\resources\views/itemsReport.blade.php ENDPATH**/ ?>