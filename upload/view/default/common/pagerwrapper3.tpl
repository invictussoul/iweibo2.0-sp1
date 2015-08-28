<!--{ if isset($pageinfo) && ( !empty($pageinfo.fronturl) || !empty($pageinfo.nexturl) )  }-->
<div class="pagerwrapper">
    <div class="pagebar">
        <!--{ if  !empty($pageinfo.fronturl) }-->
        <span class="fleft"><a class="previouspage" href="<!--{  $pageinfo.fronturl }-->">&lt;&lt;上一页</a></span>
        <!--{/if}-->
        <!--{ if  !empty($pageinfo.nexturl) }-->
        <span class="fright"><a class="nextpage" href="<!--{  $pageinfo.nexturl }-->">下一页&gt;&gt;</a></span>
        <!--{/if}-->
    </div>
</div>
<!--{/if}-->