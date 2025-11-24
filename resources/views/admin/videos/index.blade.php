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
                    <h3 class="content-header-title">Manage Videos</h3>
                    <a href="{{ url('manage-videos/create') }}" class="btn btn-dark float-right">+ Add New Video</a>

                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage Videos</li>
                    </ol>
                </div>

                {{-- FILTER TABS --}}
                <div class="mt-3">
                    <a href="{{ url('manage-videos') }}"
                        class="tab-btn {{ $type == 'all' ? 'tab-active' : '' }}">All</a>

                    <a href="{{ url('manage-videos?type=pending') }}"
                        class="tab-btn {{ $type == 'pending' ? 'tab-active' : '' }}">Pending</a>

                    <a href="{{ url('manage-videos?type=published') }}"
                        class="tab-btn {{ $type == 'published' ? 'tab-active' : '' }}">Published</a>

                    <a href="{{ url('manage-videos?type=rejected') }}"
                        class="tab-btn {{ $type == 'rejected' ? 'tab-active' : '' }}">Rejected</a>
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
                    <table class="table table-bordered" id="videoTable">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>User Details</th>
                                <th>Title</th>
                                <th>Short Description</th>
                                <th>YouTube Link</th>
                                <th>Total Views</th>
                                <th>Total Likes</th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($videos as $video)
                                <tr>
                                    {{-- Created At --}}
                                    <td>
                                        {{ $video->created_at->format('d M Y') }} <br>
                                        {{ $video->created_at->format('h:i A') }}
                                    </td>

                                    {{-- User Details --}}
                                    <td>
                                        {{ $video->user->name ?? '-' }} <br>
                                        {{ $video->user->email ?? '-' }} <br>
                                        {{ $video->user->contact ?? '-' }}
                                    </td>

                                    {{-- Title --}}
                                    <td>{{ $video->title }}</td>

                                    {{-- Short Description --}}
                                    <td>{{ $video->short_description ?? '-' }}</td>

                                    {{-- YouTube Link --}}
                                    <td>
                                        <a href="{{ $video->youtube_link }}" target="_blank">Open Video</a>
                                    </td>
                                    <td>
                                        {{ $video->views }}
                                    </td>
                                    <td>
                                        {{ $video->total_likes }}
                                    </td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if($video->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($video->status == 'published')
                                            <span class="badge badge-success">Published</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>

                                    {{-- ACTION --}}
                                    <td>
                                        <a href="{{ url('manage-videos/view/' . $video->id) }}"
                                            class="btn btn-sm btn-info">View</a>

                                        <a href="{{ url('manage-videos/edit/' . $video->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>

                                        <button class="btn btn-sm btn-danger delete-video" data-id="{{ $video->id }}">
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
    // DELETE VIDEO
    $(document).on('click', '.delete-video', function () {
        let id = $(this).data('id');

        if (!confirm("Are you sure you want to delete this video?"))
            return;

        $.ajax({
            url: "/manage-videos/delete/" + id,
            type: "DELETE",
            data: { _token: "{{ csrf_token() }}" },
            success: function (res) {
                toastr.success(res.msg);
                setTimeout(() => location.reload(), 700);
            },
            error: function () {
                toastr.error("Unable to delete video");
            }
        });
    });

    // DATATABLE
    $('#videoTable').DataTable();
</script>