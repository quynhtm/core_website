<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>{{CGlobal::$pageShopTitle}}</title>

    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    {{ HTML::style('assets/css/bootstrap.min.css'); }}
    {{ HTML::style('assets/font-awesome/4.2.0/css/font-awesome.min.css'); }}

    <!-- page specific plugin styles -->

    {{ HTML::style('assets/css/jquery-ui.min.css'); }}
    <!-- text fonts -->
    {{ HTML::style('assets/fonts/fonts.googleapis.com.css'); }}

    {{ HTML::style('assets/css/chosen.min.css'); }}
    <!-- ace styles -->
    {{ HTML::style('assets/css/ace.min.css'); }}

    <!--[if lte IE 9]>
    {{ HTML::style('assets/css/ace-part2.min.css'); }}
    <![endif]-->

    <!--[if lte IE 9]>
    {{ HTML::style('assets/css/ace-ie.min.css'); }}
    <![endif]-->

    {{--{{ HTML::style('assets/css/datepicker.min.css'); }}--}}
    {{ HTML::style('assets/admin/css/admin_css.css'); }}
    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    {{ HTML::script('assets/js/ace-extra.min.js'); }}

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <!--[if lte IE 8]>
    {{ HTML::script('assets/js/html5shiv.min.js'); }}
    {{ HTML::script('assets/js/respond.min.js'); }}
    <![endif]-->
    <script type="text/javascript">
            var WEB_ROOT = "{{ URL::to('/')}}";
        </script>
    <!-- basic scripts -->

    <!--[if !IE]> -->
    {{ HTML::script('assets/js/jquery.2.1.1.min.js'); }}

    <!--[if IE]>
    {{ HTML::script('assets/js/jquery.1.11.1.min.js'); }}
    <![endif]-->

    {{ HTML::script('assets/js/bootstrap.min.js'); }}

    {{ HTML::script('assets/js/ace-elements.min.js'); }}
    {{ HTML::script('assets/js/chosen.jquery.js'); }}
    {{ HTML::script('assets/js/jquery-ui.min.js'); }}
    {{ HTML::script('assets/js/jquery.ui.touch-punch.min.js'); }}
    {{ HTML::script('assets/js/ace.min.js'); }}
    {{ HTML::script('assets/js/ace-elements.min.js'); }}

    {{ HTML::script('assets/js/moment.min.js'); }}
    {{ HTML::script('assets/js/bootbox.min.js'); }}

    {{ HTML::script('assets/admin/js/admin.js'); }}
    {{ HTML::script('assets/admin/js/format.js'); }}

    {{CGlobal::$extraHeaderCSS}}
    {{CGlobal::$extraHeaderJS}}
</head>

<body class="no-skin">
<div id="navbar" class="navbar navbar-default navbar-fixed-top">
    <div class="navbar-container" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left">
            <a href="{{ URL::route('shop.adminShop')}}" class="navbar-brand">
                <small>
                    <i class="fa fa-leaf"></i>
                    Quản trị shop @if($user_shop['shop_name'] != '')- {{$user_shop['shop_name']}}@endif
                </small>
            </a>
        </div>

        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<span class="user-info">
									<small>Xin chào,</small>
                                    {{$user_shop['user_shop']}}
								</span>

                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li>
                            <a href="{{URL::route('site.shopChangePass')}}">
                                <i class="ace-icon fa fa-unlock"></i>
                                Đổi mật khẩu
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="{{URL::route('site.shopLogout')}}">
                                <i class="ace-icon fa fa-power-off"></i>
                                Đăng xuất
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div><!-- /.navbar-container -->
</div>

<div class="main-container" id="main-container">
    <div id="sidebar" class="sidebar sidebar-fixed sidebar-scroll responsive">
        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
            <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                <a href="{{URL::route('site.home')}}" target="_blank" title="Trang chủ shopcuatui.com.vn"><img width="185px" height="50px" src="{{Config::get('config.WEB_ROOT')}}assets/frontend/img/logo.png" alt="ShopCuaTui" /></a>
            </div>

            <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                <span class="btn btn-success"></span>

                <span class="btn btn-info"></span>

                <span class="btn btn-warning"></span>

                <span class="btn btn-danger"></span>
            </div>
        </div><!-- /.sidebar-shortcuts -->

        <ul class="nav nav-list">
            @if(sizeof($supperAdmin) > 0)
                <li class="">
                    <a href="#" target="_blank" class="dropdown-toggle">
                        <i class="menu-icon fa fa-shield"></i>
                        <span class="menu-text">Supper Admin</span>
                        <b class="arrow fa fa-angle-down"></b>
                    </a>
                    <b class="arrow"></b>
                    <ul class="submenu">
                        <li class="">
                            <a href="{{URL::route('admin.dashboard')}}" target="_blank">
                                <i class="menu-icon fa fa-caret-right"></i>
                                Quản trị Admin
                            </a>
                            <b class="arrow"></b>
                        </li>
                    </ul>
                </li>
            @endif

            <li class="@if(Route::currentRouteName() == 'shop.inforShop')active @endif">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-user"></i>
                    <span class="menu-text"> Tài khoản Shop</span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>

                <b class="arrow"></b>
                <ul class="submenu  nav-show ">
                    <li class="@if(Route::currentRouteName() == 'shop.inforShop')active @endif">
                        <a href="{{URL::route('shop.inforShop')}}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Thông tin tài khoản
                        </a>
                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>

            <li class="@if(Route::currentRouteName() == 'shop.listProduct')active @endif">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-gift"></i>
                    <span class="menu-text"> Sản phẩm </span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>
                <b class="arrow"></b>
                <ul class="submenu">
                    <li class="@if(Route::currentRouteName() == 'shop.listProduct')active @endif">
                        <a href="{{URL::route('shop.listProduct')}}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Quản lý sản phẩm
                        </a>
                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>

            <li class="@if(Route::currentRouteName() == 'shop.listOrder')active @endif">
                <a href="#" class="dropdown-toggle">
                    <i class="menu-icon fa fa-shopping-cart"></i>
                    <span class="menu-text"> Quản lý đơn hàng</span>
                    <b class="arrow fa fa-angle-down"></b>
                </a>
                <b class="arrow"></b>
                <ul class="submenu">
                    <li class="@if(Route::currentRouteName() == 'shop.listOrder')active @endif">
                        <a href="{{URL::route('shop.listOrder')}}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            Danh sách đơn hàng
                        </a>
                        <b class="arrow"></b>
                    </li>
                </ul>
            </li>
        </ul><!-- /.nav-list -->

        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>
        <script type="text/javascript">
            try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
        </script>
    </div>
    <div class="main-content">
        {{$content}}
    </div><!-- /.main-content -->
    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-info">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-300"></i>
    </a>
</div><!-- /.main-container -->

{{CGlobal::$extraFooterCSS}}
{{CGlobal::$extraFooterJS}}
</body>
</html>
