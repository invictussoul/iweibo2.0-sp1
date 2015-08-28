<!--{include file="admin/header.tpl"}-->
<div class="itemtitle"><h3>微博功能</h3></div>
<form action="/admin/config/blog" method="post" onsubmit="return $.checkForm(this)" enctype="multipart/form-data" >
    <table class="tb tb2">
        <tr>
            <td class="td27" colspan="2">本地发表内容全部审核：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <!--{TO->cfg key="censor" group="basic" assign="_censor" default="0"}-->
                <label><input type="radio" class="radio" name="config[basic][censor]"<!--{if $_censor == 1}--> checked="checked"<!--{/if}--> value="1" />是</label>
                <label><input type="radio" class="radio" name="config[basic][censor]"<!--{if $_censor == 0}--> checked="checked"<!--{/if}--> value="0" />否</label>
            </td>
            <td class="vtop tips2">本地发表的内容全部需要先通过管理人员审核之后才显示，请慎用</td>
        </tr>
    </table>
    <div class="opt"><input type="submit" name="editsubmit" value="提交" class="btn" tabindex="3" /></div>
</form>
<!--{include file="admin/footer.tpl"}-->