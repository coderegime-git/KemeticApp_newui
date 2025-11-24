@extends("admin.layouts.app")

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.translator') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.translator') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ getAdminPanelUrl("/translator/translate") }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="col-12 col-md-6">

                                        <div class="form-group">
                                            <label class="input-label">{{ trans('auth.language') }}</label>
                                            <select name="language" class="js-ajax-language form-control">
                                                <option value="">{{ trans('update.select_a_language') }}</option>
                                                @foreach($userLanguages as $lang => $language)
                                                    @if($lang != 'EN')
                                                        <option value="{{ $lang }}">{{ $language }}</option>
                                                    @endif
                                                @endforeach
                                            </select>

                                            <div class="invalid-feedback d-block"></div>
                                        </div>


                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="checkbox" name="specific_file" id="specificSwitch" value="1" class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="specificSwitch">{{ trans('update.specific_files') }}</label>
                                            </label>
                                            <div class="text-muted text-small">{{ trans('update.translate_specific_file_hint') }}</div>
                                        </div>

                                        <div class="js-specific-files-card file-folder-tree mb-3 d-none">

                                            @php
                                                function displayFilesAndFolders($items, $inputNameTmp, $folderName = null) {
                                                    $items = customSortArrayNumAndTextIndex($items);

                                                    $html = '<ul>';

                                                    foreach ($items as $key => $item) {
                                                        if (is_array($item)) {
                                                            $inputNameTmp .= "[$key]";

                                                            $html .= '<li>';
                                                            $html .= '<div class="folder-name"> ' . $key . '</div>';
                                                            $html .= displayFilesAndFolders($item, $inputNameTmp, $key);
                                                            $html .= '</li>';
                                                        } else {
                                                            $html .= '<li>' . view('admin.translator.lang_file_checkbox', ['fileName' => $item, 'inputName' => $inputNameTmp, 'folderName' => $folderName])->render() . '</li>';
                                                        }
                                                    }

                                                    $html .= '</ul>';
                                                    return $html;
                                                }
                                            @endphp

                                            <div class="folder-name p-0">Lang / en</div>

                                            {!! displayFilesAndFolders($langFiles, "lang_files") !!}

                                        </div>

                                    </div>
                                </div>

                                <div class="js-translate-msg mb-3 text-success d-none"></div>

                                <div class="">
                                    <button type="button" class="js-submit-translator btn btn-primary">{{ trans('update.translate') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-3">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.translator_hint_1_title')}}</div>
                        <div class="text-small font-600-bold">{{trans('update.translator_hint_1_desc')}}</div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.translator_hint_2_title')}}</div>
                        <div class="text-small font-600-bold">{{trans('update.translator_hint_2_desc')}}</div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.translator_hint_3_title')}}</div>
                        <div class="text-small font-600-bold">{{trans('update.translator_hint_3_desc')}}</div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('update.translator_hint_4_title')}}</div>
                        <div class="text-small font-600-bold">{{trans('update.translator_hint_4_desc')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

    <script src="/assets/default/js/admin/translator.min.js"></script>
@endpush
