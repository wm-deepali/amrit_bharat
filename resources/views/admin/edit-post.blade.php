@include('admin.header')
<section class="breadcrumb-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="content-header">
          <h3 class="content-header-title">Posts</h3>
            <button type="button" class="btn btn-dark float-right update-post-btn" postid="{{ $post->id }}">Update Post</button>
			<button type="button" class="btn btn-danger float-right draft-update-post-btn" postid="{{ $post->id }}" style="margin-right: 10px;">Save as draft</button>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Posts</li>
            <li class="breadcrumb-item active">Update Post</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="content-main-body">
	<div class="container">
    <form id="add-post-form" enctype="multipart/form-data">
		<div class="row">
			<div class="col-sm-9 col-md-7 col-lg-9">
				<div class="card">
					<div class="card-body">
						<div class="card-block">
							<h4 class="form-section-h">Post Details</h4>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">Title </label>
                                    <input type="text" class="text-control" placeholder="Enter Title" name="title" id="title" value="{{ $post->title }}">
                                    <div class="text-danger" id="title-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">Post URL (Slug - English Only) </label>
                                    <input type="text" class="text-control" placeholder="Enter URL" name="slug" id="slug" value="{{ $post->slug }}">
                                    <div class="text-danger" id="slug-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">Content </label>
                                    <textarea cols="8" rows="4" class="text-control" id="content" placeholder="Content Here" name="content">{!! $post->content !!}</textarea>
                                    <div class="text-danger" id="content-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">Video Embed </label>
                                    <input type="text" class="text-control" placeholder="Ex. youtube.com/sfhwiehf" name="video" id="video" value="{{ $post->video }}">
                                    <div class="text-danger" id="video-err"></div>
								</div>
							</div>
							<h4 class="form-section-h">SEO Details</h4>
							<div class="form-group row">
								<div class="col-sm-6">
									<label class="label-control">Meta Title </label>
                                    <input type="text" class="text-control" placeholder="Enter Meta Title" name="metatitle" id="metatitle" value="{{ $post->metatitle }}">
                                    <div class="text-danger" id="metatitle-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-6">
									<label class="label-control">Meta Keywords </label>
                                    <textarea cols="3" rows="2" class="text-control" placeholder="Meta keywords here" name="metakeyword" id="metakeyword">{{ $post->metakeyword }}</textarea>
                                    <div class="text-danger" id="metakeyword-err"></div>
								</div>
								<div class="col-sm-6">
									<label class="label-control">Meta Description </label>
                                    <textarea cols="3" rows="2" class="text-control" placeholder="Meta Description here" name="metadescription" id="metadescription">{{ $post->metadescription }}</textarea>
                                    <div class="text-danger" id="metadescription-err"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-md-5 col-lg-3">
				<div class="card">
					<div class="card-body">
						<div class="card-block">
							<div class="form-group row">
								<div class="col-sm-12">
                                    <label class="label-control">Featured Image </label>
                                    @if (isset($post->image) && Storage::exists($post->image))
                                    <img src="{{ URL::asset('storage/'.$post->image) }}" class="img-fluid">
                                    @endif
                                    <input type="file" class="text-control" name="image" id="image">
                                    <input type="hidden" class="text-control" name="imagename" id="imagename">
                                    <a href="#" class="existing-gallery" data-target="#view-existing-gallery" data-toggle="modal">Search Existing Gallery</a>
                                    <div class="text-danger" id="image-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<div class="category-side">
                                        <h3>Categories</h3>
                                        <div class="text-danger" id="category-err"></div>
										<ul>
                                            @if (isset($categories) && count($categories)>0)
                                            @foreach ($categories as $category)
                                            <li>
                                                <label>
                                                    <input type="checkbox" class="category" name="category[]" value="{{ $category->id }}" @if (!empty($postcategoryids) && in_array($category->id,$postcategoryids))
                                                        checked
                                                    @endif> {{ $category->name }}
                                                </label>
                                            </li>
                                            @endforeach
                                            @endif
										</ul>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<div class="category-side">
                                        <h3>Sub Categories</h3>
                                        <div class="text-danger" id="subcategory-err"></div>
										<ul id="subcategory">
                                            @if (isset($subcategories) && count($subcategories)>0)
                                                @foreach ($subcategories as $subcategory)
                                                <li>
                                                    <label>
                                                        <input type="checkbox" class="subcategory" name="subcategory[]" value="{{ $subcategory->id }}" @if (!empty($postsubcategoryids) && in_array($subcategory->id,$postsubcategoryids))
                                                        checked
                                                        @endif> {{ $subcategory->name }}
                                                    </label>
                                                </li>
                                                @endforeach
                                            @endif
										</ul>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<div class="category-side">
                                        <h3>Recommended Tags</h3>
                                        <div class="text-danger" id="tag-err"></div>
										<ul>
                                            @if (isset($tags) && count($tags)>0)
                                            @foreach ($tags as $tag)
                                            <li>
                                                <label>
                                                    <input type="checkbox" class="tag" name="tag[]" value="{{ $tag->id }}" @if (!empty($posttagids) && in_array($tag->id,$posttagids))
                                                        checked
                                                    @endif> {{ $tag->name }}
                                                </label>
                                            </li>
                                            @endforeach
                                            @endif
										</ul>
									</div>
								</div>
							</div>
                            <div class="form-group row">
								<div class="col-sm-12">
									<div class="category-side">
                                        <h3>Add New Tag</h3>
                                        <div class="text-danger" id="tag-err"></div>
                                        
                                            <input  type="text" id="hide_tags_input" placeholder="comma-separated tags" maxlength="100" class="form-control">
                                            
                                        
									</div>
								</div>
                                
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
    </form>
	</div>
</section>
<div class="modal fade" id="view-existing-gallery" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Search Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> 
      <div class="modal-body">
          <input type="text" name="search" id="search" class="form-control" placeholder="search">
       <div class="existing-div">
           
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>-->
@include('admin.footer')
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
    $('#content').summernote();
    // CKEDITOR.replace('content');
    $(document).on('click','.update-post-btn',function(event){
        // for ( instance in CKEDITOR.instances ){
        //     CKEDITOR.instances[instance].updateElement();
        // }
        $('#title-err').html('');
        $('#slug-err').html('');
        $('#content-err').html('');
        $('#video-err').html('');
        $('#metatitle-err').html('');
        $('#metakeyword-err').html('');
        $('#metadescription-err').html('');
        $('#image-err').html('');
        $('#category-err').html('');
        $('#subcategory-err').html('');
        $('#tag-err').html('');
        let formData=new FormData();
        formData.append('title',$('#title').val());
        formData.append('slug',$('#slug').val());
        formData.append('content',$('#content').val());
        formData.append('video',$('#video').val());
        formData.append('metatitle',$('#metatitle').val());
        formData.append('metakeyword',$('#metakeyword').val());
        formData.append('metadescription',$('#metadescription').val());
        formData.append('hide_tags_input',$('#hide_tags_input').val());
        formData.append('category',$(".category:checked").map(function(){return $(this).val();}).toArray());
        formData.append('subcategory',$(".subcategory:checked").map(function(){return $(this).val();}).toArray());
        formData.append('tag',$(".tag:checked").map(function(){return $(this).val();}).toArray());
        if(typeof($('#image')[0].files[0])=='undefined'){
            formData.append('image','');
        } else {
            formData.append('image',$('#image')[0].files[0]);
        }
        formData.append('imagename',$('#imagename').val());
        let postid=$(this).attr('postid');
        $.ajax({
            url:`{{ URL::to('update-post/${postid}') }}`,
            type:'POST',
            processData: false,
            contentType: false,
            dataType:'json',
            data:formData,
            success:function(result){
                if(result.msgCode === '200') {
                    toastr.success(result.msgText);
                    window.location = "{{ URL::to('manage-post') }}";
                } else if (result.msgCode === '401') {
                    if(result.errors.title) {
                        $('#title-err').html(result.errors.title[0]);
                    }
                    if(result.errors.slug) {
                        $('#slug-err').html(result.errors.slug[0]);
                    }
                    if(result.errors.content) {
                        $('#content-err').html(result.errors.content[0]);
                    }
                    if(result.errors.video) {
                        $('#video-err').html(result.errors.video[0]);
                    }
                    if(result.errors.metatitle) {
                        $('#metatitle-err').html(result.errors.metatitle[0]);
                    }
                    if(result.errors.metadescription) {
                        $('#metadescription-err').html(result.errors.metadescription[0]);
                    }
                    if(result.errors.metakeyword) {
                        $('#metakeyword-err').html(result.errors.metakeyword[0]);
                    }
                    if(result.errors.image) {
                        $('#image-err').html(result.errors.image[0]);
                    }
                    if(result.errors.category) {
                        $('#category-err').html(result.errors.category[0]);
                    }
                    if(result.errors.subcategory) {
                        $('#subcategory-err').html(result.errors.subcategory[0]);
                    }
                    if(result.errors.tag) {
                        $('#tag-err').html(result.errors.tag[0]);
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

    $(document).on('click','.category',function(event){
        $("#subcategory").html('');
        let categories=$(".category:checked").map(function(){return $(this).val();}).toArray();
        $.ajax({
            url:"{{ URL::to('fetch-subcategory-by-category') }}",
            type:'POST',
            dataType:'json',
            data:{'categories':categories},
            success:function(result){
                if (result.msgCode=='200') {
                    $("#subcategory").html(result.html);
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

    $(document).on('click','.draft-update-post-btn',function(event){
        $('#title-err').html('');
        $('#slug-err').html('');
        $('#content-err').html('');
        $('#video-err').html('');
        $('#metatitle-err').html('');
        $('#metakeyword-err').html('');
        $('#metadescription-err').html('');
        $('#image-err').html('');
        $('#category-err').html('');
        $('#subcategory-err').html('');
        $('#tag-err').html('');
        // for ( instance in CKEDITOR.instances ){
        //     CKEDITOR.instances[instance].updateElement();
        // }
        let formData=new FormData();
        formData.append('title',$('#title').val());
        formData.append('slug',$('#slug').val());
        formData.append('content',$('#content').val());
        formData.append('video',$('#video').val());
        formData.append('metatitle',$('#metatitle').val());
        formData.append('metakeyword',$('#metakeyword').val());
        formData.append('metadescription',$('#metadescription').val());
        formData.append('hide_tags_input',$('#hide_tags_input').val());
        formData.append('category',$(".category:checked").map(function(){return $(this).val();}).toArray());
        formData.append('subcategory',$(".subcategory:checked").map(function(){return $(this).val();}).toArray());
        formData.append('tag',$(".tag:checked").map(function(){return $(this).val();}).toArray());
        if(typeof($('#image')[0].files[0])=='undefined'){
            formData.append('image','');
        } else {
            formData.append('image',$('#image')[0].files[0]);
        }
        formData.append('imagename',$('#imagename').val());
        let postid=$(this).attr('postid');
        $.ajax({
            url:`{{ URL::to('update-post-draft/${postid}') }}`,
            type:'POST',
            processData: false,
            contentType: false,
            dataType:'json',
            data:formData,
            success:function(result){
                if(result.msgCode === '200') {
                    toastr.success(result.msgText);
                    window.location = "{{ URL::to('manage-post') }}";
                } else if (result.msgCode === '401') {
                    if(result.errors.title) {
                        $('#title-err').html(result.errors.title[0]);
                    }
                    if(result.errors.slug) {
                        $('#slug-err').html(result.errors.slug[0]);
                    }
                    if(result.errors.content) {
                        $('#content-err').html(result.errors.content[0]);
                    }
                    if(result.errors.video) {
                        $('#video-err').html(result.errors.video[0]);
                    }
                    if(result.errors.metatitle) {
                        $('#metatitle-err').html(result.errors.metatitle[0]);
                    }
                    if(result.errors.metadescription) {
                        $('#metadescription-err').html(result.errors.metadescription[0]);
                    }
                    if(result.errors.metakeyword) {
                        $('#metakeyword-err').html(result.errors.metakeyword[0]);
                    }
                    if(result.errors.image) {
                        $('#image-err').html(result.errors.image[0]);
                    }
                    if(result.errors.category) {
                        $('#category-err').html(result.errors.category[0]);
                    }
                    if(result.errors.subcategory) {
                        $('#subcategory-err').html(result.errors.subcategory[0]);
                    }
                    if(result.errors.tag) {
                        $('#tag-err').html(result.errors.tag[0]);
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
    
    $(document).on('keyup','#search',function(event){
        let search=$(this).val();
        $.ajax({
            url:"{{ URL::to('search-image-by-tag') }}",
            type:'POST',
            dataType:'json',
            data:{search},
            success:function(result){
                if (result.msgCode=='200') {
                    $(".existing-div").html(result.html);
                } else {
                    toastr.error('error encountered '+result.msgText);
                }
                $("#loader").modal('hide');
            },
            error:function(error){
                toastr.error('error encountered '+error.statusText);
                $("#loader").modal('hide');
            }
        })
    });
    
    $(document).on('click','.imagename',function(event){
        $("#imagename").val($(this).attr("imagename"));
        toastr.success("Existing Image Selected");
        $("#view-existing-gallery").modal("hide");
        
    })

    });
//     function parse() {
//         var tag_input = document.getElementById("tags_input");
//         var tags = document.getElementById("tags");
//         //
//         var input_val = tag_input.value.trim();
//         var no_comma_val = input_val.replace(/,/g, "");
//         //
//         if (input_val.slice(-1) === "," && no_comma_val.length > 0) {
//             var new_tag = compile_tag(no_comma_val);
//             tags.appendChild(new_tag);

            
//             tag_input.value = "";

            
            
//         }
//         var hide_tag_input = document.getElementById("hide_tags_input");
//             var hide_tag_input_val = hide_tag_input.value.trim();
//             hide_tag_input_val = hide_tag_input_val + ',' + no_comma_val;
//             hide_tag_input.value = "";
//             hide_tag_input.value =hide_tag_input_val;
//     }

// function compile_tag(tag_content) {
// 	var tag = document.createElement("h5");
// 	//
// 	var text = document.createElement("span");
// 	text.setAttribute("class", "badge badge-success");
// 	text.innerHTML = tag_content;
// 	//
// 	var remove = document.createElement("i");
// 	remove.setAttribute("class", "fas fa-trash");
// 	remove.setAttribute("id", "remove");
// 	remove.onclick = function() {this.parentNode.remove();
    
//     };
// 	//
// 	tag.appendChild(remove);
// 	tag.appendChild(text);
// 	//
// 	return tag;
// }
</script>
