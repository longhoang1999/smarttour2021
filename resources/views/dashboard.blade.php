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
                        <li class="nav-item mx-0 mx-lg-1" id="li_person">
                            <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#">{{ trans('messages.Youraccount') }} <i class="fas fa-sort-down"></i></a>
                            <div id="div_person">
                                <?php 
                                    use Illuminate\Support\Facades\Auth;
                                    $user = Auth::user();
                                ?>
                                @if($user->us_type == "1")
                                    <p id="comback_admin">{{ trans('messages.adminPage') }}</p>
                                @endif
                                <p id="personalInfo">{{ trans('messages.Aboutyou') }}</p>
                                <p id="p_logout">{{ trans('messages.Logout') }}</p>
                            </div>
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
                        <div class="portfolio-item mx-auto" data-toggle="modal" data-target="#portfolioModal2">
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
                    <h3 class="font-weight-bold text-center text-uppercase">--- highly rated tours ---</h3>
                    </div>
                </div>
                <div class="row justify-content-center tourPlace" role="toolbar">
                    <?php $i=1; ?>
                    @foreach($des as $value)
                    <div class="col-md-6 col-lg-4 mb-5 downPlace">
                        <div class="portfolio-item mx-auto" data-toggle="modal" data-target="#placeModal{{$i}}">
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
        <!-- Portfolio Modal 2-->
        <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-labelledby="portfolioModal2Label" aria-hidden="true">
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
                                    <h2 class="portfolio-modal-title text-secondary text-uppercase mb-0" id="portfolioModal2Label">{{ trans('messages.Previoustours') }}</h2>
                                    <!-- Icon Divider-->
                                    <div class="divider-custom">
                                        <div class="divider-custom-line"></div>
                                        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
                                        <div class="divider-custom-line"></div>
                                    </div>
                                    <!-- Portfolio Modal - Image-->
                                    <img class="img-fluid rounded mb-5" src="{{asset('assets/img/portfolio/history.jpg')}}" alt="" id="turnOffMap" />
                                    <div class="img-fluid rounded mb-5" id="map">
                                        
                                    </div>
                                    <!-- Portfolio Modal - Text-->
                                    <p>
                                        {{ trans('messages.historyTitle') }}</p>
                                    <small>{{ trans('messages.routeDetails') }}</small>
                                    <small>-- (Double click to edit the route) </small>
                                    <p class="mb-5">
                                        <?php use App\Models\Destination;
                                              use App\Models\Language;
                                              $route = Session::get('route');
                                         ?>
                                        @if ( count($route) > 0 )
                                            @foreach($route as $value)
                                                <?php 
                                                    if($value->to_startLocat == "")
                                                    {
                                                       $des_1 = ""; 
                                                    }
                                                    else $des_1 = trans('messages.startLocation')."--";
                                                    $pieces = explode("-", $value->to_des);
                                                    for ($i=0; $i < count($pieces)-1; $i++) { 
                                                        if(Session::has('website_language') && Session::get('website_language') == "vi")
                                                        {
                                                            $lang = Language::where("language","vn")->where("des_id",$pieces[$i])->first();
                                                        }
                                                        else
                                                        {
                                                            $lang = Language::where("language","en")->where("des_id",$pieces[$i])->first();
                                                        }
                                                        $des_1 = $des_1.$lang->de_name.'--';
                                                    }
                                                 ?>
                                                 <!-- <i class="fas fa-street-view point"></i> -->
                                                <p style="font-family: auto" class="lead text-center tour" data-id="{{$value->to_id}}">
                                                    <span style="font-style: italic;font-weight: bold;">{{$value->to_name}}: </span>{{$des_1}} - 
                                                    Start day: {{date('d/m/Y', strtotime($value->to_startDay))}}
                                                </p>
                                            @endforeach
                                        @else
                                        <p class="lead text-center">
                                            {{ trans('messages.notTrips') }}
                                        </p>
                                        @endif
                                    </p>
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
        <!-- Place Modal 3-->
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
                $("#comback_admin").click(function(){
                    location.replace("{{route('admin.generalInfor')}}");
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
                $("#StarttourNow").click(function(){
                    location.replace("{{route('user.maps')}}");
                });
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
            $(".tour").dblclick(function(){
                let $url_path = '{!! url('/') !!}';
                let routeEditTour = $url_path+"/editTourUser/"+$(this).attr("data-id");
                window.open(routeEditTour, '_blank');
                
            });
        </script>
        <!-- map -->
        <script type="text/javascript">
            var locationsdata = [];
            var locatsList =[];
            var locats = [];
            var allRoutePosible = [];
            var dello = 0;
            var markersArray=[];

            var labelName = [];
            var polylines = [];
            var starlocat;
            const colorlist = ['#418bca','#00bc8c','#f89a14','#ef6f6c','#5bc0de','#811411'];
            function initMap(){
                var map = new google.maps.Map(document.getElementById("map"), {
                      zoom: 12.5,
                      center: { lat: 21.0226586, lng: 105.8179091 },
                    }),
                directionsService = new google.maps.DirectionsService();
                $(".tour").click(function(){
                    $("#turnOffMap").css("display","none");
                    $("#map").css("display","block");
                    let $url_path = '{!! url('/') !!}';
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    let routeCheckTour=$url_path+"/checkTour";
                    let inputLink = $(this).attr("data-id");
                    $.ajax({
                          url:routeCheckTour,
                          method:"POST",
                          data:{_token:_token,inputLink:inputLink},
                          success:function(data){ 
                            //console.log(data);
                            if(data[2] != "")
                            {
                                data[2].lat = parseFloat(data[2].lat);
                                data[2].lng = parseFloat(data[2].lng);
                                data[0].unshift(data[2]);
                                data[1].unshift("{{ trans('messages.startLocation')}}");

                            }
                            // console.log(data[0]);
                            // console.log(data[1]);
                            drawRoutes(data[0],data[1]);
                         }
                    });
                });
                function drawRoutes(locats,labelName){
                    markersOnMap(locats,labelName);
                    var waypts = [];
                    for(var i=1; i<locats.length; i++)
                      waypts.push({
                        location: locats[i],
                        stopover: true
                      });
                    directionsService.route({
                        origin: locats[0],
                        destination: locats[locats.length-1],
                        waypoints: waypts,
                        travelMode: google.maps.TravelMode.DRIVING,
                    },customDirectionsRenderer);
                }

                function customDirectionsRenderer(response, status) {
                    var bounds = new google.maps.LatLngBounds();
                    var legs = response.routes[0].legs;

                    for(var i=0;i<polylines.length;i++){
                      polylines[i].setMap(null);
                    }
                    for (i = 0; i < legs.length; i++) {
                      (i>=5&&i%5 == 0)?index = 4:((starlocat != undefined)?index = (i%5)-1:index = (i%5));
                      if(starlocat != undefined && i==0) index = 5;
                       var polyline = new google.maps.Polyline({
                        map:map, 
                        path:[], 
                        strokeColor: colorlist[index],
                        strokeOpacity: 0.7,
                        strokeWeight: 5});
                      var steps = legs[i].steps;
                      for (j = 0; j < steps.length; j++) {
                        var nextSegment = steps[j].path;
                        for (k = 0; k < nextSegment.length; k++) {
                          polyline.getPath().push(nextSegment[k]);
                          bounds.extend(nextSegment[k]);
                        }
                      }
                      polylines.push(polyline);
                    }
                    map.fitBounds(bounds);
                    //getandsettimeline(response.routes[0].legs);
                };

                  //Draw marker on map
                function markersOnMap(locats,labelName){console.log(locats);  
                    //clear marker 
                    
                    for(var i =0 ; i<markersArray.length;i++){
                      markersArray[i].setMap(null);
                    }
                    markersArray = []; 

                    //create new marker
                    for(i=0; i<locats.length;i++){
                        console.log(locats[i]);
                      addMarkers(locats[i],i,labelName[i]);
                    }
                }
                function addMarkers(locats,index,labelName){
                    console.log(index);
                    var icon = {
                      path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
                      fillColor: colorlist[index%5],
                      fillOpacity: 1,
                      strokeColor: 'white',
                      strokeWeight: 3,
                      scale: 1.4,
                    },
                      label = {
                        text: labelName,
                        color: colorlist[index%5],
                        fontWeight: 'bold'  
                    };
                    var marker = new google.maps.Marker({
                          map: map,
                          position: locats,
                          label:label, 
                          icon: icon
                    });

                    var content = '<p><h4>AAAAAAAAAAA</h4></p>'+ 
                          '<p><a href="'+'"target="_blank">Click to view tour</a></p>',
                          infowindow = new google.maps.InfoWindow({
                            content: content,
                          });

                    marker.addListener('click',()=>{
                        marker.setIcon("{{asset('imgs/icon.jpg')}}");
                        marker.setLabel('');
                        infowindow.open(map, marker);
                    });

                    infowindow.addListener('closeclick',()=>{
                        marker.setIcon(icon);
                        marker.setMap(map);
                        marker.setLabel(label);
                    });

                    markersArray.push(marker);
                }
            };
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgbjwIY5Q1eZ-Ejqur0a8avEQWowfA39s&callback=initMap" async defer></script>
    </body>
</html>
