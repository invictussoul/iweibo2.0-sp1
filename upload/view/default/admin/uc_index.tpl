<!--{include file="admin/header.tpl"}-->
<div class="itemtitle">
    <h3>UCenter设置</h3>
</div>
<form id="ucform" name="ucform" method="post" action="/admin/uc/index">
<input type="hidden" name="action" value="setup" />
<table class="tb tb2">
    <!--{foreach from=$ucArr key=key item=value}-->
    <tr>
        <td>
            <!--{if $key=='connect'}-->
                连接方式
            <!--{elseif $key=='dbhost'}-->
                数据库主机
            <!--{elseif $key=='dbuser'}-->
                数据库用户名
            <!--{elseif $key=='dbpw'}-->
                数据库密码
            <!--{elseif $key=='dbname'}-->
                数据库名称
            <!--{elseif $key=='dbcharset'}-->
                数据库字符集
            <!--{elseif $key=='dbtablepre'}-->
                数据库表前缀
            <!--{elseif $key=='key'}-->
                通信密钥
            <!--{elseif $key=='api'}-->
                UC URL地址
            <!--{elseif $key=='charset'}-->
                UC 字符集
            <!--{elseif $key=='ip'}-->
                UC IP
            <!--{elseif $key=='appid'}-->
                当前应用ID
            <!--{else}-->
                <!--{$key}-->
            <!--{/if}-->
        </td>
        <td>
            <!--{if $key=='dbpw'}-->
                <input type="text" size="50" value="******" style="background:#CCC" disabled />
            <!--{elseif $key=='key'}-->
            	<input type="text" size="50" id="key" name="key" value="<!--{$value}-->" />
            <!--{else}-->
                <input type="text" size="50" value="<!--{$value}-->" style="background:#CCC" disabled />
            <!--{/if}-->
        </td>
        <td>
            <!--{if $key=='connect'}-->
                连接 UCenter 的方式: mysql/NULL, 默认为空时为 fscoketopen()<br>
                mysql 是直接连接的数据库, 为了效率, 建议采用 mysql
            <!--{elseif $key=='dbhost'}-->
                UCenter 数据库主机
            <!--{elseif $key=='dbuser'}-->
                UCenter 数据库用户名
            <!--{elseif $key=='dbpw'}-->
                UCenter 数据库密码
            <!--{elseif $key=='dbname'}-->
                UCenter 数据库名称
            <!--{elseif $key=='dbcharset'}-->
                UCenter 数据库字符集
            <!--{elseif $key=='dbtablepre'}-->
                UCenter 数据库表前缀
            <!--{elseif $key=='key'}-->
                与 UCenter 的通信密钥, 要与 UCenter 保持一致
            <!--{elseif $key=='api'}-->
                UCenter 的 URL 地址, 在调用头像时依赖此常量
            <!--{elseif $key=='charset'}-->
                UCenter 的字符集
            <!--{elseif $key=='ip'}-->
                UCenter 的 IP, 当 UC_CONNECT 为非 mysql 方式时, 并且当前应用服务器解析域名有问题时, 请设置此值
            <!--{elseif $key=='appid'}-->
                当前应用的 ID
            <!--{/if}-->
        </td>
    </tr>
    <!--{/foreach}-->
    <tr><td colspan="3"><input type="submit" class="btn" id="submit" name="submit" value="提交"></td></tr>
</table>
<!--{include file="admin/footer.tpl"}-->