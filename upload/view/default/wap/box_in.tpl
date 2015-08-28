<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>收件箱 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
<!--{include file="wap/common/style.tpl"}-->
</head>
<body>
<!--{include file="wap/common/top.tpl"}-->
<h1>&gt;&gt; 收件箱 - <a href="/wap/box/out">发件箱</a> - <a href="/wap/box/add">发私信</a></h1>
<p>收件箱共有<!--{if $boxinfo.totalnum}--><!--{$boxinfo.totalnum}--><!--{else}-->0<!--{/if}-->封信</p>
<ul class="t">
<!--{foreach from=$boxinfo.info item=box}-->
    <li><a href="/wap/u/<!--{$box.name}-->"><!--{$box.nick}--></a><!--{if $box.is_auth}--> <img src="/resource/images/vip.gif"/> <!--{/if}-->对我说：<!--{$box.text}--><br /><!--{$box.timestring}--><br/><a href="/wap/box/add/toname/<!--{$box.name}-->" class="button button_blue">回信</a>  <a href="/wap/box/del/tid/<!--{$box.id}-->" class="button button_gray">删除</a></li>
<!--{/foreach}-->
</ul>
<!--{include file="wap/common/pagerwrapper2.tpl"}-->
<!--{include file="wap/common/top.tpl"}-->
</body>
</html>