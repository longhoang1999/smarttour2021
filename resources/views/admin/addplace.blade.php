@extends('admin/layout/index')
@section('title')
    {{ trans('admin.addPlace') }}
@parent
@stop
@section('header_styles')
	<link rel="stylesheet" href="{{asset('css/adminDashboard.css')}}">
  <link rel="stylesheet" href="{{asset('css/addPlace.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
  <style>
    @if(Session::has('website_language') && Session::get('website_language') == "vi")
      .div_addfor_en{display: none;}
    @else
      .div_addfor_vn{display: none;}
    @endif
  </style>
@stop
@section('content')
  @if ($message = Session::get('status'))
      <div class="alert alert-danger alert-block">
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{$message}}</strong>
      </div>
  @endif
	<div class="title"><p class="text-uppercase">{{ trans('admin.inforPlace') }}</p></div>
	<div class="AllClass_Table">
        <div class="AllClass_Table_title">
          <p>{{ trans('admin.inforPlace') }}</p>
        </div>
        <div class="AllClass_Table_content">
            <div style="display: flex;">
              <span style="font-size: 1.2rem;width: 20%" class="font-weight-bold font-italic">{{ trans('admin.languageShown') }}</span> 
              <select id="selectLang" class="form-control" style="width: 40%">
                <option hidden="">--{{ trans('admin.yourChoice') }}--</option>
                <option selected="" value="en">{{ trans('admin.EN') }}</option>
                <option value="vn">{{ trans('admin.VN') }}</option>
              </select>
            </div>
            <div style="display: flex; margin-top: 1.5rem">
              <span style="font-size: 1.2rem;width: 20%" class="font-weight-bold font-italic">{{ trans('admin.typeOfPlace') }}</span> 
              <select id="selectType" class="form-control" style="width: 40%">
                <option value="All">--All--</option>
                @foreach($typeplace as $type)
                  <option value="{{$type->id}}">{{$type->nametype}}</option>
                @endforeach
              </select>
            </div>
            <table class="table table-bordered table-striped" id="Table_AllClass" style="margin-bottom: 10px;">
                  <thead>
                  <tr>
                      <th>{{ trans('admin.Order') }}</th>
                      <th>{{ trans('admin.NamePlace') }}</th>
                      <th>{{ trans('admin.Longitude') }}</th>
                      <th>{{ trans('admin.Latitude') }}</th>
                      <th>{{ trans('admin.Duration') }} ({{ trans('admin.hours') }})</th>
                      <th>{{ trans('admin.Createdby') }}</th>
                      <th>{{ trans('admin.Actions') }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
        </div>
    </div>
<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetailLabel">{{ trans('admin.DetailPlace') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <!-- name place -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.NamePlace') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-justify pb-3" id="placeName"></p>
            </div>
            <!-- type place -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.typeOfPlace') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6 text-justify" id="placeType">
            </div>
            <!-- image -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Image') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6" id="placeImage">
            </div>
            <!-- lng -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Longitude') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-justify pb-3" id="longitude"></p>
            </div>
            <!-- lat -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Latitude') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-justify pb-3" id="latitude"></p>
            </div>
            <!-- des -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Description') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-justify pb-3" id="description"></p>
            </div>
            <!-- short -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Shortdes') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-justify pb-3" id="shortdes"></p>
            </div>
            <!-- duration -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Duration') }} ({{ trans('admin.hours') }})</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-justify pb-3" id="duration"></p>
            </div>
            <!-- cost -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.cost') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-justify pb-3" id="cost"></p>
            </div>
            <!-- link map -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.linkmap') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6 text-justify">
              <a href="#" class="pb-3" id="link" target="_blank">{{ trans('admin.Linkhere') }}</a>
            </div>
            <!-- link vr -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.linkVR') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6 text-justify">
              <a href="#" class="pb-3" id="link_vr" target="_blank">{{ trans('admin.Linkhere') }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /Modal -->
  <div class="title"><p class="text-uppercase">{{ trans('admin.addlocation') }}</p></div>
  <small class="text-info">({{ trans('admin.getUrl') }})</small>
  <div class="container-fluid">
    <div class="form-group">
      <label for="inputLink">{{ trans('admin.linkggmap') }}</label>
      <div class="row">
        <div class="col-md-9 col-12 col-sm-12">
          <input type="text" class="form-control" id="inputLink" placeholder="{{ trans('admin.linkggmap') }}" required="">
        </div>
        <div class="col-md-3 col-12 col-sm-12">
          <button type="button" class="btn btn-primary" id="btn_getLink">Get link</button>
        </div>
      </div>
    </div>
  </div>
  <div class="mapBorder">
    <div id="map" class="tabcontent"></div>
  </div>
  <button class="btn btn-info" id="btn_addPlace">{{ trans('admin.addplaceForlink') }}</button>
  <button class="btn btn-info" id="btn_addPlace_2">{{ trans('admin.addplaceForClick') }}</button>

  <div class="AllClass_Table" id="Form_add">
        <div class="AllClass_Table_title">
          <p>{{ trans('admin.inforYouChosse') }}</p>
        </div>
        <div class="AllClass_Table_content">
            <form action="{{route('admin.postaddPlace')}}" method="post" id="formAddPlace" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <h4 class="font-weight-bold font-italic text-primary text-uppercase">-- {{ trans('admin.inforDescribe') }}</h4>

              <div style="display: flex;">
                <span style="font-size: 1.2rem;width: 20%" class="font-weight-bold font-italic">{{ trans('admin.languageShown') }}</span> 
                <select id="selectLang_add" class="form-control" style="width: 40%">
                  <option hidden="">--Your choice--</option>
                  @if(Session::has('website_language') && Session::get('website_language') == "vi")
                    <option value="en">{{ trans('admin.EN') }}</option>
                    <option selected="" value="vn">{{ trans('admin.VN') }}</option>
                  @else
                    <option selected="" value="en">{{ trans('admin.EN') }}</option>
                    <option value="vn">{{ trans('admin.VN') }}</option>
                  @endif
                </select>
              </div>
              <!-- Place Name -->
              <div class="div_addfor_en">
                <div class="form-group">
                  <label for="inputName">{{ trans('admin.NamePlace') }} - for English</label>
                  <input type="text" class="form-control" id="inputName" placeholder="{{ trans('admin.NamePlace') }}" name="de_name_en">
                </div>
                <!-- Short -->
                <div class="form-group">
                  <label for="inputShortdes">{{ trans('admin.Shortdes') }} - for English</label>
                  <textarea class="form-control" id="inputShortdes" placeholder="{{ trans('admin.Shortdes') }}" name="de_shortdes_en"></textarea>
                </div>
                <!-- Des -->
                <div class="form-group">
                  <label for="inputDescription">{{ trans('admin.Description') }} - for English</label>
                  <textarea class="form-control" id="inputDescription" placeholder="{{ trans('admin.Description') }}" name="de_description_en"></textarea>
                </div>
              </div>
              <!-- vn -->
              <div class="div_addfor_vn">
                <div class="form-group">
                  <label for="inputName">{{ trans('admin.NamePlace') }} - for Tiếng Việt</label>
                  <input type="text" class="form-control" id="inputName_vn" placeholder="{{ trans('admin.NamePlace') }}" name="de_name_vn">
                </div>
                <!-- Short -->
                <div class="form-group">
                  <label for="inputShortdes">{{ trans('admin.Shortdes') }} - for Tiếng Việt</label>
                  <textarea class="form-control" id="inputShortdes_vn" placeholder="{{ trans('admin.Shortdes') }}" name="de_shortdes_vn"></textarea>
                </div>
                <!-- Des -->
                <div class="form-group">
                  <label for="inputDescription">{{ trans('admin.Description') }} - for Tiếng Việt</label>
                  <textarea class="form-control" id="inputDescription_vn" placeholder="{{ trans('admin.Description') }}" name="de_description_vn"></textarea>
                </div>
              </div>

              <h4 class="font-weight-bold font-italic text-primary text-uppercase">-- {{ trans('admin.otherInfo') }}</h4>
              <div style="display: flex; margin-top: 1.5rem">
                <span style="font-size: 1.2rem;width: 20%" class="font-weight-bold font-italic">{{ trans('admin.typeOfPlace') }}</span> 
                <select id="selectType_add" class="form-control" style="width: 40%" name="typePlace">
                  @foreach($typeplace_add as $type)
                  <option value="{{$type->id}}">{{$type->nametype}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label for="inputLongitude">{{ trans('admin.Longitude') }}</label>
                <input type="text" class="form-control" id="inputLongitude" placeholder="{{ trans('admin.Longitude') }}" required="" readonly="" name="de_lng">
              </div>
              <div class="form-group">
                <label for="inputLatitude">{{ trans('admin.Latitude') }}</label>
                <input type="text" class="form-control" id="inputLatitude" placeholder="{{ trans('admin.Latitude') }}" required="" readonly="" name="de_lat">
              </div>
              <div class="form-group">
                <label for="open_inputFile">{{ trans('admin.Image') }}</label>
                <div class="open_inputFile">{{ trans('admin.Upload') }}</div>
                <p class="file_name"></p>
                <input type="file" class="form-control" id="inputImage" name="de_image" accept=".jpg,.png">
              </div>
              <div class="form-group">
                <label for="inputDuration">{{ trans('admin.avgTime') }} ({{ trans('admin.hours') }})</label>
                <input type="number" class="form-control" id="inputDuration" placeholder="{{ trans('admin.avgTime') }}" required="" name="de_duration" step="0.1">
              </div>
              <!-- cost -->
              <div class="form-group">
                <label for="inputCost">
                  {{ trans('admin.enterCost') }}
                  <span class="show_yourCost"> -{{ trans('admin.youEntered') }} <span class="show_money"></span></span>
                </label>
                <div class="enterCost_block">
                  <select name="currency" class="form-control" id="selectCurrency">
                    @if(Session::has('website_language') && Session::get('website_language') == "vi")
                      <option selected="true" value="VNĐ">VNĐ</option>
                      <option value="USD">USD</option>
                    @else
                      <option value="VNĐ">VNĐ</option>
                      <option selected="true" value="USD">USD</option>
                    @endif
                  </select>
                  <input type="number" class="form-control" id="inputCost" placeholder="Enter Cost" required="" name="de_cost" step="10">
                </div>
              </div>
              <!-- cost -->

              <div class="form-group">
                <label for="googleLink">{{ trans('admin.linkmap') }}</label>
                <input type="text" class="form-control" id="googleLink" readonly="" required="" name="de_map">
              </div>
              <div class="form-group">
                <label for="googleLink">{{ trans('admin.linkVR') }}</label>
                <input type="text" class="form-control" id="vrLink" name="de_link">
              </div>
              <button type="submit" class="btn btn-primary">{{ trans('admin.AddPlace') }}</button>
            </form>
        </div>
    </div>

@stop
@section('footer-js')
	<!-- datatable -->
  	<script type="text/javascript" src="{{ asset('datatables/js/jquery.dataTables.js') }}" ></script>  
  	<script type="text/javascript" src="{{ asset('datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        // format money
        $("#inputCost").keyup(function(){
          if($(this).val() == "")
          {
            $(".show_yourCost").hide();
          }
          else
          {
            $(".show_money").text($(this).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") +" "+$("#selectCurrency").val());
            $(".show_yourCost").show();
          }
        });
        $("#selectCurrency").change(function(){
          var string_money = $(".show_money").text();
          if(string_money.indexOf("VNĐ") != "-1")
          {
            $(".show_money").text(string_money.slice(0,string_money.indexOf("VNĐ")) + $(this).val());
          }
          else if(string_money.indexOf("USD") != "-1")
          {
            $(".show_money").text(string_money.slice(0,string_money.indexOf("USD")) + $(this).val());
          }
        });
        $("#selectLang_add").change(function(){
          if($("#selectLang_add").val() == "vn")
          {
            $(".div_addfor_en").hide();
            $(".div_addfor_vn").show();
          }
          else if($("#selectLang_add").val() == "en")
          {
            $(".div_addfor_vn").hide();
            $(".div_addfor_en").show();
          }
        });
        $(".open_inputFile").click(function(){
          $("#inputImage").click();
        });
        $("#inputImage").change(function(){
          $(".open_inputFile").css("background","linear-gradient(90deg, rgba(122,104,61,1) 0%, rgba(167,142,59,1) 35%, rgba(181,173,78,1) 72%, rgba(184,137,15,1) 100%)");
          $(".file_name").css("display","block");
          $(".file_name").html("File name: &#60;"+$("#inputImage").val().split('\\').pop()+"&#62;");
        });
      });
    </script>
    <!-- datatabel -->
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
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.showDestination') !!}',
            order:[],
            columns: [
              { data: 'stt', name: 'stt' },
                { data: 'de_name', name: 'de_name' },
                { data: 'de_lng', name: 'de_lng' },
                { data: 'de_lat', name: 'de_lat' },
                { data: 'duration', name: 'duration' },
                { data: 'status', name: 'status' },
                { data: 'actions', name: 'actions' },
                ]
            });
            table.on( 'draw.dt', function () {
          var PageInfo = $('#Table_AllClass').DataTable().page.info();
              table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                  cell.innerHTML = i + 1 + PageInfo.start;
              } );
          });
          // choose language
          $("#selectLang").change(function(){
            let $url_path = '{!! url('/') !!}';
            $("#selectType").val("All");
            if($("#selectLang").val() == "en")
            {
              var routeEN = $url_path+"/showDestination";
              table.ajax.url( routeEN ).load();
            }
            else if($("#selectLang").val() == "vn")
            {
              var routeVN = $url_path+"/showDestinationVN";
              table.ajax.url( routeVN ).load();
            }
          });
          //choose type place
          $("#selectType").change(function(){
            let $url_path = '{!! url('/') !!}';
            let type = $("#selectType").val();
            let takeLang = $("#selectLang").val();
            if(type == "All")
            {
              if($("#selectLang").val() == "en")
              {
                var routeEN = $url_path+"/showDestination";
                table.ajax.url( routeEN ).load();
              }
              else if($("#selectLang").val() == "vn")
              {
                var routeVN = $url_path+"/showDestinationVN";
                table.ajax.url( routeVN ).load();
              }
            }
            else
            {
              var routeType = $url_path+"/showDestinationType/"+type+"/"+takeLang;
              table.ajax.url( routeType ).load();
            }
          });
        });
    </script>
    <!-- detail -->
    <script type="text/javascript">
      $('#modalDetail').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var recipient = button.data('remove') 
        let $url_path = '{!! url('/') !!}';
        let _token = $('meta[name="csrf-token"]').attr('content');
        let language = $("#selectLang").val();
        let routeShowDetail=$url_path+"/showDetail/"+recipient+"/"+language;
        $.ajax({
              url:routeShowDetail,
              method:"GET",
              data:{_token:_token},
              success:function(data){ 
                if(data =="{{ trans('admin.cantFinddata') }}")
                  alert(data)
                else
                {
                  $("#placeImage").empty();
                  $("#placeName").html(data[0]);
                  $("#longitude").html(data[1]);
                  $("#latitude").html(data[2]);
                  $("#description").html(data[3]);
                  $("#shortdes").html(data[4]);
                  $("#duration").html(data[5]);
                  $("#cost").html(data[10].toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
                  if(data[6] != null)
                  {
                    $("#link_vr").html("Link here");
                    $("#link_vr").css("color","blue");
                    $("#link_vr").attr("target","_blank");
                    $("#link_vr").attr("href",data[6]);
                  }
                  else
                  {
                    $("#link_vr").attr("href","#");
                    $("#link_vr").html("Not available");
                    $("#link_vr").css("color","#ffa200");
                    $("#link_vr").removeAttr("target");
                  }
                  if(data[7] != "")
                  {
                    $("#link").attr("href",data[7]);
                  }
                  if(data[8] != "") 
                  {
                    $("#placeImage").append('<a data-fancybox="gallery" href="'+data[8]+'"><img class="img-fluid rounded mb-5" style="width:100%;" src="'+data[8]+'" alt=""></a>');
                  }
                  else
                  {
                    $("#placeImage").append('<a data-fancybox="gallery" href="{{asset("imgPlace/empty.png")}}"><img class="img-fluid rounded mb-5" style="width:100%;" src="{{asset("imgPlace/empty.png")}}" alt=""></a>');
                  }
                  $("#placeType").empty();
                  $("#placeType").append('<span class="badge badge-success">'+data[9]+'</span>');
                }
             }
        });
      })
    </script>
    <script type="text/javascript">
    //định nghĩa marker
    var staMarker;
    //biến lưu tọa độ cho sự kiện click bản đồ
    var Coordinates;
    //biến lưu mảng marker để xóa
    var markers=[];
    //ajax của js
    function getNamePlace(geocoder,map)
      {
        let $url_path = '{!! url('/') !!}';
        let _token = $('meta[name="csrf-token"]').attr('content');
        let routeCheckPlace=$url_path+"/checkPlace";
        let inputLink = document.getElementById("inputLink").value;
        //Khoi tao doi tuong
        var xhttp = new XMLHttpRequest() || ActiveXObject();
        //Bat su kien thay doi trang thai cuar request
        xhttp.onreadystatechange = function() {
                //Kiem tra neu nhu da gui request thanh cong
                if (this.readyState == 4 && this.status == 200) {
                    //In ra data nhan duoc
                  var Commonlabel =  this.responseText.toString();
                  //set map vs tên
                  geocodeAddress(geocoder, map,Commonlabel);
                }
            }
            //cau hinh request
        xhttp.open('POST', routeCheckPlace, true);
        //cau hinh header cho request
        xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        //gui request
        xhttp.send('_token='+_token+'&inputLink='+inputLink);
      }
      function initMap(){
          var map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12.5,
                center: { lat: 21.0226586, lng: 105.8179091 },
                gestureHandling: 'greedy',
              }),
          directionsService = new google.maps.DirectionsService();
          map.addListener('click',function(evt){
            var staMarker = new google.maps.Marker({
              label: 'Your location',
            });
            staMarker.setMap(map);
            staMarker.setPosition(evt.latLng);
            customLabel(staMarker);
            //push marker vào mảng để xóa
            markers.push(staMarker);
            //xét tọa độ vào form
            Coordinates = evt.latLng;
            document.getElementById("btn_addPlace_2").setAttribute("style","display:block");
            document.getElementById("btn_addPlace").setAttribute("style","display:none");
          });
          const geocoder = new google.maps.Geocoder();
          document.getElementById("btn_getLink").addEventListener("click", () => {
            //xóa marler cũ
            deleteMarker();
            let link = document.getElementById("inputLink").value;
            if(link != "")
            {
              if(link.lastIndexOf("https://www.google.com/maps/place") == '-1')
              {
                alert("{{ trans('admin.notGoogleMapLink') }}");
              }
              else
              {
                getNamePlace(geocoder,map);
              }
            }
            else
            {
              alert("{{ trans('admin.enterGoogleMapLink') }}");
            }
          });
          function deleteMarker()
          {
            $('.map-marker-label').remove();
            for (let i = 0; i < markers.length; i++) {
              markers[i].setMap(null);
            }
            markers=[];
          }
          function customLabel(marker) {
            deleteMarker();
            var label = marker.label;
            marker.label = new MarkerLabel({
              map: marker.map,
              marker: marker,
              text: label
            });
            marker.label.bindTo('position', marker, 'position');
            marker.setLabel('');
          };
          var MarkerLabel = function(options) {
            this.setValues(options);
            this.span = document.createElement('span');
            this.span.className = 'map-marker-label';
          };

          MarkerLabel.prototype = $.extend(new google.maps.OverlayView(), {
            onAdd: function() {
              this.getPanes().overlayImage.appendChild(this.span);
              var self = this;
              this.listeners = [
                google.maps.event.addListener(this, 'position_changed', function() {
                  self.draw();
                })
              ];
            },
            draw: function() {
              var markerSize = {
                x: 27,
                y: 43
              };
              var text = String(this.get('text'));
              var position = this.getProjection().fromLatLngToDivPixel(this.get('position'));
              this.span.innerHTML = text;
              // this.span.setAttribute('color',color);
              this.span.style.left = (position.x)+ 10 + 'px';
              this.span.style.top = (position.y) -15 + 'px';
            }
          });
          //submit form add
          $("#formAddPlace").submit(function(e) {
              let _token = $('meta[name="csrf-token"]').attr('content');
              e.preventDefault();
              var form = $(this);
              var formData = new FormData(this);
              var url = form.attr('action');
              $.ajax({
                    type: "POST",
                    url: url,
                     // data: form.serialize(),
                    data: formData,
                    success: function(data){
                      if(data == "false")
                      {
                        alert("{{ trans('admin.forgotEnterName') }}");
                      }
                      else
                      {
                        getDuration(data);
                      }
                    },
                    cache: false,
                    contentType: false,
                    processData: false 
              });
          });
          function getDuration(result)
          {
            var arr = [];
            var i = 1;
            var j = 0;
            while(i!=result.length){
              if(arr.length==0){
                arr.push(result[0]);
                j++;
              } 
              arr.push(result[i]);
              j++;
              i++;
              if(i == result.length || j == 5){
                locatsList = arr;
                getCoordinates(arr);
                j = 0;
                arr = [];
            }
          }

          function getCoordinates(arr){
            let _token = $('meta[name="csrf-token"]').attr('content');
            var ids = arr;
            $.ajax({
                url:"{{ route('admin.getLatLng') }}",
                type: 'post', 
                data: {_token:_token,array:ids}, 
                error: (err)=>{
                  alert("An error occured: " + err.status + " " + err.statusText);
                },
                success: (result)=>{    
                 distanceRequest(result,arr);
                 // console.log(result);
                }
            });
          }

          function distanceRequest(locats,arr){
              var geocoder = new google.maps.Geocoder();
              var service = new google.maps.DistanceMatrixService();
              service.getDistanceMatrix({
                origins: locats,
                destinations: locats,
                travelMode: google.maps.TravelMode.DRIVING,
              },(response,status)=>{
                console.log(status);
                var data = response.rows;
                var dataobj = [];
              for(var i = 0; i < data.length; i++){
                for(var j = 0; j < data.length; j++){
                  if(i == 0 && j>0){
                    dataobj.push({
                        pa_de_start: arr[i],
                        pa_de_end: arr[j],
                        pa_distance: data[i].elements[j].distance.value,
                        pa_duration: data[i].elements[j].duration.value
                      });
                  }
                  if(i != 0){
                    dataobj.push({
                        pa_de_start: arr[i],
                        pa_de_end: arr[0],
                        pa_distance: data[i].elements[0].distance.value,
                        pa_duration: data[i].elements[0].duration.value
                    });
                    break;
                  }
                }
              }
              //console.log(dataobj);
              //ajax
              let $url_path = '{!! url('/') !!}';
              let _token = $('meta[name="csrf-token"]').attr('content');
              let routeUpdatePath = $url_path+"/updatePath";
              let input = dataobj;
              $.ajax({
                  url:routeUpdatePath,
                  method:"GET",
                  data:{input:input},
                  success:function(data){ 
                    console.log(data);
                 }
              });
            });
            location.reload();
          }
        }
      };
      //var Commonlabel;
      function geocodeAddress(geocoder, resultsMap, label) {
        const address = checkLatLng();
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
            document.getElementById("btn_addPlace").setAttribute("style","display:block");
            document.getElementById("btn_addPlace_2").setAttribute("style","display:none");
          } else {
            alert("{{ trans('admin.geocodeNotSuccess') }} " + status);
          }
        });
      };
      function checkLatLng()
      {
          let link = document.getElementById("inputLink").value;
          var res = link.split("@");
          var res2 = res[1].split(",");
          return res2[0]+","+res2[1];
      }
     
      $(document).ready(function(){
        $("#btn_addPlace").click(function(){
          $("#Form_add").css("display","block");
          $("html, body").delay(200).animate({
              scrollTop: $('#Form_add').offset().top 
          }, 200);
          var latlng = checkLatLng().split(",");
          $("#inputLongitude").val(latlng[1]);
          $("#inputLatitude").val(latlng[0]);
          let x = $("#inputLongitude").val(); //p1
          let y = $("#inputLatitude").val(); //p1
          $("#googleLink").val($("#inputLink").val());

          //ajax
          let $url_path = '{!! url('/') !!}';
          let _token = $('meta[name="csrf-token"]').attr('content');
          let routeCheckPlace=$url_path+"/checkPlace";
          let inputLink = $("#inputLink").val();
          $.ajax({
              url:routeCheckPlace,
              method:"POST",
              data:{_token:_token,inputLink:inputLink},
              success:function(data){ 
                $("#inputName").val("");
                $("#inputName").val(data);
                $("#inputName_vn").val("");
                $("#inputName_vn").val(data);
                //$("#inputName").css("readonly","");
             }
          });
        });

        $("#btn_addPlace_2").click(function(){
          $("#Form_add").css("display","block");
          $("html, body").delay(200).animate({
              scrollTop: $('#Form_add').offset().top 
          }, 200);
          $("#inputLongitude").val(Coordinates.lng);
          $("#inputLatitude").val(Coordinates.lat);
          let x = $("#inputLongitude").val();
          let y = $("#inputLatitude").val();
          $("#googleLink").val("http://www.google.com/maps/place/"+y.toString()+","+x.toString());
        });
      });
      
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBgbjwIY5Q1eZ-Ejqur0a8avEQWowfA39s&callback=initMap" async defer></script>
@stop
