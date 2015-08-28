<!--{include file="admin/header.tpl"}-->
<div class="itemtitle">
    <h3>添加用户</h3>
    <ul class="tab1">
        <li><a href="/admin/user/search"><span>管理</span></a></li>
        <li class="current"><a href="javascript:void(0);"><span>添加</span></a></li>
    </ul>
</div>
<form id="newuserform" name="newuserform" method="post" action="/admin/user/add">
<input type="hidden" name="action" value="add" />
<table class="tb tb2">
    <tr><td class="td27" colspan="2">帐 号：</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="text" class="txt" id="username" name="username" value="<!--{$conditions.username}-->" /></td>
        <td class="vtop tips2" id="usernametip" name="usernametip"></td>
    </tr>
    <tr><td class="td27" colspan="2">密 码：</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="password" class="txt" id="password" name="password" maxlength="15" /></td>
        <td class="vtop tips2" id="passwordtip" name="passwordtip"></td>
    </tr>
    <tr><td class="td27" colspan="2">姓 名：</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="text" class="txt" id="nickname" name="nickname" value="<!--{$conditions.nickname}-->" /></td>
        <td class="vtop tips2" id="nicknametip" name="nicknametip"></td>
    </tr>
    <tr><td class="td27" colspan="2">邮 箱：</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="text" class="txt" id="email" name="email" value="<!--{$conditions.email}-->" /></td>
        <td class="vtop tips2" id="emailtip" name="emailtip"></td>
    </tr>
    <tr><td colspan="2"><input type="submit" class="btn" id="newusersubmit" name="addsubmit" value="提交"></td></tr>
</table>
</form>
<!--{include file="admin/footer.tpl"}-->