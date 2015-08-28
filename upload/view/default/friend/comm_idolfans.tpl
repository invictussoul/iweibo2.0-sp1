<ul class="userlist4">
<!--{foreach from=$friends item=item}-->
<li>
  <div class="fleft"><a href="/u/<!--{$item.name}-->"><img src="<!--{$item.head}-->"  onerror="this.src=iwbResourceRoot+'resource/images/default_head_50.jpg'"></a></div>
  <div class="fright">
  <!--{if $username != $item.name}-->
          <!--{if $item.isidol}-->
        <a title="取消收听" class="iwbFollowControl followbtn unfollowbtn" data-type="0" data-styleid="0" data-name="<!--{$item.name}-->" href="javascript:void(0);"></a>
        <!--{else}-->
        <a title="立即收听" class="iwbFollowControl followbtn" data-type="1" data-styleid="0" data-name="<!--{$item.name}-->" href="javascript:void(0);"></a>
        <!--{/if}-->
<!--{/if}-->
        <h6> <a href="/u/<!--{$item.name}-->"><!--{$item.nick}--></a><!--{if $item.is_auth}--><span class="icon_vip"></span><!--{/if}--><span>(@<!--{$item.name}-->)</span> </h6>
        <div><!--{foreach from=$item.tag item=it}--><a href="/search/tag/k/<!--{$it.name}-->"><!--{$it.name}--></a>  <!--{/foreach}--></div>
        <p><a href="/t/showt/tid/<!--{$item.tweet[0].id}-->"><!--{$item.tweet[0].timestring}--></a>        <!--{if $item.tweet[0].from}-->来自<!--{$item.tweet[0].from}--><!--{/if}--></p>
        <p><!--{$item.tweet[0].text}--></p>
        <div>听众<a href="/friend/follower/uname/<!--{$item.name}-->"><!--{$item.fansnum}--></a>人    收听<a href="/friend/following/uname/<!--{$item.name}-->"><!--{$item.idolnum}--></a>人</div>
    </div>
</li>
<!--{/foreach}-->
</ul>