<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Product Details</title>
    @include("links")

    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    

    <style>
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
            --font-main:    'Sora', sans-serif;
            --font-mono:    'DM Mono', monospace;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            background: var(--slate-100);
            color: var(--slate-800);
        }

        /* ── Layout ── */
        .page-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .page-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            margin-left: var(--sidebar-width);
        }

        .sidebar.collapsed ~ .page-main {
            margin-left: var(--sidebar-collapsed);
        }

        /* ── Top bar ── */
        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--slate-200);
            padding: 0.875rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .breadcrumb-trail {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 13px;
            color: var(--slate-400);
            font-weight: 500;
        }

        .breadcrumb-trail a {
            color: var(--slate-500);
            text-decoration: none;
            transition: color 0.15s;
        }

        .breadcrumb-trail a:hover { color: var(--navy); }

        .breadcrumb-trail .current {
            color: var(--navy);
            font-weight: 600;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.45rem 1rem;
            border: 1px solid var(--slate-200);
            border-radius: 8px;
            background: var(--white);
            color: var(--slate-600);
            font-size: 13px;
            font-weight: 500;
            font-family: var(--font-main);
            cursor: pointer;
            text-decoration: none;
            transition: border-color 0.15s, background 0.15s;
        }

        .btn-outline:hover {
            border-color: var(--slate-300);
            background: var(--slate-50);
            color: var(--slate-800);
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.45rem 1.1rem;
            border: none;
            border-radius: 8px;
            background: var(--navy);
            color: var(--white);
            font-size: 13px;
            font-weight: 600;
            font-family: var(--font-main);
            cursor: pointer;
            transition: background 0.15s, transform 0.15s;
        }

        .btn-primary:hover {
            background: var(--navy-light);
            transform: translateY(-1px);
        }

        /* ── Page content ── */
        .page-content {
            padding: 2rem;
            overflow-y: auto;
            flex: 1;
        }

        /* ── Hero card ── */
        .hero-card {
            background: var(--navy);
            border-radius: 16px;
            padding: 2rem 2rem 2rem 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* decorative rings */
        .hero-card::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 220px; height: 220px;
            border: 40px solid rgba(245,158,11,0.07);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-card::after {
            content: '';
            position: absolute;
            bottom: -80px; right: 120px;
            width: 160px; height: 160px;
            border: 30px solid rgba(255,255,255,0.04);
            border-radius: 50%;
            pointer-events: none;
        }

        .product-avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            border: 3px solid var(--amber);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .product-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-avatar .avatar-placeholder {
            font-size: 2rem;
            color: rgba(255,255,255,0.3);
        }

        .hero-body { flex: 1; min-width: 0; }

        .hero-category-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(245,158,11,0.15);
            border: 1px solid rgba(245,158,11,0.35);
            color: var(--amber);
            font-size: 10px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 99px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .hero-name {
            font-size: 22px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 0.2rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .hero-sub {
            font-size: 13px;
            color: rgba(255,255,255,0.45);
        }

        .hero-sub .code {
           
            font-size: 11px;
            color: rgba(255,255,255,0.3);
            margin-left: 6px;
        }

        .hero-prices {
            display: flex;
            gap: 2.5rem;
            margin-top: 1.25rem;
        }

        .price-group {}
        .price-group .plabel {
            font-size: 10px;
            color: rgba(255,255,255,0.35);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 3px;
        }

        .price-group .pval {
           
            font-size: 17px;
            font-weight: 500;
            color: var(--white);
        }

        .price-group .pval.highlight { color: var(--amber); }

        .hero-side {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        /* ── Stock badge ── */
        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 0.4rem 1rem;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
            font-family: var(--font-main);
        }

        .stock-badge .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
        }

        .stock-badge.in-stock  { background: var(--emerald-pale); color: #065f46; }
        .stock-badge.low-stock { background: var(--amber-pale);   color: #92400e; }
        .stock-badge.out-stock { background: var(--rose-pale);    color: #9f1239; }

        .stock-badge.in-stock  .dot { background: var(--emerald); }
        .stock-badge.low-stock .dot { background: var(--amber);   }
        .stock-badge.out-stock .dot { background: var(--rose);    }

        .hero-updated {
            font-size: 11px;
            color: rgba(255,255,255,0.25);
        }

        /* ── Stats row ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--white);
            border: 1px solid var(--slate-200);
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
        }

        .stat-icon-wrap {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            font-size: 17px;
        }

        .stat-icon-wrap.sky    { background: var(--sky-pale);     color: var(--sky);     }
        .stat-icon-wrap.amber  { background: var(--amber-pale);   color: #b45309;        }
        .stat-icon-wrap.emerald{ background: var(--emerald-pale); color: var(--emerald); }
        .stat-icon-wrap.rose   { background: var(--rose-pale);    color: var(--rose);    }

        .stat-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: var(--slate-400);
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--slate-800);
           
        }

        .stat-sub {
            font-size: 11px;
            color: var(--slate-400);
            margin-top: 2px;
        }

        /* ── Section title ── */
        .section-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--slate-500);
            margin-bottom: 0.875rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--slate-200);
        }

        /* ── Detail grid ── */
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            background: var(--white);
            border: 1px solid var(--slate-200);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .detail-cell {
            padding: 1rem 1.25rem;
            border-right: 1px solid var(--slate-100);
            border-bottom: 1px solid var(--slate-100);
        }

        .detail-cell:nth-child(3n)       { border-right: none; }
        .detail-cell:nth-last-child(-n+3){ border-bottom: none; }

        .dc-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: var(--slate-400);
            margin-bottom: 5px;
        }

        .dc-value {
            font-size: 13px;
            font-weight: 600;
            color: var(--slate-800);
        }

        .dc-value.mono  { font-size: 12px; color: var(--violet); }
        .dc-value.price { color: var(--navy); }

        /* ── Description card ── */
        .desc-card {
            background: var(--white);
            border: 1px solid var(--slate-200);
            border-top: 3px solid var(--amber);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
        }

        .desc-text {
            font-size: 13px;
            color: var(--slate-600);
            line-height: 1.75;
        }

        /* ── Modal ── */
        .modal-header {
            background: var(--navy) !important;
            color: var(--white) !important;
            border-bottom: none !important;
            border-radius: 12px 12px 0 0 !important;
            padding: 1.25rem 1.5rem !important;
        }

        .modal-title { font-size: 15px !important; font-weight: 600 !important; }

        .modal-content { border-radius: 12px !important; border: none !important; overflow: hidden; }

        .modal-body { padding: 1.5rem !important; }

        .form-section-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--slate-400);
            margin: 1.25rem 0 0.75rem;
        }

        .form-section-label:first-child { margin-top: 0; }

        .form-divider {
            height: 1px;
            background: var(--slate-100);
            margin: 1.25rem 0;
        }

        .form-label {
            font-size: 11px !important;
            font-weight: 600 !important;
            color: var(--slate-600) !important;
            text-transform: uppercase !important;
            letter-spacing: 0.6px !important;
            margin-bottom: 5px !important;
        }

        .form-control, .form-select {
            border: 1px solid var(--slate-200) !important;
            border-radius: 8px !important;
            background: var(--slate-50) !important;
            color: var(--slate-800) !important;
            font-size: 13px !important;
            font-family: var(--font-main) !important;
            transition: border-color 0.15s, box-shadow 0.15s !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--navy-light) !important;
            background: var(--white) !important;
            box-shadow: 0 0 0 3px rgba(26,58,107,0.1) !important;
        }

        .price-input-wrap {
            position: relative;
        }

        .price-input-wrap .currency-prefix {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 10px;
            font-weight: 700;
            color: var(--slate-400);
            pointer-events: none;
            z-index: 1;
        }

        .price-input-wrap .form-control {
            padding-left: 2.75rem !important;
            font-family: var(--font-mono) !important;
        }

        .btn-save {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 0.75rem;
            background: var(--navy);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            font-family: var(--font-main);
            cursor: pointer;
            transition: background 0.15s;
            margin-top: 0.75rem;
        }

        .btn-save:hover { background: var(--navy-light); }

        .img-preview-block {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
            border-radius: 10px;
            padding: 0.875rem 1rem;
            margin-bottom: 1rem;
        }

        .img-preview-thumb {
            width: 56px;
            height: 56px;
            border-radius: 10px;
            border: 2px solid var(--amber);
            overflow: hidden;
            flex-shrink: 0;
        }

        .img-preview-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ── Responsive ── */
        @media (max-width: 900px) {
            .stats-row { grid-template-columns: repeat(2, 1fr); }
            .detail-grid { grid-template-columns: repeat(2, 1fr); }
            .detail-cell:nth-child(3n)        { border-right: 1px solid var(--slate-100); }
            .detail-cell:nth-last-child(-n+3) { border-bottom: 1px solid var(--slate-100); }
            .detail-cell:nth-child(2n)        { border-right: none; }
            .detail-cell:nth-last-child(-n+2) { border-bottom: none; }
            .hero-prices { gap: 1.5rem; }
        }

        @media (max-width: 640px) {
            .page-content { padding: 1rem; }
            .hero-card { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .hero-side { align-items: flex-start; }
            .stats-row { grid-template-columns: repeat(2, 1fr); }
            .detail-grid { grid-template-columns: 1fr; }
            .detail-cell { border-right: none !important; border-bottom: 1px solid var(--slate-100) !important; }
            .detail-cell:last-child { border-bottom: none !important; }
            .hero-name { font-size: 18px; }
            .hero-prices { flex-wrap: wrap; gap: 1rem; }
        }
    </style>
</head>
<body>

<div class="page-wrapper">
    @include("sidenav")

    <main class="page-main">

        {{-- Top bar --}}
        <div class="topbar">
            <nav class="breadcrumb-trail" aria-label="breadcrumb">
                <a href="products">Products</a>
                <i class="bi bi-chevron-right" style="font-size:11px;"></i>
                <span class="current">Product Details</span>
            </nav>
            <div class="topbar-actions">
                <a href="products" class="btn-outline">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#editProduct">
                    <i class="bi bi-pencil-square"></i> Edit Product
                </button>
            </div>
        </div>

        <div class="page-content">

            {{-- ── Hero card ── --}}
            <div class="hero-card">
                <div class="product-avatar">
                    @if($products->img ?? '')
                        <img src="{{asset('/public/images/' . $products->img ?? '')}}" alt="{{$products->name01}}">
                    @else
                        <span class="avatar-placeholder"><i class="bi bi-box-seam"></i></span>
                    @endif
                </div>

                <div class="hero-body">
                    <div class="hero-category-badge">
                        <i class="bi bi-tag" style="font-size:9px;"></i>
                        {{$products->category ?? ''}}
                    </div>
                    <div class="hero-name">{{$products->name01 ?? ''}}</div>
                    <div class="hero-sub">
                        {{$products->name02 ?? ''}}
                        <span class="code">· {{$products->code ?? ''}}</span>
                    </div>

                    <div class="hero-prices">
                        <div class="price-group">
                            <div class="plabel">Selling Price</div>
                            <div class="pval highlight">
                                {{ $products->sPrice == null ? 'Not Set' : 'Tsh ' . number_format($products->sPrice) }}
                            </div>
                        </div>
                        <div class="price-group">
                            <div class="plabel">Cost Price</div>
                            <div class="pval">
                                {{ $products->bPrice == null ? 'Not Set' : 'Tsh ' . number_format($products->bPrice) }}
                            </div>
                        </div>
                        <div class="price-group">
                            <div class="plabel">Wholesale</div>
                            <div class="pval">
                                Tsh {{ $products->wholesale == null ? 'Not Set' : 'Tsh ' . number_format($products->wholesale)}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hero-side">
                    @php
                        if ($products->quantity <= 0) {
                            $stockClass = 'out-stock';
                            $stockIcon  = 'bi-x-circle';
                            $stockLabel = 'Out of Stock';
                        } elseif ($products->quantity < 10) {
                            $stockClass = 'low-stock';
                            $stockIcon  = 'bi-exclamation-circle';
                            $stockLabel = 'Low Stock';
                        } else {
                            $stockClass = 'in-stock';
                            $stockIcon  = 'bi-check-circle';
                            $stockLabel = 'In Stock';
                        }
                    @endphp
                    <div class="stock-badge {{$stockClass}}">
                        <span class="dot"></span>
                        {{$stockLabel}}
                    </div>
                    <div class="hero-updated">
                        <i class="bi bi-clock" style="font-size:10px;"></i>
                        {{$products->stock ?? ''}}
                    </div>
                </div>
            </div>

            {{-- ── Stats row ── --}}
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon-wrap sky">
                        <i class="bi bi-boxes"></i>
                    </div>
                    <div class="stat-label">Quantity</div>
                    <div class="stat-value">{{$products->quantity}}</div>
                    <div class="stat-sub">{{$products->unit}}</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrap amber">
                        <i class="bi bi-percent"></i>
                    </div>
                    <div class="stat-label">Discount</div>
                    <div class="stat-value">{{$products->discount ?? '0'}}%</div>
                    <div class="stat-sub">applied</div>
                </div>

                @php
                    $margin = null;
                    if ($products->sPrice && $products->bPrice && $products->sPrice > 0) {
                        $margin = round((($products->sPrice - $products->bPrice) / $products->sPrice) * 100);
                    }
                @endphp
                <div class="stat-card">
                    <div class="stat-icon-wrap emerald">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div class="stat-label">Gross Margin</div>
                    <div class="stat-value">{{$margin !== null ? $margin . '%' : 'N/A'}}</div>
                    <div class="stat-sub">on selling price</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon-wrap rose">
                        <i class="bi bi-calendar-x"></i>
                    </div>
                    <div class="stat-label">Expires</div>
                    <div class="stat-value" style="font-size:13px;">{{$products->expire ?? '—'}}</div>
                    <div class="stat-sub">best before</div>
                </div>
            </div>

            {{-- ── Product details ── --}}
            <div class="section-title">Product Information</div>
            <div class="detail-grid">
                <div class="detail-cell">
                    <div class="dc-label">Product Name</div>
                    <div class="dc-value">{{$products->name01}}</div>
                </div>
                <div class="detail-cell">
                    <div class="dc-label">Brand / Manufacturer</div>
                    <div class="dc-value">{{$products->name02}}</div>
                </div>
                <div class="detail-cell">
                    <div class="dc-label">Product Code</div>
                    <div class="dc-value mono">{{$products->product_id}}</div>
                </div>
                <div class="detail-cell">
                    <div class="dc-label">Category</div>
                    <div class="dc-value">{{$products->category}}</div>
                </div>
                <div class="detail-cell">
                    <div class="dc-label">Unit Measurement</div>
                    <div class="dc-value">{{$products->unit}}</div>
                </div>
                <div class="detail-cell">
                    <div class="dc-label">Quantity in Stock</div>
                    <div class="dc-value">{{$products->quantity}} {{$products->unit}}</div>
                </div>
                <div class="detail-cell">
                    <div class="dc-label">Wholesale Price</div>
                    <div class="dc-value price">Tsh {{number_format($products->wholesale)}}</div>
                </div>
                <div class="detail-cell">
                    <div class="dc-label">Cost Price</div>
                    <div class="dc-value price">
                        {{$products->bPrice == null ? 'Not Set' : 'Tsh ' . number_format($products->bPrice)}}
                    </div>
                </div>
                <div class="detail-cell">
                    <div class="dc-label">Selling Price</div>
                    <div class="dc-value price">
                        {{$products->sPrice == null ? 'Not Set' : 'Tsh ' . number_format($products->sPrice)}}
                    </div>
                </div>
                <div class="detail-cell">
                    <div class="dc-label">Supplier</div>
                    <div class="dc-value">{{$products->supplier ?? '—'}}</div>
                </div>
                <div class="detail-cell">
                    <div class="dc-label">Expiry Date</div>
                    <div class="dc-value">{{$products->expire ?? 'Not specified'}}</div>
                </div>
                <div class="detail-cell">
                    <div class="dc-label">Discount</div>
                    <div class="dc-value">{{$products->discount ?? '0'}}%</div>
                </div>
            </div>

            {{-- ── Description ── --}}
            <div class="section-title">Description</div>
            <div class="desc-card">
                <p class="desc-text">
                    {{$products->description ?? 'No description available for this product.'}}
                </p>
            </div>

        </div>{{-- /page-content --}}
    </main>
</div>

{{-- ── Edit Product Modal ── --}}
<div class="modal fade" id="editProduct" tabindex="-1" aria-labelledby="editProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editProductLabel">
                    <i class="bi bi-pencil-square me-2"></i>Edit Product Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="updateProducts" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{$products->product_id}}">

                    {{-- Image --}}
                    <div class="form-section-label">
                        <i class="bi bi-image me-1"></i> Product Image
                    </div>
                    <div class="img-preview-block">
                        <div class="img-preview-thumb">
                            <img src="{{asset('/public/images/' . $products->img)}}" alt="Current Image">
                        </div>
                        <div style="flex:1;">
                            <label class="form-label mb-1">Replace Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <div style="font-size:11px; color:var(--slate-400); margin-top:4px;">
                                Current image will be replaced on upload.
                            </div>
                        </div>
                    </div>

                    <div class="form-divider"></div>

                    {{-- Basic info --}}
                    <div class="form-section-label">
                        <i class="bi bi-info-circle me-1"></i> Basic Info
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Product Name</label>
                            <input type="text" class="form-control" name="name01" value="{{$products->name01}}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Product Id (Do not edit)</label>
                            <input type="text" class="form-control" name="product_id2" value="{{$products->product_id}}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Brand / Manufacturer</label>
                            <input type="text" class="form-control" name="name02" value="{{$products->name02}}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="{{$products->category}}" selected>{{$products->category}}</option>
                                <option value="Foods">Foods</option>
                                <option value="Drinks">Drinks</option>
                                <option value="Furniture">Furniture</option>
                                <option value="devices">Electronic Devices</option>
                                <option value="Farming">Farming</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unit Measurement</label>
                            <select name="unit" class="form-select" required>
                                <option value="{{$products->unit}}" selected>{{$products->unit}}</option>
                                <option value="carton">Carton</option>
                                <option value="pieces">Pieces</option>
                                <option value="Kg">Kilogram (Kg)</option>
                                <option value="liter">Liter</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" value="{{$products->quantity}}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Discount (%)</label>
                            <input type="number" class="form-control" name="discount" value="{{$products->discount}}" required>
                        </div>
                    </div>

                    <div class="form-divider"></div>

                    {{-- Pricing --}}
                    <div class="form-section-label">
                        <i class="bi bi-currency-dollar me-1"></i> Pricing (Tsh.)
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Cost Price</label>
                            <div class="price-input-wrap">
                                <span class="currency-prefix">Tsh</span>
                                <input type="number" step="0.01" class="form-control" name="bPrice" value="{{$products->bPrice}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Selling Price</label>
                            <div class="price-input-wrap">
                                <span class="currency-prefix">Tsh</span>
                                <input type="number" step="0.01" class="form-control" name="sPrice" value="{{$products->sPrice}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Wholesale Price</label>
                            <div class="price-input-wrap">
                                <span class="currency-prefix">Tsh</span>
                                <input type="number" step="0.01" class="form-control" name="wholesale" value="{{$products->wholesale}}">
                            </div>
                        </div>
                    </div>

                    <div class="form-divider"></div>

                    {{-- Other --}}
                    <div class="form-section-label">
                        <i class="bi bi-three-dots me-1"></i> Other Details
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Expiry Date</label>
                            <input type="month" class="form-control" name="expiry" value="{{$products->expire}}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Supplier</label>
                            <input type="text" class="form-control" name="supplier" value="{{$products->supplier ?? ''}}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3">{{$products->description}}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-save">
                        <i class="bi bi-check2-circle"></i> Save Changes
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('#editProduct form');
        if (form) {
            form.addEventListener('submit', function (e) {
                const sPrice = parseFloat(document.querySelector('[name="sPrice"]').value);
                const bPrice = parseFloat(document.querySelector('[name="bPrice"]').value);
                if (bPrice && sPrice < bPrice) {
                    e.preventDefault();
                    alert('Selling price cannot be lower than cost price!');
                }
            });
        }
    });
</script>
@include('footer')

</body>
</html>