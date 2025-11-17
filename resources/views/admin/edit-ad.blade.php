@include('admin.header')
<section class="breadcrumb-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<div class="content-header">
					<h3 class="content-header-title">Advertisement</h3>
					<button type="button" class="btn btn-dark float-right edit-ad-btn" adid="{{ $ad->id }}"><i class="fas fa-plus-circle"></i> Edit Ad</button>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
						</li>
						<li class="breadcrumb-item"><a href="{{ route('manage-ad.index') }}">Manage Ads</a>
						</li>
						<li class="breadcrumb-item active">Edit Ad</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>
<section class="content-main-body">
	<div class="container">
		<div class="row">
			<div class="col-sm-9 col-md-8">
				<div class="card">
					<div class="card-body">
						<div class="card-block">
							<h4 class="form-section-h">Ad Details</h4>
							<div class="form-group row">
								<div class="col-sm-4">
									<label class="label-control">Ad Title </label>
									<input type="text" class="text-control" placeholder="Enter Ad Title" name="title" id="title" value="{{ $ad->title }}">
									<div class="text-danger" id="title-err"></div>
								</div>
								<div class="col-sm-4">
									<label class="label-control">Ad Type </label>
									<select class="text-control" id="type" name="type">
										<option value="">Select Type</option>
										<option value="custom" @if ($ad->type=='custom')
											selected
										@endif>Custom Ad</option>
										<option value="google" @if ($ad->type=='google')
											selected
										@endif>Google Code</option>
									</select>
									<div class="text-danger" id="type-err"></div>
								</div>
								<div class="col-sm-4">
									<label class="label-control">Ad Page </label>
									<select class="text-control" name="page" id="page">
										<option value="">Select Page</option>
										<option value="homepage" @if ($ad->page=='homepage')
											selected
										@endif>Homepage</option>
										<option value="categorypage" @if ($ad->page=='categorypage')
											selected
										@endif>Category Page</option>
										<option value="postpage" @if ($ad->page=='postpage')
											selected
										@endif>Post Page</option>
									</select>
									<div class="text-danger" id="page-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-4">
									<label class="label-control">Ad Position </label>
									<select class="text-control" name="position" id="position">
										<option value="">Select Position</option>
										@if ($ad->page=='homepage')
										<option value="uppersidebar300x250" @if ($ad->position=='uppersidebar300x250')
											selected
										@endif>Upper Sidebar (300x250)</option>
										<option value="middlesidebar300x250" @if ($ad->position=='middlesidebar300x250')
											selected
										@endif>Middle Sidebar (300x250)</option>
										<option value="lowersidebar300x250" @if ($ad->position=='lowersidebar300x250')
											selected
										@endif>Lower Sidebar (300x250)</option>
										<option value="uppersidebar300x600" @if ($ad->position=='uppersidebar300x600')
											selected
										@endif>Upper Sidebar (300x600)</option>
										<option value="middlesidebar300x600" @if ($ad->position=='middlesidebar300x600')
											selected
										@endif>Middle Sidebar (300x600)</option>
										<option value="lowersidebar300x600" @if ($ad->position=='lowersidebar300x600')
											selected
										@endif>Lower Sidebar (300x600)</option>
										<option value="upperbanner728x90" @if ($ad->position=='upperbanner728x90')
											selected
										@endif>Upper Banner (728x90)</option>
										<option value="middlebanner728x90" @if ($ad->position=='middlebanner728x90')
											selected
										@endif>Middle Banner (728x90)</option>
										<option value="lowerbanner728x90" @if ($ad->position=='lowerbanner728x90')
											selected
										@endif>Lower Banner (728x90)</option>
										<option value="lowestbanner728x90" @if ($ad->position=='lowestbanner728x90')
											selected
										@endif>Lowest Banner (728x90)</option>
										@elseif($ad->page=='categorypage')
										<option value="sidebar300x250" @if ($ad->position=='sidebar300x250')
											selected
										@endif>Sidebar (300x250)</option>
										<option value="sidebar300x600" @if ($ad->position=='sidebar300x600')
											selected
										@endif>Sidebar (300x600)</option>
										@elseif($ad->page=='postpage')
										<option value="uppersidebar300x250" @if ($ad->position=='uppersidebar300x250')
											selected
										@endif>Upper Sidebar (300x250)</option>
										<option value="lowersidebar300x250" @if ($ad->position=='lowersidebar300x250')
											selected
										@endif>Lower Sidebar (300x250)</option>
										<option value="sidebar300x600" @if ($ad->position=='sidebar300x600')
											selected
										@endif>Sidebar (300x600)</option>
										@endif
									</select>
									<div class="text-danger" id="position-err"></div>
								</div>
								<div class="col-sm-4 customDiv" @if ($ad->type=='google')
									style="display: none;"
								@endif>
									<label class="label-control">Ad Image </label>
									@if (isset($ad->image) && Storage::exists($ad->image))
									<img src="{{ URL::asset('storage/'.$ad->image) }}" class="img-fluid" style="height: 50px;">
									@endif
									<input type="file" class="text-control" name="image" id="image">
									<div class="text-danger" id="image-err"></div>
								</div>
								<div class="col-sm-4 customDiv" @if ($ad->type=='google')
									style="display: none;"
								@endif>
									<label class="label-control">Ad Link </label>
									<input type="text" class="text-control" placeholder="Enter URL" name="link" id="link" value="{{ $ad->link }}">
									<div class="text-danger" id="link-err"></div>
								</div>
								<div class="col-sm-8 googleDiv" @if ($ad->type=='custom')
									style="display: none;"
								@endif>
									<label class="label-control">Google Ad Code </label>
									<input type="text" placeholder="Enter Code" class="text-control" name="code" id="code" value="{{ $ad->code }}">
									<div class="text-danger" id="code-err"></div>
								</div>
							</div>
							<div class="form-group row categoryDiv" @if ($ad->page!='categorypage')
								style="display: none"
							@endif>
								<div class="col-sm-4">
									<div class="category-side">
										<h3>Categories</h3>
										<div class="text-danger" id="category-err"></div>
										<ul>
											@if (isset($categories) && count($categories)>0)
											@foreach ($categories as $category)
											<li>
												<label>
													<input type="checkbox" class="category" name="category[]" value="{{ $category->id }}" @if ($ad->categories->contains('category_id',$category->id))
														checked
													@endif > {{ $category->name }}
												</label>
											</li>
											@endforeach
											@endif
										</ul>
									</div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-4">
									<label class="label-control">Start Date</label>
									<input type="date" class="text-control" placeholder="Start Date" name="startdate" id="startdate" value="{{ $ad->startdate }}">
									<div class="text-danger" id="startdate-err"></div>
								</div>
								<div class="col-sm-4">
									<label class="label-control">End Date</label>
									<input type="date" class="text-control" placeholder="End Date" name="enddate" id="enddate" value="{{ $ad->enddate }}">
									<div class="text-danger" id="enddate-err"></div>
								</div>
								<div class="col-sm-4 customDiv" @if ($ad->type=='google')
									style="display: none"
								@endif>
									<label class="label-control">Amount</label>
									<input type="number" class="text-control" placeholder="Enter Amount" name="amount" id="amount" value="{{ $ad->amount }}">
									<div class="text-danger" id="amount-err"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-md-4 customDiv" @if ($ad->type=='google')
				style="display: none"
			@endif>
				<div class="card">
					<div class="card-body">
						<div class="card-block">
							<h4 class="form-section-h">Client Details</h4>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">Name</label>
									<input type="text" class="text-control" placeholder="Enter Name" name="name" id="name" value="{{ $ad->name }}">
									<div class="text-danger" id="name-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">Mobile No.</label>
									<input type="text" class="text-control" placeholder="Enter Mobile No." name="contact" id="contact" value="{{ $ad->contact }}">
									<div class="text-danger" id="contact-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">Email</label>
									<input type="text" class="text-control" placeholder="Enter Email" name="email" id="email" value="{{ $ad->email }}">
									<div class="text-danger" id="email-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">Address</label>
									<input type="text" class="text-control" placeholder="Enter Address" name="address" id="address" value="{{ $ad->address }}">
									<div class="text-danger" id="address-err"></div>
								</div>
							</div>

							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">State</label>
									<select class="text-control" name="state" id="state">
										<option value="">Select State</option>
										@if (isset($states) && count($states)>0)
										@foreach ($states as $state)
										<option value="{{ $state->id }}" @if ($state->id==$ad->state_id)
											selected
										@endif>{{ $state->name }}</option>
										@endforeach
										@endif
									</select>
									<div class="text-danger" id="state-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">City</label>
									<select class="text-control" name="city" id="city">
										<option value="">Select City</option>
										@if (isset($cities) && count($cities)>0)
											@foreach ($cities as $city)
											<option value="{{ $city->id }}" @if ($city->id==$ad->city_id)
												selected
											@endif>{{ $city->name }}</option>
											@endforeach
										@endif
									</select>
									<div class="text-danger" id="city-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">Pincode</label>
									<input type="text" class="text-control" placeholder="Enter Pincode" name="pincode" id="pincode" value="{{ $ad->pincode }}">
									<div class="text-danger" id="pincode-err"></div>
								</div>
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
	$(document).on('click','.edit-ad-btn',function(event){
        $('#title-err').html('');
        $('#type-err').html('');
        $('#page-err').html('');
        $('#position-err').html('');
        $('#image-err').html('');
        $('#link-err').html('');
        $('#code-err').html('');
        $('#startdate-err').html('');
        $('#enddate-err').html('');
        $('#amount-err').html('');
        $('#name-err').html('');
        $('#contact-err').html('');
        $('#email-err').html('');
        $('#address-err').html('');
        $('#state-err').html('');
        $('#city-err').html('');
        $('#pincode-err').html('');
		$('#category-err').html('');
        let formData=new FormData();
        formData.append('title',$('#title').val());
        formData.append('type',$('#type').val());
        formData.append('page',$('#page').val());
        formData.append('position',$('#position').val());
        if(typeof($('#image')[0].files[0])=='undefined'){
            formData.append('image','');
        } else {
            formData.append('image',$('#image')[0].files[0]);
        }
        formData.append('link',$('#link').val());
        formData.append('code',$('#code').val());
        formData.append('startdate',$('#startdate').val());
        formData.append('enddate',$('#enddate').val());
        formData.append('amount',$('#amount').val());
        formData.append('name',$('#name').val());
        formData.append('contact',$('#contact').val());
        formData.append('email',$('#email').val());
        formData.append('address',$('#address').val());
        formData.append('state',$('#state').val());
        formData.append('city',$('#city').val());
        formData.append('pincode',$('#pincode').val());
		formData.append('category',$(".category:checked").map(function(){return $(this).val();}).toArray());
		let adid=$(this).attr('adid');
        $.ajax({
			url:`{{ URL::to('update-ad/${adid}') }}`,
            type:'POST',
            processData: false,
            contentType: false,
            dataType:'json',
            data:formData,
            success:function(result){
                if(result.msgCode === '200') {
                    toastr.success(result.msgText);
                    window.location = "{{ URL::to('manage-ad') }}";
                } else if (result.msgCode === '401') {
                    if(result.errors.title) {
                        $('#title-err').html(result.errors.title[0]);
                    }
                    if(result.errors.type) {
                        $('#type-err').html(result.errors.type[0]);
                    }
                    if(result.errors.page) {
                        $('#page-err').html(result.errors.page[0]);
                    }
                    if(result.errors.position) {
                        $('#position-err').html(result.errors.position[0]);
                    }
                    if(result.errors.image) {
                        $('#image-err').html(result.errors.image[0]);
                    }
                    if(result.errors.link) {
                        $('#link-err').html(result.errors.link[0]);
                    }
                    if(result.errors.code) {
                        $('#code-err').html(result.errors.code[0]);
                    }
                    if(result.errors.startdate) {
                        $('#startdate-err').html(result.errors.startdate[0]);
                    }
                    if(result.errors.enddate) {
                        $('#enddate-err').html(result.errors.enddate[0]);
                    }
                    if(result.errors.amount) {
                        $('#amount-err').html(result.errors.amount[0]);
                    }
					if(result.errors.name) {
                        $('#name-err').html(result.errors.name[0]);
                    }
					if(result.errors.contact) {
                        $('#contact-err').html(result.errors.contact[0]);
                    }
					if(result.errors.email) {
                        $('#email-err').html(result.errors.email[0]);
                    }
					if(result.errors.address) {
                        $('#address-err').html(result.errors.address[0]);
                    }
					if(result.errors.state) {
                        $('#state-err').html(result.errors.state[0]);
                    }
					if(result.errors.city) {
                        $('#city-err').html(result.errors.city[0]);
                    }
					if(result.errors.pincode) {
                        $('#pincode-err').html(result.errors.pincode[0]);
                    }
					if(result.errors.category) {
                        $('#category-err').html(result.errors.category[0]);
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
	$(document).on("change","#type",function(event){
		let type=$(this).val();
		if(type=='custom'){
			$(".customDiv").show();
			$(".googleDiv").hide();
		} else if(type=='google'){
			$(".customDiv").hide();
			$(".googleDiv").show();
		} else {
			$(".customDiv").hide();
			$(".googleDiv").hide();
		}
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
	$(document).on('change','#page',function(event){
		let page=$(this).val();
		if(page=='homepage'){
			$html='<option value="">Select Position</option><option value="uppersidebar300x250">Upper Sidebar (300x250)</option><option value="middlesidebar300x250">Middle Sidebar (300x250)</option><option value="lowersidebar300x250">Lower Sidebar (300x250)</option><option value="uppersidebar300x600">Upper Sidebar (300x600)</option><option value="middlesidebar300x600">Middle Sidebar (300x600)</option><option value="lowersidebar300x600">Lower Sidebar (300x600)</option><option value="upperbanner728x90">Upper Banner (728x90)</option><option value="middlebanner728x90">Middle Banner (728x90)</option><option value="lowerbanner728x90">Lower Banner (728x90)</option><option value="lowestbanner728x90">Lowest Banner (728x90)</option>';
			$('.categoryDiv').hide();
		} else if(page=='categorypage') {
			$html='<option value="">Select Position</option><option value="sidebar300x250">Sidebar (300x250)</option><option value="sidebar300x600">Sidebar (300x600)</option>';
			$('.categoryDiv').show();
		} else if(page=='postpage') {
			$html='<option value="">Select Position</option><option value="uppersidebar300x250">Upper Sidebar (300x250)</option><option value="lowersidebar300x250">Lower Sidebar (300x250)</option><option value="sidebar300x600">Sidebar (300x600)</option>';
			$('.categoryDiv').hide();
		} else {
			$html='';
			$('.categoryDiv').hide();
		}
		$("#position").html($html);
	});
});
</script>