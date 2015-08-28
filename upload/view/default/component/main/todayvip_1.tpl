<!--{if $component.contents}-->
<div class="todaystar">
    <h2><!--{$component.title|escape:"html"}--></h2>
    <!--{foreach key=key item=item from=$component.contents}-->
    <div class="fleft">
        <a title='<!--{$item.nick|escape:"html"}-->(@<!--{$item.name|escape:"html"}-->)' href="/u/<!--{$item.name|iurlencode}-->">
        <img title='<!--{$item.nick|escape:"html"}-->(@<!--{$item.name|escape:"html"}-->)' width="120" height="120" src="<!--{if $item.head}--><!--{$item.head}-->/120<!--{else}-->http://mat1.gtimg.com/www/mb/images/head_120.jpg<!--{/if}-->" /></a>
    </div>
    <div class="fright">
    <h3><!--{$item.nick|escape:"html"}--><!--{if $item.is_auth}--><em class="icon_vip"></em><!--{/if}--></h3>
    <p title='<!--{$item.description|escape:"html"}-->'><!--{$item.description|itruncate:333:"..."}--></p>
    <!--{if $item.name != $username}-->
    <div><a data-key="1" data-group="1" title='收听' data-name='<!--{$item.name|escape:"html"}-->' href="javascript:void(0);" data-type="1" data-styleid="0" class="iwbFollowControl followbtn"></a></div>
    <!--{/if}-->
    <!--{/foreach}-->
    </div>
</div>
<!--{/if}-->
