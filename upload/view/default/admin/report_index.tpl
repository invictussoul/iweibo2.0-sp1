<!--{include file="admin/header.tpl"}-->
<script language="javascript">
function _onReportformSubmit(){
if($("#report_form > input[name='keyword']").val() == '请输入举报理由') {
   $("#report_form > input[name='keyword']").val('');
}
if($("#report_form > input[name='username']").val() == '请输入举报人用户名') {
   $("#report_form > input[name='username']").val('');
}
if($("#report_form > input[name='starttime']").val() == '起始时间') {
   $("#report_form > input[name='starttime']").val('');
}
if($("#report_form > input[name='endtime']").val() == '结束时间') {
   $("#report_form > input[name='endtime']").val('');
}
}
</script>
<h3>举报管理</h3>
<div class="cuspages right">
    <script type="text/javascript" src="/resource/admin/js/calendar.js"></script>
    <form method="post" action="/admin/report/index" id="report_form">
        <select name="state">
            <option value="" >全部内容</option>
            <!--{foreach key="key" item="_state" from=$report_state}-->
            <option value="<!--{$key}-->" <!--{if isset($state) && $state== $key}--> selected="selected"<!--{/if}-->><!--{$_state}--></option>
            <!--{/foreach}-->
        </select>
        <input id="input_keywords" type="text" name="keyword" value="<!--{if $keyword}--><!--{$keyword}--><!--{else}-->请输入举报理由<!--{/if}-->" class="txt" onclick="this.value=''" size="15"/>
        <input id="input_usernames" type="text" name="username" value="<!--{if $username}--><!--{$username}--><!--{else}-->请输入举报人用户名<!--{/if}-->" class="txt" onclick="this.value=''" size="15"/>
        <input class="txt" type="text" onclick="showcalendar(event, this)" name="starttime" value="<!--{if $starttime}--><!--{$starttime}--><!--{else}-->起始时间<!--{/if}-->" size="10"/>
        <input class="txt" type="text" onclick="showcalendar(event, this)" name="endtime" value="<!--{if $endtime}--><!--{$endtime}--><!--{else}-->结束时间<!--{/if}-->" size="10"/>
        <select name="orderby">
            <option value="">默认排序</option>
            <option value="dateline" <!--{if isset($orderby) && $orderby== 'dateline'}--> selected="selected"<!--{/if}-->>发表时间</option>
            <option value="account" <!--{if isset($orderby) && $orderby== 'account'}--> selected="selected"<!--{/if}-->>作者</option>
        </select>
        <select name="ordersc">
            <option value="desc" <!--{if isset($ordersc) && $ordersc== 'desc'}--> selected="selected"<!--{/if}-->>递减</option>
            <option value="asc"  <!--{if isset($ordersc) && $ordersc== 'asc'}--> selected="selected"<!--{/if}-->>递增</option>
        </select>
        <input class="btn" type="submit" onclick="_onReportformSubmit()" name="searchsubmit"/>
    </form>
</div>
<form method="post" action="/admin/report/index" name="cpform" method="post">
    <table class="tb tb2">
        <tr class="header">
            <th></th>
            <th>举报人</th>
            <th>类型</th>
            <th>举报理由</th>
            <th>目标用户</th>
            <th>目标微博</th>
            <th>举报时间</th>
            <th>状态</th>
        </tr>
        <!--{foreach key="key" item="report" from=$reports}-->
        <tr class="hover">
            <td class="td25">
                <input type="checkbox" name="id[]" value="<!--{$report.id}-->" class="checkbox">
            </td>
            <td class="td21">
                <a href="/u/<!--{$report.name}-->" target="_blank"><!--{$report.name}--></a>
            </td>
            <td class="td26">
                <!--{$report_type[$report.type]}-->
            </td>
            <td class="td21">
                <p><!--{$report.reason}--></p>
            </td>
            <td class="td26">
                <a href="/u/<!--{$report.targetaccount}-->" target="_blank"><!--{$report.targetaccount}--></a>
            </td>
            <td class="td26">
                <div style="overflow: hidden; width: 180px;">
               <!--{$report.blogcontent}-->
                </div>
            </td>
            <td class="td21"><!--{$report.time|idate:"m月d日 H:i"}--></td>
            <td class="td25"><!--{$report_state[$report.state]}--></td>
        </tr>
        <!--{/foreach}-->
        <tr><td colspan="6">
                <div class="cuspages right"><!--{include file="common/multipage.tpl"}--></div>
                <div class="fixsel">
                    <input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'id')" />
                    <label for="chkall">全选</label>
                    <input id="submit_make_checked_all" class="btn" type="submit" value="标记已处理" name="make_checked_all" />
                    <input id="submit_delete_all" class="btn" type="submit" value="删除" name="delete_all" />
                </div>
                </div>
            </td></tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->