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
                                    <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9">
                <div class="showing-result"> <span>Showing of {{ $posts->firstItem() }} - {{ $posts->lastItem() }} from results</span>
                    <h4>Total {{ $posts->total() }} result found</h4>
                </div>
                <div class="article-listing">
                    <div class="row">
                        @if (isset($posts) && count($posts)>0)
                        @foreach ($posts as $post)
                        <div class="col-sm-12">
                            <div class="media">
                                <a href="{{ route('postdetail',[$category->slug,$post->slug]) }}">
                                    @if (isset($post->image) && Storage::exists($post->image))
                                    <img class="mr-3" src="{{ URL::asset('storage/'.$post->image) }}" alt="{{ $post->title }}">
                                    @else
                                    <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $post->title }}" class="img-fluid">
                                    @endif
                                </a>
                                <div class="media-body">
                                    <a href="{{ route('postdetail',[$category->slug,$post->slug]) }}">
                                        <span class="posted-on">{{ \Carbon\Carbon::parse($post->created_at)->format('j F, Y') }}</span>
                                        <h5 class="mt-0">{{ $post->title }}</h5>
                                        <!--<p>{!! Str::limit($post->content, 500) !!}</p>-->
                                    </a>
                                    <div class="div-tags">
                                        <ul>
                                            @if (isset($post->tags) && count($post->tags)>0)
                                                @foreach ($post->tags as $tag)
                                                    @if(isset($tag->tag))
                                                        <li>
                                                            <form action="{{ route('search') }}" method="GET">
								                                <input type="hidden" name="tag" value="{{ $tag->tag->slug }}">
								                                <button type="submit" class="btn btn-link">{{ $tag->tag->name }}</button>
							                                </form>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
                {{-- <div class="paginate pb-5">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div> --}}
                {{ $posts->links() }}
            </div>
            <div class="col-sm-3">
                <div class="sidebar-articles">
                    <div class="section-title-s">
                        <h3 class="block_title"><span>Big News</span></h3>
                    </div>
                    @if (isset($bigposts) && count($bigposts)>0)
                    <div class="inner-sd-article">
                        <a href="{{ route('postdetail',[$bigposts[0]->categories[0]->category->slug,$bigposts[0]->slug]) }}">
                            @if (isset($bigposts[0]->image) && Storage::exists($bigposts[0]->image))
                            <img src="{{ URL::asset('storage/'.$bigposts[0]->image) }}" alt="{{ $bigposts[0]->title }}" class="img-fluid">
                            @else
                            <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $bigposts[0]->title }}" class="img-fluid">
                            @endif
                            <h3>{{ $bigposts[0]->title }}</h3>
                        </a>
                        <ul class="bullet-list">
                            @foreach ($bigposts as $bigpost)
                            <li>
                                <a href="{{ route('postdetail',[$bigpost->categories[0]->category->slug,$bigpost->slug]) }}">
                                    {{ $bigpost->title }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                <div class="google-ad text-center">
                    
                    <div id="Ad1" class="carousel slide" data-ride="carousel"  data-interval="{{ $adsetting->categorypagesidebar300x250time*1000 }}">
                            <ol class="carousel-indicators">
                                @if (isset($sidebar300x250) && count($sidebar300x250)>0)
                                @foreach ($sidebar300x250 as $sidebar)
                                @if($sidebar->type=='custom')
                                <li data-target="#Ad1" data-slide-to="{{ $loop->iteration }}" @if ($loop->iteration=='1')
                                    class="active"
                                @endif></li>
                                @endif
                                @endforeach
                                @endif
                            </ol>
                            <div class="carousel-inner">
                            @if (isset($sidebar300x250) && count($sidebar300x250)>0)
                            @foreach ($sidebar300x250 as $sidebar)
                            @if($sidebar->type=='custom')
                            @if ($loop->iteration=='1')
                            <div class="carousel-item active">
                            @else
                            <div class="carousel-item">
                            @endif
                                @if (isset($sidebar->image) && Storage::exists($sidebar->image))
                                <a href="{{ $sidebar->link }}"><img src="{{ URL::asset('storage/'.$sidebar->image) }}" class="img-fluid"></a>
                                @else
                                <img src="{{ URL::asset('front/images/300x250.jpg') }}" class="img-fluid">
                                @endif
                            </div>
                            @else
                            {!! $sidebar->code !!}
                            @endif
                            @endforeach
                            @else
                            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> <!-- Prayag-darpan-Home-Square --> <ins class="adsbygoogle"      style="display:inline-block;width:300px;height:250px"      data-ad-client="ca-pub-8691037606832105"      data-ad-slot="7868777463"></ins> <script>      (adsbygoogle = window.adsbygoogle || []).push({}); </script>
                            <!--<img src="{{ URL::asset('front/images/300x250.jpg') }}" class="img-fluid">-->
                            @endif
                            </div>
                        </div>
                    
                </div>
                <div class="google-ad text-center">
                    
                    <div id="Ad2" class="carousel slide" data-ride="carousel"  data-interval="{{ $adsetting->categorypagesidebar300x600time*1000 }}">
                            <ol class="carousel-indicators">
                                @if (isset($sidebar300x600) && count($sidebar300x600)>0)
                                @foreach ($sidebar300x600 as $sidebar)
                                @if($sidebar->type=='custom')
                                <li data-target="#Ad2" data-slide-to="{{ $loop->iteration }}" @if ($loop->iteration=='1')
                                    class="active"
                                @endif></li>      
                                @endif
                                @endforeach
                                @endif
                            </ol>
                            <div class="carousel-inner">
                            @if (isset($sidebar300x600) && count($sidebar300x600)>0)
                            @foreach ($sidebar300x600 as $sidebar)
                            @if($sidebar->type=='custom')
                            @if ($loop->iteration=='1')
                            <div class="carousel-item active">
                            @else
                            <div class="carousel-item">
                            @endif
                                @if (isset($sidebar->image) && Storage::exists($sidebar->image))
                                <a href="{{ $sidebar->link }}"><img src="{{ URL::asset('storage/'.$sidebar->image) }}" class="img-fluid"></a>
                                @else
                                <img src="{{ URL::asset('front/images/300x600.jpg') }}" class="img-fluid">
                                @endif
                            </div>
                            @else
                            {!! $sidebar->code !!}
                            @endif
                            @endforeach
                            @else
                            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> <!-- Prayag-darpan-Home-Vertical --> <ins class="adsbygoogle"      style="display:block"      data-ad-client="ca-pub-8691037606832105"      data-ad-slot="3929532451"      data-ad-format="auto"      data-full-width-responsive="true"></ins> <script>      (adsbygoogle = window.adsbygoogle || []).push({}); </script>
                            <!--<img src="{{ URL::asset('front/images/300x600.jpg') }}" class="img-fluid">-->
                            @endif
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</main>
@include('front.footer')
