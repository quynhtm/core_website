<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Login Page - Ace Admin</title>

    <meta name="description" content="User login page" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    {{ HTML::style('assets/css/bootstrap.min.css'); }}
    {{--<link rel="stylesheet" href="assets/css/bootstrap.min.css" />--}}
    {{ HTML::style('assets/font-awesome/4.2.0/css/font-awesome.min.css'); }}
    {{--<link rel="stylesheet" href="assets/font-awesome/4.2.0/css/font-awesome.min.css" />--}}

    <!-- page specific plugin styles -->

    <!-- text fonts -->
    {{ HTML::style('assets/fonts/fonts.googleapis.com.css'); }}
    {{--<link rel="stylesheet" href="assets/fonts/fonts.googleapis.com.css" />--}}

    <!-- ace styles -->
    {{ HTML::style('assets/css/ace.min.css'); }}
    {{--<link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />--}}

    <!--[if lte IE 9]>
    {{ HTML::style('assets/css/ace-part2.min.css'); }}
    <![endif]-->

    <!--[if lte IE 9]>
    {{ HTML::style('assets/css/ace-ie.min.css'); }}
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    {{ HTML::script('assets/js/ace-extra.min.js'); }}
    {{--<script src="assets/js/ace-extra.min.js"></script>--}}

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <!--[if lte IE 8]>
    {{ HTML::script('assets/js/html5shiv.min.js'); }}
    {{ HTML::script('assets/js/respond.min.js'); }}
    <![endif]-->
</head>

<body class="login-layout">
<div class="main-container">
    <div class="main-content">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="login-container">
                    <div class="space-6"></div>
                    <div class="position-relative">
                        <div id="login-box" class="login-box visible widget-box no-border">
                            <div class="widget-body">
                                <div class="widget-main">
                                    <h4 class="header blue lighter bigger">
                                        <i class="ace-icon fa fa-unlock green"></i>
                                        ??ng nh?p vào c?a hàng
                                    </h4>

                                    <div class="space-6"></div>
                                    @if(isset($error))
                                        <div class="alert alert-danger">{{$error}}</div>
                                    @endif
                                    {{ Form::open(array('class'=>'form-signin')) }}
                                    <fieldset>
                                        <label class="block clearfix">
                                        <span class="block input-icon input-icon-right">
                                            <input type="text" class="form-control" name="user_name" placeholder="User"  @if(isset($username)) value="{{$username}}" @endif/>
                                            <i class="ace-icon fa fa-user"></i>
                                        </span>
                                        </label>

                                        <label class="block clearfix">
                                        <span class="block input-icon input-icon-right">
                                            <input type="password" class="form-control" name="user_password" placeholder="Password" />
                                            <i class="ace-icon fa fa-lock"></i>
                                        </span>
                                        </label>

                                        <div class="space"></div>

                                        <div class="clearfix">
                                            {{--<label class="inline">--}}
                                            {{--<input type="checkbox" class="ace" />--}}
                                            {{--<span class="lbl"> Remember Me</span>--}}
                                            {{--</label>--}}

                                            <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                <i class="ace-icon fa fa-key"></i>
                                                <span class="bigger-110">??ng nh?p</span>
                                            </button>
                                        </div>

                                        <div class="space-4"></div>
                                    </fieldset>
                                    {{ Form::close() }}
                                </div><!-- /.widget-main -->
                            </div><!-- /.widget-body -->
                        </div><!-- /.login-box -->
                    </div><!-- /.position-relative -->
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.main-content -->
</div><!-- /.main-container -->
</body>
</html>
