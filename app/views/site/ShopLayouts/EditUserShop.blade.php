<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('shop.adminShop')}}">Quản trị shop</a>
            </li>
            <li class="active">@if($id > 0)Cập nhật thông tin Shop @else Tạo mới User shop @endif</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                {{Form::open(array('role'=>'form','url' =>"thong-tin-shop.html",'files' => true))}}
                @if(isset($error))
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p>{{ $itmError }}</p>
                        @endforeach
                    </div>
                @endif
                <div style="float:left; width: 70%">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name" class="control-label">User đăng nhập</label>
                        <input type="text" placeholder="User shop" id="user_shop" name="user_shop" readonly class="form-control input-sm" value="@if(isset($data['user_shop'])){{$data['user_shop']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name" class="control-label">Tên shop</label>
                        <input type="text" placeholder="Tên shop" id="shop_name" name="shop_name" class="form-control input-sm" value="@if(isset($data['shop_name'])){{$data['shop_name']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Số điện thoại</label>
                        <input type="text" placeholder="Số điện thoại" id="shop_phone" name="shop_phone" class="form-control input-sm" value="@if(isset($data['shop_phone'])){{$data['shop_phone']}}@endif">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name" class="control-label">Email</label>
                        <input type="text" placeholder="Email" id="shop_email" name="shop_email" class="form-control input-sm" value="@if(isset($data['shop_email'])){{$data['shop_email']}}@endif">
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="col-sm-9">
                    <div class="form-group">
                        <label for="name" class="control-label">Địa chỉ</label>
                        <input type="text" placeholder="Địa chỉ" id="shop_address" name="shop_address" class="form-control input-sm" value="@if(isset($data['shop_address'])){{$data['shop_address']}}@endif">
                    </div>
                </div>
                    <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Tỉnh thành</label>
                        <select id="shop_province" name="shop_province" class="form-control">
                            {{$optionProvince}}
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name" class="control-label">Giới thiệu về shop</label>
                        <textarea class="form-control input-sm" rows="8" name="shop_about" id="shop_about">@if(isset($data['shop_about'])){{$data['shop_about']}}@endif</textarea>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name" class="control-label">Chính sách vận chuyển</label>
                        <textarea class="form-control input-sm" rows="8" name="shop_transfer" id="shop_transfer">@if(isset($data['shop_transfer'])){{$data['shop_transfer']}}@endif</textarea>
                    </div>
                </div>
                <div class="clearfix"></div>
                </div>
                <div style="float:left; width: 30%; height: 650px;">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="name" class="control-label">Danh mục sản phẩm của shop</label>
                        </div>
                    </div>
                    <div style="float: left; width: 100%; height: 920px;overflow: hidden; overflow-x: hidden;overflow-y: scroll">
                    <table class="table table-bordered table-hover">
                        @foreach ($arrCategory as $key => $cate)
                            <tr>
                                <td class="text-center text-middle">
                                    @if($cate['category_parent_id'] > 0)
                                        <input type="checkbox" class="checkItem" name="checkCategoryShop[]"
                                               @if(in_array($cate['category_id'],$arrCateShop)) checked="checked"@endif
                                               value="{{$cate['category_id']}}" />
                                    @endif
                                </td>
                                <td class="text-left text-middle">
                                    @if($cate['category_parent_id'] == 0)
                                        <b>{{$cate['category_name']}}</b>
                                    @else
                                        {{$cate['padding_left'].$cate['category_name']}}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    </div>
                </div>

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