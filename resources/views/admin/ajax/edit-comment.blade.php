<div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Update Comment</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form id="update-comment-form" comment_id="{{ $comment->id }}">
        <div class="form-group row">
		  	<div class="col-sm-12">
				<label class="label-control">Comment</label>
        <textarea class="text-control" placeholder="Enter Comments" rows="3" cols="6" name="comment">{{ $comment->content }}</textarea>
        <div class="text-danger" id="comment-err"></div>
			</div>
		  </div>
		  <div class="form-group row">
		  	<div class="col-sm-12 text-center">
				<button class="btn btn-dark update-comment-btn" type="button">Update Comment</button>
			</div>
          </div>
        </form>
      </div>
    </div>
  </div>