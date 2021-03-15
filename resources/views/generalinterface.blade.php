<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="author" content="" />
        <title>Tour Advice</title>
        <link rel="shortcut icon" href="{{asset('images/icons/favicon.ico')}}" type="image/png">
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
        <!-- css notlogin -->
        <link rel="stylesheet" href="{{asset('css/notlogin.css')}}">
        <style>
            .hightly_div_child{width: 90%; margin: auto;position: relative; cursor: pointer;}
            .hightly_div_child img{width: 100%;height: 12rem;}
            .hightly_div_child .tourContent{
                position: absolute;
                bottom: -16px;
                background: rgba(0,0,0,.6);
                width: 100%;
                text-align: center;
                line-height: 3em;
                font-weight: 700;
            }
            p.tourName {
                font-size: 19px;
                font-weight: bold;
                padding-left: 1rem;
            }
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="#page-top">Tour Advice</a>
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
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#portfolio">{{ trans('messages.StartTour') }}</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#introduce">{{ trans('messages.Introduce') }}</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#place">{{ trans('messages.Places') }}</a></li>
                        <li class="nav-item mx-0 mx-lg-1" id="li_more">
                            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#">{{ trans('messages.More') }} <i class="fas fa-sort-down"></i></a>
                            <div id="div_more">
                                <a class="nav-link py-3 px-0 px-lg-3  js-scroll-trigger" href="#about" id="p_about">{{ trans('messages.About') }}</a>
                                <a class="nav-link py-3 px-0 px-lg-3  js-scroll-trigger" href="#contact" id="p_feedback">{{ trans('messages.Feedback') }}</a>
                            </div>
                        </li>
                        <li class="nav-item mx-0 mx-lg-1">
                            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" id="titleLogin" href="#">{{ trans('messages.Login') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead bg-primary text-white text-center">
            <div class="slider autoplay" role="toolbar">
                <div style="background: url('{{asset('imgs/2.jpg')}}');background-size: cover;background-repeat: no-repeat;"></div>
                <div style="background: url('{{asset('imgs/1.jpg')}}');background-size: cover;background-repeat: no-repeat;"></div>
                <div style="background: url('{{asset('imgs/3.jpg')}}');background-size: cover;background-repeat: no-repeat;"></div>
                <div style="background: url('{{asset('imgs/4.jpg')}}');background-size: cover;background-repeat: no-repeat;"></div>
                <div style="background: url('{{asset('imgs/5.jpg')}}');background-size: cover;background-repeat: no-repeat;"></div>
                <div style="background: url('{{asset('imgs/6.jpg')}}');background-size: cover;background-repeat: no-repeat; opacity: 0.5"></div>
            </div>
            <div class="container d-flex align-items-center flex-column" id="div_Logo">
                <div>
                    <h1 class="masthead-heading text-uppercase mb-0" style="text-shadow: 0 0 12px gray;">Tour Advice</h1>
                    <!-- Icon Divider-->
                    <div class="divider-custom divider-light">
                        <div class="divider-custom-line"></div>
                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                        <div class="divider-custom-line"></div>
                    </div>
                    <!-- Masthead Subheading-->
                    <p class="masthead-subheading font-weight-light mb-0">{{ trans('messages.GiveTheBestAdvice') }}</p>
                </div>
            </div>
        </header>
        <!-- Portfolio Section-->
        <section class="page-section portfolio" id="portfolio">
            <div class="container">
                <!-- Portfolio Section Heading-->
                <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">{{ trans('messages.StartTitle') }}</h2>
                <!-- Icon Divider-->
                <div class="divider-custom">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- Portfolio Grid Items-->
                <div class="row justify-content-center">
                    <!-- Portfolio Item 1-->
                    <div class="col-md-6 col-lg-4 mb-5">
                        <div class="portfolio-item mx-auto" id="StarttourNow">
                            <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                            </div>
                            <img class="img-fluid" src="{{asset('assets/img/portfolio/cabin.png')}}" alt="" />
                        </div>
                    </div>
                    <!-- Portfolio Item 2-->
                    <div class="col-md-6 col-lg-4 mb-5">
                        <div class="portfolio-item mx-auto" data-toggle="modal" data-target="#modalLogin">
                            <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                            </div>
                            <img class="img-fluid" src="{{asset('assets/img/portfolio/history.jpg')}}" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- introduce Section-->
        <section class="page-section bg-primary text-white mb-0" id="introduce">
            <div class="container">
                <!-- About Section Heading-->
                <h2 class="page-section-heading text-center text-uppercase text-white">{{ trans('messages.Introduce') }}</h2>
                <!-- Icon Divider-->
                <div class="divider-custom divider-light">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- About Section Content-->
                <div class="row">
                    <div class="col-lg-12 ml-auto"><p class="lead text-center">{{ trans('messages.introduceContent') }}</p>
                    <h3 class="font-weight-bold text-center text-uppercase" style="margin: 6rem 0;">--- highly rated tours ---</h3>
                    </div>
                </div>
                <?php use App\Models\Route; ?>
                <div class="row justify-content-center">
                    @foreach($shareTour as $value)
                        <div class="col-md-4 col-sm-6 col-12 hightly_div mb-5">
                            <?php $route = Route::where("to_id",$value->sh_to_id)->first(); ?>
                            <p class="tourName">{{$route->to_name}}</p>
                            <div class="hightly_div_child">
                                <p class="tourContent">{{$value->number_star}} <i class="fas fa-star text-warning"></i> - {{$value->numberReviews}} votes</p>
                                @if($value->image != "")
                                    <img src="{{asset($value->image)}}" alt="" class="img_open_model{{$value->sh_id}}">
                                @else
                                    <img src="{{asset('imgPlace/empty.png')}}" alt="" title="location with no photo" class="img_open_model{{$value->sh_id}}"/>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        <!-- Modal -->
        <?php use App\Models\Destination; use Illuminate\Support\Arr; use App\Models\Language;?>
        @foreach($shareTour as $value)
            <div class="modal fade" id="modal_{{$value->sh_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tour name: {{$route->to_name}}</h5>
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
                            $start = "Not available";
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
                                <p class="font-weight-bold font-italic">Start Location</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{$start}}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">Location</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{$detailLocation}}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">Time start</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{date('h:i a', strtotime($route->to_starttime))}}</p>
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">Time end</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                @if($route->to_endtime != "")
                                    <p>{{date('h:i a', strtotime($route->to_endtime))}}</p>
                                @else
                                    <span class="badge badge-warning">Not available</span>
                                @endif
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">Comeback</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                @if($route->to_comback == "0")
                                    <span class="badge badge-warning">No</span>
                                @else
                                    <span class="badge badge-success">Yes</span>
                                @endif
                            </div>
                            <div class="col-md-4 col-sm-6 col-12">
                                <p class="font-weight-bold font-italic">Date created</p>
                            </div>
                            <div class="col-md-8 col-sm-6 col-12">
                                <p>{{date('d/m/Y', strtotime($route->to_startDay))}}</p>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning">Rating</button>
                    <a href="#" class="btn btn-success" target="_blank">View tour</a>
                  </div>
                </div>
              </div>
            </div>
        @endforeach
        <!-- introduce Section-->
        <!-- place Section-->
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
                            <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                                <div class="portfolio-item-caption-content text-center text-white"><i class="fas fa-plus fa-3x"></i></div>
                            </div>
                            <p class="lead">{{$value->de_name}}</p>
                            @if($value->de_image != "")
                            <img class="img-fluid" src="{{asset($value->de_image)}}" alt="" title="location with no photo" />
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
        <!-- /place Section -->
        <!-- About Section-->
        <section class="page-section bg-primary text-white mb-0" id="about">
            <div class="container">
                <!-- About Section Heading-->
                <h2 class="page-section-heading text-center text-uppercase text-white">{{ trans('messages.About') }}</h2>
                <!-- Icon Divider-->
                <div class="divider-custom divider-light">
                    <div class="divider-custom-line"></div>
                    <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                    <div class="divider-custom-line"></div>
                </div>
                <!-- About Section Content-->
                <div class="row">
                    <div class="col-lg-4 ml-auto"><p class="lead">{{ trans('messages.Aboutleft') }}</p></div>
                    <div class="col-lg-4 mr-auto"><p class="lead">{{ trans('messages.Aboutright') }}</p>
                    <p class="lead" style="font-style: italic;">Tel: 0327927587</p>
                    <p class="lead" style="font-style: italic;">Email: longhoanghai8499@gmail.com</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Contact Section-->
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
                        <!-- To configure the contact form email address, go to mail/contact_me.php and update the email address in the PHP file on line 19.-->
                        <form name="sentMessage" method="post" id="formFeedback">
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
                        @if ($message = Session::get('notification'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">x</button>
                                <strong>{{$message}}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
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
        <!-- Portfolio Modals-->
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

        <?php $i=1; ?>
        @foreach($des as $value)
        <div class="portfolio-modal modal fade" id="placeModal{{$i}}" tabindex="-1" role="dialog" aria-labelledby="portfolioModal3Label" aria-hidden="true">
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
                                        <p class="mb-5"><span class="font-weight-bold">{{ trans('messages.Shortdescription') }}:</span> {{$value->de_shortdes}}</p>
                                    @else
                                        <p class="mb-5"><span class="font-weight-bold">{{ trans('messages.Shortdescription') }}:</span> {{ trans('messages.NoInformation') }}</p>
                                    @endif
                                    <!-- de_description-->
                                    @if($value->de_description != "")
                                        <p class="mb-5"><span class="font-weight-bold">{{ trans('messages.Description') }}:</span> {{$value->de_description}}</p>
                                    @else
                                        <p class="mb-5"><span class="font-weight-bold">{{ trans('messages.Description') }}:</span> {{ trans('messages.NoInformation') }}</p>
                                    @endif
                                    <!-- de_duration -->
                                    @if($value->de_duration != "")
                                        <p class="mb-5"><span class="font-weight-bold">{{ trans('messages.Averagetraveltime') }}:</span> {{floatval($value->de_duration)/60/60}} {{ trans('messages.minutes') }}</p>
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
        <?php $i++; ?>
        @endforeach

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
    <!-- Change pass -->
    <div class="modal fade" id="changePass" tabindex="-1" role="dialog" aria-labelledby="changePassLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="changePassLabel">{{trans('messages.Notification')}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="text-success">{{trans('messages.defaultPass')}}</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('messages.CloseWindow')}}</button>
          </div>
        </div>
      </div>
    </div>  
        <!-- Bootstrap core JS-->
        
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
                @foreach($shareTour as $value)
                    $(".img_open_model{{$value->sh_id}}").click(function(){
                        $("#modal_{{$value->sh_id}}").modal("show");
                    });
                @endforeach


                $(".hightly_div_child").hover(function(){
                    $(this).css("box-shadow","0px 1px 20px 11px white");
                }); 
                $(".hightly_div_child").mouseleave(function(){
                    $(this).css("box-shadow","none");
                }); 
                $("#StarttourNow").click(function(){
                    location.replace("{{route('user.maps')}}");
                });
                //quên pass
                $(".pass").click(function(){
                    $("#modalForgotPass").modal("show");
                });
                $('#modalForgotPass').on('shown.bs.modal', function () {
                  $('#modalLogin').modal("hide");
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
                //hết quên mk
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
                });
                $('.tourPlace').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 2000,
                    prevArrow: false,
                    nextArrow: false,
                    dots: false,
                    fade: false
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
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#modalRegis').on('shown.bs.modal', function () {
                  $('#modalLogin').modal("hide");
                });
                $('#modalLogin').on('shown.bs.modal', function () {
                  $('#modalRegis').modal("hide");
                });
                $(".backFormLogin").click(function(){
                    $("#modalLogin").modal("show");
                });
                @if ($message = Session::get('error'))
                    $("#modalLogin").modal("show");
                @endif
                @if ($message = Session::get('success'))
                    $("#modalLogin").modal("show");
                @endif
                $("#formFeedback").submit(function(e){
                    e.preventDefault();
                    $("#modalLogin").modal("show");
                });
                $("#titleLogin").click(function(){
                    $("#modalLogin").modal("show");
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
            });
        </script>

        <script src="{{asset('vendor/animsition/js/animsition.min.js')}}"></script>
        <script src="{{asset('vendor/bootstrap/js/popper.js')}}"></script>

        <script src="{{asset('vendor/select2/select2.min.js')}}"></script>
        <script src="{{asset('vendor/daterangepicker/moment.min.js')}}"></script>
        <script src="{{asset('vendor/daterangepicker/daterangepicker.js')}}"></script>
        <script src="{{asset('vendor/countdowntime/countdowntime.js')}}"></script>
    </body>
</html>
