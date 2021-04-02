@extends('user/layout/index')
@section('title')
    Highly rater tour
@parent
@stop
@section('header_styles')
	<style>
		#header_about,#header_about:hover{
			color: #fff !important;
    		background: #1abc9c !important;
		}
        #div_more #header_about{
            color: white !important;
        }
	</style>
@stop
@section('content')
	<section class="page-section" id="about">
        <div class="container">
            <!-- About Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">{{ trans('messages.About') }}</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- About Section Content-->
            <div class="row">
                <div class="col-lg-4 ml-auto"><p class="lead">{{ trans('messages.Aboutleft') }}</p></div>
                <div class="col-lg-4 mr-auto"><p class="lead">{{ trans('messages.Aboutright') }}</p>
                <p class="lead" style="font-style: italic;"><i class="fas fa-phone-square-alt"></i> Tel: 0327927587</p>
                <p class="lead" style="font-style: italic;"><i class="fas fa-envelope"></i> Email: longhoanghai8499@gmail.com</p>
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