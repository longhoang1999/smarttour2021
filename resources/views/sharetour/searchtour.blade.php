@extends('sharetour/layout/index')
@section('title')
    {{ trans('newlang.searchtour') }}
@parent   
@stop
@section('header_styles')
  <link rel="stylesheet" href="{{asset('css/searchtour_2.css')}}">
@stop
@section('content')
  <div class="main-search">
      <h2 id="page_title" lass="page-section-heading text-center text-uppercase text-secondary mb-0">{{ trans('newlang.searchtour') }}</h2>
    <div class="divider-custom">
        <div class="divider-custom-line"></div>
        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
        <div class="divider-custom-line"></div>
    </div>
    <div id="main-page">
        <div class="left" id="sitebar">
            <ul>
                @if(Auth::check())
                <li id="site_history"><a href="{{route('user.tourhistory')}}" id="user_tour_history">{{ trans('newlang.tourhistory') }}</a></li>
                <li id="site_tourlike"><a href="{{route('user.tourUserLike')}}" id="user_tour_like">Tour you like</a></li>
                @endif
                <li id="site_searchtour">
                  <i class="fas fa-caret-right"></i>
                  <a href="{{route('searchTour')}}">{{ trans('newlang.searchtour') }}</a>
                </li>
            </ul>
            <span id="site_searchName">--Search for name</span>
            <div id="content_searchName">
                <input type="text" class="form-control" placeholder="Search tour" id="searchTourName">
                <div class="result_search_tour">
                    <ul>
                    </ul>
                </div>
            </div>

            <span id="site_searchTitle">--{{ trans('newlang.Search') }}</span>
            <div id="content_search">
                @if(Auth::check())
                  <span id="site_youShared">{{ trans('newlang.tourShare') }}</span>
                @endif
                <span id="site_highlyRated">{{ trans('newlang.highRate') }}</span>
                <span id="site_thisMonth">{{ trans('newlang.tourThisMonth') }}</span>
                <span id="site_lastMonth">{{ trans('newlang.tourLastMonth') }}</span>
            </div>
            <span id="site_selectMon">--{{ trans('newlang.selectMonth') }}</span>
            <div id="content_select">
                <input type="month" id="fdate" name="fdate" class="form-control">
            </div>
            <span id="site_total_time">--{{ trans('newlang.totalTime') }}</span>
            <div id="content_totalName">
                <span id="site_max">{{ trans('newlang.Ascending') }}</span>
                <span id="site_min">{{ trans('newlang.Descending') }}</span>
            </div>
        </div>

        <div class="right" id="main">
            <div class="container-fluid">
                <div class="row">
                      <!-- khối 1 -->
                      <div class="col-xl-3 col-md-6 mb-4 div_parent">
                        <div class="card border-left-primary shadow h-100" id="div_1">
                          <div class="card-body">
                            <div class="row no-gutters align-items-center">
                              <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ trans('newlang.tourover') }}<i class="fas fa-star text-warning"></i></div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$votes_over}} tours</div>
                              </div>
                              <div class="col-auto">
                                <i class="fas fa-star fa-2x text-warning"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- khối 1 -->
                      <!-- khối 2 -->
                      <div class="col-xl-3 col-md-6 mb-4 div_parent">
                        <div class="card border-left-success shadow h-100" id="div_2">
                          <div class="card-body">
                            <div class="row no-gutters align-items-center">
                              <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ trans('newlang.tourmostVotes') }} (>=2)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$votes_number}} tours</div>
                              </div>
                              <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-success"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- khối 2 -->
                      <!-- khối 3 -->
                      <div class="col-xl-3 col-md-6 mb-4 div_parent">
                        <div class="card border-left-info shadow h-100" id="div_3">
                          <div class="card-body">
                            <div class="row no-gutters align-items-center">
                              <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ trans('newlang.tourCreateMonth') }}</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$thismonth}} tours</div>
                              </div>
                              <div class="col-auto">
                                <i class="fas fa-map-marker-alt fa-2x text-info"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- khối 3 -->
                      <!-- khối 4 -->
                      <div class="col-xl-3 col-md-6 mb-4 div_parent">
                        <div class="card border-left-warning shadow h-100" id="div_4">
                          <div class="card-body">
                            <div class="row no-gutters align-items-center">
                              <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ trans('newlang.largestTotal') }} (> 1day)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$votes_total_time}} tours</div>
                              </div>
                              <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-primary"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- khối 4 -->
                </div>
            </div>
            <div id="search_advan">
                <span class="search_advan_title font-italic">{{ trans('newlang.advanSearch') }}</span><span class="openModalSearch">{{ trans('newlang.clickhere') }}</span>
            </div>
            <div class="AllClass_Table">
                <div class="AllClass_Table_title">
                  <p class="text-uppercase">{{ trans('newlang.tourRequest') }}</p>
                </div>
                <div class="AllClass_Table_content">
                    <table class="table table-bordered table-striped" id="Table_AllClass" style="margin-bottom: 10px;">
                          <thead>
                          <tr>
                              <th>{{ trans("newlang.Order") }}</th>
                              <th>ID</th>
                              <th>{{ trans('newlang.Tourname') }}</th>
                              <th>{{ trans('newlang.startLocation') }}</th>
                              <th>{{ trans('newlang.detailPlaces') }}</th>
                              <th>Evaluate</th>
                              <th>{{ trans('newlang.totalTime') }}</th>
                          </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                </div>
            </div>
            
            <h5 class="font-italic font-weight-bold" id="detail_title">{{ trans('newlang.tourDetail') }}</h5>
            <div class="tour_infor">
                <div class="tour_infor_left slider autoplay">
                </div>
                <div class="tour_infor_right">
                    <h3 class="font-weight-bold font-italic" id="name_tour"></h3>
                    <hr>
                    <div id="div_btn">
                        <!-- <button class="btn btn-warning" data-toggle="modal"
                        @if(Auth::check())
                            data-target="#exampleModal"
                        @else
                            data-target="#modalLogin"
                        @endif
                        >{{ trans('newlang.Rating') }}</button>
                        <a href="#" id="link_view_tour" class="btn btn-info">{{ trans('newlang.viewTour') }}</a> -->
                        <a href="#" id="link_detail_tour">
                          <i class="fas fa-globe-americas"></i> See tour details
                        </a>
                    </div>
                    <p id="p_votes"><span class="font-weight-bold font-italic">{{ trans('newlang.Yourvotes') }}: </span>
                    <span id="text_votes"></span></p>

                    <p><span class="font-weight-bold font-italic">{{ trans('newlang.Introduce') }}: </span>
                    <span id="text_intro"></span></p>

                    <p><span class="font-weight-bold font-italic">Evaluate: </span>
                    <span id="text_evaluate"></span></p>
                    <p><span class="font-weight-bold font-italic">{{ trans('newlang.startLocation') }}: </span>
                    <span id="startLocation"></span></p>

                    <p class="font-weight-bold font-italic mb-0">{{ trans('newlang.Location') }}:</p>
                    <p id="detail_location"></p>

                    <p><span class="font-weight-bold font-italic">{{ trans('newlang.startTime') }}: </span>
                    <span id="start_time"></span></p>
                    <p><span class="font-weight-bold font-italic">{{ trans('newlang.endtimeTime') }}: </span>
                    <span id="end_time"></span></p>
                    <p><span class="font-weight-bold font-italic">{{ trans('newlang.totalTourTime') }}: </span>
                    <span id="total_time"></span></p>
                    <p><span class="font-weight-bold font-italic">{{ trans('newlang.TotalCost') }}: </span>
                    <span id="total_cost"></span></p>

                    <p><span class="font-weight-bold font-italic">Tour creator: </span>
                    <span id="tour_creator"></span></p>

                    <p><span class="font-weight-bold font-italic">{{ trans('newlang.dateCreated') }}: </span>
                    <span id="date_created"></span></p>
                </div>
            </div>
            
        </div>
    </div>
  </div>
  <!-- modal rating -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ trans('newlang.Rating') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fuild">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                  <p class="font-weight-bold font-italic">{{ trans('newlang.ratingForTour') }}</p>
                </div>
                <div class="col-md-12 col-sm-12 col-12 mb-3" id="div_Starrank_tour">
                  <i class="fas fa-star star_1 fa-2x"  data-value="1" style="cursor: pointer;"></i>
                  <i class="fas fa-star star_2 fa-2x" data-value="2" style="cursor: pointer;"></i>
                  <i class="fas fa-star star_3 fa-2x" data-value="3" style="cursor: pointer;"></i>
                  <i class="fas fa-star star_4 fa-2x"  data-value="4" style="cursor: pointer;"></i>
                  <i class="fas fa-star star_5 fa-2x" data-value="5" style="cursor: pointer;"></i>
                </div>
                <input type="hidden" id="star_Share" name="numberStar">
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="btn_Rating">{{ trans('newlang.Rating') }}</button>
        </div>
      </div>
    </div>
  </div>
  <!-- modal rating -->
  <!-- modal search -->
  <div class="modal fade" id="ModalSearch" tabindex="-1" role="dialog" aria-labelledby="ModalSearchLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalSearchLabel">{{ trans('newlang.searchtour') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="border-top: 1px solid lightgray;">
          <nav class="search_nav_menu bg-light">
              <div class="search_box1">{{ trans('newlang.throughPlace') }}</div>
              <div class="search_box2">{{ trans('newlang.searchTotalCost') }}</div>
          </nav>
          <div class="search_content">
            <div class="left_block">
              <div style="display: flex;">
                  <span style="font-size: 1.2rem;width: 20%" class="font-weight-bold font-italic">{{ trans('newlang.Typeofplace') }}</span> 
                  <select id="selectType" class="form-control" style="width: 60%">
                    <option value="All">--{{ trans('newlang.All') }}--</option>
                    @foreach($allTypePlace as $typePlace)
                    <option value="{{$typePlace->id}}">{{$typePlace->nameType}}</option>
                    @endforeach
                  </select>
              </div>
              <div style="display: flex;margin-top: 1.5rem">
                  <span style="font-size: 1.2rem;width: 20%" class="font-weight-bold font-italic">{{ trans('newlang.Places') }}</span> 
                  <select id="selectPlaces" class="form-control" style="width: 60%;margin-right: 2rem;">
                    <option hidden="" value="">--{{ trans('newlang.yourChoice') }}--</option>
                    @foreach($lang as $la)
                      <option value="{{$la->des_id}}">{{$la->de_name}}</option>
                    @endforeach
                  </select>
                  <!-- <button class="btn-sm btn btn-primary" id="btn_add_place">{{ trans('newlang.addLocation') }}</button> -->
              </div>
              <div class="list_location mt-4">
                <p class="lead font-weight-bold font-italic">{{ trans('newlang.listPlace') }}</p>
                <div class="list_content">
                    <!-- append content -->
                </div>
              </div>
            </div>
            <div class="right_block">
              <h5 class="font-weight-bold font-italic">{{ trans('newlang.enterCostUse') }}:</h5>
              <div style="display: flex;" class="mb-3 mt-3"> 
                  <span style="font-size: 1.2rem;width: 20%" class="font-weight-bold font-italic">{{ trans('newlang.Denominations') }}: </span> 
                  <select name="currency" class="form-control currency" style="width: 12%">
                    @if(Session::has('website_language') && Session::get('website_language') == "vi")
                      <option selected="true" value="VNĐ">VNĐ</option>
                      <option value="USD">USD</option>
                    @else
                      <option value="VNĐ">VNĐ</option>
                      <option selected="true" value="USD">USD</option>
                    @endif
                  </select>
              </div>
              <div class="block_minimum mb-4">
                  <span class="font-weight-bold font-italic">{{ trans('newlang.Minimum') }}: </span> 
                  <input type="number" class="form-control" id="minimum" style="width: 70%" placeholder="{{ trans('newlang.Minimum') }}">
                  <p class="detail_money detail_money_minimum"></p>
              </div>
              <div class="block_maximum">
                  <span class="font-weight-bold font-italic">{{ trans('newlang.Maximum') }}: </span> 
                  <input type="number" class="form-control" id="maximum" style="width: 70%" placeholder="{{ trans('newlang.Maximum') }}">
                  <p class="detail_money detail_money_maximum"></p>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="btn_searchforlist">{{ trans('newlang.searchtour') }}</button>
          <button type="button" class="btn btn-success" id="btn_searchforcost">{{ trans('newlang.searchtour') }}</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /modal search -->
  <!-- modal not found tour -->
  <div class="modal fade" id="notFound" tabindex="-1" role="dialog" aria-labelledby="notFoundLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="notFoundLabel">{{ trans('newlang.Tournotfound') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <span class="font-weight-bold font-italic text-danger" id="notFound_notification"></span>
        </div>
      </div>
    </div>
  </div>
  <!-- / modal not found tour -->
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
  $(document).ready(function(){
    // detail_money
    $("#minimum").keyup(function(){
      if($(this).val() == "")
      {
        $(".detail_money_minimum").css("display","none");
        $("#ModalSearch .modal-footer").css("display","none");
      }
      else
      {
        $("#ModalSearch .modal-footer").css("display","flex");
        $(".detail_money_minimum").text($(this).val().toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") +" "+$(".currency").val());
        $(".detail_money_minimum").css("display","block");
      }
    });
    $("#maximum").keyup(function(e){
      if($(this).val() == "")
      {
        $(".detail_money_maximum").css("display","none");
      }
      else
      {
        $(".detail_money_maximum").text($(this).val().toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") +" "+$(".currency").val());
        $(".detail_money_maximum").css("display","block");
      }
    });
    $(".currency").change(function(){
      let string_money;
      if($(".detail_money_maximum").text() != "")
        string_money = $(".detail_money_maximum").text();
      else
        string_money = $(".detail_money_minimum").text();
      if(string_money.indexOf("VNĐ") != "-1")
      {
        $(".detail_money_maximum").text(string_money.slice(0,string_money.indexOf("VNĐ")) + $(this).val());
        $(".detail_money_minimum").text(string_money.slice(0,string_money.indexOf("VNĐ")) + $(this).val());
      }
      else if(string_money.indexOf("USD") != "-1")
      {
        $(".detail_money_maximum").text(string_money.slice(0,string_money.indexOf("USD")) + $(this).val());
        $(".detail_money_minimum").text(string_money.slice(0,string_money.indexOf("USD")) + $(this).val());
      }
    })
    //search_box
    $(".search_box1").click(function(){
      $(".left_block").slideDown();
      $(".right_block").slideUp();
      $("#btn_searchforcost").hide();
      $("#btn_searchforlist").show();
      $(".search_box2").css("background","transparent");
      $(this).css("background","#e7e6e6");
    });
    $(".search_box2").click(function(){
      $(".right_block").slideDown();
      $(".left_block").slideUp();
      $("#btn_searchforcost").show();
      $("#btn_searchforlist").hide();
      $(".search_box1").css("background","transparent");
      $(this).css("background","#e7e6e6");
    });
  });
</script> 
<script type="text/javascript">
    var listIdSearch = [];
    $(document).ready(function(){
        // votess star
        @for($i = 1; $i<= 5; $i++)
          $("#div_Starrank_tour .star_{{$i}}").click(function(){
              @for($j = 1 ; $j <= 5; $j++)
                  $("#div_Starrank_tour .star_{{$j}}").css("color","#212529");
              @endfor
              @for($j = 1 ; $j <= $i; $j++)
                  $("#div_Starrank_tour .star_{{$j}}").css("color","#ff9700");
              @endfor
              //console.log($(this).attr("data-value"));
              $("#star_Share").val($(this).attr("data-value"));
        });
        @endfor
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
    });
</script>
<script>
  $(function() {
      var table = $('#Table_AllClass').DataTable({
            "language": {
            "emptyTable": "{{trans('admin.emptyTable')}}",
            "sLengthMenu": "{{ trans('admin.showEntries') }}",
            "search": "{{ trans('admin.search') }}",
            "info": "{{ trans('admin.showingToOf') }}",
            "paginate": {
              "previous": "{{ trans('admin.previous') }}",
              "next": "{{ trans('admin.next') }}"
            }
          },
          "lengthMenu": [[5, 10, -1], [5, 10,"All"]],
          "order": [[ 1, 'asc' ]],
          "columnDefs": [
              { className: "id_class", "targets": [ 1 ] },
              { className: "id_tourname", "targets": [ 2 ] },
              { className: "startlocat_class", "targets": [ 3 ] },
              { className: "detaillocat_class", "targets": [ 4 ] }
            ],
            processing: true,
            serverSide: true,
            ajax: '{!! route('share.searchTourTable') !!}',
            order:[],
            columns: [
              { data: 'stt', name: 'stt' },
              { data: 'sh_id', name: 'sh_id' }, 
                { data: 'tourName', name: 'tourName' },
                { data: 'startLocat', name: 'startLocat' },
                { data: 'detailPlace', name: 'detailPlace' },
                { data: 'evaluate', name: 'evaluate' },
                { data: 'totalTime', name: 'totalTime' },
                ]
            });
          table.on( 'draw.dt', function () {
              var PageInfo = $('#Table_AllClass').DataTable().page.info();
              table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                  cell.innerHTML = i + 1 + PageInfo.start;
              });
          });
          $("#div_1").click(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '130'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchTourTable";
              table.ajax.url( routeOverStart ).load();
          });
          $("#site_highlyRated").click(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '130'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchTourTable";
              table.ajax.url( routeOverStart ).load();
          });
          @if(Auth::check())
            $("#site_youShared").click(function(){
                $(".AllClass_Table").show();
                $("html, body").animate({
                    scrollTop: $('.AllClass_Table').offset().top - '130'
                }, 200);
                let $url_path = '{!! url('/') !!}';
                var routeYouShared = $url_path+"/searchTourYouShared";
                table.ajax.url( routeYouShared ).load();
            });
          @endif
          //search tour
          $("#searchTourName").keyup(function(){
              if($(this).val() != "")
              {
                  let $url_path = '{!! url('/') !!}';
                  let _token = $('meta[name="csrf-token"]').attr('content');
                  let routeSraechSmart=$url_path+"/searchTourSmart";
                  $.ajax({
                        url:routeSraechSmart,
                        method:"POST",
                        data:{_token:_token,key:$(this).val()},
                        success:function(data){ 
                          if(data.length)
                          {
                              $(".result_search_tour").show();
                              $(".result_search_tour ul").empty();
                              data.forEach(myFunction);
                              function myFunction(item, index) {

                                  if(item["image"] != null)
                                  {
                                    $(".result_search_tour ul").append('<li class="select_id_tour" data-id="'+item['sh_id']+'"><img src="'+$url_path+"/"+item["image"]+'" alt="" /><span>'+item['to_name']+'</span></li>');
                                  }
                                  else
                                  {
                                    $(".result_search_tour ul").append('<li class="select_id_tour" data-id="'+item['sh_id']+'"><img src="'+$url_path+"/imgPlace/empty.png"+'" alt="" /><span>'+item['to_name']+'</span></li>');
                                  }
                              }
                          }
                          else
                          {
                              $(".result_search_tour").show();
                              $(".result_search_tour ul").empty();
                              $(".result_search_tour ul").append('<li class="select_id"><img src="{{asset("assets/img/portfolio/cabin.png")}}" alt="" />{{ trans("messages.notHaveAnyResults") }}</li>');
                          }
                       }
                  });
              }
              else
              {
                $(".result_search_tour").hide();
              }
          });
          $(".result_search_tour ul").on('click','.select_id_tour',function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '130'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeForName = $url_path+"/searchTourName/"+$(this).attr("data-id");
              table.ajax.url( routeForName ).load();
          })
          $(document).click(function (e)
          {
              var container = $("#content_searchName");
              if (!container.is(e.target) && container.has(e.target).length === 0)
              {
                  $(".result_search_tour").hide();
              }
          });
          $("#div_2").click(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '130'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchMostVotes";
              table.ajax.url( routeOverStart ).load();
          });
          $("#div_3").click(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '130'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchThisMonth";
              table.ajax.url( routeOverStart ).load();
          });
          $("#div_4").click(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '130'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchForHighTotal";
              table.ajax.url( routeOverStart ).load();
          });

          $("#site_max").click(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '130'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchMaxTotal";
              table.ajax.url( routeOverStart ).load();
          });
          $("#site_min").click(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '130'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchMinTotal";
              table.ajax.url( routeOverStart ).load();
          });
          $("#site_thisMonth").click(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '130'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchThisMonth";
              table.ajax.url( routeOverStart ).load();
          });
          $("#site_lastMonth").click(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '130'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchLastMonth";
              table.ajax.url( routeOverStart ).load();
          });
          $("#fdate").change(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '130'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              let date = $(this).val();
              var routeOverStart = $url_path+"/searchAnyMonth/"+date;
              table.ajax.url( routeOverStart ).load();
          });
          //search for place
          $(".openModalSearch").click(function(){
            $("#ModalSearch").modal("show");
          });
          $("#btn_searchforlist").click(function(){
            let type = $("#selectType").val();
            let _token = $('meta[name="csrf-token"]').attr('content');
            let $url_path = '{!! url('/') !!}';
            let routeSearchPlace=$url_path+"/selectTourForPlace";
            $.ajax({
                  url:routeSearchPlace,
                  method:"POST",
                  data:{_token:_token,listIdSearch:listIdSearch},
                  success:function(data){
                    //console.log(data);
                    if(data.length == 0)
                    {
                      $("#notFound").modal("show");
                      $("#notFound_notification").text("{{ trans('newlang.weAreSorry') }}");
                      $("#ModalSearch").modal("hide");
                    }
                    else
                    {
                      $("#ModalSearch").modal("hide");
                      $(".AllClass_Table").show();
                      $("html, body").animate({
                          scrollTop: $('.AllClass_Table').offset().top - '130'
                      }, 200);
                      var routeForPlace = $url_path+"/searchListPlace/"+data;
                      table.ajax.url( routeForPlace ).load();
                    }
                 }
            });
          });
          $("#btn_searchforcost").click(function(){
            if(parseFloat($("#maximum").val()) < parseFloat($("#minimum").val()))
            {
              alert("Bạn nhập giá tối đa nhỏ hơn giá tối thiểu");
            }
            else
            {
              let currency = $(".currency").val();
              let minimum = $("#minimum").val();
              let maximum = $("#maximum").val();
              let _token = $('meta[name="csrf-token"]').attr('content');
              let $url_path = '{!! url('/') !!}';
              let routeSearchPlace=$url_path+"/selectTourForCost";
              $.ajax({
                    url:routeSearchPlace,
                    method:"POST",
                    data:{_token:_token,minimum:minimum,maximum:maximum,currency:currency},
                    success:function(data){
                      //console.log(data);
                      if(data.length == 0)
                      {
                        $("#notFound").modal("show");
                        $("#notFound_notification").text("{{ trans('newlang.weAreSorryCost') }}");
                        $("#ModalSearch").modal("hide");
                      }
                      else
                      {
                        $("#ModalSearch").modal("hide");
                        $(".AllClass_Table").show();
                        $("html, body").animate({
                            scrollTop: $('.AllClass_Table').offset().top - '130'
                        }, 200);
                        var routeForPlace = $url_path+"/searchListPlace/"+data;
                        table.ajax.url( routeForPlace ).load();
                      }
                   }
              });
            }
          });
          $('#notFound').on('hidden.bs.modal', function (e) {
            $("#ModalSearch").modal("show");
          })
          // sài on (click) thay cho click (function)
          var idShare;
          var idShareTour;
          $('#Table_AllClass tbody').on('click', 'tr', function () {
            idShareTour = $(this).find(".id_class").text();
            let _token = $('meta[name="csrf-token"]').attr('content');
            let $url_path = '{!! url('/') !!}';
            let routeDetail=$url_path+"/takeDetailRoute";
            $.ajax({
                  url:routeDetail,
                  method:"GET",
                  data:{_token:_token,idShareTour:idShareTour},
                  success:function(data){ 
                    idShare = data[14];
                    // //show div detail
                    $("#detail_title").show();
                    $(".tour_infor").css("display","flex");
                    // set up slide show
                    $('.autoplay').slick('unslick');
                    $(".tour_infor_left").empty();
                    let i = 0;
                    data[0].forEach(myFunction);
                    function myFunction(item, index) {
                       $(".tour_infor_left").append('<div class="div_parents"><p>'+data[1][i]+'</p><a data-fancybox="gallery" href='+item+'><img class="img-fluid" src='+item+' alt="" style="width: 100%"></a></div>');
                       i++;
                    }
                    //start slide show
                    $('.autoplay').slick(getSliderSettings());
                    // scroll to div
                    $("html, body").animate({
                        scrollTop: $('#detail_title').offset().top - '130'
                    }, 200);
                    // set other
                    $("#name_tour").empty();
                    $("#name_tour").append(data[2]);
                    @if(Auth::check())
                      $("#p_votes").show();
                      $("#text_votes").empty();
                      $("#text_votes").append(data[3]);
                    @endif
                    $("#text_intro").empty();
                    $("#text_intro").append(data[4]);
                    $("#text_evaluate").empty();
                    $("#text_evaluate").append(data[17])
                    $("#startLocation").empty();
                    $("#startLocation").append(data[7]);
                    $("#detail_location").empty();
                    $("#detail_location").append(data[8]);
                    $("#start_time").empty();
                    $("#start_time").append(data[9]);
                    $("#end_time").empty();
                    $("#end_time").append(data[10]);
                    $("#date_created").empty();
                    $("#date_created").append(data[11]);
                    $("#link_detail_tour").attr("href","");
                    $("#link_detail_tour").attr("href",data[12]);

                    $("#total_time").empty();
                    var duration = moment.duration(data[13], 'minutes');
                    var durationString = duration.days() + 'd ' + duration.hours() + 'h ' + duration.minutes() + 'm';
                    $("#total_time").append(durationString);
                    // php check
                      $("#total_cost").text(data[15].toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
                    $("#tour_creator").empty();
                    $("#tour_creator").append(data[16]);
                 }
            });
          });
          $('#exampleModal').on('show.bs.modal', function (event) {
              // load start
              let _token = $('meta[name="csrf-token"]').attr('content');
              let $url_path = '{!! url('/') !!}';
              let routeCheckTour = $url_path+"/voteUser";
              $.ajax({
                    url:routeCheckTour,
                    method:"POST",
                    data:{_token:_token,shareId:idShare},
                    success:function(result){ 
                      console.log(result);
                      //reset
                      $("#star_Share").val('0');
                      $(".star_1").css("color","#212529");
                      $(".star_2").css("color","#212529");
                      $(".star_3").css("color","#212529");
                      $(".star_4").css("color","#212529");
                      $(".star_5").css("color","#212529");
                      if(result[0] == "yes")
                      {
                        $("#star_Share").val(result[1]);
                        if(result[1] == "1")
                        {
                          $(".star_1").css("color","#ff9700");
                        }
                        else if(result[1] == "2")
                        {
                          $(".star_1").css("color","#ff9700");
                          $(".star_2").css("color","#ff9700");
                        }
                        else if(result[1] == "3")
                        {
                          $(".star_1").css("color","#ff9700");
                          $(".star_2").css("color","#ff9700");
                          $(".star_3").css("color","#ff9700");
                        }
                        else if(result[1] == "4")
                        {
                          $(".star_1").css("color","#ff9700");
                          $(".star_2").css("color","#ff9700");
                          $(".star_3").css("color","#ff9700");
                          $(".star_4").css("color","#ff9700");
                        }
                        else if(result[1] == "5")
                        {
                          $(".star_1").css("color","#ff9700");
                          $(".star_2").css("color","#ff9700");
                          $(".star_3").css("color","#ff9700");
                          $(".star_4").css("color","#ff9700");
                          $(".star_5").css("color","#ff9700");
                        }
                      }
                   }
              });
          });
          $("#btn_Rating").click(function(){
              let _token = $('meta[name="csrf-token"]').attr('content');
              let $url_path = '{!! url('/') !!}';
              let routeRating=$url_path+"/rating";
              let numberStar = $("#star_Share").val();
              $.ajax({
                    url:routeRating,
                    method:"POST",
                    data:{_token:_token,numberStar:numberStar,shareId:idShareTour},
                    success:function(data){ 
                      alert("{{ trans('newlang.ratingSuccess') }}");
                      location.reload();
                    }
              });
          });
          $(".showImage").click(function(){
              $("#map").hide();
              $(".imgPlace").show();
          });
          $(".showMap").click(function(){
              $("#map").show();
              $(".imgPlace").hide();
          });
      });
      function getSliderSettings(){
        return {
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
        }
      }
      $(document).ready(function(){
        $("#selectType").change(function(){
            let type = $("#selectType").val();
            let _token = $('meta[name="csrf-token"]').attr('content');
            let $url_path = '{!! url('/') !!}';
            let routePlaceForType=$url_path+"/selectPlaceForType";
            $.ajax({
                  url:routePlaceForType,
                  method:"POST",
                  data:{_token:_token,type:type},
                  success:function(data){
                    console.log(data); 
                    $("#selectPlaces").empty();
                    data[0].forEach(myFunction);
                    function myFunction(item, index) {
                      let check = false;
                      listIdSearch.forEach(function(idSelectes){
                        if(item == idSelectes)
                        {
                          check = true;
                        }
                      });
                      if(check == true)
                      {
                        $("#selectPlaces").append('<option style="background:rgb(204,202,202)" disabled="" value="'+item+'">'+data[1][index]+'</option>');
                        check = false;
                      }
                      else
                      {
                        $("#selectPlaces").append('<option value="'+item+'">'+data[1][index]+'</option>');
                      }                      
                    }
                 }
            });
        });
        $("#selectPlaces").change(function(){
          if($("#selectPlaces").val() != "")
          {
            var attr = $('#selectPlaces option:selected').attr('disabled');
            if (typeof attr === typeof undefined || attr === false) {
              $("#ModalSearch .modal-footer").css("display","flex");
              $(".list_location").show();
              listIdSearch.push($("#selectPlaces").val());
              $(".list_content").append('<span class="mt-2" data-id = "'+$("#selectPlaces").val()+'"><i class="fas fa-street-view" style="color:#e74949;"></i>  '+$( "#selectPlaces option:selected" ).text()+'<i class="fas fa-times remove_item"></i></span>');
              $('#selectPlaces option:selected').attr("disabled","");
              $('#selectPlaces option:selected').css("background","#c8c8c8");
              for (var i = 0; i <= $('#selectPlaces option').length; i++) {
                  let option = document.getElementById('selectPlaces')[i];
                  if(option != undefined)
                  {
                    option.selected = false;
                    if(option.value != "")
                    {
                      if(option.disabled == false)
                      {
                        option.selected = true;
                        break;
                      }
                    }
                  }
                  else
                  {
                    alert("{{ trans('newlang.youSelectedAll') }}");
                  }
              }
            }
            else
            {
              alert("{{ trans('newlang.chosenThisPlace') }}");
            }
          }
          else
          {
            alert("{{ trans('newlang.PleaseSelect') }}");
          }
        });
        $(".list_content").on('click','span .remove_item',function(){
          const index = listIdSearch.indexOf($(this).parent().attr('data-id'));
          let dataId = $(this).parent().attr('data-id');
          if (index > -1) {
            listIdSearch.splice(index, 1);
          }
          $(this).parent().remove();
          if(listIdSearch.length == 0)
          {
            $(".list_location").hide();
            $("#ModalSearch .modal-footer").hide();
          }
          //remove disabled
          $("#selectPlaces option").each(function() {
              if($(this).val() == dataId)
              {
                $(this).removeAttr("disabled");
                $(this).css("background","white");
                //$("#selectPlaces").val(dataId);
              }
          });
          //remove disabled
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
              $("#startLocation").on('click','span',function(){
                showdetail($(this).attr("data-id"));
              });
              $("#detail_location").on('click','span',function(){
                showdetail($(this).attr("data-id"));
              });
            });
            function showdetail(data_id){
              $("#modalDetailPlace").modal("show");
              let $url_path = '{!! url('/') !!}';
              let _token = $('meta[name="csrf-token"]').attr('content');
              let routeGetCooor = $url_path+"/takeInforPlace";
              let des_id = data_id;
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
                        $("#short").append("<span class='badge badge-warning'>{{ trans('newlang.Notavailable') }}</span>");

                    if(data[5] != null)
                        $("#description").append(data[5]);
                    else
                        $("#description").append("<span class='badge badge-warning'>{{ trans('newlang.Notavailable') }}</span>");  

                    $("#timeAvg").html(parseFloat(data[6])/60/60+" hours");
                    $("#linkMap").empty();
                    if(data[7] != null)
                        $("#linkMap").append('<a href="'+data[7]+'" target="_blank">Link here</a>');
                    else
                        $("#linkMap").append("<span class='badge badge-warning'>{{ trans('newlang.Notavailable') }}</span>");
                    $("#linkvr").empty();
                    if(data[8] != null)
                        $("#linkvr").append('<a href="'+data[8]+'" target="_blank">Link here</a>');
                    else
                        $("#linkvr").append("<span class='badge badge-warning'>{{ trans('newlang.Notavailable') }}</span>");
                    //vẽ map
                    deleteMarker();
                    let add = data[0]+","+data[1];
                    geocodeAddress(geocoder,map,data[2],add);
                  }
                });
            }
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