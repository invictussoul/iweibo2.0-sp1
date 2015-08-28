<!--{include file="admin/header.tpl"}-->
<style>
.vipinfo h2{display:inline-block;background: url(<!--{TO->cfg key="resource_path" group="basic" default="/"}-->resource/images/vipbg.gif) 0 -26px no-repeat;*display:inline;zoom:1;}
.vipinfo h2 label {
    max-width: 156px;
    height: 25px;overflow:hidden;
    display: inline-block;*display:inline;zoom:1;
    font-size: 14px;
    text-align: center;
    line-height: 25px;
    background: url(<!--{TO->cfg key="resource_path" group="basic" default="/"}-->resource/images/vipbg.gif) 100% -26px no-repeat;
    color: #f60;
    word-wrap:break-word;
    margin-left:8px;padding-right:8px;
}
</style>
<div class="floattop">
    <div class="itemtitle">
        <h3>认证设置</h3>
    </div>
</div>
<form action="/admin/certification/setup" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="setup" />
    <table class="tb tb2">
        <tr class="noborder">
            <td class="td27" colspan="2">站点认证方式设置：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <ul>
                    <li><label><input id="localauth" name="localauth" value="1" type="checkbox" <!--{if $certInfo.localauth == 1}-->checked<!--{/if}--> /> 使用本站名人认证</label></li>
                    <li><label><input id="platformauth" name="platformauth" value="1" type="checkbox" <!--{if $certInfo.platformauth == 1}-->checked<!--{/if}--> /> 使用腾讯名人认证</label></li>
                </ul>
            </td>
            <td class="vtop tips2">根据你的运营需要，自行选择其中的认证方式，腾讯名人认证将可使用腾讯名人资源</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">认证文字：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform"> <input type="text" name="localauthtext" value="<!--{$certInfo.localauthtext}-->" maxlength="10"/></td>
            <td class="vtop tips2">认证信息将显示在右边栏，请根据自己的网站名称定义，预览效果提交后显示</td>
        </tr>
        <tr>
            <td class="td27" colspan="2">预览效果：</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform"><div class='vipinfo'><h2 class="tencent"><label><!--{$certInfo.localauthtext}--></label></h2></div></td>
            <td class="vtop tips2"></td>
        </tr>
    </table>
    <div class="opt"><input type="submit" name="authsubmit" value="提交" class="btn" tabindex="3" /></div>
</form>
<!--{include file="admin/footer.tpl"}-->