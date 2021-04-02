@extends('user/layout/index')
@section('title')
    Start tour
@parent
@stop
@section('header_styles')
	<style>
		#header_starttour,#header_starttour:hover{
			color: #fff !important;
    		background: #1abc9c !important;
		}
	</style>
@stop
@section('content')
	<?php use App\Models\Destination; use App\Models\Language;?>   
	<section class="page-section portfolio" id="portfolio">
        <div class="container">
            <!-- Portfolio Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">{{ trans('messages.StartTitle') }}</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- Portfolio Grid Items-->
            <div class="row justify-content-center">
                <!-- Portfolio Item 1-->
                <div class="col-md-6 col-lg-6 mb-5">
                    <div class="portfolio-item mx-auto" id="StarttourNow">
                        <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                            <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                        </div>
                        <img class="img-fluid" src="{{asset('assets/img/portfolio/cabin.png')}}" alt="" />
                    </div>
                </div>
                <!-- Portfolio Item 2-->
                <div class="col-md-6 col-lg-6 mb-5">
                    <div class="portfolio-item mx-auto" data-toggle="modal" 
                    @if(Auth::check())
                    	data-target="#portfolioModal2"
                    @else
                    	data-target="#modalLogin"
                    @endif
                    >
                        <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                            <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                        </div>
                        <img class="img-fluid" src="{{asset('assets/img/portfolio/history.jpg')}}" alt="" />
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
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <!-- Portfolio Modal - Title-->
                                <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0" id="portfolioModal2Label">{{ trans('messages.Previoustours') }}</h2>
                                <!-- Icon Divider-->
                                <div class="divider-custom">
                                    <div class="divider-custom-line"></div>
                                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                    <div class="divider-custom-line"></div>
                                </div>
                                <!-- Portfolio Modal - Image-->
                                <img class="img-fluid rounded mb-5" src="{{asset('assets/img/portfolio/history.jpg')}}" alt="" id="turnOffMap" />
                                <div class="img-fluid rounded mb-5" id="map">
                                    
                                </div>
                                <!-- Portfolio Modal - Text-->
                                <p>
                                    {{ trans('messages.historyTitle') }}</p>
                                <small>{{ trans('messages.routeDetails') }}</small>
                                <small>-- (Double click to edit the route) </small>
                                <div class="openScroll">
                                    <p class="mb-5">
                                        <?php 
                                              $route = Session::get('route');
                                         ?>
                                        @if ( count($route) > 0 )
                                            @foreach($route as $value)
                                                <?php 
                                                    if($value->to_startLocat == "")
                                                    {
                                                       $des_1 = ""; 
                                                    }
                                                    else 
                                                    {
                                                        $findName = Destination::select("de_name")->where("de_remove",$value->to_startLocat)->first();
                                                        $des_1 = $findName->de_name."--";
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
                                                            $des_1 = $des_1.$lang->de_name.'--';
                                                        }
                                                        else
                                                        {
                                                            $des_1 = $des_1.$checkPlace->de_name.'--';
                                                        }
                                                    }
                                                 ?>
                                                 <!-- <i class="fas fa-street-view point"></i> -->
                                                <p style="font-family: auto" class="lead text-justify tour" data-id="{{$value->to_id}}">
                                                    <span style="font-style: italic;font-weight: bold;">{{$value->to_name}}: </span>{{$des_1}} - 
                                                    Start day: {{date('d/m/Y', strtotime($value->to_startDay))}}
                                                </p>
                                            @endforeach
                                        @else
                                        <p class="lead text-center">
                                            {{ trans('messages.notTrips') }}
                                        </p>
                                        @endif
                                    </p>
                                </div>
                                <a href="{{route('user.tourhistory')}}" class="btn btn-primary">--- See more <i class="fas fa-angle-double-right pt-1"></i> ---</a>
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
	<!-- map -->
    <script type="text/javascript">
    	$(document).ready(function(){
			$(".tour").dblclick(function(){
                let $url_path = '{!! url('/') !!}';
                let routeEditTour = $url_path+"/editTourUser/"+$(this).attr("data-id");
                window.open(routeEditTour, '_blank');
            });
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
        const colorlist = ['#418bca','#00bc8c','#f89a14','#ef6f6c','#5bc0de','#811411'];
        function initMap(){
            var map = new google.maps.Map(document.getElementById("map"), {
                  zoom: 12.5,
                  center: { lat: 21.0226586, lng: 105.8179091 },
                }),
            directionsService = new google.maps.DirectionsService();
            $(".tour").click(function(){
                $("#turnOffMap").css("display","none");
                $("#map").css("display","block");
                let $url_path = '{!! url('/') !!}';
                let _token = $('meta[name="csrf-token"]').attr('content');
                let routeCheckTour=$url_path+"/checkTour";
                let inputLink = $(this).attr("data-id");
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
            function markersOnMap(locats,labelName){console.log(locats);  
                //clear marker 
                
                for(var i =0 ; i<markersArray.length;i++){
                  markersArray[i].setMap(null);
                }
                markersArray = []; 

                //create new marker
                for(i=0; i<locats.length;i++){
                    console.log(locats[i]);
                  addMarkers(locats[i],i,labelName[i]);
                }
            }
            function addMarkers(locats,index,labelName){
                console.log(index);
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