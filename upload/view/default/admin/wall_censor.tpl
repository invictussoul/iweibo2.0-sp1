<!--{include file="admin/header.tpl"}-->
<script language="javascript">
function _onBlogformSubmit(){
if($("#blog_form > input[name='keyword']").val() == '请输入话题关键字') {
   $("#blog_form > input[name='keyword']").val('');
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
    <form method="post" action="/admin/wall/censor" id="blog_form">
        <input id="input_keywords" type="text" name="keyword" value="<!--{if $keyword}--><!--{$keyword}--><!--{else}-->请输入话题关键字<!--{/if}-->" class="txt" onclick="this.value=''" size="15"/>
        <input class="btn" type="submit" onclick="_onBlogformSubmit()" name="searchsubmit"/>
    </form>
</div>
<form method="post" action="/admin/wall/censor" name="cpform" method="post">
    <table class="tb tb2">
        <tr class="header">
            <th></th>
            <th>话题</th>
            <th>微博内容</th>
            <th>显示</th>
            <th>作者</th>
            <th>发表时间</th>
            <th>审核时间</th>
        </tr>
        <!--{foreach key="key" item="blog" from=$blogs}-->
        <tr class="hover">
            <td class="td25">
                <input type="checkbox" name="opentid[]" value="<!--{$blog.opentid}-->" class="checkbox">
            </td>
            <td class="td25"><!--{$blog.topicname}--></td>
            <td class="td25">
                <div style="overflow: hidden; width: 580px;">
                <a href="#" target="_blank"><!--{$blog.txt}--></a>
                </div>
            </td>
            <td class="td25" align="center"><!--{if $blog.visible == 1}-->是<!--{else}-->否<!--{/if}--></td>
            <td class="td25"><!--{$blog.account}--></td>
            <td class="td25"><!--{$blog.dateline|idate:"m月d日 H:i"}--></td>
            <td class="td25"><!--{if $blog.censortime}--><!--{$blog.censortime|idate:"m月d日 H:i:s"}--><!--{/if}--></td>
        </tr>
        <!--{/foreach}-->
        <tr><td colspan="6">
                <div class="cuspages right"><!--{include file="common/multipage.tpl"}--></div>
                <div class="fixsel">
                    <input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'tid')" />
                    <label for="chkall">全选</label>
                    <input id="submit_maskall" class="btn" type="submit" value="屏蔽" name="maskall" />
                    <input id="submit_unmaskall" class="btn" type="submit" value="通过" name="unmaskall" />
                </div>
            </td></tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->