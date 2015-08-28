<!--{include file="admin/header.tpl"}-->
<script language="javascript">
function onLoginSelect(chk_id,input_id) {
if($("#"+chk_id).prop('checked')) {
    $("#" + input_id).prop("value","1");
} else {
    $("#" + input_id).prop("value","0");
}
}
</script>
<div class="itemtitle"><h3>登录授权</h3></div>
<form action="/admin/config/login" method="post">
    <table class="tb tb2">
        <tr>
            <td class="td27" colspan="2">网站登录方式：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <!--{TO->cfg key="login_local" group="basic" assign="_login_local" default="1"}-->
                <input class="input" id="input_local" name="config[basic][login_local]" type="hidden" value="<!--{$_login_local}-->"  />
                <!--{TO->cfg key="login_tencent" group="basic" assign="_login_tencent" default="1"}-->
                <input class="input" name="config[basic][login_tencent]" id="input_tencent" type="hidden" value="<!--{$_login_tencent}-->"  />
                <ul>
                    <li><label><input id="chk_local" value="1" type="checkbox" <!--{if $_login_local == 1}--> checked="checked"<!--{/if}--> onchange="onLoginSelect('chk_local','input_local')" /> 本地帐号登录(需绑定腾讯微博帐号)</label></li>
                    <li><label><input id="chk_tencent" value="1" type="checkbox" <!--{if $_login_tencent == 1}--> checked="checked"<!--{/if}--> onchange="onLoginSelect('chk_tencent','input_tencent')"/> 腾讯微博帐号登录</label></li>
                </ul></td>
            <td class="vtop tips2">本地帐号登录可挂接本地用户系统，微博帐号登录可方便用户登录</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">新用户注册/授权：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <!--{TO->cfg key="login_allow_new_user" group="basic" assign="allow_new_user" default="1"}-->
                <label><input type="radio" class="radio" name="config[basic][login_allow_new_user]"<!--{if $allow_new_user == 1}--> checked="checked"<!--{/if}--> value="1" />是</label>
                <label><input type="radio" class="radio" name="config[basic][login_allow_new_user]"<!--{if $allow_new_user == 0}--> checked="checked"<!--{/if}--> value="0" />否</label>
            </td>
            <td class="vtop tips2">允许，可以开通新的本地帐号，否则将不允许新用户在本地注册或授权</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">系统自动分配帐号和密码：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <!--{TO->cfg key="login_allow_auto_register" group="basic" assign="login_allow_auto_register" default="1"}-->
                <label><input type="radio" class="radio" name="config[basic][login_allow_auto_register]"<!--{if $login_allow_auto_register == 1}--> checked="checked"<!--{/if}--> value="1" />是</label>
                <label><input type="radio" class="radio" name="config[basic][login_allow_auto_register]"<!--{if $login_allow_auto_register == 0}--> checked="checked"<!--{/if}--> value="0" />否</label>
            </td>
            <td class="vtop tips2">允许，系统自动分配帐号，否则将不允许系统自动分配帐号，需用户自行手动注册</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">继承腾讯微博用户关系：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <!--{TO->cfg key="login_user_inherit" group="basic" assign="login_inherit_tencent_user" default="1"}-->
                <label><input type="radio" class="radio" name="config[basic][login_user_inherit]"<!--{if $login_inherit_tencent_user == 1}--> checked="checked"<!--{/if}--> value="1" />是</label>
                <label><input type="radio" class="radio" name="config[basic][login_user_inherit]"<!--{if $login_inherit_tencent_user == 0}--> checked="checked"<!--{/if}--> value="0" />否</label>
            </td>
            <td class="vtop tips2">可以选择是否继承腾讯微博关系，不继承则表示需要重新建立本地的用户关系，请不要随意更换</td>
        </tr>
    </table>
    <div class="opt"><input type="submit" name="editsubmit" value="提交" class="btn" tabindex="3" /></div>
</form>
<!--{include file="admin/footer.tpl"}-->