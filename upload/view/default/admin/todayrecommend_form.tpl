
<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>每日推荐</h3>
        <ul class="tab1">
            <li<!--{if 'index' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/todayrecommend/index"><span>管理</span></a></li>
            <li<!--{if 'add' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/todayrecommend/add"><span>添加</span></a></li>
        </ul>
    </div>
</div>
<form name="cpform" method="post" action="/admin/todayrecommend/<!--{$_actionName}-->" id="cpform" >
    <!--{if 'edit' == $_actionName}--><input type="hidden" name="id" value="<!--{$todayRecommend.id}-->" /><!--{/if}-->
    <table class="tb tb2 ">
        <tr><th colspan="15" class="partition"><!--{if 'add' == $_actionName}-->添加<!--{else}-->编辑<!--{/if}--></th></tr>
        <tr><td colspan="2" class="td27">内容</td></tr>
        <tr class="noborder"><td class="vtop rowform">
                <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="content" cols="50" class="tarea"><!--{$todayRecommend.content}--></textarea></td><td class="vtop tips2">请输入条目内容,支持html</td></tr>
        <tr><td colspan="15"><div class="fixsel"><input type="submit" class="btn" name="submit" value="提交" /></div></td></tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->