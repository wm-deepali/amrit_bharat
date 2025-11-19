@include('admin.header')

<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="content-header">
                    <h3 class="content-header-title">Add Banner / Offer</h3>
                    <button type="button" class="btn btn-dark float-right submit-banner-btn">
                        Submit
                    </button>

                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('manage-banners.index') }}">Banners & Offers</a>
                        </li>
                        <li class="breadcrumb-item active">Add Banner / Offer</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-main-body">
    <div class="container">
        <form id="add-banner-form" enctype="multipart/form-data">
            @csrf

            <div class="row">

                <!-- LEFT FORM -->
                <div class="col-sm-9 col-md-7 col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">

                                <h4 class="form-section-h">Banner / Offer Details</h4>

                                <!-- Title -->
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="text-control" name="title" id="title">
                                    <div class="text-danger" id="title-err"></div>
                                </div>

                                <!-- Image Upload -->
                                <div class="form-group">
                                    <label>Upload Image</label>
                                    <input type="file" class="text-control" name="image" id="image" accept="image/*">
                                    <div class="text-danger" id="image-err"></div>
                                </div>

                                <!-- URL -->
                                <div class="form-group">
                                    <label>URL (optional)</label>
                                    <input type="text" class="text-control" name="url" id="url">
                                    <div class="text-danger" id="url-err"></div>
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
                            <div class="text-danger" id="status-err"></div>
                        </div>
                    </div>
                </div>

            </div>

        </form>
    </div>
</section>

@include('admin.footer')

<script>
    // Submit Banner / Offer
    $('.submit-banner-btn').click(function (e) {
        e.preventDefault();

        let formData = new FormData(document.getElementById('add-banner-form'));

        $.ajax({
            url: "{{ url('manage-banners/store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                toastr.success("Banner / Offer Created Successfully!");
                setTimeout(() => window.location.href = "{{ route('manage-banners.index') }}", 1200);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $('.text-danger').html("");

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
</script>