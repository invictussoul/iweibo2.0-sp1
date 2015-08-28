<!--{include file="admin/header.tpl"}-->
<!--{include file="admin/tiview_menu.tpl"}-->
<div class="floattopempty"></div>
<form name="cpform" method="post" action="/admin/tiview/pass" id="cpform" >
<table class="tb tb2 ">
<tr><td colspan="7" class="td27"><!--{if $tls.tlive}-->[<!--{$tls.tlive.tname}-->] <!--{else}-->所有<!--{/if}-->审批列表</td></tr>
<tr class="header">
    <th width="60"></th>
    <th width="100">访谈间</th>
    <th>内容</th>
    <th width="100">作者</th>
    <th width="120">发布日期</th>
    <th>操作</th>
    <th width="50"></th>
</tr>
<!--{if $tls.size > 0 }-->
    <!--{foreach name=files item=tpost key=i from=$tls.data}-->
    <tr class="hover">
        <td ><input type="checkbox" name="msgid[]" value="<!--{$tpost.msgid}-->" class="checkbox" /> </td>
        <td>
        
        <!--{if $tpost.tname}--><A href="/tiview/view/id/<!--{$tpost.tid}-->" target="_blank" title="<!--{$tpost.tname}-->"><!--{$tpost.tname|itruncate:20:"..."}--></a>
        <!--{else}-->
        <A href="/tiview/view/id/<!--{$tls.tiview.id}-->" target="_blank" title="<!--{$tls.tiview.tname}-->"><!--{$tls.tiview.tname|itruncate:20:"..."}--></a>
        <!--{/if}-->
        </td>
        <td><!--{$tpost.text}--></td>
        <td><!--{$tpost.name}--></td>
        <td><!--{$tpost.dateline|idate:"m月d H:i"}--> </td>
        <td>
            <!--{if $tpost.tstatus}-->
            <span style="color:green">已通过</span>
            <a href="/admin/tiview/pass/t/0/id/<!--{$tpost.msgid}-->">屏蔽</a>
            <!--{else}-->
            <span style="color:Red">已屏蔽</span>
            <a href="/admin/tiview/pass/t/1/id/<!--{$tpost.msgid}-->">通过</a>
            <!--{/if}-->
        </td>
        <td></td>
    </tr>
    <!--{/foreach}-->
    <tr>
        <td align="left">
        <input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'msgid')" /><label for="chkall">全选</label>
        </td>
        <td colspan=2>
        <input class="btn"  type="submit" name="submit" value="屏蔽"/>&nbsp;&nbsp;
        <input class="btn"  type="submit" name="submit" value="通过"/></td>
        <td colspan="4"  align="right"><!--{include file="common/multipage.tpl"}--></td>
    </tr>
<!--{else}-->
    <tr class="hover">
        <td colspan="7" style="padding-left:50px;"> 暂无记录! </td>
    </tr>
<!--{/if}-->
</table>
</form>
</div>
<!--{include file="admin/footer.tpl"}-->