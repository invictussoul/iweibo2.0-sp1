<!--{if (!empty($auth.local) and !empty($guest.localauth)) || (!empty($auth.platform) and ($guest.isvip)) }-->
<div class="vipinfo">
<!--{if $auth.local}-->
    <!--{if $guest.localauth}-->
        <h2 class="tencent"><label><!--{$auth.localtext}--></label></h2>
        <p><!--{$guest.localauthtext}--></p>
    <!--{/if}-->
<!--{/if}-->
<!--{if $auth.platform}-->
    <!--{if $guest.isvip}-->
        <h2><label>腾讯认证资料</label></h2>
        <p><!--{$guest.verifyinfo}--></p>
    <!--{/if}-->
<!--{/if}-->
</div>
<!--{/if}-->
<div class="uprofile2">
<h2><!--{if $guest.sex==2}-->她<!--{else}-->他<!--{/if}-->的资料</h2>
<div><!--{if $guest.sex==2}-->女<!--{else}-->男<!--{/if}--><br>
<!--{if $guest.birth_month}--><!--{$guest.birth_month}-->月出生 <br><!--{/if}-->
<!--{if $guest.location}-->现居：<!--{$guest.location}--> <br><!--{/if}-->
<!--{if $guest.introduction}-->介绍：<!--{$guest.introduction}--><!--{/if}-->
</div>
<div><!--{if $guest.tag}--> 属于<!--{if $guest.sex==2}-->她<!--{else}-->他<!--{/if}-->的标签有： <br>
<!--{foreach key=key item=tag from=$guest.tag}--> <a href="/search/tag/k/<!--{$tag.name}-->"><!--{$tag.name}--></a> <!--{/foreach}-->
<!--{/if}--></div>
</div>