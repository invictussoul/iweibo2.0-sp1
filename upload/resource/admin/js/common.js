//note userAgent
var BROWSER = {};
var USERAGENT = navigator.userAgent.toLowerCase();
BROWSER.ie = window.ActiveXObject && USERAGENT.indexOf('msie') != -1 && USERAGENT.substr(USERAGENT.indexOf('msie') + 5, 3);
BROWSER.firefox = document.getBoxObjectFor && USERAGENT.indexOf('firefox') != -1 && USERAGENT.substr(USERAGENT.indexOf('firefox') + 8, 3);
BROWSER.chrome = window.MessageEvent && !document.getBoxObjectFor && USERAGENT.indexOf('chrome') != -1 && USERAGENT.substr(USERAGENT.indexOf('chrome') + 7, 10);
BROWSER.opera = window.opera && opera.version();
BROWSER.safari = window.openDatabase && USERAGENT.indexOf('safari') != -1 && USERAGENT.substr(USERAGENT.indexOf('safari') + 7, 8);
BROWSER.other = !BROWSER.ie && !BROWSER.firefox && !BROWSER.chrome && !BROWSER.opera && !BROWSER.safari;
BROWSER.firefox = BROWSER.chrome ? 1 : BROWSER.firefox;

//显示页面对象
function showObj(id) {
    $("#" + id).show();
}

//隐藏页面对象
function hideObj(id) {
    $("#" + id).hide();
}

//全选
function checkAll(type, form, value, checkall, changestyle) {
    var checkall = checkall ? checkall : 'chkall';
    for(var i = 0; i < form.elements.length; i++) {
        var e = form.elements[i];
        if(type == 'option' && e.type == 'radio' && e.value == value && e.disabled != true) {
            e.checked = true;
        } else if(type == 'value' && e.type == 'checkbox' && e.getAttribute('chkvalue') == value) {
            e.checked = form.elements[checkall].checked;
        } else if(type == 'prefix' && e.name && e.name != checkall && (!value || (value && e.name.match(value)))) {
            e.checked = form.elements[checkall].checked;
            if(changestyle && e.parentNode && e.parentNode.tagName.toLowerCase() == 'li') {
                e.parentNode.className = e.checked ? 'checked' : '';
            }
        }
    }
}

//双击是扩大textarea
function textareasize(obj, op) {
    if(!op) {
        if(obj.scrollHeight > 70) {
            obj.style.height = (obj.scrollHeight < 300 ? obj.scrollHeight : 300) + 'px';
            if(obj.style.position == 'absolute') {
                obj.parentNode.style.height = obj.style.height;
            }
        }
    } else {
        if(obj.style.position == 'absolute') {
            obj.style.position = '';
            obj.style.width = '';
            obj.parentNode.style.height = '';
        } else {
            obj.parentNode.style.height = obj.parentNode.offsetHeight + 'px';
            obj.style.width = BROWSER.ie > 6 || !BROWSER.ie ? '90%' : '600px';
            obj.style.position = 'absolute';
        }
    }
}

//添加行
var addrowdirect = 0;
function addrow(obj, type) {
    var table = obj.parentNode.parentNode.parentNode.parentNode.parentNode;
    if(!addrowdirect) {
        var row = table.insertRow(obj.parentNode.parentNode.parentNode.rowIndex);
    } else {
        var row = table.insertRow(obj.parentNode.parentNode.parentNode.rowIndex + 1);
    }
    var typedata = rowtypedata[type];
    for(var i = 0; i <= typedata.length - 1; i++) {
        var cell = row.insertCell(i);
        cell.colSpan = typedata[i][0];
        var tmp = typedata[i][1];
        if(typedata[i][2]) {
            cell.className = typedata[i][2];
        }
        tmp = tmp.replace(/\{(\d+)\}/g, function($1, $2) {return addrow.arguments[parseInt($2) + 1];});
        cell.innerHTML = tmp;
    }
    addrowdirect = 0;
}

$(document).ready(function(){
$("input[type='submit']").focus(function(){$(this).blur();});
});

function copyCode(s)
{
	if (window.clipboardData) 
	{
		window.clipboardData.setData('text',s.val());
		alert('复制成功！\t\r请将已复制的代码粘贴到要加入微博秀功能的页面。');
	}
	else
	{
		alert('你的浏览器不支持脚本复制或你拒绝了浏览器安全确认。请尝试[Ctrl+C]复制代码并粘贴到要加入功能的页面。');
	}
}