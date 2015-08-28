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
    <script type="text/javascript" language="javascript">
        //<![CDATA[
        // show login form in top frame
            if (top != self)
            {
                window.top.location.href=location;
            }
        //]]>
    </script>
    <div class="wrapper2 banner" style="display:block;"><img src="/resource/images/banner.jpg"/></div>
    <div class="wrapper2 content" style="display:block;">
        <div class="contentleft fleft">
            <div class="moduletitle5"><strong class="fleft"><em class="icon_mes"></em>大家在说</strong></div>
            <div class="tcontainer">
                <ul class="tmain" id="tmain">
                    <!--{foreach key=key item=msg from=$msglist}-->
                    <li class="tmessage">
                        <div class="extra"></div>
                        <div class="ttouxiang"><a href="javascript:void(0);"><img src="<!--{$msg.head}-->"/></a></div>
                        <div class="tbody">
                            <a href="javascript:void(0);"><!--{$msg.nick}--></a>
                            <!--{if $msg.isvip}-->
                                <span class="icon_vip"></span>
                            <!--{/if}-->
                            <span class="colon">:</span>
                            <span><!--{$msg.text}--></span>
                            <div class="tbottom">
                              来自<!--{$msg.from}-->
                            </div>
                        </div>
                    </li>
                    <!--{/foreach}-->
                </ul>
            </div>
        </div>
        <!--{TO->cfg key="login_local" group="basic" assign="_login_local" default="1"}-->
        <!--{TO->cfg key="login_tencent" group="basic" assign="_login_tencent" default="1"}-->
        <div class="contentright fright<!--{if $_login_local == 1}--> locallogin<!--{/if}--><!--{if $isCode}--> loginhascode<!--{/if}-->">
            <form name="form1" method="post" action="/login/l" class="loginform iwbFormValidatorControl">
                <ul><!--{if $_login_local == 1}-->
                    <li><label class="placeholder">&nbsp;&nbsp;&nbsp;帐号</label><input type="text" id="username" name="username" data-label="帐号" data-validator="required" autocorrect="off" autocapitalize="off" autocomplete="off" spellcheck="false" class="input_text" /></li>
                    <li><label class="placeholder">&nbsp;&nbsp;&nbsp;密码</label><input type="password" name="pwd" data-label="密码" data-validator="required" autocorrect="off" autocapitalize="off" autocomplete="off" spellcheck="false" class="input_text" /></li>
                    <!--{if $isCode}-->
                    <li><label class="placeholder">附加码</label><span class="gdcode"><input type="text" id="gdkey" name="gdkey" data-label="附加码" data-validator="required" onfocus="reloadgd(document.getElementById('gdField'))" class="input_text"/>
                        <img id="gdField" src="" gd="<!--{$_gdurl}-->" onclick="reloadgd(this, true)" alt="看不清？换一张" title="看不清？换一张"/>
                        </span>
                    </li>
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
                    <li class="space1">
                        <span class="fleft">
                        <label class="placeholder">&nbsp; &nbsp; &nbsp; </label>
                        <input type="checkbox" name="autologin" id="autologin" value="1" /><label for="autologin" class="gray">下次自动登录</label></span>
                        <label class="fright">
                        <a href="<!--{if $mailOpen}-->/login/findnav<!--{else}-->/login/qqfindpwd<!--{/if}-->" class="gray fright">忘记密码</a>
                        </label>
                    </li>
                    <li>
                        <div align="center"><input type="submit" class="loginbtn" title="登录" value=""/></div>
                    </li>
                    <!--{if $_login_tencent == 1}-->
                    <li align="center" class="space2"><a href="/login/r">使用腾讯微博帐号/QQ登录</a></li>
                    <!--{/if}-->
                    <!--{if $allowReg}--><li><div align="center"><a href="/reg" title="注册帐号" class="regbtn">注册帐号</a></div></li><!--{/if}-->
                    <!--{else}-->
                        <!--{if $_login_tencent == 1}-->
                        <li align="center"><a href="/login/r">使用腾讯微博帐号/QQ登录</a></li>
                        <!--{/if}-->
                    <!--{/if}-->
                </ul>
            </form>
            <h3 class="moduletitle6">您可以通过如下方式使用iWeibo</h3>
            <div align="center"><img src="/resource/images/p1.gif"/></div>
        </div>
    </div>
    <!--{include file="common/footer.tpl"}-->
    <!--{$ucsynlogout}-->
    <script>
        $.each($("#tmain").find("a"),function (i,node) {
            var node = $(node);
            var text = node.text();
            if (!/^http/.test(text)) {
                node.addClass("nopermission");
            }
        });
        $(".nopermission").live({
            click: function (event) {
                event.preventDefault(); // 不跳转网页
                IWB_DIALOG.modaltipbox("error", "请先登录" ,function () {
                    $('#username').focus();
                });
            }
        });
    </script>
    <script type="text/javascript">
        $('#username').focus();
        <!--{if $showmsg}-->
            alert('<!--{$showmsg}-->');
        <!--{/if}-->
    </script>
    </body>
</html>
