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

<!-- Modal Remove -->
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
        <form action="" method="post" id="formEditPlace">
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
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.Longitude') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right mb-3" id="longitude_edit"></p>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
              <p class="font-weight-bold text-left mb-3">{{ trans('admin.Latitude') }}</p>
            </div>
            <div class="col-md-9 col-sm-6 col-6">
              <p class="text-right mb-3" id="latitude_edit"></p>
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
              <input type="text" class="form-control mb-3" id="link_edit" readonly="">
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
              } );
      } );
        });
    </script>
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
        let _token = $('meta[name="csrf-token"]').attr('content');
        let routeShowDetail=$url_path+"/showDetailEdit/"+recipient;
        $.ajax({
              url:routeShowDetail,
              method:"GET",
              data:{_token:_token},
              success:function(data){ 
                if(data =="Can not find data")
                  alert(data)
                else
                {
                  $("#placeName_edit").val(data[0]);
                  $("#longitude_edit").html(data[1]);
                  $("#latitude_edit").html(data[2]);
                  $("#description_edit").html(data[3]);
                  $("#shortdes_edit").html(data[4]);
                  $("#duration_edit").val(data[5]);
                  $("#link_edit").val(data[7]);
                  $("#vr_edit").val(data[6]);
                  let routeActionForm = $url_path+"/formEditPlace/"+recipient;
                  $("#formEditPlace").attr("action",routeActionForm);
                  
                }
             }
        });
      })
    </script>
@stop
