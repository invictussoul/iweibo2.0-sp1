<!doctype html>
<html>
    <head>
    <title>举报系统 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
    </head>
<body class="whitebg">
<div class="report_tit"><strong>举报平台</strong></div><br/>
<form name="form1" method="post" action="/t/illegalreport">
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="0" vspace="10">
      <tr>
        <td height="60" align="center" bgcolor="#D3F0FF"><h3>
    <!--{$site_name}--> 致力于为用户提供健康和谐的网络交流平台
    </h3></td>
  </tr>
      <tr>
        <td height="30" align="left" class="gray">您举报的是<!--{$report.nick}-->(@<!--{$report.name}-->) <!--{if $report.head}--><!--{/if}--><!--{if $report.text}-->  的微博<!--{/if}--></td>
  </tr>
      <tr>
        <td align="left">
        <table cellpadding="4">
        <tr>
        <td>
        <span class="userhead"><img src="<!--{$report.head}-->"  onerror="this.src=iwbResourceRoot+'resource/images/default_head_50.jpg'" /></span>
        </td><td valign="top"><strong><!--{$report.nick}--></strong>：<!--{if $report.text}--><!--{$report.text}--><!--{/if}-->
        </td>
        </tr>
        </table>
        </td>
  </tr>
      <tr>
        <td height="45" align="left">举报类型：<br/>
        <!--{foreach from=$reporttype item=it key=key}-->
        <input type="radio" name="type" value="<!--{$key}-->" id="type_<!--{$key}-->" <!--{if $key == 5}-->checked="checked"<!--{/if}-->><label class="gray" for="type_<!--{$key}-->"><!--{$it}--></label>
    <!--{/foreach}-->
        </td>
  </tr>
      <tr>
        <td height="55" align="left">举报说明(可选):<br/>
        <input type="text" class="report_txt gray" name="content" maxlength="55" placeholder="您可以详细描述恶意行为（如图片中的不良信息选填）"
        onfocus="this.value=this.value==this.getAttribute('placeholder')?'':this.value;this.style.color='#333'; " onblur="this.value=this.value==''?this.getAttribute('placeholder'):this.value;this.style.color='gray';" value="您可以详细描述恶意行为（如图片中的不良信息选填）"
        />
        </td>
  </tr>
      <tr>
        <td height="50" align="center">
        <input name="tid" type="hidden" value="<!--{$report.id}-->">
        <input name="name" type="hidden" value="<!--{$report.name}-->">
        <input name="button" type="submit" class="save" id="button" value="提交"> &nbsp;&nbsp;
        <input name="button2" type="reset" class="cancel" id="button2" value="取消" onclick="parent.IWB_DIALOG._disposeAllDialog();"></td>
  </tr>
</table>
</form>
</body>
</html>