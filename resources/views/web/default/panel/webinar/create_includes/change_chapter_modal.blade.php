{{-- ===============================
    KEMETIC MODAL STYLES
================================ --}}
<style>
:root{
    --k-bg:#0f0f0f;
    --k-card:#161616;
    --k-border:#2a2a2a;
    --k-gold:#F2C94C;
    --k-gold-soft:rgba(242,201,76,.18);
    --k-text:#e6e6e6;
    --k-muted:#9a9a9a;
    --k-radius:16px;
}

/* Modal Wrapper */
.k-modal-body{
    background:var(--k-card);
    border-radius:var(--k-radius);
    padding:25px;
    border:1px solid var(--k-border);
}

/* Title */
.k-modal-title{
    color:var(--k-text);
    font-weight:600;
    font-size:18px;
    margin-bottom:20px;
    position:relative;
}

.k-modal-title::after{
    content:'';
    position:absolute;
    left:0;
    bottom:-8px;
    width:50px;
    height:2px;
    background:linear-gradient(90deg,var(--k-gold),transparent);
}

/* Form */
.k-form .input-label{
    color:var(--k-muted);
    font-size:13px;
    font-weight:500;
}

.k-form .form-control,
.k-form .custom-select{
    background:#101010;
    border:1px solid var(--k-border);
    color:var(--k-text);
    border-radius:10px;
}

.k-form .form-control:focus,
.k-form .custom-select:focus{
    border-color:var(--k-gold);
    box-shadow:0 0 0 2px var(--k-gold-soft);
}

/* Buttons */
.k-btn-save{
    background:linear-gradient(135deg,#F2C94C,#E0B63A);
    border:none;
    color:#000;
    font-weight:600;
    border-radius:10px;
    padding:6px 20px;
}

.k-btn-close{
    background:#1e1e1e;
    border:1px solid var(--k-border);
    color:var(--k-text);
    border-radius:10px;
}

/* Spacing fix inside swal */
.swal2-popup .k-modal-body{
    padding:20px;
}
</style>

{{-- ===============================
    CHANGE CHAPTER MODAL HTML
================================ --}}
<div id="changeChapterModalHtml" class="d-none">

    <div class="k-modal-body">

        {{-- TITLE --}}
        <h2 class="k-modal-title">
            {{ trans('update.change_chapter') }}
        </h2>

        {{-- FORM --}}
        <div class="js-content-form change-chapter-form k-form mt-20"
             data-action="/panel/chapters/change">

            <input type="hidden" name="ajax[webinar_id]" value="{{ $webinar->id }}">
            <input type="hidden" name="ajax[item_id]" class="js-item-id">
            <input type="hidden" name="ajax[item_type]" class="js-item-type">

            {{-- CHAPTER SELECT --}}
            <div class="form-group">
                <label class="input-label">{{ trans('public.chapter') }}</label>

                <select name="ajax[chapter_id]"
                        class="js-ajax-chapter_id custom-select">
                    <option value="">
                        {{ trans('update.select_chapter') }}
                    </option>

                    @if(!empty($webinar->chapters) && count($webinar->chapters))
                        @foreach($webinar->chapters as $chapter)
                            <option value="{{ $chapter->id }}">
                                {{ $chapter->title }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            {{-- ACTIONS --}}
            <div class="d-flex align-items-center justify-content-end mt-25">
                <button type="button"
                        class="save-change-chapter k-btn-save">
                    {{ trans('public.save') }}
                </button>

                <button type="button"
                        class="close-swl k-btn-close ml-10">
                    {{ trans('public.close') }}
                </button>
            </div>

        </div>
    </div>
</div>
