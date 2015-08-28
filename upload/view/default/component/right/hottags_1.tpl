<!--{if $component.contents}-->
<div class="textlisttit" title='<!--{$component.title|escape:"html"}-->'><!--{$component.title|escape:"html"|itruncate:33}--></div>
<div class="tagsbox">
<em class="icon_angle"></em>
    <!--{foreach key=key item=item from=$component.contents name=thisloop}--><a title='<!--{$item.tagname|escape:"html"}-->' style='color:#<!--{$item.color}-->;' href="/search/tag/k/<!--{$item.tagname|iurlencode}-->"><!--{$item.tagname|escape:"html"}--></a><!--{if !$smarty.foreach.thisloop.last}--> | <!--{/if}--><!--{/foreach}-->
</div>
<!--{/if}-->