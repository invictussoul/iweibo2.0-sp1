<!--{include file="admin/header.tpl"}-->
<script type="text/javascript">
function createCode()
{
    if(!$.checkForm($("#dtform")))
        return;
    var model = 'pendant';
    var title = $("#title").val();
    var name = $("#name").val();
    var number = $("#number").val();
    var width = $("#width").val();
    var autowidth = 0;
    if($("#autowidth").get(0).checked){
        width = 0;
        autowidth = 1;
    }
    var height = $("#height").val();
    var titlecolor = $("#titlecolor").val();
    var bgcolor = $("#bgcolor").val();
    var fontcolor = $("#fontcolor").val();
    var bordercolor = $("#bordercolor").val();
    var showtype = 1;
    if($("#showico").get(0).checked)
        showtype = 2;
    $.ajax({
        type: "POST",
        url: iwbRoot + "/admin/datatransfer/createcode",
        data: "model="+model
                +"&title="+title
                +"&name="+name
                +"&number="+number
                +"&width="+width
                +"&height="+height
                +"&autowidth="+autowidth
                +"&titlecolor="+titlecolor
                +"&bgcolor="+bgcolor
                +"&fontcolor="+fontcolor
                +"&bordercolor="+bordercolor
                +"&showtype="+showtype,
        success: function(code){
        	if(code.indexOf('error')==0)
        		alert(code.substr(6));
        	else
            	$("#code").val(code);
        }
    });
}
function setColor(titlecolor, bgcolor, fontcolor, bordercolor)
{
    $("#titlecolor").val(titlecolor);
    $("#titlecolor").focus();
    $("#bgcolor").val(bgcolor);
    $("#bgcolor").focus();
    $("#fontcolor").val(fontcolor);
    $("#fontcolor").focus();
    $("#bordercolor").val(bordercolor);
    $("#bordercolor").focus();
    $("#bordercolor").blur();
}
function setWidth()
{
    if($("#autowidth").get(0).checked){
        if($("#width").val()=='' || isNaN($("#width").val()))
            $("#width").val(0);
        $("#width").attr("readonly", "readonly");
        $("#width").css('background','#CCC');
        return;
    }
    $("#width").removeAttr("readonly");
    $("#width").css('background','#FFF');
}
function runCode(obj) {
     var winname = window.open('', "_blank", '');
     winname.document.open('text/html', 'replace');
     winname.document.write(obj.val());
     winname.document.close();
}
</script>
<div class="floattop">
    <div class="itemtitle">
        <h3>数据调用</h3>
        <ul class="tab1">
            <li><a href="/admin/datatransfer/recommenduser"><span>推荐用户</span></a></li>
            <li><a href="/admin/datatransfer/recommendtopic"><span>推荐话题</span></a></li>
            <li class="current"><a href="javascript:void(0);"><span>微博广播站</span></a></li>
            <li><a href="/admin/datatransfer/newt"><span>最新微博</span></a></li>
        </ul>
    </div>
</div>
<form id="dtform" name="dtform">
<table class="tb tb2">
    <tr>
        <td width="100">头部文字</td>
        <td width="200"><input type="text" class="txt" id="title" name="title" value="微博广播站" datatype="Require" msg="请填写头部文字" /></td>
        <td><span info="title"></span></td>
    </tr>
    <tr>
        <td>指定单用户：</td>
        <td><input type="text" class="txt" id="name" name="name" value="" datatype="Require" msg="请填写指定用户" /></td>
        <td><span info="name"></span></td>
    </tr>
    <tr>
        <td>数据条数</td>
        <td>
            <select id="number" name="number">
                <!--{section name=foo start=1 loop=31 step=1}-->
                    <option value="<!--{$smarty.section.foo.index}-->" <!--{if $smarty.section.foo.index==20}-->selected<!--{/if}-->><!--{$smarty.section.foo.index}--></option>
                <!--{/section}-->
            </select>
        </td>
        <td></td>
    </tr>
    <tr><td colspan="3"><b>尺寸</b></td></tr>
    <tr>
        <td>宽度</td>
        <td><input type="text" class="txt" maxlength="4" id="width" name="width" value="300" datatype="Number" msg="请正确填写宽度" /></td>
        <td><span info="width"></span></td>
    </tr>
    <tr>
        <td>高度</td>
        <td><input type="text" class="txt" maxlength="4" id="height" name="height" value="500" datatype="Number" msg="请正确填写高度" /></td>
        <td><span info="height"></span></td>
    </tr>
    <tr><td colspan="3"><input type="checkbox" id="autowidth" name="autowidth" value="1" onclick="setWidth();" />宽度自适应网页</td></tr>
    <tr><td colspan="3"><b>颜色</b></td></tr>
    <tr>
        <td colspan="3">
            <span style="display:inline-block;background:#E3E7EA;border:2px solid #ccc;margin:3px;width:60px;text-align:center" class="colorbox">
                <a href="javascript:setColor('E3E7EA', 'fff', '0082CB', 'DBDBDB');" style="color:#000000">方案1</a>
            </span>
            <span style="display:inline-block;background:#BBF083;border:2px solid #ccc;margin:3px;width:60px;text-align:center" class="colorbox">
                <a href="javascript:setColor('BBF083', 'fff', '090', 'A9CF7B');" style="color:#000000">方案2</a>
            </span>
            <span style="display:inline-block;background:#CDEEFF;border:2px solid #ccc;margin:3px;width:60px;text-align:center" class="colorbox">
                <a href="javascript:setColor('CDEEFF', 'fff', '3cf', 'BDE9FF');" style="color:#000000">方案3</a>
            </span>
            <span style="display:inline-block;background:#E5D374;border:2px solid #ccc;margin:3px;width:60px;text-align:center" class="colorbox">
                <a href="javascript:setColor('E5D374', 'fff', 'BAA233', 'E5D374');" style="color:#000000">方案4</a>
            </span>
        </td>
    </tr>
    <tr>
        <td>标题栏颜色</td>
        <td><input type="text" class="color {required:false}" maxlength="6" id="titlecolor" name="titlecolor" value="" datatype="Require" msg="请填写标题栏颜色" /></td>
        <td><span info="titlecolor"></span></td>
    </tr>
    <tr>
        <td>背景色</td>
        <td><input type="text" class="color {required:false}" maxlength="6" id="bgcolor" name="bgcolor" value="" datatype="Require" msg="请填写背景色" /></td>
        <td><span info="bgcolor"></span></td>
    </tr>
    <tr>
        <td>字体颜色</td>
        <td><input type="text" class="color {required:false}" maxlength="6" id="fontcolor" name="fontcolor" value="" datatype="Require" msg="请填写字体颜色" /></td>
        <td><span info="fontcolor"></span></td>
    </tr>
    <tr>
        <td>边框颜色</td>
        <td><input type="text" class="color {required:false}" maxlength="6" id="bordercolor" name="bordercolor" value="" datatype="Require" msg="请填写边框颜色" /></td>
        <td><span info="bordercolor"></span></td>
    </tr>
    <tr>
        <td>显示图片</td>
        <td>
            <input type="radio" id="showthumb" name="showtype" value="1" checked /> 显示为缩略图
            <input type="radio" id="showico" name="showtype" value="2" /> 显示为图标
        </td>
        <td></td>
    </tr>
    <tr><td colspan="3"><input type="button" class="btn" id="validate" name="validate" value="生成代码" onclick="createCode();"></td></tr>
    <tr><td colspan="3"><textarea id="code" name="code" cols="60" rows="8"></textarea></td></tr>
    <tr><td colspan="3"><input type="button" class="btn" id="copycode" name="copycode" value="拷贝代码" onclick="copyCode($('#code'));"><input type="button" class="btn" id="copycode" name="copycode" value="预览效果" onclick="runCode($('#code'));"></td></tr>
</table>
</form>
<!--{include file="admin/footer.tpl"}-->