<div class="uprofile">
    <div class="fleft">
        <a href="javascript:" title="<!--{$guest.nick}-->(@<!--{$guest.name}-->)">
            <img src="<!--{$guest.head}-->" width="120" height="120"  onerror="this.src=iwbResourceRoot+'resource/images/default_head_120.jpg'" /></a>
    </div>
    <div class="fright">
        <h1><!--{$guest.nick}-->
            <!--{if ($guest.is_auth)}-->
                <span class="icon_vip"></span>
            <!--{/if}-->        
            <span class="gray">(@<!--{$guest.name}-->)</span>
            <!--<a href="javascript:">[添加备注]</a>--></h1>
        <div><a href="<!--{$_pathroot}-->u/<!--{$guest.name}-->"><!--{$_pathroot}-->u/<!--{$guest.name}--></a></div>
        <p><span>广播<a href="/u/<!--{$guest.name}-->"><!--{$guest.tweetnum}--></a>条</span> |
            <span>听众<a href="/friend/follower/uname/<!--{$guest.name}-->"><!--{$guest.fansnum}--></a>人</span> |
            <span><!--{if $guest.sex==2}-->她<!--{else}-->他<!--{/if}-->收听<a href="/friend/following/uname/<!--{$guest.name}-->"><!--{$guest.idolnum}--></a>人</span></p>
        <div>
        <!--{if $guest.isidol}-->
            <a href="javascript:void(0);" class="iwbFollowControl followbtn unfollowbtn" title="取消收听" data-type="0" data-styleid="0" data-name="<!--{$guest.name}-->"></a> &nbsp;
        <!--{else}-->
            <a href="javascript:void(0);" class="iwbFollowControl followbtn" title="立即收听" data-type="1" data-styleid="0" data-name="<!--{$guest.name}-->"></a> &nbsp;
        <!--{/if}-->
        <a href="javascript:void(0);" data-name="<!--{$guest.name}-->" data-nick="<!--{$guest.nick}-->" title="与<!--{$guest.nick}-->对话" class="iwbTalkBtn button_talk"></a> &nbsp;
        <a href="javascript:void(0);" data-name="<!--{$guest.name}-->" class="button_report iwbJuBao" onclick="return false;" title="举报"></a>
        <!--<a href="javascript:" title="更多" class="button_more"></a> &nbsp;-->
        </div>
    </div>
</div>