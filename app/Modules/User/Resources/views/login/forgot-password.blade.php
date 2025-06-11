<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Reset Password</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{asset('admin/global/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{asset('admin/assets/css/all.min.css')}}" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{asset('admin/global/js/main/jquery.min.js')}}"></script>
	<script src="{{asset('admin/global/js/main/bootstrap.bundle.min.js')}}"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="{{asset('admin/js/app.js')}}"></script>
	<!-- /theme JS files -->

	<script>
		$(document).ready(function () {
			const togglePassword = document.querySelector('#togglePassword');
			const password = document.querySelector('#password');

			togglePassword.addEventListener('click', function (e) {
				// toggle the type attribute
				const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
				password.setAttribute('type', type);
				// toggle the eye slash icon
				this.classList.toggle('icon-eye-blocked');
			});
		});
	</script>

	<style type="text/css">
		.login-cover {
		  background: url(/admin/global/images/hrms_bg.jpg) no-repeat;
		    background-size: auto;
		  background-size: cover;
		}
	</style>

</head>

<body>

	<!-- Page content -->
	<div class="page-content login-cover">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Inner content -->
			<div class="content-inner">
			

				<!-- Content area -->
				<div class="content d-flex justify-content-center align-items-center" style="padding-left: 35%;">

					<!-- Login card -->
                    {!! Form::open(['route'=>'reset-password-link','method'=>'POST','class'=>'login-form wmin-sm-400','role'=>'form','files' => true]) !!}
					<div class="card mb-0 border-top-teal" style="border-radius: 40px;background-color: #3a606ec9;">
							<div class="card-body">
								<div class="text-center mb-3">
									<h5 class="mb-0 mt-2"><b class="text-dark">Reset Password</b></h5>
								</div>
								<div class="form-group form-group-feedback form-group-feedback-left">
									{!! Form::text('username', $value = null, ['id'=>'username','placeholder'=>'Enter your Username','class'=>'form-control']) !!}
									<div class="form-control-feedback">
										<i class="icon-user text-success"></i>
									</div>
								</div>

								<div class="form-group">
									<button type="submit" class="btn btn-warning btn-block"><b>Reset Password</b></button>
								</div>
								

								   <span class="navbar-text text-center text-light">
                                    &copy; {{date('Y')}}. <span class="text-light">HRMS</span> by <a href="http://bidhee.com/" target="_blank" class="text-light">Bidhee Pvt. Ltd</a>
                                </span>
								
							</div>
						</div>
					</form>
					<!-- /login card -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /inner content -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->

</body>
</html>
