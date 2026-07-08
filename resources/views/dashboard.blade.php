<div class="">
    <!-- Main Content -->
    <div class="ps-3">

      

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Products Card -->
            <div class="col-md-3">
                <div class="stat-card stat-card-navy">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="mb-1 opacity-75 text-uppercase" style="letter-spacing: 1px;">{{ __('messages.dashboard_stats_products') }}</p>
                                <h3 class="fw-bold mb-0">{{ number_format($TProducts ?? 0) }}</h2>
                            </div>
                            <div class="icon-shape icon-shape-navy">
                                <i class="bi bi-box-seam fs-3"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="opacity-75">{{ __('messages.dashboard_out_of_stock') }}</span>
                            <span class="fw-semibold">{{ $ofs ?? 0 }} items</span>
                        </div>
                    </div>
                </div>
            </div>
            @if (canUser('manage_employees'))
            <!-- Employees Card -->
            <div class="col-md-3">
                <div class="stat-card stat-card-sky">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="mb-1 opacity-75 text-uppercase" style="letter-spacing: 1px;">{{ __('messages.dashboard_stats_employees') }}</p>
                                <h3 class="fw-bold mb-0">{{ $users ?? 0 }}</h2>
                            </div>
                            <div class="icon-shape icon-shape-sky">
                                <i class="bi bi-people fs-3"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="opacity-75">{{ __('messages.dashboard_active_users') }}</span>
                            <span class="fw-semibold">{{ $activeUsers ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if (canUser('view_expenses'))
            <!-- Expenses Card -->
            <div class="col-md-3">
                <div class="stat-card stat-card-rose">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="mb-1 opacity-75 text-uppercase" style="letter-spacing: 1px;">{{ __('messages.dashboard_stats_expenses') }}</p>
                                <h3 class="fw-bold mb-0">Tsh.{{ number_format($revenueAmount ?? 0) }}</h2>
                            </div>
                            <div class="icon-shape icon-shape-rose">
                                <i class="bi bi-graph-down-arrow fs-3"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="opacity-75">{{ __('messages.dashboard_transactions') }}</span>
                            <span class="fw-semibold">{{ $revenue ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if (canUser('view_reports'))
            <!-- Sales Card -->
            <div class="col-md-3">
                <div class="stat-card stat-card-amber">
                    <div class="card-body p-4 text-dark">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="mb-1 opacity-75 text-uppercase" style="letter-spacing: 1px;">{{ __('messages.dashboard_stats_sales') }}</p>
                                <h3 class="fw-bold mb-0">Tsh.{{ number_format($currentMonthSales ?? 0) }}</h2>
                            </div>
                            <div class="icon-shape icon-shape-amber">
                                <i class="bi bi-graph-up-arrow fs-3"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="opacity-75">{{ __('messages.dashboard_this_month') }}</span>
                            @if($growthPercentage >= 0)
                                <span class="fw-semibold text-success">+{{ number_format($growthPercentage, 1) }}%</span>
                            @else
                                <span class="fw-semibold text-danger">{{ number_format($growthPercentage, 1) }}%</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

          <!-- Welcome Container -->
        <div class="row g-4 mb-4 animate-in">
            <div class="col-12">
                <div class="welcome-card shadow-lg">
                    <div class="welcome-decor"></div>
                    <div class="welcome-inner d-flex justify-content-between align-items-center flex-wrap gap-4">
                        <div class="d-flex align-items-center gap-4">
                            <div class="user-avatar-large">
                                @php
                                    $user = Auth::user();
                                    $profileSrc = null;
                                    if (!empty($user->userImg)) {
                                        $profileSrc = asset('/public/images/' . $user->userImg);
                                    } elseif (!empty($user->photo)) {
                                        $profileSrc = asset('/public/images/' . $user->photo);
                                    }
                                    $initials = strtoupper(substr($user->name ?? 'U', 0, 1));
                                @endphp
                                @if($profileSrc)
                                    <img src="{{ $profileSrc }}" alt="{{ $user->name }}" class="user-avatar-img">
                                @else
                                    <span class="user-initials">{{ $initials }}</span>
                                @endif
                            </div>
                            <div>
                                <p class="welcome-eyebrow">{{ __('messages.welcome_back') }}  👋</p>
                                <h2 class="welcome-name">{{ $user->name }}</h2>
                                <span class="welcome-role-badge">
                                    <i class="fas fa-shield-halved"></i>
                                    {{ $user->levelStatus }}
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <p class="welcome-date-label"><i class="bi bi-calendar3 me-1"></i>{{ date('l, F j, Y') }}</p>
                            <span id="dashboardTime" class="welcome-time">00:00:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Divider -->
        <div class="divider-text">
            <span class="px-4 bg-light text-muted fw-semibold text-uppercase">{{ $getName->bName ?? '' }} {{ __('messages.dashboard') }}</span>
        </div>

        <!-- Charts -->
        <div class="row g-4 mb-4">
            <!-- Sales Comparison Line Graph -->
            <div class="col-lg-6">
                <div class="chart-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold mb-1" style="color: var(--navy);">{{ __('messages.dashboard_sales_comparison') }}</h5>
                                <p class="text-muted small mb-0">{{ __('messages.dashboard_current_vs_last') }}</p>
                            </div>
                            <div class="text-end">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center">
                                        <span class="color-indicator" style="background: var(--navy); width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-right: 6px;"></span>
                                        <span class="text-muted small">{{ __('messages.chart_this_month') }}</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="color-indicator" style="background: var(--sky); width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-right: 6px;"></span>
                                        <span class="text-muted small">{{ __('messages.chart_last_month') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sales Stats -->
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="p-3 rounded" style="background: var(--slate-50);">
                                    <p class="text-muted small mb-1">{{ __('messages.chart_this_month') }}</p>
                                    <h4 class="fw-bold mb-0" style="color: var(--navy);">Tsh.{{ number_format($currentMonthSales ?? 0) }}</h4>
                                    <div class="d-flex align-items-center mt-1">
                                        @if($growthPercentage >= 0)
                                            <span class="text-success small"><i class="bi bi-arrow-up-right"></i> {{ number_format($growthPercentage, 1) }}%</span>
                                        @else
                                            <span class="text-danger small"><i class="bi bi-arrow-down-right"></i> {{ number_format(abs($growthPercentage), 1) }}%</span>
                                        @endif
                                        <span class="text-muted small ms-1">{{ __('messages.chart_vs_last_month') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded" style="background: var(--slate-50);">
                                    <p class="text-muted small mb-1">{{ __('messages.chart_last_month') }}</p>
                                    <h4 class="fw-bold mb-0" style="color: var(--navy);">Tsh.{{ number_format($lastMonthSales ?? 0) }}</h4>
                                    <p class="text-muted small mt-1">{{ __('messages.chart_completed') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div style="height: 250px; position: relative;">
                            <canvas id="salesComparisonChart"></canvas>
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
                                <h5 class="fw-bold mb-1" style="color: var(--navy);">{{ __('messages.dashboard_monthly_income') }}</h5>
                                <p class="text-muted small mb-0">January - December {{ date('Y') }}</p>
                            </div>
                            <div class="text-end">
                                <p class="text-muted small mb-1">{{ __('messages.dashboard_total_sales') }}</p>
                                <h6 class="fw-bold" style="color: var(--navy);">Tsh.{{ number_format(array_sum($monthlyTotalPrices ?? []), 0) }}</h6>
                            </div>
                        </div>
                        <div style="height: 300px; position: relative;">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Sales Trend -->
            <div class="col-12">
                <div class="chart-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold mb-1" style="color: var(--navy);">{{ __('messages.dashboard_daily_sales_trend') }}</h5>
                                <p class="text-muted small mb-0">{{ __('messages.dashboard_last_30_days') }}</p>
                            </div>
                            <div class="text-end">
                                @php
                                    $currentMonthAvg = isset($currentMonthDailySales) && count($currentMonthDailySales) > 0 
                                        ? array_sum($currentMonthDailySales) / count($currentMonthDailySales) 
                                        : 0;
                                @endphp
                                <p class="text-muted small mb-1">{{ __('messages.dashboard_daily_average') }}</p>
                                <h6 class="fw-bold" style="color: var(--navy);">Tsh.{{ number_format($currentMonthAvg, 0) }}</h6>
                            </div>
                        </div>
                        <div style="height: 300px; position: relative;">
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
        --navy: #0B1E3D;
        --navy-mid: #112952;
        --navy-light: #1A3A6B;
        --amber: #F59E0B;
        --amber-pale: #FEF3C7;
        --emerald: #059669;
        --emerald-pale: #D1FAE5;
        --rose: #E11D48;
        --rose-pale: #FFE4E6;
        --violet: #7C3AED;
        --violet-pale: #EDE9FE;
        --sky: #0284C7;
        --sky-pale: #E0F2FE;
        --slate-50: #F8FAFC;
        --slate-100: #F1F5F9;
        --slate-200: #E2E8F0;
        --slate-300: #CBD5E1;
        --slate-400: #94A3B8;
        --slate-500: #64748B;
        --slate-600: #475569;
        --slate-700: #334155;
        --slate-800: #1E293B;
        --white: #FFFFFF;
    }

    /* Welcome Card */
    .welcome-card {
        background: linear-gradient(135deg, var(--navy) 15%, var(--navy-mid) 50%, var(--amber) 100%);
        border-radius: 24px;
        padding: 2rem 2.5rem;
        color: white;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .welcome-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 50px rgba(11, 30, 61, 0.35);
    }

    .welcome-decor {
        position: absolute;
        top: -60px;
        right: -40px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, transparent 70%);
        pointer-events: none;
    }

    .welcome-inner {
        position: relative;
        z-index: 1;
    }

    .user-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 24px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.3);
        flex-shrink: 0;
        overflow: hidden;
    }

    .user-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 22px;
    }

    .user-initials {
        font-size: 2rem;
        font-weight: 700;
        color: white;
        line-height: 1;
    }

    .welcome-eyebrow {
        opacity: 0.85;
        font-size: 0.875rem;
        font-weight: 500;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .welcome-name {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.375rem;
        letter-spacing: -0.02em;
    }

    .welcome-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.15);
        padding: 0.35rem 0.875rem;
        border-radius: 99px;
        font-size: 0.8125rem;
        font-weight: 500;
        backdrop-filter: blur(4px);
    }

    .welcome-date-label {
        opacity: 0.7;
        font-size: 0.75rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .welcome-time {
        font-size: 1.5rem;
        font-weight: 700;
        font-family: 'Roboto', monospace;
        letter-spacing: 0.05em;
    }

    /* Stat Cards */
    .stat-card {
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        overflow: hidden;
        position: relative;
        border: none;
        height: 100%;
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

    .stat-card-navy { background: linear-gradient(135deg, var(--navy) 0%, var(--navy-light) 100%); }
    .stat-card-sky { background: linear-gradient(135deg, var(--sky) 0%, #0369a1 100%); }
    .stat-card-rose { background: linear-gradient(135deg, var(--rose) 0%, #b91c1c 100%); }
    .stat-card-amber { background: linear-gradient(135deg, var(--amber) 0%, #d97706 100%); }

    .icon-shape {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        transition: transform 0.3s;
    }

    .stat-card:hover .icon-shape {
        transform: rotate(360deg) scale(1.1);
    }

    .icon-shape-navy { background: rgba(255, 255, 255, 0.15); }
    .icon-shape-sky { background: rgba(255, 255, 255, 0.15); }
    .icon-shape-rose { background: rgba(255, 255, 255, 0.15); }
    .icon-shape-amber { background: rgba(255, 255, 255, 0.25); }

    /* Chart Cards */
    .chart-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background: white;
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
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

    /* Divider */
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
        background: linear-gradient(to right, transparent, var(--navy), transparent);
    }

    .divider-text::before {
        left: 0;
    }

    .divider-text::after {
        right: 0;
    }

    .divider-text span {
        background: white;
        padding: 0 1rem;
        color: var(--slate-500);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .welcome-card {
            padding: 1.5rem;
        }
        
        .user-avatar-large {
            width: 60px;
            height: 60px;
        }
        
        .welcome-name {
            font-size: 1.25rem;
        }
        
        .stat-card .card-body {
            padding: 1.25rem !important;
        }
        
        .icon-shape {
            width: 45px;
            height: 45px;
            font-size: 1.2rem;
        }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Update time
        function updateDashboardTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const dashboardTimeEl = document.getElementById('dashboardTime');
            if (dashboardTimeEl) {
                dashboardTimeEl.textContent = `${hours}:${minutes}:${seconds}`;
            }
        }
        updateDashboardTime();
        setInterval(updateDashboardTime, 1000);

        // ===== SALES COMPARISON CHART =====
        const comparisonCtx = document.getElementById('salesComparisonChart');
        if (comparisonCtx) {
            const currentMonthDaily = @json($currentMonthDailySales ?? array_fill(0, 31, 0));
            const lastMonthDaily = @json($lastMonthDailySales ?? array_fill(0, 31, 0));
            
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const currentMonth = currentDate.getMonth();
            const daysInCurrentMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            
            const dayLabels = [];
            const currentMonthData = [];
            const lastMonthData = [];
            
            for (let i = 1; i <= daysInCurrentMonth; i++) {
                dayLabels.push(i);
                currentMonthData.push(currentMonthDaily[i] || 0);
                lastMonthData.push(lastMonthDaily[i] || 0);
            }
            
            new Chart(comparisonCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: dayLabels,
                    datasets: [
                        {
                            label: 'This Month',
                            data: currentMonthData,
                            borderColor: '#0B1E3D',
                            backgroundColor: 'rgba(11, 30, 61, 0.05)',
                            borderWidth: 3,
                            fill: false,
                            tension: 0.4,
                            pointRadius: 3,
                            pointHoverRadius: 7,
                            pointBackgroundColor: '#0B1E3D',
                            pointBorderColor: '#0B1E3D',
                        },
                        {
                            label: 'Last Month',
                            data: lastMonthData,
                            borderColor: '#0284C7',
                            backgroundColor: 'rgba(2, 132, 199, 0.05)',
                            borderWidth: 3,
                            borderDash: [6, 4],
                            fill: false,
                            tension: 0.4,
                            pointRadius: 3,
                            pointHoverRadius: 7,
                            pointBackgroundColor: '#0284C7',
                            pointBorderColor: '#0284C7',
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
                                pointStyle: 'circle',
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(11, 30, 61, 0.9)',
                            padding: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            cornerRadius: 8,
                            displayColors: true,
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Tsh.' + context.parsed.y.toLocaleString();
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
                                    if (value >= 1000000) return 'Tsh.' + (value / 1000000).toFixed(1) + 'M';
                                    if (value >= 1000) return 'Tsh.' + (value / 1000).toFixed(0) + 'K';
                                    return 'Tsh.' + value;
                                }
                            }
                        },
                        x: {
                            border: { display: false },
                            grid: { display: false },
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
        }

        // ===== MONTHLY BAR CHART =====
        const monthlyData = @json($monthlyTotalPrices ?? []);
        const barCtx = document.getElementById('barChart');
        if (barCtx) {
            const chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            
            new Chart(barCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Monthly Sales',
                        data: monthlyData,
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#F59E0B',
                        pointBorderColor: '#0B1E3D',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true,
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(11, 30, 61, 0.9)',
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
                                    if (value >= 1000000) return 'Tsh.' + (value / 1000000).toFixed(1) + 'M';
                                    if (value >= 1000) return 'Tsh.' + (value / 1000).toFixed(0) + 'K';
                                    return 'Tsh.' + value;
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
        }

        // ===== DAILY SALES CHART =====
        const dailyCtx = document.getElementById('dailySalesChart');
        if (dailyCtx) {
            const currentMonthDaily = @json($currentMonthDailySales ?? array_fill(0, 31, 0));
            
            const last30Days = [];
            const last30DaysLabels = [];
            const today = new Date();
            
            for (let i = 29; i >= 0; i--) {
                const date = new Date();
                date.setDate(today.getDate() - i);
                last30DaysLabels.push(date.getDate() + '/' + (date.getMonth() + 1));
                
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
                        backgroundColor: 'rgba(11, 30, 61, 0.7)',
                        borderColor: '#0B1E3D',
                        borderWidth: 2,
                        borderRadius: 4,
                        hoverBackgroundColor: 'rgba(245, 158, 11, 0.8)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true,
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'rectRounded',
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(11, 30, 61, 0.9)',
                            padding: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            cornerRadius: 8,
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
                                    if (value >= 1000000) return 'Tsh.' + (value / 1000000).toFixed(1) + 'M';
                                    if (value >= 1000) return 'Tsh.' + (value / 1000).toFixed(0) + 'K';
                                    return 'Tsh.' + value;
                                }
                            }
                        },
                        x: {
                            border: { display: false },
                            grid: { display: false },
                            ticks: { 
                                font: { size: 9 },
                                maxTicksLimit: 15
                            }
                        }
                    }
                }
            });
        }
    });
</script>
