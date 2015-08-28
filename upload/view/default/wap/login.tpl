<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>登录 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="wap/common/style.tpl"}-->
</head>
<body>
    <!--{include file="wap/common/header.tpl"}-->
    <!--{if $type==1}-->
        <div class="result">
            <h2><!--{$message}--></h2>
            <div><a href="<!--{$url}-->" class="bindingbtn"><!--{$btntext}--></a></div>
            <!--{$ucsyn}-->
        </div>
        <meta http-equiv="refresh" content="1; url=<!--{$url}-->" />
    <!--{elseif $type==2}-->
        <form name="form1" method="post" action="/wap/login/l" class="regform">
            <label>绑定腾讯微博帐号</label><br />
            <label>用户名：</label><br />
            <input type="text" id="username" name="username" /><br />
            <label>密码：</label>
            <br />
            <input type="password" id="pwd" name="pwd" /><br />
            <input type="submit" value="绑定腾讯微博" class="button button_blue" />
        </form>
    <!--{else}-->
        <form id="form1" name="form1" method="post" action="/wap/login/l">
            <ul>
            <!--{if $siteClosePrompt}--><li><label><!--{$siteClosePrompt}--></label></li><!--{/if}-->
            <li><label>本地帐号：</label></li>
            <li><input type="text" id="username" name="username" /></li>
            <li><label>密码：</label></li>
            <li>
            <input type="password" id="pwd" name="pwd" /><br />
            <input type="checkbox" id="autologin" name="autologin" value="1" /><label for="checkbox">下次自动登录(需支持cookie)</label></li>
            <li><input type="submit" value="登录" class="button button_blue"/></li>
            <li><label><a href="/wap/login/r">使用腾讯微博帐号/QQ登录</a></label></li>
            </ul>
        </form>
    <!--{/if}-->
    <!--{include file="wap/common/header.tpl"}-->
</body>
</html>