<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>iweibo系统 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="wap/common/style.tpl"}-->
</head>
<body>
    <!--{include file="wap/common/top.tpl"}-->
    <h1><a href="<!--{$rurl}-->">返回前页&gt;&gt;</a></h1>
    <p><img src="<!--{$url|escape:"html"}-->/<!--{$i}-->"/></p>
    <p>
        <!--{foreach key=key item=filter from=$filterlist}-->
            <!--{if $filter.i==$i}-->
                <span><!--{$filter.name}--></span>
            <!--{else}-->
                <a href="<!--{$filter.url}-->" class="button button_gray"><!--{$filter.name}--></a>
            <!--{/if}-->
        <!--{/foreach}-->
    </p>
    <p>免费下载：<a href="<!--{$url}-->/120">120</a> <a href="<!--{$url}-->/240">240</a> <a href="<!--{$url}-->/320">320</a> <a href="<!--{$url}-->/2000">原图</a></p>
    <!--{include file="wap/common/top.tpl"}-->
</body>
</html>