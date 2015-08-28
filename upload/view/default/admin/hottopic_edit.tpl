<style>
.erroDiv {padding:5px; background:#FFFF80; border:1px solid #C4C43C;}
.errorMessage {color:red; font-weight:bold; font-size:12px; line-height:18px;}
</style>
<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>编辑热门话题</h3>
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
<form name="cpform" method="post" action="/admin/hottopic/edit" id="cpform" >
    <table class="tb tb2 ">
        <tr><th colspan="15" class="partition">编辑</th></tr>
        <tr><td colspan="2" class="td27">话题名字</td></tr>
        <tr class="noborder"><td class="vtop rowform">
            <input name="name" value="<!--{$hotTopic.name}-->" type="text" class="txt" /></td><td class="vtop tips2"></td></tr>
        <tr><td colspan="2" class="td27">话题描述</td></tr>
        <tr class="noborder"><td class="vtop rowform">
        <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="description" cols="50" class="tarea"><!--{$hotTopic.description}--></textarea></td><td class="vtop tips2">请输入话题的描述</td></tr>
        <tr><td colspan="2" class="td27">话题图片链接<span style="color:red;">(50x50尺寸)</span></td></tr>
        <tr class="noborder"><td class="vtop rowform">
            <input type="text" class="txt" name="picture" style="width:500px" value="<!--{$hotTopic.picture}-->">
        </td><td class="vtop tips2"></td></tr>
        <tr><td colspan="2" class="td27">话题图片链接2<span style="color:red;">(100x100尺寸)</span></td></tr>
        <tr class="noborder"><td class="vtop rowform">
            <input type="text" class="txt" name="picture2" style="width:500px" value="<!--{$hotTopic.picture2}-->">
        </td><td class="vtop tips2"></td></tr>
        <tr><td colspan="15">
        <div class="fixsel">
        <input type="hidden" name="action" value="post" />
        <input type="hidden" name="id" value="<!--{$hotTopic.id}-->" />
        <input type="submit" class="btn" name="submit" value="提交" />&nbsp;&nbsp;
        <input type="button" class="btn" name="return" value="返回" onClick="returnIndex();" />
        </div></td></tr>
    </table>
</form>
<script language="javascript" type="text/javascript"><!--
    function returnIndex()
    {
        window.location.href= iwbRoot + 'admin/hottopic/index';
    }
//--></script>
<!--{include file="admin/footer.tpl"}-->