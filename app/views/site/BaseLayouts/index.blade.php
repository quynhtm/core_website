<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{CGlobal::$pageTitle}}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta property="fb:app_id" content="11336688" />
    <meta name="google-site-verification" content="lJpAlY8qAQ365SzwbRN9_UEySpftXGaB4zgKeZgwKyk" />
    <meta property="og:title" content="@if(isset($title_seo)){{$title_seo}}@else {{CGlobal::web_name}}" @endif/>
    <meta property="og:type" content="product" />
    <meta property="og:url" content="@if(isset($url_seo) && $url_seo != ''){{$url_seo}} @else {{Config::get('config.WEB_ROOT')}} @endif" />
    <meta property="og:image" content="@if(isset($img_seo)){{$img_seo}}@else {{Config::get('config.WEB_ROOT')}}assets/frontend/img/logo.png @endif" />
    <meta property="og:site_name" content="{{CGlobal::web_name}}" />
    <meta property="og:description" content="@if(isset($des_seo)){{$des_seo}}@else {{CGlobal::web_name}}. @endif" />
    <link rel="image_src" href="@if(isset($img_seo)){{$img_seo}}@else {{Config::get('config.WEB_ROOT')}}assets/frontend/img/logo.png @endif" />
    <meta name="DESCRIPTION" content="@if(isset($des_seo)){{$des_seo}}@else {{CGlobal::web_name}}. @endif" />

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
