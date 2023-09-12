<!DOCTYPE html>
<html lang="en">
<head>
	<title>PackageApp</title>
	<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{asset('images/icons/favicon.ico')}}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('fonts/iconic/css/material-design-iconic-font.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('vendor/animate/animate.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('vendor/animsition/css/animsition.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{asset('vendor/daterangepicker/daterangepicker.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{asset('css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/main.css')}}">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100" style="background-image: url('{{asset('images/bg-01.jpg')}}');">
			<div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
				<form class="login100-form validate-form" method="post" action="{{route('create-session')}}">
                    {{csrf_field()}}
					<span class="login100-form-title p-b-49">
						Book Session
					</span>
					@if(session('error'))
    					<div class="alert alert-danger">
        					{{ session('error') }}
    					</div>
					@endif
                    <div class="wrap-input100 validate-input m-b-23" data-validate = "Date is required">
						<span class="label-input100">Date</span>
						<input class="input100" type="date" name="date" min="<?php echo date('Y-m-d'); ?>" placeholder="Type the date">
						<span class="focus-input100" data-symbol="&#x2605;"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-23" data-validate = "'From' is required">
						<span class="label-input100">From</span>
						<input class="input100" type="time" name="from" placeholder="Type the 'from' time">
						<span class="focus-input100" data-symbol="&#x2605;"></span>
					</div>

                    <div class="wrap-input100 validate-input m-b-23" data-validate = "'To' is required">
						<span class="label-input100">To</span>
						<input class="input100" type="time" name="to" placeholder="Type the 'to' time">
						<span class="focus-input100" data-symbol="&#x2605;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Price is required">
						<span class="label-input100">Price</span>
						<input class="input100" type="number" min="0" name="price" placeholder="Type the price">
						<span class="focus-input100" data-symbol="&euro;"></span>
					</div>
					
					<div class="text-right p-t-8 p-b-31">

					</div>
					
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn">
								Add To Schedule
							</button>
						</div>
					</div>

					
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="{{asset('vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('vendor/animsition/js/animsition.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('vendor/bootstrap/js/popper.js')}}"></script>
	<script src="{{asset('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('vendor/daterangepicker/moment.min.js')}}"></script>
	<script src="{{asset('vendor/daterangepicker/daterangepicker.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('vendor/countdowntime/countdowntime.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{asset('js/main.js')}}"></script>

</body>
</html>