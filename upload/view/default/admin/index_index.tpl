<!doctype html>
<html>
<head>
<title>iWeibo2.0管理中心 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="qq Inc." name="Copyright">
<link rel="apple-touch-icon-precomposed" href="http://mat1.gtimg.com/www/mb/images/microblog_72_72.png" />
<link rel="shortcut icon" href="http://mat1.gtimg.com/www/mb/favicon.ico"/>
<link rel="stylesheet" type="text/css" href='/resource/admin/css/manage.css'>
<script type="text/javascript" src="/resource/admin/js/jquery-1.6.min.js"></script>
<script type="text/javascript" src="/resource/admin/js/iwbDialog.js"></script>
<script>
function forceupdate(){
    var boxid;
    var boxWidth = 200;
    var boxHeight = boxWidth * 0.618;
    var boxLeft = ($("body").width() - boxWidth) / 2; // 水平居中
    var boxTop = (document.documentElement.scrollTop || document.body.scrollTop) + 0.618 * (document.documentElement.clientHeight - boxHeight) / 2; // 黄金分割垂直居中
    var boxid = IWB_DIALOG._init({
        width: boxWidth,
        height: boxHeight,
        top: boxTop,
        left: boxLeft,
        modal: true,
        showClose: false,
        autoClose: false,
        getDOM: function () {
            var dom = $("<div style=\"padding:20px;\">您的 iWeibo 已严重过期，请去官网下载最新版本升级！</div>");
            return dom;
        }
    });

}
</script>
</head>
<body scroll="no">
<div class="header">
    <div class="logo">iWeibo2.0管理中心</div>
    <div class="nav">
        <ul>
            <!--{foreach key=key item=menu from=$menulist name=topmenu}-->
<li index="<!--{$smarty.foreach.topmenu.index}-->"><div><a href="<!--{$menu.0.url}-->" target="win" hidefocus><!--{$key}--></a></div></li>
            <!--{/foreach}-->
        </ul>
    </div>
    <div class="logininfo">
            <span>欢迎, <em><!--{$adminname}--></em> [ <a href="/admin/login/logout" target="_top">退出</a> ]</span>
            <span><a href="/" target="_blank">首页</a></span>
    </div>
</div>
<div class="main" id="main">
    <div class="mainA">
        <!--{include file="admin/menu.tpl"}-->
    </div>
    <div class="mainB" id="mainB">
    <iframe src="<!--{$mainurl}-->" name="win" id="win" width="100%" height="100%" frameborder="0"></iframe>
    </div>
</div>
<script type="text/javascript">
window.onload =window.onresize= function(){winresize();}
function winresize()
{
function $(s){return document.getElementById(s);}
var D=document.documentElement||document.body,h=D.clientHeight-90,w=D.clientWidth-160;
 $("main").style.height=h+"px";
 $("mainB").style.width=w+"px";
}
$(document).ready(function(){
    var s=document.location.hash;
    if(s==undefined||s==""){s="#0_0";}
    s=s.slice(1);
    var navIndex=s.split("_");
    $(".nav").find("li:eq("+navIndex[0]+")").addClass("active");
    var targetLink=$(".menu").find("ul").hide().end()
                             .find(".left_menu:eq("+navIndex[0]+")").show()
                             .find("li:eq("+navIndex[1]+")").addClass("active")
                             .find("a").attr("href");
    $("#win").attr("src",targetLink);
    $(".nav").find("li").click(function(){
        $(this).parent().find("li").removeClass("active").end().end()
               .addClass("active");
        var index=$(this).attr("index");
        $(".menu").find(".left_menu").hide();
        $(".menu").find(".left_menu:eq("+index+")").show()
                  .find("li").removeClass("active").first().addClass("active");
        document.location.hash=index+"_0";
    });
    $(".left_menu").find("li").click(function(){
            $(this).parent().find("li").removeClass("active").end().end()
                            .addClass("active");
        document.location.hash=$(this).parent().attr("index")+"_"+$(this).attr("index");
    });
});
</script>
</body>
</html>