<div class="modal-dialog">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Add Team Category</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <form method="POST" action="{{ route('manage-team-category.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="label-control">Name</label>
                        <input type="text" class="text-control" placeholder="Enter Name" name="name" id="name">
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