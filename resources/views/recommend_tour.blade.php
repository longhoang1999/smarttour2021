@extends('layoutmap/layout/index')
@section('title')
    New UI Tour Advisor
@parent
@stop
@section('header_styles')
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgbjwIY5Q1eZ-Ejqur0a8avEQWowfA39s&callback=initMap&libraries=places"   defer></script>
	<link rel="stylesheet" type="text/css" href="{{asset('semantic/semantic.min.css')}}">
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.5.7/themes/airbnb.css'>
  	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css'>
	<link 	rel="stylesheet" type="text/css" href="{{asset('css/map.css')}}">
	<link 	rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" defer> 
	<link 	rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link 	rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<link rel="stylesheet" href="{{asset('css/retour.css')}}">
@stop
@section('content')	
	<!-- map -->
	<div id="wrap">
		<input id="pac-input" class="controls" type="text" placeholder="Search Box"> 
		<div id="map"></div>
		<div id='control-column'>
			<div id="search-box">
				<div id="search-title">
					<label for="search-input">Chọn địa điểm</label>
					<div style="z-index: 1;margin-top: -12px;margin-left: auto; cursor: pointer">
						<div class="dropdown-option">
							<span>
								<svg x="0" y="0" viewBox="0 0 24 24" width="1em" height="1em">
									<circle cx="4.5" cy="11.9" r="2.5"></circle>
									<circle cx="19.5" cy="11.9" r="2.5"></circle>
									<circle cx="12" cy="11.9" r="2.5"></circle>
								</svg>
							</span>
							<div id="myDropdown" class="dropdown-content">
								<a href="#" id="btn-rating" data-toggle="modal" data-target="#modalRating">Rating</a>
								@if(isset($to_des))
									<a href="{{route('user.maps')}}">Create a new tour</a>
								@endif
								<a href="{{route('login')}}">Home</a>
								<a href="{{route('about')}}">About</a>
								<a href="#reset" id="reset_opt">Reset</a>
								<a href="#reset" id="clear_mark" style="display: none;">Clear markers</a>
							</div>
						</div>
					</div>
				</div>
				<select id="search-input">
				</select>
				<button id="add-waypoints">Thêm điểm</button> 
				<div class="chip">
					<div class="chip-icon" alt="Timeline">
						<i class="fas fa-list-ul"></i>	
					</div>
					<span>Timeline</span>
					</div>
				</div>
			<div id="control-content">
				<div id="start-locat-container" class="locat-container">
					<button id="start-locat" data-start="0" data-clsclk="0" >
						<span>Click chuột vào đây để chọn điểm bắt đầu</span>
					</button>
				</div>

				<div id="locat-list">
					<section  id="list-container">
						<!-- <div class="list-item">
							<div class="item-content">
								<div class="order">1</div> 
								<div class="item-content-text">Alpha</div>
								<i class="fas fa-times item-content-close" ></i>
							</div>
						</div>-->

					</section>
					<!-- <div id="locat-container-height"></div> -->
				</div>
				<div id='time-cost-picker' class="option-control" style="display: none;">
					<i class="fas fa-caret-right icon_cost_picker"></i>
					<div class="ui">
						<label style="font-size: 16px;"><b>Thời gian và chi phí tại: <label id='location-dur-cost'></label></b></label>
					</div>
					<div id='time-cost-container'>
						<div class="left">
							<span style="margin-top: 0.1em; width: 20%"><b>Ghé thăm trong:</b></span>
							<div class="ui input" style="width: 100%">
					      <input type="text" id="duration-picker" value="3600">
					    </div>
						</div>
						<div class="right">
							<span class="font-weight-bold">Chi phí:</span>	
							<div class="form-group input_money">
							  <input type="number" id="amount" class="form-control">
							  <select class="form-control currency">
							  		<option selected="true" value="VNĐ">VNĐ</option>
								 	<option value="USD">USD</option>
							  </select>
							  <span class="text_money">Bạn nhập: <span class="amount-text"></span></span>
							</div>
						</div>
					</div>
						
				</div>
				<div id="get-route-pannel" class="option-control" style="display: none;">
					<label style="font-size: 20px; font-weight: bolder;">Các lựa chọn</label><br>
					<label style="font-size: 14px;  font-weight: bold;">Chọn thời gian</label><br>
					<span style="font-size: 12px;color: #333; font-style: oblique; ">Có thể bỏ trống thời gian kết thúc hoặc cả hai</span>
					<div class="datepicker">
					  <div>
					    <input type="text" id="startDate" placeholder="Choose start and end date" class="startDate" required value="" />
					  </div>
					  <div>
					    <input type="text" id="endDate" class="endDate" required>
					  </div>
						<span id="time-close"><i class="fas fa-times" ></i></span>
					</div>
					<div class="ui checkbox" style="margin-top:0.7em">
					  <input type="checkbox" id="is-back" >
					  <label><b>Quay lại điểm bắt đầu?<b></label>
					</div>
					<!-- <div class="ui form">
						<label><b>Bạn ưu tiên gì hơn?</b></label>
					  <div class="inline fields">
					    <div class="field">
					      <div class="ui radio checkbox">
					        <input type="radio" name="frequency" checked="checked">
					        <label>Thoải mái</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui radio checkbox">
					        <input type="radio" name="frequency">
					        <label>Thời gian</label>
					      </div>
					    </div>
					    <div class="field">
					      <div class="ui radio checkbox">
					        <input type="radio" name="frequency">
					        <label>Chi phí</label>
					      </div>
					    </div>
					  </div>
					</div> -->
					<div  class="ui">
						<button id="get-route" class="ui secondary button">
						  Chỉ đường
						</button>
						<button class="ui primary button" id="saveTour">
						  	@if(!isset($to_des))
								{{ trans('messages.SaveTour') }}
							@else
								{{ trans('messages.EditTour') }}
							@endif
						</button>
					</div>
				</div>
				<div id="location-detail">
					<a href="#" id="link-img" target="_blank">
						<div class="parents-img">
							<img src="{{asset('imgs/image.jpg')}}" alt="">
						</div>
					</a>
					<div class="location-detail-content">
						<a href="#" class="name-place">
							Thành cổ Sơn Tây
						</a>
						<div class="star-votes">
							<span id="star">
								<!-- none: far  -->
								<!-- has value: fas -->
								<i class="fas fa-star text-warning"></i>
								<i class="fas fa-star text-warning"></i>
								<i class="fas fa-star text-warning"></i>
								<i class="fas fa-star text-warning"></i>
								<i class="fas fa-star-half-alt text-warning"></i>
							</span>
							<span id="votes">
								2.290 votes
							</span>
						</div>
						<div>
							<a class="ui tag label">Cổ kính</a>
							<a class="ui red tag label">Lịch sử</a>
							<a class="ui teal tag label">Hoài cổ</a>
						</div>
						<div class="icon-down">
							<i class="fas fa-angle-down text-dark"></i>
						</div>
						<div class="link-vr">
							<p class="text-justify">
								<span class="font-weight-bold">Link on VR: </span>
								<a href="#" class="font-italic link-here" target="_blank">Link here</a>
							</p>
						</div>
						<div class="short-des mt-2">
							<p class="text-justify">
								<span class="font-weight-bold">Mô tả ngắn gọn: </span>
								<span id="short_des">Đây là địa điểm du lịch nổi tiếng với ngàn năm lịch sử</span>
							</p>
						</div>
						<div class="more-infor-place">
							<div class="long-des">
								<p class="text-justify">
									<span class="font-weight-bold">Mô tả chi tiết: </span>
									<span id="description"></span>
								</p>
							</div>
						</div>
					</div>
				</div>
				<div id="bottom-height"></div>
			</div>
			<div id="show-timeline">
				<i class="fas fa-times close_timeline"></i>
				<div class="timeline">
				  <div class="timeline__list  animated fadeInUp delay-1s timeline__list--type0">
				    <div class="timeline__picture" style="background-image: url('{{asset("imgs/7.jpg")}}')" >
				      <span>07:00	</span>
				    </div>
				    <div class="timeline__list__content ">
				    	<div class="timeline__list__content__title" ><a href="#" >Hoof hoanf kieemsHoof hoanf kieemsHoof hoanf kieems</a></div>
				    	<div class="star-votes">
								<span id="star">
								
									<i class="fas fa-star text-warning"></i>
									<i class="fas fa-star text-warning"></i>
									<i class="fas fa-star text-warning"></i>
									<i class="fas fa-star text-warning"></i>
									<i class="fas fa-star-half-alt text-warning"></i>
								</span>
								<span id="votes">
									2.290 votes
								</span>
						</div>
						<div class="link-vr">
							<p class="text-justify">
								<span class="font-weight-bold">Link on VR: </span>
								<a href="#" class="font-italic link-here" target="_blank">Link here</a>
							</p>
						</div>
						<div class="icon">
	            <div class="parent-icon">
                <i class="fas fa-utensils"></i> 
                <span>Restaurant</span>
	            </div>
	            <div class="parent-icon">
                <i class="fas fa-store"></i> 
                <span>Store</span>
	            </div>
	            <div class="parent-icon">
                <i class="fas fa-coffee"></i> 
                <span>Coffee</span>
	            </div>
	        	</div> 
				   </div>
				  </div> 
				  <div class="animated fadeInLeft delay-2s" style=" line-height: 40px;">20:01 - 20:00</div>
				</div>
			</div>
		</div>
		
	</div>
	<!-- / end map -->

<!-- Modal alert click -->
<div class="modal fade" id="timeAlert" tabindex="-1" role="dialog" aria-labelledby="clickWarningLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="timeAlertLabel">{{ trans('messages.Warning') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true" id="timeAlert-close">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="text">{{ trans('messages.OverTime') }}</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="del-time-click">{{ trans('messages.Autodeletelocations') }}</button>
			</div>
		</div>
	</div>
</div>
<!-- End modal -->
@stop
@section('footer-js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/plugins/rangePlugin.min.js" integrity="sha512-fEfYg/xxuu15Bma40+0fjFdH37rFqYOqCiK+wA4AzCcK4I9Dp9V4dRCDPTz9uHPUX+ErWhuP86EzOGI4fy3YBQ==" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.js" integrity="sha512-+ruHlyki4CepPr07VklkX/KM5NXdD16K1xVwSva5VqOVbsotyCQVKEwdQ1tAeo3UkHCXfSMtKU/mZpKjYqkxZA==" crossorigin="anonymous"></script>
	<script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.4/utils/Draggable.min.js'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.4/TweenMax.min.js'></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.9/flatpickr.min.css" integrity="sha512-OtwMKauYE8gmoXusoKzA/wzQoh7WThXJcJVkA29fHP58hBF7osfY0WLCIZbwkeL9OgRCxtAfy17Pn3mndQ4PZQ==" crossorigin="anonymous" />
	<script type="text/javascript" src="{{asset('js/jquery-duration-picker.js')}}"></script>
	<script src="{{asset('semantic/semantic.min.js')}}"></script>

	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css'>
	<!-- long viết -->
	<?php 
		use Illuminate\Support\Arr;
		use App\Models\Destination; 
		use Illuminate\Support\Facades\Auth;
	?>
		<script type="text/javascript">
			// part 1
			$("#time-cost-picker").mouseleave(function(){
			  $("#time-cost-picker").hide("fast");
			});
			$("#control-content").scroll(function(event) {
				$("#time-cost-picker").hide("fast");
			})
			@if(Auth::check())
				$("#saveTour").click(function(){
					$("#enterNameTour").modal("show");
				});
			@else
				$("#saveTour").click(function(){
					$("#modalLogin").modal("show");
				});
			@endif
			// part 3
			//yes or no public
			$("#yesPublic").click(function(){
				$(".inforForShare").show();
			});
			$("#noPublic").click(function(){
				$(".inforForShare").hide();
			});
			$("#saveTour").click(function(){
				let _token = $('meta[name="csrf-token"]').attr('content');	
				//loginForm
				$(".loginForm").submit(function(e){
					e.preventDefault();
					let form = $(this);
				    var url = form.attr("action");
				    $.ajax({
			           type: "POST",
			           url: url,
			           data: {_token:_token,typeLogin:"modal",us_email:$(".loginForm input[name=us_email]").val(),us_password:$(".loginForm input[name=us_password]").val()},
			           success: function(data)
			           {
			               if(data == "lock")
		               		{
		               			$("#modalLogin").modal("hide");
		               			$("#notification").modal("show");
			               		$("#contentNotification").removeClass("text-success");
			               		$("#contentNotification").addClass("text-danger");
			               		$("#contentNotification").html("Your account is locked!");
		               		}
			               else if(data == "fail login")
			               {
			               		$("#modalLogin").modal("hide");
			               		$("#notification").modal("show");
			               		$("#contentNotification").removeClass("text-success");
			               		$("#contentNotification").addClass("text-danger");
			               		$("#contentNotification").html("Login unsuccessful, you entered the wrong account or password!");
			               	}
			               else
			               {
			               		$("#modalLogin").modal("hide");
			               		$(".li_menu_acc").empty();
			               		if(data[0] == "admin")
			               			$(".li_menu_acc").append('<p class="menu_title_acc text-uppercase" id="your_account">{{ trans("messages.Youraccount") }} <i class="fas fa-sort-down"></i></p><div class="menu_content"><p id="comback_admin">{{ trans("messages.adminPage") }}</p><p id="personalInfo">{{ trans("messages.Aboutyou") }}</p><p id="p_logout">{{ trans("messages.Logout") }}</p></div>');
			               		else if(data[0] == "user")
			               			$(".li_menu_acc").append('<p class="menu_title_acc text-uppercase" id="your_account">{{ trans("messages.Youraccount") }} <i class="fas fa-sort-down"></i></p><div class="menu_content"><p id="personalInfo">{{ trans("messages.Aboutyou") }}</p><p id="p_logout">{{ trans("messages.Logout") }}</p></div>');
			               		$("#enterNameTour").modal("show");
			               		$( "#saveTour").unbind( "click" );
			               		$("#saveTour").click(function(){
									$("#enterNameTour").modal("show");
									$("#modalLogin").modal("hide");
								});
			               }
			           }
			        });
				});
				$("#formRegister").submit(function(e){
					$("#btn_Registration").hide();
					e.preventDefault();
					$("input[name=checkmodal]").val("modal")
				    var form = $(this);
				    var url = form.attr('action');
				    $.ajax({
			           type: "POST",
			           url: url,
			           data: form.serialize(),
			           success: function(data)
			           {
			           		$("#btn_Registration").show();
			           		if(data == "true")
			           		{
			           		   $("#modalRegis").modal("hide");
				           	   $("#contentNotification").html("You have successfully registered");
				               $("#notification").modal("show");
				               $('#notification').on('hidden.bs.modal', function (e) {
				               		$("#modalRegis").modal("hide");
				               		$("#modalLogin").modal("show");
				               		$("#modalLogin input[name=us_email]").val($("#modalRegis input[name=email]").val());
				               		$("#modalLogin input[name=us_password]").val($("#modalRegis input[name=password]").val());
				               		$("#modalRegis input[name=email]").val("");
				               		$("#modalRegis input[name=password]").val("");
				               		$("#modalRegis input[name=confirm]").val("");
				               		$("#modalRegis input[name=fullname]").val("");
				               		$("#modalRegis input[name=fullname]").val("");
				               		$("#modalRegis select[name=gender]").val("Male");
				               		$("#modalRegis input[name=age]").val("");
							   });

			           		}
			           		else
			           		{
			           		   $("#modalRegis").modal("hide");
			           		   $("#contentNotification").html(data);
				               $("#notification").modal("show");
				               $('#notification').on('hidden.bs.modal', function (e) {
				               		$("#modalRegis").modal("show");
							   })
			           		}
			           	   
			           }
				    });
				});
			});
			@for($i = 1; $i<= 5; $i++)
				$("#div_Starrank_tour").on('click','.star_{{$i}}',function(){
					@for($j = 1 ; $j <= 5; $j++)
						$("#div_Starrank_tour .star_{{$j}}").css("color","#212529");
					@endfor
					@for($j = 1 ; $j <= $i; $j++)
						$("#div_Starrank_tour .star_{{$j}}").css("color","#ff9700");
					@endfor
					$("#star_Share").val($(this).attr("data-value"));
				});
			@endfor
			$(".Update_img_tour").click(function(){
				$("#img_input_Rank").click();
			});
			$("#img_input_Rank").change(function(){
				$(".Update_img_tour").css("background","#ff9700");
				$(".name_file_tour").html("File name: &#60;"+$("#img_input_Rank").val().split('\\').pop()+"&#62;");
				$(".name_file_tour").show();
			});
		</script>
		<!-- part 4 -->
		@if(isset($justview) && Auth::check())
		<!-- Modal -->
		<div class="modal fade" id="modalRating" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Rating</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body pb-2">
						<div class="container-fuild">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-12">
										<p class="font-weight-bold font-italic">Rating for your tour</p>
									</div>
									<div class="col-md-12 col-sm-12 col-12 mb-3" id="Starrank_tour">
										<i class="fas fa-star star_1 fa-2x"  data-value="1" style="cursor: pointer;"></i>
										<i class="fas fa-star star_2 fa-2x" data-value="2" style="cursor: pointer;"></i>
										<i class="fas fa-star star_3 fa-2x" data-value="3" style="cursor: pointer;"></i>
										<i class="fas fa-star star_4 fa-2x"  data-value="4" style="cursor: pointer;"></i>
										<i class="fas fa-star star_5 fa-2x" data-value="5" style="cursor: pointer;"></i>
									</div>
									<input type="hidden" id="star_ShareUserVote" name="numberStar" value="0">
								</div>
							</div>
					</div>
					<div class="modal-footer pb-2 pt-2 pl-3 pr-3">
						<button type="button" class="btn btn-primary" id="btn_Rating">Rating</button>
					</div>
				</div>
			</div>
		</div>
		@endif
		@if(isset($justview))
			<style>
				#saveTour{display: none !important;}
				#btn-rating{display: block !important;}
			</style>
			<script type="text/javascript">
				@if(!Auth::check())
					$("#btn-rating").click(function(){
						$("#modalLogin").modal("show");
					});
				@else
					$("#btn-rating").click(function(){
						$("#modalRating").modal("show");
					});
					//votess star
					@for($i = 1; $i<= 5; $i++)
						$("#Starrank_tour").on('click','.star_{{$i}}',function(){
							@for($j = 1 ; $j <= 5; $j++)
									$("#Starrank_tour .star_{{$j}}").css("color","#212529");
							@endfor
							@for($j = 1 ; $j <= $i; $j++)
									$("#Starrank_tour .star_{{$j}}").css("color","#ff9700");
							@endfor
							$("#star_ShareUserVote").val($(this).attr("data-value"));
						})
					@endfor
					$("#btn_Rating").click(function(){
							let _token = $('meta[name="csrf-token"]').attr('content');
							let $url_path = '{!! url('/') !!}';
							let routeRating=$url_path+"/rating";
							let numberStar = $("#star_ShareUserVote").val();
							$.ajax({
									url:routeRating,
									method:"POST",
									data:{_token:_token,numberStar:numberStar,shareId:{{$shareId}}},
									success:function(data){ 
										alert("You have successfully evaluated");
										location.reload();
								 }
							});
					});
					$('#modalRating').on('show.bs.modal', function (event) {
			            let _token = $('meta[name="csrf-token"]').attr('content');
			            let $url_path = '{!! url('/') !!}';
			            let routeCheckTour = $url_path+"/voteUser";
			            $.ajax({
			                  url:routeCheckTour,
			                  method:"POST",
			                  data:{_token:_token,shareId:{{$shareId}}},
			                  success:function(data){ 
			                    if(data[0] == "yes")
			                    {
			                    	$("#star_ShareUserVote").val(data[1]);
				                    if(data[1] == "1")
				                    {
				                    	$(".star_1").css("color","#ff9700");
				                    }
				                    else if(data[1] == "2")
				                    {
				                    	$(".star_1").css("color","#ff9700");
				                    	$(".star_2").css("color","#ff9700");
				                    }
				                    else if(data[1] == "3")
				                    {
				                    	$(".star_1").css("color","#ff9700");
				                    	$(".star_2").css("color","#ff9700");
				                    	$(".star_3").css("color","#ff9700");
				                    }
				                    else if(data[1] == "4")
				                    {
				                    	$(".star_1").css("color","#ff9700");
				                    	$(".star_2").css("color","#ff9700");
				                    	$(".star_3").css("color","#ff9700");
				                    	$(".star_4").css("color","#ff9700");
				                    }
				                    else if(data[1] == "5")
				                    {
				                    	$(".star_1").css("color","#ff9700");
				                    	$(".star_2").css("color","#ff9700");
				                    	$(".star_3").css("color","#ff9700");
				                    	$(".star_4").css("color","#ff9700");
				                    	$(".star_5").css("color","#ff9700");
				                    }
			                    }
			                 }
			            });
			        });
				@endif
			</script>
		@endif
	<!-- /end long viết -->
	<script type="text/javascript">
		$(document).ready(function(){

			$("#show-timeline .close_timeline").click(function(){
				$(".translate-none").hide();
				$('#control-column').width('30%');
				$("#show-timeline").removeClass("translate-none");
				
			});
			$(".chip").click(function(){
				$("#show-timeline").addClass("translate-none");
				$('#control-column').width('50%');
				$(".translate-none").show();
				
			});
			$(".icon-down").click(function(){
				$(".more-infor-place").toggle();
				// $('#control-content').animate({
				// 			scrollTop: $(".more-infor-place").offset().bottom
				// 	}, 500);
			});
			$(".dropdown-option").click(function(){
				if ($('.dropdown-content').is(':visible'))
				{
					$(".dropdown-content").hide();
				}
				else
				{
					$(".dropdown-content").show();
				}
			});
			$(document).click(function (e)
			{
					var container = $(".dropdown-option");
					if (!container.is(e.target) && container.has(e.target).length === 0)
					{
							$(".dropdown-content").hide("fast");
					}
			});
			
		});
	</script>
	<script type="text/javascript">

const colorlist = ['#418bca','#00bc8c','#f89a14','#ef6f6c','#5bc0de','#811411'],
			locationdata = new Map(),
			markerArray = new Map();
var newMarkOnClk = {},
		locationID = [],
		startLocat = {},
		newLocatMark =[],
		polylines = [],
		glowPolylines =[],
		isopt = 1,
		locats = [],
		isAuDel = 0,
		disresponse,
		allRoutePosible = [],
		recentLocatID = [],
		recentStart,
		newStartIndex,
		nearMarks = [];

function initMap(){
//==================Main progress========================
	//Get locations data from server 
	$.ajax({
		url:"{{ route('showmap') }}",
		type: 'get',  
		error: (err)=>{
			alert("An error occured: " + err.status + " " + err.statusText);
		},
		success: (result)=>{
			result[0].forEach(e=>{
				locationdata.set(e[0],e[1]);
			})


			let data = result[1];
			$('#search-input').select2({
				data: data
			});
			$('#search-input').val('').trigger('change');
		}
	});	
	
	//init map, google services
	const map = new google.maps.Map(document.getElementById("map"), {
							center: { lat: 21.0226586, lng: 105.8179091 },
							gestureHandling: 'greedy',
							fullscreenControl: true,
							disableDefaultUI: true,
							zoom: 12.5							
						}),
				directionsService	= new google.maps.DirectionsService(),
				geocoder 	= new google.maps.Geocoder(),
				distanceService = new google.maps.DistanceMatrixService();

	// var searchBox = new google.maps.places.SearchBox(document.getElementById('pac-input'));
 //   map.controls[google.maps.ControlPosition.TOP_CENTER].push(document.getElementById('pac-input'));
 //   google.maps.event.addListener(searchBox, 'places_changed', function() {
 //     var places = searchBox.getPlaces();
 //     geocoderCallBack(places)
 //   });
	const searchBox = new google.maps.places.SearchBox(document.getElementById('pac-input'));
   map.controls[google.maps.ControlPosition.TOP_CENTER].push(document.getElementById('pac-input'));
   google.maps.event.addListener(searchBox, 'places_changed', function() {
     let places = searchBox.getPlaces();
     console.log(places)
     $('#add-waypoints').show();
     geocoderCallBack(places)
   });
	setEvent();
//===========================================

	function setEvent(){
		let todayDate = new Date();
		todayDate.setHours(7,0,0);
		let da = new Date();
		da.setHours(19,0,0);
		flatpickr('#startDate', {
		  enableTime: true,
			minDate: "today",
		  allowInput: true,
		  time_24hr: true,
		  onChange: function(selectedDates, dateStr, instance) {
				$("#time").val(selectedDates);
			},
			defaultDate: todayDate,
		  dateFormat: "d/m/Y h:iK",
		  "plugins": [new rangePlugin({ input: "#endDate"})]
		});
		// Event click on map to choose location
		map.addListener('click',(evt) => {
			$('#add-waypoints').show();
			//reset existing marker on map
			if(Object.entries(newMarkOnClk).length){
				newMarkOnClk.marker.setMap(null);
				deleteMarker(newMarkOnClk.id);
				newMarkOnClk = {};
			} 

			//Get clicked location info 
			geocoder.geocode({location: evt.latLng},geocoderCallBack);
		});

		$('#add-waypoints').click(processAddToList);
		$('#search-input').on('select2:select',processAddToList);
		$('#duration-picker').duration_picker(); 
		$('#start-locat').click((e)=>{

			// data-start 0 là chưa có gì 1 là đang chờ để thêm địa điểm 2 là đã thêm điểm r ko cho thay đổi nữa
			// data-clsclk click vào nút x #start-locat cũng nhận sự kiện
			if($('#start-locat').attr('data-start') === '2'){
				$("#time-cost-picker").show();
				let height_time_cost = parseFloat($(e.currentTarget).last().offset().top) - 60;
				$("#time-cost-picker").css("top",height_time_cost+"px");
				return;
			}
			if($('#start-locat').attr('data-clsclk') === '1'){
				$('#start-locat').attr('data-clsclk',0);
				return;
			} 
			$('#start-locat').html(
				`<span>Click trên bản đồ hoặc chọn trong ô tìm kiếm</span><div id="close-start" style="display: inline-flex; position: absolute; right: 0.5em;"><i class="fas fa-times " ></i></div>`
			);
			$('#start-locat').attr('data-start',1);
			closeStart();
		});	
		$('#get-route').click((e)=>{
			if((Object.entries(startLocat).length && locationID.length == 0) || (!Object.entries(startLocat).length && locationID.length<=1)){
				alert("Please choose at least 2 locations");
				return;
			} else {
				$('#saveTour').show();
				$('#get-route').hide();
				idToData(null,"LatLngArr");
				$('#time-cost-picker').hide();
				// e.currentTarget.className.replace("loading", "");
				// e.currentTarget.className += ' loading';
				if((Object.entries(startLocat).length && locationID.length == 1) || (!Object.entries(startLocat).length && locationID.length ==2)){
					drawRoutes();
				}
				else
					processanddrawrouteclient();
			}
		})
		$('#time-close').click(()=>{
			document.querySelector("#startDate")._flatpickr.clear();
		})
		$('#is-back').click(updateRoute);
		// $('#is-opt').click(updateRoute);
		// $('.dur-dis').click(updateRoute);
		$('#startDate').change(updateRoute);
		$('#time-end').change(updateRoute);
		// format money
		$("#amount").keyup(function(){
          if($(this).val() == "")
          {
          	$(".text_money").hide();
          }
          else
          {
          	idToData($("#time-cost-picker").attr('value'),'setCost',$(this).val())
            $(".amount-text").text($(this).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") +" "+$(".currency").val());
            $(".text_money").show();
          }
          updateRoute();
        });
        $(".currency").change(function(){
          var string_money = $(".amount-text").text();
          if(string_money.indexOf("VNĐ") != "-1")
          {
          	let new_money = parseFloat($("#amount").val())/23000;
            $("#amount").val(parseFloat(new_money.toFixed(2)));
          	$(".amount-text").text($("#amount").val().toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+" "+$(this).val());
          }
          else if(string_money.indexOf("USD") != "-1")
          {
            let new_money = parseFloat($("#amount").val())*23000;
            $("#amount").val(parseFloat(new_money.toFixed(2)));
          	$(".amount-text").text($("#amount").val().toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+" "+$(this).val());
          }
        });
		// Test brancg git
		$('#clear_mark').click(()=>{
			 if(nearMarks.length)
			 	nearMarks.forEach(ele=>ele.setMap(null));

			 $('.map-marker-label[value="nearByPlace"]').remove();

		})
		resetAllHTML();
	}
	

	function resetAllHTML(){
		$("#reset_opt").click(()=>{
			$("#start-locat").html("<span>Click chuột vào đây để chọn điểm bắt đầu</span>");
			$("#start-locat").attr('data-start',0);
			$("#start-locat").attr('data-clsclk',0);
			$('#pac-input').val('')
			$('#list-container').empty();
			$('#time-cost-picker').hide();
			$('#get-route-pannel').hide();
			$('#add-waypoints').hide();
			$('.chip').hide();
			$('#saveTour').hide();
			$('#location-detail').hide();
			$('#clear_mark').hide();
			$('#clear_mark').trigger('click');
			$("#search-input option").prop('disabled',false);
			$('#get-route').show()
			$('#get-route').text('Chỉ đường')

			if(polylines.length){
				for(let i =0; i<polylines.length;i++){
					glowPolylines[i].setMap(null)
					polylines[i].setMap(null)
				}
			}

			if(markerArray.size){
				markerArray.forEach((value,key)=>{
					value.setMap(null);
					markerArray.delete(key);
				});
			}

			if(Object.entries(newMarkOnClk.size).length){
				newMarkOnClk.marker.setMap(null);
			}

			if(Object.entries(startLocat).length){
				startLocat.marker.setMap(null);
			}

			$('.map-marker-label').remove();
			
			map.setCenter({ lat: 21.0226586, lng: 105.8179091 });
      		map.setZoom(12.5);

	 		newMarkOnClk = {};
	 		startLocat = {}
			locationID = []
			newLocatMark =[]
			polylines = []
			glowPolylines = []
			locats = []
			allRoutePosible = []
			recentLocatID = []
			nearMarks = []
			isopt = 1
			isAuDel = 0
			disresponse = undefined
			recentStart = undefined
			newStartIndex = undefined
		});
	}

	function updateRoute(){
		if(polylines.length){
			$("#get-route").show();
			$("#get-route").text('Update');
			// $("#get-route").removeClass("loading");
		}
		if(polylines.length){
			for(let i =0; i<polylines.length;i++){
				glowPolylines[i].setMap(null)
				polylines[i].setMap(null)
			}
		}
	}

	function drawRoutes(){
		// document.getElementById('get-route').className.replace('loading',' ');
		let waypts =[];
		for(var i=1; i<locats.length; i++)
	      waypts.push({
	        location: locats[i],
	        stopover: true
	      });
		reorderlist();
		directionsService.route({
				origin: locats[0],
				destination: locats[locats.length-1],
				waypoints: waypts,
				travelMode: 'DRIVING',
		},customDirectionsRenderer);
	}

	function customDirectionsRenderer(response, status) {
		var bounds = new google.maps.LatLngBounds();
		var legs = response.routes[0].legs;
		for(let i=0;i<polylines.length;i++){
			polylines[i].setMap(null);
			glowPolylines[i].setMap(null);
		}
		polylines = [];
		glowPolylines = [];
		for (i = 0; i < legs.length; i++) {
			(i>=5&&i%5 == 0)?index = 4:((Object.entries(startLocat).length)?index = (i%5)-1:index = (i%5));
			if(Object.entries(startLocat).length && i==0) index = 5;
			 let polyline = new google.maps.Polyline({
				map:map, 
				path:[], 
				strokeColor: colorlist[index],
				strokeOpacity: 0.7,
				strokeWeight: 5});
			 let glowPolyline = new google.maps.Polyline({
				map:map, 
				path:[], 
				strokeColor: colorlist[index],
				strokeOpacity: 0.3,
				strokeWeight: 15,
				visible: false
			 });
			let steps = legs[i].steps;
			for (j = 0; j < steps.length; j++) {
				let nextSegment = steps[j].path;
				for (k = 0; k < nextSegment.length; k++) {
					polyline.getPath().push(nextSegment[k]);
					glowPolyline.getPath().push(nextSegment[k]);
					bounds.extend(nextSegment[k]);
				}
			}
			polylines.push(polyline);
			glowPolylines.push(glowPolyline);
		}
		map.fitBounds(bounds);
		getandsettimeline(response.routes[0].legs);
	};


	function getandsettimeline(response){
	    timeline = [];
	    let tmpTimeLine =[];
	    let tmp = $('#startDate').val().substr(10).replace(/\s|am|pm/gi,'');
	    if(tmp.split(":")[0].length == 1) tmp = "0"+tmp;
	    timeline.push(tmp);
	    tmpTimeLine.push(tmp);
	    tmp = converttime(tmp);
	    if(Object.entries(startLocat).length){
	    	tmp += idToData(startLocat.id,'duration');
	       timeline.push(converttime(tmp));
	    }
	     for(var i = 0; i < response.length-1 ; i++){
	      if(!Object.entries(startLocat).length){
	        tmp += idToData(locationID[i],'duration');
	        timeline.push(converttime(tmp));
	      } else if(i >=1){
	      	if(idToData(locationID[i-1],'duration')>0){
	      		tmp += idToData(locationID[i-1],'duration');
	        	timeline.push(converttime(tmp));
	      	}
	      }
	      
	      tmp += response[i].duration.value;
	      timeline.push(converttime(tmp));
	      tmpTimeLine.push(converttime(tmp));
	    }

	    if(!$('#is-back').is(':checked')){
	      tmp += idToData(locationID[locationID.length-1],'duration');
	      timeline.push(converttime(tmp));
	    }

    let totaldistance = 0;
		for(let i = 0;i<response.length;i++){
			totaldistance+=response[i].distance.value;
		}
		totaldistance = totaldistance/1000 +'km';
		
		
		for(let i= 0;i<response.length;i++){
			let infoWindow = new google.maps.InfoWindow();
			let content = 
			'<p>- Total time: '+converttime(null,'duration_second')+'</p>'+
			'<p>- Total distance: '+totaldistance+'</p>';
			let timelineLeg = (Object.entries(startLocat).length)?i:i+1;
			// timeline__list--type
			polylines[i].addListener('mouseover', (e)=>{
				let latlng;
				if(Object.entries(e).length){
					latlng = e.latLng
				} else {
					latlng = polylines[i].getPath().Nb[parseInt(polylines[i].getPath().Nb.length/2)]
				}
				infoWindow.setPosition(latlng);
				infoWindow.setContent(content);
				infoWindow.open(map);
				after = Array.from(document.querySelectorAll('.timeline__list-after'))
				let color = after[i].style.backgroundColor;
				after[i].style.boxShadow = `0px 2px 10px 1.5px ${color}`
				glowPolylines[i].setVisible(true);
				$(`.timeline__list--type${timelineLeg}`).addClass(`timeline__list--active${timelineLeg}`);
			});

		// Close the InfoWindow on mouseout:
			polylines[i].addListener('mouseout', (e) => {
			 	infoWindow.close();
				glowPolylines[i].setVisible(false);
				after = Array.from(document.querySelectorAll('.timeline__list-after'))
				after[i].style.boxShadow = ''
			});
		}

    settimeline(tmpTimeLine);
  }

  function settimeline(tl){
  	let tmpLocationID = locationID.slice(),
  			index =0;

  	$(".timeline").children().remove();

  	if(Object.entries(startLocat).length)
  		tmpLocationID.unshift(startLocat.id);
  	if($('#is-back').is(':checked'))
  		tmpLocationID.push(tmpLocationID[0])


  	for(let i = 0; i < tl.length; i++ ){
  		let curtime = converttime(converttime(tl[i]) + Object(locationdata.get(tmpLocationID[i])).de_duration);
  		let tral_duration =curtime +' - '+ tl[i+1];
  		  		if(i == tl.length-1){
  		  			tral_duration = 'End the tour at '+curtime;
  		  			if($('#is-back').is(':checked')) 
  		  				tral_duration = 'End the tour at '+tl[i];
  		  		} 
  		let imglink;
		if(Object(locationdata.get(tmpLocationID[i])).de_img != undefined){
			imglink = `${Object(locationdata.get(tmpLocationID[i])).de_img}`
		} else {
			imglink = "{{asset('imgs/image.jpg')}}"
		}

  		$(".timeline").append(`<div class="timeline__list  animated fadeInUp delay-1s "><div class="timeline__list-after"></div><div class="timeline__list-before "></div><div class="timeline__picture"><a data-fancybox="gallery" href="${imglink}"><img class="img_timeline" src="${imglink}" alt=""></a><span>${tl[i]}</span></div><div class="timeline__list__content "><div class="timeline__list__content__title" ><a href="#">${Object(locationdata.get(tmpLocationID[i])).de_name }</a></div><div class="star-votes"><span id="star"><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star-half-alt text-warning"></i></span><span id="votes">2.290 votes</span></div><div class="link-vr"><p class="text-justify"><span class="font-weight-bold">Link on VR: </span><a href="${Object(locationdata.get(tmpLocationID[i])).de_name }" class="font-italic link-here" target="_blank">Link here</a></p></div><div class="icon" value="${tmpLocationID[i]}"><div class="parent-icon"><i class="fas fa-hotel"></i><span>Hotel</span></div><div class="parent-icon"><i class="fas fa-utensils"></i><span>Restaurant</span></div><div class="parent-icon"><i class="fas fa-store"></i><span>Store</span></div><div class="parent-icon"><i class="fas fa-coffee"></i><span>Coffee</span></div></div></div></div> `+
  			`<div class="timeline__traveltime animated fadeInLeft delay-2s" ><span>${tral_duration}</span></div>`);

    }
    // <div><i class="fas fa-route"></i></div>
    $('.chip').css('display','inline-block');
    $('.timeline__list').last().addClass('timeline__list-lastelement');
    setColorEventTimeLine(tl);
    nearByFind();
  }

  function setColorEventTimeLine(tl){
  	let before = Array.from(document.querySelectorAll('.timeline__list-before')),
  			after = Array.from(document.querySelectorAll('.timeline__list-after')),
  			title = Array.from(document.querySelectorAll('.timeline__list__content__title > a'));
  	for(let i = 0; i < tl.length; i++ ){
  		(i>=5&&i%5 == 0)?index = 4:((Object.entries(startLocat).length)?index = (i%5)-1:index = (i%5));
			if(Object.entries(startLocat).length && i==0) index = 5;
			before[i].style.backgroundColor = colorlist[index]
			after[i].style.backgroundColor = colorlist[index]
			title[i].style.color = colorlist[index]
			after[i].addEventListener('mouseover',()=>{
			  google.maps.event.trigger(polylines[i], 'mouseover',{});
			})
			after[i].addEventListener('mouseout',()=>{
			  google.maps.event.trigger(polylines[i], 'mouseout',{});
			})
		}
  }

  function nearByFind(){
		let fa = document.querySelectorAll('.parent-icon');
		for(let i = 0;i<fa.length;i++)
			fa[i].addEventListener('click',function(){
				console.log(this)
				// $('#clear-service').show();
				let type = this.lastChild.innerText;
				if(type == 'Restaurant'){
					type = 'restaurant'
				} else if(type == 'Store'){
					type = 'store'
				} else if(type == 'Coffee'){
					type = 'cafe'
				} else {
					type = 'lodging'
				}
				let val = this.parentElement.getAttribute('value');
				$('#clear_mark').show();
				
				var place = new google.maps.places.PlacesService(map);
				place.nearbySearch({
					location: Object(locationdata.get(val)).location,
					radius: '500',
					type: type,
				}, (response, status) => {
					for(let i = 0; i < nearMarks.length;i++)
						nearMarks[i].setMap(null);

					$('.map-marker-label[value="nearByPlace"]').remove();
					
					for (let i = 0; i <15; i++) 
						nearByMarks(response[i]);
					
					map.setCenter(Object(locationdata.get(val)).location);
					map.setZoom(18);
				});
			});  
	}
	function nearByMarks(place){
		var icon = {
				url: place.icon,
				size: new google.maps.Size(71, 71),
				origin: new google.maps.Point(0, 0),
				anchor: new google.maps.Point(17, 34),
				scaledSize: new google.maps.Size(25, 25),
			};

		let tmpMarker = new google.maps.Marker({
			map,
			position: place.geometry.location,
			label: place.name,
			icon: icon
		});
		nearMarks.push(tmpMarker);
		customLabel(tmpMarker,'nearByPlace');
	}
  // convert time in seconds and HH:MM format
  function converttime(time,type){
  	if(type === 'duration_second'){
		let duration = converttime(timeline[timeline.length-1]) - converttime(timeline[0])
		return duration;
  	}
  	if(type === 'duration'){
			let seconds = time;
			var d = Math.floor(seconds / (3600*24));
			seconds  -= d*3600*24;
			let h   = Math.floor(seconds / 3600);
			seconds  -= h*3600;
			let m = Math.floor(seconds / 60);
			seconds  -= m*60;
			if(seconds>=30) m+=1;

			let tmp = [];
			if(d){(d==1)?tmp.push(d + ' Day'):tmp.push(d + ' Days')}
			if(d || h){(h==1)?tmp.push(h + ' Hour'):tmp.push(h + ' Hours')};
			if(d || h || m){(m==1)?tmp.push(m + ' Minute'):tmp.push(m + ' Minutes')};
			return tmp.join(' ');
		}
    if(typeof(time) == "number"){
      var hours = Math.floor(time / 3600);
      time %= 3600;
      var minutes = Math.floor(time / 60);
      minutes = String(minutes).padStart(2, "0");
      hours = String(hours).padStart(2, "0");
      time = hours + ":" + minutes;
    } else{
    	let b = time.replace(/pm|am/gi,'');
      var a = b.split(':'); 
      var time = (+a[0]) * 60 * 60 + (+a[1]) * 60; 
    }
    return time;
  }


	function closeStart(){
		$('#close-start').click(e=>{
			updateRoute();
			$('#start-locat').attr('data-clsclk',1);
			$('#start-locat span').text('Click chuột vào đây để chọn điểm bắt đầu');
			$('#start-locat').attr('data-start',0);
			let id = startLocat.id;
			if(id != undefined) {
				$(`option[value="${startLocat.id}"`)[0].disabled = false;
				startLocat.marker.setMap(null);
				$(`.map-marker-label[value="${id}"]`).remove();
				startLocat = {};
			}
			$(e.currentTarget).remove();
		})
		$("#time-cost-picker").hide();
	}

	function showTimeCost(id){
		if(id == undefined){
			$("#time-cost-picker").hide();
			$("#get-route-pannel").hide();
			return;
		}
		$("#time-cost-picker").show();
		$("#time-cost-picker").attr('value',id);
		$("#get-route-pannel").show();
		$('#duration-picker').val(parseInt(Object(locationdata.get(id)).de_duration));
		$('#duration-picker').trigger('change');
		$('#duration-picker').change(()=>{
			idToData($("#time-cost-picker").attr('value'),'setDur',$('#duration-picker').val())
			// console.log(locationdata.get($("#time-cost-picker").attr('value')))
			updateRoute()
		})
		$('#location-dur-cost').text(Object(locationdata.get(id)).de_name);
		if($(".currency").val() == "VNĐ")
		{
			$('#amount').val(Object(locationdata.get(id)).de_cost);
			let money =idToData(id,'cost');
			$('.amount-text').text(money.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + " "+$(".currency").val());
		}
		else if($(".currency").val() == "USD")
		{
			let new_money = parseFloat(Object(locationdata.get(id)).de_cost)/23000;
			$("#amount").val(parseFloat(new_money.toFixed(2)));
			let money =$("#amount").val();
			$('.amount-text').text(money.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + " "+$(".currency").val());
		}
	}

	function processAddToList(){
		updateRoute();
		$('#add-waypoints').hide();	
		let length = markerArray.size +1;
		let id = $("#search-input").find(":selected").val();
		let text = $("#search-input").find(":selected").text();

		if($(".list-item").length != 5) {
			showTimeCost(id);
		  	//Disable selected option
			$("#search-input").find(":selected").prop('disabled',true);
			$('#search-input').val('').trigger('change');
			$('#search-input').select2();
		}else
		{
			alert("you have chosen more than 5 points");
		}
		if($('#start-locat').attr('data-start') === '1'){
			$('#start-locat').html(`<span>${text}</span><div id="close-start" style="display: inline-flex; position: absolute; right: 0.5em;" ><i class="fas fa-times " ></i></div>`);
			closeStart();
			$('#start-locat').attr('data-start',2);
			let height_time_cost = parseFloat($('#start-locat').offset().top) - 60;
			$("#time-cost-picker").css("top",height_time_cost+"px");
			startLocat.id = id;
			startLocat.marker = newMarkOnClk.marker;
			newMarkOnClk = {}
			if(Object(locationdata.get(id)).de_default == undefined){
				if(Object.entries(newMarkOnClk).length){
					newMarkOnClk.marker.setMap(null);
					deleteMarker(newMarkOnClk.id);
					newMarkOnClk ={};
				}
				addMarkers(id,colorlist[(length-1)%5]);
			}
			return;

		}
		if(length >= 6) return;
		locationID.push(id)
		if(Object.entries(newMarkOnClk).length){
			newMarkOnClk.marker.setMap(null);
			deleteMarker(newMarkOnClk.id);
			newMarkOnClk ={};
		}
		
		addToList(id,colorlist[(length-1)%5],length,text);
		addMarkers(id,colorlist[(length-1)%5]);

	}

	function addMarkers(id,color){
		let marker,icon,label;
		if(id == startLocat.id){
			startLocat.marker =  	new google.maps.Marker({
					position: Object(locationdata.get(id)).location,
					label: Object(locationdata.get(id)).de_name,
					map: map
				});
			customLabel(startLocat.marker,id);
			marker = startLocat.marker;
		} else {
			icon = {
				path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
				fillColor: color,
				fillOpacity: 1,
				strokeColor: 'white',
				strokeWeight: 3,
				scale: 1.4,
			};
			label = {
				text: Object(locationdata.get(id)).de_name,
				color: color,
				fontWeight: 'bold',
				fontSize: '20px' 
			};

				
			marker = 	new google.maps.Marker({
										map: map,
										position: Object(locationdata.get(id)).location,
										// label: label,
										icon: icon
								});

			markerArray.set(id,marker);
		}

			let content = '<p><h4>'+Object(locationdata.get(id)).de_name+'</h4></p>'+ 
					'<p><a href="'+Object(locationdata.get(id)).de_link+'"target="_blank">Click to view tour</a></p>',
					infowindow = new google.maps.InfoWindow({
						content: content,
					});

			marker.addListener('click',()=>{
				let imglink;
				if(Object(locationdata.get(id)).de_img != undefined){
					imglink = "{{asset('imgs/icon.jpg')}}"
				} else {
					imglink = "{{asset('imgs/icon.jpg')}}"
				}
				let image = {
					url: imglink,
					// size: new google.maps.Size(150, 90),
				}
				marker.setIcon(image);
				if(id != startLocat.id){
					marker.setLabel('');
				} else{
					$(`.map-marker-label[value="${id}"]`).hide();
				}
				infowindow.open(map, marker);
			});

			infowindow.addListener('closeclick',()=>{
				if(id != startLocat.id){
					//marker.setLabel(label);
				} else {
					$(`.map-marker-label[value="${id}"]`).show();
				}
				marker.setIcon(icon);
			});

			fitBoundsMarkers();
	}

	function fitBoundsMarkers() {
		if(!markerArray.size){
			map.setZoom(12.5);
			map.setCenter({lat: 21.0226586, lng: 105.8179091});
			return;
		}
    let bounds = new google.maps.LatLngBounds();
    markerArray.forEach((value,key)=>{
				bounds.extend(value.getPosition());
    })

    if(Object.entries(startLocat).length){ bounds.extend(startLocat.marker.getPosition()); }
	 	map.fitBounds(bounds);
	}

	//add location to list
	function addToList(id,color,index,text){
		$("#list-container").append(
			`<div data-target="#time-cost-picker" class="list-item" value="${id}" title="${text}">`+
				`<div class="item-content" value="${id}" style="background-color: ${color};">`+
					`<div class="order">${index}</div>`+
					`<div class="item-content-text">${text}</div>`+
					`<span class="item-content-close" value="${id}"><i class="fas fa-times" ></i></span>`+
				`</div>`+
			`</div>`
		);
		let listItemHeight = ($(`.list-item`).length-1)*71;
		let height_time_cost = parseFloat($(`.list-item`).offset().top) - 60 +listItemHeight;
		$("#time-cost-picker").css("top",height_time_cost+"px");
		deleteFromList();//delete event
		showDetail();
		sortableList();
	}

	function reorderlist(){
		$('#list-container').empty();
		for(var i= 0; i<locationID.length;i++){
			$("#list-container").append(
				`<div class="list-item" value="${locationID[i]}" title="${idToData(locationID[i],'name')}">`+
					`<div class="item-content" value="${locationID[i]}" style="background-color: ${colorlist[i]};">`+
						`<div class="order">${i+1}</div>`+
						`<div class="item-content-text">${idToData(locationID[i],'name')}</div>`+
						`<span class="item-content-close" value="${locationID[i]}"><i class="fas fa-times" ></i></span>`+
					`</div>`+
				`</div>`
			);
			deleteFromList();//delete event
			showDetail();
		}
		sortableList();
		changeMarkerColor();
	}

		function idToData(id,type,value){
		if(type == 'LatLngArr'){
			locats = [];
			locationID.forEach(ele=>{
				locats.push(Object(locationdata.get(ele)).location);
			})
			if(Object.entries(startLocat).length)  locats.unshift(idToData(startLocat.id,'LatLng'));
			if($('#is-back').is(':checked')) locats.push(locats[0]);
		}

			switch(type){
				case 'name':
					return Object(locationdata.get(id)).de_name;
					break;
				case 'default':
					return Object(locationdata.get(id)).de_default;
					break;
				case 'LatLng':
					return Object(locationdata.get(id)).location;
					break;
				case 'duration':
					return Object(locationdata.get(id)).de_duration;
					break;
				case 'link':
					return Object(locationdata.get(id)).de_link;
					break;
				case 'description':
					return Object(locationdata.get(id)).de_description;
					break;
				case 'setDur':
					if (locationdata.has(id)){
						locationdata.get(id).de_duration = parseInt(value); 
					}
					break;
				case 'setCost':
					if (locationdata.has(id)){
						locationdata.get(id).de_cost = parseInt(value); 
					}
					break;
				case 'cost':
					return Object(locationdata.get(id)).de_cost;
					break;

				default: break;
			}
	}

	function showDetail(){
		$('.list-item').click((e)=>{
			let id = e.currentTarget.getAttribute('value');
			let name = Object(locationdata.get(id)).de_name;
			let descript = Object(locationdata.get(id)).de_description;
			let link = Object(locationdata.get(id)).de_link;
			$("#link-img").attr("href",'{!! url('/') !!}'+'/showDetailPlace/'+id);
			$('.name-place').text(name);
			$('#description').text(descript);
			$('.link-here').attr('href',link);
			if(Object(locationdata.get(id)).de_description != undefined){
				$(".parents-img > img").attr('src',Object(locationdata.get(id)).de_img);
			} else {
				$(".parents-img > img").attr('src',"{{ asset('imgs/image.jpg') }}");
			}
			$('#short_des').text(Object(locationdata.get(id)).de_shortdes);
			$("#location-detail").show();
			let height = $('#control-content').height()-$("#location-detail").height();
			$('#bottom-height').height(height);
			// $('#control-content').animate({
			// 	scrollTop: $("#location-detail").offset().top
			// }, 500);
			let height_time_cost = parseFloat($(e.currentTarget).last().offset().top) - 60;
			$("#time-cost-picker").css("top",height_time_cost+"px");
			showTimeCost(id);
		});
	}

	function deleteFromList(){
		$('.item-content-close').last().click(ele => {
			let id = ele.currentTarget.attributes.value.value;
			if($('.list-item').length == 1){
			 	$('#location-detail').hide();
			}
			locationID.splice(locationID.indexOf(id),1);//Remove locationid in array
		  $(ele.currentTarget).parent().parent().remove();//Remove in list
		  $(`option[value="${id}"`)[0].disabled = false;// Undisabled in search
		  
		  
		  sortableList();
		  for(let i=0,arr = $(".order"); i<arr.length;i++){
					arr[i].innerText = i+1;
					arr[i].parentElement.style.backgroundColor = colorlist[i];
			}
			if(!markerArray.has(id)) return;
			//Remove marker
			Object(markerArray.get(id)).setMap(null);
		  markerArray.delete(id);
			fitBoundsMarkers();
			id = $('.list-item').last().attr('value');
			showTimeCost(id);
			$('#get-route').show();
			updateRoute();
		});
	}

	function deleteMarker(value){
		if(value != undefined){
			$(`.map-marker-label[value="${value}"]`).remove();
			return
		}
		$('.map-marker-label').remove();
	}

	function geocoderCallBack(response, status){
		newMarkOnClk.id = response[0].place_id;
		newMarkOnClk.marker = new google.maps.Marker({
									position: response[0].geometry.location,
									label: response[0].formatted_address,
									map: map
								});

		let obj = {
								de_name: response[0].formatted_address,
								location: response[0].geometry.location.toJSON(),
								de_duration: 3600,
								de_cost: 100000,
								de_default: 1
							}

		locationdata.set(response[0].place_id,obj);
		customLabel(newMarkOnClk.marker,response[0].place_id);

		//add new location option in select 
		$("#search-input").append('<option value="'+
					response[0].place_id+'">'+response[0].formatted_address+ '</option>');
		$('#search-input').val(response[0].place_id);
	}

	function customLabel(marker,place_id) {
		let MarkerLabel = function(options,place_id) {
			this.setValues(options);
			this.span = document.createElement('span');
			this.span.className = 'map-marker-label';
			if(place_id!=undefined);
				this.span.setAttribute('value',place_id);
		};
 
		MarkerLabel.prototype = $.extend(new google.maps.OverlayView(), {
			onAdd: function() {
				this.getPanes().overlayImage.appendChild(this.span);
				var self = this;
				this.listeners = [
					google.maps.event.addListener(this, 'position_changed', function() {
						self.draw();
					})
				];
			},
			draw: function() {
				var markerSize = {
					x: 27,
					y: 43
				};
				var text = String(this.get('text'));
				// var color = String(this.get('text'));
				var position = this.getProjection().fromLatLngToDivPixel(this.get('position'));
				this.span.innerHTML = text;
				this.span.style.left = (position.x) + 10 +'px';
				this.span.style.top = (position.y)  -15+ 'px';
			}
		});

		let label = marker.label;
		marker.label = new MarkerLabel({
			map: marker.map,
			marker: marker,
			text: label
		},place_id);
		marker.label.bindTo('position', marker, 'position');
		marker.setLabel('');
	};

	function sortableList(){
		let rowSize = 71; // => container height / number of items
		let container = document.querySelector("#list-container");
		let height = container.childElementCount;
		height = height * rowSize;
		$('#locat-list').css('height',height+'px');
		let listItems = Array.from(document.querySelectorAll(".list-item")); // Array of elements
		let sortables = listItems.map(Sortable); // Array of sortables
		let total = sortables.length;
		

		function Sortable(element, index) {

			var content = element.querySelector(".item-content");
			var order = element.querySelector(".order");

			var animation = TweenLite.to(content, 0.3, {
				boxShadow: "rgba(0,0,0,0.2) 0px 16px 32px 0px",
				force3D: true,
				scale: 1.1,
				paused: true });


			var dragger = new Draggable(element, {
				onDragStart: downAction,
				onRelease: upAction,
				onDrag: dragAction,
				cursor: "inherit",
				type: "y" });


			// Public properties and methods
			var sortable = {
				dragger: dragger,
				element: element,
				index: index,
				setIndex: setIndex };


			TweenLite.set(element, { y: index * rowSize });

			function setIndex(index) {

				sortable.index = index;
				order.textContent = index + 1;
				content.setAttribute('style',`background-color: ${colorlist[index]}`);
	
				// Don't layout if you're dragging
				if (!dragger.isDragging) layout();
			}

			function downAction() {
				animation.play();
				this.update();
			}

			function dragAction() {

				// Calculate the current index based on element's position
				var index = clamp(Math.round(this.y / rowSize), 0, total - 1);

				if (index !== sortable.index) {
					changeIndex(sortable, index);
				}
			}

			function upAction() {
				animation.reverse();
				layout();
			}

			function layout() {
				TweenLite.to(element, 0.3, { y: sortable.index * rowSize });
			}

			return sortable;
		}

		TweenLite.to(container, 0.5, { autoAlpha: 1 });

		function changeIndex(item, to) {

			// Change position in array
			arrayMove(sortables, item.index, to);

			// Change element's position in DOM. Not always necessary. Just showing how.
			if (to === total - 1) {
				container.appendChild(item.element);
			} else {
				var i = item.index > to ? to : to + 1;
				container.insertBefore(item.element, container.children[i]);
			}

			// Set index for each sortable
			sortables.forEach((sortable, index) => {
				sortable.setIndex(index);			
			});

			let tmp = [];
			for(let i = 0; i < sortables.length;i++){
				tmp.push(sortables[i].element.getAttribute('value'));
			}
			locationID = tmp;
			changeMarkerColor();
			updateRoute();
		}
		// Changes an elements's position in array
		function arrayMove(array, from, to) {
			array.splice(to, 0, array.splice(from, 1)[0]);
		}

		// Clamps a value to a min/max
		function clamp(value, a, b) {
			return value < a ? a : value > b ? b : value;
		}	
	}

	function changeMarkerColor(){
		let listItems = Array.from(document.querySelectorAll('.item-content'))
		listItems.forEach(ele=>{
			let id = ele.attributes.value.value;
			let color = ele.style.backgroundColor;
			if(markerArray.has(id)){
				let icon = {
					path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
					fillColor: color,
					fillOpacity: 1,
					strokeColor: 'white',
					strokeWeight: 3,
					scale: 1.4,
				},
				label = {
					text: Object(locationdata.get(id)).de_name,
					color: color,
					fontWeight: 'bold',
					fontSize: '20px' 
				}; 
				Object(markerArray.get(id)).setIcon(icon);
				Object(markerArray.get(id)).setLabel(label);
			} else {
				addMarkers(id,color);
			}
						
		})
		
	}

	function processanddrawrouteclient(){ 
			let Arr = [];
			allRoutePosible = [];
			//Check if recent results can reuse (Don't have to call api agian)
			if(!recentLocatID.length){
				recentLocatID = locationID.splice();
				if(Object.entries(startLocat).length) 
					recentStart = startLocat.id 
				else 
					recentStart = undefined;
	 		} else {
	 			// check nếu mảng mới kể cả điểm bắt đầu có ở mảng cũ chưa
	 			let tmpLocationID = locationID.slice();
	 			if(Object.entries(startLocat).length) tmpLocationID.unshift(startLocat.id);
	 			let tmprecentLocatID = recentLocatID.slice();
	 			if(recentStart != undefined) tmprecentLocatID.unshift(recentStart); 
				// if(locationID.every(e=>recentLocatID.includes(e))||(locationID.every(e=>recentLocatID.includes(e))&&(recentStart == startLocat.id || locationID.indexOf(recentStart)>=0))){
				if(tmpLocationID.every(e=>tmprecentLocatID.includes(e))){ 
					locationID.forEach(ele => Arr.push(
						((recentStart == startLocat.id && recentStart != undefined) || locationID.indexOf(recentStart)>=0)?(recentLocatID.indexOf(ele)+1):(recentLocatID.indexOf(ele))
					));

					//Nếu điêm bắt đầu có tồn tại gán điểm bắt đầu = 0 nếu bằng điểm bắt đầu mới else gán bằng chi số trong mảng
					if(locationID.every(e=>recentLocatID.includes(e))&&(recentStart == startLocat.id || locationID.indexOf(recentStart)>=0)){
						if(recentStart == startLocat.id) 
							newStartIndex = 0;
						else
							newStartIndex = recentLocatID.indexOf(recentStart);

					}
					arrPermutations(Arr.length,Arr);
					bestWay();
					return;
				}
			}

			if(Object.entries(startLocat).length) 
				recentStart = startLocat.id 
			else 
				recentStart = undefined;
			recentLocatID = locationID.slice();
			//init array [1,2,3,4,...n] with n is number of locations
			for(var i = 0;i < locationID.length;i++)
				Arr[i] = (Object.entries(startLocat).length)?(i+1):i;  
			/*swaps the positions of the elements 
				in the array to create all possible paths*/
			arrPermutations(Arr.length,Arr); 
			distanceService.getDistanceMatrix({
				origins: locats,
				destinations: locats,
				travelMode: google.maps.TravelMode.DRIVING,
			},bestWay);
	
			
			

			//-----------------------------------------------------
			function arrPermutations(n,Arr){ 
			if (n == 1){
			 allRoutePosible.push(Arr.slice());
			} else {
				for(var i = 0; i <= n-1; i++) {
					arrPermutations(n-1, Arr);
					swapArrEle(n % 2 == 0 ? i : 0 ,n-1,Arr);
				}
			}
		}

		// Swap elements of array
		function swapArrEle(a,b,Arr){
			var tmp = Arr[a];
			Arr[a] = Arr[b];
			Arr[b] = tmp;
		}
	}



	function bestWay(response,status){
		let time = document.querySelector("#startDate")._flatpickr.selectedDates
		let choosendur = 	(time[1]-time[0])/1000;
		let startIndex = 0;
		if(newStartIndex != undefined) startIndex = newStartIndex;
		if(response != undefined) disresponse = response;
		let routeOptimized = {
			route: [],
			value: 0
		}
		let total = 0;
		let tmp = [];
		let tmpArr = [];
		if(recentLocatID.length) 
			tmpArr = recentLocatID;
		else 
			tmpArr = locationID;
		// Optimize by duration with the start location
		if(!isAuDel && isopt == 1 && Object.entries(startLocat).length){
		// Loop all route posible to calculate the best way
			for(var i = 0 ;i<allRoutePosible.length; i++){
				var A = allRoutePosible[i];
				
					// total += disresponse.rows[A[0]].elements[A[1]].duration.value;
					for(var j = 0 ;j<A.length; j++){
						if(j==0){
							total += disresponse.rows[startIndex].elements[A[0]].duration.value;
						} else {
							 total += disresponse.rows[A[j-1]].elements[A[j]].duration.value;
						}
							total+= Object(locationdata.get(locationID[A[j]-1])).de_duration;  
					}
					if($('#is-back').is(':checked')) 
						total += disresponse.rows[A[A.length-1]].elements[0].duration.value;

					if (routeOptimized.value == 0){ 
						routeOptimized.route = A;
						routeOptimized.value = total;
					}
					if(total < routeOptimized.value){
						routeOptimized.route = A;
						routeOptimized.value = total;
					}
					total = 0;
				}
		}

		// Optimize by cost with the start location
		if(!isAuDel && isopt == 2 && Object.entries(startLocat).length){
			for(var i = 0 ;i<allRoutePosible.length; i++){// Loop all route posible to calculate the best way
				var A = allRoutePosible[i];
				for(var j = 0 ;j<A.length-1; j++){
					if(j==0){
						total += disresponse.rows[startIndex].elements[A[0]].distance.value;
					} else {
						total += disresponse.rows[A[j-1]].elements[A[j]].distance.value;
					}
				}

				if($('#is-back').is(':checked')) 
					total += disresponse.rows[A[A.length-1]].elements[0].distance.value;

				if (routeOptimized.value == 0){ 
					routeOptimized.route = A;
					routeOptimized.value = total;
				}
				if(total < routeOptimized.value){
					routeOptimized.route = A;
					routeOptimized.value = total;

				}
				total = 0;
			}
		}

		// if(!isAuDel && isopt == 1 && startlocat == undefined && newPlaceIdArr.length){
		if(!isAuDel && isopt == 1 && !Object.entries(startLocat).length){
			for(var i = 0 ;i<allRoutePosible.length; i++){
				var A = allRoutePosible[i];
				for(var j = 0 ;j<A.length-1; j++){
					total+= Object(locationdata.get(tmpArr[A[j]])).de_duration;
					total+= disresponse.rows[A[j]].elements[A[j+1]].duration.value;
				}
				total+= Object(locationdata.get(tmpArr[A[A.length-1]])).de_duration;

				if($('#is-back').is(':checked')) 
						total += disresponse.rows[A[A.length-1]].elements[0].duration.value;

				if (routeOptimized.value == 0){ 
					routeOptimized.route = A;
					routeOptimized.value = total;
				}
				if(total < routeOptimized.value){
					routeOptimized.route = A;
					routeOptimized.value = total;
				}
				total = 0;
			}
		}

		// if(!isAuDel && isopt == 2 && startlocat == undefined && newPlaceIdArr.length){
			if(!isAuDel && isopt == 2 && !Object.entries(startLocat).length){
			for(var i = 0 ;i<allRoutePosible.length; i++){
				var A = allRoutePosible[i];
				for(var j = 0 ;j<A.length-1; j++)
					total+= disresponse.rows[A[j]-1].elements[A[j+1]-1].distance.value;

				if($('#is-back').is(':checked')) 
						total += disresponse.rows[A[A.length-1]-1].elements[0].distance.value;

				if (routeOptimized.value == 0){ 
					routeOptimized.route = A;
					routeOptimized.value = total;
				}
				if(total < routeOptimized.value){
					routeOptimized.route = A;
					routeOptimized.value = total;
				}
				total = 0;
			}
		}
		
		if(isAuDel && Object.entries(startLocat).length){
			let allRouteOptimize =[];
			for(var i = 0 ;i<allRoutePosible.length; i++){
					var A = allRoutePosible[i];
					let tmpRouteOpt = {
						route: [],
						value: 0
					}
					for(var j =0;j<A.length;j++){
						if(j==0){
							total += disresponse.rows[startIndex].elements[A[0]].duration.value;
						} else {
							 total += disresponse.rows[A[j-1]].elements[A[j]].duration.value;
						}
						total+= Object(locationdata.get(tmpArr[A[j]])).de_duration;
						var tmptotal = total;
						if($('#is-back').is(':checked')){
							total+= disresponse.rows[A[j]].elements[0].duration.value;
						}
						if(total<=choosendur){
							if (tmpRouteOpt.value == 0){ 
								tmpRouteOpt.route.push(A[j]);
								tmpRouteOpt.value = tmptotal;
							}else {
								tmpRouteOpt.route.push(A[j]);
								tmpRouteOpt.value = tmptotal;
							}
						} else {
							allRouteOptimize.push(tmpRouteOpt);
							break;
						}
					} 
					total = 0;
			}
			let min = allRouteOptimize[0];
			for(let k = 1; k < allRouteOptimize.length; k++){
				if(allRouteOptimize[k].route.length >= min.route.length){
					if(allRouteOptimize[k].route.length > min.route.length){
						min = allRouteOptimize[k];
					}else if(allRouteOptimize[k].value <= min.value){
						min = allRouteOptimize[k]
					}
				}
			}

			routeOptimized = min;
		}

		if(isAuDel && !Object.entries(startLocat).length){
			let allRouteOptimize =[];
			for(var i = 0 ;i<allRoutePosible.length; i++){
					var A = allRoutePosible[i];
					let tmpRouteOpt = {
						route: [],
						value: 0
					}
					for(var j =0;j<A.length;j++){
						if(j!=0){
							 total += disresponse.rows[A[j-1]].elements[A[j]].duration.value;
						}
						total+= Object(locationdata.get(tmpArr[A[j]])).de_duration;
						var tmptotal = total;
						if($('#is-back').is(':checked')){
							total+= disresponse.rows[A[j]].elements[0].duration.value;
						}
						if(total<=choosendur){
							if (tmpRouteOpt.value == 0){ 
								tmpRouteOpt.route.push(A[j]);
								tmpRouteOpt.value = tmptotal;
							}else {
								tmpRouteOpt.route.push(A[j]);
								tmpRouteOpt.value = tmptotal;
							}
						} else {
							allRouteOptimize.push(tmpRouteOpt);
							break;
						}
					} 
					total = 0;
			}
			let min = allRouteOptimize[0];
			for(let k = 1; k < allRouteOptimize.length; k++){
				if(allRouteOptimize[k].route.length >= min.route.length){
					if(allRouteOptimize[k].route.length > min.route.length){
						min = allRouteOptimize[k];
					}else if(allRouteOptimize[k].value <= min.value){
						min = allRouteOptimize[k]
					}
				}
			}

			routeOptimized = min;
			
		}

		for(var i=0;i<routeOptimized.route.length;i++)
			tmp[i] = (Object.entries(startLocat).length)?tmpArr[routeOptimized.route[i]-1]:tmpArr[routeOptimized.route[i]];
		locationID = tmp;
		timeCheck(routeOptimized.value);	
		
	}

	function timeCheck(value){
		let time = document.querySelector("#startDate")._flatpickr.selectedDates
		let choosendur = 	(time[1]-time[0])/1000;
		if(isNaN(choosendur)){
			idToData(null,'LatLngArr');
			drawRoutes();
			return;
		}
		if(value > choosendur){
			$("#timeAlert").modal("show");
			$("#del-time-click").click(()=>{
				$("#timeAlert").modal("hide");
				isAuDel = 1;
				bestWay();
			});
			$("#timeAlert-close").click(()=>{
				$("#timeAlert").modal("hide");
				$('#get-route').show()
			});
			return;
		} else {
			idToData(null,'LatLngArr');
			drawRoutes();
		}
		isopt = 0;
	}
	function writeDetailForSave(){
		$(".content_modal_left").empty();
		$(".content_modal_left").append('<span class="mb-0">Detail tour</span>');
		if($('#endDate').val() == "")
			$(".content_modal_left").append('<span class="font-italic text-primary">from '+$('#startDate').val()+'</span>');
		else
			$(".content_modal_left").append('<span class="font-italic text-primary">from '+$('#startDate').val()+' to '+$('#endDate').val()+'</span>');
		//set start locat
		if(Object.entries(startLocat).length){
			let startlocat_image = "{{asset('imgs/image.jpg')}}";
			let startlocat_name = Object(locationdata.get(startLocat.id)).de_name;
			let startlocat_duration = Object(locationdata.get(startLocat.id)).de_duration;
			let formatCost = (Object(locationdata.get(startLocat.id)).de_cost).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
			let startlocat_cost = formatCost + $(".currency").val();
			$(".content_modal_left").append('<div class="detail_tour"><div class="left_content"><a data-fancybox="gallery" href="'+startlocat_image+'"><img class="img-fluid rounded mb-5" style="width:100%;" src="'+startlocat_image+'" alt=""></a></div><div class="right_content"><p class="mb-1">Place name: <span class="font-italic text-danger">'+startlocat_name+'</span></p><p class="mb-1">Duration: <span class="font-italic text-danger">'+converttime(startlocat_duration,'duration')+'</span></p><p class="mb-1">Cost: <span class="font-italic text-danger">'+startlocat_cost+'</span></p></div></div>');
		}
		//set detail
		locationID.forEach(ele=>{
			let detail_image;
			if(Object(locationdata.get(ele)).de_img == undefined)
				detail_image = "{{asset('imgs/image.jpg')}}";
			else
				detail_image = Object(locationdata.get(ele)).de_img;
			let detail_name = Object(locationdata.get(ele)).de_name;
			let detail_duration = Object(locationdata.get(ele)).de_duration;
			let formatCost = (Object(locationdata.get(ele)).de_cost).toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
			let detail_cost = formatCost + $(".currency").val();
			$(".content_modal_left").append('<div class="detail_tour"><div class="left_content"><a data-fancybox="gallery" href="'+detail_image+'"><img class="img-fluid rounded mb-5" style="width:100%;" src="'+detail_image+'" alt=""></a></div><div class="right_content"><p class="mb-1">Place name: <span class="font-italic text-danger">'+detail_name+'</span></p><p class="mb-1">Duration: <span class="font-italic text-danger">'+converttime(detail_duration,'duration')+'</span></p><p class="mb-1">Cost: <span class="font-italic text-danger">'+detail_cost+'</span></p></div></div>');
		})
	}
	(function (){
				// part 2
				@if(!isset($to_des))
					$('#upload_form').on('submit',(function(e) {
						e.preventDefault();
						let nameTour = $('input[name="nameTour"]').val();
						if(nameTour == "")
						{
							alert("Please enter the tour name first");
						}
						else
						{
							let $url_path = '{!! url('/') !!}';
							//let _token = $('meta[name="csrf-token"]').attr('content');
							let routeDetail=$url_path+"/saveTour";
							// information of tour
							let a = $('#startDate').val().replace(/am|pm/gi,':00');
							let b = a.replace(/\//g,'-');
							let timeStart = b;
							let timeEnd = converttime(timeline[timeline.length-1]) - converttime(timeline[0]);
							let to_comback;
							if ($('#is-back').is(':checked')) 
								to_comback = "1";
							else 
								to_comback = "0";
							let to_optimized = '1';
							let currency = $(".currency").val();
							let tmparr = [];
							let val = {};
							if(Object.entries(startLocat).length){
								val.de_id = startLocat.id;
								let coor = Object(locationdata.get(startLocat.id)).location;
								val.location = coor.lat+"|"+coor.lng;
								val.de_name = Object(locationdata.get(startLocat.id)).de_name;
								val.de_duration = Object(locationdata.get(startLocat.id)).de_duration;
								val.de_default = Object(locationdata.get(startLocat.id)).de_default;
								val.de_cost = Object(locationdata.get(startLocat.id)).de_cost;
							}
							locationID.forEach(ele=>{
								let coor = Object(locationdata.get(ele)).location;
								let tmp = ele+'';
								tmparr.push({
									de_id: tmp,
									location: coor.lat+"|"+coor.lng,
									de_name: Object(locationdata.get(ele)).de_name,
									de_duration: Object(locationdata.get(ele)).de_duration,
									de_default: Object(locationdata.get(ele)).de_default,
									de_cost: Object(locationdata.get(ele)).de_cost
								})
							})
							// information of share
							let star = $('input[name="star"]').val();
							let options = $('input[name="options"]:checked').val();
							let recommend = $('textarea[name="recommend"]').val();
							
							$.ajaxSetup({
					            headers: {
					                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					            }
					        });
							$.ajax({
								url:routeDetail,
								method:"post",
								data:{tmparr:tmparr,timeStart:timeStart,timeEnd:timeEnd,to_comback:to_comback,to_optimized:to_optimized,val:val,nameTour:nameTour,star:star,options:options,recommend:recommend,currency:currency},
								success:function(data){ 
									if(data[0] == "no")
										location.replace(data[3])
									else if(data[0] == "yes")
									{
										let form_data = new FormData(document.getElementById("upload_form"));
										$.ajax({
											url:$url_path+"/saveImgShareTour/"+data[1],
											method:"post",
											data:form_data,
											cache:false,
											contentType: false,
											processData: false,
											success:function(result){ 
												location.replace(data[3])
											}
										});
									}
								}
							});
						}
					}));
					$('#enterNameTour').on('show.bs.modal', function (event) {
			            let _token = $('meta[name="csrf-token"]').attr('content');
			            let $url_path = '{!! url('/') !!}';
			            let routeDuplicate = $url_path+"/duplicate";
			            let tmparr = [];
			            locationID.forEach(ele=>{
							let coor = Object(locationdata.get(ele)).location;
							let tmp = ele+'';
							tmparr.push({
								de_id: tmp,
								location: coor.lat+"|"+coor.lng,
								de_name: Object(locationdata.get(ele)).de_name,
								de_duration: Object(locationdata.get(ele)).de_duration,
								de_default: Object(locationdata.get(ele)).de_default,
								de_cost: Object(locationdata.get(ele)).de_cost
							})
						});
						//load detail
						writeDetailForSave();
			            $.ajax({
			                  url:routeDuplicate,
			                  method:"POST",
			                  data:{_token:_token,tmparr:tmparr},
			                  success:function(data){ 
			                  	if(data.length != 0)
			                  	{
			                  		$("#tourExists").show();
			                  		$(".list_tourExists ul").empty();
			                  		data.forEach(function(item, index){
			                  			let routeEditTourExists = $url_path+'/editTourUser/'+item.idTour;
			                  			$(".list_tourExists ul").append('<li><a href="'+routeEditTourExists+'">'+item.nameTour+'</a></li>');
			                  		});
			                  	}
			                    else
			                    {
			                    	$("#tourExists").hide();
			                    }
			                }
			            });
			        });
				@else
					//edit tour
					$('#upload_form').on('submit',(function(e) {
						e.preventDefault();
						let nameTour = $('input[name="nameTour"]').val();
						if(nameTour == "")
						{
							alert("Please enter the tour name first");
						}
						else
						{
							let $url_path = '{!! url('/') !!}';
              				let routeId = {{$id}};
							let routeDetail=$url_path+"/editRoute/"+routeId;
							// information of tour
							let a = $('#startDate').val().replace(/am|pm/gi,':00');
							let b = a.replace(/\//g,'-');
							let timeStart = b;
							let timeEnd = converttime(timeline[timeline.length-1]) - converttime(timeline[0]);
							let to_comback;
							if ($('#is-back').is(':checked')) 
								to_comback = "1";
							else 
								to_comback = "0";
							let to_optimized = '1';
							let currency = $(".currency").val();
							let tmparr = [];
							let val = {};
							if(Object.entries(startLocat).length){
								val.de_id = startLocat.id;
								let coor = Object(locationdata.get(startLocat.id)).location;
								val.location = coor.lat+"|"+coor.lng;
								val.de_name = Object(locationdata.get(startLocat.id)).de_name;
								val.de_duration = Object(locationdata.get(startLocat.id)).de_duration;
								val.de_default = Object(locationdata.get(startLocat.id)).de_default;
								val.de_cost = Object(locationdata.get(startLocat.id)).de_cost;
							}

							locationID.forEach(ele=>{
								let coor = Object(locationdata.get(ele)).location;
								let tmp = ele+'';
								tmparr.push({
									de_id: tmp,
									location: coor.lat+"|"+coor.lng,
									de_name: Object(locationdata.get(ele)).de_name,
									de_duration: Object(locationdata.get(ele)).de_duration,
									de_default: Object(locationdata.get(ele)).de_default,
									de_cost: Object(locationdata.get(ele)).de_cost
								})
							})
							// information of share
							let star = $('input[name="star"]').val();
							let options = $('input[name="options"]:checked').val();
							let recommend = $('textarea[name="recommend"]').val();
							
							$.ajaxSetup({
					            headers: {
					                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					            }
					        });
					        //console.log(tmparr);
					        ///
							$.ajax({
								url:routeDetail,
								method:"post",
								data:{tmparr:tmparr,timeStart:timeStart,timeEnd:timeEnd,to_comback:to_comback,to_optimized:to_optimized,val:val,nameTour:nameTour,star:star,options:options,recommend:recommend,currency:currency},
								success:function(data){ 
									if(data[0] == "no")
										location.replace(data[3])
									else if(data[0] == "yes")
									{
										let form_data = new FormData(document.getElementById("upload_form"));
										$.ajax({
											url:$url_path+"/saveImgShareTour/"+data[1],
											method:"post",
											data:form_data,
											cache:false,
											contentType: false,
											processData: false,
											success:function(result){ 
												location.replace(data[3])
											}
										});
									}
								}
							});
							///
						}
					}));
					$("#saveTour").show();
					$('#enterNameTour').on('show.bs.modal', function (event) {
			            let _token = $('meta[name="csrf-token"]').attr('content');
			            let $url_path = '{!! url('/') !!}';
			            let routeId = {{$id}};
			            let routeCheckTour = $url_path+"/getinfor-touredit";
			            //load detail
						writeDetailForSave();
			            $.ajax({
			                  url:routeCheckTour,
			                  method:"POST",
			                  data:{_token:_token,routeId:routeId},
			                  success:function(data){ 
			                    $("input[name=nameTour]").val(data[0]);
			                    $("#star_Share").val(data[1]);
			                    if(data[1] == "1")
			                    {
			                    	$(".star1").css("color","#ff9700");
			                    }
			                    else if(data[1] == "2")
			                    {
			                    	$(".star1").css("color","#ff9700");
			                    	$(".star2").css("color","#ff9700");
			                    }
			                    else if(data[1] == "3")
			                    {
			                    	$(".star1").css("color","#ff9700");
			                    	$(".star2").css("color","#ff9700");
			                    	$(".star3").css("color","#ff9700");
			                    }
			                    else if(data[1] == "4")
			                    {
			                    	$(".star1").css("color","#ff9700");
			                    	$(".star2").css("color","#ff9700");
			                    	$(".star3").css("color","#ff9700");
			                    	$(".star4").css("color","#ff9700");
			                    }
			                    else if(data[1] == "5")
			                    {
			                    	$(".star1").css("color","#ff9700");
			                    	$(".star2").css("color","#ff9700");
			                    	$(".star3").css("color","#ff9700");
			                    	$(".star4").css("color","#ff9700");
			                    	$(".star5").css("color","#ff9700");
			                    }
			                    if(data[2] == 'no')
			                    {
			                    	$("#noPublic").addClass("active");
			                    	$("#yesPublic").removeClass("active");
			                    	$("#option2").attr("checked","");
			                    	$("#option1").removeAttr("checked");
			                    	$(".inforForShare").hide();
			                    }
			                    else if(data[2] == 'yes')
			                    {
			                    	$("#yesPublic").addClass("active");
			                    	$("#noPublic").removeClass("active");
			                    	$("#option1").attr("checked","");
			                    	$("#option2").removeAttr("checked");
			                    	$(".inforForShare").show();
			                    	$("textarea[name=recommend]").html(data[3]);
			                    	// inforForShare
			                    	if(data[4] != "")
			                    	{
			                    		$("#oldImageTitle").show();
			                    		$("#oldImageContent").show();
			                    		$("#oldImageContent").append('<a data-fancybox="gallery" href="'+data[4]+'"><img class="img-fluid rounded" src="'+data[4]+'" alt=""></a>');
			                    	}
			                    }
			                 }
			            });
			        });
					//vẽ đường
					<?php 
							$pieces_2 = explode("|", $to_des);
							$array = array();
							for ($i=0; $i < count($pieces_2)-1; $i++) {
									$array = Arr::add($array, $i ,$pieces_2[$i]);
							}
					?>
					$('#get-route-pannel').show();
					$('.chip').css('display','inline-block');
					@foreach($array as $value)
						locationID.push('{{$value}}');
					@endforeach
					$("#time input").val();
					document.querySelector('#startDate')._flatpickr.set("minDate","");
					document.querySelector('#startDate')._flatpickr.setDate([new Date('{{date("m/d/Y h:i", strtotime($to_starttime))}}')]);

					@if($to_comback == '1')
						$('#is-back').prop('checked',true);
					@endif
					@if($to_currency == "1")
						$(".currency").val("VNĐ")
					@elseif($to_currency == "2")
						$(".currency").val("USD")
					@endif
					// @if($to_optimized == '1')
					// 	//$('.dur-dis[value="1"]').prop('checked', true);
					// @elseif($to_optimized == '2')
					// 	//$('.dur-dis[value="2"]').prop('checked', true);
					// @else
					// 	$('#is-opt').prop('checked',false);
					// @endif
					isopt = 0;
					<?php $dem = 0 ?>
					@if(count($latlng_new) > 0)
							@foreach($latlng_new as $value)
									locationdata.set("{{$placeId_new[$dem]}}",{
											de_cost: {{$cost_new[$dem]}},
											de_description: "{{$description_new[$dem]}}",
											de_duration: {{$duration_new[$dem]}},
											de_link: "",
											de_name: "{{$dename_new[$dem]}}",
											location: <?php echo json_encode($latlng_new[$dem]); ?>,
											de_default: 1,
									})
									// newPlaceIdArr.push("{{$placeId_new[$dem]}}")
									<?php $dem++; ?>
							@endforeach
					@endif
					// $array  $duration_new
					// gán lại duration vs cost cho locationdata
					setTimeout(function(){ 
						@for($i = 0 ; $i < count($array) ; $i++)
							Object(locationdata.get("{{$array[$i]}}")).de_duration = parseInt("{{$duration_new[$i]}}");
							Object(locationdata.get("{{$array[$i]}}")).de_cost = parseInt("{{$cost_new[$i]}}");
						@endfor
					}, 500);
			
					@if( $latlng_start != "")
						locationdata.set("{{$placeId_start}}",{
								de_cost: {{$cost_start}},
								de_description: "{{$description_start}}",
								de_duration: {{$duration_start}},
								de_link: "",
								de_name: "{{$dename_start}}",
								location: <?php echo json_encode($latlng_start); ?>,
								de_default: 1,
						})
						locationID.unshift();
						// newPlaceIdArr.push("{{$placeId_start}}")
					@endif
					// startlocat
					@if( $latlng_start != "")
						startLocat.id = "{{$placeId_start}}";
						startLocat.marker = new google.maps.Marker({
									label: idToData(startLocat.id,'name'),
									map:map,
									position: idToData(startLocat.id,'LatLng')
						});
						customLabel(startLocat.marker,startLocat.id);
						$('#start-locat').html(`<span>Click trên bản đồ hoặc chọn trong ô tìm kiếm</span><div id="close-start" style="display: inline-flex; position: absolute; right: 0.5em;"><i class="fas fa-times " ></i></div>`);
						$('#start-locat').attr('data-start',2);
						$('#start-locat').attr('data-clsclk',0);
						closeStart();
						// $('#your-start').show();
						// document.getElementById('your-start').innerHTML= idToData(startlocat,'text')+'<span id="your-start-close">×</span>';
						// $('#your-start-close').click(()=>{
						// 		//staMarker.setMap(null);
						// 		staMarker = undefined;
						// 		$('.map-marker-label').remove();
						// 		$('#your-start').hide();
						// 		startlocat = undefined;
						// 		updateRoute();
						// })
						
					@endif
					// drawRoutes
					@if($to_des != "")
						
						setTimeout(function(){ 
						    idToData(null,'LatLngArr');
							drawRoutes();
							$(".chip").attr('style','display: inline-block');
							$("#get-route").hide();
						}, 1000);
						// setTimeout(function(){ 
						//   $("#get-route").click();
						// }, 200);
						// let height = ($('.list-item').length+1) * 45 +5;
						// $('#container-height').css('height',height+'px');
					@endif
				@endif
	})();
}

	</script>
@stop


