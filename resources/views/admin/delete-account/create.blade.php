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
          <h3 class="content-header-title">Delete Account Request</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item active">Delete Account</li>
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
                <button type="button" class="btn btn-primary" id="deleteAcc">Delete My Account</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js
"></script>
<link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.min.css
" rel="stylesheet">
@include('admin.footer')

<script>
$('#deleteAcc').click(function(){
  Swal.fire({
  title: "Are you sure?",
  text: "If you delete your account, all your data with us will be permanently removed, however if you change your plan you can re-store your account within 90 Days from the Deletion Request",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Yes I Agree!"
}).then((result) => {
  if (result.isConfirmed) {
      window.location.href="{{ route('post-delete-acount') }}";
   
  }
});
});
</script>