<div class="modal-dialog">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Add Team</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <form method="POST" action="{{ route('manage-team.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="label-control">Category</label>
                        <select name="category" class="text-control">
                            <option value="">Select Category</option>
                            @if (isset($categories) && count($categories)>0)
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="label-control">Name</label>
                        <input type="text" class="text-control" placeholder="Enter Name" name="name" id="name">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="label-control">Designation</label>
                        <input type="text" class="text-control" placeholder="Enter Designation" name="designation" id="designation">
                    </div>
                    <div class="col-sm-6">
                        <label class="label-control">Image</label><br>
                        <input type="file" class="text-control" name="image" id="image">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="label-control">State</label>
                        <select name="state" id="state" class="text-control">
                            <option value="">Select State</option>
                            @if (isset($states) && count($states)>0)
                            @foreach ($states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="label-control">City</label>
                        <select name="city" id="city" class="text-control">
                            <option value="">Select City</option>
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