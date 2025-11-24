<div class="tab-pane mt-3 fade" id="images" role="tabpanel" aria-labelledby="images-tab">
    <div class="row">
        <div class="col-12 col-md-6">
            <form action="{{ getAdminPanelUrl() }}/users/{{ $user->id .'/updateImage' }}" method="Post">
                {{ csrf_field() }}

                <div class="form-group mt-15">
                    <label class="input-label">{{ trans('admin/main.avatar') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="input-group-text admin-file-manager" data-input="avatar" data-preview="holder">
                                <i class="fa fa-chevron-up"></i>
                            </button>
                        </div>
                        <input type="text" name="avatar" id="avatar" value="{{ !empty($user->avatar) ? $user->getAvatar() : old('image_cover') }}" class="form-control"/>
                        <div class="input-group-append">
                            <button type="button" class="input-group-text admin-file-view" data-input="avatar">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-15">
                    <label class="input-label">{{ trans('admin/main.cover_image') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="input-group-text admin-file-manager" data-input="cover_img" data-preview="holder">
                                <i class="fa fa-chevron-up"></i>
                            </button>
                        </div>
                        <input type="text" name="cover_img" id="cover_img" value="{{ !empty($user->cover_img) ? $user->cover_img : old('image_cover') }}" class="form-control"/>
                        <div class="input-group-append">
                            <button type="button" class="input-group-text admin-file-view" data-input="cover_img">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PNG Validation Alert -->
                <div id="pngValidationAlert" class="alert alert-danger mt-3" style="display: none;">
                    <strong>Danger alert!</strong> Only PNG files are allowed. Please select a PNG file.
                </div>

                <div class=" mt-4">
                    <button class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to validate PNG extension
    function validatePngOnly(inputValue, inputName) {
        const alertBox = document.getElementById('pngValidationAlert');
        
        if (inputValue && inputValue.trim() !== '') {
            const extension = inputValue.toLowerCase().split('.').pop();
            if (extension !== 'png') {
                alertBox.style.display = 'block';
                alertBox.innerHTML = 'Only PNG files are allowed for ' + inputName + '. Please select a PNG file.';
                return false;
            } else {
                alertBox.style.display = 'none';
            }
        } else {
            alertBox.style.display = 'none';
        }
        return true;
    }

    // Monitor changes to avatar input
    const avatarInput = document.getElementById('avatar');
    if (avatarInput) {
        avatarInput.addEventListener('input', function() {
            validatePngOnly(this.value, 'avatar');
        });
        
        avatarInput.addEventListener('change', function() {
            validatePngOnly(this.value, 'avatar');
        });
    }

    // Monitor changes to cover image input
    const coverImgInput = document.getElementById('cover_img');
    if (coverImgInput) {
        coverImgInput.addEventListener('input', function() {
            validatePngOnly(this.value, 'cover image');
        });
        
        coverImgInput.addEventListener('change', function() {
            validatePngOnly(this.value, 'cover image');
        });
    }

    // Form submission validation
    const form = document.querySelector('form[action*="updateImage"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const avatarValue = document.getElementById('avatar').value;
            const coverImgValue = document.getElementById('cover_img').value;
            
            if (!validatePngOnly(avatarValue, 'avatar') || !validatePngOnly(coverImgValue, 'cover image')) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>