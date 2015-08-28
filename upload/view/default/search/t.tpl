<!doctype html>
<html>
<head>
<title><!--{$data.title}--> - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
<!--{include file="common/style.tpl"}-->
</head>
<body>
<!--{include file="common/header.tpl"}-->
<div class="wrapper content">
    <div class="fleft contentleft">
        <!--主栏组件-->
        <!--{$mainComponent}-->
        <div class="tabbar">
            <ul class="tabs">
                <li class="tab"><a href="/search/all<!--{$data.addkey}-->">综合</a></li>
                <li class="tab"><a href="/search/user<!--{$data.addkey}-->">用户</a></li>
                <li class="tab active"><strong>广播</strong></li>
                <li class="tab"><a href="/search/tag<!--{$data.addkey}-->">标签</a></li>
            </ul>
            <div class="fright"></div>
        </div>
        <div class="nobg">
        <!--{include file="common/sendbox.tpl"}-->
        </div>
        <div class="searchtit">
        <h2>广播<!--{if $data.tnum}--><!--{$data.tnum}--><!--{else}-->0<!--{/if}-->条</h2>
        <!--<div><span class="active">全部<em class="icon_angledown"></em></span> <span class="gray">|</span> <a href="#">我收听的人</a></div>-->
        </div>
        <!--{if empty($data.tnum)}-->
        <div class="norecord">没有找到<span class="cKeyword"></span>相关的广播</div>
        <div class="topicform">
        <h4>你可以：</h4>
        <ul>
        <li>• 换一个相近的搜索词重新搜索</li>
        <li>• 去掉原搜索词中无意义的词，如“的”、“呢”等</li>
        </ul>
        </div>
        <!--{else}-->
        <div class="tcontainer">
        <!--{include file="common/tbody.tpl"}-->
        </div>
        <!--{/if}-->
        <!--{include file="common/pagerwrapper3.tpl"}-->
    </div>
    <div class="fright contentright">
            <!--{include file="common/profile.tpl"}-->
            <div class="rightsp" ></div>
            <!--{include file="common/menus.tpl"}-->
            <!--右栏组件-->
            <!--{$rightComponent}-->
            <div class="rightsp"></div>
    </div>
</div>
<!--{include file="common/footcontrol.tpl"}-->
<!--{include file="common/footer.tpl"}-->
    <script src="/resource/js/searchT.js"></script>
</body>
</html>
