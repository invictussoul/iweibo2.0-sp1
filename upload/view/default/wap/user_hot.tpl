<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>iweibo - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="wap/common/style.tpl"}-->
</head>
<body>
    <!--{include file="wap/common/top.tpl"}-->
    <h1>推荐名人</h1>
    <p>
       <!--{include file="wap/common/hotuser.tpl"}-->
    </p>
    <h2>名人列表</h2>
    <ul class="u">
        <!--{foreach key=key item=hot from=$userlist}-->
           <li>
                <a href="/wap/u/<!--{$hot.name}-->"><!--{$hot.nickname}--></a>(<!--{$hot.name}-->)
                <!--{if ($hot.isvip && $authtype.platform) || ($hot.localauth && $authtype.local)}-->
                    <img src="/resource/images/vip.gif"/>
                <!--{/if}-->：<br>听众<!--{$hot.fansnum}-->人
                <!--{if $hot.isidol}-->
                [<a href="/wap/friend/follow/type/0/name/<!--{$hot.name}-->">取消收听</a>]
                <!--{elseif $hot.name != $username}-->
                [<a href="/wap/friend/follow/type/1/name/<!--{$hot.name}-->">收听</a>]
                <!--{/if}-->
            </li>
        <!--{/foreach}-->
    </ul>
    <!--{include file="wap/common/top.tpl"}-->
</body>
</html>