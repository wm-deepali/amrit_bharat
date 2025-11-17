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
									<li class="breadcrumb-item active" aria-current="page">About Us</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="about-us">
					{!! $aboutus->content1 ?? Null !!}
					{!! $aboutus->content2 ?? Null !!}
				</div>
			</div>
		</div>
	</div>

	<section class="publishers-authors">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-sm-8">
					<div class="heading-pa">
						<h2>Meet Our Publishing Authors</h2>
						<p>Wherever &amp; whenever you need us. We are here for you - contact us for all your support needs, be it technical, general queries or information support.</p>
					</div>
				</div>
			</div>
			<div class="row">
				@if (isset($reporters) && count($reporters)>0)
					@foreach ($reporters as $reporter)
					<div class="col-sm-3">
						<div class="team-main">
							<div class="team-img">
								@if (isset($reporter->image) && Storage::exists($reporter->image))
								<img src="{{ URL::asset('storage/'.$reporter->image) }}" alt="{{ $reporter->name }}" class="img-fluid">
								@endif
							</div>
							<div class="team-con">
								<h3>{{ $reporter->name }}</h3>
								<h4>{{ $reporter->role }}</h4>
							</div>
						</div>
					</div>
					@endforeach
				@endif
		
			</div>
		</div>
	</section>
</main>
@include('front.footer')
