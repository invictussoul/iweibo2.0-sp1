<!doctype html>
<html>
<head>
<title><!--{$data.title}--> - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
<!--{include file="common/style.tpl"}-->
<script>var iwbInstantTimeline = true;</script>
</head>
<body>
<!--{include file="common/header.tpl"}-->
<div class="wrapper content">
    <div class="fleft contentleft">
        <div class="topicheader">
            <div class="topicinfo">
            <!--{if $data.introduction}-->
                <img class="fleft" src="<!--{$data.head}-->"/>
                <div class="fright">
                    <h1><!--{$data.title}--></h1>
                    <p class="gray"><!--{$data.introduction}--></p>
                </div>
            <!--{else}-->
                    <h1 class="fleft"><!--{$data.title}--></h1>
            <!--{/if}-->
            </div>
            <div class="nobg">
                <!--{include file="common/sendbox.tpl"}-->
            </div>
        </div>
        <div class="moduletitle">
            <strong class="fleft">一共约<!--{$data.count}-->条广播</strong>
        </div>
        <div class="tcontainer"><!--{include file="common/tbody.tpl"}--></div>
        <!--{include file="common/pagerwrapper3.tpl"}-->
    </div>
    <div class="fright contentright">
            <!--{include file="common/profile.tpl"}-->
            <div class="rightsp"></div>
            <!--{include file="common/menus.tpl"}-->
            <div class="rightsp"></div>
    </div>
</div>
<!--{include file="common/footcontrol.tpl"}-->
<!--{include file="common/footer.tpl"}-->
<script src="/resource/js/topic.js"></script>
</body>
</html>