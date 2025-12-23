<style>
    /* Backgrounds */
.bg-gray800 { background-color: #1C1C1C !important; }
.bg-gold-light { background-color: rgba(242,201,76,0.1); }

/* Text colors */
.text-gold { color: #F2C94C !important; }
.text-gray { color: #B0B0B0 !important; }

/* Typography */
.font-12 { font-size: 12px; }
.font-14 { font-size: 14px; }
.font-weight-bold { font-weight: 700; }

/* Cards and sections */
.rounded-lg { border-radius: 18px !important; }
.shadow-sm { box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

/* Noticeboard Icon */
.course-noticeboard-icon {
    width: 40px;
    height: 40px;
}

</style>

<section class="px-15 pb-15 my-15 mx-lg-15">

    @if(!empty($course->noticeboards) and count($course->noticeboards))
        @foreach($course->noticeboards as $noticeboard)
            <div class="course-noticeboards noticeboard-{{ $noticeboard->color }} p-15 mt-15 rounded-lg shadow-sm bg-gray800 w-100">
                <div class="d-flex align-items-center">
                    <div class="course-noticeboard-icon d-flex align-items-center justify-content-center rounded-circle bg-gold-light">
                        <i data-feather="{{ $noticeboard->getIcon() }}" class="text-gold" width="24" height="24"></i>
                    </div>

                    <div class="ml-10">
                        <h3 class="font-14 font-weight-bold text-gold">{{ $noticeboard->title }}</h3>
                        <span class="d-block font-12 text-gray">{{ $noticeboard->creator->full_name }} {{ trans('public.in') }} {{ dateTimeFormat($noticeboard->created_at,'j M Y') }}</span>
                    </div>
                </div>

                <div class="mt-10 font-14 text-gray">{!! $noticeboard->message !!}</div>
            </div>
        @endforeach
    @endif

</section>

