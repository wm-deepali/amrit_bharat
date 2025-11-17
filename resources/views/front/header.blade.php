<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
 <!--<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6944267502175245"
     crossorigin="anonymous"></script> -->
     
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!--Whats app SEO-->
    @if (isset($post))
    
    <title>{{ $post->metatitle }}</title>
    <meta name="description" content="{{ $post->metadescription }}">
    <meta name="keywords" content="{{ $post->metakeyword }}">
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>
    <meta property="og:type" content="article" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:site_name" content="amritbharat.com" />
    <meta property="og:title" content="{{ $post->title }} - Amrit Bharat"/>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <meta property="og:description" content="{{ Str::words($post->content, $words = 50, $end = '...') }}" />
    @if(isset($post->image) && Storage::exists($post->image))
        <meta property="og:image" content="{{ URL::asset('storage/'.$post->image) }}"/> 
    @else
        <meta property="og:image" content="{{ URL::asset('front/images/logo.png') }}"/> 
    @endif
        <meta property="og:image:width" content="600"/>
        <meta property="og:image:height" content="600"/>
    @elseif (isset($keyword))
        @if(isset($tag))
            <title>{{ $tag->metatitle }}</title>
            <meta name="description" content="{{ $tag->metadescription }}">
            <meta name="keywords" content="{{ $tag->metakeyword }}">
        @else
            <title>{{ $keyword }}</title>
            <meta name="description" content="Amrit Bharat">
            <meta name="keywords" content="Amrit Bharat">
        @endif
    @elseif (isset($subcategory))
    <title>{{ $subcategory->metatitle }}</title>
    <meta name="description" content="{{ $subcategory->metadescription }}">
    <meta name="keywords" content="{{ $subcategory->metakeyword }}">
    @elseif (isset($category))
    <title>{{ $category->metatitle }}</title>
    <meta name="description" content="{{ $category->metadescription }}">
    <meta name="keywords" content="{{ $category->metakeyword }}">
    @else
    <title>Amrit Bharat | Daily Hindi News | India News Portal</title>
    @if(isset($uppertab1category) && isset($uppertab2category) && isset($uppertab3category) && isset($uppertab4category) && isset($otherwidgetcategory) && isset($mustreadcategory) && isset($youmaylikecategory) && isset($sidebartab1category) && isset($sidebartab2category) && isset($sidebartab3category) && isset($center1category) && isset($center2category) && isset($center3category) && isset($lower1category) && isset($lower2category) && isset($lower3category))
    <meta name="description" content="{{ $uppertab1category->metadescription }},{{ $uppertab2category->metadescription }},{{ $uppertab3category->metadescription }},{{ $uppertab4category->metadescription }},{{ $otherwidgetcategory->metadescription }},{{ $mustreadcategory->metadescription }},{{ $youmaylikecategory->metadescription }},{{ $sidebartab1category->metadescription }},{{ $sidebartab2category->metadescription }},{{ $sidebartab3category->metadescription }},{{ $center1category->metadescription }},{{ $center2category->metadescription }},{{ $center3category->metadescription }},{{ $lower1category->metadescription }},{{ $lower2category->metadescription }},{{ $lower3category->metadescription }}">
    <meta name="keywords" content="{{ $uppertab1category->metakeyword }},{{ $uppertab2category->metakeyword }},{{ $uppertab3category->metakeyword }},{{ $uppertab4category->metakeyword }},{{ $otherwidgetcategory->metakeyword }},{{ $mustreadcategory->metakeyword }},{{ $youmaylikecategory->metakeyword }},{{ $sidebartab1category->metakeyword }},{{ $sidebartab2category->metakeyword }},{{ $sidebartab3category->metakeyword }},{{ $center1category->metakeyword }},{{ $center2category->metakeyword }},{{ $center3category->metakeyword }},{{ $lower1category->metakeyword }},{{ $lower2category->metakeyword }},{{ $lower3category->metakeyword }}">
    @else
    <meta name="description" content="Amrit Bharat">
    <meta name="keywords" content="Amrit Bharat">
    @endif
    @endif
    
	<link rel="stylesheet" href="{{ URL::asset('front/css/library/bootstrap-v4/bootstrap.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
	<link rel="stylesheet" href="{{ URL::asset('front/css/style.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('front/css/responsive.css') }}">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
<!--	<script data-ad-client="ca-pub-8691037606832105" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> -->
	
<!--<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6944267502175245"
     crossorigin="anonymous"></script>

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6944267502175245"
     crossorigin="anonymous"></script> -->
     
     <!--<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6944267502175245"
     crossorigin="anonymous"></script>
      -->
    <style>
        .navbar-nav .nav-link {
            white-space: nowrap;
        }
        #examtime-container {
            overflow-x: scroll;
        }
        #examtime-container::-webkit-scrollbar {
           display:none;
        }
        #examtime-container::-webkit-scrollbar-thumb {
            background: lightgrey;
            border-radius: 10px;
        }
        #examtime-container::-webkit-scrollbar-thumb:hover {
            background: #f45322;
            transform: translateX(0.5s ease);
        }
        #middle-header {
            background: #fff !important;
            border-bottom: 1px solid #9ADE7B;
        }
        #slideBack {
            position: absolute;
            top: 10px;
            left: 0px;
            border: none;
                border-radius: 50%;
        }
        #slidePrev {
            position: absolute;
            top: 10px;
            right: 0px;
            border: none;
                border-radius: 50%;
        }
        button:focus {
            outline: none;
        }
        @media (max-width: 992px) {
            #examtime-container {
                overflow-x: hidden;
            }
            #slideBack,
            #slidePrev {
                display: none;
            }
            
        }
        .topline{
            width:100%;
            height:2px ;
            background-color:#e63401;
        }
       /*.extra-highlights ul li{*/
       /*   border-right: 1px solid;*/
          
       /*}*/
       .extra-highlights ul li:last-child {
    border-right: none;
}
.extra-highlights ul li {
    position: relative; /* Required for positioning the pseudo-element */
}

.extra-highlights ul li::after {
    content: "";
    position: absolute;
    top: 50%;
    right: 0;
    transform: translateY(-50%); /* Centers the border vertically */
    width: 1px;
    height: 50%; /* Set the fixed height here */
    background-color: black; /* You can change the color of the border here */
}

.extra-highlights ul li:last-child::after {
    display: none; /* Remove the border for the last child */
}

    </style>
      
      
      
     
<!--<ins class="adsbygoogle"
     style="display:block; text-align:center;"
     data-ad-layout="in-article"
     data-ad-format="fluid"
     data-ad-client="ca-pub-6944267502175245"
     data-ad-slot="7218708602"></ins> -->
<!--<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script> -->

     <meta name="google-site-verification" content="c0ESkODl8wRCDAT2DrgEMIKE3L1-trX6HFie1ymuwWA" />
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-173556815-1');
</script>
</head>

<body>
	<header>
	    <div class="topline">
	        
	    </div>
	<div class="top-bar">
			<div class="container">
				<div class="row">
					<div class="col-sm-4">
						<img src="https://www.amritbharat.com/newlogo.png" alt="Amrit Bharat Logo"  style="width:340px;">
					</div>
					<div class="col-sm-5" style="display:flex;align-item:cente;">
						<!--<div class="social-head">
							<ul>
								<li><a href="{{ $headersetting->facebook ?? '#' }}"><i class="fab fa-facebook-f"></i></a></li>
								<li><a href="{{ $headersetting->twitter ?? '#' }}"><i class="fab fa-twitter"></i></a></li>
								<li><a href="{{ $headersetting->youtube ?? '#' }}"><i class="fab fa-youtube"></i></a></li>
								<li><a href="{{ $headersetting->instagram ?? '#' }}"><i class="fab fa-instagram"></i></a></li>
							</ul>
						</div>-->
						<div class=" " style="height:100%; display:flex; align-item:center;justify-content:space-between;">
						<a href="{{ route('login') }}" class="btn "style="height:40px; margin-top:15px;margin-right:30px;background-color:#e63401; color:#fff">Reporter Login</a>
						</div>
						<div class="search-head" style="height:40px; margin-top:15px;">
					        <form action="{{ route('search') }}" method="GET">
								<div class="input-group">
									<input type="text" class="form-control" placeholder="Search here" aria-label="search" aria-describedby="basic-addon2" name="keyword" style="height:40px;">
									<div class="input-group-append">
										<button class="btn " type="submit" style="height:100%;background-color:#e63401; color:#fff"><i class="fas fa-search"></i></button>
									</div>
								</div>
							</form>
					    </div>
					    
					</div>
					<div class="col-sm-3">
						<p class="datetime-head" style="text-align:right;margin-top:20px;">
                            @if (isset($headersetting->datetimeformat))
                            {{ date($headersetting->datetimeformat) }}
                            @else
                            {{ date('D d F, Y h:i A') }}
                            @endif
						</p>
					</div>
				</div>
			</div>
		</div>
		<!--<div class="main-header">-->
		<!--	<div class="container">-->
		<!--		<div class="row justify-content-center">-->
		<!--			<div class="col-sm-12 col-md-12 col-xs-12 col-lg-12">-->
		<!--				<div class="main-logo">-->
  <!--                          <a href="{{ route('/') }}">-->
  <!--                              @if (isset($headersetting->image) && Storage::exists($headersetting->image))-->
  <!--                              <img src="{{ URL::asset('storage/'.$headersetting->image) }}" alt="{{ $headersetting->title ?? '' }}" alt="Amrit Bharat Logo" class="img-fluid">-->
  <!--                              @else-->
		<!--						<img src="{{ URL::asset('front/images/logo.png') }}" alt="Amrit Bharat Logo" class="img-fluid">-->
  <!--                              @endif-->
		<!--					</a>-->
		<!--				</div>-->
		<!--			</div>-->
				
		<!--		</div>-->
		<!--	</div>-->
		<!--</div>-->

		<div class="navigation-menu">
			<div class="container-fluid">
			    <div class="row">
			        <div class="col-lg-12">
			            <div id="examTime" class="position-relative">
							<div class="navwrap"  id="examtime-container">
							    <nav class="navbar navbar-expand-lg">
                					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    					<span class="fas fa-bars"></span>
                  					</button>
                					<div class="collapse navbar-collapse" id="navbarSupportedContent">
                						<ul class="navbar-nav">
                							<li class="nav-item">
                								<a class="nav-link ml-4" href="{{ route('/') }}">होम</a>
                                            </li>
                                            @if (isset($headercategorieswithsubcategories) && count($headercategorieswithsubcategories)>0)
                                            @foreach ($headercategorieswithsubcategories as $headercategorieswithsubcategory)
                                            <li class="nav-item dropdown">
                								<a class="nav-link dropdown-toggle" href="{{ route('postbycategory',$headercategorieswithsubcategory->slug) }}" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          							{{ $headercategorieswithsubcategory->name }}
                        						</a>
                								<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                									@if (isset($headercategorieswithsubcategory->subcategories) && count($headercategorieswithsubcategory->subcategories)>0)
                									@foreach ($headercategorieswithsubcategory->subcategories as $subcategory)
                                                        <li class="dropdown-item"><a href="{{ route('postbycategory',[$headercategorieswithsubcategory->slug,$subcategory->slug]) }}">{{ $subcategory->name }}</a></li>
                                                    @endforeach
                                                    @elseif (isset($headercategorieswithsubcategory->posts) && count($headercategorieswithsubcategory->posts)>0)
                                                    @foreach ($headercategorieswithsubcategory->posts as $post)
                                                        <li class="dropdown-item"><a href="{{ route('postdetail',[$headercategorieswithsubcategory->slug,$post->slug]) }}">{{ $post->title }}</a></li>
                                                    @endforeach
                                                    @endif
                								</ul>
                                            </li>
                                            @endforeach
                                            @endif
                                            @if (isset($headercategorieswithoutsubcategories) && count($headercategorieswithoutsubcategories)>0)
                                            @foreach ($headercategorieswithoutsubcategories as $category)
                							<li class="nav-item">
                								<a class="nav-link" href="{{ route('postbycategory',$category->slug) }}">{{ $category->name }}</a>
                							</li>
                                            @endforeach
                                            @endif
                						</ul>
                					</div>
                				</nav>
							</div>
							<button id="slideBack" type="button"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
							<button id="slidePrev" type="button"><i class="fa fa-arrow-right" aria-hidden="true"></i></button>
						</div>
			        </div>
			    </div>
			</div>
		</div>

		<div class="bottom-menu">
			<div class="container">
				<div class="extra-highlights">
					<ul>
                        @if (isset($tags) && count($tags)>0)
                        @foreach ($tags as $tag)
						<li >
							<form action="{{ route('search') }}" method="GET">
								<input type="hidden" name="tag" value="{{ $tag->slug }}">
								<button type="submit" class="btn btn-link">{{ $tag->name }}</button>
							</form>
						</li>
                        @endforeach
                        @endif
					</ul>
				</div>
			</div>
		</div>
		
		<div class="breaking-news" style="height:36px">
		    <div class="container">
		        <div class="row">
                    <div class="col-sm-3 col-md-2">
                        <h3 style="height:36px;    padding-top: 6px;">Breaking News:</h3>
                    </div>
                    <div class="col-sm-9 col-md-10">
                        <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();"> 
                            <ul class="news-mar">
                                @if(isset($breakingnews) && count($breakingnews)>0)
                                @foreach($breakingnews as $breakingnew)
                                <li ><a href="{{ route('postdetail',[$breakingnew->categories[0]->category->slug,$breakingnew->slug]) }}">{{ $breakingnew->title }}</a> <i class="far fa-star"></i></li>
                                @endforeach
                                @endif
                            </ul>
                        </marquee>
                    </div>
                </div>
		    </div>
		</div>
	</header>
	
	
<script>
var button = document.getElementById('slidePrev');
button.onclick = function () {
    var examtimecontainer = document.getElementById('examtime-container');
    sideScroll(examtimecontainer,'right',25,100,10);
};

var back = document.getElementById('slideBack');
back.onclick = function () {
    var examtimecontainer = document.getElementById('examtime-container');
    sideScroll(examtimecontainer,'left',25,100,10);
};

function sideScroll(element,direction,speed,distance,step){
    scrollAmount = 0;
    var slideTimer = setInterval(function(){
        if(direction == 'left'){
            element.scrollLeft -= step;
        } else {
            element.scrollLeft += step;
        }
        scrollAmount += step;
        if(scrollAmount >= distance){
            window.clearInterval(slideTimer);
        }
    }, speed);
}


var button = document.getElementById('testSeriesslidePrev');
button.onclick = function () {
    var testSeriescontainer = document.getElementById('testSeries-container');
    sideScroll(testSeriescontainer,'right',25,100,10);
};

var back = document.getElementById('testSeriesslideBack');
back.onclick = function () {
    var testSeriescontainer = document.getElementById('testSeries-container');
    sideScroll(testSeriescontainer,'left',25,100,10);
};

function sideScroll(element,direction,speed,distance,step){
    scrollAmount = 0;
    var slideTimer = setInterval(function(){
        if(direction == 'left'){
            element.scrollLeft -= step;
        } else {
            element.scrollLeft += step;
        }
        scrollAmount += step;
        if(scrollAmount >= distance){
            window.clearInterval(slideTimer);
        }
    }, speed);
}

</script>
