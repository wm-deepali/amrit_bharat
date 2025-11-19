@include('admin.header')

<style>
    .tab-btn {
        padding: 8px 20px;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 600;
        display: inline-block;
        margin-right: 10px;
    }
    .tab-active {
        background: #000;
        color: #fff;
    }
</style>

<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content-header">
                    <h3 class="content-header-title">Manage Events</h3>
                    <a href="{{ url('manage-events/create') }}" class="btn btn-dark float-right">+ Add New Event</a>

                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage Events</li>
                    </ol>
                </div>

                {{-- TABS --}}
                <div class="mt-3">
                    <a href="{{ url('manage-events') }}" class="tab-btn {{ $type=='all' ? 'tab-active':'' }}">All</a>
                    <a href="{{ url('manage-events?type=pending') }}" class="tab-btn {{ $type=='pending' ? 'tab-active':'' }}">Pending</a>
                    <a href="{{ url('manage-events?type=published') }}" class="tab-btn {{ $type=='published' ? 'tab-active':'' }}">Published</a>
                    <a href="{{ url('manage-events?type=rejected') }}" class="tab-btn {{ $type=='rejected' ? 'tab-active':'' }}">Rejected</a>
                </div>

            </div>
        </div>
    </div>
</section>

<section class="content-main-body">
    <div class="container">
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="eventTable">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>User Details</th>
                                <th>Event Name</th>
                                <th>Event Date</th>
                                <th>Venue</th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($events as $event)
                                <tr>

                                    {{-- Created At --}}
                                    <td>
                                        {{ $event->created_at->format('d M Y') }}<br>
                                        {{ $event->created_at->format('h:i A') }}
                                    </td>

                                    {{-- USER DETAILS --}}
                                    <td>
                                        {{ $event->user->name ?? '-' }} <br>
                                        {{ $event->user->email ?? '-' }} <br>
                                        {{ $event->user->contact ?? '-' }}
                                    </td>

                                    {{-- EVENT TITLE --}}
                                    <td>{{ $event->title }}</td>

                                    {{-- EVENT DATE --}}
                                    <td>
                                        {{ \Carbon\Carbon::parse($event->start_datetime)->format('d M Y h:i A') }}
                                        <br>
                                        to <br>
                                        {{ \Carbon\Carbon::parse($event->end_datetime)->format('d M Y h:i A') }}
                                    </td>

                                    {{-- VENUE --}}
                                    <td>{{ $event->venue }}</td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if($event->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($event->status == 'published')
                                            <span class="badge badge-success">Published</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>

                                    {{-- ACTION --}}
                                    <td>
                                        <a href="{{ url('manage-events/view/'.$event->id) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ url('manage-events/edit/'.$event->id) }}" class="btn btn-sm btn-primary">Edit</a>

                                        <button class="btn btn-sm btn-danger delete-event"
                                                data-id="{{ $event->id }}">
                                            Delete
                                        </button>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>

@include('admin.footer')

<script>
    // Delete Event
    $(document).on('click', '.delete-event', function () {
        let id = $(this).data('id');

        if (!confirm("Are you sure you want to delete this event?"))
            return;

        $.ajax({
            url: "{{ url('manage-events/delete') }}/" + id,
            type: "DELETE",
            data: { _token: "{{ csrf_token() }}" },
            success: function (res) {
                toastr.success(res.msgText || "Deleted Successfully");
                setTimeout(() => location.reload(), 800);
            },
            error: function () {
                toastr.error("Unable to delete event");
            }
        });
    });

    $('#eventTable').DataTable();
</script>
