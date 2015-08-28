<div class="sendboxcontainer">
<div class="sendbox">
<label class="sendboxtitle"></label>
<form action="/index/t/add" id="sendTweet" target="sendTweet" method="post" enctype="multipart/form-data">
<input type="hidden" name="type" value="1" />
<input type="hidden" name="callback" value="parent.onSendboxResponse" />
<input type="hidden" name="format" value="html" />
<input type="hidden" name="music" value=""/>
<input type="hidden" name="video" value=""/>
<div class="holder">
<div class="holderhighlight"></div>
<textarea id="msgTxt" class="iwbFriendControlInput" autocorrect="off" autocapitalize="off" autocomplete="off" spellcheck="false" data-default="<!--{$defaulttext}-->" name="content"<!--{if !$defaulttext}--> placeholder="来，说说你在做什么，想什么"<!--{/if}-->></textarea>
</div>
<div class="actionlist">
<div class="first"><b class="huati"></b><a href="javascript:void(0);" id="newTopic" class="topictxt" title="创建/加入话题讨论，汇聚相同热点广播">话题</a></div>
<div><b class="zhaopian"></b><a href="javascript:void(0);" class="zhaopiantxt" id="zhaopiantxt" >照片<input type="file" id="uploadPic" name="pic" title="可选择jpg、jpeg、gif、png格式，文件小于2M"/></a></div>
<div><b class="pengyou"></b><a href="javascript:void(0);" class="pengyoutxt iwbFriendControlBtn" data-for="#msgTxt" id="pengyoutxt"  title="@朋友帐号就可以提到他" onfocus="this.blur();">朋友</a></div>
<div><b class="shipin"></b><a href="javascript:void(0);" class="shipintxt iwbAddVideoBtn" id="shipintxt"  title="腾讯、土豆、优酷、PPTV可直接播放" data-identity="mainsendboxvideo" onfocus="this.blur();">视频</a><span class="videopreviewwrapper hide"><a class="iwbImagePreviewControl gray vmiddle" href="javascript:void(0);" id="sendboxvideotitle"></a><span class="del"></span></span></div>
<div><b class="yinyue"></b><a href="javascript:void(0);" class="yinyuetxt iwbAddMusicBtn" id="yinyuetxt"  title="支持mp3,ogg,wma等格式的音乐" data-identity="mainsendboxmusic" onfocus="this.blur();">音乐</a></div>
<div><b class="biaoqing"></b><a href="javascript:void(0);" class="biaoqingtxt iwbEmotesBtn" data-for="#msgTxt" id="biaoqingtxt"  title="表情" onfocus="this.blur();">表情</a><span class="usebtns cancelMusic" title="删除"></span></div>
</div>
</form>
<iframe class="hide" name="sendTweet"></iframe>
<div class="sendcontrol">
<span class="sendmsg" id="sendmsgtip">还能输入<big>140</big>字</span>
<input class="sendbtn" id="sendbtn" title="快捷键 Ctrl+Enter" type="button"/>
</div>
</div>
</div>