@include('admin.header')
<style type="text/css">
.report-head a {
    color: #333;
    font-family: 'Poppins';
}
.total-news {
    text-align: center;
    background: #f4f4f4;
    font-family: 'Poppins';
}

.total-news label {
    background: #333;
    color: #fff;
    width: 100%;
    padding: 3px 10px;
}

.total-news .report-head {
    padding: 5px 10px;
}
.report-head {
    color: #333;
    margin: 0;
}
</style>
<section class="breadcrumb-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-xs-12">
        <div class="content-header">
          <h3 class="content-header-title">Reports</h3>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Reports</li>
            <li class="breadcrumb-item active">Manage Reports</li>
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
              <form method="POST" action="{{ route('generate-report') }}">
              @csrf
							<div class="form-group row">
								<div class="col-sm-3">
									<label class="label label-control">Category</label>
									<select class="text-control" name="category" id="category">
                    <option value="">All Category</option>
                    @if (isset($categories) && count($categories)>0)
                        @foreach ($categories as $category)
                          <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    @endif
									</select>
								</div>

								<div class="col-sm-3">
									<label class="label label-control">Sub Category</label>
									<select class="text-control" name="subcategory" id="subcategory">
										<option value="">All Sub Cat</option>
									</select>
								</div>

								<div class="col-sm-3">
									<label class="label label-control">Reporter</label>
									<select class="text-control" name="reporter" id="reporter">
                    <option value="">All Reporter</option>
                    @if (isset($reporters) && count($reporters)>0)
                        @foreach ($reporters as $reporter)
                          <option value="{{ $reporter->id }}">{{ $reporter->name }}</option>
                        @endforeach
                    @endif
									</select>
                </div>
                

								<div class="col-sm-3">
									<div class="d-block total-news">
										<label>Total News</label>
										<h3 class="report-head"><a href="#">{{ $totalposts->count() }}</a></h3>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-3">
									<label class="label label-control">Total Clicks</label>
									<div class="d-block">
										<h3 class="report-head"><a href="#">{{ $totalposts->SUM('views') }}</a></h3>
									</div>
								</div>
								
								<div class="col-sm-3">
									<label class="label label-control">Total Views</label>
									<div class="d-block">
										<h3 class="report-head"><a href="#">{{ $totalposts->SUM('views') }}</a></h3>
									</div>
								</div>
								
								<div class="col-sm-3">
									<label class="label label-control">Total Published</label>
									<div class="d-block">
										<h3 class="report-head"><a href="#">{{ $publishedposts->count() }}</a></h3>
									</div>
								</div>
								
								<div class="col-sm-3">
									<label class="label label-control">Total Pending</label>
									<div class="d-block">
										<h3 class="report-head"><a href="#">{{ $pendingposts->count() }}</a></h3>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
			<div class="d-block">
        <button type="button" class="btn btn-primary pull-right filterbtn"><i class="fa fa-check"></i>Filter</button>
				<button class="btn-dark btn float-right mb-3" type="submit">Export to Excel</button>  
      </div>
      </form>
            <div class="card-block" id="reports-container">
              @include('admin/ajax/manage-reports')
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
  $(document).on("change","#category",function(event){
    let categoryid = $(this).val();
    $.ajax({
        url:`{{ URL::to('fetchsubcategories/${categoryid}') }}`,
        type:"GET",
        dataType:"json",
        success:function(result) {
            if(result.msgCode==='200') {
                $("#subcategory").html(result.html);
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

  $(document).on('click','.filterbtn',function(event){
      let page = 1;
      let category=$('#category').val();
      let subcategory=$('#subcategory').val();
      let reporter=$('#reporter').val();
      getData(page,category,subcategory,reporter);
  })
  $(document).on('click', '.pagination a',function(event)
  {
      event.preventDefault();
      $('li').removeClass('active');
      $(this).parent('li').addClass('active');
      let page=$(this).attr('href').split('page=')[1];
      let category=$('#category').val();
      let subcategory=$('#subcategory').val();
      let reporter=$('#reporter').val();
      getData(page,category,subcategory,reporter);
  });
  function getData(page,category,subcategory,reporter){
      $.ajax({
          url: '?page=' + page + '&category=' + category + '&subcategory=' + subcategory + '&reporter=' + reporter,
          type: "get",
          datatype: "html"
      }).done(function(data){
          $("#reports-container").empty().html(data);
      }).fail(function(jqXHR, ajaxOptions, thrownError){
            alert('No response from server');
      });
  }
  
});
</script>