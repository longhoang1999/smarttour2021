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
								<a class="typePlace" href="{{route('admin.typePlace')}}">{{ trans('admin.typeOfPlace') }}</a>
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
						<a href="{{route('login')}}" target="_blank" class="btn btn-info">{{ trans('admin.Gotowebsite') }}</a>
					</div>
					<div class="col-md-7 text-right">
						<div id="div_admin" class="float-right">
							<span>{{$us_fullName}}</span>
							<?php use Illuminate\Support\Facades\Auth;
								$user= Auth::user();
							 ?>
							<img src="{{asset($user->us_image)}}" alt="" class="avatar">
							<div class="profile_admin" style="z-index: 10">
								<ul>
									<li>
										<a href="#" id="a_accModal">
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
			<!-- accModal -->	
			<div class="modal fade" id="accModal" tabindex="-1" role="dialog" aria-labelledby="personalModal" aria-hidden="true">
			  <div class="modal-dialog modal-lg">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title" id="personalModal">{{ trans('messages.Yourpersonalinformation') }}</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
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
			        <div class="container-fluid">
			            <div class="row">
			                <div class="col-md-12 col-sm-12 col-12 text-center">
			                    <div id="text_img_person" class="mb-5" ></div>
			                    <img class="mb-5" src="{{asset('assets/img/avataaars.svg')}}" alt="" id="default_img_person" />
			                </div>
			            </div>
			        </div>
			        <form action="{{route('user.editInfo')}}" method="post" id="formFixInfor_person" enctype="multipart/form-data">
			            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
			            <div class="container-fluid">
			                <div class="row">
			                    <div class="col-md-12 col-sm-12 col-12 text-center mb-2">
			                        <p class="text_content">{{ trans('messages.Avatar') }}</p>
			                        <div class="btn_upload_person">{{ trans('messages.Upload') }}</div>
			                        <p class="text_content" id="file_name_person"></p>
			                        <input type="file" class="form-control" id="input_File_person" name="file" accept="image/*">
			                    </div>
			                    <div class="col-md-4 col-sm-6 col-6"><p class="text_content">Email</p></div>
			                    <div class="col-md-8 col-sm-6 col-6" id="text_email_person"></div>
			                    <div class="col-md-4 col-sm-6 col-6"><p class="text_content">{{ trans('messages.FullName') }}</p></div>
			                    <div class="col-md-8 col-sm-6 col-6" id="text_fullName_person"></div>
			                    <div class="col-md-8 col-sm-6 col-6" id="input_fullName_person">
			                        <input type="text" placeholder="Enter your fullname" class="form-control" name="fullName">
			                    </div>
			                    <div class="col-md-4 col-sm-6 col-6"><p class="text_content">{{ trans('messages.Gender') }}</p></div>
			                    <div class="col-md-8 col-sm-6 col-6" id="text_gender_person"></div>
			                    <div class="col-md-8 col-sm-6 col-6" id="input_gender_person">
			                        <select name="gender" class="form-control">
			                            <option value="Male">{{ trans('messages.Male') }}</option>
			                            <option value="Female">{{ trans('messages.Female') }}</option>
			                        </select>
			                    </div>
			                    <div class="col-md-4 col-sm-6 col-6"><p class="text_content">{{ trans('messages.Age') }}</p></div>
			                    <div class="col-md-8 col-sm-6 col-6" id="text_age_person"></div>
			                    <div class="col-md-8 col-sm-6 col-6" id="input_age_person">
			                        <input type="number" placeholder="Enter your age" class="form-control" name="age">
			                    </div>
			                    <!-- pass -->

			                    <p class="col-md-12 col-sm-12 col-12 openChangePass_person text-info">{{ trans('messages.ifYouchange') }}. <span class="openClickHere_person">{{ trans('messages.clickHere') }}</span></p>
			                    <div class="col-md-4 col-sm-6 col-6"><p class="text_content openItems_person">{{ trans('messages.oldPassword') }}</p></div>
			                    <div class="col-md-8 col-sm-6 col-6 openItems_person" id="input_Oldpassword_person">
			                        <input type="password" placeholder="{{ trans('messages.oldPassword') }}" class="form-control" name="oldpass">
			                    </div>
			                    <div class="col-md-4 col-sm-6 col-6"><p class="text_content openItems_person">{{ trans('messages.newPassword') }}</p></div>
			                    <div class="col-md-8 col-sm-6 col-6 openItems_person" id="input_password_person">
			                        <input type="password" placeholder="{{ trans('messages.newPassword') }}" class="form-control" name="newpass">
			                    </div>
			                    <div class="col-md-4 col-sm-6 col-6 openItems_person"><p class="text_content">{{ trans('messages.confirmPassword') }}</p></div>
			                    <div class="col-md-8 col-sm-6 col-6 openItems_person" id="input_Confirmpassword_person">
			                        <input type="password" placeholder="{{ trans('messages.confirmPassword') }}" class="form-control" name="confirmpass">
			                    </div>
			                </div>   
			            </div>
			        </form> 
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-success" id="btn_editInfo_person">{{ trans('messages.Editinformation') }}</button>
			        <button type="button" class="btn btn-primary" id="btn_submitInfo_person">{{ trans('messages.SubmitEdit') }}</button>
			      </div>
			    </div>
			  </div>
			</div>

			<!-- /accModal -->
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
	@yield('footer-js')
	<script type="text/javascript">
		$(document).ready(function(){
			$("#a_settingModal").click(function(){
				$("#modalSetting").modal("show");
			});
			$("#a_accModal").click(function(){
				$("#accModal").modal("show");
			});
			$("#sitebar .name").click(function(){
				window.location.replace("{{route('admin.generalInfor')}}");
			});
			$('#accModal').on('show.bs.modal', function (event) {
                let _token = $('meta[name="csrf-token"]').attr('content');
                let $url_path = '{!! url('/') !!}';
                let routeCheckUser=$url_path+"/checkUser";
                $.ajax({
                      url:routeCheckUser,
                      method:"POST",
                      data:{_token:_token},
                      success:function(data){ 
                        $("#text_email_person").empty();
                        $("#text_fullName_person").empty();
                        $("#text_gender_person").empty();
                        $("#text_age_person").empty();
                        if(data[5] == false)
                        {
                            $("#default_img_person").css("display","block");
                            $("#text_img_person").css("display","none");
                        }
                        else
                        {
                            $("#default_img_person").css("display","none");
                            $("#text_img_person").css("display","block");
                            $("#text_img_person").css("background","url('"+data[0]+"')");
                            $("#text_img_person").css("background-size","cover");
                            $("#text_img_person").css("background-repeat","no-repeat");
                        }
                        if(data[6] != "")
                        {
                            $("#text_email_person").append(data[1]+"<span class='text-danger' style='font-style: italic;'> (Chưa xác minh)</span>");
                        }
                        if(data[6] == "")
                        {
                            $("#text_email_person").append(data[1]+"<span class='text-success' style='font-style: italic;'> (Đã xác minh)</span>");
                        }
                        $("#text_fullName_person").append(data[2]);
                        $("#text_gender_person").append(data[3]);
                        $("#text_age_person").append(data[4]);
                        //append input
                        $("#input_age_person input").val(data[4]);
                        $("#input_gender_person select").val(data[3]);
                        $("#input_fullName_person input").val(data[2]);
                     }
                });
                $("#btn_editInfo_person").click(function(){
                    //ẩn
                    $("#text_fullName_person").slideUp("fast");
                    $("#text_age_person").slideUp("fast");
                    $("#text_gender_person").slideUp("fast");
                    //hiện
                    $(".openChangePass_person").css('display','block');
                    $("#btn_submitInfo_person").css("display","block");
                    $(".btn_upload_person").slideDown("fast");
                    $("#input_age_person").slideDown("fast");
                    $("#input_gender_person").slideDown("fast");
                    $("#input_fullName_person").slideDown("fast");
                    $("#btn_editInfo_person").css("display","none");
                });
                $(".btn_upload_person").click(function(){
                    $("#input_File_person").click();
                });
                $("#btn_submitInfo_person").click(function(){
                    $("#formFixInfor_person").submit();
                });
                $(".openClickHere_person").click(function(){
                    $(".openItems_person").css("display","block");
                    $(".openChangePass_person").css("display","none");
                });
                $("#input_File_person").change(function(){
                    $(".btn_upload_person").css("background","#ff8304");
                    $("#file_name_person").css("display","block");
                    $("#file_name_person").html($("#input_File_person").val().split('\\').pop());
                });
            });
		});
	</script>
</body>
</html>