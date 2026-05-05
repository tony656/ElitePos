<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Emergency Admin Access - Elite POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .emergency-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }
        .emergency-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #ff6b6b, #ee5a5a, #ff6b6b);
            animation: emergencyPulse 2s infinite;
        }
        @keyframes emergencyPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        .emergency-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a5a 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .emergency-icon i {
            font-size: 2.5rem;
            color: white;
        }
        .emergency-badge {
            display: inline-block;
            background: #ff6b6b;
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }
        h2 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            color: #7f8c8d;
            font-size: 1rem;
            margin-bottom: 2rem;
        }
        .warning-box {
            background: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .warning-box i {
            color: #ffc107;
            margin-right: 0.5rem;
        }
        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #555;
            margin-bottom: 0.5rem;
        }
        .form-control {
            padding: 0.75rem 1rem;
            border: 2px solid #e2e5ea;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: #ff6b6b;
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
            outline: none;
        }
        .btn-emergency {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a5a 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 1rem;
        }
        .btn-emergency:hover {
            background: linear-gradient(135deg, #ee5a5a 0%, #dd4949 100%);
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }
        .btn-emergency:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        .emergency-info {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e5ea;
        }
        .emergency-info p {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        .emergency-info i {
            color: #ff6b6b;
            margin-right: 0.5rem;
        }
        .error-alert {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.6s linear infinite;
            margin-right: 8px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="emergency-card">
        <div class="emergency-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        
        <div class="emergency-badge">
            <i class="fas fa-exclamation-triangle me-2"></i>Emergency Access
        </div>
        
        <h2>System Recovery Mode</h2>
        <p class="subtitle">Use this form when system is locked out</p>

        @if(session('error'))
        <div class="error-alert">
            <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
        </div>
        @endif

        <div class="warning-box">
            <i class="fas fa-info-circle"></i>
            <strong>Important:</strong> This access bypasses system shutdown and sign-in blocks. 
            Your session will be time-limited to <strong>{{ env('EMERGENCY_ACCESS_DURATION_MINUTES', 60) }} minutes</strong>.
        </div>

        <form action="{{ route('admin.emergency.login.process') }}" method="POST" id="emergencyForm">
            @csrf
            
            <div class="mb-3">
                <label class="form-label" for="email">Admin Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                       placeholder="admin@elitepos.co.tz" required autofocus>
            </div>
            
            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="Enter your password" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label" for="emergency_code">Emergency Access Code</label>
                <input type="text" class="form-control" id="emergency_code" name="emergency_code" 
                       placeholder="Enter emergency code from .env file" required>
                <small class="text-muted">
                    <i class="fas fa-key me-1"></i>Check your <code>.env</code> file for <strong>EMERGENCY_ADMIN_PASSWORD</strong>
                </small>
            </div>

            <button type="submit" class="btn-emergency" id="submitBtn">
                <i class="fas fa-unlock me-2"></i>Grant Emergency Access
            </button>
        </form>

        <div class="emergency-info">
            <p><i class="fas fa-shield-alt"></i>This feature is only for system administrators</p>
            <p><i class="fas fa-clock"></i>Emergency sessions automatically expire after {{ env('EMERGENCY_ACCESS_DURATION_MINUTES', 60) }} minutes</p>
            <p><i class="fas fa-history"></i>All emergency access attempts are logged</p>
            <p class="mb-0"><i class="fas fa-ban"></i>Cannot be used during normal operations</p>
        </div>
    </div>

    <script>
        document.getElementById('emergencyForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Verifying...';
        });
    </script>
</body>
</html>