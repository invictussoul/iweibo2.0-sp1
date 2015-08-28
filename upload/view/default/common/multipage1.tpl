<!--{if $multipage}-->
<div class="page">
    <!--{foreach name=mpg from=$multipage item=page}-->
        <!--{if $smarty.foreach.mpg.index > 0}-->
            <!--{if $page.1}-->
                <a <!--{if $page.2}-->class="<!--{$page.2}-->"<!--{/if}--> href="<!--{$page.1}-->"><!--{$page.0}--></a>
            <!--{else}-->
                <strong><!--{$page.0}--></strong>
            <!--{/if}-->
        <!--{/if}-->
    <!--{/foreach}-->
</div>
<!--{/if}-->