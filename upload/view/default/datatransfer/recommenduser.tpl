<!doctype html>
<html>
    <head>
        <title><!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="Keywords" content="<!--{TO->cfg key="keywords" group="seo" default=""}-->" />
        <meta name="Description" content="<!--{TO->cfg key="description" group="seo" default=""}-->" />
        <link rel="shortcut icon" href='/favicon.ico'/>
        <style type="text/css">
            html{height:100%;}
            body,form{margin:0;padding:0;}
            body,h1,h2,ul,li,p{margin:0;padding:0;font-size:12px;list-style:none;}
            body{background:<!--{$css.bgcolor}-->;height:100%;overflow:hidden;}
            a{color:<!--{$css.fontcolor}-->; text-decoration:none;}
            a:hover{text-decoration:underline;}
            a img{border:none;}
            input,label{vertical-align:middle;}
            .box{border:1px solid <!--{$css.bordercolor}-->;padding:1px;zoom:1;clear:both;height:<!--{$css.height-4}-->px;overflow:hidden;}
            .box h2{height:33px;background:<!--{$css.titlecolor}-->;
                background:-moz-linear-gradient(center top,<!--{$css.titlecolor}-->,<!--{$css.shadecolor}-->);
                background:-webkit-gradient(linear, left top, left bottom, from(<!--{$css.titlecolor}-->), to(<!--{$css.shadecolor}-->));filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='<!--{$css.titlecolor}-->', endColorstr='<!--{$css.shadecolor}-->');}
            .box h2 .fleft{font-size:12px;float:left;margin-left:10px;line-height:33px;}
            .box h2 .fright{float:right;margin-right:10px;font-weight:normal;line-height:30px;_ line-height:100%;_ margin-top:5px;}
            .box h2 .fright *{vertical-align:middle;_ vertical-align:bottom;}
            .userlist{overflow:auto; margin:10px 0 0; height:<!--{$css.height-45}-->px;}
            .userlist li{float:left; margin:0 10px 10px 0; white-space:nowrap;overflow:hidden;display:inline-block;width:56px;height:80px;}
            .box .userlist{margin:10px 0 10px 10px;clear:both;}
            .box .userlist img{border:1px solid <!--{$css.bordercolor}-->;padding:2px;display:block;}
            .box .userlist h3{font:12px normal;width:54px;overflow:hidden;text-align:center;margin:3px 0;}
            .box .userlist p{display:inline-block;*display:inline;zoom:1;line-height:0;overflow:hidden;height:56px;}
            .box .userlist p input{border:0;background:none;margin:-23px 0 0 37px;height:16px;}
            .btn_green{border:1px solid #78A34F;background:#B8F07E;color:#fff;
                background:-moz-linear-gradient(center top,#B8F07E,#7EC531);
                background:-webkit-gradient(linear, left top, left bottom, from(#B8F07E), to(#7EC531));filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#B8F07E', endColorstr='#7EC531') progid:DXImageTransform.Microsoft.dropshadow(OffX=1, OffY=1, Color='#78A34F',strength=0);border:none\9;}
            .btn_green:hover{text-decoration:none;}
        </style>
        <script src="/resource/js/thirdparty/jquery/jquery-all.js"></script>
        <script type="text/javascript">
            function checkAll(type, form, value, checkall, changestyle)
            {
                var checkall = checkall ? checkall : 'chkall';
                for(var i = 0; i < form.elements.length; i++) {
                    var e = form.elements[i];
                    if(type == 'option' && e.type == 'radio' && e.value == value && e.disabled != true) {
                        e.checked = true;
                    } else if(type == 'value' && e.type == 'checkbox' && e.getAttribute('chkvalue') == value) {
                        e.checked = form.elements[checkall].checked;
                    } else if(type == 'prefix' && e.name && e.name != checkall && (!value || (value && e.name.match(value)))) {
                        e.checked = form.elements[checkall].checked;
                        if(changestyle && e.parentNode && e.parentNode.tagName.toLowerCase() == 'li') {
                            e.parentNode.className = e.checked ? 'checked' : '';
                        }
                    }
                }
            }
            function addIdol()
            {
            	names = '';
                $("input[name='names[]']").each(function(k, name){
                    if(name.checked == true)
                    	names = names + (names == '' ? $(name).val() : ',' + $(name).val());
                });
                $.getJSON("<!--{$pathRoot}-->friend/follow/type/1/name/" + names, function(json){
                    if(json != null)
                    {
                        if(json['ret'] == 0)
                        {
                        	$("input[name='names[]']").each(function(k, name){
			                    if(name.checked == true)
			                    	$("#span_" + $(name).val()).html('');
			                });
                        }
                    }
                });
            }
        </script>
    </head>
    <body>
        <div class="box">
            <form>
                <h2>
                    <strong class="fleft"><!--{$title}--></strong>
                    <span class="fright">
                    <!--{if $hasAccessToken}-->
                        <input type="checkbox" id="chkall" name="chkall" onclick="checkAll('prefix', this.form, 'names')" /><label for="selectall">全选</label><input type="button" class="btn_green" value="+收听已选" onclick="addIdol();" />
                    <!--{/if}-->
                    </span>
                </h2>
                <ul class="userlist">
                    <!--{foreach from=$openUserInfo item=user}-->
                    <li>
                        <p>
                            <a href="<!--{$pathRoot}-->u/<!--{$user.name}-->" target="_blank"><img src="<!--{$user.head}-->/50" width="50" height="50"/></a>
                            <span id="span_<!--{$user.name}-->">
                            <!--{if $hasAccessToken && $user.Ismyidol==0 && $user.name != $accessToken.name}-->
                                <input name="names[]" type="checkbox" value="<!--{$user.name}-->"/>
                            <!--{/if}-->
                            </span>
                        </p>
                        <h3><a href="<!--{$pathRoot}-->u/<!--{$user.name}-->" target="_blank"><!--{$user.nick}--></a></h3>
                    </li>
                    <!--{/foreach}-->
                </ul>
            </form>
        </div>
    </body>
</html>