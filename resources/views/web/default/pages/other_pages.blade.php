@extends(getTemplate().'.layouts.app')

@section('content')
    <section class="cart-banner position-relative text-center">
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-12 col-md-9 col-lg-7">
                    <h1 class="font-30 text-white font-weight-bold">{{ $page->title }}</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="container mt-10 mt-md-40">
        <div class="row">
            <div class="col-12">
                <div class="policy-description">
                    {!! nl2br($page->content) !!}
                </div>
            </div>
        </div>
    </section>

    @if(str_contains($page->link, 'newsletter'))
      <div style="max-width:480px;margin:30px auto;text-align:center;">
        <getresponse-form form-id="3172832d-314b-4dc0-9bf2-efd30abc46b1" e="0"></getresponse-form>
        <script async src="https://rnlpq.gr-cdn.com/getresponse-plugin-loader/v1/loader.js"></script>
      </div>
    @endif
@endsection

@push('scripts_bottom')
@if(str_contains($page->link, 'newsletter'))
<script>
  if (typeof fbq !== 'undefined') {
    fbq('track', 'Lead', { content_name: 'Newsletter Signup' });
  }
</script>
@endif
@endpush
