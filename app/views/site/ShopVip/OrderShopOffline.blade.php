<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('shop.adminShop')}}">Quản trị shop</a>
            </li>
            <li class="active">Bán hàng tại Shop</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div class="panel panel-info">
                    {{ Form::open(array('method' => 'GET', 'role'=>'form')) }}
                    <div class="panel-body">
                        <div class="form-group col-lg-4">
                            <label for="category_name">Số điện thoại khách mua hàng</label>
                            <input type="text" class="form-control input-sm" id="customer_phone" name="customer_phone" placeholder="Số điện thoại khách mua hàng">
                        </div>
                        <div class="form-group col-lg-5">
                            <label for="category_name">Id sản phẩm</label>
                            <input type="text" class="form-control input-sm" id="product_id" name="product_id" placeholder="Id sản phẩm: 111,222,333">
                        </div>
                        <div class="form-group col-lg-2 marginTop20">
                            <a class="btn btn-primary btn-sm" href="javascript:void(0);" onclick="orderShop.getInforShopCart();">
                                <i class="fa fa-search"></i> Tìm sản phẩm
                            </a>
                            <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/ajax-loader.gif" width="20" style="display: none" id="img_loading">
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
                <!---Phần hiển thị sản phảm trong giỏ hàng shop-->
                <div class="panel-body" id = 'block_show_infor_shop_cart'></div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.page-content -->
</div>
