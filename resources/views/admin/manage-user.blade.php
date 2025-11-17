@include('admin.header')
<section class="breadcrumb-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="content-header">
          <h3 class="content-header-title">Master</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">User</li>
            <li class="breadcrumb-item active">Manage User</li>
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
                      <th>User ID</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Mobile No.</th>
                      <th>Posted News</th>
                      <th>Added By</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($users) && count($users)>0)
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="user_number">{{ $user->user_number }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->contact }}</td>
                        <td>{{ $user->posts->count() }}</td>
                        <td>{{ $user->added_by }}</td>
                        <td class="status">{{ $user->status }}</td>
                        <td>
                            <ul class="action">
                                
                                <li><a href="javascript:void(0)" class="change-password" reporterid="{{ $user->id }}" title="Change Password"><i class="fas fa-lock"></i></a></li>
                                @if ($user->status=='pending')
                                <li><a href="javascript:void(0)" class="approve-reporter" reporterid="{{ $user->id }}" title="Approve Reporter"><i class="fas fa-check"></i></a></li>
                                @endif
                                <li><a href="javascript:void(0)" class="delete-reporter" reporterid="{{ $user->id }}"><i class="fas fa-trash"></i></a></li>
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

<div class="modal" id="change-password">
</div>
<div class="modal" id="approve-reporter">
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
        
    
    
    

    
    $(document).on("click",".delete-reporter",function(event){
        let reporterid = $(this).attr('reporterid');
        $.ajax({
            url:`{{ URL::to('manage-reporter/${reporterid}') }}`,
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

    $(document).on('click','.approve-reporter',function(event){
        let reporterid = $(this).attr('reporterid');
        $.ajax({
            url:`{{ URL::to('approve-reporter/${reporterid}') }}`,
            type:'POST',
            dataType:'json',
            context:this,
            success:function(result){
                if(result.msgCode==='200')
                {
                    $("#approve-reporter").html(result.html);
                    $("#approve-reporter").modal('show');
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

    $(document).on('click','.approve-reporter-btn',function(event){
        let reporterid = $(this).attr('reporterid');
        $.ajax({
            url:`{{ URL::to('approve-reporter-submit/${reporterid}') }}`,
            type:'PUT',
            dataType:'json',
            data:$('#approve-reporter-form').serialize(),
            success:function(result){
                if(result.msgCode==='200')
                {
                    toastr.success(result.msgText);
                    $('#approve-reporter').modal('hide');
                    $(`.approve-reporter[reporterid="${reporterid}"]`).hide();
                    $(`.approve-reporter[reporterid="${reporterid}"]`).closest('tr').find('.status').html(result.status);
                    $(`.approve-reporter[reporterid="${reporterid}"]`).closest('tr').find('.user_number').html(result.user_number);
                } else if (result.msgCode === '401') {
                    if(result.errors.password) {
                        $('#password-err').html(result.errors.password[0]);
                    }
                } else {
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

    $(document).on("click",".change-password",function(event){
        let reporterid = $(this).attr('reporterid');
        $.ajax({
            url:`{{ URL::to('edit-password-reporter/${reporterid}') }}`,
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#change-password").html(result.html);
                    $("#change-password").modal('show');
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

    $(document).on("click",".change-password-btn",function(event){
        $('#password-err').html('');
        let reporterid = $(this).attr('reporterid');
        $.ajax({
            url:`{{ URL::to('update-password-reporter/${reporterid}') }}`,
            type:"PUT",
            dataType:"json",
            data:$('#change-password-form').serialize(),
            success:function(result)
            {
                if (result.msgCode==='200') {
                    toastr.success(result.msgText);
                    $("#change-password").modal('hide');
                    $("#change-password").html('');
                } else if (result.msgCode === '401') {
                    if(result.errors.password) {
                        $('#password-err').html(result.errors.password[0]);
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

    });
</script>
