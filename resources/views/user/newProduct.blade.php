<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} — Add Product</title>
    @include("links")
    <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- SheetJS for Excel parsing -->
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>

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

        @keyframes fadeUp { from { opacity:0; transform:translateY(12px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        .au  { animation: fadeUp 0.35s ease both; }
        .au1 { animation-delay:0.04s; } .au2 { animation-delay:0.10s; }
        .au3 { animation-delay:0.15s; } .au4 { animation-delay:0.20s; }

        /* ── Alerts ── */
        .alert { display:flex; align-items:center; justify-content:space-between; padding:.75rem 1rem; border-radius:var(--r); margin-bottom:1rem; font-size:13px; font-weight:500; }
        .alert-success { background:var(--emerald-pale); color:var(--emerald); border-left:3px solid var(--emerald); }
        .alert-danger  { background:var(--rose-pale);    color:var(--rose);    border-left:3px solid var(--rose); }
        .close-btn { background:none; border:none; cursor:pointer; color:inherit; font-size:16px; opacity:.6; }
        .close-btn:hover { opacity:1; }

        /* ══ PAGE HEADER ══ */
        .pg-header {
            background: var(--navy); border-radius: var(--r-xl);
            padding: 1.2rem 1.6rem; margin-bottom: 1.4rem;
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem; flex-wrap: wrap; position: relative; overflow: hidden;
        }
        .pg-header::before { content:''; position:absolute; top:-50px; right:-30px; width:180px; height:180px; border-radius:50%; background:var(--navy-light); opacity:.45; pointer-events:none; }
        .pg-header::after  { content:''; position:absolute; bottom:-55px; right:100px; width:120px; height:120px; border-radius:50%; background:var(--amber); opacity:.07; pointer-events:none; }
        .pg-left { display:flex; align-items:center; gap:10px; position:relative; z-index:1; }
        .header-icon { width:40px; height:40px; border-radius:var(--r); background:var(--amber); display:flex; align-items:center; justify-content:center; font-size:18px; color:var(--navy); flex-shrink:0; }
        .pg-title-text h1 { font-size:16px; font-weight:700; color:var(--white); letter-spacing:-.2px; }
        .pg-title-text p  { font-size:12px; color:rgba(255,255,255,.45); margin-top:1px; }
        .pg-right { position:relative; z-index:1; }

        .btn-cancel-hdr { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:var(--r); background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.18); color:rgba(255,255,255,.8); font-family:var(--font); font-size:13px; font-weight:500; text-decoration:none; transition:all .15s; }
        .btn-cancel-hdr:hover { background:rgba(255,255,255,.16); color:var(--white); }

        /* ══ TABS ══ */
        .tab-shell { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-xl); overflow:hidden; box-shadow:0 1px 4px rgba(11,30,61,.05); margin-bottom:1.5rem; }

        .tab-nav { display:flex; border-bottom:1.5px solid var(--slate-200); background:var(--slate-50); }
        .tab-btn {
            flex:1; display:flex; align-items:center; justify-content:center; gap:7px;
            padding:1rem 1.5rem; background:transparent; border:none; cursor:pointer;
            font-family:var(--font); font-size:13px; font-weight:500; color:var(--slate-400);
            position:relative; transition:all .2s;
        }
        .tab-btn i { font-size:15px; }
        .tab-btn:hover { color:var(--slate-700); background:rgba(11,30,61,.02); }
        .tab-btn.active { color:var(--navy); font-weight:700; background:var(--white); }
        .tab-btn.active::after { content:''; position:absolute; bottom:-1.5px; left:0; right:0; height:2.5px; background:var(--amber); border-radius:2px 2px 0 0; }

        .tab-pane { display:none; padding:1.5rem; animation:fadeIn .25s ease; }
        .tab-pane.active { display:block; }

        /* ══ FORM SECTIONS ══ */
        .form-section {
            background:var(--white); border:1.5px solid var(--slate-200);
            border-radius:var(--r-lg); padding:1.4rem 1.5rem;
            margin-bottom:1.25rem; box-shadow:0 1px 4px rgba(11,30,61,.04);
        }
        .section-head {
            display:flex; align-items:center; gap:9px;
            margin-bottom:1.25rem; padding-bottom:.875rem;
            border-bottom:1.5px solid var(--slate-200);
        }
        .section-head-icon { width:30px; height:30px; border-radius:var(--r); display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0; }
        .shi-amber   { background:var(--amber-pale);   color:#92400e; }
        .shi-navy    { background:rgba(11,30,61,.08);  color:var(--navy-light); }
        .shi-emerald { background:var(--emerald-pale); color:var(--emerald); }
        .shi-violet  { background:var(--violet-pale);  color:var(--violet); }
        .shi-sky     { background:var(--sky-pale);     color:var(--sky); }
        .shi-rose    { background:var(--rose-pale);    color:var(--rose); }

        .section-title { font-size:14px; font-weight:700; color:var(--navy); }

        /* ── Fields ── */
        .field-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .field-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; }
        @media(max-width:680px){ .field-grid-2, .field-grid-3 { grid-template-columns:1fr; } }

        .field { display:flex; flex-direction:column; gap:5px; margin-bottom:.1rem; }

        .field-label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--slate-500); }
        .field-label .req { color:var(--rose); }

        .field-input, .field-select, .field-textarea {
            width:100%; font-family:var(--font); font-size:13.5px;
            padding:9px 12px; border:1.5px solid var(--slate-200);
            border-radius:var(--r); background:var(--white);
            color:var(--slate-800); outline:none;
            transition:border-color .18s, box-shadow .18s;
        }
        .field-input:focus, .field-select:focus, .field-textarea:focus {
            border-color:var(--navy-light);
            box-shadow:0 0 0 3px rgba(26,58,107,.1);
        }
        .field-input::placeholder, .field-textarea::placeholder { color:var(--slate-400); }
        .field-textarea { resize:vertical; min-height:100px; }
        .field-select {
            appearance:none; cursor:pointer;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 12px center; padding-right:2.25rem;
        }

        /* Input with prefix */
        .input-prefix-wrap { display:flex; border:1.5px solid var(--slate-200); border-radius:var(--r); overflow:hidden; transition:border-color .18s, box-shadow .18s; }
        .input-prefix-wrap:focus-within { border-color:var(--navy-light); box-shadow:0 0 0 3px rgba(26,58,107,.1); }
        .input-prefix { background:var(--slate-100); padding:9px 12px; font-size:13px; font-weight:600; color:var(--slate-500); border-right:1.5px solid var(--slate-200); white-space:nowrap; }
        .input-suffix { background:var(--slate-100); padding:9px 12px; font-size:13px; font-weight:600; color:var(--slate-500); border-left:1.5px solid var(--slate-200); white-space:nowrap; }
        .input-prefix-wrap input { flex:1; border:none; outline:none; padding:9px 12px; font-family:var(--mono); font-size:13.5px; color:var(--slate-800); background:transparent; }

        /* ── Image upload ── */
        .img-upload-zone {
            border:2px dashed var(--slate-300); border-radius:var(--r-lg);
            padding:2rem; text-align:center; cursor:pointer;
            transition:all .2s; background:var(--slate-50); position:relative;
        }
        .img-upload-zone:hover { border-color:var(--navy-light); background:rgba(26,58,107,.03); }
        .img-upload-zone.has-image { border-style:solid; border-color:var(--emerald); background:var(--emerald-pale); }
        .img-upload-zone i { font-size:2rem; color:var(--slate-300); display:block; margin-bottom:.5rem; transition:color .2s; }
        .img-upload-zone:hover i { color:var(--navy-light); }
        .img-upload-zone p { font-size:13px; font-weight:500; color:var(--slate-500); margin-bottom:3px; }
        .img-upload-zone small { font-size:11.5px; color:var(--slate-400); }
        .img-upload-zone input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        #imagePreview { max-height:120px; border-radius:var(--r); margin-bottom:.5rem; display:none; }

        /* ── Save button ── */
        .btn-save {
            width:100%; padding:13px; margin-top:.5rem;
            background:var(--navy); color:var(--white); border:none;
            border-radius:var(--r-lg); font-family:var(--font); font-size:14px; font-weight:700;
            cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;
            box-shadow:0 4px 16px rgba(11,30,61,.2); transition:all .18s;
        }
        .btn-save:hover { background:var(--navy-light); transform:translateY(-1px); box-shadow:0 6px 20px rgba(11,30,61,.28); }
        .btn-save i { font-size:16px; }

        /* ══ EXCEL TAB ══ */
        .excel-drop-zone {
            border:2px dashed var(--slate-300); border-radius:var(--r-xl);
            padding:3rem 2rem; text-align:center; cursor:pointer;
            transition:all .2s; background:var(--slate-50); position:relative;
            margin-bottom:1.25rem;
        }
        .excel-drop-zone:hover { border-color:var(--emerald); background:rgba(5,150,105,.03); }
        .excel-drop-zone.file-loaded { border-style:solid; border-color:var(--emerald); background:var(--emerald-pale); }
        .excel-drop-icon { font-size:3rem; color:var(--emerald); display:block; margin-bottom:.75rem; }
        .excel-drop-zone p { font-size:14px; font-weight:600; color:var(--slate-600); margin-bottom:4px; }
        .excel-drop-zone small { font-size:12px; color:var(--slate-400); }
        .excel-drop-zone input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }

        .dl-template-btn {
            display:inline-flex; align-items:center; gap:6px;
            padding:8px 16px; border-radius:var(--r); margin-top:1rem;
            background:var(--navy); color:var(--white); border:none;
            font-family:var(--font); font-size:13px; font-weight:600;
            text-decoration:none; cursor:pointer; transition:all .15s;
        }
        .dl-template-btn:hover { background:var(--navy-light); color:var(--white); }

        /* Columns reference table */
        .ref-table-wrap { overflow-x:auto; border-radius:var(--r-lg); border:1.5px solid var(--slate-200); }
        .ref-table { width:100%; border-collapse:collapse; font-size:12.5px; }
        .ref-table thead th { background:var(--navy); color:rgba(255,255,255,.7); font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; padding:9px 14px; white-space:nowrap; border:none; text-align:left; }
        .ref-table tbody td { padding:9px 14px; border-bottom:1px solid var(--slate-100); vertical-align:top; }
        .ref-table tbody tr:last-child td { border-bottom:none; }
        .ref-table tbody tr:hover td { background:var(--slate-50); }

        .ref-group-row td { background:var(--slate-50); padding:7px 14px; border-top:1.5px solid var(--slate-200); border-bottom:1px solid var(--slate-200); }
        .ref-group-label { font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; display:flex; align-items:center; gap:6px; }
        .rg-auto   { color:var(--emerald); }
        .rg-req    { color:var(--rose); }
        .rg-opt    { color:var(--violet); }

        .pill-auto { display:inline-block; padding:2px 9px; border-radius:20px; font-size:10.5px; font-weight:700; background:var(--emerald-pale); color:var(--emerald); }
        .pill-req  { display:inline-block; padding:2px 9px; border-radius:20px; font-size:10.5px; font-weight:700; background:var(--rose-pale);    color:var(--rose); }
        .pill-opt  { display:inline-block; padding:2px 9px; border-radius:20px; font-size:10.5px; font-weight:700; background:var(--violet-pale);  color:var(--violet); }

        .ref-col-name { font-weight:600; color:var(--navy); }
        .ref-default  { font-family:var(--mono); font-size:12px; color:var(--slate-500); }
        .ref-notes    { color:var(--slate-600); font-size:12px; }

        /* Tips / warnings grid */
        .tips-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-top:1.25rem; }
        @media(max-width:620px){ .tips-grid { grid-template-columns:1fr; } }

        .tip-card { padding:1rem 1.1rem; border-radius:var(--r-lg); }
        .tip-card.navy  { background:rgba(11,30,61,.05); border-left:3px solid var(--navy-light); }
        .tip-card.amber { background:var(--amber-pale);  border-left:3px solid var(--amber); }
        .tip-card h6 { font-size:12.5px; font-weight:700; margin-bottom:.5rem; display:flex; align-items:center; gap:6px; }
        .tip-card.navy h6  { color:var(--navy); }
        .tip-card.amber h6 { color:#92400e; }
        .tip-card ul { margin:0; padding-left:1.1rem; font-size:12px; color:var(--slate-600); line-height:1.9; }
        .tip-card code { background:rgba(0,0,0,.06); padding:1px 5px; border-radius:4px; font-family:var(--mono); font-size:11px; }

        /* File loaded state */
        .file-loaded-msg { text-align:center; padding:1.5rem; }
        .file-loaded-msg i { font-size:2rem; color:var(--emerald); display:block; margin-bottom:.5rem; }
        .file-loaded-msg p { font-size:13px; font-weight:600; color:var(--slate-800); }
        .file-loaded-msg small { font-size:12px; color:var(--slate-400); font-family:var(--mono); }

        /* Preview table */
        .preview-section { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-lg); overflow:hidden; margin-bottom:1.25rem; display:none; }
        .preview-head { padding:.75rem 1.2rem; background:var(--slate-50); border-bottom:1.5px solid var(--slate-200); font-size:13px; font-weight:700; color:var(--navy); display:flex; align-items:center; gap:8px; }
        .preview-tbl-wrap { overflow-x:auto; }
        .preview-tbl { width:100%; border-collapse:collapse; font-size:12.5px; }
        .preview-tbl thead th { background:var(--navy); color:rgba(255,255,255,.7); font-size:10.5px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; padding:8px 13px; border:none; text-align:left; white-space:nowrap; }
        .preview-tbl tbody td { padding:9px 13px; border-bottom:1px solid var(--slate-100); }
        .preview-tbl tbody tr:last-child td { border-bottom:none; }
        .preview-tbl tbody tr:hover td { background:var(--slate-50); }
        .more-rows { text-align:center; padding:8px; font-size:12px; color:var(--slate-400); font-family:var(--mono); }

        .btn-upload {
            width:100%; padding:13px; margin-top:.5rem;
            background:var(--emerald); color:var(--white); border:none;
            border-radius:var(--r-lg); font-family:var(--font); font-size:14px; font-weight:700;
            cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;
            box-shadow:0 4px 16px rgba(5,150,105,.2); transition:all .18s;
        }
        .btn-upload:hover:not(:disabled) { background:#047857; transform:translateY(-1px); }
        .btn-upload:disabled { background:var(--slate-300); color:var(--white); cursor:not-allowed; box-shadow:none; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @include("user/sidenav")

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="wrap">

                {{-- Alerts --}}
                @if(session('success'))
                <div class="alert alert-success au au1">
                    <span><i class="bi bi-check-circle-fill" style="margin-right:6px;"></i>{{ session('success') }}</span>
                    <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger au au1">
                    <span><i class="bi bi-exclamation-triangle-fill" style="margin-right:6px;"></i>{{ session('error') }}</span>
                    <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
                </div>
                @endif

                {{-- Page header --}}
                <div class="pg-header au au1">
                    <div class="pg-left">
                        <div class="header-icon"><i class="bi bi-plus-lg"></i></div>
                        <div class="pg-title-text">
                            <h1>Add New Product</h1>
                            <p>Manually enter details or bulk upload via Excel</p>
                        </div>
                    </div>
                    <div class="pg-right">
                        <a href="products" class="btn-cancel-hdr">
                            <i class="bi bi-x-lg"></i> Cancel
                        </a>
                    </div>
                </div>

                {{-- Tab shell --}}
                <div class="tab-shell au au2">
                    <div class="tab-nav">
                        <button class="tab-btn active" data-tab="manual">
                            <i class="bi bi-pencil-square"></i> Add Manually
                        </button>
                        <button class="tab-btn" data-tab="excel">
                            <i class="bi bi-file-earmark-spreadsheet"></i> Bulk Upload (Excel)
                        </button>
                    </div>

                    {{-- ══ MANUAL TAB ══ --}}
                    <div id="manual" class="tab-pane active">
                        <form action="/user/addProducts" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="upload_type" value="manual">

                            {{-- Product image --}}
                            <div class="form-section">
                                <div class="section-head">
                                    <div class="section-head-icon shi-amber"><i class="bi bi-image"></i></div>
                                    <span class="section-title">Product Image</span>
                                </div>
                                <div class="img-upload-zone" id="imgZone">
                                    <img id="imagePreview" src="" alt="Preview">
                                    <i class="bi bi-cloud-arrow-up" id="imgIcon"></i>
                                    <p id="imgLabel">Click to upload product image</p>
                                    <small>Recommended: 800×800px · JPEG, PNG</small>
                                    <input type="file" id="image" name="image" accept="image/*">
                                </div>
                            </div>

                            {{-- Basic details --}}
                            <div class="form-section">
                                <div class="section-head">
                                    <div class="section-head-icon shi-navy"><i class="bi bi-card-text"></i></div>
                                    <span class="section-title">Basic Details</span>
                                </div>
                                <div class="field-grid-2" style="margin-bottom:1rem;">
                                    <div class="field">
                                        <label class="field-label">Product name <span class="req">*</span></label>
                                        <input type="text" class="field-input" name="name01" placeholder="e.g. Panadol Extra" required>
                                    </div>
                                    <div class="field">
                                        <label class="field-label">Brand / Manufacturer <span class="req">*</span></label>
                                        <input type="text" class="field-input" name="name02" placeholder="e.g. GSK" required>
                                    </div>
                                </div>
                                <div class="field-grid-2" style="margin-bottom:1rem;">
                                    <div class="field">
                                        <label class="field-label">Category <span class="req">*</span></label>
                                        <select class="field-select" name="category" required>
                                            <option value="" disabled selected>Select category</option>
                                            <option value="Foods">Foods</option>
                                            <option value="Drinks">Drinks</option>
                                            <option value="Furniture">Furniture</option>
                                            <option value="devices">Electronic Devices</option>
                                            <option value="Farming">Farming</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label class="field-label">Unit measurement <span class="req">*</span></label>
                                        <select class="field-select" name="unit" required>
                                            <option value="" disabled selected>Select unit</option>
                                            <option value="pieces">Pieces</option>
                                            <option value="carton">Carton</option>
                                            <option value="box">Box</option>
                                            <option value="set">Set</option>
                                            <option value="meter">Meter</option>
                                            <option value="Kg">Kilogram (Kg)</option>
                                            <option value="liter">Liter</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="field-label">Description</label>
                                    <textarea class="field-textarea" name="description" placeholder="Enter product description…"></textarea>
                                </div>
                            </div>

                            {{-- Pricing --}}
                            <div class="form-section">
                                <div class="section-head">
                                    <div class="section-head-icon shi-emerald"><i class="bi bi-tags"></i></div>
                                    <span class="section-title">Pricing Management</span>
                                </div>
                                <div class="field-grid-3" style="margin-bottom:1rem;">
                                    <div class="field">
                                        <label class="field-label">Cost price <span class="req">*</span></label>
                                        <div class="input-prefix-wrap">
                                            <span class="input-prefix">Tsh</span>
                                            <input type="number" name="bPrice" placeholder="0" required>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="field-label">Selling price <span class="req">*</span></label>
                                        <div class="input-prefix-wrap">
                                            <span class="input-prefix">Tsh</span>
                                            <input type="number" name="sPrice" placeholder="0" required>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="field-label">Wholesale price</label>
                                        <div class="input-prefix-wrap">
                                            <span class="input-prefix">Tsh</span>
                                            <input type="number" name="wholesale" placeholder="0">
                                        </div>
                                    </div>
                                </div>
                                <div style="max-width:260px;">
                                    <div class="field">
                                        <label class="field-label">Discount limit</label>
                                        <div class="input-prefix-wrap">
                                            <input type="number" name="discount" placeholder="0" min="0" max="100">
                                            <span class="input-suffix">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Stock --}}
                            <div class="form-section">
                                <div class="section-head">
                                    <div class="section-head-icon shi-violet"><i class="bi bi-box-seam"></i></div>
                                    <span class="section-title">Stock & Inventory</span>
                                </div>
                                <div class="field-grid-2">
                                    <div class="field">
                                        <label class="field-label">Stock quantity</label>
                                        <input type="number" class="field-input" name="quantity" placeholder="Enter quantity">
                                    </div>
                                    <div class="field">
                                        <label class="field-label">Storage location</label>
                                        <input type="text" class="field-input" name="location" placeholder="e.g. Shelf B3">
                                    </div>
                                </div>
                            </div>

                            {{-- Supplier --}}
                            <div class="form-section">
                                <div class="section-head">
                                    <div class="section-head-icon shi-sky"><i class="bi bi-truck"></i></div>
                                    <span class="section-title">Supplier / Vendor</span>
                                </div>
                                <div class="field">
                                    <label class="field-label">Supplier <span class="req">*</span></label>
                                    <select class="field-select" name="supplier" required>
                                        <option value="" disabled selected>Select supplier</option>
                                        @php $fetch = DB::table('vendors')->get(); @endphp
                                        @foreach ($fetch as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Expiry --}}
                            <div class="form-section">
                                <div class="section-head">
                                    <div class="section-head-icon shi-rose"><i class="bi bi-calendar-check"></i></div>
                                    <span class="section-title">Expiry Date Tracking</span>
                                </div>
                                <div style="max-width:280px;">
                                    <div class="field">
                                        <label class="field-label">Expiry date <span class="req">*</span></label>
                                        <input type="month" class="field-input" name="expiry" required>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn-save" name="saveProduct">
                                <i class="bi bi-save"></i> Save Product
                            </button>
                        </form>
                    </div>

                    {{-- ══ EXCEL TAB ══ --}}
                    <div id="excel" class="tab-pane">
                        <form action="/user/addProducts" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="upload_type" value="excel">

                            {{-- Drop zone --}}
                            <div class="excel-drop-zone" id="excelZone">
                                <i class="bi bi-file-earmark-spreadsheet excel-drop-icon"></i>
                                <p>Click to upload Excel or CSV file</p>
                                <small>Supported: .xlsx · .xls · .csv · Max 10 MB</small>
                                <input type="file" id="excel_file" name="excel_file" accept=".xlsx,.xls,.csv">
                            </div>

                            <div style="text-align:center; margin-bottom:1.5rem;">
                                <a href="/user/downloadTemplate" download class="dl-template-btn">
                                    <i class="bi bi-download"></i> Download Template
                                </a>
                            </div>

                            {{-- Preview --}}
                            <div class="preview-section" id="previewSection">
                                <div class="preview-head">
                                    <i class="bi bi-eye" style="color:var(--navy-light);"></i>
                                    File Preview
                                    <span style="margin-left:auto; font-size:11.5px; font-weight:400; color:var(--slate-400); font-family:var(--mono);" id="previewRowCount"></span>
                                </div>
                                <div class="preview-tbl-wrap" id="previewContent"></div>
                            </div>

                            {{-- Column reference --}}
                            <div class="form-section">
                                <div class="section-head">
                                    <div class="section-head-icon shi-navy"><i class="bi bi-table"></i></div>
                                    <span class="section-title">Required & Optional Columns</span>
                                </div>

                                <div class="ref-table-wrap">
                                    <table class="ref-table">
                                        <thead>
                                            <tr>
                                                <th>Column</th>
                                                <th>Status</th>
                                                <th>Default (if blank)</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="ref-group-row"><td colspan="4"><div class="ref-group-label rg-auto"><i class="bi bi-lock-fill"></i> Auto-Assigned by System</div></td></tr>
                                            <tr>
                                                <td class="ref-col-name">ID</td>
                                                <td><span class="pill-auto">Auto</span></td>
                                                <td class="ref-default">UUID auto-generated</td>
                                                <td class="ref-notes">Leave blank — always auto-assigned. If provided, it will be used as-is.</td>
                                            </tr>
                                            <tr>
                                                <td class="ref-col-name">Account</td>
                                                <td><span class="pill-auto">Auto</span></td>
                                                <td class="ref-default">Logged-in account</td>
                                                <td class="ref-notes">Always taken from your session — do <strong>not</strong> include this column.</td>
                                            </tr>

                                            <tr class="ref-group-row"><td colspan="4"><div class="ref-group-label rg-req"><i class="bi bi-exclamation-triangle-fill"></i> Required Fields</div></td></tr>
                                            <tr>
                                                <td class="ref-col-name">Item Name</td>
                                                <td><span class="pill-req">Required</span></td>
                                                <td class="ref-default">—</td>
                                                <td class="ref-notes">Product name. Row is skipped if empty.</td>
                                            </tr>
                                            <tr>
                                                <td class="ref-col-name">Quantity</td>
                                                <td><span class="pill-req">Required</span></td>
                                                <td class="ref-default">0</td>
                                                <td class="ref-notes">Current stock quantity. Stock record only created if value > 0.</td>
                                            </tr>

                                            <tr class="ref-group-row"><td colspan="4"><div class="ref-group-label rg-opt"><i class="bi bi-check-circle-fill"></i> Optional Fields (defaults apply if blank)</div></td></tr>
                                            @foreach([
                                                ['Brand',           'Bulk Import',          'Manufacturer or brand name'],
                                                ['Category',        'Others',               'Foods · Drinks · Furniture · Electronic Devices · Farming · Others'],
                                                ['Unit',            'pieces',               'pieces · carton · box · set · meter · Kg · liter'],
                                                ['Cost Price',      '0',                    'Buying price per unit (Tsh). Also accepted: Buying Price, bPrice'],
                                                ['Selling Price',   '0',                    'Retail price per unit (Tsh). Also accepted: sPrice'],
                                                ['Wholesale Price', '0',                    'Bulk discount price (Tsh). Also accepted: Wholesale'],
                                                ['Discount',        '0',                    'Discount limit percentage (%)'],
                                                ['Location',        'blank',                'Storage location or warehouse shelf'],
                                                ['Supplier',        'First vendor on file', 'Vendor name. Falls back to first vendor if blank'],
                                                ['Expiry',          'Current month',        'Format: YYYY-MM (e.g. 2026-12). Also accepts MM/DD/YYYY, DD/MM/YYYY'],
                                                ['Description',     'Imported via bulk',    'Product description or remarks'],
                                            ] as $col)
                                            <tr>
                                                <td class="ref-col-name">{{ $col[0] }}</td>
                                                <td><span class="pill-opt">Optional</span></td>
                                                <td class="ref-default">{{ $col[1] }}</td>
                                                <td class="ref-notes">{{ $col[2] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="tips-grid">
                                    <div class="tip-card navy">
                                        <h6><i class="bi bi-lightbulb-fill"></i> Tips</h6>
                                        <ul>
                                            <li>Column headers are <strong>case-insensitive</strong></li>
                                            <li>Do <strong>not</strong> include an <em>Account</em> column</li>
                                            <li>Extra columns are safely ignored</li>
                                            <li>OSPOS export files are supported</li>
                                            <li>Max file size: <strong>10 MB</strong></li>
                                        </ul>
                                    </div>
                                    <div class="tip-card amber">
                                        <h6><i class="bi bi-exclamation-triangle-fill"></i> Common Mistakes</h6>
                                        <ul>
                                            <li>Leaving <strong>Item Name</strong> blank — row is skipped</li>
                                            <li>Currency symbols in price cells (Tsh, $) — remove them</li>
                                            <li>Wrong date format — use <code>YYYY-MM</code> for expiry</li>
                                            <li>Modifying the header row in the template</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn-upload" name="uploadExcel" id="uploadBtn" disabled>
                                <i class="bi bi-cloud-arrow-up"></i> Upload Products
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
    /* ── Tab switch ── */
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(this.dataset.tab).classList.add('active');
        });
    });

    /* ── Image preview ── */
    document.getElementById('image').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('imagePreview');
            const icon    = document.getElementById('imgIcon');
            const label   = document.getElementById('imgLabel');
            const zone    = document.getElementById('imgZone');
            preview.src = e.target.result;
            preview.style.display = 'block';
            icon.style.display = 'none';
            label.textContent = 'Click to change image';
            zone.classList.add('has-image');
        };
        reader.readAsDataURL(file);
    });

    /* ── Excel file handler ── */
    document.getElementById('excel_file').addEventListener('change', function (e) {
        const file      = e.target.files[0];
        const uploadBtn = document.getElementById('uploadBtn');
        const zone      = document.getElementById('excelZone');

        if (!file) { uploadBtn.disabled = true; return; }

        zone.classList.add('file-loaded');
        zone.querySelector('p').textContent = file.name;
        zone.querySelector('small').textContent = (file.size / 1024).toFixed(1) + ' KB · Click to change';
        zone.querySelector('i').className = 'bi bi-check-circle-fill excel-drop-icon';
        zone.querySelector('i').style.color = 'var(--emerald)';

        uploadBtn.disabled = false;

        if (file.name.endsWith('.csv')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const text = e.target.result.trim();
                // Detect delimiter: count commas vs tabs in first line
                const firstLine = text.split('\n')[0];
                const commaCount = (firstLine.match(/,/g) || []).length;
                const tabCount = (firstLine.match(/\t/g) || []).length;
                const delimiter = tabCount > commaCount ? '\t' : ',';
                console.log('CSV delimiter detected:', delimiter, 'commas:', commaCount, 'tabs:', tabCount);
                
                const rows = text.split('\n').map(r => r.split(delimiter).map(c => c.trim()));
                displayPreview(rows, file.name);
            };
            reader.readAsText(file);
        } else if (file.name.endsWith('.xlsx') || file.name.endsWith('.xls')) {
            // Excel file - use SheetJS to parse
            const reader = new FileReader();
            reader.onload = function (e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                    const rows = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });
                    
                    // Convert to array of arrays (like CSV parsing)
                    const rowArray = rows.map(row => {
                        // Handle both array and object formats
                        if (Array.isArray(row)) {
                            return row.map(cell => cell !== null && cell !== undefined ? String(cell).trim() : '');
                        } else {
                            // If object, convert to array based on headers
                            return Object.values(row).map(v => v !== null && v !== undefined ? String(v).trim() : '');
                        }
                    });
                    
                    displayPreview(rowArray, file.name);
                } catch (err) {
                    console.error('Excel parsing error:', err);
                    showLoadedMsg(file.name);
                }
            };
            reader.readAsArrayBuffer(file);
        } else {
            showLoadedMsg(file.name);
        }
    });

    function displayPreview(rows, filename) {
        const section = document.getElementById('previewSection');
        const content = document.getElementById('previewContent');
        const countEl = document.getElementById('previewRowCount');

        if (!rows.length) return;
        section.style.display = 'block';

        const headers = rows[0];
        const dataRows = rows.slice(1);
        countEl.textContent = dataRows.length + ' data rows';

        let html = '<table class="preview-tbl"><thead><tr>';
        headers.forEach(h => { html += `<th>${h}</th>`; });
        html += '</tr></thead><tbody>';

        const max = Math.min(5, dataRows.length);
        for (let i = 0; i < max; i++) {
            html += '<tr>';
            dataRows[i].forEach(cell => { html += `<td>${cell}</td>`; });
            html += '</tr>';
        }
        if (dataRows.length > max) {
            html += `<tr><td colspan="${headers.length}" class="more-rows">… ${dataRows.length - max} more rows not shown</td></tr>`;
        }
        html += '</tbody></table>';
        content.innerHTML = html;
    }

    function showLoadedMsg(filename) {
        const section = document.getElementById('previewSection');
        const content = document.getElementById('previewContent');
        section.style.display = 'block';
        content.innerHTML = `<div class="file-loaded-msg">
            <i class="bi bi-check-circle-fill"></i>
            <p>File loaded successfully</p>
            <small>${filename}</small>
        </div>`;
    }
</script>
</body>
</html>