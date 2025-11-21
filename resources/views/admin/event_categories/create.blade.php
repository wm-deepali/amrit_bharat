@include('admin.header')

<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="content-header">
                    <h3 class="content-header-title">Event Categories</h3>

                    <button type="button" class="btn btn-dark float-right submit-category-btn">
                        Submit Category
                    </button>

                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Event Categories</li>
                        <li class="breadcrumb-item active">Add Category</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-main-body">
    <div class="container">
        <form id="add-category-form">
            @csrf

            <div class="row">

                <!-- LEFT -->
                <div class="col-sm-9 col-md-7 col-lg-9">
                    <div class="card">
                        <div class="card-body">

                            <h4 class="form-section-h">Category Details</h4>

                            <!-- Name -->
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" class="text-control" name="name" id="name">
                                <div class="text-danger" id="name-err"></div>
                            </div>

                            <!-- Slug -->
                            <div class="form-group">
                                <label>Slug</label>
                                <input type="text" class="text-control" name="slug" id="slug">
                                <div class="text-danger" id="slug-err"></div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-sm-3 col-md-5 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <label>Status</label>
                            <select name="status" id="status" class="text-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
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

    // Auto slug generation
    $('#name').on('keyup blur', function () {
        let name = $(this).val();

        let slug = name
            .toLowerCase()
            .trim()
            .replace(/&/g, '-and-')
            .replace(/[\s\W-]+/g, '-')
            .replace(/^-+|-+$/g, '');

        $('#slug').val(slug);
    });

    // Submit Category
    $('.submit-category-btn').click(function (e) {
        e.preventDefault();

        let formData = new FormData(document.getElementById('add-category-form'));

        $.ajax({
            url: "{{ url('event-categories/store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                toastr.success("Category Created Successfully!");
                setTimeout(() => window.location.href = "{{ url('event-categories') }}", 1200);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;

                    $('.text-danger').html("");

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
