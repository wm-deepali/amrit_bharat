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
          <h3 class="content-header-title">Site Settings</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Site Settings</li>
            <li class="breadcrumb-item active">Manage Site </li>
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
                <table class="table table-bordered table-fitems" id="manage-content">
                  <thead>
                    <tr>
                      <th>Heading</th>
                      <th>Banner</th>
                      <th>Short Description</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($intro) && !empty($intro))
                    <tr>
                        <td>{{ $intro->heading }}</td>
                        <td>
                            @if (isset($intro->banner))
                                <img src="{{ URL::to('/').'/storage/app/public/banner/'.$intro->banner }}" height="100px" width="100px">
                            @endif
                        </td>
                        <td>{{ $intro->text }}</td>
                        <td>
                            <ul class="action">
                                <li><a href="javascript:void(0)" class="edit-content" introid='{{ $intro->id }}'><i class="fas fa-pencil-alt"></i></a></li>
                                
                            </ul>
                        </td>
                    </tr>
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


<div class="modal" id="edit-content">
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
    


    $(document).on("click",".edit-content",function(event){
        let introid = $(this).attr('introid');
        $.ajax({
            url:`{{ URL::to('manage-content/${introid}/edit') }}`,
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#edit-content").html(result.html);
                    $("#edit-content").modal('show');
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
    $("#manage-content").DataTable();
});
</script>
