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
<style type="text/css">
    .read-more-show{
      cursor:pointer;
      color: #ed8323;
    }
    .read-more-hide{
      cursor:pointer;
      color: #ed8323;
    }

    .hide_content{
      display: none;
    }
</style>
<section class="breadcrumb-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="content-header">
          <h3 class="content-header-title">Content</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Content</li>
            <li class="breadcrumb-item active">Manage Notifications</li>
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
                <h4 class="form-section-h">Send Notification</h4>
                <div class="row">
                    <div class="col-sm-12">
                        <form method="POST" action="{{ route('custom-notification.store') }}" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                         <div class="form-group row">
                            <div class="col-sm-6">
                              <label class="label-control">User</label>
                              <select name="user_id" class="text-control js-example-basic-single" required>
                                  <option>Select User</option>
                                  <option value="0">All Users</option>
                                  @foreach($users as $user)
                                  <option value="{{$user->id}}">{{$user->name}}</option>
                                  @endforeach
                              </select>
                            </div>
                			 <div class="col-sm-6">
                              <label class="label-control">Type</label>
                              <input type="text" class="text-control" placeholder="Type" name="type" id="type" required>
                            </div>
                          </div>
                          <div class="form-group row">
                            
                			  <div class="col-sm-6">
                                <label class="label-control">Title</label><br>
                                <input type="text" class="text-control" name="title" id="title" required>
                            </div>
                            <div class="col-sm-6">
                                <label class="label-control">Message</label>
                                <textarea class="text-control" placeholder="Enter Message" name="message" id="message" required></textarea>
                            </div>
                          </div>
                          
                          <div class="form-action row">
                            <div class="col-sm-12 text-center">
                              <button class="btn btn-dark btn-save" type="submit">Save</button>
                            </div>
                          </div>
                        </form>
                    </div>
                </div>
              <div class="table-responsive">
                <table class="table table-bordered table-fitems" id="manage-notifications">
                  <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>Date</th>
                      <th>User</th>
                      <th>Type</th>
                      <th>Title</th>
                      <th>Message</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if (isset($notifications) && count($notifications)>0)
                    @foreach ($notifications as $notification)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $notification->created_at }}</td>
                        <td>{{ (isset($notification->user) && !empty($notification->user)) ? $notification->user->name  : ''}}</td>
                        <td>{{ $notification->type }}</td>
                        <td>{{ $notification->title }}</td>
                        <td>
                            @if(strlen($notification->message) > 50)
                            {{substr($notification->message,0,50)}}
                                <span class="read-more-show hide_content">Read More...</i></span>
                                <span class="read-more-content"> {{substr($notification->message,50,strlen($notification->message))}} 
                                <span class="read-more-hide hide_content">Less</span> </span>
                            @else
                            {{$notification->message}}
                            @endif
                            
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
    $(document).ready(function(){

     $('.read-more-content').addClass('hide_content')
        $('.read-more-show, .read-more-hide').removeClass('hide_content')

        // Set up the toggle effect:
        $('.read-more-show').on('click', function(e) {
          $(this).next('.read-more-content').removeClass('hide_content');
          $(this).addClass('hide_content');
          e.preventDefault();
        });

        // Changes contributed by @diego-rzg
        $('.read-more-hide').on('click', function(e) {
          var p = $(this).parent('.read-more-content');
          p.addClass('hide_content');
          p.prev('.read-more-show').removeClass('hide_content'); // Hide only the preceding "Read More"
          e.preventDefault();
        });

    $("#manage-notifications").DataTable();
});
</script>