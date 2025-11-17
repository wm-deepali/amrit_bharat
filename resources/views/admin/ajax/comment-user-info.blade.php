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
							<td>{{ $comment->name }}</td>
						</tr>
						<tr>
							<th>Email</th>
							<td>{{ $comment->email }}</td>
						</tr>
						<tr>
							<th>Mobile No.</th>
							<td>{{ $comment->contact }}</td>
						</tr>
						<tr>
							<th>Comments</th>
							<td>{{ $comment->content }}</td>
						</tr>
					</table>
				</div>
			</div>
		  </div>
      </div>
    </div>
  </div>