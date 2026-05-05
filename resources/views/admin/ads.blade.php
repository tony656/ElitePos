<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config("app.name")}} — Ad Manager</title>
    @include("links")
    <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
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

        .btn-amber { display:inline-flex; align-items:center; gap:6px; padding:9px 18px; border-radius:var(--r); background:var(--amber); color:var(--navy); font-family:var(--font); font-size:13px; font-weight:700; border:none; cursor:pointer; box-shadow:0 3px 14px rgba(245,158,11,.35); transition:all .18s; }
        .btn-amber:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(245,158,11,.45); color:var(--navy); }

        /* ══ CONTROLS BAR ══ */
        .controls-bar {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-lg); padding: .85rem 1.2rem;
            margin-bottom: 1.4rem;
            display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
            box-shadow: 0 1px 4px rgba(11,30,61,.05);
        }

        /* Search */
        .search-wrap { position:relative; flex:1; min-width:180px; }
        .search-wrap i { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--slate-400); font-size:13px; pointer-events:none; }
        .search-input { width:100%; padding:8px 12px 8px 32px; border:1.5px solid var(--slate-200); border-radius:var(--r); font-family:var(--font); font-size:13px; color:var(--slate-800); background:var(--slate-50); outline:none; transition:border-color .18s, box-shadow .18s; }
        .search-input:focus { border-color:var(--navy-light); box-shadow:0 0 0 3px rgba(26,58,107,.1); background:var(--white); }
        .search-input::placeholder { color:var(--slate-400); }

        /* Filter / sort selects */
        .ctrl-select {
            padding:8px 32px 8px 12px; border:1.5px solid var(--slate-200); border-radius:var(--r);
            font-family:var(--font); font-size:13px; color:var(--slate-700);
            background:var(--white); outline:none; cursor:pointer; appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='%2394A3B8' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right 10px center;
            transition:border-color .18s; min-width:130px;
        }
        .ctrl-select:focus { border-color:var(--navy-light); }

        /* View toggle */
        .view-toggle { display:flex; border:1.5px solid var(--slate-200); border-radius:var(--r); overflow:hidden; flex-shrink:0; }
        .view-btn { width:34px; height:34px; border:none; background:transparent; color:var(--slate-400); font-size:14px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .15s; }
        .view-btn.active { background:var(--navy); color:var(--white); }
        .view-btn:not(.active):hover { background:var(--slate-100); color:var(--slate-700); }

        /* Result count */
        .result-count { font-size:12px; color:var(--slate-400); font-family:var(--mono); flex-shrink:0; white-space:nowrap; }

        /* ══ AD CARDS — GRID ══ */
        #adsGrid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.1rem;
        }

        .ad-card {
            background: var(--white); border: 1.5px solid var(--slate-200);
            border-radius: var(--r-xl); overflow: hidden;
            box-shadow: 0 1px 4px rgba(11,30,61,.05);
            transition: transform .2s, box-shadow .2s;
            display: flex; flex-direction: column;
            animation: fadeUp .35s ease both;
        }
        .ad-card:hover { transform: translateY(-4px); box-shadow: 0 10px 28px rgba(11,30,61,.12); }
        .ad-card.hidden { display: none; }

        .ad-img-wrap { position:relative; overflow:hidden; height:170px; background:var(--slate-100); flex-shrink:0; }
        .ad-img-wrap img { width:100%; height:100%; object-fit:cover; transition:transform .35s; }
        .ad-card:hover .ad-img-wrap img { transform:scale(1.04); }
        .ad-img-wrap .ad-date-tag {
            position:absolute; top:10px; left:10px;
            background:rgba(11,30,61,.75); backdrop-filter:blur(4px);
            color:rgba(255,255,255,.85); font-size:10.5px; font-family:var(--mono);
            padding:3px 9px; border-radius:20px;
        }
        .ad-img-wrap .ad-del-btn {
            position:absolute; top:10px; right:10px;
            width:28px; height:28px; border-radius:50%;
            background:rgba(225,29,72,.85); color:var(--white);
            border:none; cursor:pointer; display:flex; align-items:center; justify-content:center;
            font-size:12px; opacity:0; transition:opacity .2s;
        }
        .ad-card:hover .ad-del-btn { opacity:1; }
        .ad-del-btn:hover { background:var(--rose); }

        .ad-body { padding:1rem 1.1rem; flex:1; display:flex; flex-direction:column; }
        .ad-title { font-size:14px; font-weight:700; color:var(--navy); margin-bottom:5px; line-height:1.3; }
        .ad-desc  { font-size:12.5px; color:var(--slate-500); line-height:1.6; flex:1;
                    display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
        .ad-footer { display:flex; align-items:center; justify-content:space-between; margin-top:.75rem; padding-top:.75rem; border-top:1px solid var(--slate-100); }
        .ad-badge  { font-size:10.5px; font-weight:600; padding:2px 9px; border-radius:20px; background:var(--amber-pale); color:#92400e; }
        .ad-view-btn {
            display:inline-flex; align-items:center; gap:4px;
            font-size:11.5px; font-weight:600; color:var(--navy-light);
            text-decoration:none; transition:color .15s;
        }
        .ad-view-btn:hover { color:var(--amber); }

        /* ══ AD ROWS — LIST VIEW ══ */
        #adsList { display:none; }
        .ad-list-panel { background:var(--white); border:1.5px solid var(--slate-200); border-radius:var(--r-xl); overflow:hidden; box-shadow:0 1px 4px rgba(11,30,61,.05); }
        .ad-row {
            display:flex; align-items:center; gap:1rem; padding:.85rem 1.2rem;
            border-bottom:1px solid var(--slate-100); transition:background .12s;
        }
        .ad-row:last-child { border-bottom:none; }
        .ad-row:hover { background:var(--slate-50); }
        .ad-row.hidden { display:none; }
        .ad-row-thumb { width:60px; height:46px; border-radius:var(--r); object-fit:cover; flex-shrink:0; background:var(--slate-100); }
        .ad-row-info { flex:1; min-width:0; }
        .ar-title { font-size:13.5px; font-weight:700; color:var(--navy); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .ar-desc  { font-size:12px; color:var(--slate-400); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .ad-row-date { font-size:11.5px; color:var(--slate-400); font-family:var(--mono); flex-shrink:0; }

        /* ══ EMPTY STATE ══ */
        .empty-state { text-align:center; padding:4rem 1.5rem; color:var(--slate-400); grid-column:1/-1; }
        .empty-state i { font-size:2.5rem; display:block; margin-bottom:.75rem; opacity:.3; }
        .empty-state h4 { font-size:15px; font-weight:600; color:var(--slate-600); margin-bottom:5px; }
        .empty-state p  { font-size:13px; }

        /* ══ UPLOAD MODAL ══ */
        .modal-overlay {
            position:fixed; inset:0; background:rgba(0,0,0,.5);
            backdrop-filter:blur(6px); z-index:1000;
            display:flex; align-items:center; justify-content:center; padding:1rem;
        }
        .modal-box {
            background:var(--white); border-radius:var(--r-xl);
            width:100%; max-width:520px; max-height:90vh; overflow-y:auto;
            box-shadow:0 24px 60px rgba(11,30,61,.25);
            animation: fadeUp .3s ease;
        }
        .modal-top {
            background:var(--navy); padding:1.15rem 1.4rem;
            display:flex; align-items:center; justify-content:space-between;
            border-radius:var(--r-xl) var(--r-xl) 0 0; position:sticky; top:0; z-index:2;
        }
        .modal-top-left { display:flex; align-items:center; gap:10px; }
        .modal-top-icon { width:32px; height:32px; border-radius:var(--r); background:var(--amber); display:flex; align-items:center; justify-content:center; color:var(--navy); font-size:14px; }
        .modal-top h5 { font-size:15px; font-weight:700; color:var(--white); margin:0; }
        .modal-close-btn { background:rgba(255,255,255,.1); border:none; color:var(--white); width:30px; height:30px; border-radius:50%; cursor:pointer; font-size:16px; display:flex; align-items:center; justify-content:center; transition:background .15s; }
        .modal-close-btn:hover { background:rgba(255,255,255,.2); }
        .modal-body { padding:1.5rem 1.4rem; }

        /* Fields */
        .field { margin-bottom:1.1rem; }
        .field-label { display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--slate-500); margin-bottom:5px; }
        .field-label .req { color:var(--rose); }
        .field-input { width:100%; font-family:var(--font); font-size:13.5px; padding:9px 12px; border:1.5px solid var(--slate-200); border-radius:var(--r); background:var(--white); color:var(--slate-800); outline:none; transition:border-color .18s, box-shadow .18s; }
        .field-input:focus { border-color:var(--navy-light); box-shadow:0 0 0 3px rgba(26,58,107,.1); }
        .field-input::placeholder { color:var(--slate-400); }
        textarea.field-input { resize:vertical; min-height:90px; }

        /* Drop zone */
        .drop-zone {
            border:2px dashed var(--slate-300); border-radius:var(--r-lg);
            padding:2rem; text-align:center; cursor:pointer;
            transition:all .2s; background:var(--slate-50); position:relative;
        }
        .drop-zone:hover, .drop-zone.dragging { border-color:var(--navy-light); background:rgba(26,58,107,.03); }
        .drop-zone.has-image { border-style:solid; border-color:var(--emerald); background:var(--emerald-pale); }
        .drop-zone i { font-size:2rem; color:var(--slate-300); display:block; margin-bottom:.5rem; transition:color .2s; }
        .drop-zone:hover i { color:var(--navy-light); }
        .drop-zone p { font-size:13px; font-weight:500; color:var(--slate-500); margin-bottom:3px; }
        .drop-zone small { font-size:11.5px; color:var(--slate-400); }
        .drop-zone input[type=file] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        #imgPreview { max-width:100%; max-height:180px; border-radius:var(--r); margin-top:.75rem; display:none; object-fit:cover; }

        .modal-footer-custom { padding:1rem 1.4rem; border-top:1.5px solid var(--slate-200); display:flex; justify-content:flex-end; gap:8px; }
        .btn-cancel { padding:9px 18px; border-radius:var(--r); border:1.5px solid var(--slate-200); background:transparent; font-family:var(--font); font-size:13px; color:var(--slate-600); cursor:pointer; transition:all .15s; }
        .btn-cancel:hover { background:var(--slate-100); }
        .btn-upload { padding:9px 22px; border-radius:var(--r); background:var(--amber); color:var(--navy); border:none; font-family:var(--font); font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; box-shadow:0 3px 14px rgba(245,158,11,.3); transition:all .18s; }
        .btn-upload:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(245,158,11,.4); }

        /* ── Responsive ── */
        @media(max-width:720px) {
            .wrap { padding:1rem; }
            .controls-bar { flex-direction:column; align-items:stretch; }
            #adsGrid { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
<div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3">
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
                <span><i class="bi bi-exclamation-circle-fill" style="margin-right:6px;"></i>{{ session('error') }}</span>
                <button class="close-btn" onclick="this.closest('.alert').remove()">×</button>
            </div>
            @endif

            {{-- Page header --}}
            <div class="pg-header au au1">
                <div class="pg-left">
                    <div class="header-icon"><i class="bi bi-megaphone-fill"></i></div>
                    <div class="pg-title-text">
                        <h1>Ad Manager</h1>
                        <p>Upload and manage your advertisements</p>
                    </div>
                </div>
                <div class="pg-right">
                    <button class="btn-amber" onclick="openModal()">
                        <i class="bi bi-plus-lg"></i> Upload Ad
                    </button>
                </div>
            </div>

            {{-- Controls bar --}}
            <div class="controls-bar au au2">
                <div class="search-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" class="search-input" id="adSearch" placeholder="Search ads by title…" oninput="filterAds()">
                </div>

                <select class="ctrl-select" id="sortSelect" onchange="filterAds()">
                    <option value="newest">Newest first</option>
                    <option value="oldest">Oldest first</option>
                    <option value="az">Title A → Z</option>
                    <option value="za">Title Z → A</option>
                </select>

                <select class="ctrl-select" id="monthFilter" onchange="filterAds()">
                    <option value="">All months</option>
                    @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $i => $m)
                        <option value="{{ $i + 1 }}">{{ $m }}</option>
                    @endforeach
                </select>

                <div class="view-toggle">
                    <button class="view-btn active" id="gridViewBtn" onclick="setView('grid')" title="Grid view">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </button>
                    <button class="view-btn" id="listViewBtn" onclick="setView('list')" title="List view">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>

                <span class="result-count" id="resultCount">{{ $ads->count() }} ads</span>
            </div>

            {{-- Grid view --}}
            <div id="adsGrid" class="au au3">
                @forelse($ads as $ad)
                <div class="ad-card"
                     data-title="{{ strtolower($ad->title) }}"
                     data-date="{{ $ad->created_at }}"
                     data-month="{{ date('n', strtotime($ad->created_at)) }}">
                    <div class="ad-img-wrap">
                        <img src="{{ asset('public/images/' . $ad->image_path) }}" alt="{{ $ad->title }}" loading="lazy">
                        <span class="ad-date-tag">{{ date('d M Y', strtotime($ad->created_at)) }}</span>
                        <form action="{{ route('admin.deleteAd') }}" method="POST" style="display:contents;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                            <button type="submit" class="ad-del-btn" onclick="return confirm('Delete this ad?')" title="Delete">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </div>
                    <div class="ad-body">
                        <div class="ad-title">{{ $ad->title }}</div>
                        <div class="ad-desc">{{ $ad->description }}</div>
                        <div class="ad-footer">
                            <span class="ad-badge"><i class="bi bi-megaphone" style="margin-right:4px;"></i>Active</span>
                            <a href="{{ asset('public/images/' . $ad->image_path) }}" target="_blank" class="ad-view-btn">
                                View <i class="bi bi-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="bi bi-megaphone"></i>
                    <h4>No ads uploaded yet</h4>
                    <p>Click "Upload Ad" to add your first advertisement</p>
                </div>
                @endforelse
            </div>

            {{-- List view --}}
            <div id="adsList">
                <div class="ad-list-panel">
                    @forelse($ads as $ad)
                    <div class="ad-row"
                         data-title="{{ strtolower($ad->title) }}"
                         data-date="{{ $ad->created_at }}"
                         data-month="{{ date('n', strtotime($ad->created_at)) }}">
                        <img class="ad-row-thumb" src="{{ asset('public/images/' . $ad->image_path) }}" alt="{{ $ad->title }}" loading="lazy">
                        <div class="ad-row-info">
                            <div class="ar-title">{{ $ad->title }}</div>
                            <div class="ar-desc">{{ $ad->description }}</div>
                        </div>
                        <div class="ad-row-date">{{ date('d M Y', strtotime($ad->created_at)) }}</div>
                        <form action="{{ route('admin.deleteAd') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                            <button type="submit" style="background:var(--rose-pale);color:var(--rose);border:none;padding:5px 10px;border-radius:var(--r);font-size:12px;cursor:pointer;font-family:var(--font);font-weight:600;"
                                    onclick="return confirm('Delete this ad?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="bi bi-megaphone"></i>
                        <h4>No ads uploaded yet</h4>
                        <p>Click "Upload Ad" to add your first advertisement</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </main>
</div>

{{-- ══ UPLOAD MODAL ══ --}}
<div class="modal-overlay" id="uploadModal" style="display:none;" onclick="if(event.target===this)closeModal()">
    <div class="modal-box">
        <div class="modal-top">
            <div class="modal-top-left">
                <div class="modal-top-icon"><i class="bi bi-cloud-arrow-up-fill"></i></div>
                <h5>Upload New Ad</h5>
            </div>
            <button class="modal-close-btn" onclick="closeModal()">×</button>
        </div>
        <form action="/admin/ads" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">

                <div class="field">
                    <label class="field-label">Ad title <span class="req">*</span></label>
                    <input type="text" class="field-input" name="title" placeholder="e.g. Summer Sale 50% Off" required>
                </div>

                <div class="field">
                    <label class="field-label">Description <span class="req">*</span></label>
                    <textarea class="field-input" name="description" placeholder="Describe your advertisement…" required></textarea>
                </div>

                <div class="field">
                    <label class="field-label">Ad image <span class="req">*</span></label>
                    <div class="drop-zone" id="dropZone">
                        <i class="bi bi-cloud-arrow-up" id="dropIcon"></i>
                        <p id="dropLabel">Click or drag to upload image</p>
                        <small>JPG, PNG, GIF · Max 5 MB</small>
                        <input type="file" name="image" id="adImage" accept="image/*" required>
                        <img id="imgPreview" alt="Preview">
                    </div>
                </div>

            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-upload">
                    <i class="bi bi-cloud-arrow-up-fill"></i> Upload Ad
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    /* ── Modal ── */
    function openModal()  { document.getElementById('uploadModal').style.display = 'flex'; document.body.style.overflow = 'hidden'; }
    function closeModal() { document.getElementById('uploadModal').style.display = 'none';  document.body.style.overflow = ''; }

    /* ── Image drop zone ── */
    const dropZone = document.getElementById('dropZone');
    const adImage  = document.getElementById('adImage');
    const preview  = document.getElementById('imgPreview');

    ['dragover','dragenter'].forEach(ev => dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.add('dragging'); }));
    ['dragleave','drop'].forEach(ev => dropZone.addEventListener(ev, () => dropZone.classList.remove('dragging')));
    dropZone.addEventListener('drop', e => { e.preventDefault(); if (e.dataTransfer.files[0]) showPreview(e.dataTransfer.files[0]); });
    adImage.addEventListener('change', () => { if (adImage.files[0]) showPreview(adImage.files[0]); });

    function showPreview(file) {
        const r = new FileReader();
        r.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
            dropZone.classList.add('has-image');
            document.getElementById('dropIcon').style.display  = 'none';
            document.getElementById('dropLabel').textContent = 'Click to change image';
        };
        r.readAsDataURL(file);
    }

    /* ── View toggle ── */
    function setView(v) {
        const isGrid = v === 'grid';
        document.getElementById('adsGrid').style.display  = isGrid ? '' : 'none';
        document.getElementById('adsList').style.display  = isGrid ? 'none' : '';
        document.getElementById('gridViewBtn').classList.toggle('active',  isGrid);
        document.getElementById('listViewBtn').classList.toggle('active', !isGrid);
        localStorage.setItem('adView', v);
    }
    const savedView = localStorage.getItem('adView') || 'grid';
    setView(savedView);

    /* ── Filter + Sort ── */
    function filterAds() {
        const q     = document.getElementById('adSearch').value.toLowerCase().trim();
        const sort  = document.getElementById('sortSelect').value;
        const month = document.getElementById('monthFilter').value;

        /* Collect from both views */
        const gridCards = [...document.querySelectorAll('#adsGrid .ad-card')];
        const listRows  = [...document.querySelectorAll('#adsList .ad-row')];

        function matches(el) {
            const title = el.dataset.title || '';
            const mon   = el.dataset.month || '';
            if (q && !title.includes(q)) return false;
            if (month && mon !== month) return false;
            return true;
        }

        /* Sort helper */
        function sortEls(els) {
            return [...els].sort((a, b) => {
                if (sort === 'newest') return new Date(b.dataset.date) - new Date(a.dataset.date);
                if (sort === 'oldest') return new Date(a.dataset.date) - new Date(b.dataset.date);
                if (sort === 'az')     return (a.dataset.title || '').localeCompare(b.dataset.title || '');
                if (sort === 'za')     return (b.dataset.title || '').localeCompare(a.dataset.title || '');
                return 0;
            });
        }

        /* Apply to grid */
        const sortedGrid = sortEls(gridCards);
        const gridParent = document.getElementById('adsGrid');
        let visible = 0;
        sortedGrid.forEach(el => {
            const show = matches(el);
            el.classList.toggle('hidden', !show);
            if (show) { visible++; gridParent.appendChild(el); }
        });

        /* Apply to list */
        const sortedList = sortEls(listRows);
        const listParent = document.querySelector('#adsList .ad-list-panel');
        if (listParent) {
            sortedList.forEach(el => {
                el.classList.toggle('hidden', !matches(el));
                listParent.appendChild(el);
            });
        }

        document.getElementById('resultCount').textContent = visible + ' ad' + (visible !== 1 ? 's' : '');
    }

    /* Run initial sort */
    filterAds();
</script>
</body>
</html> 