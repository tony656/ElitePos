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
                                <h3 class="fw-bold mb-0">{{ number_format($TProducts ?? 0) }}</h2>
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

            <!-- Today's Information Panel (REPLACED SPEEDOMETER) -->
            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Today's Information</h5>
                        
                        <!-- Date & Time Section -->
                        <div class="info-section mb-4 pb-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">📅 Current Date</p>
                                    <h4 class="fw-bold mb-2" id="currentDate">{{ date('l, F j, Y') }}</h4>
                                    <p class="text-muted small mb-2">🕐 Current Time</p>
                                    <h3 class="fw-bold text-primary" id="currentTime">00:00:00</h3>
                                </div>
                                <div class="text-center">
                                    <div id="dayNightIcon" style="font-size: 5rem; margin-bottom: 10px;">🌤️</div>
                                    <p class="text-muted small fw-bold" id="dayNightText">Day</p>
                                </div>
                            </div>
                        </div>

                        <!-- Today's Sales Section -->
                        <div class="info-section mb-4 pb-4 border-bottom">
                            <p class="text-muted small mb-3 text-uppercase fw-bold">💰 Today's Sales</p>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded" style="border-left: 4px solid #667eea;">
                                        <p class="text-muted small mb-1">Total Sales</p>
                                        <h5 class="fw-bold mb-0">Tsh.{{ number_format($todaySales ?? 0) }}</h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded" style="border-left: 4px solid #38ef7d;">
                                        <p class="text-muted small mb-1">Transactions</p>
                                        <h5 class="fw-bold mb-0">{{ $todayTransactions ?? 0 }}</h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded" style="border-left: 4px solid #4facfe;">
                                        <p class="text-muted small mb-1">Cash Received</p>
                                        <h5 class="fw-bold mb-0">Tsh.{{ number_format($todayPaid ?? 0) }}</h5>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded" style="border-left: 4px solid #ff6a00;">
                                        <p class="text-muted small mb-1">Credit Sales</p>
                                        <h5 class="fw-bold mb-0">Tsh.{{ number_format($todayCredit ?? 0) }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Weather Section -->
                        <div class="info-section mb-4 pb-4 border-bottom">
                            <p class="text-muted small mb-3 text-uppercase fw-bold">🌡️ Weather</p>
                            <div class="row align-items-center">
                                <div class="col-4 text-center">
                                    <div id="weatherIcon" style="font-size: 3.5rem;">🌤️</div>
                                </div>
                                <div class="col-8">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <p class="text-muted small mb-1">Temperature</p>
                                            <h5 class="fw-bold mb-0" id="temperature">--°C</h5>
                                        </div>
                                        <div class="col-6">
                                            <p class="text-muted small mb-1">Condition</p>
                                            <h6 class="fw-bold mb-0" id="weatherCondition">Loading...</h6>
                                        </div>
                                        <div class="col-6">
                                            <p class="text-muted small mb-1">Humidity</p>
                                            <h6 class="fw-bold mb-0" id="humidity">--%</h6>
                                        </div>
                                        <div class="col-6">
                                            <p class="text-muted small mb-1">Wind Speed</p>
                                            <h6 class="fw-bold mb-0" id="windSpeed">-- km/h</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Status Section -->
                        <div class="info-section">
                            <p class="text-muted small mb-3 text-uppercase fw-bold">📊 Business Status</p>
                            <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                <div>
                                    <p class="text-muted small mb-1">Active Staff</p>
                                    <h5 class="fw-bold mb-0">{{ $activeEmployees ?? 0 }}</h5>
                                </div>
                                <div class="text-center">
                                    <p class="text-muted small mb-1">In Stock</p>
                                    <h5 class="fw-bold mb-0">{{ $inStockItems ?? 0 }}</h5>
                                </div>
                                <div class="text-end">
                                    <p class="text-muted small mb-1">System</p>
                                    <h6 class="fw-bold text-success mb-0">🟢 Online</h6>
                                </div>
                            </div>
                        </div>
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

    .info-section {
        animation: slideIn 0.4s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Update time every second
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds}`;
            
            // Update day/night based on time
            const hour = now.getHours();
            const dayNightIcon = document.getElementById('dayNightIcon');
            const dayNightText = document.getElementById('dayNightText');
            
            if (hour >= 5 && hour < 18) {
                dayNightIcon.textContent = '☀️';
                dayNightText.textContent = 'Day';
            } else {
                dayNightIcon.textContent = '🌙';
                dayNightText.textContent = 'Night';
            }
        }
        
        updateTime();
        setInterval(updateTime, 1000);

        // Fetch weather data (using open-meteo API - no key required)
        async function fetchWeather() {
            try {
                // Using Dar es Salaam coordinates (-6.8, 39.2)
                const latitude = -6.8;
                const longitude = 39.2;
                
                const response = await fetch(
                    `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&timezone=auto`
                );
                
                if (response.ok) {
                    const data = await response.json();
                    const current = data.current;
                    
                    document.getElementById('temperature').textContent = Math.round(current.temperature_2m) + '°C';
                    document.getElementById('humidity').textContent = current.relative_humidity_2m + '%';
                    document.getElementById('windSpeed').textContent = Math.round(current.wind_speed_10m) + ' km/h';
                    
                    // Set weather condition based on WMO weather code
                    const weatherCodes = {
                        0: 'Clear', 1: 'Mostly Clear', 2: 'Partly Cloudy', 3: 'Overcast',
                        45: 'Foggy', 48: 'Foggy', 51: 'Light Drizzle', 53: 'Moderate Drizzle',
                        55: 'Heavy Drizzle', 61: 'Slight Rain', 63: 'Moderate Rain', 65: 'Heavy Rain',
                        71: 'Slight Snow', 73: 'Moderate Snow', 75: 'Heavy Snow', 80: 'Slight Showers',
                        81: 'Moderate Showers', 82: 'Violent Showers', 85: 'Snow Showers', 95: 'Thunderstorm'
                    };
                    
                    const condition = weatherCodes[current.weather_code] || 'Unknown';
                    document.getElementById('weatherCondition').textContent = condition;
                    
                    // Set weather icon based on condition
                    const weatherIcons = {
                        0: '☀️', 1: '🌤️', 2: '⛅', 3: '☁️',
                        45: '🌫️', 48: '🌫️', 51: '🌦️', 53: '🌦️',
                        55: '🌧️', 61: '🌧️', 63: '🌧️', 65: '⛈️',
                        71: '🌨️', 73: '🌨️', 75: '🌨️', 80: '🌦️',
                        81: '🌧️', 82: '⛈️', 85: '🌨️', 95: '⛈️'
                    };
                    
                    document.getElementById('weatherIcon').textContent = weatherIcons[current.weather_code] || '🌤️';
                }
            } catch (error) {
                console.log('Weather data unavailable');
            }
        }
        
        fetchWeather();
        // Update weather every 30 minutes
        setInterval(fetchWeather, 30 * 60 * 1000);

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