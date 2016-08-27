<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('shop.adminShop')}}">Quản trị shop</a>
            </li>
            <li class="active">@if(isset($product_id) && $product_id > 0)Sửa sản phẩm @else Tạo mới sản phẩm @endif</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                {{Form::open(array('role'=>'form','method' => 'POST','url' =>URL::route('shop.editProduct'),'files' => true))}}
                @if(isset($error) && !empty($error))
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p>{{ $itmError }}</p>
                        @endforeach
                    </div>
                @endif

                <div style="float: left;width: 50%">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name" class="control-label">Tên sản phẩm</label>
                        <input type="text" placeholder="Tên sản phẩm" id="product_name" name="product_name"  class="form-control input-sm" value="@if(isset($data['product_name'])){{$data['product_name']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Danh mục</label>
                        <input type="text" placeholder="Số điện thoại" id="shop_phone" name="shop_phone" class="form-control input-sm" value="@if(isset($data['shop_phone'])){{$data['shop_phone']}}@endif">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Kiểu hiển thị giá</label>
                        <input type="text" placeholder="Email" id="shop_email" name="shop_email" class="form-control input-sm" value="@if(isset($data['shop_email'])){{$data['shop_email']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Loại sản phẩm</label>
                        <input type="text" placeholder="Số điện thoại" id="shop_phone" name="shop_phone" class="form-control input-sm" value="@if(isset($data['shop_phone'])){{$data['shop_phone']}}@endif">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Giá bán</label>
                        <input type="text" placeholder="Email" id="shop_email" name="shop_email" class="form-control input-sm" value="@if(isset($data['shop_email'])){{$data['shop_email']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Trạng thái Ẩn/Hiện</label>
                        <input type="text" placeholder="Số điện thoại" id="shop_phone" name="shop_phone" class="form-control input-sm" value="@if(isset($data['shop_phone'])){{$data['shop_phone']}}@endif">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Giá thị trường</label>
                        <input type="text" placeholder="Email" id="shop_email" name="shop_email" class="form-control input-sm" value="@if(isset($data['shop_email'])){{$data['shop_email']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Thông tin khuyến mại</label>
                        <input type="text" placeholder="Số điện thoại" id="shop_phone" name="shop_phone" class="form-control input-sm" value="@if(isset($data['shop_phone'])){{$data['shop_phone']}}@endif">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Giá nhập</label>
                        <input type="text" placeholder="Email" id="shop_email" name="shop_email" class="form-control input-sm" value="@if(isset($data['shop_email'])){{$data['shop_email']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>
                </div>

                <div style="float: left;width: 50%">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <a href="javascript:;"class="btn btn-primary" onclick="Common.uploadMultipleImages(1);">Upload ảnh </a>
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name" class="control-label">Mô tả ngắn</label>
                            <textarea class="form-control input-sm" rows="8" name="shop_about" id="shop_about">@if(isset($data['shop_about'])){{$data['shop_about']}}@endif</textarea>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>


                <div class="clearfix"></div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name" class="control-label">Thông tin chi tiết</label>
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