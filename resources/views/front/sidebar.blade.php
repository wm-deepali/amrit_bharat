<div class="col-sm-3 col-md-4 col-lg-3">
    <div class="google-ad">
        
        <div id="Ad4" class="carousel slide" data-ride="carousel"  data-interval="{{ $adsetting->homepageuppersidebar300x250time*1000 }}">
            <ol class="carousel-indicators">
                @if (isset($uppersidebar300x250) && count($uppersidebar300x250)>0)
                @foreach ($uppersidebar300x250 as $uppersidebar)
                @if($uppersidebar->type=='custom')
                <li data-target="#Ad4" data-slide-to="{{ $loop->index }}" @if ($loop->index=='0')
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
                @if ($loop->index=='0')
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
            <img src="{{ URL::asset('front/images/300x250.jpg') }}" class="img-fluid">
            @endif
            </div>
        </div>
        
    </div>
    @if (isset($question))
    <div class="sidebar-articles">
        <div class="section-title-s">
          <h3 class="block_title"><span>AAPKI RAI</span></h3>
        </div>
        <div class="inner-poll">
        <form id="poll-form">
           <h3>Q. {{ $question->question }}</h3>
           @if (session('questionid')!= $question->id)
           <ul>
               <li><label><input type="radio" name="option" value="option1"> {{ $question->option1 }}</label></li>
               <li><label><input type="radio" name="option" value="option2"> {{ $question->option2 }}</label></li>
               <li><label><input type="radio" name="option" value="option3"> {{ $question->option3 }}</label></li>
               <!--<li><label><input type="radio" name="option" value="option4"> {{ $question->option4 }}</label></li>-->
           </ul>
           <div class="text-danger" id="option-err"></div>
            <button class="btn btn-vote btn-sm add-poll-btn" type="button">Vote Now</button>
            @else
            <ul>
               <li><div style="width:{{ round(($question->option1vote/($question->option1vote+$question->option2vote+$question->option3vote))*100) }}%;"></div> <span>{{ $question->option1 }}</span><span>{{ round(($question->option1vote/($question->option1vote+$question->option2vote+$question->option3vote))*100) }}%</span></li>
               <li><div style="width:{{ round(($question->option2vote/($question->option1vote+$question->option2vote+$question->option3vote))*100) }}%;"></div> <span>{{ $question->option2 }}</span><span>{{ round(($question->option2vote/($question->option1vote+$question->option2vote+$question->option3vote))*100) }}%</span></li>
               <li><div style="width:{{ round(($question->option3vote/($question->option1vote+$question->option2vote+$question->option3vote))*100) }}%;"></div> <span>{{ $question->option3 }}</span><span>{{ round(($question->option3vote/($question->option1vote+$question->option2vote+$question->option3vote))*100) }}%</span></li>
           </ul>
            @endif
        </div>
        </form>
    </div>
    @endif
     <div class="google-ad">
         
        <div id="Ad5" class="carousel slide" data-ride="carousel"  data-interval="{{ $adsetting->homepagemiddlesidebar300x250time*1000 }}">
            <ol class="carousel-indicators">
                @if (isset($middlesidebar300x250) && count($middlesidebar300x250)>0)
                @foreach ($middlesidebar300x250 as $middlesidebar)
                @if($middlesidebar->type=='custom')
                <li data-target="#Ad5" data-slide-to="{{ $loop->index }}" @if ($loop->index=='0')
                    class="active"
                @endif></li>
                @endif
                @endforeach
                @endif
            </ol>
            <div class="carousel-inner">
                @if (isset($middlesidebar300x250) && count($middlesidebar300x250)>0)
                @foreach ($middlesidebar300x250 as $middlesidebar)
                @if($middlesidebar->type=='custom')
                @if ($loop->index=='0')
                <div class="carousel-item active">
                @else
                <div class="carousel-item">
                @endif
                @if (isset($middlesidebar->image) && Storage::exists($middlesidebar->image))
                <a href="{{ $middlesidebar->link }}"><img src="{{ URL::asset('storage/'.$middlesidebar->image) }}" class="img-fluid"></a>
                @else
                <img src="{{ URL::asset('front/images/300x250.jpg') }}" class="img-fluid">
                @endif
            </div>
            @else
            {!! $middlesidebar->code !!}
            @endif
            @endforeach
            @else
            <img src="{{ URL::asset('front/images/300x250.jpg') }}" class="img-fluid">
            @endif
            </div>
        </div>
        
    </div>
    <div class="sidebar-articles">
        <div class="section-title-s">
          <h3 class="block_title"><span>{{ $sidebartab1category->name }}</span></h3>
        </div>
        <div class="inner-sd-article">
            @if (isset($sidebartab1posts) && count($sidebartab1posts)>0)
            <a href="{{ route('postdetail',[$sidebartab1category->slug,$sidebartab1posts[0]->slug]) }}">
                @if (isset($sidebartab1posts[0]->image) && Storage::exists($sidebartab1posts[0]->image))
                <img src="{{ URL::asset('storage/'.$sidebartab1posts[0]->image) }}" class="img-fluid">
                @endif
                <h3>{{ $sidebartab1posts[0]->title }}</h3>
            </a>
            <ul class="bullet-list">
                @foreach ($sidebartab1posts as $sidebartab1post)
                <li><a href="{{ route('postdetail',[$sidebartab1category->slug,$sidebartab1post->slug]) }}">{{ $sidebartab1post->title }}</a></li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
     <div class="google-ad">
         
        <div id="Ad6" class="carousel slide" data-ride="carousel"  data-interval="{{ $adsetting->homepageuppersidebar300x600time*1000 }}">
            <ol class="carousel-indicators">
                @if (isset($uppersidebar300x600) && count($uppersidebar300x600)>0)
                @foreach ($uppersidebar300x600 as $uppersidebar)
                @if($uppersidebar->type=='custom')
                <li data-target="#Ad6" data-slide-to="{{ $loop->index }}" @if ($loop->index=='0')
                    class="active"
                @endif></li>      
                @endif
                @endforeach
                @endif
            </ol>
            <div class="carousel-inner">
                @if (isset($uppersidebar300x600) && count($uppersidebar300x600)>0)
                @foreach ($uppersidebar300x600 as $uppersidebar)
                @if($uppersidebar->type=='custom')
                @if ($loop->index=='0')
                <div class="carousel-item active">
                @else
                <div class="carousel-item">
                @endif
                @if (isset($uppersidebar->image) && Storage::exists($uppersidebar->image))
                <a href="{{ $uppersidebar->link }}"><img src="{{ URL::asset('storage/'.$uppersidebar->image) }}" class="img-fluid"></a>
                @else
                <img src="{{ URL::asset('front/images/300x600.jpg') }}" class="img-fluid">
                @endif
            </div>
            @else
            {!! $uppersidebar->code !!}
            @endif
            @endforeach
            @else
            <img src="{{ URL::asset('front/images/300x600.jpg') }}" class="img-fluid">
            @endif
            </div>
        </div>
        
    </div>
   
    <div class="sidebar-articles">
        <div class="section-title-s">
          <h3 class="block_title"><span>{{ $sidebartab2category->name }}</span></h3>
        </div>
        <div class="inner-sd-article">
            @if (isset($sidebartab2posts) && count($sidebartab2posts)>0)
            <a href="{{ route('postdetail',[$sidebartab2category->slug,$sidebartab2posts[0]->slug]) }}">
                @if (isset($sidebartab2posts[0]->image) && Storage::exists($sidebartab2posts[0]->image))
                <img src="{{ URL::asset('storage/'.$sidebartab2posts[0]->image) }}" class="img-fluid">
                @endif
                <h3>{{ $sidebartab2posts[0]->title }}</h3>
            </a>
            <ul class="bullet-list">
                @foreach ($sidebartab2posts as $sidebartab2post)
                <li><a href="{{ route('postdetail',[$sidebartab2category->slug,$sidebartab2post->slug]) }}">{{ $sidebartab2post->title }}</a></li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>

    <div class="google-ad">
        
        <div id="Ad7" class="carousel slide" data-ride="carousel"  data-interval="{{ $adsetting->homepagemiddlesidebar300x250time*1000 }}">
            <ol class="carousel-indicators">
                @if (isset($middlesidebar300x250) && count($middlesidebar300x250)>0)
                @foreach ($middlesidebar300x250 as $middlesidebar)
                @if($middlesidebar->type=='custom')
                <li data-target="#Ad7" data-slide-to="{{ $loop->index }}" @if ($loop->index=='0')
                    class="active"
                @endif></li>      
                @endif
                @endforeach
                @endif
            </ol>
            <div class="carousel-inner">
            @if (isset($middlesidebar300x250) && count($middlesidebar300x250)>0)
            @foreach ($middlesidebar300x250 as $middlesidebar)
            @if($middlesidebar->type=='custom')
            @if ($loop->index=='0')
            <div class="carousel-item active">
            @else
            <div class="carousel-item">
            @endif
                @if (isset($middlesidebar->image) && Storage::exists($middlesidebar->image))
                <a href="{{ $middlesidebar->link }}"><img src="{{ URL::asset('storage/'.$middlesidebar->image) }}" class="img-fluid"></a>
                @else
                <img src="{{ URL::asset('front/images/300x250.jpg') }}" class="img-fluid">
                @endif
            </div>
            @else
            {!! $middlesidebar->code !!}
            @endif
            @endforeach
            @else
            <img src="{{ URL::asset('front/images/300x250.jpg') }}" class="img-fluid">
            @endif
            </div>
        </div>
        
    </div>
    <div class="sidebar-articles">
        <div class="section-title-s">
          <h3 class="block_title"><span>{{ $sidebartab3category->name }}</span></h3>
        </div>
        <div class="inner-sd-article">
            @if (isset($sidebartab3posts) && count($sidebartab3posts)>0)
            <a href="{{ route('postdetail',[$sidebartab3category->slug,$sidebartab3posts[0]->slug]) }}">
                @if (isset($sidebartab3posts[0]->image) && Storage::exists($sidebartab3posts[0]->image))
                <img src="{{ URL::asset('storage/'.$sidebartab3posts[0]->image) }}" class="img-fluid">
                @endif
                <h3>{{ $sidebartab3posts[0]->title }}</h3>
            </a>
            <ul class="bullet-list">
                @foreach ($sidebartab3posts as $sidebartab3post)
                <li><a href="{{ route('postdetail',[$sidebartab3category->slug,$sidebartab3post->slug]) }}">{{ $sidebartab3post->title }}</a></li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
    
    <div class="google-ad">

        <div id="Ad8" class="carousel slide" data-ride="carousel"  data-interval="{{ $adsetting->homepagelowersidebar300x250time*1000 }}">
            <ol class="carousel-indicators">
                @if (isset($lowersidebar300x250) && count($lowersidebar300x250)>0)
                @foreach ($lowersidebar300x250 as $lowersidebar)
                @if($lowersidebar->type=='custom')
                <li data-target="#Ad8" data-slide-to="{{ $loop->index }}" @if ($loop->index=='0')
                    class="active"
                @endif></li>      
                @endif
                @endforeach
                @endif
            </ol>
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
                <a href="{{ $lowersidebar->link }}"><img src="{{ URL::asset('storage/'.$lowersidebar->image) }}" class="img-fluid"></a>
                @else
                <img src="{{ URL::asset('front/images/300x250.jpg') }}" class="img-fluid">
                @endif
            </div>
            @else
            {!! $lowersidebar->code !!}
            @endif
            @endforeach
            @else
            <img src="{{ URL::asset('front/images/300x250.jpg') }}" class="img-fluid">
            @endif
            </div>
        </div>

        {{-- @if (isset($lowersidebar300x250->image) && Storage::exists($lowersidebar300x250->image))
        <a href="{{ $lowersidebar300x250->link }}"><img src="{{ URL::asset('storage/'.$lowersidebar300x250->image) }}" class="img-fluid"></a>
        @else
        <img src="{{ URL::asset('front/images/300x250.jpg') }}" class="img-fluid">
        @endif --}}
    </div>
    
    <div class="google-ad">

        <div id="Ad9" class="carousel slide" data-ride="carousel"  data-interval="{{ $adsetting->homepagelowersidebar300x600number*1000 }}">
            <ol class="carousel-indicators">
                @if (isset($lowersidebar300x600) && count($lowersidebar300x600)>0)
                @foreach ($lowersidebar300x600 as $lowersidebar)
                @if($lowersidebar->type=='custom')
                <li data-target="#Ad9" data-slide-to="{{ $loop->index }}" @if ($loop->index=='0')
                    class="active"
                @endif></li>
                @endif
                @endforeach
                @endif
            </ol>
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
                <a href="{{ $lowersidebar->link }}"><img src="{{ URL::asset('storage/'.$lowersidebar->image) }}" class="img-fluid"></a>
                @else
                <img src="{{ URL::asset('front/images/300x600.jpg') }}" class="img-fluid">
                @endif
            </div>
            @else
            {!! $lowersidebar->code !!}
            @endif
            @endforeach
            @else
            <img src="{{ URL::asset('front/images/300x600.jpg') }}" class="img-fluid">
            @endif
            </div>
        </div>

        {{-- @if (isset($lowersidebar300x600->image) && Storage::exists($lowersidebar300x600->image))
        <a href="{{ $lowersidebar300x600->link }}"><img src="{{ URL::asset('storage/'.$lowersidebar300x600->image) }}" class="img-fluid"></a>
        @else
        <img src="{{ URL::asset('front/images/300x600.jpg') }}" class="img-fluid">
        @endif --}}

    </div>
</div>
