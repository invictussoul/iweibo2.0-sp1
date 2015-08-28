<ul class="u">
<!--{foreach from=$user item=it}-->
    <li><a href="/wap/u/<!--{$it.name}-->"><!--{$it.nick}--></a> (<!--{$it.name}-->)<!--{if $it.is_auth}--><img src="/resource/images/vip.gif"/><!--{/if}-->
    <br/><a href="/wap/friend/fans/uname/<!--{$it.name}-->">听众<!--{if $it.fansnum}--><!--{$it.fansnum}--><!--{else}-->0<!--{/if}-->人</a> &nbsp; <a href="/wap/friend/idol/uname/<!--{$it.name}-->">收听<!--{if $it.idolnum}--><!--{$it.idolnum}--><!--{else}-->0<!--{/if}-->人</a>
    <br/>[<!--{if $it.isidol}--><a href="/wap/friend/follow/type/0/name/<!--{$it.name}-->">取消收听</a><!--{else}--><a href="/wap/friend/follow/type/1/name/<!--{$it.name}-->">收听</a><!--{/if}-->] &nbsp; [<a href="/wap/box/add/toname/<!--{$it.name}-->">发私信</a>]
    </li><br/>
<!--{/foreach}-->
</ul>