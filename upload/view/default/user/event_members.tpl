<!doctype html>
<html>
    <head>
    <title>活动 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
    </head>
    <body>
    <!--{include file="common/header.tpl"}-->
    <div class="wrapper content">
        <div class="contentleft fleft">
            <div class="crumb">
                    <span class="fleft"><a href="/event/index">活动</a> &gt; <!--{$curMenu}--></span>
                    <span class="fright"><a href="/event/view/id/<!--{$event.id}-->">返回&gt;&gt;</a></span>
            </div>
        <div class="activitycontent"><strong>活动内容：</strong><!--{$event.message}--></div>
        <div class="crumb"><strong class="fleft">这个活动参与者(共<!--{$joins.total}-->人)</strong></div>
        <ul class="activitymembers">
        <!--{foreach name=rlist key=k item=user from=$joins.users}-->
        <li>
            <div class="fleft userhead"><a href="/u/<!--{$user.name}-->" class="" ><img src="<!--{$user.head}-->"/></a></div>
            <div class="fright">
                <a href="/u/<!--{$user.name}-->" title="<!--{$user.name}-->" title="<!--{$user.nick}-->(@<!--{$user.name}-->)"><!--{$user.nick}--></a>
                <!--{if ($user.isvip && $authtype.platform) || ($user.localauth && $authtype.local)}-->
                <span class="icon_vip"></span>
                <!--{/if}-->
                <address><!--{$user.location}--></address>
                <!--{if $event.contact && $username == $event.uname}-->
                    联系方式：<!--{$user.contact}-->
                <!--{/if}-->
                &nbsp; 听众 <a href="/friend/follower/uname/<!--{$user.name}-->"><!--{$user.fansnum}--></a>人
                <p><!--{$user.msg}--></p>
            </div>
        </li>
        <!--{/foreach}-->
        </ul>
        <!--{if $joins.total > 0 }-->
            <div class="norecord"><!--{include file="common/multipage1.tpl"}--></div>
        <!--{/if}-->
        </div>
        <div class="contentright fright">
        <!--{include file="user/event_right_list.tpl"}-->
        </div>
    </div>
    <!--{include file="common/footer.tpl"}-->
    <script src="/resource/js/eventCommon.js"></script>
    </body>
</html>