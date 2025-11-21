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
                    <h3 class="content-header-title">Manage Event Categories</h3>
                    <a href="{{ url('event-categories/create') }}" class="btn btn-dark float-right">+ Add New
                        Category</a>

                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Event Categories</li>
                    </ol>
                </div>

                {{-- FILTER TABS --}}
                <div class="mt-3">
                    <a href="{{ url('event-categories?type=all') }}"
                        class="tab-btn {{ $type == 'all' ? 'tab-active' : '' }}">All</a>

                    <a href="{{ url('event-categories?type=active') }}"
                        class="tab-btn {{ $type == 'active' ? 'tab-active' : '' }}">Active</a>

                    <a href="{{ url('event-categories?type=inactive') }}"
                        class="tab-btn {{ $type == 'inactive' ? 'tab-active' : '' }}">Inactive</a>
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
                    <table class="table table-bordered" id="categoryTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($categories as $index => $cat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    <td>{{ $cat->name }}</td>

                                    <td>{{ $cat->slug }}</td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if($cat->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td>
                                        <a href="{{ url('event-categories/' . $cat->id . '/edit') }}"
                                            class="btn btn-sm btn-primary">
                                            Edit
                                        </a>

                                        <button class="btn btn-sm btn-danger delete-category" data-id="{{ $cat->id }}">
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
    // DELETE CATEGORY
    $(document).on('click', '.delete-category', function () {
        let id = $(this).data('id');

        if (!confirm("Are you sure you want to delete this category?"))
            return;

        $.ajax({
            url: "{{ url('event-categories') }}/destroy/" + id,
            type: 'DELETE',
            data: { _token: "{{ csrf_token() }}" },
            success: function (res) {
                toastr.success(res.msg);
                setTimeout(() => location.reload(), 700);
            },
            error: function () {
                toastr.error("Unable to delete category");
            }
        });
    });

    // DATATABLE INIT
    $('#categoryTable').DataTable();
</script>