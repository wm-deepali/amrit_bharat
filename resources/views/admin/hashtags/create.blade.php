{{-- resources/views/admin/hashtags/create.blade.php --}}
@include('admin.header')

<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="content-header">
                    <h3 class="content-header-title">Add Hashtag</h3>
                    <button type="button" class="btn btn-dark float-right submit-hashtag-btn">
                        Submit Hashtag
                    </button>

                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('manage-hashtags.index') }}">Hashtags</a></li>
                        <li class="breadcrumb-item active">Add Hashtag</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="content-main-body">
    <div class="container">
        <form id="add-hashtag-form">
            @csrf

            <div class="row">

                <!-- LEFT FORM -->
                <div class="col-sm-9 col-md-7 col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">

                                <h4 class="form-section-h">Hashtag Details</h4>

                                <!-- Title -->
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="text-control" name="title" id="title">
                                    <div class="text-danger" id="title-err"></div>
                                </div>

                                <!-- Hashtag -->
                                <div class="form-group">
                                    <label>Hashtag (without #)</label>
                                    <input type="text" class="text-control" name="hashtag" id="hashtag">
                                    <div class="text-danger" id="hashtag-err"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT SIDEBAR -->
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
    // Auto-generate hashtag slug
    $('#title').on('keyup blur', function () {
        let title = $(this).val();

        let slug = title
            .toLowerCase()
            .trim()
            .replace(/&/g, '_and_')
            .replace(/[\s\W-]+/g, '_')
            .replace(/^-+|-+$/g, '');

        $('#hashtag').val(slug);
    });

    // Submit Hashtag
    $('.submit-hashtag-btn').click(function (e) {
        e.preventDefault();

        let formData = new FormData(document.getElementById('add-hashtag-form'));

        $.ajax({
            url: "{{ url('manage-hashtags/store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                toastr.success("Hashtag Created Successfully!");
                setTimeout(() => window.location.href = "{{ route('manage-hashtags.index') }}", 1200);
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
