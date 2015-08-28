<!--{if $component.contents}-->
<div class="textlisttit" title='<!--{$component.title|escape:"html"}-->'><!--{$component.title|escape:"html"|itruncate:33}--></div>
    <ul class="textlist2">
        <em class="icon_angle"></em>
        <!--{foreach key=key item=item from=$component.contents name=thisloop}-->
        <li <!--{if $smarty.foreach.thisloop.last}-->class="last"<!--{/if}-->>
            <a title='<!--{$item.name|escape:"html"}-->' href="/topic/show/k/<!--{$item.name|iurlencode}-->" class="fleft"><img title='<!--{$item.name|escape:"html"}-->' width="50" height="50" src="<!--{$item.picture}-->" /></a>
            <p title='<!--{$item.description|escape:"html"}-->'><a title='<!--{$item.name|escape:"html"}-->' href="/topic/show/k/<!--{$item.name|iurlencode}-->"><!--{$item.name|escape:"html"}--></a><!--{$item.description|itruncate:45:"..."}--></p>
            <div><a href="/topic/show/k/<!--{$item.name|iurlencode}-->">详情&gt;&gt;</a></div>
        </li>
        <!--{/foreach}-->
    </ul>
<!--{/if}-->
