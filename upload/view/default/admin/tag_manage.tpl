<!--{include file="admin/header.tpl"}-->
<div class="itemtitle"><h3>标签管理</h3></div>
<table id="tips" class="tb tb2 ">
    <!--
    <tr>
        <th class="partition">搜索标签</th>
    </tr>
    <tr>
        <td class="tipsblock">
            <ul id="tipslis">
                <li></li>
            </ul>
        </td>
    </tr>
    -->
</table>
<div class="itemtitle">
    <div class="cuspages right">
        <form id="searchtagform" name="searchtagform" method="post" action="/admin/tag/manage">
            标签：
            <input type="text" id="tagname" name="tagname" value="<!--{$conditions.tagname}-->" class="txt" onclick="this.value=''" size="8" />
            用户数介于：
            <input type="text" id="usenumstart" name="usenumstart" value="<!--{$conditions.usenumstart}-->" class="txt" onclick="this.value=''" size="8" />--
            <input type="text" id="usenumend" name="usenumend" value="<!--{$conditions.usenumend}-->" class="txt" onclick="this.value=''" size="8" />
            状态：
            <input type="radio" id="all" name="status" value="0" checked /> 全部
            <input type="radio" id="open" name="status" value="1" <!--{if $conditions.status == 1}-->checked<!--{/if}--> /> 开放
            <input type="radio" id="lock" name="status" value="2" <!--{if $conditions.status == 2}-->checked<!--{/if}--> /> 锁定
            <input class="btn" type="submit" value="搜索" name="searchsubmit" />
        </form>
    </div>
</div>
<!--{if $tags}-->
    <form id="tagadminform" name="tagadminform" method="post" action="/admin/tag/manage">
    <input type="hidden" name="action" value="manage" />
    <table class="tb tb2">
        <tr>
            <th class="partition" colspan="10">共搜索到<strong> <!--{$tagscount}--> </strong>名符合条件的标签</th>
        </tr>
        <tr class="header">
            <th width="50">&nbsp;</th>
            <th>标签</th>
            <th>颜色</th>
            <th>用户数</th>
            <th></th>
        </tr>
        <!--{foreach from=$tags key=key item=tag}-->
            <tr>
                <td><input type="checkbox" name="deleteids[]" value="<!--{$tag.id}-->" /></td>
                <td><!--{$tag.tagname}--></td>
                <td><input type="text" class="color {required:false}" maxlength="6" name="tagcolor_<!--{$tag.id}-->" <!--{if $tag.color}-->value="<!--{$tag.color}-->"<!--{/if}--> /></td>
                <td><!--{$tag.usenum}--></td>
                <td>
                    <input type="hidden" name="tagid[]" value="<!--{$tag.id}-->" />
                    <input type="hidden" name="tagname_<!--{$tag.id}-->" value="<!--{$tag.tagname}-->" />
                    <input type="radio" name="status_<!--{$tag.id}-->" value="1" <!--{if $tag.visible == 1}-->checked<!--{/if}--> />开放
                    <input type="radio" name="status_<!--{$tag.id}-->" value="2" <!--{if $tag.visible == 2}-->checked<!--{/if}--> />锁定
                </td>
            </tr>
        <!--{/foreach}-->
        <tr>
            <td colspan="2">
                <input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'deleteids')" /><label for="chkall">删除</label>
                <input type="submit" class="btn" value="提交">
            </td>
            <td colspan="2" align="right"><!--{include file="common/multipage.tpl"}--></td>
        </tr>
    </table>
    </form>
<!--{else}-->
    <table class="tb tb2">
        <tr>
            <th class="partition" colspan="10">未找到符合条件的标签</th>
        </tr>
    </table>
<!--{/if}-->
<!--{include file="admin/footer.tpl"}-->