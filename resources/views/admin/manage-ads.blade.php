@include('admin.header')
<section class="breadcrumb-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="content-header">
          <h3 class="content-header-title">Advertisement</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Advertisement</li>
            <li class="breadcrumb-item active">Manage Ads</li>
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
                      <th>Client</th>
                      <th>Type</th>
                      <th>Ad Page</th>
                      <th>Position</th>
                      <th>Payable Amount</th>
                      <th>Start - End</th>
                      <th>Mode of Payment</th>
                      <th>Payment Status</th>
                      <th>Ad Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>

                  <tbody>
                    @if (isset($ads) && count($ads)>0)
                    @foreach ($ads as $ad)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td><a href="javascript:void(0)" class="client-detail" adid="{{ $ad->id }}">{{ $ad->name ?? 'NA' }}</a></td>
                      <td>{{ $ad->type }}</td>
                      <td>{{ $ad->page }} - 
                        @if ($ad->page=='homepage')
                        <a href="{{ route('/') }}" target="_blank">View</a>
                        @elseif($ad->page=='categorypage')
                        <a href="{{ route('postbycategory',$category->slug) }}" target="_blank">View</a>
                        @else
                        <a href="{{ route('postdetail',[$category->slug,$post->slug]) }}" target="_blank">View</a>
                        
                        @endif
                      </td>
                      <td>{{ $ad->position }}</td>
                      <td><i class="fas fa-inr"></i> {{ $ad->amount ?? 'NA' }}</td>
                      <td>{{ $ad->startdate }} - {{ $ad->enddate }}</td>
                      <td><a href="javascript:void(0)" class="payment-detail" adid="{{ $ad->id }}">{{ $ad->paymentmethod ?? 'NA' }}</a></td>
                      <td>{{ $ad->paymentstatus }}</td>
                      <td>{{ $ad->status }}</td>
                      <td><ul class="action">
                        @if ($ad->status=='active')
                        <li><a href="{{ route('stop-ad',$ad->id) }}">Stop</a></li>
                        @endif
                        <li><a href="{{ route('manage-ad.edit',$ad->id) }}" title="Edit Ad"><i class="fas fa-pencil-alt"></i></a></li>
                        <li>
                          <a href="#" onclick="event.preventDefault(); document.getElementById('delete-ad-form-{{ $ad->id }}').submit();">
                            <i class="fas fa-trash"></i>
                          </a>
                          <form id="delete-ad-form-{{ $ad->id }}" action="{{ route('manage-ad.destroy',$ad->id) }}" method="POST" style="display: none;">
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
<div class="modal" id="client-detail">
</div>
<div class="modal" id="payment-detail">
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
    $(document).on("click",".client-detail",function(event){
      let adid=$(this).attr('adid');
        $.ajax({
            url:`{{ URL::to('view-client-detail/${adid}') }}`,
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#client-detail").html(result.html);
                    $("#client-detail").modal('show');
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
    $(document).on("click",".payment-detail",function(event){
      let adid=$(this).attr('adid');
        $.ajax({
            url:`{{ URL::to('view-payment-detail/${adid}') }}`,
            type:"get",
            dataType:"json",
            success:function(result)
            {
                if(result.msgCode==='200')
                {
                    $("#payment-detail").html(result.html);
                    $("#payment-detail").modal('show');
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