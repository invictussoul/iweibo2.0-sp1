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
                        +"<div class=\"iwbDialogBg\"></div>"
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
