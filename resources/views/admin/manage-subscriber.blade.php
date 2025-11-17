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
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active">Manage Subscriber</li>
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
                <table class="table table-bordered table-fitems" id="manage-subscriber">
                  <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>Email</th>
                      <th>Date Time</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($subscribers) && count($subscribers)>0)
                    @foreach ($subscribers as $subscriber)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $subscriber->email }}</td>
                        <td>{{ $subscriber->created_at }}</td>
                        <td>
                            <ul class="action">
                                <li>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('delete-subscriber-form-{{ $subscriber->id }}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <form id="delete-subscriber-form-{{ $subscriber->id }}" action="{{ route('manage-subscriber.destroy',$subscriber->id) }}" method="POST" style="display: none;">
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
        $("#manage-subscriber").DataTable();
    });
</script>
