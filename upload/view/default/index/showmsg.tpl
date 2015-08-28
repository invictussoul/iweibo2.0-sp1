<!doctype html>
<html>
    <head>
        <!--{TO->cfg key="seo_title" group="basic" default="" assign="_title"}-->
        <title><!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--> - 登录 <!--{if $_title}--> -  <!--{$_title}--><!--{/if}--> - Powered by iWeibo</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="Keywords" content="<!--{TO->cfg key="seo_keywords" group="basic" default=""}-->" />
        <meta name="Description" content="<!--{TO->cfg key="seo_description" group="basic" default=""}-->" />
        <link rel="shortcut icon" href='/favicon.ico'/>
        <!--{include file="common/style.tpl"}-->
    </head>
    <body>
<div class="wrapper2 banner" style="display:block;"><img src="/resource/images/banner.jpg"/></div>
<div class="wrapper2 content whitebg" style="display:block;">
<!-- 自动注册页面-->
<!--{if $auto}-->
<div class="infobox"  >
    <div class="infoicon fleft">
	
		<label class="result_ok"></label>
	</div>
	<div class="infoline fleft"></div>
   
    <div class="postbox fleft">
	    <table>
			<tr><th align="right" height="30" width="150">系统为您创建的帐号为：</th><td><font color="#3D91CC"><!--{$username}--></font></td></tr>
			<tr><th align="right" height="30">密码为：</th><td><font color="#3D91CC"><!--{$pwd}--></font></td></tr>
			<tr><td align="center" height="30" colspan="2"><a href="<!--{$gourl}-->" class="button button_blue">进入我的微博</a></td></tr>
			<tr><td align="center" colspan="2"><a href="<!--{$downUrl}-->" class="gray"  target='_blank'>保存帐号密码至本地</a></td></tr>
		</table>
    </div>

</div>
 <!--{else}-->
<div class="infobox" style="display:block">
    <div class="infoicon fleft">
		<label class="result_err"></label>
	</div>
	<div class="infoline fleft"></div>
    <div class="postbox fleft">
    <h1><!--{$msg}--></h1>
        <!--{if $button}-->
	    <!--{if $btntext}-->
		<p class="marginbot"><input type="button" value="<!--{$btntext}-->" onClick="window.location.href = '<!--{$seogourl}-->'" class="button button_blue"/></p>
	    <!--{else}-->
		<p class="marginbot"><input type="button" value="点击返回" onClick="window.location.href = '<!--{$seogourl}-->'" class="button button_blue"/></p>
	    <!--{/if}-->

        <!--{else}-->
            <!--<meta http-equiv="refresh" content="<!--{$time}-->; url=<!--{$seogourl}-->" />-->
            <p class="marginbot"><a href="<!--{$gourl}-->" class="button button_blue">点击返回</a></p>
        <!--{/if}-->
    </div>
</div>
 <!--{/if}-->
</div>
<!--{include file="common/footer.tpl"}-->