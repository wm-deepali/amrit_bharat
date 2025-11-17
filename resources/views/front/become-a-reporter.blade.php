@include('front.header')
@if(session()->get('success'))
    <div class="alert alert-success">
        <a href="javascript:void(0);" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> {{ session()->get('success') }}
    </div>
@endif
@if(session()->get('error'))
    <div class="alert alert-danger">
        <a href="javascript:void(0);" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Error!</strong> {{ session()->get('error') }}
    </div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <p>Fill the Form correctly and completely to register</p>
    </div>
@endif
<main class="page-content">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="breadcrumb-sec">
					<div class="row">
						<div class="col-sm-12">
							<nav class="breadcrumb-m" aria-label="breadcrumb">
								<ol class="breadcrumb">
									<li class="item">You are here :&nbsp;&nbsp;</li>
									<li class="breadcrumb-item"><a href="index.php">Home</a>
									</li>
									<li class="breadcrumb-item active" aria-current="page">Become a Reporter</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="contact-page-1">
			<div class="row justify-content-center">
				<div class="col-sm-8">
					<h3>Become a Reporter</h3>
					<p>Wherever &amp; whenever you need us. We are here for you - contact us for all your support needs, be it technical, general queries or information support.</p>
				</div>
			</div>
		</div>
	</div>

	<section class="contact-page-2">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-7">
					<div class="contact-form-block">
						<div class="section-title text-center">
							<h2>
								Reporter Registration
                            </h2>
							<p>Reporter will be verified by the tiranga times team. All the fields are required.
							</p>
						</div>
						<div class="contact-form-wrapper">
                            <form class="row" method="POST" action="{{ route('submit-become-a-reporter') }}" enctype="multipart/form-data">
                                @csrf
								<div class="form-group col-12 col-sm-6">
									<input type="file" class="text-control @error('image') is-invalid @enderror" name="image">
                                    @error('image')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    <span>Profile Picture to be less than 1 MB</span>
								</div>

								<div class="form-group col-12 col-sm-6">
                                    <input type="text" class="text-control @error('name') is-invalid @enderror" name="name" placeholder="Full Name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
								</div>

								<div class="form-group col-12 col-sm-6">
                                    <input type="email" class="text-control @error('email') is-invalid @enderror" name="email" placeholder="Email Address" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
								</div>

								<div class="form-group col-12 col-sm-6">
                                    <input type="text" class="text-control @error('contact') is-invalid @enderror" name="contact" placeholder="Mobile Number" value="{{ old('contact') }}">
                                    @error('contact')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
								</div>

								<div class="form-group col-12 col-sm-6">
									<select class="text-control @error('gender') is-invalid @enderror" name="gender">
										<option value="">Select Gender</option>
										<option value="male" @if (old('gender') && old('gender')=='male')
                                            selected
                                        @endif>Male</option>
										<option value="female" @if (old('gender') && old('gender')=='female')
                                            selected
                                        @endif>Female</option>
                                    </select>
                                    @error('gender')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
								</div>

								<!--<div class="form-group col-12 col-sm-6">-->
								<!--	<input type="file" class="text-control @error('cv') is-invalid @enderror" name="cv">-->
        <!--                            <span>Attached Updated CV</span>-->
        <!--                            @error('cv')-->
        <!--                                <div class="alert alert-danger">{{ $message }}</div>-->
        <!--                            @enderror-->
								<!--</div>-->

								<div class="form-group col-12">
                                    <input type="text" class="text-control @error('address') is-invalid @enderror" name="address" placeholder="Address" value="{{ old('address') }}">
                                    @error('address')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
								</div>

								<div class="form-group col-12 col-sm-6">
									<select class="text-control @error('state') is-invalid @enderror" name="state" id="state">
                                        <option value="">Select State</option>
                                        @if (isset($states) && count($states)>0)
                                        @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    @error('state')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
								</div>

								<div class="form-group col-12 col-sm-6">
									<select class="text-control @error('city') is-invalid @enderror" name="city" id="city">
                                        <option value="">Select City</option>
                                    </select>
                                    @error('city')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
								</div>

								<div class="col-12 text-center">
									<button class="btn btn-primary" type="submit">SUBMIT</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
@include('front.footer')
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
            },
            error:function(error) {
                toastr.error('error encountered '+error.statusText);
            }
        });
    });
});
</script>
