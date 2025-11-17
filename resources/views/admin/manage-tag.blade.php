@include('admin.header')
<section class="breadcrumb-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="content-header">
          <h3 class="content-header-title">Master</h3>
          <button class="btn btn-dark btn-save add-tag"><i class="fas fa-plus"></i> Add Tag</button>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item active">Manage Tags</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="content-main-body">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="card-block">
              <div class="table-responsive">
                <table class="table table-bordered table-fitems">
                  <thead>
                    <tr>
                      <th>Sr. No.</th>
                      <th>Tag Name</th>
                      <th>URL Slug</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($tags) && count($tags)>0)
                    @foreach ($tags as $tag)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tag->name }}</td>
                        <td>{{ $tag->slug }}</td>
                        <td class="status">{{ $tag->status }}</td>
                        <td>
                            <ul class="action">
                                <li><a href="javascript:void(0)" class="edit-tag" tagid="{{ $tag->id }}"><i class="fas fa-pencil-alt"></i></a></li>
                                <li><a href="javascript:void(0)" class="change-status-tag" tagid="{{ $tag->id }}"><i class="fas fa-times"></i></a></li>
                                <li><a href="javascript:void(0)" class="delete-tag" tagid="{{ $tag->id }}"><i class="fas fa-trash"></i></a></li>
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal" id="add-tag">
</div>
<div class="modal" id="edit-tag">
</div>
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
    $(document).on("click",".add-tag",function(event){
        $.ajax({
            url:"{{ URL::to('manage-tag/create') }}",
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#add-tag").html(result.html);
                    $("#add-tag").modal('show');
                }
                else if(result.msgCode==='400')
                {
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

    $(document).on('click','.add-tag-btn',function(event){
        $('#name-err').html('');
        $('#slug-err').html('');
        $('#metatitle-err').html('');
        $('#metadescription-err').html('');
        $('#metakeyword-err').html('');
        $.ajax({
            url:"{{ URL::to('manage-tag') }}",
            type:'POST',
            dataType:'json',
            data:$('#add-tag-form').serialize(),
            success:function(result){
                if(result.msgCode === '200') {
                    toastr.success(result.msgText);
                    location.reload();
                } else if (result.msgCode === '401') {
                    if(result.errors.name) {
                        $('#name-err').html(result.errors.name[0]);
                    }
                    slugs=Object.keys(result.errors).filter(key=>key.match(/slug/));
                    slugs.forEach(slug => {
                        $('#slug-err').append(result.errors["slug."+slug.slice(5)]);
                    });
                    if(result.errors.metatitle) {
                        $('#metatitle-err').html(result.errors.metatitle[0]);
                    }
                    if(result.errors.metadescription) {
                        $('#metadescription-err').html(result.errors.metadescription[0]);
                    }
                    if(result.errors.metakeyword) {
                        $('#metakeyword-err').html(result.errors.metakeyword[0]);
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

    $(document).on("click",".edit-tag",function(event){
        let tagid = $(this).attr('tagid');
        $.ajax({
            url:`{{ URL::to('manage-tag/${tagid}/edit') }}`,
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#edit-tag").html(result.html);
                    $("#edit-tag").modal('show');
                }
                else if(result.msgCode==='400')
                {
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

    $(document).on('click','.edit-tag-btn',function(event){
        $('#name-err').html('');
        $('#slug-err').html('');
        $('#metatitle-err').html('');
        $('#metadescription-err').html('');
        $('#metakeyword-err').html('');
        let tagid = $('#edit-tag-form').attr('tagid');
        $.ajax({
            url:`{{ URL::to('manage-tag/${tagid}') }}`,
            type:'PUT',
            dataType:'json',
            data:$('#edit-tag-form').serialize(),
            success:function(result){
                if(result.msgCode === '200') {
                    toastr.success(result.msgText);
                    location.reload();
                } else if (result.msgCode === '401') {
                    if(result.errors.name) {
                        $('#name-err').html(result.errors.name[0]);
                    }
                    if(result.errors.slug) {
                        $('#slug-err').html(result.errors.slug[0]);
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

    $(document).on("click",".delete-tag",function(event){
        let tagid = $(this).attr('tagid');
        $.ajax({
            url:`{{ URL::to('manage-tag/${tagid}') }}`,
            type:"DELETE",
            dataType:"json",
            context:this,
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $(this).closest('tr').remove();
                    toastr.success(result.msgText);
                }
                else if(result.msgCode==='400')
                {
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

    $(document).on('click','.change-status-tag',function(event){
        let tagid = $(this).attr('tagid');
        let status= $(this).closest('tr').find('.status').html();
        $.ajax({
            url:`{{ URL::to('change-tag-status/${tagid}/${status}') }}`,
            type:'PUT',
            dataType:'json',
            context:this,
            success:function(result){
                if(result.msgCode==='200')
                {
                    $(this).closest('tr').find('.status').html(result.status);
                    toastr.success(result.msgText);
                }
                else if(result.msgCode==='400')
                {
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

    });
</script>
