@extends('admin/layout/index')
@section('title')
    Edit Account
@parent
@stop
@section('header_styles')
	<link rel="stylesheet" href="{{asset('css/adminDashboard.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
  <style>
    .box2 a {
        color: white;
    }
    .user_content{display: block;}
    .user_content .editPlace{background: #eaecf4;}
    #link,#link_edit{overflow-x: auto;}
    textarea{
        min-height: 8rem;
    }
    #link, #link_vr {
        color: blue;
        text-decoration: underline;
        font-style: italic;
        font-weight: bold;
    }
    .btn_upload_edit {
        background: rgb(78,68,230);
        background: linear-gradient(
    90deg
    , rgb(130 125 214) 0%, rgb(17 101 155) 26%, rgb(147 147 223) 66%, rgba(0,212,255,1) 100%);
        color: white;
        font-weight: bold;
        text-align: center;
        padding: .5rem .2rem;
        width: 20%;
        cursor: pointer;
        border-radius: 1.3em;
        float: right;
    }
    .btn_upload_edit:hover{
      background: rgb(73,211,255);
      background: linear-gradient(90deg, rgba(73,211,255,1) 0%, rgba(60,74,218,1) 26%, rgba(153,138,235,1) 66%, rgba(0,168,255,1) 100%);
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
              <span style="font-size: 1.2rem;width: 20%" class="font-weight-bold font-italic">Language shown</span> 
              <select id="selectLang" class="form-control" style="width: 40%">
                <option hidden="">--Your choice--</option>
                <option selected="" value="en">English</option>
                <option value="vn">Tiếng việt</option>
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
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.NamePlace') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right pb-3" id="placeName"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Image') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6" id="placeImage">
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Longitude') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right pb-3" id="longitude"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Latitude') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right pb-3" id="latitude"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Description') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right pb-3" id="description"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Shortdes') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right pb-3" id="shortdes"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Duration') }} ({{ trans('admin.hours') }})</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right pb-3" id="duration"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.linkmap') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6 text-right">
              <a href="#" class="pb-3" id="link" target="_blank">{{ trans('admin.Linkhere') }}</a>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.linkVR') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6 text-right">
              <a href="#" class="pb-3" id="link_vr" target="_blank">{{ trans('admin.Linkhere') }}</a>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin.Close') }}</button>
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
          <input type="hidden" name="_token" value="{{ csrf_token() }}" />
          <div class="container-fluid">
          <div class="row">
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.NamePlace') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <input type="text" class="form-control mb-3" id="placeName_edit" name="placeName" placeholder="{{ trans('admin.NamePlace') }}">
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-2">Image</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6 text-right" id="div_edit_img">
            </div>
            <div class="col-md-12 col-sm-6 col-6 text-right mb-3">
              <input type="file" id="change_image_place"  style="display: none;" name="image">
              <p class="file_Name font-weight-bold font-italic"></p>
            </div>
            
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.Longitude') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <input type="text" name="longitude_edit" id="longitude_edit" class="form-control mb-3">
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.Latitude') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <input type="text" name="latitude_edit" id="latitude_edit" class="form-control mb-3">
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.Description') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <textarea class="form-control mb-3" id="description_edit" placeholder="{{ trans('admin.Description') }}" name="description"></textarea>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.Shortdes') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <textarea class="form-control mb-3" id="shortdes_edit" placeholder="{{ trans('admin.Shortdes') }}" name="shortdes"></textarea>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.Duration') }} ({{ trans('admin.hours') }})</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <input type="number" step="0.5" class="form-control mb-3" id="duration_edit" placeholder="{{ trans('admin.Duration') }}" required="" name="duration">
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.linkmap') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <input type="text" class="form-control mb-3" id="link_edit" name="link_edit">
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.linkVR') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <input type="text" class="form-control mb-3" id="vr_edit" placeholder="{{ trans('admin.linkVR') }}" required="" name="de_link">
            </div>
          </div>
        </div>
          <hr>
          <input type="submit" class="btn btn-danger" value="{{ trans('admin.editPlace') }}">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin.Close') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>
@stop
@section('footer-js')
	<!-- datatable -->
  	<script type="text/javascript" src="{{ asset('datatables/js/jquery.dataTables.js') }}" ></script>
  	<script type="text/javascript" src="{{ asset('datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
    <script type="text/javascript">
      var linkMapNotChange;
      $(document).ready(function(){
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
        });
    </script>
    <script type="text/javascript">
      $('#modalDetail').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var recipient = button.data('remove') 
        let $url_path = '{!! url('/') !!}';
        let language = $("#selectLang").val();
        let _token = $('meta[name="csrf-token"]').attr('content');
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
                  if(data[6] != "")
                  {
                    $("#link_vr").attr("href",data[6]);
                  }
                  if(data[7] != "")
                  {
                    $("#link").attr("href",data[7]);
                  }
                  if(data[8] != "") 
                  {
                    $("#placeImage").append('<a data-fancybox="gallery" href="'+data[8]+'"><img class="img-fluid rounded mb-5" style="width:100%;" src="'+data[8]+'" alt=""></a>');
                  }
                }
             }
        });
      })
      
      $('#modalEdit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var recipient = button.data('remove') 
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
                  $("#placeName_edit").val(data[0]);
                  $("#longitude_edit").val(data[1]);
                  $("#latitude_edit").val(data[2]);
                  $("#description_edit").html(data[3]);
                  $("#shortdes_edit").html(data[4]);
                  $("#duration_edit").val(data[5]);
                  $("#link_edit").val(data[7]);
                  $("#vr_edit").val(data[6]);
                  let routeActionForm = $url_path+"/formEditPlace/"+recipient+"/"+language;
                  $("#formEditPlace").attr("action",routeActionForm);
                  $("#div_edit_img").empty();
                  if(data[8] != "") 
                  {
                    $("#div_edit_img").append('<a data-fancybox="gallery" href="'+data[8]+'"><img class="img-fluid rounded mb-2" style="width:100%;" src="'+data[8]+'" alt=""></a>');
                    $("#div_edit_img").append('<div onclick="MyOnclick()" class="btn_upload_edit mb-3">Update Image</div>')
                  }
                  $(".file_Name").html("");
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
