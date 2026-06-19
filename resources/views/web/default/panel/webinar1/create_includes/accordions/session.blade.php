<style>
    :root{
    --kemetic-bg: #0e0e0e;
    --kemetic-panel: #141414;
    --kemetic-border: rgba(242,201,76,0.20);
    --kemetic-gold: #F2C94C;
    --kemetic-muted: #bdbdbd;
    --kemetic-radius: 14px;
}

/* Item */
.kemetic-accordion-item{
    background: var(--kemetic-panel);
    border: 1px solid var(--kemetic-border);
    border-radius: var(--kemetic-radius);
    padding: 10px;
    margin-top: 18px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.45);
    transition: 0.25s ease;
}
.kemetic-accordion-item:hover{
    border-color: rgba(242,201,76,0.35);
    transform: translateY(-2px);
}

/* Header */
.kemetic-accordion-header{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    padding:8px 6px;
}

.kemetic-accordion-left{
    display:flex;
    align-items:center;
    gap:12px;
    cursor:pointer;
}

.kemetic-icon-box{
    width:40px;
    height:40px;
    border-radius:10px;
    background: #171717;
    display:flex;
    align-items:center;
    justify-content:center;
    border:1px solid rgba(255,255,255,0.02);
}
.kemetic-icon-box i{
    color: var(--kemetic-gold);
    width:20px;
    height:20px;
}

/* Title */
.kemetic-title{
    font-size:15px;
    font-weight:600;
    color:#fff;
}

/* Right actions */
.kemetic-accordion-actions{
    display:flex;
    align-items:center;
    gap:8px;
}

.kemetic-icon-btn{
    background:transparent;
    border: none;
    color: var(--kemetic-muted);
    padding:6px;
    border-radius:8px;
    cursor:pointer;
}
.kemetic-icon-btn:hover{
    color: var(--kemetic-gold);
    background: rgba(242,201,76,0.03);
}

.kemetic-move-icon{
    color: var(--kemetic-muted);
    cursor: grab;
}

/* Chevron button */
.kemetic-chevron-btn{
    background: transparent;
    border: none;
    padding:6px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:8px;
}
.kemetic-chevron-btn i{
    color: var(--kemetic-muted);
}
.kemetic-chevron-btn:hover i{ color: var(--kemetic-gold); }

/* Disabled badge */
.kemetic-disabled-badge{
    background: rgba(255,255,255,0.03);
    color: #ffb3b3;
    padding:4px 8px;
    border-radius:8px;
    font-size:12px;
}

/* Body */
.kemetic-accordion-body{
    padding: 14px 6px 6px;
}

.kemetic-panel-inner{
    background: #0b0b0b;
    border-radius: 12px;
    padding: 16px;
    border: 1px solid rgba(242,201,76,0.08);
}

/* Form layout */
.kemetic-form-group{ margin-bottom:14px; }
.kemetic-label{ color: var(--kemetic-gold); display:block; margin-bottom:8px; font-size:13px; }

/* Grid */
.kemetic-grid-row{ display:block; } /* single column inside accordion (keeps mobile-friendly) */
.kemetic-col{ width:100%; }

/* Inputs */
.kemetic-input,
.kemetic-textarea,
.kemetic-select{
    background: #101010;
    border: 1px solid rgba(255,255,255,0.03);
    color: #fff;
    padding: 10px 12px;
    border-radius: 10px;
    width:100%;
    box-sizing:border-box;
}
.kemetic-textarea{ min-height:90px; resize:vertical; }
.kemetic-input:focus,
.kemetic-textarea:focus{ outline: none; border-color: var(--kemetic-gold); box-shadow: 0 0 10px rgba(242,201,76,0.06); }

/* Input with icon */
.kemetic-input-with-icon{ display:flex; align-items:center; gap:8px; }
.kemetic-input-with-icon .kemetic-input-icon{ background: #141414; padding:8px; border-radius:8px; display:flex; align-items:center; justify-content:center; color:var(--kemetic-gold); }
.kemetic-input-with-icon .kemetic-input{ flex:1; }

/* Radio inline */
.kemetic-radio-row{ display:flex; gap:14px; flex-wrap:wrap; }
.kemetic-radio-inline{ display:flex; align-items:center; gap:8px; cursor:pointer; color:var(--kemetic-muted); }
.kemetic-radio-inline input{ margin-right:6px; }

/* Switch */
.kemetic-switch{ display:inline-block; position:relative; width:46px; height:24px; }
.kemetic-switch-input{ opacity:0; width:0; height:0; }
.kemetic-switch-slider{ position:absolute; inset:0; background:#333; border-radius:24px; transition:0.25s; }
.kemetic-switch-slider::after{ content:''; position:absolute; width:20px; height:20px; border-radius:50%; background: #fff; left:2px; top:2px; transition:0.25s; }
.kemetic-switch-input:checked + .kemetic-switch-slider{ background: var(--kemetic-gold); }
.kemetic-switch-input:checked + .kemetic-switch-slider::after{ left:24px; background:#000; }

/* Actions row */
.kemetic-actions-row{ margin-top:16px; display:flex; align-items:center; gap:10px; flex-wrap:wrap; }

/* Buttons */
.kemetic-btn{ padding:8px 14px; border-radius:10px; border:none; cursor:pointer; font-weight:600; }
.kemetic-btn-gold{ background:var(--kemetic-gold); color:#000; }
.kemetic-btn-red{ background:#b93a3a; color:#fff; }
.kemetic-btn-secondary{ background:#2a2a2a; color:#fff; border:1px solid rgba(255,255,255,0.03); }

/* Small helpers */
.kemetic-pl-5{ padding-left:5px; }
.kemetic-disabled{ opacity:.6; pointer-events:none; }
.d-none{ display:none !important; }
.invalid-feedback{ color:#ffb3b3; font-size:13px; margin-top:6px; }

</style>

@php
    if (!empty($session->agora_settings)) {
        $session->agora_settings = json_decode($session->agora_settings);
    }
@endphp

<li data-id="{{ !empty($chapterItem) ? $chapterItem->id :'' }}" class="accordion-row kemetic-accordion-item">
    <div class="kemetic-accordion-header" role="tab" id="session_{{ !empty($session) ? $session->id :'record' }}">
        <div class="kemetic-accordion-left" 
             href="#collapseSession{{ !empty($session) ? $session->id :'record' }}" 
             aria-controls="collapseSession{{ !empty($session) ? $session->id :'record' }}" 
             data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id :'' }}" 
             role="button" data-toggle="collapse" aria-expanded="true">

            <span class="kemetic-icon-box">
                <i data-feather="file-text"></i>
            </span>

            <div class="kemetic-title">
                {{ !empty($session) ? $session->title : trans('public.add_new_sessions') }}
            </div>
        </div>

        <div class="kemetic-accordion-actions">
            @if(!empty($session) and $session->status != \App\Models\WebinarChapter::$chapterActive)
                <span class="kemetic-disabled-badge">{{ trans('public.disabled') }}</span>
            @endif

            @if(!empty($session))
                <button type="button"
                        data-item-id="{{ $session->id }}"
                        data-item-type="{{ \App\Models\WebinarChapterItem::$chapterSession }}"
                        data-chapter-id="{{ !empty($chapter) ? $chapter->id : '' }}"
                        class="kemetic-icon-btn js-change-content-chapter"
                        title="{{ trans('update.change_chapter') }}">
                    <i data-feather="grid"></i>
                </button>
            @endif

            <i data-feather="move" class="kemetic-move-icon" title="{{ trans('update.drag') }}"></i>

            @if(!empty($session))
                <a href="/panel/sessions/{{ $session->id }}/delete" class="kemetic-icon-btn delete-action" title="{{ trans('public.delete') }}">
                    <i data-feather="trash-2"></i>
                </a>
            @endif

            <button class="kemetic-chevron-btn" data-toggle="collapse" href="#collapseSession{{ !empty($session) ? $session->id :'record' }}" aria-controls="collapseSession{{ !empty($session) ? $session->id :'record' }}" aria-expanded="true">
                <i data-feather="chevron-down"></i>
            </button>
        </div>
    </div>

    <div id="collapseSession{{ !empty($session) ? $session->id :'record' }}" aria-labelledby="session_{{ !empty($session) ? $session->id :'record' }}" class="collapse @if(empty($session)) show @endif kemetic-accordion-body" role="tabpanel">
        <div class="kemetic-panel-inner">
            <div class="session-form" data-action="/panel/sessions/{{ !empty($session) ? $session->id . '/update' : 'store' }}">
                <input type="hidden" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][webinar_id]" value="{{ !empty($webinar) ? $webinar->id :'' }}">

                <!-- Session API -->
                <div class="kemetic-form-group">
                    <label class="kemetic-label">{{ trans('webinars.select_session_api') }}</label>
                    <div class="kemetic-radio-row js-session-api">
                        @foreach(getFeaturesSettings("available_session_apis") as $sessionApi)
                            <label class="kemetic-radio-inline">
                                <input type="radio"
                                       name="ajax[{{ !empty($session) ? $session->id : 'new' }}][session_api]"
                                       id="{{ $sessionApi }}_api_{{ !empty($session) ? $session->id : '' }}"
                                       value="{{ $sessionApi }}"
                                       class="js-api-input"
                                       @if((!empty($session) and $session->session_api == $sessionApi) or (empty($session) and $sessionApi == 'local')) checked @endif
                                       {{ (!empty($session) and $session->session_api != 'local') ? 'disabled' :'' }}>
                                <span>{{ trans('update.session_api_'.$sessionApi) }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="kemetic-grid-row">
                    <div class="kemetic-col">

                        @if(!empty(getGeneralSettings('content_translate')))
                            <div class="kemetic-form-group">
                                <label class="kemetic-label">{{ trans('auth.language') }}</label>
                                <select name="ajax[{{ !empty($session) ? $session->id : 'new' }}][locale]"
                                        class="kemetic-input {{ !empty($session) ? 'js-webinar-content-locale' : '' }}"
                                        data-webinar-id="{{ !empty($webinar) ? $webinar->id : '' }}"
                                        data-id="{{ !empty($session) ? $session->id : '' }}"
                                        data-relation="sessions"
                                        data-fields="title,description">
                                    @foreach($userLanguages as $lang => $language)
                                        <option value="{{ $lang }}" {{ (!empty($session) and !empty($session->locale)) ? (mb_strtolower($session->locale) == mb_strtolower($lang) ? 'selected' : '') : ($locale == $lang ? 'selected' : '') }}>{{ $language }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][locale]" value="{{ $defaultLocale }}">
                        @endif

                        <div class="kemetic-form-group js-api-secret {{ (!empty($session) and in_array($session->session_api, ['zoom', 'agora', 'jitsi'])) ? 'd-none' :'' }}">
                            <label class="kemetic-label">{{ trans('auth.password') }}</label>
                            <input type="text" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][api_secret]" class="kemetic-input js-ajax-api_secret" value="{{ !empty($session) ? $session->api_secret : '' }}" {{ (!empty($session) and $session->session_api != 'local') ? 'disabled' :'' }}/>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="kemetic-form-group js-moderator-secret {{ (empty($session) or $session->session_api != 'big_blue_button') ? 'd-none' :'' }}">
                            <label class="kemetic-label">{{ trans('public.moderator_password') }}</label>
                            <input type="text" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][moderator_secret]" class="kemetic-input js-ajax-moderator_secret" value="{{ !empty($session) ? $session->moderator_secret : '' }}" {{ (!empty($session) and $session->session_api == 'big_blue_button') ? 'disabled' :'' }}/>
                            <div class="invalid-feedback"></div>
                        </div>

                        @if(!empty($session))
                            <div class="kemetic-form-group">
                                <label class="kemetic-label">{{ trans('public.chapter') }}</label>
                                <select name="ajax[{{ !empty($session) ? $session->id : 'new' }}][chapter_id]" class="kemetic-input js-ajax-chapter_id">
                                    @foreach($webinar->chapters as $ch)
                                        <option value="{{ $ch->id }}" {{ ($session->chapter_id == $ch->id) ? 'selected' : '' }}>{{ $ch->title }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        @else
                            <input type="hidden" name="ajax[new][chapter_id]" value="" class="chapter-input">
                        @endif

                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('public.title') }}</label>
                            <input type="text" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][title]" class="kemetic-input js-ajax-title" value="{{ !empty($session) ? $session->title : '' }}" placeholder="{{ trans('forms.maximum_255_characters') }}"/>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('public.date') }}</label>
                            <div class="kemetic-input-with-icon">
                                <span class="kemetic-input-icon"><i data-feather="calendar"></i></span>
                                <input type="text" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][date]" class="kemetic-input js-ajax-date datetimepicker" value="{{ !empty($session) ? dateTimeFormat($session->date, 'Y-m-d H:i', false, true, ($session->webinar ? $session->webinar->timezone : null)) : '' }}" {{ (!empty($session) and $session->session_api != 'local') ? 'disabled' :'' }} autocomplete="off"/>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('public.duration') }} <span class="braces">({{ trans('public.minutes') }})</span></label>
                            <input type="text" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][duration]" class="kemetic-input js-ajax-duration" value="{{ !empty($session) ? $session->duration : '' }}" {{ (!empty($session) and $session->session_api != 'local') ? 'disabled' :'' }}/>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="kemetic-form-group js-local-link {{ (!empty($session) and in_array($session->session_api, ['agora', 'jitsi'])) ? 'd-none' : '' }}">
                            <label class="kemetic-label">{{ trans('public.link') }}</label>
                            <input type="text" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][link]" class="kemetic-input js-ajax-link" value="{{ !empty($session) ? $session->getJoinLink() : '' }}" {{ (!empty($session) and $session->session_api != 'local') ? 'disabled' :'' }}/>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="kemetic-form-group">
                            <label class="kemetic-label">{{ trans('public.description') }}</label>
                            <textarea name="ajax[{{ !empty($session) ? $session->id : 'new' }}][description]" class="kemetic-textarea js-ajax-description" rows="6">{{ !empty($session) ? $session->description : '' }}</textarea>
                            <div class="invalid-feedback"></div>
                        </div>

                        @if(!empty(getFeaturesSettings('extra_time_to_join_status')) and getFeaturesSettings('extra_time_to_join_status'))
                            <div class="kemetic-form-group">
                                <label class="kemetic-label">{{ trans('update.extra_time_to_join') }} <span class="braces">({{ trans('public.minutes') }})</span></label>
                                <input type="text" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][extra_time_to_join]" value="{{ (!empty($session) and $session->extra_time_to_join) ? $session->extra_time_to_join : getFeaturesSettings('extra_time_to_join_default_value') }}" class="kemetic-input js-ajax-extra_time_to_join" placeholder=""/>
                                <div class="invalid-feedback"></div>
                            </div>
                        @elseif(!empty(getFeaturesSettings('extra_time_to_join_default_value')))
                            <input type="hidden" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][extra_time_to_join]" value="{{ (!empty($session) and $session->extra_time_to_join) ? $session->extra_time_to_join : getFeaturesSettings('extra_time_to_join_default_value') }}" class="js-ajax-extra_time_to_join form-control" placeholder=""/>
                        @endif

                        <div class="kemetic-form-group kemetic-switch-row">
                            <label class="kemetic-label">{{ trans('public.active') }}</label>
                            <label class="kemetic-switch">
                                <input type="checkbox" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][status]" class="kemetic-switch-input" id="sessionStatusSwitch{{ !empty($session) ? $session->id : '_record' }}" {{ (empty($session) or $session->status == \App\Models\Session::$Active) ? 'checked' : ''  }}>
                                <span class="kemetic-switch-slider"></span>
                            </label>
                        </div>

                        <div class="js-agora-chat-and-rec {{ (empty($session) or $session->session_api !== 'agora') ? 'd-none' : '' }}">
                            @if(getFeaturesSettings('agora_chat'))
                                <div class="kemetic-form-group kemetic-switch-row">
                                    <label class="kemetic-label">{{ trans('update.chat') }}</label>
                                    <label class="kemetic-switch">
                                        <input type="checkbox" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][agora_chat]" class="kemetic-switch-input" id="sessionAgoraChatSwitch{{ !empty($session) ? $session->id : '_record' }}" {{ (!empty($session) and !empty($session->agora_settings) and $session->agora_settings->chat) ? 'checked' : ''  }}>
                                        <span class="kemetic-switch-slider"></span>
                                    </label>
                                </div>
                            @endif
                        </div>

                        @if(getFeaturesSettings('sequence_content_status'))
                            <div class="kemetic-form-group kemetic-switch-row">
                                <label class="kemetic-label">{{ trans('update.sequence_content') }}</label>
                                <label class="kemetic-switch">
                                    <input type="checkbox" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][sequence_content]" class="kemetic-switch-input js-sequence-content-switch" id="SequenceContentSwitch{{ !empty($session) ? $session->id : '_record' }}" {{ (!empty($session) and ($session->check_previous_parts or !empty($session->access_after_day))) ? 'checked' : ''  }}>
                                    <span class="kemetic-switch-slider"></span>
                                </label>
                            </div>

                            <div class="js-sequence-content-inputs kemetic-pl-5 {{ (!empty($session) and ($session->check_previous_parts or !empty($session->access_after_day))) ? '' : 'd-none' }}">
                                <div class="kemetic-form-group kemetic-switch-row">
                                    <label class="kemetic-label">{{ trans('update.check_previous_parts') }}</label>
                                    <label class="kemetic-switch">
                                        <input type="checkbox" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][check_previous_parts]" class="kemetic-switch-input" id="checkPreviousPartsSwitch{{ !empty($session) ? $session->id : '_record' }}" {{ (empty($session) or $session->check_previous_parts) ? 'checked' : ''  }}>
                                        <span class="kemetic-switch-slider"></span>
                                    </label>
                                </div>

                                <div class="kemetic-form-group">
                                    <label class="kemetic-label">{{ trans('update.access_after_day') }}</label>
                                    <input type="number" name="ajax[{{ !empty($session) ? $session->id : 'new' }}][access_after_day]" value="{{ (!empty($session)) ? $session->access_after_day : '' }}" class="kemetic-input js-ajax-access_after_day" placeholder="{{ trans('update.access_after_day_placeholder') }}"/>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="kemetic-actions-row">
                    <button type="button" class="kemetic-btn kemetic-btn-gold js-save-session">{{ trans('public.save') }}</button>

                    @if(!empty($session))
                        @if(!$session->isFinished())
                            <a href="{{ $session->getJoinLink(true) }}" target="_blank" class="kemetic-btn kemetic-btn-secondary ml-10">{{ trans('footer.join') }}</a>
                        @else
                            <button type="button" class="kemetic-btn kemetic-btn-secondary ml-10 disabled">{{ trans('footer.join') }}</button>
                        @endif
                    @endif

                    @if(empty($session))
                        <button type="button" class="kemetic-btn kemetic-btn-red ml-10 cancel-accordion">{{ trans('public.close') }}</button>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>
