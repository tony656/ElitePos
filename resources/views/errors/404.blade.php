<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
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
            color: #F59E0B;
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
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.3);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1 class="error-code">404</h1>
        <h2 class="error-title">Page Not Found</h2>
        <p class="error-message">Oops! The page you're looking for doesn't exist or has been moved.</p>
        <a href="{{ url('/dashboard') }}" class="btn-home">
            <i class="fas fa-home"></i> Go to Dashboard
        </a>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if the success message and sound flag exist
        
            playSuccessSound();
        
    });

    function playSuccessSound() {
        // Create a new Audio object with your MP3 file path
        var audio = new Audio('/public/sounds/eh.mp3'); // Put your MP3 in public/sounds/
        
        // Play the sound
        audio.play().catch(function(error) {
            // Handle autoplay restrictions gracefully
            console.log('Audio playback failed:', error);
            // You could show a play button as fallback
        });
    }
</script>
</body>
</html>