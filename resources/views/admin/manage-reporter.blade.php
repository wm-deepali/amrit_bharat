@include('admin.header')
<section class="breadcrumb-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="content-header">
          <h3 class="content-header-title">Master</h3>
          <button class="btn btn-save btn-dark float-right add-reporter" type="button"><i class="fas fa-plus"></i> Add Reporter</button>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Reporter</li>
            <li class="breadcrumb-item active">Manage Reporter</li>
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
                      <th>Reporter ID</th>
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
                                <li><a href="javascript:void(0)" class="edit-reporter" reporterid="{{ $user->id }}"><i class="fas fa-pencil-alt"></i></a></li>
                                @if ($user->status=='pending')
                                <li><a href="javascript:void(0)" class="approve-reporter" reporterid="{{ $user->id }}" title="Approve Reporter"><i class="fas fa-check"></i></a></li>
                                @endif
                                <li><a href="javascript:void(0)" class="change-password" reporterid="{{ $user->id }}" title="Change Password"><i class="fas fa-lock"></i></a></li>
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
<div class="modal" id="add-reporter">
</div>
<div class="modal" id="edit-reporter">
</div>
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
        $(document).on("click",".add-reporter",function(event){
        $.ajax({
            url:"{{ URL::to('manage-reporter/create') }}",
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#add-reporter").html(result.html);
                    $("#add-reporter").modal('show');
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

    $(document).on("change","#state",function(event){
        let stateid = $(this).val();
        $.ajax({
            url:"{{ URL::to('fetchcitybystateid') }}",
            type:"POST",
            dataType:"json",
            data:{"state_id":stateid},
            success:function(result) {
                if(result.msgCode==='200') {
                    $("#city").html(result.html);
                } else if(result.msgCode==='400') {
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

    $(document).on('click','.add-reporter-btn',function(event){
        $('#image-err').html('');
        $('#name-err').html('');
        $('#email-err').html('');
        $('#contact-err').html('');
        $('#state-err').html('');
        $('#city-err').html('');
        $('#address-err').html('');
        $('#cv-err').html('');
        let formData=new FormData();
        formData.append('name',$('#name').val());
        formData.append('email',$('#email').val());
        formData.append('contact',$('#contact').val());
        formData.append('state',$('#state').val());
        formData.append('city',$('#city').val());
        formData.append('address',$('#address').val());
        formData.append('password',$('#password').val());
        if(typeof($('#image')[0].files[0])=='undefined'){
            formData.append('image','');
        } else {
            formData.append('image',$('#image')[0].files[0]);
        }
        formData.append('cv','');
        // if(typeof($('#cv')[0].files[0])=='undefined'){
        //     formData.append('cv','');
        // } else {
        //     formData.append('cv',$('#cv')[0].files[0]);
        // }
        $.ajax({
            url:"{{ URL::to('manage-reporter') }}",
            type:'POST',
            processData: false,
            contentType: false,
            dataType:'json',
            data:formData,
            success:function(result){
                if(result.msgCode === '200') {
                    toastr.success(result.msgText);
                    window.location = "{{ URL::to('manage-reporter') }}";
                } else if (result.msgCode === '401') {
                    if(result.errors.image) {
                        $('#image-err').html(result.errors.image[0]);
                    }
                    if(result.errors.name) {
                        $('#name-err').html(result.errors.name[0]);
                    }
                    if(result.errors.email) {
                        $('#email-err').html(result.errors.email[0]);
                    }
                    if(result.errors.contact) {
                        $('#contact-err').html(result.errors.contact[0]);
                    }
                    if(result.errors.state) {
                        $('#state-err').html(result.errors.state[0]);
                    }
                    if(result.errors.city) {
                        $('#city-err').html(result.errors.city[0]);
                    }
                    if(result.errors.address) {
                        $('#address-err').html(result.errors.address[0]);
                    }
                    if(result.errors.cv) {
                        $('#cv-err').html(result.errors.cv[0]);
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

    $(document).on("click",".edit-reporter",function(event){
        let reporterid = $(this).attr('reporterid');
        $.ajax({
            url:`{{ URL::to('manage-reporter/${reporterid}/edit') }}`,
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#edit-reporter").html(result.html);
                    $("#edit-reporter").modal('show');
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

    $(document).on('click','.update-reporter-btn',function(event){
        $('#image-err').html('');
        $('#name-err').html('');
        $('#email-err').html('');
        $('#contact-err').html('');
        $('#state-err').html('');
        $('#city-err').html('');
        $('#address-err').html('');
        $('#cv-err').html('');
        let formData=new FormData();
        formData.append('name',$('#name').val());
        formData.append('email',$('#email').val());
        formData.append('contact',$('#contact').val());
        formData.append('state',$('#state').val());
        formData.append('city',$('#city').val());
        formData.append('address',$('#address').val());
        if(typeof($('#image')[0].files[0])=='undefined'){
            formData.append('image','');
        } else {
            formData.append('image',$('#image')[0].files[0]);
        }
        formData.append('cv','');
        // if(typeof($('#cv')[0].files[0])=='undefined'){
        //     formData.append('cv','');
        // } else {
        //     formData.append('cv',$('#cv')[0].files[0]);
        // }
        let reporterid=$(this).attr('reporterid');
        $.ajax({
            url:`{{ URL::to('update-reporter/${reporterid}') }}`,
            type:'POST',
            processData: false,
            contentType: false,
            dataType:'json',
            data:formData,
            success:function(result){
                if(result.msgCode === '200') {
                    toastr.success(result.msgText);
                    window.location = "{{ URL::to('manage-reporter') }}";
                } else if (result.msgCode === '401') {
                    if(result.errors.image) {
                        $('#image-err').html(result.errors.image[0]);
                    }
                    if(result.errors.name) {
                        $('#name-err').html(result.errors.name[0]);
                    }
                    if(result.errors.email) {
                        $('#email-err').html(result.errors.email[0]);
                    }
                    if(result.errors.contact) {
                        $('#contact-err').html(result.errors.contact[0]);
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
