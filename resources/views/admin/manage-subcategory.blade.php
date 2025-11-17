@include('admin.header')
<section class="breadcrumb-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="content-header">
          <h3 class="content-header-title">Master</h3>
            <button class="btn btn-dark btn-save add-subcategory"><i class="fas fa-plus"></i> Add Sub Category</button>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Manage Sub Category</li>
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
                      <th>Sub Category</th>
                      <th>URL Slug</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($subcategories) && count($subcategories)>0)
                    @foreach ($subcategories as $subcategory)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $subcategory->category->name }}</td>
                        <td>{{ $subcategory->name }}</td>
                        <td>{{ $subcategory->slug }}</td>
                        <td class="status">{{ $subcategory->status }}</td>
                        <td>
                            <ul class="action">
                                <li><a href="javascript:void(0)" class="edit-subcategory" subcategoryid="{{ $subcategory->id }}"><i class="fas fa-pencil-alt"></i></a></li>
                                <li><a href="javascript:void(0)" class="change-status-subcategory" subcategoryid="{{ $subcategory->id }}"><i class="fas fa-times"></i></a></li>
                                <li><a href="javascript:void(0)" class="delete-subcategory" subcategoryid="{{ $subcategory->id }}"><i class="fas fa-trash"></i></a></li>
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

<div class="modal" id="add-subcategory">
</div>

<div class="modal" id="edit-subcategory">
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
    $(document).on("click",".add-subcategory",function(event){
        $.ajax({
            url:"{{ URL::to('manage-subcategory/create') }}",
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#add-subcategory").html(result.html);
                    $("#add-subcategory").modal('show');
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

    $(document).on('click','.add-subcategory-btn',function(event){
        $('#category-err').html('');
        $('#name-err').html('');
        $('#slug-err').html('');
        $('#metatitle-err').html('');
        $('#metadescription-err').html('');
        $('#metakeyword-err').html('');
        $.ajax({
            url:"{{ URL::to('manage-subcategory') }}",
            type:'POST',
            dataType:'json',
            data:$('#add-subcategory-form').serialize(),
            success:function(result){
                if(result.msgCode === '200') {
                    toastr.success(result.msgText);
                    location.reload();
                } else if (result.msgCode === '401') {
                    if(result.errors.category) {
                        $('#category-err').html(result.errors.category[0]);
                    }
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

    $(document).on("click",".edit-subcategory",function(event){
        let subcategoryid = $(this).attr('subcategoryid');
        $.ajax({
            url:`{{ URL::to('manage-subcategory/${subcategoryid}/edit') }}`,
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#edit-subcategory").html(result.html);
                    $("#edit-subcategory").modal('show');
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

    $(document).on('click','.edit-subcategory-btn',function(event){
        $('#category-err').html('');
        $('#name-err').html('');
        $('#slug-err').html('');
        $('#metatitle-err').html('');
        $('#metadescription-err').html('');
        $('#metakeyword-err').html('');
        let subcategoryid = $('#edit-subcategory-form').attr('subcategoryid');
        $.ajax({
            url:`{{ URL::to('manage-subcategory/${subcategoryid}') }}`,
            type:'PUT',
            dataType:'json',
            data:$('#edit-subcategory-form').serialize(),
            success:function(result){
                if(result.msgCode === '200') {
                    toastr.success(result.msgText);
                    location.reload();
                } else if (result.msgCode === '401') {
                    if(result.errors.category) {
                        $('#category-err').html(result.errors.category[0]);
                    }
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

    $(document).on("click",".delete-subcategory",function(event){
        let subcategoryid = $(this).attr('subcategoryid');
        $.ajax({
            url:`{{ URL::to('manage-subcategory/${subcategoryid}') }}`,
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
    });

    $(document).on('click','.change-status-subcategory',function(event){
        let subcategoryid = $(this).attr('subcategoryid');
        let status= $(this).closest('tr').find('.status').html();
        $.ajax({
            url:`{{ URL::to('change-subcategory-status/${subcategoryid}/${status}') }}`,
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
        });
    });

    });
</script>
