@extends('user/layout/index')
@section('title')
    {{ trans('messages.listPlace') }}
@parent
@stop
@section('header_styles')
    <link rel="stylesheet" href="{{asset('css/sharetourDetail.css')}}">
    <link rel="stylesheet" href="{{asset('css/showplace.css')}}">
@stop
@section('content')
    <?php use App\Models\User; use Illuminate\Support\Facades\DB;use Illuminate\Support\Arr;?>
	<section class="page-section" id="contact">
        <div class="container-fuild">
            <!-- Contact Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">
            {{$lang->de_name}}</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
        </div>
            <!-- Contact Section Form-->
        <div class="container">
            <div class="row" style="background: white;">
                <div class="col-lg-7 mx-auto left_image">
                    <div class="imgPlace slider-for">
                        <a data-fancybox="gallery" href="{{asset($lang->de_image)}}">
                            <img class="img-fluid" src="{{asset($lang->de_image)}}" alt="">
                        </a>
                        @foreach($array as $arr)
                            <a data-fancybox="gallery" href="{{asset('child_img_place/'.$arr)}}">
                                <img class="img-fluid" src="{{asset('child_img_place/'.$arr)}}" alt="">
                            </a>
                        @endforeach
                    </div>
                    @if(count($array) != 0)
                        <div class="totalPlace">
                            <p>Tất cả ảnh ({{count($array)+1}})</p>
                        </div>
                    @endif
                    @if(count($array) != 0)
                        <div class="slider-nav mt-2">
                            <div class="block_img">
                                <img class="img-fluid" src="{{asset($lang->de_image)}}" alt="">
                            </div>
                            @foreach($array as $arr)
                                <div class="block_img">
                                    <img class="img-fluid" src="{{asset('child_img_place/'.$arr)}}" alt="">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="col-lg-5 mx-auto p-0 pt-2">
                    <div class="div_drop">
                        @foreach($arrTag as $arr)
                            <a class="ui tag label">{{$arr}}</a>
                        @endforeach
                    </div>
                    <p><span class="font-weight-bold">{{ trans('messages.TypePlace') }}: </span><span class="font-italic">{{$lang->nametype}}</span></p>
                    <p><span class="font-weight-bold">{{ trans('messages.Shortdescription') }}: </span><span class="font-italic">{{$lang->de_shortdes}}</span></p>
                    <p><span class="font-weight-bold">{{ trans('messages.Description') }}: </span><span class="font-italic">{{$lang->de_description}}</span></p>
                    <p><span class="font-weight-bold">{{ trans('messages.Averagetraveltime') }}: </span><span class="font-italic">{{intval($lang->de_duration)/60/60}} hours</span></p>
                    <p><span class="font-weight-bold">{{ trans('messages.Linkongooglemap') }}: </span><a target="_blank" href="{{$lang->de_map}}" class="font-italic">Link here</a></p>
                    <p><span class="font-weight-bold">{{ trans('messages.LinkVR') }}: </span>
                        @if($lang->de_link != "")
                            <a target="_blank" href="{{$lang->de_link}}" class="font-italic">Link here</a>
                        @else
                            <span class="badge badge-warning">{{ trans('messages.Notavailable') }}</span>
                        @endif
                    </p>
                </div>
            </div>

        </div>
        <div class="comtainer block_map">
            <div id="map" class="col-md-12"></div>
            <div class="container rating_comment_title">
                <h3 class="font-weight-bold font-italic">Đánh giá và phản hồi</h3>
            </div>
            <div class="container rating_comment">
                <div class="rating_comment-left">
                    <div class="line_1">
                        <span class="font-weight-bold">{{number_format((float)$svgVotes, 1, '.', '')}}</span>
                        <span class="show_star"></span>
                        <span> - {{count($findRating)}} ratings</span>
                    </div>
                    <div class="line_1">
                        <ul class="line_1_detail">
                            <li>
                                <span class="font-weight-bold">5</span><i class="fas fa-star text-warning"></i>: 
                                <span class="detail_votes">{{$fiveVote}} votes</span>
                            </li>
                            <li>
                                <span class="font-weight-bold">4</span><i class="fas fa-star text-warning"></i>: 
                                <span class="detail_votes">{{$fourVote}} votes</span>
                            </li>
                            <li>
                                <span class="font-weight-bold">3</span><i class="fas fa-star text-warning"></i>: 
                                <span class="detail_votes">{{$threeVote}} votes</span>
                            </li>
                            <li>
                                <span class="font-weight-bold">2</span><i class="fas fa-star text-warning"></i>: 
                                <span class="detail_votes">{{$twoVote}} votes</span>
                            </li>
                            <li>
                                <span class="font-weight-bold">1</span><i class="fas fa-star text-warning"></i>: 
                                <span class="detail_votes">{{$oneVote}} votes</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="rating_comment-right">
                    @if(Auth::check())
                    <?php 
                        $countShareLo = DB::table('tour')->where('to_id_user',Auth::user()->us_id)
                                ->rightJoin('sharetour', 'sharetour.sh_to_id', '=', 'tour.to_id')
                                ->count();
                     ?>
                    <div class="enter_login_rating">
                        <div class="info_userlogin_commnet">
                            <div class="info_uslogin_left">
                                <div class="block_avatar">
                                    @if(Auth::user()->us_image != "")
                                        <img src="{{asset(Auth::user()->us_image)}}" alt="">
                                    @else
                                        <img src="{{asset('assets/img/avataaars.svg')}}" alt="">
                                    @endif
                                </div>
                            
                                <div class="block_info_user">
                                    <span>{{Auth::user()->us_fullName}}</span>
                                    <small>Đã chia sẻ {{$countShareLo}} toues</small>
                                </div>
                            </div>
                            @if(!empty($findRatingUsLogin))
                            <div class="info_uslogin_right">
                                <button class="btn btn-sm btn-info" id="btn_fix_rating">Sửa đánh giá</button>
                            </div>  
                            @endif            
                        </div>
                        @if(!empty($findRatingUsLogin))
                            <div id="info_rating_place">
                                <div class="chose_star" id="div_Starrank">
                                   <i class="fas fa-star star_1" data-value="1"></i>
                                   <i class="fas fa-star star_2" data-value="2"></i>
                                   <i class="fas fa-star star_3" data-value="3"></i>
                                   <i class="fas fa-star star_4" data-value="4"></i>
                                   <i class="fas fa-star star_5" data-value="5"></i>
                               </div>
                               <p class="font-weight-bold">{{$findRatingUsLogin->ra_content}}</p>
                               <div class="block_image_UsLg"></div>
                               <small class="font-italic">Đã viết vào: <span>{{date("h:i:s d/m/Y", strtotime($findRatingUsLogin->ra_date_created))}}</span></small>
                            </div>
                            <form id="form_fix_comment" onsubmit="return validateForm()" method="post" action="{{route('user.updateRating',$findRatingUsLogin->ra_id)}}">
                               @csrf
                               <div class="chose_star" id="div_Starrank_place">
                                   <i class="fas fa-star star_1" data-value="1"></i>
                                   <i class="fas fa-star star_2" data-value="2"></i>
                                   <i class="fas fa-star star_3" data-value="3"></i>
                                   <i class="fas fa-star star_4" data-value="4"></i>
                                   <i class="fas fa-star star_5" data-value="5"></i>
                               </div>
                               <input type="hidden" name="numberStar" id="numberStar" required="">
                               <div class="block_enter_content">
                                    <textarea class="form-control" placeholder="Nhập đánh giá của bạn (nếu có)" name="content_rating">{{$findRatingUsLogin->ra_content}}</textarea> 
                                    <i class="fas fa-camera icon_camera"></i>
                                    <div class="temporary_photo">
                                        <!-- append here -->
                                    </div>
                                </div>
                               <input type="submit" class="btn btn-sm btn-primary" value="Đánh giá" id="btn_rating">
                            </form>
                            <form id="temporaryForm" enctype="multipart/form-data" method="post">
                                <input type="file" name="input_file_img[]" class="input_file_img" multiple accept="image/*">
                            </form>
                        @else
                            <form id="form_add_comment" onsubmit="return validateForm()" method="post" action="{{route('user.addRating',$idplace)}}">
                               @csrf
                               <div class="chose_star" id="div_Starrank_place">
                                   <i class="fas fa-star star_1" data-value="1"></i>
                                   <i class="fas fa-star star_2" data-value="2"></i>
                                   <i class="fas fa-star star_3" data-value="3"></i>
                                   <i class="fas fa-star star_4" data-value="4"></i>
                                   <i class="fas fa-star star_5" data-value="5"></i>
                               </div>
                               <input type="hidden" name="numberStar" id="numberStar" required="">
                               <div class="block_enter_content">
                                    <textarea class="form-control" placeholder="Nhập đánh giá của bạn (nếu có)" name="content_rating"></textarea> 
                                    <i class="fas fa-camera icon_camera"></i>
                                    <div class="temporary_photo">
                                        <!-- append here -->
                                    </div>
                                </div>
                               <input type="submit" class="btn btn-sm btn-primary" value="Đánh giá" id="btn_rating">
                            </form>
                            <form id="temporaryForm" enctype="multipart/form-data" method="post">
                                <input type="file" name="input_file_img[]" class="input_file_img" multiple accept="image/*">
                            </form>
                        @endif 
                    </div>
                    @endif
                    <div class="block_contet">
                        <!-- detail comment -->
                        @foreach($findRating as $rating)
                        <?php 
                            $findUser = User::select('us_code','us_id','us_image','us_fullName')->where("us_id",$rating->ra_us_id)->first(); 
                            $countShareUser = DB::table('tour')->where('to_id_user',$findUser->us_id)
                                ->rightJoin('sharetour', 'sharetour.sh_to_id', '=', 'tour.to_id')
                                ->count();
                        ?>
                        <div class="comment_content">
                            <div class="comment_content-title">
                                <div class="comment_content-title-left">
                                    <div class="block_avatar">
                                        @if($findUser->us_image != "")
                                            <img src="{{asset($findUser->us_image)}}" alt="">
                                        @else
                                            <img src="{{asset('assets/img/avataaars.svg')}}" alt="">
                                        @endif
                                    </div>
                                    <div class="block_info_user">
                                        <span>{{$findUser->us_fullName}}</span>
                                        <small>Đã chia sẻ {{$countShareUser}} toues</small>
                                    </div>
                                </div>
                            </div>
                            <div class="comment_content-starvotes">
                                @if($rating->ra_votes == "1")
                                    <i class="fas fa-star text-warning"></i>
                                @elseif($rating->ra_votes == "2")
                                    @for($i = 0;$i < 2;$i++ )
                                        <i class="fas fa-star text-warning"></i>
                                    @endfor
                                @elseif($rating->ra_votes == "3")
                                    @for($i = 0;$i < 3;$i++ )
                                        <i class="fas fa-star text-warning"></i>
                                    @endfor
                                @elseif($rating->ra_votes == "4")
                                    @for($i = 0;$i < 4;$i++ )
                                        <i class="fas fa-star text-warning"></i>
                                    @endfor
                                @elseif($rating->ra_votes == "5")
                                    @for($i = 0;$i < 5;$i++ )
                                        <i class="fas fa-star text-warning"></i>
                                    @endfor
                                @endif
                            </div>
                            <div class="comment_content-content">
                                <span>
                                    {!! $rating->ra_content !!}
                                </span>
                            </div>
                            @if($rating->ra_images != null)
                                <?php 
                                    $pieces_2 = explode("|", $rating->ra_images);
                                    $arrayCommentImg = array();
                                    for ($i=0; $i < count($pieces_2)-1; $i++) {
                                        $arrayCommentImg = Arr::add($arrayCommentImg, $i ,$pieces_2[$i]);
                                    }
                                 ?>
                                <div class="comment_content-image">
                                    @foreach($arrayCommentImg as $arImg)
                                        <a data-fancybox='gallery' href='{{asset("uploadUsers/".$findUser->us_code."/".$arImg)}}'> <img class='img-fluid' src='{{asset("uploadUsers/".$findUser->us_code."/".$arImg)}}' alt=''></a>
                                    @endforeach
                                </div>
                            @endif
                            <div class="comment_content-date">
                                Đã viết vào: <span>{{date("h:i:s d/m/Y", strtotime($rating->ra_date_created))}}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('footer-js')
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
        function validateForm(){
            if($("#numberStar").val() == "")
            {
                alert("Bạn hãy nhập số sao đánh giá !!");
                return false;
            }
        }
        $(".show_star").append(converStar("{{number_format((float)$svgVotes, 1, '.', '')}}"));
        $(document).ready(function(){
            @if(Auth::check())
                @if(!empty($findRatingUsLogin))
                    // fixx image
                    @if($findRatingUsLogin->ra_images != null)
                        <?php 
                            $pieces_RatingUsLogin = explode("|", $findRatingUsLogin->ra_images);
                            $array_RatingUsLogin = array();
                            for ($i=0; $i < count($pieces_RatingUsLogin)-1; $i++) {
                                $array_RatingUsLogin = Arr::add($array_RatingUsLogin, $i ,$pieces_RatingUsLogin[$i]);
                            }
                         ?>
                        $(".temporary_photo").empty();
                        @foreach($array_RatingUsLogin as $arrUsLogin)
                            $(".temporary_photo").append('<div class="temporary_detail_photo"><img src="{{asset("/uploadUsers/".Auth::user()->us_code."/".$arrUsLogin)}}" alt=""/><div class="temporary_detail_icon" data-name="{{$arrUsLogin}}"><i class="fas fa-times-circle"></i></div></div>');
                            $(".block_image_UsLg").append('<div class="block_image_UsLg_photo"><a data-fancybox="gallery" href="{{asset("/uploadUsers/".Auth::user()->us_code."/".$arrUsLogin)}}"><img class="img-fluid" src="{{asset("/uploadUsers/".Auth::user()->us_code."/".$arrUsLogin)}}" alt=""></a></div>');
                            
                        @endforeach
                    @endif
                @endif
                $(".icon_camera").click(function(){$(".input_file_img").click();})
                $(".input_file_img").change(function(){
                    if ($(".input_file_img")[0].files.length > 5) {
                        alert("Bạn chỉ được upload tối đa 5 file");
                    } else {
                        let formData = new FormData($("#temporaryForm")[0]);
                        let $url_path = '{!! url('/') !!}';
                        let _token = $('meta[name="csrf-token"]').attr('content');
                        let routeTemporaryImg = $url_path+"/temporaryImg";
                        $.ajax({
                              url:routeTemporaryImg,
                              method:"post",
                              headers: { "X-CSRF-Token": _token },
                              data:formData,
                              processData: false,
                              contentType: false,
                              enctype: 'multipart/form-data',
                              success:function(data){ 
                                if(data.length != 0)
                                {
                                    $(".temporary_photo").empty();
                                    data.forEach(function(item, index){
                                        $(".temporary_photo").append(`<div class="temporary_detail_photo"><img src='${$url_path}/temporary_Img/${item}' alt=''/><div class="temporary_detail_icon" data-name="${item}"><i class="fas fa-times-circle"></i></div></div>`);
                                    })
                                }
                            }
                        });
                    }
                });
                $(".temporary_photo").on('click','.temporary_detail_icon',function(){
                    $(this).parent().remove();
                    let $url_path = '{!! url('/') !!}';
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    let nameImg = $(this).data('name');
                    let routeDeleteImg = $url_path+"/temporaryDeleteImg";
                    $.ajax({
                          url:routeDeleteImg,
                          method:"post",
                          headers: { "X-CSRF-Token": _token },
                          data:{nameImg:nameImg},
                          success:function(data){ 
                            //
                        }
                    });
                });
            @endif
            $("#btn_fix_rating").click(function(){
                $("#info_rating_place").slideUp("fast");
                $("#form_fix_comment").slideDown("fast");
                $(this).hide();
                @if(!empty($findRatingUsLogin))
                    let $url_path = '{!! url('/') !!}';
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    let routeCopyImg = $url_path+"/temporaryCopyImg";
                    $.ajax({
                          url:routeCopyImg,
                          method:"post",
                          data:{_token:_token,ra_id:{{$findRatingUsLogin->ra_id}}},
                          success:function(data){ 
                            // console.log(data)
                        }
                    });
                @endif
            });
            @for($i = 1; $i<= 5; $i++)
              $("#div_Starrank_place .star_{{$i}}").click(function(){
                  @for($j = 1 ; $j <= 5; $j++)
                      $("#div_Starrank_place .star_{{$j}}").css("color","#212529");
                  @endfor
                  @for($j = 1 ; $j <= $i; $j++)
                      $("#div_Starrank_place .star_{{$j}}").css("color","#ff9700");
                  @endfor
                  $("#numberStar").val($(this).attr("data-value"));
            });
            @endfor

            @if(!empty($findRatingUsLogin))
                @if($findRatingUsLogin->ra_votes == "1")
                    $(".star_1").css("color","#ff9700");
                @elseif($findRatingUsLogin->ra_votes == "2")
                    @for($i=1;$i<=2;$i++)
                        $(".star_{{$i}}").css("color","#ff9700");
                    @endfor
                @elseif($findRatingUsLogin->ra_votes == "3")
                    @for($i=1;$i<=3;$i++)
                        $(".star_{{$i}}").css("color","#ff9700");
                    @endfor
                @elseif($findRatingUsLogin->ra_votes == "4")
                    @for($i=1;$i<=4;$i++)
                        $(".star_{{$i}}").css("color","#ff9700");
                    @endfor
                @elseif($findRatingUsLogin->ra_votes == "5")
                    @for($i=1;$i<=5;$i++)
                        $(".star_{{$i}}").css("color","#ff9700");
                    @endfor
                @endif
                $("#numberStar").val("{{$findRatingUsLogin->ra_votes}}");
            @endif
            @if(count($array) != 0)
                $('.slider-for').slick({
                  slidesToShow: 1,
                  slidesToScroll: 1,
                  arrows: false,
                  fade: true,
                  prevArrow: false,
                  nextArrow: false,
                  dots: false,
                  asNavFor: '.slider-nav'
                });
                $('.slider-nav').slick({
                  slidesToShow: 3,
                  slidesToScroll: 1,
                  asNavFor: '.slider-for',
                  dots: true,
                  centerMode: true,
                  prevArrow: false,
                  nextArrow: false,
                  dots: false,
                  focusOnSelect: true
                });
            @endif
        });
    </script>
    <script type="text/javascript">    
        var markers=[];
        function initMap(){
            //let lll = { lat: 21.0374, lng: 105.774 }
            var map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12.5,
                center: { lat: 21.0226586, lng: 105.8179091 },
                // gestureHandling: 'greedy',
            }),
            directionsService = new google.maps.DirectionsService();
            const geocoder = new google.maps.Geocoder();

            deleteMarker();
            let add = "{{$lang->de_lat}},{{$lang->de_lng}}";
            geocodeAddress(geocoder,map,"{{$lang->de_name}}",add);

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
                        labelOrigin: new google.maps.Point(70, 45),
                        size: new google.maps.Size(40,40),
                        anchor: new google.maps.Point(16,32),
                      },
                      label: {
                        text: label,
                        color: "#C70E20",
                        fontWeight: "bold",
                        fontFamily: 'cursive',
                        fontSize: '25px'
                      },
                    });
                    //đặt lại marker cào mảng để xóa
                    markers.push(staMarker);
                  } else {
                    alert("{{ trans('newlang.Geocode') }}: " + status);
                  }
                });
            };
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgbjwIY5Q1eZ-Ejqur0a8avEQWowfA39s&callback=initMap" async defer></script>
@stop