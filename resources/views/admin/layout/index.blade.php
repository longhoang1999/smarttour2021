<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="shortcut icon" href="{{asset('images/icons/favicon.ico')}}">
	<link rel="stylesheet" href="{{asset('vendor/bootstrap/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('fontawesome-free-5.14.0-web/css/all.css')}}">
	<link rel="stylesheet" href="{{asset('css/admin.css')}}">
	<title>
		@section('title')
            | TOUR ADVICE ADMIN
        @show
	</title>
	<!--page level css-->
  	@yield('header_styles')
  	<!--end of page level css-->
</head>
<body>
	<div id="main_div">
		<div id="sitebar">
			<div class="name">
				<span>TOUR ADVICE</span>
			</div>
			<div class="dashboard box0">
				<a href="{{route('admin.generalInfor')}}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
			</div>
			<div class="dashboard box1">
				<a href="{{route('admin.dashboard')}}"><i class="fas fa-portrait"></i> Account Information</a>
			</div>
			<div class="user" id="div_function">
				<div class="function_one">
					<div class="user_heading">Function</div>
					<ul>
						<li>
							<a href="#" class="contents">
								<i class="fas fa-map-marker-alt"></i>
								Place Management
								<i class="fas fa-chevron-right after"></i>
							</a>
							<div class="user_content">
								<p>More Function</p>
								<a class="addPlace" href="{{route('admin.addPlace')}}">Add Place</a>
								<br>
								<a class="editPlace" href="{{route('admin.editPlace')}}">Edit Place</a>
								<br>
								<a class="removePlace" href="{{route('admin.removePlace')}}">Delete Place</a>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="dashboard box3">
				<a href="{{route('admin.feedback')}}"><i class="far fa-comments"></i> Feedback Information</a>
			</div>
		</div>
		<div id="main">
			<div class="container-fluid header">
				<div class="row content">
					<div class="input_group col-md-5">
						<a href="{{route('user.dashboard')}}" target="_blank" class="btn btn-info">Go to website</a>
					</div>
					<div class="col-md-7 text-right">
						<div id="div_admin" class="float-right">
							<span>{{$us_fullName}}</span>
							<img src="{{asset('assets/img/portfolio/cabin.png')}}" alt="" class="avatar">
							<div class="profile_admin" style="z-index: 10">
								<ul>
									<!-- đường kẻ -->
									<!-- <div class="dropdown-divider"></div> -->
									<li>
										<a href="{{route('logout')}}">
											<i class="fas fa-sign-out-alt"></i>
											<span>Logout</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="content-main">
				@yield('content')
			</div>
			<div class="footer">
				<p>Copyright © Tour Advice 2021</p>
			</div>
		</div>
	</div>
	<!-- Js -->
	<script type="text/javascript" src="{{asset('vendor/jquery/jquery-3.2.1.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('vendor/bootstrap/js/bootstrap.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/admin.js')}}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script type="text/javascript" src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
	@yield('footer-js')
	<script type="text/javascript">
		$(document).ready(function(){
			$("#sitebar .name").click(function(){
				window.location.replace("{{route('admin.generalInfor')}}");
			});
		});
	</script>
</body>
</html>