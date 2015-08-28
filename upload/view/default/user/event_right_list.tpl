<a href="/event/new" class="activitybtn"></a>
<!--{if !empty($newents)}-->
    <div  class="toggle">
        最近活动 <A href="/event/index"  class="more" style="color:#2B4A78">更多<code>&gt;&gt;</code></a>
    </div>
    <!--{foreach name=rlist key=k item=ent from=$newents}-->
    <ul class="activitylist">
        <li><a href="/event/view/id/<!--{$ent.Id}-->"><!--{$ent.title}--></a>
            <p <!--{if $smarty.foreach.rlist.index == 0}--> style="display:block"<!--{else}-->style="display:none"<!--{/if}-->><em class="icon_angle"></em>
                <strong>时间：</strong><!--{$ent.sdate}--><br/>
                <strong>地点：</strong><!--{$ent.addr}--><br/>
                <strong>参与人数：</strong><!--{$ent.joins}--> 人<br/>
                <strong>活动简介：</strong><!--{$ent.message}--><br/>
            </p>
        </li>
    </ul>
    <!--{/foreach}-->
<!--{/if}-->