<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>iweibo - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="wap/common/style.tpl"}-->
</head>
<body>
<!--{include file="wap/common/top.tpl"}-->
<h1>发表广播</h1>
<form id="form1" name="form1" method="post" action="/wap/t/add"  enctype="multipart/form-data"  >
<p>
插入图片:
<input type="file" name="pic" value="插入图片">
<input type="hidden" value="1" name="type">
</p>
<div class="padt"><textarea name="content"><!--{$content}--></textarea>
</div>
<p class="padt"><!--{if $backurl}--><input type="hidden" name="backurl" value="<!--{$backurl}-->"/><!--{/if}--><input type="submit" value="广播" class="button button_blue" name="submit"/> </p>
</form>
<br/>
<p>
1.某些手机浏览器不支持图片上传功能（如iphone自带浏览器， android2.1及以下系统手机自带浏览器等），推荐您使用<a href="http://app.qq.com/g/s?aid=detail&amp;productId=1104&amp;g_f=110075">手机QQ浏览器</a>访问微博，方便拍照上传。<br/>
2.图片上传速度及大小限制视各地网络情况而定，建议200K以内，最大不超过2M。支持JPG、Png格式。<br/>
3.图片路径和名称尽量避免有中文，有些手机不支持中文文件名的图片上传。<br/>
</p>
<!--{include file="wap/common/top.tpl"}-->
</body>
</html>