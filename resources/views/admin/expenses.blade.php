<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}} - Expenses Management</title>
    @include("links")
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #0f3460;
            --primary-light: #16213e;
            --accent: #1abc76;
            --accent-alt: #30c5ff;
            --danger: #e74c3c;
            --warning: #f39c12;
            --info: #3498db;
            --light-bg: #f8f9fa;
            --border-color: #e0e7ff;
            --text-primary: #2c3e50;
            --text-muted: #7f8c8d;
        }

        main {
            padding-bottom: 2rem;
        }

        /* Header Section */
        .expense-header {
           background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border-radius: 1.25rem;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            border: none;
            box-shadow: 0 10px 30px rgba(15, 52, 96, 0.2);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .expense-header h3 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .expense-header i {
            font-size: 2rem;
            margin-right: 0.75rem;
        }

        .btn-primary {
            background: var(--accent);
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(26, 188, 118, 0.3);
        }

        .btn-primary:hover {
            background: #0fa063;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 188, 118, 0.4);
            color: white;
        }

        /* Cards */
        .expense-card {
            background: white;
            border-radius: 1.25rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .expense-card:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        /* Table Styles */
        .expense-table {
            margin-bottom: 0;
        }

        .expense-table thead th {
            background: linear-gradient(135deg, #f5f7fa 0%, #eff2f5 100%);
            font-weight: 700;
            border: none;
            color: var(--text-primary);
            padding: 1rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .expense-table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid var(--border-color);
        }

        .expense-table tbody tr:hover {
            background: linear-gradient(90deg, rgba(26, 188, 118, 0.05) 0%, transparent 100%);
        }

        .expense-table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        .expense-table tbody td:first-child {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .expense-table tbody td:nth-child(5) {
            font-weight: 700;
            color: var(--primary);
        }

        /* Category Badges */
        .category-badge {
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            text-transform: capitalize;
            border: none;
            transition: all 0.2s ease;
        }

        .category-food {
            background: rgba(76, 175, 80, 0.15);
            color: #2e7d32;
        }

        .category-drinks {
            background: rgba(52, 152, 219, 0.15);
            color: #1565c0;
        }

        .category-bills {
            background: rgba(243, 156, 18, 0.15);
            color: #e65100;
        }

        .category-purchases {
            background: rgba(155, 89, 182, 0.15);
            color: #6a1b9a;
        }

        .category-debt {
            background: rgba(231, 76, 60, 0.15);
            color: #c62828;
        }

        /* Calendar Styles */
        .calendar-container {
            background: white;
            border-radius: 1.25rem;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
        }

        .calendar-container h6 {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .calendar-table {
            width: 100%;
            text-align: center;
            margin-bottom: 1rem;
        }

        .calendar-table th {
            border-bottom: 2px solid var(--border-color);
            padding: 0.75rem 0.25rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .calendar-table td {
            padding: 0.5rem 0.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--text-primary);
        }

        .calendar-table td:hover {
            background: rgba(26, 188, 118, 0.1);
            transform: scale(1.05);
        }

        .current-date {
            background: var(--accent);
            color: white;
            font-weight: 700;
            box-shadow: 0 3px 10px rgba(26, 188, 118, 0.3);
        }

        /* Summary Card */
        .summary-card {
            background: white;
            border-radius: 1.25rem;
            padding: 1.5rem;
            margin-top: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
        }

        .summary-card h5 {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-row span:first-child {
            color: var(--text-muted);
            font-weight: 500;
        }

        .summary-row span:last-child {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--accent);
        }

        /* Modal Styles */
        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            border: none;
            color: white;
            border-radius: 1.25rem 1.25rem 0 0;
        }

        .modal-header .modal-title {
            font-weight: 700;
            font-size: 1.25rem;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-content {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .form-label {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control, .form-select {
            border: 2px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(26, 188, 118, 0.1);
            color: var(--text-primary);
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .amount-input {
            position: relative;
        }

        .amount-input::before {
            content: "Tsh.";
            position: absolute;
            left: 0.3rem;
            top: 2.3rem;
            font-weight: 700;
            color: var(--accent);
            font-size: 1.1rem;
        }

        .amount-input input {
            padding-left: 2rem;
        }

        .btn-success, .btn-outline-secondary {
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid;
        }

        .btn-success {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
        }

        .btn-success:hover {
            background: #0fa063;
            border-color: #0fa063;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 188, 118, 0.3);
        }

        .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--text-muted);
        }

        .btn-outline-secondary:hover {
            background: var(--light-bg);
            border-color: var(--text-muted);
            color: var(--text-primary);
        }

        @media (max-width: 768px) {
            .expense-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .expense-header h3 {
                justify-content: center;
            }

            .btn-primary {
                width: 100%;
            }

            .calendar-container {
                margin-top: 2rem;
            }
        }
    </style>
    <link href="{{asset('css/dashboard.css')}}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @include("admin/sidenav")
            
            <main class="col-md-9 ms-sm-auto col-lg-10 py-2 px-md-4">
                <div class="expense-header">
                    <h3>
                        <i class="bi bi-cash-stack"></i>Expenses Management
                    </h3>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newExpense">
                        <i class="bi bi-plus-circle me-1"></i> New Expense
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-lg-9">
                        <div class="expense-card">
                            <div class="table-responsive">
                                <table class="table expense-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">Date</th>
                                            <th width="25%">Expense</th>
                                            <th width="15%">Category</th>
                                            <th width="15%">User</th>
                                            <th width="15%">Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($expense as $index => $expenses)
                                        <tr>
                                            <td>{{$index + 1}}</td>
                                            <td>{{ \Carbon\Carbon::parse($expenses->created_at)->format('M d, Y h:i A') }}</td>
                                            <td>{{$expenses->expenseName}}</td>
                                            <td>
                                                <span class="category-badge category-{{strtolower($expenses->category)}}">
                                                    {{$expenses->category}}
                                                </span>
                                            </td>
                                            <td>{{$expenses->expuser}}</td>
                                            <td>Tsh.{{number_format($expenses->amount, 2)}}</td>
                                            <td>
                                                <form action="dltExpense" method="post">
                                                    <input type="text" name="expenseId" value="{{ $expenses->id }}" hidden>
                                                    @csrf
                                                    <button class="btn text-danger">
                                                        <i class="bi bi-trash"></i>
                                                        Remove
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3">
                        <div class="calendar-container">
                            <h6>
                                <i class="bi bi-calendar3 me-2"></i>Search by Date
                            </h6>
                            <div id="calendar"></div>
                            <form id="dateForm" action="expenseDate" method="post">
                                @csrf
                                <input type="hidden" name="selectedDate" id="selectedDate">
                            </form>
                        </div>
                        
                        <div class="summary-card">
                            <h5>
                                <i class="bi bi-graph-up me-2"></i>Summary
                            </h5>
                            <div class="summary-row">
                                <span>Total Expenses:</span>
                                <span>Tsh.{{number_format($expense->sum('amount') ?? 0, 2)}}</span>
                            </div>
                            <div class="summary-row">
                                <span>This Month:</span>
                                <span>Tsh.{{number_format($expense->where('created_at', '>=', now()->startOfMonth())->sum('amount') ?? 0, 2)}}</span>
                            </div>
                            <div class="summary-row">
                                <span>Today:</span>
                                <span>Tsh.{{number_format($expense->where('created_at', '>=', today())->sum('amount') ?? 0, 2)}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- New Expense Modal -->
                <div class="modal fade" id="newExpense" tabindex="-1" aria-labelledby="newExpenseLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="newExpenseLabel">
                                    <i class="bi bi-plus-circle me-2"></i>Record New Expense
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="expenseInsert" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="exName" class="form-label">Description</label>
                                        <input type="text" class="form-control" name="exName" placeholder="What was this expense for?" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <select name="category" class="form-select" required>
                                            <option value="" selected disabled>Select Category</option>
                                            <option value="Food">🍔 Food</option>
                                            <option value="Drinks">🥤 Drinks</option>
                                            <option value="Bills">📄 Bills</option>
                                            <option value="Purchases">🛍️ Purchases</option>
                                            <option value="Debt">💳 Debt</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3 amount-input">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="number" class="form-control" name="amount" placeholder="0.00" step="0.01" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="expuser" class="form-label">Recorded By</label>
                                        <input type="text" class="form-control" name="expuser" placeholder="Your name" required>
                                    </div>
                                    
                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle me-1"></i> Cancel
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-save me-1"></i> Save Expense
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script>
        function createCalendar(month, year) {
            const calendar = document.getElementById('calendar');
            calendar.innerHTML = '';

            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const firstDay = new Date(year, month, 1).getDay();
            
            const today = new Date();
            const currentDay = today.getDate();
            const currentMonth = today.getMonth();
            const currentYear = today.getFullYear();

            const daysHeader = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            const headerRow = document.createElement('tr');
            daysHeader.forEach(day => {
                const th = document.createElement('th');
                th.innerText = day;
                headerRow.appendChild(th);
            });
            
            const table = document.createElement('table');
            table.className = 'calendar-table';
            table.appendChild(headerRow);

            let row = document.createElement('tr');

            for (let i = 0; i < firstDay; i++) {
                const td = document.createElement('td');
                row.appendChild(td);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const td = document.createElement('td');
                td.innerText = day;

                if (day === currentDay && month === currentMonth && year === currentYear) {
                    td.classList.add('current-date');
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

            const monthNames = ["January", "February", "March", "April", "May", "June",
                              "July", "August", "September", "October", "November", "December"];
            const header = document.createElement('div');
            header.className = 'd-flex justify-content-between align-items-center mb-3';
            
            const monthYear = document.createElement('span');
            monthYear.className = 'fw-bold';
            monthYear.style.fontSize = '0.95rem';
            monthYear.style.color = 'var(--primary)';
            monthYear.innerText = `${monthNames[month]} ${year}`;
            
            header.appendChild(monthYear);
            calendar.appendChild(header);
            calendar.appendChild(table);
        }

        const today = new Date();
        createCalendar(today.getMonth(), today.getFullYear());
    </script>
</body>
</html>