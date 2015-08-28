<!doctype html>
<html>
    <head>
        <title>上墙 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
<!--{include file="common/style.tpl"}-->
        <script language="javascript">
            function _onBlogformSubmit(){
               if($("#topic_form > input[name='keyword']").val() == '请输入话题') {
                  $("#topic_form > input[name='keyword']").val('');
               }
            }
        </script>
    </head>
    <body>
<!--{include file="common/header.tpl"}-->
        <div class="wrapper content">
            <div class="contentleft fleft">
                <!--{if $topic}-->
                <div class="alarmtxt">请在投影屏幕中，打开微博墙页面 <a href="/wall/index/id/<!--{$topic.tid}-->" target="_blank"><!--{$wall_url}--></a></div>
                <!--{/if}-->
                <form method="post" action="/wall/search" id="topic_form" class="topicform">
                    <h2>搜话题：</h2>
                    <div>
                        <input type="text" name="keyword" value="<!--{if $keyword}--><!--{$keyword}--><!--{else}-->请输入话题<!--{/if}-->" class="txt" onclick="this.value=''" size="15"/>
                        <input type="submit" name="submit" value="确定"  class="btn"/>
                    </div>
                </form>
                <!--{if $blogs}-->
                <ul class="topicresult">
                      <!--{foreach key="key" item="blog" from=$blogs}-->
                    <li>
                        <a href="/u/<!--{$blog.account}-->" class="fleft userhead"><img src="<!--{$blog.user_head}-->"/></a>
                        <div class="fright tbody">
                            <a href="/u/<!--{$blog.account}-->"><!--{$blog.account}--></a>：<!--{$blog.content}-->
                            <div class="tbottom"><div class="tbottomleft">来自：<!--{$blog.comefrom}--></div></div>
                        </div>
                    </li>
                    <!--{/foreach}-->
                </ul>
                <!--{else}-->
                <div class="topicform"><h2>精彩上墙话题</h2></div>
                <div class="topichot">
                    <h3 class="fleft"><strong class="active">进行中</strong></h3>
                    <div>
                        <!--{foreach item="topic" from=$wallTopics_runing}-->
                        <a href="/wall/index/id/<!--{$topic.tid}-->" target="_blank"><!--{$topic.title}--></a>
                        <!--{/foreach}-->
                    </div>
                    <p class="extra"></p>
                    <h3 class="fleft"><strong>已结束</strong></h3>
                    <div class="gray">
                         <!--{foreach item="topic" from=$wallTopics_closed}-->
                        <label><!--{$topic.title}--></label>
                        <!--{/foreach}-->
                    </div>
                </div>
                <!--{/if}-->
                 <!--{if $info}-->
                <div class="norecord" align="center"><!--{$info}--></div>
                 <!--{/if}-->
            <!--{include file="common/pagerwrapper3.tpl"}-->
            </div>
            <div class="fright contentright">
            </div>
        </div>
    <!--{include file="common/footer.tpl"}-->
    <script src="/resource/js/eventCommon.js"></script>
    </body>
</html>