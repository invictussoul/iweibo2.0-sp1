<!--{include file="admin/header.tpl"}-->
<!--{include file="admin/tiview_menu.tpl"}-->
<div class="floattopempty"></div>
<table class="tb tb2 ">
<tr><td colspan="2" class="td27"><!--{if $ol}-->在线<!--{else}-->所有<!--{/if}-->访谈列表</td></tr>
<tr class="header">
    <th width="45"></th>
    <th>访谈间</th>
    <th>开始时间</th>
    <th>结束时间</th>
    <th>状态</th>
    <th>先审后发</th>
    <th>操作</th>
    <th></th>
</tr>
<!--{if $tls.size > 0 }-->
    <!--{foreach name=files item=tiview key=i from=$tls.data}-->
    <tr class="hover">
        <td></td>
        <td><a href="/tiview/view/id/<!--{$tiview.id}-->" target="_blank"><!--{$tiview.tname}--></td>
        <td><!--{$tiview.sdate}--></td>
        <td><!--{$tiview.edate}--></td>
        <td><!--{$tiview.statusText}--></td>
        <td><!--{if !$tiview.direct}-->是<!--{else}--><span style="color:red">否</span><!--{/if}--></td>
        <td>
            <a href="/admin/tiview/modify/id/<!--{$tiview.id}-->">编辑</a> &nbsp;|&nbsp;
            <a href="/admin/tiview/del/id/<!--{$tiview.id}-->" onclick="return confirm('确定删除? 操作不可恢复!');">删除</a> &nbsp;|&nbsp;
            <a href="/admin/tiview/approval/id/<!--{$tiview.id}-->">审批</a>
        </td>
        <td></td>
    </tr>
    <!--{/foreach}-->
    <tr>
        <td colspan="8" align="right"><!--{include file="common/multipage.tpl"}--></td>
    </tr>
<!--{else}-->
    <tr class="hover">
        <td colspan="8" style="padding-left:50px;"> 暂无记录! </td>
    </tr>
<!--{/if}-->
</table>
</div>
<!--{include file="admin/footer.tpl"}-->