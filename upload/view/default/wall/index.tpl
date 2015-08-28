<!doctype html>
<html>
    <head>
    <title>上墙 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <link rel="apple-touch-icon-precomposed" href="http://mat1.gtimg.com/www/mb/images/microblog_72_72.png" />
    <link rel="stylesheet" href='/resource/css/wall.css' type="text/css" media="screen, projection">
    <script>
        var iwbRoot = '<!--{$_pathroot}-->';
        var iwbResourceRoot = '<!--{$_resource}-->';
        var iwbUsername = '<!--{$username}-->';
        var wallStart = <!--{$totalCount}-->;
        var wallId = <!--{$topic.tid}-->;
    </script>
    </head>
    <body scroll="no">
<div class="wrapper head">
    <a href="#" class="logo"><img src="/resource/images/logo.png" resource="<!--{$_resource}-->"/></a>
    <div class="banner"><label>在网页或手机发</label><strong>#<!--{$topic.title}-->#</strong><label>上墙</label></div>
</div>
<!--[if IE]> <div class="wrapper walltop"></div><![endif]-->
<div class="wrapper wall">
    <ul id="walllist">
        <!--{include file="wall/wblogs.tpl"}-->
    </ul>
</div>
<!--[if IE]> <div class="wrapper wallbottom"></div><![endif]-->
<script src="/resource/js/thirdparty/jquery/jquery-all.js"></script>
<script src="/resource/js/iwbFramework/iwb.js"></script>
<script src="/resource/js/wall.js"></script>
</body>
</html>