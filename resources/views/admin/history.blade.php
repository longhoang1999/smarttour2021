@extends('admin/layout/index')
@section('title')
    View Feedback
@parent
@stop
@section('header_styles')
	<link rel="stylesheet" href="{{asset('css/adminDashboard.css')}}">
  <style>
    .box3 a {
        color: white;
    }
  </style>
@stop
@section('content')
	<div class="title"><p class="text-uppercase">{{ trans('admin.historyTitle') }}</p></div>
	<div class="AllClass_Table">
        <div class="AllClass_Table_title">
          <p>{{ trans('admin.historyTitle') }}</p>
        </div>
        <div class="AllClass_Table_content">
            <a href="{{route('user.maps')}}" target="_blank" class="btn btn-info">{{ trans('admin.Addanewtour') }}</a>
            <table class="table table-bordered table-striped" id="Table_AllClass" style="margin-bottom: 10px;">
                  <thead>
                  <tr>
                      <th>{{ trans("admin.Order") }}</th>
                      <th>{{ trans("admin.Tourname") }}</th>
                      <th>{{ trans("admin.Startlocation") }} (lat,lng)</th>
                      <th>{{ trans("admin.DetailTour") }}</th>
                      <th>{{ trans("admin.Starttime") }}</th>
                      <th>{{ trans("admin.Endtime") }}</th>
                      <th>{{ trans("admin.Actions") }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
        </div>
    </div>
  <!-- Modal -->
  <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ trans('admin.Routedetails') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row">
              <!-- Name of creator -->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Nameofcreator') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textFullName"></p>
              </div>
              <!-- Tour Name-->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Tourname') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textNameTour"></p>
              </div>
              <!-- Start location -->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Startlocation') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textStart"></p>
              </div>
              <!-- Location list -->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Locationlist') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textLocatList"></p>
              </div>
              <!-- starting time -->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Starttime') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textStartTime"></p>
              </div>
              <!-- end time -->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Endtime') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textEndTime"></p>
              </div>
              <!-- Comback -->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Comeback') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textComeback"></p>
              </div>
              <!-- Optimized -->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Optimized') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textOptimized"></p>
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
@stop
@section('footer-js')
	<!-- datatable -->
  	<script type="text/javascript" src="{{ asset('datatables/js/jquery.dataTables.js') }}" ></script>
  	<script type="text/javascript" src="{{ asset('datatables/js/dataTables.bootstrap4.js') }}" ></script>
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
          "order": [[ 1, 'asc' ]],
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.showAllRoute') !!}',
            order:[],
            columns: [
              { data: 'stt', name: 'stt' },
                { data: 'to_name', name: 'to_name' },
                { data: 'startLocat', name: 'startLocat' },
                { data: 'Detail', name: 'Detail' },
                { data: 'startTime', name: 'startTime' },
                { data: 'endTime', name: 'endTime' },
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
      $(document).ready(function(){
        $('#modalDetail').on('shown.bs.modal', function (event) {
            let $url_path = '{!! url('/') !!}';
            let _token = $('meta[name="csrf-token"]').attr('content');
            let button = $(event.relatedTarget)
            recipient = button.data('id');
            let routeDetail=$url_path+"/routeDetail";
            $.ajax({
              url:routeDetail,
              method:"POST",
              data:{_token:_token,recipient:recipient},
              success:function(data){ 
                $("#textFullName").empty();
                $("#textNameTour").empty();
                $("#textStart").empty();
                $("#textLocatList").empty();
                $("#textStartTime").empty();
                $("#textEndTime").empty();
                $("#textComeback").empty();
                $("#textOptimized").empty();
                $("#textFullName").append(data[0]);
                $("#textNameTour").append(data[1]);
                $("#textStart").append(data[2]);
                $("#textLocatList").append(data[3]);
                $("#textStartTime").append(data[4]);
                $("#textEndTime").append(data[5]);
                $("#textComeback").append(data[6]);
                $("#textOptimized").append(data[7]);
              }
            });
        });
      });
    </script>
@stop