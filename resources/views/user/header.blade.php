<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ELITE POS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        background: #f8fafc;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }

      /* Top Header */
      .header-top {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 12px 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      }

      .brand-logo {
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, #00d4ff 0%, #0099ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-decoration: none !important;
        transition: transform 0.3s ease;
      }

      .brand-logo:hover {
        transform: scale(1.05);
      }

      .search-container {
        flex: 1;
        margin: 0 40px;
      }

      .search-box {
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 10px 16px;
        background: rgba(255, 255, 255, 0.08);
        color: white;
        font-size: 14px;
        transition: all 0.3s ease;
      }

      .search-box::placeholder {
        color: rgba(255, 255, 255, 0.5);
      }

      .search-box:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(0, 212, 255, 0.4);
        box-shadow: 0 0 12px rgba(0, 212, 255, 0.2);
      }

      .user-section {
        display: flex;
        align-items: center;
        gap: 24px;
      }

      .user-info {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 8px;
        transition: background 0.3s ease;
      }

      .user-info:hover {
        background: rgba(255, 255, 255, 0.08);
      }

      .user-info i {
        font-size: 18px;
      }

      .user-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
      }

      .username {
        font-size: 14px;
        font-weight: 600;
        color: white;
      }

      .user-status {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
      }

      .signout-btn {
        padding: 8px 16px;
        border: 1px solid rgba(255, 59, 48, 0.4);
        border-radius: 6px;
        background: rgba(255, 59, 48, 0.1);
        color: #ff3b30;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
      }

      .signout-btn:hover {
        background: rgba(255, 59, 48, 0.2);
        border-color: rgba(255, 59, 48, 0.6);
      }

      /* Navigation Bar */
      .navbar-menu {
        background: white;
        border-bottom: 1px solid #e2e8f0;
        padding: 0;
        overflow-x: auto;
      }

      .nav-list {
        list-style: none;
        display: flex;
        gap: 0;
        padding: 0;
        margin: 0;
        min-width: min-content;
      }

      .nav-item-menu {
        border-right: 1px solid #e2e8f0;
      }

      .nav-item-menu:last-child {
        border-right: none;
      }

      .nav-link-menu {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 14px 20px;
        color: #475569;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s ease;
        white-space: nowrap;
        border-bottom: 3px solid transparent;
      }

      .nav-link-menu:hover {
        color: #0f172a;
        background: #f1f5f9;
        border-bottom-color: #00d4ff;
      }

      .nav-link-menu i {
        font-size: 16px;
      }

      /* Search Results Dropdown */
      .header-results {
        position: fixed;
        top: 100px;
        left: 24px;
        right: 24px;
        width: calc(100% - 48px);
        max-width: 600px;
        max-height: 400px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 10px 32px rgba(0, 0, 0, 0.1);
        z-index: 999;
        overflow-y: auto;
        display: none;
      }

      .header-results.active {
        display: block;
      }

      /* Responsive */
      @media (max-width: 768px) {
        .header-top {
          padding: 12px 16px;
        }

        .search-container {
          margin: 0 16px;
        }

        .brand-logo {
          font-size: 16px;
        }

        .user-section {
          gap: 12px;
        }

        .user-details {
          display: none;
        }

        .nav-list {
          flex-wrap: wrap;
        }

        .nav-link-menu {
          padding: 12px 16px;
          font-size: 13px;
        }
      }

      /* Scrollbar styling */
      .header-results::-webkit-scrollbar {
        width: 6px;
      }

      .header-results::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
      }

      .header-results::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
      }

      .header-results::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
      }
    </style>
</head>
<body>
  <!-- Top Header -->
  <header class="header-top">
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px;">
      <a href="dashboard" class="brand-logo">
        <img src="{{ asset('images/ElitelogoW.png') }}" width="50" alt=""> ELITE POS
      </a>
      
      <div class="search-container">
        <input 
          class="search-box w-100" 
          id="header-name" 
          type="text" 
          placeholder="Search products, customers, orders..."
          autocomplete="off"
        >
      </div>

      <div class="user-section">
        <a href="#" class="user-info">
          <i class="bi bi-person-circle"></i>
          <div class="user-details">
            <span class="username">{{ Auth::user()->name }}</span>
            <span class="user-status">{{ Auth::user()->levelStatus }}</span>
          </div>
        </a>
        <a href="login" class="signout-btn">
          <i class="bi bi-box-arrow-right"></i>
          Sign out
        </a>
      </div>
    </div>
  </header>

  <
  <!-- Search Results Container -->
  <div class="header-results" id="header-results"></div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#header-name').on('input', function() {
        let query = $(this).val();
        let resultsContainer = $('#header-results');
    
        if (query.length > 2) {
          $.ajax({
            url: "{{ url('user/searchProduct') }}", 
            method: 'GET',
            data: { query: query },
            success: function(data) {
              resultsContainer.html(data).addClass('active');
            },
            error: function() {
              resultsContainer.html('<p style="padding: 16px; color: #ef4444;">Error loading results</p>').addClass('active');
            }
          });
        } else {
          resultsContainer.html('').removeClass('active');
        }
      });

      // Close results when clicking outside
      $(document).click(function(e) {
        if (!$(e.target).closest('#header-name, #header-results').length) {
          $('#header-results').removeClass('active').html('');
        }
      });
    });
  </script>
</body>
</html>