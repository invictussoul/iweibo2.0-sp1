<!doctype html>
<html>
<head>
    <title>个人设置 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
</head>
<body>
    <!--{include file="common/header.tpl"}-->
    <div class="wrapper content whitebg">
<!--{$tabbar}-->
<div class="tagsetting">
<div class="fleft userhead"><a href="<!--{$userurl.origin}-->"><img src="<!--{$user.head}-->"/><h1><!--{$user.nick}--></h1></a></div>
<div class="fleft tagswrapper">
    <div class="fleft tags" id="tagcontent">
        <!--{if $usertag}-->
        <!--{foreach from=$usertag item=it}-->
        <span id="tag<!--{$it.id}-->"><a href="/search/tag/k/<!--{$it.name}-->"><!--{$it.name}--></a><em rel="<!--{$it.id}-->" class="delbtn">×</em></span>
        <!--{/foreach}-->
        <!--{/if}-->
    </div>
    <div class="fleft tagsdes">
        <h3>为啥要打标签</h3>
        <p>每个标签后面都隐藏着一群志同道合的人，贴上你的标签，找到你的同道中人</p>
    </div>
    <div class="tagsform">
        <input type="text" placeholder="我写我标签" class="tagsvalue"/>
        <input type="button" value="贴上" class="tagsbtn"/>
    </div>
</div>
</div>
    </div>
    <!--{include file="common/footcontrol.tpl"}-->
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
        tagval = $('.tagsvalue').val()
        if(!tagval || cnlen(tagval)<0 || cnlen(tagval)>24)
        {
            IWB_DIALOG.modaltipbox("error",'标签长度小于8个汉字或24个英文之内');
            return;
        }
        static_check = false
        $('#tagcontent > span > a').each(function(){
            if($(this).html()==tagval)
            {
                IWB_DIALOG.modaltipbox("error",'已添加此条标签!')
                static_check = true
                return false;
            }
        })
        if(static_check) return false; //中断click
        if($('#tagcontent > span').length >=10)
        {
            IWB_DIALOG.modaltipbox("error",'最多能添加10个标签');
        }else{
            $.post(iwbRoot+'tag/add',{tagname:tagval},function(data){
                data = eval('('+data+')')
                if(data.ret==0)
                {
                    $('#tagcontent').prepend('<span id="tag'+data.data.id+'"><a href="/search/tag/k/'+tagval+'">'+tagval+'</a><em rel="'+data.data.id+'" class="delbtn">×</em></span>')
                }else{
                    IWB_DIALOG.modaltipbox("error",data.msg)
                }
                $('.tagsvalue').val('')
            })
        }
    })
    $('.delbtn').live('click',function(){
        tid = $(this).attr('rel')
        if(tid)
        {
            $.post(iwbRoot+'tag/del',{tagid:tid},function(data){
                data = eval('('+data+')')
                if(data.ret==0)
                {
                    $('#tag'+tid).remove();
                }else{
                    IWB_DIALOG.modaltipbox("error",data.msg)
                }
            })
        }else{
            IWB_DIALOG.modaltipbox("error",'标签id丢失')
        }
    })
})

</script>
    <script src="/resource/js/setting.js"></script>
    </body>
</html>