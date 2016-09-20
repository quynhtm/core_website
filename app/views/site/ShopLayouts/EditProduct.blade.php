<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('shop.adminShop')}}">Quản trị shop</a>
            </li>
            <li>
                <a href="{{URL::route('shop.listProduct')}}">Danh sách sản phẩm</a>
            </li>
            <li class="active">@if(isset($product_id) && $product_id > 0)Sửa sản phẩm @else Tạo mới sản phẩm @endif</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content marginTop10">
        <div class="row ">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                {{Form::open(array('role'=>'form','method' => 'POST','url' =>URL::route('shop.editProduct',array('product_id'=>$product_id,'product_name'=>(isset($product_id) && $product_id > 0)?strtolower(FunctionLib::safe_title($data['product_name'])):strtolower(FunctionLib::safe_title('thêm sản phẩm')))),'files' => true))}}
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
                            <label for="name" class="control-label">Tên sản phẩm <span class="red"> (*) </span></label>
                            <input type="text" placeholder="Tên sản phẩm" id="product_name" name="product_name"  class="form-control input-sm" value="@if(isset($data['product_name'])){{$data['product_name']}}@endif">
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div style="float: left;width: 34%">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Danh mục<span class="red"> (*) </span></label>
                                <div class="form-group">
                                    <select name="category_id" id="category_id" class="form-control input-sm">
                                        {{$optionCategory}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Trạng thái Ẩn/Hiện</label>
                                <div class="form-group">
                                    <select name="product_status" id="product_status" class="form-control input-sm">
                                        {{$optionStatusProduct}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Thông tin khuyến mại</label>
                                <div class="clearfix"></div>
                                <textarea rows="5" cols="8" name="product_selloff" class="form-control input-sm">@if(isset($data['product_selloff'])){{$data['product_selloff']}}@endif</textarea>
                            </div>
                        </div>
                    </div>

                    <div style="float: left;width: 33%">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Kiểu hiển thị giá<span class="red"> (*) </span></label>
                                <div class="form-group">
                                    <select name="product_type_price" id="product_type_price" class="form-control input-sm">
                                        {{$optionTypePrice}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Giá bán <span class="red"> (*) </span></label>
                                <input type="text" placeholder="Giá bán" id="product_price_sell" name="product_price_sell" class="formatMoney text-left form-control" data-v-max="999999999999999" data-v-min="0" data-a-sep="." data-a-dec="," data-a-sign=" đ" data-p-sign="s" value="@if(isset($data['product_price_sell'])){{$data['product_price_sell']}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Giá thị trường</label>
                                <input type="text" placeholder="Giá thị trường" id="product_price_market" name="product_price_market" class="formatMoney text-left form-control" data-v-max="999999999999999" data-v-min="0" data-a-sep="." data-a-dec="," data-a-sign=" đ" data-p-sign="s" value="@if(isset($data['product_price_market'])){{$data['product_price_market']}}@endif">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Giá nhập</label>
                                <input type="text" placeholder="Giá nhập" id="product_price_input" name="product_price_input" class="formatMoney text-left form-control" data-v-max="999999999999999" data-v-min="0" data-a-sep="." data-a-dec="," data-a-sign=" đ" data-p-sign="s" value="@if(isset($data['product_price_input'])){{$data['product_price_input']}}@endif">
                            </div>
                        </div>

                    </div>

                    <div style="float: left;width: 33%">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Thuộc nhà cung cấp</label>
                                <div class="form-group">
                                    <select name="provider_id" id="provider_id" class="form-control input-sm">
                                        {{$optionStatusProduct}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Loại sản phẩm</label>
                                <div class="form-group">
                                    <select name="product_is_hot" id="product_is_hot" class="form-control input-sm">
                                        {{$optionTypeProduct}}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name" class="control-label">Tình trạng hàng</label>
                                <div class="form-group">
                                    <select name="is_sale" id="is_sale" class="form-control input-sm">
                                        {{$optionIsSale}}
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div style="float: left;width: 50%">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <a href="javascript:;"class="btn btn-primary" onclick="SITE.uploadImagesProduct(2);">Upload ảnh </a>
                            <input name="image_primary" type="hidden" id="image_primary" value="@if(isset($data['product_image'])){{$data['product_image']}}@endif">
                            <input name="product_image_hover" type="hidden" id="image_primary_hover" value="@if(isset($data['product_image_hover'])){{$data['product_image_hover']}}@endif">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <!--hien thi anh-->
                        <ul id="sys_drag_sort" class="ul_drag_sort">
                            @if(isset($arrViewImgOther))
                                @foreach ($arrViewImgOther as $key => $imgNew)
                                    <li id="sys_div_img_other_{{$key}}" style="margin: 1px!important;">
                                        <div class='block_img_upload'>
                                            <img src="{{$imgNew['src_img_other']}}" height='100' width='100'>
                                            <input type="hidden" id="img_other_{{$key}}" name="img_other[]" value="{{$imgNew['img_other']}}" class="sys_img_other">
                                            <div class='clear'></div>
                                            <input type="radio" id="chẹcked_image_{{$key}}" name="chẹcked_image" value="{{$key}}" @if(isset($imagePrimary) && $imagePrimary == $imgNew['img_other'] ) checked="checked" @endif onclick="SITE.checkedImage('{{$imgNew['img_other']}}','{{$key}}');">
                                            <label for="chẹcked_image_{{$key}}" style='font-weight:normal'>Ảnh đại diện</label>

                                            <div class="clearfix"></div>
                                            <input type="radio" id="chẹcked_image_hover_{{$key}}" name="chẹcked_image_hover" value="{{$key}}" @if(isset($imageHover) && $imageHover == $imgNew['img_other'] ) checked="checked" @endif onclick="SITE.checkedImageHover('{{$imgNew['img_other']}}','{{$key}}');">
                                            <label for="chẹcked_image_hover_{{$key}}" style='font-weight:normal'>Ảnh hover</label>

                                            <div class="clearfix"></div>
                                            <a href="javascript:void(0);" onclick="SITE.removeImage({{$key}},{{$product_id}},'{{$imgNew['img_other']}}');">Xóa ảnh</a>
                                            <span style="display: none"><b>{{$key}}</b></span>
                                        </div>
                                    </li>
                                    @if(isset($imagePrimary) && $imagePrimary == $imgNew['img_other'] )
                                        <input type="hidden" id="products_images_key_upload" name="products_images_key_upload" value="{{$key}}">
                                    @endif
                                @endforeach
                            @else
                                <input type="hidden" id="products_images_key_upload" name="products_images_key_upload" value="-1">
                            @endif
                        </ul>

                        <input name="list1SortOrder" id ='list1SortOrder' type="hidden" />
                        <script type="text/javascript">
                            $("#sys_drag_sort").dragsort({ dragSelector: "div", dragBetween: true, dragEnd: saveOrder });
                            function saveOrder() {
                                var data = $("#sys_drag_sort li div span").map(function() { return $(this).children().html(); }).get();
                                $("input[name=list1SortOrder]").val(data.join(","));
                            };
                        </script>
                        <!--ket thuc hien thi anh-->
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name" class="control-label">Mô tả ngắn <span class="red"> (*) </span></label>
                        <textarea class="form-control input-sm" rows="8" name="product_sort_desc" id="product_sort_desc">@if(isset($data['product_sort_desc'])){{$data['product_sort_desc']}}@endif</textarea>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name" class="control-label">Thông tin chi tiết <span class="red"> (*) </span>
                            <div class="controls"><button type="button" onclick="SITE.insertImageContent(2)" class="btn btn-primary">Chèn ảnh vào nội dung</button></div>
                        </label>
                        <textarea class="form-control input-sm" rows="8" name="product_content" id="product_content">@if(isset($data['product_content'])){{$data['product_content']}}@endif</textarea>
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="form-group col-sm-12 text-left">
                    <button  class="btn btn-primary"><i class="glyphicon glyphicon-floppy-saved"></i> Lưu lại</button>
                </div>
                <input type="hidden" id="id_hiden" name="id_hiden" value="{{$product_id}}"/>
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

<!--Popup anh khac de chen vao noi dung bai viet-->
<div class="modal fade" id="sys_PopupImgOtherInsertContent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Click ảnh để chèn vào nội dung</h4>
            </div>
            <div class="modal-body">
                <form name="uploadImage" method="post" action="#" enctype="multipart/form-data">
                    <div class="form_group">
                        <div class="clearfix"></div>
                        <div class="clearfix" style='margin: 5px 10px; width:100%;'>
                            <div id="div_image" class="float_left">
                                <?php if (isset($arrViewImgOther) && count($arrViewImgOther) > 0) {?>
                                <?php foreach($arrViewImgOther as $kk => $img_other) {?>
                                <span class="float_left image_insert_content">
                                        <a class="img_item" href="javascript:void(0);" onclick="insertImgContent('{{$img_other['src_thumb_content']}}')" >
                                            <img src="{{$img_other['src_img_other']}}" width="100" height="100">
                                        </a>
                                    </span>
                                <?php } } ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- chen anh vào noi dung-->

<script>
    CKEDITOR.replace(
            'product_sort_desc',
            {
                toolbar: [
                    { name: 'document',    items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
                    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
                    { name: 'colors',      items : [ 'TextColor','BGColor' ] },
                ],
            },
            {height:600}
    );
    CKEDITOR.replace('product_content', {height:600});
</script>
<script type="text/javascript">
    jQuery('.formatMoney').autoNumeric('init');
    //kéo thả ảnh
    jQuery("#sys_drag_sort").dragsort({ dragSelector: "div", dragBetween: true, dragEnd: saveOrder });
    function saveOrder() {
        var data = jQuery("#sys_drag_sort li div span").map(function() { return jQuery(this).children().html(); }).get();
        jQuery("input[name=list1SortOrder]").val(data.join(","));
    };
    function insertImgContent(src){
        CKEDITOR.instances.product_content.insertHtml('<img src="'+src+'"/>');
    }
</script>