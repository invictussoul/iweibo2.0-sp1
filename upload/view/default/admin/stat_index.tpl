<!--{include file="admin/header.tpl"}-->
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
<table class="tb tb2 td27">
<tr>
<th colspan="15" class="partition">统计信息
    <a href="/admin/stat/<!--{$_actionName}-->/"  style='font-weight:normal;'>
        <span <!--{if 'all' == $limit}--> style='font-weight:bolder;'<!--{/if}-->>所有</span></a>
    <a href="/admin/stat/<!--{$_actionName}-->/limit/m" style='font-weight:normal;'>
        <span <!--{if 'm' == $limit}-->   style='font-weight:bolder;'<!--{/if}-->>本月</span></a>
    <a href="/admin/stat/<!--{$_actionName}-->/limit/w" style='font-weight:normal;'>
        <span <!--{if 'w' == $limit}-->   style='font-weight:bolder;'<!--{/if}-->>本周</span></a>
    <a href="/admin/stat/<!--{$_actionName}-->/limit/d" style='font-weight:normal;'>
        <span <!--{if 'd' eq $limit}-->   style='font-weight:bolder;'<!--{/if}-->>今日</span></a>
</th>
</tr>
    <!--{foreach name=f item=st key=k from=$stat}-->
    <tr class="hover">
        <td width='15%'><!--{$st.stype}--><!--{$st.name}--></td>
        <td ><!--{$st.count}--></td>
    </tr>
    <!--{/foreach}-->
</table>
</div>
<!--{include file="admin/footer.tpl"}-->