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
          <button class="btn btn-dark btn-save add-epaper"><i class="fas fa-plus"></i> Add Epaper</button>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active">Manage E-paper</li>
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
                <table class="table table-bordered table-fitems" id="manage-epaper">
                  <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>Title</th>
                      <th>File</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($epapers) && count($epapers)>0)
                    @foreach ($epapers as $epaper)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $epaper->title }}</td>
                        <td>
                            @if (isset($epaper->file) && Storage::exists($epaper->file))
                                <a href="{{ URL::asset('storage/'.$epaper->file) }}">View/Download</a>
                            @endif
                        </td>
                        <td>{{ $epaper->date }}</td>
                        <td class="status">{{ $epaper->status }}</td>
                        <td>
                            <ul class="action">
                                <li><a href="javascript:void(0)" class="edit-epaper" epaperid='{{ $epaper->id }}'><i class="fas fa-pencil-alt"></i></a></li>
                                <li><a href="{{ route('change-status-epaper',$epaper->id) }}" class="change-status-epaper"><i class="fas fa-times"></i></a></li>
                                <li>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('delete-epaper-form-{{ $epaper->id }}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="delete-epaper-form-{{ $epaper->id }}" action="{{ route('manage-epaper.destroy',$epaper->id) }}" method="POST" style="display: none;">
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
<div class="modal" id="add-epaper">
</div>

<div class="modal" id="edit-epaper">
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
    $(document).on("click",".add-epaper",function(event){
        $.ajax({
            url:"{{ URL::to('manage-epaper/create') }}",
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#add-epaper").html(result.html);
                    $("#add-epaper").modal('show');
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


    $(document).on("click",".edit-epaper",function(event){
        let epaperid = $(this).attr('epaperid');
        $.ajax({
            url:`{{ URL::to('manage-epaper/${epaperid}/edit') }}`,
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#edit-epaper").html(result.html);
                    $("#edit-epaper").modal('show');
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
    $("#manage-epaper").DataTable();
});
</script>
