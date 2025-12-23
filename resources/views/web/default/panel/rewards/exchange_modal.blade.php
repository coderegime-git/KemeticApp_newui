<style>
    .kemetic-modal {
    background: #141414ff;
    border-radius: 1rem;
    padding: 2rem;
    width: 100%;
    max-width: 420px;
    margin: 0 auto;
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.kemetic-modal-header .section-title {
    font-weight: 700;
    color: #1e1e2d;
    font-size: 1.25rem;
}

.kemetic-modal-body {
    padding: 0 1rem;
}

.kemetic-modal-img {
    max-width: 120px;
}

.kemetic-modal-body p {
    color: #6b7280;
    font-size: 0.875rem;
}

.kemetic-modal-footer {
    justify-content: space-between;
}

.kemetic-modal-footer .btn {
    border-radius: 0.6rem;
    font-weight: 600;
    padding: 0.6rem 0;
    transition: all 0.3s ease;
}

.kemetic-modal-footer .btn-primary {
    background-color: #3b82f6;
    border-color: #3b82f6;
}

.kemetic-modal-footer .btn-primary:hover {
    background-color: #2563eb;
    border-color: #2563eb;
}

.kemetic-modal-footer .btn-outline-danger {
    color: #ef4444;
    border: 1px solid #ef4444;
}

.kemetic-modal-footer .btn-outline-danger:hover {
    background-color: #ef4444;
    color: #fff;
}

</style>
<div class="kemetic-modal d-none" id="exchangePointsModal">
    <div class="kemetic-modal-header text-center">
        <h3 class="section-title font-16 text-dark-blue mb-25">{{ trans('update.exchange_points') }}</h3>
    </div>

    <div class="kemetic-modal-body text-center">
        <img src="/assets/default/img/rewards/wallet.png" class="kemetic-modal-img mb-25" alt="wallet">

        <p class="font-14 font-weight-500 text-gray mt-20">
            <span class="d-block">{{ trans('update.you_will_get_n_for_points',['amount' => handlePrice($earnByExchange) ,'points' => $availablePoints]) }}</span>
            <span class="d-block mt-1">{{ trans('update.the_amount_will_be_charged_to_your_wallet') }}</span>
            <span class="d-block mt-1">{{ trans('update.do_you_want_to_proceed') }}</span>
        </p>
    </div>

    <div class="kemetic-modal-footer d-flex mt-30">
        <button type="button" class="js-apply-exchange btn btn-primary flex-grow-1">{{ trans('update.exchange') }}</button>
        <button type="button" class="close-swl btn btn-outline-danger ml-15 flex-grow-1">{{ trans('public.close') }}</button>
    </div>
</div>
