<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Internal Server Error</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0B1E3D;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .error-container {
            text-align: center;
            max-width: 600px;
        }
        .error-code {
            font-size: 120px;
            font-weight: 700;
            color: #DC2626;
            margin: 0;
            line-height: 1;
        }
        .error-title {
            font-size: 32px;
            font-weight: 600;
            margin: 20px 0 10px;
        }
        .error-message {
            color: rgba(255,255,255,0.7);
            font-size: 18px;
            margin-bottom: 30px;
        }
        .btn-home {
            display: inline-block;
            padding: 12px 32px;
            background: #F59E0B;
            color: #0B1E3D;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            margin: 0 5px;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
        }
        .btn-back {
            display: inline-block;
            padding: 12px 32px;
            background: transparent;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            border: 2px solid rgba(255,255,255,0.2);
            transition: all 0.3s;
            margin: 0 5px;
        }
        .btn-back:hover {
            border-color: #F59E0B;
            background: rgba(245, 158, 11, 0.1);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-code">500</h1>
        <h2 class="error-title">Internal Server Error</h2>
        <p class="error-message">
            <i class="fas fa-exclamation-triangle" style="color: #FBBF24; font-size: 24px; display: block; margin-bottom: 15px;"></i>
            Something went wrong on our end. Please try again later or contact support if the problem persists.
        </p>
        <div>
            <a href="{{ url('/dashboard') }}" class="btn-home">
                <i class="fas fa-home"></i> Go to Dashboard
            </a>
            <a href="javascript:history.back()" class="btn-back">
                <i class="fas fa-arrow-left"></i> Go Back
            </a>
        </div>
        <p style="color: rgba(255,255,255,0.3); font-size: 12px; margin-top: 30px;">
            <i class="fas fa-clock"></i> 
            {{ date('F d, Y \a\t H:i') }}
        </p>
    </div>
</body>
</html>