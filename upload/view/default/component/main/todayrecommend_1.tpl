<!--{if $component.contents}-->
<ul class="slidertext">
    <!--{foreach key=key item=item from=$component.contents}-->
    <li><em class="icon_message"></em><strong><!--{$component.title|escape:"html"}-->：</strong><!--{$item}--></li>
    <!--{/foreach}-->
</ul>
<!--{/if}-->