<!--{foreach key="key" item="blog" from=$blogs}-->
<li><a href="/u/<!--{$blog.account}-->" class="uhead"><img src="<!--{$blog.user_head}-->"/></a>
    <div class="tbody">
        <a href="/u/<!--{$blog.account}-->"><!--{$blog.nickname}--></a><!--{ if $blog.isauth }--> <span class="icon_vip"></span><!--{/if}-->：<!--{$blog.content}-->
        <!--{if $blog.picture}-->
            <a href="<!--{$blog.picture}-->/2000" target="_blank"><img src="<!--{$blog.picture}-->/320" /></a>
        <!--{/if}-->
    </div>
</li>
<!--{/foreach}-->