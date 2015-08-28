<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>搜索用户 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
<!--{include file="wap/common/style.tpl"}-->
</head>
<body>
<!--{include file="wap/common/top.tpl"}-->
<form action="/wap/search/u" method="post">
<p>
<a href="/wap/search/t<!--{$data.addkey}-->" class="button button_gray">搜广播</a>
<strong>搜人</strong>
<a href="/wap/search/tag<!--{$data.addkey}-->" class="button button_gray">搜标签</a>
</p>
<p class="padt">
<input type="text" name="k" value="<!--{if isset($searchkey)}--><!--{$searchkey}--><!--{/if}-->"/>
<input type="submit" value="搜人"  class="button button_blue">
</p>
</form>
<p class="r">“<!--{$searchkey}-->”相关人<!--{if $data.unum}--><!--{$data.unum}--><!--{else}-->0<!--{/if}-->条结果</p>
<ul class="u">
<!--{foreach from=$data.u item=it}-->
<li><a href="/wap/u/<!--{$it.name}-->"><!--{if $it.nick_light}--><!--{$it.nick_light}--><!--{else}--><!--{$it.nick}--><!--{/if}--></a>(<!--{$it.name}-->)<!--{if $it.is_auth}--><img src="/resource/images/vip.gif"/><!--{/if}--><br/>听众<!--{if $it.idolnum}--><!--{$it.idolnum}--><!--{else}-->0<!--{/if}-->人<!--{if $it.name!=$username}-->[<!--{if $it.isidol}--><a href="/wap/friend/follow/type/0/name/<!--{$it.name}--><!--{$backurl}-->">取消收听</a><!--{else}--><a href="/wap/friend/follow/type/1/name/<!--{$it.name}--><!--{$backurl}-->">收听</a><!--{/if}-->]<!--{/if}--></li>
<!--{/foreach}-->
</ul>
<!--{include file="wap/common/pagerwrapper2.tpl"}-->
<!--{include file="wap/common/top.tpl"}-->
</body>
</html>