<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e(config("app.name")); ?> — Supplier Management</title>

    <?php echo $__env->make("links", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* =========================================================
           KESSY POS BLUE THEME
           Existing Blade markup, IDs, forms and JavaScript retained.
           ========================================================= */
        :root {
            --navy:         #084b87;
            --navy-mid:     #0756a6;
            --navy-light:   #0b66b2;
            --amber:        #0b65b1;
            --amber-pale:   #e8f2fc;
            --emerald:      #07866b;
            --emerald-pale: #e5f7f1;
            --rose:         #e5485d;
            --rose-pale:    #fff0f1;
            --violet:       #7844d6;
            --violet-pale:  #f1ebff;
            --sky:          #0874c5;
            --sky-pale:     #e8f3ff;
            --slate-50:     #f5f9fd;
            --slate-100:    #edf3f9;
            --slate-200:    #dce7f1;
            --slate-300:    #c5d5e5;
            --slate-400:    #8da4bb;
            --slate-500:    #607b96;
            --slate-600:    #45627e;
            --slate-700:    #2d4c69;
            --slate-800:    #183a5a;
            --white:        #ffffff;

            --font:   'DM Sans', system-ui, sans-serif;
            --mono:   'DM Mono', monospace;
            --r:      6px;
            --r-lg:   8px;
            --r-xl:   8px;
            --shadow-sm: 0 2px 8px rgba(8,61,108,.06);
            --shadow-md: 0 5px 16px rgba(8,61,108,.09);
            --shadow-lg: 0 10px 28px rgba(8,61,108,.13);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font);
            background: #f3f7fb;
            color: var(--slate-800);
            min-height: 100vh;
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        /* ── Layout ── */
        .wrap { padding: 1.1rem 1.25rem 1.5rem; max-width: 1800px; margin: 0 auto; }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .au  { animation: fadeUp 0.4s ease both; }
        .au1 { animation-delay: 0.03s; }
        .au2 { animation-delay: 0.08s; }
        .au5 { animation-delay: 0.16s; }

        /* ══════════════════════════════════════
           PAGE HEADER — flat white surface
        ══════════════════════════════════════ */
        .pg-header {
            background: transparent;
            border: 0;
            border-radius: 0;
            padding: .25rem 0 .9rem;
            margin-bottom: .55rem;
            box-shadow: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            position: relative;
            overflow: visible;
        }
        .pg-header::before, .pg-header::after { display: none; }

        .pg-left { display: flex; align-items: center; gap: .85rem; position: relative; z-index: 1; }

        .back-btn {
            width: 34px;
            height: 34px;
            flex-shrink: 0;
            border-radius: var(--r);
            background: #fff;
            border: 1px solid #d4e1ed;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--slate-500);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .back-btn:hover {
            background: #eff6ff;
            border-color: #0b68b4;
            color: var(--navy);
            transform: translateX(-2px);
        }

        .header-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--r-lg);
            background: #e8f2fc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #0866b2;
            flex-shrink: 0;
        }

        .pg-title-text h1 {
            font-size: 1.42rem;
            font-weight: 700;
            color: #064b86;
            letter-spacing: -0.35px;
            line-height: 1.2;
            margin: 0;
        }
        .pg-title-text h1 span { color: #0871c2; }
        .pg-title-text p {
            font-size: .78rem;
            color: #647f99;
            margin: 2px 0 0;
            font-weight: 400;
        }
        .pg-title-text p i { font-size: .72rem; }

        .pg-right { display: flex; gap: .5rem; align-items: center; position: relative; z-index: 1; }

        .btn-amber {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .58rem 1rem;
            border-radius: var(--r);
            background: #0866b2;
            color: #fff;
            font-family: var(--font);
            font-size: .78rem;
            font-weight: 600;
            border: 1px solid #0866b2;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 3px 8px rgba(8,102,178,.18);
            transition: all 0.2s ease;
            letter-spacing: .01em;
            white-space: nowrap;
        }
        .btn-amber:hover {
            transform: translateY(-1px);
            background: #064f91;
            border-color: #064f91;
            color: #fff;
            box-shadow: 0 5px 14px rgba(8,102,178,.28);
        }
        .btn-amber:active { transform: translateY(0); }
        .btn-amber i { font-size: .85rem; }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .5rem .8rem;
            border-radius: var(--r);
            background: #fff;
            border: 1px solid #d4e1ed;
            color: #42617e;
            font-family: var(--font);
            font-size: .75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-ghost:hover {
            background: #eff6ff;
            border-color: #0b68b4;
            color: #0756a6;
            transform: translateY(-1px);
        }
        .btn-ghost i { font-size: .82rem; }

        /* ══════════════════════════════════════
           FILTER SECTION
        ══════════════════════════════════════ */
        .filter-section {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: .7rem;
            margin-bottom: .85rem;
            align-items: stretch;
        }
        @media (max-width: 768px) { .filter-section { grid-template-columns: 1fr; } }

        .search-bar {
            background: #fff;
            border: 1px solid #d4e1ed;
            border-radius: var(--r);
            padding: .55rem .85rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            transition: all 0.2s ease;
            box-shadow: 0 2px 7px rgba(8,61,108,.045);
        }
        .search-bar:focus-within {
            border-color: #0b68b4;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }
        .search-bar i {
            color: var(--slate-400);
            font-size: .9rem;
            flex-shrink: 0;
        }
        .search-bar input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            font-family: var(--font);
            font-size: .78rem;
            color: var(--slate-800);
        }
        .search-bar input::placeholder { color: var(--slate-400); }

        .search-count {
            font-size: .72rem;
            color: var(--slate-500);
            background: #eff6ff;
            border-radius: 20px;
            padding: 3px 10px;
            font-family: var(--mono);
            flex-shrink: 0;
            font-weight: 600;
            border: 1px solid #cfe2f5;
        }
        .btn-clear {
            font-size: .72rem;
            font-weight: 600;
            padding: .35rem .7rem;
            background: #fff;
            border: 1px solid #d4e1ed;
            border-radius: var(--r);
            color: #42617e;
            cursor: pointer;
            transition: all 0.15s;
            flex-shrink: 0;
            font-family: inherit;
        }
        .btn-clear:hover {
            background: #eff6ff;
            border-color: #0b68b4;
            color: #0756a6;
        }

        .shop-filter-card {
            background: #fff;
            border: 1px solid #d4e1ed;
            border-radius: var(--r);
            padding: .5rem .85rem;
            box-shadow: 0 2px 7px rgba(8,61,108,.045);
            transition: all 0.2s;
        }
        .shop-filter-card:focus-within {
            border-color: #0b68b4;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }
        .shop-filter-card label {
            font-size: .62rem;
            font-weight: 700;
            color: var(--slate-500);
            margin-bottom: 3px;
            display: block;
            text-transform: uppercase;
            letter-spacing: .06em;
        }
        .shop-filter-card label i { font-size: .65rem; }
        .shop-filter-card select {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-family: var(--font);
            font-size: .78rem;
            color: var(--slate-800);
            cursor: pointer;
            padding: 0;
        }

        /* ══════════════════════════════════════
           STATS GRID
        ══════════════════════════════════════ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .7rem;
            margin-bottom: .85rem;
        }
        @media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 540px) { .stats-grid { grid-template-columns: 1fr; } }

        .stat-card {
            background: var(--white);
            border: 1px solid #dce7f1;
            border-radius: var(--r-lg);
            padding: .8rem .95rem;
            box-shadow: none;
            position: relative;
            overflow: hidden;
            transition: all 0.25s ease;
            cursor: pointer;
            min-height: 88px;
        }
        .stat-card::before { display: none; }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: #cfe0ef;
        }

        .sc-navy    { background: #eff6ff; }
        .sc-emerald { background: #effaf6; }
        .sc-sky     { background: #f1f5ff; }
        .sc-amber   { background: #fffaf0; }

        .stat-inner { display: flex; align-items: center; gap: .75rem; }
        .stat-icon-wrap {
            width: 43px;
            height: 43px;
            border-radius: 9px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
        }
        .si-navy    { background: #0866b2; }
        .si-emerald { background: var(--emerald); }
        .si-sky     { background: #7146cf; }
        .si-amber   { background: #b45309; }

        .stat-body { flex: 1; }
        .stat-label {
            font-size: .68rem;
            font-weight: 600;
            text-transform: none;
            letter-spacing: 0;
            color: #59748f;
            margin-bottom: .18rem;
        }
        .stat-value {
            font-family: var(--font);
            font-size: 1.45rem;
            font-weight: 700;
            color: #084b87;
            letter-spacing: -.35px;
            line-height: 1.12;
            font-variant-numeric: tabular-nums;
        }

        /* ══════════════════════════════════════
           PANEL / TABLE
        ══════════════════════════════════════ */
        .panel {
            background: var(--white);
            border: 1px solid #dce7f1;
            border-radius: var(--r-lg);
            box-shadow: 0 2px 7px rgba(8,61,108,.045);
            overflow: hidden;
            transition: box-shadow 0.2s;
        }
        .panel:hover { box-shadow: var(--shadow-md); }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .75rem .9rem;
            border-bottom: 1px solid #dce7f1;
            background: #fff;
            gap: .85rem;
            flex-wrap: wrap;
        }
        .panel-head-left { display: flex; align-items: center; gap: .6rem; }
        .panel-head-icon {
            width: 28px;
            height: 28px;
            border-radius: var(--r);
            background: #e8f2fc;
            color: #0866b2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
        }
        .panel-title {
            font-size: .96rem;
            font-weight: 700;
            color: var(--navy);
        }
        .result-pill {
            font-size: .68rem;
            font-weight: 700;
            font-family: var(--mono);
            background: #eff6ff;
            color: #0756a6;
            padding: 2px 9px;
            border-radius: 20px;
            border: 1px solid #cfe2f5;
        }

        .panel-tools { display: flex; gap: .4rem; align-items: center; }
        .tool-btn {
            width: 30px;
            height: 30px;
            border-radius: var(--r);
            border: 1px solid #d4e1ed;
            background: #fff;
            color: #42617e;
            font-size: .82rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .tool-btn:hover {
            background: #eff6ff;
            color: #0756a6;
            border-color: #0b68b4;
            transform: translateY(-1px);
        }

        /* Table */
        .tbl-wrap {
            overflow-x: auto;
            max-height: 600px;
            overflow-y: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: .76rem;
        }

        thead th {
            background: #07569b;
            color: #fff;
            font-size: .66rem;
            font-weight: 600;
            text-transform: none;
            letter-spacing: .015em;
            padding: .62rem .68rem;
            white-space: nowrap;
            border: 1px solid rgba(255,255,255,.13);
            position: sticky;
            top: 0;
            z-index: 10;
            text-align: left;
        }
        thead th:last-child { text-align: center; }

        tbody tr { transition: all 0.15s ease; }
        tbody tr:nth-child(even) td { background: #f8fbfe; }
        tbody tr:hover td { background: #edf6ff; }

        td {
            padding: .52rem .68rem;
            vertical-align: middle;
            border: 1px solid #e5edf5;
            color: #244866;
            font-size: .74rem;
        }

        .idx {
            font-size: .72rem;
            color: var(--slate-400);
            font-family: var(--mono);
            font-weight: 500;
        }

        .vendor-avatar {
            width: 34px;
            height: 34px;
            border-radius: 7px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #0756a6 0%, #0870bd 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .72rem;
            font-weight: 700;
        }
        .vendor-cell { display: flex; align-items: center; gap: .6rem; }
        .vendor-name {
            font-weight: 600;
            color: #164a78;
            font-size: .76rem;
            margin-bottom: .08rem;
        }
        .vendor-loc {
            font-size: .68rem;
            color: #7189a0;
            display: flex;
            align-items: center;
            gap: .25rem;
        }
        .vendor-loc i { font-size: .62rem; }

        .contact-val {
            font-size: .74rem;
            color: #31516e;
            font-family: var(--mono);
        }
        .contact-val i { font-size: .65rem; color: var(--slate-400); }

        .type-badge {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            padding: .22rem .55rem;
            border-radius: 20px;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .01em;
        }
        .type-badge i { font-size: .66rem; }
        .tb-wholesale    { background: var(--sky-pale);     color: #07569b; }
        .tb-manufacturer { background: var(--violet-pale);  color: var(--violet); }
        .tb-distributor  { background: var(--emerald-pale); color: #056f58; }
        .tb-retailer     { background: var(--amber-pale);   color: #07569b; }
        .tb-default      { background: var(--slate-100);    color: var(--slate-600); }

        .credit-pos {
            font-family: var(--mono);
            font-weight: 700;
            color: var(--emerald);
            font-size: .74rem;
        }
        .credit-nil {
            font-family: var(--mono);
            font-weight: 500;
            color: var(--slate-400);
            font-size: .74rem;
        }

        /* Action buttons */
        .action-btns { display: flex; gap: .28rem; justify-content: flex-end; }
        .act-btn {
            width: 28px;
            height: 27px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .72rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid;
            background: #fff;
            font-family: inherit;
        }
        .ab-view   { border-color: #cfe2f5; color: #07569b; }
        .ab-edit   { border-color: #cfe2f5; color: #0756a6; }
        .ab-delete { border-color: #f5b8c2; color: #c9314a; }
        .ab-view:hover   { background: #eff6ff; border-color: #0b68b4; transform: translateY(-1px); }
        .ab-edit:hover   { background: #eff6ff; border-color: #0b68b4; transform: translateY(-1px); }
        .ab-delete:hover { background: var(--rose); color: #fff; border-color: var(--rose); transform: translateY(-1px); }

        /* Bottom CTA */
        .panel-footer {
            padding: .85rem 1rem;
            border-top: 1px solid #dce7f1;
            display: flex;
            justify-content: center;
            background: #fafdff;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--slate-400);
        }
        .empty-state i {
            font-size: 2.8rem;
            display: block;
            margin-bottom: .8rem;
            opacity: 0.3;
        }
        .empty-state h4 {
            font-size: .92rem;
            font-weight: 700;
            color: var(--slate-600);
            margin-bottom: .3rem;
        }
        .empty-state p {
            font-size: .78rem;
            margin-bottom: 1.1rem;
            color: var(--slate-500);
        }

        /* ══════════════════════════════════════
           MODAL STYLES
        ══════════════════════════════════════ */
        .modal.fade .modal-dialog {
            transform: scale(0.95) translateY(-30px);
            transition: transform 0.25s ease-out, opacity 0.2s ease-out;
        }
        .modal.show .modal-dialog { transform: scale(1) translateY(0); }
        .modal-content {
            border: none;
            border-radius: var(--r-lg);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            background: linear-gradient(120deg, #064b86, #0870bd);
            padding: .9rem 1.2rem;
            border-bottom: none;
        }
        .modal-header .modal-title {
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .modal-header .modal-title i {
            width: 30px;
            height: 30px;
            border-radius: var(--r);
            background: rgba(255,255,255,.15);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: .85rem;
        }
        .modal-header h5 {
            font-size: .92rem;
            font-weight: 700;
            color: #fff;
            margin: 0;
            letter-spacing: -.2px;
        }
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.7;
            transition: opacity 0.2s;
        }
        .modal-header .btn-close:hover { opacity: 1; }

        .modal-body {
            padding: 1.2rem;
            background: #fff;
        }

        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .85rem;
            margin-bottom: .85rem;
        }
        @media (max-width: 560px) { .field-row { grid-template-columns: 1fr; gap: .7rem; } }

        .field { margin-bottom: .85rem; }
        .field:last-child { margin-bottom: 0; }

        .field-label {
            display: block;
            font-size: .66rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #55728e;
            margin-bottom: .35rem;
        }
        .field-label .req { color: var(--rose); margin-left: 2px; }

        .field-input {
            width: 100%;
            font-family: var(--font);
            font-size: .78rem;
            padding: .55rem .75rem;
            border: 1px solid #d4e1ed;
            border-radius: var(--r);
            background: #fff;
            color: var(--slate-800);
            outline: none;
            transition: all 0.2s ease;
        }
        .field-input:focus {
            border-color: #0b68b4;
            box-shadow: 0 0 0 3px rgba(11,104,180,.09);
        }
        .field-input::placeholder { color: var(--slate-400); }
        select.field-input {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%238da4bb' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 2rem;
        }
        textarea.field-input { resize: vertical; min-height: 70px; }

        .modal-divider {
            height: 1px;
            background: #dce7f1;
            margin: .9rem 0;
            border: none;
        }
        .modal-section-label {
            font-size: .66rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--slate-500);
            margin-bottom: .6rem;
            display: flex;
            align-items: center;
            gap: .4rem;
        }
        .modal-section-label i { font-size: .7rem; color: #0866b2; }

        .btn-submit {
            width: 100%;
            padding: .65rem 1rem;
            margin-top: .5rem;
            background: #0866b2;
            color: #fff;
            font-family: var(--font);
            font-size: .8rem;
            font-weight: 600;
            border: 1px solid #0866b2;
            border-radius: var(--r);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            box-shadow: 0 3px 8px rgba(8,102,178,.18);
            transition: all 0.2s ease;
        }
        .btn-submit:hover {
            background: #064f91;
            border-color: #064f91;
            transform: translateY(-1px);
            box-shadow: 0 5px 14px rgba(8,102,178,.28);
        }
        .btn-submit:active { transform: translateY(0); }

        /* ── Dropdown menu ── */
        .dropdown-menu {
            border: 1px solid #dce7f1;
            border-radius: var(--r);
            box-shadow: var(--shadow-lg);
            padding: .3rem 0;
            min-width: 160px;
        }
        .dropdown-item {
            padding: .5rem .9rem;
            font-size: .76rem;
            font-weight: 500;
            color: var(--slate-700);
            display: flex;
            align-items: center;
            gap: .5rem;
            transition: all 0.15s ease;
        }
        .dropdown-item i { font-size: .78rem; color: var(--slate-500); }
        .dropdown-item:hover {
            background: #eff6ff;
            color: #0756a6;
        }
        .dropdown-item:hover i { color: #0866b2; }

        /* ── Responsive mobile card table ── */
        @media (max-width: 720px) {
            .wrap { padding: .8rem; }
            .pg-header { padding: .25rem 0 .7rem; }
            .pg-title-text h1 { font-size: 1.2rem; }
            table thead { display: none; }
            tbody tr {
                display: block;
                margin-bottom: .7rem;
                border: 1px solid #dce7f1;
                border-radius: var(--r-lg);
                padding: .7rem;
                background: #fff;
            }
            tbody tr td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: .4rem 0;
                border: none;
                border-bottom: 1px solid #edf3f9;
            }
            tbody tr td:last-child {
                border-bottom: none;
                padding-top: .55rem;
                margin-top: .4rem;
                border-top: 1px solid #dce7f1;
            }
            tbody tr td::before {
                content: attr(data-label);
                font-size: .62rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .05em;
                color: var(--slate-500);
                min-width: 90px;
            }
            .action-btns { justify-content: flex-end; }
        }

        @media print {
            .d-print-none, .pg-right, .search-bar, .panel-tools, .action-btns, .panel-footer, .filter-section { display: none !important; }
            body { background: white; }
            .panel { box-shadow: none; border: 1px solid #ccc; }
            .stat-card { break-inside: avoid; }
        }
    </style>
</head>
<body>
<?php echo $__env->make("sidenav", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<main class="main-content">
            <div class="wrap">

                
                <div class="pg-header au au1">
                    <div class="pg-left">
                        <a href="#" onclick="history.back()" class="back-btn d-print-none">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <div class="header-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="pg-title-text">
                            <h1>Shops <span>/ Suppliers</span></h1>
                            <p><i class="bi bi-database"></i> <?php echo e(count($fetch)); ?> active suppliers</p>
                        </div>
                    </div>
                    <div class="pg-right">
                        <button class="btn-ghost d-print-none" onclick="window.print()">
                            <i class="bi bi-printer"></i> Print
                        </button>
                        <a href="/settings" class="btn-amber" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                            <i class="bi bi-plus-lg"></i> New Shop
                        </a>
                    </div>
                </div>

                
                <div class="filter-section au au2">
                    <div class="search-bar">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" placeholder="Search by name, contact, or business type…">
                        <span class="search-count" id="searchCount"><?php echo e(count($fetch)); ?> results</span>
                        <button class="btn-clear" id="clearBtn">Clear</button>
                    </div>

                    <?php if(isset($shops) && count($shops) > 0): ?>
                    <div class="shop-filter-card">
                        <label><i class="bi bi-shop"></i> Filter by Shop</label>
                        <form method="GET" action="<?php echo e(url('vendors')); ?>" id="shopFilterForm">
                            <select name="shop" id="shopFilter">
                                <option value="">All Shops</option>
                                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($shop['id']); ?>" <?php echo e((isset($selectedShopId) && $selectedShopId == $shop['id']) ? 'selected' : ''); ?>>
                                        🏪 <?php echo e($shop['name']); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>

                
                <div class="panel au au5">
                    <div class="panel-head">
                        <div class="panel-head-left">
                            <div class="panel-head-icon"><i class="bi bi-table"></i></div>
                            <span class="panel-title">Shops</span>
                            <span class="result-pill" id="tableCount"><?php echo e(count($fetch)); ?></span>
                        </div>
                        <div class="panel-tools">
                            <div class="dropdown">
                                <a class="tool-btn" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="window.print()">
                                            <i class="bi bi-printer"></i> Print
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="downloadReport()">
                                            <i class="bi bi-download"></i> Export
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <?php if($fetch->isEmpty()): ?>
                    <div class="empty-state">
                        <i class="bi bi-inbox"></i>
                        <h4>No suppliers found</h4>
                        <p>Get started by adding your first supplier to the system</p>
                        <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                            <i class="bi bi-plus-lg"></i> Add Supplier
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="tbl-wrap">
                        <table id="vendorTable">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Shop Name</th>
                                    <th>Contact</th>
                                    <th>Type</th>
                                    <th>Credit (TSh)</th>
                                    <th width="15%" style="text-align:center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="vendorBody">
                                <?php $__currentLoopData = $fetch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $initials = strtoupper(substr($vendor->name, 0, 1));
                                    $parts    = explode(' ', trim($vendor->name));
                                    if (count($parts) > 1) $initials .= strtoupper(substr($parts[1], 0, 1));
                                    $type = $vendor->businessType ?? '';
                                    $typeCls = match($type) {
                                        'Wholesale'    => 'tb-wholesale',
                                        'Manufacturer' => 'tb-manufacturer',
                                        'Distributor'  => 'tb-distributor',
                                        'Retailer'     => 'tb-retailer',
                                        default        => 'tb-default',
                                    };
                                    $typeIcon = match($type) {
                                        'Wholesale'    => '🏪',
                                        'Manufacturer' => '🏭',
                                        'Distributor'  => '🚚',
                                        'Retailer'     => '🛍️',
                                        default        => '📦',
                                    };
                                ?>
                                <tr data-search="<?php echo e(strtolower($vendor->name . ' ' . $vendor->contact . ' ' . $vendor->businessType . ' ' . $vendor->location)); ?>">
                                    <td data-label="#" class="idx"><?php echo e($index + 1); ?></td>
                                    <td data-label="Supplier">
                                        <div class="vendor-cell">
                                            <div class="vendor-avatar"><?php echo e($initials); ?></div>
                                            <div>
                                                <div class="vendor-name"><?php echo e($vendor->name); ?></div>
                                                <div class="vendor-loc">
                                                    <i class="bi bi-geo-alt-fill"></i> <?php echo e($vendor->location); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Contact">
                                        <div class="contact-val">
                                            <i class="bi bi-telephone-fill"></i> <?php echo e($vendor->contact); ?>

                                        </div>
                                    </td>
                                    <td data-label="Type">
                                        <span class="type-badge <?php echo e($typeCls); ?>">
                                            <i class="bi bi-tag-fill"></i> <?php echo e($type); ?>

                                        </span>
                                    </td>
                                    <td data-label="Credit" class="<?php echo e($vendor->credit > 0 ? 'credit-pos' : 'credit-nil'); ?>">
                                        <?php echo e(number_format($vendor->credit)); ?>

                                    </td>
                                    <td data-label="Actions">
                                        <div class="action-btns">
                                            <form action="" method="post" style="display:contents;">
                                                <?php echo csrf_field(); ?>
                                                <button formaction="viewVendor" class="act-btn ab-view"
                                                        name="vendorId" value="<?php echo e($vendor->id); ?>" title="View Details">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <?php if(canUser('manage_suppliers')): ?>

                                                <button type="button" class="act-btn ab-edit"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editVendor<?php echo e($vendor->id); ?>" title="Edit Supplier">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button formaction="dltVendeor" class="act-btn ab-delete"
                                                        name="product_id" value="<?php echo e($vendor->id); ?>"
                                                        onclick="return confirm('⚠️ Delete this supplier? This action cannot be undone.')"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                
                                <div class="modal fade" id="editVendor<?php echo e($vendor->id); ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="modal-title">
                                                    <i class="bi bi-pencil-fill"></i>
                                                    <h5>Edit Shop</h5>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="updateVendor" method="post">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="vendor_id" value="<?php echo e($vendor->id); ?>">

                                                    <div class="field-row">
                                                        <div class="field">
                                                            <label class="field-label">Name <span class="req">*</span></label>
                                                            <input type="text" class="field-input" name="name" value="<?php echo e($vendor->name); ?>" required>
                                                        </div>
                                                        <div class="field">
                                                            <label class="field-label">Contact <span class="req">*</span></label>
                                                            <input type="text" class="field-input" name="contact" value="<?php echo e($vendor->contact); ?>" required>
                                                        </div>
                                                    </div>

                                                    <div class="field">
                                                        <label class="field-label">Address <span class="req">*</span></label>
                                                        <input type="text" class="field-input" name="address" value="<?php echo e($vendor->location); ?>" required>
                                                    </div>

                                                    <div class="field-row">
                                                        <div class="field">
                                                            <label class="field-label">Business Type <span class="req">*</span></label>
                                                            <select name="type" class="field-input" required>
                                                                <option value="Wholesale"    <?php echo e($vendor->businessType == 'Wholesale'    ? 'selected' : ''); ?>>Wholesale</option>
                                                                <option value="Manufacturer" <?php echo e($vendor->businessType == 'Manufacturer' ? 'selected' : ''); ?>>Manufacturer</option>
                                                                <option value="Distributor"  <?php echo e($vendor->businessType == 'Distributor'  ? 'selected' : ''); ?>>Distributor</option>
                                                                <option value="Retailer"     <?php echo e($vendor->businessType == 'Retailer'     ? 'selected' : ''); ?>>Retailer</option>
                                                            </select>
                                                        </div>
                                                        <div class="field">
                                                            <label class="field-label">Bank Name</label>
                                                            <input type="text" class="field-input" name="bank" value="<?php echo e($vendor->bank); ?>" placeholder="Optional">
                                                        </div>
                                                    </div>

                                                    <div class="field">
                                                        <label class="field-label">Account Number</label>
                                                        <input type="text" class="field-input" name="account" value="<?php echo e($vendor->account); ?>" placeholder="Optional">
                                                    </div>

                                                    <div class="field">
                                                        <label class="field-label">Description</label>
                                                        <textarea name="description" class="field-input" placeholder="Additional information about this supplier"><?php echo e($vendor->description); ?></textarea>
                                                    </div>

                                                    <button type="submit" class="btn-submit">
                                                        <i class="bi bi-check-circle-fill"></i> Update Supplier
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="panel-footer">
                        <button class="btn-amber" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                            <i class="bi bi-plus-lg"></i> Add New Shop
                        </button>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </main>


<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="bi bi-person-plus-fill"></i>
                    <h5>Add New Supplier</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="newVendor" method="post" id="addSupplierForm">
                    <?php echo csrf_field(); ?>

                    <div class="modal-section-label">
                        <i class="bi bi-info-circle-fill"></i> Basic Information
                    </div>
                    <div class="field-row">
                        <div class="field">
                            <label class="field-label">Supplier name <span class="req">*</span></label>
                            <input type="text" class="field-input" name="name" placeholder="e.g. Kariakoo Traders" required>
                        </div>
                        <div class="field">
                            <label class="field-label">Contact <span class="req">*</span></label>
                            <input type="text" class="field-input" name="contact" placeholder="Phone number or email" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Business address <span class="req">*</span></label>
                        <input type="text" class="field-input" name="address" placeholder="Physical location" required>
                    </div>

                    <div class="field">
                        <label class="field-label">Business type <span class="req">*</span></label>
                        <select name="type" class="field-input" required>
                            <option value="" disabled selected>Select business type</option>
                            <option value="Wholesale">🏪 Wholesale</option>
                            <option value="Manufacturer">🏭 Manufacturer</option>
                            <option value="Distributor">🚚 Distributor</option>
                            <option value="Retailer">🛍️ Retailer</option>
                        </select>
                    </div>

                    <hr class="modal-divider">

                    <div class="modal-section-label">
                        <i class="bi bi-bank2"></i> Banking Information (Optional)
                    </div>

                    <div class="field-row">
                        <div class="field">
                            <label class="field-label">Bank name</label>
                            <input type="text" class="field-input" name="bank" placeholder="e.g. CRDB, NMB, NBC">
                        </div>
                        <div class="field">
                            <label class="field-label">Account number</label>
                            <input type="text" class="field-input" name="account" placeholder="Bank account number">
                        </div>
                    </div>

                    <div class="field">
                        <label class="field-label">Description</label>
                        <textarea name="description" class="field-input" placeholder="Additional notes, payment terms, or special requirements..."></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="bi bi-save-fill"></i> Save Supplier
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-submit when shop filter changes
    const shopFilter = document.getElementById('shopFilter');
    if (shopFilter) {
        shopFilter.addEventListener('change', function () {
            this.form.submit();
        });
    }

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearBtn');
    const rows = document.querySelectorAll('#vendorBody tr[data-search]');
    const countEl = document.getElementById('searchCount');
    const tableCountEl = document.getElementById('tableCount');

    function updateCount(visible) {
        const text = visible + ' result' + (visible !== 1 ? 's' : '');
        if (countEl) countEl.textContent = text;
        if (tableCountEl) tableCountEl.textContent = visible;
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            let visible = 0;
            rows.forEach(row => {
                const match = !query || row.dataset.search.includes(query);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            updateCount(visible);
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            if (searchInput) {
                searchInput.value = '';
                const event = new Event('input');
                searchInput.dispatchEvent(event);
                searchInput.focus();
            }
            if (shopFilter && shopFilter.value !== '') {
                shopFilter.value = '';
                shopFilter.form.submit();
            }
        });
    }


    // Initialize tooltips if any
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide modals on form submit (optional)
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                setTimeout(() => {
                    const modals = document.querySelectorAll('.modal.show');
                    modals.forEach(modal => {
                        const bsModal = bootstrap.Modal.getInstance(modal);
                        if (bsModal) bsModal.hide();
                    });
                }, 100);
            });
        });
    });
</script>

<?php if(session('success')): ?>
<script>
    // Show success notification if needed
    alert('<?php echo e(session('success')); ?>');
</script>
<?php endif; ?>

<?php if($errors->any()): ?>
<script>
    // Show error notification if needed
    alert('<?php echo e($errors->first()); ?>');
</script>
<?php endif; ?>
<?php echo $__env->make('footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</body>
</html><?php /**PATH C:\Users\HomePC\Music\leruma pos\resources\views/vendors.blade.php ENDPATH**/ ?>