<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>【热门话题】管理</h3>
        <ul class="tab1">
            <li<!--{if 'index' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/hottopic/index"><span>管理</span></a></li>
            <li<!--{if 'add' == $_actionName}--> class="current"<!--{/if}-->><a href="/admin/hottopic/add"><span>添加</span></a></li>
        </ul>
    </div>
</div>
<form name="cpform" method="post" action="/admin/hottopic/delete" id="cpform" onSubmit="if(!confirm('您确定删除所选的热点话题吗?'))return false;">
    <table class="tb tb2 ">
        <tr class="header">
            <th width="45">删除</th>
            <th width="45">话题ID</th>
            <th>话题名字</th>
            <th>话题描述</th>
            <th>话题图片链接(50x50尺寸)</th>
            <th>话题图片链接2(100x100尺寸)</th>
            <th></th>
        </tr>
        <!--{foreach key=key item=hotTopic from=$hotTopics}-->
        <tr class="hover">
            <td class="td25"><input class="checkbox" type="checkbox" name="delete[]" value="<!--{$hotTopic.id}-->" ></td>
            <td><!--{$hotTopic.id}--></td>
            <td class="td28"><!--{$hotTopic.name}--></td>
            <td><span title='<!--{$hotTopic.description|escape:html}-->'><!--{$hotTopic.description|itruncate:60:'...'}--></span></td>
            <td><span title="<!--{$hotTopic.picture}-->"><!--{$hotTopic.picture|itruncate:40:'...'}--></span></td>
            <td><span title="<!--{$hotTopic.picture2}-->"><!--{$hotTopic.picture2|itruncate:40:'...'}--></span></td>
            <td><a href="/admin/hottopic/edit/id/<!--{$hotTopic.id}-->" >编辑</a></td>
        </tr>
        <!--{/foreach}-->
        <tr>
            <td class="td25"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'delete')" /></td>
            <td colspan="15"><div class="fixsel"><input type="submit" class="btn" name="noticesubmit" value="删除" /></div></td>
        </tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->
