@include('front.header')
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
									<li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a>
									</li>
									<li class="breadcrumb-item active" aria-current="page">Contact Us</li>
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
					<h3>{{$contact->heading}}</h3>
					<p>{{$contact->text}}</p>
				</div>
				<div class="col-sm-12">
					<img src="{{ URL::to('/').'/storage/app/public/banner/'.$contact->banner }}" alt="Amrit Bharat - Contact Us" class="img-fluid">
				</div>
			</div>
		</div>
	</div>

	<section class="contact-page-2">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-7">
					<div class="contact-form-block">
						<div class="section-title">
							<h2>
								Send Us a Message
							</h2>
							<p>Your email address will not be published. All the fields are required.</p>
						</div>
						<div class="contact-form-wrapper">
							<form class="row" id="contact-us-form">
								<div class="form-group col-12">
									<input type="text" class="text-control" name="name" placeholder="Full Name">
									<div class="text-danger" id="name-err"></div>
								</div>

								<div class="form-group col-12">
									<input type="text" class="text-control" name="contact" placeholder="Mobile Number">
									<div class="text-danger" id="contact-err"></div>
								</div>

								<div class="form-group col-12">
									<input type="email" class="text-control" name="email" placeholder="Email Address">
									<div class="text-danger" id="email-err"></div>
								</div>

								<div class="form-group col-12">
									<textarea name="message" class="text-control" rows="4" cols="6" placeholder="Your Query"></textarea>
									<div class="text-danger" id="message-err"></div>
								</div>

								<div class="col-12 text-center">
									<button class="btn btn-primary contact-us-btn" type="button">SUBMIT</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="col-lg-5">
					<div class="contact-info-wrapper">
						<div class="contact-info-inner">
							<h2>Contact Information</h2>
							<div class="contact-info">
								<address class="address">
									{!! $aboutus->address ?? Null !!}

									<h3>We're Available 24/ 7. Call Now.</h3>
									<div class="call-contact">
										<a class="tel" href="tel:{{ $aboutus->contact1 }}"><i class="fas fa-phone"></i>{{ $aboutus->contact1 }}</a>
										<a class="tel" href="tel:{{ $aboutus->contact1 }}"><i class="fas fa-phone"></i>{{ $aboutus->contact2 }}</a>
									</div>
								</address>
								<!-- End of address -->

								<div class="contact-social">
									<div class="social-title">Follow Us</div>
									<ul class="social-share">
										<li><a href="{{ $headersetting->facebook ?? '#' }}"><i class="fab fa-facebook-f"></i></a></li>
										<li><a href="{{ $headersetting->twitter ?? '#' }}"><i class="fab fa-twitter"></i></a></li>
										<li><a href="{{ $headersetting->youtube ?? '#' }}"><i class="fab fa-youtube"></i></a></li>
										<li><a href="{{ $headersetting->instagram ?? '#' }}"><i class="fab fa-instagram"></i></a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="contact-location">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<h3>Our Location</h3>
					<iframe src="{{ $aboutus->map ?? Null }}" width="100%" height="auto" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
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
	$(document).on("click",".contact-us-btn",function(event){
		$('#name-err').html('');
        $('#email-err').html('');
        $('#contact-err').html('');
        $('#message-err').html('');
        $.ajax({
            url:"{{ URL::to('add-contact-us') }}",
            type:'POST',
            dataType:'json',
            data:$('#contact-us-form').serialize(),
            success:function(result){
                if(result.msgCode === '200') {
                    toastr.success(result.msgText);
                    location.reload();
                } else if (result.msgCode === '401') {
                    if(result.errors.name) {
                        $('#name-err').html(result.errors.name[0]);
                    }
                    if(result.errors.email) {
                        $('#email-err').html(result.errors.email[0]);
                    }
                    if(result.errors.contact) {
                        $('#contact-err').html(result.errors.contact[0]);
                    }
                    if(result.errors.message) {
                        $('#message-err').html(result.errors.message[0]);
                    }
                } else {
                    toastr.error('error encountered '+result.msgText);
                }
            },
            error:function(error){
                toastr.error('error encountered '+error.statusText);
            }
        });
		});
	});
</script>