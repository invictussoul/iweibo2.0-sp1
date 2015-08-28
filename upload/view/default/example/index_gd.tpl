<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IWeibo2.0 application example - <!--{TO->cfg key="site_name" group="basic" default="iWeibo2.0"}--></title>
</head>
<body>
<form action="/example/index/gdcheck" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <p>
    <input name="gdkey" type="text" id="gdkey" onfocus="reloadgd(document.getElementById('gdField'))"/>
    <img id="gdField" src="" gd="<!--{$_gdurl}-->" width="100" height="50" style="visibility: hidden;cursor: pointer;" onclick="reloadgd(this,true)" alt="看不清？换一张" title="看不清？换一张" />
<script>
function reloadgd(el,f){
    if(f || !el.gdloaded){
        el.src=el.getAttribute('gd') + '?' + +new Date();
        el.style.visibility='visible';
        el.gdloaded = true;
    }
}
</script>
  </p>
  <p>
    <input type="submit" name="submit" value="提交" />
</p>
</form>
</body>
</html>