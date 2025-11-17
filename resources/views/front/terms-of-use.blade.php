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
<li class="breadcrumb-item active" aria-current="page">Terms of Use</li>
</ol>
</nav>
</div>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-sm-9">
<div class="terms-of-use">
{!! $termsofuse->content ?? Null !!}
</div>
</div>
<div class="col-sm-3">
<div class="google-ad"> <img src="{{ URL::asset('front/images/ads2.gif') }}" class="img-fluid"> </div>
<div class="google-ad"> <img src="{{ URL::asset('front/images/ads.gif') }}" class="img-fluid"> </div>
</div>
</div>
</div>
</main>
@include('front.footer')
