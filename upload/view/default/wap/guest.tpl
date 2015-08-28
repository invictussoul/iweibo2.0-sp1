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
        <img src="<!--{$guest.head}-->" width="50" height="50" />
        <span>
            <strong><!--{$guest.nick}--></strong>
            <!--{if $guest.isvip}-->
                <img src="/resource/images/vip.gif"/>
            <!--{/if}-->
            <br/><a href="/wap/user/index/u/<!--{$guest.name}-->">查看资料</a>
        </span>
    </p>
    <p>简介：<!--{$guest.introduction|truncate:70:"..."}--><a href="/wap/user/index/u/<!--{$guest.name}-->">&gt;&gt;</a></p>
    <p><!--{if $guest.isidol}-->已收听[<a href="/wap/friend/follow/type/0/name/<!--{$guest.name}-->">取消</a>]<!--{else}--><a href="/wap/friend/follow/type/1/name/<!--{$guest.name}-->">收听</a><!--{/if}--></p>
    <p><span>广播(<!--{$guest.tweetnum}-->)</span><a href="/wap/friend/fans/uname/<!--{$guest.name}-->">听众(<!--{$guest.fansnum}-->)</a> <a href="/wap/friend/idol/uname/<!--{$guest.name}-->">他收听(<!--{$guest.idolnum}-->)</a> </p>
    <h2><a href="/wap/u/<!--{$guest.name}-->">全部</a> |
        <a href="/wap/u/<!--{$guest.name}-->/utype/1">原创</a> |
        <a href="/wap/u/<!--{$guest.name}-->/utype/2">转播</a> |
        <a href="/wap/search">搜索</a></h2>
    <!--{include file="wap/common/tbody.tpl"}-->
    <!--{include file="wap/common/pagerwrapper.tpl"}-->
    <!--{include file="wap/common/top.tpl"}-->
</body>
</html>