<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Home</a>
            </li>
            <li><a href="{{URL::route('admin.news_list')}}"> Danh sách tin tức</a></li>
            <li class="active">@if($id > 0)Cập nhật tin tức @else Tạo mới tin tức @endif</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content marginTop30">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                {{Form::open(array('role'=>'form','url' =>($id > 0)? "admin/news/postNews/$id" : 'admin/news/postNews','files' => true))}}
                @if(isset($error))
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p>{{ $itmError }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="col-sm-2">
                    <div class="form-group">
                        <i>Tên bài viết</i>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <input type="text" placeholder="Tên bài viết" id="news_title" name="news_title" class="form-control input-sm" value="@if(isset($data['news_title'])){{$data['news_title']}}@endif">
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <i>Danh mục tin</i>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <select name="news_status" id="news_status" class="form-control input-sm">
                            {{$optionStatus}}
                        </select>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <i>Loại tin</i>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <select class="form-control input-sm" name="news_type" >
                            <?php echo $optionType;?>
                        </select>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <i>Trạng thái</i>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <select class="form-control input-sm" name="news_category">
                            <?php echo $optionCategory;?>
                        </select>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <i>Upload ảnh</i>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                          <a href="javascript:;"class="btn btn-primary" onclick="Common.uploadMultipleImages(1);">Upload ảnh </a>
                          <input name="image_primary" type="hidden" id="image_primary" value="<?php if(isset($arrItem->news_image)){ echo $arrItem->news_image; } ?>">
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <i>Mô tả ngắn</i>
                    </div>
                </div>
                <div class="col-sm-10">
                    <div class="form-group">
                        <textarea class="form-control input-sm" rows="8" name="news_desc_sort">@if(isset($data['news_desc_sort'])){{$data['news_desc_sort']}}@endif</textarea>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <i>Nội dung chi tiết</i>
                    </div>
                </div>
                <div class="col-sm-10">
                    <div class="form-group">
                        <textarea class="form-control input-sm"  name="news_content">@if(isset($data['news_content'])){{$data['news_content']}}@endif</textarea>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="form-group col-sm-12 text-right">
                    <button  class="btn btn-primary"><i class="glyphicon glyphicon-floppy-saved"></i> Lưu lại</button>
                </div>
                <input type="hidden" id="id_hiden" name="id" value="{{$id}}"/>
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
    CKEDITOR.replace('news_content', {height:800});
    /*CKEDITOR.replace(
            'news_content',
            {
                toolbar: [
                    { name: 'document',    items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
                    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
                    { name: 'colors',      items : [ 'TextColor','BGColor' ] },
                ],
            },
            {height:600}
    );*/
</script>

<script type="text/javascript">
    //kéo thả ảnh
    jQuery("#sys_drag_sort").dragsort({ dragSelector: "div", dragBetween: true, dragEnd: saveOrder });
    function saveOrder() {
        var data = jQuery("#sys_drag_sort li div span").map(function() { return jQuery(this).children().html(); }).get();
        jQuery("input[name=list1SortOrder]").val(data.join(","));
    };
    function insertImgContent(src){
        CKEDITOR.instances.news_content.insertHtml('<img src="'+src+'"/>');
    }
</script>