<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="author" content="" />
        <title>Tour Advice</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon.ico')}}" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{asset('css/styles.css')}}" rel="stylesheet" />
        <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
        <link rel="stylesheet" href="{{asset('css/sharetour.css')}}">
        <link rel="stylesheet" href="{{asset('css/notlogin.css')}}">
    </head>
    <body id="page-top">
        <?php use App\Models\Destination; use App\Models\Language;use App\Models\Uservotes;use Illuminate\Support\Facades\Auth;?>                                 
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="{{url('/#page-top')}}">Tour Advice</a>
                <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="Language">
                    <div class="lan_title">
                        <span>{{ trans('messages.lang') }}</span><i class="fas fa-caret-down"></i>
                    </div>
                    <div class="lan_more">
                        <p class="lan_vn" id="Lan_VN">VN</p>
                        <p class="lan_en" id="Lan_EN">EN</p>
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{url('/#portfolio')}}">{{ trans('messages.StartTour') }}</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{url('/#introduce')}}">{{ trans('messages.Introduce') }}</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{url('/#place')}}">{{ trans('messages.Places') }}</a></li>
                        <li class="nav-item mx-0 mx-lg-1" id="li_more">
                            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#">{{ trans('messages.More') }} <i class="fas fa-sort-down"></i></a>
                            <div id="div_more">
                                <a class="nav-link py-3 px-0 px-lg-3  js-scroll-trigger" href="{{url('/#about')}}" id="p_about">{{ trans('messages.About') }}</a>
                                <a class="nav-link py-3 px-0 px-lg-3  js-scroll-trigger" href="{{url('/#contact')}}" id="p_feedback">{{ trans('messages.Feedback') }}</a>
                            </div>
                        </li>
                        <li class="nav-item mx-0 mx-lg-1" id="li_person">
                            @if(Auth::check())
                                <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#">{{ trans('messages.Youraccount') }} <i class="fas fa-sort-down"></i></a>
                                <div id="div_person">
                                    <?php $user = Auth::user();?>
                                    @if($user->us_type == "1")
                                        <p id="comback_admin">{{ trans('messages.adminPage') }}</p>
                                    @endif
                                    <p id="personalInfo">{{ trans('messages.Aboutyou') }}</p>
                                    <p id="p_logout">{{ trans('messages.Logout') }}</p>
                                </div>
                            @else
                                <a id="openModalLogin" class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#">Login</a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <section class="page-section portfolio" id="portfolio">
            <div class="container">
                <!-- Portfolio Section Heading-->
                <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Tour details</h2>
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
                    <style type="text/css">
                        .div_parents{position: relative;}
                        .div_parents p {
                            position: absolute;
                            top: 0;
                            width: 100%;
                            display: block;
                            text-align: center;
                            height: 4.5rem;
                            line-height: 4.5rem;
                            background: rgb(0,0,0,.3);
                            color: white;
                            font-weight: bold;
                            font-size: 2rem;
                            font-style: italic;
                        }
                        #div_btn{
                            display: flex;justify-content: center;
                            margin-bottom: 1rem;
                        }
                        #div_btn button,#div_btn a{
                            width: 40%;
                            margin-right: 5px;
                        }
                    </style>
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
                            <button class="btn btn-warning" data-toggle="modal"
                            @if(Auth::check())
                                data-target="#exampleModal"
                            @else
                                data-target="#modalLogin"
                            @endif
                            >Rating</button>
                            <a href="{{route('share.viewSharetour',[$route->to_id,$share->sh_id])}}" class="btn btn-info">View tour</a>
                        </div>
                        @if(Auth::check())
                            <?php $findVotes =  Uservotes::where("sh_id",$share->sh_id)->where("us_id",Auth::user()->us_id)->first(); ?>
                            @if(!empty($findVotes))
                                <p><span class="font-weight-bold font-italic">Your votes: </span>
                                <span>{{$findVotes->vote_number}} <i class="fas fa-star text-warning"></i></span></p>
                            @else
                                <p><span class="font-weight-bold font-italic">Your votes: </span>
                                <span class="badge badge-success">You do not have reviews for this tour</span></p>
                            @endif
                        @endif
                        <p><span class="font-weight-bold font-italic">Introduce: </span>
                        <span>{{$share->content}}</span></p>
                        <p><span class="font-weight-bold font-italic">Average rating: </span>
                        <span>{{$share->number_star}} <i class="fas fa-star text-warning"></i></span></p>
                        <p><span class="font-weight-bold font-italic">Number of ratings: </span>
                        <span>{{$share->numberReviews}} votes</span></p>
                        @if($route->to_startLocat != "")
                            <?php $des_startLocat = Destination::where("de_remove",$route->to_startLocat)->first(); ?>
                            <p><span class="font-weight-bold font-italic">Start Location: </span>
                            <span id="startLocation" data-id="{{$des_startLocat->de_remove}}"><i class="fas fa-street-view" style="color:#e74949;"></i> {{$des_startLocat->de_name}}</span></p>
                        @else
                            <p><span class="font-weight-bold font-italic">Start Location: <span class="badge badge-warning">Not available</span></p>
                        @endif
                        <p class="font-weight-bold font-italic mb-0">Location:</p>
                        <p id="detail_location"></p>
                        <p><span class="font-weight-bold font-italic">Start time: </span>
                        <span>{{date('h:i a', strtotime($route->to_starttime))}}</span></p>
                        <p><span class="font-weight-bold font-italic">Endtime time: </span>
                        <span>{{date('h:i a', strtotime($route->to_endtime))}}</span></p>
                        <p><span class="font-weight-bold font-italic">Date created: </span>
                        <span>{{date('d/m/Y', strtotime($route->to_startDay))}}</span></p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Model -->
        <div class="modal fade" id="modalDetailPlace" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Place</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="opSelection">
                    <div class="showImage">Show image</div>
                    <div class="showMap">Show Map</div>
                </div>
                <div class="imgPlace mt-4 mb-4">
                </div>
                <div id="map" class="mt-4 mb-4"></div>
                <div class="container-fuild">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12 mb-4">
                            <p class="font-weight-bold font-italic">Short description</p>
                        </div>
                        <div class="col-md-8 col-sm-6 col-12 mb-4">
                            <p id="short"></p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-4">
                            <p class="font-weight-bold font-italic">Description</p>
                        </div>
                        <div class="col-md-8 col-sm-6 col-12 mb-4">
                            <p id="description"></p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-4">
                            <p class="font-weight-bold font-italic">Average travel time</p>
                        </div>
                        <div class="col-md-8 col-sm-6 col-12 mb-4">
                            <p id="timeAvg"></p>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-4">
                            <p class="font-weight-bold font-italic">Link on google map</p>
                        </div>
                        <div class="col-md-8 col-sm-6 col-12 mb-4" id="linkMap">
                        </div>
                        <div class="col-md-4 col-sm-6 col-12 mb-4">
                            <p class="font-weight-bold font-italic">Link on VR</p>
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
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rating</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="container-fuild">
                    <div class="row">
                      <div class="col-md-12 col-sm-12 col-12">
                        <p class="font-weight-bold font-italic">Rating for your tour</p>
                      </div>
                      <div class="col-md-12 col-sm-12 col-12 mb-3" id="div_Starrank_tour">
                        <i class="fas fa-star star_1 fa-2x"  data-value="1" style="cursor: pointer;"></i>
                        <i class="fas fa-star star_2 fa-2x" data-value="2" style="cursor: pointer;"></i>
                        <i class="fas fa-star star_3 fa-2x" data-value="3" style="cursor: pointer;"></i>
                        <i class="fas fa-star star_4 fa-2x"  data-value="4" style="cursor: pointer;"></i>
                        <i class="fas fa-star star_5 fa-2x" data-value="5" style="cursor: pointer;"></i>
                        <i class="fas fa-star star_6 fa-2x" data-value="6" style="cursor: pointer;"></i> 
                        <i class="fas fa-star star_7 fa-2x" data-value="7" style="cursor: pointer;"></i>
                        <i class="fas fa-star star_8 fa-2x" data-value="8" style="cursor: pointer;"></i>
                        <i class="fas fa-star star_9 fa-2x" data-value="9" style="cursor: pointer;"></i>
                        <i class="fas fa-star star_10 fa-2x" data-value="10" style="cursor: pointer;"></i>
                      </div>
                      <input type="hidden" id="star_Share" name="numberStar">
                    </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_Rating">Rating</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Login modal 1-->
        <div class="portfolio-modal modal fade" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="portfolioModal1Label" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fas fa-times"></i></span>
                    </button>
                    <div class="modal-body text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <!-- Portfolio Modal - Title-->
                                    <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0" id="portfolioModal1Label">{{ trans('messages.Login') }}</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <p class="mb-5">{{ trans('messages.pleaseLogin') }}</p>
                                    <!-- Form login -->
                                    <form class="loginForm mb-4 pt-3 pb-3" method="post" action="{{route('postLogin')}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                        @if ($message = Session::get('error'))
                                            <div class="alert alert-danger alert-block">
                                                <button type="button" class="close" data-dismiss="alert">x</button>
                                                <strong>{{$message}}</strong>
                                            </div>
                                        @endif
                                        @if ($message = Session::get('success'))
                                            <div class="alert alert-success alert-block">
                                                <button type="button" class="close" data-dismiss="alert">x</button>
                                                <strong>{{$message}}</strong>
                                            </div>
                                        @endif
                                        @if (count($errors) > 0)
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <div class="txt_field">
                                            <input type="email" name="us_email" required="">
                                            <span></span>
                                            <label>Email</label>
                                        </div>
                                        <div class="txt_field">
                                            <input type="password" name="us_password" required="">
                                            <span></span>
                                            <label>Password</label>
                                        </div>

                                        <div class="div_submit">
                                            <input type="submit" value="{{ trans('messages.Login') }}"> 
                                            <input type="button" id="btn_register" data-toggle="modal" data-target="#modalRegis" value="{{ trans('messages.Registration') }}">
                                        </div>

                                        <div class="pass">
                                            {{ trans('messages.forgotPassword') }}
                                        </div>
                                    </form>
                                    <!-- Form login -->
                                    <button class="btn btn-primary" data-dismiss="modal">
                                        <i class="fas fa-times fa-fw"></i>
                                        {{ trans('messages.CloseWindow') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal dashboard -->
        <!-- Modal reggis -->
        <div class="modal fade" id="modalRegis" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('messages.userRegistration') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="{{route('register')}}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-6 mb-3">
                                <p class="text-left font-weight-bold">Email</p>
                            </div>
                            <div class="col-md-9 col-sm-6 col-6 mb-3">
                                <input type="email" class="form-control" placeholder="Enter Email" required="" name="email">
                            </div>
                            <div class="col-md-3 col-sm-6 col-6 mb-3">
                                <p class="text-left font-weight-bold">Password</p>
                            </div>
                            <div class="col-md-9 col-sm-6 col-6 mb-3">
                                <input type="password" class="form-control" placeholder="Enter password" name="password" required="">
                            </div>
                            <div class="col-md-3 col-sm-6 col-6 mb-3">
                                <p class="text-left font-weight-bold">{{ trans('messages.confirmPassword') }}</p>
                            </div>
                            <div class="col-md-9 col-sm-6 col-6 mb-3">
                                <input type="password" class="form-control" placeholder="{{ trans('messages.confirmPassword') }}" name="confirm" required="">
                            </div>
                            <div class="col-md-3 col-sm-6 col-6 mb-3">
                                <p class="text-left font-weight-bold">{{ trans('messages.FullName') }}</p>
                            </div>
                            <div class="col-md-9 col-sm-6 col-6 mb-3">
                                <input type="text" class="form-control" placeholder="{{ trans('messages.FullName') }}" name="fullname" required="">
                            </div>
                            <div class="col-md-3 col-sm-6 col-6 mb-3">
                                <p class="text-left font-weight-bold">{{ trans('messages.Gender') }}</p>
                            </div>
                            <div class="col-md-9 col-sm-6 col-6 mb-3">
                                <select class="form-control" name="gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-sm-6 col-6 mb-3">
                                <p class="text-left font-weight-bold">{{ trans('messages.Age') }}</p>
                            </div>
                            <div class="col-md-9 col-sm-6 col-6 mb-3">
                                <input type="number" class="form-control" placeholder="{{ trans('messages.Age') }}" name="age" required="">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('messages.CloseWindow') }}</button>
                    <input type="submit" class="btn btn-primary" value="{{ trans('messages.Registration') }}">
                    <p id="p_backLogin">{{ trans('messages.youHaveAcc') }} <span class="backFormLogin">{{ trans('messages.Login') }}</span></p>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal forgotpass -->
        <div class="modal fade" id="modalForgotPass" tabindex="-1" role="dialog" aria-labelledby="modalForgotPassLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalForgotPassLabel">{{trans('messages.forgotPassword')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-12 mb-2">
                            <p class="pt-2 font-weight-bold">{{trans('messages.enterEmail')}} </p>
                        </div>
                        <div class="col-md-6 col-sm-12 col-12 mb-2">
                            <p id="icon_correct" class="text-success"><i class="fas fa-check"></i> {{trans('messages.correctEmail')}}</p>
                            <p id="icon_incorrect" class="text-danger"><i class="fas fa-check"></i> {{trans('messages.incorrectEmail')}}</p>
                            <input type="text" class="form-control" placeholder="Enter your email" id="inputEmail">
                        </div>
                        <div class="col-md-6 col-sm-12 col-12 mb-2">
                        </div>
                        <div class="col-md-6 col-sm-12 col-12 mb-2">
                            <button type="button" class="btn btn-info" id="btn_senKey">{{trans('messages.sendKey')}}</button>
                        </div>
                    </div>
                    <div class="row" id="formCheckKey">
                        <div class="col-md-6 col-sm-12 col-12 mb-2">
                            <p class="pt-2 font-weight-bold">{{trans('messages.enterKey')}} </p>
                        </div>
                        <div class="col-md-6 col-sm-12 col-12 mb-2">
                            <p id="key_incorrect" class="text-danger"><i class="fas fa-check"></i> {{trans('messages.incorrectKey')}} </p>
                            <input type="text" class="form-control text-uppercase" placeholder="{{trans('messages.enterKey')}}" id="inputKey">
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- hết modal dashboard -->
        <!-- Footer-->
        <footer class="footer text-center">
            <div class="container">
                <div class="row">
                    <!-- Footer Location-->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h4 class="text-uppercase mb-4">{{ trans('messages.Location') }}</h4>
                        <p class="lead mb-0">
                            HA NOI
                            <br />
                            VIET NAM
                        </p>
                    </div>
                    <!-- Footer Social Icons-->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h4 class="text-uppercase mb-4">{{ trans('messages.AroundtheWeb') }}</h4>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-linkedin-in"></i></a>
                        <a class="btn btn-outline-light btn-social mx-1" href="#!"><i class="fab fa-fw fa-dribbble"></i></a>
                    </div>
                    <!-- Footer About Text-->
                    <div class="col-lg-4">
                        <h4 class="text-uppercase mb-4">{{ trans('messages.Abouttouradvice') }}</h4>
                        <p class="lead mb-0">
                            {{ trans('messages.experience') }}
                        </p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Copyright Section-->
        <div class="copyright py-4 text-center text-white">
            <div class="container"><small>Copyright © Tour Advice 2021</small></div>
        </div>
        <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes)-->
        <div class="scroll-to-top d-lg-none position-fixed">
            <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top"><i class="fa fa-chevron-up"></i></a>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="personal" tabindex="-1" role="dialog" aria-labelledby="personalModal" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="personalModal">{{ trans('messages.Yourpersonalinformation') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{{$message}}</strong>
                    </div>
                @endif
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12 text-center">
                            <div id="text_img" class="mb-5" ></div>
                            <img class="mb-5" src="{{asset('assets/img/avataaars.svg')}}" alt="" id="default_img" />
                        </div>
                    </div>
                </div>
                <form action="{{route('user.editInfo')}}" method="post" id="formFixInfor" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12 text-center mb-2">
                                <p class="text_content">{{ trans('messages.Avatar') }}</p>
                                <div class="btn_upload">{{ trans('messages.Upload') }}</div>
                                <p class="text_content" id="file_name"></p>
                                <input type="file" class="form-control" id="input_File" name="file" accept="image/*">
                            </div>
                            <div class="col-md-4 col-sm-6 col-6"><p class="text_content">Email</p></div>
                            <div class="col-md-8 col-sm-6 col-6" id="text_email"></div>
                            <div class="col-md-4 col-sm-6 col-6"><p class="text_content">{{ trans('messages.FullName') }}</p></div>
                            <div class="col-md-8 col-sm-6 col-6" id="text_fullName"></div>
                            <div class="col-md-8 col-sm-6 col-6" id="input_fullName">
                                <input type="text" placeholder="Enter your fullname" class="form-control" name="fullName">
                            </div>
                            <div class="col-md-4 col-sm-6 col-6"><p class="text_content">{{ trans('messages.Gender') }}</p></div>
                            <div class="col-md-8 col-sm-6 col-6" id="text_gender"></div>
                            <div class="col-md-8 col-sm-6 col-6" id="input_gender">
                                <select name="gender" class="form-control">
                                    <option value="Male">{{ trans('messages.Male') }}</option>
                                    <option value="Female">{{ trans('messages.Female') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6 col-6"><p class="text_content">{{ trans('messages.Age') }}</p></div>
                            <div class="col-md-8 col-sm-6 col-6" id="text_age"></div>
                            <div class="col-md-8 col-sm-6 col-6" id="input_age">
                                <input type="number" placeholder="Enter your age" class="form-control" name="age">
                            </div>
                            <!-- pass -->

                            <p class="col-md-12 col-sm-12 col-12 openChangePass text-info">{{ trans('messages.ifYouchange') }}. <span class="openClickHere">{{ trans('messages.clickHere') }}</span></p>
                            <div class="col-md-4 col-sm-6 col-6"><p class="text_content openItems">{{ trans('messages.oldPassword') }}</p></div>
                            <div class="col-md-8 col-sm-6 col-6 openItems" id="input_Oldpassword">
                                <input type="password" placeholder="{{ trans('messages.oldPassword') }}" class="form-control" name="oldpass">
                            </div>
                            <div class="col-md-4 col-sm-6 col-6"><p class="text_content openItems">{{ trans('messages.newPassword') }}</p></div>
                            <div class="col-md-8 col-sm-6 col-6 openItems" id="input_password">
                                <input type="password" placeholder="{{ trans('messages.newPassword') }}" class="form-control" name="newpass">
                            </div>
                            <div class="col-md-4 col-sm-6 col-6 openItems"><p class="text_content">{{ trans('messages.confirmPassword') }}</p></div>
                            <div class="col-md-8 col-sm-6 col-6 openItems" id="input_Confirmpassword">
                                <input type="password" placeholder="{{ trans('messages.confirmPassword') }}" class="form-control" name="confirmpass">
                            </div>
                        </div>   
                    </div>
                </form> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('messages.CloseWindow') }}</button>
                <button type="button" class="btn btn-primary" id="btn_editInfo">{{ trans('messages.Editinformation') }}</button>
                <button type="button" class="btn btn-primary" id="btn_submitInfo">{{ trans('messages.SubmitEdit') }}</button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <!-- Contact form JS-->
        <script src="{{asset('assets/mail/jqBootstrapValidation.js')}}"></script>
        <script src="{{asset('assets/mail/contact_me.js')}}"></script>
        <!-- Core theme JS-->
        <script src="{{asset('js/scripts.js')}}"></script>
        <!-- add js -->
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
        <!-- <script src="{{asset('js/Scripts/jquery-migrate-1.2.1.min.js')}}"></script>
        <script src="{{asset('js/Scripts/slick.min.js')}}"></script> -->
        <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                //trang dashboard
                $('#modalRegis').on('shown.bs.modal', function () {
                  $('#modalLogin').modal("hide");
                });
                $('#modalLogin').on('shown.bs.modal', function () {
                  $('#modalRegis').modal("hide");
                });
                $(".pass").click(function(){
                    $("#modalForgotPass").modal("show");
                });
                $('#modalForgotPass').on('shown.bs.modal', function () {
                  $('#modalLogin').modal("hide");
                });
                $(".backFormLogin").click(function(){
                    $("#modalLogin").modal("show");
                });
                $("#inputEmail").keyup(function(){
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    let $url_path = '{!! url('/') !!}';
                    let routeCheckForgot=$url_path+"/checkForgot";
                    let input = $("#inputEmail").val();
                    $.ajax({
                          url:routeCheckForgot,
                          method:"POST",
                          data:{_token:_token,input:input},
                          success:function(data){ 
                            if(input == "")
                            {
                                $("#icon_correct").css("display","none");
                                $("#icon_incorrect").css("display","none");
                                $("#btn_senKey").css("display","none");
                            }
                            else
                            {
                                if(data=="true")
                                {
                                    $("#icon_correct").css("display","block");
                                    $("#btn_senKey").css("display","block");
                                    $("#icon_incorrect").css("display","none");
                                }
                                else if(data="false")
                                {
                                    $("#icon_correct").css("display","none");
                                    $("#btn_senKey").css("display","none");
                                    $("#icon_incorrect").css("display","block");
                                }
                            }
                         }
                    });
                });
                $("#btn_senKey").click(function(){
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    let $url_path = '{!! url('/') !!}';
                    let routeSendKey=$url_path+"/senkey";
                    let input = $("#inputEmail").val();
                    $.ajax({
                          url:routeSendKey,
                          method:"POST",
                          data:{_token:_token,input:input},
                          success:function(data){ 
                            if(data=="true")
                            {
                                $("#inputEmail").attr("readonly","");
                                $("#formCheckKey").css("display","flex");
                            }
                            if(data == "false")
                            {
                                alert("Cannot send email");
                            }
                         }
                    });
                });
                $("#inputKey").keyup(function(){
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    let $url_path = '{!! url('/') !!}';
                    let routeCheckKey=$url_path+"/checkkey";
                    let email = $("#inputEmail").val();
                    let input = $("#inputKey").val();
                    $.ajax({
                          url:routeCheckKey,
                          method:"POST",
                          data:{_token:_token,input:input,email:email},
                          success:function(data){
                            if(input == "")
                            {
                                $("#key_incorrect").css("display","none");
                            }
                            else
                            {
                                if(data=="true")
                                {
                                    $("#changePass").modal("show");
                                    $("#modalForgotPass").modal("hide");
                                }
                                if(data == "false")
                                {
                                    $("#key_incorrect").css("display","block");
                                }
                            }
                         }
                    });
                });
                //hết trang dashboard
                @if(!Auth::check())
                    $("#openModalLogin").click(function(){
                        $("#modalLogin").modal("show");
                    });
                @endif
                // votess star
                @for($i = 1; $i<= 10; $i++)
                  $("#div_Starrank_tour .star_{{$i}}").click(function(){
                      @for($j = 1 ; $j <= 10; $j++)
                          $("#div_Starrank_tour .star_{{$j}}").css("color","#212529");
                      @endfor
                      @for($j = 1 ; $j <= $i; $j++)
                          $("#div_Starrank_tour .star_{{$j}}").css("color","#ff9700");
                      @endfor
                      //console.log($(this).attr("data-value"));
                      $("#star_Share").val($(this).attr("data-value"));
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
                //đổi ngôn ngữ
                $("#Lan_VN").click(function(){
                    let $url_path = '{!! url('/') !!}';
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    let routeLangVN=$url_path+"/langVN";
                    $.ajax({
                          url:routeLangVN,
                          method:"POST",
                          data:{_token:_token},
                          success:function(data){ 
                            location.reload();
                         }
                    });
                });
                $("#Lan_EN").click(function(){
                    let $url_path = '{!! url('/') !!}';
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    let routeLangVN=$url_path+"/langEN";
                    $.ajax({
                          url:routeLangVN,
                          method:"POST",
                          data:{_token:_token},
                          success:function(data){ 
                            location.reload();
                         }
                    });
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#input_File").change(function(){
                    $(".btn_upload").css("background","#ff8304");
                    $("#file_name").css("display","block");
                    $("#file_name").html($("#input_File").val().split('\\').pop());
                });
                $(".lan_title").click(function(){
                    if ($('.lan_more').is(':visible'))
                    {
                        $(".lan_more").slideUp("fast");
                    }
                    else
                    {
                        $(".lan_more").slideDown("fast");
                    }
                });
                $(document).click(function (e)
                {
                    var container = $(".Language");
                    //click ra ngoài đối tượng
                    if (!container.is(e.target) && container.has(e.target).length === 0)
                    {
                        $(".lan_more").slideUp("fast");
                    }
                });
                //your account
                $("#li_person a").click(function(){
                    if ($('#div_person').is(':visible'))
                    {
                        $("#div_person").slideUp("fast");
                    }
                    else
                    {
                        $("#div_person").slideDown("fast");
                    }
                });
                $(document).click(function (e)
                {
                    var container = $("#li_person");
                    //click ra ngoài đối tượng
                    if (!container.is(e.target) && container.has(e.target).length === 0)
                    {
                        $("#div_person").slideUp("fast");
                    }
                });
                $("#p_logout").click(function(){
                    location.replace("{{route('logout')}}");
                });
                //more
                $("#li_more a").click(function(){
                    if ($('#div_more').is(':visible'))
                    {
                        $("#div_more").slideUp("fast");
                    }
                    else
                    {
                        $("#div_more").slideDown("fast");
                    }
                });
                $(document).click(function (e)
                {
                    var container = $("#li_more");
                    //click ra ngoài đối tượng
                    if (!container.is(e.target) && container.has(e.target).length === 0)
                    {
                        $("#div_more").slideUp("fast");
                    }
                });
                $("#btn_Rating").click(function(){
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    let $url_path = '{!! url('/') !!}';
                    let routeRating=$url_path+"/rating";
                    let numberStar = $("#star_Share").val();
                    $.ajax({
                          url:routeRating,
                          method:"POST",
                          data:{_token:_token,numberStar:numberStar,shareId:{{$share->sh_id}}},
                          success:function(data){ 
                            alert("You have successfully evaluated");
                            location.reload();
                         }
                    });
                });
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function(){
                $("#personalInfo").click(function(){
                    $("#personal").modal("show");
                });
                $('#personal').on('show.bs.modal', function (event) {
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    let $url_path = '{!! url('/') !!}';
                    let routeCheckUser=$url_path+"/checkUser";
                    $.ajax({
                          url:routeCheckUser,
                          method:"POST",
                          data:{_token:_token},
                          success:function(data){ 
                            $("#text_email").empty();
                            $("#text_fullName").empty();
                            $("#text_gender").empty();
                            $("#text_age").empty();
                            if(data[5] == false)
                            {
                                $("#default_img").css("display","block");
                                $("#text_img").css("display","none");
                            }
                            else
                            {
                                $("#default_img").css("display","none");
                                $("#text_img").css("display","block");
                                $("#text_img").css("background","url('"+data[0]+"')");
                                $("#text_img").css("background-size","cover");
                                $("#text_img").css("background-repeat","no-repeat");
                            }
                            if(data[6] != "")
                            {
                                $("#text_email").append(data[1]+"<span class='text-danger' style='font-style: italic;'> (Chưa xác minh)</span>");
                            }
                            if(data[6] == "")
                            {
                                $("#text_email").append(data[1]+"<span class='text-success' style='font-style: italic;'> (Đã xác minh)</span>");
                            }
                            $("#text_fullName").append(data[2]);
                            $("#text_gender").append(data[3]);
                            $("#text_age").append(data[4]);
                            //append input
                            $("#input_age input").val(data[4]);
                            $("#input_gender select").val(data[3]);
                            $("#input_fullName input").val(data[2]);
                         }
                    });
                });
                $("#btn_editInfo").click(function(){
                    //ẩn
                    $("#text_fullName").slideUp("fast");
                    $("#text_age").slideUp("fast");
                    $("#text_gender").slideUp("fast");
                    //hiện
                    $(".openChangePass").css('display','block');
                    $("#btn_submitInfo").css("display","block");
                    $(".btn_upload").slideDown("fast");
                    $("#input_age").slideDown("fast");
                    $("#input_gender").slideDown("fast");
                    $("#input_fullName").slideDown("fast");
                    $("#btn_editInfo").css("display","none");
                });
                $(".btn_upload").click(function(){
                    $("#input_File").click();
                });
                $("#btn_submitInfo").click(function(){
                    $("#formFixInfor").submit();
                });
                $(".openClickHere").click(function(){
                    $(".openItems").css("display","flex");
                    $(".openChangePass").css("display","none");
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
                                        $(".imgPlace").append("<a data-fancybox='gallery' href='{{asset('imgPlace/empty.png')}}'> <img class='img-fluid' src='{{asset('imgPlace/empty.png')}}' alt='' style='width: 70%' title='location with no photo'></a>");
                                    }
                                    $("#short").empty();
                                    $("#description").empty();
                                    if(data[4] != "")
                                        $("#short").append(data[4]);
                                    else
                                        $("#short").append('<span class="badge badge-warning">Not available</span>');

                                    if(data[5] != "")
                                        $("#description").append(data[5]);
                                    else
                                        $("#description").append('<span class="badge badge-warning">Not available</span>');  

                                    $("#timeAvg").html(parseFloat(data[6])/60/60+" hours");
                                    $("#linkMap").empty();
                                    if(data[7] != null)
                                        $("#linkMap").append('<a href="'+data[7]+'" target="_blank">Link here</a>');
                                    else
                                        $("#linkMap").append('<span class="badge badge-warning">Not available</span>');
                                    $("#linkvr").empty();
                                    if(data[8] != null)
                                        $("#linkvr").append('<a href="'+data[8]+'" target="_blank">Link here</a>');
                                    else
                                        $("#linkvr").append('<span class="badge badge-warning">Not available</span>');
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
                                    $(".imgPlace").append("<a data-fancybox='gallery' href='{{asset('imgPlace/empty.png')}}'> <img class='img-fluid' src='{{asset('imgPlace/empty.png')}}' alt='' style='width: 70%' title='location with no photo'></a>");
                                }
                                $("#short").empty();
                                $("#description").empty();
                                if(data[4] != "")
                                    $("#short").append(data[4]);
                                else
                                    $("#short").append('<span class="badge badge-warning">Not available</span>');

                                if(data[5] != "")
                                    $("#description").append(data[5]);
                                else
                                    $("#description").append('<span class="badge badge-warning">Not available</span>');  

                                $("#timeAvg").html(parseFloat(data[6])/60/60+" hours");
                                $("#linkMap").empty();
                                if(data[7] != null)
                                    $("#linkMap").append('<a href="'+data[7]+'" target="_blank">Link here</a>');
                                else
                                    $("#linkMap").append('<span class="badge badge-warning">Not available</span>');
                                $("#linkvr").empty();
                                if(data[8] != null)
                                    $("#linkvr").append('<a href="'+data[8]+'" target="_blank">Link here</a>');
                                else
                                    $("#linkvr").append('<span class="badge badge-warning">Not available</span>');
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
                        alert("Geocode was not successful for the following reason: " + status);
                      }
                    });
                };
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgbjwIY5Q1eZ-Ejqur0a8avEQWowfA39s&callback=initMap" async defer></script>
    </body>
</html>
