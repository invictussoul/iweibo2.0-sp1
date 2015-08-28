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
        <div align="center">
            <br>
            <a href="javascript:void(0)" class="followunfollowmore bigbtn button_blue" data-forkey="1">一键收听</a>
        </div>
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
    <script src="/resource/js/findPeople.js"></script>
    </body>
</html>