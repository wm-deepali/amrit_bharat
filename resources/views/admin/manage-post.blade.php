@include('admin.header')
<section class="breadcrumb-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="content-header">
          <h3 class="content-header-title">Master</h3>
          <button class="btn btn-dark btn-save" onclick="window.location.href='{{ route('manage-post.create') }}'"><i class="fas fa-plus"></i> Add Post</button>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Posts</li>
            <li class="breadcrumb-item active">Manage Posts</li>
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
              <div class="row">
                <div class="col-sm-12 filter-title">
                  <h3>Filter By <button type="button" class="btn btn-primary pull-right filterbtn"><i class="fas fa-search"></i> Filter Now</button></h3>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label class="label-control">Select Category: <b class="text-danger">*</b></label>
                    <div class="input-group">
                      <select class="form-control" id="category">
                        <option value="">All Category</option>
                        @if(isset($categories) && count($categories)>0)
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name ?? '' }}</option>
                        @endforeach
                        @endif
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                     <label class="label-control">Date Range</label>
                     <div class="row">
                       <div class="col-sm-6"><input type="date" class="form-control" name="datefrom" id="datefrom"></div>
                       <div class="col-sm-6"><input type="date" class="form-control" name="dateto" id="dateto"></div>
                     </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                     <label class="label-control">Status</label>
                     <select class="form-control" id="status" name="status">
                       <option value="">Select Status</option>
                       <option value="published">Published</option>
                       <option value="draft">Draft</option>
                       <option value="pending">Pending</option>
                     </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                     <label class="label-control">Added By</label>
                     <select class="form-control" name="user" id="user">
                       <option value="">Select Publisher</option>
                       @if(isset($reporters) && count($reporters)>0)
                       @foreach($reporters as $reporter)
                       <option value="{{ $reporter->id }}">{{ $reporter->name }}({{ $reporter->user_number }})</option>
                       @endforeach
                       @endif
                     </select>
                  </div>
                </div>
              </div>
              <div class="col-md-12" id="postcontainer">
                @include('admin/ajax/manage-post')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal" id="reporter-info">
</div>
<div class="modal fade" id="preview-post" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
  $( document ).ajaxComplete(function() {
    $("#loader").modal('hide');
  });
  $(document).ready(function(){
    $(document).on("click",".delete-post",function(event){
      let postid = $(this).attr('postid');
      $.ajax({
        url:`{{ URL::to('manage-post/${postid}') }}`,
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

    $(document).on('click','.reporter-info',function(event){
      let userid = $(this).attr('userid');
      $.ajax({
        url:`{{ URL::to('view-user-info/${userid}') }}`,
        type:'GET',
        dataType:'json',
        success:function(result){
          if (result.msgCode=='200') {
            $('#reporter-info').html(result.html);
            $('#reporter-info').modal('show');
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
    })

    $(document).on('click','.change-status-post',function(event){
      let postid = $(this).attr('postid');
      let status= $(this).closest('tr').find('.status').html();
      $.ajax({
        url:`{{ URL::to('change-post-status/${postid}/${status}') }}`,
        type:'PUT',
        dataType:'json',
        context:this,
        success:function(result){
          if(result.msgCode==='200')
          {
            if(result.status=='pending'){
              if(result.role=='admin'){
                $(result.li).insertAfter($(this).closest('li'));
              }
            } else {
              $(this).closest('ul').find('.publish-post').closest('li').remove();
            }
            $(this).closest('tr').find('.url').html('-');
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

    $(document).on('click','.publish-post',function(event){
      let postid = $(this).attr('postid');
      $.ajax({
        url:`{{ URL::to('publish-post/${postid}') }}`,
        type:'PUT',
        dataType:'json',
        context:this,
        success:function(result){
          if(result.msgCode==='200')
          {
            $(this).closest('tr').find('.url').html(result.url);
            $(this).closest('tr').find('.status').html(result.status);
            $(this).closest('tr').find('.approvedby').html(result.approvedby);
            $(this).closest('li').remove();
            toastr.success(result.msgText);
          }
          else if(result.msgCode==='400')
          {
            toastr.error('error encountered '+result.msgText);
          }
          $("#loader").modal('hide');
        },
        error:function(error) {
          toastr.error('error encountered '+error.statusText);
          $("#loader").modal('hide');
        }
      });
    });
    
    $(document).on('click','.preview-post',function(event){
      let postid = $(this).attr('postid');
      $.ajax({
        url:`{{ URL::to('manage-post/${postid}') }}`,
        type:'GET',
        dataType:'json',
        success:function(result){
          if (result.msgCode=='200') {
            $('#preview-post').html(result.html);
            $('#preview-post').modal('show');
          } else {
            toastr.error('error encountered '+result.msgText);
          }
          $("#loader").modal('hide');
        },
        error:function(error) {
          toastr.error('error encountered '+error.statusText);
          $("#loader").modal('hide');
        }
      })
    })
    
    $(document).on('click','.filterbtn',function(event){
      let page = 1;
      let category=$('#category').val();
      let datefrom=$('#datefrom').val();
      let dateto=$('#dateto').val();
      let status=$('#status').val();
      let user=$('#user').val();
      getData(page,category,datefrom,dateto,status,user);
    })
    $(document).on('click', '.pagination a',function(event)
    {
      event.preventDefault();
      $('li').removeClass('active');
      $(this).parent('li').addClass('active');
      let page=$(this).attr('href').split('page=')[1];
      let category= $('#category').val();
      let datefrom= $('#datefrom').val();
      let dateto= $('#dateto').val();
      let status= $('#status').val();
      let user= $('#user').val();
      getData(page,category,datefrom,dateto,status,user);
    });
    function getData(page,category,datefrom,dateto,status,user){
      $.ajax({
        url: '?page=' + page + '&category=' + category + '&datefrom=' + datefrom + '&dateto=' + dateto + '&status=' + status + '&user=' + user,
        type: "get",
        datatype: "html"
      }).done(function(data){
        $("#postcontainer").empty().html(data);
      }).fail(function(jqXHR, ajaxOptions, thrownError){
        alert('No response from server');
      });
    }
    
  });
</script>
