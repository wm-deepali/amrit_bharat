<div class="row">
    @if(isset($posts) && count($posts)>0)
        @foreach($posts as $post)
            @if(isset($post->image) && Storage::exists($post->image))
                <div class="col-sm-3">
                    <a href="javascript:void(0)" class="imagename" imagename="{{ $post->image }}">
                        <img src="{{ URL::asset('storage/'.$post->image) }}" class="img-fluid">
                    </a>
                </div>
            @endif
        @endforeach
   @endif
</div>