@include('admin.header')
<section class="breadcrumb-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="content-header">
          <h3 class="content-header-title">Master</h3>
          <button class="btn btn-dark btn-save add-category"><i class="fas fa-plus"></i> Add Category</button>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item active">Manage Category</li>
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
                <table class="table table-bordered table-fitems">
                  <thead>
                    <tr>
                      <th>Sr. No.</th>
                      <th>Category</th>
                      <th>URL Slug</th>
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
                        <td>{{ $category->slug }}</td>
                        <td class="status">{{ $category->status }}</td>
                        <td>
                            <ul class="action">
                                <li><a href="javascript:void(0)" class="edit-category" categoryid='{{ $category->id }}'><i class="fas fa-pencil-alt"></i></a></li>
                                <li><a href="javascript:void(0)" class="change-status-category" categoryid="{{ $category->id }}"><i class="fas fa-times"></i></a></li>
                                <li><a href="javascript:void(0)" class="delete-category" categoryid="{{ $category->id }}"><i class="fas fa-trash"></i></a></li>
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
<div class="modal" id="add-category">
</div>

<div class="modal" id="edit-category">
</div>
@include('admin.footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    $(document).on("click",".add-category",function(event){
        $.ajax({
            url:"{{ URL::to('manage-category/create') }}",
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#add-category").html(result.html);
                    $("#add-category").modal('show');
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

    $(document).on('click','.add-category-btn',function(event){
        $('#name-err').html('');
        $('#slug-err').html('');
        $('#metatitle-err').html('');
        $('#metadescription-err').html('');
        $('#metakeyword-err').html('');
        $.ajax({
            url:"{{ URL::to('add-category') }}",
            type:'POST',
            dataType:'json',
            data:$('#add-category-form').serialize(),
            success:function(result){
                if(result.msgCode === '200') {
                    toastr.success(result.msgText);
                    location.reload();
                } else if (result.msgCode === '401') {
                    if(result.errors.name) {
                        $('#name-err').html(result.errors.name[0]);
                    }
                    if(result.errors.slug) {
                        $('#slug-err').html(result.errors.slug[0]);
                    }
                    if(result.errors.metatitle) {
                        $('#metatitle-err').html(result.errors.metatitle[0]);
                    }
                    if(result.errors.metadescription) {
                        $('#metadescription-err').html(result.errors.metadescription[0]);
                    }
                    if(result.errors.metakeyword) {
                        $('#metakeyword-err').html(result.errors.metakeyword[0]);
                    }
                } else {
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

    $(document).on("click",".edit-category",function(event){
        let categoryid = $(this).attr('categoryid');
        $.ajax({
            url:`{{ URL::to('manage-category/${categoryid}/edit') }}`,
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#edit-category").html(result.html);
                    $("#edit-category").modal('show');
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

    $(document).on('click','.edit-category-btn',function(event){
        $('#name-err').html('');
        $('#slug-err').html('');
        $('#metatitle-err').html('');
        $('#metadescription-err').html('');
        $('#metakeyword-err').html('');
        let categoryid = $('#edit-category-form').attr('categoryid');
        $.ajax({
            url:`{{ URL::to('manage-category/${categoryid}') }}`,
            type:'PUT',
            dataType:'json',
            data:$('#edit-category-form').serialize(),
            success:function(result){
                if(result.msgCode === '200') {
                    toastr.success(result.msgText);
                    location.reload();
                } else if (result.msgCode === '401') {
                    if(result.errors.name) {
                        $('#name-err').html(result.errors.name[0]);
                    }
                    if(result.errors.slug) {
                        $('#slug-err').html(result.errors.slug[0]);
                    }
                    if(result.errors.metatitle) {
                        $('#metatitle-err').html(result.errors.metatitle[0]);
                    }
                    if(result.errors.metadescription) {
                        $('#metadescription-err').html(result.errors.metadescription[0]);
                    }
                    if(result.errors.metakeyword) {
                        $('#metakeyword-err').html(result.errors.metakeyword[0]);
                    }
                } else {
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

    $(document).on("click",".delete-category",function(event){
        let categoryid = $(this).attr('categoryid');
        Swal.fire({
  title: 'Are you sure?',
  text: "Deleting this category will delete all the News Post under this category!",
  showDenyButton: false,
  showCancelButton: true,
  confirmButtonText: 'Yes',
  denyButtonText: `Don't save`,
}).then((result) => {
  /* Read more about isConfirmed, isDenied below */
  if (result.isConfirmed) {
     $.ajax({
            url:`{{ URL::to('manage-category/${categoryid}') }}`,
            type:"DELETE",
            dataType:"json",
            context:this,
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $(this).closest('tr').remove();
                    toastr.success(result.msgText);
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
  }
})
        
    });

    $(document).on('click','.change-status-category',function(event){
        let categoryid = $(this).attr('categoryid');
        let status= $(this).closest('tr').find('.status').html();
        $.ajax({
            url:`{{ URL::to('change-category-status/${categoryid}/${status}') }}`,
            type:'PUT',
            dataType:'json',
            context:this,
            success:function(result){
                if(result.msgCode==='200')
                {
                    $(this).closest('tr').find('.status').html(result.status);
                    toastr.success(result.msgText);
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
        })
    })

    });
    </script>
