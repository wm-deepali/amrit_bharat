@include('admin.header')
<section class="breadcrumb-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="content-header">
          <h3 class="content-header-title">Master</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Posts</li>
            <li class="breadcrumb-item active">Manage Comments</li>
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
                <table class="table table-bordered table-fitems" id="manage-comment">
                  <thead>
                    <tr>
                      <th>Sr. No.</th>
                      <th>Post ID</th>
                      <th>Link</th>
                      <th>Details</th>
                      <th>Type</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($comments) && count($comments)>0)
                        @foreach ($comments as $comment)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $comment->post->postnumber ?? '' }}</td>
                          
                          <td>
                          @if(isset($comment->post))
                            <a href="{{ route('postdetail',[$comment->post->categories[0]->category->slug,$comment->post->slug]) }}" target="_blank">{{ $comment->post->title }}</a>
                            @endif
                          </td>

                          <td><a href="javascript:void(0)" class="cu-info" comment_id="{{ $comment->id }}">Comment/User Info</a></td>
                          <td>{{ $comment->type }}</td>
                          <td>{{ $comment->status }}</td>
                          <td>
                            <ul class="action">
                              <li><a href="javascript:void(0)" class="edit-comment" comment_id="{{ $comment->id }}"><i class="fas fa-pencil-alt"></i></a></li>
                              @if ($comment->status=='Block')
                              <li><a href="javascript:void(0)" comment_id="{{ $comment->id }}" class="approve-comment" title="Approve Comment"><i class="fas fa-check"></i></a></li> 
                              @else
                              <li><a href="javascript:void(0)" comment_id="{{ $comment->id }}" class="approve-comment" title="Block Comment"><i class="fas fa-times"></i></a></li> 
                              @endif
                              <li><a href="javascript:void(0)" comment_id="{{ $comment->id }}" class="delete-comment"><i class="fas fa-trash"></i></a></li>
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

<div class="modal" id="cu-info">
</div>

<div class="modal" id="edit-comment">
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
  $(document).on("click",".cu-info",function(event){
    let comment_id=$(this).attr('comment_id');
      $.ajax({
          url:`{{ URL::to('manage-comment/${comment_id}') }}`,
          type:"get",
          dataType:"json",
          success:function(result)
          {
              if(result.msgCode==='200')
              {
                  $("#cu-info").html(result.html);
                  $("#cu-info").modal('show');
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

  $(document).on("click",".edit-comment",function(event){
      let comment_id = $(this).attr('comment_id');
      $.ajax({
          url:`{{ URL::to('manage-comment/${comment_id}/edit') }}`,
          type:"get",
          dataType:"json",
          success:function(result)
          {
              if(result.msgCode==='200')
              {
                  $("#edit-comment").html(result.html);
                  $("#edit-comment").modal('show');
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

  $(document).on('click','.update-comment-btn',function(event){
      $('#comment-err').html('');
      let comment_id = $('#update-comment-form').attr('comment_id');
      $.ajax({
          url:`{{ URL::to('manage-comment/${comment_id}') }}`,
          type:'PUT',
          dataType:'json',
          data:$('#update-comment-form').serialize(),
          success:function(result){
              if(result.msgCode === '200') {
                  toastr.success(result.msgText);
                  location.reload();
              } else if (result.msgCode === '401') {
                  if(result.errors.comment) {
                      $('#comment-err').html(result.errors.comment[0]);
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

  $(document).on("click",".delete-comment",function(event){
      let comment_id = $(this).attr('comment_id');
      $.ajax({
          url:`{{ URL::to('manage-comment/${comment_id}') }}`,
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

  $(document).on('click','.approve-comment',function(event){
      let comment_id = $(this).attr('comment_id');
      $.ajax({
          url:`{{ URL::to('approve-comment/${comment_id}') }}`,
          type:'PUT',
          dataType:'json',
          context:this,
          success:function(result){
              if(result.msgCode==='200')
              {
                  toastr.success(result.msgText);
                  location.reload();
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
      })
  })
    $('#manage-comment').DataTable();
  });
  </script>