<!--{include file="admin/header.tpl"}-->
<style>
.erroDiv {padding:5px; background:#FFFF80; border:1px solid #C4C43C;}
.errorMessage {color:red; font-weight:bold; font-size:12px; line-height:18px;}
.tip {color:green;}
</style>
<div class="itemtitle"><h3>【排行榜】管理</h3></div>
<form action="<!--{$baseUrl}-->admin/ranking/index" method="post">
    <!--{if $errorMessage}-->
    <table class="tb tb2 " id="tips">
        <tr>
            <td><div class="erroDiv"><span class="errorMessage">
            <!--{foreach key=key item=error from=$errorMessage}-->
            <!--{$error}--><br />
            <!--{/foreach}-->
            </span></div></td>
        </tr>
    </table>
    <!--{/if}-->
    <table class="tb tb2">
        <tr>
            <td class="td27" colspan="2">
            <span class="tip">每行一个微博账号，空格和重复账号会被自动过滤。</span>
            </td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform" height="320">
                <textarea style="height:100%;width:200px;" rows="6" name="config[component.ranking][people]" id="site_tj" cols="50" class="tarea"><!--{if $accounts}--><!--{$accounts}--><!--{else}--><!--{TO->cfg key="people" group="component.ranking" default=""}--><!--{/if}--></textarea>
            </td>
            <td class="vtop tips2"></td>
        </tr>
    </table>
    <div class="opt">&nbsp;&nbsp;
        <input type="hidden" name="action" value="post" class="btn" tabindex="3" />
        <input type="submit" name="editsubmit" value="提交" class="btn" tabindex="3" />
    </div>
</form>
<!--{include file="admin/footer.tpl"}-->
