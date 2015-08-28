<!doctype html>
<html>
    <head>
    <title>活动 - <!--{$event.title}--> - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
    <script>
        var iwbInstantTimeline = true;
    </script>
    </head>
    <body>
    <!--{include file="common/header.tpl"}-->
    <div class="wrapper content">
        <div class="contentleft fleft">
            <div class="activity">
                <div class="fleft userhead"><img src="<!--{if $event.pic}--><!--{$event.pic}--><!--{else}--><!--{$_resource}-->resource/images/default_event.jpg<!--{/if}-->" width="100"/></div>
                <div class="fright">
                    <h1><!--{$ent.title}--></h1>
                    <ul>
                        <li><strong>时间：</strong><!--{$event.sdate}--> - <!--{$event.edate}--></li>
                        <li><strong>地点：</strong><adress><!--{$event.addr}--></adress></li>
                        <li class="fleft"><strong>发起者：</strong><a href="/u/<!--{$event.host.name}-->" title="<!--{$event.host.nick}-->(@<!--{$event.host.name}-->)" target="_blank"><!--{$event.host.nick}--></a></li>
                        <li class="fleft"><strong>联系方式：</strong><!--{$event.phone}--></li>
                        <li class="fleft"><strong>状态：</strong><!--{$event.statusText}--></li>
                        <li class="fleft"><strong>参加人数：</strong><a href="/event/members/id/<!--{$event.id}-->" class="gray"><!--{$event.joins}--></a> 人</li>
                    </ul>
                    <div>
                    <span class="fleft">
                    <!--{if $joined}-->
                    <a href="/event/cjoin/id/<!--{$event.id}-->" class="button button_gray">取消参加</a>
                    <!--{else}-->
                    <a href="/event/join/id/<!--{$event.id}-->" class="button button_blue">参加</a>
                    <!--{/if}-->
                    </span><span class="fright"><a href="javascript:void(0);" class="iwbAddBtn" data-text="#推荐活动#">分享到我的微博</a></span></div>
                </div>
            </div>
            <div class="activitydes">
                <h2>活动简介</h2>
                <p><!--{$event.message}--></p>
            </div>
            <div class="nobg"><!--{include file="common/sendbox.tpl"}--></div>
            <div class="moduletitle7">
                <strong class="fleft">这个活动参与者（共<a href="/event/members/id/<!--{$event.id}-->"><!--{$users.total}--></a>人）</strong>
                <span class="fright">
                    <!--{if sizeof($user.users) > 8}--><a href="/event/members/id/<!--{$event.id}-->">更多&gt;&gt;</a><!--{/if}--></span>
            </div>
            <ul class="userlist">
            <!--{foreach key=k item=user from=$users.users}-->
                <li>
                <p><a href="/u/<!--{$user.name}-->" target="_blank"><img src="<!--{$user.head}-->"></a></p>
                <h3><a href="/u/<!--{$user.name}-->" title="<!--{$user.nick}-->(@<!--{$user.name}-->)"><!--{$user.nick}--></a></h3>
                <div>
                <!--{if $user.name != $username}-->
                    <!--{if $user.isidol}-->
                        <a data-name="<!--{$user.name}-->" href="javascript:void(0);" data-type="0" data-styleid="1" class="iwbFollowControl follow unfollow"></a>
                    <!--{else}-->
                        <a data-name="<!--{$user.name}-->" href="javascript:void(0);" data-type="1" data-styleid="1" class="iwbFollowControl follow"></a>
                    <!--{/if}-->
                <!--{/if}-->
                </div>
                </li>
            <!--{/foreach}-->
            </ul>
            <div class="extra"></div>
            <div class="tcontainer"><!--{include file="common/tbody.tpl"}--></div>
            <!--{include file="common/pagerwrapper3.tpl"}-->
            </div>
        <div class="contentright fright">
        <!--{include file="user/event_right_list.tpl"}-->
        </div>
    </div>
    <!--{include file="common/footer.tpl"}-->
    <script>
        $(document).ready(function(){
            $(".sendboxtitle").html("关于<a href='javascript:void(0);'><!--{$event.title}--></a>的讨论");
            $("#msgTxt").bind("keydown",function(){
                if(!(/^\#.*\#/).test(this.value)){
                    this.value = '#<!--{$event.title}-->#' + this.value;
                }
            });
        });
    </script>
    <script src="/resource/js/eventShow.js"></script>
    </body>
</html>