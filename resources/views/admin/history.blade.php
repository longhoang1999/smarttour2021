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
	<div class="title"><p class="text-uppercase">information on previous tour</p></div>
	<div class="AllClass_Table">
        <div class="AllClass_Table_title">
          <p>information on previous tour</p>
        </div>
        <div class="AllClass_Table_content">
            <a href="{{route('user.maps')}}" target="_blank" class="btn btn-info">Add a new tour</a>
            <table class="table table-bordered table-striped" id="Table_AllClass" style="margin-bottom: 10px;">
                  <thead>
                  <tr>
                      <th>{{ trans("admin.Order") }}</th>
                      <th>Name of creator</th>
                      <th>Start location</th>
                      <th>Detail</th>
                      <th>Start time</th>
                      <th>End time</th>
                      <th>{{ trans("admin.Actions") }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
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
                { data: 'fullName', name: 'fullName' },
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
@stop