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
        @include('sidenav')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
            
            <div class="container px-2 d-flex py-3">
                <div class="p-4">
                    <img src="{{ asset('/public/images/EliteLogo.png') }}" width="100px" alt="">
                </div>
                <div class="p-4">
                    <h4 class="fw-bold">
                       Leruma Enterprise Security
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

            <div class="container mt-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-4">
                                    <i class="bi bi-shield-lock text-primary me-2"></i>
                                    System Security Controls
                                </h5>
                                
                                <div class="alert alert-{{ $systemShutdown ? 'danger' : 'success' }} mb-4">
                                    <i class="bi bi-{{ $systemShutdown ? 'exclamation-triangle' : 'check-circle' }} me-2"></i>
                                    <strong>System Status:</strong>
                                    {{ $systemShutdown ? 'SYSTEM SHUTDOWN - All access restricted' : 'System operational' }}
                                </div>

                                <div class="row g-4">
                                    <!-- Block All Sign-ins -->
                                    <div class="col-md-6">
                                        <div class="card h-100 border-{{ $blockSignins ? 'danger' : 'success' }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h5 class="card-title mb-2">
                                                            <i class="bi bi-person-x text-{{ $blockSignins ? 'danger' : 'success' }} me-2"></i>
                                                            Block All Sign-ins
                                                        </h5>
                                                        <p class="card-text text-muted mb-3">
                                                            {{ $blockSignins ? 'Currently ACTIVE - No new users can sign in' : 'Currently INACTIVE - Sign-ins allowed' }}
                                                        </p>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="blockSigninsSwitch"
                                                               {{ $blockSignins ? 'checked' : '' }}
                                                               onchange="toggleBlockSignins(this)">
                                                        <label class="form-check-label" for="blockSigninsSwitch"></label>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <small class="text-muted">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        When enabled, all user sign-ins will be blocked system-wide. Existing sessions remain active.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- System Shutdown -->
                                    <div class="col-md-6">
                                        <div class="card h-100 border-{{ $systemShutdown ? 'danger' : 'success' }}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h5 class="card-title mb-2">
                                                            <i class="bi bi-power text-{{ $systemShutdown ? 'danger' : 'success' }} me-2"></i>
                                                            System Shutdown
                                                        </h5>
                                                        <p class="card-text text-muted mb-3">
                                                            {{ $systemShutdown ? 'Currently ACTIVE - System is shut down' : 'Currently INACTIVE - System operational' }}
                                                        </p>
                                                    </div>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="systemShutdownSwitch"
                                                               {{ $systemShutdown ? 'checked' : '' }}
                                                               onchange="toggleSystemShutdown(this)">
                                                        <label class="form-check-label" for="systemShutdownSwitch"></label>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <small class="text-muted">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        When enabled, the entire system will be shut down. All users will be logged out and prevented from accessing the system.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="mt-4 p-3 bg-light rounded">
                                    <h6><i class="bi bi-exclamation-triangle text-warning me-2"></i>Important Notes:</h6>
                                    <ul class="mb-0 ps-3">
                                        <li>These controls affect the entire system globally</li>
                                        <li>Only administrators can modify these settings</li>
                                        <li>All actions are logged in the system</li>
        

                                        <li>To restore normal operations, simply toggle the switches off</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


    <script>
        function toggleBlockSignins(checkbox) {
            const originalState = checkbox.checked;
            fetch('{{ route('toggle.block.signins') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ block: checkbox.checked })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.message) {
                    console.log('Success:', data);
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                checkbox.checked = !originalState;
                const errorMsg = error.message || 'Failed to update setting. You may not have permission.';
                alert('Error: ' + errorMsg);
            });
        }

        function toggleSystemShutdown(checkbox) {
            if (!confirm('Are you sure you want to ' + (checkbox.checked ? 'SHUT DOWN' : 'RESTORE') + ' the system? This will affect all users.')) {
                checkbox.checked = !checkbox.checked;
                return;
            }
            
            const originalState = checkbox.checked;
            fetch('{{ route('toggle.system.shutdown') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ shutdown: checkbox.checked })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.message) {
                    console.log('Success:', data);
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                checkbox.checked = !originalState;
                const errorMsg = error.message || 'Failed to update setting. You may not have permission.';
                alert('Error: ' + errorMsg);
            });
        }
     
    </script>
    @include('footer')

</body>
</html>