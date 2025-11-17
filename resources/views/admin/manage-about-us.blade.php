@include('admin.header')
<form method="POST" action="{{ route('update-about-us') }}" enctype="multipart/form-data">
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
                        <li class="breadcrumb-item active">About Us</li>
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
                                <h4 class="form-section-h">Update About Us</h4>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label class="label label-control">Content 1</label>
                                                <textarea name="content1" id="content1" cols="30" rows="10" class="form-control">{{ old('content1',$aboutus->content1 ?? null) }}</textarea>
                                                @error('content1')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label class="label label-control">Content 2</label>
                                                <textarea name="content2" id="content2" cols="30" rows="10" class="form-control">{{ old('content2',$aboutus->content2 ?? null) }}</textarea>
                                                @error('content2')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label class="label label-control">Address</label>
                                                <textarea name="address" id="address" cols="30" rows="10" class="form-control">{{ old('address',$aboutus->address ?? null) }}</textarea>
                                                @error('address')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Contact 1</label>
                                                <input type="text" name="contact1" class="form-control" value="{{ old('contact1',$aboutus->contact1 ?? null) }}">
                                                @error('contact1')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Contact 2</label>
                                                <input type="text" name="contact2" class="form-control" value="{{ old('contact2',$aboutus->contact2 ?? null) }}">
                                                @error('contact2')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label class="label label-control">Map</label>
                                                <input type="text" name="map" class="form-control" value="{{ old('map',$aboutus->map ?? null) }}">
                                                @error('map')
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
  $('#content1').summernote();
  $('#content2').summernote();
  $('#address').summernote();
});
</script>