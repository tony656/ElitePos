<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config("app.name") }} - Login</title>
  @include("links")
  <style>
    /* Your existing styles remain the same */
  </style>
</head>
<body>

  <div class="container">
    <form action="login" method="post" id="loginForm">
      @csrf
      <div class="login-card">
        <div class="login-header">
          <h1>Welcome Back</h1>
          <p>Sign in to your account</p>
        </div>
        
        @if(session('success'))
          <div class="alert alert-success d-flex justify-content-between">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif
        
        @if(session('error'))
          <div class="alert alert-danger d-flex justify-content-between">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <div class="form-group">
          <i class="bi bi-envelope"></i>
          <input type="email" name="email" class="form-control" placeholder="Email address" required>
        </div>

        <div class="form-group">
          <i class="bi bi-lock"></i>
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <div class="form-group d-flex align-items-center">
          <input type="checkbox" id="remember" name="remember" class="form-check-input" style="width: 18px; height: 18px; margin-right: 10px; accent-color: #1abc76;">
          <label for="remember" style="color: rgba(255,255,255,0.7); font-size: 0.9rem; cursor: pointer;">Remember me</label>
        </div>

        <!-- Hidden fields for location data -->
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <input type="hidden" name="accuracy" id="accuracy">
        <input type="hidden" name="precise_location" id="precise_location">
        <input type="hidden" name="client_timezone" id="client_timezone">

        <button type="submit" class="btn-login" id="loginBtn">Sign In</button>
      </div>
    </form>
  </div>

  <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const loginBtn = document.getElementById('loginBtn');
      loginBtn.disabled = true;
      loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Getting location...';
      
      // Set client timezone
      const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
      document.getElementById('client_timezone').value = timezone;
      
      // Request precise location from user
      if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(
          function(position) {
            // Success - got precise location
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            const accuracy = position.coords.accuracy;
            
            document.getElementById('latitude').value = latitude;
            document.getElementById('longitude').value = longitude;
            document.getElementById('accuracy').value = accuracy;
            
            // Create a readable location string
            document.getElementById('precise_location').value = `Lat: ${latitude}, Lon: ${longitude} (Accuracy: ${Math.round(accuracy)}m)`;
            
            // Submit the form
            document.getElementById('loginForm').submit();
          },
          function(error) {
            // User denied or error getting location
            console.log('Geolocation error:', error.message);
            
            let errorMessage = '';
            switch(error.code) {
              case error.PERMISSION_DENIED:
                errorMessage = 'Location access denied. You can still login, but some features may be limited.';
                break;
              case error.POSITION_UNAVAILABLE:
                errorMessage = 'Location information unavailable. You can still login.';
                break;
              case error.TIMEOUT:
                errorMessage = 'Location request timed out. You can still login.';
                break;
              default:
                errorMessage = 'Unable to get your location. You can still login.';
            }
            
            // Show warning but still submit
            if (confirm(errorMessage + '\n\nContinue with login?')) {
              document.getElementById('loginForm').submit();
            } else {
              loginBtn.disabled = false;
              loginBtn.innerHTML = 'Sign In';
            }
          },
          {
            enableHighAccuracy: true,  // Request most accurate location
            timeout: 10000,            // 10 second timeout
            maximumAge: 0              // Don't use cached location
          }
        );
      } else {
        // Geolocation not supported by browser
        alert('Your browser does not support geolocation. You can still login.');
        document.getElementById('loginForm').submit();
      }
    });
  </script>
</body>
</html>