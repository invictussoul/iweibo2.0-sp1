<!doctype html>
<html>
    <head>
    <title>微直播 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
    </head>
    <body>
    <!--{include file="common/header.tpl"}-->
    <div class="wrapper content">
        <div class="contentleft fleft">
            <div class="moduletitle4">
                <strong class="fleft">精彩直播</strong>
            </div>
            <!--{if $top}-->
            <div class="livewrapper">
                <div class="livebroadcast">
                <h1>
                    <label><A href="/tlive/view/id/<!--{$top.id}-->" title="<!--{$top.tname}-->"><!--{$top.tname|itruncate:50}--></a></label>&nbsp;&nbsp;
                    <!--{if $top.statusText == '进行中'}-->
                    <em class="icon_living"></em>
                    <!--{/if}-->
                    <!--{if $top.statusText == '已结束'}-->
                    <em class="icon_lived"></em>
                    <!--{/if}-->
                    <!--{if $top.statusText == '未开始'}-->
                    <em class="icon_liver"></em>
                    <!--{/if}-->
                </h1>
                    <a href="/tlive/view/id/<!--{$top.id}-->" class="userhead fleft"><img src="<!--{if $top.style.cover}--><!--{$top.style.cover}--><!--{else}--><!--{$_resource}-->resource/images/default_head_120.jpg<!--{/if}-->" width="100"/></a>
                    <div class="fright">
                        <div class="gray"><!--{$top.sdate|idate:"m月d日 H:i"}--> - <!--{$top.edate|idate:"m月d日 H:i"}--></div>
                        <p><!--{$top.desc}--></p>
                        <div>直播主持人：<a href="/u/<!--{$top.user.name}-->" target="_blank" title="<!--{$top.user.nick}-->(@<!--{$top.user.name}-->)"><!--{if $top.user.nick != ''}--><!--{$top.user.nick}--><!--{else}--><!--{$top.host}--><!--{/if}--></a>&nbsp;
                            <!--{if $top.user.name != $username}-->
                                <!--{if $top.user.isidol}-->
                                <a data-name="<!--{$top.user.name}-->" href="javascript:void(0);" data-type="0" data-styleid="1" class="iwbFollowControl follow unfollow"></a>
                                <!--{else}-->
                                <a data-name="<!--{$top.user.name}-->" href="javascript:void(0);" data-type="1" data-styleid="1" class="iwbFollowControl follow"></a>
                                <!--{/if}-->
                            <!--{/if}-->
                        </div>
                    </div>
                </div>
                <div class="slider3" id="peopleSlider">
                    <h2>特邀嘉宾</h2>
                    <div class="sbox">
                        <a href="javascript:void(0)" class="btna"></a><a href="javascript:void(0)" class="btnb"></a>
                        <div class="box">
                            <ul class="userlist">
                            <!--{foreach name=files item=user key=i from=$joins.data}-->
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
                        </div>
                    </div>
                </div>
            </div>
            <!--{else}-->
            <div class="norecord">暂无直播</div>
            <!--{/if}-->
            <ul class="livelist">
            <!--{foreach name=files item=tlive key=i from=$curtls}-->
                <li>
                    <a href="/tlive/view/id/<!--{$tlive.id}-->" class="userhead fleft"><img src="<!--{if $tlive.style.cover}--><!--{$tlive.style.cover}--><!--{else}--><!--{$_resource}-->resource/images/default_head_120.jpg<!--{/if}-->" width="70" height="70"/></a>
                    <div class="fright">
                        <h2><a href="/tlive/view/id/<!--{$tlive.id}-->" title="<!--{$tlive.tname}-->"><!--{$tlive.tname|itruncate:26}--></a>&nbsp;
                            <!--{if $tlive.statusText == '进行中'}-->
                            <em class="icon_living"></em>
                            <!--{/if}-->
                            <!--{if $tlive.statusText == '已结束'}-->
                            <em class="icon_lived"></em>
                            <!--{/if}-->
                            <!--{if $tlive.statusText == '未开始'}-->
                            <em class="icon_liver"></em>
                            <!--{/if}-->
                    </h2>
                        <div class="gray"><!--{$tlive.sdate|idate:"m月d日 H:i"}--> - <!--{$tlive.edate|idate:"m月d日 H:i"}--></div>
                        <div>主持人：<a href="/u/<!--{$tlive.user.name}-->" target="_blank" title="<!--{$tlive.user.nick}-->(@<!--{$tlive.user.name}-->)"><!--{if $tlive.user.nick != ''}--><!--{$tlive.user.nick}--><!--{else}--><!--{$tlive.host}--><!--{/if}--></a>&nbsp;
                        <!--{if $tlive.user.name != $username}-->
                            <!--{if $tlive.user.isidol}-->
                            <a data-name="<!--{$tlive.user.name}-->" href="javascript:void(0);" data-type="0" data-styleid="1" class="iwbFollowControl follow unfollow"></a>
                            <!--{else}-->
                            <a data-name="<!--{$tlive.user.name}-->" href="javascript:void(0);" data-type="1" data-styleid="1" class="iwbFollowControl follow"></a>
                            <!--{/if}-->
                        <!--{/if}-->
                        </div>
                    </div>
                </li>
            <!--{/foreach}-->
            <!--{foreach name=files item=tlive key=i from=$latertls}-->
                <li>
                    <a href="/tlive/view/id/<!--{$tlive.id}-->" class="userhead fleft"><img src="<!--{if $tlive.style.cover}--><!--{$tlive.style.cover}--><!--{else}--><!--{$_resource}-->resource/images/default_head_120.jpg<!--{/if}-->" width="70" height="70"/></a>
                    <div class="fright">
                        <h2><a href="/tlive/view/id/<!--{$tlive.id}-->" title="<!--{$tlive.tname}-->"><!--{$tlive.tname|itruncate:26}--></a>&nbsp;
                            <!--{if $tlive.statusText == '进行中'}-->
                            <em class="icon_living"></em>
                            <!--{/if}-->
                            <!--{if $tlive.statusText == '已结束'}-->
                            <em class="icon_lived"></em>
                            <!--{/if}-->
                            <!--{if $tlive.statusText == '未开始'}-->
                            <em class="icon_liver"></em>
                            <!--{/if}-->
                    </h2>
                        <div class="gray"><!--{$tlive.sdate|idate:"m月d日 H:i"}--> -
                        <!--{$tlive.edate|idate:"m月d日 H:i"}--></div>
                        <div>主持人：<a href="/u/<!--{$tlive.user.name}-->" target="_blank" title="<!--{$tlive.user.nick}-->(@<!--{$tlive.user.name}-->)"><!--{if $tlive.user.nick != ''}--><!--{$tlive.user.nick}--><!--{else}--><!--{$tlive.host}--><!--{/if}--></a>&nbsp;
                        <!--{if $tlive.user.name != $username}-->
                            <!--{if $tlive.user.isidol}-->
                            <a data-name="<!--{$tlive.user.name}-->" href="javascript:void(0);" data-type="0" data-styleid="1" class="iwbFollowControl follow unfollow"></a>
                            <!--{else}-->
                            <a data-name="<!--{$tlive.user.name}-->" href="javascript:void(0);" data-type="1" data-styleid="1" class="iwbFollowControl follow"></a>
                            <!--{/if}-->
                        <!--{/if}-->
                        </div>
                    </div>
                </li>
            <!--{/foreach}-->
            </ul>
        </div>
        <div class="contentright fright">
            <div class="livedes">
                <h2>关于微直播</h2>
                <p class="gray">微直播是以微博的形式，为您全方位展现和报道各类活动。在活动现场，你还可以第一时间“面对面”与嘉宾主持人进行交流，一起参与到活动的报道中来。您想了解更感兴趣的内容吗？你有关于本次活动的精彩内容要与大家分享么？还等什么，赶紧选择你最关心的微直播内容，与各位嘉宾主持人一起主持和报道吧！</p>
            </div>
        </div>
    </div>
    <!--{include file="common/footer.tpl"}-->
    <script src="/resource/js/liveIndex.js"></script>
    </body>
</html>