<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config("app.name")}}</title>
    @include("links")
        <style>
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
          #search-results {
            width: 70%
            padding: 5px;
    border: 1px solid #eee;
    background: ddd;
    position: absolute; // This may be necessary for dropdown effect
    z-index: 1000; // Ensure it appears above other elements
}
        </style>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Custom styles for this template -->
        <link href="{{asset("css/dashboard.css")}}" rel="stylesheet">
      </head>
<body>
    
@include("user/header")
<div class="container-fluid">


    <main class="container">

      <div class="container-fluid bg-light d-flex p-3 justify-content-between">
        <h4>
          <a href="#" onclick="history.back()" class="btn  d-print-none">
            <i class="bi bi-chevron-left"></i>          
            Expenses
      </a>
          
        </h4>

        <button class="btn  bg-color rounded-4" data-bs-toggle="modal" data-bs-target="#newExpense">
          New Expense
        </button>
      </div>

      <div class="container my-3">

        <button class="btn w-25 rounded-4 text-body bg-light stylish-btn rounded-4" >
          ;">
                <div class="container text-start">
                    <h6>
                        Total Expense
                    </h6>
                </div>
                <div class="container d-flex flex-nowrap justify-content-between">
                
        <span>
        <svg width="50px" height="50px" viewBox="-0.5 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12.7003 17.1099V18.22C12.7003 18.308 12.6829 18.395 12.6492 18.4763C12.6156 18.5576 12.5662 18.6316 12.504 18.6938C12.4418 18.7561 12.3679 18.8052 12.2867 18.8389C12.2054 18.8725 12.1182 18.8899 12.0302 18.8899C11.9423 18.8899 11.8551 18.8725 11.7738 18.8389C11.6925 18.8052 11.6187 18.7561 11.5565 18.6938C11.4943 18.6316 11.4449 18.5576 11.4113 18.4763C11.3776 18.395 11.3602 18.308 11.3602 18.22V17.0801C10.9165 17.0072 10.4917 16.8468 10.1106 16.6082C9.72943 16.3695 9.39958 16.0573 9.14023 15.6899C9.04577 15.57 8.99311 15.4226 8.99023 15.27C8.99148 15.1842 9.00997 15.0995 9.04459 15.021C9.0792 14.9425 9.12927 14.8718 9.19177 14.813C9.25428 14.7542 9.32794 14.7087 9.40842 14.679C9.4889 14.6492 9.57455 14.6359 9.66025 14.6399C9.74504 14.6401 9.82883 14.6582 9.90631 14.6926C9.98379 14.7271 10.0532 14.7773 10.1102 14.8401C10.4326 15.2576 10.8657 15.5763 11.3602 15.76V13.21C10.0302 12.69 9.36023 11.9099 9.36023 10.8999C9.38027 10.3592 9.5928 9.84343 9.9595 9.44556C10.3262 9.04769 10.8229 8.79397 11.3602 8.72998V7.62988C11.3602 7.5419 11.3776 7.45482 11.4113 7.37354C11.4449 7.29225 11.4943 7.21847 11.5565 7.15625C11.6187 7.09403 11.6925 7.04466 11.7738 7.01099C11.8551 6.97732 11.9423 6.95996 12.0302 6.95996C12.1182 6.95996 12.2054 6.97732 12.2867 7.01099C12.3679 7.04466 12.4418 7.09403 12.504 7.15625C12.5662 7.21847 12.6156 7.29225 12.6492 7.37354C12.6829 7.45482 12.7003 7.5419 12.7003 7.62988V8.71997C13.0724 8.77828 13.4289 8.91103 13.7485 9.11035C14.0681 9.30967 14.3442 9.57137 14.5602 9.87988C14.6555 9.99235 14.7117 10.1329 14.7202 10.28C14.7229 10.3662 14.7084 10.4519 14.6776 10.5325C14.6467 10.613 14.6002 10.6867 14.5406 10.749C14.481 10.8114 14.4096 10.8613 14.3306 10.8958C14.2516 10.9303 14.1665 10.9487 14.0802 10.95C13.99 10.9475 13.9013 10.9257 13.8202 10.886C13.7391 10.8463 13.6675 10.7897 13.6102 10.72C13.3718 10.4221 13.0575 10.1942 12.7003 10.0601V12.3101L12.9503 12.4099C14.2203 12.9099 15.0103 13.63 15.0103 14.77C14.9954 15.3808 14.7481 15.9629 14.3189 16.3977C13.8897 16.8325 13.3108 17.0871 12.7003 17.1099ZM11.3602 11.73V10.0999C11.1988 10.1584 11.0599 10.2662 10.963 10.408C10.8662 10.5497 10.8162 10.7183 10.8203 10.8899C10.8173 11.0676 10.8669 11.2424 10.963 11.3918C11.0591 11.5413 11.1973 11.6589 11.3602 11.73ZM13.5502 14.8C13.5502 14.32 13.2203 14.03 12.7003 13.8V15.8C12.9387 15.7639 13.1561 15.6427 13.3123 15.459C13.4685 15.2752 13.553 15.0412 13.5502 14.8Z" fill="#000000"></path> <path d="M18 3.96997H6C4.93913 3.96997 3.92172 4.39146 3.17157 5.1416C2.42142 5.89175 2 6.9091 2 7.96997V17.97C2 19.0308 2.42142 20.0482 3.17157 20.7983C3.92172 21.5485 4.93913 21.97 6 21.97H18C19.0609 21.97 20.0783 21.5485 20.8284 20.7983C21.5786 20.0482 22 19.0308 22 17.97V7.96997C22 6.9091 21.5786 5.89175 20.8284 5.1416C20.0783 4.39146 19.0609 3.96997 18 3.96997Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
        </span>
        <h3>
          {{(number_format($expense->sum('amount') ?? 0))}}

        </h2>
                </div>
               
        
            </button>
        
      </div>
      <div class="container-fluid">
        <div class="row">
          <div class="col">

            <div class="container table table-responsive">
              <table class="table table-sm table-striped" style="border-top: 3px solid #30C5FF;">
                <thead>
                  <tr>
                    <th>
                      #
                    </th>
                    <th>
                      Expense Name
                    </th>
                    <th>
                      Amount
                    </th>
                    <th>
                      Used at
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($expense as $index => $expenses)
                    
                  <tr>
                    <td>
                      {{($index + 1)}}
                    </td>
                    <td>
                      {{$expenses->expenseName}}
                    </td>
                    <td>
                      {{$expenses->amount}}
                    </td>
                    <td>
                      {{$expenses->created_at}}
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-3">
            <style>
              #calendar table {
                width: 100%;
                text-align: center;
              }
              #calendar table th {
                border-bottom: 1px solid #333;
                padding: 0px 5px;
              }
              .current-date {
    background-color: blue;
    color: #fff;
    border-radius: 50%;
    font-weight: bold;
  }
            </style>
            <div class="container">
              <h6>
                Search by calendar
              </h6>
            </div>
            <div id="calendar" class=" py-3"></div>
            <form id="dateForm" action="expenseDate" method="post">
              @csrf
              <input type="hidden" name="selectedDate" onchange="this.form.submit()" id="selectedDate" readonly>
          </form>
           
            
<style>
  table {
      border-collapse: collapse;
      width: 100%;
  }

  th, td {
      border: 1px solid #ddd;
      text-align: center;
      padding: 5px;
  }

  .current-date {
      background-color: #30C5FF;
      font-weight: bold;
  }

  button {
      margin: 10px;
      padding: 5px 10px;
  }
</style>
            <script>
        function createCalendar(month, year) {
    const calendar = document.getElementById('calendar');
    calendar.innerHTML = ''; // Clear previous calendar

    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();
    
    // Get the current date
    const today = new Date();
    const currentDay = today.getDate();
    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();

    // Create header for days of the week
    const daysHeader = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];
    const headerRow = document.createElement('tr');
    daysHeader.forEach(day => {
      const th = document.createElement('th');
      th.innerText = day;
      headerRow.appendChild(th);
    });
    const table = document.createElement('table');
    table.appendChild(headerRow);

    let row = document.createElement('tr');

    // Fill in the empty cells before the first day
    for (let i = 0; i < firstDay; i++) {
      const td = document.createElement('td');
      row.appendChild(td);
    }

    // Fill in the days of the month
    for (let day = 1; day <= daysInMonth; day++) {
      const td = document.createElement('td');
      td.innerText = day;

      // Check if this is the current date
      if (day === currentDay && month === currentMonth && year === currentYear) {
        td.classList.add('current-date'); // Mark the current date
      }

      // Add click event to set the selected date
   // Add click event to set the selected date
td.addEventListener('click', function() {
    // Format the date as YYYY-MM-DD
    const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    document.getElementById('selectedDate').value = formattedDate;
    document.getElementById('dateForm').submit(); // Submit the form after setting the date
});

      row.appendChild(td);

      // Start a new row after Saturday
      if ((day + firstDay) % 7 === 0) {
        table.appendChild(row);
        row = document.createElement('tr');
      }
    }

    // Append the last row if it has any cells
    if (row.children.length > 0) {
      table.appendChild(row);
    }

    // Append the table to the calendar div
    calendar.appendChild(table);
}
const today = new Date();
createCalendar(today.getMonth(), today.getFullYear());
            </script>
          </div>
        </div>
      </div>


      <div class="modal" id="newExpense">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4>
                New Expense
              </h4>
              <button class="btn btn-close" data-bs-dismiss="modal">
              
              </button>
            </div>
            <div class="modal-body">
              <form action="expenseInsert" method="post">
                @csrf
                <label for="use">
                  Used For:
                </label>
                <input type="text" class="form-control" name="exName">

                <label for="amount">'
                  Total Amout
                </label>
                <input type="number" class="form-control" name="amount">
                <div class="container text-end mt-3">
                  <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
                    Cancel
                  </button>
                  <button class="btn btn-success">
                    Save
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>
</div>
</body>
</html>