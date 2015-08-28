<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle"><h3>数据库恢复 <span style="color:red">(数据备份文件位于 网站路径/data 目录下, 请确保该目录外部不可见!)</span></h3>
    </div>
</div>
<div class="floattopempty"></div>
<form name="cpform" method="post" autocomplete="off" action="/admin/db/restore/del/1" id="backForm" id="cpform" >
<table class="tb tb2 ">
<tr><td colspan="2" class="td27">数据备份记录:</td></tr>
<tr class="header"><th width="45"></th><th>文件名</th><th>版本</th><th>时间</th><th>尺寸</th><th>卷数</th><th></th></tr>
<!--{foreach name=files item=file key=i from=$files}-->
<tr class="hover">
    <td><input class="checkbox" type="checkbox" name="dir[]" value="<!--{$i}-->"></td>
    <td><a href="javascript:void(0);" onclick="if($('#<!--{$i}-->').css('display')=='none'){$('.sqlfile').hide();$('#<!--{$i}-->').show();}else{$('#<!--{$i}-->').hide();}"><!--{$i}--></a></td>
    <td><!--{$file.ver}--></td>
    <td><!--{$file.date}--></td>
    <td><!--{$file.size}--> KB</td>
    <td><!--{$file.num}--></td>
    <td><a class="act" href="/admin/db/restore/d/<!--{$i}-->" class="act">导入</a></td>
</tr>
<tbody id="<!--{$i}-->" style="display:none" class="sqlfile">
<!--{foreach name=f item=f key=k from=$file.files}-->
<tr class="hover">
    <td></td>
    <td><!--{$f.file}--></td>
    <td></td>
    <td><!--{$f.date}--></td>
    <td><!--{$f.size}--> KB</td>
    <td>1</td>
    <td></td>
</tr>
<!--{/foreach}-->
</tbody>
<!--{/foreach}-->
<tr>
    <td class="td25">
        <input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'dir')" />
        <label for="chkall">删?</label>
    </td>
    <td colspan="15">
        <div class="fixsel">
            <input type="submit" class="btn" id="submit_deletesubmit" name="deletesubmit" title="按 Enter 键可随时提交您的修改" value="提交" />
        </div>
    </td>
</tr>
</table>
</form>
</div>
<!--{include file="admin/footer.tpl"}-->