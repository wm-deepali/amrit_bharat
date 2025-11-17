<div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Close Account Reasons</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="POST" action="{{ route('manage-reasons.store') }}" enctype="multipart/form-data">
            @csrf
          
          <div class="form-group row">
			      <div class="col-sm-12">
              <label class="label-control">Reason</label>
              <textarea class="text-control" placeholder="Enter Reason" name="reason" id="reason" required></textarea>
            </div>
          </div>
          <div class="form-group row">
			      <div class="col-sm-6">
              <label class="label-control">Status</label>
              <select name="status" class="text-control">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
          </div>
          <div class="form-action row">
            <div class="col-sm-12 text-center">
              <button class="btn btn-dark btn-save" type="submit">Add</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
