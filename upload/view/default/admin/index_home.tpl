<!--{include file="admin/header.tpl"}-->
<script>
function check_version(){
   var url = "http://i.t.qq.com/v/iwb_update.php?verson=<!--{$version}-->&release=<!--{$release}-->";
   $.get(url,{},function(data){
        if(data.ret == 0){
        $("#remote_news").append(data.info.news);
        if(data.info.forceupdate == true){
            parent.forceupdate();
    }
    }
    },'jsonp');
}

$(document).ready(function(){
  check_version();
});
</script>


<table class="tb tb2 fixpadding">
    <tr><th colspan="15" class="partition">官方信息</th></tr>
    <tr><td>
            <div id="remote_news"></div>
        </td></tr>
</table>
<table class="tb tb2 fixpadding">
    <tr><th colspan="15" class="partition">系统信息</th></tr>
    <tr><td class="vtop td24 lineheight">程序版本</td><td class="lineheight smallfont">iWeibo2.0  <a href="http://open.t.qq.com/apps/iweibo/" class="lightlink smallfont" target="_blank">查看最新版本</a> </td></tr>
    <tr><td class="vtop td24 lineheight">操作系统及 PHP</td><td class="lineheight smallfont"><!--{$serverinfo}--></td></tr>
    <tr><td class="vtop td24 lineheight">服务器软件</td><td class="lineheight smallfont"><!--{$smarty.server.SERVER_SOFTWARE}--></td></tr>
    <tr><td class="vtop td24 lineheight">MySQL 版本</td><td class="lineheight smallfont"><!--{$serverinfo}--></td></tr>
    <tr><td class="vtop td24 lineheight">上传许可</td><td class="lineheight smallfont"><!--{$fileupload}--></td></tr>
    <tr><td class="vtop td24 lineheight">当前数据库占用</td><td class="lineheight smallfont"><!--{$dbsize}--></td></tr>
    <tr><td class="vtop td24 lineheight">主机名</td><td class="lineheight smallfont"><!--{$smarty.server.SERVER_NAME}-->(<!--{$smarty.server.SERVER_ADDR}-->:<!--{$smarty.server.SERVER_PORT}-->)</td></tr>
    <tr><td class="vtop td24 lineheight">magic_quote_gpc</td><td class="lineheight smallfont"><!--{$magic_quote_gpc}--></td></tr>
    <tr><td class="vtop td24 lineheight">allow_url_fopen</td><td class="lineheight smallfont"><!--{$allow_url_fopen}--></td></tr>
</table>
</div>
<!--{include file="admin/footer.tpl"}-->