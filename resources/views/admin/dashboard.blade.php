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
            @if ($message = Session::get('status'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{{$message}}</strong>
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{{$message}}</strong>
                </div>
            @endif
            @if (count($errors) > 0)
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            @endif
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addAccount">
              Add a new account
            </button>
            <table class="table table-bordered table-striped" id="Table_AllClass" style="margin-bottom: 10px;">
                  <thead>
                  <tr>
                      <th>Order</th>
                      <th>Email</th>
                      <th>FullName</th>
                      <th>Gender</th>
                      <th>Age</th>
                      <th>Position</th>
                      <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
        </div>
    </div>
    <!-- Modal add account-->
    <div class="modal fade" id="addAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add a new account</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{route('admin.addaccount')}}" method="post" enctype="multipart/form-data" id="form_add_newaccount">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12 col-sm-12 col-12 text-center mb-3">
                    <h6 class="font-weight-bold text-italic text-uppercase">create a new account</h6>
                  </div>
                  <!-- email -->
                  <div class="col-md-4 col-sm-6 col-6 mb-3">
                    <label for="input_email">Email: </label>
                  </div>
                  <div class="col-md-8 col-sm-6 col-6 mb-3">
                    <input id="input_email" type="email" class="form-control" placeholder="Enter email" name="us_email" required="">
                  </div>
                  <!-- password -->
                  <div class="col-md-4 col-sm-6 col-6 mb-3">
                    <label for="input_password">Password: </label>
                  </div>
                  <div class="col-md-8 col-sm-6 col-6 mb-3">
                    <input id="input_password" type="password" class="form-control" placeholder="Enter password" name="us_password" required="">
                  </div>
                  <!-- confirm password -->
                  <div class="col-md-4 col-sm-6 col-6 mb-3">
                    <label for="input_confirm">Confirm password: </label>
                  </div>
                  <div class="col-md-8 col-sm-6 col-6 mb-3">
                    <input id="input_confirm" type="password" class="form-control" placeholder="Confirm password" name="us_confirm" required="">
                  </div>
                  <!-- full name -->
                  <div class="col-md-4 col-sm-6 col-6 mb-3">
                    <label for="input_fullname">Full Name: </label>
                  </div>
                  <div class="col-md-8 col-sm-6 col-6 mb-3">
                    <input id="input_fullname" type="text" class="form-control" placeholder="Enter fullname" name="us_fullname" required="">
                  </div>
                  <!-- image -->
                  <div class="col-md-4 col-sm-6 col-6 mb-3">
                    <label for="input_image">Image: </label>
                  </div>
                  <div class="col-md-8 col-sm-6 col-6 mb-3">
                    <div class="btn-update-file">Update</div>
                    <p class="file_name"></p>
                    <input id="input_image" type="file" name="us_image" accept="image/*">
                  </div>
                  <!-- gender -->
                  <div class="col-md-4 col-sm-6 col-6 mb-3">
                    <label for="input_gender">Gender: </label>
                  </div>
                  <div class="col-md-8 col-sm-6 col-6 mb-3">
                    <select name="us_gender" id="input_gender" class="form-control">
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                    </select>
                  </div>
                  <!-- age -->
                  <div class="col-md-4 col-sm-6 col-6 mb-3">
                    <label for="input_age">Age: </label>
                  </div>
                  <div class="col-md-8 col-sm-6 col-6 mb-3">
                    <input id="input_age" type="number" step="1" class="form-control" placeholder="Enter age" name="us_age">
                  </div>
                  <!-- decentralization -->
                  <div class="col-md-4 col-sm-6 col-6 mb-3">
                    <label for="input_type">Decentralization: </label>
                  </div>
                  <div class="col-md-8 col-sm-6 col-6 mb-3">
                    <select name="us_type" id="input_type" class="form-control">
                      <option value="1">Admin</option>
                      <option value="0" selected="">User</option>
                    </select>
                  </div>

                </div>
              </div>
              <div class="modal-footer">
                <input type="submit" class="btn btn-primary" value="Create account" id="btn_submit_add">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal warning delete -->
    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalDetailLabel">Warning</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h6 class="text-danger">This action cannot be undone. Do you really want to delete this account?</h6>
          </div>
          <div class="modal-footer">
            <a href="#" id="btn_delete" class="btn btn-danger">Delete</a>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal detail-->
    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Account</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row">
                  <div class="col-md-12 col-sm-12 col-12 text-center">
                      <div id="text_img" class="mb-5" ></div>
                      <img class="mb-5" src="{{asset('assets/img/avataaars.svg')}}" alt="" id="default_img" />
                  </div>
                  <div class="col-md-12 col-sm-12 col-12 text-center mb-3">
                    <p class="font-weight-bold">Avatar</p>
                  </div>
              </div>
            </div>
            <div class="container-fluid">
              <div class="row">
                <!-- email -->
                <div class="col-md-6 col-sm-6 col-6 font-weight-bold font-italic text-content">
                  Email:
                </div>
                <div class="col-md-6 col-sm-6 col-6 text-content">
                  <span id="text_email"></span>
                </div>
                <!-- full name -->
                <div class="col-md-6 col-sm-6 col-6 font-weight-bold font-italic text-content">
                  Full Name:
                </div>
                <div class="col-md-6 col-sm-6 col-6 text-content">
                  <p class="id_fullName_user"></p>
                </div>
                <!-- Gender -->
                <div class="col-md-6 col-sm-6 col-6 font-weight-bold font-italic text-content">
                  Gender:
                </div>
                <div class="col-md-6 col-sm-6 col-6 text-content">
                  <p class="id_gender_user"></p>
                </div>
                <!-- Age -->
                <div class="col-md-6 col-sm-6 col-6 font-weight-bold font-italic text-content">
                  Age:
                </div>
                <div class="col-md-6 col-sm-6 col-6 text-content">
                  <p class="id_age_user"></p>
                </div>
                <!-- type -->
                <div class="col-md-6 col-sm-6 col-6 font-weight-bold font-italic text-content">
                  Position:
                </div>
                <div class="col-md-6 col-sm-6 col-6 id_position_user text-content">
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
            ajax: '{!! route('admin.showAllAccount') !!}',
            order:[],
            columns: [
              { data: 'stt', name: 'stt' },
                { data: 'us_email', name: 'us_email' },
                { data: 'us_fullName', name: 'us_fullName' },
                { data: 'us_gender', name: 'us_gender' },
                { data: 'us_age', name: 'us_age' },
                { data: 'position', name: 'position' },
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
        $(".btn-update-file").click(function(){
          $("#input_image").click();
        });
        $("#input_image").change(function(){
          $(".btn-update-file").css("background","#e9ba2a");
          $(".file_name").css("display","block");
          $(".file_name").html("File name: &#60;"+$("#input_image").val().split('\\').pop()+"&#62;");
        });
        $('#modalDelete').on('shown.bs.modal', function (event) {
            var $url_path = '{!! url('/') !!}';
            var button = $(event.relatedTarget)
            recipient = button.data('id');
            var routeDelete=$url_path+"/deleteAcc/"+recipient;
            var modal = $(this)
            modal.find('.modal-footer #btn_delete').prop("href",routeDelete);
            
        });
        $('#modalDetail').on('show.bs.modal', function (event) {
            let _token = $('meta[name="csrf-token"]').attr('content');
            var button = $(event.relatedTarget)
            let recipient_2 = button.data('id');
            let $url_path = '{!! url('/') !!}';
            let routeCheckUser=$url_path+"/checkUserAdmin";
            $.ajax({
                  url:routeCheckUser,
                  method:"POST",
                  data:{_token:_token,id:recipient_2},
                  success:function(data){ 
                    $(".id_fullName_user").empty();
                    $(".id_gender_user").empty();
                    $(".id_age_user").empty();
                    $(".id_position_user").empty();
                    $("#text_email").empty();
                    if(data[5] == false)
                    {
                        $("#default_img").css("display","block");
                        $("#text_img").css("display","none");
                    }
                    else
                    {
                        $("#default_img").css("display","none");
                        $("#text_img").css("display","block");
                        $("#text_img").css("background","url('"+data[0]+"')");
                        $("#text_img").css("background-size","cover");
                        $("#text_img").css("background-repeat","no-repeat");
                    }
                    if(data[6] != "")
                    {
                        $("#text_email").append(data[1]+"<span class='text-danger' style='font-style: italic;'> (Chưa xác minh)</span>");
                    }
                    if(data[6] == "")
                    {
                        $("#text_email").append(data[1]+"<span class='text-success' style='font-style: italic;'> (Đã xác minh)</span>");
                    }
                    $(".id_fullName_user").append(data[2]);
                    $(".id_gender_user").append(data[3]);
                    $(".id_age_user").append(data[4]);
                    if(data[7]== "0")
                    {
                      $(".id_position_user").append('<span class="badge badge-warning">User</span>');
                    }
                    else if(data[7]== "1")
                    {
                      $(".id_position_user").append('<span class="badge badge-primary">Admin</span>');
                    }
                 }
            });
        });
      });
    </script>
@stop