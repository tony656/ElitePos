<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Security</title>
    @include('links')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
    <style>
        .session-card {
            border-left: 4px solid #28a745;
            transition: all 0.3s ease;
        }
        .session-card.suspicious {
            border-left-color: #ffc107;
            background-color: #fff8e1;
        }
        .session-card.blocked {
            border-left-color: #dc3545;
            background-color: #ffe5e5;
        }
        .status-badge {
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
        }
        .device-info {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }
        .info-item {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .info-item strong {
            color: #333;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .action-btn {
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
        }
        .live-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }
        .live-dot {
            width: 8px;
            height: 8px;
            background-color: #28a745;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .security-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .control-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .alerts-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .alert-item {
            padding: 0.75rem;
            border-left: 4px solid;
            margin-bottom: 0.5rem;
            border-radius: 0.25rem;
            background-color: #f8f9fa;
        }
        .alert-item.high {
            border-left-color: #dc3545;
            background-color: #ffe5e5;
        }
        .alert-item.medium {
            border-left-color: #ffc107;
            background-color: #fff8e1;
        }
        .alert-item.low {
            border-left-color: #17a2b8;
            background-color: #e1f5f8;
        }
        .alert-timestamp {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <main class="row">
        @include('admin/sidenav')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
            
            <div class="container px-2 d-flex py-3">
                <div class="p-4">
                    <img src="{{ asset('images/EliteLogo.png') }}" width="100px" alt="">
                </div>
                <div class="p-4">
                    <h4 class="fw-bold">
                       Elite Security
                    </h4>
                     <div class="container">
                <div class="btn-group w-100 mt-3">
                    <button class="btn">
                        <div class="container">
                            <h5>
                                Online
                            </h5>
                            <p>
                                {{ $getOnline }}
                            </p>
                        </div>
                    </button>
                     <button class="btn">
                        <div class="container">
                            <h5>
                                Offline
                            </h5>
                            <p>
                                {{ $getOffline }}
                            </p>
                        </div>
                    </button>
                </div>
            </div>
                </div>
            </div>

           

            <div class="container">

                <h5>
                    Activity
                </h5>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>User</th>
                                                <th>Status</th>
                                                <th>Timestamp</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($logs as $log)
                                            <tr class="log-card {{ ($log->status == 'done' && strtolower($log->user ?? '') == 'unknown') || (isset($log->user) && strtolower($log->user) == 'unknown') ? 'suspicious' : '' }}">
                                                <td>{{ $log->title }}</td>
                                                <td>{{ $log->description }}</td>
                                                <td>{{ $log->user ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge {{ $log->status == 'done' ? 'bg-success' : 'bg-warning' }}">
                                                        {{ $log->status }}
                                                    </span>
                                                </td>
                                                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>

</body>
</html>