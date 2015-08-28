<!--{include file="admin/header.tpl"}-->
<script type="text/javascript">
function AC_FL_RunContent() {
    var str = '';
    var ret = AC_GetArgs(arguments, "clsid:d27cdb6e-ae6d-11cf-96b8-444553540000", "application/x-shockwave-flash");
    if(BROWSER.ie && !BROWSER.opera) {
        str += '<object ';
        for (var i in ret.objAttrs) {
            str += i + '="' + ret.objAttrs[i] + '" ';
        }
        str += '>';
        for (var i in ret.params) {
            str += '<param name="' + i + '" value="' + ret.params[i] + '" /> ';
        }
        str += '</object>';
    } else {
        str += '<embed ';
        for (var i in ret.embedAttrs) {
            str += i + '="' + ret.embedAttrs[i] + '" ';
        }
        str += '></embed>';
    }
    return str;
}
function AC_GetArgs(args, classid, mimeType) {
    var ret = new Object();
    ret.embedAttrs = new Object();
    ret.params = new Object();
    ret.objAttrs = new Object();
    for (var i = 0; i < args.length; i = i + 2){
        var currArg = args[i].toLowerCase();
        switch (currArg){
            case "classid":break;
            case "pluginspage":ret.embedAttrs[args[i]] = 'http://www.macromedia.com/go/getflashplayer';break;
            case "src":ret.embedAttrs[args[i]] = args[i+1];ret.params["movie"] = args[i+1];break;
            case "codebase":ret.objAttrs[args[i]] = 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0';break;
            case "onafterupdate":case "onbeforeupdate":case "onblur":case "oncellchange":case "onclick":case "ondblclick":case "ondrag":case "ondragend":
            case "ondragenter":case "ondragleave":case "ondragover":case "ondrop":case "onfinish":case "onfocus":case "onhelp":case "onmousedown":
            case "onmouseup":case "onmouseover":case "onmousemove":case "onmouseout":case "onkeypress":case "onkeydown":case "onkeyup":case "onload":
            case "onlosecapture":case "onpropertychange":case "onreadystatechange":case "onrowsdelete":case "onrowenter":case "onrowexit":case "onrowsinserted":case "onstart":
            case "onscroll":case "onbeforeeditfocus":case "onactivate":case "onbeforedeactivate":case "ondeactivate":case "type":
            case "id":ret.objAttrs[args[i]] = args[i+1];break;
            case "width":case "height":case "align":case "vspace": case "hspace":case "class":case "title":case "accesskey":case "name":
            case "tabindex":ret.embedAttrs[args[i]] = ret.objAttrs[args[i]] = args[i+1];break;
            default:ret.embedAttrs[args[i]] = ret.params[args[i]] = args[i+1];
        }
    }
    ret.objAttrs["classid"] = classid;
    if(mimeType) {
        ret.embedAttrs["type"] = mimeType;
    }
    return ret;
}
function browserVersion(types) {
    var other = 1;
    for(i in types) {
        var v = types[i] ? types[i] : i;
        if(USERAGENT.indexOf(v) != -1) {
            var re = new RegExp(v + '(\\/|\\s)([\\d\\.]+)', 'ig');
            var matches = re.exec(USERAGENT);
            var ver = matches != null ? matches[2] : 0;
            other = ver !== 0 && v != 'mozilla' ? 0 : other;
        }else {
            var ver = 0;
        }
        eval('BROWSER.' + i + '= ver');
    }
    BROWSER.other = other;
}
  </script>
<div class="floattop">
    <div class="itemtitle"><h3>数据统计</h3>
    <ul class="tab1">
        <li<!--{if 'index' == $_actionName}--> class="current"<!--{/if}-->>
            <a href="/admin/stat/index"><span>用户统计</span></a>
        </li>
        <li<!--{if 'cont' == $_actionName}--> class="current"<!--{/if}-->>
            <a href="/admin/stat/cont"><span>内容统计</span></a>
        </li>
        <li<!--{if 'inter' == $_actionName}--> class="current"<!--{/if}-->>
            <a href="/admin/stat/inter"><span>互动统计</span></a>
        </li>
        <li<!--{if 'view' == $_actionName}--> class="current"<!--{/if}-->>
            <a href="/admin/stat/view"><span>数据趋势</span></a>
        </li>
    </ul>
    </div>
</div>
<div class="floattopempty"></div>
<form action="/admin/stat/view" method="post" onsubmit="return $.checkForm(this)">
<table class="tb tb2 td27">
<!--{foreach name=stat key=key item=stat from=$opt}-->
<tr >
<th class="partition" style="width:100px;"><!--{$slang.$key}--></th>
<td>
<!--{if $smarty.foreach.stat.index === 0}-->
    <input type="checkbox" disabled='true' name="stat[sum]" value="1" <!--{if $sum}-->checked<!--{/if}-->/> 综合概况
<!--{/if}-->
<!--{foreach name=ss key=k item=s from=$stat}-->
 <input type="checkbox" name="stat[<!--{$key}-->][<!--{$k}-->]" <!--{if in_array($k,$stats)}-->checked<!--{/if}--> value="1" /> <!--{$s}-->
<!--{/foreach}-->
</td>
</tr>
<!--{/foreach}-->
<tr class="noborder">
    <th class="partition">开始时间</td>
    <td> <input type="text" class="txt" value="<!--{$sd|date_format:"%Y-%m-%d"}-->" name="sd" value="" onclick="showcalendar(event,this,false)" onclick="showcalendar(event,this)" datatype="Require" msg="请选择结束时间" readonly="readonly"/>
    <span info="sd"></span> - <input type="text" class="txt" value="<!--{$ed|date_format:"%Y-%m-%d"}-->" name="ed" value="" onclick="showcalendar(event,this,false)" onclick="showcalendar(event,this)" datatype="Require" msg="请选择结束时间" readonly="readonly"/>
    <span info="ed"></span>
     <input type="checkbox" name="merge" value="1" <!--{if $merge}-->checked<!--{/if}-->/> 合并统计 
    <input type="submit" name="updatecache" value="查看" class="btn"/>
    </td>
</tr>
</table>
</form>
<br/>
<!--{if $opts}-->
<div style="width:100%;">
<!--{if $ed >= ($sd + 86400*2) }-->
<script>
document.write(AC_FL_RunContent(
'width', '100%', 'height', '300',
'src', '<!--{$_resource}-->resource/flash/stat.swf?path=&settings_file=<!--{$_resource}-->resource/xml/stat_setting.xml&data_file=<!--{$_resource}-->admin/stat/stat/sd/<!--{$sd}-->/ed/<!--{$ed}-->/stat[]/<!--{$opts}-->',
'quality', 'high', 'wmode', 'transparent'
));
</script>
<!--{else}-->
需要2天或以上时间段
<!--{/if}-->
</div>
<!--{/if}-->
<script src="/resource/js/calendar.js"></script>
</div>
<!--{include file="admin/footer.tpl"}-->