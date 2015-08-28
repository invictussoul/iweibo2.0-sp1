<div class="answerbox" id="<!--{$msg.msgid}-->">
    <em class="icon_blueangle"></em>
    <a href="/u/<!--{$amsg.name}-->" class="fleft userhead">
        <img src="<!--{$amsg.head}-->" width="50"/>
        <em class="icon_answer"></em>
    </a>
    <div class="tbody">
    <a class="tname" href="/u/<!--{$amsg.name}-->" title="<!--{$amsg.nick}-->(@<!--{$amsg.name}-->)"><!--{$amsg.nick}--></a>
    <!--{if ($amsg.isvip && $authtype.platform) || ($amsg.localauth && $authtype.local)}-->
    <span class="icon_vip"></span>
    <!--{/if}-->
    <span class="renzheng"></span><span class="colon">:</span>
    <span><!--{$amsg.text}--></span>
      <div class="tbottom">
        <div class="tbottomleft">
        <a class="time" href="/t/showt/tid/<!--{$amsg.msgid}-->"><!--{$amsg.timestring}--></a>
        来自<!--{$amsg.from}-->
        </div>
      </div>
    </div>
</div>