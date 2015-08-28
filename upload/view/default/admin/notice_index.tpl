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
    <tr><td class="tipsblock"><ul id="tipslis"><li>过期公告将不再显示</li></ul></td></tr>
</table>
<form name="cpform" method="post" action="/admin/notice/index" id="cpform" >
    <table class="tb tb2 ">
        <tr class="header">
            <th></th>
            <th>显示顺序</th>
            <th>标题</th>
            <th>内容</th>
            <th>结束时间</th>
            <th></th>
        </tr>
        <!--{foreach key=key item=notice from=$notices}-->
        <tr class="hover">
            <td class="td25"><input class="checkbox" type="checkbox" name="delete[]" value="<!--{$notice.id}-->" ></td>
            <td class="td28"><input type="text" class="txt" name="displayorder[<!--{$notice.id}-->]" value="<!--{$notice.displayorder}-->" size="2" ></td>
            <td><!--{$notice.title}--></td>
            <td><!--{$notice.content}--></td>
            <td><!--{$notice.endtime|idate:"m月d日 H:i"}--></td>
            <td><a href="/admin/notice/edit/id/<!--{$notice.id}-->" >编辑</a></td>
        </tr>
        <!--{/foreach}-->
        <tr>
            <td class="td25"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'delete')" /></td>
            <td colspan="15"><div class="fixsel"><input type="submit" class="btn" name="noticesubmit" value="提交" /></div></td>
        </tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->