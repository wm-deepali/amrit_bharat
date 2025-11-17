@include('admin.header')
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<section class="breadcrumb-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="content-header">
          <h3 class="content-header-title">Content</h3>
          <button class="btn btn-dark btn-save add-team"><i class="fas fa-plus"></i> Add Team</button>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active">Manage Team</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="content-main-body">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="card-block">
              <div class="table-responsive">
                <table class="table table-bordered table-fitems" id="manage-team">
                  <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>Category</th>
                      <th>Name</th>
                      <th>Designation</th>
                      <th>Image</th>
                      <th>State</th>
                      <th>City</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($teams) && count($teams)>0)
                    @foreach ($teams as $team)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $team->teamcategory->name ?? 'NA' }}</td>
                        <td>{{ $team->name }}</td>
                        <td>{{ $team->designation }}</td>
                        <td>
                            @if (isset($team->image) && Storage::exists($team->image))
                                <img src="{{ URL::asset('storage/'.$team->image) }}" alt="{{ $team->name }}" height="50">
                            @endif
                        </td>
                        <td>{{ $team->state->name }}</td>
                        <td>{{ $team->city->name }}</td>
                        <td class="status">{{ $team->status }}</td>
                        <td>
                            <ul class="action">
                                <li><a href="javascript:void(0)" class="edit-team" teamid='{{ $team->id }}'><i class="fas fa-pencil-alt"></i></a></li>
                                <li><a href="{{ route('change-status-team',$team->id) }}" class="change-status-team"><i class="fas fa-times"></i></a></li>
                                <li>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('delete-team-form-{{ $team->id }}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="delete-team-form-{{ $team->id }}" action="{{ route('manage-team.destroy',$team->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('Delete')
                                    </form>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div class="modal" id="add-team">
</div>

<div class="modal" id="edit-team">
</div>
@include('admin.footer')
<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $( document ).ajaxStart(function() {
    $("#loader").modal('show');
    });
    $( document ). ajaxComplete(function() {
    $("#loader").modal('hide');
    });
    $(document).ready(function(){
    $(document).on("click",".add-team",function(event){
        $.ajax({
            url:"{{ URL::to('manage-team/create') }}",
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#add-team").html(result.html);
                    $("#add-team").modal('show');
                }
                else if(result.msgCode==='400')
                {
                    toastr.error('error encountered '+result.msgText);
                }
                $("#loader").modal('hide');
            },
            error:function(error){
                toastr.error('error encountered '+error.statusText);
                $("#loader").modal('hide');
            }
        });
    });


    $(document).on("click",".edit-team",function(event){
        let teamid = $(this).attr('teamid');
        $.ajax({
            url:`{{ URL::to('manage-team/${teamid}/edit') }}`,
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#edit-team").html(result.html);
                    $("#edit-team").modal('show');
                }
                else if(result.msgCode==='400')
                {
                    toastr.error('error encountered '+result.msgText);
                }
                $("#loader").modal('hide');
            },
            error:function(error){
                toastr.error('error encountered '+error.statusText);
                $("#loader").modal('hide');
            }
        });
    });

    $(document).on("change","#state",function(event){
        let stateid = $(this).val();
        $.ajax({
            url:"{{ URL::to('fetchcitybystateid') }}",
            type:"POST",
            dataType:"json",
            data:{"state_id":stateid},
            success:function(result) {
                if(result.msgCode==='200') {
                    $("#city").html(result.html);
                } else if(result.msgCode==='400') {
                    toastr.error('error encountered '+result.msgText);
                }
                $("#loader").modal('hide');
            },
            error:function(error) {
                toastr.error('error encountered '+error.statusText);
                $("#loader").modal('hide');
            }
        });
    });
    $("#manage-team").DataTable();
});
</script>
