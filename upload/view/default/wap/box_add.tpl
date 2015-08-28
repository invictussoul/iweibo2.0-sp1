<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>发私信 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
<!--{include file="wap/common/style.tpl"}-->
</head>
<body>
<!--{include file="wap/common/top.tpl"}-->
<h1>&gt;&gt; <a href="/wap/box/in">收件箱</a> - <a href="/wap/box/out">发件箱</a> - 发私信</h1>
<p>(说明：私信只能发给你的听众，若想与朋友私信交流，请先互相收听)</p>
<form id="form1" name="form1" method="post" action="/wap/box/submit">
<ul>
    <li>输入对方微博帐号</li>
    <li><input type="text" value="<!--{$toname}-->" name="name"/></li>
    <li>或从<a href="/wap/friend/fans">我的听众</a>中选择</li>
    <li>输入私信内容：(140字以内)</li>
</ul>
<textarea rows="4" name="content"></textarea>
<p class="padt"><!--{if $backurl}--><input type="hidden" name="backurl" value="<!--{$backurl}-->"/><!--{/if}--><input type="submit" class="button button_blue" value="发送"/></p>
</form>
<!--{include file="wap/common/top.tpl"}-->
</body>
</html>