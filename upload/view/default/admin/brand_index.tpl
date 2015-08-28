<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>【推荐品牌】管理</h3>
        <ul class="tab1">
            <li<!--{if 'index' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/brand/index"><span>管理</span></a></li>
            <li<!--{if 'add' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/brand/add"><span>添加</span></a></li>
        </ul>
    </div>
</div>
<form name="cpform" method="post" action="/admin/brand/delete" id="cpform" onSubmit="if(!confirm('您确定删除所选的推荐品牌吗?'))return false;">
    <table class="tb tb2 ">
        <tr class="header">
            <th width="45">删除</th>
            <th>ID</th>
            <th>品牌名字</th>
            <th>品牌描述</th>
            <th>品牌图片链接</th>
            <th>品牌链接</th>
            <th></th>
        </tr>
        <!--{foreach key=key item=brand from=$brandList}-->
        <tr class="hover">
            <td class="td25"><input type="checkbox" name="delete[]" value="<!--{$brand.id}-->" ></td>
            <td><!--{$brand.id}--></td>
            <td><!--{$brand.name}--></td>
            <td><span title='<!--{$brand.description|escape:html}-->'><!--{$brand.description|itruncate:45:"..."}--></span></td>
            <td><span title='<!--{$brand.picture}-->'><!--{$brand.picture|itruncate:45:"..."}--></span></td>
            <td><span title='<!--{$brand.link}-->'><!--{$brand.link|itruncate:45:"..."}--></span></td>
            <td></td>
            <td><a href="/admin/brand/edit/id/<!--{$brand.id}-->" >编辑</a></td>
        </tr>
        <!--{/foreach}-->
        <tr>
            <td class="td25"><input type="checkbox" name="chkall" id="chkall" onclick="checkAll('prefix', this.form, 'delete')" /></td>
            <td colspan="15"><div class="fixsel"><input type="submit" class="btn" name="noticesubmit" value="删除" /></div></td>
        </tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->
