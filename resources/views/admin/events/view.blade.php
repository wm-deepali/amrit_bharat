@include('admin.header')

<style>
    .event-image {
        width: 140px;
        height: 110px;
        object-fit: cover;
        border-radius: 6px;
        margin: 5px;
        border: 1px solid #ddd;
    }

    .default-badge {
        background: #28a745;
        color: #fff;
        padding: 3px 7px;
        border-radius: 4px;
        font-size: 11px;
    }
</style>

<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">

                <div class="content-header">
                    <h3 class="content-header-title">View Event</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('manage-events.index') }}">Events</a></li>
                        <li class="breadcrumb-item active">View Event</li>
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

                            <h4 class="form-section-h">Event Details</h4>

                            <p><strong>Title:</strong> {{ $event->title }}</p>
                            <p><strong>Slug:</strong> {{ $event->slug }}</p>
                            <p><strong>Short Content:</strong> {{ $event->short_content }}</p>

                            <p><strong>Description:</strong></p>
                            <div class="mb-2">{!! nl2br(e($event->description)) !!}</div>

                            <p><strong>Venue:</strong> {{ $event->venue }}</p>
                            <p><strong>City:</strong> {{ $event->city->name ?? '-' }}</p>
                            <p><strong>State:</strong> {{ $event->state->name ?? '-' }}</p>

                            <p><strong>Start:</strong> {{ $event->start_datetime }}</p>
                            <p><strong>End:</strong> {{ $event->end_datetime }}</p>

                            <p>
                                <strong>Type:</strong> {{ ucfirst($event->type) }}
                                @if($event->type == "paid")
                                    <br>
                                    <strong>Price:</strong> ₹{{ $event->price }}
                                @endif
                            </p>

                            <p>
                                <strong>Status:</strong>
                                <span class="badge badge-info p-2">{{ ucfirst($event->status) }}</span>
                            </p>

                            <hr>

                            <h4 class="form-section-h">Gallery</h4>
                            <div class="d-flex flex-wrap">
                                @foreach($images as $image)
                                    <div class="text-center me-3 mb-2">
                                        <img src="{{ url('uploads/events/' . $image) }}" class="event-image">
                                        @if($event->default_image == $image)
                                            <div class="default-badge mt-1">Default</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
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
                            <option>{{ ucfirst($event->status) }}</option>
                        </select>

                        <hr>

                        <label><strong>Update Status</strong></label>

                        <button class="btn btn-success btn-block update-status" data-id="{{ $event->id }}"
                            data-status="published">
                            Publish
                        </button>

                        <button class="btn btn-warning btn-block update-status mt-2" data-id="{{ $event->id }}"
                            data-status="pending">
                            Mark Pending
                        </button>

                        <button class="btn btn-danger btn-block update-status mt-2" data-id="{{ $event->id }}"
                            data-status="rejected">
                            Reject
                        </button>

                        <hr>

                        <a href="{{ route('manage-events.index') }}" class="btn btn-secondary btn-block">
                            Back to List
                        </a>

                        <a href="{{ route('manage-events.edit', $event->id) }}" class="btn btn-primary btn-block">
                            Edit Event
                        </a>


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
            url: "{{ url('manage-events/update-status') }}/" + id,
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