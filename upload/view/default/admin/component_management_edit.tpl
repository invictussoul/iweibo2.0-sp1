<style>
.erroDiv {padding:5px; background:#FFFF80; border:1px solid #C4C43C;}
.errorMessage {color:red; font-weight:bold; font-size:12px; line-height:18px;}
.warningTip {color:red; font-weight:bold; font-size:13px;}
</style>
<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>组件管理</h3>
        <!--
        <ul class="tab1">
            <li<!--{if 'index' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/brand/index"><span>管理</span></a></li>
            <li<!--{if 'add' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/brand/add"><span>添加</span></a></li>
        </ul>
        -->
    </div>
</div>
<!--{if $errorMessage}-->
<table class="tb tb2 " id="tips">
    <tr>
        <td><div class="erroDiv"><span class="errorMessage">
        <!--{foreach key=key item=error from=$errorMessage}-->
        <!--{$error}--><br />
        <!--{/foreach}-->
        </span></div></td>
    </tr>
</table>
<!--{/if}-->
<form name="cpform" method="post" action="/admin/componentmgt/edit" id="cpform" >
    <table class="tb tb2">
        <tr><th colspan="2" class="partition">当前编辑页面： 【<!--{$sitePage.$whichpage}-->】</th></tr>
        <tr>
            <td width="130" class="td27">组件类型：</td>
            <td><!--{$components.$component.type}--></td>
        </tr>
        <tr>
            <td class="td27">是否启用：</td>
            <td><input name="status"<!--{if '1' == $componentSetting.component_status}--> checked="checked"<!--{/if}--> type="checkbox"/></td>
        </tr>
        <tr>
            <td class="td27">组件标题：</td>
            <td><input name="title" value="<!--{$componentSetting.component_title}-->" type="text" class="txt" /></td>
        </tr>
        <tr class="noborder">
            <td class="td27">显示数据条数：</td>
            <td><input name="number" value="<!--{$componentSetting.component_number}-->" type="text" class="txt" /><!--{if $components.$component.desc}--><span class="warningTip">(<!--{$components.$component.desc}-->)</span><!--{/if}--></td>
        </tr>
        <tr>
            <td colspan="2" class="td27">样式选择：</td>
        </tr>
        <!--{section name=styleid loop=$components.$component.styles}-->
        <tr class="border">
            <td>
                <input type="radio" style="width:50px;" name="style" value="<!--{$smarty.section.styleid.rownum}-->" <!--{if $smarty.section.styleid.rownum == $componentSetting.component_style}--> checked="checked"<!--{/if}-->>样式<!--{$smarty.section.styleid.rownum}-->：
            </td>
            <td> <img style="border:1px dashed #D0CEC5;" src="/resource/admin/images/component/<!--{$column}-->/<!--{$component}-->_<!--{$smarty.section.styleid.rownum}-->.jpg" /></td>
        </tr>
        <!--{/section}-->
        <tr><td colspan="2">
        <div class="fixsel">
        <input type="hidden" name="action" value="post" />
        <input type="hidden" name="whichpage" value="<!--{$whichpage}-->" />
        <input type="hidden" name="type" value="<!--{$component}-->" />
        <input type="hidden" name="column" value="<!--{$column}-->" />
        <input type="submit" class="btn" name="submit" value="提交" />&nbsp;&nbsp;
        <input type="button" class="btn" name="return" value="返回" onClick="returnIndex();" />
        </div></td></tr>
    </table>
</form>
<script type="text/javascript" src="/resource/admin/js/calendar.js"></script>
<script language="javascript" type="text/javascript"><!--
    function returnIndex()
    {
        window.location.href= iwbRoot + 'admin/componentmgt/<!--{$column}-->/whichpage/<!--{$whichpage}-->';
    }
//--></script>
<!--{include file="admin/footer.tpl"}-->
