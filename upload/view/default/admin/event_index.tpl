<!--{include file="admin/header.tpl"}-->
<div class="floattop">
    <div class="itemtitle">
        <h3>活动管理</h3>
    </div>
</div>
<div class="itemtitle">
    <div class="cuspages left">
    <form id="searchtagform" name="searchtagform" method="post" action="/admin/event/search">
        名称：
        <input type="text" id="tagname" name="title" value="<!--{$title}-->" class="txt"  size="20" />&nbsp;
        <select name="type">
            <!--{foreach key=k item=t from=$types}-->
            <option value="<!--{$k}-->" <!--{if $k == $curTypeId}-->selected<!--{/if}-->><!--{$t}--></option>
            <!--{/foreach}-->
        </select>&nbsp;
        <input class="btn" type="submit" value="搜索" name="searchsubmit" />
    </form>
    </div>
</div>
<br/>
<br/>
<table class="tb tb2 ">
    <tr>
        <th colspan="7">符合以上搜索条件的结果( 共有 <!--{$total}--> 条 ) : &nbsp;&nbsp;<span style='color:red'><!--{$curTypeText}--></span></th>
    </tr>
    <tr class="header">
        <th width="20%">活动名称</th>
        <th width="15%">创建人</th>
        <th width="8%">参与人数</th>
        <th width="15%">截至时间</th>
        <th width="10%">状态</th>
        <th width="10%">操作</th>
        <th >推荐</th>
    </tr>
    <!--{foreach key=key item=ent from=$events.events}-->
    <tr class="hover">
        <td><!--{$ent.title}--></td>
        <td><!--{$ent.uname}-->
            <!--{if $ent.realname}-->
            (<!--{$ent.realname}-->)
            <!--{/if}-->
        </td>
        <td><!--{$ent.joins}--></td>
        <td><!--{$ent.deadline}--></td>
        <td>
            <!--{if $ent.status == 1}-->正常
            <!--{elseif $ent.status == 2}-->用户关闭
            <!--{elseif $ent.status == 3}-->管理封禁
            <!--{elseif $ent.status > 3}--><span style='color:green'>推荐</span>
            <!--{/if}-->
        </td>
        <td>
            <!--{if ($ent.status == 1 || $ent.status > 3) && $ent.isend == FALSE}-->
            <a href="/admin/event/set/st/lock/id/<!--{$ent.Id}-->" >封禁</a>
            <!--{elseif $ent.status == 3 && $ent.isend == FALSE }-->
            <a href="/admin/event/set/st/clock/id/<!--{$ent.Id}-->" >解封</a>
            <!--{else}-->-
            <!--{/if}-->
        </td>
        <td>
            <!--{if $ent.status == 1 && $ent.isend == FALSE}-->
            <a href="/admin/event/set/st/rec/id/<!--{$ent.Id}-->" >设置推荐</a>
            <!--{elseif $ent.status > 3 && $ent.isend == FALSE}-->
            <a href="/admin/event/set/st/crec/id/<!--{$ent.Id}-->" >取消推荐</a>
            <!--{else}-->-
            <!--{/if}-->
        </td>
    </tr>
    <!--{/foreach}-->
    <tr>
    <td colspan="6" align="right"><!--{include file="common/multipage.tpl"}--></td>
    </tr>
</table>
<!--{include file="admin/footer.tpl"}-->