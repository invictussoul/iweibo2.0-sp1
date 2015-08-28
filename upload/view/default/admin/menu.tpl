<div id="leftmenu" class="menu">
    <!--{foreach key=key item=menu from=$menulist name=topmenu}-->
    <ul index="<!--{$smarty.foreach.topmenu.index}-->" class="left_menu">
        <!--{foreach item=item from=$menu name=nav}-->
        <li index="<!--{$smarty.foreach.nav.index}-->"><a href="<!--{$item.url}-->" target="win"><!--{$item.name}--></a></li>
        <!--{/foreach}-->
    </ul>
    <!--{/foreach}-->
</div>