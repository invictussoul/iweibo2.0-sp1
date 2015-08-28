<!--{if $action == 'face'}-->
<div class="setform fleft">
    <div id="userhead_settingB" class="sethead<!--{if !$uploadType}--> hide<!--{/if}-->">
        <div class="fleft"><img src="<!--{$userhead_src}-->" alt="" id="userhead_img"></div>
        <div class="fleft upload">
            <form action="/setting/generalchangeface" method="post" enctype="multipart/form-data" name="form_upload" id="form_upload" target="_self" />
                <p><input type="file" id="pic" name="pic" class="upload" /></p>
                <p class="gray">支持jpg、jpeg、gif、png格式的图片，不超过2M<br>建议图片尺寸大于120×120</p>
                <p><a class="save" style="display:none;" id="submitbtn">保&nbsp;存</a></p>
                <input type="submit"  value="保存" class="save"/>
            </form>
        </div>
        <div class="tabformbar">
        如果想要编辑头像，请尝试使用<a href="javascript:" onclick="simpleUpload(0)">编辑上传模式</a><br/><br/>
        </div>
    </div>
    <div id="userhead_settingA" class="sethead<!--{if $uploadType}--> hide<!--{/if}-->">
        <div>
            <embed type="application/x-shockwave-flash" src="/resource/flash/saveHead.swf?imgurl=<!--{$userhead_src}-->&savepath=<!--{$_pathroot}-->setting/changeface&t=<!--{$time}-->" width="530" height="400" id="qqminiblog" name="qqminiblog" bgcolor="#FFFFFF" quality="high" allownetworking="all" allowscriptaccess="always" allowfullscreen="true" scale="noscale" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer"/>
        </div>
        <div class="tabformbar"><br/>
            如果无法上传头像，请尝试使用<a href="javascript:" onclick="simpleUpload(1)">普通上传模式</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    function simpleUpload(k)
    {
        if(k==0)
         {
             document.getElementById("userhead_settingA").style.display="block";
             document.getElementById("userhead_settingB").style.display="none";
         }
         else if(k==1)
        {
            document.getElementById("userhead_settingA").style.display="none";
             document.getElementById("userhead_settingB").style.display="block";
        }
    }
    function saveCutSuccess()
    {
        alert("保存成功！");
        window.location = iwbRoot + 'setting/face';
    }
    function cancelCutHead()
    {
        window.location.href=iwbRoot + 'setting';
    }
</script>
<!--{elseif $action == 'changepwd'}-->
<form action="/setting/changepwd" method="post" class="setform fleft iwbFormValidatorControl">
    <table border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <th valign="top"></th>
            <td></td>
        </tr>
        <tr>
            <th height="45" valign="middle"><a href="/setting"><<返回</a></th>
            <td></td>
        </tr>
        <tr>
            <th height="45" valign="top">
                <span>*</span>旧密码：
            </th>
            <td valign="top">
                <input type="password" name="oldpwd" autocomplete="off" data-label="旧密码" data-validator="required pwd" />
                <cite></cite>
                <b></b>
                <div class="gray"></div>
            </td>
        </tr>
        <tr>
            <th height="60" valign="top">
                <span>*</span>新密码：
            </th>
            <td valign="top">
                <input type="password" name="pwd" autocomplete="off" data-label="新密码" data-name="password" data-validator="required pwd" />
                <cite></cite>
                <b></b>
                <div class="gray">密码长度为3~15字符</div>
            </td>
        </tr>
        <tr>
            <th height="35" valign="top">
                <span>*</span>确认密码：
            </th>
            <td valign="top">
                <input type="password" name="pwdconfirm" autocomplete="off" data-label="确认密码" data-name="password" data-validator="required pwd"/>
                <cite></cite>
                <b></b>
                <div class="gray"></div>
            </td>
        </tr>
        <tr>
            <th height="35" valign="top"></th>
            <td><input type="submit" value="保 存" class="save"/></td>
        </tr>
        </tbody>
    </table>
    <input type="hidden" name="action" value="<!--{$action}-->" />
</form>
<!--{else}-->
<form action="/setting" method="post" class="setform fleft iwbFormValidatorControl">
    <table border="0" cellspacing="0" cellpadding="0" vspace="30">
        <tbody>
        <tr>
            <th height="43">帐号：</th>
            <td><!--{$userInfo.username}-->&nbsp;&nbsp;&nbsp;&nbsp;<a href="/setting/changepwd">修改密码&gt;&gt;</a></td>
        </tr>
        <tr>
            <th height="60" valign="top">
                <span>*</span>姓名：
            </th>
            <td valign="top">
                <input type="text" name="nickname" autocomplete="off" data-label="姓名" data-validator="required nick" value="<!--{$userInfo.nickname}-->" maxlength="12" size="35">
                <cite></cite>
                <b></b>
                <div class="gray">1-12个中文、字母、数字、下划线或减号</div>
            </td>
        </tr>
        <tr>
            <th height="43" valign="top"><span>*</span>性别：</th>
            <td valign="top">
                <input name="gender" type="radio" value="2" <!--{if $userInfo.gender == '2'}-->checked<!--{/if}-->>&nbsp;<label for="gender">女</label>&nbsp;&nbsp;
                <input name="gender" type="radio" value="1" <!--{if $userInfo.gender == '1'}-->checked<!--{/if}-->>&nbsp;<label for="gender2">男</label>
            </td>
        </tr>
        <tr>
            <th height="43" valign="top"><span>*</span>生日：</th>
            <td valign="top">
                <select id="birthyear" name="birthyear" onchange="changeDate()"></select>
                <select id="birthmonth" name="birthmonth" onchange="changeDate()"></select>
                <select id="birthday" name="birthday" onchange="changeDate()"></select>
            </td>
        </tr>
        <tr>
            <th height="43" valign="top">星座：</th>
            <td valign="top"><span id="star"></span></td>
        </tr>
        <tr>
            <th height="43" valign="top">生日显示方式：</th>
            <td valign="top">
                <select name="privbirth">
                    <!--{foreach from=$setting.privbirth key=index item=privbirth}-->
                    <option value="<!--{$index}-->" <!--{if $userInfo.privbirth == $index}-->selected<!--{/if}-->><!--{$privbirth}--></option>
                    <!--{/foreach}-->
                </select>
            </td>
        </tr>
        <tr>
            <th height="43" valign="top">家乡：</th>
            <td valign="top">
                <select id="homenation" name="homenation" onchange="changeHomeNation()"></select>
                <select id="homeprovince" name="homeprovince" onchange="changeHomeProvince()"></select>
                <select id="homecity" name="homecity"></select>
            </td>
        </tr>
        <tr>
            <th height="43" valign="top">所在地：</th>
            <td valign="top">
                <select id="nation" name="nation" onchange="changeNation()"></select>
                <select id="province" name="province" onchange="changeProvince()"></select>
                <select id="city" name="city"></select>
            </td>
        </tr>
        <tr>
            <th height="43" valign="top">从事行业：</th>
            <td valign="top">
                <span>
                    <select id="occupation" name="occupation">
                        <option></option>
                        <!--{foreach from=$setting.occupation key=index item=occupation}-->
                            <option value="<!--{$index}-->" <!--{if $userInfo.occupation == $index}-->selected<!--{/if}-->><!--{$occupation}--></option>
                        <!--{/foreach}-->
                    </select>
                    <cite></cite>
                </span>
            </td>
        </tr>
        <tr>
            <th height="60" valign="top"><span>*</span>常用邮箱：</th>
            <td valign="top">
                <input id="email" name="email" type="text" data-label="常用邮箱" data-validator="required email" maxlength="75" class="inputTxt en" value="<!--{$userInfo.email}-->" size="50">
                <cite></cite>
                <br />
                <span class="gray">常用邮箱仅用来接收微博活动或通知邮件，不会公开显示</span>
            </td>
        </tr>
        <tr>
            <th height="60" valign="top"><span></span>个人主页：</th>
            <td valign="top"><input id="homepage" name="homepage" type="text" data-label="个人主页" data-validator="homepage" size="50" maxlength="75" value="<!--{$userInfo.homepage}-->"> <cite></cite><br />
            <span class="gray">也可以<a href="javascript:document.getElementById('homepage').value='<!--{$pathRoot}-->u/<!--{$userInfo.name}-->';void(0);">填入你的微博网址</a></span></td>
        </tr>
        <tr>
            <th height="100" valign="top">个人介绍：</th>
            <td valign="top">
            <textarea id="summary" name="summary" cols="60" rows="5" onchange="this.value=this.value.substr(0, 140);" onkeyup="this.value=this.value.substr(0, 140);"><!--{$userInfo.summary}--></textarea> <span class="gray">140字以内</span></td>
        </tr>
        <tr>
            <th height="43" valign="top"></th>
            <td valign="top"><input type="submit" value="保 存" class="save"/></td>
        </tr>
        </tbody>
    </table>
    <input type="hidden" name="action" value="<!--{$action}-->" />
</form>
<!--{/if}-->