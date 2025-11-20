@include('admin.header')

<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="content-header">
                    <h3 class="content-header-title">Edit Banner & Offer</h3>
                    <button type="button" class="btn btn-dark float-right update-banner-btn">Update Banner</button>

                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('manage-banners.index') }}">Banners & Offers</a></li>
                        <li class="breadcrumb-item active">Edit Banner</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-main-body">
    <div class="container">

        <form id="update-banner-form" enctype="multipart/form-data">
            @csrf

            <div class="row">

                <!-- LEFT COLUMN -->
                <div class="col-sm-9 col-md-7 col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">

                                <h4 class="form-section-h">Banner Details</h4>

                                <!-- TITLE -->
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="text-control" name="title" id="title" value="{{ $banner->title }}">
                                    <div class="text-danger" id="title-err"></div>
                                </div>

                                <!-- URL -->
                                <div class="form-group">
                                    <label>URL (Optional)</label>
                                    <input type="url" class="text-control" name="url" id="url" value="{{ $banner->url }}">
                                    <div class="text-danger" id="url-err"></div>
                                </div>

                                <!-- IMAGE -->
                                <div class="form-group">
                                    <label>Banner Image</label>
                                    <input type="file" class="text-control" name="image" id="image">
                                    <div class="text-danger" id="image-err"></div>

                                    @if($banner->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner Image" width="150">
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="col-sm-3 col-md-5 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <label>Status</label>
                            <select name="status" id="status" class="text-control">
                                <option value="pending" {{ $banner->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="published" {{ $banner->status == 'published' ? 'selected' : '' }}>Published</option>
                                <!-- <option value="rejected" {{ $banner->status == 'rejected' ? 'selected' : '' }}>Rejected</option> -->
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
    // AJAX UPDATE BANNER
    $('.update-banner-btn').click(function (e) {
        e.preventDefault();

        let formData = new FormData(document.getElementById('update-banner-form'));

        $.ajax({
            url: "{{ route('manage-banners.update', $banner->id) }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function (res) {
                toastr.success("Banner Updated Successfully!");
                setTimeout(() => window.location.href = "{{ route('manage-banners.index') }}", 1000);
            },

            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

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
</script>
