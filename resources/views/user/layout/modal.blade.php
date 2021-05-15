<!-- modal notice -->
<div class="modal fade" id="modalNotice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ trans('messages.Notification') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @if ($message = Session::get('error'))
            <!-- <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{{$message}}</strong>
            </div> -->
            <p class="text-danger font-weight-bold">{{$message}}</p>
        @endif
        @if ($message = Session::get('success'))
            <!-- <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>{{$message}}</strong>
            </div> -->
            <p class="text-success font-weight-bold">{{$message}}</p>
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
      </div>
    </div>
  </div>
</div>
<!-- /modal notice -->
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
                                <div class="txt_field">
                                    <input type="email" name="us_email" required="">
                                    <span></span>
                                    <label>Email</label>
                                </div>
                                <div class="txt_field">
                                    <input type="password" name="us_password" required="" maxlength="32">
                                    <span></span>
                                    <label>Password</label>
                                </div>

                                <div class="div_submit">
                                    <input type="submit" value="{{ trans('messages.Login') }}"> 
                                    <input type="button" id="btn_register" data-toggle="modal" data-target="#modalRegis" value="{{ trans('messages.Registration') }}">
                                </div>      
                                <div class="btn-loginFB">
                                    <a href="{{url('/getInfo-facebook/facebook')}}" id="link_login">
                                        <i class="fab fa-facebook-square"></i>
                                        Login with Facebook
                                    </a>
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
<!-- /modal Login -->
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
                            <option value="Male">{{ trans('messages.Male') }}</option>
                            <option value="Female">{{ trans('messages.Female') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 col-6 mb-3">
                        <p class="text-left font-weight-bold">{{ trans('messages.Age') }}</p>
                    </div>
                    <div class="col-md-9 col-sm-6 col-6 mb-3">
                        <input type="number" class="form-control" placeholder="{{ trans('messages.Age') }}" name="age" required="" min="1" max="100">
                    </div>
                </div>
            </div>
            <hr>
            <div class="div_btn_register">
                <input type="submit" class="btn btn-primary" value="{{ trans('messages.Registration') }}">
                <p id="p_backLogin">{{ trans('messages.youHaveAcc') }} <span class="backFormLogin">{{ trans('messages.Login') }}</span></p>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- modal regis -->
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
<!-- /modal Forgot pass -->
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
    </div>
  </div>
</div>  
<!-- /Modal Change pass -->
<!-- modal personal -->
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
                        <input type="number" placeholder="Enter your age" class="form-control" name="age" min="0" max="100">
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
        <button type="button" class="btn btn-primary" id="btn_editInfo">{{ trans('messages.Editinformation') }}</button>
        <button type="button" class="btn btn-primary" id="btn_submitInfo">{{ trans('messages.SubmitEdit') }}</button>
      </div>
    </div>
  </div>
</div>
<!-- /modal personal -->