<!--{include file="admin/header.tpl"}-->
<div class="itemtitle"><h3>站点设置</h3></div>
<form action="/admin/config/site" method="post" onsubmit="return $.checkForm(this)" enctype="multipart/form-data" >
    <table class="tb tb2">
        <tr>
            <td class="td27" colspan="2">网站名称：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform"><input type="text" class="txt" name="config[basic][site_name]" value="<!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}-->" datatype="Require" msg="请填写网站名称"/></td>
            <td class="vtop tips2"><span info="config[site][name]"></span>将显示在浏览器窗口标题等位置</td>
        </tr>
         <tr>
            <td class="td27" colspan="2">网站 URL：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform"><input type="text" class="txt" name="config[basic][site_url]" value="<!--{TO->cfg key="site_url" group="basic" default=""}-->" /></td>
            <td class="vtop tips2"><span info="config[basic][site_url]"></span></td>
        </tr>
        <tr>
            <td class="td27" colspan="2">管理员 Email：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform"><input type="text" class="txt" name="config[basic][site_email]" value="<!--{TO->cfg key="site_email" group="basic" default=""}-->" datatype="Email" msg="请填写管理Email"/></td>
            <td class="vtop tips2"><span info="config[basic][site_email]"></span>作为邮件发送的邮箱</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">站点 LOGO：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <img src="<!--{TO->cfg key="site_logo" group="basic" default="/resource/images/iweibo.png"}-->" width="100" />
                <input type="file" class="txt" id="site_logo" name="site_logo" /></td>
            <td class="vtop tips2">请依据你的网站皮肤控制上传图片大小，安装时初始化的 LOGO为 默认的 iWeibo 的 LOGO</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">网站备案号：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform"><input type="text" class="txt" name="config[basic][site_beian]" value="<!--{TO->cfg key="site_beian" group="basic"}-->" /></td>
            <td class="vtop tips2">请填写 ICP 备案号</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">站点关闭：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <!--{TO->cfg key="site_closed" group="basic" assign="_site_closed" default="0"}-->
                <label><input type="radio" class="radio" name="config[basic][site_closed]"<!--{if $_site_closed == 1}--> checked="checked"<!--{/if}--> value="1" onclick="showObj('site_close');" />是</label>
                <label><input type="radio" class="radio" name="config[basic][site_closed]"<!--{if $_site_closed == 0}--> checked="checked"<!--{/if}--> value="0" onclick="hideObj('site_close');"/>否</label>
            </td>
            <td class="vtop tips2">站点关闭之后，只有管理员才可以登录和访问</td>
        </tr>
        <tbody id="site_close" <!--{if $site_closed == 0}-->style="display: none;"<!--{/if}-->>
               <tr>
                <td class="td27" colspan="2">站点关闭原因：</td>
            </tr>
            <tr class="noborder">
                <td class="vtop rowform">
                    <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="config[basic][site_close_prompt]" id="_site_close_prompt" cols="50" class="tarea"><!--{TO->cfg key="site_close_prompt" group="basic" default="系统维护中，请稍候......"}--></textarea>
                </td>
                <td class="vtop tips2">请填写站点关闭原因，在网站关闭时，用于给普通用户显示</td>
            </tr>
        </tbody>
        <tr>
            <td class="td27" colspan="2">网站第三方统计代码：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="config[basic][site_tj]" id="site_tj" cols="50" class="tarea"><!--{TO->cfg key="site_tj" group="basic" default=""}--></textarea>
            </td>
            <td class="vtop tips2">请填写第三方统计的 js 代码</td>
        </tr>
    </table>
    <div class="opt"><input type="submit" name="editsubmit" value="提交" class="btn" tabindex="3" /></div>
</form>
<!--{include file="admin/footer.tpl"}-->