{{-- resources/views/admin/videos/view.blade.php --}}
@include('admin.header')

<style>
    .video-thumbnail {
        width: 200px;
        height: 120px;
        object-fit: cover;
        border-radius: 6px;
        margin: 5px 0;
        border: 1px solid #ddd;
    }

    .badge-status {
        padding: 3px 8px;
        font-size: 12px;
        border-radius: 4px;
    }
</style>

<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="content-header">
                    <h3 class="content-header-title">View Video</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('manage-videos.index') }}">Videos</a></li>
                        <li class="breadcrumb-item active">View Video</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-main-body">
    <div class="container">
        <div class="row">

            <!-- LEFT SECTION -->
            <div class="col-sm-9 col-md-7 col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block">

                            <h4 class="form-section-h">Video Details</h4>

                            <p><strong>Title:</strong> {{ $video->title }}</p>
                            <p><strong>Slug:</strong> {{ $video->slug }}</p>
                            <p><strong>Short Description:</strong> {{ $video->short_description }}</p>

                            <p><strong>Detail Content:</strong></p>
                            <div class="mb-2">{!! nl2br(e($video->detail_content)) !!}</div>

                            <p><strong>YouTube Link:</strong> 
                                <a href="{{ $video->youtube_link }}" target="_blank">{{ $video->youtube_link }}</a>
                            </p>

                            <p>
                                <strong>Status:</strong>
                                <span class="badge badge-info badge-status">{{ ucfirst($video->status) }}</span>
                            </p>

                            <hr>

                            <!-- @if($video->thumbnail)
                            <h4 class="form-section-h">Thumbnail</h4>
                            <img src="{{ url('uploads/videos/' . $video->thumbnail) }}" class="video-thumbnail">
                            @endif -->

                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDEBAR -->
            <div class="col-sm-3 col-md-5 col-lg-3">
                <div class="card">
                    <div class="card-body">

                        <label><strong>Current Status</strong></label>
                        <select class="text-control" disabled>
                            <option>{{ ucfirst($video->status) }}</option>
                        </select>

                        <hr>

                        <label><strong>Update Status</strong></label>

                        <button class="btn btn-success btn-block update-status" data-id="{{ $video->id }}"
                            data-status="published">Publish</button>

                        <button class="btn btn-warning btn-block update-status mt-2" data-id="{{ $video->id }}"
                            data-status="pending">Mark Pending</button>

                        <button class="btn btn-danger btn-block update-status mt-2" data-id="{{ $video->id }}"
                            data-status="rejected">Reject</button>

                        <hr>

                        <a href="{{ route('manage-videos.index') }}" class="btn btn-secondary btn-block">Back to List</a>
                        <a href="{{ route('manage-videos.edit', $video->id) }}" class="btn btn-primary btn-block">Edit Video</a>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@include('admin.footer')

<script>
    $(document).on('click', '.update-status', function () {
        let id = $(this).data('id');
        let status = $(this).data('status');

        $.ajax({
            url: "{{ url('videos/update-status') }}/" + id,
            type: "POST",
            data: {
                status: status,
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                toastr.success(res.msg || "Status Updated");
                setTimeout(() => location.reload(), 600);
            },
            error: function () {
                toastr.error("Unable to update status");
            }
        });
    });
</script>
