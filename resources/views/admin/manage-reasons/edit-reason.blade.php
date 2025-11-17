<div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Close Account Reasons</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="POST" action="{{ route('manage-reasons.update',$reason->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

          <div class="form-group row">
			      <div class="col-sm-12">
              <label class="label-control">Reason</label>
                <textarea class="text-control" placeholder="Enter Reason" name="reason" id="reason" required>{{ $reason->reason }}</textarea>
            </div>
          </div>
          <div class="form-group row">
			      <div class="col-sm-6">
              <label class="label-control">Status</label>
              <select name="status" class="text-control">
                <option value="Active" {{$reason->reason == 'Active' ? 'selected' : ''}}>Active</option>
                <option value="Inactive"  {{$reason->reason == 'Inactive' ? 'selected' : ''}}>Inactive</option>
              </select>
            </div>
          </div>
          <div class="form-action row">
            <div class="col-sm-12 text-center">
              <button class="btn btn-dark btn-save" type="submit">Update</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
