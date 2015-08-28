<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>认证用户</h3>
    </div>
</div>
<form action="/admin/certification/auth" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="auth" />
    <input type="hidden" name="uid" value="<!--{$uid}-->" />
    <input type="hidden" name="auth" value="<!--{$auth}-->" />
    <table class="tb tb2">
        <tr class="noborder">
            <td class="td27" colspan="2">认证说明文字:</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform"> <textarea name="userauthtext"></textarea></td>
            <td class="vtop tips2"></td>
        </tr>
    </table>
    <div class="opt"><input type="submit" name="authsubmit" value="提交" class="btn" tabindex="3" /></div>
</form>
<!--{include file="admin/footer.tpl"}-->