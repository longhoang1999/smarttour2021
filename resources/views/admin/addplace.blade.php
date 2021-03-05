@extends('admin/layout/index')
@section('title')
    View Account
@parent
@stop
@section('header_styles')
	<link rel="stylesheet" href="{{asset('css/adminDashboard.css')}}">
  <style>
    .box2 a {
        color: white;
    }
    .user_content{display: block;}
    .user_content .addPlace{background: #eaecf4;}
    #map{height: 500px;width: 100%}
    .mapBorder{
      margin-bottom: 1rem;
      width: 100%;
      padding: .5rem;
      box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
      border-radius: 5px;
    }
    #btn_addPlace,#btn_addPlace_2{display: none;}
    #Form_add{display: none;}
    #link{overflow-x: auto;}
  </style>
@stop
@section('content')
  @if ($message = Session::get('status'))
      <div class="alert alert-danger alert-block">
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{$message}}</strong>
      </div>
  @endif
	<div class="title"><p class="text-uppercase">information of places in the system</p></div>
	<div class="AllClass_Table">
        <div class="AllClass_Table_title">
          <p>Information of places in the system</p>
        </div>
        <div class="AllClass_Table_content">
            <table class="table table-bordered table-striped" id="Table_AllClass" style="margin-bottom: 10px;">
                  <thead>
                  <tr>
                      <th>Order</th>
                      <th>Name</th>
                      <th>Longitude</th>
                      <th>Latitude</th>
                      <th>Duration</th>
                      <th>Actions</th>
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
        <h5 class="modal-title" id="modalDetailLabel">Detail Place</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">Place Name</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right pb-3" id="placeName"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">Longitude</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right pb-3" id="longitude"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">Latitude</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right pb-3" id="latitude"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">Description</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right pb-3" id="description"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">Shortdes</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right pb-3" id="shortdes"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">Duration (hours)</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right pb-3" id="duration"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">Link</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right pb-3" id="link"></p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- /Modal -->
  <div class="title"><p class="text-uppercase">add the location to the database</p></div>
  <small class="text-info">(Get google map url or click on the map below to add locations)</small>
  <div class="container-fluid">
    <div class="form-group">
      <label for="inputLink">Link google map</label>
      <div class="row">
        <div class="col-md-9 col-12 col-sm-12">
          <input type="text" class="form-control" id="inputLink" placeholder="Get link google map" required="">
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
  <button class="btn btn-info" id="btn_addPlace">Add place according to the link</button>
  <button class="btn btn-info" id="btn_addPlace_2">Add Place according click map</button>

  <div class="AllClass_Table" id="Form_add">
        <div class="AllClass_Table_title">
          <p>Information of the location you choose</p>
        </div>
        <div class="AllClass_Table_content">
            <form action="{{route('admin.postaddPlace')}}" method="post" id="formAddPlace" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <div class="form-group">
                <label for="inputLongitude">Longitude</label>
                <input type="text" class="form-control" id="inputLongitude" placeholder="Enter longitude" required="" readonly="" name="de_lng">
              </div>
              <div class="form-group">
                <label for="inputLatitude">Latitude</label>
                <input type="text" class="form-control" id="inputLatitude" placeholder="Enter latitude" required="" readonly="" name="de_lat">
              </div>
              <div class="form-group">
                <label for="inputName">Name</label>
                <input type="text" class="form-control" id="inputName" placeholder="Enter name" required="" name="de_name">
              </div>
              <style>
                .open_inputFile{
                  display: block;
                  width: 10%;
                  text-align: center;
                  color: white;
                  margin: 1rem 0;
                  padding: 0.4rem;
                  border-radius: 1rem;
                  cursor: pointer;
                  background: rgb(88,92,186);
                  background: linear-gradient(
              90deg
              , rgba(88,92,186,1) 0%, rgba(74,155,219,1) 35%, rgba(96,136,242,1) 72%, rgba(20,181,232,1) 100%);
                }
                .open_inputFile:hover{
                  background: rgb(61,63,122);
                  background: linear-gradient(90deg, rgba(61,63,122,1) 0%, rgba(59,119,167,1) 35%, rgba(78,106,181,1) 72%, rgba(15,143,184,1) 100%);
                }
                #inputImage{display: none;}
                .file_name{font-style: italic; font-weight: 600;display: none;}
              </style>    
              <div class="form-group">
                <label for="inputName">Image</label>
                <div class="open_inputFile">Upload here</div>
                <p class="file_name">âcscas</p>
                <input type="file" class="form-control" id="inputImage" name="de_image" accept=".jpg,.png">
              </div>
              <div class="form-group">
                <label for="inputDescription">Description</label>
                <textarea class="form-control" id="inputDescription" required="" placeholder="Enter description" name="de_description"></textarea>
              </div>
              <div class="form-group">
                <label for="inputShortdes">Brief description</label>
                <input type="text" class="form-control" id="inputShortdes" placeholder="Enter brief description" required="" name="de_shortdes">
              </div>
              <div class="form-group">
                <label for="inputDuration">Average travel time (hours)</label>
                <input type="number" class="form-control" id="inputDuration" placeholder="Enter duration" required="" name="de_duration" step="0.1">
              </div>
              <div class="form-group">
                <label for="googleLink">Google link</label>
                <input type="text" class="form-control" id="googleLink" readonly="" required="" name="de_link">
              </div>
              <button type="submit" class="btn btn-primary">Add Place</button>
            </form>
        </div>
    </div>

@stop
@section('footer-js')
	<!-- datatable -->
  	<script type="text/javascript" src="{{ asset('datatables/js/jquery.dataTables.js') }}" ></script>
  	<script type="text/javascript" src="{{ asset('datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script type="text/javascript">
      $(document).ready(function(){
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
                { data: 'actions', name: 'actions' },
                ]
            });
            table.on( 'draw.dt', function () {
          var PageInfo = $('#Table_AllClass').DataTable().page.info();
              table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                  cell.innerHTML = i + 1 + PageInfo.start;
              } );
      } );
        });
    </script>
    <!-- detail -->
    <script type="text/javascript">
      $('#modalDetail').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var recipient = button.data('remove') 
        let $url_path = '{!! url('/') !!}';
        let _token = $('meta[name="csrf-token"]').attr('content');
        let routeShowDetail=$url_path+"/showDetail/"+recipient;
        $.ajax({
              url:routeShowDetail,
              method:"GET",
              data:{_token:_token},
              success:function(data){ 
                if(data =="Can not find data")
                  alert(data)
                else
                {
                  $("#placeName").html(data[0]);
                  $("#longitude").html(data[1]);
                  $("#latitude").html(data[2]);
                  $("#description").html(data[3]);
                  $("#shortdes").html(data[4]);
                  $("#duration").html(data[5]);
                  $("#link").html(data[6]);
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
              }),
          directionsService = new google.maps.DirectionsService();
          map.addListener('click',function(evt){
            var staMarker = new google.maps.Marker({
              label: 'Your start location',
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
              getNamePlace(geocoder,map);
            }
            else
            {
              alert("Please enter a google map link");
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
                    success: getDuration,
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
            alert("Geocode was not successful for the following reason: " + status);
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
                $("#inputName").val(data);
                $("#inputName").css("readonly","");
             }
          });
        });

        $("#btn_addPlace_2").click(function(){
          $("#Form_add").css("display","block");
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
