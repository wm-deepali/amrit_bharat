{{-- resources/views/admin/events/edit.blade.php --}}
@include('admin.header')

<style>
    #remove {
        font-size: 13px;
        color: red;
        cursor: pointer;
        display: inline-block;
        margin-top: 5px;
    }

    .image-box {
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 10px;
        position: relative;
        display: inline-block;
        width: 140px;
        margin-right: 10px;
        vertical-align: top;
        text-align: center;
    }

    .image-box img {
        display: block;
        margin: 0 auto 6px auto;
        max-width: 120px;
        max-height: 90px;
        object-fit: cover;
        border-radius: 6px;
    }

    .remove-image {
        position: absolute;
        right: 6px;
        top: 6px;
        background: red;
        color: white;
        padding: 2px 6px;
        font-size: 12px;
        border-radius: 4px;
        cursor: pointer;
    }

    .default-radio {
        margin-top: 4px;
        display: inline-block;
    }
</style>

<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="content-header">
                    <h3 class="content-header-title">Events</h3>
                    <button type="button" class="btn btn-dark float-right submit-event-btn">Update Event</button>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Events</li>
                        <li class="breadcrumb-item active">Edit Event</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-main-body">
    <div class="container">
        {{-- $event, $states, $cities must be provided by controller --}}
        <form id="edit-event-form" enctype="multipart/form-data">
            <div class="row">

                <!-- LEFT FORM -->
                <div class="col-sm-9 col-md-7 col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">

                                <h4 class="form-section-h">Event Details</h4>

                                <!-- Title -->
                                <div class="form-group">
                                    <label>Event Title</label>
                                    <input type="text" class="text-control" name="title" id="title"
                                        value="{{ old('title', $event->title ?? '') }}">
                                    <div class="text-danger" id="title-err"></div>
                                </div>

                                <!-- Slug -->
                                <div class="form-group">
                                    <label>Event URL (Slug)</label>
                                    <input type="text" class="text-control" name="slug" id="slug"
                                        value="{{ old('slug', $event->slug ?? '') }}">
                                    <div class="text-danger" id="slug-err"></div>
                                </div>

                                <!-- Short Content -->
                                <div class="form-group">
                                    <label>Short Content (Max 140 chars)</label>
                                    <input type="text" class="text-control" maxlength="140" name="short_content"
                                        id="short_content"
                                        value="{{ old('short_content', $event->short_content ?? '') }}">
                                    <div class="text-danger" id="short_content-err"></div>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label>Event Description</label>
                                    <textarea rows="4" class="text-control" name="description"
                                        id="description">{{ old('description', $event->description ?? '') }}</textarea>
                                    <div class="text-danger" id="description-err"></div>
                                </div>

                                <!-- Dates -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Event Start Date & Time</label>
                                        <input type="datetime-local" class="text-control" name="start_datetime"
                                            id="start_datetime"
                                            value="{{ old('start_datetime', $event->start_datetime ? \Carbon\Carbon::parse($event->start_datetime)->format('Y-m-d\TH:i') : '') }}">
                                        <div class="text-danger" id="start_datetime-err"></div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Event End Date & Time</label>
                                        <input type="datetime-local" class="text-control" name="end_datetime"
                                            id="end_datetime"
                                            value="{{ old('end_datetime', $event->end_datetime ? \Carbon\Carbon::parse($event->end_datetime)->format('Y-m-d\TH:i') : '') }}">
                                        <div class="text-danger" id="end_datetime-err"></div>
                                    </div>
                                </div>

                                <!-- Venue -->
                                <div class="form-group">
                                    <label>Venue / Location</label>
                                    <input type="text" class="text-control" name="venue" id="venue"
                                        value="{{ old('venue', $event->venue ?? '') }}">
                                    <div class="text-danger" id="venue-err"></div>
                                </div>

                                <!-- City & State (Dynamic from Backend) -->
                                <div class="row">

                                    <!-- State -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>State</label>
                                            <select name="state_id" id="state_id" class="text-control">
                                                <option value="">Select State</option>
                                                @foreach($states as $state)
                                                    <option value="{{ $state->id }}" {{ (old('state_id', $event->state_id) == $state->id) ? 'selected' : '' }}>
                                                        {{ $state->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" id="state_id-err"></div>
                                        </div>
                                    </div>

                                    <!-- City -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>City</label>
                                            <select name="city_id" id="city_id" class="text-control">
                                                <option value="">Select City</option>
                                                {{-- cities will be populated by JS; we preselect below --}}
                                            </select>
                                            <div class="text-danger" id="city_id-err"></div>
                                        </div>
                                    </div>

                                </div>

                                <!-- Event Type -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Event Type</label>
                                        <select name="type" id="type" class="text-control">
                                            <option value="free" {{ (old('type', $event->type) == 'free') ? 'selected' : '' }}>Free</option>
                                            <option value="paid" {{ (old('type', $event->type) == 'paid') ? 'selected' : '' }}>Paid</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6" id="price-container" style="display:none;">
                                        <label>Price / Fees</label>
                                        <input type="number" class="text-control" name="price" id="price" min="0"
                                            step="0.01" value="{{ old('price', $event->price) }}">
                                    </div>
                                </div>

                                <hr>

                                <!-- Gallery -->
                                <h4 class="form-section-h">Gallery</h4>

                                <div class="form-group">
                                    <label>Existing & Upload New Images</label>
                                    <input type="file" class="text-control" id="images" multiple>
                                    <div id="preview-gallery" class="mt-2"></div>
                                    <input type="hidden" name="default_image" id="default_image">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT SIDEBAR -->
                <div class="col-sm-3 col-md-5 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <label>Status</label>
                            <select name="status" id="status" class="text-control">
                                <option value="pending" {{ (old('status', $event->status) == 'pending') ? 'selected' : '' }}>Pending</option>
                                <option value="published" {{ (old('status', $event->status) == 'published') ? 'selected' : '' }}>Published</option>
                                <option value="rejected" {{ (old('status', $event->status) == 'rejected') ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>

@include('admin.footer')

<script>
    // Data from backend
   let oldImagesFromServer = @json(json_decode($event->images, true) ?? []);

    let existingDefaultImage = @json($event->default_image ?? null);
    let allCities = @json($cities);

    // Internal state
    // oldImages: array of filenames that still exist (we remove from this when user deletes)
    // newFiles: array of File objects for newly selected files
    // removedOldImages: filenames removed (sent to backend)
    let oldImages = Array.isArray(oldImagesFromServer) ? [...oldImagesFromServer] : [];
    let newFiles = []; // {file: File, id: 'new-0'}
    let removedOldImages = [];
    let defaultImage = existingDefaultImage ? existingDefaultImage : null; // string: old filename or 'new-<index>'

    // Show price if event is paid
    $('#type').change(function () {
        if ($(this).val() === 'paid') {
            $('#price-container').show();
        } else {
            $('#price-container').hide();
            $('#price').val('');
        }
    }).trigger('change');

    // populate city dropdown and preselect if event has city
    function populateCities(preselectId = null) {
        let stateId = $('#state_id').val();
        let cityDropdown = $('#city_id');
        cityDropdown.html('<option value="">Select City</option>');

        if (!stateId) return;

        let filtered = allCities.filter(c => String(c.state_id) === String(stateId));
        filtered.forEach(city => {
            cityDropdown.append(`<option value="${city.id}">${city.name}</option>`);
        });

        if (preselectId) {
            cityDropdown.val(preselectId);
        }
    }

    $(document).ready(function () {
        // initial population of city dropdown (preselect event city)
        populateCities(@json($event->city_id ?? null));

        // If user changes state, repopulate cities
        $('#state_id').on('change', function () {
            populateCities(null);
        });

        // initial render preview combining old + new
        renderPreview();
    });

    // -----------------------
    // Image selection (new files)
    // -----------------------
    $('#images').on('change', function (event) {
        let files = Array.from(event.target.files);
        // add with unique temp id
        files.forEach((file, i) => {
            let tempId = 'new-' + (newFiles.length);
            newFiles.push({ file: file, id: tempId });
        });

        // clear input so user can select same file again if needed
        $(this).val('');
        renderPreview();
    });

    // -----------------------
    // Render combined preview
    // -----------------------
    function renderPreview() {
        $('#preview-gallery').html('');

        // Render old images first
        oldImages.forEach((filename, idx) => {
            let isDefault = (defaultImage && defaultImage === filename) ? 'checked' : '';
            let html = `
                <div class="image-box" data-type="old" data-name="${filename}">
                    <span class="remove-image remove-old" data-name="${filename}" title="Remove">X</span>
                    <img src="{{ url('uploads/events') }}/${filename}" alt="${filename}">
                    <div class="default-radio">
                        <label><input type="radio" name="default_select" class="default-select" data-value="${filename}" ${isDefault}> Default</label>
                    </div>
                    <div style="font-size:11px;margin-top:4px">${filename}</div>
                </div>
            `;
            $('#preview-gallery').append(html);
        });

        // Render new files
        newFiles.forEach((item, index) => {
            let tempId = item.id;
            let reader = new FileReader();
            reader.onload = function (e) {
                let isDefault = (defaultImage && defaultImage === tempId) ? 'checked' : '';
                let html = `
                    <div class="image-box" data-type="new" data-id="${tempId}">
                        <span class="remove-image remove-new" data-id="${tempId}" title="Remove">X</span>
                        <img src="${e.target.result}" alt="${item.file.name}">
                        <div class="default-radio">
                            <label><input type="radio" name="default_select" class="default-select" data-value="${tempId}" ${isDefault}> Default</label>
                        </div>
                        <div style="font-size:11px;margin-top:4px">${item.file.name}</div>
                    </div>
                `;
                $('#preview-gallery').append(html);
            };
            reader.readAsDataURL(item.file);
        });

        // update hidden field (for UI/display; actual value will be sent on submit)
        $('#default_image').val(defaultImage ?? '');
    }

    // -----------------------
    // Remove old image
    // -----------------------
    $(document).on('click', '.remove-old', function () {
        let filename = $(this).data('name');

        // remove from oldImages and push to removedOldImages
        oldImages = oldImages.filter(x => x !== filename);
        removedOldImages.push(filename);

        // if default was this, unset default
        if (defaultImage === filename) defaultImage = null;

        renderPreview();
    });

    // -----------------------
    // Remove new image
    // -----------------------
    $(document).on('click', '.remove-new', function () {
        let id = $(this).data('id');

        // remove from newFiles
        newFiles = newFiles.filter(x => x.id !== id);

        // if default was this, unset default
        if (defaultImage === id) defaultImage = null;

        renderPreview();
    });

    // -----------------------
    // Select default image (radio)
    // -----------------------
    $(document).on('change', '.default-select', function () {
        defaultImage = $(this).data('value'); // either filename or new-<index>
        $('#default_image').val(defaultImage);
    });

    // -----------------------
    // Submit via AJAX (PUT)
    // -----------------------
    $('.submit-event-btn').click(function (e) {
        e.preventDefault();

        // disable button to prevent double submit
        $('.submit-event-btn').prop('disabled', true);

        // build FormData
        let formElement = document.getElementById('edit-event-form');
        let formData = new FormData(formElement);

        // Append method and token
        formData.append('_method', 'POST');
        formData.append('_token', '{{ csrf_token() }}');

        // append removed old images
        removedOldImages.forEach((name) => {
            formData.append('removed_images[]', name);
        });

        // append new files
        newFiles.forEach((item) => {
            formData.append('images[]', item.file);
        });

        // append default image identifier (string)
        if (defaultImage) {
            formData.set('default_image', defaultImage);
        } else {
            formData.set('default_image', ''); // empty
        }

        // AJAX call
        $.ajax({
            url: "{{ url('manage-events/update/' . $event->id) }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                toastr.success(res.msgText || 'Event updated');
                setTimeout(function () {
                    window.location.href = "{{ url('manage-events') }}";
                }, 900);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    // Clear old errors
                    $('.text-danger').html('');

                    Object.keys(errors).forEach(key => {
                        $('#' + key + '-err').html(errors[key][0]);
                    });

                    toastr.error("Please fix form errors.");
                    return;
                }
            }
        });
    });

    // -----------------------
    // Slug auto-generation (but stop after manual edit)
    // -----------------------
    let slugTouched = false;

    $('#slug').on('input', function () {
        slugTouched = true;
    });

    $('#title').on('keyup blur', function () {
        if (slugTouched) return; // user edited slug manually -> don't override

        let title = $(this).val();
        let slug = title
            .toString()
            .toLowerCase()
            .trim()
            .replace(/&/g, '-and-')
            .replace(/[\s\W-]+/g, '-')
            .replace(/^-+|-+$/g, '');

        $('#slug').val(slug);
    });

</script>