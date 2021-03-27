@extends('sharetour/layout/index')
@section('title')
    Search Tour
@parent   
@stop
@section('header_styles')
  <style>
    #detail_location span{
      display: block;
    }
    .startlocat_class{
      width: 10rem !important;
    }
  </style>
@stop
@section('content')
  <h2 id="page_title" lass="page-section-heading text-center text-uppercase text-secondary mb-0">search tour</h2>
  <div class="divider-custom">
      <div class="divider-custom-line"></div>
      <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
      <div class="divider-custom-line"></div>
  </div>
  <div id="main-page">
      <div class="left" id="sitebar">
          <ul>
              <!-- <li id="site_history"><a href="#">Your tour history</a></li> -->
              <li id="site_searchtour"><a href="{{route('searchTour')}}">Search tour</a></li>
          </ul>
          <span id="site_searchTitle">--Search</span>
          <div id="content_search">
              @if(Auth::check())
                <span id="site_youShared">Tour you shared</span>
              @endif
              <span id="site_highlyRated">Highly rated tours</span>
              <span id="site_thisMonth">Tour this month</span>
              <span id="site_lastMonth">Tour last month</span>
          </div>
          <span id="site_selectMon">--Select month</span>
          <div id="content_select">
              <input type="month" id="fdate" name="fdate" class="form-control">
          </div>
          <span id="site_total_time">--Total time</span>
          <div id="content_totalName">
              <span id="site_max">Ascending</span>
              <span id="site_min">Descending</span>
          </div>
      </div>

      <div class="right" id="main">
          <div class="container-fluid">
              <div class="row">
                    <!-- khối 1 -->
                    <div class="col-xl-3 col-md-6 mb-4 div_parent" id="div_1">
                      <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                          <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">number of tours over 8 <i class="fas fa-star text-warning"></i></div>
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
                    <div class="col-xl-3 col-md-6 mb-4 div_parent" id="div_2">
                      <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                          <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">tour with the most votes (>=2)</div>
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
                    <div class="col-xl-3 col-md-6 mb-4 div_parent" id="div_3">
                      <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                          <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">tours created this month</div>
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
                    <div class="col-xl-3 col-md-6 mb-4 div_parent" id="div_4">
                      <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                          <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">tour has the largest total time</div>
                              <div class="h5 mb-0 font-weight-bold text-gray-800">99999 tours</div>
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
              <span class="search_advan_title font-italic">Advanced search</span><span class="openModalSearch">click here</span>
          </div>
          <div class="AllClass_Table">
              <div class="AllClass_Table_title">
                <p class="text-uppercase">tour information on request</p>
              </div>
              <div class="AllClass_Table_content">
                  <table class="table table-bordered table-striped" id="Table_AllClass" style="margin-bottom: 10px;">
                        <thead>
                        <tr>
                            <th>{{ trans("admin.Order") }}</th>
                            <th>ID</th>
                            <th>Tour name</th>
                            <th>Start Location</th>
                            <th>Detail Places</th>
                            <th>Average rating</th>
                            <th>Votes</th>
                            <th>Total time(hours)</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
              </div>
          </div>
          
          <h5 class="font-italic font-weight-bold" id="detail_title">Tour Detail</h5>
          <div class="tour_infor">
              <div class="tour_infor_left slider autoplay">
              </div>
              <div class="tour_infor_right">
                  <h3 class="font-weight-bold font-italic" id="name_tour"></h3>
                  <hr>
                  <div id="div_btn">
                      <button class="btn btn-warning" data-toggle="modal"
                      @if(Auth::check())
                          data-target="#exampleModal"
                      @else
                          data-target="#modalLogin"
                      @endif
                      >Rating</button>
                      <a href="#" id="link_view_tour" class="btn btn-info">View tour</a>
                  </div>
                  <p id="p_votes"><span class="font-weight-bold font-italic">Your votes: </span>
                  <span id="text_votes"></span></p>

                  <p><span class="font-weight-bold font-italic">Introduce: </span>
                  <span id="text_intro"></span></p>

                  <p><span class="font-weight-bold font-italic">Average rating: </span>
                  <span id="text_avgRating"></span></p>

                  <p><span class="font-weight-bold font-italic">Number of ratings: </span>
                  <span id="text_number_rates"></span></p>

                  <p><span class="font-weight-bold font-italic">Start Location: </span>
                  <span id="startLocation"></span></p>

                  <p class="font-weight-bold font-italic mb-0">Location:</p>
                  <p id="detail_location"></p>

                  <p><span class="font-weight-bold font-italic">Start time: </span>
                  <span id="start_time"></span></p>
                  <p><span class="font-weight-bold font-italic">Endtime time: </span>
                  <span id="end_time"></span></p>
                  <p><span class="font-weight-bold font-italic">Date created: </span>
                  <span id="date_created"></span></p>
              </div>
          </div>
          
      </div>
  </div>
  <!-- modal rating -->
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
  <!-- modal rating -->
  
  <!-- modal search -->
  <div class="modal fade" id="ModalSearch" tabindex="-1" role="dialog" aria-labelledby="ModalSearchLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalSearchLabel">Search Tour</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div style="display: flex;">
              <span style="font-size: 1.2rem;width: 20%" class="font-weight-bold font-italic">Type of place</span> 
              <select id="selectType" class="form-control" style="width: 60%">
                <option value="All">--All--</option>
                <option value="0">Scenic spots</option>
                <option value="1">Restaurant</option>
                <option value="2">Hotel</option>
                <option value="3">Schools</option>
                <option value="4">--Other</option>
              </select>
          </div>
          <div style="display: flex;margin-top: 1.5rem">
              <span style="font-size: 1.2rem;width: 20%" class="font-weight-bold font-italic">Places</span> 
              <select id="selectPlaces" class="form-control" style="width: 60%;margin-right: 2rem;">
                <option hidden="">--Your choice--</option>
                @foreach($lang as $la)
                  <option value="{{$la->des_id}}">{{$la->de_name}}</option>
                @endforeach
              </select>
              <button class="btn btn-primary" id="btn_add_place">Add Location</button>
          </div>
          <div class="list_location mt-4">
            <p class="lead font-weight-bold font-italic">list of places you want to go</p>
            <div class="list_content">
                <!-- append content -->
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="btn_searchforlisst">Search Tour</button>
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
          <h5 class="modal-title" id="notFoundLabel">Tour not found</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <span class="font-weight-bold font-italic text-danger">We are very sorry! The system cannot find a tour going through the locations you choose.</span>
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
@stop
@section('footer-js')     
<script type="text/javascript">
    var listIdSearch = [];
    $(document).ready(function(){
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
              { className: "startlocat_class", "targets": [ 3 ] }
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
                { data: 'rating', name: 'rating' },
                { data: 'votes', name: 'votes' },
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
                  scrollTop: $('.AllClass_Table').offset().top - '100'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchTourTable";
              table.ajax.url( routeOverStart ).load();
          });
          $("#site_highlyRated").click(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '100'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchTourTable";
              table.ajax.url( routeOverStart ).load();
          });
          @if(Auth::check())
            $("#site_youShared").click(function(){
                $(".AllClass_Table").show();
                $("html, body").animate({
                    scrollTop: $('.AllClass_Table').offset().top - '100'
                }, 200);
                let $url_path = '{!! url('/') !!}';
                var routeYouShared = $url_path+"/searchTourYouShared";
                table.ajax.url( routeYouShared ).load();
            });
          @endif
          $("#div_2").click(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '100'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchMostVotes";
              table.ajax.url( routeOverStart ).load();
          });
          $("#div_3").click(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '100'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchThisMonth";
              table.ajax.url( routeOverStart ).load();
          });
          $("#site_thisMonth").click(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '100'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchThisMonth";
              table.ajax.url( routeOverStart ).load();
          });
          $("#site_lastMonth").click(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '100'
              }, 200);
              let $url_path = '{!! url('/') !!}';
              var routeOverStart = $url_path+"/searchLastMonth";
              table.ajax.url( routeOverStart ).load();
          });
          $("#fdate").change(function(){
              $(".AllClass_Table").show();
              $("html, body").animate({
                  scrollTop: $('.AllClass_Table').offset().top - '100'
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
          $("#btn_searchforlisst").click(function(){
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
                      $("#ModalSearch").modal("hide");
                    }
                    else
                    {
                      $("#ModalSearch").modal("hide");
                      $(".AllClass_Table").show();
                      $("html, body").animate({
                          scrollTop: $('.AllClass_Table').offset().top - '100'
                      }, 200);
                      var routeForPlace = $url_path+"/searchListPlace/"+data;
                      table.ajax.url( routeForPlace ).load();
                    }
                 }
            });
          });
          $('#notFound').on('hidden.bs.modal', function (e) {
            $("#ModalSearch").modal("show");
          })
          // sài on (click) thay cho click (function)
          var idShareTour;
          $('#Table_AllClass tbody').on('click', 'tr', function () {
            idShareTour = $(this).find(".id_class").text();
            let _token = $('meta[name="csrf-token"]').attr('content');
            let $url_path = '{!! url('/') !!}';
            let routeDetail=$url_path+"/takeDetailRoute";
            $.ajax({
                  url:routeDetail,
                  method:"POST",
                  data:{_token:_token,idShareTour:idShareTour},
                  success:function(data){ 
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
                        scrollTop: $('#detail_title').offset().top - '100'
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
                    $("#text_avgRating").empty();
                    $("#text_avgRating").append(data[5]);
                    $("#text_number_rates").empty();
                    $("#text_number_rates").append(data[6]);
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
                    $("#link_view_tour").attr("href","");
                    $("#link_view_tour").attr("href",data[12]);
                    
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
                      alert("You have successfully evaluated");
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
                      $("#selectPlaces").append('<option value="'+item+'">'+data[1][index]+'</option>');
                    }
                 }
            });
        });
        $("#btn_add_place").click(function(){
          $("#ModalSearch .modal-footer").css("display","flex");
          $(".list_location").show();
          listIdSearch.push($("#selectPlaces").val());
          $(".list_content").append('<span class="mt-2" data-id = "'+$("#selectPlaces").val()+'"><i class="fas fa-street-view" style="color:#e74949;"></i>'+$( "#selectPlaces option:selected" ).text()+'<i class="fas fa-times remove_item"></i></span>');
        });
        $(".list_content").on('click','span .remove_item',function(){
          //alert($(this).parents().attr('data-id'));
          const index = listIdSearch.indexOf($(this).parent().attr('data-id'));
          if (index > -1) {
            listIdSearch.splice(index, 1);
          }
          $(this).parent().remove();
          if(listIdSearch.length == 0)
          {
            $(".list_location").hide();
            $("#ModalSearch .modal-footer").hide();
          }
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
                    alert("Geocode was not successful for the following reason: " + status);
                  }
                });
            };
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgbjwIY5Q1eZ-Ejqur0a8avEQWowfA39s&callback=initMap" async defer></script>
@stop