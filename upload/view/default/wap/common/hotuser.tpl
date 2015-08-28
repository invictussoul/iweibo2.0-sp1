<!--{foreach name=foo key=key item=hot from=$hotuser}-->
    <!--{if $smarty.foreach.foo.index<4}-->
        <a href="/wap/u/<!--{$hot.name}-->"><!--{$hot.nick}--></a>
        <!--{if $hot.isidol}-->
        [<a href="/wap/friend/follow/type/0/name/<!--{$hot.name}-->">取消收听</a>]
        <!--{else}-->
        [<a href="/wap/friend/follow/type/1/name/<!--{$hot.name}-->">收听</a>]
        <!--{/if}-->
    <!--{/if}-->
<!--{/foreach}-->