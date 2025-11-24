<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Kemetic — Membership Confirmation</title>
<style>
  :root{
    --bg:#0b0b0f;
    --panel:#141420;
    --ink:#ffffff;
    --muted:#cfcfe4;
    --accent:#ffd769;
    --line:rgba(255,255,255,.12);
    --success:#22c55e;
    --warning:#f59e0b;
    --error:#ef4444;
    --shadow:0 18px 50px rgba(0,0,0,.45);
    --radius:18px;
  }
  *{box-sizing:border-box}
  body{
    margin:0; background:radial-gradient(1200px 600px at 70% -10%, #231a3a 0%, #120f1d 45%, var(--bg) 100%);
    color:var(--ink); font:16px/1.55 system-ui, -apple-system, Segoe UI, Roboto, Inter, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
  }
  .wrap{max-width:1140px; margin:32px auto; padding:0 24px}
  header{display:flex; align-items:center; justify-content:space-between; margin-bottom:22px}
  .brand{display:flex; align-items:center; gap:12px; font-weight:800; letter-spacing:.3px}
  .brand .mark{width:34px; height:34px; border-radius:10px; background:conic-gradient(from 120deg, #7c3aed, #22d3ee, #a3e635, #facc15, #f472b6, #7c3aed); box-shadow:0 0 0 3px rgba(255,255,255,.06)}
  
  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
  }
  .status-active { background: rgba(34, 197, 94, 0.15); color: var(--success); border: 1px solid rgba(34, 197, 94, 0.5); }
  .status-pending { background: rgba(245, 158, 11, 0.15); color: var(--warning); border: 1px solid rgba(245, 158, 11, 0.5); }

  .confirm-card{
    display:grid; grid-template-columns:1fr 360px; gap:22px;
    background:linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.02));
    border:1px solid var(--line); border-radius:var(--radius); box-shadow:var(--shadow);
    padding:28px;
  }
  @media (max-width:900px){ .confirm-card{grid-template-columns:1fr} }

  .hero{
    background: radial-gradient(600px 260px at 10% -20%, rgba(124,58,237,.35), transparent 60%);
    border-radius:14px; padding:26px; min-height:220px; position:relative; overflow:hidden;
  }
  .hero .check{
    width:64px; height:64px; border-radius:50%;
    background:rgba(34,197,94,.15); display:grid; place-items:center; border:1px solid rgba(34,197,94,.5);
    color:var(--success); font-size:34px; margin-bottom:12px;
  }
  h1{margin:10px 0 4px; font-size:34px; letter-spacing:.2px}
  .sub{color:var(--muted); font-size:18px; margin:0 0 16px}
  .cta{
    display:inline-flex; align-items:center; justify-content:center; gap:10px;
    padding:14px 22px; border-radius:999px; background:var(--accent); color:#1a1400;
    font-weight:800; border:none; cursor:pointer; box-shadow:0 10px 24px rgba(255,215,105,.25);
    text-decoration: none;
  }
  .cta:hover{ filter:brightness(1.05) }
  .cta.secondary {
    background: rgba(255,255,255,.1);
    color: var(--ink);
    box-shadow: none;
    margin-left: 12px;
  }

  .aside{
    background:rgba(255,255,255,.04); border:1px solid var(--line);
    border-radius:14px; padding:18px;
  }
  .aside h3{margin:6px 0 10px; font-size:16px; color:var(--muted)}
  .kpi{display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px dashed var(--line)}
  .kpi:last-child{border-bottom:0}
  .kpi strong{font-size:18px}
  .mono{font-variant-numeric:tabular-nums; font-weight:700}

  .receipt{
    margin-top:22px; background:rgba(255,255,255,.04); border:1px solid var(--line);
    border-radius:14px; overflow:hidden;
  }
  .table{width:100%; border-collapse:collapse}
  .table th, .table td{padding:14px 16px; text-align:left}
  .table thead{background:rgba(255,255,255,.05); color:var(--muted)}
  .table tbody tr{border-top:1px solid var(--line)}
  .table tfoot tr{border-top:1px solid var(--line); background:rgba(255,215,105,.06)}
  .table tfoot td{font-weight:900}
  .meta{display:grid; grid-template-columns:repeat(4,1fr); gap:14px; padding:18px}
  .meta .cell{background:rgba(255,255,255,.03); border:1px solid var(--line); border-radius:10px; padding:12px}
  .meta .label{color:var(--muted); font-size:12px; text-transform:uppercase; letter-spacing:.6px; margin-bottom:6px}
  @media (max-width:900px){ .meta{grid-template-columns:1fr 1fr} }

  .usage-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
    margin: 20px 0;
  }
  .usage-card {
    background: rgba(255,255,255,.03);
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 16px;
    text-align: center;
  }
  .usage-value {
    font-size: 24px;
    font-weight: 800;
    margin: 8px 0;
  }
  .usage-label {
    color: var(--muted);
    font-size: 14px;
  }

  .footer-actions{display:flex; gap:12px; padding:20px 18px; border-top:1px solid var(--line); background:rgba(255,255,255,.02); flex-wrap: wrap;}
  .link-btn, .ghost{
    display:inline-flex; align-items:center; gap:10px; padding:12px 16px; border-radius:12px; text-decoration:none; font-weight:700;
  }
  .link-btn{ background:rgba(255,215,105,.14); color:var(--accent); border:1px solid rgba(255,215,105,.45) }
  .ghost{ background:rgba(255,255,255,.05); color:var(--ink); border:1px solid var(--line) }

  .chakra{display:flex; gap:6px; margin-top:8px}
  .dot{width:8px; height:8px; border-radius:50%}
  .c1{background:#ff5959}.c2{background:#ffb84d}.c3{background:#ffe34d}.c4{background:#7bd66f}.c5{background:#4dd8ff}.c6{background:#7a7cff}.c7{background:#e57aff}
</style>
</head>
<body>
  <div class="wrap">
    <header>
      <div class="brand">
        <div class="mark" aria-hidden="true"></div>
        <div>Kemetic <span style="opacity:.7;font-weight:600">Membership</span></div>
      </div>
      <div class="status-badge {{ $isActive ? 'status-active' : 'status-pending' }}">
        {{ $isActive ? '✓ Active' : '⏳ Pending Activation' }}
      </div>
    </header>

    <section class="confirm-card">
      <div class="hero">
        <div class="check" aria-hidden="true">{{ $isActive ? '✓' : '⏳' }}</div>
        <h1>Welcome to Kemetic!</h1>
        <p id="joinedCopy" class="sub">
          You've subscribed to <strong>{{ $subscribe->title }}</strong> plan.
        </p>
        
        @if($isActive && isset($activeSubscribe))
        <div class="usage-stats">
          <div class="usage-card">
            <div class="usage-value">
              @if($activeSubscribe->infinite_use)
                ∞
              @else
                {{ max(0, $activeSubscribe->usable_count - $activeSubscribe->used_count) }}
              @endif
            </div>
            <div class="usage-label">Courses Remaining</div>
          </div>
          <div class="usage-card">
            <div class="usage-value">
              {{ max(0, $activeSubscribe->days - \App\Models\Subscribe::getDayOfUse(auth()->id() ?? session('device_id'))) }}
            </div>
            <div class="usage-label">Days Remaining</div>
          </div>
        </div>
        @endif

        <div>
          <a href="/" class="cta" id="exploreBtn">Start Exploring</a>
          @if($isActive)
          <a href="/panel/subscribes" class="cta secondary">Manage Subscription</a>
          @endif
        </div>
        
        <div class="chakra" aria-hidden="true">
          <span class="dot c1"></span><span class="dot c2"></span><span class="dot c3"></span>
          <span class="dot c4"></span><span class="dot c5"></span><span class="dot c6"></span><span class="dot c7"></span>
        </div>
      </div>

      <aside class="aside">
        <h3>Your Membership</h3>
        <div class="kpi"><span>Plan</span>     <strong id="kPlan">{{ $subscribe->title }}</strong></div>
        <div class="kpi"><span>Status</span>   <strong>{{ $isActive ? 'Active' : 'Pending' }}</strong></div>
        <div class="kpi"><span>Duration</span> <strong>{{ $subscribe->days }} days</strong></div>
        <div class="kpi"><span>Usage</span>    <strong>
          @if($isActive && isset($activeSubscribe))
            {{ $activeSubscribe->used_count }}/{{ $activeSubscribe->infinite_use ? '∞' : $activeSubscribe->usable_count }}
          @else
            N/A
          @endif
        </strong></div>
        <div class="kpi"><span>Amount</span>   <strong class="mono" id="kAmount">€{{ number_format($subscribe->getPrice(), 2) }}</strong></div>
      </aside>
    </section>

    <section class="receipt" aria-labelledby="receiptTitle">
      <table class="table">
        <thead>
          <tr><th id="receiptTitle">Receipt</th><th></th><th style="text-align:right">Amount</th></tr>
        </thead>
        <tbody>
          <tr>
            <td>Subscription — {{ $subscribe->title }}</td>
            <td></td>
            <td class="mono" style="text-align:right" id="lineSub">€{{ number_format($subscribe->price, 2) }}</td>
          </tr>
          @if($subscribe->activeSpecialOffer())
          <tr>
            <td>Special Offer Discount ({{ $subscribe->activeSpecialOffer()->percent }}%)</td>
            <td></td>
            <td class="mono" style="text-align:right; color: var(--success);">-€{{ number_format(($subscribe->price * $subscribe->activeSpecialOffer()->percent / 100), 2) }}</td>
          </tr>
          @endif
          <tr>
            <td>Tax (VAT)</td>
            <td></td>
            <td class="mono" style="text-align:right" id="lineTax">€0.00</td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td>Total</td><td></td>
            <td class="mono" style="text-align:right" id="lineTotal">€{{ number_format($subscribe->getPrice(), 2) }}</td>
          </tr>
        </tfoot>
      </table>

      <div class="meta">
        <div class="cell"><div class="label">Order #</div><div class="mono" id="orderId">{{ $order->id }}</div></div>
        <div class="cell"><div class="label">Date</div><div id="orderDate">{{ \Carbon\Carbon::parse($order->created_at)->format('M j, Y') }}</div></div>
        <div class="cell"><div class="label">Subscription ID</div><div>{{ $subscribe->id }}</div></div>
        <div class="cell"><div class="label">Payment Status</div><div style="color: var(--success);">{{ ucfirst($order->status) }}</div></div>
      </div>

      <div class="footer-actions">
        <a class="link-btn" href="/panel/subscribes" id="manageLink">Manage Subscription</a>
        <a class="ghost" href="/orders/{{ $order->id }}/invoice" id="invoiceLink">Download Invoice</a>
        <a class="ghost" href="#" id="shareLink">Share with Friends</a>
      </div>
    </section>
  </div>

<script>
  // Simple currency toggle functionality
  const kAmount = document.getElementById('kAmount');
  const lineSub = document.getElementById('lineSub');
  const lineTax = document.getElementById('lineTax');
  const lineTotal = document.getElementById('lineTotal');
  const joinedCopy = document.getElementById('joinedCopy');

  const originalPrice = {{ $subscribe->getPrice() }};
  const planTitle = "{{ $subscribe->title }}";

  function setCurrency(code) {
    const rate = code === 'USD' ? 1.1 : 1; // Example rate
    const symbol = code === 'USD' ? '$' : '€';
    const convertedPrice = originalPrice * rate;

    kAmount.textContent = `${symbol}${convertedPrice.toFixed(2)}`;
    lineSub.textContent = `${symbol}${convertedPrice.toFixed(2)}`;
    lineTax.textContent = `${symbol}0.00`;
    lineTotal.textContent = `${symbol}${convertedPrice.toFixed(2)}`;
    joinedCopy.innerHTML = `You've subscribed to <strong>${planTitle}</strong> plan.`;
  }

  // Wire up CTAs
  document.getElementById('exploreBtn').onclick = () => location.href = '/';
  document.getElementById('manageLink').onclick = (e) => {
    e.preventDefault();
    location.href = '/panel/subscribes';
  };
  document.getElementById('invoiceLink').onclick = (e) => {
    e.preventDefault();
    location.href = `/orders/{{ $order->id }}/invoice`;
  };
  document.getElementById('shareLink').onclick = (e) => {
    e.preventDefault();
    const text = `I just subscribed to Kemetic — unlock amazing courses and content! Join me: https://kemetic.app`;
    if (navigator.share) {
      navigator.share({
        title: 'Join Kemetic',
        text: text,
        url: 'https://kemetic.app'
      });
    } else {
      alert('Copy this link to share:\nhttps://kemetic.app');
    }
  };
</script>
</body>
</html>