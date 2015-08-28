<!--{include file="admin/header.tpl"}-->
<style type="text/css">
    .mod_validate td{background: #FFFFFF !important; }
    .mod_delete td{background: #FFEBE7 !important; }
    .mod_ignore td{background: #EEEEEE !important; }
</style>
<script language="javascript">
function _onformSubmit(){
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
<div class="floattop"><div class="itemtitle">
        <h3>微博审核</h3>
        <ul class="tab1">
            <li<!--{if $state == 0}--> class="current"<!--{/if}-->><a href="/admin/blog/censor/state/0"><span>待审核</span></a></li>
            <li<!--{if $state == -1}--> class="current"<!--{/if}-->><a href="/admin/blog/censor/state/-1"><span>已屏蔽</span></a></li>
            <li<!--{if $state == 1}--> class="current"<!--{/if}-->><a href="/admin/blog/censor/state/1"><span>已通过</span></a></li>
        </ul>
    </div>
</div>
<div style="clear:both;"></div>
<div class="cuspages right">
    <script type="text/javascript" src="/resource/admin/js/calendar.js"></script>
    <form method="post" action="/admin/blog/censor" id="blog_form">
        <input type='hidden' name='state' value="<!--{$state}-->" />
        <select name="type">
            <option value="" >全部类型</option>
            <!--{foreach key="key" item="_type" from=$blog_type}-->
            <option value="<!--{$key}-->" <!--{if isset($type) && $type== $key}--> selected="selected"<!--{/if}-->><!--{$_type}--></option>
            <!--{/foreach}-->
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
        <input class="btn" type="submit" onclick="_onformSubmit()" name="searchsubmit"/>
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
                <a href="#" target="_blank"><!--{$blog.account}--></a>
            </td>
            <td class="td25"><!--{$blog_type[$blog.type]}--></td>
            <td>
                <div style="overflow: hidden; width: 580px;">
                    <p><!--{$blog.content}--></p>
                </div>
            </td>
            <td class="td25"><!--{$blog.dateline|idate:"m月d日 H:i"}--></td>
            <td class="td25"><!--{if $blog.visible == 0}-->是<!--{else}-->否<!--{/if}--></td>
            <td class="td25"><!--{$blog_state[$blog.state]}--></td>
        </tr>
        <!--{/foreach}-->
        <tr><td colspan="7">
                <div class="cuspages right"><!--{include file="common/multipage.tpl"}--></div>
                <div class="fixsel">
                    <input type="checkbox" name="chkall" id="chkall" onclick="checkAll('prefix', this.form, 'opentid')" />
                    <label for="chkall">全选</label>
                    <!--{if $state == 1 || $state == 0}--><input id="submit_maskall" class="btn" type="submit" value="屏蔽" name="maskall" /><!--{/if}-->
                    <!--{if $state == -1 || $state == 0}--><input id="submit_unmaskall" class="btn" type="submit" value="通过" name="unmaskall" /><!--{/if}-->
                </div>
            </td></tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->