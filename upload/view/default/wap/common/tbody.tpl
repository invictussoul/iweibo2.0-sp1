<ul class="t">
    <!--{foreach key=key item=msg from=$msglist}-->
    <!--{if $msg.visiblecode!=1}-->
    <li>
        <a href="/wap/u/<!--{$msg.name}-->"><!--{$msg.nick}--></a>
        <!--{if ($msg.isvip && $authtype.platform) || ($msg.localauth && $authtype.local)}-->
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
            <!--{if ($msg.source.isvip && $authtype.platform) || ($msg.source.localauth && $authtype.local)}-->
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
            <!--{if ($msg.source.isvip && $authtype.platform) || ($msg.source.localauth && $authtype.local)}-->
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
        <br>
        <a href="/wap/t/showt/tid/<!--{$msg.id}-->/type/2">转播</a>
        <a href="/wap/t/showt/tid/<!--{$msg.id}-->/type/4">评论</a>
        <!--{if $msg.name==$user.name}-->
        <a href="/wap/t/del/tid/<!--{$msg.id}-->">删除</a>
        <!--{else}-->
        <a href="/wap/t/showt/tid/<!--{$msg.id}-->/type/3">对话</a>
        <!--{/if}-->
        <!--{if $msg.isfav}--><a href="/wap/favor/t/tid/<!--{$msg.id}-->/type/0">取消收藏</a><!--{else}--><a href="/wap/favor/t/tid/<!--{$msg.id}-->/type/1">收藏</a><!--{/if}-->
    </li>
    <!--{/if}-->
    <!--{/foreach}-->
</ul>