@include('admin.header')
<form method="POST" action="{{ route('update-privacy-policy') }}" enctype="multipart/form-data">
@csrf
<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="content-header">
                    <h3 class="content-header-title">Footer Management</h3>
					<button class="btn btn-dark btn-save" type="submit"><i class="fas fa-plus"></i> Update Settings</button>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Footer Management</li>
                        <li class="breadcrumb-item active">Privacy Policy</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="content-main-body">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block">
                            <form class="form-body">
                                <h4 class="form-section-h">Update Privacy Policy</h4>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label class="label label-control">Content</label>
                                                <textarea name="content" id="content" cols="30" rows="10" class="form-control">{{ old('content',$privacypolicy->content ?? null) }}</textarea>
                                                @error('content')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</form>
@include('admin.footer')
<script>
$(document).ready(function() {
  $('#content').summernote();
});
</script>