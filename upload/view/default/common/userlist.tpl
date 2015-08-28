<ul class="userlist">
<!--{foreach key=key item=idol from=$idollist}-->
<li>
    <p><a href="/u/<!--{$idol.name}-->" target="_blank">
        <img src="<!--{$idol.head}-->"  onerror="this.src=iwbResourceRoot+'resource/images/default_head_50.jpg'" ></a></p>
    <h3><a href="/u/<!--{$idol.name}-->" title="<!--{$idol.nick}-->"><!--{$idol.nick}--></a></h3>
    <div><!--{if $idol.name != $user.name}--><a data-name="<!--{$idol.name}-->" href="javascript:void(0);" data-type="<!--{if $idol.isidol}-->0<!--{else}-->1<!--{/if}-->" data-styleid="1" class="iwbFollowControl follow <!--{if $idol.isidol}-->unfollow<!--{/if}-->"></a><!--{/if}--></div>
</li>
<!--{/foreach}-->
</ul>
<!--{if $idollist|@count == 12}-->
    <div class="viewmore">
        <a href="/friend/following/uname/<!--{$guest.name}-->" class="down">查看更多&gt;&gt;</a>
    </div>
<!--{/if}-->