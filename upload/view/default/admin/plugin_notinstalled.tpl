<!--{include file="admin/header.tpl"}-->
<div class="itemtitle">
    <h3>插件管理</h3>
    <ul class="tab1">
        <li><a href="/admin/plugin/installed"><span>已安装</span></a></li>
        <li class="current"><a href="javascript:void(0);"><span>未安装</span></a></li>
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
<table class="tb tb2">
    <tr class="header">
        <td></td>
        <td width="100">状态</td>
        <td width="100"></td>
        <td></td>
        <td>文件夹名</td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <!--{foreach from=$folderArr item=folder}-->
        <tr>
            <td></td>
            <td>未安装</td>
            <td></td>
            <td></td>
            <td><!--{$folder}--></td>
            <td></td>
            <td></td>
            <td><a href="/admin/plugin/install/foldername/<!--{$folder}-->">安装</a></td>
        </tr>
    <!--{/foreach}-->
</table>
<!--{include file="admin/footer.tpl"}-->