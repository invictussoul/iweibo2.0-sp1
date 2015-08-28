<!--{include file="admin/header.tpl"}-->
<div class="infobox">
    <h3 class="float_ctrl"><em>提示信息</em></h3>
    <div class="postbox">
    <p><!--{$msg}--><br /><br /></p>
        <!--{if $button}-->
            <p class="marginbot"><input type="button" value="点击返回" onclick="window.location.href = '<!--{$seogourl}-->'" /></p>
        <!--{else}-->
            <meta http-equiv="refresh" content="<!--{$time}-->; url=<!--{$seogourl}-->" />
            <p class="marginbot"><a href="<!--{$gourl}-->">点击返回</a></p>
        <!--{/if}-->
    </div>
</div>
<!--{include file="admin/footer.tpl"}-->