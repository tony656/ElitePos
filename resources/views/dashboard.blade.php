@php
    $user = Auth::user();
    $getName = $getName ?? null;
    $profileSrc = null;
    if (!empty($user->userImg)) {
        $profileSrc = asset('/public/images/' . $user->userImg);
    } elseif (!empty($user->photo)) {
        $profileSrc = asset('/public/images/' . $user->photo);
    }
    $initials = strtoupper(substr($user->name ?? 'U', 0, 1));
@endphp

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #0B1E3D 15%, #112952 50%, var(--theme-primary) 100%); color: #fff; border-radius: 24px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                    <div class="d-flex align-items-center gap-4">
                        <div style="width: 80px; height: 80px; border-radius: 24px; background: rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; border: 2px solid rgba(255,255,255,0.3); flex-shrink: 0; overflow: hidden;">
                            @if($profileSrc)
                                <img src="{{ $profileSrc }}" alt="{{ $user->name }}" style="width:100%; height:100%; object-fit:cover; border-radius: 22px;">
                            @else
                                <span style="font-size: 2rem; font-weight: 700; color: #fff; line-height: 1;">{{ $initials }}</span>
                            @endif
                        </div>
                        <div>
                            <p style="opacity: 0.85; font-size: 0.875rem; font-weight: 500; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Welcome back 👋</p>
                            <h2 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.375rem; letter-spacing: -0.02em;">{{ $user->name }}</h2>
                            <span style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.15); padding: 0.35rem 0.875rem; border-radius: 99px; font-size: 0.8125rem; font-weight: 500; backdrop-filter: blur(4px);">
                                <i class="bi bi-shield-check"></i> {{ $user->levelStatus }}
                            </span>
                            @if(!empty($getName->bName))
                                <div style="opacity:.85; font-size:13px; margin-top: 0.5rem;">
                                    <i class="bi bi-shop me-1"></i> {{ $getName->bName }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="text-end">
                        <p style="opacity: 0.7; font-size: 0.75rem; font-weight: 500; margin-bottom: 0.25rem;"><i class="bi bi-calendar3 me-1"></i>{{ date('l, F j, Y') }}</p>
                        <span id="dashboardTime" style="font-size: 1.5rem; font-weight: 700; font-family: 'JetBrains Mono', monospace; letter-spacing: 0.05em;">00:00:00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function updateDashboardTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const el = document.getElementById('dashboardTime');
            if (el) {
                el.textContent = `${hours}:${minutes}:${seconds}`;
            }
        }
        updateDashboardTime();
        setInterval(updateDashboardTime, 1000);
    });
</script>