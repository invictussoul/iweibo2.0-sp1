<!--{include file="admin/header.tpl"}-->
<div class="itemtitle"><h3>管理组</h3></div>
<table class="tb tb2 " id="tips">
    <!--
    <tr><th  class="partition">技巧提示</th></tr>
    <tr><td class="tipsblock">
        <ul id="tipslis">
            <li>系统内置的管理组不允许删除。</li>
            <li>“信息监察员”是一个特殊的管理组，此管理组的用户可以查看管理中心的所有设置但无权修改。</li>
        </ul>
    </td></tr>
    -->
</table>
<form name="cpform" method="post" action="/admin/group/manage" id="cpform" >
    <input type="hidden" name="action" value="manage" />
    <table class="tb tb2 fixpadding">
        <tr class="header">
            <th width="45"></th>
            <th>名称</th>
            <th>类型</th>
            <th>成员数</th>
        </tr>
        <!--{foreach from=$usergroups key=gid item=group}-->
            <tr class="hover">
                <td class="td25"><!--{if $group.type == '1'}--><input type="checkbox" name="deleteids[<!--{$gid}-->]" value="<!--{$gid}-->" /><!--{/if}--></td>
                <td class="td24">
                    <input type="text" name="titlenew[<!--{$gid}-->]" value="<!--{$group.title}-->" class="txt" readonly />
                </td>
                <td class="td25"><!--{if $group.type == '1'}-->自定义<!--{else}-->内置<!--{/if}--></td>
                <td><a href="<!--{$group.url}-->"><!--{$group.usernum}--></a><!--{*<a href="/admin/group/access/gid/<!--{$gid}-->">编辑</a>*}--></td>
            </tr>
        <!--{/foreach}-->
        <!--{*
        <tr class="hover">
            <td class="td25">新增:</td>
            <td class="td24"><input type="text" class="txt" size="12" name="newtitle"></td>
            <td class="td25">自定义</td>
            <td></td><td></td>
        </tr>
        <tr>
            <td class="td25">
                <input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'deleteids')" /><label for="chkall">删?</label>
            </td>
            <td colspan="3">
                <div class="fixsel"><input type="submit" class="btn" id="submit_groupsubmit" name="groupsubmit" value="提交" /></div>
            </td>
        </tr>
        *}-->
    </table>
</form>
</div>
<!--{include file="admin/footer.tpl"}-->