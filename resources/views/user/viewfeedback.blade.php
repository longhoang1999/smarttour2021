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
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">traveler reviews</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- About Section Content-->
            <?php use App\Models\User;  ?>
            @foreach($feedback as $fb)
                <?php $use = User::where("us_id",$fb->fb_us_id)->first(); ?>
                <div class="row">
                    <div class="col-md-1 col-sm-1 col-1">
                        @if($use->us_image == "")
                            <a data-fancybox="gallery" href="{{asset('assets/img/portfolio/cabin.png')}}">
                                <img class="img-fluid rounded mb-5" style="max-width:4em;" src="{{asset('assets/img/portfolio/cabin.png')}}" alt="">
                            </a>
                        @else
                            <a data-fancybox="gallery" href="{{asset($use->us_image)}}">
                                <img class="img-fluid rounded mb-5" style="max-width:4em;" src="{{asset($use->us_image)}}" alt="">
                            </a>
                        @endif
                    </div>
                    <div class="col-md-10 col-sm-2 col-2">
                        <p class="font-weight-bold mb-0">
                            {{$use->us_fullName}}
                        </p>
                        <p class="mb-0"><span class="font-weight-bold">Rating: </span><span>{{$fb->star}} <i class="fas fa-star text-warning"></i></span></p>
                        <p class="mb-0"><span class="font-weight-bold">Feedback: </span><span>{{$fb->content}}</span></p>
                    </div>
                </div>
                <hr>
            @endforeach
        </div>
    </section>
@stop
@section('footer-js')
    <script type="text/javascript">
        $(document).ready(function(){

        });
    </script>
@stop