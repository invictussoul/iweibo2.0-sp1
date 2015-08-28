<!doctype html>
<html>
    <head>
        <title><!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="Keywords" content="<!--{TO->cfg key="keywords" group="seo" default=""}-->" />
        <meta name="Description" content="<!--{TO->cfg key="description" group="seo" default=""}-->" />
        <link rel="shortcut icon" href='/favicon.ico'/>
        <style type="text/css">
            body{margin:0;padding:0;background:<!--{$css.bgcolor}-->;color:#555; font:12px Verdana, Lucida, Helvetica, Arial, "宋体",  sans-serif;}
            h1, h2, h3, h4, h5, h6, form,ul,p,li{margin:0; padding:0;list-style:normal;}
            a{text-decoration:none;}
            a img{border:0;}
            .clear{clear:both;}
            .btn_green{background:url(about:blank)\9;}
            .iweibo{height:<!--{$css.height-2}-->px;border:1px solid <!--{$css.bordercolor}-->;float:left;margin-left:0px;overflow-x:hidden;width:<!--{$css.width-2}-->px;}
            .iweibo h1{height:26px;line-height:26px;font-size:12px;text-indent:10px;background:#089;color:#000;font-weight:normal;background:-moz-linear-gradient(center top,<!--{$css.titlecolor}-->,<!--{$css.shadecolor}-->);
            background:-webkit-gradient(linear, left top, left bottom, from(<!--{$css.titlecolor}-->), to(<!--{$css.shadecolor}-->));filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='<!--{$css.titlecolor}-->', endColorstr='<!--{$css.shadecolor}-->');}
            .iweibo .sendbox{background:#F0FBFF;}
            .iweibo h2{height:19px;line-height:100%;line-height:19px\9;text-align:center;background:-moz-linear-gradient(center top,#BFEBFB,#9CDEF4);
            background:-webkit-gradient(linear, left top, left bottom, from(#BFEBFB), to(#9CDEF4));filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#BFEBFB', endColorstr='#9CDEF4');border:1px solid <!--{$css.bordercolor}-->;border-width:1px 0;font-size:24px;text-shadow:1px 1px 1px #5FC7EA;overflow:hidden;width:100%;}
            .iweibo h2 a{display:block;line-height:100%;color:#fff;text-align:center;width:100%;}
            .iweibo h2 a:hover{text-decoration:none;}
            .iweibo .userinfo{margin:10px;display:inline-block;}
            .iweibo .fleft{float:left;*display:inline;zoom:1;}
            .iweibo .profile{margin-left:10px;width:<!--{$css.width-84}-->px;_width:200px;position:relative;overflow:hidden;}
            .iweibo .profile strong a{font-size:14px;font-weight:bold;color:<!--{$css.fontcolor}-->;}
            .iweibo .profile .logo{width:18px;height:18px;position:absolute;top:0;right:0;background:url(../../../resource/images/logo16.gif) no-repeat;}
            .iweibo .sendbox textarea{clear:both;margin:0 10px;width:267px;height:68px;padding:5px;border:1px solid <!--{$css.bordercolor}-->;background:#fff;resize:none;overflow:auto;margin-bottom:6px;}
            .iweibo .sendbox textarea:focus{border-color:<!--{$css.bordercolor}-->;}
            .iweibo .sendtxt{margin:0 14px;text-align:right;line-height:16px;}
            .iweibo .sendtxt *{vertical-align:middle;}
            .iweibo .sendtxt label{cursor:default;color:gray;vertical-align:bottom;}
            .iweibo .sendtxt big{font-size:16px;vertical-align:bottom;}
            .iweibo .tcontainer{overflow:auto;overflow-x:hidden;}
            .iweibo .tcontainer ul{margin:10px;}
            .iweibo .tcontainer li{border-bottom:1px solid <!--{$css.bordercolor}-->;padding:0 8px;line-height:20px;word-wrap:break-word;}
            .iweibo .tcontainer li p{color:gray;margin-bottom:5px;}
            .iweibo .tcontainer li img{margin:8px 0;}
            .iweibo .tcontainer a{color:<!--{$css.fontcolor}-->;}
            .iweibo .tcontainer a:hover{text-decoration:none;}
            .iweibo .getmore{height:19px;line-height:18px;text-align:center;display:block;border-top:1px solid <!--{$css.bordercolor}-->;background:-moz-linear-gradient(center top,#e1e1e1,#ffffff);
            background:-webkit-gradient(linear, left top, left bottom, from(#e1e1e1), to(#fff));filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#e1e1e1', endColorstr='#ffffff');color:gray;}
            .iweibo .getmore:hover{text-decoration:none;}
            #buttonB a{-o-transform: rotate(180deg);-webkit-transform:rotate(180deg);-moz-transform:rotate(180deg);line-height:26px;filter:FlipV() progid:DXImageTransform.Microsoft.Chroma(color='#B0E5F8');overflow:hidden;height:19px;background:#B0E5F8\9;}
            .iweibo_host .sendbox{height:190px;}
            .iweibo_host .tcontainer{height:293px;}
            .iweibo_client .sendbox{height:70px;}
            .iweibo_client .tcontainer{height:<!--{$css.height-139}-->px;}
            .iweibo_more .tcontainer{height:413px;}
            .btn,.btn_green{*font-family:Georgia;_font-family:Tahoma;
            padding:0 10px;display:inline-block;text-align:center;vertical-align:middle;border-radius:2px;line-height:21px;* line-height:20px;cursor:pointer;
            }
            .btn{border:1px solid #09c;background:#64C3E9;color:#fff;
            background:-moz-linear-gradient(center top,#64C3E9,#41A0D6);
            background:-webkit-gradient(linear, left top, left bottom, from(#64C3E9), to(#41A0D6));
            filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#64C3E9',endColorstr='#41A0D6') progid:DXImageTransform.Microsoft.dropshadow(OffX=1, OffY=1, Color='#0099cc',strength=0);
            border:none\9;
            }
            .btn_green{border:1px solid #78A34F;background:#B8F07E;color:#fff;
            background:-moz-linear-gradient(center top,#B8F07E,#7EC531);
            background:-webkit-gradient(linear, left top, left bottom, from(#B8F07E), to(#7EC531));filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#B8F07E', endColorstr='#7EC531') progid:DXImageTransform.Microsoft.dropshadow(OffX=1, OffY=1, Color='#78A34F',strength=0);border:none\9;
            }
            .btn:hover{background:#41A0D6;background:-moz-linear-gradient(center top,#74CCF3,#6AAEEC);background:-webkit-gradient(linear, left top, left bottom, from(#74CCF3), to(#6AAEEC));}
            .btn_green:hover{text-decoration:none;}
        </style>
        <script src="/resource/js/thirdparty/jquery/jquery-all.js"></script>
        <script type="text/javascript">
            function addIdol(type)
            {
                $.getJSON("<!--{$pathRoot}-->friend/follow/type/"+type+"/name/<!--{$openUserTimeline.0.name}-->", function(json){
                    if(json!=null)
                    {
                        if(json['ret']==0)
                        {
                            if(type==1)
                            {
                                $("#addidol").html('<a href="javascript:addIdol(0);" class="btn_green">+取消收听</a>');
                            }
                            else
                            {
                                $("#addidol").html('<a href="javascript:addIdol(1);" class="btn_green">+立即收听</a>');
                            }
                        }
                    }
                });
            }
        </script>
    </head>
    <body>
        <div class="iweibo iweibo_client">
            <h1><!--{$title}--></h1>
            <div class="sendbox">
                <div class="userinfo">
                    <a href="<!--{$pathRoot}-->u/<!--{$openUserInfo.name}-->" target="_blank" class="userhead fleft"><img src="<!--{$openUserInfo.head}-->/50"/></a>
                    <div class="profile fleft">
                        <strong><a href="<!--{$pathRoot}-->u/<!--{$openUserInfo.name}-->" target="_blank"><!--{$openUserInfo.nick}--></a></strong> <!--{if $openUserInfo.sex==1}-->男<!--{else}-->女<!--{/if}--><br/><br/>
                        <span id="addidol">
                        <!--{if $hasAccessToken && $openUserInfo.Ismyidol==0 && $openUserInfo.name != $accessToken.name}-->
                            <a href="javascript:addIdol(1);" class="btn_green">+立即收听</a>
                        <!--{elseif $hasAccessToken && $openUserInfo.Ismyidol==1 && $openUserInfo.name != $accessToken.name}-->
                            <a href="javascript:addIdol(0);" class="btn_green">+取消收听</a>
                        <!--{/if}-->
                        </span>
                    </div>
                </div>
            </div>
            <h2 id="buttonA"><a href="javascript:void(0)">^</a></h2>
            <div class="tcontainer" id="tcontainer">
                <ul>
                    <!--{foreach from=$openUserTimeline item=timeline}-->
                    <li>
                        <a href="<!--{$pathRoot}-->t/showt/tid/<!--{$timeline.id}-->" target="_blank">
                        <!--{if $timeline.type==2}-->
                            <span>转播：</span>
                        <!--{elseif $timeline.type=="7"}-->
                            <span>评论：</span>
                        <!--{elseif $timeline.type=="4"}-->
                            <span>对 <a href="<!--{$pathRoot}-->u/<!--{$timeline.source.name}-->" target="_blank"><!--{$timeline.source.nick}--></a> 说：</span>
                        <!--{/if}-->
                        <!--{$timeline.text}-->
                        </a>
                        <!--{if $timeline.type==2 || $timeline.type==7}-->
                        <div>
                            <!--{$timeline.source.nick}-->：
                            <!--{$timeline.source.text}-->
                            <!--{if $timeline.source.image}-->
                                <p><img src="<!--{if $showtype == 2}-->/resource/images/sico.png<!--{else}--><!--{$timeline.source.image.0}-->/160<!--{/if}-->"></p>
                            <!--{/if}-->
                            <p>
                                <span>
                                    <!--{$timeline.source.timestamp|idate:"m-d H:i"}-->
                                    来自<!--{$timeline.source.from}-->
                                </span>
                                <span>
                                    <!--{if $timeline.source.count>0}-->
                                        (<!--{$timeline.source.count}-->)
                                    <!--{/if}-->
                                </span>
                            </p>
                        </div>
                        <!--{/if}-->
                        <!--{if !$timeline.source && $timeline.image}-->
                            <p><img src="<!--{if $showtype == 2}-->/resource/images/sico.png<!--{else}--><!--{$timeline.image.0}-->/160<!--{/if}-->"></p>
                        <!--{/if}-->
                        <p>
                            <span>
                                <!--{$timeline.timestamp|idate:"m-d H:i"}-->
                                来自<!--{$timeline.from}-->
                                <!--{if $timeline.count>0}-->
                                    (<!--{$timeline.count}-->)
                                <!--{/if}-->
                            </span>
                        </p>
                    </li>
                    <!--{/foreach}-->
                </ul>
            </div>
            <h2 style="border-bottom:0;" id="buttonB"><a href="javascript:void(0)">^</a></h2>
        </div>
        <script type="text/javascript">
        var timer, obj=document.getElementById('tcontainer');
        var slider={
            "slideup":function(){
                clearInterval(timer);
                timer=setInterval(function(){obj.scrollTop+=10;},10);
            },
            "slideDown":function(){
                clearInterval(timer);
                timer=setInterval(function(){obj.scrollTop-=10;},10);
            },
            "stop":function(){
                clearInterval(timer);
            }
        };
        document.getElementById("buttonA").onmouseover=function(){
            slider.slideDown();
        }
        document.getElementById("buttonA").onmouseout=function(){
            slider.stop();
        }
        document.getElementById("buttonB").onmouseover=function(){
            slider.slideup();
        }
        document.getElementById("buttonB").onmouseout=function(){
            slider.stop();
        }
        </script>
    </body>
</html>