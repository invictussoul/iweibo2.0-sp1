<!--{include file="admin/header.tpl"}-->
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
<div class="cuspages right">
    <script type="text/javascript" src="/resource/admin/js/calendar.js"></script>
    <form method="post" action="/admin/wall/index" id="topic_form">
        <input id="input_keywords" type="text" name="keyword" value="<!--{if $keyword}--><!--{$keyword}--><!--{else}-->请输入话题关键字<!--{/if}-->" class="txt" onclick="this.value=''" size="15"/>
        <select name="orderby">
            <option value="">默认排序</option>
            <option value="wallstarttime" <!--{if isset($orderby) && $orderby== 'wallstarttime'}--> selected="selected"<!--{/if}-->>开始时间</option>
            <option value="wallendtime" <!--{if isset($orderby) && $orderby== 'wallendtime'}--> selected="selected"<!--{/if}-->>结束时间</option>
        </select>
        <select name="ordersc">
            <option value="desc" <!--{if isset($ordersc) && $ordersc== 'desc'}--> selected="selected"<!--{/if}-->>递减</option>
            <option value="asc"  <!--{if isset($ordersc) && $ordersc== 'asc'}--> selected="selected"<!--{/if}-->>递增</option>
        </select>
        <input class="btn" type="submit" onclick="_onBlogformSubmit()" name="searchsubmit"/>
    </form>
</div>
<form method="post" action="/admin/wall/index" name="cpform" method="post">
    <table class="tb tb2">
        <tr class="header">
            <th></th>
            <th>话题名称</th>
            <th>话题状态</th>
            <th>上墙状态</th>
            <th>开始时间</th>
            <th>结束时间</th>
            <th>先审后发</th>
            <th></th>
        </tr>
        <!--{foreach key="key" item="topic" from=$topics}-->
        <tr class="hover">
            <td class="td25">
                <input type="checkbox" name="tid[]" value="<!--{$topic.tid}-->" class="checkbox">
            </td>
            <td class="td25">
                <div style="overflow: hidden; width: 220px;">
                <a href="/admin/wall/censor/keyword/<!--{$topic.title|iurlencode}-->"><!--{$topic.title}--></a>
                </div>
            </td>
            <td class="td21"><!--{if $topic.state == 0}-->开放<!--{else}-->锁定<!--{/if}--></td>
            <td class="td21">
                <!--{if $topic.wallstarttime > $smarty.now}-->未开始<!--{/if}-->
                <!--{if $topic.wallendtime < $smarty.now}-->已结束<!--{/if}-->
                <!--{if $topic.wallendtime > $smarty.now && $topic.wallstarttime < $smarty.now}-->进行中<!--{/if}-->
            </td>
            <td class="td21"><!--{$topic.wallstarttime|idate:"m月d日 H:i"}--></td>
            <td class="td21"><!--{$topic.wallendtime|idate:"m月d日 H:i"}--></td>
            <td class="td25"><!--{if $topic.wallcensor == 1}-->是<!--{else}-->否<!--{/if}--></td>
            <td class="td25"><a href="/admin/wall/edit/tid/<!--{$topic.tid}-->">编辑</a></td>
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