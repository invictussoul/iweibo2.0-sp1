<li id="<!--{$box.id}-->">
        <h6>发送给 <a href="/u/<!--{$box.toname}-->"><!--{$box.tonick}--></a> &nbsp;<!--{if $box.is_auth}--><em class="icon_vip"></em><!--{/if}-->： </h6>
        <p> <!--{$box.text}--></p>
        <div><!--{$box.timestring}--></div>
        <span class="action">
        <a href="javascript:void(0);" data-receiver="<!--{$box.toname}-->" class="sendmail button button_gray">再写一封</a>
        <a href="javascript:void(0);" data-msgid="<!--{$box.id}-->" class="deletemail button button_gray">删除</a>
        </span>
    <div class="extra"></div>
</li>