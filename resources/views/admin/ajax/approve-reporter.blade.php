<div class="modal-dialog">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Approve Reporter</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
        <form id="approve-reporter-form">
            <div class="form-group row">
                <div class="col-sm-12 text-center">
                    <h1>Are you Sure ?</h1>
                    <h5>Approve This User</h5>
                </div>
                
                
            </div>
            <div class="form-group row">
                <div class="col-sm-12 text-center">
                    
                    <button class="btn btn-dark approve-reporter-btn" reporterid="{{ $user->id }}" type="button">Approve</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
