<!--{include file="admin/header.tpl"}-->
<style>
.erroDiv {padding:5px; background:#FFFF80; border:1px solid #C4C43C;}
.errorMessage {color:red; font-weight:bold; font-size:12px; line-height:18px;}
/*.tip {color:green;}*/
.warningTip {color:red; font-weight:bold; font-size:13px;}
</style>
<div class="itemtitle"><h3>【名人推荐】管理</h3></div>
<form action="<!--{$baseUrl}-->admin/viprecommend/index" method="post">
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
            <span class="tip">每行一个微博账号，空格和重复账号会被自动过滤。<span class="warningTip">(请确定您添加的微博账号正确而且存在)</span></span>
            </td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform" height="320">
                <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="config[component.viprecommend][people]" id="site_tj" cols="50" style="height:100%;width:200px;"><!--{if $accounts}--><!--{$accounts}--><!--{else}--><!--{TO->cfg key="people" group="component.viprecommend" default=""}--><!--{/if}--></textarea>
            </td>
            <td class="vtop tips2"></td>
        </tr>
    </table>
    <div class="opt">&nbsp;&nbsp;
        <input type="hidden" name="action" value="post" class="btn" tabindex="3" />
        <input type="submit" name="editsubmit" value="提交" class="btn" tabindex="3" />
        &nbsp;<span class="warningTip">(提示: 【今日名人】组件所显示的用户，将随机从上面的客户显示。)</span>
    </div>
</form>
<!--{include file="admin/footer.tpl"}-->
