<div class="profile">
    <a class="head fleft" href="/setting/face" title="<!--{$user.nick}-->(@<!--{$user.name}-->)"><img src="<!--{$user.head}-->" width=100 height=100  onerror="this.src=iwbResourceRoot+'resource/images/default_head_100.jpg'" /></a>
    <div class="info fleft">
        <span>听众<a href="/friend/follower" class="newnum" id="newfans" style="display:none;"><big></big><em>12</em><small></small></a></span><a class="usehovereffect" href="/friend/follower"><!--{$user.fansnum}--></a>
        <span>收听<a href="/friend/following" class="newnum"></a></span><a class="usehovereffect" href="/friend/following"><!--{$user.idolnum}--></a>
        <span class="last">广播<a href="/wap/mine" class="newnum"></a></span><a class="usehovereffect" href="/mine"><!--{$user.tweetnum}--></a>
    </div>
    <div class="namelink">
        <span><!--{$user.nick}--></span>
        <a href="<!--{$userurl.origin}-->" title="<!--{$userurl.origin}-->"><!--{$userurl.short}--></a>
    </div>
</div>
<!--{if (!empty($auth.local) and !empty($user.localauth)) || (!empty($auth.platform) and ($user.isvip)) }-->
<div class="vipinfo">
<!--{if $auth.local}-->
    <!--{if $user.localauth}-->
        <h2 class="tencent"><label><!--{$auth.localtext}--></label></h2>
        <p><!--{$user.localauthtext}--></p>
    <!--{/if}-->
<!--{/if}-->
<!--{if $auth.platform}-->
    <!--{if $user.isvip}-->
        <h2><label>认证资料</label></h2>
        <p><!--{$user.verifyinfo}--></p>
    <!--{/if}-->
<!--{/if}-->
</div>
<!--{/if}-->