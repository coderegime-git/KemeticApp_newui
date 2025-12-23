@extends('web.default.layouts.newapp')

@push('styles_top')
<style>
:root{
    --k-bg:#0b0b0b;
    --k-card:#141414;
    --k-gold:#d4af37;
    --k-border:#262626;
    --k-text:#eaeaea;
    --k-muted:#9ca3af;
}

/* Page wrapper */
.kemetic-create{
    background:var(--k-bg);
    color:var(--k-text);
    min-height:100vh;
}

/* Form card */
.k-create-card{
    background:var(--k-card);
    border:1px solid var(--k-border);
    border-radius:20px;
    padding:25px;
}

/* Footer */
.k-create-footer{
    background:linear-gradient(135deg,#141414,#1b1b1b);
    border-top:1px solid var(--k-border);
    border-radius:18px;
    padding:18px 22px;
}

/* Buttons */
.btn-kemetic{
    background:var(--k-gold);
    color:#000;
    border:none;
    border-radius:12px;
    font-weight:600;
    padding:8px 18px;
    transition:.3s;
}
.btn-kemetic:hover{
    background:#e5c252;
    box-shadow:0 6px 20px rgba(212,175,55,.3);
}

.btn-kemetic-outline{
    background:transparent;
    color:var(--k-gold);
    border:1px solid var(--k-gold);
    border-radius:12px;
    padding:8px 18px;
}
.btn-kemetic-outline:hover{
    background:rgba(212,175,55,.1);
}

/* Danger */
.btn-kemetic-danger{
    background:#dc2626;
    color:#fff;
    border-radius:12px;
}

/* Step content */
.k-step-content{
    animation:fadeSlide .35s ease;
}
@keyframes fadeSlide{
    from{opacity:0;transform:translateY(10px)}
    to{opacity:1;transform:translateY(0)}
}
</style>
@endpush


@section('content')
    <div class="kemetic-create">

        <form method="post" action="/panel/store/products/{{ !empty($product) ? $product->id .'/update' : 'store' }}" id="productForm" class="webinar-form">
            <div class="mb-25">
                @include('web.default.panel.store.products.create_includes.progress')
            </div>

            {{ csrf_field() }}
            <input type="hidden" name="current_step" value="{{ !empty($currentStep) ? $currentStep : 1 }}">
            <input type="hidden" name="draft" value="no" id="forDraft"/>
            <input type="hidden" name="get_next" value="no" id="getNext"/>
            <input type="hidden" name="get_step" value="0" id="getStep"/>


            <div class="k-create-card k-step-content">

                @if($currentStep == 1)
                    @include('web.default.panel.store.products.create_includes.step_1')
                @elseif(!empty($product))
                    @include('web.default.panel.store.products.create_includes.step_'.$currentStep)
                @endif

            </div>


        </form>

        <div class="k-create-footer d-flex flex-column flex-md-row align-items-center justify-content-between mt-25">

            {{-- Left controls --}}
            <div class="d-flex align-items-center">
        

            @if(!empty($product))
                <a href="/panel/store/products/{{ $product->id }}/step/{{ ($currentStep - 1) }}"
                   class="btn btn-kemetic-outline {{ $currentStep < 2 ? 'disabled' : '' }}">
                    {{ trans('webinars.previous') }}
                </a>
            @else
                <button class="btn btn-kemetic-outline disabled">
                    {{ trans('webinars.previous') }}
                </button>
            @endif

            <button type="button"
                    id="getNextStep"
                    class="btn btn-kemetic ml-15" style="margin-left: 10px;"
                    @if($currentStep >= 5) disabled @endif>
                {{ trans('webinars.next') }}
            </button>
        </div>

        {{-- Right controls --}}
        <div class="mt-20 mt-md-0 d-flex flex-wrap align-items-center">

            <button type="button"
                    id="sendForReview" style="margin-right: 10px;"
                    class="btn btn-kemetic mr-10">
                {{ trans('public.send_for_review') }}
            </button>

            <button type="button"
                    id="saveAsDraft"
                    class="btn btn-kemetic-outline mr-10">
                {{ trans('public.save_as_draft') }}
            </button>

            @if(!empty($product) and $product->creator_id == $authUser->id)
                @include('web.default.panel.includes.content_delete_btn', [
                    'deleteContentUrl' => "/panel/store/products/{$product->id}/delete?redirect_to=/panel/store/products",
                    'deleteContentClassName' => 'btn btn-kemetic-danger mt-10 mt-md-0',
                    'deleteContentItem' => $product,
                ])
            @endif

        </div>
    </div>
</div>
@endsection

@push('scripts_bottom')
    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var requestFailedLang = '{{ trans('public.request_failed') }}';
        var maxFourImageCanSelect = '{{ trans('update.max_four_image_can_select') }}';
    </script>

    <script src="/assets/default/js/panel/new_product.min.js"></script>
@endpush
