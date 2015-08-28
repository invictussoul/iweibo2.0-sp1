<!doctype html>
<html>
    <head>
        <!--{TO->cfg key="seo_title" group="basic" default="" assign="_title"}-->
        <title><!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--> - 首页 <!--{if $_title}--> -  <!--{$_title}--><!--{/if}--> - Powered by iWeibo</title>
        <meta name="Keywords" content="<!--{TO->cfg key="seo_keywords" group="basic" default=""}-->" />
        <meta name="Description" content="<!--{TO->cfg key="seo_description" group="basic" default=""}-->" />
        <!--{include file="common/style.tpl"}-->
    </head>
    <div class="wrapper2 banner"><img src="/resource/images/banner2.jpg"/></div>
    <div class="wrapper2 whitebg">
        <div class="wrapper2 whitebg">
            <div class="result">
                <h2>你的位置 >> 首页</h2>
                <div style="margin:0 0 10px 0;"><a href="<!--{$pathRoot}-->plugin/example/summary" class="bindingbtn">到介绍页面去看看</a></div>
                <div><a href="<!--{$pathRoot}-->plugin/example/admin" class="bindingbtn">到管理页面去看看</a></div>
            </div>
        </div>
    </div>
    <!--{include file="common/footer.tpl"}-->
</body>
</html>