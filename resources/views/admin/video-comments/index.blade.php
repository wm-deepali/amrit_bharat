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
                    <h3 class="content-header-title">Manage Video Comments</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Video Comments</li>
                    </ol>
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
                    <table class="table table-bordered" id="videoCommentsTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>Date & Time</th>
                                <th>ID</th>
                                <th>Video Title</th>
                                <th>User Info</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($comments as $comment)
                                <tr id="commentRow{{ $comment->id }}">
                                    <td>
                                        <input type="checkbox" class="commentCheckbox" value="{{ $comment->id }}">
                                    </td>
                                    <td>{{ $comment->created_at->format('d M Y h:i A') }}</td>
                                    <td>{{ $comment->id }}</td>
                                    <td>{{ $comment->video->title ?? '-' }}</td>
                                    <td>{{ $comment->user->name ?? 'Guest' }} <br> {{ $comment->user->email ?? '-' }}
                                        <br>{{ $comment->user->contact ?? '-' }}
                                    </td>
                                    <td>{{ $comment->comment }}</td>

                                    {{-- Status --}}
                                    <td>
                                        <span
                                            class="badge badge-{{ $comment->status == 'Approved' ? 'success' : 'danger' }}"
                                            id="statusBadge{{ $comment->id }}">
                                            {{ $comment->status }}
                                        </span>
                                        <button class="btn btn-sm btn-secondary mt-1 update-status"
                                            data-id="{{ $comment->id }}">
                                            Toggle
                                        </button>
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-danger delete-comment" data-id="{{ $comment->id }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                    <button class="btn btn-danger mt-2" id="bulkDeleteBtn">Delete Selected</button>
                </div>

            </div>
        </div>

    </div>
</section>

@include('admin.footer')

<script>
    // DELETE SINGLE COMMENT
    $(document).on('click', '.delete-comment', function () {
        let id = $(this).data('id');
        if (!confirm("Are you sure you want to delete this comment?")) return;

        $.ajax({
            url: "/video-comments/" + id,
            type: "DELETE",
            data: { _token: "{{ csrf_token() }}" },
            success: function (res) {
                if (res.status) {
                    $('#commentRow' + id).remove();
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message);
                }
            },
            error: function () {
                toastr.error("Unable to delete comment");
            }
        });
    });

    // SELECT / DESELECT ALL
    $('#selectAll').on('click', function () {
        $('.commentCheckbox').prop('checked', $(this).prop('checked'));
    });

    // BULK DELETE
    $('#bulkDeleteBtn').click(function () {
        let ids = [];
        $('.commentCheckbox:checked').each(function () {
            ids.push($(this).val());
        });

        if (ids.length == 0) {
            alert("Select at least one comment");
            return;
        }

        if (!confirm("Are you sure you want to delete selected comments?")) return;

        $.ajax({
            url: "{{ route('manage-video-comments.bulk-delete') }}",
            type: "POST",
            data: { ids: ids, _token: "{{ csrf_token() }}" },
            success: function (res) {
                if (res.status) {
                    ids.forEach(id => $('#commentRow' + id).remove());
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message);
                }
            },
            error: function () {
                toastr.error("Unable to delete selected comments");
            }
        });
    });

    // TOGGLE STATUS
    $(document).on('click', '.update-status', function () {
        let id = $(this).data('id');

        $.ajax({
            url: "/video-comments/" + id + "/toggle-status",
            type: "POST",
            data: { _token: "{{ csrf_token() }}" },
            success: function (res) {
                if (res.status) {
                    let badge = $('#statusBadge' + id);
                    badge.text(res.new_status);
                    badge.removeClass('badge-success badge-danger')
                        .addClass(res.new_status == 'Approved' ? 'badge-success' : 'badge-danger');
                    toastr.success('Status updated to ' + res.new_status);
                } else {
                    toastr.error(res.message);
                }
            },
            error: function () {
                toastr.error("Unable to update status");
            }
        });
    });


    // DATATABLE
    $('#videoCommentsTable').DataTable();
</script>