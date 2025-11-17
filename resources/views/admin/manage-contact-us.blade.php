@include('admin.header')
<section class="breadcrumb-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="content-header">
          <h3 class="content-header-title">Footer Management</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Footer Management</li>
            <li class="breadcrumb-item active">Manage Contact Us</li>
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
                <table class="table table-bordered table-fitems" id="manage-contact-us">
                  <thead>
                    <tr>
                      <th>Sr. No.</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Mobile</th>
                      <th>Query</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($contactuses) && count($contactuses)>0)
                    @foreach ($contactuses as $contactus)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $contactus->name }}</td>
                        <td>{{ $contactus->email }}</td>
                        <td>{{ $contactus->contact }}</td>
                        <td>{{ $contactus->content }}</td>
                        <td>
                            <ul class="action">
                                <form action="{{ route('delete-contact-us',$contactus->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
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
$(document).ready(function(){
    $('#manage-contact-us').DataTable();
});
</script>