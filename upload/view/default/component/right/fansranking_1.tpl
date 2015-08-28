<!--{if $component.contents}-->
<div class="toggle" title='<!--{$component.title|escape:"html"}-->'><!--{$component.title|escape:"html"|itruncate:33}--></div>
<ul class="userlist5">
    <!--{counter start=0 skip=1 print=false assign=i}-->
    <!--{foreach key=key item=item from=$component.contents}-->
    <!--{counter}-->
    <!--{if $i <= 3}-->
    <li><em class="icon_num_orange fleft"><!--{$i}--></em><a title='<!--{$item.nick|escape:"html"}-->(@<!--{$item.name|escape:"html"}-->)' href="/u/<!--{$item.name|iurlencode}-->"class="fleft username"><!--{$item.nick|escape:"html"}--></a><cite class="fright fansnum"><!--{$item.fansnum|escape:"html"}--></cite></li>
    <!--{else}-->
    <li><em class="icon_num_gray fleft"><!--{$i}--></em><a title='<!--{$item.nick|escape:"html"}-->(@<!--{$item.name|escape:"html"}-->)' href="/u/<!--{$item.name|iurlencode}-->" class="fleft username"><!--{$item.nick|escape:"html"}--></a><cite class="fright fansnum"><!--{$item.fansnum|escape:"html"}--></cite></li>
    <!--{/if}-->
    <!--{/foreach}-->
</ul>
<!--{/if}-->