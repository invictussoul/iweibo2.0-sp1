<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>搜索 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
<!--{include file="wap/common/style.tpl"}-->
</head>
<body>
<!--{include file="wap/common/top.tpl"}-->
<form action="/wap/search/t" method="post">
<p><strong>搜广播</strong>
<a href="/wap/search/u<!--{$data.addkey}-->" class="button button_gray">搜人</a>
<a href="/wap/search/tag<!--{$data.addkey}-->" class="button button_gray">搜标签</a>
</p>
<p class="padt">
<input type="text" name="k" value="<!--{if isset($searchkey)}--><!--{$searchkey}--><!--{/if}-->"/>
<input type="submit" value="搜广播"  class="button button_blue">
</p>
</form>
<h2>【热门用户】</h2>
<p>
<!--{foreach name=foo key=key item=hot from=$hotuser}-->
<!--{if $smarty.foreach.foo.index<6}-->
<a href="/wap/u/<!--{$hot.name}-->"><!--{$hot.nick}--></a>
<!--{/if}-->
<!--{/foreach}-->
</p>
<h2>【热门话题】</h2>
<!--{include file="wap/common/hottopic.tpl"}-->
<!--{include file="wap/common/top.tpl"}-->
</body>
</html>