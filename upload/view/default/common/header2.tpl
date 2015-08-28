<!--{TO->cfg key="site_url" group="basic" default="/" assign="site_url"}-->
<div class="usernav2">
    <div class="wrapper">
        <span class="fleft"><a href="<!--{$site_url}-->"  title="<!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}-->"><img src="<!--{TO->cfg key="site_logo" group="basic" default="/resource/images/iweibo.png"}-->" resource="<!--{$_resource}-->"/></a></span>
        <span class="fright">
            <a href="/"><!--{$user.nick}--></a>
            <a href="/setting">设置</a>
            <a href="javascript:void(0);" id="changeskin"><em class="icon_facesetting"></em> <label>换肤</label></a>
            <!--顶部导航 start-->
            <!--{foreach from=$topNav item=nav}-->
            <a href="<!--{$nav.link}-->" <!--{if $nav.newwindow == 1}-->target="_blank"<!--{/if}-->><!--{$nav.name}--></a>
            <!--{/foreach}-->
            <!--顶部导航 end-->
            <!--{if $pluginList}-->
            <span class="vmenu"><a href="javascript:void(0);" id="iwbPlugin">插件</a>
                <ul id="iwbPluginList" class="iwbAutoCloseLayer"><em class="icon_angle"></em>
                	<!--{foreach from=$pluginList item=plugin}-->
                    <li><a href="/plugin/<!--{$plugin.foldername|lower}-->" target="_blank"><!--{$plugin.name}--></a></li>
                    <!--{/foreach}-->
                </ul>
            </span>
            <!--{/if}-->
            <!--{if $hasPermission}-->
            <a href="/admin" target="_blank">管理中心</a>
            <!--{/if}-->
            <a href="/login/logout">退出</a>
        </span>
    </div>
</div>
<div class="wrapper banner"><img src="/resource/images/banner.jpg"/></div>
<div class="wrapper navbar">
    <div class="nav fleft">
        <!--主导航 start-->
    <!--{foreach from=$mainNav item=nav}-->
        <a href="<!--{$nav.link}-->" <!--{if $active==$nav.action}-->class="active"<!--{/if}--> <!--{if $nav.newwindow == 1}-->target="_blank"<!--{/if}--> hidefocus><!--{$nav.name}--><em></em></a>
    <!--{/foreach}-->
        <!--主导航 end-->
    </div>
    <div class="searchbar fright">
        <div class="fleft"><strong>关键字</strong>
         <!--{foreach key=key item=word from=$hotWords}-->
            <a href="/search/all/k/<!--{$word|iurlencode}-->"><!--{$word}--></a>
         <!--{/foreach}-->
        </div>
        <form name="searchForm" class="search fleft" method="post" action="/search/<!--{if isset($headsearch)}--><!--{$headsearch}--><!--{else}-->all<!--{/if}-->">
            <input type="hidden" name="m" value="searchall"/>
            <input type="text"  class="searchkey" maxlength="50" name="k" placeholder="搜名字/广播/标签" value="<!--{if isset($searchkey)}--><!--{$searchkey}--><!--{/if}-->" />
            <input type="submit" class="searchbtn" value="搜索"/>
        </form>
    </div>
</div>