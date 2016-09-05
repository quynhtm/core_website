<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                Home
            </li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box-header">
                    <h3 class="box-title" style="text-align: center;">Quản lý CMS của {{CGlobal::web_name}} </h3>
                </div>

                <div class="box-body" style="margin-top: 50px">
                    @if($is_root || in_array('user_view',$aryPermission))
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail text-center">
                            <a class="quick-btn" href="{{URL::route('admin.user_view')}}">
                                <i class="fa fa-user fa-5x"></i><br/>
                                <span>Quản lý User</span>
                            </a>
                        </div>
                    </div>
                    @endif
                    @if($is_root || in_array('categories_view',$aryPermission))
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail text-center">
                            <a class="quick-btn" href="{{URL::route('admin.category_list')}}">
                                <i class="fa fa-sitemap fa-5x"></i><br/>
                                <span>Danh mục sản phẩm</span>
                            </a>
                        </div>
                    </div>
                    @endif
                    @if($is_root || in_array('order_view',$aryPermission))
                        <div class="col-sm-6 col-md-3">
                            <div class="thumbnail text-center">
                                <a class="quick-btn" href="{{URL::route('admin.news_list')}}">
                                    <i class="fa fa-book fa-5x"></i><br/>
                                    <span>Quản lý tin tức</span>
                                </a>
                            </div>
                        </div>
                    @endif
					@if($is_root || in_array('order_view',$aryPermission))
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail text-center">
                            <a class="quick-btn" href="{{URL::route('admin.order_list')}}">
                                <i class="fa fa-shopping-cart fa-5x"></i><br/>
                                <span>Quản lý đơn hàng</span>
                            </a>
                        </div>
                    </div>
                    @endif


                    <div class="clearfix"></div>
                    @if($is_root || in_array('user_shop_view',$aryPermission))
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail text-center">
                            <a class="quick-btn" href="{{URL::route('admin.userShop_list')}}">
                                <i class="fa fa-users fa-5x"></i><br/>
                                <span>Danh sách Shop</span>
                            </a>
                        </div>
                    </div>
                    @endif
                 </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>