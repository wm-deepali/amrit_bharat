<style>
    .extra
    {
        margin-bottom: 20px;border-bottom: 1px solid #eee;
    }
</style>
<div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <div class="form-group row">
		  	<div class="col-sm-12">
				<div class="table-responsive">
					<table class="table table-bordered">
						<tr>
							<th>Name</th>
							<td>{{ $comment->user->name }}</td>
						</tr>
						<tr>
							<th>Email</th>
							<td>{{ $comment->user->email }}</td>
						</tr>
						<tr>
							<th>Mobile No.</th>
							<td>{{ $comment->user->contact }}</td>
						</tr>
						<tr>
							<th>Comments</th>
							<td>{{ $comment->comment }}</td>
						</tr>
						
						
					</table>
				</div>
				<br/>
				<h4 class="modal-title extra" >Replies</h4>
                <div class="table-responsive">
                <table class="table table-bordered table-fitems" id="manage-reply">
                  <thead>
                    <tr>
                      <th>Sr. No.</th>
                      <th>User Name</th>
                      <th>Reply</th>
                      <th>Total Likes</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      @php
                        $i=1;
                        @endphp
                        @if (isset($replies) && count($replies)>0)
                        @foreach ($replies as $reply)
                        @if(isset($reply->user) && !empty($reply->user))
                        
                        <tr>
                          <td>{{ $i }}</td>
                          <td>{{ $reply->user->name ?? '' }}</td>
                          
                          <td>{{ $reply->reply ?? '' }}</td>
                          <td>{{ $reply->total_likes ?? 0 }}</td>

                          <td>{{ $reply->status  }}</td>
                          <td>
                            <ul class="action">
                              @if ($reply->status=='Block')
                              <li style="list-style: none;"><a href="javascript:void(0)" reply_id="{{ $reply->id }}" class="approve-reply" title="Approve Reply"><i class="fas fa-check"></i></a></li> 
                              @else
                              <li style="list-style: none;"><a href="javascript:void(0)" reply_id="{{ $reply->id }}" class="approve-reply" title="Block Reply"><i class="fas fa-times"></i></a></li> 
                              @endif
                              <li style="list-style: none;"><a href="javascript:void(0)" reply_id="{{ $reply->id }}" class="delete-reply"><i class="fas fa-trash"></i></a></li>
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
  
  <script>
  $.ajaxSetup({
  headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
  });
 
  $(document).ready(function(){
  

  $(document).on("click",".delete-reply",function(event){
      let reply_id = $(this).attr('reply_id');
      $.ajax({
          url:`{{ URL::to('delete-reply/${reply_id}') }}`,
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
$(document).on('click','.approve-reply',function(event){
      let reply_id = $(this).attr('reply_id');
      $.ajax({
          url:`{{ URL::to('approve-app-reply/${reply_id}') }}`,
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
  
    $('#manage-reply').DataTable();
  });
  </script>