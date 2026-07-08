<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.sales_report') }} — {{ __('messages.daily_report') }}</title>
    @include('links');
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #1abc9c;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --sidebar-width: 250px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container-fluid {
            padding: 0;
        }

        /* Main Content Styles */
        main {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 2rem !important;
            transition: all 0.3s ease;
        }

        /* Header Styles */
        .page-header {
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 15px;
            margin-bottom: 5px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-left: 5px solid var(--secondary-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-title i {
            color: var(--secondary-color);
            font-size: 1.2rem;
        }

        /* Tab Navigation */
        .tab-navigation {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 5px;
            overflow: hidden;
        }

        .tab-buttons {
            display: flex;
            border-bottom: 2px solid #f1f2f6;
            flex-wrap: wrap;
        }

        .tab-btn {
            flex: 1;
            padding: 1.25rem 1.5rem;
            background: none;
            border: none;
            font-size: 1.1rem;
            font-weight: 600;
            color: #7f8c8d;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            position: relative;
            min-width: 120px;
        }

        .tab-btn:hover {
            background: #f8f9fa;
            color: var(--primary-color);
        }

        .tab-btn.active {
            color: var(--secondary-color);
            background: #f8f9fa;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--secondary-color);
            border-radius: 3px 3px 0 0;
        }

        .tab-btn-count {
            background: var(--secondary-color);
            color: white;
            font-size: 0.85rem;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            min-width: 25px;
            text-align: center;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-top: 4px solid;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
        }

        .stat-card.sales {
            border-color: var(--success-color);
        }

        .stat-card.credit {
            border-color: var(--warning-color);
        }

        .stat-card.expenses {
            border-color: var(--danger-color);
        }

        .stat-card.profit {
            border-color: var(--secondary-color);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: white;
        }

        .stat-card.sales .stat-icon {
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
        }

        .stat-card.credit .stat-icon {
            background: linear-gradient(135deg, var(--warning-color), #f1c40f);
        }

        .stat-card.expenses .stat-icon {
            background: linear-gradient(135deg, var(--danger-color), #c0392b);
        }

        .stat-card.profit .stat-icon {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
        }

        .stat-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .stat-info h3 {
            font-size: 0.9rem;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1;
        }

        .stat-change {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            background: rgba(52, 152, 219, 0.1);
            color: var(--secondary-color);
        }

        .stat-change.positive {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        .stat-change.negative {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 0.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f1f2f6;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-title i {
            color: var(--secondary-color);
        }

        .table-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .date-filter {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }

        .date-filter label {
            font-weight: 600;
            color: var(--dark-color);
            margin: 0;
            font-size: 0.9rem;
        }

        .date-filter input {
            border: none;
            background: transparent;
            font-weight: 500;
            color: var(--dark-color);
            outline: none;
            min-width: 120px;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e9ecef;
            overflow-x: auto;
        }

        .table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 100%;
        }

        .table thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table thead th {
            font-weight: 600;
            padding: 1rem 1.5rem;
            border: none;
            white-space: nowrap;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f2f6;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.002);
        }

        /* Balance Warning Styles */
        .table tbody tr.balance-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeeba 100%) !important;
            border-left: 4px solid #f39c12;
        }

        .table tbody tr.balance-warning:hover {
            background: linear-gradient(135deg, #ffeeba 0%, #ffdf7e 100%) !important;
        }

        .balance-warning-icon {
            color: #f39c12;
            font-size: 1.2rem;
            margin-right: 0.5rem;
            cursor: help;
        }

        .balance-tooltip {
            font-size: 0.85rem;
        }

        .balance-issue {
            display: block;
            color: #856404;
            font-size: 0.8rem;
            margin-top: 2px;
        }

        .table tbody td {
            padding: 0.75rem 1.5rem;
            vertical-align: middle;
            border: none;
            font-weight: 500;
            color: #2c3e50;
            border-bottom: 1px solid #f1f2f6;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .profit-cell {
            font-weight: 700 !important;
            font-size: 1.05rem;
        }

        .profit-positive {
            color: var(--success-color) !important;
        }

        .profit-negative {
            color: var(--danger-color) !important;
        }

        .profit-neutral {
            color: #7f8c8d !important;
        }

        .action-btn {
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            border: 2px solid;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none !important;
        }

        .action-btn.cash-submit-btn {
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
            border-color: var(--success-color);
            color: white;
        }

        .action-btn.cash-submit-btn:hover {
            background: linear-gradient(135deg, #219653, #27ae60);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
        }

        .action-btn.cash-submit-btn.partial-payment {
            background: linear-gradient(135deg, var(--warning-color), #f1c40f) !important;
            border-color: var(--warning-color) !important;
            color: white !important;
        }

        .action-btn.cash-submit-btn.partial-payment:hover {
            background: linear-gradient(135deg, #e67e22, #f39c12) !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.3);
        }

        .action-btn.details {
            background: transparent;
            border-color: var(--secondary-color);
            color: var(--secondary-color);
        }

        .action-btn.details:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Staff Performance Cards */
        .staff-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .staff-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-top: 4px solid var(--secondary-color);
            position: relative;
            overflow: hidden;
        }

        .staff-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
        }

        .staff-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f2f6;
        }

        .staff-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            font-weight: 600;
        }

        .staff-info {
            flex: 1;
        }

        .staff-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .staff-role {
            font-size: 0.9rem;
            color: #7f8c8d;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .staff-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .staff-stat {
            text-align: center;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-number.sales {
            color: var(--success-color);
        }

        .stat-number.credit {
            color: var(--warning-color);
        }

        .stat-number.discount {
            color: var(--info-color);
        }

        .stat-number.debt {
            color: var(--danger-color);
        }

        .staff-performance {
            margin-top: 1rem;
        }

        .performance-bar {
            height: 8px;
            background: #f1f2f6;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .performance-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--secondary-color), #2980b9);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .performance-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: var(--primary-color);
        }

        /* Shop Avatar */
        .shop-avatar {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #7f8c8d;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
            color: var(--secondary-color);
        }

        .empty-state-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        .empty-state-text {
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        .modal-header {
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
        }

        .modal-header.bg-success {
            background: linear-gradient(135deg, #27ae60, #2ecc71) !important;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            border-top: 2px solid #f1f2f6;
            padding: 1.5rem;
        }

        .card {
            border-radius: 12px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .card.bg-light {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
        }

        .input-group-text {
            border: none;
            background: #f8f9fa;
            font-weight: 600;
        }

        .form-control-lg {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .form-control-lg:focus {
            border-color: #27ae60;
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
        }

        /* Toast Notifications */
        .toast {
            border-radius: 10px;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .table-controls {
                width: 100%;
                justify-content: space-between;
            }
            
            .staff-container {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 768px) {
            main {
                padding: 1rem !important;
            }
            
            .page-header {
                padding: 1.25rem;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .table-container {
                padding: 1rem;
            }
            
            .table thead th,
            .table tbody td {
                padding: 0.75rem;
            }
            
            .action-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }
            
            .tab-btn {
                padding: 1rem;
                font-size: 1rem;
                min-width: 100px;
            }
        }

        @media (max-width: 576px) {
            .date-filter {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
                width: 100%;
            }
            
            .date-filter input {
                width: 100%;
            }
            
            .staff-container {
                grid-template-columns: 1fr;
            }
            
            .staff-stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .tab-btn {
                padding: 0.75rem 0.5rem;
                font-size: 0.9rem;
                gap: 0.25rem;
            }
        }
    </style>
</head>
<body>
        @include("sidenav")

            <main class="main-content">
                <!-- Alerts -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-title">
                        <i class="bi bi-bar-chart-line-fill"></i>
                        {{ __('messages.sales_report') }}
                    </div>
                    <div class="date-filter">
                        <label for="reportMonth"><i class="bi bi-calendar-week"></i> {{ __('messages.month') }}</label>
                        <input type="month" id="reportMonth" class="form-control" value="{{ isset($monthParam) ? $monthParam : date('Y-m') }}">
                    </div>
                    @if(isset($allShops) && $allShops->count() > 0)
                    <div class="date-filter">
                        <label for="shopSelect"><i class="bi bi-shop"></i> {{ __('messages.shop') }}</label>
                        <select id="shopSelect" class="form-control">
                            @foreach($allShops as $shop)
                                <option value="{{ $shop->id }}" {{ (session('selected_shop_id') == $shop->id) ? 'selected' : '' }}>
                                    {{ $shop->name }} ({{ $shop->location ?? __('messages.na') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>

                <!-- Tab Navigation -->
                <div class="tab-navigation">
                    <div class="tab-buttons">
                        <button class="tab-btn" data-tab="cash-report">
                            <i class="bi bi-calendar-day"></i>
                            {{ __('messages.cash_report') }}
                            <span class="tab-btn-count">{{ $report->count() }}</span>
                        </button>
                        <button class="tab-btn active" data-tab="daily-report">
                            <i class="bi bi-calendar-day"></i>
                            {{ __('messages.daily_report') }}
                            <span class="tab-btn-count">{{ $report->count() }}</span>
                            
                        </button>
                        @if(isset($shopReport))
                        <button class="tab-btn" data-tab="shop-report">
                            <i class="bi bi-shop"></i>
                            {{ __('messages.shop_report') }}
                            <span class="tab-btn-count">{{ $shopReport->count() }}</span>
                        </button>
                        @endif
                    </div>
                </div>

                @php
                    $totalProfit = $report->sum(function($row) {
                        return ($row->Mcash_sales + ($row->paidInvoices ?? 0)) - ($row->Mexpenses - ($row->Mreturn ?? 0));
                    });
                    $avgProfit = $report->avg(function($row) {
                        return ($row->Mcash_sales + ($row->paidInvoices ?? 0)) - ($row->Mexpenses - ($row->Mreturn ?? 0));
                    });
                @endphp

                <!-- Tab 0: Cash Submit Report -->
                <div class="tab-content" id="cash-report">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-title">
                                <i class="bi bi-table"></i>
                                {{ __('messages.cash_report') }} - {{ date('F Y', strtotime(isset($monthParam) ? $monthParam . '-01' : now())) }}
                            </div>
                            <div class="table-controls">
                                <div class="date-filter">
                                    <label for="filterDateCash"><i class="bi bi-filter"></i> {{ __('messages.filter_by_date') }}</label>
                                    <input type="date" id="filterDateCash" class="form-control">
                                </div>
                                <button class="action-btn details" onclick="exportTable()">
                                    <i class="bi bi-download"></i> {{ __('messages.export') }}
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            @if($report->isEmpty())
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>
                                    <h4 class="empty-state-title">{{ __('messages.no_data_available') }}</h4>
                                    <p class="empty-state-text">{{ __('messages.no_sales_report_data') }}</p>
                                </div>
                            @else
                                <table class="table" id="cashReportTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('messages.date') }}</th>                                            
                                            <th>{{ __('messages.cash_sale') }}</th>
                                            <th>{{ __('messages.credit_sale') }}</th>
                                            <th>{{ __('messages.total_sales') }}</th>
                                            <th title="{{ __('messages.discount') }}">{{ __('messages.discount') }}</th>
                                            <th title="{{ __('messages.price_increase') }}">{{ __('messages.price_increase') }}</th>
                                            <th title="{{ __('messages.paid_invoice') }}">{{ __('messages.paid_invoice') }}</th>
                                            <th title="{{ __('messages.profit_loss') }}">{{ __('messages.profit_loss') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($report as $index => $row)
                                            @php
                                                $profit = $row->Mcash_sales - $row->Mexpenses;
                                                $profitClass = $profit > 0 ? 'profit-positive' : ($profit < 0 ? 'profit-negative' : 'profit-neutral');
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ date('M d, Y', strtotime($row->report_date)) }}</strong><br>
                                                    <small class="text-muted">{{ date('l', strtotime($row->report_date)) }}</small>
                                                </td>
                                                <td>{{ number_format($row->Mcash_sales) }}</td>
                                                <td>{{ number_format($row->Mcredit_sales) }}</td>
                                                <td>{{ number_format($row->Msales) }}</td>
                                                <td>{{ number_format($row->Mdisc) }}</td>
                                                <td>{{ number_format($row->MdiscIncrease ?? 0) }}</td>
                                                <td>{{ number_format($row->paidInvoices) }}</td>
                                                <td class="profit-cell {{ $profitClass }}">
                                                    {{ number_format($profit) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    @if($report->isNotEmpty())
                                        <tfoot>
                                            <tr style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                                                <td colspan="2" class="text-end fw-bold">{{ __('messages.total') }}:</td>                                                
                                                <td class="fw-bold">{{ number_format($report->sum('Mcash_sales')) }}</td>
                                                <td class="fw-bold">{{ number_format($report->sum('Mcredit_sales')) }}</td>
                                                <td class="fw-bold">{{ number_format($report->sum('Msales')) }}</td>
                                                <td class="fw-bold">{{ number_format($report->sum('Mdisc')) }}</td>
                                                <td class="fw-bold">{{ number_format($report->sum('MdiscIncrease') ?? 0) }}</td>
                                                <td class="fw-bold">{{ number_format($report->sum('paidInvoices')) }}</td>
                                                <td class="fw-bold {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($totalProfit) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tab 1: Daily Report (was Cash Submit Report) -->
                <div class="tab-content active" id="daily-report">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-title">
                                <i class="bi bi-table"></i>
                                {{ __('messages.daily_report') }} - {{ date('F Y', strtotime(isset($monthParam) ? $monthParam . '-01' : now())) }}
                                @if(isset($selectedShopName))
                                ({{ $selectedShopName }})
                                @endif
                            </div>
                            <div class="table-controls">
                                <div class="date-filter">
                                    <label for="filterDateDaily"><i class="bi bi-filter"></i> {{ __('messages.filter_by_date') }}</label>
                                    <input type="date" id="filterDateDaily" class="form-control">
                                </div>
                                <button class="action-btn details" onclick="exportTable()">
                                    <i class="bi bi-download"></i> {{ __('messages.export') }}
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            @if($report->isEmpty())
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>
                                    <h4 class="empty-state-title">{{ __('messages.no_data_available') }}</h4>
                                    <p class="empty-state-text">{{ __('messages.no_sales_report_data') }}</p>
                                </div>
                            @else
                                <table class="table" id="dailyReportTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('messages.status') }}</th>
                                            <th>{{ __('messages.date') }}</th>
                                            <th title="{{ __('messages.cash_sale') }}">{{ __('messages.cash_sale') }}</th>
                                            <th title="{{ __('messages.credit_sale') }}">{{ __('messages.credit_sale') }}</th>
                                            <th title="{{ __('messages.total_sales') }}">{{ __('messages.total_sales') }}</th>
                                            <th title="{{ __('messages.cash_return') }}">{{ __('messages.cash_return') }}</th>
                                            <th title="{{ __('messages.credit_return') }}">{{ __('messages.credit_return') }}</th>
                                            <th title="{{ __('messages.total_return') }}">{{ __('messages.total_return') }}</th>
                                            <th title="{{ __('messages.discount') }}">{{ __('messages.discount') }}</th>
                                            <th title="{{ __('messages.price_increase') }}">{{ __('messages.price_increase') }}</th>
                                            <th title="{{ __('messages.offered') }}">{{ __('messages.offered') }}</th>
                                            <th title="{{ __('messages.expenses') }}">{{ __('messages.expenses') }}</th>
                                            <th title="{{ __('messages.profit_loss') }}">{{ __('messages.profit_loss') }}</th>
                                            <th title="{{ __('messages.paid_invoice') }}">{{ __('messages.paid_invoice') }}</th>
                                            <th title="{{ __('messages.cash_receivings') }}">{{ __('messages.cash_receivings') }}</th>
                                            <th title="{{ __('messages.credit_receivings') }}">{{ __('messages.credit_receivings') }}</th>
                                            <th title="{{ __('messages.paid_receivings') }}">{{ __('messages.paid_receivings') }}</th>
                                            <th title="{{ __('messages.cash_amount') }}({{ __('messages.cash_sale') }}-{{ __('messages.expenses') }}-{{ __('messages.discount') }}+{{ __('messages.paid_invoice') }}-{{ __('messages.cash_receivings') }}-{{ __('messages.paid_receivings') }})">{{ __('messages.cash_amount') }}</th>
                                            <th title="{{ __('messages.cash_submit') }}">{{ __('messages.cash_submit') }}</th>
                                            <th title="{{ __('messages.bank_deposit') }}">{{ __('messages.bank_deposit') }}</th>
                                            <th title="{{ __('messages.chip_deposit') }}">{{ __('messages.chip_deposit') }}</th>
                                            <th title="{{ __('messages.chip_used') }}">{{ __('messages.chip_used') }}</th>
                                            <th title="{{ __('messages.bank_difference') }}">{{ __('messages.bank_difference') }}</th>
                                            <th title="{{ __('messages.sales') }} - {{ __('messages.cash_submit') }}">{{ __('messages.cash_amount') }} - {{ __('messages.cash_submit') }}</th>
                                            @if (canUser('manage_full_report'))
                                            <th>{{ __('messages.actions') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($report as $index => $row)
                                            @php
                                                $profit = $row->Mcash_sales - $row->Mexpenses;
                                                $profitClass = $profit > 0 ? 'profit-positive' : ($profit < 0 ? 'profit-negative' : 'profit-neutral');

                                                $submittedCash = $row->submitted_cash ?? 0;
                                                $totalChip = $row->total_chip ?? 0;

                                                $cashAmount = ($row->Mcash_sales - ($row->cash_return ?? 0)) + ($row->paidInvoices ?? 0) + ($row->MdiscIncrease ?? 0) - ($row->Mexpenses ?? 0) - ($row->paid_receivings ?? 0);

                                                $isFullyPaid = $row->Mcash_sales > 0 && $cashAmount > 0 && $submittedCash >= $cashAmount;
                                                $remainingAmount = ($row->Mcash_sales - $row->Mexpenses - $row->receivingsCredit) - $submittedCash;
                                                
                                                // Balance verification
                                                $cashSale = $row->Mcash_sales;
                                                $creditSale = $row->Mcredit_sales;
                                                $totalSale = $row->Msales;
                                                $submittedCash = $row->submitted_cash ?? 0;
                                                
                                                // Check if cash sale + credit sale = total
                                                $salesBalanced = ($cashSale + $creditSale) == $totalSale;
                                                // Check if cash amount = cash submit
                                                $cashBalanced = abs($cashAmount - $submittedCash) < 1;
                                                
                                                $isBalanced = $salesBalanced && $cashBalanced;
                                                $balanceIssues = [];
                                                if (!$salesBalanced) {
                                                    $balanceIssues[] = "Sales: " . number_format($cashSale) . "+" . number_format($creditSale) . "=" . number_format($cashSale + $creditSale) . " ≠ " . number_format($totalSale);
                                                }
                                                if (!$cashBalanced) {
                                                    $balanceIssues[] = "Cash: " . number_format($cashAmount) . " ≠ " . number_format($submittedCash);
                                                }
                                            @endphp
                                            <tr class="{{ !$isBalanced ? 'balance-warning' : '' }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    @if(!$isBalanced)
                                                        <span class="balance-warning-icon" title="{{ implode(' | ', $balanceIssues) }}">
                                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                                        </span>
                                                    @else
                                                        <span class="text-success">
                                                            <i class="bi bi-check-circle-fill"></i>
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ date('M d, Y', strtotime($row->report_date)) }}</strong><br>
                                                    <small class="text-muted">{{ date('l', strtotime($row->report_date)) }}</small>
                                                </td>
                                                <td>{{ number_format($row->Mcash_sales - ($row->Mreturn ?? 0)) }}</td>
                                                <td>{{ number_format($row->Mcredit_sales) }}</td>
                                                <td>{{ number_format($row->Mcash_sales + $row->Mcredit_sales - ($row->Mreturn ?? 0)) }}</td>
                                                <td class="{{ ($row->Mreturn ?? 0) > 0 ? 'text-success' : '' }}">{{ number_format($row->cash_return ?? 0) }}</td>
                                                <td class="text-danger">-{{ number_format($row->credit_return ?? 0) }}</td>
                                                <td class="fw6">{{ number_format($row->total_returned ?? ($row->cash_return ?? 0) + ($row->credit_return ?? 0)) }}</td>
                                                <td>{{ number_format($row->Mdisc) }}</td>
                                                <td>{{ number_format($row->MdiscIncrease ?? 0) }}</td>
                                                <td>{{ number_format($row->Moffered ?? 0) }}</td>
                                                <td>{{ $row->Mexpenses }}</td>
                                                <td>{{ number_format(($row->Mcash_sales + $row->paidInvoices ?? 0) - ($row->Mexpenses - $row->Mreturn ?? 0)) }}</td>
                                                <td>{{ number_format($row->paidInvoices) }}</td>
                                              
                                                <td>{{ number_format($row->receivingsPaid ?? 0) }}</td>
                                                <td>{{ number_format($row->receivingsCredit ?? 0) }}</td>  
                                                <td>{{ number_format($row->paid_receivings ?? 0) }}</td>                                               
                                                <td>
                                                
                                                    {{ number_format($cashAmount) }}</td>
                                                <td>{{ number_format($row->submitted_cash) }}</td>
                                                <td>{{ number_format($row->total_bank) }}</td>
                                                <td>{{ number_format($totalChip) }}</td>
                                                <td>{{ number_format($row->chip_used) }}</td>
                                                <td>
                                                    @if($row->total_bank !== null)
                                                        {{ number_format($row->total_bank - $cashAmount) }}
                                                    @else
                                                        <span class="text-secondary">{{ __('messages.na') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($row->submitted_cash < $row->Mcash_sales) 
                                                        <span class="text-danger">
                                                             {{ number_format(($row->submitted_cash ?? 0) - ($cashAmount)) }}
                                                        </span>
                                                    @else
                                                        <span class="text-success">
                                                            {{ number_format(($row->submitted_cash ?? 0) - ($cashAmount)) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                @if (canUser('manage_full_report'))
                                                <td>
                                                    @if(($row->Msales + $row->paidInvoices) == 0)
                                                        <span class="text-secondary">
                                                            {{ __('messages.na') }}
                                                        </span>
                                                    @elseif($isFullyPaid)
                                                        <span class="text-primary border border-primary p-2">
                                                            <i class="bi bi-check-circle"></i> {{ __('messages.submitted') }}
                                                        </span>
                                                    @else
                                                        <button type="button" 
                                                                class="action-btn cash-submit-btn {{ $submittedCash > 0 ? 'partial-payment' : '' }}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#cashSubmitModal"
                                                                data-date="{{ $row->report_date }}"
                                                                data-sales="{{ $row->Msales }}"
                                                                data-paid="{{ $row->Mcash_sales }}"
                                                                data-credit="{{ $row->Mcredit_sales }}"
                                                                data-shop-id="{{ $row->shop_id }}"
                                                                data-expenses="{{ $row->Mexpenses }}"
                                                                data-receivings="{{ $row->receivingsCredit }}"
                                                                data-expected="{{ $row->Mcash_sales - $row->Mexpenses - $row->receivingsCredit }}"
                                                                data-previous="{{ $submittedCash }}"
                                                                data-remaining="{{ ($row->Mcash_sales - $row->Mexpenses - $row->receivingsCredit) - $submittedCash }}"
                                                                title="{{ $submittedCash > 0 ? __('messages.remaining') . ' '.number_format(($row->Mcash_sales - $row->Mexpenses - $row->receivingsCredit) - $submittedCash) : __('messages.submit_cash') }}">
                                                            <i class="bi bi-cash-coin"></i> 
                                                            @if(($submittedCash - $cashAmount) > 0)
                                                                {{ __('messages.resubmit') }} ({{ number_format(($submittedCash) - ($cashAmount)) }})
                                                            @else
                                                                {{ __('messages.cash_submit') }}
                                                            @endif
                                                        </button>
                                                    @endif
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    @if($report->isNotEmpty())
                                        @php
                                            $tfootCashSales = $report->sum('Mcash_sales');
                                            $tfootCreditSales = $report->sum('Mcredit_sales');
                                            $tfootTotalSales = $report->sum('Msales');
                                            $tfootCashReturn = $report->sum('cash_return') ?? 0;
                                            $tfootCreditReturn = $report->sum('credit_return') ?? 0;
                                            $tfootDiscount = $report->sum('Mdisc');
                                            $tfootIncrease = $report->sum('MdiscIncrease') ?? 0;
                                            $tfootOffered = $report->sum('Moffered') ?? 0;
                                            $tfootExpenses = $report->sum('Mexpenses');
                                            $tfootPaidInvoices = $report->sum('paidInvoices');
                                            $tfootReceivingsPaid = $report->sum('receivingsPaid');
                                            $tfootReceivingsCredit = $report->sum('receivingsCredit');
                                            $tfootPaidReceivings = $report->sum('paid_receivings');
                                            $tfootCashAmount = $tfootCashSales + $tfootPaidInvoices + $tfootIncrease - $tfootDiscount - $tfootCashReturn - $tfootExpenses - $tfootPaidReceivings;
                                            $tfootTotalProfit = $tfootCashSales + $tfootPaidInvoices - ($tfootExpenses - $tfootCashReturn);
                                        @endphp
                                        <tfoot>
                                            <tr style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                                                <td colspan="3" class="text-end fw-bold">{{ __('messages.total') }}:</td>
                                                <td class="fw-bold">{{ number_format($tfootCashSales) }}</td>
                                                <td class="fw-bold">{{ number_format($tfootCreditSales) }}</td>
                                                <td class="fw-bold">{{ number_format($tfootTotalSales) }}</td>
                                                <td class="fw-bold">{{ number_format($tfootCashReturn) }}</td>
                                                <td class="fw-bold text-danger">-{{ number_format($tfootCreditReturn) }}</td>
                                                <td class="fw-bold">{{ number_format($report->sum('total_returned') ?? ($tfootCashReturn + $tfootCreditReturn)) }}</td>
                                                <td class="fw-bold">{{ number_format($tfootDiscount) }}</td>
                                                <td class="fw-bold">{{ number_format($tfootIncrease) }}</td>
                                                <td class="fw-bold">{{ number_format($tfootOffered) }}</td>
                                                <td class="fw-bold">{{ number_format($tfootExpenses) }}</td>
                                                <td class="fw-bold">{{ number_format($tfootPaidInvoices) }}</td>
                                                <td class="fw-bold {{ $tfootTotalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($tfootTotalProfit) }}
                                                </td>
                                                <td class="fw-bold">{{ number_format($tfootReceivingsPaid) }}</td>
                                                <td class="fw-bold">{{ number_format($tfootReceivingsCredit) }}</td>
                                                <td class="fw-bold">{{ number_format($tfootPaidReceivings) }}</td>
                                                <td class="fw-bold">{{ number_format($tfootCashAmount) }}</td>
                                                <td class="fw-bold">{{ number_format($report->sum('submitted_cash')) }}</td>
                                                <td class="fw-bold">{{ number_format($tfootCashAmount - $report->sum('submitted_cash')) }}</td>
                                                <td class="fw-bold">{{ number_format($report->sum('total_bank')) }}</td>
                                                <td class="fw-bold">{{ number_format($report->sum('total_chip')) }}</td>
                                                <td class="fw-bold">{{ number_format($report->sum('chip_used')) }}</td>
                                                <td class="fw-bold">{{ number_format($report->sum('total_bank') - $tfootCashAmount) }}</td>
                                                <td class="fw-bold">{{ number_format($report->sum('submitted_cash') - $tfootCashAmount) }}</td>
                                                @if (canUser('manage_full_report'))
                                                <td></td>
                                                @endif
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Shop Report -->
                @if(isset($shopReport))
                <div class="tab-content" id="shop-report">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-title">
                                <i class="bi bi-shop"></i>
                                {{ __('messages.shop_report') }} - {{ date('F Y', strtotime(isset($monthParam) ? $monthParam . '-01' : now())) }}
                            </div>
                            <div class="table-controls">
                                <div class="date-filter">
                                    <label for="filterShopDate"><i class="bi bi-filter"></i> {{ __('messages.filter_by_date') }}</label>
                                    <input type="date" id="filterShopDate" class="form-control">
                                </div>
                                <button class="action-btn cash-submit-btn" onclick="filterShopByDate()">
                                    <i class="bi bi-search"></i> {{ __('messages.filter') }}
                                </button>
                                <button class="action-btn details" onclick="exportShopReport()">
                                    <i class="bi bi-download"></i> {{ __('messages.export') }}
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            @if($shopReport->isEmpty())
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="bi bi-shop"></i>
                                    </div>
                                    <h4 class="empty-state-title">{{ __('messages.no_shop_data_available') }}</h4>
                                    <p class="empty-state-text">{{ __('messages.no_shop_sales_data') }}</p>
                                </div>
                            @else
                                <table class="table" id="shopReportTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('messages.date') }}</th>
                                            <th>{{ __('messages.shop_name') }}</th>
                                            <th>{{ __('messages.location') }}</th>
                                            <th>{{ __('messages.total_sales') }}</th>
                                            <th>{{ __('messages.return') }}</th>
                                            <th>{{ __('messages.transactions') }}</th>
                                            <th>{{ __('messages.paid') }}</th>
                                            <th>{{ __('messages.credit') }}</th>
                                            <th>{{ __('messages.discount') }}</th>
                                            <th>{{ __('messages.debt') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $rowIndex = 1; @endphp
                                        @foreach ($shopReport as $shop)
                                            <tr class="shop-sales-row" data-date="{{ $shop->sale_date }}" data-shop="{{ strtolower($shop->shop_name) }}">
                                                <td>{{ $rowIndex++ }}</td>
                                                <td>
                                                    <strong>{{ date('M d, Y', strtotime($shop->sale_date)) }}</strong><br>
                                                    <small class="text-muted">{{ date('l', strtotime($shop->sale_date)) }}</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="shop-avatar" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                                            {{ strtoupper(substr($shop->shop_name, 0, 1)) }}
                                                        </div>
                                                        <span>{{ $shop->shop_name }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $shop->location ?? __('messages.na') }}</td>
                                                <td>Tsh. {{ number_format($shop->total_sales) }}</td>
                                                <td>Tsh. {{ number_format($shop->total_return ?? 0) }}</td>
                                                <td>{{ number_format($shop->total_transactions) }}</td>
                                                <td>Tsh. {{ number_format($shop->total_cash) }}</td>
                                                <td>Tsh. {{ number_format($shop->total_credit) }}</td>
                                                <td>Tsh. {{ number_format($shop->total_discount) }}</td>
                                                <td>Tsh. {{ number_format($shop->total_debt) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    @if($shopReport->isNotEmpty())
                                        <tfoot>
                                            <tr style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                                                <td colspan="4" class="text-end fw-bold">{{ __('messages.total') }}:</td>
                                                <td class="fw-bold">Tsh. {{ number_format($shopReport->sum('total_sales')) }}</td>
                                                <td class="fw-bold">Tsh. {{ number_format($shopReport->sum('total_return') ?? 0) }}</td>
                                                <td class="fw-bold">{{ number_format($shopReport->sum('total_transactions')) }}</td>
                                                <td class="fw-bold">Tsh. {{ number_format($shopReport->sum('total_cash')) }}</td>
                                                <td class="fw-bold">Tsh. {{ number_format($shopReport->sum('total_credit')) }}</td>
                                                <td class="fw-bold">Tsh. {{ number_format($shopReport->sum('total_discount')) }}</td>
                                                <td class="fw-bold">Tsh. {{ number_format($shopReport->sum('total_debt')) }}</td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

            </main>

    <!-- Cash Submit Modal -->
    <div class="modal fade" id="cashSubmitModal" tabindex="-1" aria-labelledby="cashSubmitModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="cashSubmitModalLabel">
                        <i class="bi bi-cash-stack me-2"></i>
                        {{ __('messages.cash_submit') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="cashSubmit" id="cashSubmitForm">
                    @csrf
                    <div class="modal-body">
                        <!-- Date Display -->
                        <div class="alert alert-info d-flex align-items-center mb-4">
                            <i class="bi bi-calendar-check fs-4 me-3"></i>
                            <div>
                                <strong>{{ __('messages.date') }}:</strong> 
                                <span id="modalDate" class="ms-2"></span>
                            </div>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body p-3">
                                        <small class="text-muted d-block mb-1">{{ __('messages.total_sales_paid') }}</small>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-graph-up-arrow text-success me-2"></i>
                                            <strong id="modalSales" class="fs-5">0</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body p-3">
                                        <small class="text-muted d-block mb-1">{{ __('messages.credit_sales') }}</small>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-credit-card text-warning me-2"></i>
                                            <strong id="modalCredit" class="fs-5">0</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body p-3">
                                        <small class="text-muted d-block mb-1">{{ __('messages.expenses') }}</small>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-cart-dash text-danger me-2"></i>
                                            <strong id="modalExpenses" class="fs-5">0</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body p-3">
                                        <small class="text-muted d-block mb-1">{{ __('messages.receivings_paid') }}</small>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-wallet2 text-info me-2"></i>
                                            <strong id="modalReceivings" class="fs-5">0</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Expected Cash Calculation -->
                        <div class="card border-success mb-4">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-3 text-success">
                                    <i class="bi bi-calculator me-2"></i>
                                    {{ __('messages.expected_cash_calculation') }}
                                </h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ __('messages.paid_sales_minus_credit_minus_expenses_plus_receivings') }}</span>
                                    <strong id="expectedCash" class="text-success">0</strong>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">{{ __('messages.cash_to_submit') }}</span>
                                    <span class="fw-bold text-success" id="cashToSubmit">0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Previously Submitted Cash (if any) -->
                        <div class="form-group mb-3" id="previousSubmissionDiv" style="display: none;">
                            <label class="form-label fw-bold text-warning">
                                <i class="bi bi-clock-history me-2"></i>
                                {{ __('messages.previously_submitted') }}
                            </label>
                            <div class="alert alert-warning py-2" id="previousSubmissionAmount"></div>
                        </div>

                        <!-- Cash Input Field -->
                        <div class="form-group mb-3">
                            <label for="submitted_cash" class="form-label fw-bold">
                                <i class="bi bi-cash me-2"></i>
                                {{ __('messages.enter_submitted_cash_amount') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Tsh</span>
                                <input type="number" 
                                       class="form-control form-control-lg" 
                                       id="submitted_cash" 
                                       name="submitted_cash" 
                                       step="0.01" 
                                       min="0" 
                                       required
                                       placeholder="{{ __('messages.enter_amount') }}">
                                <span class="input-group-text bg-light">.00</span>
                            </div>
                            <div class="form-text text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                {{ __('messages.enter_actual_cash_amount') }}
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="">{{ __('messages.submit_date') }}</label>
                            <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">   
                        </div>
                        
                        <!-- Hidden Inputs -->
                        <input type="hidden" name="date" id="hiddenDate">
                        <input type="hidden" name="sales" id="hiddenSales">
                        <input type="hidden" name="shop_id" id="hiddenShopId">
                        <input type="hidden" name="credit" id="hiddenCredit">
                        <input type="hidden" name="expenses" id="hiddenExpenses">
                        <input type="hidden" name="receivings" id="hiddenReceivings">
                        <input type="hidden" name="expected_cash" id="hiddenExpectedCash">
                        <input type="hidden" name="previous_submitted" id="hiddenPreviousSubmitted" value="0">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit" class="btn btn-success" id="submitCashBtn">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ __('messages.cash_submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Success/Error Toast Notifications -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ __('messages.cash_submitted_successfully') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        
        <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ __('messages.error_submitting_cash') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- JavaScript for Tabs and Interactions -->
    <script>
        // Tab persistence using localStorage
        const TAB_STORAGE_KEY = 'salesReportActiveTab';

        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing tabs...');

            // 1. TAB NAVIGATION
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            // Handle Cash Submit Modal - populate from data attributes
            const cashSubmitModal = document.getElementById('cashSubmitModal');
            if (cashSubmitModal) {
                cashSubmitModal.addEventListener('show.bs.modal', function(e) {
                    const button = e.relatedTarget;
                    if (!button || !button.classList.contains('cash-submit-btn')) return;
                    
                    // Get all data from button attributes
                    const date = button.getAttribute('data-date');
                    const sales = parseFloat(button.getAttribute('data-sales')) || 0;
                    const paid = parseFloat(button.getAttribute('data-paid')) || 0;
                    const shopId = button.getAttribute('data-shop-id');
                    const credit = parseFloat(button.getAttribute('data-credit')) || 0;
                    const expenses = parseFloat(button.getAttribute('data-expenses')) || 0;
                    const receivings = parseFloat(button.getAttribute('data-receivings')) || 0;
                    const expectedCash = parseFloat(button.getAttribute('data-expected')) || 0;
                    const previousSubmitted = parseFloat(button.getAttribute('data-previous')) || 0;
                    const remaining = parseFloat(button.getAttribute('data-remaining')) || 0;
                    
                    // Format numbers for display
                    const formatNumber = (num) => {
                        return new Intl.NumberFormat('en-US', { 
                            minimumFractionDigits: 0, 
                            maximumFractionDigits: 0 
                        }).format(num);
                    };
                    
                    // Update modal fields
                    document.getElementById('modalDate').textContent = new Date(date).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    
                    document.getElementById('modalSales').textContent = formatNumber(sales);
                    document.getElementById('modalCredit').textContent = formatNumber(credit);
                    document.getElementById('modalExpenses').textContent = formatNumber(expenses);
                    document.getElementById('modalReceivings').textContent = formatNumber(receivings);
                    document.getElementById('expectedCash').textContent = formatNumber(expectedCash);
                    document.getElementById('cashToSubmit').textContent = formatNumber(expectedCash);
                    
                    // Set hidden inputs
                    document.getElementById('hiddenDate').value = date;
                    document.getElementById('hiddenSales').value = sales;
                    document.getElementById('hiddenShopId').value = shopId;
                    document.getElementById('hiddenCredit').value = credit;
                    document.getElementById('hiddenExpenses').value = expenses;
                    document.getElementById('hiddenReceivings').value = receivings;
                    document.getElementById('hiddenExpectedCash').value = expectedCash;
                    document.getElementById('hiddenPreviousSubmitted').value = previousSubmitted;
                    
                    // Set default cash input to expected amount, but for partial payments, show remaining
                    document.getElementById('submitted_cash').value = previousSubmitted > 0 ? remaining : expectedCash;
                    
                    // Show/hide previous submission info
                    const previousDiv = document.getElementById('previousSubmissionDiv');
                    const previousAmount = document.getElementById('previousSubmissionAmount');
                    if (previousSubmitted > 0) {
                        previousDiv.style.display = 'block';
                        previousAmount.textContent = `Previously submitted: Tsh. ${formatNumber(previousSubmitted)}`;
                    } else {
                        previousDiv.style.display = 'none';
                    }
                });
            }

            // Add input validation for cash amount
            const submittedCashInput = document.getElementById('submitted_cash');
            if (submittedCashInput) {
                submittedCashInput.addEventListener('input', function() {
                    const expected = parseFloat(document.getElementById('hiddenExpectedCash').value) || 0;
                    const submitted = parseFloat(this.value) || 0;
                    const difference = submitted - expected;
                    
                    // Show warning if amount differs significantly
                    const parentDiv = this.closest('.form-group');
                    let warningDiv = parentDiv.querySelector('#cashWarning');
                    
                    if (Math.abs(difference) > 0.01) {
                        if (!warningDiv) {
                            warningDiv = document.createElement('div');
                            warningDiv.id = 'cashWarning';
                            parentDiv.appendChild(warningDiv);
                        }
                        warningDiv.className = `alert ${difference > 0 ? 'alert-warning' : 'alert-danger'} mt-2 p-2`;
                        warningDiv.innerHTML = `
                            <i class="bi ${difference > 0 ? 'bi-exclamation-triangle' : 'bi-exclamation-octagon'} me-2"></i>
                            ${difference > 0 ? 'Excess' : 'Short'} amount: ${Math.abs(difference).toLocaleString()}
                        `;
                    } else if (warningDiv) {
                        warningDiv.remove();
                    }
                });
            }
            
            // Function to switch tabs
            function switchTab(tabId) {
                console.log('Switching to tab:', tabId);
                
                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                });
                tabContents.forEach(content => {
                    content.classList.remove('active');
                });
                
                // Add active class to clicked button
                const activeButton = document.querySelector(`[data-tab="${tabId}"]`);
                if (activeButton) {
                    activeButton.classList.add('active');
                }
                
                // Show corresponding content
                const activeContent = document.getElementById(tabId);
                if (activeContent) {
                    activeContent.classList.add('active');
                } else {
                    console.error('Tab content not found:', tabId);
                }
                
                // Save active tab to localStorage
                localStorage.setItem(TAB_STORAGE_KEY, tabId);
            }

            // Add click event to all tab buttons
            tabButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);
                });
            });

            // 2. RESTORE SAVED TAB ON PAGE LOAD
            const savedTab = localStorage.getItem(TAB_STORAGE_KEY);
            if (savedTab) {
                console.log('Restoring saved tab:', savedTab);
                
                const savedTabElement = document.getElementById(savedTab);
                if (savedTabElement) {
                    switchTab(savedTab);
                } else {
                    console.log('Saved tab not found, using default');
                    if (tabButtons.length > 0) {
                        const defaultTabId = tabButtons[0].getAttribute('data-tab');
                        localStorage.setItem(TAB_STORAGE_KEY, defaultTabId);
                    }
                }
            }

            // 3. MONTH SELECTOR - RELOAD PAGE WITH NEW MONTH
            const monthInput = document.getElementById('reportMonth');
            if (monthInput) {
                // Set default value if empty
                if (!monthInput.value) {
                    const today = new Date();
                    const currentMonth = today.toISOString().split('T')[0].substring(0, 7);
                    monthInput.value = currentMonth;
                }
                
                monthInput.addEventListener('change', function() {
                    const selectedMonth = this.value;
                    if (selectedMonth) {
                        const currentUrl = window.location.href;
                        const url = new URL(currentUrl);
                        url.searchParams.set('month', selectedMonth);
                        window.location.href = url.toString();
                    }
                });
            }

            // 3b. SHOP SELECTOR - RELOAD PAGE WITH NEW SHOP
            const shopSelect = document.getElementById('shopSelect');
            if (shopSelect) {
                shopSelect.addEventListener('change', function() {
                    const selectedShopId = this.value;
                    if (selectedShopId) {
                        const currentUrl = window.location.href;
                        const url = new URL(currentUrl);
                        url.searchParams.set('shop_id', selectedShopId);
                        window.location.href = url.toString();
                    }
                });
            }

            // 4. CASH REPORT DATE FILTER
            const filterDateCashInput = document.getElementById('filterDateCash');
            if (filterDateCashInput) {
                filterDateCashInput.addEventListener('change', function() {
                    const filterDate = this.value;
                    const rows = document.querySelectorAll('#cashReportTable tbody tr');

                    let visibleRows = 0;
                    rows.forEach(row => {
                        const dateCell = row.querySelector('td:nth-child(2)');
                        if (!dateCell) return;

                        const rowText = dateCell.textContent;
                        if (filterDate) {
                            const filterDateObj = new Date(filterDate);
                            const filterDateStr = filterDateObj.toLocaleDateString('en-US', {
                                month: 'short', 
                                day: '2-digit', 
                                year: 'numeric'
                            });

                            if (rowText.includes(filterDateStr)) {
                                row.style.display = '';
                                visibleRows++;
                            } else {
                                row.style.display = 'none';
                            }
                        } else {
                            row.style.display = '';
                            visibleRows++;
                        }
                    });

                    if (visibleRows === 0 && filterDate) {
                        showNoResultsMessage('cashReportTable', filterDate);
                    } else {
                        removeNoResultsMessage('cashReportTable');
                    }
                });

                filterDateCashInput.addEventListener('dblclick', function() {
                    this.value = '';
                    document.querySelectorAll('#cashReportTable tbody tr').forEach(row => {
                        row.style.display = '';
                    });
                    removeNoResultsMessage('cashReportTable');
                });
            }

            // 5. DAILY REPORT DATE FILTER
            const filterDateDailyInput = document.getElementById('filterDateDaily');
            if (filterDateDailyInput) {
                filterDateDailyInput.addEventListener('change', function() {
                    const filterDate = this.value;
                    const rows = document.querySelectorAll('#dailyReportTable tbody tr');

                    let visibleRows = 0;
                    rows.forEach(row => {
                        const dateCell = row.querySelector('td:nth-child(2)');
                        if (!dateCell) return;

                        const rowText = dateCell.textContent;
                        if (filterDate) {
                            const filterDateObj = new Date(filterDate);
                            const filterDateStr = filterDateObj.toLocaleDateString('en-US', {
                                month: 'short', 
                                day: '2-digit', 
                                year: 'numeric'
                            });

                            if (rowText.includes(filterDateStr)) {
                                row.style.display = '';
                                visibleRows++;
                            } else {
                                row.style.display = 'none';
                            }
                        } else {
                            row.style.display = '';
                            visibleRows++;
                        }
                    });

                    if (visibleRows === 0 && filterDate) {
                        showNoResultsMessage('dailyReportTable', filterDate);
                    } else {
                        removeNoResultsMessage('dailyReportTable');
                    }
                });

                filterDateDailyInput.addEventListener('dblclick', function() {
                    this.value = '';
                    document.querySelectorAll('#dailyReportTable tbody tr').forEach(row => {
                        row.style.display = '';
                    });
                    removeNoResultsMessage('dailyReportTable');
                });
            }

            // 6. SHOP REPORT DATE FILTER
            window.filterShopByDate = function() {
                const filterDate = document.getElementById('filterShopDate').value;
                const rows = document.querySelectorAll('#shopReportTable tbody tr.shop-sales-row');

                let visibleRows = 0;
                rows.forEach(row => {
                    const dateCell = row.querySelector('td:nth-child(2)');
                    if (!dateCell) return;

                    const rowText = dateCell.textContent;
                    if (filterDate) {
                        const filterDateObj = new Date(filterDate);
                        const filterDateStr = filterDateObj.toLocaleDateString('en-US', {
                            month: 'short',
                            day: '2-digit',
                            year: 'numeric'
                        });

                        if (rowText.includes(filterDateStr)) {
                            row.style.display = '';
                            visibleRows++;
                        } else {
                            row.style.display = 'none';
                        }
                    } else {
                        row.style.display = '';
                        visibleRows++;
                    }
                });

                if (visibleRows === 0 && filterDate) {
                    showNoResultsMessage('shopReportTable', filterDate);
                } else {
                    removeNoResultsMessage('shopReportTable');
                }
            };

            // Clear filter on double-click
            const filterShopDateInput = document.getElementById('filterShopDate');
            if (filterShopDateInput) {
                filterShopDateInput.addEventListener('dblclick', function() {
                    this.value = '';
                    document.querySelectorAll('#shopReportTable tbody tr.shop-sales-row').forEach(row => {
                        row.style.display = '';
                    });
                    removeNoResultsMessage('shopReportTable');
                });
            }


            // Helper functions
            function showNoResultsMessage(tableId, filterValue) {
                const table = document.getElementById(tableId);
                if (!table) return;
                
                let message = table.querySelector('.no-results-message');
                if (!message) {
                    message = document.createElement('div');
                    message.className = 'no-results-message text-center p-4 text-muted';
                    message.innerHTML = `<i class="bi bi-calendar-x"></i> {{ __('messages.no_results_found_for') }} ${filterValue}`;
                    
                    const tbody = table.querySelector('tbody');
                    if (tbody) {
                        tbody.parentNode.insertBefore(message, tbody.nextSibling);
                    }
                }
            }
            
            function removeNoResultsMessage(tableId) {
                const table = document.getElementById(tableId);
                if (table) {
                    const message = table.querySelector('.no-results-message');
                    if (message) {
                        message.remove();
                    }
                }
            }

            // 8. EXPORT FUNCTIONALITY
            window.exportTable = function() {
                const activeTab = localStorage.getItem(TAB_STORAGE_KEY) || 'daily-report';
                let tableToExport;
                let fileName = '';
                
                switch(activeTab) {
                    case 'cash-report':
                        tableToExport = document.getElementById('cashReportTable');
                        fileName = 'cash_report';
                        break;
                    case 'daily-report':
                        tableToExport = document.getElementById('dailyReportTable');
                        fileName = 'daily_sales_report';
                        break;
                    case 'shop-report':
                        tableToExport = document.getElementById('shopReportTable');
                        fileName = 'shop_report';
                        break;
                    case 'staff-performance':
                        tableToExport = document.getElementById('staffPerformanceTable');
                        fileName = 'staff_performance_report';
                        break;
                    case 'staff-sales-by-date':
                        tableToExport = document.getElementById('staffSalesByDateTable');
                        fileName = 'staff_sales_by_date';
                        break;
                    default:
                        tableToExport = document.querySelector('.table');
                        fileName = 'sales_report';
                }
                
                if (!tableToExport) {
                    alert('{{ __('messages.no_table_to_export') }}');
                    return;
                }

                exportToCSV(tableToExport, fileName);
            };
            
            window.exportShopReport = function() {
                const tableToExport = document.getElementById('shopReportTable');
                if (!tableToExport) {
                    alert('{{ __('messages.no_table_to_export') }}');
                    return;
                }
                exportToCSV(tableToExport, 'shop_report');
            };

            function exportToCSV(table, baseFileName) {
                let csv = [];
                const rows = table.querySelectorAll('tr');
                
                rows.forEach(row => {
                    const rowData = [];
                    const cells = row.querySelectorAll('th, td');
                    
                    cells.forEach(cell => {
                        let cellText = cell.innerText
                            .replace(/\n/g, ' ')
                            .replace(/\s+/g, ' ')
                            .trim();
                        cellText = cellText.replace(/"/g, '""');
                        rowData.push(`"${cellText}"`);
                    });
                    
                    csv.push(rowData.join(','));
                });

                const csvContent = csv.join('\n');
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.setAttribute('href', url);
                link.setAttribute('download', `${baseFileName}_${new Date().toISOString().split('T')[0]}.csv`);
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            }

            // 9. KEYBOARD SHORTCUTS
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey) {
                    const key = e.key;
                    let tabId = '';
                    
                    switch(key) {
                        case '1':
                            tabId = 'cash-report';
                            break;
                        case '2':
                            tabId = 'daily-report';
                            break;
                        case '3':
                            tabId = 'shop-report';
                            break;
                        default:
                            return;
                    }
                    
                    e.preventDefault();
                    switchTab(tabId);
                }
            });

            console.log('Tabs initialized successfully');
        });
    </script>

    <!-- Additional CSS for better UI -->
    <style>
        .tab-content {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        
        .tab-content:not(.active) {
            display: none !important;
        }
        
        .tab-content.active {
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .no-results-message {
            background: #f8f9fa;
            border-radius: 8px;
            margin: 1rem 0;
            padding: 2rem;
            font-size: 1.1rem;
        }
        
        .cash-submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .partial-payment {
            background: linear-gradient(135deg, var(--warning-color), #f1c40f) !important;
            border-color: var(--warning-color) !important;
            color: white !important;
        }

        .partial-payment:hover {
            background: linear-gradient(135deg, #e67e22, #f39c12) !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.3);
        }
        
        .tab-btn {
            position: relative;
            overflow: hidden;
        }
        
        .tab-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--secondary-color);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .tab-btn.active::after {
            transform: translateX(0);
        }
        
        .badge {
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.3rem 0.6rem;
        }
        
        .summary-stats {
            background: linear-gradient(135deg, #34495e, #2c3e50);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .summary-stats div {
            text-align: center;
        }
        
        .summary-stats div div:first-child {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }
        
        .summary-stats div div:last-child {
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        @media (max-width: 768px) {
            .summary-stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            
            .summary-stats div div:last-child {
                font-size: 1.4rem;
            }
        }
    </style>
</body>
</html>
