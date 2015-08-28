<!--{if $component.contents}-->
<div class="toggle" title='<!--{$component.title|escape:"html"}-->'><!--{$component.title|escape:"html"|itruncate:33}--></div>
    <ul class="userlist2">
    <!--{foreach key=account item=item from=$component.contents name=thisloop}-->
    <li <!--{if $smarty.foreach.thisloop.last}-->class="last"<!--{/if}-->>
        <div class="fleft">
            <a href="/u/<!--{$account|iurlencode}-->" title='<!--{$item.nick|escape:"html"}-->(@<!--{$account|escape:"html"}-->)'><img title='<!--{$item.nick|escape:"html"}-->(@<!--{$account|escape:"html"}-->)' width="50" height="50" src="<!--{if $item.head}--><!--{$item.head}-->/50<!--{else}-->http://mat1.gtimg.com/www/mb/images/head_50.jpg<!--{/if}-->" /></a>
            <!--{if $item.name != $username}--><a data-group="1" title='收听' data-name='<!--{$item.name|escape:"html"}-->' href="javascript:void(0);" data-type="<!--{if $item.isidol}-->0<!--{else}-->1<!--{/if}-->" data-styleid="1" class="iwbFollowControl follow"></a><!--{/if}-->
        </div>
        <div class="fright">
        <h6><a href="/u/<!--{$account|iurlencode}-->" title='<!--{$item.nick|escape:"html"}-->(@<!--{$account|escape:"html"}-->)'><!--{$item.nick|escape:"html"}--></a></h6><p title='<!--{$item.introduction|escape:"html"}-->'><!--{$item.introduction|itruncate:87:"..."}--></p></div>
    </li>
    <!--{/foreach}-->
    </ul>
<div class="rightsp"></div>
<!--{/if}-->