<!doctype html>
<html>
    <head>
        <title>广播大厅 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
        <!--{include file="common/style.tpl"}-->
    </head>
    <body>
        <!--{include file="common/header.tpl"}-->
        <div class="wrapper content">
           <div class="contentleft fleft">
                <!--主栏组件-->
                <!--{$mainComponent}-->
                <!--{$tabbar}-->
                <div class="tcontainer">
                    <!--{include file="common/titem.tpl"}-->
                </div>
                <!--{if $hasnext===0}-->
                    <!--{include file="common/pagerwrapper2.tpl"}-->
                <!--{/if}-->
            </div>
            <div class="contentright fright">
                <div class="rightsp" ></div>
                <!--右栏组件-->
                <!--{$rightComponent}-->
                <div class="rightsp" ></div>
                <div class="adv"></div>
            </div>
        </div>
        <!--{include file="common/footcontrol.tpl"}-->
        <!--{include file="common/footer.tpl"}-->
        <script src="/resource/js/guest.js"></script>
    </body>
</html>