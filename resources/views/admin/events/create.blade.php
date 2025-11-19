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
    }

    .remove-image {
        position: absolute;
        right: 5px;
        top: 5px;
        background: red;
        color: white;
        padding: 2px 6px;
        font-size: 12px;
        border-radius: 4px;
        cursor: pointer;
    }
</style>

<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="content-header">
                    <h3 class="content-header-title">Events</h3>
                    <button type="button" class="btn btn-dark float-right submit-event-btn">Submit Event</button>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Events</li>
                        <li class="breadcrumb-item active">Add Event</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-main-body">
    <div class="container">
        <form id="add-event-form" enctype="multipart/form-data">
            @csrf

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
                                    <input type="text" class="text-control" name="title" id="title">
                                    <div class="text-danger" id="title-err"></div>
                                </div>

                                <!-- Slug -->
                                <div class="form-group">
                                    <label>Event URL (Slug)</label>
                                    <input type="text" class="text-control" name="slug" id="slug">
                                    <div class="text-danger" id="slug-err"></div>
                                </div>

                                <!-- Short Content -->
                                <div class="form-group">
                                    <label>Short Content (Max 140 chars)</label>
                                    <input type="text" class="text-control" maxlength="140" name="short_content"
                                        id="short_content">
                                    <div class="text-danger" id="short_content-err"></div>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label>Event Description</label>
                                    <textarea rows="4" class="text-control" name="description"
                                        id="description"></textarea>
                                    <div class="text-danger" id="description-err"></div>
                                </div>

                                <!-- Dates -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label>Event Start Date & Time</label>
                                        <input type="datetime-local" class="text-control" name="start_datetime"
                                            id="start_datetime">
                                        <div class="text-danger" id="start_datetime-err"></div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Event End Date & Time</label>
                                        <input type="datetime-local" class="text-control" name="end_datetime"
                                            id="end_datetime">
                                        <div class="text-danger" id="end_datetime-err"></div>
                                    </div>
                                </div>

                                <!-- Venue -->
                                <div class="form-group">
                                    <label>Venue / Location</label>
                                    <input type="text" class="text-control" name="venue" id="venue">
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
                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
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
                                            <option value="free">Free</option>
                                            <option value="paid">Paid</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6" id="price-container" style="display:none;">
                                        <label>Price / Fees</label>
                                        <input type="number" class="text-control" name="price" id="price" min="0"
                                            step="0.01">
                                    </div>
                                </div>

                                <hr>

                                <!-- Gallery -->
                                <h4 class="form-section-h">Gallery</h4>

                                <div class="form-group">
                                    <label>Upload Images</label>
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
                                <option value="pending">Pending</option>
                                <option value="published">Published</option>
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
    let allImages = [];
    let defaultIndex = null;

    // Show price if event is paid
    $('#type').change(function () {
        $(this).val() === 'paid' ? $('#price-container').show() : $('#price-container').hide();
    }).trigger('change');

    // On image selection
    $('#images').on('change', function (event) {
        let newFiles = Array.from(event.target.files);
        allImages.push(...newFiles);
        $('#images').val("");
        renderPreview();
    });

    // Render preview
    function renderPreview() {
        $('#preview-gallery').html('');

        allImages.forEach((file, index) => {
            let reader = new FileReader();

            reader.onload = function (e) {
                let checked = defaultIndex === index ? 'checked' : '';

                let html = `
                    <div class="image-box">
                        <span class="remove-image" data-index="${index}">X</span>
                        <img src="${e.target.result}" width="120" height="120" style="object-fit:cover;border-radius:5px;">
                        <div class="mt-1">
                            <label>
                                <input type="checkbox" class="default-check" data-index="${index}" ${checked}>
                                Set as Default
                            </label>
                        </div>
                    </div>
                `;
                $('#preview-gallery').append(html);
            };

            reader.readAsDataURL(file);
        });

        $('#default_image').val(defaultIndex);
    }

    // Set default image
    $(document).on('change', '.default-check', function () {
        defaultIndex = $(this).data('index');
        renderPreview();
    });

    // Remove image
    $(document).on('click', '.remove-image', function () {
        let index = $(this).data('index');

        allImages.splice(index, 1);

        if (defaultIndex == index) defaultIndex = null;
        else if (defaultIndex > index) defaultIndex--;  // shift index

        renderPreview();
    });

    // AJAX Submit
    $('.submit-event-btn').click(function (e) {
        e.preventDefault();

        let formData = new FormData(document.getElementById('add-event-form'));

        allImages.forEach(file => {
            formData.append('images[]', file);
        });

        formData.append('default_image', defaultIndex);

        $.ajax({
            url: "{{ url('manage-events/store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                toastr.success("Event Created Successfully!");
                setTimeout(() => location.reload(), 1200);
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

                toastr.error("Something went wrong!");
            }

        });
    });

    // Auto-generate slug from title
    $('#title').on('keyup blur', function () {
        let title = $(this).val();

        let slug = title
            .toString()
            .toLowerCase()
            .trim()
            .replace(/&/g, '-and-')          // replace "&"
            .replace(/[\s\W-]+/g, '-')       // replace spaces + non-word chars with dash
            .replace(/^-+|-+$/g, '');        // remove starting/ending dashes

        $('#slug').val(slug);
    });

    let allCities = @json($cities);

    $('#state_id').on('change', function () {
        let stateId = $(this).val();
        let cityDropdown = $('#city_id');

        cityDropdown.html('<option value="">Select City</option>');

        if (stateId) {
            let filtered = allCities.filter(c => c.state_id == stateId);

            filtered.forEach(city => {
                cityDropdown.append(
                    `<option value="${city.id}">${city.name}</option>`
                );
            });
        }
    });
</script>