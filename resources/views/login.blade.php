<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Elite POS — Sign In</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --navy:        #0B1E3D;
      --navy-mid:    #112952;
      --navy-light:  #1A3A6B;
      --amber:       #F59E0B;
      --amber-warm:  #D97706;
      --amber-pale:  #FEF3C7;
      --emerald:     #059669;
      --rose:        #E11D48;
      --slate-400:   #94A3B8;
      --slate-500:   #64748B;
      --slate-200:   #E2E8F0;
      --white:       #FFFFFF;
      --serif:       'DM Serif Display', Georgia, serif;
      --sans:        'DM Sans', system-ui, sans-serif;
      --mono:        'DM Mono', monospace;
    }

    html, body {
      height: 100%;
      font-family: var(--sans);
      background: #060E1C;
      color: var(--white);
      overflow: hidden;
    }

    /* ══ NOISE TEXTURE OVERLAY ══ */
    body::before {
      content: '';
      position: fixed; inset: 0; z-index: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
      pointer-events: none;
    }

    /* ══ AMBIENT LIGHT ══ */
    .ambient {
      position: fixed; inset: 0; z-index: 0; pointer-events: none;
    }
    .amb-1 {
      position: absolute; top: -20%; left: -10%;
      width: 600px; height: 600px; border-radius: 50%;
      background: radial-gradient(circle, rgba(245,158,11,.12) 0%, transparent 70%);
    }
    .amb-2 {
      position: absolute; bottom: -30%; right: -15%;
      width: 700px; height: 700px; border-radius: 50%;
      background: radial-gradient(circle, rgba(26,58,107,.4) 0%, transparent 70%);
    }

    /* ══ LAYOUT ══ */
    .shell {
      position: relative; z-index: 1;
      display: grid;
      grid-template-columns: 1fr 480px;
      height: 100vh;
    }

    /* ══════════════════════════════
       LEFT — Dark info panel
    ══════════════════════════════ */
    .panel-left {
      position: relative;
      display: flex; flex-direction: column;
      padding: 2.5rem 2.75rem;
      border-right: 1px solid rgba(255,255,255,.06);
      overflow: hidden;
    }

    /* Brand */
    .brand {
      display: flex; align-items: center; gap: 12px;
      margin-bottom: auto;
    }
    .brand-mark {
      width: 42px; height: 42px; border-radius: 11px;
      background: var(--amber); display: flex; align-items: center; justify-content: center;
      flex-shrink: 0; box-shadow: 0 0 28px rgba(245,158,11,.35);
    }
    .brand-mark svg { width: 22px; height: 22px; }
    .brand-text {}
    .brand-name { font-family: var(--serif); font-size: 20px; color: var(--white); line-height: 1; }
    .brand-sub  { font-size: 10px; letter-spacing: .2em; text-transform: uppercase; color: rgba(255,255,255,.3); margin-top: 2px; }

    /* Hero text */
    .hero-block {
      margin-bottom: 2.5rem;
    }
    .hero-eyebrow {
      font-size: 10.5px; letter-spacing: .18em; text-transform: uppercase;
      color: var(--amber); font-weight: 600; margin-bottom: .75rem;
      display: flex; align-items: center; gap: 8px;
    }
    .hero-eyebrow::before { content:''; width: 24px; height: 1px; background: var(--amber); }
    .hero-title {
      font-family: var(--serif); font-size: clamp(2rem, 3.5vw, 3.2rem);
      line-height: 1.1; color: var(--white); margin-bottom: 1rem;
    }
    .hero-title em { font-style: italic; color: var(--amber); }
    .hero-sub { font-size: 13.5px; color: rgba(255,255,255,.4); line-height: 1.7; max-width: 380px; }

    /* Status ticker row */
    .ticker-row {
      display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 2rem;
    }
    .ticker-chip {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 5px 12px; border-radius: 20px;
      background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.08);
      font-size: 11px; color: rgba(255,255,255,.6);
    }
    .ticker-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .dot-green  { background: #10b981; box-shadow: 0 0 6px #10b981; animation: pulse 2s infinite; }
    .dot-amber  { background: var(--amber); box-shadow: 0 0 6px var(--amber); animation: pulse 2.4s infinite; }
    .dot-red    { background: var(--rose); box-shadow: 0 0 6px var(--rose); }
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

    /* Stat cards */
    .stat-strip {
      display: grid; grid-template-columns: repeat(3,1fr); gap: 1px;
      background: rgba(255,255,255,.07);
      border: 1px solid rgba(255,255,255,.07);
      border-radius: 14px; overflow: hidden; margin-bottom: 2rem;
    }
    .stat-cell {
      background: rgba(255,255,255,.025);
      padding: 1rem 1.1rem;
    }
    .stat-cell:hover { background: rgba(255,255,255,.05); }
    .sc-label { font-size: 9.5px; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,255,255,.3); margin-bottom: 6px; }
    .sc-val { font-family: var(--mono); font-size: 20px; font-weight: 500; color: var(--white); line-height: 1; margin-bottom: 4px; }
    .sc-sub { font-size: 10.5px; color: rgba(255,255,255,.35); }

    /* Connectivity list */
    .conn-card {
      background: rgba(255,255,255,.04);
      border: 1px solid rgba(255,255,255,.07);
      border-radius: 14px; padding: 1rem 1.2rem;
      margin-bottom: 1.5rem;
    }
    .conn-item {
      display: flex; align-items: center; justify-content: space-between;
      padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,.05);
    }
    .conn-item:last-child { border-bottom: none; padding-bottom: 0; }
    .conn-item:first-child { padding-top: 0; }
    .ci-label { font-size: 11.5px; color: rgba(255,255,255,.4); }
    .ci-val { display: flex; align-items: center; gap: 6px; font-size: 11.5px; font-weight: 500; color: rgba(255,255,255,.75); }

    /* Location badge */
    .loc-badge {
      display: inline-flex; align-items: center; gap: 7px;
      background: rgba(5,150,105,.1); border: 1px solid rgba(5,150,105,.25);
      border-radius: 8px; padding: 7px 12px; font-size: 11.5px; color: #34d399;
      margin-bottom: 1.5rem;
    }
    .loc-badge i { font-size: 13px; }

    /* Date */
    .date-line {
      font-size: 11px; color: rgba(255,255,255,.25);
      font-family: var(--mono); letter-spacing: .05em;
    }

    /* Decorative grid lines */
    .grid-lines {
      position: absolute; inset: 0; pointer-events: none;
      background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
      background-size: 60px 60px;
      mask-image: radial-gradient(ellipse at 30% 70%, rgba(0,0,0,.5) 0%, transparent 65%);
    }

    /* ══════════════════════════════
       RIGHT — Login form
    ══════════════════════════════ */
    .panel-right {
      display: flex; flex-direction: column; justify-content: center;
      padding: 2.5rem 2.75rem;
      background: rgba(255,255,255,.02);
      position: relative;
    }

    /* Vertical label on right edge */
    .vert-label {
      position: absolute; right: -1px; top: 50%;
      transform: translateY(-50%) rotate(90deg);
      font-size: 9px; letter-spacing: .25em; text-transform: uppercase;
      color: rgba(255,255,255,.12); white-space: nowrap;
    }

    .form-eyebrow {
      font-size: 10px; letter-spacing: .2em; text-transform: uppercase;
      color: rgba(255,255,255,.3); margin-bottom: 1.5rem;
    }

    .form-title {
      font-family: var(--serif); font-size: 2rem; color: var(--white);
      line-height: 1.15; margin-bottom: .4rem;
    }
    .form-sub { font-size: 13px; color: rgba(255,255,255,.35); margin-bottom: 2rem; }

    /* Role selector — pill strip */
    .role-strip {
      display: flex; background: rgba(255,255,255,.06);
      border: 1px solid rgba(255,255,255,.08);
      border-radius: 10px; padding: 3px; gap: 2px; margin-bottom: 1.75rem;
    }
    .role-pill {
      flex: 1; padding: 7px; background: transparent; border: none;
      font-family: var(--sans); font-size: 12px; font-weight: 500;
      color: rgba(255,255,255,.4); cursor: pointer; border-radius: 7px;
      transition: all .18s;
    }
    .role-pill.active {
      background: var(--navy-light);
      color: var(--white);
      box-shadow: 0 2px 8px rgba(11,30,61,.5);
    }
    .role-pill:hover:not(.active) { color: rgba(255,255,255,.7); }

    /* Fields */
    .field { margin-bottom: 1rem; }
    .field-label {
      display: block; font-size: 10.5px; font-weight: 600;
      letter-spacing: .07em; text-transform: uppercase;
      color: rgba(255,255,255,.35); margin-bottom: 6px;
    }
    .field-input {
      width: 100%; padding: 11px 14px;
      background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
      border-radius: 10px; font-family: var(--sans); font-size: 14px;
      color: var(--white); outline: none; transition: all .2s;
    }
    .field-input::placeholder { color: rgba(255,255,255,.2); }
    .field-input:focus {
      border-color: var(--amber);
      background: rgba(245,158,11,.07);
      box-shadow: 0 0 0 3px rgba(245,158,11,.12);
    }
    .field-input:-webkit-autofill {
      -webkit-box-shadow: 0 0 0 50px #101e35 inset;
      -webkit-text-fill-color: var(--white);
    }

    /* Password row */
    .pw-wrap { position: relative; }
    .pw-toggle {
      position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
      background: none; border: none; cursor: pointer; color: rgba(255,255,255,.3);
      font-size: 14px; padding: 4px; transition: color .15s;
    }
    .pw-toggle:hover { color: rgba(255,255,255,.7); }

    /* Remember row */
    .remember-row {
      display: flex; align-items: center; justify-content: space-between;
      margin-bottom: 1.5rem;
    }
    .check-group { display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .check-box {
      width: 16px; height: 16px; border-radius: 4px;
      border: 1.5px solid rgba(255,255,255,.2);
      background: rgba(255,255,255,.05); cursor: pointer;
      transition: all .15s; appearance: none; -webkit-appearance: none;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .check-box:checked {
      background: var(--amber); border-color: var(--amber);
      background-image: url("data:image/svg+xml,%3Csvg width='10' height='8' viewBox='0 0 10 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 3.5L4 6.5L9 1' stroke='%230B1E3D' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
      background-repeat: no-repeat; background-position: center;
    }
    .check-label { font-size: 12.5px; color: rgba(255,255,255,.4); cursor: pointer; }

    /* Submit button */
    .btn-sign {
      width: 100%; padding: 13px 20px;
      background: var(--amber); color: var(--navy);
      font-family: var(--sans); font-size: 14px; font-weight: 700;
      border: none; border-radius: 10px; cursor: pointer;
      display: flex; align-items: center; justify-content: center; gap: 8px;
      box-shadow: 0 4px 20px rgba(245,158,11,.35);
      transition: all .18s; letter-spacing: .02em;
      position: relative; overflow: hidden;
    }
    .btn-sign::before {
      content: '';
      position: absolute; inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,.15) 0%, transparent 60%);
      opacity: 0; transition: opacity .18s;
    }
    .btn-sign:hover::before { opacity: 1; }
    .btn-sign:hover { transform: translateY(-1px); box-shadow: 0 8px 28px rgba(245,158,11,.45); }
    .btn-sign:active { transform: translateY(0); }
    .btn-sign:disabled { background: rgba(245,158,11,.3); color: rgba(11,30,61,.5); cursor: not-allowed; transform: none; box-shadow: none; }
    .btn-sign svg { width: 16px; height: 16px; }

    /* Spinner */
    .spinner {
      width: 15px; height: 15px;
      border: 2px solid rgba(11,30,61,.3);
      border-top-color: var(--navy);
      border-radius: 50%;
      animation: spin .6s linear infinite; flex-shrink: 0;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Error */
    .err-box {
      margin-top: 1rem; padding: 10px 14px;
      background: rgba(225,29,72,.12); border: 1px solid rgba(225,29,72,.25);
      border-radius: 8px; font-size: 12.5px; color: #fca5a5;
    }

    /* Divider */
    .divider {
      display: flex; align-items: center; gap: 10px; margin: 1.4rem 0;
    }
    .div-line { flex: 1; height: 1px; background: rgba(255,255,255,.08); }
    .div-text { font-size: 11px; color: rgba(255,255,255,.2); white-space: nowrap; }

    /* Alt buttons */
    .alt-row { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 1.5rem; }
    .alt-btn {
      padding: 10px; border-radius: 9px;
      background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08);
      font-family: var(--sans); font-size: 12px; color: rgba(255,255,255,.5);
      cursor: pointer; transition: all .15s;
      display: flex; align-items: center; justify-content: center; gap: 7px;
    }
    .alt-btn svg { width: 14px; height: 14px; opacity: .7; }
    .alt-btn:hover { border-color: rgba(245,158,11,.4); color: var(--amber); background: rgba(245,158,11,.05); }

    /* Help */
    .help-row {
      text-align: center; font-size: 12px;
      color: rgba(255,255,255,.2);
    }
    .help-row a { color: var(--amber); text-decoration: none; }
    .help-row a:hover { text-decoration: underline; }

    /* Location modal */
    .modal-overlay {
      position: fixed; inset: 0; z-index: 100;
      background: rgba(0,0,0,.7);
      backdrop-filter: blur(8px);
      display: flex; align-items: center; justify-content: center;
    }
    .modal-box {
      background: #0F1E35; border: 1px solid rgba(255,255,255,.1);
      border-radius: 20px; padding: 2rem; max-width: 340px; width: 90%;
      text-align: center;
      animation: slideUp .3s ease;
    }
    @keyframes slideUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
    .modal-icon {
      width: 64px; height: 64px; border-radius: 50%;
      background: rgba(245,158,11,.12); border: 1px solid rgba(245,158,11,.2);
      display: flex; align-items: center; justify-content: center;
      margin: 0 auto 1.25rem; font-size: 28px;
    }
    .modal-title { font-family: var(--serif); font-size: 1.4rem; color: var(--white); margin-bottom: .5rem; }
    .modal-text { font-size: 12.5px; color: rgba(255,255,255,.45); line-height: 1.7; margin-bottom: 1.5rem; }
    .modal-btns { display: flex; gap: 8px; }
    .modal-btn {
      flex: 1; padding: 10px; border-radius: 9px; border: none;
      font-family: var(--sans); font-size: 13px; font-weight: 600; cursor: pointer; transition: all .15s;
    }
    .mb-allow { background: var(--amber); color: var(--navy); }
    .mb-allow:hover { background: var(--amber-warm); }
    .mb-deny  { background: rgba(255,255,255,.07); color: rgba(255,255,255,.6); }
    .mb-deny:hover  { background: rgba(255,255,255,.12); }

    /* Location status */
    #locationStatusEl {
      font-size: 11px; color: rgba(255,255,255,.35);
      font-family: var(--mono); margin-top: 1.25rem; text-align: center;
      min-height: 16px; transition: color .3s;
    }

    /* ── Responsive ── */
    @media (max-width: 860px) {
      .shell { grid-template-columns: 1fr; overflow-y: auto; }
      html, body { overflow: auto; }
      .panel-left { display: none; }
      .panel-right { min-height: 100vh; }
    }
  </style>
</head>
<body>

<div class="ambient">
  <div class="amb-1"></div>
  <div class="amb-2"></div>
</div>

<div class="shell">

  <!-- ═══ LEFT PANEL ═══ -->
  <div class="panel-left">
    <div class="grid-lines"></div>

    <div class="brand">
      <div class="brand-mark">
        <svg viewBox="0 0 22 22" fill="none">
          <rect x="3" y="3" width="7" height="7" rx="1.5" fill="#0B1E3D"/>
          <rect x="12" y="3" width="7" height="7" rx="1.5" fill="rgba(11,30,61,.5)"/>
          <rect x="3" y="12" width="7" height="7" rx="1.5" fill="rgba(11,30,61,.5)"/>
          <rect x="12" y="12" width="7" height="7" rx="1.5" fill="#0B1E3D"/>
        </svg>
      </div>
      <div class="brand-text">
        <div class="brand-name">Elite POS</div>
        <div class="brand-sub">Point of Sale</div>
      </div>
    </div>

    <div style="flex:1; display:flex; flex-direction:column; justify-content:center; gap:1.75rem;">

      <div class="hero-block">
        <div class="hero-eyebrow">Management System</div>
        <div class="hero-title">
          Your business,<br><em>under control.</em>
        </div>
        <div class="hero-sub">
          Real-time sales tracking, inventory management, and supplier credit — all in one secure platform.
        </div>
      </div>

      <!-- Status chips -->
      <div class="ticker-row">
        <div class="ticker-chip"><div class="ticker-dot dot-green"></div>Database</div>
        <div class="ticker-chip"><div class="ticker-dot dot-green"></div>Server</div>
        <div class="ticker-chip"><div class="ticker-dot dot-green"></div>API</div>
        <div class="ticker-chip"><div class="ticker-dot dot-amber"></div>Backup sync</div>
      </div>

      <!-- Stats strip -->
      <div class="stat-strip">
        <div class="stat-cell">
          <div class="sc-label">Active users</div>
          <div class="sc-val">{{ $activeUsers ?? '12' }}</div>
          <div class="sc-sub">online now</div>
        </div>
        <div class="stat-cell">
          <div class="sc-label">Pending orders</div>
          <div class="sc-val">{{ $pendingOrders ?? '3' }}</div>
          <div class="sc-sub">awaiting action</div>
        </div>
        <div class="stat-cell">
          <div class="sc-label">Low stock</div>
          <div class="sc-val" style="color:{{ ($lowStockCount ?? 5) > 0 ? '#fbbf24' : '#10b981' }};">{{ $lowStockCount ?? '5' }}</div>
          <div class="sc-sub">items</div>
        </div>
      </div>

      <!-- Connectivity -->
      <div class="conn-card">
        <div class="conn-item">
          <span class="ci-label">Internet</span>
          <span class="ci-val"><div class="ticker-dot dot-green" style="width:5px;height:5px;animation:none;"></div>{{ $internetStatus ?? 'Connected' }}</span>
        </div>
        <div class="conn-item">
          <span class="ci-label">Payment gateway</span>
          <span class="ci-val"><div class="ticker-dot dot-green" style="width:5px;height:5px;animation:none;"></div>{{ $paymentGateway ?? 'Online' }}</span>
        </div>
        <div class="conn-item">
          <span class="ci-label">SMS service</span>
          <span class="ci-val"><div class="ticker-dot dot-green" style="width:5px;height:5px;animation:none;"></div>{{ $smsService ?? 'Available' }}</span>
        </div>
        <div class="conn-item">
          <span class="ci-label">Storage usage</span>
          <span class="ci-val" style="font-family:var(--mono);">{{ $storagePercent ?? '68%' }}</span>
        </div>
        <div class="conn-item">
          <span class="ci-label">Last backup</span>
          <span class="ci-val" style="font-family:var(--mono);">{{ $lastBackup ?? '2 hrs ago' }}</span>
        </div>
      </div>

      <!-- Location -->
      <div class="loc-badge" id="locBadge">
        <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
        <span id="locText">Waiting for location…</span>
      </div>

    </div>

    <div class="date-line" id="liveDate">Loading…</div>
  </div>

  <!-- ═══ RIGHT PANEL ═══ -->
  <div class="panel-right">
    <div class="vert-label">Elite POS · Secure Login</div>

    <div class="form-eyebrow">Authentication</div>
    <div class="form-title">Sign in to<br>your workspace</div>
    <div class="form-sub">Enter your credentials to access the dashboard</div>

    <!-- Role selector -->
    <div class="role-strip">
      <button class="role-pill active" onclick="setRole(this)">Cashier</button>
      <button class="role-pill" onclick="setRole(this)">Manager</button>
      <button class="role-pill" onclick="setRole(this)">Admin</button>
    </div>

    <form id="loginForm" action="{{ route('login') }}" method="POST">
      @csrf
      <input type="hidden" name="role" id="role-input" value="cashier">
      <input type="hidden" name="latitude"         id="latitude">
      <input type="hidden" name="longitude"        id="longitude">
      <input type="hidden" name="accuracy"         id="accuracy">
      <input type="hidden" name="client_timezone"  id="client_timezone">
      <input type="hidden" name="location_consent" id="location_consent" value="pending">

      <div class="field">
        <label class="field-label" for="email">Email address</label>
        <input class="field-input" type="email" id="email" name="email"
               placeholder="you@elitepos.co.tz" required autocomplete="email">
      </div>

      <div class="field">
        <label class="field-label" for="password">Password</label>
        <div class="pw-wrap">
          <input class="field-input" type="password" id="password" name="password"
                 placeholder="••••••••" required autocomplete="current-password" style="padding-right:42px;">
          <button type="button" class="pw-toggle" onclick="togglePw()">
            <svg id="eyeIcon" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
              <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
              <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="remember-row">
        <label class="check-group">
          <input type="checkbox" class="check-box" id="remember" name="remember">
          <span class="check-label">Keep me signed in</span>
        </label>
      </div>

      <button type="submit" class="btn-sign" id="loginBtn">
        <svg viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V4a1 1 0 00-1-1H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 4a1 1 0 10-2 0v1a1 1 0 102 0v-1zM9 7a1 1 0 10-2 0v4a1 1 0 102 0V7z" clip-rule="evenodd"/>
        </svg>
        Sign in
      </button>

      @if($errors->any())
      <div class="err-box">{{ $errors->first() }}</div>
      @endif
      @if(session('error'))
      <div class="err-box">{{ session('error') }}</div>
      @endif

      <div id="locationStatusEl"></div>
    </form>

    <div class="divider">
      <div class="div-line"></div>
      <span class="div-text">other options</span>
      <div class="div-line"></div>
    </div>

    <div class="alt-row">
      <button class="alt-btn">
        <svg viewBox="0 0 20 20" fill="currentColor">
          <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"/>
        </svg>
        PIN Login
      </button>
      <button class="alt-btn">
        <svg viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm3 2h6v4H7V5zm8 8v2h1v-2h-1zm-2-2H7v4h6v-4zm2 0h1V9h-1v2zm1-4V5h-1v2h1zM5 5v2H4V5h1zm-1 4h1v2H4V9zm1 4H4v2h1v-2z" clip-rule="evenodd"/>
        </svg>
        QR Scan
      </button>
    </div>

    <div class="help-row">
      Need help? <a href="tel:+255627781324">+255 627 781 324</a>
    </div>
  </div>
</div>

<script>
  /* ── Live date ── */
  const days   = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
  const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  const now    = new Date();
  document.getElementById('liveDate').textContent =
    days[now.getDay()] + ' ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear()
    + '  ·  ' + now.toLocaleTimeString('en-GB', { hour:'2-digit', minute:'2-digit' });
  document.getElementById('client_timezone').value = Intl.DateTimeFormat().resolvedOptions().timeZone;

  /* ── Role pills ── */
  function setRole(el) {
    document.querySelectorAll('.role-pill').forEach(p => p.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('role-input').value = el.textContent.trim().toLowerCase();
  }

  /* ── Password toggle ── */
  function togglePw() {
    const pw = document.getElementById('password');
    pw.type = pw.type === 'password' ? 'text' : 'password';
  }

  /* ── Location status helpers ── */
  function setLocStatus(msg, ok) {
    const el = document.getElementById('locationStatusEl');
    el.textContent = msg;
    el.style.color = ok === true ? '#34d399' : ok === false ? '#fca5a5' : 'rgba(255,255,255,.3)';
    document.getElementById('locText').textContent = msg;
    const badge = document.getElementById('locBadge');
    badge.style.borderColor = ok === true ? 'rgba(5,150,105,.25)' : ok === false ? 'rgba(225,29,72,.25)' : '';
    badge.style.color       = ok === true ? '#34d399' : ok === false ? '#fca5a5' : '';
  }

  function getPreciseLocation() {
    return new Promise((resolve, reject) => {
      if (!navigator.geolocation) { reject(new Error('Not supported')); return; }
      setLocStatus('Acquiring GPS signal…', null);
      navigator.geolocation.getCurrentPosition(
        pos => {
          const lat = pos.coords.latitude.toFixed(4);
          const lng = pos.coords.longitude.toFixed(4);
          const acc = Math.round(pos.coords.accuracy);
          setLocStatus(`${lat}°, ${lng}° ±${acc}m`, true);
          resolve(pos.coords);
        },
        err => {
          const msgs = { 1: 'Permission denied', 2: 'Position unavailable', 3: 'Timeout' };
          setLocStatus(msgs[err.code] || 'Location error', false);
          reject(err);
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
      );
    });
  }

  /* ── Location modal ── */
  function showLocModal() {
    return new Promise(resolve => {
      const overlay = document.createElement('div');
      overlay.className = 'modal-overlay';
      overlay.innerHTML = `
        <div class="modal-box">
          <div class="modal-icon">📍</div>
          <div class="modal-title">Location Access</div>
          <div class="modal-text">
            Elite POS uses your location to verify login security, enable accurate tax calculations, and log access events.
          </div>
          <div class="modal-btns">
            <button class="modal-btn mb-allow" id="mbAllow">Allow access</button>
            <button class="modal-btn mb-deny"  id="mbDeny">Not now</button>
          </div>
        </div>`;
      document.body.appendChild(overlay);
      document.getElementById('mbAllow').onclick = () => { overlay.remove(); resolve(true); };
      document.getElementById('mbDeny').onclick  = () => { overlay.remove(); resolve(false); };
    });
  }

  /* ── Form submit ── */
  const storedPerm = localStorage.getItem('pos_loc_perm');
  const btn = document.getElementById('loginBtn');

  document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    btn.disabled = true;
    btn.innerHTML = '<div class="spinner"></div> Processing…';

    try {
      let consent;
      if (storedPerm === null) {
        btn.innerHTML = '<div class="spinner"></div> Requesting location…';
        consent = await showLocModal();
        localStorage.setItem('pos_loc_perm', consent ? 'granted' : 'denied');
      } else {
        consent = storedPerm === 'granted';
      }

      document.getElementById('location_consent').value = consent ? 'granted' : 'denied';

      if (consent) {
        btn.innerHTML = '<div class="spinner"></div> Getting location…';
        try {
          const coords = await getPreciseLocation();
          document.getElementById('latitude').value  = coords.latitude;
          document.getElementById('longitude').value = coords.longitude;
          document.getElementById('accuracy').value  = coords.accuracy;
        } catch (_) {}
      }

      btn.innerHTML = '<div class="spinner"></div> Signing in…';
      setTimeout(() => this.submit(), 80);
    } catch (err) {
      btn.disabled = false;
      btn.innerHTML = 'Sign in';
    }
  });
</script>
</body>
</html>