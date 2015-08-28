<!--{include file="admin/header.tpl"}-->
<div class="itemtitle"><h3>搜索管理</h3></div>
<form action="/admin/config/search" method="post">
    <table class="tb tb2">
        <tr><td colspan="2" class="td27">热词设置：</td></tr>
        <tr class="noborder"><td class="vtop rowform">
                <input name="config[basic][hot_words]" type="text" maxlength="25" class="txt" value="<!--{TO->cfg key="hot_words" group="basic" default="iWeibo 腾讯微博"}-->" /></td>
            <td class="vtop tips2">每个热词之间用空格隔开，总长度不能超过 25 个字节，一个汉字 3 个字节</td>
        </tr>
    </table>
    <div class="opt"><input type="submit" name="submit" value="提交" class="btn"/></div>
</form>
<!--{include file="admin/footer.tpl"}-->