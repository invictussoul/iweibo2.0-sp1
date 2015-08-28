<!--{include file="admin/header.tpl"}-->
<div class="itemtitle">
    <h3>编辑管理组</h3>
    <ul class="tab1">
        <li><a href="/admin/group/manage"><span>管理</span></a></li>
    </ul>
</div>
<form action="/admin/group/access" method="post">
    <input type="hidden" name="action" value="access" />
    <input type="hidden" name="gid" value="<!--{$group.gid}-->" />
    <table class="tb tb2">
        <tr>
            <td class="td27" colspan="2">名称:</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform"><!--{$group.title}--></td>
            <td class="vtop tips2"></td>
        </tr>
        <tr>
            <td class="td27">
                允许使用的管理权限:<input class="checkbox" name="chkall1" onclick="checkAll('prefix', this.form, 'accessnew', 'chkall1', true)" id="chkall1" type="checkbox"><label for="chkall1"> 全选</label>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                前台权限
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <ul class="dblist" onmouseover="altStyle(this);">
                    <li><!--{html_checkboxes name="accessnew" options=$user_checkboxes checked=$user_checked separator="</li><li>"}--></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                后台权限
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <ul class="dblist" onmouseover="altStyle(this);">
                    <li><!--{html_checkboxes name="accessnew" options=$admin_checkboxes checked=$admin_checked separator="</li><li>"}--></li>
                </ul>
            </td>
        </tr>
    </table>
    <div class="opt"><input type="submit" name="editsubmit" value=" 提交 " class="btn" tabindex="3" /></div>
</form>
<!--{include file="admin/footer.tpl"}-->