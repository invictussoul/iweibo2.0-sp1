<!doctype html>
<html>
    <head>
    <title>微访谈 - <!--{$tiview.tname}--> - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <script> var iwbAddUrl = "tiview/add/id/<!--{$tiview.id}-->";</script>
    <!--{include file="common/style.tpl"}-->
    <!--{if $tiview.style.linkcolor}-->
    <style> a{color:<!--{$tiview.style.linkcolor}-->;}</style>
    <!--{/if}-->
    </head>
    <body>
    <!--{include file="common/header.tpl"}-->
    <div class="wrapper content">
        <!--{if $tiview.style.outward}-->
        <div><img src="<!--{$tiview.style.outward}-->" width="800" height="100"/></div>
        <!--{/if}-->
        <div class="contentleft fleft nobg">
            <!--{if $tiview.statusText != '已结束'}-->
            <div class="nobg"><!--{include file="common/sendbox.tpl"}--></div>
            <!--{/if}-->
            <div class="moduletitle">
                <strong class="fleft">访谈内容</strong>
                <span class="fleft">(共有<!--{$tps.total}-->个问题 <!--{$tps.reply}-->个回复)</span>
            </div>
            <div id="interviewlist">
            <!--{include file="user/tiview_tbody.tpl"}-->
            </div>
            <!--{include file="common/multipage1.tpl"}-->
            </div>
        <div class="contentright fright"><br/>
        <div class="textlisttit" title="热门话题">&#10077;<!--{$tiview.tname}-->&#10078;</div>
        <div class="textlisttit" title="访谈简介">访谈简介&nbsp;
        <!--{if $tiview.statusText == '进行中'}-->
            <em class="icon_living"></em>
        <!--{elseif $tiview.statusText == '未开始'}-->
            [未开始]
        <!--{else}-->
            <em class="icon_lived"></em>
        <!--{/if}-->
        </div>
        <div class="livedes">
        <em class="icon_angle"></em>
        <strong>访谈时间：</strong>
        <p class="gray"><!--{$tiview.sdate|idate:"m月d日 H:i"}--> 至 <!--{$tiview.edate|idate:"m月d日 H:i"}--></p>
        <strong>访谈内容：</strong>
        <p class="gray"><!--{$tiview.desc}--></p>
        <center><a data-text="#微访谈#<!--{$tiview.desc}-->" href="javascript:void(0);" class="iwbAddBtn commendbtn"></a></center>
        </div>
        <div class="toggle" title="主持人">主持人</div>
        <ul class="userlist">
            <!--{foreach name=user item=user key=i from=$join.0}-->
            <li>
            <p><a href="/u/<!--{$user.name}-->" target="_blank"><img src="<!--{$user.head}-->"></a></p>
            <h3><a href="/u/<!--{$user.name}-->" target="_blank" title="<!--{$user.nick}-->(@<!--{$user.name}-->)"><!--{if $user.nick != ''}--><!--{$user.nick}--><!--{else}--><!--{$user.name}--><!--{/if}--></a></h3>
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
        <div class="toggle" title="嘉宾">嘉宾</div>
        <ul class="userlist">
            <!--{foreach name=user item=user key=i from=$join.1}-->
            <li>
            <p><a href="/u/<!--{$user.name}-->" target="_blank"><img src="<!--{$user.head}-->"></a></p>
            <h3><a href="/u/<!--{$user.name}-->" target="_blank" title="<!--{$user.nick}-->(@<!--{$user.name}-->)"><!--{if $user.nick != ''}--><!--{$user.nick}--><!--{else}--><!--{$user.name}--><!--{/if}--></a></h3>
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
    <!--{include file="common/footer.tpl"}-->
    <script>
        $(document).ready(function(){
        <!--{if $tiview.statusText != '已结束'}-->
            var topic = '#<!--{$tiview.tname}-->#';
            var titlebox = $('<div>我有个问题向 <!--{$slt}--> 提问</div>');
            var selector = titlebox.find(".selecttit");
            var ppllist = titlebox.find("ul");
            var ppls = titlebox.find(".altto");
            selector.hover(function () {
                ppllist.show();
            });
            ppllist.hover(function () {},function () {
                $(this).hide();
            });
            ppls.click(function () {
                $("#msgTxt").val(topic+'@'+$(this).attr('data-name')+' ');
                ppllist.hide();
            });
            $("#sendTweet").attr('action',window.iwbRoot + 'tiview/add/id/<!--{$tiview.id}-->');
            $(".sendboxtitle").append(titlebox);
            $("#msgTxt").bind("keydown",function(){
                var val = $(this).val();
                if(!(/^\#.*\#/).test(val)){
                    $(this).val('#<!--{$tiview.tname}-->#' + val);
                }
            });
        <!--{/if}-->
        <!--{if $tiview.style.background}-->
            $("BODY").css({"background":' url(<!--{$tiview.style.background}-->)<!--{if !$tiview.style.repeat}--> no-repeat<!--{/if}-->',"filter":'null'});
        <!--{/if}-->
        <!--{if $tiview.style.bgcolor}-->
            $("BODY").css("background-color",'<!--{$tiview.style.bgcolor}-->');
        <!--{/if}-->
         });
    </script>
    <!--{if $tiview.statusText != '已结束'}-->
    <script src="/resource/js/interviewOn.js"></script>
    <!--{else}-->
    <script src="/resource/js/interviewOff.js"></script>
    <!--{/if}-->
    </body>
</html>