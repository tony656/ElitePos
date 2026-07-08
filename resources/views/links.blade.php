<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Popper.js (required for Bootstrap) -->
<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

<link rel="icon" type="image/x-icon" href="/public/favicon.ico">
@auth
<meta name="user-id" content="{{ Auth::user()->id }}">
@endauth

<!-- Aos Animations -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<style>
    .active-users-badges {
        position: fixed;
        bottom: 30px;
        right: 110px;
        z-index: 1000;
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
        padding: 10px 16px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        transition: all 0.3s ease;
    }

    .active-users-badges:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5);
    }

    .active-users-badges i {
        font-size: 16px;
    }

    .active-users-badges span {
        font-size: 15px;
        min-width: 20px;
        text-align: center;
    }
</style>

<script>
(function() {
    var INACTIVITY_LIMIT = 30 * 60 * 1000;
    var WARN_AT = 2 * 60 * 1000;
    var timer = null;
    var modalInstance = null;
    var warned = false;
    var sessionCleared = false;

    function createModal() {
        var modal = document.createElement('div');
        modal.className = 'modal';
        modal.id = 'sessionExpiredModal';
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-body p-5 text-center">
                        <div class="mb-4">
                            <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Session Expired</h4>
                        <p class="text-muted mb-4">Your session has expired due to inactivity.<br>Please log in again to continue.</p>
                        <button type="button" class="btn btn-primary btn-lg px-5 py-2 rounded-pill shadow-sm" id="modal-login-btn">Login Again</button>
                    </div>
                </div>
            </div>`;
        document.body.appendChild(modal);
        document.getElementById('modal-login-btn').addEventListener('click', function() {
            loginAgain();
        });
        modalInstance = new bootstrap.Modal(modal);
    }

    function clearSession() {
        if (sessionCleared) return;
        sessionCleared = true;
        fetch('/signout', { method: 'GET', redirect: 'manual', credentials: 'same-origin' });
    }

    function resetTimer() {
        warned = false;
        clearTimeout(timer);
        timer = setTimeout(function() {
            clearSession();
            if (modalInstance) {
                modalInstance.show();
            }
        }, INACTIVITY_LIMIT);
    }

    function loginAgain() {
        if (modalInstance) {
            modalInstance.hide();
        }
        resetTimer();
        sessionCleared = false;
        window.location.href = '/login';
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (!document.getElementById('sessionExpiredModal')) {
            createModal();
        }
        var events = ['mousemove', 'keydown', 'click', 'scroll', 'touchstart', 'mousedown'];
        events.forEach(function(ev) {
            document.addEventListener(ev, resetTimer, true);
        });
        resetTimer();
    });
})();
</script>

<script>
(function() {
    function updateActiveUsers() {
        fetch('/api/active-users-count', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
        })
        .then(function(response) {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(function(data) {
            var el = document.getElementById('activeUsersCount');
            if (el) {
                el.textContent = data.active_users || 0;
            }
        })
        .catch(function(err) {
            console.warn('Failed to fetch active users count:', err);
            var el = document.getElementById('activeUsersCount');
            if (el) {
                el.textContent = '?';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateActiveUsers();
        setInterval(updateActiveUsers, 60000);
    });
})();
</script>
