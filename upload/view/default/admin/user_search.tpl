<!--{include file="admin/header.tpl"}-->
<div class="itemtitle">
    <h3>用户管理</h3>
    <ul class="tab1">
        <li class="current"><a href="javascript:void(0);"><span>管理</span></a></li>
        <li><a href="/admin/user/add"><span>添加</span></a></li>
    </ul>
</div>
<table id="tips" class="tb tb2 ">
    <!--
    <tr>
        <th class="partition">技巧提示</th>
    </tr>
    <tr>
        <td class="tipsblock">
            <ul id="tipslis">
                <li>通过用户管理，您可以进行编辑会员资料、用户组以及删除会员等操作。</li>
                <li>请先根据条件搜索用户，然后选择相应的操作。</li>
                <li>在“等待验证用户”组的用户，管理员不能编辑其用户组，在人工审核状态下，可使用用户审核，在Email验证状态下，请等待用户验证邮箱地址。</li>
                <li>当用户通过了注册验证，则不能将其用户组变更为“等待验证用户”。</li>
                </ul>
        </td
    </tr>
    -->
</table>
<div class="itemtitle">
    <div class="cuspages right">
        <form id="searchuserform" name="searchuserform" method="post" action="/admin/user/search">
            <select name="type">
                <option value="nickname" <!--{if $conditions.type == 'nickname'}-->selected<!--{/if}-->>姓名</option>
                <option value="email" <!--{if $conditions.type == 'email'}-->selected<!--{/if}-->>Email</option>
                <option value="username" <!--{if $conditions.type == 'username'}-->selected<!--{/if}-->>帐号</option>
            </select>
            <input type="text" name="keyword" value="<!--{if $conditions.keyword}--><!--{$conditions.keyword}--><!--{else}-->关键字<!--{/if}-->" class="txt" onclick="this.value=''" size="8" />
            <select id="gid" name="gid">
                <option value="0">用户组</option>
                <!--{foreach from=$usergroups key=gid item=group}-->
                    <option value="<!--{$gid}-->" <!--{if $conditions.gid == $gid}-->selected<!--{/if}-->><!--{$group.title}--></option>
                <!--{/foreach}-->
            </select>
            <select name="gender">
                <option value="0">性别</option>
                <option value="1" <!--{if $conditions.gender == 1}-->selected<!--{/if}-->>男</option>
                <option value="2" <!--{if $conditions.gender == 2}-->selected<!--{/if}-->>女</option>
            </select>
            <select name="regdate">
                <option value="0">注册时间</option>
                <option value="1" <!--{if $conditions.regdate == 1}-->selected<!--{/if}-->>一周内</option>
                <option value="2" <!--{if $conditions.regdate == 2}-->selected<!--{/if}-->>两周内</option>
                <option value="3" <!--{if $conditions.regdate == 3}-->selected<!--{/if}-->>一月内</option>
                <option value="4" <!--{if $conditions.regdate == 4}-->selected<!--{/if}-->>半年内</option>
                <option value="5" <!--{if $conditions.regdate == 5}-->selected<!--{/if}-->>一年内</option>
                <option value="6" <!--{if $conditions.regdate == 6}-->selected<!--{/if}-->>一年前</option>
            </select>
            <select name="lastvisit">
                <option value="0">最后访问</option>
                <option value="1" <!--{if $conditions.lastvisit == 1}-->selected<!--{/if}-->>一周内</option>
                <option value="2" <!--{if $conditions.lastvisit == 2}-->selected<!--{/if}-->>两周内</option>
                <option value="3" <!--{if $conditions.lastvisit == 3}-->selected<!--{/if}-->>一月内</option>
                <option value="4" <!--{if $conditions.lastvisit == 4}-->selected<!--{/if}-->>半年内</option>
                <option value="5" <!--{if $conditions.lastvisit == 5}-->selected<!--{/if}-->>一年内</option>
                <option value="6" <!--{if $conditions.lastvisit == 6}-->selected<!--{/if}-->>一年前</option>
            </select>
            <input class="btn" type="submit" value="搜索" name="searchsubmit" onclick="if(this.form.keyword.value=='关键字') {this.form.keyword.value=''}" />
        </form>
    </div>
</div>
<!--{if $users}-->
    <form id="useradminform" name="useradminform" method="post" action="/admin/user/delete" onsubmit="return confirm('是否删除？');">
    <table class="tb tb2">
        <tr>
            <th class="partition" colspan="10">共搜索到<strong> <!--{$userscount}--> </strong>名符合条件的用户</th>
        </tr>
        <tr class="header">
            <th width="20">&nbsp;</th>
            <th>帐号</th>
            <th>姓名</th>
            <th>Email</th>
            <th>用户组</th>
            <th>注册 IP</th>
            <th>最后访问 IP</th>
            <th>注册时间</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
        <!--{foreach from=$users item=user}-->
            <tr>
                <td><!--input type="checkbox" name="deleteids[]" value="<!--{$user.uid}-->" /--></td>
                <td><a href="/u/<!--{$user.name}-->" target="_blank"><!--{$user.username}--></a></td>
                <td><!--{$user.nickname}--></td>
                <td><!--{$user.email}--></td>
                <td><!--{$usergroups[$user.gid].title}--></td>
                <td><!--{$user.regip}--></td>
                <td><!--{$user.lastip}--></td>
                <td><!--{$user.regtime|idate:"Y-m-d"}--></td>
                <td><!--{if $user.gid!=1}--><!--{if $user.gid==4}--><a href="/admin/user/shield/gid/2/uid/<!--{$user.uid}-->">取消屏蔽</a><!--{else}--><a href="/admin/user/shield/gid/4/uid/<!--{$user.uid}-->">屏蔽</a><!--{/if}--><!--{/if}--></td>
                <td><a href="/admin/user/edit/uid/<!--{$user.uid}-->">编辑</a></td>
            </tr>
        <!--{/foreach}-->
        <tr>
            <td colspan="2">
                <!--
                <input type="checkbox" name="chkall" id="chkall" class="checkbox" onclick="checkAll('prefix', this.form, 'deleteids')" /><label for="chkall">全选</label>
                <input type="submit" class="btn" value="删除">
                -->
            </td>
            <td colspan="7" align="right"><!--{include file="common/multipage.tpl"}--></td>
        </tr>
    </table>
    </form>
<!--{else}-->
    <table class="tb tb2">
        <tr>
            <th class="partition" colspan="10">未找到符合条件的用户</th>
        </tr>
    </table>
<!--{/if}-->
<!--{include file="admin/footer.tpl"}-->