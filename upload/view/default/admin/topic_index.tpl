<!--{include file="admin/header.tpl"}-->
<script language="javascript">
function _onBlogformSubmit(){
if($("#topic_form > input[name='keyword']").val() == '请输入话题关键字') {
   $("#topic_form > input[name='keyword']").val('');
}
}
</script>
<h3>话题管理</h3>
<div class="cuspages right">
    <script type="text/javascript" src="/resource/admin/js/calendar.js"></script>
    <form method="post" action="/admin/topic/index" id="topic_form">
        <select name="state">
            <option value="" >全部内容</option>
            <option value="0" <!--{if isset($state) && $state== 0}--> selected="selected"<!--{/if}-->>开放</option>
            <option value="1" <!--{if isset($state) && $state== 1}--> selected="selected"<!--{/if}-->>锁定</option>
        </select>
        <input id="input_keywords" type="text" name="keyword" value="<!--{if $keyword}--><!--{$keyword}--><!--{else}-->请输入话题关键字<!--{/if}-->" class="txt" onclick="this.value=''" size="15"/>
        <select name="orderby">
            <option value="">默认排序</option>
            <option value="mblogs" <!--{if isset($orderby) && $orderby== 'mblogs'}--> selected="selected"<!--{/if}-->>微博数</option>
        </select>
        <select name="ordersc">
            <option value="desc" <!--{if isset($ordersc) && $ordersc== 'desc'}--> selected="selected"<!--{/if}-->>递减</option>
            <option value="asc"  <!--{if isset($ordersc) && $ordersc== 'asc'}--> selected="selected"<!--{/if}-->>递增</option>
        </select>
        <input class="btn" type="submit" onclick="_onBlogformSubmit()" name="searchsubmit"/>
    </form>
</div>
<form method="post" action="/admin/topic/index" name="cpform" method="post">
    <table class="tb tb2">
        <tr class="header">
            <th></th>
            <th>话题名称</th>
            <th>微博数</th>
            <th>状态</th>
        </tr>
        <!--{foreach key="key" item="topic" from=$topics}-->
        <tr class="hover">
            <td class="td25">
                <input type="checkbox" name="tid[]" value="<!--{$topic.tid}-->" class="checkbox">
            </td>
            <td class="td25">
                <div style="overflow: hidden; width: 580px;">
                <a href="/topic/show/k/<!--{$topic.title}-->" target="_blank"><!--{$topic.title}--></a>
                </div>
            </td>
            <td class="td25"><!--{$topic.mblogs}--></td>
            <td class="td25"><!--{if $topic.state == 0}-->开放<!--{else}-->锁定<!--{/if}--></td>
        </tr>
        <!--{/foreach}-->
        <tr><td colspan="6">
                <div class="cuspages right"><!--{include file="common/multipage.tpl"}--></div>
                <div class="fixsel">
                    <input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'tid')" />
                    <label for="chkall">全选</label>
                    <input id="submit_lockall" class="btn" type="submit" value="锁定" name="lockall" />
                    <input id="submit_unlockall" class="btn" type="submit" value="开放" name="unlockall" />
                </div>
            </td></tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->