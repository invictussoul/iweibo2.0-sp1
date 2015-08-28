<!--{include file="admin/header.tpl"}-->
<!--{TO->cfg key="auth" group="mail" assign="mail_auth" default="0"}-->
<div class="itemtitle"><h3>邮件设置</h3></div>
<form action="/admin/config/mail" method="post" onsubmit="return $.checkForm(this)">
    <table class="tb tb2">
        <tr>
            <td class="td27" colspan="2">开启邮件发送功能：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <!--{TO->cfg key="open" group="mail" assign="mail_on" default="0"}-->
                <label><input type="radio" class="radio" name="config[mail][open]"<!--{if $mail_on == 1}--> checked="checked"<!--{/if}--> value="1" onclick="showObj('mail_on');$('#mail_mail').attr('checked','checked');"/>是</label>
                <label><input type="radio" class="radio" name="config[mail][open]"<!--{if $mail_on == 0}--> checked="checked"<!--{/if}--> value="0" onclick="hideObj('mail_on');hideObj('mail_sendmail');hideObj('mail_smtp');hideObj('mail_smtp_auth');"/>否</label>
            </td>
            <td class="vtop tips2">找回密码等功能需要开启邮件功能</td>
        </tr>
        <tbody id="mail_on" <!--{if $mail_on == 0}-->style="display: none;"<!--{/if}-->>
            <tr>
                <td class="td27" colspan="2">邮件发送方式:</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform" colspan="2">
                <!--{TO->cfg key="type" group="mail" assign="mail_type" default="mail"}-->
                    <label><input id="mail_mail" type="radio" class="radio" name="config[mail][type]"<!--{if $mail_type == 'mail'}--> checked="checked"<!--{/if}--> value="mail" onclick="hideObj('mail_smtp');hideObj('mail_sendmail');hideObj('mail_smtp_auth');"/>通过 PHP 的 mail 函数发送</label><br />
                    <label><input type="radio" class="radio" name="config[mail][type]"<!--{if $mail_type == 'smtp'}--> checked="checked"<!--{/if}--> value="smtp" onclick="showObj('mail_smtp');<!--{if $mail_auth == 1}-->showObj('mail_smtp_auth');<!--{/if}-->hideObj('mail_sendmail');"/>通过 SOCKET 连接 SMTP 服务器发送（支持 ESMTP 验证）</label><br />
                    <label><input type="radio" class="radio" name="config[mail][type]"<!--{if $mail_type == 'sendmail'}--> checked="checked"<!--{/if}--> value="sendmail" onclick="showObj('mail_sendmail');hideObj('mail_smtp');hideObj('mail_smtp_auth');"/>直接使用 sendmail 发送（适用于类 UNIX 系统）</label>
                </td>
            </tr>
        </tbody>
        <tbody id="mail_smtp" <!--{if $mail_type != 'smtp' || $mail_on == 0}-->style="display: none;"<!--{/if}-->>
            <tr>
                <td class="td27" colspan="2">SMTP 服务器地址：</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform">
                    <input type="text" class="txt" name="config[mail][smtp_server]" value="<!--{TO->cfg key="smtp_server" group="mail" default=""}-->"/>
                </td>
                <td class="vtop tips2">推荐使用 QQ 企业邮箱：<a href="http://exmail.qq.com" target="_blank">http://exmail.qq.com</a></td>
            </tr>
            <tr>
                <td class="td27" colspan="2">SMTP 服务器端口：</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform">
                    <input type="text" class="txt" name="config[mail][smtp_port]" value="<!--{TO->cfg key="smtp_port" group="mail" default=""}-->"/>
                </td>
                <td class="vtop tips2"></td>
            </tr>
            <tr>
                <td class="td27" colspan="2">发信人邮件地址：</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform">
                    <input type="text" class="txt" name="config[mail][sender]" value="<!--{TO->cfg key="sender" group="mail" default=""}-->"/>
                </td>
                <td class="vtop tips2"></td>
            </tr>
            <tr>
                <td class="td27" colspan="2">是否需要验证：</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform">
                    <label><input type="radio" class="radio" name="config[mail][auth]"<!--{if $mail_auth == 1}--> checked="checked"<!--{/if}--> value="1"  onclick="showObj('mail_smtp_auth');" />是</label>
                    <label><input type="radio" class="radio" name="config[mail][auth]"<!--{if $mail_auth == 0}--> checked="checked"<!--{/if}--> value="0"  onclick="hideObj('mail_smtp_auth');"/>否</label>
                </td>
                <td class="vtop tips2"></td>
            </tr>
            <tbody id="mail_smtp_auth" <!--{if $mail_type != 'smtp' || $mail_on == 0 || $mail_auth == 0}-->style="display: none;"<!--{/if}-->>
            <tr>
                <td class="td27" colspan="2">SMTP 身份验证用户名：</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform">
                    <input type="text" class="txt" name="config[mail][smtp_user]" value="<!--{TO->cfg key="smtp_user" group="mail" default=""}-->"/>
                </td>
                <td class="vtop tips2"></td>
            </tr>
            <tr>
                <td class="td27" colspan="2">SMTP 身份验证密码：</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform">
                    <input type="password" class="txt" name="config[mail][smtp_pwd]" value="<!--{TO->cfg key="smtp_pwd" group="mail" default=""}-->"/>
                </td>
                <td class="vtop tips2"></td>
            </tr>
            </tbody>
        </tbody>
        <tbody id="mail_sendmail" <!--{if $mail_type != 'sendmail' || $mail_on == 0}-->style="display: none;"<!--{/if}-->>
            <tr>
                <td class="td27" colspan="2">sendmail 路径：</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform">
                    <input type="text" class="txt" name="config[mail][sendmail_path]" value="<!--{TO->cfg key="sendmail_path" group="mail" default=""}-->"/>
                </td>
                <td class="vtop tips2"></td>
            </tr>
            <tr>
                <td class="td27" colspan="2">sendmail 参数：</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform">
                    <input type="text" class="txt" name="config[mail][sendmail_args]" value="<!--{TO->cfg key="sendmail_args" group="mail" default=""}-->"/>
                </td>
                <td class="vtop tips2"></td>
            </tr>
        </tbody>
    </table>
    <div class="opt"><input type="submit" name="editsubmit" value="提交" class="btn" tabindex="3" /></div>
</form>
<!--{include file="admin/footer.tpl"}-->