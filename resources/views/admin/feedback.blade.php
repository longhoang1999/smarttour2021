@extends('admin/layout/index')
@section('title')
    View Feedback
@parent
@stop
@section('header_styles')
	<link rel="stylesheet" href="{{asset('css/adminDashboard.css')}}">
  <style>
    .box4 a {
        color: white;
    }
  </style>
@stop
@section('content')
	<div class="title"><p class="text-uppercase">{{ trans("admin.infoFeedback") }}</p></div>
  @if ($message = Session::get('error'))
      <div class="alert alert-danger alert-block">
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{$message}}</strong>
      </div>
  @endif
  @if ($message = Session::get('success'))
      <div class="alert alert-success alert-block">
          <button type="button" class="close" data-dismiss="alert">x</button>
          <strong>{{$message}}</strong>
      </div>
  @endif
	<div class="AllClass_Table">
        <div class="AllClass_Table_title">
          <p>{{ trans("admin.infoFeedback") }}</p>
        </div>
        <div class="AllClass_Table_content">
            <table class="table table-bordered table-striped" id="Table_AllClass" style="margin-bottom: 10px;">
                  <thead>
                  <tr>
                      <th>{{ trans("admin.Order") }}</th>
                      <th>Email</th>
                      <th>{{ trans("admin.FullName") }}</th>
                      <th>{{ trans("admin.Content") }}</th>
                      <th>{{ trans("admin.Star") }}</th>
                      <th>Share feedback</th>
                      <th>{{ trans("admin.Actions") }}</th>
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
        <h5 class="modal-title" id="exampleModalLabel">{{ trans("admin.DetailFeedback") }}</h5>
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
              <h6 class="font-weight-bold">{{ trans("admin.FullName") }}</h6>
            </div>
            <div class="col-md-6 col-sm-6 col-6">
              <p class="textFullName"></p>
            </div>
            <div class="col-md-6 col-sm-6 col-6">
              <h6 class="font-weight-bold">{{ trans("admin.Content") }}</h6>
            </div>
            <div class="col-md-6 col-sm-6 col-6">
              <p class="textContent"></p>
            </div>
            <div class="col-md-6 col-sm-6 col-6">
              <h6 class="font-weight-bold">{{ trans("admin.Star") }}</h6>
            </div>
            <div class="col-md-6 col-sm-6 col-6">
              <p class="textStar"></p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" data-id="" data-function="" id="btn_shareFeedback" class="btn btn-success">Share feedback</button>
      </div>
    </div>
  </div>
</div>
<!-- modal reply -->
<div class="modal fade" id="modalAnswer" tabindex="-1" role="dialog" aria-labelledby="modalAnswerLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAnswerLabel">{{ trans('admin.Replytofeedback') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{route('admin.sendFeedback')}}" method="post">
      <div class="modal-body">
          <input type="hidden" name="_token" value="{{ csrf_token() }}" />
          <div class="container-fluid">
            <div class="row">
              <!-- recipient email -->
              <input type="hidden" name="emailRecipient" id="emailRecipient">
              <div class="col-md-12 col-sm-12 col-12 mb-2">
                <label for="titleEecipient" class="font-weight-bold text-italic">{{ trans('admin.Replytofeedback') }}</label>
              </div>
              <div class="col-md-12 col-sm-12 col-12 mb-2">
                <span class="text-italic" id="p_email"></span><span id="verification"></span>
              </div>
              <!-- title -->
              <div class="col-md-12 col-sm-12 col-12 mb-2">
                <label for="titleEmail" class="font-weight-bold text-italic">{{ trans('admin.TitleEmailFeedback') }}</label>
              </div>
              <div class="col-md-12 col-sm-12 col-12 mb-2">
                <input id="titleEmail" type="text" class="form-control" placeholder="{{ trans('admin.TitleEmailFeedback') }}" required="" name="title">
              </div>
              <!-- content -->
              <div class="col-md-12 col-sm-12 col-12 mb-2">
                <label for="contentEmail" class="font-weight-bold text-italic">{{ trans('admin.ResponseEmailContent') }}</label>
              </div>
              <div class="col-md-12 col-sm-12 col-12 mb-2">
                <textarea class="form-control" required="" id="contentEmail" name="content" style="min-height: 13rem">{{ trans('admin.ContentReply') }}</textarea>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-success" value="{{ trans('admin.Replytofeedback') }}">
      </div>
      </form>
    </div>
  </div>
</div>
<!-- /modal reply -->
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
            ajax: '{!! route('admin.showAllFeedback') !!}',
            order:[],
            columns: [
              { data: 'stt', name: 'stt' },
                { data: 'email', name: 'email' },
                { data: 'fullName', name: 'fullName' },
                { data: 'content', name: 'content' },
                { data: 'star', name: 'star' },
                { data: 'share', name: 'share' },
                { data: 'action', name: 'action' },
                ]
            });
          	table.on( 'draw.dt', function () {
  		    	var PageInfo = $('#Table_AllClass').DataTable().page.info();
  		         	table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
  		            	cell.innerHTML = i + 1 + PageInfo.start;
  		        	});
  			    });

            $("#btn_shareFeedback").click(function(){
              var $url_path = '{!! url('/') !!}';
              var _token = $('meta[name="csrf-token"]').attr('content');
              let idfeedback = $(this).attr("data-id");
              let function_status = $(this).attr("data-function");
              var routeShareFeedback=$url_path+"/sharefeedback";
              $.ajax({
                  url:routeShareFeedback,
                  method:"POST",
                  data:{_token:_token,idfeedback:idfeedback,function_status:function_status},
                  success:function(data){ 
                    $("#exampleModal").modal("hide");
                    var routeReload = $url_path+"/showAllFeedback";
                    table.ajax.url( routeReload ).load();
                 }
              });
            });
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
                    if(data[4] == "0")
                    {
                      $("#btn_shareFeedback").removeClass("btn-danger");
                      $("#btn_shareFeedback").addClass("btn-success");
                      $("#btn_shareFeedback").html("Share feedback");
                      $("#btn_shareFeedback").attr("data-id",recipient);
                      $("#btn_shareFeedback").attr("data-function","share");
                    }
                    else
                    {
                      $("#btn_shareFeedback").removeClass("btn-success");
                      $("#btn_shareFeedback").addClass("btn-danger");
                      $("#btn_shareFeedback").html("Feedback retrieval");
                      $("#btn_shareFeedback").attr("data-id",recipient);
                      $("#btn_shareFeedback").attr("data-function","withdraw");
                    }
               }
            });
        });
      $('#modalAnswer').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var recipient = button.data('id');
            var routeDetail=$url_path+"/getEmail";
            $.ajax({
                url:routeDetail,
                method:"POST",
                data:{_token:_token,recipient:recipient},
                success:function(data){ 
                    $("#p_email").empty();
                    $("#p_email").append(data[0]);
                    $("#emailRecipient").val("");
                    $("#emailRecipient").val(data[0]);
                    $("#verification").empty();
                    $("#titleEmail").val("");
                    $("#titleEmail").val("Send: "+data[0]);
                    if(data[1] == "false")
                    {
                      $("#verification").append('&nbsp;&nbsp;<span class="badge badge-danger">{{ trans("admin.Unverifiedemail") }}</span>');
                    }
                    else if(data[1] == "true")
                    {
                      $("#verification").append('&nbsp;&nbsp;<span class="badge badge-success">{{ trans("admin.Verifiedemail") }}</span>');
                    }
               }
          });
        });
    </script>
@stop