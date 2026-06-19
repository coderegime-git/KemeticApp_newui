@extends(getTemplate().'.layouts.app')
<style>
  :root{
    --bg:#0b0b0f;            /* royal dark purple ~ near black */
    --panel:#141420;
    --ink:#ffffff;
    --muted:#cfcfe4;
    --accent:#ffd769;        /* gold */
    --line:rgba(255,255,255,.12);
    --success:#22c55e;
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
  .top-actions{display:flex; gap:10px}
  .pill{
    display:inline-flex; align-items:center; gap:10px;
    padding:10px 14px; border-radius:999px; background:rgba(255,255,255,.06);
    color:var(--ink); border:1px solid var(--line); cursor:pointer; user-select:none;
  }
  .pill[aria-pressed="true"]{ background:rgba(255,215,105,.14); border-color:rgba(255,215,105,.5); color:var(--accent) }

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
  }
  .cta:hover{ filter:brightness(1.05) }

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

  .footer-actions{display:flex; gap:12px; padding:20px 18px; border-top:1px solid var(--line); background:rgba(255,255,255,.02)}
  .link-btn, .ghost{
    display:inline-flex; align-items:center; gap:10px; padding:12px 16px; border-radius:12px; text-decoration:none; font-weight:700;
  }
  .link-btn{ background:rgba(255,215,105,.14); color:var(--accent); border:1px solid rgba(255,215,105,.45) }
  .ghost{ background:rgba(255,255,255,.05); color:var(--ink); border:1px solid var(--line) }

  /* Chakra hint accents for fun */
  .chakra{display:flex; gap:6px; margin-top:8px}
  .dot{width:8px; height:8px; border-radius:50%}
  .c1{background:#ff5959}.c2{background:#ffb84d}.c3{background:#ffe34d}.c4{background:#7bd66f}.c5{background:#4dd8ff}.c6{background:#7a7cff}.c7{background:#e57aff}
</style>

@section('content')


    @if(!empty($order) && $order->status === \App\Models\Order::$paid)
        <div class="no-result default-no-result my-50 d-flex align-items-center justify-content-center flex-column">
            <div class="no-result-logo">
                <img src="/assets/default/img/no-results/search.png" alt="">
            </div>
            <div class="d-flex align-items-center flex-column mt-30 text-center">
                <h2>{{ trans('cart.success_pay_title') }}</h2>
                <p class="mt-5 text-center">{!! trans('cart.success_pay_msg') !!}</p>

                <a href="academyapp://payment-success" class="btn btn-sm btn-primary mt-20 d-flex d-sm-none">{{ trans('public.redirect_to_app') }}</a>

                <a href="/panel" class="btn btn-sm btn-primary mt-20 d-none d-sm-flex">{{ trans('public.my_panel') }}</a>
            </div>
        </div>
    @endif

    @if(!empty($order) && $order->status === \App\Models\Order::$fail)
        <div class="no-result status-failed my-50 d-flex align-items-center justify-content-center flex-column">
            <div class="no-result-logo">
                <img src="/assets/default/img/no-results/failed_pay.png" alt="">
            </div>
            <div class="d-flex align-items-center flex-column mt-30 text-center">
                <h2>{{ trans('cart.failed_pay_title') }}</h2>
                <p class="mt-5 text-center">{!! nl2br(trans('cart.failed_pay_msg')) !!}</p>

                <a href="academyapp://payment-failed" class="btn btn-sm btn-primary mt-20 d-flex d-sm-none">{{ trans('public.redirect_to_app') }}</a>

                <a href="/panel" class="btn btn-sm btn-primary mt-20 d-none d-sm-flex" style="margin-bottom: 10px;">{{ trans('public.my_panel') }}</a>
            </div>
        </div>
    @endif


@endsection
