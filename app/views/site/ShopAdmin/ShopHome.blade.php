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
                    <h3 class="box-title" style="text-align: center;">Chào mừng bạn đến hệ thống quản lý Shop - {{CGlobal::web_name}} </h3>
                </div>
                @if(isset($error) && !empty($error))
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p><b>{{ $itmError }}</b></p>
                        @endforeach
                    </div>
                @endif

                <div class="box-body" style="margin-top: 35px">
                    <div class="col-sm-6 col-md-3">
                        <a class="quick-btn a_control" target="_blank" href="{{URL::route('shop.home', array('shop_id'=>$user_shop->shop_id, 'shop_name'=>$user_shop->shop_name))}}">
                            <div class="thumbnail text-center">
                                <i class="fa fa-home fa-5x"></i><br/>
                                <span>Trang Shop</span>
                            </div>
                        </a>
                    </div>
                    <div class="box-body">
                    <div class="col-sm-6 col-md-3">
                        <a class="quick-btn a_control" href="{{URL::route('shop.inforShop')}}">
                            <div class="thumbnail text-center">
                                <i class="fa fa-pencil-square-o fa-5x"></i><br/>
                                <span>Thông tin shop</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <a class="quick-btn a_control" href="{{URL::route('shop.listProduct')}}">
                            <div class="thumbnail text-center">

                                <i class="fa fa-sitemap fa-5x"></i><br/>
                                <span>Quản lý sản phẩm</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <a class="quick-btn a_control" href="{{URL::route('shop.listOrder')}}">
                            <div class="thumbnail text-center">
                                <i class="fa fa-shopping-cart fa-5x"></i><br/>
                                <span>Quản lý đơn hàng</span>
                            </div>
                        </a>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-sm-6 col-md-3">
                        <a class="quick-btn a_control" href="{{URL::route('shop.orderShopOffline')}}">
                            <div class="thumbnail text-center">
                                <i class="fa fa-random fa-5x"></i><br/>
                                <span>Bán hàng tại Shop</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <a class="quick-btn a_control" href="{{URL::route('shop.listBanner')}}">
                            <div class="thumbnail text-center">
                                <i class="fa fa-globe fa-5x"></i><br/>
                                <span>Banner quảng cáo</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <a class="quick-btn a_control" href="{{URL::route('shop.listProvider')}}">
                            <div class="thumbnail text-center">
                                <i class="fa fa-users fa-5x"></i><br/>
                                <span>QL nhà cung cấp</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>