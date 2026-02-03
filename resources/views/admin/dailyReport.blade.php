<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report Dashboard</title>
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

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
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
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-left: 5px solid var(--secondary-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-title i {
            color: var(--secondary-color);
            font-size: 1.5rem;
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
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f2f6;
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
        }

        .table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead {
            background: #34495e;
            position: sticky;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f2f6;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.002);
        }

        .table tbody td {
            padding: 0.25rem 1.5rem;
            vertical-align: middle;
            border: none;
            font-weight: 500;
            color: #2c3e50;
            border-bottom: 1px solid #f1f2f6;
        }
        .table tbody td:hover {
            background: #04498f;
            transform: scale(1.002);
            color: white;
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

        .action-btn.cash-submit {
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
            border-color: var(--success-color);
            color: white;
        }

        .action-btn.cash-submit:hover {
            background: linear-gradient(135deg, #219653, #27ae60);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
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

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            transform: translateY(-20px);
            opacity: 0;
            transition: all 0.3s ease 0.1s;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0);
            opacity: 1;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.4rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label i {
            color: var(--secondary-color);
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--success-color);
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
            outline: none;
        }

        .readonly-field {
            background: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
            cursor: not-allowed;
        }

        .amount-display {
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            color: var(--success-color);
            margin: 1rem 0;
            padding: 1rem;
            background: rgba(39, 174, 96, 0.1);
            border-radius: 10px;
            border: 2px dashed rgba(39, 174, 96, 0.3);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            flex: 1;
            padding: 0.875rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #219653, #27ae60);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(39, 174, 96, 0.4);
        }

        /* Date Info */
        .date-info {
            background: rgba(52, 152, 219, 0.1);
            border: 2px solid rgba(52, 152, 219, 0.2);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .date-info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .date-info-item:last-child {
            margin-bottom: 0;
        }

        .date-label {
            color: #7f8c8d;
            font-weight: 600;
        }

        .date-value {
            color: var(--dark-color);
            font-weight: 700;
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

        /* Responsive Design */
        @media (max-width: 992px) {
            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .table-controls {
                width: 100%;
                justify-content: space-between;
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
                padding: 1rem;
            }
            
            .action-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }
            
            .modal-content {
                width: 95%;
            }
        }

        @media (max-width: 576px) {
            .date-filter {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @include("admin/sidenav")

            <main class="col-md-9 ms-sm-auto col-lg-10 px-4 py-3">
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
                <!-- Page Header
                <div class="page-header">
                    <div class="page-title">
                        <i class="bi bi-bar-chart-line-fill"></i>
                        Sales Report Dashboard
                    </div>
                    <div class="date-filter">
                        <label for="reportDate"><i class="bi bi-calendar-week"></i> Report Date:</label>
                        <input type="date" id="reportDate" class="form-control">
                    </div>
                </div> -->

                <!-- Stats Summary 
                <div class="stats-container">
                    <div class="stat-card sales">
                        <div class="stat-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-info">
                                <h3>Total Sales</h3>
                                <div class="stat-value">Tsh. {{ number_format($report->sum('Msales')) }}</div>
                            </div>
                            <span class="stat-change positive">+{{ number_format($report->avg('Msales')) }}/day</span>
                        </div>
                    </div>

                    <div class="stat-card credit">
                        <div class="stat-icon">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-info">
                                <h3>Total Credit</h3>
                                <div class="stat-value">Tsh. {{ number_format($report->sum('MDebt')) }}</div>
                            </div>
                            <span class="stat-change negative">{{ number_format($report->avg('MDebt')) }}/day</span>
                        </div>
                    </div>

                    <div class="stat-card expenses">
                        <div class="stat-icon">
                            <i class="bi bi-receipt-cutoff"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-info">
                                <h3>Total Expenses</h3>
                                <div class="stat-value">Tsh. {{ number_format($report->sum('Mexpenses')) }}</div>
                            </div>
                            <span class="stat-change">{{ number_format($report->avg('Mexpenses')) }}/day</span>
                        </div>
                    </div>

                    <div class="stat-card profit">
                        <div class="stat-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-info">
                                <h3>Total Profit</h3>
                                @php
                                    $totalProfit = $report->sum(function($row) {
                                        return $row->Msales - $row->MDebt - $row->Mexpenses;
                                    });
                                @endphp
                                <div class="stat-value {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                    Tsh. {{ number_format($totalProfit) }}
                                </div>
                            </div>
                            @php
                                $avgProfit = $report->avg(function($row) {
                                    return $row->Msales - $row->MDebt - $row->Mexpenses;
                                });
                            @endphp
                            <span class="stat-change {{ $avgProfit >= 0 ? 'positive' : 'negative' }}">
                                {{ number_format($avgProfit) }}/day
                            </span>
                        </div>
                    </div>
                </div>-->

                <!-- Report Table -->
                <div class="table-container">
                    <div class="table-header">
                        <div class="table-title">
                            <i class="bi bi-table"></i>
                            Daily Sales Report
                        </div>
                        <div class="table-controls">
                            <div class="date-filter">
                                <label for="filterDate"><i class="bi bi-filter"></i> Filter:</label>
                                <input type="date" id="filterDate" class="form-control">
                            </div>
                            <button class="action-btn details">
                                <i class="bi bi-download"></i> Export
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        @if($report->isEmpty())
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <h4 class="empty-state-title">No Data Available</h4>
                                <p class="empty-state-text">No sales report data found for the selected period.</p>
                            </div>
                        @else
                            <table class="table">
                                <thead class="sticky-top">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Sales </th>
                                        <th>Paid</th>
                                        <th>Credit </th>
                                        <th title="Discount">D </th>
                                        <th title="Expenses">Exp </th>                                        
                                        <th title="Profit/Loss">P/L </th>
                                        <th title="Credit Receivings">Cr.R </th>
                                        <th title="Cash Receivings">Ca.R </th>
                                        <th title="Cash Submit">C.S </th>
                                        <th title="Sales - cash submit">S - C.S</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($report as $index => $row)
                                        @php
                                            $profit = $row->Msales - $row->MDebt - $row->Mexpenses;
                                            $profitClass = $profit > 0 ? 'profit-positive' : ($profit < 0 ? 'profit-negative' : 'profit-neutral');
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ date('M d, Y', strtotime($row->date)) }}</strong><br>
                                                <small class="text-muted">{{ date('l', strtotime($row->date)) }}</small>
                                            </td>
                                            <td>{{ number_format($row->Msales) }}</td>
                                            <td>{{ number_format($row->Mpaid) }}</td>
                                            <td>{{ number_format($row->Mcredit) }}</td>
                                            <td>{{ number_format($row->Mdisc) }}</td>
                                            <td>{{ number_format($row->Mexpenses) }}</td>                                        
                                            <td class="profit-cell {{ $profitClass }}">
                                                {{ number_format($profit) }}
                                            </td>
                                            <td>{{ number_format($row->receivings_credit) }}</td>
                                            <td>{{ number_format($row->receivings_paid) }}</td>
                                            <td>{{ number_format($row->submitted_cash) }}</td>
                                            <td>{{ number_format( $row->submitted_cash - $row->Mpaid) }}</td>
                                            <td>
                                                <form action="" method="post" class="cash-submit-form" data-row="{{ $row->date }}">
                                                    @csrf
                                                    <input type="hidden" name="date" value="{{ $row->date }}">
                                                    <input type="hidden" name="sales" value="{{ $row->Msales }}">
                                                    <input type="hidden" name="credit" value="{{ $row->MDebt }}">
                                                    <input type="hidden" name="expenses" value="{{ $row->Mexpenses }}">
                                                    <input type="hidden" name="receivings" value="{{ $row->receivings_paid }}">
                                                    <button type="button" class="action-btn cash-submit cash-submit-btn">
                                                        <i class="bi bi-cash-coin"></i> Cash Submit
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                @if($report->isNotEmpty())
                                    <tfoot>
                                        <tr style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                                            <td colspan="2" class="text-end fw-bold">TOTAL:</td>
                                            <td class="fw-bold">{{ number_format($report->sum('Msales')) }}</td>
                                            <td class="fw-bold">{{ number_format($report->sum('MDebt')) }}</td>
                                            <td class="fw-bold">{{ number_format($report->sum('Mdisc')) }}</td>
                                            <td class="fw-bold">{{ number_format($report->sum('Mexpenses')) }}</td>
                                            <td class="fw-bold">{{ number_format($report->sum('CostPrice')) }}</td>
                                            <td class="fw-bold {{ $totalProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($totalProfit) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Cash Submit Modal -->
    <div class="modal-overlay" id="cashSubmitModal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="bi bi-cash-coin"></i>
                    Cash Submission
                </div>
                <button class="modal-close" id="closeModal">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="cashForm" method="post" action="cashSubmit">
                    @csrf
                    <input type="hidden" id="modalDate" name="date">
                    
                    <!-- Date Information -->
                    <div class="date-info">
                        <div class="date-info-item">
                            <span class="date-label">Selected Date:</span>
                            <span class="date-value" id="displayDate"></span>
                        </div>
                        <div class="date-info-item">
                            <span class="date-label">Day:</span>
                            <span class="date-value" id="displayDay"></span>
                        </div>
                    </div>

                    <!-- Readonly Fields -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-cash-stack"></i>
                            Total Sales
                        </label>
                        <input type="text" id="totalSales" class="form-control readonly-field" readonly>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-credit-card"></i>
                            Total Credit
                        </label>
                        <input type="text" id="totalCredit" class="form-control readonly-field" readonly>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-receipt-cutoff"></i>
                            Total Expenses
                        </label>
                        <input type="text" id="totalExpenses" class="form-control readonly-field" readonly>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-box-seam"></i>
                            Cash Receivings
                        </label>
                        <input type="text" id="totalReceivings" class="form-control readonly-field" readonly>
                    </div>

                    <!-- Calculated Profit -->
                    <div class="amount-display" id="calculatedProfit">
                        Tsh. 0.00
                    </div>

                    <!-- Cash Amount Input -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-wallet2"></i>
                            Cash to Submit
                            <small class="text-muted">(Enter amount to deposit)</small>
                        </label>
                        <input type="number" id="cashAmount" name="cash_amount" class="form-control" 
                               placeholder="Enter cash amount to submit" min="0" step="0.01" required>
                        <small class="text-muted d-block mt-1">Available balance will be calculated automatically</small>
                    </div>

                    <!-- Date Selection for Submission -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-calendar-check"></i>
                            Submission Date
                        </label>
                        <input type="date" id="submissionDate" name="submission_date" class="form-control" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="cancelSubmit">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Submit Cash
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Modal Elements
        const modal = document.getElementById('cashSubmitModal');
        const closeModalBtn = document.getElementById('closeModal');
        const cancelSubmitBtn = document.getElementById('cancelSubmit');
        const cashForm = document.getElementById('cashForm');
        const cashAmountInput = document.getElementById('cashAmount');
        
        // Display Elements
        const displayDate = document.getElementById('displayDate');
        const displayDay = document.getElementById('displayDay');
        const totalSales = document.getElementById('totalSales');
        const totalCredit = document.getElementById('totalCredit');
        const totalExpenses = document.getElementById('totalExpenses');
        const totalReceivings = document.getElementById('totalReceivings');
        const calculatedProfit = document.getElementById('calculatedProfit');
        const submissionDate = document.getElementById('submissionDate');

        // Store current row data
        let currentRowData = {};

        // Format currency
        function formatCurrency(amount) {
            return 'Tsh. ' + parseFloat(amount).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Calculate and display profit
        function calculateProfit() {
            const sales = parseFloat(currentRowData.sales) || 0;
            const credit = parseFloat(currentRowData.credit) || 0;
            const expenses = parseFloat(currentRowData.expenses) || 0;
            const profit = sales - credit - expenses;
            
            calculatedProfit.textContent = formatCurrency(profit);
            
            // Color coding
            if (profit > 0) {
                calculatedProfit.style.color = 'var(--success-color)';
                calculatedProfit.style.borderColor = 'rgba(39, 174, 96, 0.5)';
            } else if (profit < 0) {
                calculatedProfit.style.color = 'var(--danger-color)';
                calculatedProfit.style.borderColor = 'rgba(231, 76, 60, 0.5)';
            } else {
                calculatedProfit.style.color = 'var(--warning-color)';
                calculatedProfit.style.borderColor = 'rgba(243, 156, 18, 0.5)';
            }
        }

        // Open modal with row data
        document.querySelectorAll('.cash-submit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.cash-submit-form');
                currentRowData = {
                    date: form.querySelector('input[name="date"]').value,
                    sales: form.querySelector('input[name="sales"]').value,
                    credit: form.querySelector('input[name="credit"]').value,
                    expenses: form.querySelector('input[name="expenses"]').value,
                    receivings: form.querySelector('input[name="receivings"]').value
                };

                // Format date
                const dateObj = new Date(currentRowData.date);
                const formattedDate = dateObj.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                const dayName = dateObj.toLocaleDateString('en-US', { weekday: 'long' });

                // Update modal display
                document.getElementById('modalDate').value = currentRowData.date;
                displayDate.textContent = formattedDate;
                displayDay.textContent = dayName;
                totalSales.value = formatCurrency(currentRowData.sales);
                totalCredit.value = formatCurrency(currentRowData.credit);
                totalExpenses.value = formatCurrency(currentRowData.expenses);
                totalReceivings.value = formatCurrency(currentRowData.receivings);

                // Calculate profit
                calculateProfit();

                // Set submission date to tomorrow
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                submissionDate.value = tomorrow.toISOString().split('T')[0];

                // Clear cash amount
                cashAmountInput.value = '';

                // Open modal
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                // Focus on cash amount input
                setTimeout(() => {
                    cashAmountInput.focus();
                }, 300);
            });
        });

        // Close modal functions
        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        closeModalBtn.addEventListener('click', closeModal);
        cancelSubmitBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });

        // Validate cash amount
        cashAmountInput.addEventListener('input', function() {
            const sales = parseFloat(currentRowData.sales) || 0;
            const credit = parseFloat(currentRowData.credit) || 0;
            const expenses = parseFloat(currentRowData.expenses) || 0;
            const receivings = parseFloat(currentRowData.receivings) || 0;
            
            const availableCash = sales - credit - expenses - receivings;
            const enteredAmount = parseFloat(this.value) || 0;

            if (enteredAmount > availableCash) {
                this.style.borderColor = 'var(--danger-color)';
                this.style.boxShadow = '0 0 0 3px rgba(231, 76, 60, 0.1)';
                this.setCustomValidity(`Cannot exceed available cash of ${formatCurrency(availableCash)}`);
            } else {
                this.style.borderColor = '';
                this.style.boxShadow = '';
                this.setCustomValidity('');
            }
        });

        // Form submission
        cashForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate cash amount
            const sales = parseFloat(currentRowData.sales) || 0;
            const credit = parseFloat(currentRowData.credit) || 0;
            const expenses = parseFloat(currentRowData.expenses) || 0;
            const receivings = parseFloat(currentRowData.receivings) || 0;
            const enteredAmount = parseFloat(cashAmountInput.value) || 0;
            
            const availableCash = sales - credit - expenses - receivings;

            if (enteredAmount > availableCash) {
                alert(`Cannot submit more than available cash: ${formatCurrency(availableCash)}`);
                cashAmountInput.focus();
                return;
            }

            if (!enteredAmount || enteredAmount <= 0) {
                alert('Please enter a valid cash amount');
                cashAmountInput.focus();
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('.btn-primary');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
            submitBtn.disabled = true;

            // Submit form
            setTimeout(() => {
                this.submit();
            }, 1000);
        });

        // Date filtering
        document.getElementById('filterDate').addEventListener('change', function() {
            const filterDate = this.value;
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const rowDate = row.querySelector('input[name="date"]')?.value;
                if (filterDate && rowDate !== filterDate) {
                    row.style.display = 'none';
                } else {
                    row.style.display = '';
                }
            });
        });

        // Clear filter on double click
        document.getElementById('filterDate').addEventListener('dblclick', function() {
            this.value = '';
            document.querySelectorAll('tbody tr').forEach(row => {
                row.style.display = '';
            });
        });

        // Initialize date inputs with current date
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('reportDate').value = today;
            document.getElementById('filterDate').value = today;
            
            // Set min date for submission date (tomorrow)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            submissionDate.min = tomorrow.toISOString().split('T')[0];
        });
    </script>
</body>
</html>