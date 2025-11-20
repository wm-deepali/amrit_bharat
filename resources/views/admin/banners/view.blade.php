@include('admin.header')

<style>
    .banner-image {
        width: 100%;
        max-width: 250px;
        height: auto;
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
                    <h3 class="content-header-title">View Banner & Offer</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('manage-banners.index') }}">Banners & Offers</a></li>
                        <li class="breadcrumb-item active">View Banner</li>
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

                            <h4 class="form-section-h">Banner Details</h4>

                            <p><strong>Title:</strong> {{ $banner->title }}</p>
                            <p><strong>URL:</strong> 
                                @if($banner->url)
                                    <a href="{{ $banner->url }}" target="_blank">{{ $banner->url }}</a>
                                @else
                                    -
                                @endif
                            </p>

                            <p>
                                <strong>Status:</strong>
                                <span class="badge badge-info badge-status">{{ ucfirst($banner->status) }}</span>
                            </p>

                            <hr>

                            @if($banner->image)
                                <h4 class="form-section-h">Banner Image</h4>
                                <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner Image" class="banner-image">
                            @endif

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
                            <option>{{ ucfirst($banner->status) }}</option>
                        </select>

                        <hr>

                        <label><strong>Update Status</strong></label>

                        <button class="btn btn-success btn-block update-status" data-id="{{ $banner->id }}"
                            data-status="published">Publish</button>

                        <button class="btn btn-warning btn-block update-status mt-2" data-id="{{ $banner->id }}"
                            data-status="pending">Mark Pending</button>

                        <!-- <button class="btn btn-danger btn-block update-status mt-2" data-id="{{ $banner->id }}"
                            data-status="rejected">Reject</button> -->

                        <hr>

                        <a href="{{ route('manage-banners.index') }}" class="btn btn-secondary btn-block">Back to List</a>
                        <a href="{{ route('manage-banners.edit', $banner->id) }}" class="btn btn-primary btn-block">Edit Banner</a>

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
            url: "{{ url('banners/update-status') }}/" + id,
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
