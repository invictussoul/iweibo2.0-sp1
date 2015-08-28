<div class="floattop">
    <div class="itemtitle"><h3>微访谈</h3>
    <ul class="tab1">
        <li<!--{if 'index' == $_actionName && !$ol }--> class="current"<!--{/if}-->>
            <a href="/admin/tiview/index"><span>所有访谈列表</span></a>
        </li>
        <li<!--{if 'index' == $_actionName && $ol}--> class="current"<!--{/if}-->>
            <a href="/admin/tiview/index/ol/1"><span>在线访谈列表</span></a>
        </li>
        <li<!--{if 'approval' == $_actionName}--> class="current"<!--{/if}-->>
            <a href="/admin/tiview/approval"><span>访谈审批列表</span></a>
        </li>
        <li<!--{if 'new' == $_actionName}--> class="current"<!--{/if}-->>
            <a href="/admin/tiview/new"><span>新增微访谈</span></a>
        </li>
    </ul>
    </div>
</div>