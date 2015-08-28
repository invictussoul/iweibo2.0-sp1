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
<form action="/search/<!--{if isset($headsearch)}--><!--{$headsearch}--><!--{else}-->all<!--{/if}-->" method="post" class="topicform" name="searchForm">
<h2></h2>
<div>
<input type="hidden" value="searchall" name="m">
<input type="text" value="<!--{if isset($searchkey)}--><!--{$searchkey}--><!--{/if}-->" class="txt" name="k">
<input type="submit" class="btn" value="确定" name="submit">
</div>
</form>
<br/>
<div class="topicform">
没有找到<span class="cKeyword"></span>相关的内容
<h4>你可以：</h4>
<ul>
<li>• 换一个相近的搜索词重新搜索</li>
<li>• 去掉原搜索词中无意义的词，如“的”、“呢”等</li>
</ul>
</div>
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
