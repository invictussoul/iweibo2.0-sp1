<!--{include file="admin/header.tpl"}-->
<!--{include file="admin/tlive_menu.tpl"}-->
<script>
    function chkFrom(obj){
        var res = $.checkForm(obj);
        if(res){
            var sdate = parseInt($("#sdate").val().replace(/\D+/g,''));
            var edate = parseInt($("#edate").val().replace(/\D+/g,''));
            if(sdate >= edate){
                $("#errorspan").removeClass('validate').addClass('invalidate').text('开始时间须小于结束时间!');
                $res = false;
                return false;
            }
        }
        return res;
    }
</script>
<div class="floattopempty"></div>
<form action="/admin/tlive/new" method="post" onsubmit="return chkFrom(this)" enctype="multipart/form-data" >
<table class="tb tb2 td27">
<tr class="noborder" >
    <th colspan="15" class="partition">微直播 <span style="color:#999;font-weight:normal">编辑/添加在线微直播信息</span> </th>
</tr>
<tr>
    <td class="td27" colspan="2">直播名称:</td>
</tr>
<tr class="noborder">
    <td class="vtop rowform"><input type="text" class="txt" name="tlive[tname]" value="<!--{$tlive.tname}-->" datatype="Require" msg="请填写微直播名称"/></td>
    <td class="vtop tips2"><span info="tlive[tname]"></span></td>
</tr>
<tr class="noborder">
    <td class="vtop rowform"><input type="checkbox"  name="tlive[direct]" value="0" <!--{if !$tlive.direct }-->checked<!--{/if}--> /> 直播内容先审后发</td>
    <td class="vtop tips2"></td>
</tr>
<tr class="noborder">
    <td class="td27" colspan="2">直播话题:</td>
</tr>
<tr class="noborder">
    <td class="vtop rowform"><input type="text" class="txt" name="tlive[title]" value="<!--{$tlive.title}-->" datatype="Require" msg="请填写微直播话题"/></td>
    <td class="vtop tips2"><span info="tlive[title]"></span></td>
</tr>
 <tr>
    <td class="td27" colspan="2">直播简介:</td>
</tr>
<tr class="noborder">
    <td class="vtop rowform">
        <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" name="tlive[desc]"  cols="50" class="tarea"><!--{$tlive.desc}--></textarea>
    </td>
    <td class="vtop tips2"></td>
</tr>
<tr class="noborder">
    <td class="td27" colspan="2">开始时间:</td>
</tr>
<tr class="noborder">
    <td class="vtop rowform"><input type="text" id="sdate" class="txt" value="<!--{$tlive.sdate}-->" name="tlive[sdate]" value="" onclick="showcalendar(event,this,true)" datatype="Require" msg="请填写开始时间"  readonly="readonly"/></td>
    <td class="vtop tips2"><span info="tlive[sdate]" id="errorspan"></span></td>
</tr>
<tr class="noborder">
    <td class="td27" colspan="2">结束时间:</td>
</tr>
<tr class="noborder">
    <td class="vtop rowform"><input type="text" id="edate" class="txt" value="<!--{$tlive.edate}-->" name="tlive[edate]" value="" onclick="showcalendar(event,this,true)" onclick="showcalendar(event,this,true)" datatype="Require" msg="请选择结束时间" readonly="readonly"/></td>
    <td class="vtop tips2"><span info="tlive[edate]" id="errorspan"></td>
</tr>
<tr class="noborder" style="display:none">
    <td class="td27" colspan="2">提醒时间:</td>
</tr>
<tr class="noborder" style="display:none">
    <td class="vtop rowform"><input type="hidden" class="txt" name="tlive[notice]" value="0"  /></td>
    <td class="vtop tips2"><span info="tlive[edate]">单位分钟</span></td>
</tr>
<tr class="">
    <td class="td27" colspan="2">主持人:</td>
</tr>
<tr class="noborder">
    <td class="vtop rowform">
        <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)" datatype="Require" msg="主持人不能为空" name="user[0]"  cols="50" class="tarea"><!--{$tlive.users.0}--></textarea>
    </td>
    <td class="vtop tips2"> <span name="user[0]" info="user[0]"></span> 输入用户帐号,以 ";" 或换行隔开 </td>
</tr>
<tr class="noborder">
    <td class="td27" colspan="2">嘉宾:</td>
</tr>
<tr class="noborder">
    <td class="vtop rowform">
        <textarea  rows="6" ondblclick="textareasize(this, 1)" onkeyup="textareasize(this, 0)"  datatype="Require" msg="嘉宾不能为空!" name="user[1]"  cols="50" class="tarea"><!--{$tlive.users.1}--></textarea>
    </td>
    <td class="vtop tips2"><span name="user[1]" info="user[1]"></span> 输入用户帐号,以 ";" 或换行隔开 </td>
</tr>
<tr class="noborder">
    <td class="td27" colspan="2">外观:</td>
</tr>
<tr class="noborder">
    <td class="vtop rowform">
        <input type="hidden" name="style[outward]" id="style-outward" value="<!--{$tlive.style.outward}-->"/>
        <input type="file" name="outward" id="outward"/><br/>
    </td>
    <td class="vtop tips2">
        <cite>请上传小于1M的jpg,png格式图片</cite><br/>
    </td>
</tr>
<!--{if $tlive.style.outward}-->
<tr class="noborder">
    <td class="td27" colspan="2">
        <img src="<!--{$tlive.style.outward}-->" width="400" height="50"/>
        <a href="javascript:void(0);" onclick="rmImage('style-outward',this)">删除</a>
    </td>
</tr>
<!--{/if}-->
<tr class="noborder">
    <td class="td27" colspan="2">封面:</td>
</tr>
<tr class="noborder">
    <td class="vtop rowform">
        <input type="hidden" name="style[cover]" id="style-cover" value="<!--{$tlive.style.cover}-->"/>
        <input type="file" name="cover" id="cover"/><br/>
    </td>
    <td class="vtop tips2">
        <cite>请上传小于1M的jpg,png格式图片</cite><br/>
    </td>
</tr>
<!--{if $tlive.style.cover}-->
<tr class="noborder">
    <td class="td27" colspan="2">
        <img src="<!--{$tlive.style.cover}-->" width="100" height="100"/>
        <a href="javascript:void(0);" onclick="rmImage('style-cover',this)">删除</a>
    </td>
</tr>
<!--{/if}-->
<tr class="noborder">
    <td class="td27" colspan="2">背景:</td>
</tr>
<tr class="noborder">
    <td class="vtop rowform">
        <input type="hidden" name="style[background]" id="style-background" value="<!--{$tlive.style.background}-->"/>
        <input type="file" name="background" id="background"/><br/>
        <p><br/><input type="radio" name="style[repeat]" value="1" <!--{if $tlive.style.repeat}-->checked<!--{/if}--> /> 背景重复
        <input type="radio" name="style[repeat]" value="0" <!--{if !$tlive.style.repeat}-->checked<!--{/if}--> /> 背景不重复</p>
    </td>
    <td class="vtop tips2">
        <cite>请上传小于1M的jpg,png格式图片</cite><br/>
    </td>
</tr>
<!--{if $tlive.style.background}-->
<tr class="noborder">
    <td class="td27" colspan="2">
        <img src="<!--{$tlive.style.background}-->" width="100" height="100"/> 
        <a href="javascript:void(0);" onclick="rmImage('style-background',this)">删除</a>
    </td>
</tr>
<!--{/if}-->
</table>
<table class="tb tb2 td27">
<tr class="noborder">
    <td class="td27" colspan="2">颜色方案:</td>
</tr>
<tr class="noborder">
    <td class="vtop " width="100%">
    <input type="radio" id="syscolor" name="colorRadio" value="1" checked="true" /> 已有方案<br/>&nbsp;&nbsp;&nbsp;&nbsp;
    <!--{foreach name=files item=color key=i from=$colors}-->
        <span style="display:inline-block;background:<!--{$color.bg}-->;border:2px solid #ccc;margin:3px;width:60px;text-align:center" class="colorbox">
            <a href="javascript:void(0);" style="color:<!--{$color.a}-->" onclick="selectColor(this,'<!--{$color.bg}-->','<!--{$color.a}-->')">选择</a>
        </span>
    <!--{/foreach}-->
    <p class="tips2" id="mycolortips" >&nbsp;&nbsp;&nbsp;&nbsp;点击预览框选择颜色</p>
    </td>
    <input type="hidden" name="tlive[id]" value="<!--{$tlive.id}-->" />
</tr>
<tr class="noborder">
    <td class="td27" colspan="2">效果预览:</td>
</tr>
<tr class="noborder">
    <td class="vtop " colspan="2" >
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" class="color {required:false,hash:true}" name="style[bgcolor]"   id="sbgcolor" value="<!--{$tlive.style.bgcolor}-->"/> 背景色&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" class="color {required:false,hash:true}" name="style[linkcolor]" id="scolor"    value="<!--{$tlive.style.linkcolor}-->"/> 链接色
    </td>
</tr>
</table>
 <div class="opt"><input type="submit" name="submit" value="提交" class="btn" tabindex="3" /></div>
</form>
</div>
<script src="/resource/js/calendar.js"></script>
<script>
    function selectColor(obj,bg,a){
        $(obj).blur();
        if($("#syscolor").attr("checked")){
            $(".colorbox").css("border","2px solid #ccc");
            $(obj).parent().css("border","2px solid #f00");
            $("#sbgcolor").val(bg);
            $("#sbgcolor").trigger('focus');
            $("#sbgcolor").trigger('blur');
            $("#scolor").trigger('focus');
            $("#scolor").trigger('blur');
            $("#scolor").val(a);
            $("#demobg, #demolink").unbind();
        }
    }

    function rmImage(id,obj){
        $(obj).parent().parent().html('');
        $("#"+id).val('');
    }
</script>
<!--{include file="admin/footer.tpl"}-->