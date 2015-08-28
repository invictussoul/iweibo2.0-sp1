<!--{foreach name=msglist key=key item=msg from=$msglist}-->
    <!--{ if empty($msg.visiblecode) }-->
            <div class="tinterview">
                <!--{include file="user/tiview_askbox.tpl"}-->
                <!--{if isset($msg.answer) && !empty($msg.answer)}-->
                <!--{foreach key=key item=amsg from=$msg.answer}-->
                    <!--{ if empty($amsg.visiblecode) }-->
                        <!--{include file="user/tiview_answerbox.tpl"}-->
                    <!--{/if}-->
                <!--{/foreach}-->
                <!--{/if}-->
            </div>
    <!--{/if}-->
<!--{/foreach}-->