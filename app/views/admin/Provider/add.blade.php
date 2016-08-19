<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li><a href="{{URL::route('admin.userShop_list')}}"> Danh sách Shop</a></li>
            <li class="active">@if($id > 0)Cập nhật User shop @else Tạo mới User shop @endif</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                {{Form::open(array('role'=>'form','url' =>($id > 0)? "admin/userShop/postUserShop/$id" : 'admin/userShop/getUserShop','files' => true))}}
                @if(isset($error))
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p>{{ $itmError }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="name" class="control-label">User đăng nhập</label>
                        <input type="text" placeholder="User shop" id="user_shop" name="user_shop" class="form-control input-sm" value="@if(isset($data['user_shop'])){{$data['user_shop']}}@endif">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Đổi pass - nếu có</label>
                        <input type="password" id="user_password" name="user_password" class="form-control input-sm" value="">
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="form-group">
                        <label for="name" class="control-label">Tên shop</label>
                        <input type="text" placeholder="Tên shop" id="shop_name" name="shop_name" class="form-control input-sm" value="@if(isset($data['shop_name'])){{$data['shop_name']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>


                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="name" class="control-label">Số điện thoại</label>
                        <input type="text" placeholder="Số điện thoại" id="shop_phone" name="shop_phone" class="form-control input-sm" value="@if(isset($data['shop_phone'])){{$data['shop_phone']}}@endif">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Email</label>
                        <input type="text" placeholder="Email" id="shop_email" name="shop_email" class="form-control input-sm" value="@if(isset($data['shop_email'])){{$data['shop_email']}}@endif">
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="form-group">
                        <label for="name" class="control-label">Địa chỉ</label>
                        <input type="text" placeholder="Địa chỉ" id="shop_address" name="shop_address" class="form-control input-sm" value="@if(isset($data['shop_address'])){{$data['shop_address']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>


                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="name" class="control-label">Kiểu shop</label>
                        <select name="is_shop" id="is_shop" class="form-control input-sm">
                            {{$optionIsShop}}
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="name" class="control-label">Trạng thái</label>
                        <select name="shop_status" id="shop_status" class="form-control input-sm">
                            {{$optionStatus}}
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>


                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Giới thiệu về shop</label>
                        <textarea class="form-control input-sm" rows="8" name="shop_about" id="shop_about">@if(isset($data['shop_about'])){{$data['shop_about']}}@endif</textarea>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Chính sách vận chuyển</label>
                        <textarea class="form-control input-sm" rows="8" name="shop_transfer" id="shop_transfer">@if(isset($data['shop_transfer'])){{$data['shop_transfer']}}@endif</textarea>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="form-group col-sm-12 text-left">
                    <button  class="btn btn-primary"><i class="glyphicon glyphicon-floppy-saved"></i> Lưu lại</button>
                </div>
                {{ Form::close() }}
                <!-- PAGE CONTENT ENDS -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.page-content -->
</div>


<!--Popup upload ảnh-->
<div class="modal fade" id="sys_PopupUploadImg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Upload ảnh</h4>
            </div>
            <div class="modal-body">
                <form name="uploadImage" method="post" action="#" enctype="multipart/form-data">
                <div class="form_group">
                    <div id="sys_mulitplefileuploader" class="btn btn-primary">Upload ảnh</div>
                    <div id="status"></div>

                    <div class="clearfix"></div>
                    <div class="clearfix" style='margin: 5px 10px; width:100%;'>
                        <div id="div_image"></div>
                    </div>
                </div>
               </form>
            </div>
        </div>
    </div>
</div>
<!--Popup upload ảnh-->
<script>
    CKEDITOR.replace(
            'shop_about',
            {
                toolbar: [
                    { name: 'document',    items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
                    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
                    { name: 'colors',      items : [ 'TextColor','BGColor' ] },
                ],
            },
            {height:800}
    );CKEDITOR.replace(
            'shop_transfer',
            {
                toolbar: [
                    { name: 'document',    items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
                    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
                    { name: 'colors',      items : [ 'TextColor','BGColor' ] },
                ],
            },
            {height:800}
    );
</script>