<!doctype html>
<html>
<head>
<title><!--{$data.title}--> - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
<!--{include file="common/style.tpl"}-->
</head>
<body>
<!--{include file="common/header.tpl"}-->
<div class="wrapper content">
    <div class="fleft contentleft">
    <!--{$mainComponent}-->
    </div>
    <div class="fright contentright">
        <!--{include file="common/profile.tpl"}-->
        <div class="rightsp" ></div>
        <!--{include file="common/menus.tpl"}-->
        <div class="rightsp"></div>
        <!--{$rightComponent}-->
    </div>
</div>
<!--{include file="common/footer.tpl"}-->
<script src="/resource/js/eventCommon.js"></script>
</body>
</html>