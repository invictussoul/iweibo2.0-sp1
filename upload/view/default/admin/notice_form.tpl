
<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>公告</h3>
        <ul class="tab1">
            <li<!--{if 'index' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/notice/index"><span>管理</span></a></li>
            <li<!--{if 'add' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/notice/add"><span>添加</span></a></li>
        </ul>
    </div>
</div>
<table class="tb tb2 " id="tips">
    <tr><th  class="partition">技巧提示</th></tr>
    <tr><td class="tipsblock"><ul id="tipslis"><li>请添加公告</li></ul></td></tr>
</table>
<script type="text/javascript" src="/resource/admin/js/calendar.js"></script>
<form name="cpform" method="post" action="/admin/notice/<!--{$_actionName}-->" id="cpform" >
    <!--{if 'edit' == $_actionName}--><input type="hidden" name="id" value="<!--{$notice.id}-->" /><!--{/if}-->
    <table class="tb tb2 ">
        <tr><th colspan="15" class="partition"><!--{if 'add' == $_actionName}-->添加<!--{else}-->编辑<!--{/if}--></th></tr>
        <tr><td colspan="2" class="td27">标题</td></tr>
        <tr class="noborder"><td class="vtop rowform">
                <input name="title" value="<!--{$notice.title}-->" type="text" class="txt" /></td><td class="vtop tips2"></td></tr>
        <tr><td colspan="2" class="td27">结束时间</td></tr>
        <tr class="noborder"><td class="vtop rowform">
                <input type="text" class="txt" name="endtime" value="<!--{$notice.endtime|idate:"Y-m-d H:i:s"}-->" onclick="showcalendar(event, this)">
            </td><td class="vtop tips2"></td></tr>
        <tr><td colspan="2" class="td27">内容</td></tr>
        <tr class="noborder"><td class="vtop rowform">
                <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="content" cols="50" class="tarea"><!--{$notice.content}--></textarea></td><td class="vtop tips2">请输入公告内容</td></tr>
        <tr><td colspan="15"><div class="fixsel"><input type="submit" class="btn" name="submit" value="提交" /></div></td></tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->