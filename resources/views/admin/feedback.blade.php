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
                      <th>Content</th>
                      <th>Star</th>
                      <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail Feedback</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6 col-sm-6 col-6">
              <h6 class="font-weight-bold">Email</h6>
            </div>
            <div class="col-md-6 col-sm-6 col-6">
              <p class="textEmail"></p>
            </div>
            <div class="col-md-6 col-sm-6 col-6">
              <h6 class="font-weight-bold">FullName</h6>
            </div>
            <div class="col-md-6 col-sm-6 col-6">
              <p class="textFullName"></p>
            </div>
            <div class="col-md-6 col-sm-6 col-6">
              <h6 class="font-weight-bold">Feedback Content</h6>
            </div>
            <div class="col-md-6 col-sm-6 col-6">
              <p class="textContent"></p>
            </div>
            <div class="col-md-6 col-sm-6 col-6">
              <h6 class="font-weight-bold">Number Star</h6>
            </div>
            <div class="col-md-6 col-sm-6 col-6">
              <p class="textStar"></p>
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
            ajax: '{!! route('admin.showAllFeedback') !!}',
            order:[],
            columns: [
              { data: 'stt', name: 'stt' },
                { data: 'email', name: 'email' },
                { data: 'fullName', name: 'fullName' },
                { data: 'content', name: 'content' },
                { data: 'star', name: 'star' },
                { data: 'action', name: 'action' },
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
      var $url_path = '{!! url('/') !!}';
      var _token = $('meta[name="csrf-token"]').attr('content');
      $('#exampleModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var recipient = button.data('id');
            var routeDetail=$url_path+"/detaiFeedback/"+recipient;
            $.ajax({
                url:routeDetail,
                method:"POST",
                data:{_token:_token},
                success:function(data){ 
                    $(".textEmail").empty();
                    $(".textFullName").empty();
                    $(".textContent").empty();
                    $(".textStar").empty();
                    $(".textEmail").append(data[0]);
                    $(".textFullName").append(data[1]);
                    $(".textContent").append(data[2]);
                    $(".textStar").append(data[3]);
               }
          });
        });
    </script>
@stop