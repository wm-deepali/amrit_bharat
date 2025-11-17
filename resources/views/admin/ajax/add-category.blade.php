<div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Category</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form action="" id="add-category-form">
          <div class="form-group row">
            <div class="col-sm-6">
              <label class="label-control">Category</label>
              <input type="text" class="text-control" placeholder="Enter Category Name" name="name" id="name">
              <div class="text-danger" id="name-err"></div>
            </div>
			  <div class="col-sm-6">
              <label class="label-control">Settings</label><br>
			  <label><input type="checkbox" name="hassubcategory" value="yes" id="hassubcategory"> Has Sub Category</label><br>
			  <label><input type="checkbox" name="showonheader" value="yes" id="showonheader"> Show on Header</label><br>
			  <label><input type="checkbox" name="showonfooter" value="yes" id="showonfooter"> Show on Footer</label>
            </div>
          </div>
          <div class="form-group row">
			<div class="col-sm-6">
              <label class="label-control">Slug</label>
              <input type="text" class="text-control" placeholder="Enter URL Slug" name="slug" id="slug">
              <div class="text-danger" id="slug-err"></div>
            </div>
            <div class="col-sm-6">
              <label class="label-control">Meta Title</label>
              <input type="text" class="text-control" placeholder="Enter Meta Title" name="metatitle" id="metatitle">
              <div class="text-danger" id="metatitle-err"></div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-12">
              <label class="label-control">Meta Description</label>
              <input type="text" class="text-control" placeholder="Enter Meta Description" name="metadescription" id="metadescription">
              <div class="text-danger" id="metadescription-err"></div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-sm-12">
              <label class="label-control">Meta Keywords</label>
              <input type="text" class="text-control" placeholder="Enter Meta Keywords" name="metakeyword" id="metakeyword">
              <div class="text-danger" id="metakeyword-err"></div>
            </div>
          </div>
          <div class="form-action row">
            <div class="col-sm-12 text-center">
              <button class="btn btn-dark btn-save add-category-btn" type="button">Add Category</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
