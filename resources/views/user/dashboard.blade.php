
<div class="">
   

    <!-- Main Content -->
    <div class="ps-3">
           <!-- Ads Carousel Banner -->
    <div class="mb-4">
        @if($ads->isnotempty())
        <div class="ads-carousel-container">
            <div id="adsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                <!-- Indicators -->
                @if(count($ads) > 1)
                <div class="carousel-indicators">
                    @foreach($ads as $index => $ad)
                    <button type="button" 
                            data-bs-target="#adsCarousel" 
                            data-bs-slide-to="{{ $index }}" 
                            class="{{ $index == 0 ? 'active' : '' }}"
                            aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                            aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
                @endif
                <!-- Carousel items -->
                <div class="carousel-inner rounded-4 shadow-lg overflow-hidden">
                    @foreach($ads as $index => $ad)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="ad-carousel-item position-relative">
                            <!-- Background Image with Overlay -->
                            <div class="ad-background" 
                                 style='background: url("{{ asset("public/images/".$ad->image_path) }}");background-repeat:no-repeat;background-position: center;background-size: contain;'></div>
                            
                            <!-- Dark Overlay -->
                            <div class="ad-overlay"></div>
                            
                            <!-- Content -->
                            <div class="ad-content position-relative text-white p-4 p-md-5">
                                <div class="row align-items-center min-vh-25">
                                    <div class="col-lg-8">
                                        <div class="mb-3">
                                            <span class="badge bg-primary-gradient px-3 py-2 mb-3">
                                                <i class="fas fa-bullhorn me-2"></i>Advertisement
                                            </span>
                                            <h2 class="display-6 fw-bold mb-3">{{ $ad->title }}</h2>
                                            <p class="lead mb-4 opacity-90">{{ Str::limit($ad->description, 150) }}</p>
                                         
                                        </div>
                                    </div>
                                    <div class="col-lg-4 text-center">
                                        <div class="ad-icon-container">
                                            <i class="fas fa-ad display-1 text-white opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Controls -->
                @if(count($ads) > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#adsCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#adsCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                @endif

                <!-- Auto-play indicator -->
                <div class="carousel-auto-play-indicator">
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
        @else
<!-- No ads fallback -->
<div class="no-ads-banner bg-gradient-primary rounded-4 shadow-lg p-5 text-center text-white mb-4">
    <div class="py-5">
        <i class="fas fa-ad display-1 mb-4 opacity-50"></i>
        <h2 class="fw-bold mb-3">No Active Advertisements</h2>
        <p class="lead mb-4 opacity-90">Upload your first advertisement to showcase promotions here</p>
        <a href="" class="btn btn-light btn-lg px-4 rounded-pill">
            <i class="fas fa-plus me-2"></i>Upload First Ad
        </a>
    </div>
</div>
<style>
.no-ads-banner {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endif
    </div>
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
            @if (canUser("view_employees"))                
            
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
            @endif
            <!-- Expenses Card -->
            @if (canUser("view_expenses"))     
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
            @endif
            <!-- Total Sales Card -->
            @if (canUser("view_reports"))    
            <div class="col-md-3">
                <div class="stat-card bg-success-gradient shadow-lg">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <p class="mb-1 opacity-75 text-uppercase" style=" letter-spacing: 1px;">Monthly Sales</p>
                                <h3 class="fw-bold mb-0">Tsh.{{ number_format($Msale ?? 0) }}</h2>
                            </div>
                            <div class="icon-shape rounded-circle">
                                <i class="bi bi-graph-up-arrow fs-3"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="opacity-75" style="">Last Month</span>
                            <span class="fw-semibold">Tsh.{{ number_format($lastMonthSales ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Daily Summary Cards -->
        <div class="row g-4 mb-4">
            <!-- Today's Received Items Card -->
            <div class="col-md-6">
                <div class="stat-card bg-warning-gradient shadow-lg">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <p class="mb-0 opacity-75 text-uppercase" style="letter-spacing: 1px; font-size: 0.75rem;">Today's Received Items</p>
                                <h2 class="fw-bold mb-0" style="font-size: 2rem;">Tsh.{{ number_format($todayReceivedCost ?? 0) }}</h2>
                                <p class="mb-0 opacity-75" style="font-size: 0.85rem;">{{ $todayReceivedItems ?? 0 }} items received</p>
                            </div>
                            <div class="icon-shape rounded-circle">
                                <i class="bi bi-box-seam fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Sold Items Card -->
            <div class="col-md-6">
                <div class="stat-card bg-success-gradient shadow-lg">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <p class="mb-0 opacity-75 text-uppercase" style="letter-spacing: 1px; font-size: 0.75rem;">Today's Sold Items</p>
                                <h2 class="fw-bold mb-0" style="font-size: 2rem;">Tsh.{{ number_format($todaySalesRevenue ?? 0) }}</h2>
                                <p class="mb-0 opacity-75" style="font-size: 0.85rem;">{{ $todaySoldItems ?? 0 }} items sold</p>
                            </div>
                            <div class="icon-shape rounded-circle">
                                <i class="bi bi-cart-check fs-3"></i>
                            </div>
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
 .ads-carousel-container {
        position: relative;
        margin-bottom: 2rem;
    }

    .ad-carousel-item {
        border-radius: 20px;
        overflow: hidden;
        min-height: 320px;
    }

    .ad-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        transform: scale(1.1);
        transition: transform 8s ease;
    }

    .ad-carousel-item:hover .ad-background {
        transform: scale(1.15);
    }


    .ad-content {
        z-index: 2;
        height: 320px;
        display: flex;
        align-items: center;
    }

    .ad-content h2 {
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .ad-content .lead {
        font-size: 1.25rem;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }

    .ad-icon-container {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-20px);
        }
    }

    .carousel-indicators {
        bottom: 20px;
    }

    .carousel-indicators button {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin: 0 5px;
        background-color: rgba(255, 255, 255, 0.5);
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .carousel-indicators button.active {
        background-color: white;
        transform: scale(1.2);
        box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        margin: 0 20px;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-50%) scale(1.1);
    }

    .carousel-control-prev {
        left: 10px;
    }

    .carousel-control-next {
        right: 10px;
    }

    .carousel-auto-play-indicator {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.1);
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
        overflow: hidden;
    }

    .carousel-auto-play-indicator .progress-bar {
        animation: progressBar 4s linear infinite;
        background: linear-gradient(to right, #667eea, #764ba2);
    }

    @keyframes progressBar {
        0% {
            width: 0%;
        }
        100% {
            width: 100%;
        }
    }

    /* Pause animation on hover */
    #adsCarousel:hover .carousel-auto-play-indicator .progress-bar {
        animation-play-state: paused;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .ad-content {
            height: 280px;
            padding: 2rem !important;
        }
        
        .ad-content h2 {
            font-size: 1.8rem;
        }
        
        .ad-content .lead {
            font-size: 1rem;
        }
        
    }

    @media (max-width: 576px) {
        .ad-carousel-item {
            min-height: 280px;
        }
        
        .ad-content {
            height: 280px;
            padding: 1.5rem !important;
        }
        
        .ad-content h2 {
            font-size: 1.5rem;
        }
        
        .ad-content .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }

    /* Fade animation for carousel */
    .carousel-fade .carousel-item {
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
    }

    .carousel-fade .carousel-item.active,
    .carousel-fade .carousel-item-next.carousel-item-left,
    .carousel-fade .carousel-item-prev.carousel-item-right {
        opacity: 1;
    }

    .carousel-fade .active.carousel-item-left,
    .carousel-fade .active.carousel-item-right {
        opacity: 0;
    }

    .carousel-fade .carousel-item-next,
    .carousel-fade .carousel-item-prev,
    .carousel-fade .carousel-item.active,
    .carousel-fade .active.carousel-item-left,
    .carousel-fade .active.carousel-item-prev {
        transform: translateX(0);
        transform: translate3d(0, 0, 0);
    }

    /* Add this to make carousel fade */
    #adsCarousel {
        opacity: 0;
        animation: fadeIn 1s ease forwards;
    }

    @keyframes fadeIn {
        to {
            opacity: 1;
        }
    }

    /* Optional: Add a subtle pulse animation to CTA buttons */
    @keyframes pulseCTA {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(255, 255, 255, 0);
        }
    }

    .btn-light {
        animation: pulseCTA 2s infinite;
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
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('adsCarousel');
    
    if (carousel) {
        // Add fade animation class
        carousel.classList.add('carousel-fade');
        
        // Auto-play progress bar
        const progressBar = carousel.querySelector('.progress-bar');
        const carouselInstance = new bootstrap.Carousel(carousel, {
            interval: 4000,
            ride: 'carousel',
            wrap: true,
            pause: 'hover'
        });
        
        // Update progress bar
        let progress = 0;
        let progressInterval;
        
        function startProgress() {
            progress = 0;
            progressBar.style.width = '0%';
            
            progressInterval = setInterval(() => {
                progress += 0.25;
                progressBar.style.width = progress + '%';
                
                if (progress >= 100) {
                    clearInterval(progressInterval);
                }
            }, 10);
        }
        
        // Start progress on load
        startProgress();
        
        // Reset progress on slide change
        carousel.addEventListener('slide.bs.carousel', function() {
            clearInterval(progressInterval);
            startProgress();
        });
        
        // Pause progress on hover
        carousel.addEventListener('mouseenter', function() {
            clearInterval(progressInterval);
        });
        
        // Resume progress on mouse leave
        carousel.addEventListener('mouseleave', function() {
            startProgress();
        });
        
        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                carouselInstance.prev();
            } else if (e.key === 'ArrowRight') {
                carouselInstance.next();
            } else if (e.key === ' ') {
                carouselInstance.cycle();
            }
        });
        
        // Touch swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        
        carousel.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        carousel.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            
            if (touchEndX < touchStartX - swipeThreshold) {
                // Swipe left - next slide
                carouselInstance.next();
            }
            
            if (touchEndX > touchStartX + swipeThreshold) {
                // Swipe right - previous slide
                carouselInstance.prev();
            }
        }
        
        // Auto-hide controls when not interacting
        let hideControlsTimeout;
        
        function showControls() {
            const controls = carousel.querySelectorAll('.carousel-control-prev, .carousel-control-next');
            controls.forEach(control => {
                control.style.opacity = '1';
            });
            
            clearTimeout(hideControlsTimeout);
            hideControlsTimeout = setTimeout(() => {
                controls.forEach(control => {
                    control.style.opacity = '0';
                });
            }, 3000);
        }
        
        function hideControls() {
            const controls = carousel.querySelectorAll('.carousel-control-prev, .carousel-control-next');
            controls.forEach(control => {
                control.style.opacity = '0';
            });
        }
        
        // Show controls on interaction
        carousel.addEventListener('mouseenter', showControls);
        carousel.addEventListener('touchstart', showControls);
        
        // Hide controls after delay
        hideControlsTimeout = setTimeout(hideControls, 3000);
    }
    
    // Add smooth transitions for carousel items
    const carouselItems = document.querySelectorAll('.carousel-item');
    carouselItems.forEach(item => {
        item.style.transition = 'transform 0.8s ease, opacity 0.8s ease';
    });
});
</script>
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