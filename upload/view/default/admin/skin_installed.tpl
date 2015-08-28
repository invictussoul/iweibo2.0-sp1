<!--{include file="admin/header.tpl"}-->
<div class="itemtitle">
    <h3>皮肤管理</h3>
    <ul class="tab1">
        <li class="current"><a href="javascript:void(0);"><span>已安装</span></a></li>
        <li><a href="/admin/skin/notinstalled"><span>未安装</span></a></li>
    </ul>
</div>
<table id="tips" class="tb tb2 ">
    <!--
    <tr>
        <th class="partition">技巧提示</th>
    </tr>
    <tr>
        <td class="tipsblock">
            <ul id="tipslis">
                <li>添加新的风格前，请先将风格图片、模板文件上传至服务器指定目录下。</li>
                <li>模板目录必须是tpl目录下的单独目录。</li>
                <li>设置风格是否可用来控制前台用户可选择的风格。</li>
            </ul>
        </td>
    </tr>
    -->
</table>
<form name="setstyleform" method="post" action="/admin/skin/installed">
<input type="hidden" name="action" value="installed" />
<table class="tb tb2">
    <tr class="header">
        <td>卸载</td>
        <td width="100">状态</td>
        <td width="100">启用</td>
        <td>模板名称</td>
        <td>文件夹名</td>
        <td>缩略图</td>
        <td>排序</td>
        <td></td>
    </tr>
    <!--{foreach from=$styles item=style}-->
        <tr>
            <td><!--{if $style.foldername != 'default'}--><input type="checkbox" class="checkbox" id="style_delete_<!--{$style.id}-->" name="style_delete[]" value="<!--{$style.id}-->" ><!--{/if}--></td>
            <td><!--{if $style.error}-->错误<!--{else}-->正常<!--{/if}--></td>
            <td><!--{if $style.useable}-->启用<!--{else}-->停用<!--{/if}--></td>
            <td><!--{$style.name}--></td>
            <td><!--{$style.foldername}--></td>
            <td><img src="<!--{$style.thumb}-->" width="100" /></td>
            <td><!--{$style.orderkey}--></td>
            <td><!--{if $style.foldername != 'default'}--><a href="/admin/skin/edit/id/<!--{$style.id}-->">编辑</a><!--{/if}--></td>
        </tr>
    <!--{/foreach}-->
    <tr>
        <td colspan="2">
            <input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'style_delete')" /><label for="chkall">卸载</label>
            <input type="submit" class="btn" value="提交" />
        </td>
        <td colspan="6" align="right"><!--{include file="common/multipage.tpl"}--></td>
    </tr>
</table>
</form>
<!--{include file="admin/footer.tpl"}-->