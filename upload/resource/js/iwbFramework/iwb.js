var IWB_UTIL; // 实用工具类

IWB_UTIL = {

    ltrim: function (text) {
        return text == null ? 
                "" :
                text.toString().replace(/^\s+/ ,"")
    }

   ,rtrim: function (text) {
        return text == null ? 
                "" :
                text.toString().replace(/\s+$/ ,"")
   }

   ,trim: function (text) {
       return this.ltrim(this.rtrim(text));
   }

   ,msglen: function (text) { // 微博字数计算规则 汉字 1 英文 0.5 网址 11 除去首尾空白
        text = text.replace(new RegExp("((news|telnet|nttp|file|http|ftp|https)://){1}(([-A-Za-z0-9]+(\\.[-A-Za-z0-9]+)*(\\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\\.[0-9]{1,3}){3}))(:[0-9]*)?(/[-A-Za-z0-9_\\$\\.\\+\\!\\*\\(\\),;:@&=\\?/~\\#\\%]*)*","gi"),'填充填充填充填充填充填');
        return Math.ceil(($.trim(text.replace(/[^\u0000-\u00ff]/g,"aa")).length)/2);
   }

   ,limit: function (text ,max ,suff) {
        if (text == null) {
            return "";
        }
        text = text.toString();
        suff = suff || "...";
        if (text.length <= max) {
            return text;
        } else {
            return text.substring(0,max) + suff;
        }
   }

   ,limitFileName: function (filename,max) {
        max = max || 10;
        var fileExt;
        var fileNameArr = filename.split(".");
        if (fileNameArr.length > 1) {
            fileExt = fileNameArr.splice(-1);
            return this.limit(fileNameArr.join("") ,max ,"") + "." + fileExt.join("");
        } else {
            return this.limit(fileNameArr[0] ,max ,"");
        }
   }

   ,timedesc: function (nowtime,timestamp) {
       var diff = nowtime - timestamp;
       var nowtimeDate = new Date(nowtime*1000);
       var previousDayDate = new Date( (nowtime - 24 * 3600) * 1000 );
       var timestampDate = new Date(timestamp*1000);
       var timestampYear = timestampDate.getFullYear();
       var timestampMonth = timestampDate.getMonth();
       var timestampDay = timestampDate.getDate();
       var timestampHours = timestampDate.getHours()<10?"0"+timestampDate.getHours():timestampDate.getHours();
       var timestampMinutes = timestampDate.getMinutes()<10?"0"+timestampDate.getMinutes():timestampDate.getMinutes();
       
       if(diff < 60){
           return "刚刚";
       }else if(diff < 3600){
           return Math.floor(diff/60)+"分钟前";
       }
       
       if(nowtimeDate.getFullYear() == timestampYear){
           if(nowtimeDate.getMonth() == timestampMonth){
               if(nowtimeDate.getDate() == timestampDay){
                   return "今天"+timestampHours+":"+timestampMinutes;
               }else if( previousDayDate.getDate() == timestampDay ){
                   return "昨天"+timestampHours+":"+timestampMinutes;
               }else{
                   return [timestampMonth+1,"月",timestampDay,"日"," ",timestampHours,":",timestampMinutes].join("");
               }
           }else{
               return [timestampMonth+1,"月",timestampDay,"日"," ",timestampHours,":",timestampMinutes].join("");
           }
       }else{
           return [timestampYear,"年",timestampMonth+1,"月",timestampDay,"日"," ",timestampHours,":",timestampMinutes].join("");
       }
   }

   // 向text holder插入指定的文本后高亮选中，若已有指定的文本则只高亮

   ,highlightOrInsert: function (text ,holder ,trailer ,suff) {
       trailer = trailer || "#";
       suff = suff || "";
       var range;
       var holderText = holder.value || "";
       var start = holderText.lastIndexOf(text);
       var end = start + text.length;

       // 插入文字
       if (start < 0) {
           holder.value = [holderText ,trailer ,text ,trailer ,suff].join("");
           start = holder.value.lastIndexOf(text);
           end = start + text.length;
       }

       // 高亮文字
       if (document.createRange) {
            holder.setSelectionRange( start, end );
       } else {
            range = holder.createTextRange();
            range.collapse(true);
            range.moveStart( 'character', start );
            range.moveEnd( 'character', end - start );
            range.select();
       }
   }
   
   // 输入框中指定位置插入文本
   ,insertText: function (text ,caret ,holder) {

       var pre;
       var suff;
       var holderText;

       caret = caret || 0;

       if (holder.nodeName) {
           holder = $(holder);
       }

       holderText = holder.val();
       pre = holderText.substr(0,caret);
       suff = holderText.substr(caret);
       holderText = [pre,text,suff].join("");
       holder.val(holderText);
       holder.focus();
       holder.cursorPos([pre,text].join("").length);
   }
};

var IWB_MUSIC_PLAYER; // mp3,ogg,wma播放器封装

(function () {
    
    var player; //播放器
    var error;
    var stateChangeHandlers = [];
    var notifyHandlers;
    
    var wmplayerEventScript; // WMP事件
    var html5AudioEventHandler; // html5音频事件
    
    var playerWrapper = document.createElement("div");
    playerWrapper.style.width = "0";
    playerWrapper.style.height = "0";
    playerWrapper.style.display = "none";
    
    if (window.ActiveXObject) { // IE使用WMP播放控件
        
        playerWrapper.innerHTML = "<object id=\"mplayer\" classid=\"CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6\"></object>";
        
    } else { // 其它浏览器用html5播放
        
        playerWrapper.innerHTML = "<audio id=\"mplayer\"></audio>";
        
    }
    
    document.body.appendChild(playerWrapper);
    
    player = document.getElementById("mplayer");

    notifyHandlers = function (state ,message) {
        var i;
        for (i=0; i<stateChangeHandlers.length; i++) {
            stateChangeHandlers[i](state ,message);
        }
    }
    
    if (player.controls) { // mediaPlayer可用
        
        //wmplayerEventScript = document.createElement("script");
        //wmplayerEventScript.setAttribute("for","mplayer");
        //wmplayerEventScript.setAttribute("event","PlayStateChange(newState)");
        //wmplayerEventScript.text = "if (IWB_MUSIC_PLAYER) {IWB_MUSIC_PLAYER.handlePlayStateChange.call(IWB_MUSIC_PLAYER,newState);}";
        //document.getElementsByTagName('head')[0].appendChild(wmplayerEventScript);
        
        IWB_MUSIC_PLAYER = {

            _src:"",

            _playReady: false,
            
            src: function (url) { // 初始化音乐播放器对象
                this._src = url;
            },
            
            load: function () { // mediaPlayer啥都不干
            },
            
            play: function () {
                if (!player.URL) {
                    player.URL = this._src;
                } else {
                    player.controls.play();
                }
            },

            pause: function () {
                player.controls.pause();
            },

            stop: function () {
                player.controls.stop();
            },
            
            handlePlayStateChange: function (newState) {
                if (newState===3) {
                    notifyHandlers("playing");
                    this._playReady = true;
                } else if (newState===1) {
                    notifyHandlers("stopped");
                    this._playReady = false;
                } else if (newState===2) {
                    notifyHandlers("paused");
                    this._playReady = false;
                } else if (newState===6) {
                    notifyHandlers("buffering");
                } else if (newState===10 && !this._playReady) { // 状态已到准备就绪但没播放，则表示加载音乐出错
                    notifyHandlers("error","加载音乐失败");
                } //else {
                    // 更多 pzayState
                    // http://msdn.microsoft.com/en-us/library/dd564085(v=VS.85).aspx
                //}
                }
            
        };
        
    } else if (player.play) { // html5播放器可用
        // TODO html5标签buffer事件
        html5AudioEvent = {
            handleEvent: function(e){
                switch (e.type) {
                case "playing":
                notifyHandlers("playing");
                break;
                case "pause":
                notifyHandlers("paused");
                break;
                case "ended":
                notifyHandlers("stopped");
                break;
                case "error":
                if (player.networkState === 3) {
                    notifyHandlers("error","加载音乐失败");
                    return;
                }
                notifyHandlers("error","加载音乐失败");
                break;
                }
            }
        };
        
        // https://developer.mozilla.org/En/Using_audio_and_video_in_Firefox
        player.addEventListener("playing",html5AudioEvent,false);
        player.addEventListener("pause",html5AudioEvent,false);
        player.addEventListener("error",html5AudioEvent,false);
        player.addEventListener("ended",html5AudioEvent,false);
        
        IWB_MUSIC_PLAYER = {
            
            src: function (url) {
                try {
                    player.setAttribute("src",url);
                } catch (error) {
                    return false;
                }
                return true;
            },
            
            load: function () {
                player.load();
            },
            
            play: function () {
                player.play();
            },

            pause: function () {
                player.pause();
            },

            stop: function () {
                player.pause();
                player.currentTime=0;
                this.src(player.src); // 同时停止音乐缓冲
                this.load();
                notifyHandlers("stopped");
            }
        };
    } else { // 无播放器可用
        IWB_MUSIC_PLAYER = null;
    }

    if (IWB_MUSIC_PLAYER) {
        IWB_MUSIC_PLAYER.onPlayStateChange = function (handler) {
            if (typeof handler === "function") {
                stateChangeHandlers.push(handler);
            }
        }
    }

}());

var IWB_VALIDATOR; // 数据验证封装

IWB_VALIDATOR = {
    // 错误信息中的label代表将被替换的字段,|代表默认值 
    _regexp: {
                required : [/.+/,"[label]不能为空"] // 字段为必填
                ,email : [/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,"[label|邮箱]无效"] // 邮箱
                ,url : [/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/,"[label|网址]格式不正确，请输入有效的网址"] // 网址
                ,chinese :  [/^[\u4E00-\u9FA5]+$/,"[label]格式不正确，只能输入中文字符"] // 备用
                ,username : [/^[a-z]\w{3,}$/i,"[label|用户名]只能由a-zA-Z0-9组成，且长度大于3，不能以数字开头"] // 新用户用户名
                ,regusername : [/^[a-z]\w{2,14}$/i,"[label|用户名]无效"] // 新用户用户名
                ,regpassword : [/^.{3,}$/i,"[label|密码]不能少于3个字符"] // 新用户密码
                ,uname : [/^\w{3,15}$/i,"[label|帐号]无效，请使用3-15个字母、数字或下划线&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"] // 帐号                
                ,pwd : [/^.{3,15}$/i,"[label|密码]无效，请使用3-15位任意字符。"] // 密码                
                ,nick : [/^[\u4E00-\u9FA5\w-]+$/,"[label|姓名]无效，请使用1-12个中文、字母、数字、下划线或减号。"] // 姓名               
                ,homepage : [/^((news|telnet|nttp|file|http|ftp|https):\/\/)(([-A-Za-z0-9]+(\.[-A-Za-z0-9]+)*(\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\.[0-9]{1,3}){3}))(:[0-9]*)?(\/[-A-Za-z0-9_\$\.\+\!\*\(\),;:@&=\?\/~\#\%]*)*$/,"[label|个人主页]请输入正确的网址。"] // 个人主页
    }
};

(function () { //根据正则表达式自动添加方法
    var validator;
    for ( validator in IWB_VALIDATOR._regexp) {
        if ( IWB_VALIDATOR._regexp.hasOwnProperty(validator) ) {
            // 返回空代表验证通过，否则返回具有一定规则信息的错误信息
            IWB_VALIDATOR[validator] = new Function("str","return IWB_VALIDATOR._regexp[\"" + validator +"\"][0].test(str) ? \"\": IWB_VALIDATOR._regexp[\"" + validator + "\"][1]");
        }
    }
}());

var IWB_LOCALSTORAGE; // 本地存储封装

IWB_LOCALSTORAGE = {};

// JSON库
/*
    http://www.JSON.org/json2.js
    2011-02-23

    Public Domain.

    NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.

    See http://www.JSON.org/js.html


    This code should be minified before deployment.
    See http://javascript.crockford.com/jsmin.html

    USE YOUR OWN COPY. IT IS EXTREMELY UNWISE TO LOAD CODE FROM SERVERS YOU DO
    NOT CONTROL.


    This file creates a global JSON object containing two methods: stringify
    and parse.

        JSON.stringify(value, replacer, space)
            value       any JavaScript value, usually an object or array.

            replacer    an optional parameter that determines how object
                        values are stringified for objects. It can be a
                        function or an array of strings.

            space       an optional parameter that specifies the indentation
                        of nested structures. If it is omitted, the text will
                        be packed without extra whitespace. If it is a number,
                        it will specify the number of spaces to indent at each
                        level. If it is a string (such as '\t' or '&nbsp;'),
                        it contains the characters used to indent at each level.

            This method produces a JSON text from a JavaScript value.

            When an object value is found, if the object contains a toJSON
            method, its toJSON method will be called and the result will be
            stringified. A toJSON method does not serialize: it returns the
            value represented by the name/value pair that should be serialized,
            or undefined if nothing should be serialized. The toJSON method
            will be passed the key associated with the value, and this will be
            bound to the value

            For example, this would serialize Dates as ISO strings.

                Date.prototype.toJSON = function (key) {
                    function f(n) {
                        // Format integers to have at least two digits.
                        return n < 10 ? '0' + n : n;
                    }

                    return this.getUTCFullYear()   + '-' +
                         f(this.getUTCMonth() + 1) + '-' +
                         f(this.getUTCDate())      + 'T' +
                         f(this.getUTCHours())     + ':' +
                         f(this.getUTCMinutes())   + ':' +
                         f(this.getUTCSeconds())   + 'Z';
                };

            You can provide an optional replacer method. It will be passed the
            key and value of each member, with this bound to the containing
            object. The value that is returned from your method will be
            serialized. If your method returns undefined, then the member will
            be excluded from the serialization.

            If the replacer parameter is an array of strings, then it will be
            used to select the members to be serialized. It filters the results
            such that only members with keys listed in the replacer array are
            stringified.

            Values that do not have JSON representations, such as undefined or
            functions, will not be serialized. Such values in objects will be
            dropped; in arrays they will be replaced with null. You can use
            a replacer function to replace those with JSON values.
            JSON.stringify(undefined) returns undefined.

            The optional space parameter produces a stringification of the
            value that is filled with line breaks and indentation to make it
            easier to read.

            If the space parameter is a non-empty string, then that string will
            be used for indentation. If the space parameter is a number, then
            the indentation will be that many spaces.

            Example:

            text = JSON.stringify(['e', {pluribus: 'unum'}]);
            // text is '["e",{"pluribus":"unum"}]'


            text = JSON.stringify(['e', {pluribus: 'unum'}], null, '\t');
            // text is '[\n\t"e",\n\t{\n\t\t"pluribus": "unum"\n\t}\n]'

            text = JSON.stringify([new Date()], function (key, value) {
                return this[key] instanceof Date ?
                    'Date(' + this[key] + ')' : value;
            });
            // text is '["Date(---current time---)"]'


        JSON.parse(text, reviver)
            This method parses a JSON text to produce an object or array.
            It can throw a SyntaxError exception.

            The optional reviver parameter is a function that can filter and
            transform the results. It receives each of the keys and values,
            and its return value is used instead of the original value.
            If it returns what it received, then the structure is not modified.
            If it returns undefined then the member is deleted.

            Example:

            // Parse the text. Values that look like ISO date strings will
            // be converted to Date objects.

            myData = JSON.parse(text, function (key, value) {
                var a;
                if (typeof value === 'string') {
                    a =
/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)Z$/.exec(value);
                    if (a) {
                        return new Date(Date.UTC(+a[1], +a[2] - 1, +a[3], +a[4],
                            +a[5], +a[6]));
                    }
                }
                return value;
            });

            myData = JSON.parse('["Date(09/09/2001)"]', function (key, value) {
                var d;
                if (typeof value === 'string' &&
                        value.slice(0, 5) === 'Date(' &&
                        value.slice(-1) === ')') {
                    d = new Date(value.slice(5, -1));
                    if (d) {
                        return d;
                    }
                }
                return value;
            });


    This is a reference implementation. You are free to copy, modify, or
    redistribute.
*/

/*jslint evil: true, strict: false, regexp: false */

/*members "", "\b", "\t", "\n", "\f", "\r", "\"", JSON, "\\", apply,
    call, charCodeAt, getUTCDate, getUTCFullYear, getUTCHours,
    getUTCMinutes, getUTCMonth, getUTCSeconds, hasOwnProperty, join,
    lastIndex, length, parse, prototype, push, replace, slice, stringify,
    test, toJSON, toString, valueOf
*/


// Create a JSON object only if one does not already exist. We create the
// methods in a closure to avoid creating global variables.

var JSON;
if (!JSON) {
    JSON = {};
}

(function () {
    "use strict";

    function f(n) {
        // Format integers to have at least two digits.
        return n < 10 ? '0' + n : n;
    }

    if (typeof Date.prototype.toJSON !== 'function') {

        Date.prototype.toJSON = function (key) {

            return isFinite(this.valueOf()) ?
                this.getUTCFullYear()     + '-' +
                f(this.getUTCMonth() + 1) + '-' +
                f(this.getUTCDate())      + 'T' +
                f(this.getUTCHours())     + ':' +
                f(this.getUTCMinutes())   + ':' +
                f(this.getUTCSeconds())   + 'Z' : null;
        };

        String.prototype.toJSON      =
            Number.prototype.toJSON  =
            Boolean.prototype.toJSON = function (key) {
                return this.valueOf();
            };
    }

    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap,
        indent,
        meta = {    // table of character substitutions
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        },
        rep;


    function quote(string) {

// If the string contains no control characters, no quote characters, and no
// backslash characters, then we can safely slap some quotes around it.
// Otherwise we must also replace the offending characters with safe escape
// sequences.

        escapable.lastIndex = 0;
        return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
            var c = meta[a];
            return typeof c === 'string' ? c :
                '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
        }) + '"' : '"' + string + '"';
    }


    function str(key, holder) {

// Produce a string from holder[key].

        var i,          // The loop counter.
            k,          // The member key.
            v,          // The member value.
            length,
            mind = gap,
            partial,
            value = holder[key];

// If the value has a toJSON method, call it to obtain a replacement value.

        if (value && typeof value === 'object' &&
                typeof value.toJSON === 'function') {
            value = value.toJSON(key);
        }

// If we were called with a replacer function, then call the replacer to
// obtain a replacement value.

        if (typeof rep === 'function') {
            value = rep.call(holder, key, value);
        }

// What happens next depends on the value's type.

        switch (typeof value) {
        case 'string':
            return quote(value);

        case 'number':

// JSON numbers must be finite. Encode non-finite numbers as null.

            return isFinite(value) ? String(value) : 'null';

        case 'boolean':
        case 'null':

// If the value is a boolean or null, convert it to a string. Note:
// typeof null does not produce 'null'. The case is included here in
// the remote chance that this gets fixed someday.

            return String(value);

// If the type is 'object', we might be dealing with an object or an array or
// null.

        case 'object':

// Due to a specification blunder in ECMAScript, typeof null is 'object',
// so watch out for that case.

            if (!value) {
                return 'null';
            }

// Make an array to hold the partial results of stringifying this object value.

            gap += indent;
            partial = [];

// Is the value an array?

            if (Object.prototype.toString.apply(value) === '[object Array]') {

// The value is an array. Stringify every element. Use null as a placeholder
// for non-JSON values.

                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || 'null';
                }

// Join all of the elements together, separated with commas, and wrap them in
// brackets.

                v = partial.length === 0 ? '[]' : gap ?
                    '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']' :
                    '[' + partial.join(',') + ']';
                gap = mind;
                return v;
            }

// If the replacer is an array, use it to select the members to be stringified.

            if (rep && typeof rep === 'object') {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    if (typeof rep[i] === 'string') {
                        k = rep[i];
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            } else {

// Otherwise, iterate through all of the keys in the object.

                for (k in value) {
                    if (Object.prototype.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            }

// Join all of the member texts together, separated with commas,
// and wrap them in braces.

            v = partial.length === 0 ? '{}' : gap ?
                '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}' :
                '{' + partial.join(',') + '}';
            gap = mind;
            return v;
        }
    }

// If the JSON object does not yet have a stringify method, give it one.

    if (typeof JSON.stringify !== 'function') {
        JSON.stringify = function (value, replacer, space) {

// The stringify method takes a value and an optional replacer, and an optional
// space parameter, and returns a JSON text. The replacer can be a function
// that can replace values, or an array of strings that will select the keys.
// A default replacer method can be provided. Use of the space parameter can
// produce text that is more easily readable.

            var i;
            gap = '';
            indent = '';

// If the space parameter is a number, make an indent string containing that
// many spaces.

            if (typeof space === 'number') {
                for (i = 0; i < space; i += 1) {
                    indent += ' ';
                }

// If the space parameter is a string, it will be used as the indent string.

            } else if (typeof space === 'string') {
                indent = space;
            }

// If there is a replacer, it must be a function or an array.
// Otherwise, throw an error.

            rep = replacer;
            if (replacer && typeof replacer !== 'function' &&
                    (typeof replacer !== 'object' ||
                    typeof replacer.length !== 'number')) {
                throw new Error('JSON.stringify');
            }

// Make a fake root object containing our value under the key of ''.
// Return the result of stringifying the value.

            return str('', {'': value});
        };
    }


// If the JSON object does not yet have a parse method, give it one.

    if (typeof JSON.parse !== 'function') {
        JSON.parse = function (text, reviver) {

// The parse method takes a text and an optional reviver function, and returns
// a JavaScript value if the text is a valid JSON text.

            var j;

            function walk(holder, key) {

// The walk method is used to recursively walk the resulting structure so
// that modifications can be made.

                var k, v, value = holder[key];
                if (value && typeof value === 'object') {
                    for (k in value) {
                        if (Object.prototype.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v;
                            } else {
                                delete value[k];
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value);
            }


// Parsing happens in four stages. In the first stage, we replace certain
// Unicode characters with escape sequences. JavaScript handles many characters
// incorrectly, either silently deleting them, or treating them as line endings.

            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function (a) {
                    return '\\u' +
                        ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
                });
            }

// In the second stage, we run the text against regular expressions that look
// for non-JSON patterns. We are especially concerned with '()' and 'new'
// because they can cause invocation, and '=' because it can cause mutation.
// But just to be safe, we want to reject all unexpected forms.

// We split the second stage into 4 regexp operations in order to work around
// crippling inefficiencies in IE's and Safari's regexp engines. First we
// replace the JSON backslash pairs with '@' (a non-JSON character). Second, we
// replace all simple value tokens with ']' characters. Third, we delete all
// open brackets that follow a colon or comma or that begin the text. Finally,
// we look to see that the remaining characters are only whitespace or ']' or
// ',' or ':' or '{' or '}'. If that is so, then the text is safe for eval.

            if (/^[\],:{}\s]*$/
                    .test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
                        .replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
                        .replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

// In the third stage we use the eval function to compile the text into a
// JavaScript structure. The '{' operator is subject to a syntactic ambiguity
// in JavaScript: it can begin a block or an object literal. We wrap the text
// in parens to eliminate the ambiguity.

                j = eval('(' + text + ')');

// In the optional fourth stage, we recursively walk the new structure, passing
// each name/value pair to a reviver function for possible transformation.

                return typeof reviver === 'function' ?
                    walk({'': j}, '') : j;
            }

// If the text is not JSON parseable, then a SyntaxError is thrown.

            throw new SyntaxError('JSON.parse');
        };
    }
}());

// MD5算法用于生成hashkey
(function () {
    /* md5.js - MD5 Message-Digest
     * Copyright (C) 1999,2002 Masanao Izumo <iz@onicos.co.jp>
     * Version: 2.0.0
     * LastModified: May 13 2002
     *
     * This program is free software.  You can redistribute it and/or modify
     * it without any warranty.  This library calculates the MD5 based on RFC1321.
     * See RFC1321 for more information and algorism.
     */
    
    /* Interface:
     * md5_128bits = MD5_hash(data);
     * md5_hexstr = MD5_hexhash(data);
     */
    
    /* ChangeLog
     * 2002/05/13: Version 2.0.0 released
     * NOTICE: API is changed.
     * 2002/04/15: Bug fix about MD5 length.
     */
    //    md5_T[i] = parseInt(Math.abs(Math.sin(i)) * 4294967296.0);
    var MD5_T = new Array(0x00000000, 0xd76aa478, 0xe8c7b756, 0x242070db,
                  0xc1bdceee, 0xf57c0faf, 0x4787c62a, 0xa8304613,
                  0xfd469501, 0x698098d8, 0x8b44f7af, 0xffff5bb1,
                  0x895cd7be, 0x6b901122, 0xfd987193, 0xa679438e,
                  0x49b40821, 0xf61e2562, 0xc040b340, 0x265e5a51,
                  0xe9b6c7aa, 0xd62f105d, 0x02441453, 0xd8a1e681,
                  0xe7d3fbc8, 0x21e1cde6, 0xc33707d6, 0xf4d50d87,
                  0x455a14ed, 0xa9e3e905, 0xfcefa3f8, 0x676f02d9,
                  0x8d2a4c8a, 0xfffa3942, 0x8771f681, 0x6d9d6122,
                  0xfde5380c, 0xa4beea44, 0x4bdecfa9, 0xf6bb4b60,
                  0xbebfbc70, 0x289b7ec6, 0xeaa127fa, 0xd4ef3085,
                  0x04881d05, 0xd9d4d039, 0xe6db99e5, 0x1fa27cf8,
                  0xc4ac5665, 0xf4292244, 0x432aff97, 0xab9423a7,
                  0xfc93a039, 0x655b59c3, 0x8f0ccc92, 0xffeff47d,
                  0x85845dd1, 0x6fa87e4f, 0xfe2ce6e0, 0xa3014314,
                  0x4e0811a1, 0xf7537e82, 0xbd3af235, 0x2ad7d2bb,
                  0xeb86d391);
    
    var MD5_round1 = new Array(new Array( 0, 7, 1), new Array( 1,12, 2),
                   new Array( 2,17, 3), new Array( 3,22, 4),
                   new Array( 4, 7, 5), new Array( 5,12, 6),
                   new Array( 6,17, 7), new Array( 7,22, 8),
                   new Array( 8, 7, 9), new Array( 9,12,10),
                   new Array(10,17,11), new Array(11,22,12),
                   new Array(12, 7,13), new Array(13,12,14),
                   new Array(14,17,15), new Array(15,22,16));
    
    var MD5_round2 = new Array(new Array( 1, 5,17), new Array( 6, 9,18),
                   new Array(11,14,19), new Array( 0,20,20),
                   new Array( 5, 5,21), new Array(10, 9,22),
                   new Array(15,14,23), new Array( 4,20,24),
                   new Array( 9, 5,25), new Array(14, 9,26),
                   new Array( 3,14,27), new Array( 8,20,28),
                   new Array(13, 5,29), new Array( 2, 9,30),
                   new Array( 7,14,31), new Array(12,20,32));
    
    var MD5_round3 = new Array(new Array( 5, 4,33), new Array( 8,11,34),
                   new Array(11,16,35), new Array(14,23,36),
                   new Array( 1, 4,37), new Array( 4,11,38),
                   new Array( 7,16,39), new Array(10,23,40),
                   new Array(13, 4,41), new Array( 0,11,42),
                   new Array( 3,16,43), new Array( 6,23,44),
                   new Array( 9, 4,45), new Array(12,11,46),
                   new Array(15,16,47), new Array( 2,23,48));
    
    var MD5_round4 = new Array(new Array( 0, 6,49), new Array( 7,10,50),
                   new Array(14,15,51), new Array( 5,21,52),
                   new Array(12, 6,53), new Array( 3,10,54),
                   new Array(10,15,55), new Array( 1,21,56),
                   new Array( 8, 6,57), new Array(15,10,58),
                   new Array( 6,15,59), new Array(13,21,60),
                   new Array( 4, 6,61), new Array(11,10,62),
                   new Array( 2,15,63), new Array( 9,21,64));
    
    function MD5_F(x, y, z) { return (x & y) | (~x & z); }
    function MD5_G(x, y, z) { return (x & z) | (y & ~z); }
    function MD5_H(x, y, z) { return x ^ y ^ z;          }
    function MD5_I(x, y, z) { return y ^ (x | ~z);       }
    
    var MD5_round = new Array(new Array(MD5_F, MD5_round1),
                  new Array(MD5_G, MD5_round2),
                  new Array(MD5_H, MD5_round3),
                  new Array(MD5_I, MD5_round4));
    
    function MD5_pack(n32) {
      return String.fromCharCode(n32 & 0xff) +
         String.fromCharCode((n32 >>> 8) & 0xff) +
         String.fromCharCode((n32 >>> 16) & 0xff) +
         String.fromCharCode((n32 >>> 24) & 0xff);
    }
    
    function MD5_unpack(s4) {
      return  s4.charCodeAt(0)        |
         (s4.charCodeAt(1) <<  8) |
         (s4.charCodeAt(2) << 16) |
         (s4.charCodeAt(3) << 24);
    }
    
    function MD5_number(n) {
      while (n < 0)
        n += 4294967296;
      while (n > 4294967295)
        n -= 4294967296;
      return n;
    }
    
    function MD5_apply_round(x, s, f, abcd, r) {
      var a, b, c, d;
      var kk, ss, ii;
      var t, u;
    
      a = abcd[0];
      b = abcd[1];
      c = abcd[2];
      d = abcd[3];
      kk = r[0];
      ss = r[1];
      ii = r[2];
    
      u = f(s[b], s[c], s[d]);
      t = s[a] + u + x[kk] + MD5_T[ii];
      t = MD5_number(t);
      t = ((t<<ss) | (t>>>(32-ss)));
      t += s[b];
      s[a] = MD5_number(t);
    }
    
    function MD5_hash(data) {
      var abcd, x, state, s;
      var len, index, padLen, f, r;
      var i, j, k;
      var tmp;
    
      state = new Array(0x67452301, 0xefcdab89, 0x98badcfe, 0x10325476);
      len = data.length;
      index = len & 0x3f;
      padLen = (index < 56) ? (56 - index) : (120 - index);
      if(padLen > 0) {
        data += "\x80";
        for(i = 0; i < padLen - 1; i++)
          data += "\x00";
      }
      data += MD5_pack(len * 8);
      data += MD5_pack(0);
      len  += padLen + 8;
      abcd = new Array(0, 1, 2, 3);
      x    = new Array(16);
      s    = new Array(4);
    
      for(k = 0; k < len; k += 64) {
        for(i = 0, j = k; i < 16; i++, j += 4) {
          x[i] = data.charCodeAt(j) |
            (data.charCodeAt(j + 1) <<  8) |
            (data.charCodeAt(j + 2) << 16) |
            (data.charCodeAt(j + 3) << 24);
        }
        for(i = 0; i < 4; i++)
          s[i] = state[i];
        for(i = 0; i < 4; i++) {
          f = MD5_round[i][0];
          r = MD5_round[i][1];
          for(j = 0; j < 16; j++) {
        MD5_apply_round(x, s, f, abcd, r[j]);
        tmp = abcd[0];
        abcd[0] = abcd[3];
        abcd[3] = abcd[2];
        abcd[2] = abcd[1];
        abcd[1] = tmp;
          }
        }
    
        for(i = 0; i < 4; i++) {
          state[i] += s[i];
          state[i] = MD5_number(state[i]);
        }
      }
    
      return MD5_pack(state[0]) +
         MD5_pack(state[1]) +
         MD5_pack(state[2]) +
         MD5_pack(state[3]);
    }
    
    function MD5_hexhash(data) {
        var i, out, c;
        var bit128;
    
        bit128 = MD5_hash(data);
        out = "";
        for(i = 0; i < 16; i++) {
        c = bit128.charCodeAt(i);
        out += "0123456789abcdef".charAt((c>>4) & 0xf);
        out += "0123456789abcdef".charAt(c & 0xf);
        }
        return out;
    }
    
    IWB_LOCALSTORAGE.hash = function (data) {
        return MD5_hexhash(data);
    }
})();

(function () {
    
    var host = window.location.host; // 完整的域名包含端口
    var defaultExpire = 7; // 自存入或修改起保存的天数
    var userData; // IE 下本地存储解决方案
    var fixhash; //IE下USER DATA KEY不能以数字(0?)开头
    
    if (window.localStorage) { // 支持html5标准本地存储的浏览器,html5本身未实现expire机制，我们在这里实现,html5优先
        
        IWB_LOCALSTORAGE.set = function (key ,val ,expire) {
            var keyhash = this.hash(host+key);
            expire = expire || defaultExpire; // 过期天数
            expire = Math.round(new Date().getTime() / 1000) + expire * 24 * 3600;//过期时间
            var val = {
                       value: val,
                       expire: expire
                      };
            localStorage[keyhash] = JSON.stringify(val);
            // return localStorage[keyhash]; // 立刻取得，原始数据格式，有返回说明保存成功
            return this.get(key);
        };
        
        IWB_LOCALSTORAGE.get = function (key) {
            var keyhash = this.hash(host+key);
            var val = localStorage[keyhash];
            var time = Math.round(new Date().getTime() / 1000);
            if (!val) { // 无值
                return null;
            }
            val = JSON.parse(val);
            if (time > val.expire) { // 已过期
                return null;
            }
            return val.value;
        };
        
        IWB_LOCALSTORAGE.del = function (key) {
            var keyhash = this.hash(host+key);
            if (this.get(key)) { // key存在
                localStorage[keyhash] = "";
            }
            // return true;
            return !!!this.get(key); // 取不到值说明删除成功
        };
        
    } else {
        userData = document.getElementById("#iwbUserData");
        fixhash = function (hash) {
            if (hash.match(/^\d/)) { 
                return "fix"+hash;
            }
            return hash
        };
        
        if (!userData || userData.nodeName.toLowerCase() !== "input") { // 防止取错节点
            userData = document.createElement("input");
            userData.type = "hidden";
            userData.style.display="none";
            userData.addBehavior("#default#userData");
            userData.expires = new Date(new Date().getTime() + 365 * 10 * 24 * 3600 * 1000).toUTCString(); // 不使用IE内建过期机制，10年过期时间
            document.body.appendChild(userData);
        }
        
        IWB_LOCALSTORAGE.set = function (key ,val ,expire) {
            var hosthash = fixhash(this.hash(host));
            var keyhash = fixhash(this.hash(host+key));
            expire = expire || defaultExpire; // 过期天数
            expire = Math.round(new Date().getTime() / 1000) + expire * 24 * 3600;//过期时间
            var val = {
                       value: val,
                       expire: expire
                      };
            userData.load(hosthash);
            userData.setAttribute(keyhash,JSON.stringify(val));
            userData.save(hosthash);
            // return userData.getAttribute(keyhash); 原始数据格式
            return this.get(key);
        };
        
        IWB_LOCALSTORAGE.get = function (key) {
            var hosthash =  fixhash(this.hash(host));
            var keyhash = fixhash(this.hash(host+key));
            var val;
            userData.load(hosthash);
            val = userData.getAttribute(keyhash);
            var time = Math.round(new Date().getTime() / 1000);
            if (!val) { // 无值
                return null;
            }
            val = JSON.parse(val);
            if (time > val.expire) { // 已过期
                return null;
            }
            return val.value;
        };
        
        IWB_LOCALSTORAGE.del = function (key) {
            var hosthash = fixhash(this.hash(host));
            var keyhash = fixhash(this.hash(host+key));
            if (this.get(key)) { // key存在
                userData.load(hosthash);
                userData.setAttribute(keyhash,"");
                userData.save(hosthash);
            }
            // return true;
            return !!!this.get(key); // 取不到值说明删除成功
        };
    }
})();
var IWB_API; // 后台交互封装

IWB_API = {

    // 支持二级目录
    _fixUrl: function (url) {
        return (window.iwbRoot/*路径根目录*/ || "/index.php/") + url;
    },
    
    // 修正广播提交地址
    _getAddUrl: function () {
        return (window.iwbAddUrl || "index/t/add");
    },

    // 当前登录的用户名
    _getUsername: function () {
        return window.iwbUsername || "";
    },
    
    // 创建本地存储KEY，由三部分组成种类，是否本地关系链，key名称
    _buildKey: function (category ,name) {
        return [category,window.iwbStoreType || "0",name].join("");
    },

    //  Ajax GET封装
    _get: function (identity ,uri ,callback) {
        var notifier = this;
        IWB_API._HTTP.get(IWB_API._fixUrl(uri), function (response) {
            notifier.notify(identity,response);
            if (callback) {
                callback(identity ,response);
            }
        }, {
            timeout: 30 * 1000 ,
            timeoutHandler: function (url) {
                var timeoutResponse = {
                    ret: -1,
                    msg: "timeout",
                    data: {
                        url: url
                    }
                };
                notifier.notify(identity ,timeoutResponse);
                if (callback) {
                    callback(identity ,timeoutResponse);
                }
            },
            errorHandler: function (status ,statusText ,e) {
                var serverErrorResponse = {
                    ret: -1,
                    msg: "error",
                    data: {
                        status: status
                        ,statusText: statusText
                        ,exception: e ? e.toString() : "no exception"
                    }
                }; 
                notifier.notify(identity,serverErrorResponse);
                if (callback) {
                    callback(identity ,serverErrorResponse);
                }
            }
        });
    },

    //  Ajax POST封装
    _post: function (identity ,uri ,postdata ,callback) {
        var notifier = this;
        IWB_API._HTTP.post(
            IWB_API._fixUrl(uri)
           ,postdata
           ,function (response) {
                notifier.notify(identity,response);
                if (callback) {
                    callback(identity,response);
                }
            }
           ,function (status ,statusText ,e) {
                var serverErrorResponse = {
                    ret: -1,
                    msg: "error",
                    data: {
                        status: status
                        ,statusText: statusText
                        ,exception: e ? e.toString() : "no exception"
                    }
                }; 
                notifier.notify(identity,serverErrorResponse);
                if (callback) {
                    callback(identity,serverErrorResponse);
                }
            }
        );
        
    }, 

    // 话题墙更新
    _wall: function (identity ,options ,callback) {
        this._get.call(options.notifier || this._wall ,identity ,"wall/more/id/" + options.id + "/start/" + options.offset + "/limit/" + options.count ,callback);
    },

    // 获取一条话题墙
    wallOne: function (identity ,id ,offset ,callback) {
        var self = this.wallOne;
        this._wall(identity, {
            notifier: self
            ,"id": id // 1 收藏
            ,"offset": offset
            ,"count": 1
        } ,callback);
    },

    // 皮肤列表
    listSkin: function (identity ,callback) {
        this._get.call(this.listSkin ,identity ,"index/skin" ,callback);
    },

    // 为当前用户保存皮肤设置
    saveSkin: function (identity ,skinName ,callback) {
        this._get.call(this.saveSkin ,identity ,"index/skin/set/s/" + skinName ,callback);
    },

    // 发私信
    sendMail: function (identity ,receiver ,content ,callback) {
        this._post.call(this.sendMail ,identity ,"box/add" ,{name:receiver,"content":content} ,callback);
    },
    // 收听/取消收听
    _follow: function (identity ,options ,callback) {
        this._get.call(options.notifier || this._follow ,identity ,"index/friend/follow/type/" + options.type + "/name/" + options.name ,callback);
    },

    // 收听
    follow: function (identity ,name ,callback) {
        var self = this.follow;
        this._follow(identity, {
            notifier: self,
            type: 1, // 1 收听
            name: name
        } ,callback);
    },

    // 取消收听
    unfollow: function (identity ,name ,callback) {
        var self = this.follow;
        this._follow(identity, {
            notifier: self,
            type: 0, // 0 取消收听
            name: name
        } ,callback);
    },

    // 提醒当前用户新听众计数，首页未阅读的广播计数，新的提到我的广播计数，新私信计数
    updateNotice: function (identity ,callback) {
        this._get.call(this.updateNotice ,identity ,"index/t/newmsginfo" ,callback);
    },

    // 拉取更多时间线
    // type 时间线类型 1 收听的人和自己 的广播  2 自己的广播 3 提到我的广播  4 收藏的广播
    // name 用户名，不填写为登录用户
    // lid 页面最后一条广播的id
    // ltime 页面最后一条广播的时间戳
    timelineMore: function (identity ,options ,callback) {
        var requrl = "index/"; 
        if (!options.name) {
            switch (options.type) {
                case 1:
                requrl += "index/more";
                break;
                case 2:
                requrl += "mine/more";
                break;
                case 3:
                requrl += "at/more";
                break;
                case 4:
                requrl += "favor/more";
                break;
                default:
                requrl += "index/more";
            }
        } else {
            requrl = "index/guest/more/u/";
            if (options.name) {
                requrl += options.name;
            }
        }

        if (options.f) {
            requrl += ("/f/" + options.f);
        }

        if (options.ltime) {
            requrl += ("/t/" + options.ltime);
        }

        if (options.lid) {
            requrl += ("/lid/" + options.lid);
        }

        if (options.num) {
            requrl += ("/num/" + options.num);
        }

        if (options.utype) {
            requrl += ("/utype/" + options.utype);
        }

        if (options.ctype) {
            requrl += ("/ctype/" + options.ctype);
        }

        this._get.call(this.timelineMore ,identity ,requrl ,callback);
    },
    
    // 删除一条广播
    del: function (identity ,msgid ,callback) {
        this._get.call(this.del ,identity ,"index/t/del/tid/"+msgid ,callback);
    },
    
    // 收藏的操作
    _favor: function (identity ,options ,callback) {
        this._get.call(options.notifier || this._favor ,identity ,"index/favor/t/type/" + options.type + "/tid/" + options.msgid ,callback);
    },
    
    // 收藏
    addFavor: function (identity ,msgid ,callback) {
        var self = this.addFavor;
        this._favor(identity, {
            notifier: self,
            type: 1, // 1 收藏
            msgid: msgid
        } ,callback);
    },
    
    // 取消收藏
    delFavor: function (identity ,msgid ,callback) {
        var self = this.delFavor;
        this._favor(identity, {
            notifier: self,
            type: 0, // 0 取消收藏
            msgid: msgid
        } ,callback);
    },
    
    // 发表任意类型广播
    _add: function (identity ,options ,callback) {
        var self = this._add;
        var notifier = options.notifier || self;
        delete options.notifier;
        this._post.call(notifier ,identity ,this._getAddUrl() ,options ,callback);
    },

    // 原创
    add: function (identity ,content/*内容*/ ,callback) {
        var self = this.add;
        this._add(identity ,{
            notifier: self,
            type: 1,
            content: content,
            format: ""
        } ,callback);
    },
    
    // 对话
    chat: function (identity ,id/*原始帖子id*/ ,content/*内容*/ ,callback) {
        var self = this.chat;
        this._add(identity ,{
            notifier: self,
            type: 3,
            content: content,
            reid: id,
            format: "html"
        } ,callback);
    },
    
    // 评论
    reply: function (identity ,id/*原始帖子id*/ ,content/*内容*/ ,callback) {
        var self = this.reply;
        this._add(identity ,{
            notifier: self,
            type: 4,
            content: content,
            reid: id,
            format: "html"
        } ,callback);
    },
    
    // 转播
    repost: function (identity ,id/*原始帖子id*/ ,content/*内容*/ ,callback) {
        var self = this.repost;
        this._add(identity ,{
            notifier: self,
            type: 2,
            content: content,
            reid: id,
            format: "html"
        } ,callback);
    },
    
    // 微博转播列表
    relist: function (identity ,msgid ,callback) {
        this._get.call(this.relist ,identity ,"index/t/rellist/tid/"+msgid ,callback);
    },
    
    // 账户信息，不穿参取本人的
    userinfo: function (identity ,name ,_callback) {
        var key = this._buildKey("iwbUserinfo",name);
        var val;
        var response;
        // 首先尝试从本地存储取数据
        // 暂不缓存
        if (false && window.IWB_LOCALSTORAGE) {
            if (name) {//尝试从本地存储中取数据
                val = IWB_LOCALSTORAGE.get(key);
                if (val) {
                    response = {ret: 0,msg: "ok",data: val};
                    this.userinfo.notify(identity,response);
                    if (_callback) {
                        _callback(identity ,response);
                    }
                    return;
                }
            }
        }
        
        // 
        this._get.call(this.userinfo ,identity ,"index/u/guestinfo/name/"+name ,function (identity ,response){
            if (response.ret === 0) { // 成功后本地存储
                if (window.IWB_LOCALSTORAGE && name) {
                    IWB_LOCALSTORAGE.set(key,response.data);
                }
            }
            if (_callback) {
                _callback(identity ,response);
            }
        });
    },

    // 取粉丝列表
    idollist: function (identity ,_callback) {
        var self = IWB_API.idollist;
        var name =  this._getUsername();
        var key = this._buildKey("iwbIdollist",name);
        var val;
        var response;

        // 首先尝试从本地存储取数据
        if (window.IWB_LOCALSTORAGE) {
            if (name) {//尝试从本地存储中取数据
                val = IWB_LOCALSTORAGE.get(key);
                if (val) {
                    response = {ret: 0,msg: "ok",data: val};
                    self.notify(identity,response);
                    if (_callback) {
                        _callback(identity ,response);
                    }
                    return;
                }
            }
        }

        // 
        this._get.call(self ,identity ,"index/t/myidollist" ,function (identity ,response){
            if (response.ret === 0) { // 成功后本地存储
                if (window.IWB_LOCALSTORAGE && name) {
                    IWB_LOCALSTORAGE.set(key,response.data);
                }
            }
            if (_callback) {
                _callback(identity ,response);
            }
        });
    },
    
    // 取视频信息
    videoInfo: function (identity ,videoUrl ,callback) {
        var self = this.videoInfo;
        videoUrl = videoUrl || "";
        videoUrl = encodeURIComponent(videoUrl);

        this._post.call(self ,identity ,"index/t/videoinfo" ,{url:videoUrl} ,callback);
    },
    
    // 取音乐信息，目前是简单分析链接直接返回
    musicInfo: function (identity ,musicUrl ,callback){
        musicUrl = musicUrl || "";
        var musicFullName = musicUrl.match(/[^\/\\]+$/);
        var self = this.musicInfo;
        var response; 
        if (musicFullName) {
            response = {
                ret: 0,
                msg: "ok",
                data: {
                    artist: "",
                    title: musicFullName[0],
                    url: musicUrl
                }
            };
        } else {
            response = {
                ret: -1,
                msg: "invalid song"
            };
        }

        self.notify(identity,response);
        if (callback) {
            callback(identity,response);
        }
    }

};

// 重新封装Ajax请求，目的是不依赖jQuery
IWB_API._HTTP = {};
IWB_API._HTTP._factories = [
    function() { return new XMLHttpRequest(); },
    function() { return new ActiveXObject("Msxml2.XMLHTTP"); },
    function() { return new ActiveXObject("Microsoft.XMLHTTP"); }
];
IWB_API._HTTP._factory = null;
IWB_API._HTTP.newRequest = function() {
    var factory;
    var request;
    var i;
    if (IWB_API._HTTP._factory !== null) {
        return IWB_API._HTTP._factory();
    }
    for(i = 0; i < IWB_API._HTTP._factories.length; i++) {
        try {
            factory = IWB_API._HTTP._factories[i];
            request = factory();
            if (request !== null) {
                IWB_API._HTTP._factory = factory;
                return request;
            }
        } catch(e) {
            continue;
        }
    }
    IWB_API._HTTP._factory = function() {
        throw new Error("XMLHttpRequest not supported");
    }
    IWB_API._HTTP._factory();
};
IWB_API._HTTP._getResponse = function(request) { // 返回统一解析为JSON格式
    /*switch (request.getResponseHeader("Content-Type")) {
    case "text/xml":
        return request.responseXML;
    case "text/json":
    case "text/javascript":
    case "application/javascript":
    case "application/x-javascript":*/
        // 解析JSON，以下代码来自jQuery源码
        var data = request.responseText;
        if ( typeof data !== "string" || !data ) {
            return null;
        }
        // Make sure leading/trailing whitespace is removed (IE can't handle it)
        data = data.replace(/^\s+/,"").replace(/\s+$/,"");

        // Attempt to parse using the native JSON parser first
        if ( window.JSON && window.JSON.parse ) {
            return window.JSON.parse( data );
        }

        // Make sure the incoming data is actual JSON
        // Logic borrowed from http://json.org/json2.js
        if ( /^[\],:{}\s]*$/.test( data.replace( /\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, "@" )
            .replace( /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, "]" )
            .replace( /(?:^|:|,)(?:\s*\[)+/g, "")) ) {

            return (new Function( "return " + data ))();
        }
        throw new SyntaxError("Invalid JSON: " + data);
    /*default:
        return request.responseText;
    }*/
};
IWB_API._HTTP.encodeFormData = function(data) {
    var pairs = [];
    var regexp = /%20/g;
    for(var name in data) {
        var value = data[name].toString();
        
        var pair = encodeURIComponent(name).replace(regexp,"+") + '=' +
            encodeURIComponent(value).replace(regexp,"+");
        pairs.push(pair);
    }
    return pairs.join('&');
};
IWB_API._HTTP.get = function(url, callback, options) {
    
    var request = IWB_API._HTTP.newRequest();
    var n = 0;
    var timer;
    
    if (options.timeout) {
        timer = setTimeout(function () {
                request.abort();
                if (options.timeoutHandler) {
                    options.timeoutHandler(url);
                }
            },options.timeout);
    }
    
    request.onreadystatechange = function() {
        if (request.readyState === 4) {
            if (timer) {
                clearTimeout(timer);
            }
            if (request.status === 200) {
                try {
                    callback(IWB_API._HTTP._getResponse(request));
                } catch(e) { // 有可能json解析失败,或调用者callback失败
                    if (options.errorHandler) {
                        options.errorHandler(request.status ,request.statusText ,e);
                    } else {
                        callback(null);
                    }
                }
            } else {
                if (options.errorHandler) {
                    options.errorHandler(request.status,request.statusText);
                } else {
                    callback(null);
                }
            }
        } else if (options.progressHandler) {
            options.progressHandler(++n);
        }
    };

    var target = url;
    if (options.parameters) {
        target += "?" + IWB_API._HTTP.encodeFormData(options.parameters);
    }
    request.open("GET", target);
    request.setRequestHeader("X-Requested-With","XMLHttpRequest");
    request.send(null);
};

IWB_API._HTTP.post = function(url, values, callback, errorHandler) {
    
    var request = IWB_API._HTTP.newRequest();
    
    request.onreadystatechange = function() {
        if (request.readyState === 4) {
            if (request.status === 200) {
                try {
                    callback(IWB_API._HTTP._getResponse(request));
                } catch (e) { // 有可能json解析失败
                    if (errorHandler) {
                        errorHandler(request.status, request.statusText ,e);
                    } else {
                        callback(null);
                    }
                }
            } else {
                if (errorHandler) {
                    errorHandler(request.status, request.statusText);
                } else {
                    callback(null);
                }
            }
        }
    };
    request.open("POST", url);
    request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    request.setRequestHeader("X-Requested-With","XMLHttpRequest");
    request.send(IWB_API._HTTP.encodeFormData(values));
};

// 为IWB_API添加观察者模式
(function () {
    for (var prop in IWB_API) {
        
        if(IWB_API.hasOwnProperty(prop)){
            
            IWB_API[prop].observers = []; // 观察者
        
            IWB_API[prop].addObserver = function (observer) {
                if( observer.get ){ // 自动转换jQuery Object为DOM Object
                    observer = observer.get(0);
                }
                for (var i=0,l=this.observers.length;i<l;i++) { // 去除重复的
                    if (observer === this.observers[i]){
                        return;
                    }
                }
                this.observers.push(observer);
            };
            
            IWB_API[prop].notify = function (identity,response) {
                for (var j=0,c=this.observers.length;j<c;j++){
                    var ob = this.observers[j];
                    if (ob.onResponse) {
                        ob.onResponse(identity,response);
                    }
                }
            };
        }
    }
})();

//jQ闪烁插件
// maxblink 最多闪烁次数 0 为不闪烁 1秒后恢复
// maxblink > 0 闪烁的次数
// arg1 替换的html内容或callback
// arg2 callback 闪烁完毕后的callback

// 标记一个节点为animating
jQuery.fn.animating = function (flag) {
    var animateflag = "animating";
    var that = $(this);
    var undefined;
    if (flag === undefined) {
        return that.attr("data-animating") === animateflag;
    }

    if (flag) {
        that.attr("data-animating",animateflag);
    } else {
        that.attr("data-animating","");
    }
    return that;
};

jQuery.fn.blink = function(maxblink,arg1,arg2){
    var times = maxblink || 0;
    var that = $(this);
    var replacement;
    var callback;
    var cTimes = 0;
    var doblink = function (obj,_callback) {
        if (cTimes > times - 1) {
            if(_callback){
                _callback();
            }
            return;
        }
        obj.animate({
            opacity: 0
        },500,function () {
            obj.animate({
                opacity: 1
            },500,function () {
                cTimes++;
                doblink(obj,_callback);
            });
        });
    };

    if (that.animating()) { // 正在动作中不允许直接返回
        return;
    }

    if ($.isFunction(arg1)) {
        callback = arg1;
    } else {
        replacement = arg1;
        if ($.isFunction(arg2)) {
            callback = arg2;
        }
    }
    
    if (replacement && (typeof replacement).toLowerCase() === "string") {
        replacement = $(replacement);
    }
    
    that.animating(true);
    //无需闪烁提示
    if (maxblink === 0) { // 无需闪烁特殊处理，消息延时1s后消失
        if (!replacement) {
            setTimeout(function () {
                that.show();
                that.animating(false);
                if (callback) {
                    callback();
                }
            } ,1000);            
        } else {
            that.hide().before(replacement);
            setTimeout(function () {
                that.show();
                replacement.remove();
                that.animating(false);
                if (callback) {
                    callback();
                }
            } ,1000);            
        }
        return;
    };

    //需要闪烁提示
    if (!replacement) {
        doblink(that,function () {
            that.animating(false);
            if (callback) {
                callback();
            }
        });
    } else {
        that.hide().before(replacement);
        doblink(replacement,function () {
            that.show();
            replacement.remove();
            that.animating(false);
            if (callback) {
                callback();
            }
        });
    }
};

//jQ旋转图片插件
jQuery.fn.rotate = function(angle,maxwidth,absolute/* 绝对角度 */){
    var that = this;
    var image = this.get(0); // 原始图片
    var supportCanvas = "getContext" in document.createElement("canvas");
    var widthMax = maxwidth || that.parent().parent().width(); // 默认旋转后的宽度不超过现在图片盒子的宽度
    var boundary;
    var scale = 1; // 图片缩放比
    var rotation; // 旋转弧度值
    
    //盒子旋转后所占用的空间计算
    var getBoundary = function (rw, rh, radians) {
        var x1 = -rw/2,
            x2 = rw/2,
            x3 = rw/2,
            x4 = -rw/2,
            y1 = rh/2,
            y2 = rh/2,
            y3 = -rh/2,
            y4 = -rh/2;
            
        var x11 = x1 * Math.cos(radians) + y1 * Math.sin(radians),
            y11 = -x1 * Math.sin(radians) + y1 * Math.cos(radians),
            x21 = x2 * Math.cos(radians) + y2 * Math.sin(radians),
            y21 = -x2 * Math.sin(radians) + y2 * Math.cos(radians), 
            x31 = x3 * Math.cos(radians) + y3 * Math.sin(radians),
            y31 = -x3 * Math.sin(radians) + y3 * Math.cos(radians),
            x41 = x4 * Math.cos(radians) + y4 * Math.sin(radians),
            y41 = -x4 * Math.sin(radians) + y4 * Math.cos(radians);
        
        var x_min = Math.min(x11,x21,x31,x41),
            x_max = Math.max(x11,x21,x31,x41);
        
        var y_min = Math.min(y11,y21,y31,y41);
            y_max = Math.max(y11,y21,y31,y41);
        
        return [x_max-x_min,y_max-y_min];
    };
    
    //保存图片原始宽高信息
    if(!image.naturalWidth){
        image.naturalWidth = image.width;
    }
    
    if(!image.naturalHeight){
        image.naturalHeight = image.height;
    }
    
    //旋转角度计算
    if( absolute) {
        image.angle = angle;
    } else {
        if (!image.angle) {
            image.angle = 0;
        }
        image.angle = image.angle + angle;
    }
    
    if ( image.angle >= 0) {
        rotation = Math.PI * image.angle / 180;
    } else {
        rotation = Math.PI * ( 360 + image.angle ) / 180;
    }
    
    boundary = getBoundary(image.naturalWidth,image.naturalHeight,rotation); // 当前盒子占用的面积
    
    if( boundary[0] > widthMax ){ // 最大宽度校验
        scale =  widthMax / boundary[0];
    }
    
    boundary[0] *= scale;
    boundary[1] *= scale;
    
    if (supportCanvas) {
        
        if (!image.canvas) {
            image.canvas = document.createElement("canvas");
            that.before(image.canvas);
            that.hide();
        }
        
        image.parentNode.style.width = boundary[0]+"px";
        image.parentNode.style.height = boundary[1]+"px";
        image.canvas.width = boundary[0];
        image.canvas.height = boundary[1];

        var ctx = image.canvas.getContext("2d");
        ctx.clearRect(0,0,boundary[0],boundary[1]);
        ctx.save();
        ctx.translate(boundary[0]/2,boundary[1]/2);
        ctx.rotate(rotation);
        ctx.drawImage(image,-image.naturalWidth*scale/2,-image.naturalHeight*scale/2,image.naturalWidth*scale,image.naturalHeight*scale);
        ctx.restore();
    } else {
        image.parentNode.style.width = boundary[0]+"px";
        image.parentNode.style.height = boundary[1]+"px";
        image.style.filter = "progid:DXImageTransform.Microsoft.Matrix(M11="+Math.cos(rotation)*scale+",M12="+(-Math.sin(rotation))*scale+",M21="+Math.sin(rotation)*scale+",M22="+Math.cos(rotation)*scale+",SizingMethod='auto expand')";
    }
};

$(function(){
    var createFlashVideoPlayer = function (videotitle, videoreferer, videoplayer) {
            var flashVideoPlayerHtml = "<div class=\"iwbFlashVideoPlayer\"><div class=\"videoPlayerToolbar\">"
                                      +"<div class=\"videoCollpase\"><a href=\"javascript:void(0);\">收起</a></div>"
                                      +"<div class=\"videoLink\"><a target=\"_blank\" href=\"" + videoreferer + "\">" + videotitle + "</a></div>"
                                      +"</div>"
                                      +"<embed src=\"" + videoplayer + "\" quality=\"high\" pluginspage=\"http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\" width=\"460\" height=\"372\" allowscriptaccess=\"never\" allowfullscreen=\"true\" allownetworking=\"internal\" flashvars=\"playMovie=true&amp;isAutoPlay=true&amp;auto=1&amp;autoPlay=true\" wmode=\"transparent\">"
                                      +"</div>";
            return $(flashVideoPlayerHtml);
        };
    
 
    $(".iwbFlashVideo").live({
        click: function () {
                // 视频播放互斥
                $(".iwbFlashVideoPlayer").find(".videoCollpase").trigger("click");
                //
                var flashVideoPlayerInvokeBtn = $(this);
                var videoCloseCollapseBtn; // 收起flash视频播放器
                var videoInfo = {
                        videotitle: flashVideoPlayerInvokeBtn.attr("data-title"),
                        videoreferer: flashVideoPlayerInvokeBtn.attr("data-referer"),
                        videoplayer: flashVideoPlayerInvokeBtn.attr("data-player")
                    };
                var flashVideoPlayer = createFlashVideoPlayer(videoInfo.videotitle, videoInfo.videoreferer, videoInfo.videoplayer);
                
                videoCloseCollapseBtn = flashVideoPlayer.find(".videoCollpase");
                
                flashVideoPlayerInvokeBtn.after(flashVideoPlayer);
                flashVideoPlayerInvokeBtn.remove();
                
                videoCloseCollapseBtn.click(function () {
                    flashVideoPlayer.before(flashVideoPlayerInvokeBtn);
                    flashVideoPlayer.remove();
                });
            }
    });
});

$(function(){
        
        var songUrl = ""; // 当前播放的歌曲地址
        
        var createMusicPlayer = function () {
                var musicHtml = "<div class=\"iwbMusicPlayerBg iwbMusicPlayerWrapper\">"
                                +"<a href=\"javascript:void(0);\" class=\"iwbMusicPlayerBg iwbMusicPlayerBtn iwbMusicPlayerPlayBtn\"></a>"
                                +"<div class=\"iwbMusicPlayerInfo\"></div>"
                                +"<a href=\"javascript:void(0);\" class=\"iwbMusicPlayerBg iwbMusicPlayerBtn iwbMusicPlayerCloseBtn\"></a>"
                                +"</div>";
                return $(musicHtml);
            };
        
        $(".iwbMusicInfo").live({
            click: function () {
                $(this).parent().find(".iwbMusicPlayerInvokeBtn").trigger("click");
            }
        });
        
        $(".iwbMusicPlayerInvokeBtn").live({
            
            mouseover: function () {
                $(this).addClass("hover");
            },
            
            mouseout: function () {
                $(this).removeClass("hover");
            },
            
            click: function () {
                var musicPlayerInvokBtn = $(this); // 激活音乐播放器的按钮
                var musicInfo = musicPlayerInvokBtn.parent().find(".iwbMusicInfo");
                var musicData = {
                        songartist: musicPlayerInvokBtn.attr("data-songArtist"),
                        songname: musicPlayerInvokBtn.attr("data-songName"),
                        songurl: musicPlayerInvokBtn.attr("data-songUrl")
                    }; // jQuery1.6下IE不支持 musicPlayerInvokBtn.data();
                    
                var musicPlayer = createMusicPlayer(); // 创建播放器界面
                var musicPlayerMusicInfo; // 音乐信息显示区
                var musicPlayerMusicInfoDomObj;
                var musicPlayerMusicInfoScrollDirection = -1; // 音乐信息展示区初始向左滚动，步长为2像素
                var musicPlayerMusicInfoTextIndent = 0; // 当前文字缩进值
                var musicPlayerMusicInfoDisplayInterval; // 定时更新显示区域信息
                var musicPlayerCloseBtn; // 播放器界面关闭按钮
                
                songUrl = musicData.songurl; // 保存目前的音乐地址
                musicPlayerInvokBtn.after(musicPlayer);
                musicPlayerInvokBtn.remove();
                musicInfo.remove();
                
                musicPlayerMusicInfo = musicPlayer.find(".iwbMusicPlayerInfo");
                musicPlayerMusicInfoDomObj = musicPlayerMusicInfo.get(0);
                musicPlayerCloseBtn = musicPlayer.find(".iwbMusicPlayerCloseBtn");
                
                // 设置音乐信息区域信息
                musicPlayerMusicInfo.text("正在连接...");
                // musicPlayerMusicInfo.text([musicData.songartist,musicData.songname].join("-"));

                // 关闭其它已打开的播放器
                $(".iwbMusicPlayerCloseBtn").trigger("click");
                
                // 开始播放音乐
                musicPlayer.find(".iwbMusicPlayerPlayBtn").trigger("click");
               
                // 检查音乐信息展示区域空间大小
                var startScroll = function () { // direction为初始化方向 * 动画步长
                        if (musicPlayerMusicInfoDomObj.scrollHeight===musicPlayerMusicInfoDomObj.clientHeight && musicPlayerMusicInfoTextIndent >= 0) { // 无需滚动
                            stopScroll();
                            return;
                        }
                        musicPlayerMusicInfoDisplayInterval = setInterval(function () {
                            musicPlayerMusicInfoTextIndent += musicPlayerMusicInfoScrollDirection;
                            musicPlayerMusicInfo.css("textIndent", musicPlayerMusicInfoTextIndent+"px");
                            
                            if (musicPlayerMusicInfoDomObj.scrollHeight===musicPlayerMusicInfoDomObj.clientHeight || musicPlayerMusicInfoTextIndent >= 0) {
                                pauseScroll();
                                setTimeout(function(){
                                    musicPlayerMusicInfoScrollDirection = -musicPlayerMusicInfoScrollDirection;
                                    startScroll();
                                },2 * 1000); // 到达边缘停留1秒后反方向滚动
                            }
                        }, 1000 / 25 );// 每秒25帧动画
                    };
                    
                // 音乐信息区暂停滚动
                var pauseScroll = function () {
                        clearInterval(musicPlayerMusicInfoDisplayInterval);
                        musicPlayerMusicInfoDisplayInterval = null;
                    };
                
                // 音乐信息区停止滚动
                var stopScroll = function () {
                        pauseScroll();
                        musicPlayerMusicInfoTextIndent = 0;
                        musicPlayerMusicInfo.css("textIndent","0px");
                    };
                
                // 初始化音乐信息区滚动
                var initScroll = function () {
                    stopScroll();
                    if (musicPlayerMusicInfoDomObj.scrollHeight > musicPlayerMusicInfoDomObj.clientHeight) {
                        startScroll();
                    }
                };

                IWB_MUSIC_PLAYER.onPlayStateChange(function (state,message) {
                    if (state==="playing") {
                        musicPlayerMusicInfo.text([musicData.songartist,musicData.songname].join("-"));
                        initScroll();
                    } else if (state==="stopped") {
                        musicPlayerMusicInfo.text("已停止");
                        stopScroll();
                    } else if (state==="buffering") {
                        musicPlayerMusicInfo.text("正在缓冲...");
                        stopScroll();
                    } else if (state==="paused") {
                        musicPlayerMusicInfo.text("已暂停");
                        stopScroll();
                    } else if (state==="error") {
                        musicPlayerMusicInfo.html("<span style=\"color:#F47700;\">" + message + "</span>");
                        stopScroll();
                    }
                }); 

                // 鼠标悬浮到音乐信息区暂停滚动
                musicPlayerMusicInfo.mouseover(function () {
                    if (musicPlayerMusicInfoDisplayInterval) { // 正在滚动，则暂停滚动
                        pauseScroll();
                    }
                });
                
                // 鼠标离开音乐信息区继续滚动
                musicPlayerMusicInfo.mouseout(function () {
                    if (!musicPlayerMusicInfoDisplayInterval) { // 未滚动，则开始滚动
                        startScroll();
                    }
                });
                // 关闭播放器界面
                musicPlayerCloseBtn.click(function () {
                    stopScroll();
                    musicPlayerInvokBtn.removeClass("hover");
                    musicPlayer.before(musicPlayerInvokBtn);
                    musicPlayer.before(musicInfo);
                    musicPlayer.remove();
                    IWB_MUSIC_PLAYER.stop();
                });
            }
        });
        
        $(".iwbMusicPlayerPlayBtn").live({
            click: function () {
                IWB_MUSIC_PLAYER.src(songUrl);
                IWB_MUSIC_PLAYER.load();
                IWB_MUSIC_PLAYER.play();
                $(this).removeClass("iwbMusicPlayerPlayBtn").addClass("iwbMusicPlayerPauseBtn");
                $(this).parent().find(".iwbMusicPlayerInfo").text("正在连接...");
            }
        });
        
        $(".iwbMusicPlayerPauseBtn").live({
            click: function () {
                IWB_MUSIC_PLAYER.stop();
                $(this).removeClass("iwbMusicPlayerPauseBtn").addClass("iwbMusicPlayerPlayBtn");
            }
        });
});

$(function () {
    
    var createIwbQQFace = function (){
            var emotions="f14|微笑,f1|撇嘴,f2|色,f3|发呆,f4|得意,f5|流泪,f6|害羞,f7|闭嘴,f8|睡,f9|大哭,f10|尴尬,f11|发怒,f12|调皮,f13|呲牙,f0|惊讶,f15|难过,f16|酷,f96|冷汗,f18|抓狂,f19|吐,f20|偷笑,f21|可爱,f22|白眼,f23|傲慢,f24|饥饿,f25|困,f26|惊恐,f27|流汗,f28|憨笑,f29|大兵,f30|奋斗,f31|咒骂,f32|疑问,f33|嘘,f34|晕,f35|折磨,f36|衰,f37|骷髅,f38|敲打,f39|再见,f97|擦汗,f98|抠鼻,f99|鼓掌,f100|糗大了,f101|坏笑,f102|左哼哼,f103|右哼哼,f104|哈欠,f105|鄙视,f106|委屈,f107|快哭了,f108|阴险,f109|亲亲,f110|吓,f111|可怜,f112|菜刀,f89|西瓜,f113|啤酒,f114|篮球,f115|乒乓,f60|咖啡,f61|饭,f46|猪头,f63|玫瑰,f64|凋谢,f116|示爱,f66|爱心,f67|心碎,f53|蛋糕,f54|闪电,f55|炸弹,f56|刀,f57|足球,f117|瓢虫,f59|便便,f75|月亮,f74|太阳,f69|礼物,f49|拥抱,f76|强,f77|弱,f78|握手,f79|胜利,f118|抱拳,f119|勾引,f120|拳头,f121|差劲,f122|爱你,f123|NO,f124|OK,f42|爱情,f85|飞吻,f43|跳跳,f41|发抖,f86|怄火,f125|转圈,f126|磕头,f127|回头,f128|跳绳,f129|挥手,f130|激动,f131|街舞,f132|献吻,f133|左太极,f134|右太极";
            var emotionsArr = emotions.split(",");
            var emotionsHtml = "<div class=\"iwbAutoCloseLayer iwbQQFace\">"
                              +"<a href=\"javascript:void(0);\" class=\"close\" title=\"关闭\"></a>"
                              +"<div class=\"qqFaceBox\">";
            var i;
            for (i=0,l=emotionsArr.length;i<l;i++){
                var temp = emotionsArr[i].split("|");
                emotionsHtml += ("<a href=\"javascript:void(0);\" data-code=\"" + temp[0] +"\" title=\"" + temp[1] +"\"></a>");
            }
            emotionsHtml += ("<div class=\"qqFacePreview\"><div class=\"qqFacePreviewImg\"><img src=\"\" alt=\"表情\"/></div><div class=\"qqFacePreviewText\">测试</div></div></div></div>");
            
            return $(emotionsHtml);
        };
        
    var targetInput; // 目标输入框
    
    $(".iwbEmotesBtn").live({
        click: function () {
            $(".iwbAutoCloseLayer").hide();
            var emotesBtn = $(this);
            var target = emotesBtn.attr("data-for");
            targetInput = $(target); // 目标input对象
            var iwbQQFace = $(".iwbQQFace");
            var hasQQFace = iwbQQFace.length > 0;
            var iwbQQFaceOffsetTop = emotesBtn.offset().top + emotesBtn.height();
            var iwbQQFaceOffsetLeft = emotesBtn.offset().left;
            var iwbQQFacePreview;
            
            if (!hasQQFace) { // 创建QQ表情浮层，该浮层有iwbAutoClosedLayer属性
                iwbQQFace = createIwbQQFace();
                iwbQQFacePreview = iwbQQFace.find(".qqFacePreview");
                $("body").append(iwbQQFace);
                 
                // 第一次创建时注册事件
                iwbQQFace.click(function (e) {
                    e.stopPropagation();
                });
                // 关闭按钮事件
                iwbQQFace.find(".close").click(function () {
                    iwbQQFace.fadeOut(200);
                });
                
                // 表情预览
                iwbQQFace.find(".qqFaceBox > a").mouseover(function () {
                    var faceBtn = $(this);
                    var resourceDir = window.iwbResourceRoot || "/";
                    var previewUrl = resourceDir + 'resource/images/emotions/' + faceBtn.attr("data-code").match(/\d+$/)[0] + ".gif";
                    var previewTitle = faceBtn.attr("title");
                    var faceBtnIndex = faceBtn.index() + 1;
                    faceBtnIndex = faceBtnIndex % 15; // 每行15个表情，我们使用余数来确定左右区域位置
                    if (faceBtnIndex===0) {
                        faceBtnIndex = 15;
                    }
                    iwbQQFacePreview.find("img").attr("src",previewUrl);
                    iwbQQFacePreview.find(".qqFacePreviewText").text(previewTitle);
                    iwbQQFacePreview.css({"left":"","right":""});
                    iwbQQFacePreview.css(faceBtnIndex > 8?"left":"right","0px");
                    iwbQQFacePreview.show();
                }).click(function () {
                    var caret;
                    var undefined;
                    if (targetInput.length > 0){
                        caret = (targetInput.get(0).caret !== undefined ? targetInput.get(0).caret : targetInput.val().length) ;
                        IWB_UTIL.insertText("/" + $(this).attr("title") ,caret ,targetInput);
                    }
                    iwbQQFace.hide();
                });
                
                // 关闭预览
                iwbQQFace.find(".qqFaceBox").mouseout(function () {
                    iwbQQFacePreview.hide();
                });
            }
            iwbQQFace.css({
                top: iwbQQFaceOffsetTop + 5 + "px",
                left: iwbQQFaceOffsetLeft - 60 + "px"
            });
            iwbQQFace.hide().fadeIn(200);
        }
    });
});

$(function () {
    var createIwbAddVideo = function () {
            var addVideoHtml = "<div class=\"iwbAutoCloseLayer iwbAddVideo\">"
                              +"<a href=\"javascript:void(0);\" class=\"close\" title=\"关闭\"></a>"
                              +"<label for=\"videoAddr\">粘贴视频播放页地址<br>"
                              +"<span class=\"tip\">(优酷、土豆、凤凰视频、56等网站视频可直接播放)</span>"
                              +"</label>"
                              +"<div class=\"videoFields\">"
                              +"<input type=\"text\" name=\"videoUrl\" class=\"videoAddr\"/>"
                              +"<button type=\"button\" class=\"videoSubmit\">确定</button>"
                              +"</div>"
                              +"<div class=\"videoLoading\">稍等一下，正在获取视频信息...</div>"
                              +"<div class=\"videoInfo\">暂不支持该视频地址，<a href=\"javascript:void(0);\">作为普通链接显示</a></div>"
                              +"</div>";
            return $(addVideoHtml);
    };
    
    var refererVideoBtn; // 引用到的发视频按钮
    
    $(".iwbAddVideoBtn").live({
        click: function () {
            $(".iwbAutoCloseLayer").hide();
            var addVideoBtn = $(this);
            refererVideoBtn = addVideoBtn;
            var addVideoBtnOffsetTop = addVideoBtn.offset().top + addVideoBtn.height();
            var addVideoBtnOffsetLeft = addVideoBtn.offset().left;
            var addVideoBox = $(".iwbAddVideo");
            var videoUrlField = addVideoBox.find("input[name=videoUrl]");
            var addVideoSubmit = addVideoBox.find("button[class=videoSubmit]");
            var videoLoading = addVideoBox.find(".videoLoading");
            var videoInfo = addVideoBox.find(".videoInfo");
            
            if (addVideoBox.length <= 0){ // 创建发表视频浮层及表单
                addVideoBox = createIwbAddVideo();
                videoUrlField = addVideoBox.find("input[name=videoUrl]");
                addVideoSubmit = addVideoBox.find("button[class=videoSubmit]");
                videoLoading = addVideoBox.find(".videoLoading");
                videoInfo = addVideoBox.find(".videoInfo");
                
                $("body").append(addVideoBox);
                
                // 第一次创建时注册事件
                addVideoBox.click(function (e) {
                    e.stopPropagation();
                });
                // 关闭事件
                addVideoBox.find(".close").click(function () {
                    addVideoBox.fadeOut(200);
                });
                
                // 提交事件注册
                addVideoSubmit.click(function () {
                    var videoUrl = $.trim(videoUrlField.val());
                    var identity = refererVideoBtn.attr("data-identity"); // 优先使用data-identity作为标识符
                    if(!identity){ // 专用标识符无法使用则使用id
                        identity = refererVideoBtn.attr("id");
                    }
                    if(IWB_VALIDATOR.url(videoUrl)){
                        videoInfo.html("无效的视频地址").hide().fadeIn(200);
                        return;
                    }
                    videoInfo.hide(); // 隐藏出错信息(如果存在)
                    videoLoading.show();
                    addVideoSubmit.prop('disabled',true);
                    IWB_API.videoInfo.addObserver(refererVideoBtn); // 每次点击都会触发，addObserver内部会检查重复
                    IWB_API.videoInfo(refererVideoBtn.attr("data-identity"),videoUrl);
                });
                
            }
            // 还原提示信息及状态
            videoLoading.html("稍等一下，正在获取视频信息...").hide();
            
            // 还原提示信息及状态
            videoInfo.html("暂不支持视频地址，<a href=\"javascript:void(0);\">作为普通链接显示</a>").hide();
            
            // 还原提交按钮
            addVideoSubmit.prop('disabled',false);
            
            // 清空之前的地址
            videoUrlField.val("");
            
            // 刷新位置
            addVideoBox.css({
                top: addVideoBtnOffsetTop + 5 + "px",
                left: addVideoBtnOffsetLeft - 60 + "px"
            });
            
            
            // 显示浮层后设置焦点
            addVideoBox.hide().fadeIn(200,function () {
                videoUrlField.focus();
            });
        }
    });
    
});

$(function () {
    var createIwbAddMusic = function () {
            var addMusicHtml = "<div class=\"iwbAutoCloseLayer iwbAddMusic\">"
                              +"<a href=\"javascript:void(0);\" class=\"close\" title=\"关闭\"></a>"
                              +"<label for=\"musicAddr\">粘贴音乐地址<br>"
                              +"<span class=\"tip\">(支持mp3,ogg,wma等音乐格式)</span>"
                              +"</label>"
                              +"<div class=\"musicFields\">"
                              +"<input type=\"text\" name=\"musicUrl\" class=\"musicAddr\"/>"
                              +"<button type=\"button\" class=\"musicSubmit\">确定</button>"
                              +"</div>"
                              +"<div class=\"musicLoading\">稍等一下，正在获取音乐信息...</div>"
                              +"<div class=\"musicInfo\">暂不支持该音乐地址，<a href=\"javascript:void(0);\">作为普通链接显示</a></div>"
                              +"</div>";
            return $(addMusicHtml);
    };
    
    var refererMusicBtn; // 引用到的发音乐按钮
    
    $(".iwbAddMusicBtn").live({
        click: function () {
            $(".iwbAutoCloseLayer").hide();
            var addMusicBtn = $(this);
            refererMusicBtn = addMusicBtn;
            var addMusicBtnOffsetTop = addMusicBtn.offset().top + addMusicBtn.height();
            var addMusicBtnOffsetLeft = addMusicBtn.offset().left;
            var addMusicBox = $(".iwbAddMusic");
            var musicUrlField = addMusicBox.find("input[name=musicUrl]");
            var addMusicSubmit = addMusicBox.find("button[class=musicSubmit]");
            var musicLoading = addMusicBox.find(".musicLoading");
            var musicInfo = addMusicBox.find(".musicInfo");
            
            if (addMusicBox.length <= 0){ // 创建发表音乐浮层及表单
                addMusicBox = createIwbAddMusic();
                musicUrlField = addMusicBox.find("input[name=musicUrl]");
                addMusicSubmit = addMusicBox.find("button[class=musicSubmit]");
                musicLoading = addMusicBox.find(".musicLoading");
                musicInfo = addMusicBox.find(".musicInfo");
                
                $("body").append(addMusicBox);
                
                // 第一次创建时注册事件
                addMusicBox.click(function (e) {
                    e.stopPropagation();
                });
                // 关闭事件
                addMusicBox.find(".close").click(function () {
                    addMusicBox.fadeOut(200);
                });
                
                // 提交事件注册
                addMusicSubmit.click(function () {
                    var musicUrl = $.trim(musicUrlField.val());
                    var identity = refererMusicBtn.attr("data-identity"); // 优先使用data-identity作为标识符
                    if(!identity){ // 专用标识符无法使用则使用id
                        identity = refererMusicBtn.attr("id");
                    }
                    if(IWB_VALIDATOR.url(musicUrl)){
                        musicInfo.html("无效的音乐地址").hide().fadeIn(200);
                        return;
                    }
                    musicInfo.hide(); // 隐藏出错信息(如果存在)
                    musicLoading.show();
                    addMusicSubmit.prop('disabled',true);
                    IWB_API.musicInfo.addObserver(refererMusicBtn); // 每次点击都会触发，addObserver内部会检查重复
                    IWB_API.musicInfo(refererMusicBtn.attr("data-identity"),musicUrl);
                });
                
            }
            // 还原提示信息及状态
            musicLoading.html("稍等一下，正在获取音乐信息...").hide();
            
            // 还原提示信息及状态
            musicInfo.html("暂不支持音乐地址，<a href=\"javascript:void(0);\">作为普通链接显示</a>").hide();
            
            // 还原提交按钮
            addMusicSubmit.prop('disabled',false);
            
            // 清空之前的地址
            musicUrlField.val("");
            
            // 刷新位置
            addMusicBox.css({
                top: addMusicBtnOffsetTop + 5 + "px",
                left: addMusicBtnOffsetLeft - 60 + "px"
            });
            
            
            // 显示浮层后设置焦点
            addMusicBox.hide().fadeIn(200,function () {
                musicUrlField.focus();
            });
        }
    });
    
});

$(function () {
    
    var createImagePreiew = function () {
        var imagePreviewHtml = "<div class=\"iwbImagePreview\">"
                               +"<div class=\"previewMask\"></div>"
                               +"<img src=\"\"></img>"
                               +"</div>";
        return $(imagePreviewHtml);
    };
    
    $(".iwbImagePreviewControl").live({
        
        mouseover: function () {
            var invoker = $(this);
            var invokerOffsetTop = invoker.offset().top + invoker.height();
            var invokerOffsetLeft = invoker.offset().left;
            var imagePreviewControl = $(".iwbImagePreview");
            var imageUrl = invoker.attr("data-imageUrl"); // 图片地址
            var loadingImageUrl = "./resource/images/loading16.gif";
            var previewImage;
            
            if (imagePreviewControl.length<=0) { // 第一次激活，初始化图片预览
                imagePreviewControl = createImagePreiew();
                $("body").append(imagePreviewControl);
            }
            imagePreviewControl.css({
                top: invokerOffsetTop + 5 + "px",
                left: invokerOffsetLeft + "px"
            });
            previewImage = new Image();
            previewImage.src = imageUrl; // 加载图片
            
            if (previewImage.complete) { // 图片有缓存，直接显示图片 IE使用此判断有效
                imagePreviewControl.find("img").attr("src",previewImage.src);
                imagePreviewControl.show();
            } else { // 无缓存
                imagePreviewControl.find(".previewMask").hide();
                imagePreviewControl.find("img").attr("src",loadingImageUrl);
                previewImage.onload = function () {
                    imagePreviewControl.find("img").attr("src",previewImage.src);
                    imagePreviewControl.find(".previewMask").show();
                    imagePreviewControl.show();
                };
            }
        },
        
        mouseout: function () {
            $(".iwbImagePreview").hide();
        }
        
    });
});
$(function () {
    // 获取光标索引位置
    // http://jsbin.com/iwopa
    if (!$.fn.caret) {
        $.fn.caret = function (callback) {
            var el = $(this).get(0);
            var ret = 0;
            if (el.nodeName.toLowerCase() === "textarea") {
                if (el.selectionStart) {
                    ret = el.selectionStart;
                } else if (document.selection) {
                    var r = document.selection.createRange();
                    if (r !== null) {
                        var re = el.createTextRange();
                        var rc = re.duplicate();
                        re.moveToBookmark(r.getBookmark());
                        rc.setEndPoint('EndToStart', re);
                        ret = rc.text.length;
                    }
                }
            }

            if (callback) {
                callback(el);
            }

            return ret;
        };
    }
    
    // 获取坐标，相对于textarea左上角
    $.fn.caretOffset = function () {
        var el = $(this).get(0);
        var that = $(this);
        var boxHeight; // 容器高度
        var boxWidth; // 容器宽度
        var boxFontFamily;
        var boxFontSize;
        var boxCharsBeforeCaret; // 光标之前的文字
        var dummyBox = $(".iwbTextareaDummyBox"); // 模拟textarea的容器，用于获取一个指定文字的像素坐标
        var lastChar; // 最后一个文字的位移
        var lastCharOffset;
        if (el.nodeName.toLowerCase() === "textarea") { 
            boxHeight = that.height();
            boxWidth = that.width();
            boxFontFamily = that.css("fontFamily");
            boxFontSize = that.css("fontSize");
            boxCharsBeforeCaret = that.val().substring(0,that.caret()).split("");
            if (dummyBox.length<=0) {
                dummyBox = $("<div class=\"iwbTextareaDummyBox\"></div>");
                $("body").append(dummyBox);
            }
            // 设置dummybox的宽高，及字体样式
            dummyBox.css({
                position: "absolute",
                top: 0,
                left: 0,
              //  top: 200,
              //  left: 200,
              //  zIndex: 999,
                width: boxWidth, // textare的宽度
                height: boxHeight, //textarea的高度
                visibility: "hidden", // dummybox用户不可见
                zIndex: -1, // 不会阻挡用户操作
                fontFamily: boxFontFamily, // 字体样式
                fontSize: boxFontSize // 字号大小
            });
            // 清空dummybox内容
            dummyBox.html("");
            // 设置dummybox内容
            $.each(boxCharsBeforeCaret,function (i,c) {
                var singleChar;
                if (c==="\n") {
                    singleChar = "<br>";
                } else if (c===" ") {
                    singleChar = "<div style=\"float:left;\">&nbsp;</div>";
                } else {
                    singleChar = "<div style=\"float:left;\">"+c+"</div>";
                }
                dummyBox.append(singleChar);
            });
            lastChar = dummyBox.find("div").last();
            lastCharOffset = lastChar.offset();
            return { // 以7点钟位置为准返回文字内部相对坐标
                    top: lastCharOffset.top + lastChar.height(),
                    left: lastCharOffset.left
                   };
        }
        
        return {
                top: 0,
                left: 0
            };
    };
    
    var currentTextarea; // 当前输入框
    
    var createFriendSelector = function () { // 初始化选择器
        var friendSelectorHtml = "<div class=\"iwbAutoCloseLayer iwbFriendSelector\">"
                                +"<div class=\"friendSearch\">"
                                +"<div class=\"inputWrapper\">"
                                +"<input type=\"text\" class=\"\"/>"
                                +"<a href=\"javascript:void(0);\" class=\"searchBtn search\"></a>"
                                +"</div>"
                                +"<a href=\"javascript:void(0);\" class=\"close\"></a>"
                                +"</div>"
                                +"<div class=\"friendDisplay\">"
                                +"<div class=\"loading\">正在读取好友列表,请稍候...</div>"
                                +"<ul class=\"displayAll\">"
                                +"</ul>"
                                +"<ul class=\"displayResult\">"
                                +"</ul>"
                                +"</div>"
                                +"<div class=\"tips\">@朋友帐号,他就能在[提到我的]页收到</div>"
                                +"</div>";
       
       var selector = $(friendSelectorHtml); // 选择器
       var friendSearchJq = selector.find(".friendSearch"); //搜索条
       var refererSelector = selector.get(0); // 选择器全局dom
       var searchInput = selector.find(".inputWrapper").find("input");
       var searchBtn = selector.find(".inputWrapper").find(".searchBtn");
       var closeBtn = selector.find(".close"); // 隐藏选择器
       var loading = selector.find(".loading"); // 拉取朋友数据
       var searchResult = selector.find(".displayResult"); // 朋友搜索结果展示
       var allResult = selector.find(".displayAll"); // 显示全部朋友
       var allFriend = []; // 原始数据，记录好友列表
       
       // 搜索朋友数据并格式化为html
       var searchFriends = function (kw) { // 格式化
           var result = [];
           var resultUsername = [];
           var resultHtml = "";
           var maxchar = 24;
           var searchStartPos;
           var limitCharFriend; // 截取后的朋友字符串
           
           $.each(allFriend,function (index,friend) {
               if (!kw) { // 没有传入搜索key返回全部
                   resultUsername.push(friend.match(/\((.*)\)/)[1]);
                   friend = IWB_UTIL.limit(friend,maxchar);
                   result.push(friend);
               } else {
                  searchStartPos = friend.search(kw);
                  if(searchStartPos !== -1) { // 在搜索结果
                      resultUsername.push(friend.match(/\((.*)\)/)[1]);
                      limitCharFriend = IWB_UTIL.limit(friend,maxchar);
                      if (limitCharFriend !== friend) { // 处理截取后的字符高亮问题
                          if (searchStartPos+kw.length < limitCharFriend.length - 3 /*suff length*/) {
                              limitCharFriend = limitCharFriend.replace(kw,"<b>"+kw+"</b>");
                          } else { //
                              limitCharFriend = [limitCharFriend.substring(0,searchStartPos),"<b>"+limitCharFriend.substring(searchStartPos,limitCharFriend.length -3)+"</b>","..."].join("");
                          }
                      } else { // 高亮字符串
                          limitCharFriend = limitCharFriend.replace(kw,"<b>"+kw+"</b>");
                      }
                      result.push(limitCharFriend);
                  }
               }
           });
           $.each(result, function (index,data) {
               resultHtml += ("<li data-username=\"" + resultUsername[index] + "\">" + data + "</li>");
           });
           return resultHtml;
       };
       
       // 关闭按钮
       closeBtn.click(function () {
           selector.hide(); // 隐藏目前的selector
           //刷新数据及状态
       });
       
       
       // 初始化好友数据,调用的先后顺序在某些情况下很重要
       IWB_API.idollist.addObserver(refererSelector);
       refererSelector.onResponse = function (identity,response) {
           var boxHeight;
           if (identity === "friendlist") {
               if(response && response.ret === 0) {
                   loading.hide();
                   $.each(response.data, function (index,namenick) {
                        allFriend.push([namenick.nick," (",namenick.name,")"].join(""));
                   });
                   allResult.html(searchFriends());// 搜索朋友为空意为显示全部
                   // 朋友全部列表数量不超过10个，滚动条永远不需要出现，缩小默认的高度，默认是200px
                   boxHeight = allFriend.length < 200 / 20 ? allFriend.length * 20 : 10 * 20;
                   allResult.css({
                       height: boxHeight
                   });
                   searchResult.css({
                       height: boxHeight
                   });
                   // 注册全部好友列表事件
                   allResult.find("li").mouseover(function () {
                       $(this).addClass("highlight");
                   }).mouseout(function () {
                       $(this).removeClass("highlight");
                   }).click(function () {
                       var that = $(this);
                       var name = that.attr("data-username"); // @朋友值
                       var val = currentTextarea.val(); // 输入框现在的值
                       var lastCaret;

                       if (!name) {
                           return;
                       }

                       lastCaret = currentTextarea.get(0).caret; // 失去焦点时光标的位置
                       lastCaret = lastCaret || 0;
                       IWB_UTIL.insertText(["@" ,name ," "].join("") ,lastCaret ,currentTextarea);
                       currentTextarea.get(0).caret += ["@" ,name ," "].join("").length; //更新光标位置
                   });
                   allResult.show();
                   searchInput.trigger("keyup"); // 如果搜索框有数据，直接激活搜索结果
               } else {
                   alert("好友列表获取失败");
               }
           }
       };
       IWB_API.idollist("friendlist");
       // 搜索与清除按钮
       searchBtn.click(function () {
           if ($(this).hasClass("clear")) {
               searchInput.val("");
               searchResult.hide();
               allResult.show();
           }
       });
       
       // 搜索好友列表
       searchInput.bind("paste",function () {
           searchInput.trigger("keyup");
       });
       
       // 搜索输入框
       searchInput.keyup(function () {
           if (allFriend.length <= 0) { // 还没数据无法执行搜索
               return;
           }
           var searchKw = $(this).val();
           var doSearchResult;
           var doSearchLen;
           
           if (searchKw) {
               searchBtn.removeClass("search").addClass("clear");
               allResult.hide();
               doSearchResult = searchFriends(searchKw);
               if (!doSearchResult) {
                    doSearchResult = "<li>没有匹配的结果</li>";
               }
               doSearchLen = doSearchResult.match(/<li/gi).length;
               searchResult.html(doSearchResult);

               //朋友全部列表数量不超过10个，滚动条永远不需要出现，缩小默认的高度，默认是200px
               if (doSearchLen < 200 / 20 ) {  
                   searchResult.css({
                       height: doSearchLen * 20
                   });
               }
               
               // 注册搜索结果事件
               searchResult.find("li").mouseover(function () {
                   $(this).addClass("highlight");
               }).mouseout(function () {
                   $(this).removeClass("highlight");
               }).click(function () {
                   var that = $(this);
                   var name = that.attr("data-username"); // @朋友值
                   var val = currentTextarea.val(); // 输入框现在的值
                   var atReg = /(@[^@]*)$/;
                   var friendSearchVisible;
                   var lastCaret;
                   var textPre;
                   var textAfter;
                   var atMatch;

                   if (!name) {
                       return;
                   }

                   lastCaret = currentTextarea.get(0).caret; // 失去焦点时光标的位置
                   lastCaret = lastCaret || 0;
                   textPre = val.substring(0,lastCaret);
                   textAfter = val.substring(lastCaret); 
                   atMatch = textPre.match(atReg);
                   if (atMatch) { // 不太可能只有输入@才会激活
                       textPre = textPre.replace(atReg,'');
                       lastCaret -= atMatch[1].length;
                       currentTextarea.get(0).caret = lastCaret;
                   }
                   currentTextarea.val([textPre,textAfter].join(""));
                   IWB_UTIL.insertText(["@" ,name ," "].join("") ,lastCaret ,currentTextarea);
                   currentTextarea.get(0).caret += ["@" ,name ," "].join("").length; //更新光标位置

                   // 搜索区域不可见，说明需要朋友选择器需要自动隐藏
                   friendSearchVisible = parseInt(friendSearchJq.css("marginTop"),10) >= 0;
                   if (!friendSearchVisible) {
                       selector.fadeOut(500);
                   }
               });
               
               searchResult.show();
               
           } else {
               searchBtn.removeClass("clear").addClass("search");
               searchResult.hide();
               allResult.show();
           }
       });
       return selector;
    };
    
    // 激活朋友选择框
    $(".iwbFriendControlBtn").live({
        click: function () {
            $(".iwbAutoCloseLayer").hide();
            var friendBtn = $(this);
            var friendSelector = $(".iwbFriendSelector");
            var top = friendBtn.offset().top + friendBtn.height() + 5;
            var left = friendBtn.offset().left;
            var input; // 输入框
            
            currentTextarea = $(friendBtn.attr("data-for")); // 设置当前输入框
            
            if (friendSelector.length<=0) { // 创建朋友选择框
                friendSelector = createFriendSelector();
                friendSelector.click(function (e) {
                    e.stopPropagation();
                });
                $("body").append(friendSelector);
            }
            input = friendSelector.find(".inputWrapper").find("input"); // 输入框
            friendSelector.find(".friendSearch").css({ // 显示搜索栏，有可能被从textarea激活的选择器隐藏
                marginTop: 0
            });
            friendSelector.find(".close").show(); // 显示关闭按钮，有可能被从textarea激活的选择器隐藏
            friendSelector.css({
                top: top,
                left: left
            });
            friendSelector.find(".displayResult").hide(); // 隐藏搜索结果
            friendSelector.find(".displayAll").hide(); // 隐藏全部朋友列表
            input.val("").trigger("keyup"); // 输入框重置
            friendSelector.hide().fadeIn(500,function () {
                input.focus();// 设置输入框为焦点
            });
        }
    });
    
    $(".iwbFriendControlInput").live({
        keyup : function () {
            var that = $(this);
            var val = that.val();
            var caret = that.caret();
            var caretOffset; // 光标所处位置的选择器
            var thatOffset; // 文本框的位置
            var selectorTop; // 朋友选择器Y位移
            var selectorLeft; // 朋友选择器X位移
            var friendSelector = $(".iwbFriendSelector");
            var textBeforeCaret = val.substring(0,caret);
            var kw = textBeforeCaret.match(/@(\w*)$/);
            
            if (kw) {
                kw = kw[1];
                currentTextarea = that;
                if (friendSelector.length<=0) { // @提示不存在，创建提示
                    friendSelector = createFriendSelector();
                    $("body").append(friendSelector);
                }
                
                // 如果选择器已存在并且是按钮触发的选择器，自动关闭之
                if (friendSelector.is(":visible") && friendSelector.find(".close").is(":visible")) {
                    friendSelector.find(".close").trigger("click");
                }

                if (!kw) { //无输入搜索
                    friendSelector.find(".friendSearch").hide();
                    friendSelector.find(".friendDisplay").hide();
                } else { // 有输入搜索
                    friendSelector.find(".friendSearch").show();
                    friendSelector.find(".friendDisplay").show();
                    friendSelector.find(".displayResult").hide();
                    friendSelector.find(".friendSearch").css({
                        marginTop: -36+"px"
                    });
                    friendSelector.find(".close").hide();
                    friendSelector.find(".inputWrapper").find("input").val(kw); // 激活搜索
                    friendSelector.find(".inputWrapper").find("input").trigger("keyup");
                }
                
                if (!friendSelector.is(":visible")){ // 重新定位选择器
                    caretOffset = $(this).caretOffset();
                    thatOffset = that.offset();
                    selectorLeft = thatOffset.left + caretOffset.left;
                    selectorTop = thatOffset.top + Math.min(that.height(),caretOffset.top)/*最高不超过文本框底部*/;
                    friendSelector.css({
                        top: selectorTop + 5,
                        left: selectorLeft
                    });
                    friendSelector.hide().fadeIn(500);
                }
            } else { //
                if (friendSelector.length>=1) {
                    friendSelector.fadeOut(500);
                }
            }
        }
    });
});

//对话框控件
var IWB_DIALOG;

IWB_DIALOG = {

    _id: 0,

    _zindex: 999,

    _msgboxTextHeight: function (text,width) { // 模拟计算文字高度
            var dummy = $("<div class=\"iwbDialog\"><div class=\"msgbox\"><div class=\"msgtext\">" + text + "</div></div></div>");
            var dummycss;
            var dummyHeight;
            dummy.css({
                visibility:"hidden"
            });
            dummy.append(text); // 修正
            $("body").append(dummy);
            dummyHeight = dummy.find(".msgtext").height();
            dummy.remove();
            return dummyHeight;
    },
    
    // 移除所有的对话框
    
    _disposeAllDialog: function () { 
        
        var dialog = $(".iwbDialog"); // 对话框主层
        var modalBgLayer = $(".iwbDialogModalBg"); // 取背景层
        dialog.remove();
        modalBgLayer.animate({
            opacity:0
        },200,function () {
            modalBgLayer.remove();
        });
    },

    // 移除指定ID的对话框
    _disposeDialog: function (id) {
        $("#dialog"+id).remove();
        $("#dialogModalBg"+id).remove();
    },

    _createDialog: function (options) { // 对话框初始化
        var dialog;
        var dialogClose;
        var innerDOM;
        var dialogHtml = "<div class=\"iwbDialog\" data-id=\"" + this._id + "\" id=\"dialog" + this._id + "\">"
                        +"<div class=\"iwbDialogBg\"><iframe></iframe></div>"
                        +"<div class=\"iwbDialogMain\"></div>";
        if(options && options.showClose){ // 是否显示关闭按钮
            dialogHtml += "<a class=\"close\" href=\"javascript:void(0);\"></a>";
        }
        dialogHtml += "</div>";
        dialog = $(dialogHtml);
        
        dialog.css({
            zIndex: IWB_DIALOG._zindex
        });

        dialogClose = dialog.find(".close");

        if (dialogClose.length>0) { // 关闭按钮
            dialogClose.click(function () {
                IWB_DIALOG._disposeDialog(dialog.attr("data-id"));
            });
        }
        
        if(options && options.autoClose){
            setTimeout(function () {
                IWB_DIALOG._disposeDialog(dialog.attr("data-id"));
                if (options.autoClose.callback) {
                    options.autoClose.callback();
                }
            },(options.autoClose.wait || 2000));
        }
        
        if(options && options.getDOM){ // 添加DOM(jQuery Object)
            innerDOM = options.getDOM();
            dialog.find(".iwbDialogMain").append(innerDOM);
        }
        
        dialog.css({
            width: options.width + "px",
            height: options.height + "px",
            top: options.top + "px",
            left: options.left + "px"
        });
        return dialog; 
    },

    _merge: function (obj1,obj2) {
        var k;
        for (k in obj1) {
            if (obj1.hasOwnProperty(k)) {
                if (obj2.hasOwnProperty(k)) {
                    obj1[k] = obj2[k];
                }
            }
        }
        return obj1;
    },

    _init: function (options) {
        
        var dialog = $(".iwbDialog"); // 对话框主层
        var modalBgLayer = $(".iwbDialogModalBg"); // 取背景层

        this._id ++;
        this._zindex ++;
        
        if (options && options.modal){ // 无背景层,调用者要求使用背景层
            modalBgLayer = $("<div class=\"iwbDialogModalBg\" id=\"dialogModalBg" + this._id + "\"><iframe></iframe></div>");
            modalBgLayer.css({
                opacity: 0.35
               ,zIndex: IWB_DIALOG._zindex
            });
            
            $("body").append(modalBgLayer);
            
        } 

        /*
        if (options && !options.modal && modalBgLayer.length > 0){
            modalBgLayer.remove();
        }*/
        
        // 移除已存在的对话框
        
        /*if (dialog.length > 0) { 
            dialog.remove();
        }*/
        
        dialog = this._createDialog(options);

        $("body").append(dialog);

        if (options.callback) {
            options.callback(dialog);
        }

        return this._id;
    },
    
    msgbox: function (msgtype,text,options){ // 消息提示, msgtype为error,warning,info，text为要显示的文字
        msgtype = msgtype || "warning";
        var textHeight = this._msgboxTextHeight(text,140/*信息区文字宽度*/);
        var msgboxWidth = 220; // 信息提示框宽度
        var mssgboxHeight = textHeight + 30 ; // 信息提示框高度
        var msgboxLeft = ($("body").width() - msgboxWidth) / 2; // 水平居中
        var msgboxTop = (document.documentElement.scrollTop || document.body.scrollTop) + (options.verticalAlign === "middle" ? 1:0.618) * (document.documentElement.clientHeight - mssgboxHeight) / 2; // 黄金分割垂直居中
        var defaultOptions = {
            showClose: true,
            modal: true,
            autoClose: {
                wait: 2000,
                callback: null
            }
        };
        defaultOptions = this._merge(defaultOptions,options);
        this._init({
            modal: defaultOptions.modal,
            showClose: defaultOptions.showClose,
            autoClose: defaultOptions.autoClose,
            width: msgboxWidth,
            height: mssgboxHeight,
            top: msgboxTop,
            left: msgboxLeft,
            getDOM: function () {
                var msgInfo = "<div class=\"msgbox\"><div class=\"msgicon\" style=\"height:"+textHeight+"px;\"><div class=\"icon "+msgtype+"\"></div></div><div class=\"msgtext\">"+text+"</div></div>";
                return $(msgInfo);
            }
        });
    },
    
    _tipbox: function (type ,text ,modal ,callback) {
        type = type || "success";
        this.msgbox(type, text,{
            showClose: false,
            "modal": modal,
            autoClose: {
                wait: 2000,
                callback: callback
            }
        });
    },

    tipbox: function (type ,text ,callback) {
        this._tipbox(type ,text ,false ,callback);
    },

    modaltipbox: function (type ,text ,callback) {
        this._tipbox(type ,text ,true ,callback);
    },

    confirmbox: function (options) {
        var confirmboxWidth = 150;
        var confirmboxHeight = 60;
        var confirmboxLeft = options.left || ($("body").width() - confirmboxWidth) / 2; // 水平居中
        var confirmboxTop = options.top || ((document.documentElement.scrollTop || document.body.scrollTop) + 0.618 * (document.documentElement.clientHeight - confirmboxHeight) / 2); // 黄金分割垂直居中
        var text = options.text || "请确认";
        this._init({
            modal: false,
            showClose: false,
            width: confirmboxWidth,
            height: confirmboxHeight,
            top: confirmboxTop,
            left: confirmboxLeft,
            getDOM: function () {
                var msgInfoText = "<div class=\"confirmbox\"><div class=\"confirmtext\">"+text+"</div><div class=\"confirmbtns\"><button class=\"iwbok\">确认</button><button class=\"iwbcancel\">取消</button></div></div>";
                var msgInfo = $(msgInfoText);
                msgInfo.find("button[class=iwbok]").click(function () {
                    if (options.ok) {
                        options.ok();
                    }
                    // 移除所有对话框
                    IWB_DIALOG._disposeAllDialog();
                });
                msgInfo.find("button[class=iwbcancel]").click(function () {
                    if (options.cancel) {
                        options.cancel();
                    }
                    // 移除所有对话框
                    IWB_DIALOG._disposeAllDialog();
                });
                return msgInfo;
            }
        });
    }
};

var IWB_SILDEWARE;

IWB_SILDEWARE = function (silders ,width/*optional*/ ,height/*optional*/) {

    width = width || "auto";
    height = height || "auto";

    if (this instanceof IWB_SILDEWARE) {
        this.width = width;
        this.height = height; // sliderbox height
        this.sliders = silders;
        this.slideware = this.init();
        return this.slideware;
    } else {
        return new IWB_SILDEWARE(silders ,width ,height);
    }
};

IWB_SILDEWARE.prototype = {
    _getSliders: function () {
        var i;
        var j;
        var slider;
        var initleft = 0;
        var hasDefaultSelectPic = false; // 没有指定默认选中的图片选中第一张
        var sliderHtml = ["<div class=\"sliders\""];

        for (j=0; j<this.sliders.length;j++) {
            if (this.sliders[j].selected) {
                hasDefaultSelectPic = true;
                break;
            }
            initleft -= this.width;
        }

        sliderHtml.push(" style=\"left:" + (hasDefaultSelectPic ? initleft : 0) + "px;\">");

        for (i=0; i<this.sliders.length;i++) {
            slider = this.sliders[i];
            sliderHtml.push("<a target=\"_blank\"")
            if (this.width) {
                sliderHtml.push(" style=\"width:" + (/\d$/.test(this.width) ? (this.width +"px") : this.width) + ";\"");
            }
            if (slider.selected) {
            }
            if (slider.title) {
                sliderHtml.push(" title=\"" + slider.title + "\"");
            }
            if (slider.url) {
                sliderHtml.push(" href=\"" + slider.url + "\"");
            }
            sliderHtml.push(">");
            if (slider.pic) {
                sliderHtml.push("<img src=\"" + slider.pic + "\"></img>");
            }
            sliderHtml.push("</a>");
        }
        sliderHtml.push("</div>");
        return sliderHtml.join("");
    }

    ,_getSliderControls: function () {
        var i;
        var j;
        var slider;
        var scontrols = [];
        var hasDefaultSelected = false; // 默认选中的图片
        var selectedMutex; // 只允许选中一张图片

        if (this.sliders.length <= 1) {
            return "";
        }

        for (j=0; j<this.sliders.length; j++) {
            if(this.sliders[j].selected) {
                hasDefaultSelected = true;
                break;
            }
        }

        scontrols.push("<div class=\"silidercontrols\" style=\"width:" + this.sliders.length * 20 + "px;\">");

        for (i=0; i<this.sliders.length; i++) {
            slider = this.sliders[i];
            scontrols.push("<a href=\"javascript:void(0);\"");
            if (!hasDefaultSelected && i === 0) { // 无默认选中，选中第一张
                selectedMutex = true;
                scontrols.push(" class=\"selected\"");
            }
            if (!selectedMutex && slider.selected) {
                selectedMutex = true;
                scontrols.push(" class=\"selected\"");
            }
            scontrols.push("></a>");
        }
        scontrols.push("</div>");
        return scontrols.join("");
    }
    ,init: function () {
        var _this = this;
        var sliderware = ["<div class=\"iwbSlideware\">"
                         ,"<a href=\"javascript:void(0);\" class=\"close\" title=\"关闭\"></a>"
                         ,"<div class=\"slidersbox\">"
                         ,this._getSliders()
                         ,"</div>"
                         ,this._getSliderControls()
                         ,"</div>"].join("");
        var sliderware = $(sliderware);

        sliderware.css({
            width: _this.width
        });
        sliderware.find(".slidersbox").css({
            height: _this.height
        });
        sliderware.find(".silidercontrols > a").click(function () { // 动画
            var self = $(this);
            var sliders = sliderware.find(".sliders");
            sliderware.find(".silidercontrols > a").removeClass("selected");
            self.addClass("selected");
            sliders.animate({
                left: -self.index() * _this.width 
            },500);
        });
        sliderware.find(".close").click(function () {
            sliderware.remove();
        });
        return sliderware;
    }
};

$(function () {
    
    var createImageView = function (controlableImgObj, originalImageUrl) {
        var imageViewHtml = "<div class=\"iwbImageViewControl\">"
                            +"<div class=\"imageViewToolbar\">"
                            +"<div class=\"imageViewToolbarLeft\">"
                            +"<a class=\"rotateLeft\" href=\"javascript:void(0);\">向左转</a>"
                            +"<b>|</b>"
                            +"<a class=\"rotateRight\" href=\"javascript:void(0);\">向右转</a>"
                            +"</div>"
                            +"<div class=\"imageViewToolbarRight\">"
                            +"<a target=\"_blank\" class=\"viewOriginalImage\" href=\"" + originalImageUrl + "\">查看原图</a>"
                            +"</div>"
                            +"</div>"
                            +"<div class=\"imageViewControlBox\">"
                            +"<img class=\"controlableImage\" src=\"" + controlableImgObj.src + "\">"
                            +"</div>";
        var imageView = $(imageViewHtml);
        var controlBox = imageView.find(".imageViewControlBox");
        var rotateLeftBtn = imageView.find(".rotateLeft");
        var rotateRightBtn = imageView.find(".rotateRight");
        var controlableImage = imageView.find(".controlableImage");
        
        controlBox.css({
            width: (controlableImgObj.naturalWidth || controlableImgObj.width) + "px",
            height: (controlableImgObj.naturalHeight || controlableImgObj.height) + "px"
        });
        controlBox.click( function () {
            imageView.parent().find(".iwbImageView").show();
            imageView.hide();
        });
        rotateLeftBtn.click( function () {
            controlableImage.rotate(-90);
        });
        rotateRightBtn.click( function () {
            controlableImage.rotate(90);
        });
        return imageView;
    };
    
    $(".imageViewSmall").live({
        
        click: function () {
            var previewImage = $(this);
            var previewImageContainer = previewImage.parent();
            var existedImageViewer = previewImageContainer.parent().find(".iwbImageViewControl");
            var hasControl = existedImageViewer.length > 0;
            var loadingMask = previewImageContainer.find(".imageLoading");
            var controlableImageUrl = previewImage.attr("data-imageBig");
            var originalImageUrl = previewImage.attr("data-imageHuge");
            
            if (!hasControl) { // 初始化图片控制界面，逻辑只执行一次
                
                loadingMask.css({ // 初始化为图片大小
                    width: previewImage.outerWidth() + "px",
                    height: previewImage.outerHeight() + "px"
                }).show();
                
                var controlableImage = new Image();
                controlableImage.onload = function () { //
                    var imageViewer = createImageView(this,originalImageUrl);
                    previewImageContainer.before(imageViewer);
                    previewImageContainer.hide();
                    loadingMask.hide();
                    controlableImage.onload = null; // IE gif图片会多次出发onload
                };
                controlableImage.onerror = function () {
                    loadingMask.hide();
                    alert("图片加载失败");
                };
                controlableImage.src = controlableImageUrl + "?" + new Date().getTime();
            } else {
                previewImageContainer.hide();
                existedImageViewer.show();
            }
            
        }
        
    });
});

$(function () {

    var formValidate;
    var checkSame;

    Array.prototype.uniquePush = function ( val ) {
        var i;
        for ( i=0;i<this.length;i++) {
            if (val === this[i]) {
                return;
            }
        }
        this.push(val);
    };

    $.fn.fivalidate = function () { // 单个检查
        var field = $(this);
        var validators;
        var fieldValue; 
        var validator; // 验证组
        var validatorErrorInfo;
        var fieldLabel; // 输入框描述字段
        var defaultLabel;
        var i;
        
        if( field.attr("type") && field.attr("type").toLowerCase().match(/text|password/) ) {
            validators = field.attr("data-validator"); // 表单输入限制，支持多个
            fieldValue = field.val(); // 表单值
            fieldLabel = field.attr("data-label"); //字段描述

            if (fieldValue.length <= 0) {
                if (validators.toLowerCase().indexOf("required") !== -1) {
                    validatorErrorInfo = IWB_VALIDATOR["required"](fieldValue);
                    // 解析错误信息label
                    if (!fieldLabel) {
                        fieldLabel = validatorErrorInfo.match(/\[label\|(.*?)\]/); // 提取出一个默认的label
                        if (fieldLabel) {
                            fieldLabel = fieldLabel[1];
                        } else {
                            fieldLabel = ""; // 提取出错label置空
                        }
                    }
                    return validatorErrorInfo.replace(/\[label.*?\]/ig,fieldLabel); // 替换出错信息
                } else {
                    return "";
                }
            }

            if (validators) {
                validators = validators.split(/\s/); // 根据空白分割为多个要求验证
                for(i=0,l=validators.length;i<l;i++) { // 对该字段每项要求进行验证
                    validator = validators[i]; // 每个验证规则
                    // console.log("validating " + fieldLabel + " at rule " + validator);
                    if (IWB_VALIDATOR.hasOwnProperty(validator)) { // 只对IWB_VALIDATOR规则表中中的规则进行验证
                        validatorErrorInfo = IWB_VALIDATOR[validator](fieldValue);
                        if (validatorErrorInfo) { // 验证未通过,返回空代表验证通过
                            // 解析错误信息label
                            if (!fieldLabel) {
                                fieldLabel = validatorErrorInfo.match(/\[label\|(.*?)\]/); // 提取出一个默认的label
                                if (fieldLabel) {
                                    fieldLabel = fieldLabel[1];
                                } else {
                                    fieldLabel = ""; // 提取出错label置空
                                }
                            }
                            return validatorErrorInfo.replace(/\[label.*?\]/ig,fieldLabel); // 替换出错信息
                        }
                    }
                }
            }
        }
        return ""; // 返回空代表验证通过
    };

    $.fn.fovalidate = function () { // 所在表单检查
        var field = $(this);
        var form = field.closest("form");
        var result = validateForm(form);
        return result;
    };

    //$("form[class*=iwbFormValidatorControl]").find("input").each(function (index, field) {

        //// 记录原始背景信息到dom里
        //var jField = $(field);

        //if (jField.attr("type").toLowerCase() === "text") { // 只记录类型为text的表单初始背景
            
            //// 保存原始数据到DOM
            //field.initedBackground = {
                //backgroundColor: jField.css("backgroundColor"),
                //backgroundImage: jField.css("backgroundImage"),
                //backgroundRepeat: jField.css("backgroundRepeat"),
                //backgroundPosition: jField.css("backgroundPosition")
            //};
            
            ////
            ////jField.change(function () {
                ////var self = $(this);
                ////var form = self.closest("form");
                ////var result = validateForm(form);
            ////});
        //}
        
    //});

    checkSame = function ( fields ) {
        var lastVal;
        var labels=[];
        var error;

        if (fields.length > 1) {
            $.each(fields ,function (i,field) {
                field = $(field);
                var val = field.val();
                var label = field.attr("data-label");
                labels.push(label);
                if (lastVal && val !== lastVal) { // 不匹配
                    error = labels.join("、") + "必须一致";
                    return false;
                }
                lastVal = val;
            });
        }
        return error;
    };

    // 表单验证例程
    validateForm = function (form) {

        var form = (form.get && form.nodeName) ? $(form) : form ; // 表单对象
        var fields; // 所有表单域
        var vResult = {ret:0,msg:"ok"};

        var names = []; // 标记为字段值必须一样的域
        
        fields = form.find("input");
        
        $.each(fields,function ( index,field ) {

            var name; 
            var validateError;

            field = $(field);
            validateError = field.fivalidate();

            if (validateError) {

                vResult =  {
                    ret: -1
                    ,msg: validateError
                    ,referer: field
                };

                return false;
            }

            name = field.attr("data-name");

            if (name) {
                names.uniquePush(name);
            }

        });

        $.each(names, function (sindex ,sname) {
            var sfields = $("input[data-name=" + sname + "]");
            var error = checkSame(sfields);
            if (error) {
                vResult = {
                    ret: -1
                    ,msg: error
                    ,referer: sfields.last()
                };
                return false;
            }
        });

        return vResult;
    };

    // 表单验证提交提示
    formValidate = function () {
        var form; // 表单对象
        var fields; //
        var self = $(this);
        var names = []; // 标记为字段值必须一样的域
        var allowSubmit = true;

        if (self.is("form")) {
            form = self;
        } else {
            form = $(this.form);
        }

        fields = form.find("input");
        
        $.each(fields,function ( index,field ) {
            var name; //
            var validateError;
            field = $(field);
            validateError = field.fivalidate();
            if (validateError) {
                IWB_DIALOG.modaltipbox("error",validateError,function () {
                    field.focus();
                });
                allowSubmit = false;
                return false; // 已出错，中断验证
            }
            name = field.attr("data-name");
            if (name) {
                names.uniquePush(name);
            }
        });

        if (allowSubmit) { // 一般正则检查通过
            // data-name相同的表单值必须相同，否则报错
            $.each(names, function (sindex ,sname) {
                var sameFields = $("input[data-name=" + sname + "]");
                var error = checkSame(sameFields);
                if (error) {
                    IWB_DIALOG.modaltipbox("error",error,function () {
                        sameFields.last().focus();
                    });
                    allowSubmit = false;
                    return false; // 已报错中断验证
                }
            });
        }

        return allowSubmit;
    };

    $("form[class*=iwbFormValidatorControl]").find("button[name=validate]").click(formValidate);
    $("form[class*=iwbFormValidatorControl]").submit(formValidate);
});

