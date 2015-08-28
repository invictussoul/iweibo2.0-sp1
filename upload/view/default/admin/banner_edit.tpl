<style>
.erroDiv {padding:5px; background:#FFFF80; border:1px solid #C4C43C;}
.errorMessage {color:red; font-weight:bold; font-size:12px; line-height:18px;}
</style>
<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>编辑广告</h3>
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
<form name="cpform" method="post" action="/admin/banner/edit" id="cpform" >
    <table class="tb tb2 ">
        <tr><th colspan="15" class="partition">编辑</th></tr>
        <tr><td colspan="2" class="td27">广告名字</td></tr>
        <tr class="noborder"><td class="vtop rowform">
            <input name="name" value="<!--{$banner.name}-->" type="text" class="txt" /></td><td class="vtop tips2"></td></tr>
        <tr><td colspan="2" class="td27">广告描述</td></tr>
        <tr class="noborder"><td class="vtop rowform">
        <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="description" cols="50" class="tarea"><!--{$banner.description}--></textarea></td><td class="vtop tips2">请输入广告的描述</td></tr>
        <tr>
            <td colspan="2" class="td27">广告链接 <span style="color:red;font-weight:bold;">(请以http://开头)</span></td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform"><input name="url" value="<!--{$banner.url}-->" type="text" class="txt" /></td>
            <td class="vtop tips2"></td>
        </tr>
        <tr>
            <td colspan="2" class="td27">广告图片链接 <span style="color:red;font-weight:bold;">(请确定您添加的图片链接正确.)</span></td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform"><input name="picture" value="<!--{$banner.picture}-->" type="text" class="txt" /></td>
            <td class="vtop tips2"></td>
        </tr>
        <tr>
            <td colspan="2" class="td27">广告开始时间</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <input type="text" class="txt" name="start_time" value='<!--{if $banner.start_time}--><!--{$banner.start_time|date_format:"%Y-%m-%d"}--><!--{/if}-->' onclick="showcalendar(event, this)">
            </td>
            <td class="vtop tips2"></td>
        </tr>
        <tr>
            <td colspan="2" class="td27">广告结束时间</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <input type="text" class="txt" name="end_time" value='<!--{if $banner.end_time}--><!--{$banner.end_time|date_format:"%Y-%m-%d"}--><!--{/if}-->' onclick="showcalendar(event, this)">
            </td>
            <td class="vtop tips2"></td>
        </tr>
        <tr><td colspan="15">
        <div class="fixsel">
        <input type="hidden" name="action" value="post" />
        <input type="hidden" name="id" value="<!--{$banner.id}-->" />
        <input type="submit" class="btn" name="submit" value="提交" />&nbsp;&nbsp;
        <input type="button" class="btn" name="return" value="返回" onClick="returnIndex();" />
        </div></td></tr>
    </table>
</form>
<script language="javascript" type="text/javascript"><!--
    function returnIndex()
    {
        window.location.href= iwbRoot + 'admin/banner/index';
    }
//--></script>
<!--{include file="admin/footer.tpl"}-->
