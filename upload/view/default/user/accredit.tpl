<!doctype html>
<html>
<head>
    <title>个人设置 - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
    <!--{include file="common/style.tpl"}-->
</head>
<body>
    <!--{include file="common/header.tpl"}-->
    <div class="wrapper content whitebg">
    <!--{if $rt.code}-->
        <div class="result">
            <h2><!--{$rt.msg}--></h2>
            <span><a href="<!--{$rt.link}-->">返回</a></span>
            <meta http-equiv="refresh" content="3; url=<!--{$rt.link}-->" />
        </div>
    <!--{else}-->
            <!--{$tabbar}-->
            <!--{include file="common/settingnav.tpl"}-->
            <!--{include file="common/unaccredit.tpl"}-->
    <!--{/if}-->
    </div>
    <!--{include file="common/footer.tpl"}-->
    <script src="/resource/js/setting.js"></script>
    <!--{if $action == 'update'}-->
        <script type="text/javascript">
            $(function(){
                setDate(<!--{$userInfo.birthyear}-->, <!--{$userInfo.birthmonth}-->, <!--{$userInfo.birthday}-->);
                setHomeNation('<!--{$userInfo.homenation}-->', '<!--{$userInfo.homeprovince}-->', '<!--{$userInfo.homecity}-->');
                setNation('<!--{$userInfo.nation}-->', '<!--{$userInfo.province}-->', '<!--{$userInfo.city}-->');
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
                $.getJSON(iwbRoot+"setting/getnation/", function(json){
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
                $.getJSON(iwbRoot+"setting/getprovince/nation/"+nationVal, function(json){
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
                $.getJSON(iwbRoot+"setting/getcity/nation/"+nationVal+"/province/"+provinceVal, function(json){
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
                $.getJSON(iwbRoot+"setting/getnation/", function(json){
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
                $.getJSON(iwbRoot+"setting/getprovince/nation/"+nationVal, function(json){
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
                $.getJSON(iwbRoot+"setting/getcity/nation/"+nationVal+"/province/"+provinceVal, function(json){
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
    <!--{/if}-->
    </body>
    <!--{if $showmsg}-->
    <script type="text/javascript">
	    $(function(){
    		alert('<!--{$showmsg}-->');
    	});
    </script>
    <!--{/if}-->
</html>