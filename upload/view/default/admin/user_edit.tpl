<!--{include file="admin/header.tpl"}-->
<script type="text/javascript" src="/resource/admin/js/calendar.js"></script>
<div class="itemtitle">
    <h3>编辑用户</h3>
    <ul class="tab1">
        <li><a href="/admin/user/search"><span>管理</span></a></li>
        <li><a href="/admin/user/add"><span>添加</span></a></li>
    </ul>
</div>
<form id="edituserform" name="edituserform" method="post" action="/admin/user/edit">
<input type="hidden" name="action" value="edit" />
<input type="hidden" name="uid" value="<!--{$conditions.uid}-->" />
<input type="hidden" name="username" value="<!--{$conditions.username}-->" />
<table class="tb tb2">
    <tr><td class="td27" colspan="2">账号</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><!--{$conditions.username}--></td>
        <td class="vtop tips2" id="usernametip" name="usernametip"></td>
    </tr>
    <tr><td class="td27" colspan="2">密码</td></tr>
    <tr class="noborder">
        <td class="vtop rowform" style="width:400px;"><input type="password" class="txt" id="password" name="password" value="" /> 留空则不修改</td>
        <td class="vtop tips2" id="passwordtip" name="passwordtip"></td>
    </tr>
    <tr><td class="td27" colspan="2">姓名</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="text" class="txt" id="nickname" name="nickname" value="<!--{$conditions.nickname}-->" /></td>
        <td class="vtop tips2" id="nicknametip" name="nicknametip"></td>
    </tr>
    <tr><td class="td27" colspan="2">用户组</td></tr>
    <tr class="noborder">
        <td class="vtop rowform">
            <select id="gid" name="gid">
                <!--{foreach key=gid item=group from=$usergroups}-->
                    <option value="<!--{$gid}-->" <!--{if $conditions.gid == $gid}-->selected<!--{/if}-->><!--{$group.title}--></option>
                <!--{/foreach}-->
            </select>
        </td>
        <td class="vtop tips2" id="usergrouptip" name="usergrouptip"></td>
    </tr>
    <tr><td class="td27" colspan="2">性别</td></tr>
    <tr class="noborder">
        <td class="vtop rowform">
            <input type="radio" name="gender" value="1" <!--{if $conditions.gender == '1'}-->checked<!--{/if}--> > 男
            <input type="radio" name="gender" value="2" <!--{if $conditions.gender == '2'}-->checked<!--{/if}--> > 女
        </td>
        <td class="vtop tips2"></td>
    </tr>
    <tr><td class="td27" colspan="2">生日</td></tr>
    <tr class="noborder">
        <td>
            <select id="birthyear" name="birthyear" onchange="changeDate()"></select>
            <select id="birthmonth" name="birthmonth" onchange="changeDate()"></select>
            <select id="birthday" name="birthday" onchange="changeDate()"></select>&nbsp;&nbsp;&nbsp;<span id="star"></span>
        </td>
        <td class="vtop tips2"></td>
    </tr>
    <tr><td class="td27" colspan="2">家乡</td></tr>
    <tr class="noborder">
        <td>
            <select id="homenation" name="homenation" onchange="changeHomeNation()"></select>
            <select id="homeprovince" name="homeprovince" onchange="changeHomeProvince()"></select>
            <select id="homecity" name="homecity"></select>
        </td>
        <td class="vtop tips2"></td>
    </tr>
    <tr><td class="td27" colspan="2">所在地</td></tr>
    <tr class="noborder">
        <td>
            <select id="nation" name="nation" onchange="changeNation()"></select>
            <select id="province" name="province" onchange="changeProvince()"></select>
            <select id="city" name="city"></select>
        </td>
        <td class="vtop tips2"></td>
    </tr>
    <tr><td class="td27" colspan="2">从事行业</td></tr>
    <tr class="noborder">
        <td class="vtop rowform">
            <select id="occupation" name="occupation">
                <option></option>
                <!--{foreach from=$setting.occupation key=index item=occupation}-->
                    <option value="<!--{$index}-->" <!--{if $conditions.occupation == $index}-->selected<!--{/if}-->><!--{$occupation}--></option>
                <!--{/foreach}-->
            </select>
        </td>
        <td class="vtop tips2" id="occupationtip" name="occupationtip"></td>
    </tr>
    <tr>
    <tr><td class="td27" colspan="2">Email</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="text" class="txt" id="email" name="email" value="<!--{$conditions.email}-->" /></td>
        <td class="vtop tips2" id="emailtip" name="emailtip"></td>
    </tr>
    <tr><td class="td27" colspan="2">手机号</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="text" class="txt" id="mobile" name="mobile" value="<!--{$conditions.mobile}-->" /></td>
        <td class="vtop tips2" id="mobiletip" name="mobiletip"></td>
    </tr>
    <tr><td class="td27" colspan="2">个人主页</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="text" class="txt" id="homepage" name="homepage" value="<!--{$conditions.homepage}-->" /></td>
        <td class="vtop tips2" id="homepagetip" name="homepagetip"></td>
    </tr>
    <tr><td class="td27" colspan="2">个人介绍</td></tr>
    <tr class="noborder">
        <td class="vtop rowform">
            <textarea name="summary" cols="60" rows="8"><!--{$conditions.summary}--></textarea>
        </td>
        <td class="vtop tips2" id="summarytip" name="summarytip"></td>
    </tr>
    <tr><td class="td27" colspan="2">注册IP</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="text" class="txt" id="regip" name="regip" value="<!--{$conditions.regip}-->" /></td>
        <td class="vtop tips2" id="regiptip" name="regiptip"></td>
    </tr>
    <tr><td class="td27" colspan="2">注册时间</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="text" class="txt" id="regtime" name="regtime" onclick="showcalendar(event, this)" value="<!--{$conditions.regtime|idate:'Y-m-d'}-->" /></td>
        <td class="vtop tips2" id="regtimetip" name="regtimetip"></td>
    </tr>
    <tr><td class="td27" colspan="2">最后访问IP</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="text" class="txt" id="lastip" name="lastip" value="<!--{$conditions.lastip}-->" /></td>
        <td class="vtop tips2" id="lastiptip" name="lastiptip"></td>
    </tr>
    <tr><td class="td27" colspan="2">最后访问时间</td></tr>
    <tr class="noborder">
        <td class="vtop rowform"><input type="text" class="txt" id="lastvisit" name="lastvisit" onclick="showcalendar(event, this)" value="<!--{$conditions.lastvisit|idate:'Y-m-d'}-->" /></td>
        <td class="vtop tips2" id="lastvisittip" name="lastvisittip"></td>
    </tr>
    <tr><td colspan="2"><input type="submit" class="btn" id="editusersubmit" name="editusersubmit" value="提交"></td></tr>
</table>
</form>
<script type="text/javascript">
    $(function(){
        setDate(<!--{$conditions.birthyear}-->, <!--{$conditions.birthmonth}-->, <!--{$conditions.birthday}-->);
        setHomeNation('<!--{$conditions.homenation}-->', '<!--{$conditions.homeprovince}-->', '<!--{$conditions.homecity}-->');
        setNation('<!--{$conditions.nation}-->', '<!--{$conditions.province}-->', '<!--{$conditions.city}-->');
    })
    function changeHomeNation()
    {
        setHomeProvince($("#homenation").val());
    }
    function changeHomeProvince()
    {
        setHomeCity($("#homenation").val(), $("#homeprovince").val());
    }
    function changeNation()
    {
        setProvince($("#nation").val());
    }
    function changeProvince()
    {
        setCity($("#nation").val(), $("#province").val());
    }
    function changeDate()
    {
        setDate($("#birthyear").val(), $("#birthmonth").val(), $("#birthday").val());
    }
    function setHomeNation(nationVal, provinceVal, cityVal)
    {
        var nation = $("#homenation");
        nation.get(0).options.length = 0;
        $.getJSON(iwbRoot+"admin/user/getnation/", function(json){
            if(json!=null){
                $.each(json, function(i, n){
                    var option = new Option(n, i);
                    if (i == nationVal) {
                        option.selected = true;
                    }
                    nation.get(0).options.add(option);
                });
                setHomeProvince(nation.val(), provinceVal, cityVal);
            }
        });
    }
    function setHomeProvince(nationVal, provinceVal, cityVal)
    {
        var province = $("#homeprovince");
        var city = $("#homecity");
        province.get(0).options.length = 0;
        city.get(0).options.length = 0;
        $.getJSON(iwbRoot+"admin/user/getprovince/nation/"+nationVal, function(json){
            if(json!=null){
                $.each(json, function(i, n){
                    var option = new Option(n, i);
                    if (i == provinceVal) {
                        option.selected = true;
                    }
                    province.get(0).options.add(option);
                });
                if(province.val())
                    province.show();
                else
                    province.hide();
                setHomeCity(nationVal, province.val(), cityVal);
            }else{
                province.hide();
                city.hide();
            }
        });
    }
    function setHomeCity(nationVal, provinceVal, cityVal)
    {
        var city = $("#homecity");
        city.get(0).options.length = 0;
        provinceVal = provinceVal == null ? '' : provinceVal;
        $.getJSON(iwbRoot+"admin/user/getcity/nation/"+nationVal+"/province/"+provinceVal, function(json){
            if(json!=null){
                $.each(json, function(i, n){
                    var option = new Option(n, i);
                    if (i == cityVal) {
                        option.selected = true;
                    }
                    city.get(0).options.add(option);
                });
                if(city.val())
                    city.show();
                else
                    city.hide();
            }else{
                city.hide();
            }
        });
    }
    function setNation(nationVal, provinceVal, cityVal)
    {
        var nation = $("#nation");
        nation.get(0).options.length = 0;
        $.getJSON(iwbRoot+"admin/user/getnation/", function(json){
            if(json!=null){
                $.each(json, function(i, n){
                    var option = new Option(n, i);
                    if (i == nationVal) {
                        option.selected = true;
                    }
                    nation.get(0).options.add(option);
                });
                setProvince(nation.val(), provinceVal, cityVal);
            }
        });
    }
    function setProvince(nationVal, provinceVal, cityVal)
    {
        var province = $("#province");
        var city = $("#city");
        province.get(0).options.length = 0;
        city.get(0).options.length = 0;
        $.getJSON(iwbRoot+"admin/user/getprovince/nation/"+nationVal, function(json){
            if(json!=null){
                $.each(json, function(i, n){
                    var option = new Option(n, i);
                    if (i == provinceVal) {
                        option.selected = true;
                    }
                    province.get(0).options.add(option);
                });
                if(province.val())
                    province.show();
                else
                    province.hide();
                setCity(nationVal, province.val(), cityVal);
            }else{
                province.hide();
                city.hide();
            }
        });
    }
    function setCity(nationVal, provinceVal, cityVal)
    {
        var city = $("#city");
        city.get(0).options.length = 0;
        provinceVal = provinceVal == null ? '' : provinceVal;
        $.getJSON(iwbRoot+"admin/user/getcity/nation/"+nationVal+"/province/"+provinceVal, function(json){
            if(json!=null){
                $.each(json, function(i, n){
                    var option = new Option(n, i);
                    if (i == cityVal) {
                        option.selected = true;
                    }
                    city.get(0).options.add(option);
                });
                if(city.val())
                    city.show();
                else
                    city.hide();
            }else{
                city.hide();
            }
        });
    }
    function setDate(yearVal, monthVal, dayVal){
        var year = $("#birthyear");
        var month = $("#birthmonth");
        var day = $("#birthday");
        var daysInMonth = [31,31,28,31,30,31,30,31,31,30,31,30,31];
        if(((yearVal%4 == 0) && (yearVal%100 != 0)) || (yearVal%400 == 0))
        {
            daysInMonth[2] = 29;
        }
        var dayCount = daysInMonth[monthVal];
        year.get(0).options.length = 0;
        var curData = new Date();
        for(var i=1900;i<=curData.getFullYear();i++)
        {
            var option = new Option(i+'年', i);
            if (i == yearVal) {
                option.selected = true;
            }
            year.get(0).options.add(option);
        }
        month.get(0).options.length = 0;
        for(var i=1;i<=12;i++)
        {
            var option = new Option(i+'月', i);
            if (i == monthVal) {
                option.selected = true;
            }
            month.get(0).options.add(option);
        }
        day.get(0).options.length = 0;
        for(var i=0;i<dayCount;i++)
        {
            option = new Option(i+1+'日', i+1);
            if(i == dayVal-1)
            {
                option.selected = true;
            }
            day.get(0).options.add(option);
        }
        $('#star').html(star(monthVal, dayVal));
    }
    function star(month, day){
        var num = month * 100 + day * 1;
        if (num >= 120 && num <= 218){
            return "水瓶座";
        }else if (num >= 219 && num <= 320){
            return "双鱼座";
        }else if (num >= 321 && num <= 420){
            return "白羊座";
        }else if (num >= 421 && num <= 520){
            return "金牛座";
        }else if (num >= 521 && num <= 621){
            return "双子座";
        }else if (num >= 622 && num <= 722){
            return "巨蟹座";
        }else if (num >= 723 && num <= 822){
            return "狮子座";
        }else if (num >= 823 && num <= 922){
            return "处女座";
        }else if (num >= 923 && num <= 1022){
            return "天秤座";
        }else if (num >= 1023 && num <= 1121){
            return "天蝎座";
        }else if (num >= 1122 && num <= 1221){
            return "射手座";
        }else if (num >= 1222 || num <= 119){
            return "摩羯座";
        }
    }
</script>
<!--{include file="admin/footer.tpl"}-->