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
    </head>
    <body scroll="no" style="background:none;">
        <script type="text/javascript" language="javascript">
            //<![CDATA[
            // show login form in top frame
                if (top != self)
                {
                    window.top.location.href=location;
                }
            //]]>
        </script>
        <div class="header">
            <div class="logo">iWeibo2.0管理中心</div>
            <div class="nav"><ul></ul></div>
            <div class="logininfo"></div>
        </div>
        <div class="main loginmain" id="main">
            <div class="mainC">
                 <h1>iWeibo管理中心</h1>
                 <p>iWeibo系统是基于腾讯微博开放平台API开发的一套免费微博系统，广大开发者和站长可下载安装文件部署到自己的服务器上，为网站提供微博服务，也可以在此系统基础上进行更丰富的功能开发。</p>
            </div>
            <div class="mainD" id="mainD">
                <form method="post" name="login" id="loginform" action="/admin/login/login">
                    <div class="msg"></div>
                    <table border="0" cellpadding="0"cellspacing="0">
                        <tr>
                            <th width="60" height="36">用户名</th>
                            <td><input type="text" id="username" name="username" class="txt" /></td>
                            <td><td>
                        </tr>
                        <tr>
                            <th height="36">密  码</th>
                            <td><input type="password" name="password" class="txt" /></td>
                            <td><td>
                        </tr>
                        <!--{if $isCode}-->
                        <tr valign="top">
                            <th height="55" style="padding:8px 10px 0 0;">附加码</th>
                            <td><input type="text" id="gdkey" name="gdkey" class="txt" onfocus="reloadgd(document.getElementById('gdField'))" /> </td>
                            <td><img id="gdField" src="" gd="<!--{$_gdurl}-->" onclick="reloadgd(this, true)" alt="看不清？换一张" title="看不清？换一张" style="visibility: hidden;cursor: pointer;" /><td>
                        </tr>
                        <script>
                            function reloadgd(el,f){
                                if(f || !el.gdloaded){
                                    el.src=el.getAttribute('gd') + '?' + +new Date();
                                    el.style.visibility='visible';
                                    el.gdloaded = true;
                                }
                            }
                        </script>
                        <!--{/if}-->
                        <tr>
                            <td></td>
                            <td><input name="loginsubmit" value="登录"  tabindex="3" type="submit" class="btn"/></td>
                            <td><td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <div class="copyright">Powered by iWeibo 2.0 &copy; 1998-2011 Tecent.</div>
        <script type="text/javascript">
        $(function (){
        	$('#username').focus();
        });
    	</script>
    </body>
</html>