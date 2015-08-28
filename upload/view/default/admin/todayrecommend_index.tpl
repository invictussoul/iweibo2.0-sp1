<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>每日推荐</h3>
        <ul class="tab1">
            <li<!--{if 'index' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/todayrecommend/index"><span>管理</span></a></li>
            <li<!--{if 'add' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/todayrecommend/add"><span>添加</span></a></li>
        </ul>
    </div>
</div>
<form name="cpform" method="post" action="/admin/todayrecommend/index" id="cpform" >
    <table class="tb tb2 ">
        <tr class="header">
            <th width="45">删除</th>
            <th>显示顺序</th>
            <th>内容</th>
            <th></th>
        </tr>
        <!--{foreach key=key item=todayRecommend from=$todayRecommends}-->
        <tr class="hover">
            <td><input class="checkbox" type="checkbox" name="delete[]" value="<!--{$todayRecommend.id}-->" ></td>
            <td width="115"><input type="text" class="txt" name="displayorder[<!--{$todayRecommend.id}-->]" value="<!--{$todayRecommend.displayorder}-->" size="2" ></td>
            <td><!--{$todayRecommend.content}--></td>
            <td><a href="/admin/todayrecommend/edit/id/<!--{$todayRecommend.id}-->" >编辑</a></td>
        </tr>
        <!--{/foreach}-->
        <tr>
            <td class="td25"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'delete')" /></td>
            <td colspan="15"><div class="fixsel"><input type="submit" class="btn" name="todayRecommendsubmit" value="提交" /></div></td>
        </tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->