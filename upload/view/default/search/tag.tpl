<!doctype html>
<html>
<head>
<title><!--{$data.title}--> - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
<!--{include file="common/style.tpl"}-->
</head>
<body>
<!--{include file="common/header.tpl"}-->
<div class="wrapper content">
    <div class="fleft contentleft">
        <!--主栏组件-->
        <!--{$mainComponent}-->
        <div class="tabbar">
        <ul class="tabs">
            <li class="tab"><a href="/search/all<!--{$data.addkey}-->">综合</a></li>
            <li class="tab"><a href="/search/user<!--{$data.addkey}-->">用户</a></li>
            <li class="tab"><a href="/search/t<!--{$data.addkey}-->">广播</a></li>
            <li class="tab active"><strong>标签</strong></li>
        </ul>
        <div class="fright">
        </div>
        </div>
        <!--{if $syssrc==0}-->
        <div class="cityinfo">
        <div class="fleft">
        <!--{if $usersrc==0}-->
        <strong>本地标签</strong>
        <a href="/search/tag/src/1<!--{$data.addkey}-->">腾讯标签</a>
        <!--{else}-->
        <a href="/search/tag/src/0<!--{$data.addkey}-->">本地标签</a>
        <strong>腾讯标签</strong>
        <!--{/if}-->
        </div>
        </div>
        <!--{/if}-->
        <!--{if $data.unum}-->
        <div class="moduletitle4">
            <strong class="fleft"><!--{$data.searchkey}-->(<!--{$data.unum}-->)</strong> &nbsp; &nbsp;
            <a href="javascript:void(0);" class="tagsbtn">+添加此标签</a>
        </div>
        <!--{/if}-->
        <!--{if empty($data.unum)}-->
        <div class="norecord">没有找到<span class="cKeyword"></span>相关的标签<!--{if $syssrc==0 && $usersrc==0}-->,请试用 <a href="/search/tag/src/1<!--{$data.addkey}-->">腾讯标签</a><!--{/if}--></div>
        <div class="topicform">
        <h4>你可以：</h4>
        <ul>
        <li>• 换一个相近的搜索词重新搜索</li>
        <li>• 去掉原搜索词中无意义的词，如“的”、“呢”等</li>
        </ul>
        </div>
        <!--{/if}-->
        <ul class="userlist4">
        <!--{foreach from=$data.u item=it}-->
        <li>
        <div class="fleft"><a href="/u/<!--{$it.name}-->" title="<!--{$it.nick}-->(@<!--{$it.name}-->)"><img src="<!--{$it.head}-->" onerror="this.src=iwbResourceRoot+'resource/images/default_head_50.jpg'"></a> <a href="javascript:void(0);" data-username="cydreader"></a></div>
        <div class="fright">
            <!--{if $it.name!=$username}-->
            <!--{if $it.isidol}-->
            <a data-name="<!--{$it.name}-->" data-type="0" data-styleid="0" href="javascript:void(0);"  class="iwbFollowControl followbtn unfollowbtn" title="取消收听"></a>
            <!--{else}-->
            <a data-name="<!--{$it.name}-->" data-type="1" data-styleid="0" href="javascript:void(0);" class="iwbFollowControl followbtn" title="收听用户"></a>
            <!--{/if}-->
            <!--{/if}-->
            <h6> <a href="/u/<!--{$it.name}-->" title="<!--{$it.nick}-->(@<!--{$it.name}-->)"><!--{$it.nick}--></a><!--{if $it.is_auth}--><img src="/resource/images/vip.gif"><!--{/if}--><span>(@<!--{$it.name}-->)</span>  </h6>
            <div><!--{$it.tags_light}--></div>
        </div>
        </li>
        <!--{/foreach}-->
        </ul>
        <!--{include file="common/pagerwrapper3.tpl"}-->
    </div>
    <div class="fright contentright">
        <!--{include file="common/profile.tpl"}-->
        <div class="rightsp" ></div>
        <!--{include file="common/menus.tpl"}-->
        <!--右栏组件-->
        <!--{$rightComponent}-->
        <div class="rightsp"></div>
    </div>
</div>
<!--{include file="common/footer.tpl"}-->
<script>
//检测汉字和字符串长度
function cnlen(s)
{
    var l = 0;
    var a = s.split("");
    for (var i=0;i<a.length;i++)
    {
         if (a[i].charCodeAt(0)<299)
         {
            l++;
        } else {
            l+=3;
        }
    }
    return l;
}
$(function(){
    $('.tagsbtn').click(function(){
        tagval = '<!--{$data.searchkey}-->'
        if(!tagval || cnlen(tagval)<0 || cnlen(tagval)>24)
        {
            IWB_DIALOG.modaltipbox("error",'标签长度小于8个汉字或24个英文之内');
            return;
        }
        $.post(window.iwbRoot + '/tag/add',{tagname:tagval},function(data){
            data = eval('('+data+')')
            if(data.ret==0)
            {
                $('#tagcontent').prepend('<span id="tag'+data.data.id+'"><a href="/search/tag/k/'+tagval+'">'+tagval+'</a><em rel="'+data.data.id+'" class="delbtn">×</em></span>')
                IWB_DIALOG.modaltipbox("error",'添加成功')
                $('.tagsbtn').html('已添加此标签')
                $('.tagsbtn').removeClass('tagsbtn')
            }else{
                if(data.ret== -119){
                    $('.tagsbtn').html('已添加此标签')
                    $('.tagsbtn').removeClass('tagsbtn')
                }
                IWB_DIALOG.modaltipbox("error",data.msg)
            }
            $('.tagsvalue').val('')
        })
    })
})
</script>
<script src="/resource/js/searchTag.js"></script>
</body>
</html>
