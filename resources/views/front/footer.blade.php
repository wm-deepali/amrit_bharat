<div id="loader" class="modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog loader">
        <div class="modal-content">
            <div class="modal-body">
                <img src="{{ URL::asset('front/images/loader.gif') }}" alt="loader">
            </div>
        </div>
    </div>
</div>
<footer>
	<div class="container">
		<!--<div class="top-footer">-->
		<!--	<div class="row">-->
		<!--		<div class="col-sm-12">-->
				    
		<!--			<div class="foo-socials" style="margin-bottom:5px;">-->
		<!--			    <a href="{{ route('/') }}" class="d-inline-block">-->
		<!--				   <img src="https://www.amritbharat.com/newlogo.png" alt="Amrit Bharat Logo"  style="width:340px;">-->
		<!--				</a>-->
		<!--				<ul>-->
		<!--					<li><a href="{{ $footersetting->facebook ?? '#' }}"><i class="fab fa-facebook-f"></i></a></li>-->
		<!--					<li><a href="{{ $footersetting->twitter ?? '#' }}"><i class="fab fa-twitter"></i></a></li>-->
		<!--					<li><a href="{{ $footersetting->rss ?? '#' }}"><i class="fa fa-rss-square"></i></a></li>-->
		<!--				</ul>-->
		<!--			</div>-->
		<!--		</div>-->
		<!--	</div>-->
		<!--</div>-->
		<div class="middle-footer">
			<div class="row">
				<div class="col-sm-2 col-md-3">
					<ul>
					    <li class="title border-bottom" style="font-weight:600;font-size:20px;margin-bottom:15px;">Company Info</li>
						<li><a href="{{ route('about-us') }}">About Us</a></li>
						<li><a href="{{ route('become-a-reporter') }}">Become a Reporter</a></li>
						<li><a href="{{ route('contact-us') }}">Contact Us</a></li>
						<li><a href="{{ route('our-team') }}">Our Team</a></li>
						<li><a href="{{ route('e-paper') }}">E-Paper</a></li>
						<li><a href="{{ $footersetting->rss ?? '#' }}">RSS</a></li>
						<li><a href="{{ $footersetting->twitter ?? '#' }}">Twitter</a></li>
						<li><a href="{{ $footersetting->facebook ?? '#' }}">Facebook</a></li>
						<li><a href="{{ route('privacy-policy') }}">Privacy-Policy</a></li>
					</ul>
				</div>
				<div class="col-sm-2 col-md-3">
					<ul>
 <li class="title border-bottom" style="font-weight:600;font-size:20px;margin-bottom:15px;">Section </li>
                        <li><a href="{{ route('/') }}" title="Front Page">Front Page</a></li>
                        @if (isset($footercategories) && count($footercategories)>0)
                        @foreach ($footercategories as $footercategory)
                        <li><a href="{{ route('postbycategory',$footercategory->slug) }}" title="{{ $footercategory->name }}">{{ $footercategory->name }}</a></li>
                        @endforeach
                        @endif
					</ul>
				</div>
				<div class="col-sm-2 col-md-3">
					<ul>
						  <li class="title border-bottom" style="font-weight:600;font-size:20px;margin-bottom:15px;">Plus</li>
                          @if (isset($footercategories) && count($footercategories)>0)
                          @foreach ($footercategories as $footercategory)
                          <li><a href="{{ route('postbycategory',$footercategory->slug) }}" title="{{ $footercategory->name }}">{{ $footercategory->name }}</a></li>
                          @endforeach
                          @endif
					</ul>
				</div>
				<div class="col-sm-2 col-md-3">
				    <div class="">
			<div class="row">
				<div class="col-sm-12">
				    
					<div class="foo-socials" style="margin-bottom:15px;">
					    <a href="{{ route('/') }}" class="d-inline-block">
						   <img src="https://www.amritbharat.com/newlogo.png" alt="Amrit Bharat Logo"  style="width:240px;">
						</a>
					
					</div>
				</div>
			</div>
		</div>
					<div class="subscribe-form">
					    <form method="POST" action="{{ route('add-subscriber') }}">
							@csrf
					        <div class="form-group row">
					            <div class="col-sm-12">
					                <h3>Stay Connected</h3>
					                <label>Join over 10,500 people who received daily email and updates.</label>
					                <input type="email" class="form-control" placeholder="Enter Your Email" name="email">
					                <button class="btn btn-subscribe" type="submit">Subscribe Now</button>
					            </div>
					        </div>
					    </form>
					    	<ul class="d-flex " style="gap:20px;">
							<li><a href="{{ $footersetting->facebook ?? '#' }}"><i class="fab fa-facebook-f"></i></a></li>
							<li><a href="{{ $footersetting->twitter ?? '#' }}"><i class="fab fa-twitter"></i></a></li>
							<li><a href="{{ $footersetting->rss ?? '#' }}"><i class="fa fa-rss-square"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
		<div class="bottom-footer">
			<p>{{ $footersetting->content ?? 'Copyright © 2022 Amrit Bharat. - All Right Reserved' }} <a href="{{ route('terms-of-use') }}">Terms of Use</a> <a href="{{ route('privacy-policy') }}">Privacy Policy</a> <a href="{{ route('cookie-policy') }}">Cookie Policy</a></p>
		</div>
</footer>
<script src="{{ URL::asset('front/js/library/jquery/jquery.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('front/js/library/jquery/poppers.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('front/js/library/bootstrap-v4/bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('front/js/custom.js') }}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
</body>
</html>
