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
                @if(isset($error) && !empty($error))
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p><b>{{ $itmError }}</b></p>
                        @endforeach
                    </div>
                @endif
                <div class="box-body" style="margin-top: 50px">
                    @if($is_root || in_array('user_view',$aryPermission))
                    <div class="col-sm-6 col-md-3">
                        <a class="quick-btn a_control" href="{{URL::route('admin.user_view')}}">
                            <div class="thumbnail text-center">
                                <i class="fa fa-user fa-5x"></i><br/>
                                <span>Quản lý User</span>
                            </div>
                        </a>
                    </div>
                    @endif
                    @if($is_root || in_array('categories_view',$aryPermission))
                        <div class="col-sm-6 col-md-3">
                            <a class="quick-btn a_control" href="{{URL::route('admin.category_list')}}">
                                <div class="thumbnail text-center">
                                    <i class="fa fa-sitemap fa-5x"></i><br/>
                                    <span>Danh mục sản phẩm</span>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if($is_root || in_array('product_view',$aryPermission))
                        <div class="col-sm-6 col-md-3">
                            <a class="quick-btn a_control" href="{{URL::route('admin.product_list')}}">
                                <div class="thumbnail text-center">
                                    <i class="fa fa-gift fa-5x"></i><br/>
                                    <span>Sản phẩm</span>
                                </div>
                            </a>
                        </div>
                    @endif
					@if($is_root || in_array('order_view',$aryPermission))
                    <div class="col-sm-6 col-md-3">
                        <a class="quick-btn a_control" href="{{URL::route('admin.order_list')}}">
                            <div class="thumbnail text-center">
                                <i class="fa fa-shopping-cart fa-5x"></i><br/>
                                <span>Quản lý đơn hàng</span>
                            </div>
                        </a>
                    </div>
                    @endif



                    <div class="clearfix"></div>
                    @if($is_root || in_array('user_shop_view',$aryPermission))
                    <div class="col-sm-6 col-md-3">
                        <a class="quick-btn a_control" href="{{URL::route('admin.userShop_list')}}">
                            <div class="thumbnail text-center">
                                <i class="fa fa-users fa-5x"></i><br/>
                                <span>Danh sách Shop</span>
                            </div>
                        </a>
                    </div>
                    @endif
                    @if($is_root || in_array('order_view',$aryPermission))
                        <div class="col-sm-6 col-md-3">
                            <a class="quick-btn a_control" href="{{URL::route('admin.news_list')}}">
                                <div class="thumbnail text-center">
                                    <i class="fa fa-book fa-5x"></i><br/>
                                    <span>Quản lý tin tức</span>
                                </div>
                            </a>
                        </div>
                    @endif
                    @if($is_root || in_array('banner_view',$aryPermission))
                    <div class="col-sm-6 col-md-3">
                        <a class="quick-btn a_control" href="{{URL::route('admin.banner_list')}}">
                            <div class="thumbnail text-center">
                                <i class="fa fa-globe fa-5x"></i><br/>
                                <span>Quảng cáo</span>
                            </div>
                        </a>
                    </div>
                    @endif
                    @if($is_root || in_array('banner_view',$aryPermission))
                    <div class="col-sm-6 col-md-3">
                        <a class="quick-btn a_control" href="{{URL::route('admin.provider_list')}}">
                            <div class="thumbnail text-center">
                                <i class="fa fa-briefcase fa-5x"></i><br/>
                                <span>Nhà cung cấp của Shop</span>
                            </div>
                        </a>
                    </div>
                    @endif

                    @if($is_root || in_array('contact_view',$aryPermission))
                    <div class="col-sm-6 col-md-3">
                        <a class="quick-btn a_control" href="{{URL::route('admin.contact_list')}}">
                            <div class="thumbnail text-center">
                                <i class="fa fa-envelope-o fa-5x"></i><br/>
                                <span>Liên hệ của Shop</span>
                            </div>
                        </a>
                    </div>
                    @endif
                 </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div>