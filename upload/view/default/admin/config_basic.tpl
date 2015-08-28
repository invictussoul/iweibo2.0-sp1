<!--{include file="admin/header.tpl"}-->
<div class="itemtitle"><h3>基础设置</h3></div>
<form action="/admin/config/basic" method="post" onsubmit="return $.checkForm(this)">
    <table class="tb tb2">
        <tr>
            <td class="td27" colspan="2">Cookie 前缀：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <input type="text" class="txt" value="<!--{TO->cfg key='cookiepre' group='basic' default='t_'}-->" datatype="Require" msg="请填写Cookie前缀" name="config[basic][cookiepre]" />
            </td>
            <td class="vtop tips2"><span info="config[basic][cookiepre]"></span> 用于区分本站 cookie，默认为 t_，请不要随便修改</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">Cookie 域：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <input type="text" class="txt" value="<!--{TO->cfg key="cookiedomain" group="basic" default=""}-->" name="config[basic][cookiedomain]" />
            </td>
            <td class="vtop tips2">默认为空，建议不要随便修改</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">会话保持时间（分钟）：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <input type="text" class="txt" value="<!--{TO->cfg key="cookietime" group="basic" default="30"}-->"  datatype="Number" msg="请填写Cookie时间" name="config[basic][cookietime]" /></td><td class="vtop tips2"><span info="config[basic][cookietime]"></span> 默认为 30</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">默认时区：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <input type="text" class="txt" value="<!--{TO->cfg key="timezone" group="basic" default="8"}-->" name="config[basic][timezone]" datatype="Number" msg="请填写默认时区" /></td><td class="vtop tips2"><span info="config[basic][timezone]"></span> 当地时间与 GMT 的时差，默认为 8（北京时区），请不要随便修改</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">时间修正(秒)：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <input type="text" class="txt" value="<!--{TO->cfg key="timemodify" group="basic" default="0"}-->" name="config[basic][timemodify]" datatype="Int" msg="请填写时间修正" /></td>
            <td class="vtop tips2"><span info="config[basic][timemodify]"></span> 当服务器时间误差时使用此功能 修正后时间<!--{$righttime|idate:"m月d日 H:i"}--></td>
        </tr>
        <tr>
            <td class="td27" colspan="2">Debug 模式：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <!--{TO->cfg key="rundebug" group="basic" assign="_rundebug" default="0"}-->
                <label><input type="radio" class="radio" name="config[basic][rundebug]"<!--{if $_rundebug == 1}--> checked="checked"<!--{/if}--> value="1" />开启</label>
                <label><input type="radio" class="radio" name="config[basic][rundebug]"<!--{if $_rundebug == 0}--> checked="checked"<!--{/if}--> value="0" />关闭</label>
            </td>
            <td class="vtop tips2">网站出现问题时的调试，默认为关闭</td>
        </tr>
    </table>
    <div class="opt"><input type="submit" name="submit" value="提交" class="btn"/></div>
</form>
<!--{include file="admin/footer.tpl"}-->