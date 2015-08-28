<!doctype html>
<html>
    <head>
    <title>私信_收件箱 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
    <script>
        var iwbTimelineMoreType = 1;
    </script>
    </head>
    <body>
    <!--{include file="common/header.tpl"}-->
<div class="wrapper content">
<div class="fleft contentleft">
    <!--主栏组件-->
    <!--{$mainComponent}-->
    <div class="moduletitle4"><strong class="fleft"><em class="icon_mail"></em>私信</strong><span class="fright">私信只能发送给你的听众，若想与朋友通过私信交流，请先相互收听</span></div>
    <div class="tabbar">
        <ul class="tabs">
        <li class="tab active"><strong>收件箱</strong></li>
        <li class="tab"><a href="/box/sendbox">发件箱</a></li>
        </ul>
        <div class="fright">
            <label>收件箱共有<!--{if $boxinfo.totalnum}--><!--{$boxinfo.totalnum}--><!--{else}-->0<!--{/if}-->封信</label> <a href="javascript:void(0);" title="发私信" class="sendmail button button_blue">发私信</a>
        </div>
    </div>
    <!--{if $boxinfo.info}-->
    <ul class="topicshow">
    <!--{foreach from=$boxinfo.info item=box}-->
    <li id="<!--{$box.id}-->">
      <div class="fleft"><a href="/u/<!--{$box.name}-->"><img src="<!--{$box.head}-->"  onerror="this.src=iwbResourceRoot+'resource/images/default_head_50.jpg'"></a> <a href="javascript:void(0);" data-username="cydreader"></a></div>
      <div class="fright">
            <h6> <a href="/u/<!--{$box.name}-->"><!--{$box.nick}--></a> &nbsp;<!--{if $box.is_auth}--><em class="icon_vip"></em><!--{/if}-->： </h6>
            <p> <!--{$box.text}--></p>
            <div><!--{$box.timestring}--></div>
            <span class="action">
            <a href="javascript:void(0);" data-receiver="<!--{$box.name}-->" class="sendmail button button_gray">回信</a>
            <a href="javascript:void(0);" data-msgid="<!--{$box.id}-->" class="deletemail button button_gray">删除</a>
            </span>
        </div>
        <div class="extra"></div>
    </li>
    <!--{/foreach}-->
    </ul>
    <!--{else}-->
        <p class="norecord" id="tmainnorecord">暂无内容<p>
    <!--{/if}-->
    <!--{include file="common/pagerwrapper3.tpl"}-->
</div>
<div class="fright contentright">
    <!--{include file="common/profile.tpl"}-->
    <div class="rightsp" ></div>
    <!--{include file="common/menus.tpl"}-->
    <div class="rightsp"></div>
    <!--右栏组件-->
    <!--{$rightComponent}-->
</div>
</div>
<!--{include file="common/footcontrol.tpl"}-->
<!--{include file="common/footer.tpl"}-->
<script src="/resource/js/mail.js"></script>
</body>
</html>