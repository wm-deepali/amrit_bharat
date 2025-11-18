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
                    <h3 class="content-header-title">Manage Hashtags</h3>
                    <a href="{{ route('manage-hashtags.create') }}" class="btn btn-dark float-right">+ Add New Hashtag</a>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Hashtags</li>
                    </ol>

                    {{-- FILTER TABS --}}
                    <div class="mt-3">
                        <a href="{{ route('manage-hashtags.index', ['type'=>'all']) }}" class="tab-btn {{ $type=='all'?'tab-active':'' }}">All</a>
                        <a href="{{ route('manage-hashtags.index', ['type'=>'active']) }}" class="tab-btn {{ $type=='active'?'tab-active':'' }}">Active</a>
                        <a href="{{ route('manage-hashtags.index', ['type'=>'inactive']) }}" class="tab-btn {{ $type=='inactive'?'tab-active':'' }}">Inactive</a>
                    </div>

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
                    <table class="table table-bordered" id="hashtagsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Hashtag</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($hashtags as $index => $hashtag)
                                <tr>
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $hashtag->title }}</td>
                                    <td>#{{ $hashtag->hashtag }}</td>
                                    <td>{{ ucfirst($hashtag->status) }}</td>
                                    <td>
                                        <a href="{{ route('manage-hashtags.edit', $hashtag->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <button class="btn btn-sm btn-danger delete-hashtag" data-id="{{ $hashtag->id }}">Delete</button>
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
$(document).on('click', '.delete-hashtag', function() {
    if(!confirm("Are you sure you want to delete this hashtag?")) return;

    let id = $(this).data('id');
    $.ajax({
        url: "{{ url('manage-hashtags') }}/" + id,
        type: 'DELETE',
        data: { _token: "{{ csrf_token() }}" },
        success: function(res){
            toastr.success(res.msg);
            setTimeout(() => location.reload(), 500);
        },
        error: function(){ toastr.error("Unable to delete."); }
    });
});

// DATATABLE
$('#hashtagsTable').DataTable();
</script>
