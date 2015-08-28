<!--{include file="admin/header.tpl"}-->
<style>
#whichpage {width:160px;}
INPUT.txt {width:20px;}
</style>
<div class="floattop">
    <div class="itemtitle">
        <h3>组件管理：</h3>
    </div>
</div>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td width="100" height="30"><strong>组件显示页面：</strong></td>
        <td>
            <select name="whichpage" id="whichpage">
                <!--{foreach key=key item=page from=$sitePage}-->
                <option value="<!--{$key}-->"<!--{if $key == $whichpage}--> selected="selected"<!--{/if}-->><!--{$page}--></option>
                <!--{/foreach}-->
            </select> <br />
        </td>
        <td></td>
    </tr>
    <tr>
        <td height="30"><strong>组件显示区域：</strong></td>
        <td>
            <select name="displaycolumn" id="displaycolumn">
                <option value="main"<!--{if 'main' == $column}--> selected="selected"<!--{/if}-->>主体模块</option>
                <option value="right"<!--{if 'right' == $column}--> selected="selected"<!--{/if}-->>右侧模块</option>
            </select>
        </td>
        <td></td>
    </tr>
</table>
<!--
<table class="tb tb2 " id="tips">
    <tr><th  class="partition">技巧提示</th></tr>
    <tr><td class="tipsblock"><ul id="tipslis"><li>每日推荐</li></ul></td></tr>
</table>
-->
    <form name="cpform" method="post" action="/admin/componentmgt/batchsetting" id="cpform" >
    <table class="tb tb2 ">
        <tr class="header">
            <th align="center" width="45">启用</th>
            <th align="center" width="100">顺序</th>
            <th align="center" width="300">标题</th>
            <th align="center" width="200">组件类型</th>
            <th align="center">操作</th>
            <th></th>
        </tr>
        <!--微博组件/话题组件/用户组件/名人推荐/广告组件/运营组件/排行榜-->
        <!--{foreach key=componentType item=component from=$componentsSettings}-->
        <tr class="hover">
            <td><input class="checkbox" type="checkbox" name="configs[<!--{$componentType}-->][status]"<!--{if '1' == $component.component_status}--> checked="checked"<!--{/if}-->></td>
            <td align="align"><input type="text" class="txt" style="width:30px;" name="configs[<!--{$componentType}-->][sequence]" value="<!--{if $component.component_sequence}--><!--{$component.component_sequence}--><!--{else}-->0<!--{/if}-->" size="2" ></td>
            <td align="align"><!--{if $component.component_title}--><!--{$component.component_title}--><!--{/if}--></td>
            <td align="align">【<!--{if $component.type}--><!--{$component.type}--><!--{else}--><!--{$componentType}--><!--{/if}-->】</td>
            <td align="align"><a href="/admin/componentmgt/edit/whichpage/<!--{$whichpage}-->/component/<!--{$componentType}-->/right">编辑</a></td>
            <td></td>
        </tr>
        <!--{/foreach}-->
        <tr>
            <td class="td25">
                <input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'status')" />
                <input type="hidden" name="whichpage" value="<!--{$whichpage}-->" />
                <input type="hidden" name="column" value="<!--{$column}-->" />
                <input type="hidden" name="action" value="post" />
            </td>
            <td colspan="15"><div class="fixsel"><input type="submit" class="btn" value="提交" /></div></td>
        </tr>
        <tr>
            <td class="td25"></td>
            <td colspan="15"><div class="fixsel"></div></td>
        </tr>
    </table>
</form>
<script language="javascript" type="text/javascript"><!--
    $(document).ready(function(){
        $('#whichpage').change(function(){
            var whichpage = $('#whichpage').val();
            window.location.href = iwbRoot + 'admin/componentmgt/right/whichpage/' +　whichpage;
        });
        $('#displaycolumn').change(function(){
            window.location.href = iwbRoot + 'admin/componentmgt/main/whichpage/<!--{$whichpage}-->';
        });
        $('#addComponent').click(function(){
            //var whichpage = $('#whichpage').val();
            window.location.href = iwbRoot + 'admin/componentmgt/add/whichpage/<!--{$whichpage}-->';
        });
    });
//--></script>
<!--{include file="admin/footer.tpl"}-->