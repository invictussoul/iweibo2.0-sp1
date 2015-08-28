<!doctype html>
<html>
    <head>
        <!--{TO->cfg key="seo_title" group="basic" default="" assign="_title"}-->
        <title><!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--> - 注册 <!--{if $_title}--> -  <!--{$_title}--><!--{/if}--> - Powered by iWeibo</title>
        <meta name="Keywords" content="<!--{TO->cfg key="seo_keywords" group="basic" default=""}-->" />
        <meta name="Description" content="<!--{TO->cfg key="seo_description" group="basic" default=""}-->" />
        <!--{include file="common/style.tpl"}-->
    </head>
    <div class="wrapper2 banner"><img src="/resource/images/banner2.jpg"/></div>
    <div class="wrapper2 whitebg">
        <div class="wrapper2 whitebg">
            <div class="result" style="padding: 30px 20px;">
                <h2>你的位置 >> 介绍页</h2>
                <!--{TO->hack name="summary"}-->
                <br />
                <div><a href="<!--{$pathRoot}-->plugin/example" class="bindingbtn">回到首页</a></div>
            </div>
        </div>
    </div>
    <!--{include file="common/footer.tpl"}-->
</body>
</html>