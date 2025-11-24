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
            <li class="breadcrumb-item active">Manage App Comments</li>
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
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @php
                        $i=1;
                        @endphp
                        @if (isset($comments) && count($comments)>0)
                        @foreach ($comments as $comment)
                        @if(isset($comment->post) && !empty($comment->post) && isset($comment->user) && !empty($comment->user))
                        
                        <tr>
                          <td>{{ $i }}</td>
                          <td>{{ $comment->post->postnumber ?? '' }}</td>
                          
                          <td>
                          @if(isset($comment->post))
                            <a href="{{ route('postdetail',[$comment->post->categories[0]->category->slug,$comment->post->slug]) }}" target="_blank">{{ $comment->post->title }}</a>
                            @endif
                          </td>
                          <td><a href="javascript:void(0)" class="cu-info" comment_id="{{ $comment->id }}">Comment/User Info</a></td>
                          <td>{{ $comment->status }}</td>
                          <td>
                            <ul class="action">
                              @if ($comment->status=='Block')
                              <li><a href="javascript:void(0)" comment_id="{{ $comment->id }}" class="approve-comment" title="Approve Comment"><i class="fas fa-check"></i></a></li> 
                              @else
                              <li><a href="javascript:void(0)" comment_id="{{ $comment->id }}" class="approve-comment" title="Block Comment"><i class="fas fa-times"></i></a></li> 
                              @endif
                              <li><a href="javascript:void(0)" comment_id="{{ $comment->id }}" class="delete-comment"><i class="fas fa-trash"></i></a></li>
                            </ul>
                          </td>
                        </tr>
                        @php
                        $i++;
                        @endphp
                        @endif
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
          url:`{{ URL::to('view-app-comment/${comment_id}') }}`,
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

  

  $(document).on("click",".delete-comment",function(event){
      let comment_id = $(this).attr('comment_id');
      $.ajax({
          url:`{{ URL::to('delete-app-comment/${comment_id}') }}`,
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
          url:`{{ URL::to('approve-app-comment/${comment_id}') }}`,
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