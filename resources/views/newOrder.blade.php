<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('messages.create_new_sales')</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --navy:          #0B1E3D;
            --navy-mid:      #112952;
            --navy-light:    #1A3A6B;
            --amber:         #F59E0B;
            --amber-pale:    #FEF3C7;
            --emerald:       #059669;
            --emerald-pale:  #D1FAE5;
            --rose:          #E11D48;
            --rose-pale:     #FFE4E6;
            --violet:        #7C3AED;
            --violet-pale:   #EDE9FE;
            --fuchsia:       #C026D3;
            --fuchsia-pale:  #FAE8FF;
            --sky:           #0284C7;
            --sky-pale:      #E0F2FE;
            --slate-50:      #F8FAFC;
            --slate-100:     #F1F5F9;
            --slate-200:     #E2E8F0;
            --slate-300:     #CBD5E1;
            --slate-400:     #94A3B8;
            --slate-500:     #64748B;
            --slate-600:     #475569;
            --slate-700:     #334155;
            --slate-800:     #1E293B;
            --white:         #FFFFFF;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            
            background: #EEF2F9;
            color: var(--slate-800);
            min-height: 100vh;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        /* ── Main wrap ── */
        .main-wrap { max-width: 1800px; margin: 0 auto; padding: 1.1rem 1.4rem; }

        /* ── Page header ── */
        .pg-header {
            background: var(--navy);
            border-radius: 12px;
            padding: 0.95rem 1.4rem;
            margin-bottom: 1.1rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.28);
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
        }

        .pg-title {
            display: flex; align-items: center; gap: 0.75rem;
            color: var(--white); font-size: 1.3rem; font-weight: 700;
        }

        .pg-title-icon {
            width: 38px; height: 38px;
            background: rgba(245,158,11,0.18);
            border: 1px solid rgba(245,158,11,0.35);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            color: var(--amber); font-size: 1rem; flex-shrink: 0;
        }

        .pg-title span { color: var(--amber); }

        .header-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        .hbtn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            
            font-size: 0.8rem; font-weight: 600;
            padding: 0.44rem 0.95rem;
            border-radius: 7px; border: 1px solid transparent;
            cursor: pointer; text-decoration: none; transition: all 0.15s;
        }
        .hbtn-ghost {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.18);
            color: rgba(255,255,255,0.85);
        }
        .hbtn-ghost:hover { background: rgba(255,255,255,0.15); color: #fff; }

        /* ── Alerts ── */
        .alert-bar {
            display: flex; align-items: center; justify-content: space-between; gap: 0.65rem;
            padding: 0.7rem 1rem;
            border-radius: 10px;
            font-size: 0.875rem; font-weight: 500;
            margin-bottom: 1rem;
            animation: fadeSlide 0.3s ease;
        }
        @keyframes fadeSlide { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }
        .alert-ok  { background: var(--emerald-pale); border-left: 4px solid var(--emerald); color: #065F46; }
        .alert-err { background: var(--rose-pale);    border-left: 4px solid var(--rose);    color: #9F1239; }
        .alert-bar button { background: none; border: none; cursor: pointer; opacity: 0.5; font-size: 1rem; }
        .alert-bar button:hover { opacity: 1; }

        /* ── Two-col layout ── */
        .layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1rem;
            align-items: start;
        }
        @media (max-width: 1100px) { .layout { grid-template-columns: 1fr; } }

        /* ── Panel ── */
        .panel {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(11,30,61,0.08);
            overflow: hidden;
        }

        .panel-head {
            background: var(--navy);
            padding: 0.75rem 1.1rem;
            display: flex; align-items: center; justify-content: space-between;
            color: var(--white); font-size: 0.9rem; font-weight: 600;
            flex-shrink: 0;
        }
        .panel-head-left { display: flex; align-items: center; gap: 0.55rem; }
        .panel-head i { color: var(--amber); }

        /* ── Search box ── */
        .search-wrap { padding: 0.85rem 1rem; border-bottom: 1px solid var(--slate-200); position: relative; }

        .sbox {
            position: relative;
            display: flex; align-items: center;
        }
        .sbox-icon {
            position: absolute; left: 0.7rem;
            color: var(--slate-400); font-size: 0.875rem; pointer-events: none;
        }
        .sbox-input {
            
            font-size: 0.875rem;
            width: 100%;
            padding: 0.55rem 2.2rem 0.55rem 2.1rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            background: var(--slate-50);
            color: var(--slate-800);
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
        }
        .sbox-input:focus {
            border-color: var(--navy-light);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }
        .sbox-input::placeholder { color: var(--slate-400); }

        /* ── Dropdown ── */
        .dropdown {
            position: relative;
            top: calc(100% + 4px);
            left: 1rem; right: 1rem;
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 10px;
            box-shadow: 0 8px 32px rgba(11,30,61,0.14);
            z-index: 1000;
            display: none;
            max-height: 700px;
            overflow-y: auto;
            animation: dropIn 0.16s ease;
        }
        .dropdown.open { display: block; }
        @keyframes dropIn { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }

        .dd-item {
            padding: 0.6rem 0.9rem;
            border-bottom: 1px solid var(--slate-100);
            cursor: pointer;
            transition: background 0.12s;
        }
        .dd-item:last-child { border-bottom: none; }
        .dd-item:hover { background: #EFF4FF; }
        .dd-item-name { font-weight: 600; color: var(--navy); font-size: 0.865rem; margin-bottom: 0.18rem; }
        .dd-item-meta {
            display: flex; gap: 0.75rem; align-items: center;
            font-size: 0.74rem; color: var(--slate-500);
            
        }
        .dd-badge {
            display: inline-flex; align-items: center; gap: 0.2rem;
            font-size: 0.7rem; font-weight: 700;
            padding: 0.18rem 0.55rem; border-radius: 20px;
        }
        .dd-badge-price  { background: var(--emerald-pale); color: #065F46; }
        .dd-badge-offer  { background: var(--fuchsia-pale); color: var(--fuchsia); }
        .dd-badge-stock  { background: var(--sky-pale);      color: var(--sky); }

        .dd-loading { padding: 0.9rem; text-align: center; color: var(--slate-400); font-size: 0.85rem; }
        .dd-empty   { padding: 1.5rem; text-align: center; color: var(--slate-400); font-size: 0.85rem; }

        /* ── Selected badge ── */
        .sel-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: var(--navy); color: var(--white);
            font-size: 0.78rem; font-weight: 600;
            padding: 0.3rem 0.35rem 0.3rem 0.7rem;
            border-radius: 20px;
            margin-top: 0.5rem;
        }
        .sel-badge-remove {
            width: 18px; height: 18px;
            background: rgba(255,255,255,0.15);
            border: none; color: white; cursor: pointer;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; transition: background 0.15s; flex-shrink: 0;
        }
        .sel-badge-remove:hover { background: var(--rose); }

        /* ── Cart section ── */
        .cart-section { padding: 0 0 0.5rem; }

        .cart-toolbar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0.65rem 1rem;
            border-bottom: 1px solid var(--slate-200);
        }
        .cart-label {
            font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--slate-500); display: flex; align-items: center; gap: 0.4rem;
        }
        .cart-label i { color: var(--amber); }
        .cart-badge {
            background: var(--amber); color: var(--navy);
            font-size: 0.7rem; font-weight: 700;
            padding: 0.15rem 0.5rem; border-radius: 20px;
        }
        .offer-banner {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(90deg, var(--fuchsia-pale), #FFF);
            border-bottom: 1px solid #F5D0FE;
            font-size: 0.78rem; font-weight: 600; color: var(--fuchsia);
        }
        .offer-chip {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: #F5D0FE;
            border: 1px solid #F3E8FF;
            border-radius: 8px;
            padding: 0.3rem 0.55rem;
            font-size: 0.75rem; font-weight: 600; color: var(--fuchsia);
            align-self: flex-start;
        }
        .offer-chip span { color: var(--navy); font-weight: 700; }
        .offer-remove {
            background: none; border: none; cursor: pointer; color: var(--rose);
            font-size: 1rem; line-height: 1; padding: 0; display: inline-flex; align-items: center;
        }
        .offer-remove:hover { color: #9F1239; }
        .toast-item { padding: 0.6rem 0.9rem; border-radius: 8px; font-size: 0.82rem; font-weight: 600; color: var(--white); display:flex; align-items:center; gap:0.5rem; margin-top:0.4rem; }
        .toast-ok { background: var(--emerald); }
        .toast-err { background: var(--rose); }

        /* ── Cart table ── */
        .cart-overflow { overflow-x: auto; }

        table.cart-tbl { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        table.cart-tbl thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            padding: 0.5rem 0.7rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
        }
        table.cart-tbl tbody td {
            padding: 0.5rem 0.7rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: middle;
        }
        table.cart-tbl tbody tr:hover td { background: #F8FAFF; }
        table.cart-tbl tbody tr.offer-row td { background: #F0FDF4; }
        table.cart-tbl tbody tr.offer-row:hover td { background: #DCFCE7; }
        table.cart-tbl tbody tr.offer-row td:first-child { border-left: 3px solid var(--emerald); }

        .cart-prod-name {
            font-weight: 600; color: var(--navy);
            max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .mono {  font-size: 0.78rem; }
        .num-right { text-align: right;  font-size: 0.8rem; }
        .fw-total { font-weight: 600; color: var(--navy); }

        .tbl-input {
             font-size: 0.78rem;
            width: 72px; padding: 0.28rem 0.4rem;
            border: 1.5px solid var(--slate-200); border-radius: 5px;
            background: var(--white); color: var(--slate-800);
            text-align: center; outline: none;
            transition: border-color 0.15s;
        }
        .tbl-input:focus { border-color: var(--navy-light); background: #EEF3FF; }

        .del-btn {
            width: 27px; height: 27px;
            background: var(--rose-pale); color: var(--rose);
            border: none; border-radius: 6px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; font-size: 0.8rem;
            transition: background 0.15s, transform 0.12s;
        }
        .del-btn:hover { background: var(--rose); color: var(--white); transform: scale(1.08); }

        .empty-cart {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 3rem 1rem; color: var(--slate-400); text-align: center;
        }
        .empty-cart i { font-size: 2.2rem; margin-bottom: 0.6rem; opacity: 0.4; display: block; }
        .empty-cart-title { font-size: 0.9rem; font-weight: 600; color: var(--slate-500); margin-bottom: 0.25rem; }
        .empty-cart p { font-size: 0.8rem; }

        /* ── Right panel sections ── */
        .rp-sec {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--slate-200);
        }
        .rp-sec:last-child { border-bottom: none; }

        .sec-title {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em;
            color: var(--slate-400); margin-bottom: 0.75rem;
            display: flex; align-items: center; gap: 0.35rem;
        }
        .sec-title i { color: var(--amber); font-size: 0.8rem; }

        /* ── Customer info card ── */
        .cust-info {
            background: var(--slate-50);
            border: 1.5px solid var(--slate-200);
            border-left: 3px solid var(--amber);
            border-radius: 8px;
            padding: 0.7rem 0.85rem;
            margin-top: 0.6rem;
        }
        .cust-info-title {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--slate-500); margin-bottom: 0.6rem;
        }
        .cust-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 0.25rem 0;
            font-size: 0.815rem;
            border-bottom: 1px solid var(--slate-200);
        }
        .cust-row:last-child { border-bottom: none; }
        .cust-key { color: var(--slate-500); font-weight: 500; }
        .cust-val {  font-weight: 500; color: var(--slate-800); font-size: 0.8rem; }
        .cust-val.available { color: var(--emerald); }

        /* ── Order meta ── */
        .order-meta {
            display: flex; gap: 0.75rem; flex-wrap: wrap;
            margin-top: 0.6rem;
        }
        .meta-chip {
            display: flex; flex-direction: column; gap: 0.1rem;
            background: var(--slate-100); border-radius: 7px;
            padding: 0.4rem 0.65rem; flex: 1; min-width: 90px;
        }
        .meta-chip-label { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--slate-400); }
        .meta-chip-value { font-size: 0.82rem; font-weight: 600; color: var(--navy); }

        /* ── Field ── */
        .field { display: flex; flex-direction: column; gap: 0.25rem; margin-bottom: 0.6rem; }
        .field:last-child { margin-bottom: 0; }
        .field-label { font-size: 0.77rem; font-weight: 600; color: var(--slate-600); display: flex; align-items: center; gap: 0.3rem; }
        .field-label i { color: var(--navy-light); font-size: 0.8rem; }
        .field-input {
            width: 100%;
             font-size: 0.855rem;
            padding: 0.46rem 0.7rem;
            border: 1.5px solid var(--slate-200); border-radius: 7px;
            background: var(--white); color: var(--slate-800);
            outline: none; transition: border-color 0.18s, box-shadow 0.18s;
        }
        .field-input:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
        }
        select.field-input {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 0.7rem center; padding-right: 2rem;
            appearance: none;
        }
        .field-hint { font-size: 0.71rem; color: var(--slate-400); }

        /* ── Inline Shop Selector ── */
        .shop-select-inline {
            
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.5rem 2rem 0.5rem 0.75rem;
            border: 1.5px solid var(--slate-200);
            border-radius: 8px;
            background: var(--white);
            color: var(--navy);
            cursor: pointer;
            outline: none;
            min-width: 160px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%234361EE' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 0.6rem center;
            appearance: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .shop-select-inline:hover {
            border-color: var(--navy-light);
        }
        .shop-select-inline:focus {
            border-color: var(--navy-light);
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1);
            outline: none;
        }
        .shop-select-inline option {
            
            font-size: 0.85rem;
            padding: 0.5rem;
            color: var(--slate-800);
            background: var(--white);
        }

        /* ── Pay distribution ── */
        .pay-dist {
            display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;
            background: var(--slate-50); border: 1.5px solid var(--slate-200);
            border-radius: 8px; padding: 0.65rem;
            margin-bottom: 0.6rem;
        }
        .pay-dist-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: var(--slate-500); margin-bottom: 0.3rem; }

        /* ── Pricing summary ── */
        .price-rows { display: flex; flex-direction: column; gap: 0.35rem; margin-bottom: 0.75rem; }
        .price-row-item {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 0.845rem;
        }
        .price-row-item .key { color: var(--slate-600); font-weight: 500; }
        .price-row-item .val {  font-weight: 500; color: var(--slate-700); }

        .total-card {
            background: var(--navy);
            border-radius: 10px; padding: 0.85rem 1rem;
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 0.85rem;
        }
        .total-card-label { color: rgba(255,255,255,0.6); font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; }
        .total-card-amount {  font-size: 1.15rem; color: var(--amber); font-weight: 500; }
        .total-card-curr { font-size: 0.72rem; color: rgba(255,255,255,0.4); margin-right: 2px; }

        /* ── Submit button ── */
        .submit-btn {
            width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem;
             font-size: 0.925rem; font-weight: 700;
            padding: 0.75rem;
            background: var(--amber); color: var(--navy);
            border: none; border-radius: 9px; cursor: pointer;
            box-shadow: 0 3px 12px rgba(245,158,11,0.3);
            transition: box-shadow 0.18s, transform 0.12s, filter 0.15s;
        }
        .submit-btn:hover { box-shadow: 0 5px 20px rgba(245,158,11,0.42); transform: translateY(-1px); }
        .submit-btn:active { transform: translateY(0); }
        .submit-btn:disabled { background: var(--slate-300); color: var(--slate-500); box-shadow: none; cursor: not-allowed; transform: none; }

        /* ── Toast ── */
        .toast-stack { position: fixed; top: 1.2rem; right: 1.2rem; z-index: 9999; display: flex; flex-direction: column; gap: 0.45rem; }
        .toast-item {
            display: flex; align-items: center; gap: 0.55rem;
            background: var(--white); border-radius: 10px;
            box-shadow: 0 4px 20px rgba(11,30,61,0.16);
            padding: 0.65rem 0.95rem;
            min-width: 240px; max-width: 320px;
            font-size: 0.855rem; font-weight: 500;
            opacity: 0; transform: translateX(16px);
            transition: opacity 0.22s, transform 0.22s;
        }
        .toast-item.show { opacity: 1; transform: translateX(0); }
        .toast-ok  { border-left: 4px solid var(--emerald); }
        .toast-err { border-left: 4px solid var(--rose); }
        .toast-ok  .t-ico { color: var(--emerald); }
        .toast-err .t-ico { color: var(--rose); }

        /* ── Modal tweaks ── */
        .modal-header-navy {
            background: var(--navy);
            color: var(--white);
            border-bottom: none;
        }
        .modal-header-navy .btn-close { filter: invert(1) brightness(0.8); }
        .modal-header-navy i { color: var(--amber); }

        .modal-header-emerald {
            background: var(--emerald);
            color: var(--white);
            border-bottom: none;
        }
        .modal-header-emerald .btn-close { filter: invert(1) brightness(0.8); }

        .suspended-row {
            display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;
            padding: 0.7rem 0.9rem;
            background: var(--white);
            border-left: 3px solid var(--amber);
            border-radius: 8px;
            margin-bottom: 0.5rem;
            box-shadow: 0 2px 6px rgba(11,30,61,0.07);
            transition: box-shadow 0.15s, transform 0.12s;
        }
        .suspended-row:hover { box-shadow: 0 4px 14px rgba(245,158,11,0.2); transform: translateX(3px); }
        .suspended-row-name { font-weight: 600; color: var(--navy); font-size: 0.875rem; }
        .suspended-row-meta {  font-size: 0.78rem; color: var(--slate-500); }

        .resume-btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
             font-size: 0.78rem; font-weight: 600;
            padding: 0.38rem 0.8rem;
            background: var(--navy); color: var(--white);
            border: none; border-radius: 7px; cursor: pointer;
            transition: background 0.15s; text-decoration: none; white-space: nowrap;
        }
        .resume-btn:hover { background: var(--navy-light); color: var(--white); }

        .info-box {
            background: #EFF6FF;
            border-left: 3px solid var(--sky);
            border-radius: 7px;
            padding: 0.7rem 0.85rem;
            font-size: 0.835rem; color: var(--slate-700);
            margin-bottom: 0.85rem;
        }
        .info-box i { color: var(--sky); }
    </style>
</head>
<body>

@include("sidenav")
<main class="main-content">
        <div class="main-wrap">

            {{-- ── Page Header ── --}}
            <div class="pg-header">
                    <div class="pg-title">
                        <div class="pg-title-icon"><i class="bi bi-bag-plus-fill"></i></div>
                        @lang('messages.create_new_sales_header')
                    </div>
                <div class="header-actions">
                    @if (Auth::user()->levelStatus === 'Admin')
                        <button class="hbtn hbtn-ghost" data-bs-toggle="modal" data-bs-target="#pastSalesModal">
                        <i class="bi bi-pencil-square"></i> @lang('messages.edit_past_sales')
                    </button>
                    @endif                    
                    <button class="hbtn hbtn-ghost" data-bs-toggle="modal" data-bs-target="#suspendedModal">
                        <i class="bi bi-pause-circle"></i> @lang('messages.suspended_orders')
                    </button>
                </div>
            </div>

            {{-- ── Alerts ── --}}
            @if(session('success'))
            <div class="alert-bar alert-ok">
                <span><i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}</span>
                <button onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert-bar alert-err">
                <span><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}</span>
                <button onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>
            </div>
            @endif

            <div class="layout">

                {{-- ══ LEFT: Product search + Cart ══ --}}
                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-head-left">
                            <i class="bi bi-grid-3x3-gap-fill"></i> @lang('messages.select_products')
                        </div>
                    </div>

                    {{-- Product search with shop selector --}}
                    <div class="search-wrap">
                        <div class="sbox" style="display:flex; gap:0.5rem; align-items:center;">
                             <div class="field">
                                <label class="field-label"><i class="bi bi-tag"></i> @lang('messages.sales_type')</label>
                                 <select id="orderType" name="orderType" class="field-input">
                                     <option value="Sell" {{ session('orderType', 'Sell') == 'Sell' ? 'selected' : '' }}>@lang('messages.pay')</option>
                                     <option value="Return" {{ session('orderType', 'Sell') == 'Return' ? 'selected' : '' }}>@lang('messages.return')</option>
                                 </select>
                            </div>
                            {{-- Shop Selector --}}
                            @if(isset($allShops) && count($allShops) > 1)
                            <select class="shop-select-inline" id="shopSelect" onchange="changeShop(this.value)" title="@lang('messages.select_shop')">
                                <option value="" selected disabled>@lang('messages.select_shop')</option>
                                @foreach($allShops as $shop)
                                <option value="{{ $shop['id'] }}" {{ (session('selected_shop_id') == $shop['id']) ? 'selected' : '' }}>
                                    {{ $shop['name'] }}
                                </option>
                                @endforeach
                            </select>
                            @endif
                            {{-- Search Input --}}
                            <div style="flex:1; position:relative;">
                                <i class="bi bi-search sbox-icon"></i>
                                <input type="text" id="productSearch" class="sbox-input"
                                    placeholder="@lang('messages.search_products_placeholder')" autocomplete="off">
                            </div>
                        </div>
                        <div class="dropdown" id="productDropdown"></div>
                    </div>

                    {{-- Cart --}}
                    <div class="cart-section">
                    <div class="cart-toolbar">
                        <div class="d-flex">
                            <span class="cart-label"><i class="bi bi-cart3"></i> @lang('messages.cart')</span>
                        <span class="cart-badge" id="cartBadge">
                            {{ $cart->where('offered_items', '!=', 1)->count() }}
                        </span>
                        </div>
                        <button type="button" class="btn btn-outline-danger" id="clearCartBtn" style="font-size:0.75rem;padding:0.3rem 0.55rem;">
                            <i class="bi bi-x"></i> Clear All
                        </button>
                    </div>

                        @php $offeredItems = $cart->where('offered_items', 1); @endphp
                        @if($offeredItems->count() > 0)
                        <div class="offer-banner" style="flex-direction:column;align-items:stretch;gap:0.5rem;">
                            <div style="display:flex;align-items:center;justify-content:space-between;gap:0.5rem;">
                                <span><i class="bi bi-gift-fill"></i> @lang('messages.free_offer_items_included', ['count' => $offeredItems->count()])</span>
                            </div>
                            <div id="offerList" style="display:flex;flex-direction:column;gap:0.35rem;">
                                @foreach($offeredItems as $oi)
                                @php
                                    $oiName = DB::table('products')->where('account', getCurrentShopId())->where('product_id', $oi->productId)->value('name01') ?? __('messages.unknown');
                                @endphp
                                <div class="offer-chip" data-product-id="{{ $oi->productId }}">
                                    <i class="bi bi-gift"></i>
                                    <span>{{ $oiName }} &times;{{ $oi->pQuantity }}</span>
                                    <button type="button" class="offer-remove" title="@lang('messages.remove_free_item')" onclick="removeOfferItem(this,'{{ $oi->order_id }}','{{ $oi->productId }}',{{ $oi->pQuantity }})">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                         
                        <div class="cart-overflow">
                            @php
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
                            @endphp

                            @if($cartItems->count() > 0)
                            <table class="cart-tbl">
                                <thead>
                                    <tr>
                                        <th>@lang('messages.products_col_product')</th>
                                        <th style="text-align:center;">@lang('messages.col_qty')</th>
                                        <th style="text-align:right;">@lang('messages.col_unit_price')</th>
                                        <th style="text-align:right;">@lang('messages.amount')</th>
                                        <th style="text-align:right;">@lang('messages.discount')</th>
                                        <th style="text-align:right;">@lang('messages.total')</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                    @php
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
                                    @endphp
                                    <tr class="{{ $qualifies ? 'offer-row' : '' }}">
                                        <td>
                                            <div class="cart-prod-name" title="{{ $productName }}">
                                                {{ $productName }}
                                            </div>
                                            @if($qualifies && $offerInfo)
                                                <span class="dd-badge dd-badge-offer" style="margin-top:2px;">
                                                    <i class="bi bi-gift-fill"></i> +{{ $freeCount }} @lang('messages.free')
                                                </span>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            <input type="number" class="tbl-input" value="{{ $item->pQuantity }}"
                                                onchange="updateCartItem('{{ $item->order_id }}','{{ $item->productId }}','pQuantity',this.value)">
                                        </td>
                                        <td class="num-right">{{ number_format($item->productPrice) }}</td>
                                        <td class="num-right">{{ number_format($amount) }}</td>
                                        <td style="text-align:center;">
                                            <input type="number" class="tbl-input" value="{{ $netAdj }}"
                                                placeholder="±"
                                                onchange="updateCartItem('{{ $item->order_id }}','{{ $item->productId }}','adjustment',this.value)">
                                        </td>
                                        <td class="num-right fw-total">{{ number_format($total) }}</td>
                                        <td style="text-align:center;">
                                            <form action="removeFromCart" method="post" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="itemId" value="{{ $item->productId }}">
                                                <input type="hidden" name="orderId" value="{{ $item->order_id }}">
                                                <input type="hidden" name="prodQuantity" value="{{ $item->pQuantity }}">
                                                <button type="submit" class="del-btn" title="@lang('messages.remove')">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="empty-cart">
                                <i class="bi bi-cart-x"></i>
                                <div class="empty-cart-title">@lang('messages.cart_empty')</div>
                                <p>@lang('messages.cart_empty_hint')</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>{{-- /left panel --}}

                {{-- ══ RIGHT: Summary + Submit ══ --}}
                <div class="panel">
                    <div class="panel-head">
                        <div class="panel-head-left"><i class="bi bi-receipt"></i> @lang('messages.sales_summary')</div>
                    </div>

                    {{-- Customer --}}
                    <div class="rp-sec">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
                            <div class="sec-title" style="margin-bottom:0;"><i class="bi bi-person-circle"></i> @lang('messages.customer')</div>
                            <a href="customers" class="hbtn" style="background:var(--navy);color:#fff;font-size:0.75rem;padding:0.3rem 0.65rem;">
                                <i class="bi bi-plus-lg"></i> @lang('messages.new')
                            </a>
                        </div>

                        <form action="saveInfos" id="saveInfosForm" method="post">
                            @csrf
                            <input type="hidden" name="orderId" value="{{ $orders->order_id ?? '' }}">
                            <input type="hidden" name="orderType" id="orderTypeForm1" value="{{ session('orderType', 'Sell') }}">
                            <input type="hidden" name="selectedCustomer" id="selectedCustomer">
                            <div style="position:relative;">
                                <div class="sbox">
                                    <i class="bi bi-search sbox-icon"></i>
                                    <input type="text" id="customerSearch" class="sbox-input"
                                        placeholder="@lang('messages.search_customer_placeholder')" autocomplete="off">
                                </div>
                                <div class="dropdown" id="customerDropdown"></div>
                            </div>

                            <div id="customerBadgeWrap" style="display:{{ !empty($orders->cPhone) ? 'block' : 'none' }};margin-top:0.5rem;">
                                <span class="sel-badge">
                                    <span id="customerBadgeText">{{ $orders->cName ?? '' }}</span>
                                    <button type="button" class="sel-badge-remove" onclick="clearCustomer()">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </span>
                            </div>
                        </form>

                        @php
                            $checkz = DB::table('customers')->where('id', $orders->cPhone ?? '')->first();
                            $odez1   = DB::table('orders')->whereIn('status', ['Debt','Partial'])
                                        ->where('cPhone', $orders->cPhone ?? '')->sum('credit');
                            $odez2   = DB::table('debts')->where('cId', $orders->cPhone ?? '')->sum('amount');
                            $odez    = +($odez1 - $odez2);
                            $totalSpent = $odez1;
                        @endphp

                        @if(!empty($orders->cPhone))
                        <div class="cust-info" id="custInfoCard">
                            <div class="cust-info-title">@lang('messages.customer_info')</div>
                            <div class="cust-row">
                                <span class="cust-key">@lang('messages.name')</span>
                                <span class="cust-val" id="custName">{{ $checkz->name ?? '—' }}</span>
                            </div>
                            <div class="cust-row">
                                <span class="cust-key">@lang('messages.credit_limit')</span>
                                <span class="cust-val" id="custLimit">{{ number_format($checkz->limits ?? 0) }}</span>
                            </div>
                            <div class="cust-row">
                                <span class="cust-key">@lang('messages.used_credit')</span>
                                <span class="cust-val" id="custCredit">{{ number_format($odez1 ?? 0) }}</span>
                            </div>
                            <div class="cust-row">
                                <span class="cust-key">@lang('messages.total_paid')</span>
                                <span class="cust-val" id="custPaid">{{ number_format($odez2 ?? 0) }}</span>
                            </div>
                            <div class="cust-row">
                                <span class="cust-key">@lang('messages.remaining_debt')</span>
                                <span class="cust-val" id="custRemaining">{{ number_format($odez ?? 0) }}</span>
                            </div>
                            <div class="cust-row">
                                <span class="cust-key">@lang('messages.available')</span>
                                <span class="cust-val available" id="custAvail">{{ number_format(($checkz->limits ?? 0) - ($odez ?? 0)) }}</span>
                            </div>
                        </div>
                        @else
                        <div class="cust-info" id="custInfoCard" style="display:none;"></div>
                        @endif

                        @if(!empty($orders->orderName))
                        <div class="order-meta">
                            <div class="meta-chip">
                                <span class="meta-chip-label">@lang('messages.order_id')</span>
                                <span class="meta-chip-value">{{ $orders->orderName ?? '—' }}</span>
                            </div>
                            <div class="meta-chip">
                                <span class="meta-chip-label">@lang('messages.status')</span>
                                <span class="meta-chip-value">{{ $orders->status ?? '—' }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Seller --}}
                    <div class="rp-sec">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
                            <div class="sec-title" style="margin-bottom:0;"><i class="bi bi-person-badge"></i> @lang('messages.seller')</div>
                        </div>
                        
                        <form action="saveSeller" id="saveSellerForm" method="post">
                            @csrf
                            <input type="hidden" name="orderId" value="{{ $orders->order_id ?? '' }}">
                            <input type="hidden" name="orderType" id="orderTypeForm2" value="{{ session('orderType', 'Sell') }}">

                            <div style="position:relative;">
                                <div class="sbox">
                                    <i class="bi bi-search sbox-icon"></i>
                                    <input type="text" id="sellerSearch" class="sbox-input"
                                        placeholder="@lang('messages.search_seller_placeholder')" autocomplete="off"
                                        value="{{ $orders->served_by ?? '' }}">
                                    <input type="hidden" name="selectedSeller" id="selectedSeller"
                                        value="{{ $orders->served_by ?? '' }}">
                                </div>
                                <div class="dropdown" id="sellerDropdown"></div>
                            </div>
                            
                            <div id="sellerBadgeWrap" style="display:{{ $orders->served_by ? 'block' : 'none' }};margin-top:0.5rem;">
                                <span class="sel-badge">
                                    <span id="sellerBadgeText">{{ $orders->served_by ?? '' }}</span>
                                    <button type="button" class="sel-badge-remove" onclick="clearSeller()">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </span>
                            </div>
                        </form>
                    </div>

                    {{-- Order form --}}
                    <div class="rp-sec">
                        <form action="payout" method="post" id="payoutForm">
                            @csrf
                            <input type="hidden" name="orderId"          value="{{ $orders->order_id ?? '' }}">
                            <input type="hidden" name="served"           id="servedHidden" value="{{ $orders->served_by ?? '' }}">
                            <input type="hidden" name="selectedCustomer" id="custHidden">
                            <input type="hidden" name="offered_items"    id="offeredItemsInput" value="">
                            <input type="hidden" name="orderType" id="orderTypeHidden" value="{{ session('orderType', 'Sell') }}">

                            <div class="sec-title"><i class="bi bi-sliders"></i> @lang('messages.order_settings')</div>


                            <div id="payDistWrap">
                                <div class="pay-dist">
                                    <div>
                                        <div class="pay-dist-label">@lang('messages.paid')</div>
                                        <input type="number" id="paidInput" name="paid" class="field-input" style="margin:0;">
                                    </div>
                                    <div>
                                        <div class="pay-dist-label">@lang('messages.credit')</div>
                                        <input type="number" id="creditInput" name="credit" class="field-input" style="margin:0;">
                                    </div>
                                </div>
                            </div>

                            <div id="payMethodWrap" class="field">
                                <label class="field-label"><i class="bi bi-wallet2"></i> @lang('messages.payment_method')</label>
                                <select id="paymentType" name="paymentMethod" class="field-input">
                                    <option value="Cash">@lang('messages.cash')</option>
                                     <option value="Credit">@lang('messages.credit')</option>
                                </select>
                            </div>

                            <div id="debtNote" style="display:none;" class="field">
                                <label class="field-label"><i class="bi bi-sticky"></i> @lang('messages.credit_note')</label>
                                <textarea class="field-input" placeholder="@lang('messages.add_a_note_placeholder')" rows="2" style="resize:none;"></textarea>
                            </div>

                            <div id="suspendNote" style="display:none;" class="field">
                                <label class="field-label"><i class="bi bi-pause-circle"></i> @lang('messages.suspension_reason')</label>
                                <textarea class="field-input" placeholder="@lang('messages.suspension_reason_placeholder')" rows="2" style="resize:none;"></textarea>
                            </div>

                            @if(canUser('manage_sales_date'))
                            @php
                                $orderDate = null;
                                if (!empty($orders->created_at)) {
                                    $d = \Carbon\Carbon::parse($orders->created_at);
                                    if ($d->format('Y-m-d') !== date('Y-m-d')) $orderDate = $d->format('Y-m-d');
                                }
                            @endphp
                            <div class="field">
                                <label class="field-label"><i class="bi bi-calendar3"></i> @lang('messages.sale_date') <span style="font-weight:400;color:var(--slate-400);">@lang('messages.optional')</span></label>
                                <input type="date" name="saleDate" value="{{ $orderDate ?? date('Y-m-d') }}" class="field-input">
                            </div>
                            @endif

                            {{-- Summary --}}
                            @php
                                $totalAdj = ($totalD ?? 0) - ($totalDI ?? 0);
                                $grandTotal = $totalP;
                            @endphp
                            <div class="price-rows" style="margin-top:0.75rem;">
                                <div class="price-row-item">
                                    <span class="key">@lang('messages.subtotal')</span>
                                    <span class="val" id="sumSubtotal">{{ number_format($totalP + $totalD + $totalDI) }}</span>
                                </div>
                                <div class="price-row-item">
                                    <span class="key">@lang('messages.discount')</span>
                                    <span class="val" id="sumDiscount">{{ number_format($totalAdj) }}</span>
                                </div>
                            </div>

                            <div class="total-card">
                                <div>
                                    <div class="total-card-label">@lang('messages.total')</div>
                                </div>
                                <div class="total-card-amount">
                                    <span class="total-card-curr">Tsh</span><span id="grandTotalEl">{{ number_format($grandTotal) }}</span>
                                </div>
                            </div>

                            <button type="submit" class="submit-btn" id="submitBtn">
                                <i class="bi bi-check-circle-fill"></i> @lang('messages.submit_sale')
                            </button>
                        </form>
                    </div>
                </div>{{-- /right panel --}}

            </div>{{-- /layout --}}
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════
     MODALS
════════════════════════════════════════════ --}}

{{-- Past Sales Modal --}}
<div class="modal fade" id="pastSalesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-navy">
                 <h5 class="modal-title"><i class="bi bi-pencil-square"></i> @lang('messages.edit_past_sales')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="info-box mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    @lang('messages.past_sales_search_note')
                </div>
                <div style="position:relative;margin-bottom:1rem;">
                    <div class="sbox">
                        <i class="bi bi-search sbox-icon"></i>
                        <input type="text" id="pastSalesSearch" class="sbox-input" placeholder="@lang('messages.search_by_customer_name')" autocomplete="off">
                    </div>
                </div>
                <div id="pastSalesResults" style="max-height:400px;overflow-y:auto;"></div>
            </div>
        </div>
    </div>
</div>

{{-- Confirm Return Modal --}}
<div class="modal fade" id="confirmReturnModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-navy">
                 <h5 class="modal-title"><i class="bi bi-arrow-return-left"></i> @lang('messages.confirm_return')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="margin-bottom:0.75rem;font-size:0.9rem;color:var(--slate-600);">
                    @lang('messages.return_to_orders_note')
                </p>
                <div id="returnSaleDetails" class="cust-info" style="display:none;"></div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('messages.cancel')</button>
                <button type="button" id="confirmReturnBtn" class="submit-btn" style="width:auto;padding:0.5rem 1.2rem;">
                    <i class="bi bi-check-circle-fill"></i> @lang('messages.confirm')
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Suspended Orders Modal --}}
<div class="modal fade" id="suspendedModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-navy">
                 <h5 class="modal-title"><i class="bi bi-pause-circle"></i> @lang('messages.suspended_orders')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height:70vh;overflow-y:auto;">
                @forelse($Suspended as $index => $order)
                <div class="suspended-row">
                    <div>
                        <div class="suspended-row-name">{{ $order->cName ?: __('messages.no_customer') }}</div>
                        <div class="suspended-row-meta">Tsh {{ number_format($order->total_price) }}</div>
                    </div>
                    <form action="resumeOrder" method="post">
                        @csrf
                        <input type="hidden" name="orderId" value="{{ $order->order_id }}">
                        <button type="submit" class="resume-btn">
                            @lang('messages.resume') <i class="bi bi-arrow-right-short"></i>
                        </button>
                    </form>
                </div>
                @empty
                <div style="text-align:center;padding:2.5rem 1rem;color:var(--slate-400);">
                    <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:0.5rem;"></i>
                     @lang('messages.no_suspended_orders')
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Offer Popup Modal --}}
<div class="modal fade" id="offerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-emerald">
                 <h5 class="modal-title"><i class="bi bi-gift-fill me-2"></i>@lang('messages.special_offer')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center" style="padding:1.5rem;">
                <i class="bi bi-gift" style="font-size:3rem;color:var(--emerald);display:block;margin-bottom:1rem;"></i>
                <p style="font-size:0.9rem;color:var(--slate-600);margin-bottom:0.75rem;">                    @lang('messages.special_offer_qualify')</p>
                <div style="background:var(--emerald-pale);border-left:3px solid var(--emerald);border-radius:8px;padding:0.75rem;margin-bottom:1rem;font-size:0.875rem;font-weight:600;color:#065F46;">
                    Buy @lang('messages.special_offer_buy_get_free', ['req' => '<span id="offerReqQty">0</span>', 'free' => '<span id="offerFreeQty">0</span>'])
                </div>
                    <p style="font-size:0.85rem;color:var(--slate-600);margin-bottom:1rem;">@lang('messages.product_label') <strong id="offerProdName"></strong></p>
                <div style="text-align:left;">
                    <label class="field-label"><i class="bi bi-hash"></i> @lang('messages.how_many_free_items')</label>
                    <input type="number" id="offerAcceptQty" class="field-input" value="0" min="0" style="margin-top:0.3rem;">
                    <div class="field-hint" style="margin-top:0.3rem;">@lang('messages.maximum_free_items', ['count' => '<span id="offerMaxQty">0</span>'])</div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" onclick="declineOffer()">@lang('messages.no_thanks')</button>
                <button type="button" class="submit-btn" onclick="acceptOffer()" style="width:auto;padding:0.5rem 1.2rem;">
                    <i class="bi bi-check-circle-fill"></i> @lang('messages.accept_offer')
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Toast stack --}}
<div class="toast-stack" id="toastStack"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
// ════════════════════════════════════════════
// State
// ════════════════════════════════════════════
let selectedProductId   = null;
let selectedCustomerObj = null;
let selectedSellerName  = null;
const offersData        = @json($offers ?? []);

function getOffer(productId) { return offersData.find(o => o.product_id === productId); }

// ════════════════════════════════════════════
// PRODUCT SEARCH
// ════════════════════════════════════════════
const productSearch = document.getElementById('productSearch');
const productDD     = document.getElementById('productDropdown');

productSearch.addEventListener('input', async e => {
    const q = e.target.value.trim();
    if (q.length < 1) { productDD.classList.remove('open'); return; }

    productDD.innerHTML = '<div class="dd-loading"><i class="bi bi-hourglass-split me-1"></i>' + @json(__('messages.searching')) + '</div>';
    productDD.classList.add('open');

    try {
        const res  = await fetch(`{{ url('searchProduct') }}?query=${encodeURIComponent(q)}`);
        const data = await res.json();

        if (!data.length) {
            productDD.innerHTML = '<div class="dd-empty">' + @json(__('messages.no_products_found')) + '</div>';
        } else {
            productDD.innerHTML = data.map(p => {
                const offer = getOffer(p.product_id);
                const offerBadge = offer
                    ? `<span class="dd-badge dd-badge-offer"><i class="bi bi-gift-fill"></i> Buy ${offer.required_quantity} Get ${offer.offer_quantity}</span>`
                    : '';
                return `<div class="dd-item" onclick="addProductToOrder(${p.id}, '${escHtml(p.name01)}', ${p.sPrice})">
                    <div class="dd-item-name">${escHtml(p.name01)} ${offerBadge}</div>
                    <div class="dd-item-meta">
                        <span class="dd-badge dd-badge-price">${Number(p.bPrice).toLocaleString()} Tsh</span>
                        <span class="dd-badge dd-badge-stock"><i class="bi bi-box-seam"></i> ${p.quantity}</span>
                    </div>
                </div>`;
            }).join('');
        }
    } catch {
        productDD.innerHTML = '<div class="dd-empty" style="color:var(--rose);">' + @json(__('messages.error_loading_products')) + '</div>';
    }
});

function addProductToOrder(id, name, price) {
    productSearch.value = name;
    productDD.classList.remove('open');

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ url("newOrder") }}';
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="pId" value="${id}">
        <input type="hidden" name="orderType" value="Sell">
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

custSearch.addEventListener('input', async e => {
    const q = e.target.value.trim();
    if (q.length < 1) { custDD.classList.remove('open'); return; }

    custDD.innerHTML = '<div class="dd-loading"><i class="bi bi-hourglass-split me-1"></i>' + @json(__('messages.searching')) + '</div>';
    custDD.classList.add('open');

    try {
        const res  = await fetch(`{{ url('searchCustomers') }}?query=${encodeURIComponent(q)}`);
        const data = await res.json();
        if (!data.length) {
            custDD.innerHTML = '<div class="dd-empty">' + @json(__('messages.no_customers_found')) + '</div>';
        } else {
            custDD.innerHTML = data.map(c => `
                <div class="dd-item" onclick="selectCustomer(${c.id}, '${escHtml(c.name)}', ${c.limits || 0})">
                    <div class="dd-item-name">${escHtml(c.name)}</div>
                    <div class="dd-item-meta">
                        <span><i class="bi bi-phone"></i> ${c.phone || 'N/A'}</span>
                        <span class="dd-badge dd-badge-stock">Limit: ${Number(c.limits || 0).toLocaleString()}</span>
                    </div>
                </div>
            `).join('');
        }
    } catch {
        custDD.innerHTML = '<div class="dd-empty" style="color:var(--rose);">' + @json(__('messages.error')) + '</div>';
    }
});

    async function selectCustomer(id, name, limit) {
    const orderType = document.getElementById('orderTypeHidden')?.value || 'Sell';
    document.getElementById('orderTypeForm1').value = orderType;
    try {
        await fetch('{{ url('setOrderType') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
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
        const res  = await fetch(`{{ url('getCustomerDetails') }}/${id}`);
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
        <div class="cust-info-title">@lang('messages.customer_info')</div>
        <div class="cust-row"><span class="cust-key">@lang('messages.name')</span><span class="cust-val" id="custName">${name}</span></div>
        <div class="cust-row"><span class="cust-key">@lang('messages.credit_limit')</span><span class="cust-val">${Number(limits||0).toLocaleString()}</span></div>
        <div class="cust-row"><span class="cust-key">@lang('messages.used_credit')</span><span class="cust-val">${Number(credit||0).toLocaleString()}</span></div>
        <div class="cust-row"><span class="cust-key">@lang('messages.total_paid')</span><span class="cust-val">${Number(paid||0).toLocaleString()}</span></div>
        <div class="cust-row"><span class="cust-key">@lang('messages.remaining_debt')</span><span class="cust-val" style="font-weight:700;color:var(--rose);">${Number(remainingDebt||0).toLocaleString()}</span></div>
        <div class="cust-row"><span class="cust-key">@lang('messages.available')</span><span class="cust-val available">${Number(available||0).toLocaleString()}</span></div>
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

sellerSearch.addEventListener('input', async e => {
    const q = e.target.value.trim();
    if (q.length < 1) { sellerDD.classList.remove('open'); return; }

    sellerDD.innerHTML = '<div class="dd-loading"><i class="bi bi-hourglass-split me-1"></i>' + @json(__('messages.searching')) + '</div>';
    sellerDD.classList.add('open');

    try {
        const res  = await fetch(`{{ url('searchSellers') }}?query=${encodeURIComponent(q)}`);
        const data = await res.json();
        if (!data.length) {
            sellerDD.innerHTML = '<div class="dd-empty">' + @json(__('messages.no_sellers_found')) + '</div>';
        } else {
            sellerDD.innerHTML = data.map(s => `
                <div class="dd-item" onclick="selectSeller('${escHtml(s.name)}', '${escHtml(s.levelStatus)}')">
                    <div class="dd-item-name">${escHtml(s.name)}</div>
                    <div class="dd-item-meta"><span class="dd-badge dd-badge-stock">${s.levelStatus}</span></div>
                </div>
            `).join('');
        }
    } catch {
        sellerDD.innerHTML = '<div class="dd-empty" style="color:var(--rose);">' + @json(__('messages.error')) + '</div>';
    }
});

function selectSeller(name, role) {
    const orderType = document.getElementById('orderTypeHidden')?.value || 'Sell';
    document.getElementById('orderTypeForm2').value = orderType;
    try {
        fetch('{{ url('setOrderType') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
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

function applyOrderType(v) {
    const pd = document.getElementById('payDistWrap');
    const pm = document.getElementById('payMethodWrap');
    const dn = document.getElementById('debtNote');
    const sn = document.getElementById('suspendNote');
    const otHidden = document.getElementById('orderTypeHidden');

    pd.style.display = 'block'; pm.style.display = 'block';
    dn.style.display = 'none';  sn.style.display = 'none';
    if (otHidden) otHidden.value = v;

    if (v === 'Debt')      { pd.style.display = 'none'; pm.style.display = 'none'; dn.style.display = ''; }
    if (v === 'Suspended') { pd.style.display = 'none'; pm.style.display = 'none'; sn.style.display = ''; }
    if (v === 'Return')    { pd.style.display = 'none'; }
}

// ════════════════════════════════════════════
// ORDER TYPE
// ════════════════════════════════════════════
document.getElementById('orderType').addEventListener('change', function() {
    const v = this.value;
    applyOrderType(v);

    fetch('{{ url('setOrderType') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ orderType: v })
    });
});

document.addEventListener('DOMContentLoaded', () => {
    applyOrderType(document.getElementById('orderType').value);
});

// ════════════════════════════════════════════
// PAY DISTRIBUTION
// ════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    const rawTotal = document.getElementById('grandTotalEl').textContent.replace(/,/g, '');
    const total    = parseFloat(rawTotal) || 0;
    const paid     = document.getElementById('paidInput');
    const credit   = document.getElementById('creditInput');
    paid.value   = total;
    credit.value = 0;

    credit.addEventListener('input', () => {
        let c = Math.min(Math.max(parseFloat(credit.value) || 0, 0), total);
        credit.value = c;
        paid.value   = (total - c).toFixed(2);
    });
    paid.addEventListener('input', () => {
        let p = Math.min(Math.max(parseFloat(paid.value) || 0, 0), total);
        paid.value   = p;
        credit.value = (total - p).toFixed(2);
    });
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

    fetch('{{ url("updateCartItem") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: fd
    })
    .then(r => r.json())
        .then(d => { if (d.success) location.reload(); else showToast(d.error || @json(__('messages.error_updating_item')), 'err'); })
    .catch(e => showToast(@json(__('messages.network_error')) + ': ' + e.message, 'err'));
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
            const orderId = '{{ $orders->order_id ?? '' }}';
            if (!orderId) return;

            const fd = new FormData();
            fd.append('orderId', orderId);

            fetch('{{ url("clearCart") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: fd
            })
            .then(r => r.text())
            .then(() => location.reload())
            .catch(e => showToast(@json(__('messages.network_error')) + ': ' + e.message, 'err'));
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

document.getElementById('pastSalesSearch').addEventListener('input', e => {
    loadPastSales(e.target.value.trim());
});

async function loadPastSales(q) {
    const el = document.getElementById('pastSalesResults');
    el.innerHTML = '<div class="dd-loading">' + @json(__('messages.searching')) + '</div>';
    try {
        const url = q ? `{{ url('searchSales') }}?search=${encodeURIComponent(q)}` : `{{ url('searchSales') }}`;
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (!data.sales?.length) {
            el.innerHTML = '<div class="dd-empty">' + @json(__('messages.no_past_sales_found')) + '</div>';
            return;
        }

        el.innerHTML = data.sales.map(s => {
            const d      = new Date(s.created_at);
            const dt     = d.toLocaleDateString() + ' ' + d.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
            const stBadge = s.status === 'Debt'
                ? `<span class="dd-badge dd-badge-offer">@lang('messages.credit')</span>`
                : `<span class="dd-badge dd-badge-price">@lang('messages.paid')</span>`;
            return `<div class="dd-item" onclick="selectPastSale('${s.sales_id}','${escHtml(s.cName || @json(__('messages.unknown_customer')))}',${s.totalAmount},${s.totalQuantity},'${dt}')">
                <div class="dd-item-name">${escHtml(s.cName || @json(__('messages.unknown_customer')))} ${stBadge}</div>
                <div class="dd-item-meta">
                    <span><i class="bi bi-calendar3"></i> ${dt}</span>
                    <span><i class="bi bi-box-seam"></i> ${s.totalQuantity} @lang('messages.items')</span>
                    <span class="dd-badge dd-badge-price">${Number(s.totalAmount).toLocaleString()} Tsh</span>
                </div>
            </div>`;
        }).join('');
    } catch (error) {
        console.error('Error loading past sales:', error);
        el.innerHTML = `<div class="dd-empty" style="color:var(--rose);">@lang('messages.error_loading_sales') ${error.message}</div>`;
    }
}

function selectPastSale(id, name, total, qty, date) {
    pendingReturnId = id;
    const det = document.getElementById('returnSaleDetails');
    det.innerHTML = `
        <div class="cust-row"><span class="cust-key">@lang('messages.customer')</span><span class="cust-val">${name}</span></div>
        <div class="cust-row"><span class="cust-key">@lang('messages.date')</span><span class="cust-val">${date}</span></div>
        <div class="cust-row"><span class="cust-key">@lang('messages.total')</span><span class="cust-val">${Number(total).toLocaleString()} Tsh</span></div>
        <div class="cust-row"><span class="cust-key">@lang('messages.items')</span><span class="cust-val">${qty}</span></div>
    `;
    det.style.display = '';
    new bootstrap.Modal(document.getElementById('confirmReturnModal')).show();
}

document.getElementById('confirmReturnBtn').addEventListener('click', async () => {
    if (!pendingReturnId) return;
    const btn = document.getElementById('confirmReturnBtn');
    btn.disabled = true; btn.innerHTML = '<i class="bi bi-hourglass-split"></i> ' + @json(__('messages.processing'));
    try {
        const fd = new FormData(); fd.append('sales_id', pendingReturnId);
        const data = await (await fetch('{{ url("returnSaleToOrder") }}', {
            method:'POST', body:fd, headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
        })).json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('confirmReturnModal'))?.hide();
            bootstrap.Modal.getInstance(document.getElementById('pastSalesModal'))?.hide();
            showToast(@json(__('messages.sale_returned_to_orders')), 'ok');
            setTimeout(() => location.reload(), 1200);
        } else { showToast(@json(__('messages.error')) + ': ' + data.message, 'err'); }
    } catch (e) { showToast(@json(__('messages.error')) + ': ' + e.message, 'err'); }
    btn.disabled = false; btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> ' + @json(__('messages.confirm'));
    pendingReturnId = null;
});

// ════════════════════════════════════════════
// OFFERS
// ════════════════════════════════════════════
let offerCtx = null;

document.querySelector('form[action="payout"]').addEventListener('submit', function() {
    const items = JSON.parse(sessionStorage.getItem('offeredItems') || '[]');
    document.getElementById('offeredItemsInput').value = JSON.stringify(items);
});

async function checkOffer(productId, qty) {
    try {
        const d = await (await fetch(`{{ url('checkOffer') }}/${productId}/${qty}`)).json();
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
        showToast(`${@json(__('messages.added'))} ${qty} ${@json(__('messages.free'))} ${offerCtx.offer.offer_product_name}!`, 'ok');
    }
    bootstrap.Modal.getInstance(document.getElementById('offerModal'))?.hide();
    offerCtx = null;
}
function declineOffer() { offerCtx = null; }

// ════════════════════════════════════════════
// Shop Selector
// ════════════════════════════════════════════
function changeShop(shopId) {
    // Show loading state
    const shopSelect = document.getElementById('shopSelect');
    if (shopSelect) {
        shopSelect.disabled = true;
    }
    
    // Send request to update session
    fetch(`{{ url('changeShop') }}?shop_id=${shopId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (response.ok) {
            // Reload the page to refresh data for the selected shop
            window.location.reload();
        } else {
            showToast(@json(__('messages.failed_change_shop')), 'err');
            if (shopSelect) shopSelect.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error changing shop:', error);
        showToast(@json(__('messages.network_error_changing_shop')), 'err');
        if (shopSelect) shopSelect.disabled = false;
    });
}

// ════════════════════════════════════════════
// Close dropdowns on outside click
// ════════════════════════════════════════════
document.addEventListener('click', e => {
    if (!productSearch.contains(e.target) && !productDD.contains(e.target))  productDD.classList.remove('open');
    if (!custSearch.contains(e.target)    && !custDD.contains(e.target))     custDD.classList.remove('open');
    if (!sellerSearch.contains(e.target)  && !sellerDD.contains(e.target))   sellerDD.classList.remove('open');
});

// ════════════════════════════════════════════
// Toast
// ════════════════════════════════════════════
function showToast(msg, type = 'ok') {
    const stack = document.getElementById('toastStack');
    const el = document.createElement('div');
    el.className = `toast-item toast-${type}`;
    el.innerHTML = `<i class="bi ${type === 'ok' ? 'bi-check-circle-fill' : 'bi-x-circle-fill'} t-ico"></i><span>${msg}</span>`;
    stack.appendChild(el);
    requestAnimationFrame(() => requestAnimationFrame(() => el.classList.add('show')));
    setTimeout(() => { el.classList.remove('show'); setTimeout(() => el.remove(), 250); }, 3200);
}

// ════════════════════════════════════════════
// Util
// ════════════════════════════════════════════
function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const existingLimit = document.getElementById('custLimit')?.textContent?.replace(/,/g, '').trim();
        if (existingLimit !== undefined && existingLimit !== null) {
            const pt = document.getElementById('paymentType');
            if (pt) {
                const creditOpt = pt.querySelector('option[value="Credit"]');
                if (creditOpt) creditOpt.disabled = Number(existingLimit) <= 0;
                if (pt.value === 'Credit' && creditOpt && creditOpt.disabled) pt.value = 'Cash';
            }
        }

        // Check if the success message and sound flag exist
        @if(session('success') && session('play_sound'))
            playSuccessSound();
        @endif
    });

    function playSuccessSound() {
        // Create a new Audio object with your MP3 file path
        var audio = new Audio('/public/sounds/cash.mp3'); // Put your MP3 in public/sounds/
        
        // Play the sound
        audio.play().catch(function(error) {
            // Handle autoplay restrictions gracefully
            console.log('Audio playback failed:', error);
            // You could show a play button as fallback
        });
    }
</script>
</body>
</html>