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
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active">Manage Close Account Requests</li>
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
                <table class="table table-bordered table-fitems" id="manage-request">
                  <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>User Name</th>
                      <th>Reason</th>
                      <th>Detail</th>
                      <th>File</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($requests) && count($requests)>0)
                    @foreach ($requests as $request)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ (isset($request->user) && !empty($request->user)) ? $request->user->name  : ''}}</td>
                        <td>{{ (isset($request->reason) && !empty($request->reason)) ? $request->reason->reason : ''}}</td>
                        <td>{{ $request->detail }}</td>
                        <td>
                        @if (isset($request->file) && $request->file !='')
                            <a href="{{ URL::to('/').'/storage/app/public/help/'.$request->file }}" download>
                            <img src="{{ URL::to('/').'/storage/app/public/help/'.$request->file }}" height="100px" width="100px">
                            </a>
                                
                            @endif
                        </td>
                        <td><input data-id="{{$request->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="secondary" data-toggle="toggle" data-on="Approved" data-off="Pending" {{ $request->status == 'Approved' ? 'checked' : '' }}></td>
                        <td>
                            <ul class="action">
                               
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('delete-close-account-form-{{ $request->id }}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="delete-close-account-form-{{ $request->id }}" action="{{ route('manage-close-account-request.destroy',$request->id) }}" method="POST" style="display: none;">
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

@include('admin.footer')
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">  
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 'Approved' : 'Pending'; 
        var id = $(this).data('id'); 
         
        $.ajax({
            url:"{{ URL::to('change-status') }}",
            type:"get",
            dataType:"json",
            data: {'status': status, 'id': id},
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                  toastr.success('Success: '+result.msgText);
                }
                else if(result.msgCode==='400')
                {
                    toastr.error('error encountered '+result.msgText);
                }
            },
            error:function(error){
                toastr.error('error encountered '+error.statusText);
            }
        });
    })
  })
    $(document).ready(function(){

    $("#manage-request").DataTable();
});
</script>
