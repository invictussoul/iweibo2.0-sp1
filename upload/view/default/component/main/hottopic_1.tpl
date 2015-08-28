<!--{if $component.contents}-->
<h2 class="moduletitle"><strong class="fleft"><!--{$component.title|escape:"html"}--></strong></h2>
<ul class="topiclist">
    <!--{foreach key=key item=item from=$component.contents}-->
    <li>
        <a href="/topic/show/k/<!--{$item.name|iurlencode}-->" title='<!--{$item.name|escape:"html"}-->' class="fleft"><img title='<!--{$item.name|escape:"html"}-->' width="100" height="100" src="<!--{$item.picture2}-->" /></a>
        <div class="fright">
            <h3><a title='<!--{$item.name|escape:"html"}-->' href="/topic/show/k/<!--{$item.name|iurlencode}-->"><!--{$item.name|escape:"html"}--></a><!--{if $item.is_auth}--><em class="icon_vip"></em><!--{/if}-->   </h3>
            <p title='<!--{$item.description|escape:"html"}-->'><!--{$item.description|itruncate:150:"..."}--></p>
        </div>
    </li>
    <!--{/foreach}-->
</ul>
<!--{/if}-->