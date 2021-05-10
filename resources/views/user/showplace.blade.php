@extends('user/layout/index')
@section('title')
    {{ trans('messages.listPlace') }}
@parent
@stop
@section('header_styles')
@stop
@section('content')
	<section class="page-section" id="contact">
        <div class="container">
            <!-- Contact Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">
            {{$lang->de_name}}</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- Contact Section Form-->
            <div class="row">
                <div class="col-lg-5 mx-auto">
                    <div class="imgPlace">
                        <a data-fancybox="gallery" href="{{asset($lang->de_image)}}">
                            <img class="img-fluid rounded mb-5" src="{{asset($lang->de_image)}}" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-lg-7 mx-auto">
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
    </section>
@stop
@section('footer-js')
    <script type="text/javascript">
        $(document).ready(function(){

        });
    </script>
@stop