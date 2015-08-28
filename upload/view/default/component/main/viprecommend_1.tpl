<!--{if $component.contents}-->
<h2 class="moduletitle"><div class="fleft"><strong><!--{$component.title|escape:"html"}--></strong></div></h2>
<ul class="userlist userlist7">
<!--{foreach key=account item=item from=$component.contents}-->
    <li>
        <p><a title='<!--{$item.nick|escape:"html"}-->(@<!--{$item.name|escape:"html"}-->)' href="/u/<!--{$account|iurlencode}-->" target="_blank"><img title='<!--{$item.nick|escape:"html"}-->(@<!--{$item.name|escape:"html"}-->)' width="50" src="<!--{if $item.head}--><!--{$item.head}-->/50<!--{else}-->http://mat1.gtimg.com/www/mb/images/head_50.jpg<!--{/if}-->" /></a></p>
        <h3><a title='<!--{$item.nick|escape:"html"}-->(@<!--{$item.name|escape:"html"}-->)' href="/u/<!--{$account|iurlencode}-->"><!--{$item.nick|escape:"html"}--></a></h3>
        <!--{if $item.name != $username}-->
        <div><a data-key="1" data-group="1" title='收听' data-name='<!--{$item.name|escape:"html"}-->' href="javascript:void(0);" data-type="<!--{if $item.isidol}-->0<!--{else}-->1<!--{/if}-->" data-styleid="1" class="iwbFollowControl follow"></a></div>
        <!--{/if}-->
    </li>
<!--{/foreach}-->
</ul>
<!--{/if}-->