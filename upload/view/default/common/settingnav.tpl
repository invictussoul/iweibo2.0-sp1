<div class="fleft settingnav">
    <a href="/u/<!--{$user.name}-->" class="head"><img src="<!--{$userhead_src}-->"></a>
    <ul>
        <!--{if $action == 'face'}-->
        <li><a href="/setting">基本资料</a></li>
        <li class="active">修改头像</li>
	<li><a href="/setting/accredit">授权设置</a></li>
        <!--{elseif $action == 'accredit'}-->
        <li><a href="/setting">基本资料</a></li>
        <li><a href="/setting/face">修改头像</a></li>
	<li class="active">授权设置</li>
        <!--{else}-->
        <li class="active">基本资料</li>
        <li><a href="/setting/face">修改头像</a></li>
	<li><a href="/setting/accredit">授权设置</a></li>
        <!--{/if}-->
    </ul>
</div>