<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>iweibo - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="wap/common/style.tpl"}-->
</head>
<body>
    <!--{include file="wap/common/top.tpl"}-->
    <p>
        <!--{$user.nick}-->
        <!--{if $user.isvip}-->
            <img src="/resource/images/vip.gif"/>
        <!--{/if}-->
         <a href="/wap/user/index/u/<!--{$username}-->">个人资料</a>
     </p>
    <p><a href="/wap/at">提到我的</a>.
        <a href="/wap/friend/fans">听众(<!--{$user.fansnum}-->)</a>.
        <a href="/wap/friend/idol">我收听(<!--{$user.idolnum}-->)</a>.
        <a href="/wap/mine">我的广播(<!--{$user.tweetnum}-->)</a>.
        <a href="/wap/favor">收藏</a>.
        <a href="/wap/box/in">私信</a></p>
 <!--{include file="wap/common/sendbox.tpl"}-->
    <h2>全部广播</h2>
    <!--{include file="wap/common/tbody.tpl"}-->
    <!--{include file="wap/common/pagerwrapper.tpl"}-->
    <p>设置每页条数：
        <!--{foreach key=key item=filter from=$filterlist}-->
            <!--{if $filter==$num}-->
                <strong><!--{$filter}--></strong>
            <!--{else}-->
                <a href="<!--{$requrl}-->/num/<!--{$filter}-->"><!--{$filter}--></a>
            <!--{/if}-->
        <!--{/foreach}-->
    </p>
    <!--{include file="wap/common/top.tpl"}-->
</body>
</html>