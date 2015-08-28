<!--{if $recents.events}-->
    <!--{foreach key=k item=ent from=$recents.events}-->
    <div class="activity">
        <div class="fleft userhead">
            <a href="/event/view/id/<!--{$ent.id}-->"><img src="<!--{if $ent.pic}--><!--{$ent.pic}--><!--{else}--><!--{$_resource}-->resource/images/default_event.jpg<!--{/if}-->" width="100"/></a>
        </div>
        <div class="fright">
            <h1><a href="/event/view/id/<!--{$ent.id}-->"><!--{$ent.title}--></a></h1>
            <ul>
                <li><strong>时间：</strong><!--{$ent.sdate}--> - <!--{$ent.edate}--></li>
                <li><strong>地点：</strong><adress><!--{$ent.addr}--></adress></li>
                <li class="fleft"><strong>发起者：</strong><a href="/u/<!--{$ent.host.name}-->" title="<!--{$ent.host.nick}-->(@<!--{$ent.host.name}-->)" target="_blank"><!--{$ent.host.nick}--></a></li>
                <li class="fleft"><strong>联系方式：</strong><!--{$ent.phone}--></li>
                <li class="fleft"><strong>状态：</strong><!--{$ent.statusText}--></li>
                <li class="fleft"><strong>参加人数：</strong><a href="/event/members/id/<!--{$ent.id}-->" class="gray"><!--{$ent.joins}--></a> 人</li>
                <li><strong>内容：</strong><!--{$ent.message}--></li>
            </ul>
        </div>
    </div>
    <div class="extra"></div>
    <!--{/foreach}-->
    <!--{if $recents.size < $recents.total}-->
    <div class="pagerwrapper">
        <div class="pagebar">
            <!--{if $recents.pid > 1}-->
            <span class="fleft"><a href="/event/index/p/<!--{$recents.pid-1}-->">&lt;&lt;上一页</a></span>
            <!--{/if}-->
            <!--{if $recents.pid < $recents.maxPage}-->
            <span class="fright"><a href="/event/index/p/<!--{$recents.pid+1}-->">下一页&gt;&gt;</a></span>
            <!--{/if}-->
        </div>
    </div>
    <!--{/if}-->
<!--{else}-->
    <div class="norecord">暂无推荐活动</div>
<!--{/if}-->