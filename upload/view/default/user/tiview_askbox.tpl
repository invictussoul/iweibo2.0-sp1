<div class="askbox" id="<!--{$msg.msgid}-->">
    <a href="/u/<!--{$msg.name}-->" class="fleft userhead">
        <img src="<!--{$msg.head}-->" width="50"/>
        <em class="icon_ask"></em>
    </a>
    <div class="tbody">
    <a class="tname" href="/u/<!--{$msg.name}-->" title="<!--{$msg.nick}-->(@<!--{$msg.name}-->)"><!--{$msg.nick}--></a> 
    <!--{if ($msg.isvip && $authtype.platform) || ($msg.localauth && $authtype.local)}-->
    <span class="icon_vip"></span>
    <!--{/if}-->
    <span class="renzheng"></span><span class="colon">:</span>
    <span><!--{$msg.text}--></span>
      <div class="tbottom">
        <div class="tbottomleft"> <a class="time" href="/t/showt/tid/<!--{$msg.msgid}-->"><!--{$msg.timestring}--></a> 来自<!--{$msg.from}--></div>
        <!--{if isset($_action) && $_action == 'view'}--><div class="tbottomright"><a class="tanswer" data-reid="<!--{$msg.msgid}-->" href="javascript:void(0);" >回答</a> </div><!--{/if}-->
      </div>
    </div>
</div>