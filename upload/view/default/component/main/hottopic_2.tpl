<!--{if $component.contents}-->
<h2 class="moduletitle"><strong class="fleft"><!--{$component.title|escape:"html"}--></strong></h2>
    <ul class="toplisttxt">
        <!--{foreach key=key item=item from=$component.contents}-->
        <li><a title='<!--{$item.name|escape:"html"}-->' href="/topic/show/k/<!--{$item.name|iurlencode}-->" target="_blank"><!--{$item.name|escape:"html"|itruncate:30:"..."}--></a></li>
        <!--{/foreach}-->
    </ul>
<!--{/if}-->