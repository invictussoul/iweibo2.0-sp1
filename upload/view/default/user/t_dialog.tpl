<!doctype html>
<html>
    <head>
    <title>对话 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
    </head>
    <body>
    <!--{include file="common/header.tpl"}-->
    <div class="wrapper content">
        <div class="contentleft fleft">
            <div class="tcontainer">
                <div class="moduletitle3">
                    <span class="fleft"><strong>相关对话：</strong><strong class="gray"><!--{$listcount}-->条</strong></span>
                </div>
            </div>
            <ul class="tmain dialoglist">
                <!--{foreach key=key item=msg from=$msglist}-->
                    <li class="tmessage singlemessage">
                        <div class="extra"></div>
                        <div class="ttouxiang">
                            <a href="/u/<!--{$msg.name}-->" title="<!--{$msg.nick}-->(@<!--{$msg.name}-->)">
                                <img data-identity="<!--{$msg.id}-->" data-cardname="<!--{$msg.name}-->" class="iwbUsercardControl" src="<!--{$msg.head}-->" />
                            </a>
                        </div>
                        <div class="tbody">
                            <em class="icon_angleleft"></em>
                            <a class="tname" href="/u/<!--{$msg.name}-->"><!--{$msg.nick}--></a>
                            <!--{if $msg.isvip}-->
                                <span class="icon_vip"></span>
                            <!--{/if}-->
                            <span class="colon">说:</span>
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
                          <div class="tbottom">
                            <div class="tbottomleft">
                                <a class="time" id="<!--{$msg.timestamp}-->" data-favtime="<!--{$msg.favtimestamp}-->"
                                                    href="/t/showt/tid/<!--{$msg.id}-->"><!--{$msg.timestring}--></a>
                                                                                    来自<!--{$msg.from}-->
                            </div>
                          </div>
                        </div>
                      </li>
                <!--{/foreach}-->
            </ul>
            <!--{if $hasnext===0}-->
                <!--{include file="common/pagerwrapper3.tpl"}-->
            <!--{/if}-->
        </div>
    </div>
    <!--{include file="common/footcontrol.tpl"}-->
    <!--{include file="common/footer.tpl"}-->
    <script src="/resource/js/index.js"></script>
    </body>
</html>