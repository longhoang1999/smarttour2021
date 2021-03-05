@extends('admin/layout/index')
@section('title')
    View Account
@parent
@stop
@section('header_styles')
	<link rel="stylesheet" href="{{asset('css/adminDashboard.css')}}">
  <style>
    .box1 a {
        color: white;
    }
  </style>
@stop
@section('content')
	<div class="title"><p class="text-uppercase">account information in the system</p></div>
	<div class="AllClass_Table">
        <div class="AllClass_Table_title">
          <p>Account information in the system</p>
        </div>
        <div class="AllClass_Table_content">
            <table class="table table-bordered table-striped" id="Table_AllClass" style="margin-bottom: 10px;">
                  <thead>
                  <tr>
                      <th>Order</th>
                      <th>Email</th>
                      <th>FullName</th>
                      <th>Gender</th>
                      <th>Age</th>
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
          "order": [[ 1, 'asc' ]],
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.showAllAccount') !!}',
            order:[],
            columns: [
              { data: 'stt', name: 'stt' },
                { data: 'us_email', name: 'us_email' },
                { data: 'us_fullName', name: 'us_fullName' },
                { data: 'us_gender', name: 'us_gender' },
                { data: 'us_age', name: 'us_age' }
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