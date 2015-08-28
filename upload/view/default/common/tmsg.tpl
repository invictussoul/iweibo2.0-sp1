<!--{ if empty($msg.visiblecode) }-->
<li class="tmessage" id="<!--{$msg.id}-->" data-time="<!--{$msg.timestamp}-->">
    <div class="extra"></div>
    <div class="ttouxiang">
        <a href="/u/<!--{$msg.name}-->" data-identity="<!--{$msg.id}-->" data-cardname="<!--{$msg.name}-->" class="timelineUsercard" <!--{if $username == $msg.name}-->title="<!--{$msg.nick}-->(@<!--{$msg.name}-->)"<!--{/if}-->>
            <img src="<!--{$msg.head}-->" onerror="this.src=iwbResourceRoot+'resource/images/default_head_50.jpg'"/>
        </a>
    </div>
    <div class="tbody">
        <a class="tname" href="/u/<!--{$msg.name}-->"><!--{$msg.nick}--></a>
        <!--{if ($msg.isvip && $authtype.platform) || ($msg.localauth && $authtype.local)}-->
            <span class="icon_vip"></span>
        <!--{/if}-->
        <!--{if $msg.type=="2"}-->
            <span class="zhuanbo">转播 </span>
        <!--{elseif $msg.type=="7"}-->
            <span class="zhuanbo">评论 </span>
        <!--{elseif $msg.type=="4"}-->
            <span class="zhuanbo">对 </span>
            <a href="/u/<!--{$msg.source.name}-->"><!--{$msg.source.nick}--></a>
            <!--{if ($msg.source.isvip && $authtype.platform) || ($msg.source.localauth && $authtype.local)}-->
                <span class="icon_vip"></span>
            <!--{/if}-->
            <span class="zhuanbo">说</span>
        <!--{/if}-->
        <span>:</span>
        <span><!--{$msg.text}--></span>
        <!--{if $msg.video}-->
            <div>
            <a href="javascript:void(0);" class="iwbFlashVideo" data-title="<!--{$msg.video.title}-->" data-referer="<!--{$msg.video.realurl}-->" data-player="<!--{$msg.video.player}-->">
                <img class="videopreview" src="<!--{$msg.video.picurl}-->"/><em></em>
            </a>
            </div>
        <!--{/if}-->
        <!--{if $msg.music}-->
        <div class="iwbMusicControl">
            <a href="javascript:void(0);" class="iwbMusicPlayerBg iwbMusicPlayerBtn iwbMusicPlayerInvokeBtn" data-songArtist="<!--{$msg.music.author}-->" data-songName="<!--{$msg.music.title}-->" data-songUrl="<!--{$msg.music.url}-->"></a>
            <a href="javascript:void(0);" class="iwbMusicInfo"><!--{$msg.music.author}-->-<!--{$msg.music.title}--></a>
        </div>
        <!--{/if}-->
        <!--{if !$msg.source && $msg.image}-->
            <div class="iwbImageView">
                <div class="imageLoading">
                    <div class="imageLoadingIcon"></div>
               </div>
                   <img class="imageViewSmall" data-imageBig="<!--{$msg.image}-->/460" data-imageHuge="<!--{$msg.image}-->/2000" src="<!--{$msg.image}-->/160"/>
            </div>
        <!--{/if}-->
        <!--{if $msg.source && ($msg.type=="2" || $msg.type=="7")}-->
            <div class="tyinyong" data-innerid="<!--{$msg.source.id}-->">
                <a href="/u/<!--{$msg.source.name}-->"><!--{$msg.source.nick}--></a>
                <!--{if ($msg.source.isvip && $authtype.platform) || ($msg.source.localauth && $authtype.local)}-->
                    <span class="icon_vip"></span>
                <!--{/if}-->
                <!--{$msg.source.text}-->
                <!--{if $msg.source.image}-->
                <div class="iwbImageView">
                    <div class="imageLoading">
                        <div class="imageLoadingIcon"></div>
                   </div>
                       <img class="imageViewSmall" data-imageBig="<!--{$msg.source.image}-->/460" data-imageHuge="<!--{$msg.source.image}-->/2000" src="<!--{$msg.source.image}-->/160"/>
                </div>
                <!--{/if}-->
                <!--{if $msg.source.video}-->
                    <div>
                    <a href="javascript:void(0);" class="iwbFlashVideo" data-title="<!--{$msg.source.video.title}-->" data-referer="<!--{$msg.source.video.realurl}-->" data-player="<!--{$msg.source.video.player}-->">
                        <img class="videopreview" src="<!--{$msg.source.video.picurl}-->"/><em></em>
                    </a>
                    </div>
                <!--{/if}-->
                <!--{if $msg.source.music}-->
                <div class="iwbMusicControl">
                    <a href="javascript:void(0);" class="iwbMusicPlayerBg iwbMusicPlayerBtn iwbMusicPlayerInvokeBtn" data-songArtist="<!--{$msg.source.music.author}-->" data-songName="<!--{$msg.source.music.title}-->" data-songUrl="<!--{$msg.source.music.url}-->"></a>
                    <a href="javascript:void(0);" class="iwbMusicInfo"><!--{$msg.source.music.author}-->-<!--{$msg.source.music.title}--></a>
                   </div>
                <!--{/if}-->
                <div class="tbottomleft">
                    <a class="time" data-time="<!--{$msg.source.timestamp}-->" href="/t/showt/tid/<!--{$msg.source.id}-->"><!--{$msg.source.timestring}--></a>
                    来自<!--{$msg.source.from}-->
                    <!--{if $msg.source.count+$msg.source.mcount>0}-->
                        <a data-msgid="<!--{$msg.id}-->" data-reid="<!--{$msg.source.id}-->" data-name="<!--{$msg.name}-->" data-text="<!--{$msg.origtext}-->" class="tchakan" href="javascript:void(0);">查看转播和评论(<b><!--{$msg.source.count+$msg.source.mcount}--></b>)</a>
                    <!--{/if}-->
                </div>
            </div>
        <!--{/if}-->
        <div class="tbottom">
            <div class="tbottomleft">
                <a class="time" id="<!--{$msg.timestamp}-->" data-favtime="<!--{$msg.favtimestamp}-->"
                    href="/t/showt/tid/<!--{$msg.id}-->"><!--{$msg.timestring}--></a>
                来自<!--{$msg.from}-->
                <!--{if $msg.type=="4"}-->
                    <a class="chakanduihua" href="/t/dialog/tid/<!--{$msg.id}-->">查看对话</a>
                <!--{/if}-->
                <!--{if $msg.count+$msg.mcount>0}-->
                    <a data-msgid="<!--{$msg.id}-->" data-reid="<!--{$msg.id}-->" class="tchakan" href="javascript:void(0);">查看转播和评论(<b><!--{$msg.count+$msg.mcount}--></b>)</a>
                <!--{/if}-->
            </div>
            <div class="tbottomright">
                <a data-msgid="<!--{$msg.id}-->" data-reid="<!--{if $msg.source.id}--><!--{$msg.source.id}--><!--{else}--><!--{$msg.id}--><!--{/if}-->" data-count="<!--{$msg.count}-->" <!--{if $msg.source}-->data-name="<!--{$msg.name}-->" data-text="<!--{$msg.origtext}-->" <!--{/if}--> class="tzhuanbo" href="javascript:void(0);">转播</a>
                <span class="tactionsp">|</span>
                <a data-msgid="<!--{$msg.id}-->" data-reid="<!--{if $msg.source.id}--><!--{$msg.source.id}--><!--{else}--><!--{$msg.id}--><!--{/if}-->" data-mcount="<!--{$msg.mcount}-->" <!--{if $msg.source}-->data-name="<!--{$msg.name}-->" data-text="<!--{$msg.origtext}-->" <!--{/if}--> class="tdianping" href="javascript:void(0);">评论</a>
                <span class="tactionsp">|</span>
                <a href="javascript:void(0);return false;" class="more" onclick="return false;">
                    <em>更多<small class="icon_down"></small></em>
                    <ul>
                        <li data-msgid="<!--{$msg.id}-->" data-reid="<!--{$msg.id}-->" data-nick="<!--{$msg.nick}-->" class="<!--{if $msg.name==$user.name}-->tshanchu<!--{else}-->tduihua<!--{/if}-->" href="javascript:void(0);"><!--{if $msg.name==$user.name}-->删除<!--{else}-->对话<!--{/if}--></li>
                        <li data-href="/t/showt/tid/<!--{$msg.id}-->" onclick='javascript:window.location.href="/t/showt/tid/<!--{$msg.id}-->";'>详情</li>
                        <!--{if $msg.name!=$user.name}--><li class="iwbJuBao" data-id="<!--{$msg.id}-->">举报</li><!--{/if}-->
                        <li data-msgid="<!--{$msg.id}-->" class="favaction <!--{if $msg.isfav}-->txshoucang<!--{else}-->tshoucang<!--{/if}-->"><!--{if $msg.isfav}-->取消收藏<!--{else}-->收藏<!--{/if}--></li>
                    </ul>
                </a>
            </div>
           </div>
       </div>
</li>
<!--{/if}-->