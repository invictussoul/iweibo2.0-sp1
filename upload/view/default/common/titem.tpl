<!--{if $msglist}-->
<ul class="tmain" id="tmain">
<!--{include file="common/teach.tpl"}-->
</ul>
<!--{else}-->
<ul class="tmain" id="tmain">
</ul>
<p class="<!--{if $active=='at'}-->noatme<!--{elseif $active=='favor'}-->nocollection<!--{else}-->norecord<!--{/if}-->" id="tmainnorecord">
暂无内容
<p>
<!--{/if}-->