
<div class="wrapper footer copyright">
    <!--底部导航 start-->
    <!--{foreach from=$footNav item=nav}-->
    <a href="<!--{$nav.link}-->" <!--{if $nav.newwindow == 1}-->target="_blank"<!--{/if}-->><!--{$nav.name}--></a>
    <!--{/foreach}-->
    <!--底部导航 end-->
    <br />
    <!--备案号 start--><!--{TO->cfg key="site_beian" group="basic" default="" assign=icpcode}-->
<!--{if $icpcode}-->备案号：<a href="http://www.miitbeian.gov.cn/" target="_blank"><!--{$icpcode}--></a><br/><!--{/if}-->
    <!--备案号 end-->
    Powered by iWeibo 2.0 &copy; 1998-2011 Tencent.
    <!--统计代码 start--><!--{TO->cfg key="site_tj" group="basic" default=""}--><!--统计代码 end-->
</div>
<script src="/resource/js/thirdparty/jquery/jquery-all.js"></script>
<script src="/resource/js/friend.js"></script>
<script src="/resource/js/iwbFramework/iwb.js"></script>