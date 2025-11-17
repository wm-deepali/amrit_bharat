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
          <h3 class="content-header-title">Master</h3>
          <button class="btn btn-dark btn-save add-reason"><i class="fas fa-plus"></i> Add Reason</button>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item active">Manage Reasons For Close Account </li>
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
                <table class="table table-bordered table-fitems" id="manage-reason">
                  <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>Reason</th>
                      <th>status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($reasons) && count($reasons)>0)
                    @foreach ($reasons as $reason)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $reason->reason }}</td>
                        
                        <td class="{{$reason->status == 'Inactive' ? 'text-danger' : 'text-success'}}">{{ $reason->status }}</td>
                        <td>
                            <ul class="action">
                                <li><a href="javascript:void(0)" class="edit-reason" reasonid='{{ $reason->id }}'><i class="fas fa-pencil-alt"></i></a></li>
                                <li>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('delete-reason-form-{{ $reason->id }}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="delete-reason-form-{{ $reason->id }}" action="{{ route('manage-reasons.destroy',$reason->id) }}" method="POST" style="display: none;">
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
<div class="modal" id="add-reason">
</div>

<div class="modal" id="edit-reason">
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
    $(document).on("click",".add-reason",function(event){
        $.ajax({
            url:"{{ URL::to('manage-reasons/create') }}",
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#add-reason").html(result.html);
                    $("#add-reason").modal('show');
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


    $(document).on("click",".edit-reason",function(event){
        let reasonid = $(this).attr('reasonid');
        $.ajax({
            url:`{{ URL::to('manage-reasons/${reasonid}/edit') }}`,
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#edit-reason").html(result.html);
                    $("#edit-reason").modal('show');
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
    $("#manage-reason").DataTable();
});
</script>
