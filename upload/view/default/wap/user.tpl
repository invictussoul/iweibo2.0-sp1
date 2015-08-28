<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>iweibo - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="wap/common/style.tpl"}-->
</head>
<body>
    <!--{include file="wap/common/top.tpl"}-->
    <p>&gt;&gt; 个人资料</p>
    <h1>【头像】 </h1>
    <img src="<!--{$user.head}-->"/>
    <h2>【个人信息】 <!--{if $user.name==$username}--><a href="/wap/user/setting">设置</a><!--{/if}--></h2>
    <p><!--{$user.nick}-->@<!--{$user.name}--><br/>
       <!--{if $user.sex==2}-->她<!--{else}-->他<!--{/if}-->/<!--{$user.birth_month}-->月出生<br/>
       [个人简介]：<!--{$user.introduction}-->
    </p>
    <!--{include file="wap/common/top.tpl"}-->
</body>
</html>