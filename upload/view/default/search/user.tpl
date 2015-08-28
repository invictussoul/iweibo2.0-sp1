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
            <li class="tab active"><strong>用户</strong></li>
            <li class="tab"><a href="/search/t<!--{$data.addkey}-->">广播</a></li>
            <li class="tab"><a href="/search/tag<!--{$data.addkey}-->">标签</a></li>
        </ul>
        <div class="fright"></div>
        </div>
        <!--{if empty($data.unum)}-->
        <div class="norecord">没有找到<span class="cKeyword"></span>相关的用户</div>
        <div class="topicform">
        <h4>你可以：</h4>
        <ul>
        <li>• 换一个相近的搜索词重新搜索</li>
        <li>• 去掉原搜索词中无意义的词，如“的”、“呢”等</li>
        </ul>
        </div>
        <!--{else}-->
        <div class="moduletitle4"><strong class="fleft">用户<!--{if $data.unum}--><!--{$data.unum}--><!--{else}-->0<!--{/if}-->位</strong></div>
        <ul class="userlist4">
        <!--{foreach from=$data.u item=it}-->
        <li>
        <div class="fleft"><a href="/u/<!--{$it.name}-->" title="<!--{$it.nick}-->(@<!--{$it.name}-->)"><img src="<!--{$it.head}-->" onerror="this.src=iwbResourceRoot+'resource/images/default_head_50.jpg'"></a></div>
        <div class="fright">
            <!--{if $it.name!=$username}-->
            <!--{if $it.isidol}-->
            <a data-name="<!--{$it.name}-->" data-type="0" data-styleid="0" href="javascript:void(0);"  class="iwbFollowControl followbtn unfollowbtn" title="取消收听"></a>
            <!--{else}-->
            <a data-name="<!--{$it.name}-->" data-type="1" data-styleid="0" href="javascript:void(0);" class="iwbFollowControl followbtn" title="收听用户"></a>
            <!--{/if}-->
            <!--{/if}-->
            <h6> <a href="/u/<!--{$it.name}-->" title="<!--{$it.nick}-->(@<!--{$it.name}-->)"><!--{if $it.nick_light}--><!--{$it.nick_light}--><!--{else}--><!--{$it.nick}--><!--{/if}--><!--{if $it.is_auth}--><img src="/resource/images/vip.gif"><!--{/if}--></a><span>(@<!--{if $it.name_light}--><!--{$it.name_light}--><!--{else}--><!--{$it.name}--><!--{/if}-->)</span> </h6>
            <div>听众<a href="/friend/following/uname/<!--{$it.name}-->"><!--{$it.idolnum}--></a>人    收听<a href="/friend/follower/uname/<!--{$it.name}-->"><!--{$it.fansnum}--></a>人</div>
        </div>
        </li>
        <!--{/foreach}-->
        </ul>
        <!--{include file="common/pagerwrapper3.tpl"}-->
        <!--{/if}-->
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
<script src="/resource/js/searchU.js"></script>
</body>
</html>
