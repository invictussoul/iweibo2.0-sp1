<!--{include file="admin/header.tpl"}-->
<script type="text/JavaScript">
var rowtypedata = [
[
  [1, '', 'td25']
, [1,'<input name="newdisplayorder[]" value="" size="3" type="text" class="txt">', 'td25']
, [1, '<input name="newname[]" value="" size="15" type="text" class="txt">', 'td21']
, [1, '<input name="newaction[]" value="" size="15" type="text" class="txt">', 'td21']
, [1, '<input name="newlink[]" value="" size="15" type="text" class="txt"><input type="hidden" name="newparentid[]" value="0" />', 'td26']
, [1, '', 'td25']
, [1, '', 'td25']
, [1, '', 'td25']
]
];
</script>
<div class="floattop">
    <div class="itemtitle">
        <h3>导航管理</h3>
        <ul class="tab1">
             <!--{foreach key="key" item="_nav" from=$nav_type}-->
            <li<!--{if $type == $key}--> class="current"<!--{/if}-->><a href="/admin/nav/index/type/<!--{$key}-->"><span><!--{$_nav}--></span></a></li>
            <!--{/foreach}-->
        </ul>
    </div>
</div>
<form name="cpform" method="post" action="/admin/nav/index" id="cpform" >
    <input type="hidden" name="type" value="<!--{$type}-->" />
    <table class="tb tb2">
        <tr class="header">
            <th width="45"></th>
            <th>顺序</th>
            <th>名称</th>
            <th>Action</th>
            <th>链接</th>
            <th>类型</th>
            <th>新窗口</th>
            <th>可用</th>
        </tr>
        <!--{foreach key=key item=nav from=$navs}-->
        <tr class="hover">
            <td class="td25"><!--{if $nav.system == '0' && !$subnavs[$nav.id]}--><input class="checkbox" type="checkbox" name="delete[]" value="<!--{$nav.id}-->" ><!--{/if}--></td>
            <td class="td25">
                <input type="hidden" class="txt" name="parentid[<!--{$nav.id}-->]" value="<!--{$nav.parentid}-->" size="2" />
                <input type="hidden" class="txt" name="id[<!--{$nav.id}-->]" value="<!--{$nav.id}-->" size="2" />
                <input type="text" class="txt" name="displayorder[<!--{$nav.id}-->]" value="<!--{$nav.displayorder}-->" size="2" ></td>
            <td class="td21">
                <input type="text" class="txt" name="name[<!--{$nav.id}-->]" value="<!--{$nav.name}-->" size="20" >
            </td>
            <td class="td21">
                <!--{if $nav.system == '0'}-->
                    <input type="text" class="txt" name="action[<!--{$nav.id}-->]" value="<!--{$nav.action}-->" size="20" >
                <!--{else}-->
                    <!--{$nav.action}-->
                <!--{/if}-->
            </td>
            <td class="td26">
                <!--{if $nav.system == '0'}-->
                    <input type="text" class="txt" name="action[<!--{$nav.id}-->]" value="<!--{$nav.link}-->" size="20" >
                <!--{else}-->
                    <!--{$nav.link}-->
                <!--{/if}-->
            </td>
            <td class="td25"><!--{if $nav.system == '0'}-->自定义<!--{else}-->内置<!--{/if}--></td>
            <td class="td25">
                <input type="checkbox" value="1" name="newwindow[<!--{$nav.id}-->]" class="checkbox" <!--{if $nav.newwindow == '1'}-->checked="checked"<!--{/if}-->>
            </td>
            <td class="td25">
                <input type="checkbox" value="1" name="useable[<!--{$nav.id}-->]" class="checkbox" <!--{if $nav.useable == '1'}-->checked="checked"<!--{/if}-->>
            </td>
        </tr>
        <!--{/foreach}-->
        <tr>
            <td></td>
            <td colspan="7"><div>
                    <a class="addtr" onclick="addrow(this, 0, 0)" href="###">添加</a>
                </div></td>
        </tr>
        <tr>
            <td class="td25"><input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'delete')" />删除</td>
            <td colspan="7">
                <div class="cuspages right"><!--{include file="common/multipage.tpl"}--></div>
                <div class="fixsel"><input type="submit" class="btn" name="navsubmit" value="提交" /></div>
            </td>
        </tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->