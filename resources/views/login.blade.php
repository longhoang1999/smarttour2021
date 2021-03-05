<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login V3</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
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
	<link rel="stylesheet" href="{{asset('css/login.css')}}">	
</head>
<body>
	<div class="header" style="background: url('{{asset('images/banner.png')}}');background-size: cover;background-repeat: no-repeat;">
		<p class="namePage">TOUR ADVICE</p>
		<p class="moreInfor">Specializes in consulting introduce tours to famous places</p>
	</div>
	<div class="limiter">
		<div class="container-login100 body_main" style="background-image: url('{{asset('images/ss.png')}}');">
			<a href="#div_login" class="text-uppercase text_title">tour advice system</a>
			<div class="wrap-login100" id="div_login">
				<form class="login100-form validate-form" method="post" action="{{route('postLogin')}}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<span class="login100-form-logo">
						<i class="zmdi zmdi-landscape"></i>
					</span>

					<span class="login100-form-title p-b-34 p-t-27">
						Login
					</span>
					@if ($message = Session::get('error'))
						<div class="alert alert-danger alert-block">
					        <button type="button" class="close" data-dismiss="alert">x</button>
					        <strong>{{$message}}</strong>
					    </div>
					@endif
					@if ($message = Session::get('success'))
						<div class="alert alert-success alert-block">
					        <button type="button" class="close" data-dismiss="alert">x</button>
					        <strong>{{$message}}</strong>
					    </div>
					@endif
					@if (count($errors) > 0)
					    <div class="alert alert-danger">
					        <ul>
					            @foreach ($errors->all() as $error)
					                <li>{{ $error }}</li>
					            @endforeach
					        </ul>
					    </div>
					@endif
					<div class="wrap-input100 validate-input" data-validate = "Enter email">
						<input class="input100" type="email" name="us_email" placeholder="Email">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="us_password" placeholder="Password">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="container-login100-form-btn">
						<button type="submit" class="login100-form-btn">
							Login
						</button>
						<input type="button" id="btn_register" data-toggle="modal" data-target="#modalRegis" value="Registration">

					</div>
					<div class="text-center p-t-90">
						<p class="txt1" id="btn_forgot">
							Forgot password?
						</p>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Modal reggis -->
	<div class="modal fade" id="modalRegis" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLabel">User Registration</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <form action="{{route('register')}}" method="post">
	        	<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	        	<div class="container-fluid">
	        		<div class="row">
	        			<div class="col-md-3 col-sm-6 col-6 mb-3">
	        				<p class="text-left font-weight-bold">Email</p>
	        			</div>
	        			<div class="col-md-9 col-sm-6 col-6 mb-3">
	        				<input type="email" class="form-control" placeholder="Enter Email" required="" name="email">
	        			</div>
	        			<div class="col-md-3 col-sm-6 col-6 mb-3">
	        				<p class="text-left font-weight-bold">Password</p>
	        			</div>
	        			<div class="col-md-9 col-sm-6 col-6 mb-3">
	        				<input type="password" class="form-control" placeholder="Enter password" name="password" required="">
	        			</div>
	        			<div class="col-md-3 col-sm-6 col-6 mb-3">
	        				<p class="text-left font-weight-bold">Confirm password</p>
	        			</div>
	        			<div class="col-md-9 col-sm-6 col-6 mb-3">
	        				<input type="password" class="form-control" placeholder="Enter password" name="confirm" required="">
	        			</div>
	        			<div class="col-md-3 col-sm-6 col-6 mb-3">
	        				<p class="text-left font-weight-bold">Full Name</p>
	        			</div>
	        			<div class="col-md-9 col-sm-6 col-6 mb-3">
	        				<input type="text" class="form-control" placeholder="Enter your full name" name="fullname" required="">
	        			</div>
	        			<div class="col-md-3 col-sm-6 col-6 mb-3">
	        				<p class="text-left font-weight-bold">Gender</p>
	        			</div>
	        			<div class="col-md-9 col-sm-6 col-6 mb-3">
	        				<select class="form-control" name="gender">
	        					<option value="Male">Male</option>
	        					<option value="Female">Female</option>
	        				</select>
	        			</div>
	        			<div class="col-md-3 col-sm-6 col-6 mb-3">
	        				<p class="text-left font-weight-bold">Age</p>
	        			</div>
	        			<div class="col-md-9 col-sm-6 col-6 mb-3">
	        				<input type="number" class="form-control" placeholder="Enter your age" name="age" required="">
	        			</div>
	        		</div>
	        	</div>
	        	<hr>
	        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        	<input type="submit" class="btn btn-primary" value="Registration">
	        </form>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- Modal forgotpass -->
	<div class="modal fade" id="modalForgotPass" tabindex="-1" role="dialog" aria-labelledby="modalForgotPassLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="modalForgotPassLabel">Forgot Password</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <div class="container-fluid">
	        	<div class="row">
	        		<div class="col-md-6 col-sm-12 col-12 mb-2">
	        			<p class="pt-2 font-weight-bold">Enter your email: </p>
	        		</div>
	        		<div class="col-md-6 col-sm-12 col-12 mb-2">
	        			<p id="icon_correct" class="text-success"><i class="fas fa-check"></i> correct email</p>
	        			<p id="icon_incorrect" class="text-danger"><i class="fas fa-check"></i> email is incorrect</p>
	        			<input type="text" class="form-control" placeholder="Enter your email" id="inputEmail">
	        		</div>
	        		<div class="col-md-6 col-sm-12 col-12 mb-2">
	        		</div>
	        		<div class="col-md-6 col-sm-12 col-12 mb-2">
	        			<button type="button" class="btn btn-info" id="btn_senKey">Send key</button>
	        		</div>
	        	</div>
	        	<div class="row" id="formCheckKey">
	        		<div class="col-md-6 col-sm-12 col-12 mb-2">
	        			<p class="pt-2 font-weight-bold">Enter your key: </p>
	        		</div>
	        		<div class="col-md-6 col-sm-12 col-12 mb-2">
	        			<p id="key_incorrect" class="text-danger"><i class="fas fa-check"></i> key is incorrect</p>
	        			<input type="text" class="form-control text-uppercase" placeholder="Enter your key" id="inputKey">
	        		</div>
	        	</div>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- Change pass -->
	<div class="modal fade" id="changePass" tabindex="-1" role="dialog" aria-labelledby="changePassLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="changePassLabel">Notification</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <h4 class="text-success">Your password has been changed to your email. Access your account and change your password!</h4>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div>
	<div id="dropDownSelect1"></div>

	<footer class="footer text-center">
        <div class="container">
            <div class="row">
                <!-- Footer Location-->
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <h4 class="text-uppercase mb-4">Location</h4>
                    <p class="lead mb-0">
                        HA NOI
                        <br />
                        VIET NAM
                    </p>
                </div>
                <!-- Footer Social Icons-->
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <h4 class="text-uppercase mb-4">Around the Web</h4>
                    <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-twitter"></i></a>
                    <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-linkedin-in"></i></a>
                    <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-dribbble"></i></a>
                </div>
                <!-- Footer About Text-->
                <div class="col-lg-4">
                    <h4 class="text-uppercase mb-4">About tour advice</h4>
                    <p class="lead mb-0">
                        Website is designed to give you a great experience
                        
                    </p>
                </div>
            </div>
        </div>
    </footer>
	<!-- Copyright Section-->
    <div class="copyright py-4 text-center text-white">
        <div class="container"><small>Copyright © Your Website 2021</small></div>
    </div>
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
<!--===============================================================================================--> <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
	<script src="{{asset('js/main.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".namePage").click(function(){
				location.replace("{{route('login')}}");
			});
			$("#btn_forgot").click(function(){
				$("#modalForgotPass").modal("show");
			});
			$("#inputEmail").keyup(function(){
				let _token = $('meta[name="csrf-token"]').attr('content');
                let $url_path = '{!! url('/') !!}';
                let routeCheckForgot=$url_path+"/checkForgot";
                let input = $("#inputEmail").val();
                $.ajax({
                      url:routeCheckForgot,
                      method:"POST",
                      data:{_token:_token,input:input},
                      success:function(data){ 
                      	if(input == "")
                      	{
                      		$("#icon_correct").css("display","none");
                        	$("#icon_incorrect").css("display","none");
                        	$("#btn_senKey").css("display","none");
                      	}
                      	else
                      	{
                      		if(data=="true")
	                        {
	                        	$("#icon_correct").css("display","block");
	                        	$("#btn_senKey").css("display","block");
	                        	$("#icon_incorrect").css("display","none");
	                        }
	                        else if(data="false")
	                        {
	                        	$("#icon_correct").css("display","none");
	                        	$("#btn_senKey").css("display","none");
	                        	$("#icon_incorrect").css("display","block");
	                        }
                      	}
                     }
                });
			});
			$("#btn_senKey").click(function(){
				let _token = $('meta[name="csrf-token"]').attr('content');
                let $url_path = '{!! url('/') !!}';
                let routeSendKey=$url_path+"/senkey";
                let input = $("#inputEmail").val();
                $.ajax({
                      url:routeSendKey,
                      method:"POST",
                      data:{_token:_token,input:input},
                      success:function(data){ 
                      	if(data=="true")
                      	{
                      		$("#inputEmail").attr("readonly","");
                      		$("#formCheckKey").css("display","flex");
                      	}
                      	if(data == "false")
                      	{
                      		alert("Cannot send email");
                      	}
                     }
                });
			});
			$("#inputKey").keyup(function(){
				let _token = $('meta[name="csrf-token"]').attr('content');
                let $url_path = '{!! url('/') !!}';
                let routeCheckKey=$url_path+"/checkkey";
                let email = $("#inputEmail").val();
                let input = $("#inputKey").val();
                $.ajax({
                      url:routeCheckKey,
                      method:"POST",
                      data:{_token:_token,input:input,email:email},
                      success:function(data){
                      	if(input == "")
                      	{
                      		$("#key_incorrect").css("display","none");
                      	}
                      	else
                      	{
                      		if(data=="true")
	                      	{
	                      		$("#changePass").modal("show");
	                      		$("#modalForgotPass").modal("hide");
	                      	}
	                      	if(data == "false")
	                      	{
	                      		$("#key_incorrect").css("display","block");
	                      	}
                      	}
                     }
                });
			});
		});
	</script>
</body>
</html>