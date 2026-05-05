<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Shutdown - {{ config('app.name') }}</title>
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
        .shutdown-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            max-width: 600px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .icon-container {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
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
        .icon-container i {
            font-size: 4rem;
            color: white;
        }
        h1 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        .message {
            color: #7f8c8d;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .status-badge {
            display: inline-block;
            background: #ff6b6b;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        .contact-info {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
        }
        .contact-info p {
            margin: 0.5rem 0;
            color: #6c757d;
        }
        .contact-info i {
            color: #3498db;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="shutdown-card">
        <div class="icon-container">
            <i class="fas fa-power-off"></i>
        </div>
        
        <div class="status-badge">
            <i class="fas fa-exclamation-triangle me-2"></i>
            SYSTEM SHUTDOWN
        </div>
        
        <h1>System Temporarily Unavailable</h1>
        
        <p class="message">
            {{ $message ?? 'The system has been shut down by the administrator. All access has been restricted. Please try again later or contact your system administrator for assistance.' }}
        </p>
        
        <div class="mt-4">
            <i class="fas fa-clock fa-2x text-muted mb-3"></i>
            <p class="text-muted">
                <small>All user sessions have been terminated</small>
            </p>
        </div>
        
        <div class="contact-info">
            <p><i class="fas fa-envelope"></i> Contact: <strong>system@lerumapos.com</strong></p>
            <p><i class="fas fa-phone"></i> Support: <strong>+255 123 456 789</strong></p>
            <p class="mb-0"><i class="fas fa-info-circle"></i> <small>Please have your user details ready when contacting support.</small></p>
        </div>
    </div>
</body>
</html>