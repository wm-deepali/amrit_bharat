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
          <button class="btn btn-dark btn-save add-intro"><i class="fas fa-plus"></i> Add Site Introduction</button>
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
                <table class="table table-bordered table-fitems" id="manage-siteintro">
                  <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>Heading</th>
                      <th>Image</th>
                      <th>Short Description</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($intros) && count($intros)>0)
                    @foreach ($intros as $intro)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $intro->heading }}</td>
                        <td>
                            @if (isset($intro->image))
                                <img src="{{ URL::to('/').'/storage/app/public/intro/'.$intro->image }}" height="100px" width="100px">
                            @endif
                        </td>
                        <td>{{ $intro->short_description }}</td>
                        <td>
                            <ul class="action">
                                <li><a href="javascript:void(0)" class="edit-intro" introid='{{ $intro->id }}'><i class="fas fa-pencil-alt"></i></a></li>
                                <li>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('delete-intro-form-{{ $intro->id }}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="delete-intro-form-{{ $intro->id }}" action="{{ route('manage-site-intro.destroy',$intro->id) }}" method="POST" style="display: none;">
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
<div class="modal" id="add-intro">
</div>

<div class="modal" id="edit-intro">
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
    $(document).on("click",".add-intro",function(event){
        $.ajax({
            url:"{{ URL::to('manage-site-intro/create') }}",
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#add-intro").html(result.html);
                    $("#add-intro").modal('show');
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


    $(document).on("click",".edit-intro",function(event){
        let introid = $(this).attr('introid');
        $.ajax({
            url:`{{ URL::to('manage-site-intro/${introid}/edit') }}`,
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#edit-intro").html(result.html);
                    $("#edit-intro").modal('show');
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
    $("#manage-intro").DataTable();
});
</script>
