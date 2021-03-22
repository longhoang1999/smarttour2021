<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="author" content="" />
        <title>
            @section('title')
                | TOUR ADVICE
            @show
        </title>
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
        <!--page level css-->
            @yield('header_styles')
        <!--end of page level css-->
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
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{url('/#introduce')}}">Tour</a></li>
                        <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{url('/#place')}}">{{ trans('messages.Places') }}</a></li>
                        @if(Auth::check())
                            <li class="nav-item mx-0 mx-lg-1" id="li_more">
                                <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#">{{ trans('messages.More') }} <i class="fas fa-sort-down"></i></a>
                                <div id="div_more">
                                    <a class="nav-link py-3 px-0 px-lg-3  js-scroll-trigger" href="{{url('/#about')}}" id="p_about">{{ trans('messages.About') }}</a>
                                    <a class="nav-link py-3 px-0 px-lg-3  js-scroll-trigger" href="{{url('/#contact')}}" id="p_feedback">{{ trans('messages.Feedback') }}</a>
                                </div>
                            </li>
                        @else
                            <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{url('/#about')}}">{{ trans('messages.About') }}</a></li>
                        @endif
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
        @yield('content')
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal login -->
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
        <script type="text/javascript" src="{{ asset('datatables/js/jquery.dataTables.js') }}" ></script>
        <script type="text/javascript" src="{{ asset('datatables/js/dataTables.bootstrap4.js') }}" ></script>
        <script type="text/javascript">
            $(document).ready(function(){
                @if(!Auth::check())
                    $("#openModalLogin").click(function(){
                        $("#modalLogin").modal("show");
                    });
                @endif
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
                //hết trang dashboard\
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
        @yield('footer-js')