@include( 'front.header' )
<style type="text/css">
    .date-h {
    font-size: 18px;
    text-decoration: underline;
    margin: 1em 0 0.7em;
}
.date-h a { float: right;
    background: #c02222;
    padding: 6px 8px;
    color: #fff;
    border-radius: 3px;
    display: inline-block;
    font-size: 0.7em;
    }
</style>
	<section class="page-content">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="breadcrumb-sec">
						<div class="row">
							<div class="col-sm-12">
								<nav class="breadcrumb-m" aria-label="breadcrumb">
									<ol class="breadcrumb">
										<li class="item">You are here :&nbsp;&nbsp;</li>
										<li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">E-Paper</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<section class="team-section">
	<div class="container">
	    <div class="row">
	        <div class="col-sm-12 text-center">
	            <h3 class="team-h">E-Paper</h3>
	       </div>
	   </div>
	   <div class="row justify-content-center">
	       <div class="col-sm-4">
	            <form id="filter-form" style="display:flex;justify-content: center;">
	                <div>
	                    <label class="m-0 label-control">Select Date</label>
	                    <select class='form-control' name='date' id='date'>
	                        <option value=''>Select Date</option>
	                        @if(isset($dates) && count($dates)>0)
	                        @foreach($dates as $date)
	                        <option value='{{ $date->date }}'>{{ $date->date }}</option>
	                        @endforeach
	                        @endif
	                    </select>
	                    <!--<input type="date" name="date" id="date" class="form-control" value="{{ now()->toDateString() }}">-->
                    </div>
                    &nbsp;&nbsp;
                    <div style="align-self: flex-end;">
                        <button class="btn btn-primary filterBtn" type="button">Filter</button>
                    </div>
                </form>
	        </div>
	    </div>
		<div class="row justify-content-center" id="epaperDiv">
		    <div class="col-sm-8">
		      @if (isset($epaper))
                <h3 class="date-h">Showing Result: {{ $epaper->date }} <a href="{{ URL::asset('storage/'.$epaper->file) }}" download>Download E-Paper</a></h3>
                @if (isset($epaper->file) && Storage::exists($epaper->file))
                <iframe src="{{ URL::asset('storage/'.$epaper->file) }}" width="100%" height="800px"></iframe>
                @else
                <a href="#">File Not Available</a>
                @endif
            @endif
		    </div>
		</div>
	</div>
</section>
@include( 'front.footer' )
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
		$(document).on('click','.filterBtn',function(event){
			$.ajax({
				url:"{{ URL::to('filter-epaper') }}",
				type:'POST',
				dataType:'json',
				data:$('#filter-form').serialize(),
				success:function(result){
					if(result.msgCode === '200') {
                        $("#epaperDiv").html(result.html);
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