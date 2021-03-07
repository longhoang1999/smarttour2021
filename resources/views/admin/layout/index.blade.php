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
				<a href="{{route('admin.generalInfor')}}"><i class="fas fa-tachometer-alt"></i> {{ trans('admin.Dashboard') }}</a>
			</div>
			<div class="dashboard box1">
				<a href="{{route('admin.dashboard')}}"><i class="fas fa-portrait"></i> {{ trans('admin.accountInformation') }}</a>
			</div>
			<div class="user" id="div_function">
				<div class="function_one">
					<div class="user_heading">{{ trans('admin.Function') }}</div>
					<ul>
						<li>
							<a href="#" class="contents">
								<i class="fas fa-map-marker-alt"></i>
								{{ trans('admin.placeManagement') }}
								<i class="fas fa-chevron-right after"></i>
							</a>
							<div class="user_content">
								<p>{{ trans('admin.moreFunction') }}</p>
								<a class="addPlace" href="{{route('admin.addPlace')}}">{{ trans('admin.addPlace') }}</a>
								<br>
								<a class="editPlace" href="{{route('admin.editPlace')}}">{{ trans('admin.editPlace') }}</a>
								<br>
								<a class="removePlace" href="{{route('admin.removePlace')}}">{{ trans('admin.deletePlace') }}</a>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="dashboard box3">
				<a href="{{route('admin.history')}}"><i class="fas fa-motorcycle"></i> Tour history</a>
			</div>
			<div class="dashboard box4">
				<a href="{{route('admin.feedback')}}"><i class="far fa-comments"></i> {{ trans('admin.feedbackInformation') }}</a>
			</div>
		</div>
		<div id="main">
			<div class="container-fluid header">
				<div class="row content">
					<div class="input_group col-md-5">
						<a href="{{route('user.dashboard')}}" target="_blank" class="btn btn-info">{{ trans('admin.Gotowebsite') }}</a>
					</div>
					<div class="col-md-7 text-right">
						<div id="div_admin" class="float-right">
							<span>{{$us_fullName}}</span>
							<img src="{{asset('assets/img/portfolio/cabin.png')}}" alt="" class="avatar">
							<div class="profile_admin" style="z-index: 10">
								<ul>
									<li>
										<a href="#">
											<i class="fas fa-user-secret"></i>
											<span>{{ trans('admin.Youraccount') }}</span>
										</a>
									</li>
									<li>
										<a href="#" id="a_settingModal">
											<i class="fas fa-cog"></i>
											<span>{{ trans('admin.Setting') }}</span>
										</a>
									</li>
									<div class="dropdown-divider"></div>
									<li>
										<a href="{{route('logout')}}">
											<i class="fas fa-sign-out-alt"></i>
											<span>{{ trans('admin.Logout') }}</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- modal -->
			<div class="modal fade" id="modalSetting" tabindex="-1" role="dialog" aria-labelledby="modalSettingLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="modalSettingLabel">{{ trans('admin.Setting') }}</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <form action="{{route('admin.changeLanguage')}}" method="post">
			      	@csrf
			      	<div class="modal-body">
				        <div class="container-fluid">
				        	<div class="row">
				        		<div class="col-md-6 col-sm-6 col-6 mb-3">
				        			<label class="font-weight-bold text-italic" for="select-language">{{ trans('admin.changeLang') }}</label>
				        		</div>
				        		<div class="col-md-6 col-sm-6 col-6 mb-3">
				        			<select name="lang" id="select-language"class="form-control" >
				        				<option value="vi">{{ trans('admin.VN') }}</option>
				        				<option value="en">{{ trans('admin.EN') }}</option>
				        			</select>
				        		</div>
				        	</div>
				        </div>
				    </div>
				    <div class="modal-footer">
				        <button type="submit" class="btn btn-success">{{ trans('admin.saveChanges') }}</button>
				      </div>
			      </form>
			    </div>
			  </div>
			</div>
			<!-- /modal -->
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
			$("#a_settingModal").click(function(){
				$("#modalSetting").modal("show");
			});
			$("#sitebar .name").click(function(){
				window.location.replace("{{route('admin.generalInfor')}}");
			});
		});
	</script>
</body>
</html>