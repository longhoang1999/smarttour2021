@extends('sharetour/layout/index')
@section('title')
    {{ trans('newlang.tourDetail') }}
@parent   
@stop
@section('header_styles')  
    <link rel="stylesheet" href="{{asset('css/sharetourDetail.css')}}">
@stop
@section('content')
    <?php 
        use App\Models\Destination;
        use App\Models\Language;
        use App\Models\Uservotes;
        use Illuminate\Support\Facades\Auth;
        use Illuminate\Support\Facades\DB;
        use App\Models\User;
        use App\Models\Comment;
    ?>
    <section class="page-section portfolio" id="portfolio">
        <div class="container">
            <!-- Portfolio Section Heading-->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">{{ trans('newlang.tourDetail') }}</h2>
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
                        <ul>
                            <li>
                                <span class="like_tour"><i class="fas fa-heart"></i> Like 
                                    <span class="total_like">({{count($array_user_like)}})</span>
                                </span>
                            </li>
                            <li>
                                <span class="rating_tour" 
                                @if(Auth::check())
                                    onclick="crollFunction()"
                                @else
                                    data-toggle="modal"
                                    data-target="#modalLogin"
                                @endif>
                                    <i class="fas fa-flag-checkered"></i> {{ trans('newlang.Rating') }} ({{$share->numberReviews}})
                                </span>
                            </li>
                            <li>
                                <a href="{{route('share.viewSharetour',[$route->to_id,$share->sh_id])}}" class="view_tour">
                                    <i class="fas fa-map-marked"></i> View tour
                                </a>
                            </li>
                        </ul>
                    </div>
                    @if(Auth::check())
                        <?php $findVotes =  Uservotes::where("sh_id",$share->sh_id)->where("us_id",Auth::user()->us_id)->first(); ?>
                        @if(!empty($findVotes))
                            <p><span class="font-weight-bold font-italic">{{ trans('newlang.Yourvotes') }}: </span>
                            <span>
                                <i class="fas fa-star star_1" data-value="1"></i>
                                <i class="fas fa-star star_2" data-value="2"></i>
                                <i class="fas fa-star star_3" data-value="3"></i>
                                <i class="fas fa-star star_4" data-value="4"></i>
                                <i class="fas fa-star star_5" data-value="5"></i>
                            </span></p>
                        @else
                            <p><span class="font-weight-bold font-italic">{{ trans('newlang.Yourvotes') }}: </span>
                            <span class="badge badge-success">{{ trans('newlang.notHavereviews') }}</span></p>
                        @endif
                    @endif
                    <p><span class="font-weight-bold font-italic">{{ trans('newlang.Introduce') }}: </span>
                    <span>{{$share->content}}</span></p>
                    <p><span class="font-weight-bold font-italic">{{ trans('newlang.averageRating') }}: </span>
                    <span>{{$share->number_star}} <i class="fas fa-star text-warning"></i></span></p>
                    <p><span class="font-weight-bold font-italic">{{ trans('newlang.numberofRatings') }}: </span>
                    <span>{{$share->numberReviews}} votes</span></p>
                    @if($route->to_startLocat != "")
                        <?php $des_startLocat = Destination::where("de_remove",$route->to_startLocat)->first(); ?>
                        <p><span class="font-weight-bold font-italic">{{ trans('newlang.startLocation') }}: </span>
                        <span id="startLocation" data-id="{{$des_startLocat->de_remove}}"><i class="fas fa-street-view" style="color:#e74949;"></i> {{$des_startLocat->de_name}}</span></p>
                    @else
                        <p><span class="font-weight-bold font-italic">{{ trans('newlang.startLocation') }}: <span class="badge badge-warning">{{ trans('newlang.Notavailable') }}</span></p>
                    @endif
                    <p class="font-weight-bold font-italic mb-0">{{ trans('newlang.Location') }}:</p>
                    <p id="detail_location"></p>
                    <p><span class="font-weight-bold font-italic">{{ trans('newlang.startTime') }}: </span>
                    <span>{{date('d/m/Y h:i a', strtotime($route->to_starttime))}}</span></p>
                    <p><span class="font-weight-bold font-italic">{{ trans('newlang.endtimeTime') }}: </span>
                    <span>{{date('d/m/Y h:i a', strtotime($route->to_endtime))}}</span></p>
                    <?php 
                        $total = Carbon\Carbon::parse($route->to_endtime)->diffInMinutes(Carbon\Carbon::parse($route->to_starttime));
                     ?>
                    <p><span class="font-weight-bold font-italic">{{ trans('newlang.totalTourTime') }}: </span>
                    <span class="total_time"></span></p>
                    <!-- js take total -->
                    <script type="text/javascript">
                        var duration = moment.duration({{$total}}, 'minutes');
                        var durationString = duration.days() + 'd ' + duration.hours() + 'h ' + duration.minutes() + 'm';
                        console.log(durationString);
                        $(".total_time").html(durationString);
                    </script>
                    <!-- /endis -->
                    <p><span class="font-weight-bold font-italic">Người tạo: </span>
                    <span>{{$creatorName}}</span></p>
                    <p><span class="font-weight-bold font-italic">{{ trans('newlang.dateCreated') }}: </span>
                    <span>{{date('d/m/Y', strtotime($route->to_startDay))}}</span></p>
                </div>
            </div>
        </div>
    </section>
    <!-- Portfolio Item 4-->
    <div class="container rating_comment_title">
        <h3 class="font-weight-bold font-italic">{{ trans('messages.HIGHLIGHTS_TOUR') }}</h3>
    </div>
    <div class="container mb-5 slide-show" id="slideshow">
        <div class="slide-show-tour">
            @foreach($shareTour as $value)
            <?php $route = Route::where("to_id",$value->sh_to_id)->first(); ?>
            <a href="{{route('viewtour',$value->sh_id)}}" class="hightly_div_child">
                <p class="tourContent">
                    <span class="nameTour">{{$route->to_name}}</span>
                    {{$value->number_star}}<i class="fas fa-star text-warning"></i> - {{$value->numberReviews}} votes
                </p>
                @if($value->image != "")
                    <img src="{{asset($value->image)}}" alt="" class="img_open_model{{$value->sh_id}}">
                @else
                    <img src="{{asset('imgPlace/empty.png')}}" alt="" title="location with no photo" class="img_open_model{{$value->sh_id}}"/>
                @endif
            </a>
            @endforeach
        </div>
    </div>

    <div class="container rating_comment_title">
        <h3 class="font-weight-bold font-italic">Đánh giá và phản hồi</h3>
    </div>
    <div class="container rating_comment">
        <div class="rating_comment-left">
            <div class="line_1">
                <span class="font-weight-bold">{{$share->number_star}}</span>
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <i class="fas fa-star text-warning"></i>
                <span> - {{$totalVotes}} votes</span>
            </div>
            <div class="line_1">
                <ul class="line_1_detail">
                    <li>
                        <span class="font-weight-bold">5</span><i class="fas fa-star text-warning"></i>: 
                        <span class="detail_votes">{{$fiveStar}} votes</span>
                    </li>
                    <li>
                        <span class="font-weight-bold">4</span><i class="fas fa-star text-warning"></i>: 
                        <span class="detail_votes">{{$fourStar}} votes</span>
                    </li>
                    <li>
                        <span class="font-weight-bold">3</span><i class="fas fa-star text-warning"></i>: 
                        <span class="detail_votes">{{$threeStar}} votes</span>
                    </li>
                    <li>
                        <span class="font-weight-bold">2</span><i class="fas fa-star text-warning"></i>: 
                        <span class="detail_votes">{{$towStar}} votes</span>
                    </li>
                    <li>
                        <span class="font-weight-bold">1</span><i class="fas fa-star text-warning"></i>: 
                        <span class="detail_votes">{{$oneStar}} votes</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="rating_comment-right">
            @if(Auth::check())
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
                        <?php 
                            $countShare = DB::table('tour')->where('to_id_user',Auth::user()->us_id)
                                ->rightJoin('sharetour', 'sharetour.sh_to_id', '=', 'tour.to_id')
                                ->count();
                         ?>
                        <div class="block_info_user">
                            <span>{{Auth::user()->us_fullName}}</span>
                            @if($countShare > 0)
                                <small>Đã chia sẻ {{$countShare}} toues</small>
                            @endif
                        </div>
                    </div>
                    <div class="info_uslogin_right">
                        <form method="post" action="{{route('user.choseComment')}}" id="form_chose_comment">
                            @csrf
                            <input type="hidden" value="{{$shareId}}" name="share_tour_id">
                            <select class="form-control" id="chose_comment" name="chose_comment">
                                <option hidden="">---Lọc comment</option>
                                <option value="1"
                                @if($typeComment == "all")
                                    selected="" 
                                @endif
                                >Tất cả</option>
                                <option value="2"
                                @if($typeComment == "user_login")
                                    selected="" 
                                @endif
                                >Đánh giá của bạn</option>
                            </select>
                        </form>
                    </div>              
                </div>
                <form id="form_add_comment" method="post" action="{{route('user.addcomment',$share->sh_id)}}"> 
                   @csrf
                   <div class="chose_star" id="div_Starrank_tour">
                       <i class="fas fa-star star_1" data-value="1"></i>
                       <i class="fas fa-star star_2" data-value="2"></i>
                       <i class="fas fa-star star_3" data-value="3"></i>
                       <i class="fas fa-star star_4" data-value="4"></i>
                       <i class="fas fa-star star_5" data-value="5"></i>
                   </div>
                   <input type="hidden" name="numberStar" id="numberStar" required="">
                   <textarea class="form-control" placeholder="Nhập bình luận của bạn (nếu có)" name="content_rating"></textarea> 
                   <input type="submit" class="btn btn-sm btn-primary" value="Đánh giá" id="btn_rating">
                </form>
            </div>
            @endif
            <!-- detail comment -->
            @foreach($findComment as $Comment)
                <?php 
                    $usVotes = Uservotes::where("id",$Comment->id_user_votes)->first();
                    $findUser = User::where("us_id",$usVotes->us_id)->first();
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
                        <div class="comment_content-title-right">
                            @if(Auth::check())
                                @if($usVotes->us_id == Auth::user()->us_id)
                                <span class="menu_icon">
                                    <i class="fas fa-ellipsis-h"></i>
                                </span>
                                <div class="menu_more">
                                    <ul>
                                        <li>
                                            <a href="#" data-id="{{$Comment->co_id}}" data-toggle="modal" data-target="#warningDelete">Xóa</a>
                                        </li>
                                    </ul>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="comment_content-starvotes">
                        @if($usVotes->vote_number == "1")
                            <i class="fas fa-star text-warning"></i>
                        @elseif($usVotes->vote_number == "2")
                            @for($i = 0;$i < 2;$i++ )
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                        @elseif($usVotes->vote_number == "3")
                            @for($i = 0;$i < 3;$i++ )
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                        @elseif($usVotes->vote_number == "4")
                            @for($i = 0;$i < 4;$i++ )
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                        @elseif($usVotes->vote_number == "5")
                            @for($i = 0;$i < 5;$i++ )
                                <i class="fas fa-star text-warning"></i>
                            @endfor
                        @endif
                    </div>
                    <div class="comment_content-content">
                        <span>
                            {!! $Comment->co_content !!}
                        </span>
                    </div>
                    <div class="comment_content-date">
                        Đã viết vào: <span>{{date("d/m/Y", strtotime($Comment->co_date_created))}}</span>
                    </div>
                </div>
            @endforeach
            <div class="paginate">
                {{ $findComment->links() }}
            </div>
        </div>
    </div>
    <!-- warning delete -->
    <div class="modal fade" id="warningDelete" tabindex="-1" role="dialog" aria-labelledby="warningDeleteLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="warningDeleteLabel">Chú ý!</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Bạn có muốn xóa bình luận này không!!
          </div>
          <div class="modal-footer">
            <a href="#" class="btn btn-danger">Có</a>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Không</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Model -->
    <div class="modal fade" id="modalDetailPlace" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ trans('newlang.DetailPlace') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="opSelection">
                <div class="showImage">{{ trans('newlang.Showimage') }}</div>
                <div class="showMap">{{ trans('newlang.Showmap') }}</div>
            </div>
            <div class="imgPlace mt-4 mb-4">
            </div>
            <div id="map" class="mt-4 mb-4"></div>
            <div class="container-fuild">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-12 mb-4">
                        <p class="font-weight-bold font-italic">{{ trans('newlang.TypeofPlace') }}</p>
                    </div>
                    <div class="col-md-8 col-sm-6 col-12 mb-4">
                        <p id="typePlace"></p>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12 mb-4">
                        <p class="font-weight-bold font-italic">{{ trans('newlang.shortDescription') }}</p>
                    </div>
                    <div class="col-md-8 col-sm-6 col-12 mb-4">
                        <p id="short"></p>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12 mb-4">
                        <p class="font-weight-bold font-italic">{{ trans('newlang.Description') }}</p>
                    </div>
                    <div class="col-md-8 col-sm-6 col-12 mb-4">
                        <p id="description"></p>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12 mb-4">
                        <p class="font-weight-bold font-italic">{{ trans('newlang.averageTravelTime') }}</p>
                    </div>
                    <div class="col-md-8 col-sm-6 col-12 mb-4">
                        <p id="timeAvg"></p>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12 mb-4">
                        <p class="font-weight-bold font-italic">{{ trans('newlang.Linkongooglemap') }}</p>
                    </div>
                    <div class="col-md-8 col-sm-6 col-12 mb-4" id="linkMap">
                    </div>
                    <div class="col-md-4 col-sm-6 col-12 mb-4">
                        <p class="font-weight-bold font-italic">{{ trans('newlang.LinkonVR') }}</p>
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
@stop
@section('footer-js')  
    <script type="text/javascript">
        @if(Auth::check())
            @foreach($array_user_like as $arr)
                @if(Auth::user()->us_id == $arr)
                    $("#div_btn .like_tour").css("color","#fb0000");
                    $("#div_btn .like_tour").css("font-weight","bold");
                @endif
            @endforeach
        @endif
        @if(Auth::check())
        $(".like_tour").click(function(){
            let $url_path = '{!! url('/') !!}';
            let _token = $('meta[name="csrf-token"]').attr('content');
            let routeChangeLike = $url_path+"/changeLikeTour";
            $.ajax({
                  url:routeChangeLike,
                  method:"post",
                  data:{_token:_token,shareId:{{$shareId}}},
                  success:function(data){ 
                    console.log(data[0])
                    if(data[0] == 1)
                    {
                        $(".like_tour").css("color","#fb0000");
                        $(".total_like").text(`(${data[1]})`);
                        $("#div_btn .like_tour").css("font-weight","bold");
                    }
                    if(data[0] == 2)
                    {
                        $(".like_tour").css("color","black");
                        $(".total_like").text(`(${data[1]})`);
                        $("#div_btn .like_tour").css("font-weight","normal");
                    }
                }
            });
        })
        @else
        $(".like_tour").click(function(){
            $("#modalLogin").modal("show");
        })
        @endif
        $('#form_add_comment').one('submit', function(e) {
            e.preventDefault();
            if($("#numberStar").val() == "")
            {
                alert("Bạn hãy nhập số sao đánh giá!");
            }
            else
                $(this).submit();
        });
        $('#warningDelete').on('show.bs.modal', function (event) {
          let button = $(event.relatedTarget);
          let recipient = button.data('id');
          let route = '{!! url('/') !!}'+"/deleteComment/"+recipient;
          var modal = $(this);
          modal.find('.modal-footer a').attr('href',route);
        })
        $(".menu_icon").click(function(){
            $(this).parent().find(".menu_more").toggle(200);
        });
        $(document).click(function (e)
        {
            var container = $(".comment_content-title-right");
            if (!container.is(e.target) && container.has(e.target).length === 0)
            {
                $(".menu_more").hide();
            }
        });
        $("#chose_comment").change(function(){
            $("#form_chose_comment").submit();
        });
        @if(!empty($findVotes))
            @if($findVotes->vote_number == "1")
                $(".star_1").css("color","#ff9700");
            @elseif($findVotes->vote_number == "2")
                @for($i=1;$i<=2;$i++)
                    $(".star_{{$i}}").css("color","#ff9700");
                @endfor
            @elseif($findVotes->vote_number == "3")
                @for($i=1;$i<=3;$i++)
                    $(".star_{{$i}}").css("color","#ff9700");
                @endfor
            @elseif($findVotes->vote_number == "4")
                @for($i=1;$i<=4;$i++)
                    $(".star_{{$i}}").css("color","#ff9700");
                @endfor
            @elseif($findVotes->vote_number == "5")
                @for($i=1;$i<=5;$i++)
                    $(".star_{{$i}}").css("color","#ff9700");
                @endfor
            @endif
            $("#numberStar").val("{{$findVotes->vote_number}}");
        @endif
        function crollFunction(){
            $("textarea[name=content_rating]").focus();
            $("html, body").delay(500).animate({
                scrollTop: $('.rating_comment').offset().top - 150
            }, 500);
        }
        $(document).ready(function(){
            $('.slide-show-tour').slick({
                slidesToShow: 3,
                slidesToScroll: 2,
                autoplay: true,
                autoplaySpeed: 2500,
                dots: true,
                dotClass: 'slick-dots',
                fade: false,
                pauseOnHover: false,
                prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-caret-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fas fa-caret-right"></i></button>',
            });
            // votess star
            @for($i = 1; $i<= 5; $i++)
              $("#div_Starrank_tour .star_{{$i}}").click(function(){
                  @for($j = 1 ; $j <= 5; $j++)
                      $("#div_Starrank_tour .star_{{$j}}").css("color","#212529");
                  @endfor
                  @for($j = 1 ; $j <= $i; $j++)
                      $("#div_Starrank_tour .star_{{$j}}").css("color","#ff9700");
                  @endfor
                  $("#numberStar").val($(this).attr("data-value"));
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
        var markers=[];
        function initMap(){
            //let lll = { lat: 21.0374, lng: 105.774 }
            var map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12.5,
                center: { lat: 21.0226586, lng: 105.8179091 },
                gestureHandling: 'greedy',
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
                                    $(".imgPlace").append("<a data-fancybox='gallery' href='{{asset('imgPlace/empty.png')}}'> <img class='img-fluid' src='{{asset('imgPlace/empty.png')}}' alt='' style='width: 70%' title='{{ trans('newlang.locationNoPhoto') }}'></a>");
                                }
                                $("#typePlace").empty();
                                $("#typePlace").append(data[9]);
                                $("#short").empty();
                                $("#description").empty();
                                if(data[4] != null)
                                    $("#short").append(data[4]);
                                else
                                    $("#short").append('<span class="badge badge-warning">{{ trans("newlang.Notavailable") }}</span>');
                                if(data[5] != null)
                                    $("#description").append(data[5]);
                                else
                                    $("#description").append('<span class="badge badge-warning">{{ trans("newlang.Notavailable") }}</span>');  

                                $("#timeAvg").html(parseFloat(data[6])/60/60+" hours");
                                $("#linkMap").empty();
                                if(data[7] != null)
                                    $("#linkMap").append('<a href="'+data[7]+'" target="_blank">Link here</a>');
                                else
                                    $("#linkMap").append('<span class="badge badge-warning">{{ trans("newlang.Notavailable") }}</span>');
                                $("#linkvr").empty();
                                if(data[8] != null)
                                    $("#linkvr").append('<a href="'+data[8]+'" target="_blank">Link here</a>');
                                else
                                    $("#linkvr").append('<span class="badge badge-warning">{{ trans("newlang.Notavailable") }}</span>');
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
                                $(".imgPlace").append("<a data-fancybox='gallery' href='{{asset('imgPlace/empty.png')}}'> <img class='img-fluid' src='{{asset('imgPlace/empty.png')}}' alt='' style='width: 70%' title='{{ trans('newlang.locationNoPhoto') }}'></a>");
                            }
                            $("#typePlace").empty();
                            $("#typePlace").append(data[9]);
                            $("#short").empty();
                            $("#description").empty();
                            if(data[4] != null)
                                $("#short").append(data[4]);
                            else
                                $("#short").append('<span class="badge badge-warning">{{ trans("newlang.Notavailable") }}</span>');

                            if(data[5] != null)
                                $("#description").append(data[5]);
                            else
                                $("#description").append('<span class="badge badge-warning">{{ trans("newlang.Notavailable") }}</span>');  

                            $("#timeAvg").html(parseFloat(data[6])/60/60+" hours");
                            $("#linkMap").empty();
                            if(data[7] != null)
                                $("#linkMap").append('<a href="'+data[7]+'" target="_blank">Link here</a>');
                            else
                                $("#linkMap").append('<span class="badge badge-warning">{{ trans("newlang.Notavailable") }}</span>');
                            $("#linkvr").empty();
                            if(data[8] != null)
                                $("#linkvr").append('<a href="'+data[8]+'" target="_blank">Link here</a>');
                            else
                                $("#linkvr").append('<span class="badge badge-warning">{{ trans("newlang.Notavailable") }}</span>');
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
                    alert("{{ trans('newlang.Geocode') }}: " + status);
                  }
                });
            };
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgbjwIY5Q1eZ-Ejqur0a8avEQWowfA39s&callback=initMap" async defer></script>
@stop
