<!--{if $component.contents}-->
<div class="textlisttit" title='<!--{$component.title|escape:"html"}-->'><!--{$component.title|escape:"html"|itruncate:33}--></div>
<ul class="textlist"><em class="icon_angle"></em>
    <!--{foreach key=key item=item from=$component.contents}-->
    <li><a href="/topic/show/k/<!--{$item.name|iurlencode}-->" title='<!--{$item.name|escape:"html"}-->'><!--{$item.name|escape:"html"}--><span></span></a>
    <!--{if $item.description}--><p title='<!--{$item.description|escape:"html"}-->'><!--{$item.description|itruncate:72:"..."}--></p><!--{/if}-->
    </li>
    <!--{/foreach}-->
</ul>
<!--{/if}-->
