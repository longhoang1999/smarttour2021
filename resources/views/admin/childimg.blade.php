@extends('admin/layout/index')
@section('title')
    {{ trans('admin.typeOfPlace') }}
@parent
@stop
@section('header_styles')
	<link rel="stylesheet" href="{{asset('css/adminDashboard.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />
  <link href="{{ asset('dropzone/css/dropzone.css') }}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="{{asset('css/childImg.css')}}">
@stop
@section('content')
<!-- privious  tour-->
	<div class="title"><p class="text-uppercase">Các hình ảnh chi tiết về địa điểm</p></div>
  <div class="AllClass_Table">
        <div class="AllClass_Table_title">
          <p class="text-lowercase">Các hình ảnh chi tiết về địa điểm</p>
        </div>
        <div class="AllClass_Table_content">
          <h4 class="text-center mb-3 font-weight-bold text-primary">{{$namePlace}}</h4>
          <div class="drop-zone">
            <form action="{{route('admin.postChildImg',$idPlace)}}" method="post" id="DropzoneImg" class="dropzone" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <div class="dz-message" data-dz-message>
                <span>Ảnh mô tả địa danh(file jpeg, jpg, png)</span>
                <br><br>
                <p>Kéo thả file của bạn vào đây!</p>
              </div>
                  <div class="fallback">
                      <input name="file" type="file" multiple />
                  </div>
            </form>
          </div>
          <button class="btn btn-info btn-sm" id="reloadImgChild">Làm mới</button>
          <div class="container-fuild detail_image">
            <div class="row">
              @foreach($array as $ar)
              <div class="col-md-2 img_child">
                <i class="fas fa-times-circle icon_time" title="Delete this image" data-id="{{$ar}}"></i>
                <a data-fancybox="gallery" href="{{asset('child_img_place/'.$ar)}}">
                  <img class="img-fluid rounded mb-5" src="{{asset('child_img_place/'.$ar)}}" alt="">
                </a>
              </div>
              @endforeach
            </div>
          </div>
        </div>
    </div>
<!-- modal -->
<!-- Modal -->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalDeleteLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDeleteLabel">Cảnh báo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-danger">Bạn có muốn xóa ảnh này không?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal" id="btn_delete_image">Có</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Không</button>
      </div>
    </div>
  </div>
</div>
@stop
@section('footer-js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
	<!-- datatable -->
  	<script type="text/javascript" src="{{ asset('datatables/js/jquery.dataTables.js') }}" ></script>
  	<script type="text/javascript" src="{{ asset('datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('dropzone/js/dropzone.js') }}" ></script>
    <script type="text/javascript">
      Dropzone.options.DropzoneImg = {
          acceptedFiles:'.jpeg,.jpg,.png',
          maxFiles: 10,
      };
      $("#reloadImgChild").click(function(){
        let $url_path = '{!! url('/') !!}';
        let _token = $('meta[name="csrf-token"]').attr('content');
        let routeReloadImg = $url_path+"/reloadChildImg";
        $.ajax({
              url:routeReloadImg,
              method:"POST",
              data:{_token:_token,idPlace:'{{$idPlace}}'},
              success:function(data){ 
                if(data.length == 0)
                  alert("Không có hình ảnh");
                else
                {
                  $(".detail_image .row").empty();
                  data.forEach(function(item, index){
                    $(".detail_image .row").append('<div class="col-md-2 img_child"><i class="fas fa-times-circle icon_time" title="Delete this image" data-id="'+item+'"></i><a data-fancybox="gallery" href="{{asset("/child_img_place")}}/'+item+'"><img class="img-fluid rounded mb-5" src="{{asset("/child_img_place")}}/'+item+'" alt=""></a></div>');
                  })
                  //Dropzone.forElement('#DropzoneImg').removeAllFiles(true);
                }
             }
        });
      })
      var icon;
      var icon_id;
      $(".detail_image .row").on('click','.img_child .icon_time',function(){
        $("#modalDelete").modal("show");
        icon = $(this);
        icon_id = $(this).data('id');
      })
      $("#btn_delete_image").click(function(){
        $(".detail_image .row").find(icon.parent()).remove();
        let $url_path = '{!! url('/') !!}';
        let _token = $('meta[name="csrf-token"]').attr('content');
        let routeDeleteImg = $url_path+"/deleteImage";
        $.ajax({
              url:routeDeleteImg,
              method:"POST",
              data:{_token:_token,idPlace:'{{$idPlace}}',nameImg:icon_id},
              success:function(data){ 
                
             }
        });
      })
    </script>
@stop