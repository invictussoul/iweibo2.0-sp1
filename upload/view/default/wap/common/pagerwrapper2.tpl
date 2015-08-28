<!--{ if isset($pageinfo) && ( !empty($pageinfo.fronturl) || !empty($pageinfo.nexturl) )  }-->
<p>
    <!--{ if  !empty($pageinfo.fronturl) }-->
    <a  href="<!--{  $pageinfo.fronturl }-->">&lt;&lt;上一页</a>
    <!--{/if}-->
    <!--{ if  !empty($pageinfo.nexturl) }-->
    <a  href="<!--{  $pageinfo.nexturl }-->">下一页&gt;&gt;</a>
    <!--{/if}-->
</p>
<!--{/if}-->