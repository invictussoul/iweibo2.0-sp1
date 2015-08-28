<div class="menus">
    <div class="menu menu1 <!--{if $active=="index"}-->active<!--{/if}-->"><b class="icon_home"></b><a href="/u/<!--{$user.name}-->">我的主页<span class="menuhint hide">(<span></span>)</span></a></div>
    <div class="menu menu2 <!--{if $active=="mine"}-->active<!--{/if}-->"><b class="icon_broadcast"></b><a href="/mine">我的广播<span class="menuhint hide">(<span></span>)</span></a></div>
    <div class="menu menu3 <!--{if $active=="at"}-->active<!--{/if}-->"><b class="icon_at"></b><a href="/at">提到我的<span class="menuhint hide" id="newmention">(<span></span>)</span></a></div>
    <div class="menu menu4 <!--{if $active=="favor"}-->active<!--{/if}-->"><b class="icon_collect"></b><a href="/favor">我的收藏<span class="menuhint hide">(<span></span>)</span></a></div>
    <div class="menu menu5 <!--{if $active=="inbox"}-->active<!--{/if}-->"><b class="icon_mail"></b><a href="/box/inbox">私信<span class="menuhint hide" id="newmail">(<span></span>)</span></a></div>
</div>