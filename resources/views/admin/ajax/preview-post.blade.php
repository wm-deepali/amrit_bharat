<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Check Preview</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="form-group row">
           <div class="col-sm-4">
               <label class="con-label">Featured Image</label><br>
               @if(isset($post->image) && Storage::exists($post->image))
               <img src="{{ URL::asset('storage/'.$post->image) }}" class="img-fluid" style="height:70px;">
               @else
               <img src="{{ URL::asset('front/images/ppn-logo.jpeg') }}" class="img-fluid" style="height:70px;">
               @endif
           </div>
       </div>        
       <div class="form-group row">
           <div class="col-sm-4">
               <label class="con-label">Post Title</label>
               <h3 class="text-con-label">{{ $post->title }}</h3>
           </div>
           <div class="col-sm-4">
               <label class="con-label">Post Slug</label>
               <h3 class="text-con-label">{{ $post->slug }}</h3>
           </div>
           <div class="col-sm-4">
               <label class="con-label">Youtube Embed</label>
               <h3 class="text-con-label">{{ $post->video ?? 'NA' }}</h3>
           </div>
       </div>
       
       <div class="form-group row">
           <div class="col-sm-12">
               <label class="con-label">Content</label>
               {!! $post->content !!}
           </div>
       </div>
       
       <div class="form-group row">
           <div class="col-sm-4">
               <label class="con-label">Category</label>
               <h3 class="text-con-label">
                    @if (isset($post->categories) && count($post->categories)>0)
                    @foreach ($post->categories as $category)
                    {{ $category->category->name }}
                    @endforeach
                    @endif
                </h3>
           </div>
           <div class="col-sm-4">
               <label class="con-label">Sub Category</label>
               <h3 class="text-con-label">
                @if (isset($post->subcategories) && count($post->subcategories)>0)
                    @foreach ($post->subcategories as $subcategory)
                    {{ $subcategory->subcategory->name }}
                    @endforeach
                @endif
                </h3>
           </div>
           <div class="col-sm-4">
               <label class="con-label">Tags</label>
               <h3 class="text-con-label">
                @if (isset($post->tags) && count($post->tags)>0)
                @foreach ($post->tags as $tag)
                    {{ $tag->tag->name ?? 'NA' }}
                @endforeach
                @endif
               </h3>
           </div>
       </div>
       
       <div class="form-group row">
           <div class="col-sm-4">
               <label class="con-label">Meta Title</label>
               <h3 class="text-con-label">{{ $post->metatitle }}</h3>
           </div>
           <div class="col-sm-4">
               <label class="con-label">Meta Description</label>
               <h3 class="text-con-label">{{ $post->metadescription }}</h3>
           </div>
           <div class="col-sm-4">
               <label class="con-label">Meta Keywords</label>
               <h3 class="text-con-label">{{ $post->metakeyword }}</h3>
           </div>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>