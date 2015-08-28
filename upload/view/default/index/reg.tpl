<!doctype html>
<html>
    <head>
        <!--{TO->cfg key="seo_title" group="basic" default="" assign="_title"}-->
        <title><!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--> - 注册 <!--{if $_title}--> -  <!--{$_title}--><!--{/if}--> - Powered by iWeibo</title>
        <meta name="Keywords" content="<!--{TO->cfg key="seo_keywords" group="basic" default=""}-->" />
        <meta name="Description" content="<!--{TO->cfg key="seo_description" group="basic" default=""}-->" />
        <!--{include file="common/style.tpl"}-->
    </head>
    <div class="wrapper2 banner"><img src="/resource/images/banner.jpg"/></div>
    <div class="wrapper2 whitebg">
        <!--{if $type==1}-->
        <form name="form1" method="post" action="/reg/r" class="regform iwbFormValidatorControl">
            <table border="0" align="center" cellpadding="0" cellspacing="0" width="380">
                <tr>
                    <th></th>
                    <td height="80"><strong>15秒注册本站帐号，分享精彩内容！</strong></td>
                </tr>
                <tr>
                    <th width="70" height="60" align="right" valign="top"><span>*</span>帐号</th>
                    <td align="left" valign="top"><input type="text" class="input_text" data-label="帐号" data-validator="required uname" name="username" id="username"><span class="emsg hide"><em class="icon_ok"></em></span>
                        <cite class="tipmsg">3-15个字母、数字或下划线</cite>
                    </td>
                </tr>
                <tr>
                    <th height="60" align="right" valign="top"><span>*</span>密码</th>
                    <td align="left" valign="top">
                        <input type="password" data-label="密码" data-name="password" data-validator="required pwd" name="pwd" id="pwd" class="pwd input_text"><span class="emsg hide"><em class="icon_ok"></em></span>
                        <cite class="tipmsg">密码长度为3~15字符</cite>
                    </td>
                </tr>
                <tr>
                    <th height="43" align="right" valign="top"><span>*</span>确认密码</th>
                    <td align="left" valign="top">
                        <input type="password" data-label="确认密码" data-name="password" data-validator="required pwd" name="pwdconfirm" class="pwdconfirm input_text"><span class="emsg hide"><em class="icon_ok"></em></span>
                        <cite class="tipmsg"></cite>
                   </td>
                </tr>
                <tr>
                    <th height="43" align="right" valign="top"><span>*</span>Email</th>
                    <td align="left" valign="top">
                        <input type="text" data-label="邮箱" data-validator="required email" name="email" id="email" class="email input_text"><span class="emsg hide"><em class="icon_ok"></em></span>
                        <cite class="tipmsg"></cite>
                    </td>
                </tr>
                <!--{if $isCode}-->
                     <tr>
                        <th align="right" valign="top" height="43"><span>*</span>附加码</th>
                        <td align="left" valign="top">
                        	<span style="position:relative;">
                            <input type="text" class="input_text" id="gdkey" name="gdkey" data-label="附加码" data-validator="required" onfocus="reloadgd(document.getElementById('gdField'))" style="width:35px;" />
                            <img id="gdField" src="" gd="<!--{$_gdurl}-->" onclick="reloadgd(this, true)" alt="看不清？换一张" title="看不清？换一张" style="visibility: hidden;cursor: pointer;position:absolute;top:-10px;left:60px;" />
                            </span>
                        </td>
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
                    <th height="43" align="right">&nbsp;</th>
                    <td align="left"><input type="submit" name="button" id="button" value="提交" class="save"/></td>
                </tr>
                <tr>
                	<th height="100" colspan="2"></th>
                </tr>
            </table>
        </form>
        <!--{elseif $type==3}-->
        <div class="wrapper2 whitebg">
            <form name="form1" method="post" action="/login/l" class="iwbFormValidatorControl bindform">
                <table border="0" align="center" cellpadding="0" cellspacing="0" width="220" vspace="10">
                	<tr>
                		<th colspan="2" align="left" height="80"><strong>已有本地帐号</strong></th>
                	</tr>
                    <tr>
                        <th height="30" width="45" align="left">帐号：</th>
                        <td align="left"><input type="text" class="input_text" name="username" id="username" data-label="帐号" data-validator="required uname" /><!--<span class="emsg"><em class="icon_ok"></em>格式不正确</span>-->
                        </td>
                    </tr>
                    <tr>
                        <th height="60" align="left">密码：</th>
                        <td align="left"><input type="password" class="input_text" name="pwd" id="pwd" data-label="密码" data-validator="required pwd"></td>
                    </tr>
                    <!--{if $isCode}-->
                    <tr>
                        <th align="right" valign="top">附加码</th>
                        <td align="left" valign="top">
			    			<span style="position:relative;">
                            <input type="text" class="input_text" id="gdkey" name="gdkey" data-label="附加码" data-validator="required" onfocus="reloadgd(document.getElementById('gdField'))" style="width:35px;" />
                            <img id="gdField" src="" gd="<!--{$_gdurl}-->" onclick="reloadgd(this, true)" alt="看不清？换一张" title="看不清？换一张" style="visibility: hidden;cursor: pointer;position:absolute;top:-10px;left:60px;" />
			    			</span>
                        </td>
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
                        <th height="43" align="left" valign="top"></th>
                        <td align="left">
                            <input type="submit" value="绑定本地帐号" class="button button_blue"/>
                            
                        </td>
                    </tr>
                    <tr>
                	<th height="100" colspan="2"></th>
                	</tr>
                </table>
            </form>
            <div class="fastreg">
        		<h2>15秒注册本站帐号，分享精彩内容！</h2>
        		<div><!--{if $allowReg}--><a href="/reg" class="button button_blue">立即注册</a><!--{/if}--></div>
        	</div>
        </div>
        
        <!--{elseif $type==6}-->
        <div class="crumb">
            <span class="fleft">找回密码 &gt; 选择密码找回方式</span>
        </div>
        <div align="center" class="usermsg"><br/><br/><br/><br/>
            <a href="/login/qqfindpwd" class="findpaswbtn">通过腾讯微博帐号找回</a><br/><br/>
            <a href="/login/findpwd" class="findpaswbtn">通过本站注册的邮箱找回</a>
        </div>
        <!--{elseif $type==7}-->
        <div class="crumb">
                <span class="fleft">找回密码 &gt; 通过腾讯微博帐号找回</span>
        </div>
        <form name="form1" method="post" action="/login/qqfindpwd" class="regform iwbFormValidatorControl">
            <input type="hidden" name="op" value="qqfind" />
            <table align="center" class="usermsg" vspace="20">
                <tr>
                    <th height="50">帐号：</th>
                    <td><input type="text" class="input_text" data-label="帐号" data-validator="required uname" name="username" id="username"></td>
                </tr>
                <tr>
                    <th height="50"></th><td><input type="submit" value="下一步" class="save"/></td>
                </tr>
                <tr>
                	<th height="100" colspan="2"></th>
                </tr>
            </table>
        </form>
        <!--{elseif $type==4}-->
        <div class="crumb">
                <span class="fleft">找回密码 &gt; 通过本站邮箱找回</span>
        </div>
        <form name="form1" method="post" action="/login/findpwd" class="regform iwbFormValidatorControl">
            <input type="hidden" name="op" value="find" />
            <table align="center" class="usermsg" vspace="20">
                <tr>
                    <th height="50">帐号：</th>
                    <td><input type="text" class="input_text" data-label="帐号" data-validator="required uname" name="username" id="username"></td>
                </tr>
                <tr>
                    <th height="50">邮箱：</th>
                    <td><input type="text" class="input_text" data-label="邮箱" data-validator="required email" name="email" id="email"></td>
                </tr>
                <tr>
                    <th height="50"></th><td><input type="submit" value="下一步" class="save"/></td>
                </tr>
                <tr>
                	<th height="100" colspan="2"></th>
                </tr>
            </table>
        </form>
        <!--{elseif $type==5}-->
        <div class="crumb">
                <span class="fleft">找回密码 &gt; 设置新密码</span>
        </div>
        <form name="form1" method="post" action="/login/changepwd" class="regform iwbFormValidatorControl">
            <input type="hidden" name="op" value="change" />
            <table align="center" class="usermsg" vspace="20">
                <tr>
                    <th height="50">帐号：</th>
                    <td><!--{$changeuser}--></td>
                </tr>
                <tr>
                    <th height="50">新密码：</th>
                    <td><input type="password" class="input_text" data-label="密码" data-name="password" data-validator="required pwd" name="pwd" id="pwd"></td>
                </tr>
                <tr>
                    <th height="50">确认新密码：</th>
                    <td><input type="password" class="input_text" data-label="确认密码" data-name="password" data-validator="required pwd" name="pwdconfirm" id="pwdconfirm"></td>
                </tr>
                <tr>
                    <th height="50"></th>
                    <td><input type="submit" value="提交" class="save"/></td>
                </tr>
                <tr>
                	<th height="100" colspan="2"></th>
                </tr>
            </table>
        </form>
        <!--{else}-->
        <div class="wrapper2 whitebg">
            <div class="result">
                <h2><!--{$message}--></h2>
                <div><a href="<!--{$url}-->" class="bindingbtn"><!--{$btntext}--></a></div>
                <!--meta http-equiv="refresh" content="3; url=<!--{$url}-->" /-->
            </div>
        </div>
        <!--{/if}-->
    </div>
    <!--{include file="common/footer.tpl"}-->
    <script>
        $(function () {
            $(".regform").find("input").blur(function () {
                var self = $(this);
                var error = self.fivalidate();
                if (error) {
                    // 提示错误
                    self.parent().find(".emsg").hide();
                    self.parent().find(".tipmsg").css({
                        color: "#ff4700"
                    });
                } else {
                    // 打勾
                    self.parent().find(".emsg").show();
                    self.parent().find(".tipmsg").css({
                        color: "#666"
                    });
                }
            });
            $(".regform").find(".pwd").blur(function (){
                if ($(".regform").find(".pwdconfirm").val().length > 0) {
                    $(".regform").find(".pwdconfirm").trigger("blur"); //修改密码同时进行一致性检查
                }
            });
            $(".regform").find(".pwdconfirm").blur(function () { // 密码输入框一致性检查
                var self = $(this);
                var error = self.fivalidate(); // 先检查自身规则
                var val1,val2;
                self.parent().find(".emsg").hide();
                if (error) {
                    self.parent().find(".emsg").hide();
                    self.parent().find(".tipmsg").text("密码长度为3~15字符").css({
                        color: "#ff4700"
                    });
                    return;
                }
                val1 = $(".regform").find(".pwd").val();
                val2 = self.val();
                if (val1==val2) { // 密码相同
                    self.parent().find(".emsg").show();
                    self.parent().find(".tipmsg").text("").css({
                        color: "#666"
                    });
                } else {
                    self.parent().find(".emsg").hide();
                    self.parent().find(".tipmsg").text("两次输入的密码不一致").css({
                        color: "#ff4700"
                    });
                }
            });
            $(".regform").find(".email").blur(function () { //邮箱出错提示使用配置的出错信息
                var self = $(this);
                var error = self.fivalidate();
                if (error) {
                    self.parent().find(".tipmsg").text(error);
                } else {
                    self.parent().find(".tipmsg").text("");
                }
            })
        });
    </script>
</body>
<!--{if $showmsg}-->
<script type="text/javascript">
    alert('<!--{$showmsg}-->');
</script>
<!--{/if}-->
</html>