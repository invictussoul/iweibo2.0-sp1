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
        <li class="tab active"><strong>综合</strong></li>
        <li class="tab"><a href="/search/user<!--{$data.addkey}-->">用户</a></li>
        <li class="tab"><a href="/search/t<!--{$data.addkey}-->">广播</a></li>
        <li class="tab"><a href="/search/tag<!--{$data.addkey}-->">标签</a></li>
    </ul>
    <div class="fright">
    </div>
</div>
<!--{if empty($data.unum)}-->
<div class="norecord">没有找到<span class="cKeyword"></span>相关的用户</div>
<!--{else}-->
<div class="moduletitle4"><strong class="fleft">用户<!--{if $data.unum}--><!--{$data.unum}--><!--{else}-->0<!--{/if}-->位</strong></div>
    <ul class="userlist2 userlist6">
    <!--{foreach from=$data.u item=it}-->
    <li>
      <div class="fleft"><a href="/u/<!--{$it.name}-->" title="<!--{$it.nick}-->(@<!--{$it.name}-->)"><img src="<!--{$it.head}-->" onerror="this.src=iwbResourceRoot+'resource/images/default_head_50.jpg'" /></a>
      <!--{if $it.name!=$user.name}--><a href="javascript:void(0);" data-name="<!--{$it.name}-->" data-type="<!--{if $it.isidol}-->0<!--{else}-->1<!--{/if}-->" data-styleid="1" class="iwbFollowControl follow<!--{if $it.isidol}--> unfollow<!--{/if}-->"></a><!--{/if}--></div>
      <div class="fright">
        <h6><a href="/u/<!--{$it.name}-->" title="<!--{$it.nick}-->(@<!--{$it.name}-->)"><!--{if $it.nick_light}--><!--{$it.nick_light}--><!--{else}--><!--{$it.nick}--><!--{/if}--><!--{if $it.is_auth}--><img src="/resource/images/vip.gif"><!--{/if}--></a></h6><p>@<!--{if $it.name_light}--><!--{$it.name_light}--><!--{else}--><!--{$it.name}--><!--{/if}--><br/>听众<a href="/friend/follower/uname/<!--{$it.name}-->"><!--{$it.fansnum}--></a>人</p></div>
    </li>
    <!--{/foreach}-->
    </ul>
<div class="tcontainer"><a href="/search/user<!--{$data.addkey}-->">查看全部&gt;&gt;</a></div>
<!--{/if}-->
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
<div class="searchtit">
    <h2>广播<!--{if $data.tnum}--><!--{$data.tnum}--><!--{else}-->0<!--{/if}-->条</h2>
</div>
<div class="tcontainer">
    <!--{include file="common/tbody.tpl"}-->
</div>
<!--{include file="common/pagerwrapper3.tpl"}-->
<!--{/if}-->
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
    <script src="/resource/js/searchAll.js"></script>
    </body>
</html>
