@extends('user/layout/index')
@section('title')
    Highly rater tour
@parent
@stop
@section('header_styles')
    <link rel="stylesheet" href="{{asset('css/listplace.css')}}">
@stop
@section('content')
	<section class="page-section" id="contact">
        <div class="container">
            <!-- Contact Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">
            {{$langType}}
            </h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- Contact Section Form-->
            <div class="row">
                <div class="col-lg-3 mx-auto">
                    <div class="list">
                        <div class="list_title">
                            {{$langType}}
                        </div>
                        <div class="list_content">
                            <ul>
                                @foreach($listPlace as $list)
                                    <li class="showItem" data-id="{{$list->de_remove}}">{{$list->de_name}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div> 
                <div class="col-lg-9 mx-auto">
                    <div class="div_detail">
                        <div class="imgItem item">
                            <p class="font-weight-bold">Image: </p>
                            <a data-fancybox="gallery" id="place_a" href="#">
                                <img class="img-fluid rounded mb-5" id="place_img" src="" alt="">
                            </a>
                        </div>
                        <div class="contentItem item">
                            <p class="text-justify"><span class="font-weight-bold">
                                {{$langType}}
                                name: 
                            </span><span class="font-italic" id="placeName"></span></p>
                            <p class="text-justify"><span class="font-weight-bold">Short description: </span>
                                <span class="font-italic" id="placeShort"></span>
                            </p>
                            <p class="text-justify"><span class="font-weight-bold">Description: </span>
                                <span class="font-italic" id="placeDescrip"></span>
                            </p>
                            <p class="text-justify"><span class="font-weight-bold">Average travel time: </span>
                                <span class="font-italic" id="placeTime"></span>
                            </p>
                            <p class="text-justify"><span class="font-weight-bold">Link on google map: </span>
                                <a href="#" class="font-italic" id="placeMap" target="_blank">Link here</a>
                            </p>
                            <p class="text-justify"><span class="font-weight-bold">Link on vr: </span>
                                <a href="#" class="font-italic" id="placeVr" target="_blank">Link here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('footer-js')
    <script type="text/javascript">
        $(document).ready(function(){
            var idPlace = "{{$listPlace[0]->de_remove}}";
            callajax(idPlace);
            $(".showItem").click(function(){
                $(".list_content ul li").css("background","#f2fcff");
                $(this).css("background","#cee0e5");
                idPlace = $(this).attr("data-id");
                callajax(idPlace);
            });

            function callajax(idPlace){
                $(".div_detail").slideUp("fast");
                let $url_path = '{!! url('/') !!}';
                let _token = $('meta[name="csrf-token"]').attr('content');
                let routeLoadPlaceInfo = $url_path+"/loadPlaceInfo";
                $.ajax({
                      url:routeLoadPlaceInfo,
                      method:"POST",
                      data:{_token:_token,idPlace:idPlace},
                      success:function(data){ 
                        $(".div_detail").slideDown("fast");
                        if(data['de_image'] == "")
                        {
                            $("#place_a").attr("href",$url_path+ "/assets/img/portfolio/cabin.png");
                            $("#place_img").attr("src",$url_path+ "/assets/img/portfolio/cabin.png");
                        }
                        else
                        {
                            $("#place_a").attr("href",data['de_image']);
                            $("#place_img").attr("src",data['de_image']);
                        }
                        $("#placeName").html(data['de_name']);
                        $("#placeShort").html(data['de_shortdes']);
                        $("#placeDescrip").html(data['de_description']);
                        $("#placeTime").html(data['de_duration']+" hours");
                        $("#placeMap").attr("href",data['de_map']);
                        $("#placeVr").attr("href",data['de_link']);
                     }
                });
            }
        });
    </script>
@stop