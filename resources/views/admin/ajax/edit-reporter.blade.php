<div class="modal-dialog">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Edit Reporter</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="label-control">Profile Picture</label>
                    @if (isset($user->image) && Storage::exists($user->image))
                    <img src="{{ URL::asset('storage/'.$user->image) }}" class="img-fluid">
                    @endif
                    <input type="file" class="text-control" name="image" id="image">
                    <div class="text-danger" id="image-err"></div>
                </div>
                <div class="col-sm-6">
                    <label class="label-control">Name</label>
                    <input type="text" class="text-control" placeholder="Enter Name" name="name" id="name" value="{{ $user->name }}">
                    <div class="text-danger" id="name-err"></div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="label-control">Email</label>
                    <input type="text" class="text-control" placeholder="Enter Email" name="email" id="email" value="{{ $user->email }}">
                    <div class="text-danger" id="email-err"></div>
                </div>
                <div class="col-sm-6">
                    <label class="label-control">Mobile No.</label>
                    <input type="text" class="text-control" placeholder="Enter Mobile No." name="contact" id="contact" value="{{ $user->contact }}">
                    <div class="text-danger" id="contact-err"></div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="label-control">State</label>
                    <select name="state" id="state" class="form-control">
                        <option value="">Select State</option>
                        @if (isset($states) && count($states)>0)
                        @foreach ($states as $state)
                        <option value="{{ $state->id }}" @if ($state->id==$user->state_id)
                            selected
                        @endif>{{ $state->name }}</option>
                        @endforeach
                        @endif
                    </select>
                    <div class="text-danger" id="state-err"></div>
                </div>
                <div class="col-sm-6">
                    <label class="label-control">City</label>
                    <select name="city" id="city" class="form-control">
                        <option value="">Select City</option>
                        @if (isset($cities) && count($cities)>0)
                        @foreach ($cities as $city)
                        <option value="{{ $city->id }}" @if ($city->id==$user->city_id)
                            selected
                        @endif>{{ $city->name }}</option>
                        @endforeach
                        @endif
                    </select>
                    <div class="text-danger" id="city-err"></div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="label-control">Address</label>
                    <input type="text" class="text-control" placeholder="Enter Address" name="address" id="address" value="{{ $user->address }}">
                    <div class="text-danger" id="address-err"></div>
                </div>
                <!--<div class="col-sm-6">-->
                <!--    <label class="label-control">CV</label>-->
                <!--    @if (isset($user->cv) && Storage::exists($user->cv))-->
                <!--    <a href="{{ URL::asset('storage/'.$user->cv) }}">view/download</a>-->
                <!--    @endif-->
                <!--    <input type="file" class="text-control" name="cv" id="cv">-->
                <!--    <div class="text-danger" id="cv-err"></div>-->
                <!--</div>-->
            </div>

            <div class="form-group row">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-dark update-reporter-btn" reporterid="{{ $user->id }}" type="button">Update Reporter</button>
                </div>
            </div>
        </div>
    </div>
</div>
