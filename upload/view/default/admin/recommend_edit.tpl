<style>
.erroDiv {padding:5px; background:#FFFF80; border:1px solid #C4C43C;}
.errorMessage {color:red; font-weight:bold; font-size:12px;}
.warningTip {color:red; font-weight:bold; font-size:13px;}
</style>
<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>编辑推荐用户</h3>
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
<script type="text/javascript" src="/resource/admin/js/calendar.js"></script>
<form name="cpform" method="post" action="/admin/recommend/edit" id="cpform" >
    <table class="tb tb2 ">
        <tr><th colspan="15" class="partition">编辑</th></tr>
        <tr><td colspan="2" class="td27">推荐人账号 <span class="warningTip">(请确定您编辑的推荐人微博账号正确而且存在)</span></td></tr>
        <tr class="noborder"><td class="vtop rowform">
            <input name="account" value="<!--{$recommender.account}-->" type="text" class="txt" /></td><td class="vtop tips2"></td></tr>
        <tr><td colspan="2" class="td27">推荐人描述</td></tr>
        <tr class="noborder"><td class="vtop rowform">
        <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="description" cols="50" class="tarea"><!--{$recommender.description}--></textarea></td><td class="vtop tips2">请输入推荐人的描述</td></tr>
        <tr><td colspan="15">
        <div class="fixsel">
        <input type="hidden" name="action" value="post" />
        <input type="hidden" name="id" value="<!--{$recommender.id}-->" />
        <input type="submit" class="btn" name="submit" value="提交" />&nbsp;&nbsp;
        <input type="button" class="btn" name="return" value="返回" onClick="returnIndex();" />
        </div></td></tr>
    </table>
</form>
<script language="javascript" type="text/javascript"><!--
    function returnIndex()
    {
        window.location.href= iwbRoot + 'admin/recommend/index';
    }
//--></script>
<!--{include file="admin/footer.tpl"}-->
