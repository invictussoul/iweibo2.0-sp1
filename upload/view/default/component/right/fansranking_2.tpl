<!--{if $component.contents}-->
<div class="toggle" title='<!--{$component.title|escape:"html"}-->'><!--{$component.title|escape:"html"|itruncate:33}--></div>
<ul class="userlist5">
    <!--{counter start=0 skip=1 print=false assign=i}-->
    <!--{foreach key=key item=item from=$component.contents}-->
    <!--{counter}-->
    <!--{if $i <= 3}-->
    <li class="active">
        <a title='<!--{$item.nick|escape:"html"}-->(@<!--{$item.name|escape:"html"}-->)' href="/u/<!--{$item.name|iurlencode}-->" class="fleft head"><img title='<!--{$item.nick|escape:"html"}-->(@<!--{$item.name|escape:"html"}-->)' width="40" height="40" src="<!--{$item.head}-->/40"/><em class="icon_num_orange"><!--{$i}--></em></a>
        <p class="fleft"><a title='<!--{$item.nick|escape:"html"}-->(@<!--{$item.name|escape:"html"}-->)' href="/u/<!--{$item.name|iurlencode}-->" class="username"><!--{$item.nick|escape:"html"}--></a><!--{$item.fansnum|escape:"html"}--></p>
        <!--{if $item.name != $username}--><a data-group="1" title='收听' data-name='<!--{$item.name|escape:"html"}-->' href="javascript:void(0);" data-type="<!--{if $item.isidol}-->0<!--{else}-->1<!--{/if}-->" data-styleid="1" class="iwbFollowControl follow"></a><!--{/if}-->
    </li>
    <!--{else}-->
    <li><em class="icon_num_gray fleft"><!--{$i}--></em><a title='<!--{$item.nick|escape:"html"}-->(@<!--{$item.name|escape:"html"}-->)' href="/u/<!--{$item.name|iurlencode}-->"class="fleft username"><!--{$item.nick|escape:"html"}--></a><cite class="fright fansnum"><!--{$item.fansnum|escape:"html"}--></cite></li>
    <!--{/if}-->
<!--{/foreach}-->
</ul>
<!--{/if}-->