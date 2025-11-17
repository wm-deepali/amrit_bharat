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
            <li class="breadcrumb-item active">Manage Help Requests</li>
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
                <table class="table table-bordered table-fitems" id="manage-requests">
                  <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>User Name</th>
                      <th>Subject</th>
                      <th>Detail</th>
                      <th>File</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($requests) && count($requests)>0)
                    @foreach ($requests as $request)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ (isset($request->user) && !empty($request->user)) ? $request->user->name  : ''}}</td>
                        <td>{{ $request->subject }}</td>
                        <td>{{ $request->details }}</td>
                        <td>
                            @if (isset($request->file) && $request->file !='')
                            <a href="{{ URL::to('/').'/storage/app/public/help/'.$request->file }}" download>
                            <img src="{{ URL::to('/').'/storage/app/public/help/'.$request->file }}" height="100px" width="100px">
                            </a>
                                
                            @endif
                        </td>
                        <td>
                            <ul class="action">
                               
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('delete-close-account-form-{{ $request->id }}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="delete-close-account-form-{{ $request->id }}" action="{{ route('manage-help-request.destroy',$request->id) }}" method="POST" style="display: none;">
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
<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    
    $(document).ready(function(){

    $("#manage-requests").DataTable();
});
</script>
