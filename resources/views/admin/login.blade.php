<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config("app.name") }} - Login</title>
  @include("links")
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', sans-serif;
      background: linear-gradient(135deg, #0f3460 0%, #16213e 50%, #0f3460 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      overflow: hidden;
    }

    /* Animated background elements */
    body::before {
      content: '';
      position: absolute;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, rgba(26, 188, 230, 0.1) 0%, transparent 70%);
      border-radius: 50%;
      top: -100px;
      left: -100px;
      animation: float 8s ease-in-out infinite;
    }

    body::after {
      content: '';
      position: absolute;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, rgba(26, 188, 230, 0.08) 0%, transparent 70%);
      border-radius: 50%;
      bottom: -50px;
      right: -50px;
      animation: float 10s ease-in-out infinite reverse;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(30px); }
    }

    .container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 420px;
      padding: 1.5rem;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.15);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      border-radius: 2.5rem;
      padding: 3.5rem 2.5rem;
      transition: all 0.3s ease;
    }

    .login-card:hover {
      background: rgba(255, 255, 255, 0.1);
      border-color: rgba(26, 188, 230, 0.3);
      box-shadow: 0 20px 80px rgba(26, 188, 230, 0.15);
    }

    .login-header {
      margin-bottom: 2.5rem;
      text-align: center;
    }

    .login-header h1 {
      color: #fff;
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      letter-spacing: -0.5px;
    }

    .login-header p {
      color: rgba(255, 255, 255, 0.6);
      font-size: 0.9rem;
      font-weight: 500;
    }

    .alert {
      padding: 1rem;
      border-radius: 1.2rem;
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
      border: none;
      animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-success {
      background: rgba(76, 175, 80, 0.2);
      color: #a8e6a1;
      border-left: 4px solid #4caf50;
    }

    .alert-danger {
      background: rgba(244, 67, 54, 0.2);
      color: #ff9999;
      border-left: 4px solid #f44336;
    }

    .form-group {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .form-control {
      width: 100%;
      background: rgba(255, 255, 255, 0.12);
      border: 2px solid rgba(255, 255, 255, 0.2);
      color: #fff;
      padding: 1rem 1rem 1rem 2.75rem;
      border-radius: 1.2rem;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      font-weight: 500;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }

    .form-control:focus {
      outline: none;
      background: rgba(255, 255, 255, 0.15);
      border-color: rgba(26, 188, 230, 0.5);
      box-shadow: 0 0 20px rgba(26, 188, 230, 0.2);
      color: #fff;
    }

    .form-group i {
      position: absolute;
      left: 1.2rem;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(255, 255, 255, 0.5);
      transition: color 0.3s ease;
      font-size: 1.1rem;
    }

    .form-group:has(.form-control:focus) i {
      color: rgba(26, 188, 230, 0.8);
    }

    .btn-login {
      width: 100%;
      background: linear-gradient(135deg, #1abc76 0%, #0d9654 100%);
      border: none;
      color: #fff;
      padding: 1.1rem;
      border-radius: 1.2rem;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 1.5rem;
      letter-spacing: 0.5px;
      box-shadow: 0 10px 30px rgba(26, 188, 230, 0.2);
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 15px 40px rgba(26, 188, 230, 0.3);
      background: linear-gradient(135deg, #20d67b 0%, #12b055 100%);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    @media (max-width: 480px) {
      .login-card {
        padding: 2.5rem 1.5rem;
      }

      .login-header h1 {
        font-size: 1.5rem;
      }

      body::before {
        width: 250px;
        height: 250px;
      }

      body::after {
        width: 200px;
        height: 200px;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <form action="login" method="post">
      @csrf
      <div class="login-card">
        <div class="login-header">
          <h1>Welcome Back</h1>
          <p>Sign in to your account</p>
        </div>
    @if(session('success'))
      <div class="alert alert-success  d-flex justify-content-between">
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
        @if(session('success'))
        <div class="alert alert-success">
          <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger">
          <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
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

        <button type="submit" class="btn-login">Sign In</button>
      </div>
    </form>
  </div>

</body>
</html>