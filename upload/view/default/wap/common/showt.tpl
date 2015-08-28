<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>iweibo - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="wap/common/style.tpl"}-->
</head>
<body>
<!--{include file="wap/common/top.tpl"}-->
<form id="form1" name="form1" method="post" action="/wap/t/add"  enctype="multipart/form-data"  >
    <p>
    <!--{if !empty($searchkey)}--><label>针对“<!--{$searchkey}-->”说点什么：(140字以内)</label><!--{else}-->说说新鲜事儿，140字以内<!--{/if}--></P>
    <textarea name="content"><!--{if $searchkey}-->#<!--{$searchkey}-->#<!--{/if}--></textarea>
    <p class="padt">
    <input type="submit" value="广播" class="button button_blue"/>
    <!--{if isset($sendbox.tid)}--><input type="hidden" value="<!--{$sendbox.tid}-->" name="reid"><!--{/if}-->
    <!--{if isset($sendbox.type)}--><input type="hidden" value="<!--{$sendbox.type}-->" name="type"><!--{/if}-->
    </p>
    <!--{if $backurl}--><input type="hidden" name="backurl" value="<!--{$backurl}-->"/><!--{/if}-->
</form>
<br/>
<ul class="t">
    <li>
        <a href="/wap/u/<!--{$msg.name}-->"><!--{$msg.nick}--></a>
        <!--{if $msg.isvip}-->
        <img src="/resource/images/vip.gif"/>
        <!--{/if}-->：
        <!--{if $msg.type=="2"}-->
            <span class="zhuanbo">转播 </span><br/>
        <!--{elseif $msg.type=="7"}-->
            <span class="zhuanbo">评论 </span><br/>
        <!--{elseif $msg.type=="4"}-->
            <span class="zhuanbo">对 </span>
            <a href="/wap/u/<!--{$msg.source.name}-->"><!--{$msg.source.nick}--></a>
            <!-- 腾讯认证 -->
            <!--{if $msg.source.isvip}-->
                <img src="/resource/images/vip.gif"/>
            <!--{/if}-->
            <span class="zhuanbo">说</span><br/>
        <!--{/if}-->
        <!--{$msg.text}-->
        <!-- 视频 -->
        <!--{if $msg.video}-->
            [视频]<a href="<!--{$msg.video.realurl}-->"><!--{$msg.video.title}--></a>
        <!--{/if}-->
        <!-- 音乐 -->
        <!--{if $msg.music}-->
            [音乐]<a href="<!--{$msg.music.url}-->"><!--{$msg.music.url}--></a>
        <!--{/if}-->
        <!-- 只显示原帖图片 -->
        <!--{if !$msg.source && $msg.image}-->
            <br><img src="<!--{$msg.image}-->/120" valign="top"/> <a href="/wap/index/img/url/<!--{$msg.image|iurlencode}-->/rurl/<!--{$pathinfo|iurlencode}-->">大图</a>
        <!--{/if}-->
        <!-- 显示转播或者评论 -->
        <!--{if $msg.source && ($msg.type=="2" || $msg.type=="7")}-->
            【原文<a href="/wap/u/<!--{$msg.source.name}-->"><!--{$msg.source.nick}--></a>
            <!-- 腾讯认证 -->
            <!--{if $msg.source.isvip}-->
                <img src="/resource/images/vip.gif"/>
            <!--{/if}-->
            <!--{$msg.source.text}-->
            <!-- 视频 -->
            <!--{if $msg.source.video}-->
                [视频]<a href="<!--{$msg.video.realurl}-->"><!--{$msg.video.title}--></a>
            <!--{/if}-->
            <!-- 音乐 -->
            <!--{if $msg.source.music}-->
                [音乐]<a href="<!--{$msg.music.url}-->"><!--{$msg.music.url}--></a>
            <!--{/if}-->】
            <!-- 显示图片 -->
            <!--{if $msg.source.image}-->
                <br><img src="<!--{$msg.source.image}-->/120" valign="top"/> <a href="/wap/index/img/url/<!--{$msg.source.image|iurlencode}-->/rurl/<!--{$pathinfo|iurlencode}-->">大图</a>
            <!--{/if}-->
        <!--{/if}-->
                （<!--{$msg.timestring}-->来自<!--{$msg.from}-->）
   </li>
</ul>
<br/>
<!--{ if  isset($tall) && is_array($tall) && $tall }-->
<ul class="t">
<li ><strong>转播和评论共<!--{$msg.count+$msg.mcount}-->条</strong></li>
<!--{foreach from=$tall item=ts }-->
<li >
   <!-- <span class="ttouxiang"><a href="/wap/u/<!--{$ts.name}-->"  title="<!--{$ts.nick}-->(@/<!--{$ts.name}-->)"><img src="<!--{$ts.head}-->"></a></span>-->
    <span class="tbody"><a class="tname" href="/wap/u/<!--{$ts.name}-->"><!--{$ts.nick}--></a>
    <!--{ if $ts.isvip }--><em class="icon_vip"><!--{$ts.isvip}--></em><!--{/if}-->
    <!--{ if $ts.frommobile }--><em class="icon_mobile"></em><!--{/if}-->
    <span class="zhuanbo"><!--{if $ts.type==7 }-->评论<!--{else}-->转播<!--{/if}--></span><span class="colon">:</span><span><!--{$ts.text}--></span>
    <br/>
      <span>
        <span><a href="/wap/t/showt/tid/<!--{$ts.id}-->/type/2"><!--{$ts.timestring}--></a>&nbsp;来自 <!--{$ts.from}--></span>
      </span>
    </span>
</li>
<!--{/foreach}-->
</ul>
<!--{include file="common/pagerwrapper3.tpl"}-->
<!--{/if}-->
<br/>
<!--{include file="wap/common/top.tpl"}-->
</body>
</html>