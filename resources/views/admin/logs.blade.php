<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Logs Management</title>
    @include("links")
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --navy:          #0B1E3D;
            --navy-mid:      #112952;
            --navy-light:    #1A3A6B;
            --amber:         #F59E0B;
            --amber-dark:    #D97706;
            --amber-pale:    #FEF3C7;
            --emerald:       #059669;
            --emerald-pale:  #D1FAE5;
            --rose:          #E11D48;
            --rose-pale:     #FFE4E6;
            --violet:        #7C3AED;
            --violet-pale:   #EDE9FE;
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
            font-family: 'Outfit', sans-serif;
            background: #EEF2F9;
            color: var(--slate-800);
            min-height: 100vh;
            line-height: 1.6;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--slate-100); }
        ::-webkit-scrollbar-thumb { background: var(--slate-300); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--slate-400); }

        /* ── Main wrap ── */
        .main-wrap { max-width: 1900px; margin: 0 auto; padding: 1.25rem 1.5rem; }

        /* ── Page header ── */
        .pg-header {
            background: var(--navy);
            border-radius: 12px;
            padding: 1.4rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 32px rgba(11,30,61,0.28);
            position: relative;
            overflow: hidden;
        }

        .pg-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: rgba(245,158,11,0.08);
            border-radius: 50%;
            pointer-events: none;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .pg-icon-wrap {
            width: 52px; height: 52px;
            background: rgba(245,158,11,0.15);
            border: 1.5px solid rgba(245,158,11,0.3);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--amber);
            font-size: 1.5rem;
        }

        .pg-title-wrap h1 {
            color: var(--white); font-size: 1.45rem; font-weight: 700;
            margin: 0 0 0.15rem 0;
        }
        .pg-subtitle {
            color: rgba(255,255,255,0.7); font-size: 0.82rem;
            margin: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .date-picker-wrap {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.1);
            padding: 0.5rem 0.85rem;
            border-radius: 8px;
            border: 1.5px solid rgba(255,255,255,0.2);
        }

        .date-input {
            padding: 0.4rem 0.65rem;
            border: none;
            border-radius: 6px;
            background: rgba(255,255,255,0.15);
            color: var(--white);
            font-size: 0.82rem;
            outline: none;
            cursor: pointer;
            font-family: 'Outfit', sans-serif;
        }
        .date-input::-webkit-calendar-picker-indicator {
            filter: invert(1);
        }

        .btn-filter {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.5rem 0.85rem;
            background: rgba(255,255,255,0.15);
            color: var(--white);
            border: 1.5px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.18s;
        }
        .btn-filter:hover {
            background: rgba(255,255,255,0.25);
            border-color: var(--amber);
        }

        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.5rem 0.85rem;
            background: var(--amber);
            color: var(--navy);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 3px 12px rgba(245,158,11,0.3);
            transition: all 0.18s;
        }
        .btn-export:hover {
            background: var(--amber-dark);
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(245,158,11,0.4);
        }

        /* ── Table card ── */
        .table-card {
            background: var(--white);
            border: 1.5px solid var(--slate-200);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(11,30,61,0.04);
            overflow: hidden;
        }

        table.logs-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        table.logs-tbl thead th {
            background: var(--slate-100);
            color: var(--slate-500);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 0.85rem;
            border-bottom: 2px solid var(--slate-200);
            white-space: nowrap;
        }

        table.logs-tbl tbody td {
            padding: 1rem 0.85rem;
            border-bottom: 1px solid var(--slate-100);
            vertical-align: top;
            color: var(--slate-800);
        }

        table.logs-tbl tbody tr:hover td {
            background: #F8FAFF;
        }

        .log-title {
            font-weight: 700;
            color: var(--navy);
            font-size: 0.875rem;
        }

        .log-desc {
            color: var(--slate-600);
            font-size: 0.82rem;
            line-height: 1.6;
            margin-bottom: 0.75rem;
        }

        /* ── Location info ── */
        .location-panel {
            background: var(--slate-50);
            border-left: 3px solid var(--emerald);
            border-radius: 8px;
            padding: 0.85rem;
            margin-top: 0.75rem;
        }

        .location-header {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--emerald);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .location-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: var(--slate-600);
            margin-bottom: 0.35rem;
        }

        .location-row:last-child {
            margin-bottom: 0;
        }

        .location-row i {
            color: var(--slate-400);
            width: 14px;
        }

        .coord-link {
            font-family: 'DM Mono', monospace;
            font-weight: 600;
            color: var(--sky);
            text-decoration: none;
            transition: all 0.15s;
        }
        .coord-link:hover {
            color: var(--navy);
            text-decoration: underline;
        }

        .accuracy-badge {
            display: inline-flex;
            align-items: center;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.45rem;
            border-radius: 4px;
            background: var(--amber-pale);
            color: #92400E;
            margin-left: 0.35rem;
        }

        .ip-location-panel {
            background: var(--sky-pale);
            border-left: 3px solid var(--sky);
        }

        .ip-location-panel .location-header {
            color: var(--sky);
        }

        .no-location-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.35rem 0.65rem;
            border-radius: 5px;
            background: var(--slate-200);
            color: var(--slate-600);
            margin-top: 0.75rem;
        }

        .user-agent-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.35rem 0.65rem;
            border-radius: 5px;
            background: var(--violet-pale);
            color: var(--violet);
            margin-top: 0.5rem;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* ── Source badge ── */
        .source-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.35rem 0.65rem;
            border-radius: 5px;
        }

        .source-badge.gps {
            background: var(--emerald-pale);
            color: #065F46;
        }

        .source-badge.ip {
            background: var(--sky-pale);
            color: #075985;
        }

        .source-badge.unknown {
            background: var(--slate-200);
            color: var(--slate-600);
        }

        /* ── Timestamp ── */
        .timestamp-cell {
            font-size: 0.78rem;
        }

        .timestamp-main {
            font-family: 'DM Mono', monospace;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: 0.25rem;
        }

        .timestamp-relative {
            font-size: 0.72rem;
            color: var(--slate-500);
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 4rem 1.5rem;
        }
        .empty-icon {
            width: 80px; height: 80px;
            margin: 0 auto 1rem;
            display: flex; align-items: center; justify-content: center;
            background: var(--slate-100);
            border-radius: 50%;
            color: var(--slate-400);
            font-size: 2rem;
        }
        .empty-title {
            font-size: 1.1rem; font-weight: 700;
            color: var(--slate-600);
            margin-bottom: 0.4rem;
        }
        .empty-desc {
            font-size: 0.875rem; color: var(--slate-500);
        }

        /* ── Pagination ── */
        .pagination-wrap {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .main-wrap { padding: 1rem; }
            .pg-header { padding: 1rem; margin-bottom: 1rem; }
            .header-row { flex-direction: column; align-items: flex-start; }
            .header-actions { width: 100%; flex-direction: column; }
            .date-picker-wrap { width: 100%; }
            .btn-export { width: 100%; justify-content: center; }

            table.logs-tbl thead { display: none; }
            table.logs-tbl tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1.5px solid var(--slate-200);
                border-radius: 10px;
                padding: 1rem;
                background: var(--white);
            }
            table.logs-tbl tbody td {
                display: block;
                padding: 0.65rem 0;
                border-bottom: none;
            }
            table.logs-tbl tbody td:not(:last-child) {
                border-bottom: 1px solid var(--slate-100);
                padding-bottom: 0.85rem;
                margin-bottom: 0.85rem;
            }
        }

        /* ── Animation ── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .table-card { animation: slideUp 0.4s ease forwards; }

        /* ── Tooltip ── */
        .tooltip-wrap {
            position: relative;
            display: inline-block;
            cursor: help;
        }

        .tooltip-wrap:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: var(--navy);
            color: var(--white);
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.72rem;
            white-space: nowrap;
            z-index: 1000;
            margin-bottom: 0.5rem;
            box-shadow: 0 4px 12px rgba(11,30,61,0.3);
        }
    </style>
</head>
<body>

<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
        <div class="main-wrap">

            {{-- ── Page Header ── --}}
            <div class="pg-header">
                <div class="header-row">
                    <div class="header-left">
                        <div class="pg-icon-wrap">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="pg-title-wrap">
                            <h1>Activity Logs</h1>
                            <p class="pg-subtitle">Monitor system activities and user actions</p>
                        </div>
                    </div>
                    <div class="header-actions">
                        <form method="GET" action="{{ url()->current() }}" style="display: flex; gap: 0.75rem; align-items: center;">
                            <div class="date-picker-wrap">
                                <i class="bi bi-calendar3" style="color: rgba(255,255,255,0.7);"></i>
                                <input type="date" name="date" class="date-input" 
                                    value="{{ request('date', \Carbon\Carbon::now()->toDateString()) }}">
                            </div>
                            <button type="submit" class="btn-filter">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </form>
                        <button type="button" class="btn-export" onclick="exportLogs()">
                            <i class="bi bi-download"></i> Export
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── Logs Table ── --}}
            <div class="table-card">
                <div class="table-responsive">
                    <table class="logs-tbl">
                        <thead>
                            <tr>
                                <th width="15%">Title</th>
                                <th width="50%">Description & Location</th>
                                <th width="15%">Source</th>
                                <th width="20%">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($fetch->isEmpty())
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <div class="empty-title">No Logs Found</div>
                                        <p class="empty-desc">There are currently no activity logs in the system.</p>
                                    </div>
                                </td>
                            </tr>
                            @else
                                @foreach ($fetch as $log)
                                <tr>
                                    <td>
                                        <div class="log-title">{{ $log->title }}</div>
                                    </td>
                                    <td>
                                        <div class="log-desc">{{ $log->description }}</div>

                                        {{-- GPS Location --}}
                                        @if($log->latitude && $log->longitude)
                                        <div class="location-panel">
                                            <div class="location-header">
                                                <i class="bi bi-geo-alt-fill"></i> Precise Location (GPS)
                                            </div>
                                            <div class="location-row">
                                                <i class="bi bi-pin-map"></i>
                                                <a href="https://www.google.com/maps?q={{ $log->latitude }},{{ $log->longitude }}" 
                                                   target="_blank" class="coord-link">
                                                    {{ number_format($log->latitude, 6) }}, {{ number_format($log->longitude, 6) }}
                                                </a>
                                                @if($log->accuracy)
                                                <span class="accuracy-badge tooltip-wrap" 
                                                    data-tooltip="Accuracy radius: {{ round($log->accuracy) }} meters">
                                                    ±{{ round($log->accuracy) }}m
                                                </span>
                                                @endif
                                            </div>
                                            @if($log->street && $log->street != 'Unknown')
                                            <div class="location-row">
                                                <i class="bi bi-building"></i> {{ $log->street }}
                                            </div>
                                            @endif
                                            @if($log->city && $log->city != 'Unknown')
                                            <div class="location-row">
                                                <i class="bi bi-city"></i> {{ $log->city }}
                                            </div>
                                            @endif
                                            @if($log->district && $log->district != 'Unknown')
                                            <div class="location-row">
                                                <i class="bi bi-diagram-2"></i> {{ $log->district }}
                                            </div>
                                            @endif
                                            @if($log->region && $log->region != 'Unknown')
                                            <div class="location-row">
                                                <i class="bi bi-map"></i> {{ $log->region }}
                                            </div>
                                            @endif
                                            @if($log->country && $log->country != 'Unknown')
                                            <div class="location-row">
                                                <i class="bi bi-flag"></i> {{ $log->country }}
                                            </div>
                                            @endif
                                            @if($log->timezone)
                                            <div class="location-row">
                                                <i class="bi bi-clock"></i> {{ $log->timezone }}
                                            </div>
                                            @endif
                                        </div>

                                        {{-- IP Location --}}
                                        @elseif($log->region && $log->region != 'Unknown Location')
                                        <div class="location-panel ip-location-panel">
                                            <div class="location-header">
                                                <i class="bi bi-globe"></i> Location (IP-based)
                                            </div>
                                            <div class="location-row">
                                                <i class="bi bi-geo-alt"></i> {{ $log->region }}
                                            </div>
                                            @if($log->ip_address)
                                            <div class="location-row">
                                                <i class="bi bi-hdd-network"></i> IP: <span style="font-family: 'DM Mono', monospace;">{{ $log->ip_address }}</span>
                                            </div>
                                            @endif
                                        </div>

                                        {{-- No Location --}}
                                        @else
                                        <div class="no-location-badge">
                                            <i class="bi bi-shield-exclamation"></i> Location not available
                                        </div>
                                        @endif

                                        {{-- User Agent --}}
                                        @if($log->user_agent)
                                        <div class="user-agent-badge tooltip-wrap" data-tooltip="{{ $log->user_agent }}">
                                            <i class="bi bi-browser-chrome"></i> 
                                            {{ substr($log->user_agent, 0, 50) }}{{ strlen($log->user_agent) > 50 ? '...' : '' }}
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->location_source)
                                        <span class="source-badge {{ $log->location_source == 'GPS (Precise)' ? 'gps' : 'ip' }}">
                                            <i class="bi bi-{{ $log->location_source == 'GPS (Precise)' ? 'satellite' : 'globe' }}"></i>
                                            {{ $log->location_source }}
                                        </span>
                                        @else
                                        <span class="source-badge unknown">
                                            <i class="bi bi-question-circle"></i> Unknown
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="timestamp-cell">
                                            <div class="timestamp-main">
                                                {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i:s') }}
                                            </div>
                                            <div class="timestamp-relative">
                                                {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── Pagination ── --}}
            @if(method_exists($fetch, 'links'))
            <div class="pagination-wrap">
                {{ $fetch->links() }}
            </div>
            @endif

        </div>
    </main>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function exportLogs() {
        // Export functionality
        alert('Export functionality would be implemented here');
    }

    $(document).ready(function() {
        // Map link click handler
        $('.coord-link').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            window.open(url, '_blank');
        });
    });
</script>

</body>
</html>