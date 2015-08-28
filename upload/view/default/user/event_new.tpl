<!doctype html>
<html>
    <head>
    <title>发起活动 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
    <style>
    /*note sub table */
    .sub, .sub .td27{padding-left:25px !important;}
    .sub .rowform{padding-left:25px !important; width:281px;}
    .sub .rowform .txt, .sub .rowform textarea{width:225px;}
    .sub select{width:231px;}
    .mt10{margin-top:10px;}
    .mt0{margin-top:0}
    .header, .header td, .header th{border-top:1px dotted #DEEFFB; font-weight:700;}
    .smallefont{font-size:11px}
    .avatarlist{height:1%; overflow:hidden;}
    .avatarlist li{width:100px; float:left; margin-bottom:6px;}
    .avataraddlist{height:1%; overflow:hidden; border-bottom:1px dotted #DEEFFB; padding-bottom:6px; margin-bottom:6px;}
    .avataraddlist li{width:100px; float:left; morgin-bottom:6px;}
    #calendar{padding:5px; text-align:left; border:1px solid #7FCAE2; background:#FFF; margin-bottom:0.8em;}
    #calendar td{padding:2px; font-weight:bold;}
    #calendar_week td{height:2em; line-height:2em; border-bottom:1px solid #E3EDF5;}
    #hourminute td{padding:4px 2px; border-top:1px solid #E3EDF5;}
    .calendar_expire, .calendar_expire a:link, .calendar_expire a:visited{color:#666; font-weight:normal;}
    .calendar_default, .calendar_default a:link, .calendar_default a:visited{color:#09C;}
    .calendar_checked, .calendar_checked a:link, .calendar_checked a:visited{color:#F60; font-weight:bold;}
    td.calendar_checked, span.calendar_checked{background:#E3EDF5;}
    .calendar_today, .calendar_today a:link, .calendar_today a:visited{color:#444; font-weight:bold;}
    #calendar_header td{width:30px; height:20px; border-bottom:1px solid #E3EDF5; font-weight:normal;}
    #calendar_year{display:none; line-height:130%; background:#FFF; position:absolute; z-index:10;}
    #calendar_year .col{float:left; background:#FFF; margin-left:1px; border:1px solid #E3EDF5; padding:4px;}
    #calendar_month{display:none; background:#FFF; line-height:130%; border:1px solid #DDD; padding:4px; position:absolute; z-index:11;}
    .errors{ color:Red;text-indent:10px;font-size:12px;}
    </style>
    <!--[if lte IE 6]>
    <link rel="stylesheet" href='/resource/css/ie6.css' type="text/css" media="screen, projection">
    <![endif]-->
    </head>
    <body>
    <!--{include file="common/header.tpl"}-->
    <div id="append_parent"></div>
    <div class="wrapper content">
        <div class="contentleft fleft">
            <form name="form1" id="form1" method="post" action="/event/new/submit/1" target="newevent" class="regform" method="post" onsubmit="return checkForm('form1');" enctype="multipart/form-data">
              <input type="hidden" name="callback" value="parent.newEventResponse" />
              <table border="0" align="center" cellpadding="10" cellspacing="0">
                <tr>
                  <td align="left" cols="2" height="15"></td>
                </tr>
                <tr>
                  <th height="30" align="right"><span>*</span>活动标题</th>
                  <td align="left">
                    <input type="text" name="title" id="title"  value="<!--{$event.title}-->" datatype="Require" msg="标题不能为空!"/>
                    <span class="errors"></span>
                </td>
                </tr>
                <tr>
                  <th height="30" align="right">联系方式</th>
                  <td align="left"><label for="textfield2"></label>
                  <input type="text" name="phone" id="phone" value="<!--{$event.phone}-->" />
                  </td>
                </tr>
                <tr>
                  <th height="30" align="right">联系人</th>
                  <td align="left"><input type="text" name="realname" id="realname"  value="<!--{$event.realname}-->"/></td>
                </tr>
                <tr>
                  <th height="30" align="right"><span>*</span>活动地点</th>
                  <td align="left"><input type="text" name="addr" id="addr"  value="<!--{$event.addr}-->" datatype="Require" msg="活动地点不能为空!"/>
                  <span class="errors"></span></td>
                </tr>
                <tr>
                  <th height="30" align="right"><span>*</span>报名截止</th>
                  <td align="left"><input type="text" name="deadline" id="deadline"  value="<!--{$event.deadline}-->" onclick="showcalendar(event, this,true)" readonly="true" datatype="Require" msg="报名截止时间不能为空!" /> <a class="icon_data" href="javascript:void(0)" onclick="showcalendar(event, document.getElementById('deadline'),true)" ></a>
                  <span class="errors"></span></td>
                </tr>
                <tr>
                  <th height="30" align="right"><span>*</span>开始时间</th>
                  <td align="left"><input type="text" name="sdate" id="sdate" readonly="true" value="<!--{$event.sdate}-->" onclick="showcalendar(event, this,true)"  datatype="Require" msg="开始时间不能为空!" /> <a class="icon_data" href="javascript:void(0)" onclick="showcalendar(event, document.getElementById('sdate'),true)" ></a>
                  <span class="errors"></span></td>
                </tr>
                <tr>
                  <th height="30" align="right"><span>*</span>结束时间</th>
                  <td align="left"><input type="text" name="edate" id="edate" readonly="true" onclick="showcalendar(event,this,true)" value="<!--{$event.edate}-->" datatype="Require" msg="结束时间不能为空!"/> <a class="icon_data" href="javascript:void(0)" onclick="showcalendar(event, document.getElementById('edate'),true)"></a>
                  <span class="errors"></span>
                  </td>
                </tr>
                <tr>
                  <th height="30" align="right">人均费用</th>
                  <td align="left">
                  <!--{if $event.cost == 0}-->
                    <input type="radio" checked name="cost"  value="0" /><label>免费</label>
                    <input type="radio" name="cost" value="1"/>
                    <input type="text" name="money" id="money" style="width:110px;" disabled="true" onblur="this.value=this.value==''?0:(parseFloat(/^\D+$/.test(this.value)?0:this.value).toFixed(2));" value="0"/>
                <!--{else}-->
                    <input type="radio"  name="cost" value="0" /><label>免费</label>
                    <input type="radio" checked name="cost" value="1"/>
                    <input type="text" name="money" id="money" style="width:110px;" onblur="this.value=this.value==''?0:(parseFloat(/^\D+$/.test(this.value)?0:this.value).toFixed(2));" value="<!--{$event.cost}-->"/>
                <!--{/if}-->
                <label>元</label>
                 </td>
                </tr>
                <tr>
                  <th height="30" align="right">其它要求</th>
                  <td align="left"><input type="checkbox" name="contact" <!--{if $event.contact}-->checked<!--{/if}--> value="1"/> <label>要求参与者填写联系方式和简单说明</label></td>
                </tr>
                <tr>
                  <th height="30" align="right"  valign="top">活动介绍</th>
                  <td align="left"><textarea cols="40" name="message" rows="5" ><!--{$event.message}--></textarea></td>
                </tr>
                <tr>
                  <th height="30" align="right"  valign="top" style="padding-top:5px;">封面</th>
                  <td align="left"><!--{if $event.pic}--><img src="<!--{$event.pic}-->" width="200"/><br/><!--{/if}-->
                  <input type="file" name="pic" id="pic"/><br/>
                  <cite>请上传小于1M的jpg,png格式图片，尺寸为120*120px</cite>
                  </td>
                </tr>
                <!--{if $event.id > 0}-->
                    <input type="hidden" name="eid" value="<!--{$event.id}-->"/>
                <!--{/if}-->
                <!--{if $event.pic != ''}-->
                    <input type="hidden" name="pic" value="<!--{$event.pic}-->"/>
                <!--{/if}-->
                <tr>
                  <th height="30" align="right">&nbsp;</th>
                  <td align="left"><input type="submit" name="submit" id="button" value="确认" class="save"/></td>
                </tr>
              </table>
            </form>
            <iframe class="hide" name="newevent"></iframe>
        </div>
        <div class="contentright fright">
            <!--{include file="user/event_right_list.tpl"}-->
        </div>
    </div>
    <!--{include file="common/footer.tpl"}-->
    <script src="/resource/js/calendar.js"></script>
    <script>
        $(document).ready(function(){
            // radio
            $("#form1 :radio").bind('click',function(){
                if($(this).attr('checked')){
                    if($(this).val() == '1'){
                        $('#money').attr('disabled',false);
                    }else{
                        $('#money').attr('disabled',true);
                    }
                    $('#money').val('0');
                }
            });
        });
        // 表单验证
        function checkForm(id){
            var ret = true;
            $('#'+id+' :input').each(function(i,o){
                if($(o).attr('datatype') == 'Require'){
                    if(!(/.+/i).test($(o).val())){
                        $(".errors").html(' ');
                        $('#'+$(o).attr('id')+' ~ span').html($(o).attr('msg'));
                        $(o).focus();
                        ret =  false;
                        return false;
                    }
                }
            });
            var ddate = parseInt($("#deadline").val().replace(/\D+/g,''));
            var sdate = parseInt($("#sdate").val().replace(/\D+/g,''));
            var edate = parseInt($("#edate").val().replace(/\D+/g,''));
            if(ddate >= sdate){
                $(".errors").html(' ');
                $('#deadline ~ span').html('报名时间须小于开始时间!');
                $ret = false;
                return false;
            }
            if(ddate >= edate){
                $(".errors").html(' ');
                $('#deadline ~ span').html('报名时间须小于结束时间!');
                $ret = false;
                return false;
            }
            if(sdate >= edate){
                $(".errors").html(' ');
                $('#sdate ~ span').html('开始时间须小于结束时间!');
                $ret = false;
                return false;
            }
            return ret;
        }
    </script>
    <script src="/resource/js/eventCommon.js"></script>
    <script>
    var newEventResponse = function (response) {
        var eventid;
        if (response.ret === 0) {
            eventid = response.data.eventid || 0;
            window.location.href = "<!--{$_pathroot}-->event/view/id/" + eventid;
        } else {
            IWB_DIALOG.modaltipbox("error",response.msg);
        }
    }
    </script>
    </body>
</html>