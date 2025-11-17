@include('admin.header')
<section class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="content-header">
                    <h3 class="content-header-title">My Account</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">My Account</li>
                        <li class="breadcrumb-item active">Update Profile</li>
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
                            <form class="form-body" method="POST" action="{{ route('update-profile') }}" enctype="multipart/form-data">
                                @csrf
                                <h4 class="form-section-h">Update Profile</h4>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                @if (isset($user->image) && Storage::exists($user->image))
                                                <img src="{{ URL::asset('storage/'.$user->image) }}" class="img-fluid img-stud-pro">
                                                @else
                                                <img src="{{ URL::asset('admin/images/usr.png') }}" class="img-fluid img-stud-pro">
                                                @endif
                                            </div>
                                            <div class="col-sm-12">
                                                <input type="file" class="text-control" name="image">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Name</label>
                                                <input type="text" class="text-control" placeholder="Enter Name" name="name" value="{{ $user->name }}">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Email</label>
                                                <input type="text" class="text-control" placeholder="Enter Email" name="email" value="{{ $user->email }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label label-control">Mobile No.</label>
                                                <input type="text" class="text-control" placeholder="Enter Mobile No." name="contact" value="{{ $user->contact }}">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label label-control">Address</label>
                                                <input type="text" class="text-control" placeholder="Enter Address" name="address" value="{{ $user->address }}">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label class="label-control">State</label>
                                                <select name="state" id="state" class="form-control">
                                                    <option value="">Select State</option>
                                                    @if (isset($states) && count($states)>0)
                                                    @foreach ($states as $state)
                                                    <option value="{{ $state->id }}" @if ($state->id==$user->state_id)
                                                        selected
                                                    @endif>{{ $state->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="label-control">City</label>
                                                <select name="city" id="city" class="form-control">
                                                    <option value="">Select City</option>
                                                    @if (isset($cities) && count($cities)>0)
                                                    @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}" @if ($city->id==$user->city_id)
                                                        selected
                                                    @endif>{{ $city->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-center">
                                        <button type="submit" class="btn btn-dark">Update Profile</button>
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
                            <form action="{{ route('update-profile-password') }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <h4 class="form-section-h">Login Security</h4>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="label-control">New Password <span class="required">*</span></label>
                                        <input type="password" class="text-control" placeholder="Enter New Password" name="password">
                                        <span>Leave Blank if you don't want to change.</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="label-control">Re-enter Password <span class="required">*</span></label>
                                        <input type="password" class="text-control" placeholder="Re-enter Password" name="password_confirmation">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <button type="submit" class="btn-w100 btn btn-dark">Update Changes</button>
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
@include('admin.footer')
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$( document ).ajaxStart(function() {
    $("#loader").modal('show');
});
$( document ). ajaxComplete(function() {
    $("#loader").modal('hide');
});
$(document).ready(function(){
    $(document).on("change","#state",function(event){
        let stateid = $(this).val();
        $.ajax({
            url:"{{ URL::to('fetchcitybystateid') }}",
            type:"POST",
            dataType:"json",
            data:{"state_id":stateid},
            success:function(result) {
                if(result.msgCode==='200') {
                    $("#city").html(result.html);
                } else if(result.msgCode==='400') {
                    toastr.error('error encountered '+result.msgText);
                }
                $("#loader").modal('hide');
            },
            error:function(error) {
                toastr.error('error encountered '+error.statusText);
                $("#loader").modal('hide');
            }
        });
    });
});
</script>
