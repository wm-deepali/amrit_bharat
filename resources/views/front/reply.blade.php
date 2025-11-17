@if(isset($child_Category->user) && !empty($child_Category->user))
<div class="media mt-3">
    <a class="pr-3" href="#">
        <img class="mr-3 img-fluid" src="{{ URL::asset('front/images/jenny.jpg') }}" alt="{{ $child_Category->user->name ?? '' }}">
    </a>
    <div class="media-body">
        <h5 class="comm-h5">{{ $child_Category->user->name ?? '' }}<span class="upda-time"> {{ $child_Category->created_at->diffForHumans() }}</span></h5>
        <p>{{ $child_Category->reply }}</p>
        <!--a href="javascript:void(0)" comment_id="{{ $child_Category->id }}" class="reply-box">Reply</a>
        @if (isset($child_Category->replies) && count($child_Category->replies)>0)
        @foreach ($child_Category->replies as $childCategory)
            @include('front.reply', ['child_Category' => $childCategory])
        @endforeach
        @endif -->
    </div>
</div>
@endif