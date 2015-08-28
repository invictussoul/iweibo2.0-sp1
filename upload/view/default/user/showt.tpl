<!doctype html>
<html>
    <head>
    <title>单条微博 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
    </head>
    <body>
    <!--{include file="common/header.tpl"}-->
<div class="wrapper content">
    <div class="fleft contentleft">
<div class="tcontainer">
<ul class="tmain">
  <li class="tmessage singlemessage" id="<!--{$msg.id}-->" data-time="<!--{$msg.timestamp}-->" >
    <div class="extra"></div>
    <div class="ttouxiang"><a href="/index/u/<!--{$msg.name}-->" title="<!--{$msg.nick}-->(@<!--{$msg.name}-->)"><img src="<!--{$msg.head}-->"  onerror="this.src=iwbResourceRoot+'resource/images/default_head_50.jpg'" /></a></div>
    <div class="tbody"><em class="icon_angleleft"></em>
        <a class="tname" href="/u/<!--{$msg.name}-->"><!--{$msg.nick}--></a>
        <!-- 腾讯认证 -->
        <!--{if $msg.isvip}-->
            <span class="icon_vip"></span>
        <!--{/if}-->
        <!-- 手机用户 -->
        <!--{if $msg.frommobile}-->
            <span class="icon_mobile"></span>
        <!--{/if}-->
        <!--{if $msg.type=="2"}-->
            <span class="zhuanbo">转播 </span>
        <!--{elseif $msg.type=="7"}-->
            <span class="zhuanbo">评论 </span>
        <!--{elseif $msg.type=="4"}-->
            <span class="zhuanbo">对 </span>
            <a href="/u/<!--{$msg.source.name}-->"><!--{$msg.source.nick}--></a>
            <!-- 腾讯认证 -->
            <!--{if ($msg.source.isvip && $authtype.platform) || ($msg.source.localauth && $authtype.local)}-->
                <span class="icon_vip"></span>
            <!--{/if}-->
            <span class="zhuanbo">说</span>
        <!--{/if}-->
        <span>:</span>
        <span><!--{$msg.text}--></span>
    <!-- 视频 -->
        <!--{if $msg.video}-->
            <a href="javascript:void(0);" class="iwbFlashVideo" data-title="<!--{$msg.video.title}-->" data-referer="<!--{$msg.video.realurl}-->" data-player="<!--{$msg.video.player}-->">
                <img class="videopreview" src="<!--{$msg.video.picurl}-->"/><em></em>
            </a>
        <!--{/if}-->
        <!-- 音乐 -->
        <!--{if $msg.music}-->
        <div class="iwbMusicControl">
            <a href="javascript:void(0);" class="iwbMusicPlayerBg iwbMusicPlayerBtn iwbMusicPlayerInvokeBtn" data-songArtist="<!--{$msg.music.author}-->" data-songName="<!--{$msg.music.title}-->" data-songUrl="<!--{$msg.music.url}-->"></a>
            <a href="javascript:void(0);" class="iwbMusicInfo"><!--{$msg.music.author}-->-<!--{$msg.music.title}--></a>
        </div>
        <!--{/if}-->
        <!-- 只显示原帖图片 -->
        <!--{if !$msg.source && $msg.image}-->
            <div class="iwbImageView">
                <div class="imageLoading">
                    <div class="imageLoadingIcon"></div>
               </div>
                   <img class="imageViewSmall" data-imageBig="<!--{$msg.image}-->/460" data-imageHuge="<!--{$msg.image}-->/2000" src="<!--{$msg.image}-->/160"/>
            </div>
        <!--{/if}-->
        <!-- 显示转播或者评论 -->
    <!--{if $msg.source && ($msg.type=="2" || $msg.type=="7")}-->
            <div class="tyinyong" data-innerid="<!--{$msg.source.id}-->">
                <a href="/u/<!--{$msg.source.name}-->"><!--{$msg.source.nick}--></a>
                <!-- 腾讯认证 -->
            <!--{if ($msg.source.isvip && $authtype.platform) || ($msg.source.localauth && $authtype.local)}-->
                    <span class="icon_vip"></span>
                <!--{/if}-->
                <!-- 手机用户 -->
                <!--{if $msg.source.frommobile}-->
                    <span class="icon_mobile"></span>
                <!--{/if}-->
                <!--{$msg.source.text}-->
                <!-- 显示图片 -->
                <!--{if $msg.source.image}-->
                <div class="iwbImageView">
                    <div class="imageLoading">
                        <div class="imageLoadingIcon"></div>
                   </div>
                       <img class="imageViewSmall" data-imageBig="<!--{$msg.source.image}-->/460" data-imageHuge="<!--{$msg.source.image}-->/2000" src="<!--{$msg.source.image}-->/160"/>
                </div>
              <!--{/if}-->
            <!-- 视频 -->
                <!--{if $msg.source.video}-->
                    <a href="javascript:void(0);" class="iwbFlashVideo" data-title="<!--{$msg.source.video.title}-->" data-referer="<!--{$msg.source.video.realurl}-->" data-player="<!--{$msg.source.video.player}-->">
                        <img class="videopreview" src="<!--{$msg.source.video.picurl}-->"/><em></em>
                    </a>
                <!--{/if}-->
                <!-- 音乐 -->
                <!--{if $msg.source.music}-->
                <div class="iwbMusicControl">
                    <a href="javascript:void(0);" class="iwbMusicPlayerBg iwbMusicPlayerBtn iwbMusicPlayerInvokeBtn" data-songArtist="<!--{$msg.source.music.author}-->" data-songName="<!--{$msg.source.music.title}-->" data-songUrl="<!--{$msg.source.music.url}-->"></a>
                    <a href="javascript:void(0);" class="iwbMusicInfo"><!--{$msg.source.music.author}-->-<!--{$msg.source.music.title}--></a>
                   </div>
                <!--{/if}-->
            </div>
        <!--{/if}-->
      <div class="tbottom">
        <div class="tbottomleft"><a class="time" id="<!--{$msg.timestamp}-->" data-favtime="<!--{$msg.favtimestamp}-->" href="/t/showt/tid/<!--{$msg.id}-->"><!--{$msg.timestring}--></a> 来自<!--{$msg.from}--></div>
        <div class="tbottomright">
            <a data-msgid="<!--{$msg.id}-->" data-reid="<!--{if $msg.source.id}--><!--{$msg.source.id}--><!--{else}--><!--{$msg.id}--><!--{/if}-->" data-count="<!--{$msg.count}-->" <!--{if $msg.source}-->data-name="<!--{$msg.name}-->" data-text="<!--{$msg.origtext}-->" <!--{/if}--> class="tzhuanbo" href="javascript:void(0);">转播</a>
                <span class="tactionsp">|</span>
                <a data-msgid="<!--{$msg.id}-->" data-reid="<!--{if $msg.source.id}--><!--{$msg.source.id}--><!--{else}--><!--{$msg.id}--><!--{/if}-->" data-mcount="<!--{$msg.mcount}-->" <!--{if $msg.source}-->data-name="<!--{$msg.name}-->" data-text="<!--{$msg.origtext}-->" <!--{/if}--> class="tdianping" href="javascript:void(0);">评论</a>
                <span class="tactionsp">|</span>
                <a href="javascript:void(0);" class="more">
                    <em>更多<small class="icon_down"></small></em>
                    <ul>
                        <!--{if $msg.name!=$username}--><li data-msgid="<!--{$msg.id}-->" data-reid="<!--{$msg.id}-->" data-nick="<!--{$msg.nick}-->" class="<!--{if $msg.name==$username}-->tshanchu<!--{else}-->tduihua<!--{/if}-->" href="javascript:void(0);"><!--{if $msg.name==$username}-->删除<!--{else}-->对话<!--{/if}--></li><!--{/if}-->
                         <!--{if $msg.name!=$username}--><li class="iwbJuBao" data-id="<!--{$msg.id}-->">举报</li><!--{/if}-->
                        <li data-msgid="<!--{$msg.id}-->" class="favaction <!--{if $msg.isfav}-->txshoucang<!--{else}-->tshoucang<!--{/if}-->"><!--{if $msg.isfav}-->取消收藏<!--{else}-->收藏<!--{/if}--></li>
                    </ul>
            </a>
        </div>
      </div>
    </div>
  </li>
</ul>
<!--{ if  isset($tall) && is_array($tall) && $tall }-->
<div class=" moduletitle5">
    <span class="fleft"><strong>转播和评论共<!--{$msg.count+$msg.mcount}-->条</strong></span>
</div>
<ul class="tmain hasnext">
<!--{foreach from=$tall item=ts }-->
<li class="tmessage">
    <div class="extra"></div>
    <div class="ttouxiang"><a href="/u/<!--{$ts.name}-->"  title="<!--{$ts.nick}-->(@/<!--{$ts.name}-->)"><img src="<!--{$ts.head}-->" onerror="this.src=iwbResourceRoot+'resource/images/default_head_50.jpg'"/></a></div>
    <div class="tbody"><a class="tname" href="/u/<!--{$ts.name}-->"><!--{$ts.nick}--></a>
    <!--{if ($ts.isvip && $authtype.platform) || ($ts.localauth && $authtype.local)}-->
    <em class="icon_vip"></em><!--{/if}-->
    <!--{ if $ts.frommobile }--><em class="icon_mobile"></em><!--{/if}-->
    <span class="zhuanbo"><!--{if $ts.type==7 }-->评论<!--{else}-->转播<!--{/if}--></span><span class="colon">:</span><span><!--{$ts.text}--></span>
      <div class="tbottom">
        <div class="tbottomleft"><a href="/t/showt/tid/<!--{$ts.id}-->"><!--{$ts.timestring}--></a>&nbsp;来自 <!--{$ts.from}--></div>
      </div>
    </div>
</li>
<!--{/foreach}-->
</ul>
<!--{/if}-->
</div>
<!--{include file="common/pagerwrapper3.tpl"}-->
        </div>
        <div class="fright contentright">
    <!--{if $msg.name==$username}-->
            <!--{include file="common/profile.tpl"}-->
            <div class="rightsp" ></div>
            <!--{include file="common/menus.tpl"}-->
            <div class="rightsp"></div>
        <!--{else}-->
            <!--{include file="common/uprofile2.tpl"}-->
        <!--{/if}-->
        </div>
    </div>
    <!--{include file="common/footer.tpl"}-->
    <script src="/resource/js/showt.js"></script>
    </body>
</html>