@extends('user/layout/index')
@section('title')
    {{ trans('messages.Places') }}
@parent
@stop
@section('header_styles')
	<link rel="stylesheet" href="{{asset('css/place.css')}}">
@stop
@section('content')
	<section class="page-section" id="place">
        <div class="container">
            <!-- Contact Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">{{ trans('messages.Places') }}</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- Contact Section Form-->
            <div class="row justify-content-center tourPlace" role="toolbar">
                <!-- Portfolio Item 1-->
                <?php $i=1; ?>
                @foreach($des as $value)
                <div class="downPlace">
                    <div class="portfolio-item mx-auto" data-toggle="modal" data-target="#placeModal{{$i}}"
                    style="  
                        @if($value->de_image != "")
                            background:url('{{asset($value->de_image)}}');
                            background-size:cover;
                            background-repeat: no-repeat;
                        @else
                            background:url('{{asset('imgPlace/empty.png')}}');
                            background-size:cover;
                            background-repeat: no-repeat;
                        @endif
                    ">
                        <p class="lead">{{$value->de_name}}</p>
                    </div>
                </div>
                <?php $i++; ?>
                @endforeach
            </div>
        </div>
    </section>
    <!-- modal place -->
    <!-- Place Modal 3-->
    <?php $i=1; ?>
    @foreach($des as $value)
    <div class="portfolio-modal modal fade modaldetail_Place" id="placeModal{{$i}}" tabindex="-1" role="dialog" aria-labelledby="portfolioModal3Label" aria-hidden="true">
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
                                <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0" id="placeModal{{$i}}Label">{{$value->de_name}}</h2>
                                <!-- Icon Divider-->
                                <div class="divider-custom">
                                    <div class="divider-custom-line"></div>
                                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                    <div class="divider-custom-line"></div>
                                </div>
                                <div class="detail-content">
                                    <div class="detail-content-left">
                                        <!-- Portfolio Modal - Image-->
                                        @if($value->de_image != "")
                                        <a data-fancybox="gallery" href="{{asset($value->de_image)}}">
                                            <img class="img-fluid rounded mb-5" src="{{asset($value->de_image)}}" alt="">
                                        </a>
                                        @else
                                        <a data-fancybox="gallery" href="{{asset('imgPlace/empty.png')}}">
                                            <img class="img-fluid rounded mb-5" src="{{asset('imgPlace/empty.png')}}" alt="" title="{{ trans('messages.locationwithnophoto') }}">
                                        </a>
                                        @endif
                                    </div>
                                    <div class="detail-content-right">
                                        <!-- de_shortdes-->
                                        @if($value->de_shortdes != "")
                                            <p class="mb-3 text-justify"><span class="font-weight-bold">{{ trans('messages.Shortdescription') }}:</span> {{$value->de_shortdes}}</p>
                                        @else
                                            <p class="mb-3 text-justify"><span class="font-weight-bold">{{ trans('messages.Shortdescription') }}:</span> {{ trans('messages.NoInformation') }}</p>
                                        @endif
                                        <!-- de_description-->
                                        @if($value->de_description != "")
                                            <p class="mb-3 text-justify"><span class="font-weight-bold">{{ trans('messages.Description') }}:</span> {{$value->de_description}}</p>
                                        @else
                                            <p class="mb-3 text-justify"><span class="font-weight-bold">{{ trans('messages.Description') }}:</span> {{ trans('messages.NoInformation') }}</p>
                                        @endif
                                        <!-- de_duration -->
                                        @if($value->de_duration != "")
                                            <p class="mb-3"><span class="font-weight-bold">{{ trans('messages.Averagetraveltime') }}:</span> {{floatval($value->de_duration)/60/60}} {{ trans('messages.hours') }}</p>
                                        @else
                                            <p class="mb-3"><span class="font-weight-bold">{{ trans('messages.Averagetraveltime') }}:</span> {{ trans('messages.NoInformation') }}</p>
                                        @endif
                                        <!-- de_link -->
                                        @if($value->de_map != "")
                                            <p class="mb-3"><span class="font-weight-bold">{{ trans('messages.Linkongooglemap') }}:</span> <a target="_blank" href="{{$value->de_map}}">{{ trans('messages.Linkhere') }}</a></p>
                                        @else
                                            <p class="mb-3"><span class="font-weight-bold">{{ trans('messages.Linkongooglemap') }}:</span> {{ trans('messages.NoInformation') }}</p>
                                        @endif
                                        <!-- de_vr -->
                                        @if($value->de_link != "")
                                            <p class="mb-3"><span class="font-weight-bold">{{ trans('messages.LinkVR') }} :</span> <a target="_blank" href="{{$value->de_link}}">{{ trans('messages.Linkhere') }}</a></p>
                                        @else
                                            <p class="mb-3"><span class="font-weight-bold">{{ trans('messages.LinkVR') }} :</span> {{ trans('messages.NoInformation') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $i++; ?>
    @endforeach
    <!-- /modal place -->
@stop
@section('footer-js')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.tourPlace').slick({
                slidesToShow: 3,
                slidesToScroll: 2,
                autoplay: true,
                autoplaySpeed: 3000,
                prevArrow: false,
                nextArrow: false,
                dots: false,
                fade: false,
                pauseOnHover: false
            });
        });
    </script>
@stop