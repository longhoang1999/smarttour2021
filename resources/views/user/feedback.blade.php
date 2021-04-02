@extends('user/layout/index')
@section('title')
    Highly rater tour
@parent
@stop
@section('header_styles')
	<style>
        #header_feedback,#header_feedback:hover{
            color: #fff !important;
            background: #1abc9c !important;
        }
        #div_more #header_feedback{
            color: white !important;
        }
	</style>
@stop
@section('content')
	<section class="page-section" id="contact">
        <div class="container">
            <!-- Contact Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">
            {{ trans('messages.Feedback') }}</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- Contact Section Form-->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    @if ($message = Session::get('notification'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{$message}}</strong>
                        </div>
                    @endif
                    <!-- To configure the contact form email address, go to mail/contact_me.php and update the email address in the PHP file on line 19.-->
                    <form name="sentMessage" method="post" action="{{route('user.feedback')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="control-group">
                            <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                <label>{{ trans('messages.Star') }}</label>
                                <input class="form-control" id="name" type="number" placeholder="{{ trans('messages.Star') }}" required="required" data-validation-required-message="Please enter your star" min=0 max=5 name="star"/>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                <label>{{ trans('messages.Feedback') }}</label>
                                <textarea class="form-control" placeholder="{{ trans('messages.Feedback') }}" required="required" data-validation-required-message="Please enter your feedback." name="feedback"></textarea>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <br />
                        <div id="success"></div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-xl" type="submit">{{ trans('messages.sendFeedback') }}</button>
                        </div>
                    </form>
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