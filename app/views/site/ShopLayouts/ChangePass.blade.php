<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('shop.adminShop')}}">Quản trị shop</a>
            </li>
            <li class="active">@if($id > 0)Thay đổi mật khẩu của shop @endif</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                {{Form::open(array('role'=>'form','url' =>"thay-doi-pass.html",'files' => true))}}
                @if(isset($error) && sizeof($error) > 0)
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p>{{ $itmError }}</p>
                        @endforeach
                    </div>
                @endif
                <div style="float:left; width: 70%">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Mật khẩu cũ <span class="red"> (*) </span></label>
                        <input type="password" placeholder="Mật khẩu cũ" id="user_password_old" name="user_password_old"  class="form-control input-sm" value="">
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Mật khẩu mới <span class="red"> (*) </span></label>
                        <input type="password" placeholder="Mật khẩu mới" id="user_password" name="user_password" class="form-control input-sm" value="">
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Nhập lại mật khẩu mới <span class="red"> (*) </span></label>
                        <input type="password" placeholder="Nhập lại mật khẩu mới" id="user_password_reply" name="user_password_reply" class="form-control input-sm" value="">
                    </div>
                </div>
                <div class="clearfix"></div>

                <div class="form-group col-sm-12 text-left">
                    <button  class="btn btn-primary"><i class="glyphicon glyphicon-floppy-saved"></i> Đổi pass</button>
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