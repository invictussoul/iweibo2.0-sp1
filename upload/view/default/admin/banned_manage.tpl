<!--{include file="admin/header.tpl"}-->
<script type="text/javascript">
function fillstar()
{
    if($("#ip1new").val() || $("#ip2new").val() || $("#ip3new").val() || $("#ip4new").val())
    {
        if(!$("#ip1new").val())
            $("#ip1new").val("*");
        if(!$("#ip2new").val())
            $("#ip2new").val("*");
        if(!$("#ip3new").val())
            $("#ip3new").val("*");
        if(!$("#ip4new").val())
            $("#ip4new").val("*");
    }
}
</script>
<div class="itemtitle"><h3>禁止 IP</h3></div>
<table class="tb tb2 " id="tips">
    <!--
    <tr><th  class="partition">技巧提示</th></tr>
    <tr>
        <td class="tipsblock"><ul id="tipslis"><li>可以使用“*”作为通配符禁止某段地址。</li></ul></td>
    </tr>
    -->
</table>
<form name="cpform" method="post" action="/admin/banned/manage" id="cpform" onsubmit="fillstar()">
    <input type="hidden" name="action" value="manage" />
    <table class="tb tb2 ">
        <tr class="header">
            <th></th>
            <th>IP 地址</th>
            <th>添加者</th>
        </tr>
        <!--{foreach from=$ipbanneds item=ipbanned}-->
        <tr class="hover">
            <td class="td25"><input class="checkbox" type="checkbox" name="delete[<!--{$ipbanned.id}-->]" value="<!--{$ipbanned.id}-->"/></td>
            <td><!--{$ipbanned.ip}--></td>
            <td><!--{$ipbanned.username}--></td>
        </tr>
        <!--{/foreach}-->
        <tr class="hover">
            <td>新增:</td>
            <td class="td28">
                <input type="text" class="txt" id="ip1new" name="ip1new" value="" size="3" maxlength="3">.
                <input type="text" class="txt" id="ip2new" name="ip2new" value="" size="3" maxlength="3">.
                <input type="text" class="txt" id="ip3new" name="ip3new" value="" size="3" maxlength="3">.
                <input type="text" class="txt" id="ip4new" name="ip4new" value="" size="3" maxlength="3">
                可以使用“*”作为通配符禁止某段地址。
            </td>
            <td></td>
        </tr>
        <tr>
            <td class="td25"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'delete')" /><label for="chkall">删?</label></td>
            <td><div class="fixsel"><input type="submit" class="btn" id="submit_ipbansubmit" name="ipbansubmit" value="提交" /></div></td>
            <td align="right"><!--{include file="common/multipage.tpl"}--></td>
        </tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->