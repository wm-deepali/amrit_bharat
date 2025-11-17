<div class="modal-dialog">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Change Password</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
        <form id="change-password-form">
            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="label-control">New Password</label>
                    <input type="text" class="text-control" placeholder="Enter New Password" name="password">
                    <div class="text-danger" id="password-err"></div>
                </div>
                <div class="col-sm-6">
                    <label class="label-control">Re-enter Password</label>
                    <input type="text" class="text-control" placeholder="Enter Re-enter Password" name="password_confirmation">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-dark change-password-btn" reporterid="{{ $user->id }}" type="button">Change Password</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
