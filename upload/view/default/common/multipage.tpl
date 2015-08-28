<!--{if $multipage}-->
<div class="pages">
    <!--{foreach from=$multipage item=page}-->
        <!--{if $page.1}-->
            <a <!--{if $page.2}-->class="<!--{$page.2}-->"<!--{/if}--> href="<!--{$page.1}-->"><!--{$page.0}--></a>
        <!--{else}-->
            <strong><!--{$page.0}--></strong>
        <!--{/if}-->
    <!--{/foreach}-->
</div>
<!--{/if}-->