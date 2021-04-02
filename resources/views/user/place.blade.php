@extends('user/layout/index')
@section('title')
    Highly rater tour
@parent
@stop
@section('header_styles')
	<style>
		#header_place,#header_place:hover{
			color: #fff !important;
    		background: #1abc9c !important;
		}
	</style>
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
            <p class="lead text-center">{{ trans('messages.clickPlace') }}</p>
            <!-- Contact Section Form-->
            <div class="row justify-content-center tourPlace" role="toolbar">
                <!-- Portfolio Item 1-->
                <?php $i=1; ?>
                @foreach($des as $value)
                <div class="col-md-6 col-lg-4 mb-5 downPlace">
                    <div class="portfolio-item mx-auto" data-toggle="modal" data-target="#placeModal{{$i}}">
                        <p class="lead">{{$value->de_name}}</p>
                        @if($value->de_image != "")
                        <img class="img-fluid" src="{{asset($value->de_image)}}" alt="" title="{{$value->de_name}}" />
                        @else
                        <img class="img-fluid" src="{{asset('imgPlace/empty.png')}}" alt="" title="location with no photo" />
                        @endif
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
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <!-- Portfolio Modal - Title-->
                                <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0" id="placeModal{{$i}}Label">{{$value->de_name}}</h2>
                                <!-- Icon Divider-->
                                <div class="divider-custom">
                                    <div class="divider-custom-line"></div>
                                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                    <div class="divider-custom-line"></div>
                                </div>
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
                                <!-- de_shortdes-->
                                @if($value->de_shortdes != "")
                                    <p class="mb-5 text-justify"><span class="font-weight-bold">{{ trans('messages.Shortdescription') }}:</span> {{$value->de_shortdes}}</p>
                                @else
                                    <p class="mb-5 text-justify"><span class="font-weight-bold">{{ trans('messages.Shortdescription') }}:</span> {{ trans('messages.NoInformation') }}</p>
                                @endif
                                <!-- de_description-->
                                @if($value->de_description != "")
                                    <p class="mb-5 text-justify"><span class="font-weight-bold">{{ trans('messages.Description') }}:</span> {{$value->de_description}}</p>
                                @else
                                    <p class="mb-5 text-justify"><span class="font-weight-bold">{{ trans('messages.Description') }}:</span> {{ trans('messages.NoInformation') }}</p>
                                @endif
                                <!-- de_duration -->
                                @if($value->de_duration != "")
                                    <p class="mb-5"><span class="font-weight-bold">{{ trans('messages.Averagetraveltime') }}:</span> {{floatval($value->de_duration)/60/60}} {{ trans('messages.hours') }}</p>
                                @else
                                    <p class="mb-5"><span class="font-weight-bold">{{ trans('messages.Averagetraveltime') }}:</span> {{ trans('messages.NoInformation') }}</p>
                                @endif
                                <!-- de_link -->
                                @if($value->de_map != "")
                                    <p class="mb-5"><span class="font-weight-bold">{{ trans('messages.Linkongooglemap') }}:</span> <a target="_blank" href="{{$value->de_map}}">{{ trans('messages.Linkhere') }}</a></p>
                                @else
                                    <p class="mb-5"><span class="font-weight-bold">{{ trans('messages.Linkongooglemap') }}:</span> {{ trans('messages.NoInformation') }}</p>
                                @endif
                                <!-- de_vr -->
                                @if($value->de_link != "")
                                    <p class="mb-5"><span class="font-weight-bold">{{ trans('messages.LinkVR') }} :</span> <a target="_blank" href="{{$value->de_link}}">{{ trans('messages.Linkhere') }}</a></p>
                                @else
                                    <p class="mb-5"><span class="font-weight-bold">{{ trans('messages.LinkVR') }} :</span> {{ trans('messages.NoInformation') }}</p>
                                @endif
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
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                prevArrow: false,
                nextArrow: false,
                dots: false,
                fade: false,
                pauseOnHover: false
            });
        });
    </script>
@stop