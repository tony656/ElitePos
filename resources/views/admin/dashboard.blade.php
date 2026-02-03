<div class="">
    <!-- Main Content -->
    <div class="ps-3">
        <!-- Header -->
        <div class="mb-4">
            <h3 class="fs-3 fw-bold text-dark mb-1">Dashboard</h1>
            <p class="text-muted">Welcome back! Here's what's happening with your business.</p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Products Card -->
            <div class="col-md-3">
                <div class="stat-card bg-primary-gradient shadow-lg">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="mb-1 opacity-75 text-uppercase" style="letter-spacing: 1px;">Products</p>
                                <h3 class="fw-bold mb-0">{{ $TProducts ?? '' }}</h2>
                            </div>
                            <div class="icon-shape rounded-circle">
                                <i class="bi bi-box-seam fs-3"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="opacity-75" style="">Out of stock</span>
                            <span class="fw-semibold">{{ $ofs ?? '' }} items</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employees Card -->
            <div class="col-md-3">
                <div class="stat-card bg-info-gradient shadow-lg">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="mb-1 opacity-75 text-uppercase" style=" letter-spacing: 1px;">Employees</p>
                                <h3 class="fw-bold mb-0">{{ $users ?? '' }}</h2>
                            </div>
                            <div class="icon-shape rounded-circle">
                                <i class="bi bi-people fs-3"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="opacity-75" style="">New this year</span>
                            <span class="fw-semibold">0 people</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expenses Card -->
            <div class="col-md-3">
                <div class="stat-card bg-danger-gradient shadow-lg">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="mb-1 opacity-75 text-uppercase" style=" letter-spacing: 1px;">Expenses</p>
                                <h3 class="fw-bold mb-0">Tsh.{{ number_format($revenueAmount ?? 0) }}</h2>
                            </div>
                            <div class="icon-shape rounded-circle">
                                <i class="bi bi-graph-down-arrow fs-3"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="opacity-75" style="">Transactions</span>
                            <span class="fw-semibold">{{ $revenue ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profit Card -->
            <div class="col-md-3">
                <div class="stat-card bg-success-gradient shadow-lg">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="mb-1 opacity-75 text-uppercase" style=" letter-spacing: 1px;">Profit</p>
                                <h3 class="fw-bold mb-0">Tsh.{{ number_format($NetProfit ?? 0) }}</h2>
                            </div>
                            <div class="icon-shape rounded-circle">
                                <i class="bi bi-graph-up-arrow fs-3"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="opacity-75" style="">Last year</span>
                            <span class="fw-semibold">${{ number_format($LNetProfit ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="divider-text">
            <span class="px-4 bg-light text-muted fw-semibold text-uppercase" style="">{{ $getName->bName ?? '' }} DASHBOARD</span>
        </div>

        <!-- Charts -->
        <div class="row g-4 mb-4">
            <!-- Sales Comparison Line Graph -->
            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">Sales Progress - Month Comparison</h5>
                                <p class="text-muted small mb-0">Current Month vs Last Month</p>
                            </div>
                            <div class="text-end">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center">
                                        <div class="color-indicator bg-primary me-2" style="width: 10px; height: 10px; border-radius: 50%;"></div>
                                        <span class="text-muted small">This Month</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="color-indicator bg-info me-2" style="width: 10px; height: 10px; border-radius: 50%;"></div>
                                        <span class="text-muted small">Last Month</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sales Stats -->
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <p class="text-muted small mb-1">This Month</p>
                                    <h4 class="fw-bold mb-0">Tsh.{{ number_format($currentMonthSales ?? 0) }}</h4>
                                    <div class="d-flex align-items-center mt-1">
                                        @if($growthPercentage >= 0)
                                            <span class="text-success small"><i class="bi bi-arrow-up-right"></i> {{ number_format($growthPercentage, 1) }}%</span>
                                        @else
                                            <span class="text-danger small"><i class="bi bi-arrow-down-right"></i> {{ number_format(abs($growthPercentage), 1) }}%</span>
                                        @endif
                                        <span class="text-muted small ms-1">vs last month</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <p class="text-muted small mb-1">Last Month</p>
                                    <h4 class="fw-bold mb-0">Tsh.{{ number_format($lastMonthSales ?? 0) }}</h4>
                                    <p class="text-muted small mt-1">Completed</p>
                                </div>
                            </div>
                        </div>
                        
                        <div style="height: 250px;">
                            <canvas id="salesComparisonChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Speedometer Chart -->
            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Sales Progress</h5>
                        <p class="text-muted small mb-4">Monthly target achievement</p>
                        <div id="speedometer" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Monthly Income Chart -->
            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">Monthly Income</h5>
                                <p class="text-muted small mb-0">January - December {{ date('Y') }}</p>
                            </div>
                            <div class="text-end">
                                <p class="text-muted small mb-1">Total Sales</p>
                                <h6 class="fw-bold">Tsh.{{ number_format(array_sum($monthlyTotalPrices ?? []), 0) }}</h6>
                            </div>
                        </div>
                        <div style="height: 300px;">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Sales Trend -->
            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold mb-1">Daily Sales Trend</h5>
                                <p class="text-muted small mb-0">Last 30 days performance</p>
                            </div>
                            <div class="text-end">
                                @php
                                    $currentMonthAvg = isset($currentMonthDailySales) && count($currentMonthDailySales) > 0 
                                        ? array_sum($currentMonthDailySales) / count($currentMonthDailySales) 
                                        : 0;
                                @endphp
                                <p class="text-muted small mb-1">Daily Average</p>
                                <h6 class="fw-bold">Tsh.{{ number_format($currentMonthAvg, 0) }}</h6>
                            </div>
                        </div>
                        <div style="height: 300px;">
                            <canvas id="dailySalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="chart-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1">Recent Sales</h5>
                        <p class="text-muted small mb-0">Latest transactions from your customers</p>
                    </div>
                    <button class="btn btn-outline-primary btn-sm">View All <i class="bi bi-arrow-right ms-1"></i></button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold">Product</th>
                                <th class="fw-semibold">Quantity</th>
                                <th class="fw-semibold">Amount</th>
                                <th class="fw-semibold">Staff</th>
                                <th class="fw-semibold">Date</th>
                                <th class="fw-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders ?? [] as $order)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle me-2" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-box"></i>
                                            </div>
                                            <span class="fw-semibold">{{ $order->cName ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td><span class="fw-bold">{{ $order->pQuantity ?? 0 }}</span></td>
                                    <td><span class="fw-bold text-success">Tsh.{{ number_format($order->totalPrice ?? 0, 2) }}</span></td>
                                    <td>{{ $order->served_by ?? 'Unknown' }}</td>
                                    <td>{{ isset($order->created_at) ? \Carbon\Carbon::parse($order->created_at)->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            @if (count($orders ?? []) == 0)
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No sales found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        --danger-gradient: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%);
        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
    }

    .stat-card {
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        overflow: hidden;
        position: relative;
        border: none;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.1);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .bg-primary-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .bg-success-gradient { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-danger-gradient { background: linear-gradient(135deg, #ee0979 0%, #ff6a00 100%); }
    .bg-info-gradient { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .bg-warning-gradient { background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); }

    .icon-shape {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        transition: transform 0.3s;
    }

    .stat-card:hover .icon-shape {
        transform: rotate(360deg) scale(1.1);
    }

    .chart-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background: white;
        transition: transform 0.3s;
    }

    .chart-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .color-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 8px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
        transform: scale(1.01);
        transition: all 0.2s;
    }

    .divider-text {
        position: relative;
        text-align: center;
        margin: 2rem 0;
    }

    .divider-text::before,
    .divider-text::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 45%;
        height: 2px;
        background: linear-gradient(to right, transparent, #667eea, transparent);
    }

    .divider-text::before {
        left: 0;
    }

    .divider-text::after {
        right: 0;
    }

    .pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Monthly Bar Chart
        const monthlyData = @json($monthlyTotalPrices ?? []);
        const barCtx = document.getElementById('barChart').getContext('2d');
        const chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        new Chart(barCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Monthly Sales',
                    data: monthlyData,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#667eea',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Tsh.' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        border: { display: false },
                        grid: { 
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 11 },
                            callback: function(value) {
                                return 'Tsh.' + (value / 1000).toFixed(0) + 'K';
                            }
                        }
                    },
                    x: {
                        border: { display: false },
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });

        // Sales Comparison Chart (Month vs Last Month)
        const comparisonCtx = document.getElementById('salesComparisonChart').getContext('2d');
        
        // Get sales data from backend
        const currentMonthDaily = @json($currentMonthDailySales ?? array_fill(0, 31, 0));
        const lastMonthDaily = @json($lastMonthDailySales ?? array_fill(0, 31, 0));
        
        // Generate day labels for current month
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const currentMonth = currentDate.getMonth();
        const daysInCurrentMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        
        // Generate day labels
        const dayLabels = [];
        for (let i = 1; i <= daysInCurrentMonth; i++) {
            dayLabels.push('Day ' + i);
        }
        
        // Prepare data for chart
        const currentMonthData = [];
        const lastMonthData = [];
        
        for (let i = 1; i <= dayLabels.length; i++) {
            currentMonthData.push(currentMonthDaily[i] || 0);
            // For last month data, we need to make sure we have data for this day
            lastMonthData.push(lastMonthDaily[i] || 0);
        }
        
        new Chart(comparisonCtx, {
            type: 'line',
            data: {
                labels: dayLabels,
                datasets: [
                    {
                        label: 'This Month',
                        data: currentMonthData,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4,
                        pointRadius: 2,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#667eea',
                    },
                    {
                        label: 'Last Month',
                        data: lastMonthData,
                        borderColor: '#4facfe',
                        backgroundColor: 'rgba(79, 172, 254, 0.05)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0.4,
                        pointRadius: 2,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#4facfe',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'top',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 8,
                        displayColors: true,
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Tsh.' + context.parsed.y.toLocaleString();
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        border: { display: false },
                        grid: { 
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: { size: 11 },
                            callback: function(value) {
                                return 'Tsh.' + (value / 1000).toFixed(0) + 'K';
                            }
                        }
                    },
                    x: {
                        border: { display: false },
                        grid: { 
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: { 
                            font: { size: 10 },
                            maxTicksLimit: 15
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Daily Sales Chart
        const dailyCtx = document.getElementById('dailySalesChart');
        if (dailyCtx) {
            // Get last 30 days data
            const last30Days = [];
            const last30DaysLabels = [];
            const today = new Date();
            
            for (let i = 29; i >= 0; i--) {
                const date = new Date();
                date.setDate(today.getDate() - i);
                last30DaysLabels.push(date.getDate());
                
                // Find sales for this day
                const daySales = currentMonthDaily[date.getDate()] || 0;
                last30Days.push(daySales);
            }
            
            new Chart(dailyCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: last30DaysLabels,
                    datasets: [{
                        label: 'Daily Sales',
                        data: last30Days,
                        backgroundColor: 'rgba(102, 126, 234, 0.7)',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        borderRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Tsh.' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Tsh.' + (value / 1000).toFixed(0) + 'K';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>