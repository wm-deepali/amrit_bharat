@include('admin.header')
<form method="POST" action="{{ route('update-ad-setting') }}" enctype="multipart/form-data">
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
                        <li class="breadcrumb-item active">Cookie Policy</li>
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
                                <h4 class="form-section-h">Update Ads Setting</h4>
                                <div class="row">
                                    <h5 class="form-section">Home Page</h5>
                                    <div class="col-sm-12">
                                        <h6 class="form-section">Upper-sidebar (300x250)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="homepageuppersidebar300x250time" value="{{ $setting->homepageuppersidebar300x250time ?? '' }}">
                                                @error('homepageuppersidebar300x250time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="homepageuppersidebar300x250number" value="{{ $setting->homepageuppersidebar300x250number ?? '' }}">
                                                @error('homepageuppersidebar300x250time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <h6 class="form-section">Middle-sidebar (300x250)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="homepagemiddlesidebar300x250time" id="" value="{{ $setting->homepagemiddlesidebar300x250time ?? '' }}">
                                                @error('homepagemiddlesidebar300x250time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="homepagemiddlesidebar300x250number" id="" value="{{ $setting->homepagemiddlesidebar300x250number ?? '' }}">
                                                @error('homepagemiddlesidebar300x250number')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <h6 class="form-section">Lower-sidebar (300x250)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="homepagelowersidebar300x250time" id="" value="{{ $setting->homepagelowersidebar300x250time ?? '' }}">
                                                @error('homepagelowersidebar300x250time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="homepagelowersidebar300x250number" id="" value="{{ $setting->homepagelowersidebar300x250number ?? '' }}">
                                                @error('homepagelowersidebar300x250number')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <h6 class="form-section">Upper-sidebar (300x600)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="homepageuppersidebar300x600time" id="" value="{{ $setting->homepageuppersidebar300x600time ?? '' }}">
                                                @error('homepageuppersidebar300x600time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="homepageuppersidebar300x600number" id="" value="{{ $setting->homepageuppersidebar300x600number ?? '' }}">
                                                @error('homepageuppersidebar300x600number')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <h6 class="form-section">Middle-sidebar (300x600)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="homepagemiddlesidebar300x600time" id="" value="{{ $setting->homepagemiddlesidebar300x600time ?? '' }}">
                                                @error('homepagemiddlesidebar300x600time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="homepagemiddlesidebar300x600number" id="" value="{{ $setting->homepagemiddlesidebar300x600number ?? '' }}">
                                                @error('homepagemiddlesidebar300x600number')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <h6 class="form-section">Lower-sidebar (300x600)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="homepagelowersidebar300x600time" id="" value="{{ $setting->homepagelowersidebar300x600time ?? '' }}">
                                                @error('homepagelowersidebar300x600time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="homepagelowersidebar300x600number" id="" value="{{ $setting->homepagelowersidebar300x600number ?? '' }}">
                                                @error('homepagelowersidebar300x600number')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <h6 class="form-section">Upper-banner (728x90)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="homepageupperbanner728x90time" id="" value="{{ $setting->homepageupperbanner728x90time ?? '' }}">
                                                @error('homepageupperbanner728x90time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="homepageupperbanner728x90number" id="" value="{{ $setting->homepageupperbanner728x90number ?? '' }}">
                                                @error('homepageupperbanner728x90number')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <h6 class="form-section">Middle-banner (728x90)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="homepagemiddlebanner728x90time" id="" value="{{ $setting->homepagemiddlebanner728x90time ?? '' }}">
                                                @error('homepagemiddlebanner728x90time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="homepagemiddlebanner728x90number" id="" value="{{ $setting->homepagemiddlebanner728x90number ?? '' }}">
                                                @error('homepagemiddlebanner728x90number')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <h6 class="form-section">Lower-banner (728x90)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="homepagelowerbanner728x90time" id="" value="{{ $setting->homepagelowerbanner728x90time ?? '' }}">
                                                @error('homepagelowerbanner728x90time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="homepagelowerbanner728x90number" id="" value="{{ $setting->homepagelowerbanner728x90number ?? '' }}">
                                                @error('homepagelowerbanner728x90number')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <h6 class="form-section">Lowest-banner (728x90)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="homepagelowestbanner728x90time" id="" value="{{ $setting->homepagelowestbanner728x90time ?? '' }}">
                                                @error('homepagelowestbanner728x90time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="homepagelowestbanner728x90number" id="" value="{{ $setting->homepagelowestbanner728x90number ?? '' }}">
                                                @error('homepagelowestbanner728x90number')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <h5 class="form-section">Category Page</h5>
                                    <div class="col-sm-12">
                                        <h6 class="form-section">Sidebar (300x250)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="categorypagesidebar300x250time" id="" value="{{ $setting->categorypagesidebar300x250time ?? '' }}">
                                                @error('categorypagesidebar300x250time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="categorypagesidebar300x250number" id="" value="{{ $setting->categorypagesidebar300x250number ?? '' }}">
                                                @error('categorypagesidebar300x250number')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <h6 class="form-section">Sidebar (300x600)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="categorypagesidebar300x600time" id="" value="{{ $setting->categorypagesidebar300x600time ?? '' }}">
                                                @error('categorypagesidebar300x600time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="categorypagesidebar300x600number" id="" value="{{ $setting->categorypagesidebar300x600number ?? '' }}">
                                                @error('categorypagesidebar300x600number')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <h5 class="form-section">Post Page</h5>
                                    <div class="col-sm-12">
                                        <h6 class="form-section">Upper-Sidebar (300x250)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="postpageuppersidebar300x250time" id="" value="{{ $setting->postpageuppersidebar300x250time ?? '' }}">
                                                @error('postpageuppersidebar300x250time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="postpageuppersidebar300x250number" id="" value="{{ $setting->postpageuppersidebar300x250number ?? '' }}">
                                                @error('postpageuppersidebar300x250number')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <h6 class="form-section">Lower-Sidebar (300x250)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="postpagelowersidebar300x250time" id="" value="{{ $setting->postpagelowersidebar300x250time ?? '' }}">
                                                @error('postpagelowersidebar300x250time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="postpagelowersidebar300x250number" id="" value="{{ $setting->postpagelowersidebar300x250number ?? '' }}">
                                                @error('postpagelowersidebar300x250number')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <h6 class="form-section">Sidebar (300x600)</h6>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Repetition Time</label>
                                                <input type="text" class="form-control" name="postpagesidebar300x600time" id="" value="{{ $setting->postpagesidebar300x600time ?? '' }}">
                                                @error('postpagesidebar300x600time')
                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Number of Ads</label>
                                                <input type="text" class="form-control" name="postpagesidebar300x600number" id="" value="{{ $setting->postpagesidebar300x600number ?? '' }}">
                                                @error('postpagesidebar300x600number')
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