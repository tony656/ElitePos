<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title>{{config("app.name")}}</title>
@include("links")
    <style>
      body {
        widows: 50% !important;
      }
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>


    <!-- Custom styles for this template -->
    <link href="{{asset("css/dashboard.css")}}" rel="stylesheet">
  </head>
  <body>
<div class="container-fluid">
  <div class="row">

      @include("user/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10  pt-3">
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
     
       @include('user/dashboard')
   
   <!-- Balance Check Button -->
   <div style="position: fixed; bottom: 100px; right: 30px; z-index: 1000;">
       <button id="balanceCheckBtn" class="btn-balance-check" title="Check Today's Balance">
           <i class="fas fa-balance-scale"></i>
           <span class="btn-text">Check Balance</span>
           <div class="btn-pulse"></div>
       </button>
   </div>

   <!-- Balance Result Modal -->
   <div id="balanceModal" class="balance-modal-overlay" style="display: none;">
       <div class="balance-modal">
           <div class="balance-modal-header">
               <h3 id="modalTitle">Today's Balance Check</h3>
               <button class="balance-modal-close" onclick="closeBalanceModal()">
                   <i class="fas fa-times"></i>
               </button>
           </div>
           <div class="balance-modal-body">
               <div id="balanceLoading" style="display: none; text-align: center; padding: 2rem;">
                   <div class="balance-spinner"></div>
                   <p>Checking balance...</p>
               </div>
               <div id="balanceContent">
                   <!-- Results will be inserted here -->
               </div>
           </div>
       </div>
   </div>

   <style>
       /* Balance Check Button Styles */
       .btn-balance-check {
           position: relative;
           padding: 14px 24px;
           background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
           color: white;
           border: none;
           border-radius: 50px;
           cursor: pointer;
           font-size: 14px;
           font-weight: 600;
           display: flex;
           align-items: center;
           gap: 10px;
           box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
           transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
           overflow: hidden;
           z-index: 1;
       }

       .btn-balance-check:hover {
           transform: translateY(-3px) scale(1.05);
           box-shadow: 0 8px 25px rgba(245, 158, 11, 0.5);
           background: linear-gradient(135deg, #D97706 0%, #B45309 100%);
       }

       .btn-balance-check:active {
           transform: translateY(-1px) scale(1.02);
       }

       .btn-balance-check i {
           font-size: 18px;
           transition: transform 0.3s ease;
       }

       .btn-balance-check:hover i {
           transform: rotate(15deg) scale(1.1);
       }

       .btn-text {
           position: relative;
           z-index: 2;
       }

       /* Pulse animation */
       .btn-pulse {
           position: absolute;
           top: 50%;
           left: 50%;
           width: 100%;
           height: 100%;
           background: rgba(255, 255, 255, 0.3);
           border-radius: 50%;
           transform: translate(-50%, -50%) scale(0);
           z-index: 1;
       }

       .btn-balance-check:hover .btn-pulse {
           animation: pulse-animation 1.5s infinite;
       }

       @keyframes pulse-animation {
           0% {
               transform: translate(-50%, -50%) scale(0.8);
               opacity: 1;
           }
           100% {
               transform: translate(-50%, -50%) scale(2.5);
               opacity: 0;
           }
       }

       /* Modal Styles */
       .balance-modal-overlay {
           position: fixed;
           top: 0;
           left: 0;
           right: 0;
           bottom: 0;
           background: rgba(0, 0, 0, 0.6);
           backdrop-filter: blur(4px);
           display: flex;
           align-items: center;
           justify-content: center;
           z-index: 9999;
           animation: fadeIn 0.3s ease;
       }

       @keyframes fadeIn {
           from { opacity: 0; }
           to { opacity: 1; }
       }

       .balance-modal {
           background: white;
           border-radius: 16px;
           width: 90%;
           max-width: 600px;
           max-height: 80vh;
           overflow-y: auto;
           box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
           animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
       }

       @keyframes slideUp {
           from {
               opacity: 0;
               transform: translateY(30px) scale(0.95);
           }
           to {
               opacity: 1;
               transform: translateY(0) scale(1);
           }
       }

       .balance-modal-header {
           display: flex;
           align-items: center;
           justify-content: space-between;
           padding: 1.25rem 1.5rem;
           border-bottom: 1px solid #E4E2DA;
           background: linear-gradient(135deg, #F7F6F2 0%, #FFFFFF 100%);
           border-radius: 16px 16px 0 0;
       }

       .balance-modal-header h3 {
           margin: 0;
           font-size: 18px;
           font-weight: 600;
           color: #0B1E3D;
       }

       .balance-modal-close {
           width: 36px;
           height: 36px;
           border: none;
           background: rgba(245, 158, 11, 0.1);
           color: #F59E0B;
           border-radius: 50%;
           cursor: pointer;
           display: flex;
           align-items: center;
           justify-content: center;
           transition: all 0.2s;
       }

       .balance-modal-close:hover {
           background: #F59E0B;
           color: white;
           transform: rotate(90deg);
       }

       .balance-modal-body {
           padding: 1.5rem;
       }

       /* Summary Card */
       .balance-summary {
           background: linear-gradient(135deg, #F7F6F2 0%, #FFFFFF 100%);
           border: 1px solid #E4E2DA;
           border-radius: 12px;
           padding: 1.25rem;
           margin-bottom: 1.25rem;
       }

       .balance-status {
           display: flex;
           align-items: center;
           gap: 12px;
           margin-bottom: 1rem;
       }

       .status-icon {
           width: 48px;
           height: 48px;
           border-radius: 50%;
           display: flex;
           align-items: center;
           justify-content: center;
           font-size: 24px;
       }

       .status-icon.good {
           background: #E6F4ED;
           color: #1A6B45;
       }

       .status-icon.bad {
           background: #FDECEA;
           color: #B63A2F;
       }

       .status-text h4 {
           margin: 0;
           font-size: 16px;
           font-weight: 600;
           color: #0B1E3D;
       }

       .status-text p {
           margin: 4px 0 0;
           font-size: 13px;
           color: #7A7870;
       }

       .balance-stats {
           display: grid;
           grid-template-columns: repeat(2, 1fr);
           gap: 12px;
       }

       .stat-item {
           background: white;
           padding: 12px;
           border-radius: 8px;
           border: 1px solid #E4E2DA;
       }

       .stat-label {
           font-size: 11px;
           color: #7A7870;
           text-transform: uppercase;
           letter-spacing: 0.5px;
           margin-bottom: 4px;
       }

       .stat-value {
           font-size: 16px;
           font-weight: 600;
           color: #0B1E3D;
           font-family: 'DM Mono', monospace;
       }

       /* Shop Details */
       .balance-shops {
           margin-top: 1.25rem;
       }

       .balance-shops h4 {
           font-size: 14px;
           font-weight: 600;
           color: #0B1E3D;
           margin-bottom: 12px;
       }

       .shop-item {
           background: #F7F6F2;
           border: 1px solid #E4E2DA;
           border-radius: 10px;
           padding: 1rem;
           margin-bottom: 10px;
           transition: all 0.2s;
       }

       .shop-item.unbalanced {
           background: #FFFBF0;
           border-color: #F59E0B;
       }

       .shop-item.problem {
           background: #FDECEA;
           border-color: #B63A2F;
       }

       .shop-header {
           display: flex;
           align-items: center;
           justify-content: space-between;
           margin-bottom: 8px;
       }

       .shop-name {
           font-weight: 600;
           color: #0B1E3D;
           font-size: 14px;
       }

       .shop-status {
           padding: 4px 10px;
           border-radius: 20px;
           font-size: 11px;
           font-weight: 600;
       }

       .shop-status.balanced {
           background: #E6F4ED;
           color: #1A6B45;
       }

       .shop-status.unbalanced {
           background: #FEF3D7;
           color: #D97706;
       }

       .shop-status.problem {
           background: #FDECEA;
           color: #B63A2F;
       }

       .shop-details {
           display: grid;
           grid-template-columns: repeat(2, 1fr);
           gap: 8px;
           font-size: 12px;
       }

       .shop-detail-item {
           display: flex;
           justify-content: space-between;
       }

       .shop-detail-label {
           color: #7A7870;
       }

       .shop-detail-value {
           font-family: 'DM Mono', monospace;
           font-weight: 500;
           color: #0B1E3D;
       }

       .shop-issues {
           margin-top: 10px;
           padding: 10px;
           background: #FDECEA;
           border-left: 3px solid #B63A2F;
           border-radius: 6px;
           font-size: 12px;
           color: #B63A2F;
       }

       .shop-issues ul {
           margin: 0;
           padding-left: 16px;
       }

       .shop-issues li {
           margin-bottom: 4px;
       }

       /* Spinner */
       .balance-spinner {
           width: 40px;
           height: 40px;
           border: 3px solid rgba(245, 158, 11, 0.2);
           border-top-color: #F59E0B;
           border-radius: 50%;
           animation: spin 1s linear infinite;
           margin: 0 auto 1rem;
       }

       @keyframes spin {
           to { transform: rotate(360deg); }
       }

       /* Responsive */
       @media (max-width: 768px) {
           .btn-balance-check {
               padding: 12px 18px;
               font-size: 13px;
           }
           
           .btn-text {
               display: none;
           }
           
           .balance-modal {
               width: 95%;
               margin: 1rem;
           }
           
           .balance-stats {
               grid-template-columns: 1fr;
           }
       }
   </style>

   <script>
       // Balance Check Functionality
       const balanceBtn = document.getElementById('balanceCheckBtn');
       const balanceModal = document.getElementById('balanceModal');
       const balanceContent = document.getElementById('balanceContent');
       const balanceLoading = document.getElementById('balanceLoading');
       const modalTitle = document.getElementById('modalTitle');

       // Hover animation - subtle bounce
       balanceBtn.addEventListener('mouseenter', function() {
           this.style.animation = 'bounce 0.5s ease';
           setTimeout(() => {
               this.style.animation = '';
           }, 500);
       });

       // Click to check balance
       balanceBtn.addEventListener('click', function() {
           openBalanceModal();
           checkTodayBalance();
       });

       function openBalanceModal() {
           balanceModal.style.display = 'flex';
           balanceContent.style.display = 'none';
           balanceLoading.style.display = 'block';
           modalTitle.textContent = "Checking Today's Balance...";
       }

       function closeBalanceModal() {
           balanceModal.style.display = 'none';
       }

       async function checkTodayBalance() {
           try {
               const response = await fetch('{{ route("user.check.today.balance") }}', {
                   method: 'GET',
                   headers: {
                       'X-Requested-With': 'XMLHttpRequest',
                       'Accept': 'application/json',
                   },
                   credentials: 'same-origin'
               });

               const data = await response.json();
               
               balanceLoading.style.display = 'none';
               balanceContent.style.display = 'block';
               modalTitle.textContent = "Today's Balance Report - " + new Date().toLocaleDateString();

               if (!data.success) {
                   balanceContent.innerHTML = `
                       <div class="balance-summary">
                           <div class="balance-status">
                               <div class="status-icon bad">
                                   <i class="fas fa-exclamation-triangle"></i>
                               </div>
                               <div class="status-text">
                                   <h4>Error</h4>
                                   <p>${data.message || 'Unable to check balance'}</p>
                               </div>
                           </div>
                       </div>
                   `;
                   return;
               }

               // Build summary HTML
               let html = `
                   <div class="balance-summary">
                       <div class="balance-status">
                           <div class="status-icon ${data.overall_balanced ? 'good' : 'bad'}">
                               <i class="fas fa-${data.overall_balanced ? 'check-circle' : 'exclamation-circle'}"></i>
                           </div>
                           <div class="status-text">
                               <h4>${data.message}</h4>
                               <p>${data.date}</p>
                           </div>
                       </div>
                       <div class="balance-stats">
                           <div class="stat-item">
                               <div class="stat-label">Cash Sales</div>
                               <div class="stat-value">Tsh ${formatNumber(data.summary.cash_sales)}</div>
                           </div>
                           <div class="stat-item">
                               <div class="stat-label">Credit Sales</div>
                               <div class="stat-value">Tsh ${formatNumber(data.summary.credit_sales)}</div>
                           </div>
                           <div class="stat-item">
                               <div class="stat-label">Total Sales</div>
                               <div class="stat-value">Tsh ${formatNumber(data.summary.total_sales)}</div>
                           </div>
                           <div class="stat-item">
                               <div class="stat-label">Cash Submitted</div>
                               <div class="stat-value">Tsh ${formatNumber(data.summary.cash_submitted)}</div>
                           </div>
                       </div>
                   </div>
               `;

               // Add shop details if there are shops
               if (data.shops && data.shops.length > 0) {
                   html += `
                       <div class="balance-shops">
                           <h4>Shop Details</h4>
                   `;
                   
                   data.shops.forEach(shop => {
                       const statusClass = shop.is_balanced ? 'balanced' : (shop.cash_balanced ? 'unbalanced' : 'problem');
                       const statusText = shop.is_balanced ? 'Balanced' : (shop.cash_balanced ? 'Sales OK' : 'Issue');
                       
                       html += `
                           <div class="shop-item ${statusClass}">
                               <div class="shop-header">
                                   <span class="shop-name">${shop.shop_name}</span>
                                   <span class="shop-status ${statusClass}">${statusText}</span>
                               </div>
                               <div class="shop-details">
                                   <div class="shop-detail-item">
                                       <span class="shop-detail-label">Cash Sales:</span>
                                       <span class="shop-detail-value">Tsh ${formatNumber(shop.cash_sales)}</span>
                                   </div>
                                   <div class="shop-detail-item">
                                       <span class="shop-detail-label">Credit Sales:</span>
                                       <span class="shop-detail-value">Tsh ${formatNumber(shop.credit_sales)}</span>
                                   </div>
                                   <div class="shop-detail-item">
                                       <span class="shop-detail-label">Total Sales:</span>
                                       <span class="shop-detail-value">Tsh ${formatNumber(shop.total_sales)}</span>
                                   </div>
                                   <div class="shop-detail-item">
                                       <span class="shop-detail-label">Cash Amount:</span>
                                       <span class="shop-detail-value">Tsh ${formatNumber(shop.cash_amount)}</span>
                                   </div>
                                   <div class="shop-detail-item">
                                       <span class="shop-detail-label">Submitted:</span>
                                       <span class="shop-detail-value">Tsh ${formatNumber(shop.cash_submitted)}</span>
                                   </div>
                                   <div class="shop-detail-item">
                                       <span class="shop-detail-label">Sales Balanced:</span>
                                       <span class="shop-detail-value">${shop.sales_balanced ? '✓ Yes' : '✗ No'}</span>
                                   </div>
                               </div>
                               ${shop.issues && shop.issues.length > 0 ? `
                                   <div class="shop-issues">
                                       <strong>Issues found:</strong>
                                       <ul>
                                           ${shop.issues.map(issue => `<li>${issue}</li>`).join('')}
                                       </ul>
                                   </div>
                               ` : ''}
                           </div>
                       `;
                   });
                   
                   html += `</div>`;
               }

               balanceContent.innerHTML = html;

           } catch (error) {
               console.error('Balance check error:', error);
               balanceLoading.style.display = 'none';
               balanceContent.style.display = 'block';
               modalTitle.textContent = "Error";
               balanceContent.innerHTML = `
                   <div class="balance-summary">
                       <div class="balance-status">
                           <div class="status-icon bad">
                               <i class="fas fa-times-circle"></i>
                           </div>
                           <div class="status-text">
                               <h4>Connection Error</h4>
                               <p>Could not connect to server. Please try again.</p>
                           </div>
                       </div>
                   </div>
               `;
           }
       }

       function formatNumber(num) {
           return new Intl.NumberFormat('en-KE', {
               minimumFractionDigits: 2,
               maximumFractionDigits: 2
           }).format(num);
       }

       // Close modal on overlay click
       balanceModal.addEventListener('click', function(e) {
           if (e.target === balanceModal) {
               closeBalanceModal();
           }
       });

       // Close on Escape key
       document.addEventListener('keydown', function(e) {
           if (e.key === 'Escape' && balanceModal.style.display === 'flex') {
               closeBalanceModal();
           }
       });
   </script>

      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script><script src="dashboard.js"></script>
  </body>
</html>
