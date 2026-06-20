(function ($) {
    "use strict";

    if (jQuery().summernote) {
        makeSummernote($('#summernote'), 400)
    }

    $('body').on('click', '#sendForReview', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var originalText = $btn.html();

        $btn.addClass('loadingbar primary').prop('disabled', true);
        $btn.html('<i class="spinner-border spinner-border-sm mr-10" role="status" aria-hidden="true"></i> ' + originalText);

        $('#forDraft').val(0);

        var form = $('#productForm');
        var formData = new FormData(form[0]);

        Swal.fire({
            html: '<div class="d-flex align-items-center justify-content-center py-20"><div class="spinner-border text-primary" role="status"></div><span class="ml-15 font-16">Please wait...</span></div>',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            width: '20rem'
        });

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'Accept': 'application/json'
            },
            success: function(response) {
                if (response && response.code === 200) {
                    Swal.fire({
                        icon: 'success',
                        html: '<h3 class="font-20 text-center text-dark-blue py-25">' + saveSuccessLang + '</h3>',
                        showConfirmButton: false,
                        width: '25rem',
                    });

                    $btn.html(originalText);
                    $btn.removeClass('loadingbar primary').prop('disabled', false);

                    setTimeout(() => {
                        if (response.redirect_url) {
                            window.location.href = response.redirect_url;
                        } else {
                            window.location.href = '/panel/store/products';
                        }
                    }, 500);
                }
            },
            error: function(err) {
                Swal.close();
                $btn.html(originalText);
                $btn.removeClass('loadingbar primary').prop('disabled', false);

                var errors = err.responseJSON;
                if (errors && errors.errors) {
                    Object.keys(errors.errors).forEach((key) => {
                        const error = errors.errors[key];
                        let element = form.find('.js-ajax-' + key);
                        if (element.length === 0) {
                            element = form.find('[name="' + key + '"]');
                        }

                        element.addClass('is-invalid');
                        element.parent().find('.invalid-feedback').text(error[0]);

                        if (key === 'rules') {
                            $.toast({
                                heading: requestFailedLang,
                                text: error[0],
                                bgColor: '#f63c3c',
                                textColor: 'white',
                                hideAfter: 5000,
                                position: 'bottom-right',
                                icon: 'error'
                            });
                        }
                    });
                }
            }
        });
    });

    $('body').on('click', '#saveAsDraft', function (e) {
        $(this).addClass('loadingbar primary').prop('disabled', true);
        e.preventDefault();
        $('#forDraft').val(1);
        $('#productForm').trigger('submit');
    });

    $('body').on('click', '#getNextStep', function (e) {
        $(this).addClass('loadingbar primary').prop('disabled', true);
        e.preventDefault();
        $('#forDraft').val(1);
        $('#getNext').val(1);
        $('#productForm').trigger('submit');
    });

    $('body').on('click', '.js-get-next-step', function (e) {
        e.preventDefault();

        if (!$(this).hasClass('active')) {
            $(this).addClass('loadingbar primary').prop('disabled', true);
            const step = $(this).attr('data-step');

            $('#getStep').val(step);
            $('#forDraft').val(1);
            $('#getNext').val(1);
            $('#productForm').trigger('submit');
        }
    });

    $('body').on('change', '#unlimitedInventorySwitch', function () {
        const inventoryInputs = $('.js-inventory-inputs');

        if (this.checked) {
            inventoryInputs.addClass('d-none');
        } else {
            inventoryInputs.removeClass('d-none');
        }
    });

    $('body').on('click', '.add-btn', function (e) {
        var mainRow = $('.main-row');
        var imagesCount = $('.product-images-input-group').length;

        if (imagesCount < 4) {
            let icon = feather.icons['x'].toSvg({width: 18, height: 18});

            var copy = mainRow.clone();
            copy.removeClass('main-row');
            copy.removeClass('d-none');

            const addBtn = copy.find('.add-btn');

            if (addBtn) {
                addBtn.removeClass('add-btn btn-primary')
                    .addClass('btn-danger remove-btn')
                    .html(icon);
            }

            var copyHtml = copy.prop('innerHTML');
            copyHtml = copyHtml.replaceAll('record', randomString());
            copyHtml = copyHtml.replaceAll('btn-primary', 'btn-danger');
            copyHtml = copyHtml.replaceAll('add-btn', 'remove-btn');

            copy.html(copyHtml);
            $('#productImagesInputs').append(copy);
        } else {
            $.toast({
                heading: requestFailedLang,
                text: maxFourImageCanSelect,
                bgColor: '#f63c3c',
                textColor: 'white',
                hideAfter: 10000,
                position: 'bottom-right',
                icon: 'error'
            });
        }
    });

    $('body').on('click', '.remove-btn', function (e) {
        e.preventDefault();
        $(this).closest('.input-group').remove();
    });

    function randomString() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        for (var i = 0; i < 4; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    /**
     * add product files
     * */
    $('body').off('click', '#productAddFile').on('click', '#productAddFile', function (e) {
        e.preventDefault();
        const key = randomString();

        let add_file = $('#newFileForm').html();
        add_file = add_file.replaceAll('record', key);

        $('#filesAccordion').prepend(add_file);

        feather.replace();
    });

    $('body').off('click', '.js-save-file').on('click', '.js-save-file', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.file-form');

        handleProductItemForm(form, $this);
    });

    $('body').off('click', '.js-save-specification').on('click', '.js-save-specification', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.specification-form');

        handleProductItemForm(form, $this);
    });

    $('body').on('click', '.js-save-faq', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.faq-form');

        handleProductItemForm(form, $this);
    });

    function handleProductItemForm(form, $this) {
        let data = serializeObjectByTag(form);
        let action = form.attr('data-action');

        $this.addClass('loadingbar primary').prop('disabled', true);
        form.find('input').removeClass('is-invalid');
        form.find('textarea').removeClass('is-invalid');

        $.post(action, data, function (result) {
            if (result && result.code === 200) {
                //window.location.reload();
                Swal.fire({
                    icon: 'success',
                    html: '<h3 class="font-20 text-center text-dark-blue py-25">' + saveSuccessLang + '</h3>',
                    showConfirmButton: false,
                    width: '25rem',
                });

                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }
        }).fail(err => {
            $this.removeClass('loadingbar primary').prop('disabled', false);
            var errors = err.responseJSON;

            if (errors && errors.errors) {
                Object.keys(errors.errors).forEach((key) => {
                    const error = errors.errors[key];
                    let element = form.find('.js-ajax-' + key);

                    element.addClass('is-invalid');
                    element.parent().find('.invalid-feedback').text(error[0]);
                });
            }
        });
    }

    $('body').on('change', '#categories', function (e) {
        e.preventDefault();
        let category_id = this.value;
        $.get('/panel/store/products/filters/get-by-category-id/' + category_id, function (result) {

            if (result && typeof result.filters !== "undefined" && result.filters.length) {
                const defaultLocale = result.defaultLocale;
                let html = '';

                Object.keys(result.filters).forEach(key => {
                    let filter = result.filters[key];
                    let options = [];

                    if (filter.options.length) {
                        options = filter.options;
                    }

                    let filterTitle = filter.title;

                    if (!filterTitle && filter.translations) {
                        filterTitle = handleGetFiltersTitleFromTranslations(filter.translations, defaultLocale);
                    }

                    html += '<div class="col-12 col-md-3">\n' +
                        '<div class="webinar-category-filters">\n' +
                        '<strong class="category-filter-title d-block">' + filterTitle + '</strong>\n' +
                        '<div class="py-10"></div>\n' +
                        '\n';

                    if (options.length) {
                        Object.keys(options).forEach(index => {
                            let option = options[index];

                            let optionTitle = option.title;

                            if (!optionTitle && option.translations) {
                                optionTitle = handleGetFiltersTitleFromTranslations(option.translations, defaultLocale);
                            }

                            html += '<div class="form-group mt-20 d-flex align-items-center justify-content-between">\n' +
                                '<label class="cursor-pointer" for="filterOption' + option.id + '">' + optionTitle + '</label>\n' +
                                '<div class="custom-control custom-checkbox">\n' +
                                '<input type="checkbox" name="filters[]" value="' + option.id + '" class="custom-control-input" id="filterOption' + option.id + '">\n' +
                                '<label class="custom-control-label" for="filterOption' + option.id + '"></label>\n' +
                                '</div>\n' +
                                '</div>\n';
                        });
                    }

                    html += '</div></div>';
                });

                $('#categoriesFiltersContainer').removeClass('d-none');
                $('#categoriesFiltersCard').html(html);
            } else {
                $('#categoriesFiltersContainer').addClass('d-none');
                $('#categoriesFiltersCard').html('');
            }
        });
    });

    function handleGetFiltersTitleFromTranslations(translations, defaultLocale) {
        let title = null;

        if (Object.keys(translations).length) {
            Object.keys(translations).forEach(key => {
                const translation = translations[key];

                if (translation.locale === defaultLocale) {
                    title = translation.title
                }
            })

            if (!title) {
                title = translations[0].title
            }
        }

        return title;
    }

    function handleSpecificationSelect2(elClassName) {
        const el = $('.' + elClassName);

        if (el && el.length) {
            el.select2({
                placeholder: el.attr('data-placeholder'),
                //minimumInputLength: 3,
                //allowClear: true,
                /*ajax: {
                    url: '/panel/store/products/specifications/search',
                    dataType: 'json',
                    type: "POST",
                    quietMillis: 50,
                    data: function (params) {
                        return {
                            term: params.term,
                            category_id: el.attr('data-category'),
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.title,
                                    id: item.id,
                                    input_type: item.input_type,
                                }
                            })
                        };
                    }
                }*/
            });

            el.on('change', function (e) {
                const specificationId = e.target.value;
                const $form = $(e.target).closest('.specification-form');

                handleSpecificationInputType($form, specificationId)
            });
        }
    }

    function handleSpecificationMultiValueSelect2(elClassName) {
        const el = $('.' + elClassName);

        if (el && el.length) {
            el.select2();
        }
    }

    $(document).ready(function () {
        handleSpecificationMultiValueSelect2('select-multi-values-select2');
    })

    function handleSpecificationInputType($form, specificationId) {
        $.get('/panel/store/products/specifications/' + specificationId + '/get', function (result) {
            if (result) {
                const {specification, multiValues} = result;

                const multiValuesInput = $form.find('.js-multi-values-input');
                const summaryInput = $form.find('.js-summery-input');
                const allowSelectionInput = $form.find('.js-allow-selection-input');

                $form.find('.js-input-type').val(specification.input_type);

                allowSelectionInput.find('input').prop('checked', false);

                if (specification.input_type === 'multi_value') {
                    multiValuesInput.removeClass('d-none');
                    allowSelectionInput.removeClass('d-none');
                    summaryInput.addClass('d-none');

                    const select = $form.find('.js-ajax-multi_values');
                    let html='';
                    if (multiValues) {
                        for (const multiValue of multiValues) {
                            html += `<option value="${multiValue.id}">${multiValue.title}</option>`;
                        }
                    }
                    select.empty().append(html);
                    
                    if (select.hasClass('select2-hidden-accessible')) {
                        select.select2('destroy');
                    }
                    select.select2();
                } else {
                    multiValuesInput.addClass('d-none');
                    allowSelectionInput.addClass('d-none');
                    summaryInput.removeClass('d-none');
                }

                allowSelectionInput.find('input').prop('checked', false);
            }
        });
    }

    function handleSpecificationTags(elClassName) {
        if (jQuery().tagsinput) {
            var input_tags = $('.' + elClassName);
            input_tags.tagsinput({
                tagClass: 'badge badge-primary py-5',
                maxTags: (input_tags.data('max-tag') ? input_tags.data('max-tag') : 10),
            });
        }
    }

    handleSpecificationSelect2('search-specification-select2');

    $('body').off('click', '.cancel-accordion').on('click', '.cancel-accordion', function (e) {
        e.preventDefault();

        $(this).closest('.accordion-row').remove();
    });

    $('body').off('click', '#productAddSpecification').on('click', '#productAddSpecification', function (e) {
        e.preventDefault();
        const key = randomString();

        let html = $('#newSpecificationForm').html();
        html = html.replaceAll('record', key);

        html = html.replaceAll('specification-select2', 'search-specification-select2-' + key);
        html = html.replaceAll('multi_values-select', 'multi_values-select2');

        html = html.replaceAll('input_tags', 'input_tags-' + key);

        $('#specificationsAccordion').prepend(html);

        handleSpecificationTags('input_tags-' + key);

        handleSpecificationSelect2('search-specification-select2-' + key);
    });

    $('body').off('click', '#productAddFAQ').on('click', '#productAddFAQ', function (e) {
        e.preventDefault();
        const key = randomString();

        let html = $('#newFaqForm').html();
        html = html.replaceAll('record', key);

        $('#faqsAccordion').prepend(html);
    });

    $(document).ready(function () {

        function updateToDatabase(path, idString) {
            $.post(path, {items: idString}, function (result) {

            });
        }

        function setSortable(target) {
            if (target.length) {
                target.sortable({
                    group: 'no-drop',
                    handle: '.move-icon',
                    axis: "y",
                    update: function (e, ui) {
                        var sortData = target.sortable('toArray', {attribute: 'data-id'});
                        var path = e.target.getAttribute('data-order-path');

                        updateToDatabase(path, sortData.join(','));
                    }
                });
            }
        }

        var target = $('.draggable-lists');
        var target2 = $('.draggable-lists2');

        setSortable(target);

        if (target2 && target2.length) {
            setSortable(target2);
        }
    });

    $('body').on('change', '.js-product-content-locale', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $(this).closest('.js-content-form');
        const locale = $this.val();
        const productId = $this.attr('data-product-id');
        const item_id = $this.attr('data-id');
        const relation = $this.attr('data-relation');
        let fields = $this.attr('data-fields');
        fields = fields.split(',');


        $this.addClass('loadingbar gray');

        const path = '/panel/store/products/' + productId + '/getContentItemByLocale';
        const data = {
            item_id,
            locale,
            relation
        };

        $.post(path, data, function (result) {
            if (result && result.item) {
                const item = result.item;

                Object.keys(item).forEach(function (key) {
                    const value = item[key];

                    if ($.inArray(key, fields) !== -1) {
                        let elKey = key;

                        if (relation === 'selectedSpecifications') {
                            elKey = 'tags';

                            if (item.type === 'textarea') {
                                elKey = 'summary';
                            }
                        }

                        let element = $form.find('.js-ajax-' + elKey);

                        if (elKey === 'tags') {
                            element.tagsinput('destroy');
                        }

                        element.val(value);

                        if (elKey === 'tags') {
                            const randomClass = 'tags-' + randomString();

                            element.addClass(randomClass)
                            handleSpecificationTags(randomClass);
                        }
                    }
                });

                $this.removeClass('loadingbar gray');
            }
        }).fail(err => {
            $this.removeClass('loadingbar gray');
        });
    });

    $('body').on('change', '.js-ajax-file_type', function (e) {
        e.preventDefault();

        const fileType = $(this).val();
        const $form = $(this).closest('.file-form');
        //const source = formGroup.find('.js-file-storage').val();

        const $onlineViewerInput = $form.find('.js-online_viewer-input');

        if (fileType && (fileType === 'pdf')) {
            $onlineViewerInput.removeClass('d-none');
        } else {
            $onlineViewerInput.find('input').prop('checked', false);
            $onlineViewerInput.addClass('d-none');
        }
    });
})(jQuery);
