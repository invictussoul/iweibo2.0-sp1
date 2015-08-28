<!--{include file="admin/header.tpl"}-->
<script language="javascript">
function _onBlogformSubmit(){
if($("#blog_form > input[name='keyword']").val() == '请输入内容关键字') {
   $("#blog_form > input[name='keyword']").val('');
}
if($("#blog_form > input[name='nickname']").val() == '请输入作者昵称') {
   $("#blog_form > input[name='nickname']").val('');
}
if($("#blog_form > input[name='starttime']").val() == '起始时间') {
   $("#blog_form > input[name='starttime']").val('');
}
if($("#blog_form > input[name='endtime']").val() == '结束时间') {
   $("#blog_form > input[name='endtime']").val('');
}
}
</script>
<h3>微博管理</h3>
<div class="cuspages right">
    <script type="text/javascript" src="/resource/admin/js/calendar.js"></script>
    <form method="post" action="/admin/blog/index" id="blog_form">
        <select name="type">
            <option value="" >全部类型</option>
            <!--{foreach key="key" item="_type" from=$blog_type}-->
            <option value="<!--{$key}-->" <!--{if isset($type) && $type== $key}--> selected="selected"<!--{/if}-->><!--{$_type}--></option>
            <!--{/foreach}-->
        </select>
        <select name="visible">
            <option value="" >全部内容</option>
            <option value="1" <!--{if isset($visible) && $visible== 1}--> selected="selected"<!--{/if}-->>正常内容</option>
            <option value="0" <!--{if isset($visible) && $visible== 0}--> selected="selected"<!--{/if}-->>屏蔽内容</option>
        </select>
        <input id="input_keywords" type="text" name="keyword" value="<!--{if $keyword}--><!--{$keyword}--><!--{else}-->请输入内容关键字<!--{/if}-->" class="txt" onclick="this.value=''" size="15"/>
        <input type="text" name="nickname" value="<!--{if $nickname}--><!--{$nickname}--><!--{else}-->请输入作者昵称<!--{/if}-->" class="txt" onclick="this.value=''" size="15"/>
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
        <input class="btn" type="submit" onclick="_onBlogformSubmit()" name="searchsubmit"/>
    </form>
</div>
<form method="post" action="/admin/blog/index" name="cpform" method="post">
    <table class="tb tb2">
        <tr class="header">
            <th></th>
            <th>作者</th>
            <th>类型</th>
            <th>内容</th>
            <th>发表时间</th>
            <th>屏蔽</th>
            <th>审核状态</th>
        </tr>
        <!--{foreach key="key" item="blog" from=$blogs}-->
        <tr class="hover">
            <td class="td25">
                <input type="checkbox" name="opentid[]" value="<!--{$blog.opentid}-->">
            </td>
            <td class="td25">
                <a href="/u/<!--{$blog.account}-->" target="_blank"><!--{$blog.account}--></a>
            </td>
            <td class="td26"><!--{$blog_type[$blog.type]}--></td>
            <td>
                <div style="overflow: hidden; width: 300px;">
                    <p><!--{$blog.content}--></p>
                </div>
            </td>
            <td class="td26"><!--{$blog.dateline|idate:"m月d日 H:i"}--></td>
            <td class="td25" align="center"><!--{if $blog.visible == 0}-->是<!--{else}-->否<!--{/if}--></td>
            <td class="td26" align="center"><!--{$blog_state[$blog.state]}--></td>
        </tr>
        <!--{/foreach}-->
        <tr><td colspan="6">
                <div class="cuspages right"><!--{include file="common/multipage.tpl"}--></div>
                <div class="fixsel">
                    <input type="checkbox" name="chkall" id="chkall" onclick="checkAll('prefix', this.form, 'opentid')" />
                    <label for="chkall">全选</label>
                    <input id="submit_maskall" class="btn" type="submit" value="屏蔽" name="maskall" />
                    <input id="submit_unmaskall" class="btn" type="submit" value="取消屏蔽" name="unmaskall" />
                </div>
            </td></tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->