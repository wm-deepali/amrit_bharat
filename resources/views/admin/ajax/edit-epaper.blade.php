<div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Epaper</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="POST" action="{{ route('manage-epaper.update',$epaper->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
          <div class="form-group row">
            <div class="col-sm-6">
              <label class="label-control">Title</label>
              <input type="text" class="text-control" placeholder="Enter Title" name="title" id="title" value="{{ $epaper->title }}">
            </div>
			  <div class="col-sm-6">
              <label class="label-control">Epaper</label><br>
              <input type="file" class="text-control" name="file" id="file">
            </div>
          </div>
          <div class="form-group row">
			<div class="col-sm-6">
              <label class="label-control">Date</label>
              <input type="date" class="text-control" placeholder="Enter Date" name="date" id="date" value="{{ $epaper->date }}">
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
