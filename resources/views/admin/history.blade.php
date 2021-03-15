@extends('admin/layout/index')
@section('title')
    View Feedback
@parent
@stop
@section('header_styles')
	<link rel="stylesheet" href="{{asset('css/adminDashboard.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
  <style>
    .box3 a {
        color: white;
    }
    div#Table_AllClass_Star_filter {
        float: right;
    }
  </style>
@stop
@section('content')
<!-- privious  tour-->
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
                      <th>{{ trans("admin.Startlocation") }}</th>
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
<!-- star tour -->
<div class="title"><p class="text-uppercase">The tours are shared and appreciated</p></div>
  <div class="AllClass_Table">
        <div class="AllClass_Table_title">
          <p>The tours are shared and appreciated</p>
        </div>
        <div class="AllClass_Table_content">
            <table class="table table-bordered table-striped" id="Table_AllClass_Star" style="margin-bottom: 10px;">
                  <thead>
                  <tr>
                      <th>{{ trans("admin.Order") }}</th>
                      <th>{{ trans("admin.Tourname") }}</th>
                      <th>{{ trans("admin.DetailTour") }}</th>
                      <th>Introduce</th>
                      <th>Average rating</th>
                      <th>{{ trans("admin.Actions") }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
        </div>
    </div>
    <!-- modal remove -->
    
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Warning</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p class="font-weight-bold text-danger font-italic">This action cannot be undone</p>
            <p>delete the tour from the category "shared tours"</p>
          </div>
          <div class="modal-footer">
            <a href="#" class="btn btn-danger">Delete</a>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /modal remove -->
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
  <!-- /Modal detail 1 -->

  <!-- Modal 2 -->
  <div class="modal fade" id="modalDetail2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <p id="textFullName_detail2"></p>
              </div>
              <!-- Tour Name-->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Tourname') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textNameTour_detail2"></p>
              </div>
              <!-- Start location -->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Startlocation') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textStart_detail2"></p>
              </div>
              <!-- Location list -->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Locationlist') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textLocatList_detail2"></p>
              </div>
              <!-- starting time -->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Starttime') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textStartTime_detail2"></p>
              </div>
              <!-- end time -->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Endtime') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textEndTime_detail2"></p>
              </div>
              <!-- Comback -->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Comeback') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textComeback_detail2"></p>
              </div>
              <!-- Optimized -->
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">{{ trans('admin.Optimized') }}</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="textOptimized_detail2"></p>
              </div>
            </div>
          </div>
          <hr>
          <div class="container-fuild">
            <div class="row">
              
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">Introduce</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="introduce_detail2"></p>
              </div>
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">Average rating</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3">
                <p id="number_star_detail2"></p>
              </div>
              <div class="col-md-4 col-sm-6 col-6 mb-3">
                <p class="font-weight-bold text-italic">Image</p>
              </div>
              <div class="col-md-8 col-sm-6 col-6 mb-3" id="image_detail2">
                
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-danger" target="_blank">Edit tour</a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('admin.Close') }}</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Model detail -2 -->
@stop
@section('footer-js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
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
          "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "All"]],
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
		        	});
			       });
        });
    </script>
    <script>
    $(function() {
        var table = $('#Table_AllClass_Star').DataTable({
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
          "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "All"]],
          "order": [[ 1, 'asc' ]],
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.showAllRouteRating') !!}',
            order:[],
            columns: [
              { data: 'stt', name: 'stt' },
                { data: 'tourName', name: 'tourName' },
                { data: 'Detail', name: 'Detail' },
                { data: 'content', name: 'content' },
                { data: 'avg', name: 'avg' },
                { data: 'actions', name: 'actions' },
                ]
            });
            table.on( 'draw.dt', function () {
             var PageInfo = $('#Table_AllClass').DataTable().page.info();
              table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                  cell.innerHTML = i + 1 + PageInfo.start;
              });
             });
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
    <script type="text/javascript">
      $(document).ready(function(){
        $('#modalDetail2').on('shown.bs.modal', function (event) {
            let $url_path = '{!! url('/') !!}';
            let _token = $('meta[name="csrf-token"]').attr('content');
            let button = $(event.relatedTarget)
            recipient = button.data('id');
            let routeDetail=$url_path+"/routeDetail2";
            $.ajax({
              url:routeDetail,
              method:"POST",
              data:{_token:_token,recipient:recipient},
              success:function(data){ 
                $("#textFullName_detail2").empty();
                $("#textNameTour_detail2").empty();
                $("#textStart_detail2").empty();
                $("#textLocatList_detail2").empty();
                $("#textStartTime_detail2").empty();
                $("#textEndTime_detail2").empty();
                $("#textComeback_detail2").empty();
                $("#textOptimized_detail2").empty();
                $("#introduce_detail2").empty();
                $("#number_star_detail2").empty();
                $("#image_detail2").empty();
                
                $("#textFullName_detail2").append(data[0]);
                $("#textNameTour_detail2").append(data[1]);
                $("#textStart_detail2").append(data[2]);
                $("#textLocatList_detail2").append(data[3]);
                $("#textStartTime_detail2").append(data[4]);
                $("#textEndTime_detail2").append(data[5]);
                $("#textComeback_detail2").append(data[6]);
                $("#textOptimized_detail2").append(data[7]);
                $("#introduce_detail2").append(data[8]);
                $("#number_star_detail2").append(data[9]+ '<i class="fas fa-star text-warning"></i>');
                if(data[10] != "")
                {
                  $("#image_detail2").append('<a data-fancybox="gallery" href="'+data[10]+'"><img class="img-fluid rounded mb-5" style="width:100%;" src="'+data[10]+'" alt=""></a>');
                }
                else
                {
                  $("#image_detail2").append('<span class="badge badge-warning">Not available</span>');
                }
              }
            });
            var routeView=$url_path+"/editTour/"+recipient;
            var modal = $(this)
            modal.find('.modal-footer a').prop("href",routeView);
        });
        $('#modalDelete').on('shown.bs.modal', function (event) {
            var $url_path = '{!! url('/') !!}';
            var button = $(event.relatedTarget)
            recipient = button.data('id');
            var routeDelete=$url_path+"/sharetourDelete/"+recipient;
            var modal = $(this)
            modal.find('.modal-footer a').prop("href",routeDelete);
        });
      });
    </script>
    
@stop