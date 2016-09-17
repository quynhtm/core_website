<!DOCTYPE html>
<html lang="en">
<head>
	{{CGlobal::$extraMeta}}
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="shortcut icon" href="{{Config::get('config.WEB_ROOT')}}assets/frontend/img/favicon.ico" type="image/vnd.microsoft.icon">

    {{ HTML::style('assets/frontend/css/site.css?ver='.CGlobal::$css_ver, array(), Config::get('config.SECURE')) }}
	{{CGlobal::$extraHeaderCSS}}
    <script type="text/javascript">
        var WEB_ROOT = "{{url('', array(), Config::get('config.SECURE'))}}";
        var DEVMODE = "{{Config::get('config.DEVMODE')}}";
        var COOKIE_DOMAIN = "{{Config::get('config.DOMAIN_COOKIE_SERVER')}}";
    </script>
    {{ HTML::script('assets/js/jquery.2.1.1.min.js', array(), Config::get('config.SECURE')) }}
    {{CGlobal::$extraHeaderJS}}
    @if(Config::get('config.DEVMODE') == false)
        <meta name="google-site-verification" content="lJpAlY8qAQ365SzwbRN9_UEySpftXGaB4zgKeZgwKyk" />
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-76848213-1', 'auto');
            ga('send', 'pageview');
        </script>
    @endif
</head>
<body>
{{--<div class="alert-w"></div>--}}
<div class="container-page" id="wrapper">
        @if(isset($header))
            <div id="header">
                {{$header}}
            </div>
        @endif

        <div id="content">
            <div class="wrapper-content">
                @if(isset($content))
                    {{$content}}
                @endif
            </div>
        </div>

        @if(isset($footer))
            <div id="footer">
                {{$footer}}
            </div>
        @endif
</div>
{{CGlobal::$extraFooterCSS}}
{{CGlobal::$extraFooterJS}}
</body>
</html>
