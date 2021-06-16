@extends('user/layout/index_dashboard')
@section('title')
    {{ trans('messages.Home') }}
@parent
@stop
@section('header_styles')
    <link rel="stylesheet" href="{{asset('css/dashboard_2.css')}}">
    <style>
        .color-white{
            color: white !important;
        }
    </style>
@stop
@section('content')
    <?php 
        use App\Models\Route; 
        use Illuminate\Support\Facades\Auth;
        use App\Models\Destination;
        use App\Models\Language;
        use App\Models\ShareTour;
        use Illuminate\Support\Arr;
    ?> 
    <script type="text/javascript">
        function converStar(num){
            num = parseFloat(num);
            var roundnum = Math.round((num+0.5)*2)/2 -0.5;
            var arr =[];
            for(var i =5; i>0; i--){
                for(var j = 1; j>=0;j = j-0.5){     
                    if((roundnum - j)>=0 ){
                        roundnum -= j;
                        arr.push(j)
                        break
                    }
                }
            }
            let starStsing = '';
            arr.forEach(function(item, index){
                if(item == 1)
                    starStsing += '<i class="fas fa-star text-warning"></i>';
                else if(item == 0)
                    starStsing += '<i class="far fa-star text-warning"></i>';
                else if(item == 0.5)
                    starStsing += '<i class="fas fa-star-half-alt text-warning"></i>';
            })
            return starStsing;
        }
    </script>
	<section class="page-section portfolio" id="portfolio" style="margin-bottom: 10rem;">
        <div class="container title-main-page">
            <!-- Portfolio Grid Items-->
            <div class="row" id="introduce-page">
                <!-- Portfolio Item 0-->  
                <div class="col-md-12 col-lg-12 mb-5" id="search_title">
                    <div class="div-search">
                        <div id="text_title">
                            <h4 class="title-page text-uppercase mb-2">
                                {{ trans('messages.homeTitle') }}
                            </h4>
                            <p>{{ trans('messages.smallTitle') }}</p>
                        </div>
                        <div id="div_search">
                            <div id="content_search">
                                <input type="text" class="navbar-brand js-scroll-trigger form-control" placeholder="{{ trans('messages.searchPlace') }}" id="searchPlace">
                                <div class="result_search">
                                    <ul>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Portfolio Item 1-->
                @if(Auth::check())
                @if(count($arrayTourSeen) > 0)
                <div class="col-md-12 col-lg-12 mb-5 slide-show" id="slideshow_seen">
                    <span class="title_start_tour text-uppercase mt-5">Tours you have seen</span>
                    <div class="slide-show-tour_seen">
                        @foreach($arrayTourSeen as $arr )
                        <?php   $findShare = ShareTour::where("sh_id",$arr)->first();
                                $findRoute = Route::select('to_name','user_like')->where("to_id",$findShare->sh_to_id)->first();
                                $pieces = explode("|", $findRoute->user_like);
                                $array = array();
                                for ($i=0; $i < count($pieces)-1; $i++) {
                                    $array = Arr::add($array, $i ,$pieces[$i]);
                                }
                         ?>
                        <a href="{{route('viewtour',$arr)}}" class="hightly_div_child us_seen">
                            <div class="like_tour" data-id="{{$arr}}">
                                <i class="fas fa-heart"></i>
                            </div>
                            @foreach($array as $ar)
                                @if(Auth::user()->us_id == $ar)
                                    <style>
                                        .us_seen .like_tour[data-id="{{$arr}}"]{background: rgba(255,255,255,0.9);}
                                        .us_seen .like_tour[data-id="{{$arr}}"] svg{color: #ff0a0a;}
                                    </style>
                                @endif
                            @endforeach
                            <p class="tourContent">
                                <span class="nameTour">{{$findRoute->to_name}}</span>
                                <span id="show_starSeen_{{$arr}}"></span>
                                <span>- {{$findShare->numberReviews}} votes</span>
                            </p>
                            <script type="text/javascript">
                                $("#show_starSeen_{{$arr}}").append(converStar("{{number_format((float)$findShare->number_star, 1, '.', '')}}"));
                            </script>
                            @if($findShare->image != "")
                                <img src="{{asset($findShare->image)}}" alt="" class="img_open_model{{$findShare->sh_id}}">
                            @else
                                <img src="{{asset('imgPlace/empty.png')}}" alt="" title="location with no photo" class="img_open_model{{$findShare->sh_id}}"/>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
                @endif
                <!-- Portfolio Item 2-->
                <div class="col-md-12 col-lg-12 mb-5" id="starttour">
                    <span class="title_start_tour text-uppercase">{{ trans('messages.createTourTitle') }}</span>
                    <div class="start_tour">
                        <div class="start_tour_left">
                            <p>{{ trans('messages.createNewTour') }}</p>
                            <div class="icon">
                                <div class="parent-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Places</span>
                                </div>
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
                        <div class="start_tour_right">
                            <img src="{{asset('images/tourha.jpg')}}" alt="">
                            <a href="{{route('user.maps')}}" id="StarttourNow">{{ trans('messages.Startour') }}</a>
                        </div>
                    </div>
                </div>
                <!-- Portfolio Item 3-->
                <div class="col-md-12 col-lg-12 mb-5 previous-tour" id="previoustour">
                    <span class="title_start_tour text-uppercase mt-5">{{ trans('messages.PREVIOUSTOURS') }}</span>
                    <div class="start_tour">
                        <div class="start_tour_left bg-green">
                            <p>{{ trans('messages.seeYourPrevious') }}</p>
                            <div class="icon align-items">
                                <div class="parent-icon mr-0 mt-4">
                                    <i class="fas fa-car-side"></i>
                                </div>
                                <div class="arrow"></div>
                                <div class="parent-icon mr-0">
                                    <i class="fas fa-car-side"></i>
                                </div>
                                <div class="arrow_2"></div>
                                <div class="parent-icon mr-0 mt-3">
                                    <i class="fas fa-car-side"></i>
                                </div>
                            </div>
                        </div>
                        <div class="start_tour_right">
                            <img src="{{asset('images/history.jpg')}}" alt="">
                            <div id="StarttourNow" data-toggle="modal"
                            @if(Auth::check())
                                data-target="#portfolioModal2"
                            @else
                                data-target="#modalLogin"
                            @endif
                            >{{ trans('messages.PREVIOUSTOURS') }}</div>
                        </div>
                    </div>
                </div>
                @if(Auth::check())
                <!-- Portfolio Item 4-->
                <div class="col-md-12 col-lg-12 mb-5 tour_you_like" id="tourYouLike">
                    <span class="title_start_tour text-uppercase mt-5">Tour you like</span>
                    <div class="start_tour">
                        <div class="start_tour_left bg-orange">
                            <p>Tours you have enjoyed</p>
                            <div class="icon align-items">
                                <div class="parent-icon mr-0 mt-4">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="arrow"></div>
                                <div class="parent-icon mr-0">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="arrow_2"></div>
                                <div class="parent-icon mr-0 mt-3">
                                    <i class="fas fa-heart"></i>
                                </div>
                            </div>
                        </div>
                        <div class="start_tour_right">
                            <img src="{{asset('images/heartplace.jpg')}}" alt="">
                            <a href="{{route('user.tourUserLike')}}" id="StarttourNow">Tour you like</a>
                        </div>
                    </div>
                </div>
                @endif
                <!-- Portfolio Item 5-->
                <div class="col-md-12 col-lg-12 mb-5 search-tour" id="searchtour">
                    <span class="title_start_tour text-uppercase mt-5">{{ trans('messages.SEARCHTOURS') }}</span>
                    <div class="start_tour">
                        <div class="start_tour_left bg-blue">
                            <p>{{ trans('messages.titleSearchTour') }}</p>
                            <div class="icon align-items">
                                <div class="parent-icon mr-0 mt-4">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="arrow"></div>
                                <div class="parent-icon mr-0">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="arrow_2"></div>
                                <div class="parent-icon mr-0 mt-3">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                            </div>
                        </div>
                        <div class="start_tour_right">
                            <img src="{{asset('images/search.jpg')}}" alt="">
                            <a href="{{route('searchTour')}}" id="StarttourNow">{{ trans('messages.SEARCHTOURS') }}</a>
                        </div>
                    </div>
                </div>  
                <!-- Portfolio Item 6-->
                <div class="col-md-12 col-lg-12 mb-5 slide-show" id="slideshow">
                    <span class="title_start_tour text-uppercase mt-5">{{ trans('messages.HIGHLIGHTS_TOUR') }}</span>
                    <div class="slide-show-tour">
                        @foreach($shareTour as $value)
                        <?php   $findShare_hight = ShareTour::where("sh_id",$value->sh_id)->first();
                                $findRoute_hight = Route::select('to_name','user_like')->where("to_id",$findShare_hight->sh_to_id)->first();
                                $pieces_hight = explode("|", $findRoute_hight->user_like);
                                $array_hight = array();
                                for ($i=0; $i < count($pieces_hight)-1; $i++) {
                                    $array_hight = Arr::add($array_hight, $i ,$pieces_hight[$i]);
                                }
                        ?>
                        <?php $route = Route::where("to_id",$value->sh_to_id)->first(); ?>
                        <a href="{{route('viewtour',$value->sh_id)}}" class="hightly_div_child tour_highlight">
                            @if(Auth::check())
                                <div class="like_tour" data-id="{{$value->sh_id}}">
                                    <i class="fas fa-heart"></i>
                                </div>
                                @foreach($array_hight as $ar)
                                    @if(Auth::user()->us_id == $ar)
                                        <style>
                                            .tour_highlight .like_tour[data-id="{{$value->sh_id}}"]{background: rgba(255,255,255,0.9);}
                                            .tour_highlight .like_tour[data-id="{{$value->sh_id}}"] svg{color: #ff0a0a;}
                                        </style>
                                    @endif
                                @endforeach
                            @endif
                            <p class="tourContent">
                                <span class="nameTour">{{$route->to_name}}</span>
                                <span id="show_star_{{$value->sh_id}}"></span>
                                <span>- {{$value->numberReviews}} votes</span>
                            </p>
                            <script type="text/javascript">
                                $("#show_star_{{$value->sh_id}}").append(converStar("{{number_format((float)$value->number_star, 1, '.', '')}}"));
                            </script>
                            @if($value->image != "")
                                <img src="{{asset($value->image)}}" alt="" class="img_open_model{{$value->sh_id}}">
                            @else
                                <img src="{{asset('imgPlace/empty.png')}}" alt="" title="location with no photo" class="img_open_model{{$value->sh_id}}"/>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if(Auth::check())
    <!-- Portfolio Modal 2-->
    <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-labelledby="portfolioModal2Label" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
                <div class="modal-body text-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- Portfolio Modal - Title-->
                                <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0" id="portfolioModal2Label">{{ trans('messages.Previoustours') }}</h2>
                                <!-- Icon Divider-->
                                <div class="text-center mt-3 mb-3">
                                    <span>{{ trans('messages.historyTitle') }}</span>
                                    <br>
                                    <small>{{ trans('messages.routeDetails') }}</small>
                                    <!-- <small>-- Double click to edit the route </small> -->
                                </div>
                                <div id="centent-previous">
                                    <div style="width: 60%">
                                        <!-- Portfolio Modal - Image-->
                                        <img class="img-fluid rounded mb-5" src="{{asset('assets/img/portfolio/history.jpg')}}" alt="" id="turnOffMap" />
                                        <div class="img-fluid rounded mb-5" id="map">
                                            
                                        </div>
                                        <!-- Portfolio Modal - Text-->
                                    </div>
                                    <?php   
                                            $route = Session::get('route');
                                            $date_route = Route::select('to_startDay')->where('to_id_user',Auth::user()->us_id)->orderBy('to_startDay', 'desc')->groupBy('to_startDay')->get();
                                            $des_1 = array();
                                    ?>              
                                    <div style="width: 40%">
                                        <div class="openScroll">
                                            @if ( count($route) > 0 )
                                            @foreach($date_route as $date)
                                                <p class="dateCreated_group">
                                                    <!-- <span class="font-weight-bold">Ngày tạo: </span> -->
                                                    <span>{{date('d/m/Y', strtotime($date->to_startDay))}}</span>
                                                </p>
                                                @foreach($route as $value)
                                                @if($value->to_startDay == $date->to_startDay)
                                                    <?php 
                                                        if($value->to_startLocat != "")
                                                        {
                                                           $findName = Destination::select("de_name")->where("de_remove",$value->to_startLocat)->first();
                                                            array_push($des_1, $findName->de_name);
                                                        }
                                                        $pieces = explode("|", $value->to_des);
                                                        for ($i=0; $i < count($pieces)-1; $i++) { 
                                                            $checkPlace = Destination::where("de_remove",$pieces[$i])->first();
                                                            if($checkPlace->de_default == "0")
                                                            {
                                                                if(Session::has('website_language') && Session::get('website_language') == "vi")
                                                                {
                                                                    $lang = Language::where("language","vn")->where("des_id",$pieces[$i])->first();
                                                                }
                                                                else
                                                                {
                                                                    $lang = Language::where("language","en")->where("des_id",$pieces[$i])->first();
                                                                }
                                                                array_push($des_1,$lang->de_name);
                                                            }
                                                            else
                                                            {
                                                                array_push($des_1,$checkPlace->de_name);
                                                            }
                                                        }
                                                     ?>  
                                                    <div>
                                                        <?php $findShare = ShareTour::where("sh_to_id",$value->to_id)->first(); ?>
                                                        <p class="text-left big-size tour" data-id="{{$value->to_id}}" title="{{$value->to_name}}">
                                                            <span>
                                                            @if(empty($findShare))
                                                                {{$value->to_star}} <i class="fas fa-star text-warning"></i> <span class="font-italic">-- {{ trans('messages.NoShare') }}</span>
                                                            @else
                                                                {{$findShare->number_star}} <i class="fas fa-star text-warning"></i> <span class="font-italic">-- {{ trans('messages.Shared') }}</span>
                                                            @endif
                                                            </span>
                                                            {{$value->to_name}}
                                                            <a href="{{route('user.editTour',$value->to_id)}}" class="btn btn-link btn-sm" id="detailTour">{{ trans('messages.Detail') }}</a>
                                                        </p>
                                                        <div class="detail-tour">
                                                            @foreach($des_1 as $des)
                                                                <p>
                                                                    <i class="fas fa-street-view point text-danger"></i>
                                                                    <span>{{$des}}</span>
                                                                </p>
                                                            @endforeach
                                                            <!-- delete array -->
                                                            <?php $des_1=array(); ?>
                                                            <p><span class="font-italic">{{ trans('messages.Startday') }}</span>: {{date('d/m/Y', strtotime($value->to_startDay))}}</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                @endforeach
                                            @endforeach
                                            @else
                                            <p class="lead text-center">
                                                {{ trans('messages.notTrips') }}
                                            </p>
                                            @endif 
                                        </div>
                                        <a href="{{route('user.tourhistory')}}" class="btn btn-primary">--- {{ trans('messages.seeMore') }}<i class="fas fa-angle-double-right pt-1"></i> ---</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@stop
@section('footer-js')
    <script type="text/javascript" src="{{asset('js/dashboard_2.js')}}"></script>
	<!-- map -->
    <script type="text/javascript">
        $(document).ready(function(){
            $(".like_tour").click(function(e){
                e.preventDefault();
                let $url_path = '{!! url('/') !!}';
                let _token = $('meta[name="csrf-token"]').attr('content');
                let routeChangeLike = $url_path+"/changeLikeTour";
                let shareId = $(this).data('id');
                $.ajax({
                      url:routeChangeLike,
                      method:"post",
                      data:{_token:_token,shareId:shareId},
                      success:function(data){ 
                        console.log(data[0])
                        if(data[0] == 1)
                        {
                            $(`.like_tour[data-id="${shareId}"]`).find('svg').css("color","#ff0a0a");
                            $(`.like_tour[data-id="${shareId}"]`).css("background-color","rgba(255,255,255,0.9)");
                        }
                        if(data[0] == 2)
                        {
                            $(`.like_tour[data-id="${shareId}"]`).find('svg').css("color","white");
                            $(`.like_tour[data-id="${shareId}"]`).css("background-color","rgba(0,0,0,0.6)");
                        }
                    }
                });
            })
            $('.slide-show-tour').slick({
                slidesToShow: 3,
                slidesToScroll: 2,
                autoplay: true,
                autoplaySpeed: 2500,
                dots: true,
                dotClass: 'slick-dots',
                fade: false,
                pauseOnHover: false,
                prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-caret-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fas fa-caret-right"></i></button>',
            });
            $('.slide-show-tour_seen').slick({
                slidesToShow: 3,
                slidesToScroll: 2,
                autoplay: true,
                fade: false,
                pauseOnHover: false,
                prevArrow: '<button type="button" class="slick-prev slide-seem"><i class="fas fa-caret-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next slide-seem"><i class="fas fa-caret-right"></i></button>',
            });
            
        });
        window.addEventListener("scroll", function() {
            // var elementTarget = document.getElementById("search_title");
            // if (window.scrollY > (elementTarget.offsetTop + 20)) {
            //     $("#slideshow_seen").css("transform",'translateX(0)');
            // }
            // var slideshow_seen = document.getElementById("slideshow_seen");
            // if (window.scrollY > (starttour.offsetTop + 20)) {
            //     $("#starttour").css("transform",'translateX(0)');
            // }
            // var starttour = document.getElementById("starttour");
            // if (window.scrollY > (starttour.offsetTop + 20)) {
            //     $(".previous-tour").css("transform",'translateX(0)');
            // }
            // var previoustour = document.getElementById("previoustour");
            // if (window.scrollY > (previoustour.offsetTop + 20)) {
            //     $(".tour_you_like").css("transform",'translateX(0)');
            // }
            // var tourYouLike = document.getElementById("tourYouLike");
            // if (window.scrollY > (tourYouLike.offsetTop + 20)) {
            //     $(".search-tour").css("transform",'translateX(0)');
            // }
            // var searchtour = document.getElementById("searchtour");
            // if (window.scrollY > (searchtour.offsetTop + 20)) {
            //     $(".slide-show").css("transform",'translateX(0)');
            // }
        });
    	$(document).ready(function(){
			$(".tour").dblclick(function(){
                let $url_path = '{!! url('/') !!}';
                let routeEditTour = $url_path+"/editTourUser/"+$(this).attr("data-id");
                window.open(routeEditTour, '_blank');
            });
            $(".detail-tour").slideUp();
		});
        var locationsdata = [];
        var locatsList =[];
        var locats = [];
        var allRoutePosible = [];
        var dello = 0;
        var markersArray=[];

        var labelName = [];
        var polylines = [];
        var starlocat;
        var tour_id;
        const colorlist = ['#418bca','#00bc8c','#f89a14','#ef6f6c','#5bc0de','#811411'];
        function initMap(){
            var map = new google.maps.Map(document.getElementById("map"), {
                  zoom: 12.5,
                  center: { lat: 21.0226586, lng: 105.8179091 },
                }),
            directionsService = new google.maps.DirectionsService();
            $(".tour").click(function(){
                //set up css btn
                $(".detail-tour").slideUp();
                $(".tour").css("background","white");
                $(".tour").css("color","black");
                $(".tour").css("font-weight","600");
                $(this).css("background","whitesmoke");
                $(this).css("color","#303030");
                $(this).css("font-weight","bold");
                tour_id = $(this).attr("data-id");
                if ($(this).parent().find(".detail-tour").is(':visible'))
                {
                    $(this).parent().find(".detail-tour").slideUp("fast");
                }
                else
                {
                    $(this).parent().find(".detail-tour").slideDown("fast");
                    // var height_obj = $(this).offset().top;
                    // var height_pr_obj = $('.openScroll').offset().top;
                    // console.log(height_obj,height_active,height_pr_obj);
                    // $('.openScroll').animate({
                    //     scrollTop: height_obj + height_active - height_pr_obj 
                    // }, 500);
                    drawMap(tour_id);
                } 
                function drawMap(tour_id)
                {
                    $("#turnOffMap").css("display","none");
                    $("#map").css("display","block");
                    let $url_path = '{!! url('/') !!}';
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    let routeCheckTour=$url_path+"/checkTour";
                    let inputLink = tour_id;
                    $.ajax({
                          url:routeCheckTour,
                          method:"POST",
                          data:{_token:_token,inputLink:inputLink},
                          success:function(data){ 
                            if(data[2] != "")
                            {
                                data[2].lat = parseFloat(data[2].lat);
                                data[2].lng = parseFloat(data[2].lng);
                                data[0].unshift(data[2]);
                                data[1].unshift(data[3]);

                            }
                            drawRoutes(data[0],data[1]);
                         }
                    });
                }
            });
            function drawRoutes(locats,labelName){
                markersOnMap(locats,labelName);
                var waypts = [];
                for(var i=1; i<locats.length; i++)
                  waypts.push({
                    location: locats[i],
                    stopover: true
                  });
                directionsService.route({
                    origin: locats[0],
                    destination: locats[locats.length-1],
                    waypoints: waypts,
                    travelMode: google.maps.TravelMode.DRIVING,
                },customDirectionsRenderer);
            }

            function customDirectionsRenderer(response, status) {
                var bounds = new google.maps.LatLngBounds();
                var legs = response.routes[0].legs;

                for(var i=0;i<polylines.length;i++){
                  polylines[i].setMap(null);
                }
                for (i = 0; i < legs.length; i++) {
                  (i>=5&&i%5 == 0)?index = 4:((starlocat != undefined)?index = (i%5)-1:index = (i%5));
                  if(starlocat != undefined && i==0) index = 5;
                   var polyline = new google.maps.Polyline({
                    map:map, 
                    path:[], 
                    strokeColor: colorlist[index],
                    strokeOpacity: 0.7,
                    strokeWeight: 5});
                  var steps = legs[i].steps;
                  for (j = 0; j < steps.length; j++) {
                    var nextSegment = steps[j].path;
                    for (k = 0; k < nextSegment.length; k++) {
                      polyline.getPath().push(nextSegment[k]);
                      bounds.extend(nextSegment[k]);
                    }
                  }
                  polylines.push(polyline);
                }
                map.fitBounds(bounds);
                //getandsettimeline(response.routes[0].legs);
            };

              //Draw marker on map
            function markersOnMap(locats,labelName){
                //clear marker 
                
                for(var i =0 ; i<markersArray.length;i++){
                  markersArray[i].setMap(null);
                }
                markersArray = []; 

                //create new marker
                for(i=0; i<locats.length;i++){
                    // console.log(locats[i]);
                  addMarkers(locats[i],i,labelName[i]);
                }
            }
            function addMarkers(locats,index,labelName){
                // console.log(index);
                var icon = {
                  path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
                  fillColor: colorlist[index%5],
                  fillOpacity: 1,
                  strokeColor: 'white',
                  strokeWeight: 3,
                  scale: 1.4,
                },
                  label = {
                    text: labelName,
                    color: colorlist[index%5],
                    fontWeight: 'bold'  
                };
                var marker = new google.maps.Marker({
                      map: map,
                      position: locats,
                      label:label, 
                      icon: icon
                });

                var content = '<p><h4>AAAAAAAAAAA</h4></p>'+ 
                      '<p><a href="'+'"target="_blank">Click to view tour</a></p>',
                      infowindow = new google.maps.InfoWindow({
                        content: content,
                      });

                marker.addListener('click',()=>{
                    marker.setIcon("{{asset('imgs/icon.jpg')}}");
                    marker.setLabel('');
                    infowindow.open(map, marker);
                });

                infowindow.addListener('closeclick',()=>{
                    marker.setIcon(icon);
                    marker.setMap(map);
                    marker.setLabel(label);
                });

                markersArray.push(marker);
            }
        };
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgbjwIY5Q1eZ-Ejqur0a8avEQWowfA39s&callback=initMap" async defer></script>
@stop