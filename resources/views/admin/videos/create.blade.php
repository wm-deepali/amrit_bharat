@include('admin.header')

<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="content-header">
                    <h3 class="content-header-title">Videos</h3>
                    <button type="button" class="btn btn-dark float-right submit-video-btn">
                        Submit Video
                    </button>

                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Videos</li>
                        <li class="breadcrumb-item active">Add Video</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-main-body">
    <div class="container">
        <form id="add-video-form">
            @csrf

            <div class="row">

                <!-- LEFT FORM -->
                <div class="col-sm-9 col-md-7 col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">

                                <h4 class="form-section-h">Video Details</h4>

                                <!-- Title -->
                                <div class="form-group">
                                    <label>Video Title</label>
                                    <input type="text" class="text-control" name="title" id="title">
                                    <div class="text-danger" id="title-err"></div>
                                </div>

                                <!-- Slug -->
                                <div class="form-group">
                                    <label>Video URL (Slug)</label>
                                    <input type="text" class="text-control" name="slug" id="slug">
                                    <div class="text-danger" id="slug-err"></div>
                                </div>

                                <!-- Short Description -->
                                <div class="form-group">
                                    <label>Short Description (Max 200 chars)</label>
                                    <input type="text" maxlength="200" class="text-control" name="short_description" id="short_description">
                                    <div class="text-danger" id="short_description-err"></div>
                                </div>

                                <!-- YouTube Link -->
                                <div class="form-group">
                                    <label>YouTube URL</label>
                                    <input type="text" class="text-control" name="youtube_link" id="youtube_link">
                                    <div class="text-danger" id="youtube_link-err"></div>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label>Detail Content</label>
                                    <textarea rows="4" class="text-control" name="detail_content" id="detail_content"></textarea>
                                    <div class="text-danger" id="detail_content-err"></div>
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

    // Auto-generate slug from title
    $('#title').on('keyup blur', function () {
        let title = $(this).val();

        let slug = title
            .toLowerCase()
            .trim()
            .replace(/&/g, '-and-')
            .replace(/[\s\W-]+/g, '-')
            .replace(/^-+|-+$/g, '');

        $('#slug').val(slug);
    });

    // Submit Video
    $('.submit-video-btn').click(function (e) {
        e.preventDefault();

        let formData = new FormData(document.getElementById('add-video-form'));

        $.ajax({
            url: "{{ url('manage-videos/store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                toastr.success("Video Created Successfully!");
                setTimeout(() => location.reload(), 1200);
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
