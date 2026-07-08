<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} — Employee Details</title>
    @include("links")
    
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:         #0B1E3D;
            --navy-mid:     #112952;
            --navy-light:   #1A3A6B;
            --amber:        #F59E0B;
            --amber-pale:   #FEF3C7;
            --emerald:      #059669;
            --emerald-pale: #D1FAE5;
            --rose:         #E11D48;
            --rose-pale:    #FFE4E6;
            --violet:       #7C3AED;
            --violet-pale:  #EDE9FE;
            --sky:          #0284C7;
            --sky-pale:     #E0F2FE;
            --slate-50:     #F8FAFC;
            --slate-100:    #F1F5F9;
            --slate-200:    #E2E8F0;
            --slate-300:    #CBD5E1;
            --slate-400:    #94A3B8;
            --slate-500:    #64748B;
            --slate-600:    #475569;
            --slate-700:    #334155;
            --slate-800:    #1E293B;
            --white:        #FFFFFF;
            --font: 'Sora', system-ui, sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --r: 8px; --r-lg: 13px; --r-xl: 16px;
        }

        body { font-family: var(--font); background: #ECF0F8; color: var(--slate-800); min-height: 100vh; font-size: 14px; line-height: 1.6; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }

        .wrap { padding: 1.5rem 1.75rem 3rem; }

        @keyframes fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:translateY(0); } }
        .au  { animation: fadeUp 0.38s ease both; }
        .au1 { animation-delay:.04s; } .au2 { animation-delay:.10s; }
        .au3 { animation-delay:.16s; } .au4 { animation-delay:.22s; }
        .au5 { animation-delay:.28s; }

        /* PAGE HEADER */
        .pg-header {
            background: var(--navy); border-radius: var(--r-xl);
            padding: 1.2rem 1.6rem; margin-bottom: 1.4rem;
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; flex-wrap: wrap; position: relative; overflow: hidden;
        }
        .pg-header::before { content:''; position:absolute; top:-50px; right:-30px; width:180px; height:180px; border-radius:50%; background:var(--navy-light); opacity:.45; pointer-events:none; }
        .pg-header::after  { content:''; position:absolute; bottom:-55px; right:100px; width:120px; height:120px; border-radius:50%; background:var(--amber); opacity:.07; pointer-events:none; }
        .pg-left { display:flex; align-items:center; gap:10px; position:relative; z-index:1; }
        .back-btn { width:34px; height:34px; border-radius:var(--r); background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.15); display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,.7); cursor:pointer; flex-shrink:0; transition:all .15s; }
        .back-btn:hover { background:rgba(255,255,255,.16); color:var(--white); }
        .header-icon { width:40px; height:40px; border-radius:var(--r); background:var(--amber); display:flex; align-items:center; justify-content:center; font-size:18px; color:var(--navy); flex-shrink:0; }
        .pg-title-text h1 { font-size:16px; font-weight:700; color:var(--white); letter-spacing:-.2px; }
        .pg-title-text p  { font-size:12px; color:rgba(255,255,255,.45); margin-top:1px; }

        /* LAYOUT GRID */
        .page-grid { display:grid; grid-template-columns: 280px 1fr; gap:1.25rem; align-items:start; }
        @media(max-width:900px) { .page-grid { grid-template-columns:1fr; } }

        /* PROFILE CARD */
        .profile-card {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl); overflow: hidden;
            box-shadow: 0 1px 4px rgba(11,30,61,.05);
            position: sticky; top: 1.5rem;
        }
        .profile-banner {
            height: 80px; background: var(--navy);
            position: relative;
        }
        .profile-banner::after {
            content:''; position:absolute; inset:0;
            background: linear-gradient(135deg, var(--navy-light) 0%, transparent 70%);
        }
        .profile-avatar-wrap {
            display: flex; flex-direction: column; align-items: center;
            padding: 0 1.25rem 1.25rem;
            margin-top: -44px; position: relative; z-index: 1;
        }
        .profile-avatar {
            width: 88px; height: 88px; border-radius: 50%;
            border: 4px solid var(--white);
            background: var(--navy-mid);
            object-fit: cover; display: flex; align-items: center; justify-content: center;
            font-size: 28px; font-weight: 700; color: rgba(255,255,255,.85);
            box-shadow: 0 4px 16px rgba(11,30,61,.2); flex-shrink: 0;
            overflow: hidden;
        }
        .profile-name { font-size: 15px; font-weight: 700; color: var(--navy); margin-top: .875rem; text-align: center; }
        .profile-role {
            display: inline-flex; align-items: center; gap: 5px;
            margin-top: 5px; padding: 4px 12px; border-radius: 20px;
            background: var(--amber-pale); color: #92400e;
            font-size: 11.5px; font-weight: 700;
        }

        .profile-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 1px; background: var(--slate-200); margin-top: 1rem; }
        .ps-cell { background: var(--white); padding: .75rem 1rem; text-align: center; }
        .ps-val   { font-size: 17px; font-weight: 700; color: var(--navy); font-family: var(--mono); }
        .ps-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--slate-400); margin-top: 2px; }

        .profile-meta { padding: 1rem 1.25rem; }
        .meta-row { display: flex; align-items: center; gap: 9px; font-size: 12.5px; color: var(--slate-600); padding: 5px 0; border-bottom: 1px solid var(--slate-100); }
        .meta-row:last-child { border-bottom: none; }
        .meta-row i { width: 16px; color: var(--slate-400); font-size: 13px; flex-shrink: 0; }

        .update-photo-btn {
            width: calc(100% - 2.5rem); margin: 0 1.25rem 1.25rem;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 8px; border: 1.5px dashed var(--slate-300);
            border-radius: var(--r); background: transparent;
            font-family: var(--font); font-size: 12.5px; font-weight: 600;
            color: var(--slate-500); cursor: pointer; transition: all .15s;
        }
        .update-photo-btn:hover { border-color: var(--navy-light); color: var(--navy); background: rgba(26,58,107,.04); }

        /* FORM SECTIONS */
        .form-section {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl); overflow: hidden;
            box-shadow: 0 1px 4px rgba(11,30,61,.05);
            margin-bottom: 1.25rem;
        }

        .section-head {
            display: flex; align-items: center; gap: 9px;
            padding: .875rem 1.4rem; border-bottom: 1.5px solid var(--slate-200);
            background: var(--slate-50);
        }
        .section-head-icon { width: 28px; height: 28px; border-radius: var(--r); display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0; }
        .shi-amber   { background: var(--amber-pale);   color: #92400e; }
        .shi-violet  { background: var(--violet-pale);  color: var(--violet); }
        .shi-sky     { background: var(--sky-pale);     color: var(--sky); }
        .shi-rose    { background: var(--rose-pale);    color: var(--rose); }
        .shi-emerald { background: var(--emerald-pale); color: var(--emerald); }
        .section-title { font-size: 13.5px; font-weight: 700; color: var(--navy); }

        .section-body { padding: 1.4rem; }

        /* Fields */
        .field-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
        .field-grid-2:last-child { margin-bottom: 0; }
        @media(max-width:640px) { .field-grid-2 { grid-template-columns: 1fr; } }

        .field { display: flex; flex-direction: column; gap: 5px; }
        .field-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--slate-500); }
        .field-label .req { color: var(--rose); }
        .field-input, .field-select {
            font-family: var(--font); font-size: 13.5px; padding: 9px 12px;
            border: 1.5px solid var(--slate-200); border-radius: var(--r);
            background: var(--white); color: var(--slate-800); outline: none;
            transition: border-color .18s, box-shadow .18s;
        }
        .field-input:focus, .field-select:focus { border-color: var(--navy-light); box-shadow: 0 0 0 3px rgba(26,58,107,.1); }
        .field-input::placeholder { color: var(--slate-400); }
        .field-select { appearance: none; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; padding-right: 2.25rem; }

        /* PERMISSIONS PANEL */
        .perms-block { background: var(--slate-50); border: 1.5px solid var(--slate-200); border-radius: var(--r-lg); padding: 1rem 1.1rem; margin-bottom: 1rem; }
        .perms-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--slate-400); margin-bottom: .5rem; }
        .perms-hint  { font-size: 11.5px; color: var(--slate-400); margin-bottom: .65rem; display: flex; align-items: center; gap: 5px; }

        /* Shop Checkbox Styles */
        .shop-checkbox-item {
            display: flex;
            align-items: center;
            padding: 12px;
            border-bottom: 1px solid var(--slate-200);
            transition: background 0.15s;
            background: var(--white);
        }
        .shop-checkbox-item:hover {
            background: var(--slate-50);
        }
        .shop-checkbox {
            width: 18px;
            height: 18px;
            margin-right: 12px;
            cursor: pointer;
            accent-color: var(--amber);
            flex-shrink: 0;
        }
        .shop-info {
            flex: 1;
        }
        .shop-name {
            font-weight: 600;
            color: var(--navy);
            font-size: 13px;
            margin-bottom: 2px;
        }
        .shop-id {
            font-size: 11px;
            color: var(--slate-400);
        }
        .shop-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            margin-left: 8px;
        }
        .badge-primary {
            background: var(--emerald);
            color: var(--white);
        }
        .badge-success {
            background: var(--emerald-pale);
            color: var(--emerald);
        }
        .shops-scroll {
            max-height: 320px;
            overflow-y: auto;
            border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg);
            background: var(--white);
            margin-bottom: 1rem;
        }

        .badge-list {
            min-height: 64px; max-height: 160px; overflow-y: auto;
            display: flex; flex-wrap: wrap; gap: 6px; align-content: flex-start;
            overscroll-behavior: contain; scroll-behavior: smooth;
            padding: 4px 0;
        }

        .perm-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 11px; border-radius: 20px;
            background: var(--navy); color: rgba(255,255,255,.85);
            font-size: 11.5px; font-weight: 600; cursor: pointer;
            border: 2px solid transparent; transition: all .15s; user-select: none;
        }
        .perm-badge:hover { opacity: .85; }
        .perm-badge.selected { border-color: var(--amber); box-shadow: 0 0 0 2px rgba(245,158,11,.25); }
        .perm-badge.shop-badge { background: var(--sky); }
        .perm-badge.primary-badge { background: var(--emerald); }

        .empty-badge { font-size: 12.5px; color: var(--slate-400); font-style: italic; padding: 4px 0; }

        .perms-add-row { display: flex; gap: 8px; margin-top: .75rem; flex-wrap: wrap; }
        .perms-add-row .field-select { flex: 1; min-width: 160px; }

        .btn-sm-add { display: inline-flex; align-items: center; gap: 5px; padding: 8px 14px; border-radius: var(--r); background: var(--navy); color: var(--white); font-family: var(--font); font-size: 12.5px; font-weight: 700; border: none; cursor: pointer; transition: all .15s; white-space: nowrap; }
        .btn-sm-add:hover { background: var(--navy-light); }
        .btn-sm-add.amber { background: var(--amber); color: var(--navy); }
        .btn-sm-add.amber:hover { background: #D97706; }

        .btn-sm-del { display: inline-flex; align-items: center; gap: 5px; padding: 8px 14px; border-radius: var(--r); background: var(--rose-pale); color: var(--rose); font-family: var(--font); font-size: 12.5px; font-weight: 700; border: 1.5px solid rgba(225,29,72,.2); cursor: pointer; transition: all .15s; margin-top: .65rem; }
        .btn-sm-del:hover { background: var(--rose); color: var(--white); }

        /* ACTION FOOTER */
        .action-footer { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; padding-top: 1.25rem; border-top: 1.5px solid var(--slate-200); margin-top: 1.25rem; }
        .action-footer-right { display: flex; gap: 8px; flex-wrap: wrap; }

        .btn-save { display: inline-flex; align-items: center; gap: 6px; padding: 10px 22px; border-radius: var(--r); background: var(--amber); color: var(--navy); font-family: var(--font); font-size: 13px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 3px 14px rgba(245,158,11,.3); transition: all .18s; }
        .btn-save:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(245,158,11,.4); }

        .btn-warn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: var(--r); background: var(--amber-pale); color: #92400e; font-family: var(--font); font-size: 13px; font-weight: 700; border: 1.5px solid rgba(245,158,11,.3); cursor: pointer; transition: all .15s; }
        .btn-warn:hover { background: var(--amber); color: var(--navy); }

        .btn-danger { display: inline-flex; align-items: center; gap: 6px; padding: 10px 18px; border-radius: var(--r); background: var(--rose-pale); color: var(--rose); font-family: var(--font); font-size: 13px; font-weight: 700; border: 1.5px solid rgba(225,29,72,.2); cursor: pointer; transition: all .15s; }
        .btn-danger:hover { background: var(--rose); color: var(--white); }
        
        .btn-idcard { display: inline-flex; align-items: center; gap: 6px; padding: 10px 22px; border-radius: var(--r); background: var(--violet); color: var(--white); font-family: var(--font); font-size: 13px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 3px 14px rgba(124,58,237,.3); transition: all .18s; }
        .btn-idcard:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(124,58,237,.4); }

        /* Selected shops display */
        .selected-shops-info {
            background: var(--slate-50);
            padding: 8px 12px;
            border-radius: var(--r);
            margin-top: 10px;
            font-size: 12px;
            color: var(--slate-600);
            text-align: center;
        }

        /* ===== ID CARD MODAL ===== */
        .id-card-modal .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(11, 30, 61, 0.3);
        }

        .id-card-modal .modal-header {
            background: var(--navy);
            padding: 1.2rem 1.5rem;
            border: none;
        }

        .id-card-modal .modal-header h5 {
            color: var(--white);
            font-weight: 700;
            font-family: 'Syne', sans-serif;
        }

        .id-card-modal .modal-header .btn-close {
            filter: invert(1) brightness(0.8);
        }

        .id-card-modal .modal-body {
            padding: 2rem;
            background: var(--slate-50);
        }

        /* ===== ID CARD PREVIEW ===== */
        .id-card-preview {
            max-width: 420px;
            margin: 0 auto;
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(11, 30, 61, 0.15);
            border: 1px solid var(--slate-200);
            position: relative;
        }

        .id-card-header {
            background: var(--navy);
            padding: 1.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .id-card-header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -30%;
            width: 200px;
            height: 200px;
            background: rgba(245, 158, 11, 0.15);
            border-radius: 50%;
        }

        .id-card-header::before {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -20%;
            width: 150px;
            height: 150px;
            background: rgba(245, 158, 11, 0.10);
            border-radius: 50%;
        }

        .id-card-logo {
            position: relative;
            z-index: 1;
        }

        .id-card-logo img {
            height: 40px;
            filter: brightness(0) invert(1);
        }

        .id-card-logo h4 {
            color: var(--white);
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0.25rem 0 0;
            letter-spacing: 2px;
        }

        .id-card-body {
            padding: 1.5rem;
            text-align: center;
        }

        .id-card-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: -50px auto 0.75rem;
            border: 4px solid var(--white);
            background: var(--navy-mid);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: rgba(255,255,255,.85);
            box-shadow: 0 4px 16px rgba(11, 30, 61, 0.2);
            position: relative;
            z-index: 2;
            overflow: hidden;
            object-fit: cover;
        }

        .id-card-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .id-card-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--navy);
            margin: 0.5rem 0 0.25rem;
        }

        .id-card-role {
            display: inline-block;
            padding: 0.25rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            background: var(--amber-pale);
            color: #92400e;
        }

        .id-card-details {
            margin: 1rem 0;
            text-align: left;
            padding: 0 0.5rem;
        }

        .id-card-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.4rem 0;
            border-bottom: 1px solid var(--slate-100);
            font-size: 0.8rem;
        }

        .id-card-detail-row:last-child {
            border-bottom: none;
        }

        .id-card-detail-label {
            color: var(--slate-500);
            font-weight: 500;
        }

        .id-card-detail-value {
            color: var(--slate-800);
            font-weight: 600;
        }

        .id-card-footer {
            background: var(--slate-50);
            padding: 0.75rem;
            text-align: center;
            border-top: 1px solid var(--slate-200);
            font-size: 0.65rem;
            color: var(--slate-400);
            letter-spacing: 0.5px;
        }

        /* ===== ID CARD WATERMARK ===== */
        .id-card-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 4rem;
            font-weight: 900;
            color: rgba(255, 255, 255, 0.06);
            pointer-events: none;
            white-space: nowrap;
            font-family: 'Syne', sans-serif;
        }

        /* ===== ID CARD ACTIONS ===== */
        .id-card-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .id-card-actions .btn {
            min-width: 120px;
            justify-content: center;
        }

        .btn-idcard-print {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 22px;
            border-radius: var(--r);
            background: var(--navy);
            color: var(--white);
            font-family: var(--font);
            font-size: 13px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all .18s;
        }

        .btn-idcard-print:hover {
            background: var(--navy-light);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(11, 30, 61, 0.3);
        }

        .btn-idcard-download {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 22px;
            border-radius: var(--r);
            background: var(--emerald);
            color: var(--white);
            font-family: var(--font);
            font-size: 13px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all .18s;
        }

        .btn-idcard-download:hover {
            background: #047857;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(5, 150, 105, 0.4);
        }

        /* ===== PRINT STYLES FOR ID CARD ===== */
        @media print {
            .id-card-modal .modal-dialog {
                max-width: 100%;
                margin: 0;
            }
            .id-card-modal .modal-content {
                box-shadow: none;
                border: none;
                border-radius: 0;
            }
            .id-card-modal .modal-header,
            .id-card-actions {
                display: none !important;
            }
            .id-card-modal .modal-body {
                padding: 0;
                background: white;
            }
            .id-card-preview {
                box-shadow: none;
                border: 1px solid #ddd;
                max-width: 100%;
            }
            body * {
                visibility: visible !important;
            }
            .modal {
                position: static !important;
                display: block !important;
            }
            .modal-backdrop {
                display: none !important;
            }
        }

        @media(max-width:768px) { 
            .wrap { padding: 1rem; } 
            .id-card-preview { max-width: 100%; }
            .id-card-actions .btn { min-width: 100%; }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @include("sidenav")

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="wrap">

                {{-- Page header --}}
                <div class="pg-header au au1">
                    <div class="pg-left">
                        <button class="back-btn" onclick="history.back()"><i class="bi bi-chevron-left"></i></button>
                        <div class="header-icon"><i class="bi bi-person-fill"></i></div>
                        <div class="pg-title-text">
                            <h1>Employee Details</h1>
                            <p>Manage profile, permissions and shop assignments</p>
                        </div>
                    </div>
                </div>

                <div class="page-grid">

                    {{-- LEFT — Profile card --}}
                    <aside class="au au2">
                        <div class="profile-card">
                            <div class="profile-banner"></div>
                            <div class="profile-avatar-wrap">
                                @if($employee->userImg)
                                    <img class="profile-avatar" src="{{ asset('/public/images/' . $employee->userImg) }}" alt="{{ $employee->name }}">
                                @else
                                    @php
                                        $initials = strtoupper(substr($employee->name ?? 'E', 0, 1));
                                        $parts    = explode(' ', trim($employee->name ?? ''));
                                        if(count($parts)>1) $initials .= strtoupper(substr($parts[1],0,1));
                                    @endphp
                                    <div class="profile-avatar">{{ $initials }}</div>
                                @endif
                                <div class="profile-name">{{ $employee->name ?? 'N/A' }}</div>
                                <span class="profile-role">
                                    <i class="bi bi-shield-fill"></i>
                                    {{ $employee->levelStatus ?? 'N/A' }}
                                </span>
                            </div>

                            @php
                                $employee = $employee;
                                $currentPermissions = $employee->permissions ?? [];
                                if (is_string($currentPermissions)) {
                                    $decoded = json_decode($currentPermissions, true);
                                    $currentPermissions = is_array($decoded) ? $decoded : [];
                                } elseif (!is_array($currentPermissions)) {
                                    $currentPermissions = [];
                                }
                            @endphp
                            <div class="profile-stats">
                                <div class="ps-cell">
                                    <div class="ps-val">{{ count($currentPermissions) }}</div>
                                    <div class="ps-label">Permissions</div>
                                </div>
                                <div class="ps-cell">
                                    <div class="ps-val">{{ $userAccounts->count() }}</div>
                                    <div class="ps-label">Shops</div>
                                </div>
                            </div>

                            <div class="profile-meta">
                                <div class="meta-row"><i class="bi bi-envelope"></i> {{ $employee->email ?? '—' }}</div>
                                <div class="meta-row"><i class="bi bi-phone"></i> {{ $employee->contact ?? '—' }}</div>
                                <div class="meta-row"><i class="bi bi-calendar3"></i>
                                    @if($employee->age) Age {{ $employee->age }} @else No age set @endif
                                </div>
                                <div class="meta-row">
                                    <i class="bi bi-circle-fill" style="font-size:8px; color:{{ $employee->status === 'active' ? 'var(--emerald)' : 'var(--rose)' }};"></i>
                                    {{ ucfirst($employee->status ?? 'unknown') }}
                                </div>
                            </div>

                            <button class="update-photo-btn" onclick="document.getElementById('photo').click()">
                                <i class="bi bi-camera"></i> Update photo
                            </button>
                        </div>
                    </aside>

                    {{-- RIGHT — Forms --}}
                    <div>

                        {{-- Personal Info --}}
                        <div class="form-section au au3">
                            <div class="section-head">
                                <div class="section-head-icon shi-amber"><i class="bi bi-person"></i></div>
                                <span class="section-title">Personal Information</span>
                            </div>
                            <div class="section-body">
                                <form action="{{ url('updateEmployee') }}" method="POST" enctype="multipart/form-data" id="employeeForm">
                                    @csrf
                                    <input type="hidden" name="employeeId" value="{{ $employee->id }}">

                                    <div class="field-grid-2">
                                        <div class="field">
                                            <label class="field-label">Full name <span class="req">*</span></label>
                                            <input type="text" class="field-input" name="name" value="{{ $employee->name ?? '' }}" required>
                                        </div>
                                        <div class="field">
                                            <label class="field-label">Age <span class="req">*</span></label>
                                            <input type="number" class="field-input" name="age" value="{{ $employee->age ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="field-grid-2">
                                        <div class="field">
                                            <label class="field-label">Contact <span class="req">*</span></label>
                                            <input type="text" class="field-input" name="contact" value="{{ $employee->contact ?? '' }}" required>
                                        </div>
                                        <div class="field">
                                            <label class="field-label">Email <span class="req">*</span></label>
                                            <input type="email" class="field-input" name="email" value="{{ $employee->email ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="field-grid-2">
                                        <div class="field">
                                            <label class="field-label">Profile photo</label>
                                            <input type="file" class="field-input" name="photo" id="photo" accept="image/*">
                                        </div>
                                        <div class="field">
                                            <label class="field-label">Role / Level <span class="req">*</span></label>
                                            <select class="field-select" name="levelStatus" required>
                                                @foreach(['Admin','Manager','Seller'] as $role)
                                                <option value="{{ $role }}" {{ ($employee->levelStatus ?? '') == $role ? 'selected' : '' }}>{{ $role }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Permissions --}}
                                    <div class="section-head" style="margin: 1.25rem -1.4rem; border-top:1.5px solid var(--slate-200); border-bottom:1.5px solid var(--slate-200); padding:.875rem 1.4rem; background:var(--slate-50);">
                                        <div class="section-head-icon shi-violet"><i class="bi bi-shield-check"></i></div>
                                        <span class="section-title">Permissions</span>
                                    </div>

                                    <div class="perms-block" style="background:var(--white); border:1.5px solid var(--slate-200);">
                                        <div class="perms-label" style="margin-bottom:.75rem;">Assigned permissions</div>
                                        <div style="border: 1.5px solid #E2E8F0; border-radius: 12px; max-height: 380px; overflow-y: auto; background: white;" id="viewPermissionsContainer">

                                            <!-- Inventory Management -->
                                            <div class="perm-group" style="margin:0; border-bottom:1px solid #E2E8F0;">
                                                <div class="perm-group-head" data-group="inventory" onclick="toggleViewPermGroup(this)">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; cursor:pointer;">
                                                        <div style="display:flex; align-items:center; gap:.5rem;">
                                                            <i class="bi bi-box-seam" style="color:#F59E0B; font-size:1.1rem;"></i>
                                                            <strong style="color:var(--navy);">Inventory Management</strong>
                                                        </div>
                                                        <i class="bi bi-chevron-down" style="color:#94A3B8; transition:transform .2s;"></i>
                                                    </div>
                                                </div>
                                                <div class="perm-group-body" style="padding:0 1rem .75rem 1rem; display:none;">
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_items" id="vperm_view_items" {{ in_array('view_items', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_items">View Items</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_items" id="vperm_manage_items" {{ in_array('manage_items', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_items">Manage Items</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_items" id="vperm_create_items" {{ in_array('create_items', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_create_items">Create Items</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_products" id="vperm_edit_products" {{ in_array('edit_products', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_edit_products">Edit Products</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_products" id="vperm_delete_products" {{ in_array('delete_products', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_delete_products">Delete Products</label></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Item Request Management -->
                                            <div class="perm-group" style="margin:0; border-bottom:1px solid #E2E8F0;">
                                                <div class="perm-group-head" data-group="Request" onclick="toggleViewPermGroup(this)">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; cursor:pointer;">
                                                        <div style="display:flex; align-items:center; gap:.5rem;">
                                                            <i class="bi bi-box-seam" style="color:#F59E0B; font-size:1.1rem;"></i>
                                                            <strong style="color:var(--navy);">Item Request Management</strong>
                                                        </div>
                                                        <i class="bi bi-chevron-down" style="color:#94A3B8; transition:transform .2s;"></i>
                                                    </div>
                                                </div>
                                                <div class="perm-group-body" style="padding:0 1rem .75rem 1rem; display:none;">
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="item_request" id="vperm_item_request" {{ in_array('item_request', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_items">Items Request</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="item_request_date" id="vperm_item_request_date" {{ in_array('item_request_date', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vitem_request_date">Items Request Date</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_item_requests" id="vperm_view_item_requests" {{ in_array('view_item_requests', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_item_requests">View Items Request</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_item_request" id="vperm_manage_item_request" {{ in_array('manage_item_request', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_item_request">Manage Items Request</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_item_request" id="vperm_delete_item_request" {{ in_array('delete_item_request', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_delete_item_request">Delete Items Request</label></div>
                                                </div>
                                            </div> 

                                            <!-- Sales Management -->
                                            <div class="perm-group" style="margin:0; border-bottom:1px solid #E2E8F0;">
                                                <div class="perm-group-head" data-group="sales" onclick="toggleViewPermGroup(this)">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; cursor:pointer;">
                                                        <div style="display:flex; align-items:center; gap:.5rem;">
                                                            <i class="bi bi-cart" style="color:#F59E0B; font-size:1.1rem;"></i>
                                                            <strong style="color:var(--navy);">Sales Management</strong>
                                                        </div>
                                                        <i class="bi bi-chevron-down" style="color:#94A3B8; transition:transform .2s;"></i>
                                                    </div>
                                                </div>
                                                <div class="perm-group-body" style="padding:0 1rem .75rem 1rem; display:none;">
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_sales" id="vperm_view_sales" {{ in_array('view_sales', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_sales">View Sales</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="create_sales" id="vperm_create_sales" {{ in_array('create_sales', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_create_sales">Create Sales</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_invoices" id="vperm_view_invoices" {{ in_array('view_invoices', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_invoices">{{ __('messages.view_invoices') }}</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_sales" id="vperm_manage_sales" {{ in_array('manage_sales', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_sales">Manage Sales</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_sales_date" id="vperm_manage_sales_date" {{ in_array('manage_sales_date', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_sales_date">Alter Sales Date</label></div>
                                                </div>
                                            </div>

                                            <!-- Debts Management -->
                                            <div class="perm-group" style="margin:0; border-bottom:1px solid #E2E8F0;">
                                                <div class="perm-group-head" data-group="debts" onclick="toggleViewPermGroup(this)">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; cursor:pointer;">
                                                        <div style="display:flex; align-items:center; gap:.5rem;">
                                                            <i class="bi bi-receipt" style="color:#F59E0B; font-size:1.1rem;"></i>
                                                            <strong style="color:var(--navy);">Debts Management</strong>
                                                        </div>
                                                        <i class="bi bi-chevron-down" style="color:#94A3B8; transition:transform .2s;"></i>
                                                    </div>
                                                </div>
                                                <div class="perm-group-body" style="padding:0 1rem .75rem 1rem; display:none;">
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_shop_debts" id="vperm_view_shop_debts" {{ in_array('view_shop_debts', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_shop_debts">{{ __('messages.view_shop_debts') }}</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_debts" id="vperm_edit_debts" {{ in_array('edit_debts', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_edit_debts">Edit Shop Debts</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_paid_invoice" id="vperm_manage_paid_invoice" {{ in_array('manage_paid_invoice', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_paid_invoice">{{ __('messages.manage_paid_debts') }}</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="pay_debts" id="vperm_pay_debts" {{ in_array('pay_debts', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_pay_debts">Pay Debts</label></div>
                                                </div>
                                            </div>

                                            <!-- Receivings & Returns -->
                                            <div class="perm-group" style="margin:0; border-bottom:1px solid #E2E8F0;">
                                                <div class="perm-group-head" data-group="receivings" onclick="toggleViewPermGroup(this)">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; cursor:pointer;">
                                                        <div style="display:flex; align-items:center; gap:.5rem;">
                                                            <i class="bi bi-truck" style="color:#F59E0B; font-size:1.1rem;"></i>
                                                            <strong style="color:var(--navy);">Receivings & Returns</strong>
                                                        </div>
                                                        <i class="bi bi-chevron-down" style="color:#94A3B8; transition:transform .2s;"></i>
                                                    </div>
                                                </div>
                                                <div class="perm-group-body" style="padding:0 1rem .75rem 1rem; display:none;">
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_receivings" id="vperm_view_receivings" {{ in_array('view_receivings', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_receivings">{{ __('messages.view_receivings') }}</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="approve_receiving" id="vperm_approve_receiving" {{ in_array('approve_receiving', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_approve_receiving">Approve Receivings</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="undo_receiving" id="vperm_undo_receiving" {{ in_array('undo_receiving', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_undo_receiving">Undo Receivings</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_receiving" id="vperm_delete_receiving" {{ in_array('delete_receiving', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_delete_receiving">Delete Receivings</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="print_receiving" id="vperm_print_receiving" {{ in_array('print_receiving', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_print_receiving">Print Receivings</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="make_return" id="vperm_make_return" {{ in_array('make_return', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_make_return">{{ __('messages.make_return') }}</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_returns" id="vperm_view_returns" {{ in_array('view_returns', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_returns">{{ __('messages.view_returns') }}</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="set_restock_date" id="vperm_set_restock_date" {{ in_array('set_restock_date', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_set_restock_date">Alter Receivings Date</label></div>
                                                </div>
                                            </div>

                                            <!-- Reports -->
                                            <div class="perm-group" style="margin:0; border-bottom:1px solid #E2E8F0;">
                                                <div class="perm-group-head" data-group="reports" onclick="toggleViewPermGroup(this)">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; cursor:pointer;">
                                                        <div style="display:flex; align-items:center; gap:.5rem;">
                                                            <i class="bi bi-graph-up" style="color:#F59E0B; font-size:1.1rem;"></i>
                                                            <strong style="color:var(--navy);">Reports</strong>
                                                        </div>
                                                        <i class="bi bi-chevron-down" style="color:#94A3B8; transition:transform .2s;"></i>
                                                    </div>
                                                </div>
                                                <div class="perm-group-body" style="padding:0 1rem .75rem 1rem; display:none;">
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_reports" id="vperm_view_reports" {{ in_array('view_reports', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_reports">View Reports</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_sales_report" id="vperm_view_sales_report" {{ in_array('view_sales_report', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_sales_report">Sales Report</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_stock_report" id="vperm_view_stock_report" {{ in_array('view_stock_report', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_stock_report">Stock Report</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_shops_report" id="vperm_view_shops_report" {{ in_array('view_shops_report', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_shops_report">Shops Report</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_full_report" id="vperm_view_full_report" {{ in_array('view_full_report', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_full_report">View Full Report</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_full_report" id="vperm_manage_full_report" {{ in_array('manage_full_report', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_full_report">Manage Full Report</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_shop_cash_submit" id="vperm_manage_shop_cash_submit" {{ in_array('manage_shop_cash_submit', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_shop_cash_submit">Shop Cash Submit</label></div>
                                                </div>
                                            </div>

                                            <!-- Customers -->
                                            <div class="perm-group" style="margin:0; border-bottom:1px solid #E2E8F0;">
                                                <div class="perm-group-head" data-group="customers" onclick="toggleViewPermGroup(this)">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; cursor:pointer;">
                                                        <div style="display:flex; align-items:center; gap:.5rem;">
                                                            <i class="bi bi-people" style="color:#F59E0B; font-size:1.1rem;"></i>
                                                            <strong style="color:var(--navy);">Customers</strong>
                                                        </div>
                                                        <i class="bi bi-chevron-down" style="color:#94A3B8; transition:transform .2s;"></i>
                                                    </div>
                                                </div>
                                                <div class="perm-group-body" style="padding:0 1rem .75rem 1rem; display:none;">
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_customers" id="vperm_view_customers" {{ in_array('view_customers', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_customers">View Customers</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="add_customers" id="vperm_add_customers" {{ in_array('add_customers', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_add_customers">Add Customers</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_customers" id="vperm_edit_customers" {{ in_array('edit_customers', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_edit_customers">Edit Customers</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_customers" id="vperm_manage_customers" {{ in_array('manage_customers', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_customers">Manage Customers</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_all_chips" id="vperm_view_all_chips" {{ in_array('view_all_chips', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_all_chips">View Chip</label></div>
                                                </div>
                                            </div>

                                            <!-- Suppliers / Vendors -->
                                            <div class="perm-group" style="margin:0; border-bottom:1px solid #E2E8F0;">
                                                <div class="perm-group-head" data-group="suppliers" onclick="toggleViewPermGroup(this)">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; cursor:pointer;">
                                                        <div style="display:flex; align-items:center; gap:.5rem;">
                                                            <i class="bi bi-building" style="color:#F59E0B; font-size:1.1rem;"></i>
                                                            <strong style="color:var(--navy);">{{ __('messages.suppliers_vendors') }}</strong>
                                                        </div>
                                                        <i class="bi bi-chevron-down" style="color:#94A3B8; transition:transform .2s;"></i>
                                                    </div>
                                                </div>
                                                <div class="perm-group-body" style="padding:0 1rem .75rem 1rem; display:none;">
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_suppliers" id="vperm_view_suppliers" {{ in_array('view_suppliers', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_suppliers">{{ __('messages.view_suppliers') }}</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_supplier_credit" id="vperm_manage_supplier_credit" {{ in_array('manage_supplier_credit', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_supplier_credit">{{ __('messages.manage_supplier_credit') }}</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_suppliers" id="vperm_manage_suppliers" {{ in_array('manage_suppliers', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_suppliers">{{ __('messages.manage_suppliers') }}</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="add_supplier" id="vperm_add_supplier" {{ in_array('add_supplier', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_add_supplier">Add Supplier</label></div>
                                                </div>
                                            </div>

                                            <!-- Expenses -->
                                            <div class="perm-group" style="margin:0; border-bottom:1px solid #E2E8F0;">
                                                <div class="perm-group-head" data-group="expenses" onclick="toggleViewPermGroup(this)">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; cursor:pointer;">
                                                        <div style="display:flex; align-items:center; gap:.5rem;">
                                                            <i class="bi bi-receipt" style="color:#F59E0B; font-size:1.1rem;"></i>
                                                            <strong style="color:var(--navy);">Expenses</strong>
                                                        </div>
                                                        <i class="bi bi-chevron-down" style="color:#94A3B8; transition:transform .2s;"></i>
                                                    </div>
                                                </div>
                                                <div class="perm-group-body" style="padding:0 1rem .75rem 1rem; display:none;">
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_expenses" id="vperm_view_expenses" {{ in_array('view_expenses', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_expenses">View Expenses</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_expenses" id="vperm_manage_expenses" {{ in_array('manage_expenses', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_expenses">Manage Expenses</label></div>
                                                </div>
                                            </div>

                                            <!-- Banking -->
                                            <div class="perm-group" style="margin:0; border-bottom:1px solid #E2E8F0;">
                                                <div class="perm-group-head" data-group="banking" onclick="toggleViewPermGroup(this)">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; cursor:pointer;">
                                                        <div style="display:flex; align-items:center; gap:.5rem;">
                                                            <i class="bi bi-bank" style="color:#F59E0B; font-size:1.1rem;"></i>
                                                            <strong style="color:var(--navy);">Banking</strong>
                                                        </div>
                                                        <i class="bi bi-chevron-down" style="color:#94A3B8; transition:transform .2s;"></i>
                                                    </div>
                                                </div>
                                                <div class="perm-group-body" style="padding:0 1rem .75rem 1rem; display:none;">
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_banking" id="vperm_view_banking" {{ in_array('view_banking', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_banking">View Banking</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="add_banking_supplier" id="vperm_add_banking_supplier" {{ in_array('add_banking_supplier', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_add_banking_supplier">Add Banking Supplier</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_banking_supplier" id="vperm_edit_banking_supplier" {{ in_array('edit_banking_supplier', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_edit_banking_supplier">Edit Banking Supplier</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_banking_supplier" id="vperm_delete_banking_supplier" {{ in_array('delete_banking_supplier', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_delete_banking_supplier">Delete Banking Supplier</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="add_banking_beneficiary" id="vperm_add_banking_beneficiary" {{ in_array('add_banking_beneficiary', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_add_banking_beneficiary">Add Banking Beneficiary</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_banking_beneficiary" id="vperm_edit_banking_beneficiary" {{ in_array('edit_banking_beneficiary', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_edit_banking_beneficiary">Edit Banking Beneficiary</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_banking_beneficiary" id="vperm_delete_banking_beneficiary" {{ in_array('delete_banking_beneficiary', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_delete_banking_beneficiary">Delete Banking Beneficiary</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="add_banking_transfer" id="vperm_add_banking_transfer" {{ in_array('add_banking_transfer', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_add_banking_transfer">Add Banking Transfer</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_banking_transfer" id="vperm_delete_banking_transfer" {{ in_array('delete_banking_transfer', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_delete_banking_transfer">Delete Banking Transfer</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="add_banking_chip" id="vperm_add_banking_chip" {{ in_array('add_banking_chip', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_add_banking_chip">Add Banking Chip</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="edit_banking_chip" id="vperm_edit_banking_chip" {{ in_array('edit_banking_chip', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_edit_banking_chip">Edit Banking Chip</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="delete_banking_chip" id="vperm_delete_banking_chip" {{ in_array('delete_banking_chip', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_delete_banking_chip">Delete Banking Chip</label></div>
                                                </div>
                                            </div>

                                            <!-- Main Store Section -->
                                            <div class="perm-group" style="margin:0; border-bottom:1px solid #E2E8F0;">
                                                <div class="perm-group-head" data-group="mainstore" onclick="toggleViewPermGroup(this)">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; cursor:pointer;">
                                                        <div style="display:flex; align-items:center; gap:.5rem;">
                                                            <i class="bi bi-shop" style="color:#F59E0B; font-size:1.1rem;"></i>
                                                            <strong style="color:var(--navy);">Main Store Section</strong>
                                                        </div>
                                                        <i class="bi bi-chevron-down" style="color:#94A3B8; transition:transform .2s;"></i>
                                                    </div>
                                                </div>
                                                <div class="perm-group-body" style="padding:0 1rem .75rem 1rem; display:none;">
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_main_store" id="vperm_view_main_store" {{ in_array('view_main_store', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_main_store">View Main Store Section</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="main_view_items" id="vperm_main_view_items" {{ in_array('main_view_items', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_main_view_items">View Items</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="main_create_items" id="vperm_main_create_items" {{ in_array('main_create_items', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_main_create_items">Create Items</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="main_create_item_request" id="vperm_main_create_item_request" {{ in_array('main_create_item_request', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_main_create_item_request">Create Item Request</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="main_view_item_request" id="vperm_main_view_item_request" {{ in_array('main_view_item_request', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_main_view_item_request">View Item Request</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="main_view_item_reports" id="vperm_main_view_item_reports" {{ in_array('main_view_item_reports', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_main_view_item_reports">View Item Reports</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="main_view_receiving" id="vperm_main_view_receiving" {{ in_array('main_view_receiving', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_main_view_receiving">{{ __('messages.view_receivings') }}</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="main_view_returns" id="vperm_main_view_returns" {{ in_array('main_view_returns', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_main_view_returns">{{ __('messages.view_returns') }}</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="main_make_receiving" id="vperm_main_make_receiving" {{ in_array('main_make_receiving', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_main_make_receiving">{{ __('messages.make_receiving') }}</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="main_make_return" id="vperm_main_make_return" {{ in_array('main_make_return', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_main_make_return">{{ __('messages.make_return') }}</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="main_supplier_credit" id="vperm_main_supplier_credit" {{ in_array('main_supplier_credit', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_main_supplier_credit">{{ __('messages.supplier_credit') }}</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="main_view_customers" id="vperm_main_view_customers" {{ in_array('main_view_customers', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_main_view_customers">View Customers</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_main_store_report" id="vperm_view_main_store_report" {{ in_array('view_main_store_report', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_main_store_report">Main Store Report</label></div>
                                                </div>
                                            </div>

                                            <!-- System Access -->
                                            <div class="perm-group" style="margin:0; border-bottom:1px solid #E2E8F0;">
                                                <div class="perm-group-head" data-group="system" onclick="toggleViewPermGroup(this)">
                                                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; cursor:pointer;">
                                                        <div style="display:flex; align-items:center; gap:.5rem;">
                                                            <i class="bi bi-gear" style="color:#F59E0B; font-size:1.1rem;"></i>
                                                            <strong style="color:var(--navy);">System Access</strong>
                                                        </div>
                                                        <i class="bi bi-chevron-down" style="color:#94A3B8; transition:transform .2s;"></i>
                                                    </div>
                                                </div>
                                                <div class="perm-group-body" style="padding:0 1rem .75rem 1rem; display:none;">
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_logs" id="vperm_view_logs" {{ in_array('view_logs', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_logs">View Logs</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_logs" id="vperm_manage_logs" {{ in_array('manage_logs', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_logs">Manage Logs</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_settings" id="vperm_view_settings" {{ in_array('view_settings', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_settings">View Settings</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_settings" id="vperm_manage_settings" {{ in_array('manage_settings', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_settings">Manage Settings</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_employees" id="vperm_manage_employees" {{ in_array('manage_employees', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_employees">Manage Employees</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_employees" id="vperm_view_employees" {{ in_array('view_employees', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_employees">View Employees</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="manage_language" id="vperm_manage_language" {{ in_array('manage_language', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_manage_language">Manage Language</label></div>
                                                    <div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="permissions[]" value="view_all_shops" id="vperm_view_all_shops" {{ in_array('view_all_shops', $currentPermissions) ? 'checked' : '' }}><label class="form-check-label" for="vperm_view_all_shops">View All Shops</label></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="perms-add-row" style="margin-top:.75rem;">
                                            <select id="viewNewPermission" class="field-select">
                                                <option value="" selected disabled>Select permission to add…</option>
                                                @foreach($allPermissions as $val => $lbl)
                                                    <option value="{{ $val }}">{{ $lbl }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" id="viewAddPermission" class="btn-sm-add amber">
                                                <i class="bi bi-plus-circle"></i> Add
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Shop assignments --}}
                                    <div class="section-head" style="margin: 1.25rem -1.4rem; border-top:1.5px solid var(--slate-200); border-bottom:1.5px solid var(--slate-200); padding:.875rem 1.4rem; background:var(--slate-50);">
                                        <div class="section-head-icon shi-sky"><i class="bi bi-shop"></i></div>
                                        <span class="section-title">Shop Assignments</span>
                                    </div>

                                    <div class="perms-block">
                                        <div class="perms-label">Select shops to assign</div>
                                        <div class="perms-hint"><i class="bi bi-info-circle"></i> Check/uncheck shops to assign or remove access</div>
                                        
                                        @php
                                            $assignedShopIds = $userAccounts->pluck('account')->toArray();
                                        @endphp

                                        <div class="shops-scroll">
                                            @forelse($accounts as $account)
                                                @php
                                                    $isChecked = in_array($account->id, $assignedShopIds);
                                                    $userAccount = $userAccounts->where('account', $account->id)->first();
                                                    $isPrimary = $userAccount->is_primary ?? false;
                                                @endphp
                                                <label class="shop-checkbox-item">
                                                    <input type="checkbox" 
                                                           class="shop-checkbox" 
                                                            name="accounts[]" 
                                                           value="{{ $account->id }}"
                                                           {{ $isChecked ? 'checked' : '' }}
                                                           data-shop-name="{{ $account->name }}">
                                                    <div class="shop-info">
                                                        <div class="shop-name">
                                                            {{ $account->name }}
                                                            @if($isPrimary)
                                                                <span class="shop-badge badge-primary">
                                                                    <i class="bi bi-star-fill"></i> Primary
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="shop-id">ID: {{ $account->id }}</div>
                                                    </div>
                                                    @if($isChecked)
                                                        <span class="shop-badge badge-success">
                                                            <i class="bi bi-check-circle"></i> Assigned
                                                        </span>
                                                    @endif
                                                </label>
                                            @empty
                                                <div style="text-align: center; padding: 2rem; color: var(--slate-400);">
                                                    <i class="bi bi-shop" style="font-size: 2rem;"></i>
                                                    <p style="margin-top: 0.5rem;">No shops available</p>
                                                </div>
                                            @endforelse
                                        </div>

                                        <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 0.5rem;">
                                            <button type="button" id="selectAllShopsBtn" class="btn-sm-add" style="background: var(--sky);">
                                                <i class="bi bi-check-all"></i> Select All
                                            </button>
                                            <button type="button" id="deselectAllShopsBtn" class="btn-sm-add" style="background: var(--slate-400);">
                                                <i class="bi bi-x-circle"></i> Deselect All
                                            </button>
                                        </div>

                                        <div id="selectedShopsCount" class="selected-shops-info">
                                            0 shops selected
                                        </div>
                                    </div>

                                    {{-- Action footer --}}
                                    <div class="action-footer">
                                        <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Save changes</button>
                                        <div class="action-footer-right">
                                            @if($employee->status != 'deleted')
                                            <button type="submit" formaction="{{ url('banUser') }}" class="btn-warn">
                                                <i class="bi bi-shield-x"></i>
                                                {{ $employee->status == 'banned' ? 'Unban' : 'Ban' }}
                                            </button>
                                            <button type="submit" formaction="{{ url('deleteUser') }}" class="btn-danger"
                                                    onclick="return confirm('Delete this employee? This cannot be undone.')">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                            @endif
                                            <button type="button" class="btn-idcard" data-bs-toggle="modal" data-bs-target="#idCardModal">
                                                <i class="bi bi-card-heading"></i> Generate ID Card
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Change Password --}}
                        <div class="form-section au au4">
                            <div class="section-head">
                                <div class="section-head-icon shi-rose"><i class="bi bi-key"></i></div>
                                <span class="section-title">Change Password</span>
                            </div>
                            <div class="section-body">
                                <form action="{{ url('changePassword') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="employeeId" value="{{ $employee->id }}">
                                    <div class="perms-hint" style="margin-bottom:1rem;"><i class="bi bi-info-circle"></i> Leave blank to keep the current password</div>
                                    <div class="field-grid-2">
                                        <div class="field">
                                            <label class="field-label">New password</label>
                                            <input type="password" class="field-input" name="new_password" placeholder="Enter new password">
                                        </div>
                                        <div class="field">
                                            <label class="field-label">Confirm password</label>
                                            <input type="password" class="field-input" name="confirm_password" placeholder="Confirm new password">
                                        </div>
                                    </div>
                                    <div class="action-footer">
                                        <button type="submit" class="btn-save"><i class="bi bi-key-fill"></i> Update password</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>{{-- end right --}}
                </div>{{-- end page-grid --}}

            </div>
        </main>
    </div>
</div>

{{-- ============================================= --}}
{{-- ID CARD MODAL --}}
{{-- ============================================= --}}
<div class="modal fade id-card-modal" id="idCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-card-heading me-2"></i> Employee ID Card</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="idCardPreviewContainer">
                {{-- ID Card Preview will be rendered here --}}
                <div id="idCardPreview"></div>
                
                {{-- Action Buttons --}}
                <div class="id-card-actions">
                    <button type="button" class="btn-idcard-print" onclick="printIdCard()">
                        <i class="bi bi-printer"></i> Print ID
                    </button>
                    <button type="button" class="btn-idcard-download" onclick="downloadIdCard()">
                        <i class="bi bi-download"></i> Download
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Shop checkboxes functionality
        const shopCheckboxes = document.querySelectorAll('.shop-checkbox');
        const selectedCountSpan = document.getElementById('selectedShopsCount');
        const selectAllBtn = document.getElementById('selectAllShopsBtn');
        const deselectAllBtn = document.getElementById('deselectAllShopsBtn');

        function updateSelectedCount() {
            const checked = document.querySelectorAll('.shop-checkbox:checked');
            const count = checked.length;
            if (selectedCountSpan) {
                selectedCountSpan.textContent = count + ' shop' + (count !== 1 ? 's' : '') + ' selected';
            }
        }

        if (selectAllBtn) {
            selectAllBtn.onclick = function() {
                shopCheckboxes.forEach(cb => cb.checked = true);
                updateSelectedCount();
            };
        }

        if (deselectAllBtn) {
            deselectAllBtn.onclick = function() {
                shopCheckboxes.forEach(cb => cb.checked = false);
                updateSelectedCount();
            };
        }

        shopCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateSelectedCount);
        });

        updateSelectedCount();

        // Permission management
        const viewNewPermS = document.getElementById('viewNewPermission');
        const container = document.getElementById('viewPermissionsContainer');

        function findCheckbox(value) {
            return container.querySelector('input[name="permissions[]"][value="' + value + '"]');
        }

        function expandGroupFor(el) {
            const groupHead = el.closest('.perm-group')?.querySelector('.perm-group-head');
            if (!groupHead) return;
            const body = groupHead.nextElementSibling;
            const icon = groupHead.querySelector('.bi-chevron-down, .bi-chevron-up');
            if (body && getComputedStyle(body).display === 'none') {
                body.style.display = 'block';
                if (icon) icon.className = 'bi bi-chevron-up';
            }
        }

        const addPermBtn = document.getElementById('viewAddPermission');
        if (addPermBtn) {
            addPermBtn.onclick = () => {
                const v = viewNewPermS.value;
                if (!v) return;
                const cb = findCheckbox(v);
                if (cb) {
                    expandGroupFor(cb);
                    cb.checked = true;
                    cb.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                viewNewPermS.value = '';
            };
        }

        if (container) {
            container.addEventListener('change', function (e) {
                if (e.target.matches('input[name="permissions[]"]')) {
                    if (!e.target.checked) return;
                    if (viewNewPermS) {
                        viewNewPermS.querySelectorAll('option').forEach(opt => {
                            if (opt.value === e.target.value) opt.remove();
                        });
                    }
                }
            });
        }

        // Make all perm groups closed by default
        document.querySelectorAll('.perm-group-body').forEach(body => {
            body.style.display = 'none';
        });
        
        // Toggle permission groups
        window.toggleViewPermGroup = function(element) {
            const groupBody = element.nextElementSibling;
            const icon = element.querySelector('.bi-chevron-down, .bi-chevron-up');
            const isOpen = groupBody.style.display === 'block';
            
            groupBody.style.display = isOpen ? 'none' : 'block';
            if (icon) {
                icon.className = isOpen ? 'bi bi-chevron-down' : 'bi bi-chevron-up';
            }
        };

        // =============================================
        // GENERATE ID CARD
        // =============================================
        const idCardPreview = document.getElementById('idCardPreview');

        function generateIdCard() {
            const employee = @json($employee);
            const profileSrc = employee.userImg ? '{{ asset('/public/images/') }}/' + employee.userImg : null;
            const initials = employee.name ? employee.name.charAt(0).toUpperCase() : 'E';
            
            // Get employee details
            const name = employee.name || 'N/A';
            const role = employee.levelStatus || 'N/A';
            const email = employee.email || 'N/A';
            const contact = employee.contact || 'N/A';
            const age = employee.age || 'N/A';
            const employeeId = employee.id || 'N/A';
            
            // Get current date
            const now = new Date();
            const dateStr = now.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });

            const avatarHtml = profileSrc 
                ? `<img src="${profileSrc}" alt="${name}" style="width:100%;height:100%;object-fit:cover;">`
                : `<span style="font-size:2.5rem;font-weight:700;color:rgba(255,255,255,.85);">${initials}</span>`;

            const html = `
                <div class="id-card-preview" id="idCardToPrint">
                    <div class="id-card-header">
                        <div class="id-card-logo">
                            <img src="{{ asset('/public/images/leruma.png') }}" alt="Leruma">
                            <h4>Leruma Ent.</h4>
                        </div>
                        <div class="id-card-watermark">EMPLOYEE</div>
                    </div>
                    <div class="id-card-body">
                        <div class="id-card-avatar">
                            ${avatarHtml}
                        </div>
                        <div class="id-card-name">${name}</div>
                        <span class="id-card-role">${role}</span>
                        
                        <div class="id-card-details">
                            <div class="id-card-detail-row">
                                <span class="id-card-detail-label">Employee ID</span>
                                <span class="id-card-detail-value">#${employeeId}</span>
                            </div>
                            <div class="id-card-detail-row">
                                <span class="id-card-detail-label">Email</span>
                                <span class="id-card-detail-value">${email}</span>
                            </div>
                            <div class="id-card-detail-row">
                                <span class="id-card-detail-label">Contact</span>
                                <span class="id-card-detail-value">${contact}</span>
                            </div>
                            <div class="id-card-detail-row">
                                <span class="id-card-detail-label">Age</span>
                                <span class="id-card-detail-value">${age}</span>
                            </div>
                            <div class="id-card-detail-row">
                                <span class="id-card-detail-label">Issued</span>
                                <span class="id-card-detail-value">${dateStr}</span>
                            </div>
                        </div>
                    </div>
                    <div class="id-card-footer">
                        Valid ID Card • Leruma POS System • ${dateStr}
                    </div>
                </div>
            `;

            return html;
        }

        // Generate ID when modal opens
        document.getElementById('idCardModal').addEventListener('show.bs.modal', function () {
            if (idCardPreview) {
                idCardPreview.innerHTML = generateIdCard();
            }
        });

        // =============================================
        // PRINT ID CARD
        // =============================================
        window.printIdCard = function() {
            const printContents = document.getElementById('idCardToPrint');
            if (!printContents) return;
            
            const originalContents = document.body.innerHTML;
            const printStyles = `
                <style>
                    body { margin: 0; padding: 20px; background: white; }
                    .id-card-preview {
                        max-width: 420px;
                        margin: 0 auto;
                        background: white;
                        border-radius: 16px;
                        overflow: hidden;
                        box-shadow: none;
                        border: 1px solid #ddd;
                    }
                    .id-card-header { background: #0B1E3D; padding: 1.5rem; text-align: center; position: relative; overflow: hidden; }
                    .id-card-header::after, .id-card-header::before { display: none; }
                    .id-card-logo img { height: 40px; filter: brightness(0) invert(1); }
                    .id-card-logo h4 { color: white; font-weight: 700; margin: 0.25rem 0 0; letter-spacing: 2px; }
                    .id-card-body { padding: 1.5rem; text-align: center; }
                    .id-card-avatar { width: 100px; height: 100px; border-radius: 50%; margin: -50px auto 0.75rem; border: 4px solid white; background: #112952; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; z-index: 2; }
                    .id-card-avatar img { width: 100%; height: 100%; object-fit: cover; }
                    .id-card-name { font-size: 1.1rem; font-weight: 700; color: #0B1E3D; margin: 0.5rem 0 0.25rem; }
                    .id-card-role { display: inline-block; padding: 0.25rem 1rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: #FEF3C7; color: #92400e; }
                    .id-card-details { margin: 1rem 0; text-align: left; padding: 0 0.5rem; }
                    .id-card-detail-row { display: flex; justify-content: space-between; padding: 0.4rem 0; border-bottom: 1px solid #E2E8F0; font-size: 0.8rem; }
                    .id-card-detail-row:last-child { border-bottom: none; }
                    .id-card-detail-label { color: #64748B; font-weight: 500; }
                    .id-card-detail-value { color: #1E293B; font-weight: 600; }
                    .id-card-footer { background: #F8FAFC; padding: 0.75rem; text-align: center; border-top: 1px solid #E2E8F0; font-size: 0.65rem; color: #94A3B8; letter-spacing: 0.5px; }
                    .id-card-watermark { display: none; }
                    .modal, .modal-backdrop, .id-card-actions { display: none !important; }
                    .id-card-preview { box-shadow: none !important; }
                </style>
            `;

            const printContent = printContents.outerHTML;
            
            document.body.innerHTML = printStyles + printContent;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        };

        // =============================================
        // DOWNLOAD ID CARD (using html2canvas)
        // =============================================
        window.downloadIdCard = function() {
            const element = document.getElementById('idCardToPrint');
            if (!element) return;

            // Check if html2canvas is loaded
            if (typeof html2canvas === 'undefined') {
                // Load html2canvas dynamically
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
                script.onload = function() {
                    captureAndDownload(element);
                };
                document.head.appendChild(script);
            } else {
                captureAndDownload(element);
            }
        };

        function captureAndDownload(element) {
            html2canvas(element, {
                scale: 2,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff',
                logging: false
            }).then(function(canvas) {
                const link = document.createElement('a');
                link.download = 'employee-id-card.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            }).catch(function(error) {
                console.error('Error generating image:', error);
                alert('Failed to download ID card. Please try printing instead.');
            });
        }
    });
</script>
</body>
</html>