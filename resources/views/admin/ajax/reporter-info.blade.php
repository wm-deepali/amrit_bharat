<div class="modal-dialog">
    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Reporter Info</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="form-action row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <td>{{ $user->name }}</td>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Mobile Number</th>
                                <td>{{ $user->contact }}</td>
                                <th>Approved Posts</th>
                                <td>{{ $user->approvedposts->count() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
