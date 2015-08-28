<form id="form1" name="form1" method="post" action="/wap/t/add"  enctype="multipart/form-data"  >
    <!--{if !empty($searchkey)}--><p>针对“<!--{$searchkey}-->”说点什么：(140字以内)</p><!--{else}--><p>说说新鲜事儿，140字以内</p><!--{/if}-->
    <textarea name="content"><!--{if $searchkey}-->#<!--{$searchkey}-->#<!--{/if}--></textarea>
    <p class="padt">
    <input type="submit" value="广播" class="button button_blue" name="submit"/>
    <input type="submit" value="插图片" class="button button_blue" name="addpic"/>
    <!--<input type="file" name="pic" value="上传图片" size="15" style="border: 1px solid #B3B9C3;margin: 0 0 4px 0;-webkit-appearance: initial;padding: initial;background-color: initial;">-->
    <!--{if $backurl}--><input type="hidden" name="backurl" value="<!--{$backurl}-->"/><!--{/if}-->
    <!--{if isset($sendbox.reid)}--><input type="hidden" value="<!--{$sendbox.reid}-->" name="reid"><!--{/if}-->
    <!--{if isset($sendbox.type)}--><input type="hidden" value="<!--{$sendbox.type}-->" name="type"><!--{/if}-->
    </p>
</form>