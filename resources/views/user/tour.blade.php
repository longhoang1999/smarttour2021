@extends('user/layout/index')
@section('title')
    {{ trans('messages.Highlyratertour') }}
@parent
@stop
@section('header_styles')
	<style>
		#header_tour,#header_tour:hover{
			color: #fff !important;
    		background: #1abc9c !important;
		}
        #loadMoreTour{
            color: #005dcc;
        }
	</style>
@stop
@section('content')
	<?php use App\Models\Destination; use App\Models\Language;?>   
	<section class="page-section portfolio" id="introduce">
        <div class="container">
            <!-- About Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">{{ trans('messages.Highlyratertour') }}</h2>
            <!-- Icon Divider-->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                <div class="divider-custom-line"></div>
            </div>
            <!-- About Section Content-->
            <?php use App\Models\Route; ?>
            <div class="row justify-content-center">
                @foreach($shareTour as $value)
                    <div class="col-md-4 col-sm-6 col-12 hightly_div mb-3">
                        <?php $route = Route::where("to_id",$value->sh_to_id)->first(); ?>
                        <p class="tourName" title="{{$route->to_name}}">{{$route->to_name}}</p>
                        <div class="hightly_div_child">
                            <p class="tourContent">{{$value->number_star}} <i class="fas fa-star text-warning"></i> - {{$value->numberReviews}} votes</p>
                            @if($value->image != "")
                                <img src="{{asset($value->image)}}" alt="" class="img_open_model{{$value->sh_id}}">
                            @else
                                <img src="{{asset('imgPlace/empty.png')}}" alt="" title="{{ trans('messages.locationwithnophoto') }}" class="img_open_model{{$value->sh_id}}"/>
                            @endif
                        </div>
                    </div>
                @endforeach
                <div class="col-md-12 col-sm-12 col-12" id="div_loadMore">
                    <?php $type="notlogin" ?>
                    <a href="{{route('searchTour')}}" id="loadMoreTour">--- {{ trans('messages.searchTour') }} <i class="fas fa-angle-double-right pt-2"></i> ---</a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 ml-auto"><p class="lead text-center">{{ trans('messages.introduceContent') }}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- modal detail tour -->
    <?php use App\Models\Uservotes; ?>
    <?php use Illuminate\Support\Arr;?>
    @if(Auth::check())
        @foreach($shareTour as $value)
            <?php $route = Route::where("to_id",$value->sh_to_id)->first(); ?>
            <div class="modal fade" id="modal_{{$value->sh_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans('messages.Tourname') }}: {{$route->to_name}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <?php 
                        if($route->to_startLocat != "")
                        {
                            $des_start = Destination::where("de_remove",$route->to_startLocat)->first();
                            $start = $des_start->de_name;
                        }
                        else
                        {
                            $start = "Not available";
                        }

                        $pieces = explode("|", $route->to_des);
                        $array = array();
                        for ($i=0; $i < count($pieces)-1; $i++) {
                            $array = Arr::add($array, $i ,$pieces[$i]);
                        }
                        $detailLocation = array();
                        foreach ($array as  $ar) {
                            $checkDes = Destination::where("de_remove",$ar)->first();
                            if($checkDes->de_default == "0")
                            {
                                if(Session::has('website_language') && Session::get('website_language') == "vi")
                                {
                                    $desName = Language::select('de_name')->where("language","vn")->where("des_id",$ar)->first();
                                    array_push($detailLocation, $desName->de_name);
                                }
                                else
                                {
                                    $desName = Language::select('de_name')->where("language","en")->where("des_id",$ar)->first();
                                    array_push($detailLocation, $desName->de_name);
                                }
                            }
                            else if($checkDes->de_default == "1")
                            {
                                array_push($detailLocation, $checkDes->de_name);
                            }
                        }
                   ?>
                  <div class="modal-body">
                    <div class="container-fuild">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.StartLocation') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{$start}}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Location') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12 mb-2">
                                @foreach($detailLocation as $detail)
                                <p class="mb-0">
                                    <i class="fas fa-street-view point text-danger"></i>
                                    <span>{{$detail}}</span>
                                </p>
                                @endforeach
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Timestart') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{date('d/m/Y h:i a', strtotime($route->to_starttime))}}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Timeend') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                @if($route->to_endtime != "")
                                    <p>{{date('d/m/Y h:i a', strtotime($route->to_endtime))}}</p>
                                @else
                                    <span class="badge badge-warning">{{ trans('messages.Notavailable') }}</span>
                                @endif
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Totaltourtime') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12 total_time">
                            </div>
                            <?php 
                                $total = Carbon\Carbon::parse($route->to_endtime)->diffInMinutes(Carbon\Carbon::parse($route->to_starttime));
                             ?>
                            <!-- js take total -->
                            <script type="text/javascript">
                                duration = moment.duration({{$total}}, 'minutes');
                                durationString = duration.days() + 'd ' + duration.hours() + 'h ' + duration.minutes() + 'm';
                                console.log(durationString);
                                $("#modal_{{$value->sh_id}} .total_time").html(durationString);
                            </script>
                            <!-- /endis -->
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Comeback') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                @if($route->to_comback == "0")
                                    <span class="badge badge-warning">No</span>
                                @else
                                    <span class="badge badge-success">Yes</span>
                                @endif
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Startday') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{date('d/m/Y', strtotime($route->to_startDay))}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Introduce') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{$value->content}}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.avgStar') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{$value->number_star}} <i class="fas fa-star text-warning"></i></p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.numRating') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{$value->numberReviews}}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Yourvote') }}: </p>
                            </div>
                            <?php $findVotes =  Uservotes::where("sh_id",$value->sh_id)->where("us_id",Auth::user()->us_id)->first(); ?>
                            @if(!empty($findVotes))
                                <div class="col-md-8 col-sm-6 col-12">
                                    <p>{{$findVotes->vote_number}} <i class="fas fa-star text-warning"></i></p>
                                </div>
                            @else
                                <div class="col-md-8 col-sm-6 col-12">
                                    <span class="badge badge-success">{{ trans('messages.dontHavereviews') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <a href="{{route('viewtour',$value->sh_id)}}" class="btn btn-success">{{ trans('messages.Viewtour') }}</a>
                  </div>
                </div>
              </div>
            </div>
        @endforeach
    @else
        @foreach($shareTour as $value)
            <div class="modal fade" id="modal_{{$value->sh_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ trans('messages.Tourname') }}: {{$route->to_name}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <?php $route = Route::where("to_id",$value->sh_to_id)->first(); ?>
                  <?php 
                        if($route->to_startLocat != "")
                        {
                            $des_start = Destination::where("de_remove",$route->to_startLocat)->first();
                            $start = $des_start->de_name;
                        }
                        else
                        {
                            $start = "{{ trans('messages.Notavailable') }}";
                        }

                        $pieces = explode("|", $route->to_des);
                        $array = array();
                        for ($i=0; $i < count($pieces)-1; $i++) {
                            $array = Arr::add($array, $i ,$pieces[$i]);
                        }
                        $detailLocation = "";
                        foreach ($array as  $ar) {
                            $checkDes = Destination::where("de_remove",$ar)->first();
                            if($checkDes->de_default == "0")
                            {
                                if(Session::has('website_language') && Session::get('website_language') == "vi")
                                {
                                    $desName = Language::select('de_name')->where("language","vn")->where("des_id",$ar)->first();
                                    $detailLocation=$detailLocation.'--'.$desName->de_name;
                                }
                                else
                                {
                                    $desName = Language::select('de_name')->where("language","en")->where("des_id",$ar)->first();
                                    $detailLocation=$detailLocation.'--'.$desName->de_name;
                                }
                            }
                            else if($checkDes->de_default == "1")
                            {
                                $detailLocation= $detailLocation.'--'.$checkDes->de_name;
                            }
                        }
                   ?>
                  <div class="modal-body">
                    <div class="container-fuild">
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.StartLocation') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{$start}}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Location') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{$detailLocation}}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Timestart') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{date('d/m/Y h:i a', strtotime($route->to_starttime))}}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Timeend') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                @if($route->to_endtime != "")
                                    <p>{{date('d/m/Y h:i a', strtotime($route->to_endtime))}}</p>
                                @else
                                    <span class="badge badge-warning">Not available</span>
                                @endif
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Totaltourtime') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12 total_time">
                            </div>
                            <?php 
                                $total = Carbon\Carbon::parse($route->to_endtime)->diffInMinutes(Carbon\Carbon::parse($route->to_starttime));
                             ?>
                            <!-- js take total -->
                            <script type="text/javascript">
                                duration = moment.duration({{$total}}, 'minutes');
                                durationString = duration.days() + 'd ' + duration.hours() + 'h ' + duration.minutes() + 'm';
                                console.log(durationString);
                                $("#modal_{{$value->sh_id}} .total_time").html(durationString);
                            </script>
                            <!-- /endis -->
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Comeback') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                @if($route->to_comback == "0")
                                    <span class="badge badge-warning">No</span>
                                @else
                                    <span class="badge badge-success">Yes</span>
                                @endif
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Startday') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{date('d/m/Y', strtotime($route->to_startDay))}}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.Introduce') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{$value->content}}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.avgStar') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{$value->number_star}} <i class="fas fa-star text-warning"></i></p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">{{ trans('messages.numRating') }}</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{$value->numberReviews}}</p>
                            </div>
                            @if(Auth::check())
                                <div class="col-md-4 col-sm-6 col-12">
                                    <p class="font-weight-bold font-italic">{{ trans('messages.Yourvote') }}: </p>
                                </div>
                                <?php $findVotes =  Uservotes::where("sh_id",$value->sh_id)->where("us_id",Auth::user()->us_id)->first(); ?>
                                @if(!empty($findVotes))
                                    <div class="col-md-8 col-sm-6 col-12">
                                        <p>{{$findVotes->vote_number}} <i class="fas fa-star text-warning"></i></p>
                                    </div>
                                @else
                                    <div class="col-md-8 col-sm-6 col-12">
                                        <span class="badge badge-success">{{ trans('messages.dontHavereviews') }}</span>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <a href="{{route('viewtour',$value->sh_id)}}" class="btn btn-success">{{ trans('messages.Viewtour') }}</a>
                  </div>
                </div>
              </div>
            </div>
        @endforeach
    @endif
    <!-- /modal detail tour -->
@stop
@section('footer-js')
    <script type="text/javascript">
        $(document).ready(function(){
            @foreach($shareTour as $value)
                $(".img_open_model{{$value->sh_id}}").click(function(){
                    $("#modal_{{$value->sh_id}}").modal("show");
                });
                $(".img_open_model{{$value->sh_id}}").parent().click(function(){
                    $("#modal_{{$value->sh_id}}").modal("show");
                });
            @endforeach
        });
    </script>
@stop