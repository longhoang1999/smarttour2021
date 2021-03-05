<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Email verification notification</title>
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
</head>
<body>
	<style>
		body{padding: 0;margin: 0;}
		.container-login100::before {
		    content: "";
		    display: block;
		    position: absolute;
		    z-index: -1;
		    width: 100%;
		    height: 100%;
		    top: 0;
		    left: 0;
		    background-color: rgb(235 233 249 / 90%);
		}
		#text_title{position: absolute;top: 0;font-size: 10rem;font-weight: bold;color: #dde4ea;     text-shadow: 20px 13px 12px #1866da;cursor: pointer;}
		#text_content h1{
			font-style: italic;
			text-align: center;
			font-family: auto;
			font-weight: bold;
		}
		#text_content h2{
			margin-top: 2rem;
			font-style: italic;
			text-decoration: underline;
			text-align: center;
			font-family: auto;
			font-weight: bold;
		}
	</style>	
	<div class="container-login100" style="background: url('{{asset('images/ss.png')}}');width: 100%;min-height: 100vh;flex-wrap: wrap;display: flex;justify-content: center;align-items: center;background-repeat: no-repeat;background-size: cover;background-position: center;z-index: 1;position:relative; ">
		<div id="text_title" class="text-uppercase">tour advice</div>
		<div id="text_content">
			<h1>email verification notification</h1>
			@if($status == "success")
				<h2 class="text-success">Email verification is successful</h2>
			@elseif($status == "wrongKey")
				<h2 class="text-danger">Wrong verification code. Please review the verification code in your email</h2>
			@elseif($status=="authenticated")
				<h2 class="text-danger">Your account has been previously verified</h2>
			@endif
		</div>
	</div>
	<script src="{{asset('vendor/jquery/jquery-3.2.1.min.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#text_title").click(function(){
				var win = window.open("{{route('login')}}", '_blank');
			});
		});
	</script>
</body>
</html>