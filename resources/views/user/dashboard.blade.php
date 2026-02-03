<style>
.button-80 {
  backface-visibility: hidden;
  border-radius: 3rem;
  border: none;
  outline: none;
  border-width: .125rem;
  box-sizing: border-box;
  color: #212121;
  cursor: pointer;
  display: inline-block;
  font-family: Circular,Helvetica,sans-serif;
  font-size: 1.125rem;
  font-weight: 700;
  letter-spacing: -.01em;
  line-height: 1.3;
  position: relative;
  text-align: center;
  text-decoration: none;
  transform: translateZ(0) scale(1);
  transition: transform .2s;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
}

.button-80:not(:disabled):hover {
  transform: scale(1.2);
}

.button-80:not(:disabled):hover:active {
  transform: scale(1.05) translateY(.125rem);
}

.button-80:focus:before {
  content: "";
  left: calc(-1*.375rem);
  pointer-events: none;
  position: absolute;
  top: calc(-1*.375rem);
  transition: border-radius;
  user-select: none;
}
.button-80:not(:disabled):active {
  transform: translateY(.125rem);
}

.metric-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 15px;
  padding: 20px;
  color: white;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
}

.metric-card:hover {
  transform: translateY(-5px);
}

.metric-card h3 {
  font-size: 2.5rem;
  margin-bottom: 5px;
  font-weight: bold;
}

.metric-card p {
  font-size: 1.1rem;
  margin: 0;
  opacity: 0.9;
}

.chart-container {
  background: white;
  border-radius: 15px;
  padding: 20px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  margin-bottom: 20px;
}

.chart-container h4 {
  color: #333;
  margin-bottom: 15px;
  text-align: center;
}
</style>

<div class="container-fluid">
  <!-- Key Metrics Section -->
  <div class="row mt-4 mb-4">
    <div class="col-md-3 mb-3">
      <div class="metric-card">
        <h3>{{ number_format($Msale, 0 ?? '0') }}</h3>
        <p>Monthly Sales</p>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="metric-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
        <h3>{{ number_format($MrevenueAmount, 0) }}</h3>
        <p>Monthly Expenses</p>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="metric-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
        <h3>{{ $totalOrders }}</h3>
        <p>Total Orders</p>
      </div>
    </div>
    <div class="col-md-3 mb-3">
      <div class="metric-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
        <h3>{{ $TProducts }}</h3>
        <p>Total Products</p>
      </div>
    </div>
  </div>
<h1>
  {{ session('permission') }}
</h1>
  <!-- Charts Section -->
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="chart-container">
        <h4>Monthly Sales</h4>
        <canvas id="salesChart" width="400" height="300"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="chart-container">
        <h4>Monthly Expenses</h4>
        <canvas id="expensesChart" width="400" height="300"></canvas>
      </div>
    </div>
  </div>

  <!-- Quick Actions Section -->
  <div class="row">
    <div class="col-12">
      <div class="chart-container">
        <h4>Quick Actions</h4>
        <div class="btn btn-group border-0 mt-3 d-flex justify-content-center flex-wrap" style="gap: 15px;">

          <button class="btn border-0 button-80" onclick="window.location.href='products'" >
            <svg fill="#000000" width="70px" height="70px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M0 10l8 4 8-4v2l-8 4-8-4v-2zm0-4l8 4 8-4v2l-8 4-8-4V6zm8-6l8 4-8 4-8-4 8-4z" fill-rule="evenodd"></path> </g></svg>
            <br>
             Products
          </button>
          <button class="btn border-0 button-80" onclick="window.location.href='newOrder'" >
            <svg width="72px" height="72px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M7 5C5.34315 5 4 6.34315 4 8V16C4 17.6569 5.34315 19 7 19H17C18.6569 19 20 17.6569 20 16V12.5C20 11.9477 20.4477 11.5 21 11.5C21.5523 11.5 22 11.9477 22 12.5V16C22 18.7614 19.7614 21 17 21H7C4.23858 21 2 18.7614 2 16V8C2 5.23858 4.23858 3 7 3H10.5C11.0523 3 11.5 3.44772 11.5 4C11.5 4.55228 11.0523 5 10.5 5H7Z" fill="#000000"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M18.8431 3.58579C18.0621 2.80474 16.7957 2.80474 16.0147 3.58579L11.6806 7.91992L11.0148 11.9455C10.8917 12.6897 11.537 13.3342 12.281 13.21L16.3011 12.5394L20.6347 8.20582C21.4158 7.42477 21.4158 6.15844 20.6347 5.37739L18.8431 3.58579ZM13.1933 11.0302L13.5489 8.87995L17.4289 5L19.2205 6.7916L15.34 10.6721L13.1933 11.0302Z" fill="#000000"></path> </g></svg>
            <br>
              New Order
          </button>

          <button class="btn button-80" onclick="window.location.href='ordersList'" >
           <svg width="71px" height="71px" viewBox="0 -0.5 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M18.194 7.55504H8.76001L9.41201 13.944L16.7 13.214C17.1551 13.2156 17.5783 12.9809 17.818 12.594L19.312 9.49404C19.5529 9.09564 19.5581 8.59777 19.3255 8.19445C19.093 7.79112 18.6595 7.54617 18.194 7.55504Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M10.167 19.063C10.1648 19.5777 9.74612 19.9934 9.23136 19.992C8.7166 19.9905 8.30029 19.5724 8.30103 19.0576C8.30176 18.5429 8.71926 18.126 9.23402 18.126C9.48199 18.1265 9.7196 18.2255 9.89458 18.4012C10.0695 18.577 10.1675 18.815 10.167 19.063V19.063Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M15.767 19.063C15.7648 19.5777 15.3461 19.9934 14.8313 19.992C14.3166 19.9905 13.9003 19.5724 13.901 19.0576C13.9017 18.5429 14.3192 18.126 14.834 18.126C15.082 18.1265 15.3196 18.2255 15.4946 18.4012C15.6695 18.577 15.7675 18.815 15.767 19.063V19.063Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M8.03326 7.74034C8.13561 8.14171 8.54395 8.38411 8.94532 8.28176C9.34669 8.17941 9.58909 7.77106 9.48674 7.36969L8.03326 7.74034ZM8.136 5.10801L8.86281 4.92267L8.86017 4.91288L8.136 5.10801ZM7.993 5.00001V5.75009L8.00342 5.74994L7.993 5.00001ZM5.5 4.25001C5.08579 4.25001 4.75 4.5858 4.75 5.00001C4.75 5.41423 5.08579 5.75001 5.5 5.75001V4.25001ZM9.44322 14.6934C9.85707 14.6761 10.1786 14.3267 10.1614 13.9128C10.1441 13.4989 9.79464 13.1774 9.38078 13.1947L9.44322 14.6934ZM9.412 16.25L9.38078 16.9994C9.39118 16.9998 9.40159 17 9.412 17L9.412 16.25ZM16.054 17C16.4682 17 16.804 16.6642 16.804 16.25C16.804 15.8358 16.4682 15.5 16.054 15.5V17ZM9.48674 7.36969L8.86274 4.92269L7.40926 5.29334L8.03326 7.74034L9.48674 7.36969ZM8.86017 4.91288C8.75358 4.51729 8.39224 4.2444 7.98258 4.25009L8.00342 5.74994C7.72726 5.75378 7.48369 5.56982 7.41183 5.30315L8.86017 4.91288ZM7.993 4.25001H5.5V5.75001H7.993V4.25001ZM9.38078 13.1947C8.36094 13.2371 7.55603 14.0763 7.55603 15.097H9.05603C9.05603 14.8804 9.22682 14.7024 9.44322 14.6934L9.38078 13.1947ZM7.55603 15.097C7.55603 16.1177 8.36094 16.9569 9.38078 16.9994L9.44322 15.5007C9.22682 15.4916 9.05603 15.3136 9.05603 15.097H7.55603ZM9.412 17H16.054V15.5H9.412V17Z" fill="#000000"></path> </g></svg>
           <br>
              Orders
          </button>
          <button class="btn button-80" onclick="window.location.href='sales'" >
            <svg fill="#000000" width="71px" height="71px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M960 95.888l-256.224.001V32.113c0-17.68-14.32-32-32-32s-32 14.32-32 32v63.76h-256v-63.76c0-17.68-14.32-32-32-32s-32 14.32-32 32v63.76H64c-35.344 0-64 28.656-64 64v800c0 35.343 28.656 64 64 64h896c35.344 0 64-28.657 64-64v-800c0-35.329-28.656-63.985-64-63.985zm0 863.985H64v-800h255.776v32.24c0 17.679 14.32 32 32 32s32-14.321 32-32v-32.224h256v32.24c0 17.68 14.32 32 32 32s32-14.32 32-32v-32.24H960v799.984zM736 511.888h64c17.664 0 32-14.336 32-32v-64c0-17.664-14.336-32-32-32h-64c-17.664 0-32 14.336-32 32v64c0 17.664 14.336 32 32 32zm0 255.984h64c17.664 0 32-14.32 32-32v-64c0-17.664-14.336-32-32-32h-64c-17.664 0-32 14.336-32 32v64c0 17.696 14.336 32 32 32zm-192-128h-64c-17.664 0-32 14.336-32 32v64c0 17.68 14.336 32 32 32h64c17.664 0 32-14.32 32-32v-64c0-17.648-14.336-32-32-32zm0-255.984h-64c-17.664 0-32 14.336-32 32v64c0 17.664 14.336 32 32 32h64c17.664 0 32-14.336 32-32v-64c0-17.68-14.336-32-32-32zm-256 0h-64c-17.664 0-32 14.336-32 32v64c0 17.664 14.336 32 32 32h64c17.664 0 32-14.336 32-32v-64c0-17.68-14.336-32-32-32zm0 255.984h-64c-17.664 0-32 14.336-32 32v64c0 17.68 14.336 32 32 32h64c17.664 0 32-14.32 32-32v-64c0-17.648-14.336-32-32-32z"></path></g></svg>
            <br>
             Report
          </button>
          <button class="btn button-80" onclick="window.location.href='expenses'"  >
            <svg width="69px" height="69px" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.8702 16.97V18.0701C12.8702 18.2478 12.7995 18.4181 12.6739 18.5437C12.5482 18.6694 12.3778 18.74 12.2001 18.74C12.0224 18.74 11.852 18.6694 11.7264 18.5437C11.6007 18.4181 11.5302 18.2478 11.5302 18.0701V16.9399C11.0867 16.8668 10.6625 16.7051 10.2828 16.4646C9.90316 16.2241 9.57575 15.9097 9.32013 15.54C9.21763 15.428 9.16061 15.2817 9.16016 15.1299C9.16006 15.0433 9.17753 14.9576 9.21155 14.8779C9.24557 14.7983 9.29545 14.7263 9.35809 14.6665C9.42074 14.6067 9.49484 14.5601 9.57599 14.5298C9.65713 14.4994 9.7436 14.4859 9.83014 14.49C9.91602 14.4895 10.0009 14.5081 10.0787 14.5444C10.1566 14.5807 10.2254 14.6338 10.2802 14.7C10.6 15.1178 11.0342 15.4338 11.5302 15.6099V13.0701C10.2002 12.5401 9.53015 11.77 9.53015 10.76C9.55019 10.2193 9.7627 9.70353 10.1294 9.30566C10.4961 8.9078 10.9929 8.65407 11.5302 8.59009V7.47998C11.5302 7.30229 11.6007 7.13175 11.7264 7.0061C11.852 6.88045 12.0224 6.81006 12.2001 6.81006C12.3778 6.81006 12.5482 6.88045 12.6739 7.0061C12.7995 7.13175 12.8702 7.30229 12.8702 7.47998V8.58008C13.2439 8.63767 13.6021 8.76992 13.9234 8.96924C14.2447 9.16856 14.5226 9.43077 14.7402 9.73999C14.8284 9.85568 14.8805 9.99471 14.8901 10.1399C14.8928 10.2256 14.8783 10.3111 14.8473 10.3911C14.8163 10.4711 14.7696 10.5439 14.7099 10.6055C14.6502 10.667 14.5787 10.7161 14.4998 10.7495C14.4208 10.7829 14.3359 10.8001 14.2501 10.8C14.1607 10.7989 14.0725 10.7787 13.9915 10.7407C13.9104 10.7028 13.8384 10.648 13.7802 10.5801C13.5417 10.2822 13.2274 10.054 12.8702 9.91992V12.1699L13.1202 12.27C14.3902 12.76 15.1802 13.4799 15.1802 14.6299C15.163 15.2399 14.9149 15.8208 14.4862 16.2551C14.0575 16.6894 13.4799 16.9449 12.8702 16.97ZM11.5302 11.5901V9.96997C11.3688 10.0285 11.2298 10.1363 11.1329 10.2781C11.0361 10.4198 10.9862 10.5884 10.9902 10.76C10.9984 10.93 11.053 11.0945 11.1483 11.2356C11.2435 11.3767 11.3756 11.4889 11.5302 11.5601V11.5901ZM13.7302 14.6599C13.7302 14.1699 13.3902 13.8799 12.8702 13.6599V15.6599C13.1157 15.6254 13.3396 15.5009 13.4985 15.3105C13.6574 15.1202 13.74 14.8776 13.7302 14.6299V14.6599Z" fill="#000000"></path> <path d="M12.58 3.96997H6C4.93913 3.96997 3.92178 4.39146 3.17163 5.1416C2.42149 5.89175 2 6.9091 2 7.96997V17.97C2 19.0308 2.42149 20.0482 3.17163 20.7983C3.92178 21.5485 4.93913 21.97 6 21.97H18C19.0609 21.97 20.0783 21.5485 20.8284 20.7983C21.5786 20.0482 22 19.0308 22 17.97V11.8999" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M16.3398 8.57992L21.9998 2.91992" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4805 2.91992H22.0005V7.44992" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
            <br>
             Expenses
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js"></script>
@php
$getName = DB::table('system')->first();

// Fetch total metrics
$totalSales = DB::table('sales')->sum('totalPrice');
$totalExpenses = DB::table('expenses')->sum('amount');
$totalOrders = DB::table('orders')->count();
$totalProducts = DB::table('products')->count();

// Monthly sales data
$salesMonths = DB::table('sales')
  ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(totalPrice) as totalPrice'))
  ->groupBy('month')
  ->get()
  ->map(function ($month) {
      return [
          'month' => $month->month,
          'totalPrice' => $month->totalPrice,
      ];
  })
  ->toArray();

// Monthly expenses data
$expensesMonths = DB::table('expenses')
  ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount) as totalAmount'))
  ->groupBy('month')
  ->get()
  ->map(function ($month) {
      return [
          'month' => $month->month,
          'totalAmount' => $month->totalAmount,
      ];
  })
  ->toArray();

// Prepare data for charts
$salesData = array_fill(0, 12, 0);
$expensesData = array_fill(0, 12, 0);

foreach ($salesMonths as $month) {
  $salesData[$month['month'] - 1] = $month['totalPrice'];
}

foreach ($expensesMonths as $month) {
  $expensesData[$month['month'] - 1] = $month['totalAmount'];
}
@endphp

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Sales Chart
  const salesCtx = document.getElementById('salesChart').getContext('2d');
  new Chart(salesCtx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      datasets: [{
        label: 'Monthly Sales',
        data: @json($salesData),
        borderColor: '#667eea',
        backgroundColor: 'rgba(102, 126, 234, 0.1)',
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return 'KES ' + value.toLocaleString();
            }
          }
        }
      }
    }
  });

  // Expenses Chart
  const expensesCtx = document.getElementById('expensesChart').getContext('2d');
  new Chart(expensesCtx, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      datasets: [{
        label: 'Monthly Expenses',
        data: @json($expensesData),
        backgroundColor: '#f5576c',
        borderColor: '#f5576c',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return 'KES ' + value.toLocaleString();
            }
          }
        }
      }
    }
  });
});
</script>
