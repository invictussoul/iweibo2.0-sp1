<!doctype html>
<html>
    <head>
    <title>发起活动 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
    </head>
    <body>
    <!--{include file="common/header.tpl"}-->
    <div id="append_parent"></div>
    <div class="wrapper content">
        <div class="contentleft fleft">
            <div class="crumb">
                    <span class="fleft"><a href="/event/index">活动</a> &gt; <!--{$curMenu}--> &gt; <!--{$event.title}--></span>
                    <span class="fright"><a href="/event/index">返回&gt;&gt;</a></span>
            </div>
            <form name="form1" method="post" action="/event/join/id/<!--{$event.id}-->/submit/1" target="newevent" class="regform" enctype="multipart/form-data">
              <input type="hidden" name="callback" value="parent.newEventResponse" />
              <table border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td align="left" cols="2" height="43"></td>
                </tr>
                <tr>
                  <th height="43" align="right" valign="top">参与人</th>
                  <td align="left" valign="top"><!--{$user.name}--></td>
                </tr>
                <tr>
                  <th height="43" align="right" valign="top">联系方式</th>
                  <td align="left" valign="top"><label for="contact"></label>
                  <input  name="contact" id="contact" ></td>
                </tr>
                <tr>
                  <th height="43" align="right" valign="top">人均费用</th>
                  <td align="left" valign="top">
                    <!--{if $event.cost == 0}-->免费
                    <!--{else}--><!--{$event.cost}--> 元
                    <!--{/if}--></td>
                </tr>
                <tr>
                  <th height="70" align="right" valign="top">我的留言</th>
                  <td align="left" valign="top"><textarea cols="40" rows="5" name="msg"></textarea></td>
                </tr>
                <tr>
                  <th height="43" align="right">&nbsp;</th>
                  <td align="left"><input type="submit" name="submit" id="button" value="确认参加" class="save"/></td>
                </tr>
              </table>
            </form>
            <iframe class="hide" name="newevent"></iframe>
        </div>
        <div class="contentright fright">
            <!--{include file="user/event_right_list.tpl"}-->
        </div>
    </div>
    <!--{include file="common/footer.tpl"}-->
    <script src="/resource/js/eventCommon.js"></script>
    <script>
    var newEventResponse = function (response) {
        var eventid;
        if (response.ret === 0) {
            eventid = response.data.eventid || 0;
            window.location.href = "<!--{$_pathroot}-->event/view/id/" + eventid;
        } else {
            IWB_DIALOG.modaltipbox("error",response.msg);
        }
    }
    </script>
    </body>
</html>