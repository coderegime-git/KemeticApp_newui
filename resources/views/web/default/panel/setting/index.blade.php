@extends('web.default.layouts.newapp')
@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush
<style>
/* CHECKBOX */
.custom-control {
  position: relative;
  z-index: 1;
  display: block;
  min-height: 1.3rem;
  padding-left: 2rem;
  -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
}

.custom-control-inline {
  display: inline-flex;
  margin-right: 1rem;
}

.custom-control-input {
  position: absolute;
  left: 0;
  z-index: -1;
  width: 1.5rem;
  height: 1.4rem;
  opacity: 0;
}
.custom-control-input:checked ~ .custom-control-label::before {
  color: #ffffff;
  border-color: #43d477;
  background-color: #43d477;
}
.custom-control-input:focus ~ .custom-control-label::before {
  box-shadow: none, 1.5rem;
}
.custom-control-input:focus:not(:checked) ~ .custom-control-label::before {
  border-color: #43d477;
}
.custom-control-input:not(:disabled):active ~ .custom-control-label::before {
  color: #ffffff;
  background-color: #43d477;
  border-color: #43d477;
}
.custom-control-input[disabled] ~ .custom-control-label, .custom-control-input:disabled ~ .custom-control-label {
  color: #6c757d;
}
.custom-control-input[disabled] ~ .custom-control-label::before, .custom-control-input:disabled ~ .custom-control-label::before {
  background-color: #f1f1f1;
}

.custom-control-label {
  position: relative;
  margin-bottom: 0;
  vertical-align: top;
}
.custom-control-label::before {
  position: absolute;
  top: -0.1rem;
  left: -2rem;
  display: block;
  width: 1.5rem;
  height: 1.5rem;
  pointer-events: none;
  content: "";
  background-color: #ffffff;
  border: 2px solid #adb5bd;
  box-shadow: none;
}
.custom-control-label::after {
  position: absolute;
  top: -0.1rem;
  left: -2rem;
  display: block;
  width: 1.5rem;
  height: 1.5rem;
  content: "";
  background: 50%/50% 50% no-repeat;
}

.custom-checkbox .custom-control-label::before {
  border-radius: 0.25rem;
}
.custom-checkbox .custom-control-input:checked ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%23ffffff' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26l2.974 2.99L8 2.193z'/%3e%3c/svg%3e");
}
.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::before {
  border-color: #F2C94C;
  background-color: #F2C94C;
}#
.custom-checkbox .custom-control-input:indeterminate ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='4' viewBox='0 0 4 4'%3e%3cpath stroke='%23ffffff' d='M0 2h4'/%3e%3c/svg%3e");
}
.custom-checkbox .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: #F2C94C;
}
.custom-checkbox .custom-control-input:disabled:indeterminate ~ .custom-control-label::before {
  background-color: #F2C94C;
}

.custom-radio .custom-control-label::before {
  border-radius: 50%;
}
.custom-radio .custom-control-input:checked ~ .custom-control-label::after {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23ffffff'/%3e%3c/svg%3e");
}
.custom-radio .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(67, 212, 119, 0.5);
}

.custom-switch {
  padding-left: 3.125rem;
}
.custom-switch .custom-control-label::before {
  left: -3.125rem;
  width: 2.625rem;
  pointer-events: all;
  border-radius: 0.75rem;
}
.custom-switch .custom-control-label::after {
  top: calc(-0.1rem + 4px);
  left: calc(-3.125rem + 4px);
  width: calc(1.5rem - 8px);
  height: calc(1.5rem - 8px);
  background-color: #adb5bd;
  border-radius: 0.75rem;
  transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
@media (prefers-reduced-motion: reduce) {
  .custom-switch .custom-control-label::after {
    transition: none;
  }
}
.custom-switch .custom-control-input:checked ~ .custom-control-label::after {
  background-color: #ffffff;
  transform: translateX(1.125rem);
}
.custom-switch .custom-control-input:disabled:checked ~ .custom-control-label::before {
  background-color: rgba(67, 212, 119, 0.5);
}

/* Container */
.kemetic-actions {
    display: flex;
    gap: 12px;
    margin-top: 12px;
}

/* Base Button */
.kemetic-btn {
    padding: 8px 18px;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.5px;
    border-radius: 10px;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid transparent;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

/* Edit Button */
.kemetic-btn-edit {
    background: linear-gradient(135deg, #d4af37, #b8962e);
    color: #000;
    border-color: #d4af37;
}

.kemetic-btn-edit:hover {
    background: #000;
    color: #d4af37;
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.6);
}

/* Delete Button */
.kemetic-btn-delete {
    background: #000;
    color: #d4af37;
    border: 1px solid #d4af37;
}

.kemetic-btn-delete:hover {
    background: #8b0000;
    color: #fff;
    border-color: #8b0000;
    box-shadow: 0 0 10px rgba(139, 0, 0, 0.6);
}

</style>
@section('content')
    <main class="settings-main">
        <div class="settings-header">
            <div>
                <h1>Settings</h1>
                <span>Update your profile, address, membership and privacy.</span>
            </div>
            <div class="settings-pill">
                
            </div>
        </div>

        <!-- Profile summary -->
        <section class="settings-profile-card">
            <div class="settings-profile-card-main">
                <div class="settings-profile-avatar-big">
                    <span><img src="{{ $user->getAvatar(150) }}" alt="{{ $user->full_name }}" width="50" ></span>
                </div>
                <div class="settings-profile-info">
                    <h2>{{ $user->full_name}}</h2>
                    <span>{{ $user->role->caption}}</span>
                    <div class="settings-profile-badges">
                        <span class="settings-badge settings-chakra">Global Rank 12</span>
                        <span class="settings-badge">42.3K Likes</span>
                        <span class="settings-badge">3.9K Reviews</span>
                    </div>
                </div>
            </div>
            <!-- <div class="settings-profile-actions">
                <button class="settings-btn-outline">View Dashboard</button>
            </div> -->
        </section>

        <!-- Tabs -->
        <nav class="settings-profile-tabs" id="tabs">
            <a class="settings-profile-tab settings-active" data-tab="basic" data-step="1">Basic Information</a>
            <a class="settings-profile-tab" data-tab="images" data-step="2">Images</a>
            <a class="settings-profile-tab" data-tab="about" data-step="3">About</a>
            <a class="settings-profile-tab" data-tab="experience" data-step="5">Experience</a>
            <a class="settings-profile-tab" data-tab="education" data-step="4">Education</a>
            <a class="settings-profile-tab" data-tab="identity" data-step="6">Identity & Financial</a>
        </nav>
        <form method="post" id="userSettingForm" class="mt-30" action="{{ (!empty($new_user)) ? '/panel/manage/'. $user_type .'/new' : '/panel/setting' }}">
        
        {{ csrf_field() }}
        <input type="hidden" name="step" id="currentStep" value="{{ !empty($currentStep) ? $currentStep : 1 }}">
        <input type="hidden" name="next_step" value="0">

        @if(!empty($organization_id))
            <input type="hidden" name="organization_id" value="{{ $organization_id }}">
            <input type="hidden" id="userId" name="user_id" value="{{ $user->id }}">
        @endif
        <!-- Content sections -->
        <div id="tab-contents">
            <!-- Basic Information Tab -->
            <div class="settings-tab-content settings-active" id="basic-content">
                <section class="settings-grid">
                    <div class="settings-field-group">
                        <label class="settings-field-label">Full Name</label>
                        <input type="text" name="full_name" value="{{ (!empty($user) and empty($new_user)) ? $user->full_name : old('full_name') }}" class="settings-field-input @error('full_name') settings-field-error @enderror" placeholder="Enter your full name"/>
                        @error('full_name')
                        <div class="settings-field-error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="settings-field-group">
                        <label class="settings-field-label">Display Name</label>
                         <input type="text" name="display_name" value="{{ $user->display_name ?? old('display_name') }}" class="settings-field-input" placeholder="Enter a display name"/>
                    </div>

                    <!-- <div class="settings-field-group">
                        <label class="settings-field-label">Username</label>
                        <input class="settings-field-input" type="text" value="@mythoughtsoneverything" />
                    </div> -->

                    <div class="settings-field-group">
                        <label class="settings-field-label">Email Address</label>
                        <input type="email" name="email" value="{{ (!empty($user) and empty($new_user)) ? $user->email : old('email') }}" class="settings-field-input @error('email') settings-field-error @enderror" placeholder="you@email.com"/>
                        @error('email')
                        <div class="settings-field-error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- <div class="settings-two-col-row">
                        <div class="settings-field-group">
                            <label class="settings-field-label">New Password</label>
                            <input type="password" name="password" class="settings-field-input @error('password') settings-field-error @enderror" placeholder="Enter new password"/>
                            @error('password')
                            <div class="settings-field-error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="settings-field-group">
                            <label class="settings-field-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="settings-field-input @error('password_confirmation') settings-field-error @enderror" placeholder="Confirm password"/>
                            @error('password_confirmation')
                            <div class="settings-field-error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> -->

                    <div class="settings-field-group">
                        <label class="settings-field-label">Phone Number</label>
                        <input type="tel" name="mobile" value="{{ $user->mobile }}" class="settings-field-input @error('mobile') settings-field-error @enderror" placeholder="+31 6 0000 0000"/>
                        @error('mobile')
                        <div class="settings-field-error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="settings-field-group">
                        <label class="settings-field-label">Country</label>
                        <select name="country_id" class="settings-field-input" style="color: #bdb5b5;">
                            <option value="">Select country</option>
                            @if(!empty($countries))
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @if($user->country_id == $country->id) selected @endif>
                                        {{ $country->title }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="settings-two-col-row">
                        <div class="settings-field-group">
                            <label class="settings-field-label">Address Line 1</label>
                            <input class="settings-field-input" type="text" name="address" placeholder="Street + number" value="{{ $user->address ?? old('address') }}" />
                        </div>
                        <div class="settings-field-group">
                            <label class="settings-field-label">Address Line 2 (optional)</label>
                            <input class="settings-field-input" type="text" name="address1" placeholder="Apartment / Suite" value="{{ $user->address1 ?? old('address1') }}" />
                        </div>
                    </div>

                    <div class="settings-field-group">
                        <label class="settings-field-label">City</label>
                        <input type="text" name="city_name" value="{{ $user->city_name ?? old('city_name') }}" class="settings-field-input" placeholder="Enter your city"/>
                    </div>
                    <div class="settings-field-group">
                        <label class="settings-field-label">ZIP / Postal Code</label>
                        <input type="text" name="zip_code" value="{{ $user->zip_code ?? old('zip_code') }}" class="settings-field-input" placeholder="3000 AA"/>
                    </div>

                     <div class="settings-field-group">
                            <label class="settings-field-label">Language</label>
                            <select name="language" class="settings-field-input" style="color: #bdb5b5;">
                                <option value="">Select Language</option>
                                @if(!empty($userLanguages))
                                    @foreach($userLanguages as $lang => $language)
                                        <option value="{{ $lang }}" @if($user->language == $lang) selected @endif>
                                            {{ $language }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="settings-field-group">
                            <label class="settings-field-label">Timezone</label>
                            <select name="timezone" class="settings-field-input" style="color: #bdb5b5;">
                                <option value="" disabled>Select Timezone</option>
                                @foreach(getListOfTimezones() as $timezone)
                                    <option value="{{ $timezone }}" @if($user->timezone == $timezone) selected @endif>
                                        {{ $timezone }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if(!empty($currencies) and count($currencies))
                            <div class="settings-field-group">
                                <label class="settings-field-label">Currency</label>
                                <select name="currency" class="settings-field-input" style="color: #bdb5b5;">
                                    @foreach($currencies as $currencyItem)
                                        <option value="{{ $currencyItem->currency }}" {{ $user->currency == $currencyItem->currency ? 'selected' : '' }}>
                                            {{ currenciesLists($currencyItem->currency) }} ({{ currencySign($currencyItem->currency) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                    <!-- Payment & Membership -->
                    <h3 class="settings-section-title">Payment & Membership</h3>
                    <div class="settings-section">
                        <div class="settings-card">
                            <div class="settings-card-head">
                                <span>Membership</span>
                                <button type="button" class="settings-btn-outline" style="padding:6px 14px;font-size:11px;">Manage</button>
                            </div>
                            <div class="settings-card-main">
                                €1 / month • Full access to Kemetic App
                            </div>
                            <div class="settings-card-sub">
                                Courses, PDFs, audio, reels, livestreams, articles & more.
                            </div>
                        </div>

                        <div class="settings-card">
                            <div class="settings-card-head">
                                <span>Primary Payment Method</span>
                                <button type="button" class="settings-btn-outline" style="padding:6px 14px;font-size:11px;">Update</button>
                            </div>
                            <div class="settings-card-main">
                                • **** 4242 VISA • Expires 09/27
                            </div>
                            <div class="settings-card-sub">
                                Used for memberships, course bundles & shop orders.
                            </div>
                        </div>
                    </div>

                    <!-- Notifications & Security -->
                    <h3 class="settings-section-title">Notifications & Security</h3>
                    <div class="settings-section">
                        <div class="settings-card">
                            <div class="settings-card-head">
                                <span>Push Notifications</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="push_notification" class="custom-control-input" id="pushToggle" {{ !empty($user) && $user->push_notification ? 'checked' : (old('push_notification') ? 'checked' : '')  }}>
                                    <label class="custom-control-label" for="pushToggle"></label>
                                </div>
                                
                            </div>
                            <div class="settings-card-main">
                                Chakra-colored alerts for messages, livestreams & new drops.
                            </div>
                            <div class="settings-card-sub">
                                You can fine-tune per feature from the Notification Center.
                            </div>
                        </div>

                        <div class="settings-card">
                            <div class="settings-card-head">
                                <span>Change Password</span>
                                <button type="button" class="settings-btn-outline" style="padding:6px 14px;font-size:11px;">Change</button>
                            </div>
                            <div class="settings-card-main">
                                Keep your Kemetic universe protected.
                            </div>
                            <div class="settings-card-sub">
                                We recommend updating your password every 3–6 months.
                            </div>
                        </div>

                        <div class="settings-card">
                            <div class="settings-card-head">
                                <span>Join Newsletter</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="newsletter" class="custom-control-input" id="newsletterToggle" {{ !empty($user) && $user->newsletter ? 'checked' : (old('newsletter') ? 'checked' : '')  }}>
                                    <label class="custom-control-label" for="newsletterToggle"></label>
                                </div>
                            </div>
                            <div class="settings-card-main">
                                Receive updates about new courses and features.
                            </div>
                        </div>

                        <div class="settings-card">
                            <div class="settings-card-head">
                                <span>Public Messages</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="public_message" class="custom-control-input" id="publicMessagesToggle" {{ !empty($user) && $user->public_message ? 'checked' : (old('public_message') ? 'checked' : '')  }}>
                                    <label class="custom-control-label" for="publicMessagesToggle"></label>
                                </div>
                                
                            </div>
                            <div class="settings-card-main">
                                Allow other users to send you messages.
                            </div>
                        </div>

                    </div>
                </section>

                <div class="settings-footer">
                    <button type="submit" class="settings-btn-primary">Save Changes</button>
                </div>
            </div>

            <!-- Images Tab -->
            <div class="settings-tab-content" id="images-content">
                <div class="settings-page-shell">
                    <!-- Top header -->
                    <div class="settings-header-row">
                        <div class="settings-header-title">
                            <h1>Profile Photos</h1>
                            <span>
                                Your main profile picture appears on Reels, Courses, Books, Chat and Livestreams.
                            </span>
                        </div>

                        <!-- <div class="settings-profile-chip">
                            <div class="settings-profile-avatar-big" style="width:26px;height:26px;border:none;box-shadow:none;">
                                <span>SN</span>
                            </div>
                            <span>@MyThoughtsOnEverything</span>
                        </div> -->
                    </div>

                    <!-- Main content -->
                    <div class="settings-page-grid">
                        <!-- LEFT: Main profile photo -->
                        <section class="settings-panel">
                            <div class="settings-panel-tag">Main Profile Photo</div>
                            <h2>Face of Your Kemetic Journey</h2>
                            <p>Choose a clear, warm photo so Seekers and Wisdom Keepers recognize you instantly.</p>

                            <div class="settings-main-photo-wrap">
                                <div class="settings-chakra-ring">
                                    <div class="settings-main-photo">
                                        <span><img src="{{ (!empty($user)) ? $user->getAvatar(150) : '' }}" alt="" id="profileImagePreview" width="150" height="150" class="rounded-circle my-15 d-block ml-5"></span>
                                        <div class="settings-camera-badge"><span onclick="document.getElementById('profileImageInput').click()">📷</span></div>
                                    </div>
                                </div>

                                <p class="settings-photo-help">
                                    This photo will be shown on your Reels, Courses, Books, Articles, and Chat profile.
                                </p>

                                <div class="settings-main-actions">
                                <input type="hidden" name="profile_image" id="profile_image" class="form-control @error('profile_image')  is-invalid @enderror"/>
                                    <input type="file" 
                               id="profileImageInput" name="profileImageInput" accept="image/*" style="display: none;"
                               onchange="previewProfileImage(event)">
                                    <button type="button"  onclick="document.getElementById('profileImageInput').click()" class="settings-btn settings-btn-primary" data-ref-image="profileImagePreview" data-ref-input="profile_image">
                                        📤 Upload new photo
                                    </button>
                                    <button type="button" onclick="openMobileGallery('profile')" class="settings-btn settings-btn-ghost" data-ref-image="profileImagePreview" data-ref-input="profile_image">
                                        ✨ Use from gallery
                                    </button>
                                </div>
                            </div>
                        </section>

                        <!-- RIGHT: Gallery -->
                        <section class="settings-panel">
                            <div class="settings-gallery-header">
                                <div>
                                    <h2>Profile Gallery</h2>
                                    <span>Share your energy from different angles – up to 12 photos.</span>
                                </div>
                                <!-- <button class="settings-btn settings-btn-ghost" style="font-size:12px;padding:6px 12px;" id="addGalleryPhoto">
                                    + Add photo
                                </button> -->
                            </div>

                            <div class="settings-gallery-grid"  id="galleryContainer">
                                @php
                                    $galleryPhotos = $user->userMetas->where('name', 'gallery') ?? collect([]);
                                @endphp
                                
                                @if($galleryPhotos->count() > 0)
                                    @foreach($galleryPhotos as $index => $photo)
                                        <div class="settings-gallery-card" data-photo-id="{{ $photo->id }}">
                                            <div class="settings-gallery-thumb">
                                                <img src="{{ $photo->value }}" alt="Gallery photo {{ $index + 1 }}" style="width: 220px;">
                                            </div>
                                            <div class="settings-gallery-actions">
                                                <button type="button" class="settings-gallery-action-btn settings-btn-set-main" data-photo-id="{{ $photo->id }}" title="Set as Main">
                                                    <i class="fa fa-star"></i>
                                                </button>
                                                <button type="button" class="settings-gallery-action-btn settings-btn-delete" data-photo-id="{{ $photo->id }}" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                            <input type="hidden" name="cover_img[]" value="{{ $photo->value }}">
                                        </div>
                                    @endforeach
                                @else
                                    <div class="settings-gallery-empty">
                                        <i class="fa fa-images"></i>
                                        <p>No photos in gallery yet</p>
                                        <span>Add photos to showcase your work</span>
                                    </div>
                                @endif

                                <!-- Add tile -->
                                <input type="file" 
                                    id="galleryInput" 
                                    name="gallery_images[]" 
                                    multiple 
                                    accept="image/*" 
                                    style="display: none;"
                                    onchange="previewGalleryImages(event)">
                                <div class="settings-gallery-add" onclick="document.getElementById('galleryInput').click()">
                                    <div class="settings-gallery-add-inner">
                                        <span>＋</span>
                                        Add another photo<br />
                                        <small>PNG / JPG, max 10 MB</small>
                                    </div>
                                </div>
                            </div>

                            <div class="settings-bottom-actions">
                                <button class="settings-btn settings-btn-ghost">Discard changes</button>
                                <button type="submit" class="settings-btn settings-btn-primary">Save photos</button>
                            </div>
                            <div class="settings-muted-link">
                                Tip: choose one strong portrait as your main photo so Seekers trust your energy instantly.
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            <div class="settings-tab-content" id="about-content">
                <div class="settings-about-card">
                    <!-- <div class="settings-about-header">
                        <div class="settings-about-header-left">
                            <div class="settings-avatar-wrap">
                                <div class="settings-avatar-inner">
                                    <img src="{{ $user->getAvatar(150) }}" alt="{{ $user->full_name }}" width="50" >
                                </div>
                            </div>
                            <div>
                                <div class="settings-display-name">{{ $user->full_name }}</div>
                                <div class="settings-username">@mythoughtsoneverything</div> 
                                <div class="settings-badge-wisdom">
                                    <span class="settings-badge-dot"></span>
                                    {{$user->role->caption}}
                                </div>
                            </div>
                        </div>

                        <div class="settings-about-header-right">
                            <div class="settings-role-toggle">
                                <div class="settings-role-pill @if($user->role->caption == 'Seeker' || $user->role->caption == 'Student')  active @endif">
                                    <span class="dot"></span> Seeker / Student
                                </div>
                                <div class="settings-role-pill @if($user->role->caption == 'Wisdom Keeper' || $user->role->caption == 'Partner') active @endif">
                                    <span class="dot"></span> Wisdom Keeper / Partner
                                </div>
                            </div>

                            <div class="settings-stats-row">
                                <div class="settings-stat-pill">
                                    <span class="settings-stat-icon settings-likes"></span>
                                    <span><strong>42.3K</strong> Likes</span>
                                </div>
                                <div class="settings-stat-pill">
                                    <span class="settings-stat-icon settings-reviews"></span>
                                    <span><strong>1.2K</strong> Reviews</span>
                                </div>
                                <div class="settings-stat-pill">
                                    <span class="settings-stat-icon settings-comments"></span>
                                    <span><strong>9.8K</strong> Comments</span>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="settings-about-body">
                        <!-- LEFT: profile info + About Me -->
                        <div>
                            <!-- <div class="settings-field-group">
                                <div class="settings-field-row">
                                    <div style="flex:1">
                                        <label for="firstName">First Name</label>
                                        <input id="firstName" type="text" placeholder="Loretta" />
                                    </div>
                                    <div style="flex:1">
                                        <label for="lastName">Last Name</label>
                                        <input id="lastName" type="text" placeholder="Sun" />
                                    </div>
                                </div>
                            </div>

                            <div class="settings-field-group">
                                <div class="settings-field-row">
                                    <div style="flex:1">
                                        <label for="username">Username</label>
                                        <input id="username" type="text" placeholder="@mythoughtsoneverything" />
                                    </div>
                                    <div style="flex:1">
                                        <label for="phone">Phone</label>
                                        <input id="phone" type="tel" placeholder="+31 6 1234 5678" />
                                    </div>
                                </div>
                            </div> -->

                            <div class="settings-field-group">
                                <label for="about">About Me / About Us</label>
                                <textarea id="about" name="about" maxlength="1000" placeholder="Share your journey, what you teach, and how you serve the community...">{{ $user->about }}</textarea>
                                <div class="settings-char-count"><span class="current-count">0</span> / <span class="max-count">1,000</span>
                                <span class="limit-message" style="display: none; color: #e74c3c;"> - Character limit reached!</span>
                                </div>
                                <div class="settings-hint">
                                    This text appears on your public profile. Use it to speak directly to your Seekers.
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT: contact + payout + tags -->
                        <!-- <div>
                            <div class="settings-field-group">
                                <label for="email">Contact Email</label>
                                <input id="email" type="email" placeholder="you@example.com" />
                            </div>

                            <div class="settings-field-group">
                                <div class="settings-field-row">
                                    <div style="flex:1">
                                        <label for="country">Country</label>
                                        <select id="country">
                                            <option>Netherlands</option>
                                            <option>Germany</option>
                                            <option>United States</option>
                                            <option>Ghana</option>
                                        </select>
                                    </div>
                                    <div style="flex:1">
                                        <label for="city">City</label>
                                        <input id="city" type="text" placeholder="Rotterdam" />
                                    </div>
                                </div>
                            </div>

                            <div class="settings-field-group">
                                <label for="wallet">Payout Wallet / IBAN</label>
                                <input id="wallet" type="text" placeholder="NL00 BANK 0123 4567 89" />
                                <div class="settings-hint">Used only for payouts from Kemetic App (courses, books, shop).</div>
                            </div>

                            <div class="settings-panel-soft">
                                <h3>What do you offer?</h3>
                                <p>Select the roles that match your work on Kemetic App.</p>
                                <div class="settings-chip-row">
                                    <span class="settings-chip highlight">Courses</span>
                                    <span class="settings-chip">Books &amp; PDFs</span>
                                    <span class="settings-chip">Livestream Classes</span>
                                    <span class="settings-chip">1-on-1 Sessions</span>
                                    <span class="settings-chip">Herbs &amp; Products</span>
                                </div>
                            </div>
                        </div> -->
                    </div>

                    <div class="settings-save-row">
                        <button type="button" class="settings-btn settings-btn-ghost">Cancel</button>
                        <button type="submit" class="settings-btn settings-btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>

            <!-- Experience Tab -->
            <div class="settings-tab-content" id="experience-content">
                <div class="settings-experience-container">
                    <div class="settings-experience-header">
                        <h1>Professional Experience</h1>
                        <p>Showcase your journey and expertise to your Seekers.</p>
                    </div>
                    
                    <div class="settings-experience-content">
                        @if(!empty($experiences) and !$experiences->isEmpty())
                        @foreach($experiences as $experience)
                        <div class="settings-experience-item">
                            <h2 class="settings-experience-title">{{ $experience->value }}</h2>
                            <p class="settings-experience-subtitle">{{ $experience->organization }}</p>
                            <span class="settings-experience-period">{{ $experience->start_date }} - {{ $experience->end_date }}</span>
                            <div class="settings-experience-description">{{ $experience->description }}</div>
                            <div class="settings-experience-actions kemetic-actions">
                                <button type="button" data-experience-id="{{ $experience->id }}" data-user-id="{{ (!empty($user) and empty($new_user)) ? $user->id : '' }}"  class="kemetic-btn kemetic-btn-edi">Edit</button>
                                <a href="/panel/setting/metas/{{ $experience->id }}/delete?user_id={{ (!empty($user) and empty($new_user)) ? $user->id : '' }}" class="kemetic-btn kemetic-btn-delete">Delete</a>              
                            </div>

                        </div>
                        @endforeach
                        @else
                            @include(getTemplate() . '.includes.no-result',[
                                'file_name' => 'exp.png',
                                'title' => trans('auth.experience_no_result'),
                                'hint' => trans('auth.experience_no_result_hint'),
                            ])
                        @endif
                        
                        
                        <div class="settings-add-experience">
                            <button type="button" class="settings-add-button" id="addExperienceBtn">
                                + Add Experience
                            </button>
                        </div>
                        
                        <!-- <div class="settings-save-section">
                            <button class="settings-save-button">Save</button>
                        </div> -->
                    </div>
                </div>
            </div>

            <!-- Education Tab -->
            <div class="settings-tab-content" id="education-content">
                <div class="settings-education-container">
                    <div class="settings-education-header">
                        <h1>Education</h1>
                        <p>Add your education & certifications.<br>Show your wisdom path to your Seekers.</p>
                    </div>
                    
                    <div class="settings-education-content">
                        @if(!empty($educations) and !$educations->isEmpty())
                            @foreach($educations as $education)
                                <div class="settings-education-item">
                                    <h2 class="settings-education-title">{{ $education->value }}</h2>
                                    <p class="settings-education-subtitle">{{ $education->institution }}</p>
                                    <span class="settings-education-period">{{ $education->year }}</span>
                                    <div class="settings-education-actions kemetic-actions">
                                        <button type="button" data-education-id="{{ $education->id }}" data-user-id="{{ (!empty($user) and empty($new_user)) ? $user->id : '' }}" class="kemetic-btn kemetic-btn-edi">Edit</button>
                                        <a href="/panel/setting/metas/{{ $education->id }}/delete?user_id={{ (!empty($user) and empty($new_user)) ? $user->id : '' }}" class="kemetic-btn kemetic-btn-delete">Delete</a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            @include(getTemplate() . '.includes.no-result',[
                                'file_name' => 'edu.png',
                                'title' => trans('auth.education_no_result'),
                                'hint' => trans('auth.education_no_result_hint'),
                            ])
                        @endif
                        
                        <div class="settings-add-education">
                            <button type="button" class="settings-add-button" id="addEducationBtn">
                                + Add degree/course
                            </button>
                        </div>
                        
                        <!-- <div class="settings-save-section">
                            <button class="settings-save-button">Save</button>
                        </div> -->
                    </div>
                </div>
            </div>

            <!-- Identity & Financial Tab -->
            <div class="settings-tab-content" id="identity-content">
                <div class="settings-page-wrap">
                    <header class="settings-page-header">
                        <div class="settings-page-title-group">
                            <h1>Identity &amp; Finance</h1>
                            <p>
                                Secure your account, verify your identity, and manage how you get
                                paid for your wisdom.
                            </p>
                        </div>
                        <!-- <div class="settings-header-actions">
                            <div class="settings-pill">
                                <span class="settings-pill-dot"></span>
                                Payouts auto-enabled
                            </div>
                            <div class="settings-badge-chakra">
                                <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                            </div>
                            <button class="settings-btn-gold">Save All Changes</button>
                        </div> -->
                    </header>

                    <main class="settings-layout-grid">
                        <!-- LEFT COLUMN: Identity & Bank -->
                        <section class="settings-card">
                            <div class="settings-card-header">
                                <div>
                                    <h2>Identity Verification</h2>
                                    <p>We never show this publicly. Used only for payouts &amp; security.</p>
                                </div>
                                <div class="settings-status-pill settings-status-pill--pending">
                                    <!-- <span class="settings-status-dot"></span> -->
                                    <!-- Pending review -->
                                </div>
                            </div>

                            <div class="settings-form-grid">
                                <div class="settings-form-group">
                                    <label for="legal-name">Full legal name</label>
                                    <input id="legal-name" name="legal_name" type="text" placeholder="As shown on your ID" value="{{ $usersidentity->legal_name ?? '' }}" />
                                </div>
                                <div class="settings-form-group">
                                    <label for="dob">Date of birth</label>
                                    <input id="dob" type="date" name="dob" value="{{ !empty($usersidentity->dob) ? \Carbon\Carbon::parse($usersidentity->dob)->format('Y-m-d') : '' }}" />
                                </div>

                                <div class="settings-form-group">
                                    <label for="country">Country of residence</label>
                                    <select name="country_id" class="settings-field-input">
                                        <option value="">Select country</option>
                                        @if(!empty($countries))
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" @if(!empty($usersidentity) && $usersidentity->country_id == $country->id) selected @endif>
                                                    {{ $country->title }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="settings-form-group">
                                    <label for="city">City</label>
                                    <input id="city" name="city" type="text" placeholder="Rotterdam" value="{{ $usersidentity->city ?? '' }}" />
                                </div>

                                <div class="settings-form-group settings-full">
                                    <label>ID document</label>
                                    <p class="settings-upload-hint">Passport, ID card, or driver's license.</p>
                                    
                                    <div class="settings-identity-upload">
                                        <!-- Preview area -->
                                        <div class="settings-doc-preview-container" id="idScanPreviewContainer">
                                            @if(!empty($user) && !empty($usersidentity->identity_scan))
                                                <div class="settings-doc-preview-card">
                                                    <div class="settings-doc-preview">
                                                        <img src="{{ $usersidentity->identity_scan }}" alt="ID Document" class="settings-doc-preview-image">
                                                        <div class="settings-doc-preview-overlay">
                                                            <button type="button" class="settings-doc-action-btn" onclick="viewFullImage('{{ $usersidentity->identity_scan }}', 'ID Document')">
                                                                <i class="fa fa-search"></i>
                                                            </button>
                                                            <button type="button" class="settings-doc-action-btn settings-doc-delete" onclick="removeDocument('identity_scan')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <span class="settings-doc-status">Uploaded</span>
                                                </div>
                                            @else
                                                <div class="settings-doc-empty" id="idScanEmpty" onclick="document.getElementById('identityScanInput').click()">
                                                    <i class="fa fa-id-card"></i>
                                                    <p>No ID document uploaded</p>
                                                    <span>Click to upload your ID</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Upload controls -->
                                        <div class="settings-upload-controls">
                                            <input type="file" 
                                                id="identityScanInput" 
                                                name="identity_scan_file"
                                                accept="image/*,.pdf"
                                                style="display: none;"
                                                onchange="previewIdentityScan(event)">
                                            
                                            <!-- Hidden input for storing file path/URL -->
                                            <input type="hidden" 
                                                name="identity_scan" 
                                                id="identity_scan" 
                                                value="{{ (!empty($usersidentity) and empty($new_user)) ? $usersidentity->identity_scan : old('identity_scan') }}" 
                                                class="form-control @error('identity_scan')  is-invalid @enderror" 
                                                {{ ($user->financial_approval) ? 'disabled' : '' }}/>
                                            
                                            <button type="button" 
                                                    class="settings-btn settings-btn-outline"
                                                    onclick="document.getElementById('identityScanInput').click()"
                                                    {{ ($user->financial_approval) ? 'disabled' : '' }}>
                                                <i class="fa fa-upload"></i> Upload ID Document
                                            </button>
                                            
                                            <div class="settings-upload-info">
                                                <small>Supports JPG, PNG, PDF. Max 10MB</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @error('identity_scan')
                                    <div class="settings-field-error-message">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="settings-form-group settings-full">
                                    <label>Proof of address</label>
                                    <p class="settings-upload-hint">Utility bill, bank statement, or official document with your address.</p>
                                    
                                    <div class="settings-identity-upload">
                                        <!-- Preview area -->
                                        <div class="settings-doc-preview-container" id="certificatePreviewContainer">
                                            @if(!empty($usersidentity) && !empty($usersidentity->certificate))
                                                <div class="settings-doc-preview-card">
                                                    <div class="settings-doc-preview">
                                                        <img src="{{ $usersidentity->certificate }}" alt="Proof of Address" class="settings-doc-preview-image">
                                                        <div class="settings-doc-preview-overlay">
                                                            <button type="button" class="settings-doc-action-btn" onclick="viewFullImage('{{ $usersidentity->certificate }}', 'Proof of Address')">
                                                                <i class="fa fa-search"></i>
                                                            </button>
                                                            <button type="button" class="settings-doc-action-btn settings-doc-delete" onclick="removeDocument('certificate')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <span class="settings-doc-status">Uploaded</span>
                                                </div>
                                            @else
                                                <div class="settings-doc-empty" id="certificateEmpty" onclick="document.getElementById('certificateInput').click()">
                                                    <i class="fa fa-file-invoice"></i>
                                                    <p>No proof of address uploaded</p>
                                                    <span>Click to upload your document</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Upload controls -->
                                        <div class="settings-upload-controls">
                                            <input type="file" 
                                                id="certificateInput" 
                                                name="certificate_file"
                                                accept="image/*,.pdf"
                                                style="display: none;"
                                                onchange="previewCertificate(event)">
                                            
                                            <!-- Hidden input for storing file path/URL -->
                                            <input type="hidden" 
                                                name="certificate" 
                                                id="certificate" 
                                                value="{{ (!empty($usersidentity) and empty($new_user)) ? $usersidentity->certificate : old('certificate') }}" 
                                                class="form-control"/>
                                            
                                            <button type="button" 
                                                    class="settings-btn settings-btn-outline"
                                                    onclick="document.getElementById('certificateInput').click()">
                                                <i class="fa fa-upload"></i> Upload Proof of Address
                                            </button>
                                            
                                            <div class="settings-upload-info">
                                                <small>Supports JPG, PNG, PDF. Max 10MB</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-form-group settings-full">
                                    <label for="extra-notes">
                                        Extra info for compliance
                                        <span class="settings-pill-small">Optional</span>
                                    </label>
                                    <textarea
                                        id="extra-notes" name="notes"
                                        placeholder="Share any extra info our team might need to verify you (stage name, company structure, etc.)."
                                    >{{ (!empty($usersidentity) and empty($new_user)) ? $usersidentity->notes : old('notes') }}</textarea>
                                </div>
                            </div>

                            <div class="settings-card-footer">
                                <button class="settings-btn-ghost">Discard</button>
                                <button type="submit" class="settings-btn-gold">Save verification</button>
                            </div>
                        </section>

                        <!-- RIGHT COLUMN: Wallet & Methods -->
                        <section class="settings-card">
                            <div class="settings-card-header">
                                <div>
                                    <h2>Wallet &amp; Payout Methods</h2>
                                    <p>Control how you receive your Kemetic earnings.</p>
                                </div>
                                <span class="settings-wallet-badge">
                                    <span class="settings-status-dot" style="background: var(--chakra-5);"></span>
                                    Wisdom Keeper wallet active
                                </span>
                            </div>

                            <div>
                                <div class="settings-wallet-balance">€842,30</div>
                                <div class="settings-wallet-sub">Available to withdraw</div>

                                <div class="settings-wallet-actions">
                                    <button class="settings-btn-outline">Withdraw to bank</button>
                                    <button class="settings-btn-outline">Send to Kemetic Wallet</button>
                                </div>
                            </div>

                            <div class="settings-method-list">
                                <div class="settings-method-item">
                                    <div class="settings-method-main">
                                        <div class="settings-method-icon">€</div>
                                        <div class="settings-method-label">
                                            <span>Primary Bank Account</span>
                                            <span>NL32 KEME 1234 5678 90 &bull; BLACKBEACON B.V.</span>
                                        </div>
                                    </div>
                                    <div class="settings-method-tags">
                                        <span class="settings-tag-primary">Default payout</span>
                                        <a href="#" class="settings-link-mini">Edit</a>
                                    </div>
                                </div>

                                <div class="settings-method-item">
                                    <div class="settings-method-main">
                                        <div class="settings-method-icon">W</div>
                                        <div class="settings-method-label">
                                            <span>Wise / PayPal</span>
                                            <span>connected@kemetic.app</span>
                                        </div>
                                    </div>
                                    <div class="settings-method-tags">
                                        <span class="settings-tag-muted">Backup method</span>
                                        <a href="#" class="settings-link-mini">Edit</a>
                                    </div>
                                </div>

                                <div class="settings-method-item">
                                    <div class="settings-method-main">
                                        <div class="settings-method-icon">★</div>
                                        <div class="settings-method-label">
                                            <span>Kemetic Wallet</span>
                                            <span>Used to pay for courses, books, and shop orders.</span>
                                        </div>
                                    </div>
                                    <div class="settings-method-tags">
                                        <a href="#" class="settings-link-mini">View history</a>
                                    </div>
                                </div>
                            </div>

                            <div class="settings-card-footer">
                                <button class="settings-btn-ghost">Cancel</button>
                                <button class="settings-btn-gold">Save payout settings</button>
                            </div>
                        </section>
                    </main>
                </div>
            </div>
        </div>
        </form>
    </main>

    <!-- Education Modal -->
    <div class="settings-modal" id="educationModal">
        <div class="settings-modal-content">
            <div class="settings-modal-header">
                <h2 class="settings-modal-title">Add Education</h2>
                <button class="settings-close-modal">&times;</button>
            </div>
            <form class="settings-modal-form">
                <div class="settings-field-group">
                    <label for="course">Course/Degree</label>
                    <input type="text" id="new_education_val" placeholder="e.g. Sacred Sciences & Esoteric History">
                </div>
                <div class="settings-field-group">
                    <label for="institution">Institution</label>
                    <input type="text" id="institution"  name="institution" placeholder="e.g. Kemetic Mystery School">
                </div>
                <div class="settings-field-group">
                    <label for="year">Year</label>
                    <input type="text" id="year" name="year" placeholder="e.g. 2018-2020">
                </div>
                <div class="settings-modal-actions">
                    <button type="button" class="settings-btn-ghost settings-close-modal">Cancel</button>
                    <button type="button" class="settings-btn-primary" id="saveEducation">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Experience Modal -->
    <div class="settings-modal" id="experienceModal">
        <div class="settings-modal-content">
            <div class="settings-modal-header">
                <h2 class="settings-modal-title">Add Experience</h2>
                <button class="settings-close-modal">&times;</button>
            </div>
            <form class="settings-modal-form">
                <div class="settings-field-group">
                    <label for="jobTitle">Job Title</label>
                    <input type="text" id="new_experience_val" placeholder="e.g. Holistic Health Instructor">
                </div>
                <div class="settings-field-group">
                    <label for="organization">Organization</label>
                    <input type="text" id="organization" name="company" placeholder="e.g. Kemetic Healing Center">
                </div>
                <div class="settings-field-group">
                    <label for="startDate">Start Date</label>
                    <input type="text" id="startDate" name="start_date" placeholder="e.g. Jan 2020">
                </div>
                <div class="settings-field-group">
                    <label for="endDate">End Date</label>
                    <input type="text" id="endDate" name="end_date" placeholder="e.g. Present">
                </div>
                <div class="settings-field-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Describe your role, responsibilities, and achievements..."></textarea>
                </div>
                <div class="settings-modal-actions">
                    <button type="button" class="settings-btn-ghost settings-close-modal">Cancel</button>
                    <button type="button" class="settings-btn-primary" id="saveExperience">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts_bottom')

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            
            // Tab functionality
            const tabs = document.querySelectorAll('.settings-profile-tab');
            const currentStepInput = document.getElementById('currentStep');
            
            function showActiveTabContent() {
                // Hide all tab contents
                document.querySelectorAll('.settings-tab-content').forEach(content => {
                    content.classList.remove('settings-active');
                });
                
                // Show only the active tab content
                const activeTab = document.querySelector('.settings-profile-tab.settings-active');
                if (activeTab) {
                    const tabId = activeTab.getAttribute('data-tab');
                    const activeContent = document.getElementById(`${tabId}-content`);
                    if (activeContent) {
                        activeContent.classList.add('settings-active');
                    }
                }
            }
            
            // Initialize - show only active tab content on page load
            showActiveTabContent();
            
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('settings-active'));
                    
                    // Add active class to clicked tab
                    tab.classList.add('settings-active');

                     const stepValue = tab.getAttribute('data-step');
            
                    // Update the current step input
                    if (currentStepInput) {
                        currentStepInput.value = stepValue;
                    }
                    
                    
                    // Show only the active tab content
                    showActiveTabContent();
                });
            });

            
            
            // Handle password change button
            const changePasswordBtn = document.querySelector('.settings-btn-outline');
            changePasswordBtn.addEventListener('click', function() {
                //alert('Password change functionality would be implemented here.');
            });

            const profileImageInput = document.querySelector('input[name="profileImageInput"]');
            const profileImagePreview = document.getElementById('profileImagePreview');
            
            if (profileImageInput) {
                profileImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            profileImagePreview.src = e.target.result;
                            // You can also submit the form here or use AJAX
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Gallery photo upload
            const galleryFileInputs = document.querySelectorAll('.settings-gallery-file-input');
            galleryFileInputs.forEach(input => {
                input.addEventListener('change', function(e) {
                    const files = e.target.files;
                    if (files.length > 0) {
                        // You can preview images here or directly submit the form
                        console.log('Gallery files selected:', files);
                    }
                });
            });

            // Set gallery photo as main
            document.querySelectorAll('.settings-btn-set-main').forEach(btn => {
                btn.addEventListener('click', function() {
                    const photoId = this.getAttribute('data-photo-id');
                    setAsMainProfileImage(photoId);
                });
            });

            // Delete gallery photo
            document.querySelectorAll('.settings-btn-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    const photoId = this.getAttribute('data-photo-id');
                    deleteGalleryPhoto(photoId);
                });
            });

            const addEducationBtn = document.getElementById('addEducationBtn');
            const educationModal = document.getElementById('educationModal');
            const saveEducationBtn = document.getElementById('saveEducation');
            const addExperienceBtn = document.getElementById('addExperienceBtn');
            const experienceModal = document.getElementById('experienceModal');
            const saveExperienceBtn = document.getElementById('saveExperience');

            const closeModalBtns = document.querySelectorAll('.settings-close-modal');

            // Education modal - UPDATED
            addEducationBtn.addEventListener('click', function() {
                // Reset form for new entry
                document.querySelector('#educationModal input#new_education_val').value = '';
                document.querySelector('#educationModal input#institution').value = '';
                document.querySelector('#educationModal input#year').value = '';
                educationModal.style.display = 'flex';
            });

            // Experience modal - UPDATED
            addExperienceBtn.addEventListener('click', function() {
                // Reset form for new entry
                document.querySelector('#experienceModal input#new_experience_val').value = '';
                document.querySelector('#experienceModal input#organization').value = '';
                document.querySelector('#experienceModal input#startDate').value = '';
                document.querySelector('#experienceModal input#endDate').value = '';
                document.querySelector('#experienceModal textarea#description').value = '';
                experienceModal.style.display = 'flex';
            });

            closeModalBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    educationModal.style.display = 'none';
                    experienceModal.style.display = 'none';
                });
            });

            // Save Education - UPDATED
            saveEducationBtn.addEventListener('click', function() {
                var $this = $(this);
                $this.addClass('loadingbar primary').prop('disabled', true);
                var $input = $('#educationModal #new_education_val');
                var $inputInstitution = $('#educationModal #institution');
                var $inputYear = $('#educationModal #year');
                var $inputOrganization = $('#experienceModal #organization');
                var $inputStartDate = $('#experienceModal #startDate');
                var $inputEndDate = $('#experienceModal #endDate');
                var $inputDescription = $('#experienceModal #description');

                submitMetas($this, $input, $inputInstitution, $inputYear, $inputOrganization, $inputStartDate, $inputEndDate, $inputDescription, 'education', function() {
                    educationModal.style.display = 'none';
                });
            });

            // Save Experience - UPDATED
            saveExperienceBtn.addEventListener('click', function() {
                var $this = $(this);
                $this.addClass('loadingbar primary').prop('disabled', true);
                var $input = $('#experienceModal #new_experience_val');
                var $inputInstitution = $('#educationModal #institution');
                var $inputYear = $('#educationModal #year');
                var $inputOrganization = $('#experienceModal #organization');
                var $inputStartDate = $('#experienceModal #startDate');
                var $inputEndDate = $('#experienceModal #endDate');
                var $inputDescription = $('#experienceModal #description');
            

                submitMetas($this, $input,$inputInstitution, $inputYear, $inputOrganization, $inputStartDate, $inputEndDate, $inputDescription, 'experience', function() {
                    experienceModal.style.display = 'none';
                });
            });

            // Edit Education buttons - UPDATED
            document.querySelectorAll('.settings-education-edit').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var educationId = this.getAttribute('data-education-id');
                    var userId = this.getAttribute('data-user-id');
                    var educationItem = this.closest('.settings-education-item');
                    var educationValue = educationItem.querySelector('.settings-education-title').textContent;
                    var institutionValue = educationItem.querySelector('.settings-education-subtitle').textContent;
                    var yearValue = educationItem.querySelector('.settings-education-period').textContent;
                    
                    // Populate modal with existing data
                    document.querySelector('#educationModal input#new_education_val').value = educationValue;
                    document.querySelector('#educationModal input#institution').value = institutionValue;
                    document.querySelector('#educationModal input#year').value = yearValue;
                    
                    // Change modal to edit mode
                    const modal = document.getElementById('educationModal');
                    const title = modal.querySelector('.settings-modal-title');
                    const saveBtn = modal.querySelector('#saveEducation');
                    
                    title.textContent = 'Edit Education';
                    saveBtn.textContent = 'Update';
                    saveBtn.setAttribute('data-education-id', educationId);
                    saveBtn.setAttribute('data-user-id', userId);
                    saveBtn.setAttribute('id', 'editEducation');
                    
                    modal.style.display = 'flex';
                });
            });

            // Edit Experience buttons - UPDATED
            document.querySelectorAll('.settings-experience-edit').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    var experienceId = this.getAttribute('data-experience-id');
                    var userId = this.getAttribute('data-user-id');
                    var experienceItem = this.closest('.settings-experience-item');
                    var experienceValue = experienceItem.querySelector('.settings-experience-title').textContent;
                    var organizationValue = experienceItem.querySelector('.settings-experience-subtitle').textContent;
                    var startDateValue = experienceItem.querySelector('.settings-experience-period').textContent.split(' - ')[0];
                    var endDateValue = experienceItem.querySelector('.settings-experience-period').textContent.split(' - ')[1];
                    var descriptionValue = experienceItem.querySelector('.settings-experience-description').textContent;
                    
                    // Populate modal with existing data
                    document.querySelector('#experienceModal input#new_experience_val').value = experienceValue;
                    document.querySelector('#experienceModal input#organization').value = organizationValue;
                    document.querySelector('#experienceModal input#startDate').value = startDateValue;
                    document.querySelector('#experienceModal input#endDate').value = endDateValue;
                    document.querySelector('#experienceModal textarea#description').value = descriptionValue;
                    
                    // Change modal to edit mode
                    const modal = document.getElementById('experienceModal');
                    const title = modal.querySelector('.settings-modal-title');
                    const saveBtn = modal.querySelector('#saveExperience');
                    
                    title.textContent = 'Edit Experience';
                    saveBtn.textContent = 'Update';
                    saveBtn.setAttribute('data-experience-id', experienceId);
                    saveBtn.setAttribute('data-user-id', userId);
                    saveBtn.setAttribute('id', 'editExperience');
                    
                    modal.style.display = 'flex';
                });
            });

            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === educationModal) {
                    educationModal.style.display = 'none';
                    resetEducationModal();
                }
                if (event.target === experienceModal) {
                    experienceModal.style.display = 'none';
                    resetExperienceModal();
                }
            });

            
        });

        function resetEducationModal() {
            const modal = document.getElementById('educationModal');
            const title = modal.querySelector('.settings-modal-title');
            const saveBtn = modal.querySelector('#saveEducation');
            
            title.textContent = 'Add Education';
            saveBtn.textContent = 'Save';
            saveBtn.setAttribute('id', 'saveEducation');
            saveBtn.removeAttribute('data-education-id');
            saveBtn.removeAttribute('data-user-id');
        }

        function resetExperienceModal() {
            const modal = document.getElementById('experienceModal');
            const title = modal.querySelector('.settings-modal-title');
            const saveBtn = modal.querySelector('#saveExperience');
            
            title.textContent = 'Add Experience';
            saveBtn.textContent = 'Save';
            saveBtn.setAttribute('id', 'saveExperience');
            saveBtn.removeAttribute('data-experience-id');
            saveBtn.removeAttribute('data-user-id');
        }

        function submitMetas($this, $input, $inputInstitution, $inputYear,$inputOrganization, $inputStartDate, $inputEndDate, $inputDescription, name, callback) {
            var val = $input.val();
            $input.removeClass('is-invalid');
            var institution = $inputInstitution.val();
            var year = $inputYear.val();
            var organization = $inputOrganization.val();
            var startDate = $inputStartDate.val();
            var endDate = $inputEndDate.val();
            var description = $inputDescription.val();
            
            var user_id = null;
            if ($('input#userId').length) {
                user_id = $('input#userId').val();
            }

            if (val !== '' && val !== null) {
                var data = {
                    name: name,
                    value: val,
                    institution: institution,
                    year: year,
                    organization: organization,
                    start_date: startDate,
                    end_date: endDate,
                    description: description,
                    user_id: user_id
                };

                $.post('/panel/setting/metas', data, function (result) {
                    if (result && result.code == 200) {
                        Swal.fire({
                            icon: 'success',
                            html: '<h3 class="font-20 text-center text-dark-blue py-25">success</h3>',
                            showConfirmButton: false,
                            width: '25rem',
                        });

                        if (typeof callback === 'function') {
                            callback();
                        }

                        setTimeout(() => {
                            window.location.reload();
                        }, 500)
                    }
                }).fail(err => {
                    Swal.fire({
                        icon: 'error',
                        html: '<h3 class="font-20 text-center text-dark-blue py-25">error</h3>',
                        showConfirmButton: false,
                        width: '25rem',
                    });

                    $this.removeClass('loadingbar primary').prop('disabled', false);
                });
            } else {
                $input.addClass('is-invalid');
                $this.removeClass('loadingbar primary').prop('disabled', false);
            }
        }

        // Handle edit education
        $('body').on('click', '#educationModal #editEducation', function (e) {
            e.preventDefault();
            var $this = $(this);
            $this.addClass('loadingbar primary').prop('disabled', true);
            var $input = $('#educationModal #new_education_val');
            var $inputInstitution = $('#educationModal #institution');
            var $inputYear = $('#educationModal #year');
            var user_id = $(this).attr('data-user-id');
            var education_id = $(this).attr('data-education-id');
            var val = $input.val();
            var institution = $inputInstitution.val();
            var year = $inputYear.val();

            if (val !== '' && val !== null) {
                var data = {
                    user_id: user_id,
                    value: val,
                    institution: institution,
                    year: year,
                    name: 'education',
                };

                $.post('/panel/setting/metas/' + education_id + '/update', data, function (result) {
                    if (result && result.code == 200) {
                        Swal.fire({
                            icon: 'success',
                            html: '<h3 class="font-20 text-center text-dark-blue py-25">success</h3>',
                            showConfirmButton: false,
                            width: '25rem',
                        });

                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else if (result.code == 403) {
                        Swal.fire({
                            icon: 'error',
                            html: '<h3 class="font-20 text-center text-dark-blue py-25">error</h3>',
                            showConfirmButton: false,
                            width: '25rem',
                        });

                        $this.removeClass('loadingbar primary').prop('disabled', false);
                    }
                }).fail(err => {
                    Swal.fire({
                        icon: 'error',
                        html: '<h3 class="font-20 text-center text-dark-blue py-25">error</h3>',
                        showConfirmButton: false,
                        width: '25rem',
                    });

                    $this.removeClass('loadingbar primary').prop('disabled', false);
                });
            }
        });

        // Handle edit experience
        $('body').on('click', '#experienceModal #editExperience', function (e) {
            e.preventDefault();
            var $this = $(this);
            $this.addClass('loadingbar primary').prop('disabled', true);
            var $input = $('#experienceModal #new_experience_val');
            var user_id = $(this).attr('data-user-id');
            var experience_id = $(this).attr('data-experience-id');
            var val = $input.val();
            var organization = $('#experienceModal #organization').val();
            var startDate = $('#experienceModal #startDate').val();
            var endDate = $('#experienceModal #endDate').val();
            var description = $('#experienceModal #description').val();


            if (val !== '' && val !== null) {
                var data = {
                    user_id: user_id,
                    value: val,
                    organization: organization,
                    start_date: startDate,
                    end_date: endDate,
                    description: description,
                    name: 'experience',
                };

                $.post('/panel/setting/metas/' + experience_id + '/update', data, function (result) {
                    if (result && result.code == 200) {
                        Swal.fire({
                            icon: 'success',
                            html: '<h3 class="font-20 text-center text-dark-blue py-25">success</h3>',
                            showConfirmButton: false,
                            width: '25rem',
                        });

                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else if (result.code == 403) {
                        Swal.fire({
                            icon: 'error',
                            html: '<h3 class="font-20 text-center text-dark-blue py-25">error</h3>',
                            showConfirmButton: false,
                            width: '25rem',
                        });

                        $this.removeClass('loadingbar primary').prop('disabled', false);
                    }
                }).fail(err => {
                    Swal.fire({
                        icon: 'error',
                        html: '<h3 class="font-20 text-center text-dark-blue py-25">error</h3>',
                        showConfirmButton: false,
                        width: '25rem',
                    });

                    $this.removeClass('loadingbar primary').prop('disabled', false);
                });
            }
        });

        function previewProfileImage(event) {
            const file = event.target.files[0];
            if (file) {
                // Check file size (max 10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert('File size must be less than 10MB');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImagePreview').src = e.target.result;
                    let base64Data = e.target.result;
                    document.getElementById('profile_image').value = base64Data;
                    document.getElementById('userSettingForm').submit();
                    // Upload immediately
                    //uploadProfileImage(file);
                };
                reader.readAsDataURL(file);
            }
        }

        // Function to preview gallery images
        function previewGalleryImages(event) {
            const files = event.target.files;
            const galleryContainer = document.getElementById('galleryContainer');
            const galleryEmpty = galleryContainer.querySelector('.settings-gallery-empty');
            
            // Remove empty state if it exists
            if (galleryEmpty) {
                galleryEmpty.remove();
            }
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                // Check file size (max 10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert(`File "${file.name}" exceeds 10MB limit`);
                    continue;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create gallery card
                    const galleryCard = document.createElement('div');
                    galleryCard.className = 'settings-gallery-card';
                    galleryCard.innerHTML = `
                        <div class="settings-gallery-thumb">
                            <img src="${e.target.result}" alt="Gallery photo" style="width: 220px;">
                            <div class="settings-gallery-overlay">
                                <button type="button" 
                                        class="settings-gallery-action-btn settings-btn-set-main" 
                                        title="Set as Main"
                                        onclick="setAsMainFromGallery(this)">
                                    <i class="fa fa-star"></i>
                                </button>
                                <button type="button" 
                                        class="settings-gallery-action-btn settings-btn-delete" 
                                        title="Delete"
                                        onclick="removeGalleryCard(this)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="new_gallery_images[]" value="${e.target.result}">
                    `;
                    
                    // Insert before the add button
                    const addButton = galleryContainer.querySelector('.settings-gallery-add');
                    galleryContainer.insertBefore(galleryCard, addButton);
                };
                reader.readAsDataURL(file);
            }
        }

        // Function to open mobile gallery
        function openMobileGallery(type) {
            if (isMobileDevice()) {
                // For mobile devices, open file picker
                if (type === 'profile') {
                    document.getElementById('profileImageInput').click();
                } else {
                    document.getElementById('galleryInput').click();
                }
            } else {
                document.getElementById('profileImageInput').click();
                // For desktop, you could implement a gallery modal
                //alert('This feature opens your device gallery on mobile. On desktop, use the upload button.');
            }
        }

        // Check if device is mobile
        function isMobileDevice() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }

        // Upload profile image via AJAX
        function uploadProfileImage(file) {
            const formData = new FormData();
            formData.append('profileImageInput', file);
            formData.append('step', '2');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/panel/setting', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Profile image updated successfully!');
                } else {
                    showError(data.message || 'Failed to upload image');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred while uploading the image');
            });
        }

        // Save gallery photos
        function saveGalleryPhotos() {
            const galleryCards = document.querySelectorAll('.settings-gallery-card');
            const images = [];
            
            galleryCards.forEach(card => {
                const img = card.querySelector('img');
                if (img) {
                    images.push({
                        src: img.src,
                        isNew: card.querySelector('input[name="new_gallery_images[]"]') !== null
                    });
                }
            });
            
            // Send to server
            fetch('/panel/setting/save-gallery', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ images: images })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Gallery photos saved successfully!');
                    // Reload page to get updated image URLs
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showError(data.message || 'Failed to save gallery');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred while saving gallery');
            });
        }

        // Set image as main from gallery
        function setAsMainFromGallery(button) {
            const galleryCard = button.closest('.settings-gallery-card');
            const img = galleryCard.querySelector('img');
            
            if (img) {
                // Update main profile preview
                document.getElementById('profileImagePreview').src = img.src;
                
                // Upload as main profile image
                fetch(img.src)
                    .then(res => res.blob())
                    .then(blob => {
                        const file = new File([blob], "profile.jpg", { type: "image/jpeg" });
                        uploadProfileImage(file);
                    });
            }
        }

        // Remove gallery card
        function removeGalleryCard(button) {
            const galleryCard = button.closest('.settings-gallery-card');
            const photoId = galleryCard.getAttribute('data-photo-id');
            
            if (photoId) {
                // If it's an existing photo, delete from server
                deleteGalleryPhoto(photoId);
            } else {
                // If it's a new photo, just remove from DOM
                galleryCard.remove();
                
                // Check if gallery is empty
                const galleryContainer = document.getElementById('galleryContainer');
                const galleryCards = galleryContainer.querySelectorAll('.settings-gallery-card');
                
                if (galleryCards.length === 0) {
                    // Add empty state
                    galleryContainer.innerHTML = `
                        <div class="settings-gallery-empty">
                            <i class="fa fa-images"></i>
                            <p>No photos in gallery yet</p>
                            <span>Add photos to showcase your work</span>
                        </div>
                        <input type="file" 
                            id="galleryInput" 
                            name="gallery_images[]" 
                            multiple 
                            accept="image/*" 
                            style="display: none;"
                            onchange="previewGalleryImages(event)">
                        <div class="settings-gallery-add" onclick="document.getElementById('galleryInput').click()">
                            <div class="settings-gallery-add-inner">
                                <span>＋</span>
                                Add another photo<br />
                                <small>PNG / JPG, max 10 MB</small>
                            </div>
                        </div>
                    `;
                }
            }
        }

        // Clear gallery selections
        function clearGallerySelections() {
            const galleryContainer = document.getElementById('galleryContainer');
            const newImages = galleryContainer.querySelectorAll('input[name="new_gallery_images[]"]');
            
            newImages.forEach(input => {
                const card = input.closest('.settings-gallery-card');
                if (card && !card.getAttribute('data-photo-id')) {
                    card.remove();
                }
            });
            
            // Reset file input
            document.getElementById('galleryInput').value = '';
            
            showSuccess('New selections cleared');
        }

        // Helper functions for notifications
        function showSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: message,
                timer: 2000,
                showConfirmButton: false
            });
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                timer: 3000
            });
        }

        // AJAX function to delete gallery photo
        function deleteGalleryPhoto(photoId) {
            if (!confirm('Are you sure you want to delete this photo?')) {
                return;
            }

            fetch(`/panel/setting/gallery-photo/${photoId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the photo element from DOM
                    const photoElement = document.querySelector(`[data-photo-id="${photoId}"]`);
                    if (photoElement) {
                        photoElement.remove();
                    }
                    showSuccess('Photo deleted successfully!');
                } else {
                    showError('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred while deleting the photo.');
            });
        }

        function previewIdentityScan(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        // Check file size (max 10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const base64Data = e.target.result;
            
            // Update hidden input
            document.getElementById('identity_scan').value = base64Data;
            
            // Update preview
            const previewContainer = document.getElementById('idScanPreviewContainer');
            previewContainer.innerHTML = `
                <div class="settings-doc-preview-card">
                    <div class="settings-doc-preview">
                        <img src="${base64Data}" alt="ID Document" class="settings-doc-preview-image">
                        <div class="settings-doc-preview-overlay">
                            <button type="button" class="settings-doc-action-btn" onclick="viewFullImage('${base64Data}', 'ID Document')">
                                <i class="fa fa-search"></i>
                            </button>
                            <button type="button" class="settings-doc-action-btn settings-doc-delete" onclick="removeDocument('identity_scan')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <span class="settings-doc-status">Ready to upload</span>
                </div>
            `;
        };
        
        reader.readAsDataURL(file);
    }

    // Function to preview certificate
    function previewCertificate(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        // Check file size (max 10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const base64Data = e.target.result;
            
            // Update hidden input
            document.getElementById('certificate').value = base64Data;
            
            // Update preview
            const previewContainer = document.getElementById('certificatePreviewContainer');
            previewContainer.innerHTML = `
                <div class="settings-doc-preview-card">
                    <div class="settings-doc-preview">
                        <img src="${base64Data}" alt="Proof of Address" class="settings-doc-preview-image">
                        <div class="settings-doc-preview-overlay">
                            <button type="button" class="settings-doc-action-btn" onclick="viewFullImage('${base64Data}', 'Proof of Address')">
                                <i class="fa fa-search"></i>
                            </button>
                            <button type="button" class="settings-doc-action-btn settings-doc-delete" onclick="removeDocument('certificate')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <span class="settings-doc-status">Ready to upload</span>
                </div>
            `;
        };
        
        reader.readAsDataURL(file);
    }

    // Function to view full image
    function viewFullImage(src, title) {
        const modal = document.getElementById('imageViewerModal');
        const image = document.getElementById('fullImageView');
        const titleElement = document.getElementById('imageViewerTitle');
        
        image.src = src;
        titleElement.textContent = title;
        modal.style.display = 'flex';
    }

    // Function to close image viewer
    function closeImageViewer() {
        document.getElementById('imageViewerModal').style.display = 'none';
    }

    // Function to remove document
    function removeDocument(type) {
        if (!confirm('Are you sure you want to remove this document?')) {
            return;
        }
        
        // Reset the hidden input
        document.getElementById(type).value = '';
        
        // Reset file input
        if (type === 'identity_scan') {
            document.getElementById('identityScanInput').value = '';
            const container = document.getElementById('idScanPreviewContainer');
            container.innerHTML = `
                <div class="settings-doc-empty" id="idScanEmpty" onclick="document.getElementById('identityScanInput').click()">
                    <i class="fa fa-id-card"></i>
                    <p>No ID document uploaded</p>
                    <span>Click to upload your ID</span>
                </div>
            `;
        } else if (type === 'certificate') {
            document.getElementById('certificateInput').value = '';
            const container = document.getElementById('certificatePreviewContainer');
            container.innerHTML = `
                <div class="settings-doc-empty" id="certificateEmpty" onclick="document.getElementById('certificateInput').click()">
                    <i class="fa fa-file-invoice"></i>
                    <p>No proof of address uploaded</p>
                    <span>Click to upload your document</span>
                </div>
            `;
        }
        
        showSuccess('Document removed');
    }

    // Function to reset identity form
    function resetIdentityForm() {
        // Get original values from server
        const originalIdentityScan = "{{ !empty($user) ? $user->identity_scan : '' }}";
        const originalCertificate = "{{ !empty($user) ? $user->certificate : '' }}";
        
        // Reset ID scan
        document.getElementById('identity_scan').value = originalIdentityScan;
        document.getElementById('identityScanInput').value = '';
        
        if (originalIdentityScan) {
            document.getElementById('idScanPreviewContainer').innerHTML = `
                <div class="settings-doc-preview-card">
                    <div class="settings-doc-preview">
                        <img src="${originalIdentityScan}" alt="ID Document" class="settings-doc-preview-image">
                        <div class="settings-doc-preview-overlay">
                            <button type="button" class="settings-doc-action-btn" onclick="viewFullImage('${originalIdentityScan}', 'ID Document')">
                                <i class="fa fa-search"></i>
                            </button>
                            <button type="button" class="settings-doc-action-btn settings-doc-delete" onclick="removeDocument('identity_scan')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <span class="settings-doc-status">Uploaded</span>
                </div>
            `;
        } else {
            document.getElementById('idScanPreviewContainer').innerHTML = `
                <div class="settings-doc-empty" id="idScanEmpty" onclick="document.getElementById('identityScanInput').click()">
                    <i class="fa fa-id-card"></i>
                    <p>No ID document uploaded</p>
                    <span>Click to upload your ID</span>
                </div>
            `;
        }
        
        // Reset certificate
        document.getElementById('certificate').value = originalCertificate;
        document.getElementById('certificateInput').value = '';
        
        if (originalCertificate) {
            document.getElementById('certificatePreviewContainer').innerHTML = `
                <div class="settings-doc-preview-card">
                    <div class="settings-doc-preview">
                        <img src="${originalCertificate}" alt="Proof of Address" class="settings-doc-preview-image">
                        <div class="settings-doc-preview-overlay">
                            <button type="button" class="settings-doc-action-btn" onclick="viewFullImage('${originalCertificate}', 'Proof of Address')">
                                <i class="fa fa-search"></i>
                            </button>
                            <button type="button" class="settings-doc-action-btn settings-doc-delete" onclick="removeDocument('certificate')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <span class="settings-doc-status">Uploaded</span>
                </div>
            `;
        } else {
            document.getElementById('certificatePreviewContainer').innerHTML = `
                <div class="settings-doc-empty" id="certificateEmpty" onclick="document.getElementById('certificateInput').click()">
                    <i class="fa fa-file-invoice"></i>
                    <p>No proof of address uploaded</p>
                    <span>Click to upload your document</span>
                </div>
            `;
        }
        
        // Reset extra notes
        document.getElementById('extra-notes').value = "{{ old('extra_notes') }}";
        
        showSuccess('Form reset to original values');
    }
    </script>
    <script>
        $(document).ready(function() {
            const $textarea = $('#about');
            const $charCount = $('.settings-char-count .current-count');
            const $limitMessage = $('.settings-char-count .limit-message');
            const maxChars = 1000;

            // Update character count
            function updateCharCount() {
                const currentLength = $textarea.val().length;
                $charCount.text(currentLength);
                
                if (currentLength >= maxChars) {
                    $limitMessage.show();
                    $textarea.addClass('limit-exceeded');
                    
                    if (currentLength === maxChars) {
                        showLimitAlert();
                    }
                } else {
                    $limitMessage.hide();
                    $textarea.removeClass('limit-exceeded');
                }
                
                // Progress color
                const percentage = (currentLength / maxChars) * 100;
                if (percentage >= 100) {
                    $charCount.css('color', '#e74c3c');
                } else if (percentage >= 80) {
                    $charCount.css('color', '#f39c12');
                } else {
                    $charCount.css('color', '#27ae60');
                }
            }

            // Show notification
            function showLimitAlert() {
                // Using Toastr if available, or create custom
                if (typeof toastr !== 'undefined') {
                    toastr.warning('You have reached the maximum of 1,000 characters.', 'Character Limit Reached');
                } else {
                    alert('Character limit reached! Maximum 1,000 characters allowed.');
                }
            }

            // Prevent exceeding limit
            $textarea.on('input', function() {
                if ($textarea.val().length > maxChars) {
                    $textarea.val($textarea.val().substring(0, maxChars));
                }
                updateCharCount();
            });

            // Initialize
            updateCharCount();
        });
        </script>
      <!-- <script src="/assets/default/js/parts/img_cropit.min.js"></script> -->
    <script src="/assets/default/vendors/select2/select2.min.js"></script>

    <script>
        var editEducationLang = '{{ trans('site.edit_education') }}';
        var editExperienceLang = '{{ trans('site.edit_experience') }}';
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var saveErrorLang = '{{ trans('site.store_error_try_again') }}';
        var notAccessToLang = '{{ trans('public.not_access_to_this_content') }}';
    </script>

    <script src="/assets/default/js/panel/user_setting.min.js"></script>
@endpush