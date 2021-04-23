@extends('sharetour/layout/index')
@section('title')
    Share Tour
@parent   
@stop
@section('header_styles')  
    <style>
        p{margin: 0}
    </style>
@stop
@section('content')
    <?php use App\Models\Destination; use App\Models\Language;use App\Models\Uservotes;use Illuminate\Support\Facades\Auth;?>
    <section class="page-section portfolio" id="portfolio">
        <div class="container">
            <!-- Portfolio Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Tour details</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- Portfolio Grid Items-->
            <div class="row">
                <?php use App\Models\Route; use App\Models\ShareTour;?>
                <?php 
                    $route = Route::where("to_id",$share->sh_to_id)->first(); 
                    $pieces = explode("|", $route->to_des);
                    $array = array();
                    for ($i=0; $i < count($pieces)-1; $i++) {
                        $array = Arr::add($array, $i ,$pieces[$i]);
                    }
                    //des - place
                    $pieces = explode("|", $route->to_des);
                    $array = array();
                    for ($i=0; $i < count($pieces)-1; $i++) {
                        $array = Arr::add($array, $i ,$pieces[$i]);
                    }
                    //->save ID place to array()
                ?>
                <!-- Portfolio Item 1-->
                <div class="col-md-7 col-lg-7 mb-5 slider autoplay" role="toolbar">
                    @if($share->image != "")
                        <div class="div_parents">
                            <p>---{{$route->to_name}}---</p>
                            <a data-fancybox='gallery' href='{{asset($share->image)}}'>
                                <img class="img-fluid" src='{{asset($share->image)}}' alt='' style="width: 100%">
                            </a>
                        </div>
                    @else
                        <div class="div_parents">
                            <p>---{{$route->to_name}}---</p>
                            <a data-fancybox='gallery' href="{{asset('imgPlace/empty.png')}}">
                                <img class="img-fluid" src="{{asset('imgPlace/empty.png')}}" alt='' style="width: 100%">
                            </a>
                        </div>
                    @endif
                </div>
                
                <!-- Portfolio Item 2-->
                <div class="col-md-5 col-lg-5 mb-5">
                    <h3 class="font-weight-bold font-italic">{{$route->to_name}}</h3>
                    <hr>
                    <div id="div_btn">
                        <button class="btn btn-warning" data-toggle="modal"
                        @if(Auth::check())
                            data-target="#exampleModal"
                        @else
                            data-target="#modalLogin"
                        @endif
                        >Rating</button>
                        <a href="{{route('share.viewSharetour',[$route->to_id,$share->sh_id])}}" class="btn btn-info">View tour</a>
                    </div>
                    @if(Auth::check())
                        <?php $findVotes =  Uservotes::where("sh_id",$share->sh_id)->where("us_id",Auth::user()->us_id)->first(); ?>
                        @if(!empty($findVotes))
                            <p><span class="font-weight-bold font-italic">Your votes: </span>
                            <span>{{$findVotes->vote_number}} <i class="fas fa-star text-warning"></i></span></p>
                        @else
                            <p><span class="font-weight-bold font-italic">Your votes: </span>
                            <span class="badge badge-success">You do not have reviews for this tour</span></p>
                        @endif
                    @endif
                    <p><span class="font-weight-bold font-italic">Introduce: </span>
                    <span>{{$share->content}}</span></p>
                    <p><span class="font-weight-bold font-italic">Average rating: </span>
                    <span>{{$share->number_star}} <i class="fas fa-star text-warning"></i></span></p>
                    <p><span class="font-weight-bold font-italic">Number of ratings: </span>
                    <span>{{$share->numberReviews}} votes</span></p>
                    @if($route->to_startLocat != "")
                        <?php $des_startLocat = Destination::where("de_remove",$route->to_startLocat)->first(); ?>
                        <p><span class="font-weight-bold font-italic">Start Location: </span>
                        <span id="startLocation" data-id="{{$des_startLocat->de_remove}}"><i class="fas fa-street-view" style="color:#e74949;"></i> {{$des_startLocat->de_name}}</span></p>
                    @else
                        <p><span class="font-weight-bold font-italic">Start Location: <span class="badge badge-warning">Not available</span></p>
                    @endif
                    <p class="font-weight-bold font-italic mb-0">Location:</p>
                    <p id="detail_location"></p>
                    <p><span class="font-weight-bold font-italic">Start time: </span>
                    <span>{{date('d/m/Y h:i a', strtotime($route->to_starttime))}}</span></p>
                    <p><span class="font-weight-bold font-italic">Endtime time: </span>
                    <span>{{date('d/m/Y h:i a', strtotime($route->to_endtime))}}</span></p>
                    <?php 
                        $total = Carbon\Carbon::parse($route->to_endtime)->diffInMinutes(Carbon\Carbon::parse($route->to_starttime));
                     ?>
                    <p><span class="font-weight-bold font-italic">Total tour time: </span>
                    <span class="total_time"></span></p>
                    <!-- js take total -->
                    <script type="text/javascript">
                        var duration = moment.duration({{$total}}, 'minutes');
                        var durationString = duration.days() + 'd ' + duration.hours() + 'h ' + duration.minutes() + 'm';
                        console.log(durationString);
                        $(".total_time").html(durationString);
                    </script>
                    <!-- /endis -->

                    <p><span class="font-weight-bold font-italic">Date created: </span>
                    <span>{{date('d/m/Y', strtotime($route->to_startDay))}}</span></p>
                </div>
            </div>
        </div>
    </section>
    <!-- Model -->
    <div class="modal fade" id="modalDetailPlace" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Place</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="opSelection">
                <div class="showImage">Show image</div>
                <div class="showMap">Show Map</div>
            </div>
            <div class="imgPlace mt-4 mb-4">
            </div>
            <div id="map" class="mt-4 mb-4"></div>
            <div class="container-fuild">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-12 mb-4">
                        <p class="font-weight-bold font-italic">Type of Place</p>
                    </div>
                    <div class="col-md-8 col-sm-6 col-12 mb-4">
                        <p id="typePlace"></p>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12 mb-4">
                        <p class="font-weight-bold font-italic">Short description</p>
                    </div>
                    <div class="col-md-8 col-sm-6 col-12 mb-4">
                        <p id="short"></p>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12 mb-4">
                        <p class="font-weight-bold font-italic">Description</p>
                    </div>
                    <div class="col-md-8 col-sm-6 col-12 mb-4">
                        <p id="description"></p>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12 mb-4">
                        <p class="font-weight-bold font-italic">Average travel time</p>
                    </div>
                    <div class="col-md-8 col-sm-6 col-12 mb-4">
                        <p id="timeAvg"></p>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12 mb-4">
                        <p class="font-weight-bold font-italic">Link on google map</p>
                    </div>
                    <div class="col-md-8 col-sm-6 col-12 mb-4" id="linkMap">
                    </div>
                    <div class="col-md-4 col-sm-6 col-12 mb-4">
                        <p class="font-weight-bold font-italic">Link on VR</p>
                    </div>
                    <div class="col-md-8 col-sm-6 col-12 mb-4" id="linkvr">
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /Model -->
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Rating</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container-fuild">
                <div class="row">
                  <div class="col-md-12 col-sm-12 col-12">
                    <p class="font-weight-bold font-italic">Rating for your tour</p>
                  </div>
                  <div class="col-md-12 col-sm-12 col-12 mb-3" id="div_Starrank_tour">
                    <i class="fas fa-star star_1 fa-2x"  data-value="1" style="cursor: pointer;"></i>
                    <i class="fas fa-star star_2 fa-2x" data-value="2" style="cursor: pointer;"></i>
                    <i class="fas fa-star star_3 fa-2x" data-value="3" style="cursor: pointer;"></i>
                    <i class="fas fa-star star_4 fa-2x"  data-value="4" style="cursor: pointer;"></i>
                    <i class="fas fa-star star_5 fa-2x" data-value="5" style="cursor: pointer;"></i>
                  </div>
                  <input type="hidden" id="star_Share" name="numberStar">
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="btn_Rating">Rating</button>
          </div>
        </div>
      </div>
    </div>
@stop
@section('footer-js')  
    <script type="text/javascript">
        $(document).ready(function(){
            // votess star
            @for($i = 1; $i<= 5; $i++)
              $("#div_Starrank_tour .star_{{$i}}").click(function(){
                  @for($j = 1 ; $j <= 5; $j++)
                      $("#div_Starrank_tour .star_{{$j}}").css("color","#212529");
                  @endfor
                  @for($j = 1 ; $j <= $i; $j++)
                      $("#div_Starrank_tour .star_{{$j}}").css("color","#ff9700");
                  @endfor
                  //console.log($(this).attr("data-value"));
                  $("#star_Share").val($(this).attr("data-value"));
              });
            @endfor
            @foreach ($array as $value)
                <?php $des = Destination::where("de_id",$value)->first();
                    if(Session::has('website_language') && Session::get('website_language') == "vi")
                    {
                        $lang = Language::where("des_id",$value)->where("language","vn")->first();
                    }
                    else
                    {
                        $lang = Language::where("des_id",$value)->where("language","en")->first();
                    }
                 ?>
                @if($des->de_default == "0")
                    $(".autoplay").append("<div class='div_parents'><p>---{{$lang->de_name}}---</p><a data-fancybox='gallery' href='{{asset($des->de_image)}}'><img class='img-fluid' src='{{asset($des->de_image)}}' alt='' style='width: 100%''></a></div>");
                @endif
            @endforeach
            //find location
            <?php 
                $detailLocation = "";
                $dem = 0;
                foreach ($array as  $ar) {
                $checkDes = Destination::where("de_remove",$ar)->first();
                    if($checkDes->de_default == "0")
                    {
                        if(Session::has('website_language') && Session::get('website_language') == "vi")
                        {
                            $desName = Language::select('de_name')->where("language","vn")->where("des_id",$ar)->first();
                            $detailLocation=$detailLocation.'<i class="fas fa-street-view" style="color:#e74949;"></i><span class="openModal'.$dem.'" data-id="'.$ar.'">'.$desName->de_name.'</span><br>';
                        }
                        else
                        {
                            $desName = Language::select('de_name')->where("language","en")->where("des_id",$ar)->first();
                            $detailLocation=$detailLocation.'<i class="fas fa-street-view" style="color:#e74949;"></i><span class="openModal'.$dem.'" data-id="'.$ar.'">'.$desName->de_name.'</span><br>';
                        }
                    }
                    else if($checkDes->de_default == "1")
                    {
                        $detailLocation= $detailLocation.'<i class="fas fa-street-view" style="color:#e74949;"></i><span class="openModal'.$dem.'" data-id="'.$checkDes->de_remove.'">'.$checkDes->de_name.'</span><br>';
                    }
                    $dem++;
                }
            ?>                
            $("#detail_location").append('{!!$detailLocation!!}');
            $(".showMap").click(function(){
                $("#map").show();
                $(".imgPlace").hide();
            });
            $(".showImage").click(function(){
                $("#map").hide();
                $(".imgPlace").show();
            });
            
            $('.autoplay').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                prevArrow: false,
                nextArrow: false,
                pauseOnHover: false,
                pauseOnFocus: false,
                fade: true,
                dots: false,
                adaptiveHeight:false
            });
            $(".hightly_div_child").hover(function(){
                $(this).css("box-shadow","0px 1px 20px 11px white");
            }); 
            $(".hightly_div_child").mouseleave(function(){
                $(this).css("box-shadow","none");
            }); 
            $("#comback_admin").click(function(){
                location.replace("{{route('admin.generalInfor')}}");
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#exampleModal').on('show.bs.modal', function (event) {
              // load start
              let _token = $('meta[name="csrf-token"]').attr('content');
              let $url_path = '{!! url('/') !!}';
              let routeCheckTour = $url_path+"/voteUser";
              $.ajax({
                    url:routeCheckTour,
                    method:"POST",
                    data:{_token:_token,shareId:{{$share->sh_id}}},
                    success:function(result){ 
                      console.log(result);
                      //reset
                      $("#star_Share").val('0');
                      $(".star_1").css("color","#212529");
                      $(".star_2").css("color","#212529");
                      $(".star_3").css("color","#212529");
                      $(".star_4").css("color","#212529");
                      $(".star_5").css("color","#212529");
                      if(result[0] == "yes")
                      {
                        $("#star_Share").val(result[1]);
                        if(result[1] == "1")
                        {
                          $(".star_1").css("color","#ff9700");
                        }
                        else if(result[1] == "2")
                        {
                          $(".star_1").css("color","#ff9700");
                          $(".star_2").css("color","#ff9700");
                        }
                        else if(result[1] == "3")
                        {
                          $(".star_1").css("color","#ff9700");
                          $(".star_2").css("color","#ff9700");
                          $(".star_3").css("color","#ff9700");
                        }
                        else if(result[1] == "4")
                        {
                          $(".star_1").css("color","#ff9700");
                          $(".star_2").css("color","#ff9700");
                          $(".star_3").css("color","#ff9700");
                          $(".star_4").css("color","#ff9700");
                        }
                        else if(result[1] == "5")
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
            $("#input_File").change(function(){
                $(".btn_upload").css("background","#ff8304");
                $("#file_name").css("display","block");
                $("#file_name").html($("#input_File").val().split('\\').pop());
            });
            
            $("#btn_Rating").click(function(){
                let _token = $('meta[name="csrf-token"]').attr('content');
                let $url_path = '{!! url('/') !!}';
                let routeRating=$url_path+"/rating";
                let numberStar = $("#star_Share").val();
                $.ajax({
                      url:routeRating,
                      method:"POST",
                      data:{_token:_token,numberStar:numberStar,shareId:{{$share->sh_id}}},
                      success:function(data){ 
                        alert("You have successfully evaluated");
                        location.reload();
                     }
                });
            });
        });
    </script>
    <script type="text/javascript">    
        var markers=[];
        function initMap(){
            //let lll = { lat: 21.0374, lng: 105.774 }
            var map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12.5,
                center: { lat: 21.0226586, lng: 105.8179091 },
            }),
            directionsService = new google.maps.DirectionsService();
            const geocoder = new google.maps.Geocoder();

            $(document).ready(function(){
                <?php $dem2 = 0; ?>
                @foreach ($array as  $ar)
                    $(".openModal{{$dem2}}").click(function(){
                        $("#modalDetailPlace").modal("show");
                        let $url_path = '{!! url('/') !!}';
                        let _token = $('meta[name="csrf-token"]').attr('content');
                        let routeGetCooor = $url_path+"/takeInforPlace";
                        let des_id = $(this).attr("data-id");
                        $.ajax({
                              url:routeGetCooor,
                              method:"post",
                              data:{_token:_token,des_id:des_id},
                              success:function(data){ 
                                $("#exampleModalLabel").html(data[2]);
                                $(".imgPlace").empty();
                                if(data[3] != "")
                                {
                                    $(".imgPlace").append("<a data-fancybox='gallery' href='"+data[3]+"'> <img class='img-fluid' src='"+data[3]+"' alt='' style='width: 70%'></a>");
                                }
                                else
                                {
                                    $(".imgPlace").append("<a data-fancybox='gallery' href='{{asset('imgPlace/empty.png')}}'> <img class='img-fluid' src='{{asset('imgPlace/empty.png')}}' alt='' style='width: 70%' title='location with no photo'></a>");
                                }
                                $("#typePlace").empty();
                                $("#typePlace").append(data[9]);
                                $("#short").empty();
                                $("#description").empty();
                                if(data[4] != null)
                                    $("#short").append(data[4]);
                                else
                                    $("#short").append('<span class="badge badge-warning">Not available</span>');
                                if(data[5] != null)
                                    $("#description").append(data[5]);
                                else
                                    $("#description").append('<span class="badge badge-warning">Not available</span>');  

                                $("#timeAvg").html(parseFloat(data[6])/60/60+" hours");
                                $("#linkMap").empty();
                                if(data[7] != null)
                                    $("#linkMap").append('<a href="'+data[7]+'" target="_blank">Link here</a>');
                                else
                                    $("#linkMap").append('<span class="badge badge-warning">Not available</span>');
                                $("#linkvr").empty();
                                if(data[8] != null)
                                    $("#linkvr").append('<a href="'+data[8]+'" target="_blank">Link here</a>');
                                else
                                    $("#linkvr").append('<span class="badge badge-warning">Not available</span>');
                                //vẽ map
                                deleteMarker();
                                let add = data[0]+","+data[1];
                                geocodeAddress(geocoder,map,data[2],add);
                            }
                        });
                    });
                    <?php $dem2++; ?>
                @endforeach
                $("#startLocation").click(function(){
                    $("#modalDetailPlace").modal("show");
                    let $url_path = '{!! url('/') !!}';
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    let routeGetCooor = $url_path+"/takeInforPlace";
                    let des_id = $(this).attr("data-id");
                    $.ajax({
                          url:routeGetCooor,
                          method:"post",
                          data:{_token:_token,des_id:des_id},
                          success:function(data){ 
                            $("#exampleModalLabel").html(data[2]);
                            $(".imgPlace").empty();
                            if(data[3] != "")
                            {
                                $(".imgPlace").append("<a data-fancybox='gallery' href='"+data[3]+"'> <img class='img-fluid' src='"+data[3]+"' alt='' style='width: 70%'></a>");
                            }
                            else
                            {
                                $(".imgPlace").append("<a data-fancybox='gallery' href='{{asset('imgPlace/empty.png')}}'> <img class='img-fluid' src='{{asset('imgPlace/empty.png')}}' alt='' style='width: 70%' title='location with no photo'></a>");
                            }
                            $("#typePlace").empty();
                            $("#typePlace").append(data[9]);
                            $("#short").empty();
                            $("#description").empty();
                            if(data[4] != null)
                                $("#short").append(data[4]);
                            else
                                $("#short").append('<span class="badge badge-warning">Not available</span>');

                            if(data[5] != null)
                                $("#description").append(data[5]);
                            else
                                $("#description").append('<span class="badge badge-warning">Not available</span>');  

                            $("#timeAvg").html(parseFloat(data[6])/60/60+" hours");
                            $("#linkMap").empty();
                            if(data[7] != null)
                                $("#linkMap").append('<a href="'+data[7]+'" target="_blank">Link here</a>');
                            else
                                $("#linkMap").append('<span class="badge badge-warning">Not available</span>');
                            $("#linkvr").empty();
                            if(data[8] != null)
                                $("#linkvr").append('<a href="'+data[8]+'" target="_blank">Link here</a>');
                            else
                                $("#linkvr").append('<span class="badge badge-warning">Not available</span>');
                            //vẽ map
                            deleteMarker();
                            let add = data[0]+","+data[1];
                            geocodeAddress(geocoder,map,data[2],add);
                        }
                    });
                });
            });
            function deleteMarker()
            {
                $('.map-marker-label').remove();
                for (let i = 0; i < markers.length; i++) {
                  markers[i].setMap(null);
                }
                markers=[];
            }
            function geocodeAddress(geocoder, resultsMap, label,add) {
                const address = add;
                geocoder.geocode({ address: address }, (results, status) => {
                  if (status === "OK") {
                    resultsMap.setCenter(results[0].geometry.location);
                    var staMarker = new google.maps.Marker({
                      map: resultsMap,
                      position: results[0].geometry.location,
                      icon: {
                        url: "{{asset('images/red-dot.png')}}",
                        labelOrigin: new google.maps.Point(65, 32),
                        size: new google.maps.Size(40,40),
                        anchor: new google.maps.Point(16,32),
                      },
                      label: {
                        text: label,
                        color: "#C70E20",
                        fontWeight: "bold"
                      },
                    });
                    //đặt lại marker cào mảng để xóa
                    markers.push(staMarker);
                  } else {
                    alert("Geocode was not successful for the following reason: " + status);
                  }
                });
            };
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgbjwIY5Q1eZ-Ejqur0a8avEQWowfA39s&callback=initMap" async defer></script>
@stop
