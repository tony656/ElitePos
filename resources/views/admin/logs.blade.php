<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Logs Management</title>
    @include("links")
    <style>
        /* Your existing styles... */
                :root {
            --primary: #0f3460;
            --primary-light: #16213e;
            --accent: #1abc76;
            --accent-alt: #30c5ff;
            --danger: #e74c3c;
            --warning: #f39c12;
            --info: #3498db;
            --light-bg: #f8f9fa;
            --border-color: #e0e7ff;
            --text-primary: #2c3e50;
            --text-muted: #7f8c8d;
            --border-radius: 1.25rem;
            --box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            --box-shadow-lg: 0 15px 40px rgba(0,0,0,0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            color: #fff;
            min-height: 100vh;
        }

        .container-fluid {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding-bottom: 2rem;
        }

        main {
            padding-bottom: 2rem;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
            border: none;
            box-shadow: var(--box-shadow-lg);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .page-header h3 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-header i {
            font-size: 2rem;
        }

        /* Buttons */
        .btn-toolbar {
            display: flex;
            gap: 1rem;
        }

        .btn-group {
            display: flex;
            gap: 0.75rem;
        }

        .btn-outline-secondary {
            border: 2px solid white;
            color: white;
            padding: 0 0.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .table-container:hover {
            box-shadow: var(--box-shadow-lg);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: linear-gradient(135deg, #f5f7fa 0%, #eff2f5 100%);
            border-bottom: 2px solid var(--border-color);
        }

        .table thead th {
            font-weight: 700;
            padding: 1.25rem;
            color: var(--primary);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--border-color);
        }

        .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(26, 188, 118, 0.05) 0%, transparent 100%);
        }

        .table tbody td {
            padding: 1.25rem;
            vertical-align: middle;
            color: var(--text-primary);
        }

        .table tbody td strong {
            font-weight: 700;
            color: var(--primary);
        }

        .table tbody td.text-muted {
            color: var(--text-muted);
            line-height: 1.6;
            word-break: break-word;
        }

        .table tbody td:last-child {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            border: none;
        }

        .status-pending {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.15) 0%, rgba(243, 156, 18, 0.05) 100%);
            color: #d68910;
            border: 1px solid rgba(243, 156, 18, 0.2);
        }

        .status-completed {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.15) 0%, rgba(76, 175, 80, 0.05) 100%);
            color: #2e7d32;
            border: 1px solid rgba(76, 175, 80, 0.2);
        }

        .status-in-progress {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.15) 0%, rgba(52, 152, 219, 0.05) 100%);
            color: #1565c0;
            border: 1px solid rgba(52, 152, 219, 0.2);
        }

        /* Empty State */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: var(--text-muted);
            opacity: 0.3;
        }

        .empty-state h4 {
            color: var(--text-primary);
            font-weight: 700;
            margin-bottom: 0.75rem;
            font-size: 1.5rem;
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        /* Search Container */
        .search-container {
            position: relative;
            margin-bottom: 2rem;
        }

        .search-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(26, 188, 118, 0.1);
        }

        #search-results {
            width: 100%;
            max-height: 350px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            background: white;
            position: absolute;
            z-index: 1000;
            border-radius: 0 0 0.75rem 0.75rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            display: none;
            top: 100%;
        }

        .search-result-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .search-result-item:hover {
            background: linear-gradient(90deg, rgba(26, 188, 118, 0.05) 0%, transparent 100%);
        }

        .search-result-item:last-child {
            border-bottom: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1.5rem;
            }

            .btn-toolbar {
                width: 100%;
                flex-wrap: wrap;
            }

            .btn-group {
                width: 100%;
            }

            .btn-outline-secondary {
                flex: 1;
                justify-content: center;
            }

            .table thead th,
            .table tbody td {
                padding: 0.75rem 0.5rem;
                font-size: 0.85rem;
            }

            .page-header h3 {
                font-size: 1.35rem;
            }
        }

        @media (max-width: 576px) {
            main {
                padding: 0 1rem;
            }

            .page-header {
                padding: 1.5rem;
            }

            .table-container {
                border-radius: 1rem;
            }
        }
        /* Additional styles for location display */
        .location-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: rgba(26, 188, 118, 0.1);
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            color: #1abc76;
            margin-top: 6px;
        }
        
        .location-badge i {
            font-size: 12px;
        }
        
        .location-details {
            margin-top: 8px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 11px;
            border-left: 3px solid #1abc76;
        }
        
        .location-details span {
            display: inline-block;
            margin-right: 12px;
        }
        
        .coord-link {
            color: #1abc76;
            text-decoration: none;
            cursor: pointer;
        }
        
        .coord-link:hover {
            text-decoration: underline;
        }
        
        .tooltip-custom {
            position: relative;
            display: inline-block;
        }
        
        .tooltip-custom .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 11px;
        }
        
        .tooltip-custom:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
    </style>
    <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @include("admin/sidenav")
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="page-header">
                    <h3>
                        <i class="bi bi-clock-history"></i>Logs Management
                    </h3>
                    <div class="btn-toolbar">
                        <div class="btn-group">
                            <form method="GET" action="{{ url()->current() }}" style="display: flex; gap: 0.5rem; align-items: center;">
                                <input type="date" name="date" class="form-control" value="{{ request('date', \Carbon\Carbon::now()->toDateString()) }}" style="padding: 0.5rem; border-radius: 0.5rem; border: 2px solid rgba(255,255,255,0.3); background: rgba(255,255,255,0.1); color: white;">
                                <button type="submit" class="btn-outline-secondary">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                            </form>
                            <button type="button" class="btn-outline-secondary" onclick="exportLogs()">
                                <i class="bi bi-download"></i> Export
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="15%">Title</th>
                                    <th width="50%">Description & Location</th>
                                    <th width="15%">Location Source</th>
                                    <th width="20%">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($fetch->isEmpty())
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <h4>No Logs Found</h4>
                                            <p>There are currently no activity logs in the system.</p>
                                        </div>
                                    </td>
                                </tr>
                                @else
                                @foreach ($fetch as $log)
                                <tr>
                                    <td>
                                        <strong>{{ $log->title }}</strong>
                                    </td>
                                    <td>
                                        {{ $log->description }}
                                        
                                        @if($log->latitude && $log->longitude)
                                        <div class="location-details">
                                            <strong><i class="bi bi-geo-alt-fill"></i> Precise Location:</strong><br>
                                            <span><i class="bi bi-pin-map"></i> GPS: 
                                                <a href="https://www.google.com/maps?q={{ $log->latitude }},{{ $log->longitude }}" target="_blank" class="coord-link">
                                                    {{ number_format($log->latitude, 6) }}, {{ number_format($log->longitude, 6) }}
                                                </a>
                                                @if($log->accuracy)
                                                <span class="tooltip-custom">
                                                    ±{{ round($log->accuracy) }}m
                                                    <span class="tooltip-text">Accuracy radius: {{ round($log->accuracy) }} meters</span>
                                                </span>
                                                @endif
                                            </span><br>
                                            @if($log->street && $log->street != 'Unknown')
                                            <span><i class="bi bi-building"></i> {{ $log->street }}</span><br>
                                            @endif
                                            @if($log->city && $log->city != 'Unknown')
                                            <span><i class="bi bi-city"></i> {{ $log->city }}</span>
                                            @endif
                                            @if($log->district && $log->district != 'Unknown')
                                            <span><i class="bi bi-diagram-2"></i> {{ $log->district }}</span>
                                            @endif
                                            @if($log->region && $log->region != 'Unknown')
                                            <span><i class="bi bi-map"></i> {{ $log->region }}</span>
                                            @endif
                                            @if($log->country && $log->country != 'Unknown')
                                            <span><i class="bi bi-flag"></i> {{ $log->country }}</span>
                                            @endif
                                            @if($log->timezone)
                                            <span><i class="bi bi-clock"></i> {{ $log->timezone }}</span>
                                            @endif
                                        </div>
                                        @elseif($log->region && $log->region != 'Unknown Location')
                                        <div class="location-details">
                                            <strong><i class="bi bi-globe"></i> Location (IP-based):</strong><br>
                                            <span><i class="bi bi-geo-alt"></i> {{ $log->region }}</span>
                                            @if($log->ip_address)
                                            <span><i class="bi bi-hdd-network"></i> IP: {{ $log->ip_address }}</span>
                                            @endif
                                        </div>
                                        @else
                                        <div class="location-badge">
                                            <i class="bi bi-shield-exclamation"></i> Location not available
                                        </div>
                                        @endif
                                        
                                        @if($log->user_agent)
                                        <div class="location-badge" style="background: rgba(52, 152, 219, 0.1); color: #3498db;">
                                            <i class="bi bi-browser-chrome"></i> {{ substr($log->user_agent, 0, 50) }}{{ strlen($log->user_agent) > 50 ? '...' : '' }}
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->location_source)
                                        <span class="status-badge status-{{ $log->location_source == 'GPS (Precise)' ? 'completed' : 'in-progress' }}" style="font-size: 11px;">
                                            <i class="bi bi-{{ $log->location_source == 'GPS (Precise)' ? 'satellite' : 'globe' }}"></i>
                                            {{ $log->location_source }}
                                        </span>
                                        @else
                                        <span class="status-badge status-pending">Unknown</span>
                                        @endif
                                     </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($log->created_at)->format('M d, Y H:i:s') }}
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</small>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination -->
                @if(method_exists($fetch, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $fetch->links() }}
                </div>
                @endif
            </main>
        </div>
    </div>

    <script>
 
        
        // Optional: Add search functionality
        $(document).ready(function() {
            // Add map link click handler
            $('.coord-link').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                window.open(url, '_blank');
            });
        });
    </script>
</body>
</html>