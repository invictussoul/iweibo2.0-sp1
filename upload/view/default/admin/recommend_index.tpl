<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>【推荐用户】管理</h3>
        <ul class="tab1">
            <li<!--{if 'index' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/recommend/index"><span>管理</span></a></li>
            <li<!--{if 'add' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/recommend/add"><span>添加</span></a></li>
        </ul>
    </div>
</div>
<form name="cpform" method="post" action="/admin/recommend/delete" id="cpform" onSubmit="if(!confirm('您确定删除所选的推荐收听吗?'))return false;">
    <table class="tb tb2 ">
        <tr class="header">
            <th width="45">删除</th>
            <th>ID</th>
            <th>推荐人账号</th>
            <th>推荐人描述</th>
            <th></th>
        </tr>
        <!--{foreach key=key item=recommender from=$recommendList}-->
        <tr class="hover">
            <td class="td25"><input class="checkbox" type="checkbox" name="delete[]" value="<!--{$recommender.id}-->" ></td>
            <td><!--{$recommender.id}--></td>
            <td><!--{$recommender.account}--></td>
            <td><span title='<!--{$recommender.description|escape:html}-->'><!--{$recommender.description|itruncate:150:"..."}--></span></td>
            <td><a href="/admin/recommend/edit/id/<!--{$recommender.id}-->" >编辑</a></td>
        </tr>
        <!--{/foreach}-->
        <tr>
            <td class="td25"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'delete')" /></td>
            <td colspan="15"><div class="fixsel"><input type="submit" class="btn" name="noticesubmit" value="删除" /></div></td>
        </tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->
