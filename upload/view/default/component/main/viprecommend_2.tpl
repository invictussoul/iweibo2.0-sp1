<!--{if $component.contents}-->
<h2 class="moduletitle"><div class="fleft"><strong><!--{$component.title}--></strong></div></h2>
<ul class="topiclist">
    <!--{foreach key=account item=item from=$component.contents}-->
    <li>
        <a title='<!--{$item.nick|escape:"html"}-->(@<!--{$item.name|escape:"html"}-->)' href="/u/<!--{$item.name|iurlencode}-->" class="fleft">
        <img title='<!--{$item.nick|escape:"html"}-->(@<!--{$item.name|escape:"html"}-->)' width="100" src="<!--{if $item.head}--><!--{$item.head}-->/100<!--{else}-->http://mat1.gtimg.com/www/mb/images/head_100.jpg<!--{/if}-->" /></a>
        <div class="fright">
            <h3><a title='<!--{$item.nick|escape:"html"}-->(@<!--{$item.name|escape:"html"}-->)' href="/u/<!--{$item.name|iurlencode}-->"><!--{$item.nick|escape:"html"}--></a><!--{if $item.is_auth}--><em class="icon_vip"></em><!--{/if}--></h3>
            <p title='<!--{$item.description|escape:"html"}-->'><!--{$item.description|itruncate:60:"..."}--></p>
            <!--{if $item.name != $username}-->
            <div><a data-key="1" data-group="1" title='收听' data-name='<!--{$item.name|escape:"html"}-->' href="javascript:void(0);" data-type="<!--{if $item.isidol}-->0<!--{else}-->1<!--{/if}-->" data-styleid="0" class="iwbFollowControl followbtn"></a></div>
            <!--{/if}-->
        </div>
    </li>
    <!--{/foreach}-->
</ul>
<!--{/if}-->