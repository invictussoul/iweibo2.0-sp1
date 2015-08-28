<!--{include file="admin/header.tpl"}-->
<script type="text/javascript" src="/resource/admin/js/calendar.js"></script>
<script language="javascript">
function _onBlogformSubmit(){
if($("#topic_form > input[name='keyword']").val() == '请输入话题关键字') {
   $("#topic_form > input[name='keyword']").val('');
}
}
</script>
<div class="floattop">
    <div class="itemtitle">
        <h3>上墙</h3>
        <ul class="tab1">
            <li <!--{if $_actionName == 'index'}-->class="current"<!--{/if}-->><a href="/admin/wall/index"><span>上墙话题列表</span></a></li>
            <li <!--{if $_actionName == 'censor'}-->class="current"<!--{/if}-->><a href="/admin/wall/censor"><span>审核上墙消息</span></a></li>
            <li <!--{if $_actionName == 'add'}-->class="current"<!--{/if}-->><a href="/admin/wall/add"><span>添加上墙话题</span></a></li>
        </ul>
    </div>
</div>
<form name="cpform" method="post" action="/admin/wall/<!--{$_actionName}-->" id="cpform" >
    <!--{if 'edit' == $_actionName}--><input type="hidden" name="tid" value="<!--{$wall.tid}-->" /><!--{/if}-->
    <table class="tb tb2 ">
        <tr><th colspan="15" class="partition"><!--{if 'add' == $_actionName}-->添加<!--{else}-->编辑<!--{/if}--></th></tr>
        <tr><td colspan="2" class="td27">话题名称</td></tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <!--{if 'edit' == $_actionName}--><!--{$wall.title}--><!--{/if}-->
                <!--{if 'add' == $_actionName}--><input name="title" value="<!--{$wall.title}-->" type="text" class="txt"/><!--{/if}-->
            </td>
            <td class="vtop tips2"><!--{if 'add' == $_actionName}-->添加以后不可修改<!--{/if}--></td>
        </tr>
        <tr><td colspan="2" class="td27"></td></tr>
        <tr>
            <td class="td27" colspan="2">访谈内容先审后发:</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <label><input type="radio" class="radio" name="wallcensor"<!--{if $wall.wallcensor == 1}--> checked="checked"<!--{/if}--> value="1" />是</label>
                <label><input type="radio" class="radio" name="wallcensor"<!--{if $wall.wallcensor == 0}--> checked="checked"<!--{/if}--> value="0" />否</label>
            </td>
            <td class="vtop tips2">本地发表的内容全部需要通过审核后才能显示</td>
        </tr>
        <tr><td colspan="2" class="td27">开始时间</td></tr>
        <tr class="noborder">
            <td>
                <input type="text" class="txt" name="wallstarttime_Date" value="<!--{$wall.wallstarttime|date_format:"%Y-%m-%d"}-->" onclick="showcalendar(event, this)">
                 <!--{html_select_time use_24_hours=true time=$wall.wallstarttime prefix='wallstarttime_'}-->
            </td><td class="vtop tips2"></td></tr>
        <tr><td colspan="2" class="td27">结束时间</td></tr>
        <tr class="noborder">
            <td>
                 <input type="text" class="txt" name="wallendtime_Date" value="<!--{$wall.wallendtime|date_format:"%Y-%m-%d"}-->" onclick="showcalendar(event, this)">
                 <!--{html_select_time use_24_hours=true time=$wall.wallendtime prefix='wallendtime_'}-->
            </td><td class="vtop tips2"></td></tr>
        <tr><td colspan="15"><div class="fixsel"><input type="submit" class="btn" name="submit" value="提交" /></div></td></tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->