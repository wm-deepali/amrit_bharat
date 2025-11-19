@include('admin.header')

<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="content-header">
                    <h3 class="content-header-title">Edit Video</h3>
                    <button type="button" class="btn btn-dark float-right update-video-btn">Update Video</button>

                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Videos</li>
                        <li class="breadcrumb-item active">Edit Video</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-main-body">
    <div class="container">

        <form id="update-video-form">
            @csrf

            <div class="row">

                <!-- LEFT COLUMN -->
                <div class="col-sm-9 col-md-7 col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">

                                <h4 class="form-section-h">Video Details</h4>

                                <!-- TITLE -->
                                <div class="form-group">
                                    <label>Video Title</label>
                                    <input type="text" class="text-control" name="title"
                                           id="title" value="{{ $video->title }}">
                                    <div class="text-danger" id="title-err"></div>
                                </div>

                                <!-- SLUG -->
                                <div class="form-group">
                                    <label>Video URL (Slug)</label>
                                    <input type="text" class="text-control" name="slug"
                                           id="slug" value="{{ $video->slug ?? '' }}">
                                    <div class="text-danger" id="slug-err"></div>
                                </div>

                                <!-- SHORT DESCRIPTION -->
                                <div class="form-group">
                                    <label>Short Description</label>
                                    <input type="text" maxlength="200" class="text-control"
                                           name="short_description" id="short_description"
                                           value="{{ $video->short_description }}">
                                    <div class="text-danger" id="short_description-err"></div>
                                </div>

                                <!-- YOUTUBE LINK -->
                                <div class="form-group">
                                    <label>YouTube Link</label>
                                    <input type="url" class="text-control" name="youtube_link"
                                           id="youtube_link" value="{{ $video->youtube_link }}">
                                    <div class="text-danger" id="youtube_link-err"></div>
                                </div>

                                <!-- DETAIL CONTENT -->
                                <div class="form-group">
                                    <label>Detail Content</label>
                                    <textarea rows="5" class="text-control"
                                              name="detail_content"
                                              id="detail_content">{{ $video->detail_content }}</textarea>
                                    <div class="text-danger" id="detail_content-err"></div>
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
                                <option value="pending" {{ $video->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="published" {{ $video->status == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="rejected" {{ $video->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
            .toString()
            .toLowerCase()
            .trim()
            .replace(/&/g, '-and-')
            .replace(/[\s\W-]+/g, '-')
            .replace(/^-+|-+$/g, '');

        $('#slug').val(slug);
    });

    // AJAX UPDATE
    $('.update-video-btn').click(function (e) {
        e.preventDefault();

        let formData = new FormData(document.getElementById('update-video-form'));

        $.ajax({
            url: "{{ url('manage-videos/update/'.$video->id) }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function (res) {
                toastr.success("Video Updated Successfully!");
                setTimeout(() => window.location.href = "{{ url('manage-videos') }}", 1000);
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
