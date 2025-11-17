@include('front.header')
<main class="page-content">
<div class="container">
<div class="row">
  <div class="col-sm-12">
	<div class="photo-article">
    @if (isset($catalogueposts) && count($catalogueposts)>0)
      <div class="row">
        
        <div class="col-sm-3">
            @if (isset($catalogueposts[0]))
            @if (isset($catalogueposts[0]->categories[0]->category))
            <div class="mr-main1">
                <a href="{{ route('postdetail',[$catalogueposts[0]->categories[0]->category->slug,$catalogueposts[0]->slug]) }}">
                    @if (isset($catalogueposts[0]->image) && Storage::exists($catalogueposts[0]->image))
                    <img src="{{ URL::asset('storage/'.$catalogueposts[0]->image) }}" alt="{{ $catalogueposts[0]->title }}" class="img-fluid">
                    @else
                    <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $catalogueposts[0]->title }}" class="img-fluid">
                    @endif
                    <div class="overlay-content">
                        <h3>{{ $catalogueposts[0]->title }}</h3>
                    </div>
                </a>
            </div>
            @endif
            @endif
            @if (isset($catalogueposts[1]))
            @if (isset($catalogueposts[1]->categories[0]->category))
            <div class="mr-main1">
                <a href="{{ route('postdetail',[$catalogueposts[1]->categories[0]->category->slug,$catalogueposts[1]->slug]) }}">
                    @if (isset($catalogueposts[1]->image) && Storage::exists($catalogueposts[1]->image))
                    <img src="{{ URL::asset('storage/'.$catalogueposts[1]->image) }}" alt="{{ $catalogueposts[1]->title }}" class="img-fluid">
                    @else
                    <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $catalogueposts[1]->title }}" class="img-fluid">
                    @endif
                    <div class="overlay-content">
                        <h3>{{ $catalogueposts[1]->title }}</h3>
                    </div>
                </a>
            </div>
            @endif
            @endif
        </div>
        <div class="col-sm-3">
            @if (isset($catalogueposts[3]))
            @if (isset($catalogueposts[3]->categories[0]->category))
            <div class="mr-main1">
                <a href="{{ route('postdetail',[$catalogueposts[3]->categories[0]->category->slug,$catalogueposts[3]->slug]) }}">
                    @if (isset($catalogueposts[3]->image) && Storage::exists($catalogueposts[3]->image))
                    <img src="{{ URL::asset('storage/'.$catalogueposts[3]->image) }}" alt="{{ $catalogueposts[3]->title }}" class="img-fluid">
                    @else
                    <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $catalogueposts[3]->title }}" class="img-fluid">
                    @endif
                    <div class="overlay-content">
                        <h3>{{ $catalogueposts[3]->title }}</h3>
                    </div>
                </a>
            </div>
            @endif
            @endif
            @if (isset($catalogueposts[4]))
            @if (isset($catalogueposts[4]->categories[0]->category))
            <div class="mr-main1">
                <a href="{{ route('postdetail',[$catalogueposts[4]->categories[0]->category->slug,$catalogueposts[4]->slug]) }}">
                    @if (isset($catalogueposts[4]->image) && Storage::exists($catalogueposts[4]->image))
                    <img src="{{ URL::asset('storage/'.$catalogueposts[4]->image) }}" alt="{{ $catalogueposts[4]->title }}" class="img-fluid">
                    @else
                    <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $catalogueposts[4]->title }}" class="img-fluid">
                    @endif
                    <div class="overlay-content">
                        <h3>{{ $catalogueposts[4]->title }}</h3>
                    </div>
                </a>
            </div>
            @endif
            @endif
        </div>
        <div class="col-sm-6">
            @if (isset($catalogueposts[2]))
            @if (isset($catalogueposts[2]->categories[0]->category))
            <div class="mr-main">
                <a href="{{ route('postdetail',[$catalogueposts[2]->categories[0]->category->slug,$catalogueposts[2]->slug]) }}">
                    @if (isset($catalogueposts[2]->image) && Storage::exists($catalogueposts[2]->image))
                    <img src="{{ URL::asset('storage/'.$catalogueposts[2]->image) }}" alt="{{ $catalogueposts[2]->title }}" class="img-fluid">
                    @else
                    <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $catalogueposts[2]->title }}" class="img-fluid">
                    @endif
                    <div class="overlay-content">
                        <h3>{{ $catalogueposts[2]->title }}</h3>
                    </div>
                </a>
            </div>
            @endif
            @endif
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
<div class="row">
	<div class="col-sm-12 col-md-8 col-lg-12">
	    <div class="trending-articles">
      <div class="section-title">
        <h3 class="block_title"><span>Trending Articles</span></h3>
      </div>
      <div class="row">
          @if (isset($trendingposts) && count($trendingposts)>0)
          @foreach ($trendingposts->chunk(3) as $trending)
          <div class="col-sm-4">
            <ul>
                @foreach ($trending as $post)
                @if (isset($post->categories[0]->category))
                <li> <a href="{{ route('postdetail',[$post->categories[0]->category->slug,$post->slug]) }}">{{ $post->title }}</a> </li>
                @endif
                @endforeach
            </ul>
          </div>
          @endforeach
          @endif
      </div>
    </div>
    <div class="google-ad text-center">
        
        <div id="Ad1" class="carousel slide" data-ride="carousel"  data-interval="{{ $adsetting->homepageupperbanner728x90time*1000 }}">
            <ol class="carousel-indicators">
                @if (isset($upperbanner728x90) && count($upperbanner728x90)>0)
                @foreach ($upperbanner728x90 as $upperbanner)
                @if($upperbanner->type=='custom')
                <li data-target="target_blank" data-slide-to="{{ $loop->index }}" @if ($loop->index=='0')
                    class="active"
                @endif></li>
                @endif
                @endforeach
                @endif
            </ol>

            <div class="carousel-inner">
            @if (isset($upperbanner728x90) && count($upperbanner728x90)>0)
                @foreach ($upperbanner728x90 as $upperbanner)
                    @if($upperbanner->type=='custom')
                        @if ($loop->index=='0')
                            <div class="carousel-item active">
                        @else
                            <div class="carousel-item">
                        @endif
                        @if (isset($upperbanner->image) && Storage::exists($upperbanner->image))
                            <a href="{{ $upperbanner->link }}" target="_blank"><img src="{{ URL::asset('storage/'.$upperbanner->image) }}" class="img-fluid"></a>
                        @else
                            <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
                        @endif
                        </div>
                    @else
                    {!! $upperbanner->code !!}
                    @endif
                @endforeach
            @else
                <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
            @endif
            </div>
        </div>
        <div class="carousel-inner">
            @if (isset($uppersidebar300x600) && count($uppersidebar300x600)>0)
                @foreach ($uppersidebar300x600 as $lowersidebar)
                    @if($lowersidebar->type=='custom')
                        @if ($loop->index=='0')
                            <div class="carousel-item active">
                        @else
                            <div class="carousel-item">
                        @endif
                        @if (isset($lowersidebar->image) && Storage::exists($lowersidebar->image))
                            <a href="{{ $lowersidebar->link }}" target="_blank"><img src="{{ URL::asset('storage/'.$lowersidebar->image) }}" class="img-fluid"></a>
                        @else
                            <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
                        @endif
                        </div>
                    @else
                    {!! $lowersidebar->code !!}
                    @endif
                @endforeach
            @else
                <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
            @endif
            </div>
            </div>
            
            <div class="carousel-inner">
            @if (isset($uppersidebar300x250) && count($uppersidebar300x250)>0)
                @foreach ($uppersidebar300x250 as $lowersidebar)
                    @if($lowersidebar->type=='custom')
                        @if ($loop->index=='0')
                            <div class="carousel-item active">
                        @else
                            <div class="carousel-item">
                        @endif
                        @if (isset($lowersidebar->image) && Storage::exists($lowersidebar->image))
                            <a href="{{ $lowersidebar->link }}" target="_blank"><img src="{{ URL::asset('storage/'.$lowersidebar->image) }}" class="img-fluid"></a>
                        @else
                            <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
                        @endif
                        </div>
                    @else
                    {!! $lowersidebar->code !!}
                    @endif
                @endforeach
            @else
                <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
            @endif
            </div>
            
            
        </div>
        
    </div>
	    <div class="recent-post-bycat">
    <div class="row" style="">
        @if (isset($center1posts) && count($center1posts)>0)
        @if (isset($center1category))
        <div class="col-sm-4 " style="padding:0px;">
            <div class="main-postbycat">
                <a href="{{ route('postdetail',[$center1category->slug,$center1posts[0]->slug]) }}">
                    <div class="postbycat-img">
                        @if (isset($center1posts[0]->image) && Storage::exists($center1posts[0]->image))
                        <img src="{{ URL::asset('storage/'.$center1posts[0]->image) }}" alt="{{ $center1posts[0]->title }}" class="img-fluid">
                        @else
                        <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $center1posts[0]->title }}" class="img-fluid">
                        @endif
                        <span>{{ $center1category->name }}</span>
                    </div>
                    <h3>{{ $center1posts[0]->title }}</h3>
                </a>
                <ul class="bullet-list">
                    @foreach ($center1posts as $center1post)
                    <li><a href="{{ route('postdetail',[$center1category->slug,$center1post->slug]) }}">{{ $center1post->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        @endif

        @if (isset($center2posts) && count($center2posts)>0)
        @if(isset($center2category->slug))
        <div class="col-sm-4 " style="padding:0px;">
            <div class="main-postbycat">
                <a href="{{ route('postdetail',[$center2category->slug,$center2posts[0]->slug]) }}">
                    <div class="postbycat-img">
                        @if (isset($center2posts[0]->image) && Storage::exists($center2posts[0]->image))
                        <img src="{{ URL::asset('storage/'.$center2posts[0]->image) }}" alt="{{ $center2posts[0]->title }}" class="img-fluid">
                        @else
                        <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $center2posts[0]->title }}" class="img-fluid">
                        @endif
                        <span>{{ $center2category->name }}</span>
                    </div>
                    <h3>{{ $center2posts[0]->title }}</h3>
                </a>
                <ul class="bullet-list">
                    @foreach ($center2posts as $center2post)
                    <li><a href="{{ route('postdetail',[$center2category->slug,$center2post->slug]) }}">{{ $center2post->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        @endif

        @if (isset($center3posts) && count($center3posts)>0)
        @if(isset($center3category->slug))
             <div class="col-sm-4 " style="padding:0px;">
            <div class="main-postbycat">
                <a href="{{ route('postdetail',[$center3category->slug,$center3posts[0]->slug]) }}">
                    <div class="postbycat-img">
                        @if (isset($center3posts[0]->image) && Storage::exists($center3posts[0]->image))
                        <img src="{{ URL::asset('storage/'.$center3posts[0]->image) }}" alt="{{ $center3posts[0]->title }}" class="img-fluid">
                        @else
                        <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $center3posts[0]->title }}" class="img-fluid">
                        @endif
                        <span>{{ $center3category->name }}</span>
                    </div>
                    <h3>{{ $center3posts[0]->title }}</h3>
                </a>
                <ul class="bullet-list">
                    @foreach ($center3posts as $center3post)
                    <li><a href="{{ route('postdetail',[$center3category->slug,$center3post->slug]) }}">{{ $center3post->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        @endif
      </div>
    </div>
	<div class="new-eightbyfour">
      <div class="row">
        <div class="col-sm-12">
			<div class="news-block">
				<div class="row">
				    @if(isset($uppertab1category) && !empty($uppertab1category))
					<div class="col-sm-6">
						<div class="block-main">
							<h3><a href="{{ route('postbycategory',$uppertab1category->slug) }}">{{ $uppertab1category->name }}<i class="fas fa-chevron-right"></i></a></h3>
							<ul>
                                @if (isset($uppertab1posts) && count($uppertab1posts)>0)
                                @foreach ($uppertab1posts as $uppertab1post)
								<li class="border-bottom"><a href="{{ route('postdetail',[$uppertab1category->slug,$uppertab1post->slug]) }}">{{ $uppertab1post->title }}</a></li>
                                @endforeach
                                @endif
							</ul>
						</div>
					</div>
					@endif
					@if(isset($uppertab2category) && !empty($uppertab2category))
					<div class="col-sm-6">
						<div class="block-main">
							<h3><a href="{{ route('postbycategory',$uppertab2category->slug) }}">{{ $uppertab2category->name }}<i class="fas fa-chevron-right"></i></a></h3>
							<ul>
								@if (isset($uppertab2posts) && count($uppertab2posts)>0)
                                @foreach ($uppertab2posts as $uppertab2post)
								<li class="border-bottom"><a href="{{ route('postdetail',[$uppertab2category->slug,$uppertab2post->slug]) }}">{{ $uppertab2post->title }}</a></li>
                                @endforeach
                                @endif
							</ul>
						</div>
					</div>
					@endif
					@if(isset($uppertab3category) && !empty($uppertab3category))
					<div class="col-sm-6">
						<div class="block-main">
							<h3><a href="{{ route('postbycategory',$uppertab3category->slug) }}">{{ $uppertab3category->name }}<i class="fas fa-chevron-right"></i></a></h3>
							<ul>
								@if (isset($uppertab3posts) && count($uppertab3posts)>0)
                                @foreach ($uppertab3posts as $uppertab3post)
								<li class="border-bottom"><a href="{{ route('postdetail',[$uppertab3category->slug,$uppertab3post->slug]) }}">{{ $uppertab3post->title }}</a></li>
                                @endforeach
                                @endif
							</ul>
						</div>
					</div>
					@endif
					@if(isset($uppertab4category) && !empty($uppertab3category))
					<div class="col-sm-6">
						<div class="block-main">
							<h3><a href="{{ route('postbycategory',$uppertab4category->slug) }}">{{ $uppertab4category->name }}<i class="fas fa-chevron-right"></i></a></h3>
							<ul>
								@if (isset($uppertab4posts) && count($uppertab4posts)>0)
                                @foreach ($uppertab4posts as $uppertab4post)
								<li class="border-bottom"><a href="{{ route('postdetail',[$uppertab4category->slug,$uppertab4post->slug]) }}">{{ $uppertab4post->title }}</a></li>
                                @endforeach
                                @endif
							</ul>
						</div>
					</div>
					@endif
				</div>
			</div>
        </div>
      </div>
    </div>
    <div class="google-ad text-center">
        
        <div id="Ad2" class="carousel slide" data-ride="carousel"  data-interval="{{ $adsetting->homepagemiddlebanner728x90time*1000 }}" style="width: 50%;float: left;">
            <ol class="carousel-indicators">
                @if (isset($middlebanner728x90) && count($middlebanner728x90)>0)
                    @foreach ($middlebanner728x90 as $middlebanner)
                        @if($middlebanner->type=='custom')
                            <li data-target="#Ad2" data-slide-to="{{ $loop->index }}" @if ($loop->index=='0')
                            class="active"
                            @endif></li>      
                        @endif
                    @endforeach
                @endif
            </ol>
            <div class="carousel-inner">
                @if (isset($middlebanner728x90) && count($middlebanner728x90)>0)
                    @foreach ($middlebanner728x90 as $middlebanner)
                        @if($middlebanner->type=='custom')
                            @if ($loop->index=='0')
                                <div class="carousel-item active">
                            @else
                                <div class="carousel-item">
                            @endif
                            @if (isset($middlebanner->image) && Storage::exists($middlebanner->image))
                                <a href="{{ $middlebanner->link }}" target="_blank"><img src="{{ URL::asset('storage/'.$middlebanner->image) }}" class="img-fluid"></a>
                            @else
                                <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
                            @endif
                            </div>
                        @else
                        {!! $middlebanner->code !!}
                        @endif
                    @endforeach
                @else
                    <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
                @endif
            </div>
        </div>
        <div class="carousel-inner" style="width: 50%;">
            @if (isset($middlesidebar300x600) && count($middlesidebar300x600)>0)
                @foreach ($middlesidebar300x600 as $lowersidebar)
                    @if($lowersidebar->type=='custom')
                        @if ($loop->index=='0')
                            <div class="carousel-item active">
                        @else
                            <div class="carousel-item">
                        @endif
                        @if (isset($lowersidebar->image) && Storage::exists($lowersidebar->image))
                            <a href="{{ $lowersidebar->link }}" target="_blank"><img src="{{ URL::asset('storage/'.$lowersidebar->image) }}" class="img-fluid" style="width: 250px;"></a>
                        @else
                            <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
                        @endif
                        </div>
                    @else
                    {!! $lowersidebar->code !!}
                    @endif
                @endforeach
            @else
                <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
            @endif
            </div>
            </div>
            <div class="carousel-inner">
            @if (isset($middlesidebar300x250) && count($middlesidebar300x250)>0)
                @foreach ($middlesidebar300x250 as $lowersidebar)
                    @if($lowersidebar->type=='custom')
                        @if ($loop->index=='0')
                            <div class="carousel-item active">
                        @else
                            <div class="carousel-item">
                        @endif
                        @if (isset($lowersidebar->image) && Storage::exists($lowersidebar->image))
                            <a href="{{ $lowersidebar->link }}" target="_blank"><img src="{{ URL::asset('storage/'.$lowersidebar->image) }}" class="img-fluid"></a>
                        @else
                            <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
                        @endif
                        </div>
                    @else
                    {!! $lowersidebar->code !!}
                    @endif
                @endforeach
            @else
                <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
            @endif
            </div>
            </div>
        
    </div>
    <div class="slider-section">
      <div id="slider" class="carousel slide" data-ride="carousel">

        <!-- Indicators -->
        <ul class="carousel-indicators">
        @if (isset($sliderposts) && count($sliderposts)>0)
        @foreach ($sliderposts as $sliderpost)
          <li data-target="#slider" data-slide-to="{{ $loop->index }}" @if ($loop->index=='0')
            class="active"
          @endif></li>
        @endforeach
        @endif
        </ul>

        <!-- The slideshow -->
        <div class="carousel-inner">
            @if (isset($sliderposts) && count($sliderposts)>0)
            @foreach ($sliderposts as $sliderpost)
            @if ($loop->index=='0')
            <div class="carousel-item active">
            @else
            <div class="carousel-item">
            @endif
                @if (isset($sliderpost->image) && Storage::exists($sliderpost->image))
                <img src="{{ URL::asset('storage/'.$sliderpost->image) }}" alt="{{ $sliderpost->title }}" class="img-fluid">
                @else
                <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $sliderpost->title }}" class="img-fluid">
                @endif
                <div class="carousel-caption">
                    <h3>
                        @if(isset($sliderpost->categories) && count($sliderpost->categories)>0)
                        @if(isset($sliderpost->categories->first()->category) && !empty($sliderpost->categories->first()->category))
                        <a href="{{ route('postdetail',[$sliderpost->categories->first()->category->slug,$sliderpost->slug]) }}">
                            {{ $sliderpost->title }}
                        </a>
                        @endif
                        @else
                            {{ $sliderpost->title }}
                        @endif
                    </h3>
                </div>
            </div>
            @endforeach
            @endif
        </div>
        <!-- Left and right controls -->
        <a class="carousel-control-prev" href="#slider" data-slide="prev"> <span class="carousel-control-prev-icon"></span> </a> <a class="carousel-control-next" href="#slider" data-slide="next"> <span class="carousel-control-next-icon"></span> </a> </div>
    </div>
    <div class="google-ad text-center">
        
            <div id="Ad3" class="carousel slide" data-ride="carousel"  data-interval="{{ $adsetting->homepagelowerbanner728x90time*1000 }}">
            <ol class="carousel-indicators">
                @if (isset($lowerbanner728x90) && count($lowerbanner728x90)>0)
                @foreach ($lowerbanner728x90 as $lowerbanner)
                @if($lowerbanner->type=='custom')
                <li data-target="#Ad3" data-slide-to="{{ $loop->index }}" @if ($loop->index=='0')
                    class="active"
                @endif></li>      
                @endif
                @endforeach
                @endif
            </ol>
            <div class="carousel-inner">
                @if (isset($lowerbanner728x90) && count($lowerbanner728x90)>0)
                    @foreach ($lowerbanner728x90 as $lowerbanner)
                        @if($lowerbanner->type=='custom')
                            @if ($loop->index=='0')
                                <div class="carousel-item active">
                            @else
                                <div class="carousel-item">
                            @endif
                            @if (isset($lowerbanner->image) && Storage::exists($lowerbanner->image))
                                <a href="{{ $lowerbanner->link }}" target="_blank"><img src="{{ URL::asset('storage/'.$lowerbanner->image) }}" class="img-fluid"></a>
                            @else
                                <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
                            @endif
                            </div>
                        @else
                            {!! $lowerbanner->code !!}
                        @endif
                    @endforeach
                @else
                    <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
                @endif
            </div>
        </div>
        <div class="carousel-inner">
            @if (isset($lowersidebar300x600) && count($lowersidebar300x600)>0)
                @foreach ($lowersidebar300x600 as $lowersidebar)
                    @if($lowersidebar->type=='custom')
                        @if ($loop->index=='0')
                            <div class="carousel-item active">
                        @else
                            <div class="carousel-item">
                        @endif
                        @if (isset($lowersidebar->image) && Storage::exists($lowersidebar->image))
                            <a href="{{ $lowersidebar->link }}" target="_blank"><img src="{{ URL::asset('storage/'.$lowersidebar->image) }}" class="img-fluid"></a>
                        @else
                            <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
                        @endif
                        </div>
                    @else
                    {!! $lowersidebar->code !!}
                    @endif
                @endforeach
            @else
                <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
            @endif
            </div>
            </div>
            <div class="carousel-inner">
            @if (isset($lowersidebar300x250) && count($lowersidebar300x250)>0)
                @foreach ($lowersidebar300x250 as $lowersidebar)
                    @if($lowersidebar->type=='custom')
                        @if ($loop->index=='0')
                            <div class="carousel-item active">
                        @else
                            <div class="carousel-item">
                        @endif
                        @if (isset($lowersidebar->image) && Storage::exists($lowersidebar->image))
                            <a href="{{ $lowersidebar->link }}" target="_blank"><img src="{{ URL::asset('storage/'.$lowersidebar->image) }}" class="img-fluid"></a>
                        @else
                            <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
                        @endif
                        </div>
                    @else
                    {!! $lowersidebar->code !!}
                    @endif
                @endforeach
            @else
                <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
            @endif
            </div>
            </div>
        
    </div>
    

	<div class="video-article" >
      <div class="section-title">
        <h3 class="block_title"><span>Videos</span></h3>
      </div>
      <ul class="nav nav-tabs" id="myTab" role="tablist">
          @if (isset($videocategories) && count($videocategories)>0)
          @foreach ($videocategories as $videocategory)
          <li class="nav-item">
              <a @if ($loop->index=='0')
                class="nav-link active"
                @else
                class="nav-link"
              @endif id="{{ $videocategory->slug }}-tab" data-toggle="tab" href="#{{ $videocategory->slug }}" role="tab" aria-controls="{{ $videocategory->slug }}" aria-selected="false">{{ $videocategory->name }}</a>
            </li>
          @endforeach
          @endif
      </ul>
      <div class="tab-content" id="myTabContent">
        @if (isset($videocategories) && count($videocategories)>0)
        @foreach ($videocategories as $videocategory)
        <div
        @if ($loop->index=='0')
        class="tab-pane fade show active"
        @else
        class="tab-pane fade"
        @endif
            id="{{ $videocategory->slug }}" role="tabpanel" aria-labelledby="{{ $videocategory->slug }}-tab">
          <div class="row">
              @if (isset($videocategory->videoposts) && count($videocategory->videoposts)>0)
              @foreach ($videocategory->videoposts as $video)
              <div class="col-sm-4">
                  <div class="video-tabs-m">
                      <a href="{{ route('postdetail',[$videocategory->slug,$video->slug]) }}" title="{{ $video->title }}">
                        @if (isset($video->image) && Storage::exists($video->image))
                        <img src="{{ URL::asset('storage/'.$video->image) }}" alt="{{ $video->title }}" class="img-fluid">
                        @else
                        <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $video->title }}" class="img-fluid">
                        @endif
                        <p>{{ $video->title }}</p>
                      </a>
                  </div>
              </div>
              @endforeach
              @endif
          </div>
        </div>
        @endforeach
        @endif
      </div>
      <div class="more-videos"><a href="{{ route('postbycategory','videos') }}">More Videos <i class="fas fa-chevron-right"></i></a> </div>
    </div>

    <div class="most-read">
      <div class="section-title">
        <h3 class="block_title"><span>{{ $mustreadcategory->name ?? '' }}</span></h3>
      </div>
    <div class="row">
        @if (isset($mustreadposts) && count($mustreadposts)>0)
        @foreach ($mustreadposts as $mustreadpost)
        @if(isset($mustreadcategory))
        <div class="col-sm-3">
            <div class="mr-main" style=" padding:10px;
      border-radius:7px;
       box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; margin-bottom:20px;">
                <a href="{{ route('postdetail',[$mustreadcategory->slug,$mustreadpost->slug]) }}">
                    @if (isset($mustreadpost->image) && Storage::exists($mustreadpost->image))
                    <img src="{{ URL::asset('storage/'.$mustreadpost->image) }}" alt="{{ $mustreadpost->title }}" class="img-fluid">
                    @else
                    <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $mustreadpost->title }}" class="img-fluid">
                    @endif
                   <h3 id="postTitle" style="margin-top:10px; height:70px;">{{ $mustreadpost->title }}</h3>
                </a>
            </div>
        </div>
        @endif
        @endforeach
        @endif
    </div>
    </div>

    <div class="health-article">
      <div class="section-title">
        <h3 class="block_title"><span>{{ $otherwidgetcategory->name }}</span></h3>
      </div>
    <div class="row">
        @if (isset($otherwidgetposts) && count($otherwidgetposts)>0)
        @foreach ($otherwidgetposts as $otherwidgetpost)
        @if(isset($otherwidgetcategory))
        <div class="col-sm-3">
            <div class="mr-main" style=" padding:10px;
      border-radius:7px;
       box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;margin-bottom:20px;">
                <a href="{{ route('postdetail',[$otherwidgetcategory->slug,$otherwidgetpost->slug]) }}">
                    @if (isset($otherwidgetpost->image) && Storage::exists($otherwidgetpost->image))
                    <img src="{{ URL::asset('storage/'.$otherwidgetpost->image) }}" alt="{{ $otherwidgetpost->title }}" class="img-fluid">
                    @else
                    <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $otherwidgetpost->title }}" class="img-fluid">
                    @endif
                    <h3 style="margin-top:20px; height:100px;">{{ $otherwidgetpost->title }}</h3>
                </a>
            </div>
        </div>
        @endif
        @endforeach
        @endif
    </div>
    </div>
    
    <!---lower Category Tab --->
     <div class="recent-post-bycat">
    <div class="row">
        
        @if (isset($lower1posts) && count($lower1posts)>0)
        @if (isset($lower1category))
        <div class="col-sm-4">
            <div class="main-postbycat">
                <a href="{{ route('postdetail',[$lower1category->slug,$lower1posts[0]->slug]) }}">
                    <div class="postbycat-img">
                        @if (isset($lower1posts[0]->image) && Storage::exists($lower1posts[0]->image))
                        <img src="{{ URL::asset('storage/'.$lower1posts[0]->image) }}" alt="{{ $lower1posts[0]->title }}" class="img-fluid">
                        @else
                        <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $lower1posts[0]->title }}" class="img-fluid">
                        @endif
                        <span>{{ $lower1category->name }}</span>
                    </div>
                    <h3 style="margin-top:20px; height:100px;">{{ $lower1posts[0]->title }}</h3>
                </a>
                <ul class="bullet-list">
                    @foreach ($lower1posts as $lower1post)
                    <li><a href="{{ route('postdetail',[$lower1category->slug,$lower1post->slug]) }}">{{ $lower1post->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        @endif

        @if (isset($lower2posts) && count($lower2posts)>0)
        @if(isset($lower2category->slug))
        <div class="col-sm-4">
            <div class="main-postbycat">
                <a href="{{ route('postdetail',[$lower2category->slug,$lower2posts[0]->slug]) }}">
                    <div class="postbycat-img">
                        @if (isset($lower2posts[0]->image) && Storage::exists($lower2posts[0]->image))
                        <img src="{{ URL::asset('storage/'.$lower2posts[0]->image) }}" alt="{{ $lower2posts[0]->title }}" class="img-fluid">
                        @else
                        <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $lower2posts[0]->title }}" class="img-fluid">
                        @endif
                        <span>{{ $lower2category->name }}</span>
                    </div>
                    <h3 style="margin-top:20px; height:100px;">{{ $lower2posts[0]->title }}</h3>
                </a>
                <ul class="bullet-list">
                    @foreach ($lower2posts as $lower2post)
                    <li><a href="{{ route('postdetail',[$lower2category->slug,$lower2post->slug]) }}">{{ $lower2post->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        @endif

        @if (isset($lower3posts) && count($lower3posts)>0)
        @if(isset($lower3category->slug))
        <div class="col-sm-4">
            <div class="main-postbycat">
                <a href="{{ route('postdetail',[$lower3category->slug,$lower3posts[0]->slug]) }}">
                    <div class="postbycat-img">
                        @if (isset($lower3posts[0]->image) && Storage::exists($lower3posts[0]->image))
                        <img src="{{ URL::asset('storage/'.$lower3posts[0]->image) }}" alt="{{ $lower3posts[0]->title }}" class="img-fluid">
                        @else
                        <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $lower3posts[0]->title }}" class="img-fluid">
                        @endif
                        <span>{{ $lower3category->name }}</span>
                    </div>
                    <h3 style="margin-top:20px; height:100px;">{{ $lower3posts[0]->title }}</h3>
                </a>
                <ul class="bullet-list">
                    @foreach ($lower3posts as $lower3post)
                    <li><a href="{{ route('postdetail',[$lower3category->slug,$lower3post->slug]) }}">{{ $lower3post->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        @endif
      </div>
    </div>
    
    
    
    <div class="google-ad text-center">
        
        <div id="Ad8" class="carousel slide" data-ride="carousel"  data-interval="{{ $adsetting->homepagelowestbanner728x90time*1000 }}">
            <ol class="carousel-indicators">
                @if (isset($lowestbanner728x90) && count($lowestbanner728x90)>0)
                @foreach ($lowestbanner728x90 as $lowestbanner)
                @if($lowestbanner->type=='custom')
                <li data-target="#Ad8" data-slide-to="{{ $loop->index }}" @if ($loop->index=='0')
                    class="active"
                @endif></li>      
                @endif
                @endforeach
                @endif
            </ol>
            <div class="carousel-inner">
                @if (isset($lowestbanner728x90) && count($lowestbanner728x90)>0)
                    @foreach ($lowestbanner728x90 as $lowestbanner)
                        @if($lowestbanner->type=='custom')
                            @if ($loop->index=='0')
                                <div class="carousel-item active">
                            @else
                                <div class="carousel-item">
                            @endif
                            @if (isset($lowestbanner->image) && Storage::exists($lowestbanner->image))
                                <a href="https://{{ $lowerbanner->link }}" target="_blank"><img src="{{ URL::asset('storage/'.$lowestbanner->image) }}" class="img-fluid"></a>
                            @else
                                <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
                            @endif
                            </div>
                        @else
                            {!! $lowestbanner->code !!}
                        @endif
                    @endforeach
                @else
                    <img src="{{ URL::asset('front/images/728x90.jpg') }}" class="img-fluid">
                @endif
            </div>
        </div>
    </div>
	</div>
  @include('front.sidebar')
</div>
<div class="row">
	<div class="col-sm-12">
	<div class="youmay-article">
      <div class="section-title">
          <h3 class="block_title"><span>You May Like</span></h3>
      </div>
    <div class="row">
        @if (isset($youmaylikeposts) && count($youmaylikeposts)>0)
        @foreach ($youmaylikeposts as $youmaylikepost)
        <div class="col-sm-3">
            <div class="mr-main">
                <a href="{{ route('postdetail',[$youmaylikecategory->slug,$youmaylikepost->slug]) }}">
                    @if (isset($youmaylikepost->image) && Storage::exists($youmaylikepost->image))
                    <img src="{{ URL::asset('storage/'.$youmaylikepost->image) }}" alt="{{ $youmaylikepost->title }}" class="img-fluid">
                    @else
                    <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $youmaylikepost->title }}" class="img-fluid">
                    @endif
                    <h3>{{ $youmaylikepost->title }}</h3>
                </a>
            </div>
        </div>
        @endforeach
        @endif
      </div>
    </div>
	</div>
</div>
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
		$(document).on('click','.add-poll-btn',function(event){
			$('#option-err').html('');
			$.ajax({
				url:"{{ URL::to('submit-poll') }}",
				type:'POST',
				dataType:'json',
				data:$('#poll-form').serialize(),
				success:function(result){
					if(result.msgCode === '200') {
						toastr.success(result.msgText);
						window.location = "{{ URL::to('/') }}";
					} else if (result.msgCode === '401') {
						if(result.errors.option) {
							$('#option-err').html(result.errors.option[0]);
						}
					} else {
						toastr.error('error encountered '+result.msgText);
					}
					$("#loader").modal('hide');
				},
				error:function(error){
					toastr.error('error encountered '+error.statusText);
					$("#loader").modal('hide');
				}
			});
		});
	});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const postTitleElement = document.getElementById('postTitle');
        const postTitleText = postTitleElement.textContent.trim();
        const wordLimit = 5;

        // Split the title into an array of words
        const words = postTitleText.split(' ');

        // Check if there are more than the word limit
        if (words.length > wordLimit) {
            // Join only the first 10 words and add an ellipsis
            const truncatedText = words.slice(0, wordLimit).join(' ') + '...';
            postTitleElement.textContent = truncatedText;
        }
    });
</script>