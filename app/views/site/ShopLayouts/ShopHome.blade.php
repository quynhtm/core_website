<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                Quản trị shop
            </li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box-header">
                    <h3 class="box-title" style="text-align: center;">Chào mừng bạn đến hệ thống quản lý Shop của {{CGlobal::web_name}} </h3>
                </div>

                <div class="box-body" style="margin-top: 50px">
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail text-center">
                            <a class="quick-btn" href="{{URL::route('shop.inforShop')}}">
                                <i class="fa fa-pencil-square-o fa-5x"></i><br/>
                                <span>Thông tin shop</span>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail text-center">
                            <a class="quick-btn" href="{{URL::route('shop.listProduct')}}">
                                <i class="fa fa-sitemap fa-5x"></i><br/>
                                <span>Quản lý sản phẩm</span>
                            </a>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail text-center">
                            <a class="quick-btn" href="{{URL::route('shop.listOrder')}}">
                                <i class="fa fa-shopping-cart fa-5x"></i><br/>
                                <span>Quản lý đơn hàng</span>
                            </a>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>