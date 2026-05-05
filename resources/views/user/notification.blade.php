<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{config('app.name')}} - Notifications</title>
    @include("links")
    <style>
        .notification-container {
            max-width: 800px;
            margin: 0 auto;
            padding-bottom: 100px; /* Space for fixed bottom form */
        }
        
        .notification-header {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 20px;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(5px);
            background-color: rgba(248, 249, 250, 0.8);
        }
        
        .notification-card {
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            background-color: white !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }
        
        .notification-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .notification-card.system {
            border-left-color: #30C5FF;
        }
        
        .notification-card.alert {
            border-left-color: #ffc530;
        }
        
        .notification-card.important {
            border-left-color: #ff304f;
        }
        
        .notification-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #f0f8ff;
            color: #30C5FF;
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .notification-message {
            color: #7f8c8d;
            margin-bottom: 0;
        }
        
        .notification-time {
            font-size: 0.8rem;
            color: #95a5a6;
            margin-top: 5px;
        }
        
        .delete-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
        }
        
        .delete-btn:hover {
            background-color: rgba(255, 48, 79, 0.1);
            color: #ff304f;
        }
        
        .compose-form {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: white;
            padding: 15px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        
        .compose-input {
            border-radius: 20px;
            padding-left: 15px;
            border: 1px solid #dfe6e9;
        }
        
        .send-btn {
            border-radius: 20px;
            padding: 8px 20px;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #7f8c8d;
        }
        
        .empty-state-icon {
            font-size: 3rem;
            color: #dfe6e9;
            margin-bottom: 15px;
        }
        
        @media (max-width: 768px) {
            .notification-container {
                padding: 0 15px 120px;
            }
            
            .compose-form .input-group {
                flex-direction: column;
            }
            
            .compose-form .form-select {
                width: 100% !important;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    
    
    <div class="container-fluid">
          <div class="row">
    @include("user/sidenav")

    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-3">
        <main class="notification-container">
            <div class="notification-header">
                <div class="d-flex align-items-center">
                    <a href="#" onclick="history.back()" class="btn btn-sm btn-outline-secondary me-3">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <h4 class="mb-0">Notifications</h4>
                </div>
            </div>

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

            @if($data->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-bell"></i>
                </div>
                <h5>No notifications yet</h5>
                <p>Your notifications will appear here</p>
            </div>
            @else
                @foreach ($data as $row)
                <div class="notification-card @if($row->head === 'System') system @elseif($row->important) important @else alert @endif">
                    <div class="d-flex align-items-start">
                        <div class="notification-icon me-3">
                            @if($row->head === 'System')
                            <i class="bi bi-gear-fill"></i>
                            @else
                            <i class="bi bi-bell-fill"></i>
                            @endif
                        </div>
                        <div class="notification-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="notification-title">{{$row->head}} @if($row->target) <small class="text-muted">~ {{$row->target}}</small> @endif</h5>
                                <form action="deleteNotif" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-danger delete-btn" name="id" value="{{$row->id}}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <p class="notification-message">{{$row->message}}</p>
                            <div class="notification-time">
                                {{ \Carbon\Carbon::parse($row->created_at)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            <!-- System welcome notification -->
            <div class="notification-card system">
                <div class="d-flex align-items-start">
                    <div class="notification-icon me-3">
                        <i class="bi bi-stars"></i>
                    </div>
                    <div class="notification-content">
                        <h5 class="notification-title">System</h5>
                        <p class="notification-message">
                            Hello {{session('username')}}, welcome to the system. You have successfully logged in to your admin account. 
                            You can now manage all operations efficiently with our POS system.
                        </p>
                        <div class="notification-time">
                            Just now
                        </div>
                    </div>
                </div>
            </div>
                <!-- Compose form fixed at bottom -->
        <div class="compose-form">
            <form action="sendNotif" method="post">
                @csrf
                <div class="d-flex flex-wrap align-items-center">
                    <div class="me-2 mb-2" style="flex: 1; min-width: 200px;">
                        <select name="to" class="form-select compose-input" required>
                            <option value="" selected disabled>Select Recipient</option>
                            @php
                                $receiver = DB::table('users')->where('levelStatus', '!=', 'Admin')->get();
                            @endphp
                            @foreach ($receiver as $rec)
                            <option value="{{$rec->name}}">{{$rec->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex mb-2" style="flex: 2; min-width: 300px;">
                        <input type="text" name="message" class="form-control compose-input" placeholder="Type your message..." required>
                        <button type="submit" class="btn btn-primary send-btn ms-2">
                            <i class="bi bi-send-fill me-1"></i> Send
                        </button>
                    </div>
                </div>
            </form>
        </div>
        </main>
    </div>
</div>
    
    </div>

    <script>
        // Auto-focus the message input
        document.addEventListener('DOMContentLoaded', function() {
            const messageInput = document.querySelector('input[name="message"]');
            if (messageInput) {
                messageInput.focus();
            }
            
            // Smooth scroll to bottom for new notifications
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>