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
  <div class="row">
    @include("admin/sidenav")

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <div class="container-fluid p-3 bg-light d-flex justify-content-between">

            <h4>newEmployee
                Employees
            </h4>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newEmployee">
                New employee
            </button>
        </div>

        <div class="container">

            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <th>
                            Employee name
                        </th>
                        <th>
                            Age
                        </th>
                        <th>
                            Contact
                        </th>
                        <th>
                            Level status
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if($users->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center">
                            No Users found.
                        </td>
                    </tr>
                @else
                    @foreach ($users as $index => $user )
                        <tr>
                            <td>
                                {{$index + 1}}
                            </td>
                            <td>
                                {{$user->name ?? 'N/A'}}
                            </td>
                            <td>
                                {{$user->age ?? 'N/A'}}
                            </td>
                            <td>
                                {{$user->contact ?? 'N/A'}}
                            </td>
                            <td>
                                {{$user->levelStatus ?? 'N/A'}}
                            </td>
                            <td>
                                <button class="btn btn-secondary btn-sm">
                                    Manage
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </main>
  </div>
</div>

<div class="modal" id="newEmployee">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Register new employee
                </h4>
            </div>
            <div class="modal-body">
                <form action="registerEmployee" method="POST">
                    @csrf
                    <label for="name">
                        Employee name:
                    </label>
                    <input type="text" class="form-control" name="fname" placeholder="Full name" required>

                    <label for="name">
                        Employee Contact: <i class="text-muted">(optional)</i>
                    </label>
                    <input type="number" class="form-control" name="contact" placeholder="phone">

                    <label for="name">
                        Employee Email:
                    </label>
                    <input type="email" class="form-control" name="email" placeholder="Email address" required>

                    <label for="name">
                        Employee age: <i class="text-muted">(optional)</i>
                    </label>
                    <input type="number" name="age" class="form-control" placeholder="Age">

                    <label for="name">
                        Password:
                    </label>
                    <input type="password" name="password1" id="password" class="form-control" placeholder="xxx xxx" required>

                    <label for="name">
                       Confirm password:
                    </label>
                    <input type="password" name="password2" id="confirm_password" class="form-control" placeholder="xxx xxx" required>
<script>
    $(document).ready(function() {
        var password = document.getElementById("password"),
            confirm_password = document.getElementById("confirm_password");

        function validatePassword() {
            if (password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Passwords Don't Match");
            } else {
                confirm_password.setCustomValidity('');
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    });
</script>
<label for="name">
    Employee level:
</label>
<select name="level" name="level" class="form-control">
    <option selected disabled>
        Choose
    </option>
    <option value="Admin">Admin</option>
    <option value="Seller">Seller</option>
</select>

<button class="btn btn-success w-100 my-3">
    Save
</button>
</form>
            </div>
        </div>
    </div>
</div>
</body>
</html>