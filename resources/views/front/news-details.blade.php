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
                                    <li class="breadcrumb-item"><a href="{{ route('postbycategory',$category->slug) }}">{{ $category->name }}</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 50) }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9">
                <div class="news-details">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="news-title">
                                {{ $post->title }}
                            </h3>
                            <div class="social-news-share">
                                <div class="row">
                                    <div class="col-sm-12 text-right">
                                        <script type="text/javascript" src="https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5ff859e4a6a00bf3"></script>
                                        <div class="addthis_inline_share_toolbox"></div>
                                    </div>
                                </div>
                            </div>
                            <ul class="news-det">
                                @if(isset($post->user) && !empty($post->user) && $post->user->id !=1)
                                     <li>Posted By: <a href="{{ route('search', ['source' => $post->user->id]) }}">{{ $post->user->name }} 
                                    @if (isset($post->user->image) && Storage::exists($post->user->image))
                                        <img src="{{ URL::asset('storage/'.$post->user->image) }}" alt="{{ $post->user->name }}" height="50">
                                    @endif
                                </a>
                                </li>
                                    
                                @else
                                 <li>Posted By: <a href="javascript:void(0);">{{ $post->user->name }} 
                                    @if (isset($post->user->image) && Storage::exists($post->user->image))
                                        <img src="{{ URL::asset('storage/'.$post->user->image) }}" alt="{{ $post->user->name }}" height="50">
                                    @endif
                                </a>
                                </li>
                                @endif
                               
                                <li><a href="{{ route('postbycategory',$category->slug) }}">{{ $category->name }}</a>
                                </li>
                                <li>Updated: {{ \Carbon\Carbon::parse($post->created_at)->format('j F, Y H:i') }}</li>
                                <li><i class="fas fa-eye"></i> {{ $views ?? '0' }}</li>
                                <li><a target="_blank" href="https://api.whatsapp.com/send?text={{ $post->title }} {{ url($category->slug)}}/{{$post->slug}}/detail" style="color:green !important;"><i class="fab fa-whatsapp"></i></a></li>
                                <li><a target="_blank" href="http://www.facebook.com/sharer.php?u={{ url($category->slug)}}/{{$post->slug}}/detail"><i class="fab fa-facebook-f" aria-hidden="true" style="color: rgb(29 155 240);"></i></a></li>
                                <li><a target="_blank" href="http://twitter.com/share?text= {{ $post->title }}&url={{ url($category->slug)}}/{{$post->slug}}/detail"><i class="fab fa-twitter" style="color: rgb(29 155 240);"></i></a></li>
                                <li><a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url={{ url($category->slug)}}/{{$post->slug}}/detail"><i class="fa fa-rss-square" aria-hidden="true" style="color: rgb(29 155 240);"></i></a></li>
                                <li><a target="_blank" href="https://www.instagram.com/?url={{ url($category->slug)}}/{{$post->slug}}/detail"><i class="fab fa-instagram" style="color:#c733a7;"></i></a></li>
                                <li><a target="_blank" href="http://pinterest.com/pin/create/button/?url={{ url($category->slug)}}/{{$post->slug}}/detail&media={{ URL::asset('storage/'.$post->image) }}&text={{ $post->title }}"><i class="fab fa-pinterest" style="color:#b7081b;"></i></a></li>
                            
                            </ul>
                            <div class="news-img">
                                @if (isset($post->video))
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $post->video }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                @elseif (isset($post->image) && Storage::exists($post->image))
                                <img src="{{ URL::asset('storage/'.$post->image) }}" alt="{{ $post->title }}" class="img-fluid">
                                @else
                                <img src="{{ URL::asset('front/images/logo.png') }}" alt="{{ $post->title }}" class="img-fluid">
                                @endif
                            </div>
                            <div class="news-content">
                                {!! $post->content !!}
                            </div>

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

                            <div class="news-section-comments">
                                <h5 class="comm-head">Comments</h5>
                                
                                @if (isset($post->postcomments) && count($post->postcomments)>0)
                                @foreach ($post->postcomments as $comment) 
                                @if(isset($comment->user) && !empty($comment->user))
                                <div class="media">
                                    <img class="mr-3 img-fluid" src="{{ URL::asset('front/images/matt.jpg') }}" alt="{{ $comment->name }}">
                                    <div class="media-body">
                                        <h5 class="comm-h5">{{ $comment->user->name ?? '' }} <span class="upda-time">{{ $comment->created_at->diffForHumans() }}</span></h5>
                                        <p>{{ $comment->comment }}</p>
                                        <a href="javascript:void(0)" comment_id="{{ $comment->id }}" class="reply-box">Reply</a>
                                        @if (isset($comment->commentreplies) && count($comment->commentreplies)>0)
                                        @foreach ($comment->commentreplies as $childCategory)
                                            @include('front.reply', ['child_Category' => $childCategory])
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @endforeach
                                @endif
                            </div>

                            <div class="news-reply-comment newcomment">
                                <h3>Leave A Comment</h3>
                                <p>Your email address will not be published. Required fields are marked *</p>
                                <div class="comment-section-form">
                                    <form id="add-comment-form">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Name" name="name" class="text-control commbox">
                                            <div class="text-danger" id="name-err"></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="email" placeholder="Email" name="email" class="text-control commbox">
                                            <div class="text-danger" id="email-err"></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Mobile Number" name="contact" class="text-control commbox">
                                            <div class="text-danger" id="contact-err"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <textarea cols="4" rows="2" class="text-control" placeholder="Your Comments" name="comment"></textarea>
                                            <div class="text-danger" id="comment-err"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="post_id" id="post_id" value="{{ $post->id }}">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <button class="btn btn-primary add-comment-btn" type="button">Post Comment</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="news-reply-comment replycomment" style="display:none">
                                <h3>Leave A Reply</h3>
                                <p>Your email address will not be published. Required fields are marked *</p>
                                <div class="comment-section-form">
                                    <form id="add-comment-reply-form">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Name" name="name" id="name" class="text-control">
                                            <div class="text-danger" id="replyname-err"></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="email" placeholder="Email" name="email" class="text-control">
                                            <div class="text-danger" id="replyemail-err"></div>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" placeholder="Mobile Number" name="contact" class="text-control">
                                            <div class="text-danger" id="replycontact-err"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <textarea cols="4" rows="2" class="text-control" placeholder="Your Comments" name="comment"></textarea>
                                            <div class="text-danger" id="replycomment-err"></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                    <input type="hidden" name="comment_id" id="comment_id">
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <button class="btn btn-primary add-comment-reply-btn" type="button">Reply</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="sidebar-articles">
                    <h4><span>Big News</span></h4>
                    @if (isset($bigposts) && count($bigposts)>0)
                    <div class="inner-sd-article">
                        <a href="{{ route('postdetail',[$category->slug,$bigposts[0]->slug]) }}">
                            @if (isset($bigposts[0]->image) && Storage::exists($bigposts[0]->image))
                            <img src="{{ URL::asset('storage/'.$bigposts[0]->image) }}" alt="{{ $bigposts[0]->title }}" class="img-fluid">
                            @endif
                            <h3>{{ $bigposts[0]->title }}</h3>
                        </a>
                        <ul class="bullet-list">
                            @foreach ($bigposts as $bigpost)
                            <li><a href="{{ route('postdetail',[$category->slug,$bigpost->slug]) }}">{{ $bigpost->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
            </div>
            <div class="google-ad text-center">
                
                    <div id="Ad1" class="carousel slide" data-ride="carousel"  data-interval="{{ $adsetting->postpageuppersidebar300x250time*1000 }}">
                        <ol class="carousel-indicators">
                            @if (isset($uppersidebar300x250) && count($uppersidebar300x250)>0)
                            @foreach ($uppersidebar300x250 as $uppersidebar)
                            @if($uppersidebar->type=='custom')
                            <li data-target="#Ad1" data-slide-to="{{ $loop->iteration }}" @if ($loop->iteration=='1')
                                class="active"
                            @endif></li>      
                            @endif
                            @endforeach
                            @endif
                        </ol>
                        <div class="carousel-inner">
                            @if (isset($uppersidebar300x250) && count($uppersidebar300x250)>0)
                            @foreach ($uppersidebar300x250 as $uppersidebar)
                            @if($uppersidebar->type=='custom')
                            @if ($loop->iteration=='1')
                            <div class="carousel-item active">
                            @else
                            <div class="carousel-item">
                            @endif
                            @if (isset($uppersidebar->image) && Storage::exists($uppersidebar->image))
                            <a href="{{ $uppersidebar->link }}"><img src="{{ URL::asset('storage/'.$uppersidebar->image) }}" class="img-fluid"></a>
                            @else
                            <img src="{{ URL::asset('front/images/300x250.jpg') }}" class="img-fluid">
                            @endif
                        </div>
                        @else
                        {!! $uppersidebar->code !!}
                        @endif
                        @endforeach
                        @else
                        <!--<img src="{{ URL::asset('front/images/300x250.jpg') }}" class="img-fluid">-->
                        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> <!-- Prayag-darpan-Home-Square --> <ins class="adsbygoogle"      style="display:inline-block;width:300px;height:250px"      data-ad-client="ca-pub-8691037606832105"      data-ad-slot="7868777463"></ins> <script>      (adsbygoogle = window.adsbygoogle || []).push({}); </script>
                        @endif
                        </div>
                    </div>
                    
                </div>
                <div class="google-ad text-center">
                    
                    <div id="Ad3" class="carousel slide" data-ride="carousel"  data-interval="{{ $adsetting->postpagesidebar300x600time*1000 }}">
                            <ol class="carousel-indicators">
                                @if (isset($sidebar300x600) && count($sidebar300x600)>0)
                                @foreach ($sidebar300x600 as $sidebar)
                                @if($sidebar->type=='custom')
                                <li data-target="#Ad3" data-slide-to="{{ $loop->iteration }}" @if ($loop->iteration=='1')
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
                            <!--<img src="{{ URL::asset('front/images/300x600.jpg') }}" class="img-fluid">-->
                            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> <!-- Prayag-darpan-Home-Vertical --> <ins class="adsbygoogle"      style="display:block"      data-ad-client="ca-pub-8691037606832105"      data-ad-slot="3929532451"      data-ad-format="auto"      data-full-width-responsive="true"></ins> <script>      (adsbygoogle = window.adsbygoogle || []).push({}); </script>
                            @endif
                            </div>
                        </div>
                    
                </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="youmay-article">
                <div class="section-title">
                    <h3><span class="trend-ico"> <i class="fas fa-file-alt"></i> </span> You May Like </h3>
                </div>
                <div class="row">
                    @if (isset($youmaylikeposts) && count($youmaylikeposts)>0)
                    @foreach ($youmaylikeposts as $youmaylikepost)
                    <div class="col-sm-3">
                        <div class="mr-main">
                            <a href="{{ route('postdetail',[$category->slug,$youmaylikepost->slug]) }}">
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


    $(document).on('click','.add-comment-btn',function(event){
        $('#name-err').html('');
        $('#email-err').html('');
        $('#contact-err').html('');
        $('#comment-err').html('');
        $.ajax({
            url:"{{ URL::to('add-comment') }}",
            type:'POST',
            dataType:'json',
            data:$('#add-comment-form').serialize(),
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
                    if(result.errors.comment) {
                        $('#comment-err').html(result.errors.comment[0]);
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

    $(document).on("click",".reply-box",function(event){
        let comment_id=$(this).attr('comment_id');
        $('.newcomment').hide();
        $('.replycomment').show();
        $("#add-comment-reply-form").find('#name').focus();
        $("#comment_id").val(comment_id);
        document.getElementById('add-comment-reply-form').reset();
    });

    $(document).on('click','.add-comment-reply-btn',function(event){
        $('#replyname-err').html('');
        $('#replyemail-err').html('');
        $('#replycontact-err').html('');
        $('#replycomment-err').html('');
        let comment_id=$("#comment_id").val();
        $.ajax({
            url:`{{ URL::to('add-comment-reply/${comment_id}') }}`,
            type:'POST',
            dataType:'json',
            data:$('#add-comment-reply-form').serialize(),
            success:function(result){
                if(result.msgCode === '200') {
                    toastr.success(result.msgText);
                    location.reload();
                } else if (result.msgCode === '401') {
                    if(result.errors.name) {
                        $('#replyname-err').html(result.errors.name[0]);
                    }
                    if(result.errors.email) {
                        $('#replyemail-err').html(result.errors.email[0]);
                    }
                    if(result.errors.contact) {
                        $('#replycontact-err').html(result.errors.contact[0]);
                    }
                    if(result.errors.comment) {
                        $('#replycomment-err').html(result.errors.comment[0]);
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
