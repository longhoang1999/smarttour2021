@extends('admin/layout/index')
@section('title')
    {{ trans('admin.deletePlace') }}
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
    .contents{color: white}
    .user_content .removePlace{background: #eaecf4;}
    #link{overflow-x: auto;}
    #link, #link_vr {
        color: blue;
        text-decoration: underline;
        font-style: italic;
        font-weight: bold;
    }
    .img-fluid{
      width: 100%;
      max-height: 20rem !important;
    }
  </style>
@stop
@section('content')
  @if ($message = Session::get('status'))
      <div class="alert alert-danger alert-block">
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{$message}}</strong>
      </div>
  @endif
	<div class="title"><p class="text-uppercase">{{ trans('admin.titlePageRemove') }}</p></div>
	<div class="AllClass_Table">
        <div class="AllClass_Table_title">
          <p class="text-lowercase">{{ trans('admin.titlePageRemove') }}</p>
        </div>
        <div class="AllClass_Table_content">
            <div style="display: flex;">
              <span style="font-size: 1.2rem;width: 20%" class="font-weight-bold font-italic">{{ trans('admin.languageShown') }}</span> 
              <select id="selectLang" class="form-control" style="width: 40%">
                <option hidden="">--{{ trans('admin.yourChoice') }}--</option>
                <option selected="" value="en">English</option>
                <option value="vn">Tiếng việt</option>
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
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.NamePlace') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-justify pb-3" id="placeName"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.typeOfPlace') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6 text-justify" id="placeType">
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
              <p class="text-justify pb-3" id="longitude"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Latitude') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-justify pb-3" id="latitude"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Description') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-justify pb-3" id="description"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.Shortdes') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-justify pb-3" id="shortdes"></p>
            </div>
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
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left pb-3">{{ trans('admin.linkmap') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6 text-justify">
              <a href="#" class="pb-3" id="link" target="_blank">{{ trans('admin.Linkhere') }}</a>
            </div>
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
<!-- Modal Remove -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDeleteLabel">{{ trans('admin.Warning') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h5 class="text-danger">{{ trans('admin.wantDelete') }}</h5>
      </div>
      <div class="modal-footer">
        <a href="#" id="removePlace" class="btn btn-danger">{{ trans('admin.Remove') }}</a>
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
            ajax: '{!! route('admin.showDestinationRemove') !!}',
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
                var routeEN = $url_path+"/showDestinationRemove";
                table.ajax.url( routeEN ).load();
              }
              else if($("#selectLang").val() == "vn")
              {
                var routeVN = $url_path+"/showDestinationRemoveVN";
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
                  var routeEN = $url_path+"/showDestinationRemove";
                  table.ajax.url( routeEN ).load();
                }
                else if($("#selectLang").val() == "vn")
                {
                  var routeVN = $url_path+"/showDestinationRemoveVN";
                  table.ajax.url( routeVN ).load();
                }
              }
              else
              {
                var routeType = $url_path+"/showDestinationRemoveType/"+type+"/"+takeLang;
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

      $('#modalDelete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var recipient = button.data('remove')
        var $url_path = '{!! url('/') !!}';
        var routeDelete=$url_path+"/placeDelete/"+recipient;
        var modal = $(this)
        modal.find('.modal-footer a').prop("href",routeDelete);
      })
    </script>
@stop
