<div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Site Introduction</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="POST" action="{{ route('manage-site-intro.store') }}" enctype="multipart/form-data">
            @csrf
          <div class="form-group row">
            <div class="col-sm-6">
              <label class="label-control">Heading</label>
              <input type="text" class="text-control" placeholder="Enter Heading" name="heading" id="heading" required>
            </div>
			  <div class="col-sm-6">
              <label class="label-control">Image</label><br>
              <input type="file" class="text-control" name="image" id="image" required>
            </div>
          </div>
          <div class="form-group row">
			<div class="col-sm-6">
              <label class="label-control">Short Description</label>
              <textarea class="text-control" placeholder="Enter Description" name="short_description" id="short_description" required></textarea>
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
