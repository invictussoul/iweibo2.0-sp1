<!doctype html>
<html>
    <head>
    <title>我的活动 - <!--{$curMenu}--> - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
    </head>
    <body>
    <!--{include file="common/header.tpl"}-->
    <div class="wrapper content">
        <div class="contentleft fleft">
        <div class="tabbar">
            <ul class="tabs">
                <li class="tab "><a href="/event/index">热门推荐</a></li>
                <li class="tab active"><strong href="/event/myevent">我的活动</strong></li>
            </ul>
        </div>
        <div class="eventmenu" align="right">
                <em class="icon_mess"></em> <A href="/event/myjoin">我参与的</a> |
                <em class="icon_note"></em> <a href="/event/myevent/all/1">我发起的</a>
        </div>
        <!--{if $ents.events}-->
            <!--{foreach key=k item=ent from=$ents.events}-->
            <div class="activity">
                <div class="fleft userhead">
                    <a href="/event/view/id/<!--{$ent.id}-->"><img src="<!--{if $ent.pic}--><!--{$ent.pic}--><!--{else}--><!--{$_resource}-->resource/images/default_event.jpg<!--{/if}-->" width="100"/></a>
                </div>
                <div class="fright">
                    <h1><a href="/event/view/id/<!--{$ent.id}-->"><!--{$ent.title}--></a></h1>
                    <ul>
                        <li><strong>时间：</strong><!--{$ent.sdate}--> - <!--{$ent.edate}--></li>
                        <li><strong>地点：</strong><adress><!--{$ent.addr}--></adress></li>
                        <li class="fleft"><strong>发起者：</strong><a href="/u/<!--{$ent.host.name}-->" title="<!--{$ent.host.nick}-->(@<!--{$ent.host.name}-->)" target="_blank"><!--{$ent.host.nick}--></a></li>
                        <li class="fleft"><strong>联系方式：</strong><!--{$ent.phone}--></li>
                        <li class="fleft"><strong>状态：</strong><!--{$ent.statusText}--></li>
                        <li class="fleft"><strong>参加人数：</strong><a href="/event/members/id/<!--{$ent.id}-->" class="gray"><!--{$ent.joins}--></a> 人</li>
                        <li><strong>内容：</strong><!--{$ent.message}--></li>
                    </ul>
                    <!--{if $ent.uname == $user.name}-->
                    <div class="fright" align="right">
                        <!--{if $ent.status != 2}--><a href="/event/close/id/<!--{$ent.id}-->">关闭</a>
                        <!--{else}-->关闭
                        <!--{/if}-->|
                        <a href="/event/modify/id/<!--{$ent.id}-->">编辑</a> |
                        <a href="/event/del/id/<!--{$ent.id}-->">删除</a>
                    </div>
                    <!--{/if}-->
                </div>
            </div>
            <div class="extra"></div>
            <!--{/foreach}-->
        <!--{/if}-->
        <!--{if $ents.size > 0 }-->
                <div class="norecord"><!--{include file="common/multipage1.tpl"}--></div>
        <!--{else}-->
            <div class="norecord">暂无活动</div>
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