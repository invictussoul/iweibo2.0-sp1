<!--{include file="admin/header.tpl"}-->
<div class="itemtitle"><h3>个性化域名设置</h3></div>
<form action="/admin/config/domain" method="post">
    <table class="tb tb2">
        <tr><td class="td27" colspan="2">个性化域名：</td></tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <!--{TO->cfg key="domain_diy" group="basic" default='0' assign="_domain_diy_open"}-->
                <label><input type="radio" class="radio" name="config[basic][domain_diy]"<!--{if $_domain_diy_open == 1}--> checked="checked"<!--{/if}--> value="1" onclick="showObj('domain_diy_open');"/>开启</label>
                <label><input type="radio" class="radio" name="config[basic][domain_diy]"<!--{if $_domain_diy_open == 0}--> checked="checked"<!--{/if}--> value="0" onclick="hideObj('domain_diy_open');"/>关闭</label>
            </td>
            <td class="vtop tips2"></td>
        </tr>
        <tbody id="domain_diy_open" <!--{if $_domain_diy_open == 0}-->style="display: none;"<!--{/if}-->>
               <tr><td class="td27" colspan="2">保留的个性化域名：</td></tr>
            <tr class="noborder"><td class="vtop rowform">
                    <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="config[basic][holddomain]" id="_domain_holddomain" cols="50" class="tarea"><!--{TO->cfg key="holddomain" group="basic" default="www|iweibo"}--></textarea>
                </td><td class="vtop tips2">多个之间用 | 隔开，可以使用通配符* </td></tr>
        </tbody>
    </table>
    <div class="opt"><input type="submit" name="submit" value="提交" class="btn"/></div>
</form>
<!--{include file="admin/footer.tpl"}-->