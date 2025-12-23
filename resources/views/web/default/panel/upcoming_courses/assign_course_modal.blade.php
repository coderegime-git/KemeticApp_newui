<style>
/* ========== KEMETIC MODAL ========== */
.k-modal {
    background: rgba(14,17,23,.9);
    backdrop-filter: blur(8px);
    padding: 20px;
    border-radius: 20px;
}

.k-card {
    background: #151a23;
    border: 1px solid #262c3a;
    border-radius: 16px;
}

.k-title {
    color: #F2C94C;
    font-weight: 700;
}

.k-label {
    color: #9ca3af;
    font-weight: 500;
}

.k-input {
    background: #0e1117;
    border: 1px solid #262c3a;
    color: #e5e7eb;
}

.k-input:focus {
    border-color: #F2C94C;
    box-shadow: none;
}

.k-btn {
    background: linear-gradient(135deg,#F2C94C,#e0b93d);
    color: #000;
    font-weight: 600;
    border-radius: 14px;
    padding: 6px 20px;
}
</style>
<div id="upcomingAssignCourseModal" class="k-modal" data-action="/panel/upcoming_courses/{{ $upcomingCourse->id }}/assign-course">
    <div class="custom-modal-body k-card p-25">
        <h2 class="section-title k-title after-line">{{ trans('update.assign_published_course') }}</h2>

        <div class="mt-20">
            <input type="hidden" name="upcoming_id" value="{{ $upcomingCourse->id }}">

            <div class="form-group">
                <label class="k-label">{{ trans('product.course') }}</label>
                <select name="course" class="js-ajax-course form-control js-select2 k-input">
                    <option value="">{{ trans('update.select_a_course') }}</option>
                    @foreach($webinars as $webinar)
                        <option value="{{ $webinar->id }}">{{ $webinar->title }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback d-block"></div>
            </div>

            <div class="d-flex align-items-center justify-content-end mt-20">
                <button type="button" class="js-save-assign-course btn k-btn btn-sm">
                    {{ trans('public.save') }}
                </button>
                <button type="button" class="close-swl btn btn-sm btn-danger ml-2">
                    {{ trans('public.close') }}
                </button>
            </div>
        </div>
    </div>
</div>

