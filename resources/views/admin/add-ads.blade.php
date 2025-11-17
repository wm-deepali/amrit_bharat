@include('admin.header')
<section class="breadcrumb-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<div class="content-header">
					<h3 class="content-header-title">Advertisement</h3>
					<button type="button" class="btn btn-dark float-right add-ad-btn"><i class="fas fa-plus-circle"></i> Add Ad</button>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
						</li>
						<li class="breadcrumb-item"><a href="{{ route('manage-ad.index') }}">Manage Ads</a>
						</li>
						<li class="breadcrumb-item active">Add Ads</li>
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
									<input type="text" class="text-control" placeholder="Enter Ad Title" name="title" id="title">
									<div class="text-danger" id="title-err"></div>
								</div>
								<div class="col-sm-4">
									<label class="label-control">Ad Type </label>
									<select class="text-control" id="type" name="type">
										<option value="">Select Type</option>
										<option value="custom">Custom Ad</option>
										<option value="google">Google Code</option>
									</select>
									<div class="text-danger" id="type-err"></div>
								</div>
								<div class="col-sm-4">
									<label class="label-control">Ad Page </label>
									<select class="text-control" name="page" id="page">
										<option value="">Select Page</option>
										<option value="homepage">Homepage</option>
										<option value="categorypage">Category Page</option>
										<option value="postpage">Post Page</option>
									</select>
									<div class="text-danger" id="page-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-4">
									<label class="label-control">Ad Position </label>
									<select class="text-control" name="position" id="position">
										<option value="">Select Position</option>
									</select>
									<div class="text-danger" id="position-err"></div>
								</div>
								<div class="col-sm-4 customDiv" style="display: none;">
									<label class="label-control">Ad Image </label>
									<input type="file" class="text-control" name="image" id="image">
									<div class="text-danger" id="image-err"></div>
								</div>
								<div class="col-sm-4 customDiv" style="display: none;">
									<label class="label-control">Ad Link </label>
									<input type="text" class="text-control" placeholder="Enter URL" name="link" id="link">
									<div class="text-danger" id="link-err"></div>
								</div>
								<div class="col-sm-8 googleDiv" style="display: none;">
									<label class="label-control">Google Ad Code </label>
									<input type="text" placeholder="Enter Code" class="text-control" name="code" id="code">
									<div class="text-danger" id="code-err"></div>
								</div>
							</div>
							<div class="form-group row categoryDiv" style="display: none">
								<div class="col-sm-4">
									<div class="category-side">
										<h3>Categories</h3>
										<div class="text-danger" id="category-err"></div>
										<ul>
											@if (isset($categories) && count($categories)>0)
											@foreach ($categories as $category)
											<li>
												<label>
													<input type="checkbox" class="category" name="category[]" value="{{ $category->id }}"> {{ $category->name }}
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
									<input type="date" class="text-control" placeholder="Start Date" name="startdate" id="startdate">
									<div class="text-danger" id="startdate-err"></div>
								</div>
								<div class="col-sm-4">
									<label class="label-control">End Date</label>
									<input type="date" class="text-control" placeholder="End Date" name="enddate" id="enddate">
									<div class="text-danger" id="enddate-err"></div>
								</div>
								<div class="col-sm-4 customDiv">
									<label class="label-control">Amount</label>
									<input type="number" class="text-control" placeholder="Enter Amount" name="amount" id="amount">
									<div class="text-danger" id="amount-err"></div>
								</div>
							</div>

							<div class="form-group row customDiv">
								<div class="col-sm-4">
									<label class="label-control">Payment Method</label>
									<select class="text-control" id="paymentmethod" name="paymentmethod">
										<option value="">Select Mode</option>
										<option value="cash">Cash</option>
										<option value="cheque">Cheque/DD</option>
										<option value="netbanking">Net Banking</option>
										<option value="upi">UPI</option>
										<option value="wallet">Wallet</option>
									</select>
									<div class="text-danger" id="paymentmethod-err"></div>
								</div>
								<!--Cash-->
								<div class="col-sm-4 cashDiv" style="display: none;">
									<label class="label-control">Collected Person</label>
									<input type="text" class="text-control" placeholder="Enter Collected Person" name="collectedby" id="collectedby">
									<div class="text-danger" id="collectedby-err"></div>
								</div>

								<div class="col-sm-4">
									<label class="label-control">Remark</label>
									<input type="text" class="text-control" placeholder="Enter Remark" name="remark" id="remark">
									<div class="text-danger" id="remark-err"></div>
								</div>

								<!--Chequee/DD-->
								<div class="col-sm-4 chequeDiv" style="display: none;">
									<label class="label-control">Cheque date</label>
									<input type="date" class="text-control" name="chequedate" id="chequedate">
									<div class="text-danger" id="chequedate-err"></div>
								</div>
								<div class="col-sm-4 chequeDiv" style="display: none;">
									<label class="label-control">Cheque No.</label>
									<input type="text" placeholder="Enter Cheque No." class="text-control" name="chequenumber" id="chequenumber">
									<div class="text-danger" id="chequenumber-err"></div>
								</div>
								<div class="col-sm-4 chequeDiv mt-2" style="display: none;">
									<label class="label-control">Bank Name</label>
									<input type="text" placeholder="Enter Bank Name" class="text-control" name="bankname" id="bankname">
									<div class="text-danger" id="bankname-err"></div>
								</div>
								<div class="col-sm-4 chequeDiv mt-2" style="display: none;">
									<label class="label-control">Bank Branch</label>
									<input type="text" placeholder="Enter Bank Branch" class="text-control" name="bankbranch" id="bankbranch">
									<div class="text-danger" id="bankbranch-err"></div>
								</div>

								<!--NET Banking-->
								<div class="col-sm-4 netbankingDiv" style="display: none;">
									<label class="label-control">UTR Number</label>
									<input type="text" class="text-control" placeholder="Enter UTR Number" name="utrnumber" id="utrnumber">
									<div class="text-danger" id="utrnumber-err"></div>
								</div>
								<div class="col-sm-4 netbankingDiv" style="display: none;">
									<label class="label-control">Payment Date</label>
									<input type="date" class="text-control" name="utrdate" id="utrdate">
									<div class="text-danger" id="utrdate-err"></div>
								</div>

								<!--UPI-->
								<div class="col-sm-4 upiDiv" style="display: none;">
									<label class="label-control">UPI ID</label>
									<input type="text" class="text-control" placeholder="Enter UPI ID" name="upiid" id="upiid">
									<div class="text-danger" id="upiid-err"></div>
								</div>
								<div class="col-sm-4 upiDiv" style="display: none;">
									<label class="label-control">Payment Date</label>
									<input type="date" class="text-control" name="upidate" id="upidate">
									<div class="text-danger" id="upidate-err"></div>
								</div>
								<div class="col-sm-4 upiDiv mt-2" style="display: none;">
									<label class="label-control">Ref No.</label>
									<input type="text" class="text-control" placeholder="Enter Ref No." name="upireferencenumber" id="upireferencenumber">
									<div class="text-danger" id="upireferencenumber-err"></div>
								</div>

								<!--Wallet-->
								<div class="col-sm-4 walletDiv" style="display: none;">
									<label class="label-control">Wallet</label>
									<select class="text-control" name="wallet" id="wallet">
										<option value="">Select Wallet</option>
										<option value="paytm">Paytm</option>
									</select>
									<div class="text-danger" id="wallet-err"></div>
								</div>
								<div class="col-sm-4 walletDiv" style="display: none;">
									<label class="label-control">Payment Date</label>
									<input type="date" class="text-control" name="walletdate" id="walletdate">
									<div class="text-danger" id="walletdate-err"></div>
								</div>
								<div class="col-sm-4 walletDiv mt-2" style="display: none;">
									<label class="label-control">Ref No.</label>
									<input type="text" class="text-control" placeholder="Enter Ref No." name="walletreferencenumber" id="walletreferencenumber">
									<div class="text-danger" id="walletreferencenumber-err"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3 col-md-4 customDiv">
				<div class="card">
					<div class="card-body">
						<div class="card-block">
							<h4 class="form-section-h">Client Details</h4>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">Name</label>
									<input type="text" class="text-control" placeholder="Enter Name" name="name" id="name">
									<div class="text-danger" id="name-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">Mobile No.</label>
									<input type="text" class="text-control" placeholder="Enter Mobile No." name="contact" id="contact">
									<div class="text-danger" id="contact-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">Email</label>
									<input type="text" class="text-control" placeholder="Enter Email" name="email" id="email">
									<div class="text-danger" id="email-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">Address</label>
									<input type="text" class="text-control" placeholder="Enter Address" name="address" id="address">
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
										<option value="{{ $state->id }}">{{ $state->name }}</option>
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
									</select>
									<div class="text-danger" id="city-err"></div>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-12">
									<label class="label-control">Pincode</label>
									<input type="text" class="text-control" placeholder="Enter Pincode" name="pincode" id="pincode">
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
	$(document).on('click','.add-ad-btn',function(event){
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
        $('#paymentmethod-err').html('');
        $('#collectedby-err').html('');
        $('#remark-err').html('');
        $('#chequedate-err').html('');
        $('#chequenumber-err').html('');
        $('#bankname-err').html('');
        $('#bankbranch-err').html('');
        $('#utrnumber-err').html('');
        $('#utrdate-err').html('');
        $('#upiid-err').html('');
        $('#upidate-err').html('');
        $('#upireferencenumber-err').html('');
		$('#wallet-err').html('');
        $('#walletdate-err').html('');
        $('#walletreferencenumber-err').html('');
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
        formData.append('paymentmethod',$('#paymentmethod').val());
        formData.append('collectedby',$('#collectedby').val());
        formData.append('remark',$('#remark').val());
        formData.append('chequedate',$('#chequedate').val());
        formData.append('chequenumber',$('#chequenumber').val());
        formData.append('bankname',$('#bankname').val());
        formData.append('bankbranch',$('#bankbranch').val());
        formData.append('utrnumber',$('#utrnumber').val());
        formData.append('utrdate',$('#utrdate').val());
        formData.append('upiid',$('#upiid').val());
        formData.append('upidate',$('#upidate').val());
        formData.append('upireferencenumber',$('#upireferencenumber').val());
		formData.append('wallet',$('#wallet').val());
        formData.append('walletdate',$('#walletdate').val());
        formData.append('walletreferencenumber',$('#walletreferencenumber').val());
        formData.append('name',$('#name').val());
        formData.append('contact',$('#contact').val());
        formData.append('email',$('#email').val());
        formData.append('address',$('#address').val());
        formData.append('state',$('#state').val());
        formData.append('city',$('#city').val());
        formData.append('pincode',$('#pincode').val());
		formData.append('category',$(".category:checked").map(function(){return $(this).val();}).toArray());
        $.ajax({
            url:"{{ URL::to('manage-ad') }}",
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
                    if(result.errors.paymentmethod) {
                        $('#paymentmethod-err').html(result.errors.paymentmethod[0]);
                    }
                    if(result.errors.collectedby) {
                        $('#collectedby-err').html(result.errors.collectedby[0]);
                    }
					if(result.errors.remark) {
                        $('#remark-err').html(result.errors.remark[0]);
                    }
					if(result.errors.chequedate) {
                        $('#chequedate-err').html(result.errors.chequedate[0]);
                    }
					if(result.errors.chequenumber) {
                        $('#chequenumber-err').html(result.errors.chequenumber[0]);
                    }
					if(result.errors.bankname) {
                        $('#bankname-err').html(result.errors.bankname[0]);
                    }
					if(result.errors.bankbranch) {
                        $('#bankbranch-err').html(result.errors.bankbranch[0]);
                    }
					if(result.errors.utrnumber) {
                        $('#utrnumber-err').html(result.errors.utrnumber[0]);
                    }
					if(result.errors.utrdate) {
                        $('#utrdate-err').html(result.errors.utrdate[0]);
                    }
					if(result.errors.upiid) {
                        $('#upiid-err').html(result.errors.upiid[0]);
                    }
					if(result.errors.upidate) {
                        $('#upidate-err').html(result.errors.upidate[0]);
                    }
					if(result.errors.upireferencenumber) {
                        $('#upireferencenumber-err').html(result.errors.upireferencenumber[0]);
                    }
					if(result.errors.wallet) {
                        $('#wallet-err').html(result.errors.wallet[0]);
                    }
					if(result.errors.walletdate) {
                        $('#walletdate-err').html(result.errors.walletdate[0]);
                    }
					if(result.errors.walletreferencenumber) {
                        $('#walletreferencenumber-err').html(result.errors.walletreferencenumber[0]);
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
	$(document).on("change","#paymentmethod",function(event){
		let paymentmethod=$(this).val();
		if(paymentmethod=='cash'){
			$(".cashDiv").show();
			$(".chequeDiv").hide();
			$(".netbankingDiv").hide();
			$(".upiDiv").hide();
			$(".walletDiv").hide();
		} else if(paymentmethod=='cheque') {
			$(".cashDiv").hide();
			$(".chequeDiv").show();
			$(".netbankingDiv").hide();
			$(".upiDiv").hide();
			$(".walletDiv").hide();
		} else if(paymentmethod=='netbanking') {
			$(".cashDiv").hide();
			$(".chequeDiv").hide();
			$(".netbankingDiv").show();
			$(".upiDiv").hide();
			$(".walletDiv").hide();
		} else if(paymentmethod=='upi') {
			$(".cashDiv").hide();
			$(".chequeDiv").hide();
			$(".netbankingDiv").hide();
			$(".upiDiv").show();
			$(".walletDiv").hide();
		} else if(paymentmethod=='wallet') {
			$(".cashDiv").hide();
			$(".chequeDiv").hide();
			$(".netbankingDiv").hide();
			$(".upiDiv").hide();
			$(".walletDiv").show();
		} else {
			$(".cashDiv").hide();
			$(".chequeDiv").hide();
			$(".netbankingDiv").hide();
			$(".upiDiv").hide();
			$(".walletDiv").hide();
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