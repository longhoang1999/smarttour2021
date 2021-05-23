@extends('sharetour/layout/index')
@section('title')
    Tour you like
@parent   
@stop
@section('header_styles')
  <link rel="stylesheet" href="{{asset('css/tourhistory.css')}}">
  <style>
    #site_tourlike{
      position: relative;
    }
    #site_tourlike svg{
      position: absolute;
      color: #00ade5;
      top: 50%;
      transform: translateY(-50%);
      font-size: 110px;
    }
    #site_tourlike a {
        background: lightblue !important;
        color: #117964 !important;
    }
  </style>
@stop
@section('content')
  <h2 id="page_title" lass="page-section-heading text-center text-uppercase text-secondary mb-0">Tour you like</h2>
  <div class="divider-custom">
      <div class="divider-custom-line"></div>
      <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
      <div class="divider-custom-line"></div>
  </div>
  <div id="main-page">
      <div class="left" id="sitebar">
          <ul>
              <li id="site_history">
                <a href="{{route('user.tourhistory')}}">{{ trans('newlang.tourhistory') }}</a>
              </li>
              <li id="site_searchtour"><a href="{{route('searchTour')}}">{{ trans('newlang.searchtour') }}</a></li>
              <li id="site_tourlike">
                <i class="fas fa-caret-right"></i>
                <a href="{{route('user.tourUserLike')}}" id="user_tour_like">Tour you like</a>
              </li>
              
          </ul>
      </div>

      <div class="right" id="main">
          <div class="AllClass_Table">
              <div class="AllClass_Table_title">
                <p class="text-uppercase">Tour information you liked <i class="fas fa-heart text-danger"></i></p>
              </div>
              <div class="AllClass_Table_content">
                  <table class="table table-bordered table-striped" id="Table_AllClass" style="margin-bottom: 10px;">
                        <thead>
                        <tr>
                            <th>{{ trans("admin.Order") }}</th>
                            <th>ID</th>
                            <th>{{ trans('newlang.Tourname') }}</th>
                            <!-- <th>{{ trans('newlang.startLocation') }}</th>
                            <th>{{ trans('newlang.detailPlaces') }}</th> -->
                            <th>{{ trans('newlang.Star') }}</th>
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
                      <a href="#" id="link_view_tour">
                        <i class="fas fa-edit"></i>
                        Show detail tour
                      </a>
                  </div>
                  <p id="p_votes"><span class="font-weight-bold font-italic">{{ trans('newlang.Yourvotes') }}: </span>
                  <span id="text_votes"></span></p>
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
                  <p><span class="font-weight-bold font-italic">{{ trans('newlang.dateCreated') }}: </span>
                  <span id="date_created"></span></p>
              </div>
          </div>
          
      </div>
  </div>
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
              <a href="#" class="showLink">Detail Place</a>
          </div>
          <div class="imgPlace mt-4 mb-4">
          </div>
          <div id="map" class="mt-4 mb-4"></div>
          <div class="container">
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
              { className: "startlocat_class", "targets": [ 3 ] },
              { className: "detaillocat_class", "targets": [ 4 ] }
            ],
            processing: true,
            serverSide: true,
            ajax: "{!! route('share.showtourlike') !!}",
            order:[],
            columns: [
              { data: 'stt', name: 'stt' },
              { data: 'to_id', name: 'to_id' }, 
                { data: 'to_name', name: 'to_name' },
                // { data: 'startLocat', name: 'startLocat' },
                // { data: 'detailPlace', name: 'detailPlace' },
                { data: 'Star', name: 'Star' },
                { data: 'totalTime', name: 'totalTime' },
                ]
            });
          table.on( 'draw.dt', function () {
              var PageInfo = $('#Table_AllClass').DataTable().page.info();
              table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                  cell.innerHTML = i + 1 + PageInfo.start;
              });
          });
          // sài on (click) thay cho click (function)
          var idTour;
          $('#Table_AllClass tbody').on('click', 'tr', function () {
            idTour = $(this).find(".id_class").text();
            let _token = $('meta[name="csrf-token"]').attr('content');
            let $url_path = '{!! url('/') !!}';
            let routeDetail=$url_path+"/takeDetailTour/haveshare";
            $.ajax({
                  url:routeDetail,
                  method:"POST",
                  data:{_token:_token,idTour:idTour},
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

                    $("#startLocation").empty();
                    $("#startLocation").append(data[3]);
                    $("#detail_location").empty();
                    $("#detail_location").append(data[4]);
                    $("#start_time").empty();
                    $("#start_time").append(data[5]);
                    $("#end_time").empty();
                    $("#end_time").append(data[6]);
                    $("#date_created").empty();
                    $("#date_created").append(data[7]);
                    $("#link_view_tour").attr("href","");
                    $("#link_view_tour").attr("href",data[8]);

                    $("#total_time").empty();
                    var duration = moment.duration(data[9], 'minutes');
                    var durationString = duration.days() + 'd ' + duration.hours() + 'h ' + duration.minutes() + 'm';
                    $("#total_time").append(durationString);
                 }
            });
          });
      })
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
                    $(".showLink").attr("href",data[10]);
                    $("#typePlace").empty();
                    $("#typePlace").append(data[9]);
                    $("#short").empty();
                    $("#description").empty();
                    if(data[4] != null)
                        $("#short").append(data[4]);
                    else
                        $("#short").append('<span class="badge badge-warning">{{ trans("newlang.Notavailable") }}</span>');

                    if(data[5] != null)
                        $("#description").append(data[5]);
                    else
                        $("#description").append('<span class="badge badge-warning">{{ trans("newlang.Notavailable") }}</span>');  

                    $("#timeAvg").html(parseFloat(data[6])/60/60+" hours");
                    $("#linkMap").empty();
                    if(data[7] != null)
                        $("#linkMap").append('<a href="'+data[7]+'" target="_blank">Link here</a>');
                    else
                        $("#linkMap").append('<span class="badge badge-warning">{{ trans("newlang.Notavailable") }}</span>');
                    $("#linkvr").empty();
                    if(data[8] != null)
                        $("#linkvr").append('<a href="'+data[8]+'" target="_blank">Link here</a>');
                    else
                        $("#linkvr").append('<span class="badge badge-warning">{{ trans("newlang.Notavailable") }}</span>');
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