<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>iweibo - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="wap/common/style.tpl"}-->
</head>
<body>
    <!--{include file="wap/common/top.tpl"}-->
    <h1>&gt;&gt;个人资料</h1>
    <form action="/wap/user/save" method="post">
    <p>微博帐号：<!--{$user.name}--></p>
    <p>名字（必填）：<input type="text" name="nick" value="<!--{$user.nick}-->"/><br/>
    <label class="gray">（1-12位中文、字母、数字、下划线或中划线，以中文、字母或数字开头）</label>
    </p>
    <p>性别（必选）：<input type="radio" value="1" name="sex" <!--{if $user.sex==1}-->checked<!--{/if}--> />男 <input type="radio" value="2" name="sex" <!--{if $user.sex==2}-->checked<!--{/if}--> />女</p>
    <p>生日：<input type="text" name="month" value="<!--{$user.birth_month}-->" size="5"/>月<input type="text" name="day" value="<!--{$user.birth_day}-->" size="5" />日</p>
    <p>个人简介：</p>
    <textarea name="introduction"><!--{$user.introduction}--></textarea>
        <p class="padt"><input type="submit" value="保存" class="button button_blue"/></p>
    </form>
    <!--{include file="wap/common/top.tpl"}-->
</body>
</html>