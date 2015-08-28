<!--{include file="admin/header.tpl"}-->
<div class="itemtitle"><h3>防灌水设置</h3></div>
<form action="/admin/config/sec" method="post" onsubmit="return $.checkForm(this)">
    <table class="tb tb2">
        <tr>
            <td class="td27" colspan="2">注册开启验证码：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <!--{TO->cfg key="code_on_reg" group="basic" assign="_code_on_reg" default="0"}-->
                <label><input type="radio" class="radio" name="config[basic][code_on_reg]"<!--{if $_code_on_reg == 1}--> checked="checked"<!--{/if}--> value="1" />开启</label>
                <label><input type="radio" class="radio" name="config[basic][code_on_reg]"<!--{if $_code_on_reg == 0}--> checked="checked"<!--{/if}--> value="0" />关闭</label>
            </td>
            <td class="vtop tips2">&nbsp;</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">登录开启验证码：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <!--{TO->cfg key="code_on_login" group="basic" assign="_code_on_login" default="0"}-->
                <label><input type="radio" class="radio" name="config[basic][code_on_login]"<!--{if $_code_on_login == 1}--> checked="checked"<!--{/if}--> value="1" />开启</label>
                <label><input type="radio" class="radio" name="config[basic][code_on_login]"<!--{if $_code_on_login == 0}--> checked="checked"<!--{/if}--> value="0" />关闭</label>
            </td>
            <td class="vtop tips2">&nbsp;</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">后台登录开启验证码：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <!--{TO->cfg key="code_on_adminlogin" group="basic" assign="_code_on_adminlogin" default="0"}-->
                <label><input type="radio" class="radio" name="config[basic][code_on_adminlogin]"<!--{if $_code_on_adminlogin == 1}--> checked="checked"<!--{/if}--> value="1" />开启</label>
                <label><input type="radio" class="radio" name="config[basic][code_on_adminlogin]"<!--{if $_code_on_adminlogin == 0}--> checked="checked"<!--{/if}--> value="0" />关闭</label>
            </td>
            <td class="vtop tips2">&nbsp;</td>
        </tr>
    </table>
    <div class="opt"><input type="submit" name="submit" value="提交" class="btn"/></div>
</form>
<!--{include file="admin/footer.tpl"}-->