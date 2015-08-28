<!--{include file="admin/header.tpl"}-->
<style>
.erroDiv {padding:5px; background:#FFFF80; border:1px solid #C4C43C;}
.errorMessage {color:red; font-weight:bold; font-size:12px; line-height:18px;}
.tip {color:green;}
</style>
<div class="itemtitle"><h3>微博组件设置(大家都在说)</h3></div>
<form action="/admin/allsaying/index" method="post">
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
            <td class="vtop rowform">
                <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="config[component.allsaying][people]" id="site_tj" cols="50" class="tarea"><!--{if $accounts}--><!--{$accounts}--><!--{else}--><!--{TO->cfg key="people" group="component.allsaying" default=""}--><!--{/if}--></textarea>
            </td>
            <td class="vtop tips2"></td>
        </tr>
    </table>
    <div class="opt">&nbsp;&nbsp;
        <input type="hidden" name="action" value="post" class="btn" tabindex="3"/>
        <input type="submit" name="editsubmit" value="提交" class="btn" tabindex="3"/>
    </div>
</form>
<!--{include file="admin/footer.tpl"}-->