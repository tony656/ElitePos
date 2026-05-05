<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config("app.name") }} - Select Account</title>
  @include("links")
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: url("https://wallpapercave.com/wp/wp13508917.jpg");
      background-repeat: no-repeat;
      background-position: center;
      background-size: cover;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      overflow: hidden;
    }

    .container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 450px;
      padding: 1.5rem;
    }

    .account-card {
      background: linear-gradient(135deg, #0f3460 0%, #16213e 50%, #0f3460 100%);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.15);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      border-radius: 2.5rem;
      padding: 3rem 2.5rem;
      transition: all 0.3s ease;
    }

    .account-card:hover {
      border: 3px solid gold;
      box-shadow: 0 20px 80px rgba(26, 188, 230, 0.15);
    }

    .account-header {
      text-align: center;
      margin-bottom: 2rem;
    }

    .account-header h1 {
      color: #fff;
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .account-header p {
      color: rgba(255, 255, 255, 0.6);
      font-size: 0.9rem;
    }

    .account-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      max-height: 300px;
      overflow-y: auto;
      padding-right: 0.5rem;
    }

    .account-list::-webkit-scrollbar {
      width: 6px;
    }

    .account-list::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 3px;
    }

    .account-list::-webkit-scrollbar-thumb {
      background: rgba(26, 188, 230, 0.5);
      border-radius: 3px;
    }

    .account-list::-webkit-scrollbar-thumb:hover {
      background: rgba(26, 188, 230, 0.8);
    }

    .account-item {
      display: flex;
      align-items: center;
      padding: 1.25rem;
      background: rgba(255, 255, 255, 0.1);
      border: 2px solid rgba(255, 255, 255, 0.2);
      border-radius: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .account-item:hover {
      background: rgba(255, 255, 255, 0.15);
      border-color: rgba(26, 188, 230, 0.5);
      transform: translateY(-2px);
    }

    .account-icon {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, #1abc76 0%, #0d9654 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
      font-size: 1.5rem;
    }

    .account-info {
      flex: 1;
    }

    .account-name {
      color: #fff;
      font-weight: 600;
      font-size: 1.1rem;
    }

    .account-label {
      color: rgba(255, 255, 255, 0.5);
      font-size: 0.8rem;
      margin-top: 0.25rem;
    }

    .account-arrow {
      color: rgba(255, 255, 255, 0.5);
      font-size: 1.2rem;
    }

    .alert {
      padding: 1rem;
      border-radius: 1.2rem;
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
      border: none;
    }

    .alert-danger {
      background: rgba(244, 67, 54, 0.2);
      color: #ff9999;
      border-left: 4px solid #f44336;
    }
  </style>
</head>
<body>
  <div class="container">
    <form action="{{ route('select.account') }}" method="POST">
      @csrf
      <div class="account-card">
        <div class="account-header">
            <img src="{{ asset('images/EliteLogoW.PNG') }}" width="60" alt="Logo">
          <h1>Select Account</h1>
          <p>Choose which account to access</p>
        </div>

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <div class="account-list">
          @foreach($accounts as $userAccount)
          <label class="account-item">
            <input type="radio" name="account" value="{{ $userAccount->account }}" required style="display: none;">
            <div class="account-icon">🏪</div>
            <div class="account-info">
              <div class="account-name">{{ $userAccount->accountRel->name ?? 'Unknown Account' }}</div>
              <div class="account-label">
                @if($userAccount->is_primary)
                Primary Account
                @else
                Assigned Account
                @endif
              </div>
            </div>
            <div class="account-arrow">→</div>
          </label>
          @endforeach
        </div>

        <button type="submit" class="btn-login" style="
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
        ">Continue</button>
      </div>
    </form>
  </div>

  <script>
    // Handle account selection click
    document.querySelectorAll('.account-item').forEach(item => {
      item.addEventListener('click', function() {
        // Remove selected class from all items
        document.querySelectorAll('.account-item').forEach(i => {
          i.style.borderColor = 'rgba(255, 255, 255, 0.2)';
        });
        
        // Add selected class to clicked item
        this.style.borderColor = '#1abc76';
        
        // Check the radio button
        const radio = this.querySelector('input[type="radio"]');
        radio.checked = true;
      });
    });
  </script>
</body>
</html>