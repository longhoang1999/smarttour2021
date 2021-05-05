@extends('admin/layout/index')
@section('title')
    Edit Account
@parent
@stop
@section('header_styles')
	<link rel="stylesheet" href="{{asset('css/adminDashboard.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
  <link rel="stylesheet" href="{{asset('css/editPlace.css')}}">
  <style>
    .contents{color: white}
    .img-fluid{
      width: 100%;
      max-height: 20rem !important;
    }
  </style>
@stop
@section('content')
  @if ($message = Session::get('status'))
      <div class="alert alert-success alert-block">
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{$message}}</strong>
      </div>
  @endif
	<div class="title"><p class="text-uppercase">{{ trans('admin.editLoaction') }}</p></div>
	<div class="AllClass_Table">
        <div class="AllClass_Table_title">
          <p class="text-lowercase">{{ trans('admin.editLoaction') }}</p>
        </div>
        <div class="AllClass_Table_content">
            <div style="display: flex;">
              <span style="font-size: 1.2rem;width: 20%" class="font-weight-bold font-italic">{{ trans('admin.languageShown') }}</span> 
              <select id="selectLang" class="form-control" style="width: 40%">
                <option hidden="">--Your choice--</option>
                <option selected="" value="en">English</option>
                <option value="vn">Tiếng việt</option>
              </select>
            </div>
            <div style="display: flex; margin-top: 1.5rem">
              <span style="font-size: 1.2rem;width: 20%" class="font-weight-bold font-italic">Type of place</span> 
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
                      <th>Created by</th>
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
              <p class="font-weight-bold text-left pb-3">Type of place</p>
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

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDeleteLabel">{{ trans('admin.editPlaces') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="post" id="formEditPlace" enctype="multipart/form-data">
          <input type="hidden" id="defaultPlace" name="de_default">
          <input type="hidden" name="_token" value="{{ csrf_token() }}" />
          <div class="container-fluid">
          <div class="row">
            <!-- name place -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.NamePlace') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <input type="text" class="form-control mb-3" id="placeName_edit" name="placeName" placeholder="{{ trans('admin.NamePlace') }}">
            </div>
            <!-- type place -->
            <div class="col-md-3 col-sm-6 col-6 mb-3">
              <p class="font-weight-bold text-left mb-3">Type of place</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6 mb-3">
                <select id="selectType_edit" class="form-control" style="width: 40%" name="type">
                  @foreach($typeplace_edit as $type)
                  <option value="{{$type->id}}">{{$type->nametype}}</option>
                  @endforeach
                </select>
                <span class="badge badge-success" id="typePlaceUser">Success</span>
            </div>
            <!-- image -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-2">Image</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6 text-right" id="div_edit_img">
            </div>
            <div class="col-md-12 col-sm-6 col-6 text-right mb-3">
              <input type="file" id="change_image_place"  style="display: none;" name="image">
              <p class="file_Name font-weight-bold font-italic"></p>
            </div>
            <!-- lng -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.Longitude') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <input type="text" name="longitude_edit" id="longitude_edit" class="form-control mb-3">
            </div>
            <!-- lat -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.Latitude') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <input type="text" name="latitude_edit" id="latitude_edit" class="form-control mb-3">
            </div>
            <!-- des -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.Description') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <textarea class="form-control mb-3" id="description_edit" placeholder="{{ trans('admin.Description') }}" name="description"></textarea>
            </div>
            <!-- short -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.Shortdes') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <textarea class="form-control mb-3" id="shortdes_edit" placeholder="{{ trans('admin.Shortdes') }}" name="shortdes"></textarea>
            </div>
            <!-- duration -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.Duration') }} ({{ trans('admin.hours') }})</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <input type="number" step="0.5" class="form-control mb-3" id="duration_edit" placeholder="{{ trans('admin.Duration') }}" required="" name="duration">
            </div>
            <!-- cost -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.cost') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6 form-group">
              <span class="show_yourCost"> -Bạn đã nhập: <span class="show_money"></span></span>
              <div class="enterCost_block">
                <select name="currency" class="form-control" id="selectCurrency">
                  <option selected="true" value="VNĐ">VNĐ</option>
                  <option value="USD">USD</option>
                </select>
                <input type="number" class="form-control" id="inputCost" placeholder="Enter Cost" required="" name="de_cost" step="0.01">
              </div>
            </div>

            <!-- link map -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.linkmap') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <input type="text" class="form-control mb-3" id="link_edit" name="link_edit">
            </div>
            <!-- link vr -->
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.linkVR') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <input type="text" class="form-control mb-3" id="vr_edit" placeholder="{{ trans('admin.linkVR') }}" name="de_link">
            </div>
          </div>
        </div>
          <div class="modal-footer">
            <input type="submit" class="btn btn-danger" value="{{ trans('admin.editPlace') }}">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Edit -->

@stop
@section('footer-js')
	<!-- datatable -->
  	<script type="text/javascript" src="{{ asset('datatables/js/jquery.dataTables.js') }}" ></script>
  	<script type="text/javascript" src="{{ asset('datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
    <script type="text/javascript">
      var linkMapNotChange;
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
          var Commonlabel = "";
          $("#longitude_edit").change(function(){
              let longitude = $("#longitude_edit").val();
              let latitude = $("#latitude_edit").val();
              $("#link_edit").val("http://www.google.com/maps/place/"+latitude.toString()+","+longitude.toString());
          });
          $("#latitude_edit").change(function(){
              let longitude = $("#longitude_edit").val();
              let latitude = $("#latitude_edit").val();
              $("#link_edit").val("http://www.google.com/maps/place/"+latitude.toString()+","+longitude.toString());
          });
          $("#link_edit").change(function(){
            if($("#link_edit").val().lastIndexOf("https://www.google.com/maps/place") == '-1')
            {
              alert("The path you entered is not the path of google map");
              $("#link_edit").val(linkMapNotChange);
            }
            else
            {
              var latlng = checkLatLng().split(",");
              $("#longitude_edit").val(latlng[1]);
              $("#latitude_edit").val(latlng[0]);

              let $url_path = '{!! url('/') !!}';
              let _token = $('meta[name="csrf-token"]').attr('content');
              let routeCheckPlace=$url_path+"/checkPlace";
              let inputLink = $("#link_edit").val();
              $.ajax({
                    url:routeCheckPlace,
                    method:"POST",
                    data:{_token:_token,inputLink:inputLink},
                    success:function(data){ 
                      $("#placeName_edit").val(data);
                   }
              });
            }
          });
      });
      function checkLatLng()
      {
          let link = document.getElementById("link_edit").value;
          var res = link.split("@");
          var res2 = res[1].split(",");
          return res2[0]+","+res2[1];
      }
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
              processing: true,
              serverSide: true,
              ajax: '{!! route('admin.showDestinationEdit') !!}',
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
                });
            });
            $("#selectLang").change(function(){
              let $url_path = '{!! url('/') !!}';
              $("#selectType").val("All");
              if($("#selectLang").val() == "en")
              {
                var routeEN = $url_path+"/showDestinationEdit";
                table.ajax.url( routeEN ).load();
              }
              else if($("#selectLang").val() == "vn")
              {
                var routeVN = $url_path+"/showDestinationEditVN";
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
                  var routeEN = $url_path+"/showDestinationEdit";
                  table.ajax.url( routeEN ).load();
                }
                else if($("#selectLang").val() == "vn")
                {
                  var routeVN = $url_path+"/showDestinationEditVN";
                  table.ajax.url( routeVN ).load();
                }
              }
              else
              {
                var routeType = $url_path+"/showDestinationEditType/"+type+"/"+takeLang;
                table.ajax.url( routeType ).load();
              }
            });
        });
    </script>
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
                if(data =="Can not find data")
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
      $('#modalEdit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var recipient = button.data('remove') 
        var typeplace = button.data('typeplace')
        let $url_path = '{!! url('/') !!}';
        let language = $("#selectLang").val();
        let _token = $('meta[name="csrf-token"]').attr('content');
        let routeShowDetail=$url_path+"/showDetailEdit/"+recipient+"/"+language;
        $.ajax({
              url:routeShowDetail,
              method:"GET",
              data:{_token:_token},
              success:function(data){ 
                if(data =="Can not find data")
                  alert(data)
                else
                {
                  linkMapNotChange = data[7];
                  $("#defaultPlace").val(data[11]);
                  $("#placeName_edit").val(data[0]);
                  $("#longitude_edit").val(data[1]);
                  $("#latitude_edit").val(data[2]);
                  $("#description_edit").html(data[3]);
                  $("#shortdes_edit").html(data[4]);
                  $("#duration_edit").val(data[5]);
                  $("#link_edit").val(data[7]);
                  $("#vr_edit").val(data[6]);

                  if($("#selectLang").val() == "en")
                  {
                    $("#selectCurrency").val("USD");
                    $(".show_yourCost").show();
                    let moneyUSD = (parseFloat(data[12])/23000).toFixed(2); 
                    $(".show_money").text(moneyUSD.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + $("#selectCurrency").val());
                    $("#inputCost").val(moneyUSD);
                  }
                  else if($("#selectLang").val() == "vn")
                  {
                    $("#selectCurrency").val("VNĐ");
                    $(".show_yourCost").show();
                    $(".show_money").text(data[12].toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + $("#selectCurrency").val());
                    $("#inputCost").val(data[12]);
                  }
            
                  let routeActionForm = $url_path+"/formEditPlace/"+recipient+"/"+language;
                  $("#formEditPlace").attr("action",routeActionForm);
                  $("#div_edit_img").empty();
                  if(data[8] != "") 
                  {
                    $("#div_edit_img").append('<a data-fancybox="gallery" href="'+data[8]+'"><img class="img-fluid rounded mb-2" style="width:100%;" src="'+data[8]+'" alt=""></a>');
                    $("#div_edit_img").append('<div onclick="MyOnclick()" class="btn_upload_edit mb-3">Update Image</div>')
                  }
                  else
                  {
                    $("#div_edit_img").append('<a data-fancybox="gallery" href="{{asset("imgPlace/empty.png")}}"><img class="img-fluid rounded mb-5" style="width:100%;" src="{{asset("imgPlace/empty.png")}}" alt=""></a>');
                    $("#div_edit_img").append('<div onclick="MyOnclick()" class="btn_upload_edit mb-3">Update Image</div>')
                  }
                  $(".file_Name").html("");
                  if(typeplace == "0")
                  {
                    $("#selectType_edit").show();
                    $("#typePlaceUser").hide();
                    $("#selectType_edit").val(data[9]);
                  }
                  else if(typeplace == "1")
                  {
                    $("#selectType_edit").hide();
                    $("#typePlaceUser").show();
                    $("#typePlaceUser").html(data[10]);
                  }
                }
             }
        });
      })
      function MyOnclick()
      {
        $("#change_image_place").click();
      }
      $("#change_image_place").change(function(){
        $(".btn_upload_edit").css("background","rgb(255,210,73)");
        $(".btn_upload_edit").css("background","linear-gradient(90deg, rgba(255,210,73,1) 0%, rgba(218,160,60,1) 26%, rgba(235,205,138,1) 66%, rgba(255,188,0,1) 100%)");
        $(".file_Name").html("File name: &#60;"+$("#change_image_place").val().split('\\').pop()+"&#62;");
      });
    </script>
@stop
