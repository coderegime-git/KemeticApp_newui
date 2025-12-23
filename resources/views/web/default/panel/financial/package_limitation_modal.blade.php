<style>
/* =========================
   KEMETIC UPGRADE MODAL
========================= */
.kemetic-upgrade-modal {
    background: #141414;
    border-radius: 18px;
    padding: 25px;
    box-shadow: 0 12px 40px rgba(0,0,0,.65);
    color: #f5f5f5;
    text-align: center;
}

.kemetic-upgrade-modal .section-title {
    color: #d4af37;
}

.kemetic-upgrade-modal .buy-with-points-modal-img {
    max-width: 80px;
    margin-bottom: 20px;
}

.kemetic-upgrade-modal p span {
    display: block;
    margin-bottom: 5px;
    color: #9a9a9a;
}

.kemetic-upgrade-modal .btn-kemetic-primary:hover {
    transform: translateY(-2px);
    background: #b8952c;
    color: #0b0b0b;
}

.kemetic-upgrade-modal .btn-kemetic-cancel:hover {
    background: #d4af37;
    color: #0b0b0b;
}
</style>
<div class="kemetic-upgrade-modal p-25 rounded-lg" style="background: #141414; box-shadow: 0 12px 40px rgba(0,0,0,.65); color: #f5f5f5; max-width: 400px; margin: auto;">

    {{-- Title --}}
    <h3 class="section-title font-16 mb-25" style="color: #d4af37; font-weight: 700; letter-spacing: 0.6px;">
        {{ trans('update.upgrade_your_plan') }}
    </h3>

    {{-- Diamond Image --}}
    <div class="text-center">
        <img src="/assets/default/img/icons/diamond.png" class="buy-with-points-modal-img" alt="diamond" style="width: 80px; height: 80px; margin-bottom: 20px;">

        {{-- Description --}}
        <p class="font-14 font-weight-500 text-muted mt-30" style="line-height: 1.6;">
            <span class="d-block">{{ trans('update.your_account_limited') }}</span>
            <span class="d-block">{{ trans('update.your_account_'. $type .'_limited_hint') }}</span>
            @if(!empty($currentCount))
                <span class="d-block">{{ trans('update.your_current_plan_'.$type,['count' => $currentCount]) }}</span>
            @endif
        </p>
    </div>

    {{-- Buttons --}}
    <div class="d-flex align-items-center mt-25">
        <a href="/panel/financial/registration-packages" 
           class="btn btn-kemetic-primary flex-grow-1" 
           style="background: #d4af37; color: #0b0b0b; font-weight: 700; border-radius: 18px; padding: 10px 20px; text-align: center; transition: transform 0.3s;">
           {{ trans('update.upgrade') }}
        </a>
        <button type="button" 
                class="btn btn-kemetic-cancel ml-15 flex-grow-1 close-swl" 
                style="background: transparent; border: 2px solid #d4af37; color: #d4af37; font-weight: 700; border-radius: 18px; padding: 10px 20px; transition: background 0.3s, color 0.3s;">
            {{ trans('public.cancel') }}
        </button>
    </div>
</div>
