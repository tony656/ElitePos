<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Sales Dashboard</title>
    @include("links")
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #30C5FF;
            --danger-color: #f72585;
            --success-color: #4cc9f0;
            --warning-color: #f8961e;
            --light-bg: #f8f9fa;
            --dark-text: #2b2d42;
            --light-text: #8d99ae;
            --border-radius: 12px;
            --box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .dashboard-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1rem 2rem;
            margin-bottom: 1.5rem;
            border-radius: var(--border-radius);
        }
        
        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            border-top: 4px solid var(--primary-color);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .stat-card.sales {
            border-top-color: var(--primary-color);
        }
        
        .stat-card.discount {
            border-top-color: var(--danger-color);
        }
        
        .stat-card.products {
            border-top-color: var(--warning-color);
        }
        
        .stat-card.profit {
            border-top-color: #38b000;
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .stat-icon.discount {
            background-color: rgba(247, 37, 133, 0.1);
            color: var(--danger-color);
        }
        
        .stat-icon.products {
            background-color: rgba(248, 150, 30, 0.1);
            color: var(--warning-color);
        }
        
        .stat-icon.profit {
            background-color: rgba(56, 176, 0, 0.1);
            color: #38b000;
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--light-text);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
           .sales-date {
        background-color: #c6f6d5; /* light green */
        border-radius: 50%;
        color: #22543d;
        font-weight: bold;
    }

    .current-date {
        background-color: #3182ce; /* blue for today */
        color: white;
        border-radius: 50%;
        font-weight: bold;
    }
        .stat-comparison {
            display: flex;
            justify-content: space-between;
            font-size: 0.875rem;
            color: var(--light-text);
            border-top: 1px solid #e9ecef;
            padding-top: 0.75rem;
            margin-top: 0.75rem;
        }
        
        .search-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .search-input {
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.1);
        }
        
        .sales-table {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }
        
        .sales-table thead {
            background-color: var(--primary-color);
            color: white;
        }
        
        .sales-table th {
            font-weight: 500;
            padding: 1rem;
        }
        
        .sales-table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }
        
        .btn-view {
            background-color: var(--primary-color);
            color: white;
            border-radius: 8px;
            padding: 0.375rem 0.75rem;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-view:hover {
            background-color: var(--secondary-color);
        }
        
        .calendar-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .calendar-nav-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.25rem;
            cursor: pointer;
        }
        
        .calendar-table {
            width: 100%;
            text-align: center;
            border-collapse: collapse;
        }
        
        .calendar-table th {
            padding: 0.5rem;
            font-weight: 500;
            color: var(--light-text);
        }
        
        .calendar-table td {
            padding: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .calendar-table td:hover {
            background-color: rgba(67, 97, 238, 0.1);
        }
        
        .current-date {
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            font-weight: bold;
        }
        
        .summary-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 1.5rem;
        }
        
        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 1rem;
            }
            
            .sales-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    

<div class="container-fluid">
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">

        <div class="dashboard-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">
                    <a href="#" onclick="history.back()" class="text-decoration-none text-dark">
                        <i class="bi bi-chevron-left"></i>          
                        Sales Dashboard
                    </a>
                </h4>
            </div>
            <div>
              
        <a href="{{ route('admin.sales.export', ['selectedDate' => request('selectedDate')]) }}" 
   class="btn btn-success mb-3">
   Download Excel Report
</a>

            </div>
        </div>

        <div class="row">
            <!-- Total Sales Card -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card sales">
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.7003 17.1099V18.22C12.7003 18.308 12.6829 18.395 12.6492 18.4763C12.6156 18.5576 12.5662 18.6316 12.504 18.6938C12.4418 18.7561 12.3679 18.8052 12.2867 18.8389C12.2054 18.8725 12.1182 18.8899 12.0302 18.8899C11.9423 18.8899 11.8551 18.8725 11.7738 18.8389C11.6925 18.8052 11.6187 18.7561 11.5565 18.6938C11.4943 18.6316 11.4449 18.5576 11.4113 18.4763C11.3776 18.395 11.3602 18.308 11.3602 18.22V17.0801C10.9165 17.0072 10.4917 16.8468 10.1106 16.6082C9.72943 16.3695 9.39958 16.0573 9.14023 15.6899C9.04577 15.57 8.99311 15.4226 8.99023 15.27C8.99148 15.1842 9.00997 15.0995 9.04459 15.021C9.0792 14.9425 9.12927 14.8718 9.19177 14.813C9.25428 14.7542 9.32794 14.7087 9.40842 14.679C9.4889 14.6492 9.57455 14.6359 9.66025 14.6399C9.74504 14.6401 9.82883 14.6582 9.90631 14.6926C9.98379 14.7271 10.0532 14.7773 10.1102 14.8401C10.4326 15.2576 10.8657 15.5763 11.3602 15.76V13.21C10.0302 12.69 9.36023 11.9099 9.36023 10.8999C9.38027 10.3592 9.5928 9.84343 9.9595 9.44556C10.3262 9.04769 10.8229 8.79397 11.3602 8.72998V7.62988C11.3602 7.5419 11.3776 7.45482 11.4113 7.37354C11.4449 7.29225 11.4943 7.21847 11.5565 7.15625C11.6187 7.09403 11.6925 7.04466 11.7738 7.01099C11.8551 6.97732 11.9423 6.95996 12.0302 6.95996C12.1182 6.95996 12.2054 6.97732 12.2867 7.01099C12.3679 7.04466 12.4418 7.09403 12.504 7.15625C12.5662 7.21847 12.6156 7.29225 12.6492 7.37354C12.6829 7.45482 12.7003 7.5419 12.7003 7.62988V8.71997C13.0724 8.77828 13.4289 8.91103 13.7485 9.11035C14.0681 9.30967 14.3442 9.57137 14.5602 9.87988C14.6555 9.99235 14.7117 10.1329 14.7202 10.28C14.7229 10.3662 14.7084 10.4519 14.6776 10.5325C14.6467 10.613 14.6002 10.6867 14.5406 10.749C14.481 10.8114 14.4096 10.8613 14.3306 10.8958C14.2516 10.9303 14.1665 10.9487 14.0802 10.95C13.99 10.9475 13.9013 10.9257 13.8202 10.886C13.7391 10.8463 13.6675 10.7897 13.6102 10.72C13.3718 10.4221 13.0575 10.1942 12.7003 10.0601V12.3101L12.9503 12.4099C14.2203 12.9099 15.0103 13.63 15.0103 14.77C14.9954 15.3808 14.7481 15.9629 14.3189 16.3977C13.8897 16.8325 13.3108 17.0871 12.7003 17.1099ZM11.3602 11.73V10.0999C11.1988 10.1584 11.0599 10.2662 10.963 10.408C10.8662 10.5497 10.8162 10.7183 10.8203 10.8899C10.8173 11.0676 10.8669 11.2424 10.963 11.3918C11.0591 11.5413 11.1973 11.6589 11.3602 11.73ZM13.5502 14.8C13.5502 14.32 13.2203 14.03 12.7003 13.8V15.8C12.9387 15.7639 13.1561 15.6427 13.3123 15.459C13.4685 15.2752 13.553 15.0412 13.5502 14.8Z" fill="currentColor"></path>
                            <path d="M18 3.96997H6C4.93913 3.96997 3.92172 4.39146 3.17157 5.1416C2.42142 5.89175 2 6.9091 2 7.96997V17.97C2 19.0308 2.42142 20.0482 3.17157 20.7983C3.92172 21.5485 4.93913 21.97 6 21.97H18C19.0609 21.97 20.0783 21.5485 20.8284 20.7983C21.5786 20.0482 22 19.0308 22 17.97V7.96997C22 6.9091 21.5786 5.89175 20.8284 5.1416C20.0783 4.39146 19.0609 3.96997 18 3.96997Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <div class="stat-value">Tsh {{ number_format($Tsale) }}</div>
                    <div class="stat-label">Total Sales</div>
                    <div class="stat-comparison">
                        <span>This Month</span>
                        <span>Tsh {{ number_format($Msale) }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Discount Card -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card discount">
                    <div class="stat-icon discount">
                        <svg width="24" height="24" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 5.5H6M9 9.5H10M10 5L5 10M6.80145 0.789347L5.67243 1.91837C5.48717 2.10363 5.23589 2.20772 4.97389 2.20772H3.19561C2.65001 2.20772 2.20772 2.65001 2.20772 3.19561V4.97389C2.20772 5.23589 2.10363 5.48717 1.91837 5.67243L0.789347 6.80145C0.403551 7.18725 0.403551 7.81275 0.789347 8.19855L1.91837 9.32757C2.10363 9.51283 2.20772 9.76411 2.20772 10.0261V11.8044C2.20772 12.35 2.65001 12.7923 3.19561 12.7923H4.97389C5.23589 12.7923 5.48717 12.8964 5.67243 13.0816L6.80145 14.2107C7.18725 14.5964 7.81275 14.5964 8.19855 14.2107L9.32757 13.0816C9.51283 12.8964 9.76411 12.7923 10.0261 12.7923H11.8044C12.35 12.7923 12.7923 12.35 12.7923 11.8044V10.0261C12.7923 9.76411 12.8964 9.51283 13.0816 9.32757L14.2107 8.19855C14.5964 7.81275 14.5964 7.18725 14.2107 6.80145L13.0816 5.67243C12.8964 5.48717 12.7923 5.23589 12.7923 4.97389V3.19561C12.7923 2.65001 12.35 2.20772 11.8044 2.20772H10.0261C9.76411 2.20772 9.51283 2.10363 9.32757 1.91837L8.19855 0.789347C7.81275 0.403551 7.18725 0.403551 6.80145 0.789347Z" stroke="currentColor" stroke-width="1.5"></path>
                        </svg>
                    </div>
                    <div class="stat-value">Tsh {{ number_format($Tdiscount) }}</div>
                    <div class="stat-label">Total Discounts</div>
                    <div class="stat-comparison">
                        <span>This Month</span>
                        <span>Tsh {{ number_format($Mdiscount) }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Products Sold Card -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card products">
                    <div class="stat-icon products">
                        <svg width="24" height="24" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="m4.25 6.75-2.5 1.25 6.25 3.25 6.25-3.25-2.5-1.25m-10 4.25 6.25 3.25 6.25-3.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="m8 8.25v-6.5m-2.25 2 2.25-2 2.25 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <div class="stat-value">{{ number_format($Tdebt) }}</div>
                    <div class="stat-label">Total Debts</div>
                    <div class="stat-comparison">
                        <span>This Month</span>
                        <span>{{ number_format($Mdebt) }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Profit Card -->
            <div class="col-md-6 col-lg-3">
                <div class="stat-card profit">
                    <div class="stat-icon profit">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM16.03 8.03L9 15.06L5.5 11.5L6.91 10.09L9 12.17L14.59 6.59L16.03 8.03Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <div class="stat-value">Tsh {{ number_format($TNetProfit) }}</div>
                    <div class="stat-label">Total Profit</div>
                    <div class="stat-comparison">
                        <span>This Month</span>
                        <span>Tsh {{ number_format($MoNetProfit) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-9">
                <div class="search-container">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="search" id="search-input" class="form-control search-input border-start-0" placeholder="Search sales by customer name, sales ID...">
                    </div>
                </div>
                
                <div class="sales-table">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Sales ID</th>
                                    <th>Customer</th>
                                    <th>Sales Agent</th>
                                    <th>Status</th>                                    
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
    @if($sales->isEmpty())
        <tr>
            <td colspan="6" class="text-center">
                <h4>
                    <i class="bi bi-graph-up fs-3"></i>
                    No sales found
                </h4>
            </td>
        </tr>
    @else
        @foreach ($sales as $index => $sale)
        @php
            if($sale->status == 'Debt') {
            $color = 'danger';
            $text = 'Credit';
            }
            else {
            $color = 'success';
            $text = $sale->transactionType ?? 'Cash';
            }
        @endphp
            <tr">
                <td>{{ $index + 1 }}</td>
                <td>{{ date('M d, Y', strtotime($sale->created_at)) }}</td> 
                <td>{{ $sale->salesName }}</td>
                <td>{{ $sale->cName }}</td>
                <td>{{ $sale->served_by }}</td>
                <td class="text-{{ $color }}">{{ $text }}</td>
                               
                <td class="text-end">
                    <form action="viewSales" method="post">
                        @csrf
                        <button class="btn btn-sm btn-view" name="salesName" value="{{ $sale->sales_id }}">
                            <i class="bi bi-eye"></i> View
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    @endif
</tbody>

                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3">
                <div class="calendar-container">
                    <div class="calendar-header">
                        <h6 class="mb-0">Filter by Date</h6>
                    </div>
                    
                    <div id="calendar-navigation" class="d-flex justify-content-between align-items-center mb-2">
                        <button id="prevMonth" class="calendar-nav-btn">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <h6 id="currentMonthYear" class="mb-0 text-center"></h6>
                        <button id="nextMonth" class="calendar-nav-btn">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                    
                    <div id="calendar" class="mb-3"></div>
                    
                    <form id="dateForm" action="{{ route('admin.saleDate') }}" method="post">
                        @csrf
                        <input type="hidden" id="selectedDate" name="selectedDate">
                    </form>
                </div>
                
                <div class="summary-card">
                    <h6 class="mb-3">Summary</h6>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Total Sales</span>
                        <strong>Tsh {{ number_format($sales->sum('totalPrice') ?? 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Transactions</span>
                        <strong>{{ count($sales) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Avg. Sale</span>
                        <strong>Tsh {{ number_format($sales->avg('totalPrice') ?? 0) }}</strong>
                    </div>
                </div>
            </div>
        </div>
  <script>
    const saleDates = @json($monthlySaleDates);
</script>
        <script>
           function downloadReport(account = 'all') {
    const currentMonth = new Date().getMonth() + 1;
    const url = `/export-monthly-report?month=${currentMonth}&account=${account}`;
    window.location.href = url;
}


            $(document).ready(function() {
                $('#search-input').on('keyup', function() {
                    var value = $(this).val().toLowerCase();
                    $('tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });

            // Calendar functionality
            let currentMonth = new Date().getMonth();
            let currentYear = new Date().getFullYear();

            function createCalendar(month, year) {
                const calendar = document.getElementById('calendar');
                calendar.innerHTML = '';

                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const firstDay = new Date(year, month, 1).getDay();

                const monthNames = [
                    'January', 'February', 'March', 'April', 'May', 
                    'June', 'July', 'August', 'September', 'October', 
                    'November', 'December'
                ];
                document.getElementById('currentMonthYear').innerText = `${monthNames[month]} ${year}`;

                const today = new Date();
                const currentDay = today.getDate();
                const currentMonth = today.getMonth();
                const currentYear = today.getFullYear();

                const daysHeader = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
                const headerRow = document.createElement('tr');
                daysHeader.forEach(day => {
                    const th = document.createElement('th');
                    th.innerText = day;
                    th.style.padding = '0.5rem';
                    headerRow.appendChild(th);
                });
                const table = document.createElement('table');
                table.classList.add('calendar-table');
                table.appendChild(headerRow);

                let row = document.createElement('tr');

                for (let i = 0; i < firstDay; i++) {
                    const td = document.createElement('td');
                    row.appendChild(td);
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    const td = document.createElement('td');
                    td.innerText = day;

                  const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

// Highlight today's date
if (
    day === new Date().getDate() &&
    month === new Date().getMonth() &&
    year === new Date().getFullYear()
) {
    td.classList.add('current-date');
}

// Highlight sale dates
if (saleDates.includes(dateString)) {
    td.classList.add('sales-date');
}


                    td.addEventListener('click', function() {
                        const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                        document.getElementById('selectedDate').value = formattedDate;
                        document.getElementById('dateForm').submit(); 
                    });

                    row.appendChild(td);

                    if ((day + firstDay) % 7 === 0) {
                        table.appendChild(row);
                        row = document.createElement('tr');
                    }
                }

                if (row.children.length > 0) {
                    table.appendChild(row);
                }

                calendar.appendChild(table);
            }

            document.getElementById('prevMonth').addEventListener('click', function() {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                createCalendar(currentMonth, currentYear);
            });

            document.getElementById('nextMonth').addEventListener('click', function() {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                createCalendar(currentMonth, currentYear);
            });

            createCalendar(currentMonth, currentYear);
        </script>
      

    </main>
  </div>
</div>
</body>
</html>