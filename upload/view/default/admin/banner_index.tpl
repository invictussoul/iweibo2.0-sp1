<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>【广告】管理</h3>
        <ul class="tab1">
            <li<!--{if 'index' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/banner/index"><span>管理</span></a></li>
            <li<!--{if 'add' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/banner/add"><span>添加</span></a></li>
        </ul>
    </div>
</div>
<form name="cpform" method="post" action="/admin/banner/delete" id="cpform" onSubmit="if(!confirm('您确定删除所选的广告设置吗?'))return false;">
    <table class="tb tb2 ">
        <tr class="header">
            <th width="45">删除</th>
            <th>ID</th>
            <th>广告名字</th>
            <th>广告描述</th>
            <th>广告链接</th>
            <th>广告图片链接</th>
            <th>广告开始时间</th>
            <th>广告结束时间</th>
            <th></th>
        </tr>
        <!--{foreach key=key item=banner from=$bannerList}-->
        <tr class="hover">
            <td class="td25"><input class="checkbox" type="checkbox" name="delete[]" value="<!--{$banner.id}-->" ></td>
            <td><!--{$banner.id}--></td>
            <td><!--{$banner.name}--></td>
            <td><span title='<!--{$banner.description|escape:html}-->'><!--{$banner.description|itruncate:150:"..."}--></span></td>
            <td><span title='<!--{$banner.url}-->'><!--{$banner.url|itruncate:45:"..."}--></span></td>
            <td><span title='<!--{$banner.url}-->'><!--{$banner.picture|itruncate:45:"..."}--></span></td>
            <td><!--{$banner.start_time|date_format:"%Y-%m-%d"}--></td>
            <td><!--{$banner.end_time|date_format:"%Y-%m-%d"}--></td>
            <td><a href="/admin/banner/edit/id/<!--{$banner.id}-->" >编辑</a></td>
        </tr>
        <!--{/foreach}-->
        <tr>
            <td class="td25"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'delete')" /></td>
            <td colspan="15"><div class="fixsel"><input type="submit" class="btn" name="noticesubmit" value="删除" /></div></td>
        </tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->
