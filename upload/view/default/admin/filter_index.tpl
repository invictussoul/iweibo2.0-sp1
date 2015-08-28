<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>词语过滤</h3>
    </div>
</div>
<form name="cpform" method="post" action="/admin/filter/index" id="cpform" >
    <table class="tb tb2 ">
        <tr class="header">
            <th width="45">删除</th>
            <th>关键词</th>
            <th>级别</th>
        </tr>
        <!--{foreach key=key item=filter from=$filters}-->
        <tr class="hover">
            <td><input class="checkbox" type="checkbox" name="delete[]" value="<!--{$filter.id}-->" ></td>
            <td class="td26"><input type="text" class="txt" size="30" name="word[<!--{$filter.id}-->]" value="<!--{$filter.word}-->" ></td>
            <td class="td26">
                <select name="replacement[<!--{$filter.id}-->]">
                    <!--{html_options options=$stateOption selected=$filter.replacement}-->
                </select>
              </td>
        </tr>
        <!--{/foreach}-->
        <tr class="hover">
            <td class="td25">
                新增
            </td>
            <td class="td26">
                <input type="text" class="txt" size="30" name="newword">
            </td>
            <td class="td26">
                <select name="newreplacement">
                    <option value="1">审核</option>
                    <option value="2">禁止</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="td25">
                <input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'delete')" /><label for="chkall">删除</label>
            </td>
            <td colspan="2">
                <div class="cuspages right"><!--{include file="common/multipage.tpl"}--></div>
                <div class="fixsel">
                    <input type="submit" class="btn" id="submit_censorsubmit" name="censorsubmit" value="提交" />
                </div>
            </td>
        </tr>
    </table>
</form>
<!--{include file="admin/footer.tpl"}-->