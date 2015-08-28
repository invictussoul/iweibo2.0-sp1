<div class="floattop">
    <div class="itemtitle"><h3>微直播</h3>
    <ul class="tab1">
        <li<!--{if 'index' == $_actionName && !$ol }--> class="current"<!--{/if}-->>
            <a href="/admin/tlive/index"><span>所有直播列表</span></a>
        </li>
        <li<!--{if 'index' == $_actionName && $ol}--> class="current"<!--{/if}-->>
            <a href="/admin/tlive/index/ol/1"><span>在线直播列表</span></a>
        </li>
        <li<!--{if 'approval' == $_actionName}--> class="current"<!--{/if}-->>
            <a href="/admin/tlive/approval"><span>直播审批列表</span></a>
        </li>
        <li<!--{if 'new' == $_actionName}--> class="current"<!--{/if}-->>
            <a href="/admin/tlive/new"><span>新增微直播</span></a>
        </li>
    </ul>
    </div>
</div>