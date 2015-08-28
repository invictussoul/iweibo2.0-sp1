<!doctype html>
<html>
    <head>
    <title><!--{$guest.nick}-->的听众 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
    </head>
    <body>
    <!--{include file="common/header.tpl"}-->
<div class="wrapper content">
<div class="fleft contentleft">
    <!--{include file="common/uprofile.tpl"}-->
    <!--主栏组件-->
    <!--{$mainComponent}-->
    <div class="tabbar">
    <ul class="tabs">
        <li class="tab"><a href="/friend/following/uname/<!--{$guest.name}-->"><!--{if $guest.sex==2}-->她<!--{else}-->他<!--{/if}-->收听的人</a></li>
        <li class="tab active"><strong><!--{if $guest.sex==2}-->她<!--{else}-->他<!--{/if}-->的听众</strong></li>
    </ul>
    <div class="fright">
        听众<!--{$unum}-->人
    </div>
    </div>
    <!--{include file="friend/comm_idolfans.tpl"}-->
    <!--{include file="common/pagerwrapper3.tpl"}-->
</div>
<div class="fright contentright">
    <!--{include file="common/uprofile2.tpl"}-->
    <div class="toggle"><!--{if $guest.sex==2}-->她<!--{else}-->他<!--{/if}--></a>收听<!--{$guest.idolnum}-->人</div>
    <!--{include file="common/userlist.tpl"}-->
</div>
</div>
    <!--{include file="common/footcontrol.tpl"}-->
    <!--{include file="common/footer.tpl"}-->
    <script src="/resource/js/guest.js"></script>
    </body>
</html>