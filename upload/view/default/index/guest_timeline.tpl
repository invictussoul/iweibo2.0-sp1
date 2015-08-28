<!doctype html>
<html>
    <head>
        <title><!--{$guest.nick}-->的微博 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
        <!--{include file="common/style.tpl"}-->
        <script>
            var iwbTimelineMoreType = 1;
        </script>
    </head>
    <body>
        <!--{include file="common/header.tpl"}-->
        <div class="wrapper content">
            <div class="contentleft fleft">
                <!--{include file="common/uprofile.tpl"}-->
                <div class="moduletitle">
                    <strong class="fleft"><!--{$guest.nick}-->说</strong>
                    <span class="fright">
                        <!--{foreach key=key item=filter from=$filterlist}-->
                        <!--{if ($ctype=="0" && $filter.utype==$utype) || $filter.ctype==$ctype}-->
                        <strong><!--{$filter.name}--></strong>
                        <!--{else}-->
                        <a href="<!--{$filter.url}-->"><!--{$filter.name}--></a>
                        <!--{/if}-->
                        <!--{if $filter.ctype!=16}--> | <!--{/if}-->
                        <!--{/foreach}-->
                    </span>
                </div>
                <div class="tcontainer">
                    <!--{include file="common/titem.tpl"}-->
                </div>
                <!--{if $hasnext===0 && $guest.gid != 4}-->
                    <!--{include file="common/pagerwrapper.tpl"}-->
                <!--{/if}-->
            </div>
            <div class="contentright fright">
                <!--{include file="common/uprofile2.tpl"}-->
                <div class="toggle"><!--{if $guest.sex==2}-->她<!--{else}-->他<!--{/if}-->收听<!--{$guest.idolnum}-->人</div>
                <!--{include file="common/userlist.tpl"}-->
            </div>
        </div>
        <!--{include file="common/footcontrol.tpl"}-->
        <!--{include file="common/footer.tpl"}-->
        <script src="/resource/js/guest.js"></script>
    </body>
</html>