<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>搜索标签 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
<!--{include file="wap/common/style.tpl"}-->
</head>
<body>
<!--{include file="wap/common/top.tpl"}-->
<form action="/wap/search/tag" method="post">
<p>
<a href="/wap/search/t<!--{$data.addkey}-->" class="button button_gray">搜广播</a>
<a href="/wap/search/u<!--{$data.addkey}-->" class="button button_gray">搜人</a>
<strong>搜标签</strong>
</p>
<p class="padt">
<input type="text" name="k" value="<!--{if isset($searchkey)}--><!--{$searchkey}--><!--{/if}-->"/>
<input type="submit" value="搜标签"  class="button button_blue">
</p>
</form>
<p class="r">搜索标签“<!--{$data.searchkey}-->”，获得<!--{if $data.unum}--><!--{$data.unum}--><!--{else}-->0<!--{/if}-->条结果</p>
<ul class="u">
<!--{foreach from=$data.u item=it}-->
<li><a href="/wap/u/<!--{$it.name}-->"><!--{$it.nick}--></a><!--{if $it.is_auth}--><img src="/resource/images/vip.gif"/><!--{/if}-->（@<!--{$it.name}-->）<br/>听众<!--{if $it.fansnum}--><!--{$it.fansnum}--><!--{else}-->0<!--{/if}-->人<!--{if $it.name!=$username}-->[<!--{if $it.isidol}--><a href="/wap/friend/follow/type/0/name/<!--{$it.name}--><!--{$backurl}-->">取消收听</a><!--{else}--><a href="/wap/friend/follow/type/1/name/<!--{$it.name}--><!--{$backurl}-->">收听</a><!--{/if}-->]<!--{/if}--><div><!--{$it.tags_light}--></div></li>
<!--{/foreach}-->
</ul>
<!--{include file="wap/common/pagerwrapper2.tpl"}-->
<!--{include file="wap/common/top.tpl"}-->
</body>
</html>