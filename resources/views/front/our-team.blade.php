@include( 'front.header' )
<style type="text/css">
    .t-head {
    border-bottom: 2px solid #333;
    color: #333;
    padding: 7px;
    display: inline-block;
    margin: 0;
    font-size: 1.2em;
}
</style>
	<section class="page-content">
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
										<li class="breadcrumb-item active" aria-current="page">Our Team</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<section class="team-section">
	<div class="container">
	    <div class="row">
	        <div class="col-sm-12 text-center">
	            <h3 class="team-h">Our Team</h3>
	        </div>
	    </div>
		
			@if(isset($categories) && count($categories)>0)
		  	@foreach($categories as $category)
		  	<div class="row justify-content-center">
		  	<div class="col-sm-12 mb-4 text-center">
		  	    <h4 class="t-head">{{ $category->name }}</h4>
		  	</div>
				
		  	@if(isset($category->teams) && count($category->teams)>0)
		  	@foreach($category->teams as $team)
			<div class="col-sm-3 mb-4">
				<div class="team-main">
					<div class="team-img">
						@if(isset($team->image) && Storage::exists($team->image))
		  				<img src="{{ URL::asset('storage/'.$team->image) }}" alt="{{ $team->name }}" class="img-fluid">
		  				@endif
					</div>
					<div class="team-contnt">
						<h3>{{ $team->name }}</h3>
						<h4>{{ $team->designation }}</h4>
						<h4>{{ $team->city->name }} ({{ $team->state->name }})</h4>
					</div>
				</div>
			</div>
			@endforeach
		  	@endif
		</div>
			@endforeach
		  	@endif
	</div>
</section>
@include( 'front.footer' )