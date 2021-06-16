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
                | Tour Advice
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
        <link rel="stylesheet" href="{{asset('css/notlogin.css')}}">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            var duration;
            var durationString;
        </script>
        <style>
            body{
                background: url("{{asset('images/vhl.jpeg')}}");
                background-size: cover;
                background-repeat: no-repeat;
                position: relative;
            }
            .nav_more{display: none;}
        </style>
        <!--page level css-->
        @yield('header_styles')
        <!--end of page level css-->
    </head>
    <body id="page-top">
        <?php use App\Models\Destination; use App\Models\Language;use Illuminate\Support\Facades\Auth;?>
        @include('layoutmap.layout.header')
        @yield('content')
        @include('layoutmap.layout.footer')
        @include('layoutmap.layout.modal')
        <!-- Third party plugin JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
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
                // console.log(document.getElementById('checkImage').checked);
                $("#checkImage").change(function(){
                    console.log(document.getElementById('checkImage').checked);
                    if(document.getElementById('checkImage').checked == true)
                    {
                        $("#oldImageTitle").show();
                        $("#oldImageContent").show();
                        $("#uploadImageTitle").show();
                        $("#uploadImageBtn").show();
                    }
                    else if(document.getElementById('checkImage').checked == false)
                    {
                        $("#oldImageTitle").hide();
                        $("#oldImageContent").hide();
                        $("#uploadImageTitle").hide();
                        $("#uploadImageBtn").hide();
                    }
                });
            });
        </script>
        <script type="text/javascript">
            $(".up_btn").click(function(){
                $(".nav_more").slideUp("fast");
                $("#wrap").css("margin-top","5em");
                $(".show_btn").show();
            });
            $(".show_btn").click(function(){
                $(".nav_more").slideDown("fast");
                $("#wrap").css("margin-top","8em");
                $(this).hide();
            });
            $("#searchPlace").keyup(function(){
                if($(this).val() != "")
                {
                    let $url_path = '{!! url('/') !!}';
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    let routeSraechSmart=$url_path+"/searchPlaceSmart";
                    $.ajax({
                          url:routeSraechSmart,
                          method:"POST",
                          data:{_token:_token,key:$(this).val()},
                          success:function(data){ 
                            if(data.length)
                            {
                                $(".result_search").show();
                                $(".result_search ul").empty();
                                data.forEach(myFunction);
                                function myFunction(item, index) {
                                    $(".result_search ul").append('<li class="select_id" data-id="'+item['des_id']+'"><img src="'+$url_path+"/"+item["de_image"]+'" alt="" />'+item['de_name']+'</li>');
                                    $(".select_id").click(function(){
                                        location.replace($url_path + "/showDetailPlace/"+$(this).attr("data-id"));
                                        //console.log($(this).attr("data-id"));
                                    });
                                }
                            }
                            else
                            {
                                $(".result_search").show();
                                $(".result_search ul").empty();
                                $(".result_search ul").append('<li class="select_id"><img src="{{asset("assets/img/portfolio/cabin.png")}}" alt="" />{{ trans("messages.notHaveAnyResults") }}</li>');
                            }
                         }
                    });
                }
            });
            
            $(document).click(function (e)
            {
                var container = $("#div_search");
                //click ra ngoài đối tượng
                if (!container.is(e.target) && container.has(e.target).length === 0)
                {
                    $(".result_search").hide();
                }
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
            $("#StarttourNow").click(function(){
                location.replace("{{route('user.maps')}}");
            });  
            //language
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
            $("#li_person div.nav-link").click(function(){
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
            //open modal login
            $("#openModalLogin").click(function(){
                $("#modalLogin").modal("show");
            });
            $('#modalRegis').on('shown.bs.modal', function () {
              $('#modalLogin').modal("hide");
            });
            @if(Auth::check())
            //more
            $("#li_more div.nav-link").click(function(){
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
            @endif
            $('#modalLogin').on('shown.bs.modal', function () {
              $('#modalRegis').modal("hide");
            });
            $(".backFormLogin").click(function(){
                $("#modalLogin").modal("show");
            });
            @if ($message = Session::get('error') || $message = Session::get('success') || count($errors) > 0)
                $("#modalNotice").modal("show");
            @endif

            $("#formFeedback").submit(function(e){
                e.preventDefault();
                $("#modalLogin").modal("show");
            });
            $("#titleLogin").click(function(){
                $("#modalLogin").modal("show");
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
                            alert("{{ trans('messages.cantSendEmail') }}");
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
        </script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#comback_admin").click(function(){
                    location.replace("{{route('admin.generalInfor')}}");
                });
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
                            if(data[6] != "" && data[6] != null)
                            {
                                $("#text_email").append(data[1]+"<span class='text-danger' style='font-style: italic;'> ({{ trans('messages.notVerified') }})</span>");
                            }
                            if(data[6] == "" ||  data[6] == null)
                            {
                                $("#text_email").append(data[1]+"<span class='text-success' style='font-style: italic;'> ({{ trans('messages.Verified') }})</span>");
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
                $("#input_File").change(function(){
                    $(".btn_upload").css("background","#ff8304");
                    $("#file_name").css("display","block");
                    $("#file_name").html($("#input_File").val().split('\\').pop());
                });
            });
        </script>
        <script type="text/javascript">
            function animationHeader(){
                $("#li_more").on("click",'a',function(){
                    if ($('#div_more').is(':visible'))
                    {
                        $("#div_more").slideUp("fast");
                    }
                    else
                    {
                        $("#div_more").slideDown("fast");
                    }
                });
                $("#li_person").on("click",'a',function(){ 
                    if ($('#div_person').is(':visible'))
                    {
                        $("#div_person").slideUp("fast");
                    }
                    else
                    {
                        $("#div_person").slideDown("fast");
                    }
                });
                $("#comback_admin").click(function(){
                    location.replace("{{route('admin.generalInfor')}}");
                });
                $("#personalInfo").click(function(){
                    $("#personal").modal("show");
                });
                $("#p_logout").click(function(){
                    location.replace("{{route('logout')}}");
                });
            }
        </script>
        @yield('footer-js')
    </body>