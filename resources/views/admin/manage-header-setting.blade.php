@include('admin.header')
<form method="POST" action="{{ route('update-header-setting') }}" enctype="multipart/form-data">
@csrf
<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="content-header">
                    <h3 class="content-header-title">Site Settings</h3>
					<button class="btn btn-dark btn-save" type="submit"><i class="fas fa-plus"></i> Update Settings</button>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Site Settings</li>
                        <li class="breadcrumb-item active">Header Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="content-main-body">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block">
                            <form class="form-body">
                                <h4 class="form-section-h">Update Header</h4>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Logo</label>
                                                <input type="file" class="text-control" name="image">
                                                @if (isset($header->image) && Storage::exists($header->image))
                                                    <img src="{{ URL::asset('storage/'.$header->image) }}" class="img-fluid" style="height: 40px;">
                                                @endif
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Logo Link</label>
                                                <input type="text" class="text-control" placeholder="Logo Link" value="{{ old('link',$header->link ?? null) }}" name="link">
                                            </div>
                                        </div>
										<div class="form-group row">
											<div class="col-sm-6">
                                                <label class="label label-control">Alt/Title</label>
                                                <input type="text" class="text-control" placeholder="Tiranga Times" name="title" value="{{ old('title',$header->title ?? null) }}">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Date &amp; Time Format</label>
                                                <select class="text-control" name="datetimeformat">
                                                    <option value="l d M Y G:i A" @if (isset($header->datetimeformat) && $header->datetimeformat == "l d M Y G:i A" )
                                                        selected
                                                    @endif>Wed 05 March, 2014 08:19:18 AM</option>
													<option value="d-M-Y G:i A" @if (isset($header->datetimeformat) && $header->datetimeformat == "d-M-Y G:i A" )
                                                        selected
                                                    @endif>05 March, 2014 08:19:18 AM</option>
													<option value="d-m-Y G:i A" @if (isset($header->datetimeformat) && $header->datetimeformat == "d-m-Y G:i A" )
                                                        selected
                                                    @endif>05-04-2014 08:19 AM</option>
												</select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block">
                            <div class="form-body">
                                <h4 class="form-section-h">Social Settings</h4>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="label-control">Facebook <span class="required">*</span></label>
                                        <input type="text" class="text-control" placeholder="Enter Facebook URL" value="{{ old('facebook',$header->facebook ?? null) }}" name="facebook">
                                    </div>
                                </div>
								<div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="label-control">Twitter <span class="required">*</span></label>
                                        <input type="text" class="text-control" placeholder="Enter Twitter URL" value="{{ old('twitter',$header->twitter ?? null) }}" name="twitter">
                                    </div>
                                </div>
								<div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="label-control">Youtube <span class="required">*</span></label>
                                        <input type="text" class="text-control" placeholder="Enter Youtube URL" value="{{ old('youtube',$header->youtube ?? null) }}" name="youtube">
                                    </div>
                                </div>
								<div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="label-control">Instagram <span class="required">*</span></label>
                                        <input type="text" class="text-control" placeholder="Enter Instagram URL" value="{{ old('instagram',$header->instagram ?? null) }}" name="instagram">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</form>
@include('admin.footer')
