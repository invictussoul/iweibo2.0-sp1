<!doctype html>
<html>
    <head>
    <title>提到我的 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
    <script>
        var iwbTimelineMoreType = 3;
    </script>
    </head>
    <body>
    <!--{include file="common/header.tpl"}-->
    <div class="wrapper content">
        <div class="contentleft fleft">
            <!--{include file="common/sendbox.tpl"}-->
            <!--主栏组件-->
            <!--{$mainComponent}-->
            <div class="moduletitle">
                    <strong class="fleft">提到我的</strong>
                    <span class="fright">
                        <!--{foreach key=key item=filter from=$filterlist}-->
                            <!--{if $filter.utype==$utype}-->
                                <strong><!--{$filter.name}--></strong>
                            <!--{else}-->
                                <a href="<!--{$filter.url}-->"><!--{$filter.name}--></a>
                            <!--{/if}-->
                            <!--{if $filter.utype!=64}--> | <!--{/if}-->
                        <!--{/foreach}-->
                    </span>
            </div>
            <div class="tcontainer">
                <!--{include file="common/tbody.tpl"}-->
            </div>
            <!--{if $hasnext===0}-->
                <!--{include file="common/pagerwrapper.tpl"}-->
            <!--{/if}-->
        </div>
        <div class="contentright fright">
            <!--{include file="common/profile.tpl"}-->
            <div class="rightsp" ></div>
            <!--{include file="common/menus.tpl"}-->
            <div class="rightsp"></div>
            <!--右栏组件-->
            <!--{$rightComponent}-->
            <div class="adv"></div>
        </div>
    </div>
    <!--{include file="common/footcontrol.tpl"}-->
    <!--{include file="common/footer.tpl"}-->
    <script src="/resource/js/index.js"></script>
    </body>
</html>