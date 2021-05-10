<style>
    .box-header:hover a{
        color: white;
        text-decoration: none;
    }
    .box-place{
        padding: 0;
    }
    .box-place a{
        padding: .5rem .6rem;
        display: block;
    }
</style>
<div class="fixed-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg text-uppercase change-background" id="mainNav">
        <div class="container">
            <a style="color: #497689;" class="navbar-brand js-scroll-trigger" href="{{route('login')}}">Tour Advice</a>
            <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item mx-0 mx-lg-1">
                        <a style="color: #497689;" class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{route('login')}}" id="header_starttour">{{ trans('messages.Home') }}</a>
                    </li>
                    <li class="nav-item mx-0 mx-lg-1">
                        <a style="color: #497689;" class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{route('tour')}}" id="header_tour">{{ trans('messages.Tour') }}</a>
                    </li>
                    <li class="nav-item mx-0 mx-lg-1">
                        <a style="color: #497689;" class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{route('place')}}" id="header_place">{{ trans('messages.Places') }}</a>
                    </li>
                    @if(Auth::check())
                    <li class="nav-item mx-0 mx-lg-1" id="li_more">
                        <a style="color: #497689;" class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#">{{ trans('messages.More') }} <i class="fas fa-sort-down"></i></a>
                        <div id="div_more">
                            <a style="color: #497689;" class="nav-link py-3 px-0 px-lg-3  js-scroll-trigger" href="{{route('about')}}" id="header_about">{{ trans('messages.About') }}</a>
                            <a style="color: #497689;" class="nav-link py-3 px-0 px-lg-3  js-scroll-trigger" href="{{route('feedback')}}" id="header_feedback">{{ trans('messages.sendFeedback') }}</a>
                        </div>
                    </li>
                    @else
                    <li class="nav-item mx-0 mx-lg-1">
                        <a style="color: #497689;" class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{route('about')}}" id="header_about">{{ trans('messages.About') }}</a>
                    </li>
                    @endif

                    @if(Auth::check())
                    <?php $user = Auth::user();?>
                    <li class="nav-item mx-0 mx-lg-1" id="li_person">
                        <a style="color: #497689;" class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#">{{ $user->us_fullName }} <i class="fas fa-sort-down"></i></a>
                        <div id="div_person">
                            @if($user->us_type == "1")
                                <p id="comback_admin">{{ trans('messages.adminPage') }}</p>
                            @endif
                            <p id="personalInfo">{{ trans('messages.Aboutyou') }}</p>
                            <p id="p_logout">{{ trans('messages.Logout') }}</p>
                        </div>
                    </li>
                    @else
                    <li class="nav-item mx-0 mx-lg-1">
                        <a style="color: #497689;" class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#" id="openModalLogin">{{ trans('messages.Login') }}</a>
                    </li>
                    @endif
                    <li class="nav-item mx-0 mx-lg-1 show_btn">
                        <a style="color: #497689;" class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#"><i class="fas fa-angle-double-down"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <nav class="nav_more">
        <div class="container-fuild child_header">
            <div id="div_search">
                <input type="text" class="navbar-brand js-scroll-trigger form-control" placeholder="{{ trans('messages.searchPlace') }}" id="searchPlace">
                <div class="result_search">
                    <ul>
                    </ul>
                </div>
            </div>
            
            <div class="box-header" id="searchTour">
                <i class="fas fa-search"></i> {{ trans('messages.searchTour') }}
            </div>
            <?php 
                use App\Models\TypePlace; 
                use App\Models\Langtype;
                $randomType = TypePlace::where("status","<>","1")->where('totalPlace','<>','0')->inRandomOrder()->limit(3)->get();
                foreach ($randomType as $value) {
                    if(Session::has('website_language') && Session::get('website_language') == "vi")
                    {
                        $findLang = Langtype::select("nametype")->where("language","vn")->where("type_id",$value->id)->first();
                        $value['nametype'] = $findLang->nametype;
                    }
                    else
                    {
                        $findLang = Langtype::select("nametype")->where("language","en")->where("type_id",$value->id)->first();
                        $value['nametype'] = $findLang->nametype;
                    }
                }
            ?>
            @foreach($randomType as $value)
            <div class="box-header box-place">
                <a href="{{route('listPlaceForType',$value->id)}}"><i class="fas fa-map-marker-alt"></i> {{$value->nametype}}</a>
            </div>
            @endforeach
            <div class="box-header" id="seeFeedback">
                <i class="fas fa-comments"></i> {{ trans('messages.Feedback') }}
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