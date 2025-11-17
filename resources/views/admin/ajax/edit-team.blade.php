<div class="modal-dialog">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Edit Team</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <form method="POST" action="{{ route('manage-team.update',$team->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="label-control">Category</label>
                        <select name="category" class="text-control">
                            <option value="">Select Category</option>
                            @if (isset($categories) && count($categories)>0)
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @if ($category->id==$team->teamcategory_id)
                                selected
                            @endif>{{ $category->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="label-control">Name</label>
                        <input type="text" class="text-control" placeholder="Enter Name" name="name" id="name" value="{{ $team->name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label class="label-control">Designation</label>
                        <input type="text" class="text-control" placeholder="Enter Designation" name="designation" id="designation" value="{{ $team->designation }}">
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
                            <option value="{{ $state->id }}" @if ($state->id==$team->state_id)
                                selected
                            @endif>{{ $state->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="label-control">City</label>
                        <select name="city" id="city" class="text-control">
                            <option value="">Select City</option>
                            @if (isset($cities) && count($cities)>0)
                                @foreach ($cities as $city)
                                <option value="{{ $city->id }}" @if ($city->id==$team->city_id)
                                    selected
                                @endif>{{ $city->name }}</option>
                                @endforeach
                            @endif
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