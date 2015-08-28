<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IWeibo2.0 application example - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
<script src="/resource/admin/js/jquery-1.6.min.js">
</script>
</head>
<body>
<a href="#" onclick="test();return false;">ajax</a>
<script>
function test()
{
    $.post('/example/index/ajaxpost',{inajax:1},function(data){
        alert(date);
    },'json');
}
</script>
</body>
</html>