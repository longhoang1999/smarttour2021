@extends('admin/layout/index')
@section('title')
    View Feedback
@parent
@stop
@section('header_styles')
	<link rel="stylesheet" href="{{asset('css/adminDashboard.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
  <style>
    .contents{color: white}
    .user_content {
        display: block;
    }
    .user_content .typePlace {
        background: #eaecf4;
    }
  </style>
@stop
@section('content')
<!-- privious  tour-->
	<div class="title"><p class="text-uppercase">Information about place types</p></div>
	<div class="AllClass_Table">
        <div class="AllClass_Table_title">
          <p>Information about place types</p>
        </div>
        <div class="AllClass_Table_content">
            <button class="btn btn-info" data-toggle="modal" data-target="#addnewtype">Add new place type</button>
            <div style="display: flex; margin:1rem 0;">
              <span style="font-size: 1.2rem;width: 20%" class="font-weight-bold font-italic">Language shown</span> 
              <select id="selectLang" class="form-control" style="width: 40%">
                <option selected="" value="en">English</option>
                <option value="vn">Tiếng việt</option>
              </select>
            </div>
            <table class="table table-bordered table-striped" id="Table_AllClass" style="margin: 10px 0;">
                  <thead>
                  <tr>
                      <th>{{ trans("admin.Order") }}</th>
                      <th>Name type</th>
                      <th>Total Place</th>
                      <th>Date created</th>
                      <th>Status</th>
                      <th>{{ trans("admin.Actions") }}</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
        </div>
  </div>
  <!-- modal add new type -->
  <div class="modal fade" id="addnewtype" tabindex="-1" role="dialog" aria-labelledby="addnewtypeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addnewtypeLabel">Add new type place</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('admin.addtypeplace')}}" method="post" id="addTypePlace">
          <div class="modal-body">
            <label for="inputEN">Name type (English)</label>
            <input type="text" class="form-control" id="inputEN" name="nametypeEn" required="" placeholder="Name type">
            <label for="inputVN">Name type (Vietnamese)</label>
            <input type="text" class="form-control" id="inputVN" name="nametypeVn" required="" placeholder="Name type">
          </div>
          <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="Add type">
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /modal add new type -->
  <!-- modal edit type -->
  <div class="modal fade" id="modaEditType" tabindex="-1" role="dialog" aria-labelledby="modaEditTypeLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modaEditTypeLabel">Edit Name Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('admin.fixNameType')}}" method="post" id="fixNameType">
          <input type="hidden" id="inputId" name="idtype">
          <div class="modal-body">
            <label for="inputName">Name Type</label>
            <input type="text" placeholder="Name Type" required="" name="nametype" id="inputName" class="form-control">
          </div>
          <div class="modal-footer">
            <input type="submit" class="btn btn-success" value="Edit">
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- modal delete -->
  <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDeleteLabel">Delete Place Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="text-danger">Do you really want to delete?</p>
        </div>
        <div class="modal-footer">
          <button type="button" data-id="" id="btn-delete" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>
  <!-- /modal delete -->
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
            ajax: '{!! route('admin.showtypeplace') !!}',
            order:[],
            columns: [
                { data: 'stt', name: 'stt' },
                { data: 'nametype', name: 'nametype' },
                { data: 'totalPlace', name: 'totalPlace' },
                { data: 'DateCreated', name: 'DateCreated' },
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
            // choose language
            $("#selectLang").change(function(){
              let $url_path = '{!! url('/') !!}';
              if($("#selectLang").val() == "en")
              {
                var routeEN = $url_path+"/showtypeplace";
                table.ajax.url( routeEN ).load();
              }
              else if($("#selectLang").val() == "vn")
              {
                var routeVN = $url_path+"/showtypeplaceVn";
                table.ajax.url( routeVN ).load();
              }
            });
            //edit
            $("#fixNameType").submit(function(e){
                e.preventDefault();
                let $url_path = '{!! url('/') !!}';
                var form = $(this);
                var url = form.attr('action');
                let _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                       type: "POST",
                       url: url,
                       data: {_token:_token,idtype:$("#inputId").val(),nametype:$("#inputName").val(),lang:$("#selectLang").val()},
                       success: function(data)
                       {
                          alert("You have successfully edited");
                          $("#modaEditType").modal("hide");
                          if($("#selectLang").val() == "en")
                          {
                            var routeEN = $url_path+"/showtypeplace";
                            table.ajax.url( routeEN ).load();
                          }
                          else if($("#selectLang").val() == "vn")
                          {
                            var routeVN = $url_path+"/showtypeplaceVn";
                            table.ajax.url( routeVN ).load();
                          }
                       }
                });
            });
            //add type
            $("#addTypePlace").submit(function(e){
                e.preventDefault();
                let $url_path = '{!! url('/') !!}';
                var form = $(this);
                var url = form.attr('action');
                let _token = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                       type: "POST",
                       url: url,
                       data: {_token:_token,nametypeEn:$("#inputEN").val(),nametypeVn:$("#inputVN").val()},
                       success: function(data)
                       {
                          alert("You have successfully added");
                          $("#addnewtype").modal("hide");
                          if($("#selectLang").val() == "en")
                          {
                            var routeEN = $url_path+"/showtypeplace";
                            table.ajax.url( routeEN ).load();
                          }
                          else if($("#selectLang").val() == "vn")
                          {
                            var routeVN = $url_path+"/showtypeplaceVn";
                            table.ajax.url( routeVN ).load();
                          }
                       }
                });
            });
            // modal delete type
            $("#btn-delete").click(function(){
              let $url_path = '{!! url('/') !!}';
              let _token = $('meta[name="csrf-token"]').attr('content');
              let id = $(this).attr("data-id");
              var routeDeleteType = $url_path+"/deleteTypePlace";
              $.ajax({
                   type: "POST",
                   url: routeDeleteType,
                   data: {_token:_token,id:id},
                   success: function(data)
                   {
                      alert("you have successfully deleted");
                      $("#modalDelete").modal("hide");
                      if($("#selectLang").val() == "en")
                      {
                        var routeEN = $url_path+"/showtypeplace";
                        table.ajax.url( routeEN ).load();
                      }
                      else if($("#selectLang").val() == "vn")
                      {
                        var routeVN = $url_path+"/showtypeplaceVn";
                        table.ajax.url( routeVN ).load();
                      }
                   }
              });
            });
        });
    </script>    
    <script type="text/javascript">
      $('#modaEditType').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget)
        var recipient = button.data('id') 
        let $url_path = '{!! url('/') !!}';
        let language = $("#selectLang").val();
        let _token = $('meta[name="csrf-token"]').attr('content');
        let routeShowType=$url_path+"/routeShowType";
        $.ajax({
              url:routeShowType,
              method:"POST",
              data:{_token:_token,id:recipient,lang:language},
              success:function(data){ 
                $("#inputName").val(data);
                $("#inputId").val(recipient);
              }
        });
      })
      $('#modalDelete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('id');
        var $url_path = '{!! url('/') !!}';
        $("#btn-delete").attr("data-id",recipient);
      })
    </script>
@stop