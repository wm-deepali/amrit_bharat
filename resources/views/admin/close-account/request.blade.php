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
          <h3 class="content-header-title">Close Account Request</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Master</li>
            <li class="breadcrumb-item active">Close Account Request</li>
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
            @if(empty($request))
                <form method="POST" action="{{ route('manage-close-account-request.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="label-control">Reason</label>
                            <select class="text-control" name="reason" id="reason" required>
                                <option value="">Select Reason</option>
                                @if(isset($reasons) && count($reasons) > 0)
                                @foreach($reasons as $reason)
                                <option value="{{$reason->id}}">{{$reason->reason}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="label-control">Image</label><br>
                            <input type="file" class="text-control" name="image" id="image">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="label-control">Detail</label>
                            <textarea class="text-control" placeholder="Enter Detail" name="detail" id="detail" required></textarea>
                        </div>
                    </div>
                    <div class="form-action row">
                        <div class="col-sm-12 text-center">
                            <button class="btn btn-dark btn-save" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            @else
            <div class="form-group row">
                <div class="col-sm-6">
                    <label class="label-control">Reason</label>
                    <p>{{$request->reason->reason}}</p>
                </div>
                <div class="col-sm-6">
                    <label class="label-control">File</label>
                    @if (isset($request->file) && $request->file !='')
                    <a href="{{ URL::to('/').'/storage/app/public/help/'.$request->file }}" download>
                        <img src="{{ URL::to('/').'/storage/app/public/help/'.$request->file }}" height="100px" width="100px">
                    </a>
                    @endif
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="label-control">Detail</label>
                    <p>{{$request->detail}}</p>
                </div>
               
            </div>
            @endif

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@include('admin.footer')

