<style>
.erroDiv {padding:5px; background:#FFFF80; border:1px solid #C4C43C;}
.errorMessage {color:red; font-weight:bold; font-size:12px; line-height:18px;}
.warningTip {color:red; font-weight:bold; font-size:13px;}
</style>
<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>添加品牌</h3>
        <ul class="tab1">
            <li<!--{if 'index' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/brand/index"><span>管理</span></a></li>
            <li<!--{if 'add' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/brand/add"><span>添加</span></a></li>
        </ul>
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
<form name="cpform" method="post" action="/admin/brand/add" id="cpform" >
    <table class="tb tb2 ">
        <tr><th colspan="15" class="partition">添加</th></tr>
        <tr><td colspan="2" class="td27">品牌名字 <span class="warningTip">(请确定您添加的品牌微博账号正确而且存在)</span></td></tr>
        <tr class="noborder"><td class="vtop rowform">
            <input name="name" value="<!--{$brand.name}-->" type="text" class="txt" /></td><td class="vtop tips2"></td></tr>
        <tr><td colspan="2" class="td27">品牌描述</td></tr>
        <tr class="noborder"><td class="vtop rowform">
        <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="description" cols="50" class="tarea"><!--{$brand.description}--></textarea></td><td class="vtop tips2">请输入品牌的描述</td></tr>
        <tr>
            <td colspan="2" class="td27">品牌图片链接 <span style="color:red;font-weight:bold;">(请确定您添加的图片链接正确.)</span></td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform"><input name="picture" value="<!--{$brand.picture}-->" type="text" class="txt" /></td>
            <td class="vtop tips2"></td>
        </tr>
        <tr>
            <td colspan="2" class="td27">品牌链接<span style="color:red;font-weight:bold;">(请以http://开头)</span></td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <input name="link" value="<!--{$brand.link}-->" type="text" class="txt" />
            </td>
            <td class="vtop tips2"></td>
        </tr>
        <tr><td colspan="15">
        <div class="fixsel">
        <input type="hidden" name="action" value="post" />
        <input type="submit" class="btn" name="submit" value="提交" />&nbsp;&nbsp;
        <input type="button" class="btn" name="return" value="返回" onClick="returnIndex();" />
        </div></td></tr>
    </table>
</form>
<script type="text/javascript" src="/resource/admin/js/calendar.js"></script>
<script language="javascript" type="text/javascript"><!--
    function returnIndex()
    {
        window.location.href= iwbRoot + 'admin/brand/index';
    }
//--></script>
<!--{include file="admin/footer.tpl"}-->
