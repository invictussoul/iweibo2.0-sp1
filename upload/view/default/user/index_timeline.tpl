<!doctype html>
<html>
    <head>
    <title>我的主页 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
    <script>
        var iwbTimelineMoreType = 1;
        var iwbInstantTimeline = true; // 转播，对话,发表框消息直接写入timeline
        var iwbTimelineNotice = true;
    </script>
    </head>
    <body>
    <!--{include file="common/header.tpl"}-->
    <div class="wrapper content">
        <div class="contentleft fleft">
            <!--{include file="common/sendbox.tpl"}-->
            <!--{$mainComponent}-->
            <div class="moduletitle">
                <strong class="fleft">全部广播</strong>
                <span class="fright">
                    <input type="checkbox" class="timelinefilter" data-filter="/utype/1" <!--{if $utype==1}-->checked<!--{/if}--> /><label>原创</label>
                    <input type="checkbox" class="timelinefilter" data-filter="/ctype/4" <!--{if $ctype==4}-->checked<!--{/if}--> /><label>图片</label>
                </span>
            </div>
            <div class="tcontainer">
                <!--{include file="common/tbody.tpl"}-->
            </div>
            <!--{if $hasnext===0}-->
                <!--{include file="common/pagerwrapper.tpl"}-->
            <!--{/if}-->
        </div>
        <div class="contentright fright">
            <!--{include file="common/profile.tpl"}-->
            <div class="rightsp" ></div>
            <!--{include file="common/menus.tpl"}-->
            <!--{$rightComponent}-->
            <div class="adv"></div>
        </div>
    </div>
    <!--{include file="common/footcontrol.tpl"}-->
    <!--{include file="common/footer.tpl"}-->
    <script src="/resource/js/index.js"></script>
    <!--{$ucsynlogin}-->
    <!--{if $showmsg}-->
    <script type="text/javascript">
    	$(function(){
    		IWB_DIALOG.modaltipbox('error', '<!--{$showmsg}-->');
    	});
    </script>
    <!--{/if}-->
    </body>
</html>