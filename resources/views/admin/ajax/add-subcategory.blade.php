<div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Subcategory</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form id="add-subcategory-form">
          <div class="form-group row">
            <div class="col-sm-6">
              <label class="label-control">Category</label>
              <select class="text-control" name="category">
                <option value="">Select Category</option>
                @if (isset($categories) && count($categories)>0)
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
                @endif
              </select>
              <div class="text-danger" id="category-err"></div>
            </div>
			  <div class="col-sm-6">
              <label class="label-control">Sub Category</label>
              <input type="text" class="text-control" placeholder="Enter Sub Category Name" name="name">
              <div class="text-danger" id="name-err"></div>
            </div>
          </div>
          <div class="form-group row">
			<div class="col-sm-6">
              <label class="label-control">Slug</label>
              <input type="text" class="text-control" placeholder="Enter Slub URL" name="slug">
              <div class="text-danger" id="slug-err"></div>
            </div>
            <div class="col-sm-6">
              <label class="label-control">Meta Title</label>
              <input type="text" class="text-control" placeholder="Enter Meta Title" name="metatitle">
              <div class="text-danger" id="metatitle-err"></div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-12">
              <label class="label-control">Meta Description</label>
              <input type="text" class="text-control" placeholder="Enter Meta Description" name="metadescription">
              <div class="text-danger" id="metadescription-err"></div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-12">
              <label class="label-control">Meta Keywords</label>
              <input type="text" class="text-control" placeholder="Enter Meta Keywords" name="metakeyword">
              <div class="text-danger" id="metakeyword-err"></div>
            </div>
          </div>
          <div class="form-action row">
            <div class="col-sm-12 text-center">
              <button class="btn btn-dark btn-save add-subcategory-btn" type="button">Add Subcategory</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
