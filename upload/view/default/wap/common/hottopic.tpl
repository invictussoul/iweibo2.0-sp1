<p>热点：
<!--{foreach name=foo key=key item=hot from=$hotlist}-->
    <!--{if $smarty.foreach.foo.index<6}-->
        <a href="/wap/topic/show/k/<!--{$hot.name|iurlencode}-->"><!--{$hot.name}--></a>
    <!--{/if}-->
<!--{/foreach}-->
</p>