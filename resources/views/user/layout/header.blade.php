<div class="fixed-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg bg-secondary text-uppercase " id="mainNav">
        <div class="container">
            <a class="navbar-brand js-scroll-trigger" href="{{route('login')}}">Tour Advice</a>
            <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{route('login')}}" id="header_starttour">{{ trans('messages.StartTour') }}</a></li>
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{route('tour')}}" id="header_tour">Tour</a></li>
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{route('place')}}" id="header_place">{{ trans('messages.Places') }}</a></li>
                    @if(Auth::check())
                    <li class="nav-item mx-0 mx-lg-1" id="li_more">
                        <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#">{{ trans('messages.More') }} <i class="fas fa-sort-down"></i></a>
                        <div id="div_more">
                            <a class="nav-link py-3 px-0 px-lg-3  js-scroll-trigger" href="{{route('about')}}" id="header_about">{{ trans('messages.About') }}</a>
                            <a class="nav-link py-3 px-0 px-lg-3  js-scroll-trigger" href="{{route('feedback')}}" id="header_feedback">Send feedback</a>
                        </div>
                    </li>
                    @else
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{route('about')}}" id="header_about">{{ trans('messages.About') }}</a></li>
                    @endif

                    @if(Auth::check())
                    <li class="nav-item mx-0 mx-lg-1" id="li_person">
                        <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#">{{ trans('messages.Youraccount') }} <i class="fas fa-sort-down"></i></a>
                        <div id="div_person">
                            <?php $user = Auth::user();?>
                            @if($user->us_type == "1")
                                <p id="comback_admin">{{ trans('messages.adminPage') }}</p>
                            @endif
                            <p id="personalInfo">{{ trans('messages.Aboutyou') }}</p>
                            <p id="p_logout">{{ trans('messages.Logout') }}</p>
                        </div>
                    </li>
                    @else
                    <li class="nav-item mx-0 mx-lg-1"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#" id="openModalLogin">Login</a></li>
                    @endif
                    <li class="nav-item mx-0 mx-lg-1 show_btn"><a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#"><i class="fas fa-angle-double-down"></i></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <nav class="nav_more">
        <div class="container-fuild child_header">
            <div id="div_search">
                <input type="text" class="navbar-brand js-scroll-trigger form-control" placeholder="Search place" id="searchPlace">
                <div class="result_search">
                    <ul>
                    </ul>
                </div>
            </div>
            
            <div class="box-header" id="searchTour">
                <i class="fas fa-search"></i> Search Tour
            </div>
            <div class="box-header" id="searchHotel">
                <i class="fas fa-building"></i> Hotel
            </div>
            <div class="box-header" id="searchRestaurant">
                <i class="fas fa-utensils"></i> Restaurant
            </div>
            <div class="box-header" id="searchScenicspots">
                <i class="fab fa-redhat"></i> Scenic spots
            </div>
            <div class="box-header" id="seeFeedback">
                <i class="fas fa-comments"></i> Feedback
            </div>
            <div class="Language">
                <div class="lan_title">
                    <span>{{ trans('messages.lang') }}</span><i class="fas fa-caret-down"></i>
                </div>
                <div class="lan_more">
                    <p class="lan_vn" id="Lan_VN">VN</p>
                    <p class="lan_en" id="Lan_EN">EN</p>
                </div>
            </div>
            <div class="up_btn">
                <i class="fas fa-angle-double-up"></i>
            </div>
            
        </div>
    </nav>
</div>