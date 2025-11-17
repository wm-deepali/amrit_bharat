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
          <button class="btn btn-dark btn-save add-team-category"><i class="fas fa-plus"></i> Add Team Category</button>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active">Manage Team Category</li>
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
                <table class="table table-bordered table-fitems" id="manage-team-category">
                  <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>Name</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($categories) && count($categories)>0)
                    @foreach ($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->name }}</td>
                        <td class="status">{{ $category->status }}</td>
                        <td>
                            <ul class="action">
                                <li><a href="javascript:void(0)" class="edit-team-category" categoryid='{{ $category->id }}'><i class="fas fa-pencil-alt"></i></a></li>
                                <li><a href="{{ route('change-status-team-category',$category->id) }}" class="change-status-team"><i class="fas fa-times"></i></a></li>
                                <li>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('delete-team-category-form-{{ $category->id }}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="delete-team-category-form-{{ $category->id }}" action="{{ route('manage-team-category.destroy',$category->id) }}" method="POST" style="display: none;">
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
<div class="modal" id="add-team-category">
</div>

<div class="modal" id="edit-team-category">
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
    $(document).on("click",".add-team-category",function(event){
        $.ajax({
            url:"{{ URL::to('manage-team-category/create') }}",
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#add-team-category").html(result.html);
                    $("#add-team-category").modal('show');
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


    $(document).on("click",".edit-team-category",function(event){
        let categoryid = $(this).attr('categoryid');
        $.ajax({
            url:`{{ URL::to('manage-team-category/${categoryid}/edit') }}`,
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#edit-team-category").html(result.html);
                    $("#edit-team-category").modal('show');
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
    $("#manage-team-category").DataTable();
});
</script>
