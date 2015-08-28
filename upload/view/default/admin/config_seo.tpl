<!--{include file="admin/header.tpl"}-->
<div class="itemtitle"><h3>SEO 设置</h3></div>
<form action="/admin/config/seo" method="post">
    <table class="tb tb2">
        <tr>
            <td class="td27" colspan="2">URL 静态化：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <!--{TO->cfg key="seo" group="basic" assign="_seo_url_static" default="1"}-->
                <label><input type="radio" class="radio" name="config[basic][seo]"<!--{if $_seo_url_static == 1}--> checked="checked"<!--{/if}--> value="1" onclick="showObj('seoext');" />开启</label>
                <label><input type="radio" class="radio" name="config[basic][seo]"<!--{if $_seo_url_static == 0}--> checked="checked"<!--{/if}--> value="0" onclick="hideObj('seoext');" />关闭</label>
            </td>
            <td class="vtop tips2">开启之后，URL地址将变短，需服务器支持rewrite，rewrite规则请参考iWeibo使用说明书</td>
        </tr>
        <tbody id="seoext" <!--{if $_seo_url_static == 0}-->style="display: none;"<!--{/if}-->>
               <tr><td colspan="2" class="td27">URL 扩展名：</td></tr>
            <tr class="noborder">
                <td class="vtop rowform">
                    <!--{TO->cfg key="seoext" group="basic" default=".html" assign=_ext}-->
                    <!--{html_options name="config[basic][seoext]" options=$exts selected=$_ext}-->
                </td>
                <td class="vtop tips2">URL扩展名，如.html，默认选择无后缀</td>
            </tr>
        </tbody>
        <tr>
            <td class="td27" colspan="2">标题附加字：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="config[basic][seo_title]" id="seo_title" cols="50" class="tarea"><!--{TO->cfg key="seo_title" group="basic" default=""}--></textarea>
            </td>
            <td class="vtop tips2">用于显示网站首页标题部分，如果有多个关键词，建议用“|”、“,”（不含引号）隔开</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">Meta Keywords：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="config[basic][seo_keywords]" id="seo_keywords" cols="50" class="tarea"><!--{TO->cfg key="seo_keywords" group="basic" default=""}--></textarea>
            </td>
            <td class="vtop tips2">用于显示网站首页meta部分，如果有多个关键词，建议用“|”、“,”（不含引号）隔开</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">Meta Description：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="config[basic][seo_description]" id="seo_description" cols="50" class="tarea"><!--{TO->cfg key="seo_description" group="basic" default=""}--></textarea>
            </td>
            <td class="vtop tips2">用于显示网站首页meta部分，如果有多个关键词，建议用“|”、“,”（不含引号）隔开</td>
        </tr>
    </table>
    <div class="opt"><input type="submit" name="submit" value="提交" class="btn"/></div>
</form>
<!--{include file="admin/footer.tpl"}-->