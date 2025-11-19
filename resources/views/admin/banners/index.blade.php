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
                    <h3 class="content-header-title">Manage Banners & Offers</h3>
                    <a href="{{ route('manage-banners.create') }}" class="btn btn-dark float-right">+ Add New Banner</a>

                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage Banners & Offers</li>
                    </ol>
                </div>

                {{-- FILTER TABS --}}
                <div class="mt-3">
                    <a href="{{ route('manage-banners.index') }}" class="tab-btn {{ $type == 'all' ? 'tab-active' : '' }}">All</a>
                    <a href="{{ route('manage-banners.index', ['type' => 'pending']) }}" class="tab-btn {{ $type == 'pending' ? 'tab-active' : '' }}">Pending</a>
                    <a href="{{ route('manage-banners.index', ['type' => 'published']) }}" class="tab-btn {{ $type == 'published' ? 'tab-active' : '' }}">Published</a>
                    <a href="{{ route('manage-banners.index', ['type' => 'rejected']) }}" class="tab-btn {{ $type == 'rejected' ? 'tab-active' : '' }}">Rejected</a>
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
                    <table class="table table-bordered" id="bannerTable">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>User Details</th>
                                <th>Title</th>
                                <th>Image</th>
                                <th>URL</th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($banners as $banner)
                                <tr>
                                    {{-- Created At --}}
                                    <td>
                                        {{ $banner->created_at->format('d M Y') }} <br>
                                        {{ $banner->created_at->format('h:i A') }}
                                    </td>

                                    {{-- User Details --}}
                                    <td>
                                        {{ $banner->user->name ?? '-' }} <br>
                                        {{ $banner->user->email ?? '-' }}
                                    </td>

                                    {{-- Title --}}
                                    <td>{{ $banner->title }}</td>

                                    {{-- Image --}}
                                    <td>
                                        @if($banner->image)
                                            <img src="{{ asset('storage/' . $banner->image) }}" alt="Banner Image" width="100">
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- URL --}}
                                    <td>
                                        @if($banner->url)
                                            <a href="{{ $banner->url }}" target="_blank">{{ $banner->url }}</a>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        @if($banner->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($banner->status == 'published')
                                            <span class="badge badge-success">Published</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td>
                                         <a href="{{ url('manage-banners/view/' . $banner->id) }}"
                                            class="btn btn-sm btn-info">View</a>

                                        <a href="{{ route('manage-banners.edit', $banner->id) }}" class="btn btn-sm btn-primary">Edit</a>

                                        <button class="btn btn-sm btn-danger delete-banner" data-id="{{ $banner->id }}">Delete</button>
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
    // DELETE BANNER
    $(document).on('click', '.delete-banner', function () {
        let id = $(this).data('id');

        if (!confirm("Are you sure you want to delete this banner?")) return;

        $.ajax({
            url: "/manage-banners/" + id + "/delete",
            type: "DELETE",
            data: { _token: "{{ csrf_token() }}" },
            success: function (res) {
                toastr.success(res.message);
                setTimeout(() => location.reload(), 700);
            },
            error: function () {
                toastr.error("Unable to delete banner");
            }
        });
    });

    // DATATABLE
    $('#bannerTable').DataTable();
</script>
