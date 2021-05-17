<link rel="stylesheet" href="{{asset('css/modallayoutmap.css')}}">  
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
@if(!isset($justview))
<!-- total cost -->
<div class="modal fade" id="totalCostModal" tabindex="-1" role="dialog" aria-labelledby="totalCostModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="totalCostModalLabel">Chi phí cả tour</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label for="inputTotalCost">Ước lượng tổng chi phí mà bạn có thể chi:</label>
            <div class="block_input_cost">
                <select class="form-control currency">
                    <option selected="true" value="VNĐ">VNĐ</option>
                    <option value="USD">USD</option>
                </select>
                <input type="number" class="form-control" id="inputTotalCost" placeholder="Total cost">
                <span class="show_yourCost"> -{{ trans('admin.youEntered') }} <span class="show_money"></span></span>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
@endif
<!-- map -->
<div class="modal fade" id="enterNameTour" tabindex="-1" role="dialog" aria-labelledby="enterNameTourLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enterNameTourLabel">{{ trans('messages.SaveTour') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="content_modal">
                <div class="content_modal_left">
                </div>
                <form method="post" id="upload_form" enctype="multipart/form-data" action="">
                    <div class="modal-body pb-3">
                        <div class="container-fluid">
                            <div class="row mb-1 pt-0 pb-0">
                                <div class="col-md-12 col-sm-12 col-12" id="tourExists">
                                    <p class="m-0">{{ trans('messages.tourExit') }}</p>
                                    <div class="list_tourExists">
                                        <ul>
                                        </ul>
                                    </div>
                                    <p class="font-italic">{{ trans('messages.orMakeNewTour') }}</p>
                                </div>
                                <div class="col-md-12 col-sm-12 col-12">
                                    <p class="font-weight-bold font-italic mb-0">{{ trans('messages.NameTour') }}</p>
                                </div>
                                <div class="col-md-12 col-sm-12 col-12 mb-2">
                                    <input type="text" class="form-control" placeholder="{{ trans('messages.NameTour') }}" name="nameTour">
                                </div>
                                <div class="col-md-12 col-sm-12 col-12">
                                    <p class="font-weight-bold font-italic mb-1">{{ trans('messages.ratingYourTour') }}</p>
                                </div>
                                <div class="col-md-12 col-sm-12 col-12 mb-2" id="div_Starrank_tour">
                                    <i class="fas fa-star star_1 fa-2x star1"  data-value="1" style="cursor: pointer;"></i>
                                    <i class="fas fa-star star_2 fa-2x star2" data-value="2" style="cursor: pointer;"></i>
                                    <i class="fas fa-star star_3 fa-2x star3" data-value="3" style="cursor: pointer;"></i>
                                    <i class="fas fa-star star_4 fa-2x star4"  data-value="4" style="cursor: pointer;"></i>
                                    <i class="fas fa-star star_5 fa-2x star5" data-value="5" style="cursor: pointer;"></i>
                                </div>
                                <input type="hidden" id="star_Share" name="star" value="0">
                                <div class="col-md-12 col-sm-12 col-12">
                                    <p class="font-weight-bold font-italic mb-1">{{ trans('messages.wantShareTour') }}</p>
                                </div>
                                <div class="col-md-12 col-sm-12 col-12 mb-2">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                      <label class="btn btn-primary" id="noPublic">
                                        <input type="radio" value="no" name="options" id="option2" autocomplete="off" checked> No
                                      </label>
                                      <label class="btn btn-primary active" id="yesPublic">
                                        <input type="radio" value="yes" name="options" id="option1" autocomplete="off"> Yes
                                      </label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row inforForShare pt-0 pb-0">
                                <div class="col-md-12 col-sm-12 col-12">
                                    <p class="font-weight-bold font-italic mb-1">{{ trans('messages.RecommendTour') }}</p>
                                </div>
                                <div class="col-md-12 col-sm-12 col-12">
                                    <textarea class="form-control" placeholder="Recommend" name="recommend"></textarea>
                                </div>
                                <div class="col-md-12 col-sm-12 col-12 mt-2 mb-2">
                                    <div class="custom-control custom-switch">
                                        <p class="font-weight-bold font-italic m-0 mr-2">Do you want to upload images?</p>
                                        <input type="checkbox" data-toggle="toggle" data-size="xs" id="checkImage" data-on="Yes" data-off="No">
                                    </div>
                                </div>
                                <!-- old image -->
                                <div class="col-md-12 col-sm-12 col-12" id="oldImageTitle">
                                    <p class="font-weight-bold font-italic mb-1">{{ trans('messages.oldImage') }}</p>
                                </div>
                                <div class="col-md-12 col-sm-12 col-12" id="oldImageContent">
                                </div>
                                <!-- /oldimage -->
                                <div class="col-md-12 col-sm-12 col-12" id="uploadImageTitle">
                                    <p class="font-weight-bold font-italic mb-1">{{ trans('messages.photoForYourTour') }}</p>
                                </div>
                                <div class="col-md-12 col-sm-12 col-12 mb-3" id="uploadImageBtn">
                                    <div class="Update_img_tour">{{ trans('messages.Upload') }}</div>
                                    <p class="name_file_tour font-weight-bold font-italic"></p>
                                    <input accept="image/*" type="file" name="image_tour" class="form-control" id="img_input_Rank">
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-2 pl-5 pr-5">
                        <button type="submit" class="btn btn-primary" id="btnSaveNameTour">
                            @if(!isset($to_des))
                                {{ trans('messages.SaveTour') }}
                            @else
                                {{ trans('messages.EditTour') }}
                            @endif
                        </button>
                        <!-- @if(!isset($to_des))
                            <button type="button" class="btn btn-success" id="btnSaveShareTour">Save and share the tour</button>
                        @endif -->
                    </div>
                </form>
            </div>
            
        </div>
    </div>
</div>
<!-- /end map -->
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
                                <div class="btn-loginFB mt-4">
                                    <a target="_blank" href="{{url('/getInfo-facebook/facebook')}}" id="link_login" class="link_login_FB">
                                        <i class="fab fa-facebook-square"></i>
                                        Login with Facebook
                                    </a>
                                </div>
                                <div class="btn-loginGoogle mt-2">
                                    <a target="_blank" href="{{url('/getInfo-google/google')}}" id="link_login" class="link_login_google">
                                        <i class="fab fa-google"></i>
                                        Login with Google
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
                <form action="{{route('register')}}" method="post" id="formRegister">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="hidden" name="checkmodal" value="">
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
                                                <input type="number" class="form-control" placeholder="{{ trans('messages.Age') }}" name="age" required="" min="0" max="100">
                                        </div>
                                </div>
                        </div>
                        <hr>
                        <div class="div_btn_register">
                                <input type="submit" class="btn btn-primary" value="{{ trans('messages.Registration') }}" id="btn_Registration">
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