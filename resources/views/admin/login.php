<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="css/bootstrap-4.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
	<link rel="stylesheet" href="css/style.css">
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-md-5 mx-auto">
				<div class="myform form ">
					<div class="logo mb-3">
						<img src="images/logo.png" class="img-fluid">
					</div>
					<form name="login">
						<div class="form-group">
							<label for="exampleInputId">Email</label>
							<input type="text" name="id" class="form-control" id="email" placeholder="Enter Email">
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">Password</label>
							<input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
						</div>
						<div class="form-group row">
							<div class="col-md-12 text-center ">
								<button type="submit" class=" btn btn-block mybtn btn-dark tx-tfm">Login</button>
							</div>
						</div>
						<div class="form-group text-center">
							<a href="#forgot-pass" data-target="#forgot-pass" data-toggle="modal">Forgot Password ?</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="forgot-pass" tabindex="-1" role="dialog" aria-labelledby="forgot-pass" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="forgot-pass">Forgot Password</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
				
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<div class="col-sm-12">
							<label class="label-control">Email Address</label>
							<input type="text" class="text-control" placeholder="Enter Registered Email Address">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-12 text-center">

							<button type="button" class="btn btn-dark">Reset Now</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="js/jquery.js" type="text/javascript"></script>
	<script src="js/poppers.js" type="text/javascript"></script>
	<script src="js/bootstrap-4.js" type="text/javascript"></script>
</body>
</html>