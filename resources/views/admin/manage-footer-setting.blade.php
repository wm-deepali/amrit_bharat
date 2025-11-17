@include('admin.header')
<form method="POST" action="{{ route('update-footer-setting') }}" enctype="multipart/form-data">
@csrf
<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="content-header">
                    <h3 class="content-header-title">Site Settings</h3>
					<button class="btn btn-dark btn-save" type="submit"><i class="fas fa-plus"></i> Update Settings</button>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="route('home')">Dashboard</a></li>
                        <li class="breadcrumb-item">Site Settings</li>
                        <li class="breadcrumb-item active">Footer Settings</li>
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
                                <h4 class="form-section-h">Update Footer</h4>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Logo</label>
                                                <input type="file" class="text-control" name="image">
                                                @if (isset($footer->image) && Storage::exists($footer->image))
                                                    <img src="{{ URL::asset('storage/'.$footer->image) }}" class="img-fluid" style="height: 40px;">
                                                @endif
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Logo Link</label>
                                                <input type="text" class="text-control" placeholder="Logo Link" value="{{ old('link',$footer->link ?? null) }}" name="link">
                                            </div>
                                        </div>
										<div class="form-group row">
                                            <div class="col-sm-12">
                                                <label class="label label-control">Copyright Text</label>
                                                <input type="text" value="{{ old('content',$footer->content ?? null) }}" placeholder="Enter Copyright Text" class="text-control" name="content">
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
                                        <input type="text" class="text-control" placeholder="Enter Facebook URL" value="{{ old('facebook',$footer->facebook ?? null) }}" name="facebook">
                                    </div>
                                </div>
								<div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="label-control">Twitter <span class="required">*</span></label>
                                        <input type="text" class="text-control" placeholder="Enter Twitter URL" value="{{ old('twitter',$footer->twitter ?? null) }}" name="twitter">
                                    </div>
                                </div>
								<div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="label-control">RSS <span class="required">*</span></label>
                                        <input type="text" class="text-control" placeholder="Enter RSS URL" value="{{ old('rss',$footer->rss ?? null) }}" name="rss">
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
