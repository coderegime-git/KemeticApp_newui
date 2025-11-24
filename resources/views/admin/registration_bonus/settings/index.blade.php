@extends('admin.layouts.app')

@push('libraries_top')

@endpush


@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{ $pageTitle }}!</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a>
            </div>
            <div class="breadcrumb-item active">
                {{ trans('update.registration_bonus_settings') }}
            </div>
        </div>
    </div>

    <div class="section-body">

        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-body">

                        <div class="section-body">

                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <ul class="nav nav-pills" id="myTab3" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="basic-tab" data-toggle="tab"
                                                        href="#basic" role="tab" aria-controls="basic"
                                                        aria-selected="true">Basic</a>
                                                </li>

                                                <li class="nav-item">
                                                    <a class="nav-link" id="terms-tab" data-toggle="tab" href="#terms"
                                                        role="tab" aria-controls="terms" aria-selected="true">Terms</a>
                                                </li>
                                            </ul>

                                            <div class="tab-content" id="myTabContent2">
                                                <div class="tab-pane mt-3 fade show active" id="basic" role="tabpanel"
                                                    aria-labelledby="basic-tab">
                                                    <div class="row">
                                                        <div class="col-12 col-md-6">
                                                            <form action="{{ getAdminPanelUrl() }}/registration_bonus/settings" method="post">
                                                                @csrf
                                                                <input type="hidden" name="page" value="general">
                                                                <input type="hidden" name="name"
                                                                    value="registration_bonus_settings">
                                                                <input type="hidden" name="locale" value="en">



                                                                <div class="form-group custom-switches-stacked ">
                                                                    <label
                                                                        class="custom-switch pl-0 d-flex align-items-center">
                                                                        <input type="hidden" name="value[status]" value="{{getRegistrationBonusSettings('status')??0}}">
                                                                        <input type="checkbox" name="value[status]"
                                                                            id="statusSwitch" value="{{ getRegistrationBonusSettings('status') ?? 0 }}"
                                                                            {{ getRegistrationBonusSettings('status') == 1 ? 'checked' : '' }}
                                                                            class="custom-switch-input" data-setting="status" />
                                                                        <span class="custom-switch-indicator"></span>
                                                                        <label
                                                                            class="custom-switch-description mb-0 cursor-pointer"
                                                                            for="statusSwitch">Active</label>
                                                                    </label>

                                                                    <div class="text-muted text-small">By activating this
                                                                        feature, registration bonus will be awarded for new
                                                                        users</div>
                                                                </div>

                                                                <div class="form-group custom-switches-stacked ">
                                                                    <label
                                                                        class="custom-switch pl-0 d-flex align-items-center">
                                                                        <input type="hidden"
                                                                            name="value[unlock_registration_bonus_instantly]"
                                                                            value="{{getRegistrationBonusSettings('unlock_registration_bonus_instantly')??0}}">
                                                                        <input type="checkbox" name="value[unlock_registration_bonus_instantly]"
                                                                            id="unlock_registration_bonus_instantlySwitch"
                                                                            value="{{ getRegistrationBonusSettings('unlock_registration_bonus_instantly') ?? 0 }}"
                                                                            {{ getRegistrationBonusSettings('unlock_registration_bonus_instantly') == 1 ? 'checked' : '' }}
                                                                            class="custom-switch-input" data-setting="unlock_registration_bonus_instantly" />
                                                                        <span class="custom-switch-indicator"></span>
                                                                        <label
                                                                            class="custom-switch-description mb-0 cursor-pointer"
                                                                            for="unlock_registration_bonus_instantlySwitch">Unlock
                                                                            registration bonus instantly</label>
                                                                    </label>

                                                                    <div class="text-muted text-small">Users can use the
                                                                        bonus instantly after registration and can make
                                                                        purchases</div>
                                                                </div>

                                                                <div
                                                                    class="js-unlock-registration-bonus-with-referral-field form-group custom-switches-stacked {{getRegistrationBonusSettings('unlock_registration_bonus_instantly') == 1?'d-none':''}}">
                                                                    <label
                                                                        class="custom-switch pl-0 d-flex align-items-center">
                                                                        <input type="hidden"
                                                                            name="value[unlock_registration_bonus_with_referral]"
                                                                            value="{{getRegistrationBonusSettings('unlock_registration_bonus_with_referral')??0}}">
                                                                            <input type="checkbox" name="value[unlock_registration_bonus_with_referral]"
                                                                                id="unlock_registration_bonus_with_referralSwitch"
                                                                                value="{{ getRegistrationBonusSettings('unlock_registration_bonus_with_referral') ?? 0 }}"
                                                                                {{ getRegistrationBonusSettings('unlock_registration_bonus_with_referral') == 1 ? 'checked' : '' }}
                                                                                class="custom-switch-input" data-setting="unlock_registration_bonus_with_referral" />
                                                                        <span class="custom-switch-indicator"></span>
                                                                        <label
                                                                            class="custom-switch-description mb-0 cursor-pointer"
                                                                            for="unlock_registration_bonus_with_referralSwitch">Unlock
                                                                            registration bonus with referral</label>
                                                                    </label>

                                                                    <div class="text-muted text-small">Users need to refer a
                                                                        specific number of users to unlock the bonus</div>
                                                                </div>

                                                                <div class="js-number-of-referred-users-field form-group {{getRegistrationBonusSettings('unlock_registration_bonus_with_referral') == 0?'d-none':''}}">
                                                                    <label>Number of referred users</label>
                                                                    <input type="number"
                                                                        name="value[number_of_referred_users]" value="{{getRegistrationBonusSettings('number_of_referred_users')}}"
                                                                        class="form-control" />
                                                                    <div class="text-muted text-small mt-1">How many users
                                                                        need to be referred to the platform to unlock the
                                                                        bonus</div>
                                                                </div>

                                                                <div
                                                                    class="js-enable-referred-users-purchase-field form-group custom-switches-stacked {{getRegistrationBonusSettings('unlock_registration_bonus_instantly') == 1?'d-none':''}}">
                                                                    <label
                                                                        class="custom-switch pl-0 d-flex align-items-center">
                                                                        <input type="hidden"
                                                                            name="value[enable_referred_users_purchase]"
                                                                            value="{{getRegistrationBonusSettings('enable_referred_users_purchase')??0}}">
                                                                        <input type="checkbox"
                                                                            name="value[enable_referred_users_purchase]"
                                                                            id="enable_referred_users_purchaseSwitch"
                                                                            value="{{getRegistrationBonusSettings('enable_referred_users_purchase')??0}}" {{ getRegistrationBonusSettings('enable_referred_users_purchase') == 1 ? 'checked' : '' }}
                                                                            class="custom-switch-input" data-setting="enable_referred_users_purchase"/>
                                                                        <span class="custom-switch-indicator"></span>
                                                                        <label
                                                                            class="custom-switch-description mb-0 cursor-pointer"
                                                                            for="enable_referred_users_purchaseSwitch">Enable
                                                                            referred users purchase</label>
                                                                    </label>

                                                                    <div class="text-muted text-small">Each referred user
                                                                        needs to purchase a specific amount to unlock the
                                                                        bonus</div>
                                                                </div>

                                                                <div
                                                                    class="js-purchase-amount-for-unlocking-bonus-field form-group {{getRegistrationBonusSettings('enable_referred_users_purchase') == 0?'d-none':''}}">
                                                                    <label>Purchase amount for unlocking bonus</label>
                                                                    <input type="number"
                                                                        name="value[purchase_amount_for_unlocking_bonus]"
                                                                        value="{{getRegistrationBonusSettings('purchase_amount_for_unlocking_bonus')}}" class="form-control" />
                                                                    <div class="text-muted text-small mt-1">The amount that
                                                                        each referred user needs to make purchse on the
                                                                        platform to unlock the bonus</div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Registration bonus amount</label>
                                                                    <input type="number"
                                                                        name="value[registration_bonus_amount]" value="{{getRegistrationBonusSettings('registration_bonus_amount')}}"
                                                                        class="form-control" />
                                                                    <div class="text-muted text-small mt-1">This amount will
                                                                        be charged to the user balance after registration
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Bonus wallet</label>
                                                                    <select name="value[bonus_wallet]" class="form-control">
                                                                        <option {{getRegistrationBonusSettings('bonus_wallet') == 'income_wallet'?'selected':''}} value="income_wallet">Income wallet</option>
                                                                        <option {{getRegistrationBonusSettings('bonus_wallet') == 'balance_wallet'?'selected':''}} value="balance_wallet">Balance
                                                                            wallet</option>
                                                                    </select>
                                                                    <div class="text-muted text-small mt-1">The registration
                                                                        bonus will be charged to this wallet. The income
                                                                        wallet is withdrawable.</div>
                                                                </div>


                                                                <button type="submit"
                                                                    class="btn btn-primary mt-1">Save</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane mt-3 fade" id="terms" role="tabpanel"
                                                    aria-labelledby="terms-tab">
                                                    <form action="/admin/registration_bonus/settings" method="post">
                                                        <input type="hidden" name="_token"
                                                            value="StbTiVZEefTsCwA1da5FFrdvfYXoR9qBVgCgBJJ1">
                                                        <input type="hidden" name="page" value="general">
                                                        <input type="hidden" name="name"
                                                            value="registration_bonus_terms_settings">

                                                        <div class="row">
                                                            <div class="col-12 col-md-6">
                                                                <div class="form-group">
                                                                    <label class="input-label">Language</label>
                                                                    <select name="locale"
                                                                        class="form-control js-edit-content-locale">
                                                                        <option value="EN" selected>English</option>
                                                                        <option value="AR">Arabic</option>
                                                                        <option value="ES">Spanish</option>
                                                                    </select>
                                                                </div>


                                                                <div class="form-group mt-15">
                                                                    <label class="input-label">Image</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <button type="button"
                                                                                class="input-group-text admin-file-manager"
                                                                                data-input="term_image"
                                                                                data-preview="holder">
                                                                                <i class="fa fa-upload"></i>
                                                                            </button>
                                                                        </div>
                                                                        <input type="text" name="value[term_image]"
                                                                            id="term_image"
                                                                            value="/store/1/default_images/registration bonus/banner.png"
                                                                            class="form-control " />
                                                                        <div class="input-group-append">
                                                                            <button type="button"
                                                                                class="input-group-text admin-file-view"
                                                                                data-input="term_image">
                                                                                <i class="fa fa-eye"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div id="addAccountTypes">

                                                                    <button type="button"
                                                                        class="btn btn-success add-btn mb-4 fa fa-plus"></button>


                                                                    <div class="form-group d-flex align-items-start">
                                                                        <div class="px-2 py-1 border flex-grow-1">

                                                                            <div class="form-group mb-1">
                                                                                <label class="mb-1">Icon</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <button type="button"
                                                                                            class="input-group-text admin-file-manager"
                                                                                            data-input="icon_record"
                                                                                            data-preview="holder">
                                                                                            <i class="fa fa-upload"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <input type="text"
                                                                                        name="value[items][DnrPr][icon]"
                                                                                        id="icon_DnrPr"
                                                                                        value="/store/1/default_images/registration bonus/step1.svg"
                                                                                        class="form-control" />
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group mb-1">
                                                                                <label class="mb-1">Title</label>
                                                                                <input type="text"
                                                                                    name="value[items][DnrPr][title]"
                                                                                    class="form-control" value="Sign up" />
                                                                            </div>

                                                                            <div class="form-group mb-1">
                                                                                <label class="mb-1">Description</label>
                                                                                <input type="text"
                                                                                    name="value[items][DnrPr][description]"
                                                                                    class="form-control"
                                                                                    value="Create an account on platform and get $50" />
                                                                            </div>
                                                                        </div>
                                                                        <button type="button"
                                                                            class="fas fa-times btn ml-2 remove-btn btn-danger"></button>
                                                                    </div>
                                                                    <div class="form-group d-flex align-items-start">
                                                                        <div class="px-2 py-1 border flex-grow-1">

                                                                            <div class="form-group mb-1">
                                                                                <label class="mb-1">Icon</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <button type="button"
                                                                                            class="input-group-text admin-file-manager"
                                                                                            data-input="icon_record"
                                                                                            data-preview="holder">
                                                                                            <i class="fa fa-upload"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <input type="text"
                                                                                        name="value[items][eNMTB][icon]"
                                                                                        id="icon_eNMTB"
                                                                                        value="/store/1/default_images/registration bonus/step2.svg"
                                                                                        class="form-control" />
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group mb-1">
                                                                                <label class="mb-1">Title</label>
                                                                                <input type="text"
                                                                                    name="value[items][eNMTB][title]"
                                                                                    class="form-control"
                                                                                    value="Refer your friends" />
                                                                            </div>

                                                                            <div class="form-group mb-1">
                                                                                <label class="mb-1">Description</label>
                                                                                <input type="text"
                                                                                    name="value[items][eNMTB][description]"
                                                                                    class="form-control"
                                                                                    value="Refer at least 5 users to the system using your affiliate URL" />
                                                                            </div>
                                                                        </div>
                                                                        <button type="button"
                                                                            class="fas fa-times btn ml-2 remove-btn btn-danger"></button>
                                                                    </div>
                                                                    <div class="form-group d-flex align-items-start">
                                                                        <div class="px-2 py-1 border flex-grow-1">

                                                                            <div class="form-group mb-1">
                                                                                <label class="mb-1">Icon</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <button type="button"
                                                                                            class="input-group-text admin-file-manager"
                                                                                            data-input="icon_record"
                                                                                            data-preview="holder">
                                                                                            <i class="fa fa-upload"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <input type="text"
                                                                                        name="value[items][fdIUc][icon]"
                                                                                        id="icon_fdIUc"
                                                                                        value="/store/1/default_images/registration bonus/step3.svg"
                                                                                        class="form-control" />
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group mb-1">
                                                                                <label class="mb-1">Title</label>
                                                                                <input type="text"
                                                                                    name="value[items][fdIUc][title]"
                                                                                    class="form-control"
                                                                                    value="Reach purchase target" />
                                                                            </div>

                                                                            <div class="form-group mb-1">
                                                                                <label class="mb-1">Description</label>
                                                                                <input type="text"
                                                                                    name="value[items][fdIUc][description]"
                                                                                    class="form-control"
                                                                                    value="Each referred user should purchase $100 on the platform" />
                                                                            </div>
                                                                        </div>
                                                                        <button type="button"
                                                                            class="fas fa-times btn ml-2 remove-btn btn-danger"></button>
                                                                    </div>
                                                                    <div class="form-group d-flex align-items-start">
                                                                        <div class="px-2 py-1 border flex-grow-1">

                                                                            <div class="form-group mb-1">
                                                                                <label class="mb-1">Icon</label>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <button type="button"
                                                                                            class="input-group-text admin-file-manager"
                                                                                            data-input="icon_record"
                                                                                            data-preview="holder">
                                                                                            <i class="fa fa-upload"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                    <input type="text"
                                                                                        name="value[items][oeMZr][icon]"
                                                                                        id="icon_oeMZr"
                                                                                        value="/store/1/default_images/registration bonus/step4.svg"
                                                                                        class="form-control" />
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group mb-1">
                                                                                <label class="mb-1">Title</label>
                                                                                <input type="text"
                                                                                    name="value[items][oeMZr][title]"
                                                                                    class="form-control"
                                                                                    value="Unlock your bonus" />
                                                                            </div>

                                                                            <div class="form-group mb-1">
                                                                                <label class="mb-1">Description</label>
                                                                                <input type="text"
                                                                                    name="value[items][oeMZr][description]"
                                                                                    class="form-control"
                                                                                    value="Your bonus will be unlocked! Enjoy spending..." />
                                                                            </div>
                                                                        </div>
                                                                        <button type="button"
                                                                            class="fas fa-times btn ml-2 remove-btn btn-danger"></button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary mt-1">Save</button>
                                                    </form>

                                                    <div class="main-row d-none">
                                                        <div class="form-group d-flex align-items-start">
                                                            <div class="px-2 py-1 border flex-grow-1">

                                                                <div class="form-group mb-1">
                                                                    <label class="mb-1">Icon</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <button type="button"
                                                                                class="input-group-text admin-file-manager"
                                                                                data-input="icon_record"
                                                                                data-preview="holder">
                                                                                <i class="fa fa-upload"></i>
                                                                            </button>
                                                                        </div>
                                                                        <input type="text" name="value[items][record][icon]"
                                                                            id="icon_record" value=""
                                                                            class="form-control" />
                                                                    </div>
                                                                </div>

                                                                <div class="form-group mb-1">
                                                                    <label class="mb-1">Title</label>
                                                                    <input type="text" name="value[items][record][title]"
                                                                        class="form-control" />
                                                                </div>

                                                                <div class="form-group mb-1">
                                                                    <label class="mb-1">Description</label>
                                                                    <input type="text"
                                                                        name="value[items][record][description]"
                                                                        class="form-control" />
                                                                </div>
                                                            </div>
                                                            <button type="button"
                                                                class="fas fa-times btn ml-2 remove-btn btn-danger d-none"></button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
</section>





@endsection

@push('scripts_bottom')
<script src="/assets/default/js/admin/settings/site_bank_accounts.min.js"></script>
<script src="/assets/default/js/admin/registration_bonus_settings.min.js"></script>
<script>
    $(document).ready(function() {
        $('.custom-switch-input').on('change', function() {
            // Toggle the value between 1 and 0
            let currentValue = $(this).is(':checked') ? 1 : 0;
            
            // Update the value attribute to reflect the toggled value
            $(this).val(currentValue);
            
            // If there's a hidden input with the same name, update its value too
            let settingName = $(this).data('setting');
            
            if (currentValue == 1 && settingName === 'unlock_registration_bonus_instantly') {
                // Update the related hidden fields to 0
                $('input[type="hidden"][name="value[unlock_registration_bonus_with_referral]"]').val(0);
                $('input[type="hidden"][name="value[enable_referred_users_purchase]"]').val(0);
                
                // If checkboxes with these names exist, uncheck them
                $('input[type="checkbox"][name="value[unlock_registration_bonus_with_referral]"]').prop('checked', false).val(0);
                $('input[type="checkbox"][name="value[enable_referred_users_purchase]"]').prop('checked', false).val(0);
            }
            
            // Update the value of the current checkbox's associated hidden field
            $(`input[type="hidden"][name="value[${settingName}]"]`).val(currentValue);
        });
    });
</script>
@endpush