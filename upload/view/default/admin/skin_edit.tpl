<!--{include file="admin/header.tpl"}-->
<div class="itemtitle">
    <h3>安装皮肤</h3>
    <ul class="tab1">
        <li><a href="/admin/skin/installed"><span>已安装</span></a></li>
        <li><a href="/admin/skin/notinstalled"><span>未安装</span></a></li>
    </ul>
</div>
<form id="editskinform" name="editskinform" method="post" enctype="multipart/form-data" action="/admin/skin/edit">
<input type="hidden" name="action" value="edit" />
<input type="hidden" name="id" value="<!--{$conditions.id}-->" />
<table class="tb tb2">
    <tr><td class="td27" colspan="2">模板名称：</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="text" class="txt" id="name" name="name" value="<!--{$conditions.name}-->" /></td>
        <td class="vtop tips2" id="nametip" name="nametip"></td>
    </tr>
    <tr><td class="td27" colspan="2">文件夹名：</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="text" class="txt" id="foldername" name="foldername" value="<!--{$conditions.foldername}-->" /></td>
        <td class="vtop tips2" id="foldernametip" name="foldernametip"></td>
    </tr>
    <tr><td class="td27" colspan="2">缩略图：</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="file" class="txt" id="thumb" name="thumb" /></td>
        <td class="vtop tips2" id="thumbtip" name="thumbtip"></td>
    </tr>
    <tr><td class="td27" colspan="2">排序：</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="text" class="txt" id="orderkey" name="orderkey" value="<!--{$conditions.orderkey}-->" /></td>
        <td class="vtop tips2" id="orderkeytip" name="orderkeytip"></td>
    </tr>
    <tr><td class="td27" colspan="2">是否启用：</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="checkbox" id="useable" name="useable" value="1" <!--{if $conditions.useable}-->checked<!--{/if}--> /></td>
        <td class="vtop tips2" id="useabletip" name="useabletip"></td>
    </tr>
    <tr><td colspan="2"><input type="submit" class="btn" id="newusersubmit" name="addsubmit" value="提交"></td></tr>
</table>
</form>
<!--{include file="admin/footer.tpl"}-->