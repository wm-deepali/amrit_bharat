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
                                    <li class="breadcrumb-item active" aria-current="page">Search : {{ $keyword }}</li>
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
                                <a href="{{ route('postdetail',[$post->categories[0]->category->slug,$post->slug]) }}">
                                    @if (isset($post->image) && Storage::exists($post->image))
                                    <img class="mr-3" src="{{ URL::asset('storage/'.$post->image) }}" alt="{{ $post->title }}">
                                    @else
                                    <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $post->title }}" class="img-fluid">
                                    @endif
                                </a>
                                <div class="media-body">
                                    <a href="{{ route('postdetail',[$post->categories[0]->category->slug,$post->slug]) }}">
                                        <span class="posted-on">{{ \Carbon\Carbon::parse($post->created_at)->format('j F, Y') }}</span>
                                        <h5 class="mt-0">{{ $post->title }}</h5>
                                        <!--<p>{!! Str::limit($post->content, 500) !!}</p>-->
                                    </a>
                                     @if(isset($post->user) && !empty($post->user) && $post->user->id !=1)
                                        <p>News Source: 
                                        <a href="{{ route('search', ['source' => $post->user->id]) }}" style="color: #000;">{{  $post->user->name }}</a></p>
                                    @endif
                                    <div class="div-tags">
                                        <ul>
                                            @if (isset($post->tags) && count($post->tags)>0)
                                            @foreach ($post->tags as $tag)
                                            <li>
                                                <form action="{{ route('search') }}" method="GET">
								                    <input type="hidden" name="tag" value="{{ $tag->tag->slug ?? 'NA' }}">
								                    <button type="submit" class="btn btn-link">{{ $tag->tag->name ?? 'NA' }}</button>
							                    </form>
                                            </li>
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
               {{ $posts->appends(Request::get('tag'))->links() }}
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
                            <li><a href="{{ route('postdetail',[$bigpost->categories[0]->category->slug,$bigpost->slug]) }}">{{ $bigpost->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                <div class="google-ad text-center">
                    <img src="{{ URL::asset('front/images/300x250.jpg') }}" class="img-fluid">
                </div>
                <div class="google-ad text-center">
                    <img src="{{ URL::asset('front/images/300x600.jpg') }}" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</main>
@include('front.footer')
