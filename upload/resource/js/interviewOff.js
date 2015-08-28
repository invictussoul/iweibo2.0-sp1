// 全局事件
$(function () {
    var createSkinSelector = function () {
        var boxId;
        var skinId = "myskin" + new Date().getTime()+""; // 避免与页面静态CSS链接重复
        var boxWidth = 590;
        var boxHeight = 200;
        var boxLeft = ($("body").width() - boxWidth) / 2;
        var boxTop = (document.documentElement.scrollTop || document.body.scrollTop) + 0.618 * (document.documentElement.clientHeight - boxHeight) / 2;
        boxId = IWB_DIALOG._init({
            modal: false
           ,showClose: true
           ,width: boxWidth
           ,height: boxHeight
           ,top: boxTop
           ,left: boxLeft
           ,getDOM: function () {
                var skinSelector = ["<div class=\"facesettingBox\" style=\"border:none;position:relative;top:-10px;height:210px;\">"
                              ,"    <div class=\"tit\">"
                              ,"        <span class=\"facesetting\"></span>"
                              ,"        <strong>皮肤设置</strong>"
                              ,"    </div>"
                              ,"    "
                              ,"    <div class=\"facelist\">"
                              ,"        <ul class=\"DtempList clear\" id=\"skinBox\">"
                              ,"              正在读取皮肤列表,请稍候..."
                              ,"        </ul>"
                              ,"    </div>"
                              ,"    <div class=\"funBox\">"
                              ,"        <input type=\"button\" value=\"保存\" name=\"save\" class=\"button button_blue\">"
                              ,"        <input type=\"button\" value=\"取消\" name=\"cancel\" class=\"button button_gray\">"
                              ,"    </div>"
                              ,"</div>"].join("");

               var skinBox; // 皮肤列表 
               var saveBtn; // 保存
               var cancelBtn; // 取消
               var curSkin; // 当前选择的皮肤

               skinSelector = $(skinSelector);
               skinBox = skinSelector.find("#skinBox"); 
               saveBtn = skinSelector.find("input[name=save]");
               cancelBtn = skinSelector.find("input[name=cancel]");
               curSkin = "";

               // 加载皮肤列表
               IWB_API.listSkin("listskin" ,function (identity ,response) {
                   var skins;
                   var skin;
                   var skinBlock;
                   var i;
                   var noskin = (window.iwbResourceRoot ? iwbResourceRoot : "/") +  "resource/images/noskin.jpg";
                   if (response.ret === 0) {
                        skins = response.data;
                        skinBlock = [];
                        for (i=0; i<skins.length; i++) {
                            skin = skins[i];
                            skinBlock.push("<li data-folder=\"" + skin.foldername + "\">");
                            skinBlock.push("<img src=\"" + (skin.thumb ? skin.thumb : noskin) + "\"/>");
                            skinBlock.push("<p class=\"ico_lock\"></p>");
                            skinBlock.push("<p class=\"tempName\">" + (skin.name || "默认皮肤") + "</p>");
                            skinBlock.push("<div class=\"mask\"></div>");
                            skinBlock.push("</li>");
                        }
                        skinBlock = $(skinBlock.join(""));
                        skinBlock.click(function () {
                            var self = $(this);
                            var folder = self.attr("data-folder");
                            var skinCss = (window.iwbResourceRoot ? iwbResourceRoot : "/") + "view/" + folder + "/skin.css"; 
                            var link;

                            $("link[id=" + skinId + "]").remove();

                            // http://www.subchild.com/2010/05/20/cross-browser-problem-with-dynamic-css-loading-with-jquery-1-4-solved/
                            link = $("<link rel=\"stylesheet\" type=\"text/css\" href=\"" + skinCss + "\" media=\"screen, projection\" id=\"" + skinId + "\"/>").appendTo("head");
                            // link = $("<link/>").appendTo("head");

                            link.attr({
                                rel: "stylesheet"
                               ,type: "text/css"
                               ,href: skinCss
                               ,media: "screen, projection"
                               ,id: skinId
                            });

                            curSkin = self.attr("data-folder");
                        });
                        skinBox.html("");
                        skinBox.append(skinBlock);
                   } else {
                       IWB_DIALOG.modaltipbox("warning","读取皮肤列表失败，请稍候重试");
                   }
               });

               // 保存设置
               saveBtn.click(function () {
                   if (!curSkin) {
                       IWB_DIALOG.msgbox("warning" ,"请选择皮肤", {
                           showClose: false
                           ,modal: true
                           ,autoClose: {
                               wait: 1500
                               ,callback: null
                           }
                       });
                       return;
                   }
                   IWB_API.saveSkin("setskin" ,curSkin ,function (identity ,response) {
                       if (response.ret === 0) {
                           IWB_DIALOG.tipbox("success","皮肤设置保存成功");
                       } else {
                           IWB_DIALOG.tipbox("warning","皮肤设置保存失败");
                       }
                       IWB_DIALOG._disposeDialog(boxId);
                   });
               });

               // 刷新页面
               cancelBtn.click(function () {
                   $("link[id=" + skinId + "]").remove();
                   IWB_DIALOG._disposeDialog(boxId);
               });

               return skinSelector;
           } // end getDOM
        });// end dialog _init
    };

    // 换肤按钮
    $("#changeskin").click(function () {
        createSkinSelector();
    });

    //幻灯片组件
    if (window.sliderBanner && IWB_SILDEWARE) {
        // 格式化数据
        for (var i=0; i<sliderBanner.length; i++) {
            if(sliderBanner[i].description) {
                sliderBanner[i].title = sliderBanner[i].description;
            }
            if(sliderBanner[i].picture) {
                sliderBanner[i].pic = sliderBanner[i].picture;
            }
        }
        $("#sliderBanner").append(IWB_SILDEWARE(sliderBanner,576,129));
    }

    // 插件
    $("#iwbPlugin").hover(function () {
        $(this).trigger("click");
    }, function () {
    });

    $("#iwbPlugin").click(function (e) {
        e.stopPropagation();
        $("#iwbPluginList").toggle();
    });

    // 
    $("body").click(function () {
        $(".iwbAutoCloseLayer").hide();
    });

});

// 时间线
$(function () {

    // 创建转播，评论，对话框，基础框架
    var replyForm = function () {

        var instance = $(["<div class=\"replyform answerbox\">"
                        ,    "<em class=\"icon_blueangle\"></em>"
                        ,    "<a href=\"javascript:void(0);\" class=\"close\"></a>"
                        ,    "<ul class=\"top topinfo\">"
                       // ,        "<li class=\"gray\">转播原文，把它分享给你的听众</li>"
                       // ,        "<li class=\"gray\">顺便说两句：</li>"
                        ,    "</ul>"
                        ,    "<ul class=\"top\">"
                        ,        "<li><textarea id=\"treplyforminput\" class=\"iwbFriendControlInput\"></textarea></li>"
                        ,    "</ul>"
                        ,    "<div class=\"fleft\">"
                        ,        "<a href=\"javascript:void(0);\" class=\"icon_topic\"></a> <a href=\"javascript:void(0);\" class=\"iwbFriendControlBtn icon_at\" data-for=\"#treplyforminput\"></a> <a href=\"javascript:void(0);\" data-for=\"#treplyforminput\" class=\"iwbEmotesBtn icon_emotion\"></a>"
                     //  ,        "<input type=\"checkbox\" id=\"replayformcheckbox\">"
                     //   ,        "<label for=\"replayformcheckbox\">分享到QQ空间</label>"
                        ,    "</div>"
                        ,    "<div class=\"fright\">"
                        ,        "<label id=\"replyformtip\" class=\"label\">还能输入140字</label>"
                        ,       "<input type=\"hidden\" name=\"reid\" value=\"\"></input>"
                        ,        "<input type=\"button\" data-identity=\"timelineform\" class=\"replyformsubmit button button_blue\" value=\"\"></input>"
                        ,    "</div>"
                        ,"</div>"].join(""));
        
        var submit =  instance.find(".replyformsubmit");
        var forminput = instance.find("#treplyforminput");
        var submitBtn = submit.get(0);

        IWB_API.reply.addObserver(submitBtn);

        submitBtn.onResponse = function (identity,response) {
            var self = $(this);
            var submitIdentity = submit.attr("data-identity");
            var reid = parseInt(instance.find("input[name=reid]").val(),10);
            var inlineTip = instance.find("#replyformtip");

            var tmessage;
            var tmessageHeight;
            var tmessageImage;
            var temssageImageSrc;
            var tmessageVideoPreview;
            var tmessageVideoPreviewSrc;

            if (identity === submitIdentity) {
                if (response.ret === 0) { // 转播/评论/对话操作成功
                    self.prop("disabled",false);
                    instance.detach();
                    IWB_DIALOG.tipbox(null,"提交成功");
                    // 发送广播成功动画效果 copy from sendbox js
                    tmessage = $(response.data);

                    $("#"+reid).first().parent().append(tmessage);
                    tmessage.hide().fadeIn(500);
                    //
                } else {

                   inlineTip.blink(2,"<span class=\"minisendboxerror\">"+response.msg+"</span>",function () {
                       forminput.focus();
                       forminput.trigger("keyup");
                       self.prop("disabled",false);
                   });

                   inlineTip.addClass("formerror");
                }
            }
        };

        // 关闭按钮
        instance.find(".close").click(function() {
            instance.detach();
        });

        // 插入话题
        instance.find(".icon_topic").click(function() {
            var range;
            var topicText = "输入话题标题";
            var textInput = forminput;
            var textField = textInput.get(0);
            var topicStart = textInput.val().lastIndexOf(topicText);
            var topicEnd = topicStart + topicText.length;
            if (topicStart < 0) { // 输入框内输入话题，并高亮之
                textInput.val(textInput.val() + "#" + topicText +"#");
                topicStart = textInput.val().lastIndexOf(topicText);
                topicEnd = topicStart + topicText.length;
            }
            if (document.createRange) { // IE高亮
                textField.setSelectionRange( topicStart, topicEnd );
            } else { // 非IE高亮
                range = textField.createTextRange();
                range.collapse(true);
                range.moveStart( 'character', topicStart );
                range.moveEnd( 'character', topicEnd - topicStart );
                range.select();
            }
        });

        // 字数检查
        forminput.keyup(function(event) {
            if (event.keyCode === 17 || event.keyCode === 13) { // ctrl enter释放不刷新字数
                return;
            }
            var msgbox = $(this);
            var msg = msgbox.val();
            var msglen = IWB_UTIL.msglen(msg);
            var sendmsg = instance.find("#replyformtip");
            var submit = instance.find(".replyformsubmit");
            var tip;
            
            if (msglen<=140) {
                tip = "还能输入<big>"+(140-msglen)+"</big>字";
                sendmsg.removeClass("formerror");
            } else {
                tip = "超出<big style=\"color:#ff4700;\">"+(Math.abs(140-msglen) > 100?"很多":Math.abs(140-msglen))+"</big>字";
                sendmsg.addClass("formerror");
            }
            sendmsg.html(tip);
        }).keydown(function (event) {
            if (event.ctrlKey && (event.keyCode === 13 || event.keyCode === 10)) {
                if (submit.prop("disabled")) { // 提交按钮不允许被点击
                    return;
                }
                submit.trigger("click");
            }
        });

       // 提交按钮
       submit.click(function () {
           var that = $(this);
           var tip = instance.find("#replyformtip");
           var reid = instance.find("input[name=reid]").val();
           var content = forminput.val();
           var identity = that.attr("data-identity");

           if (tip.hasClass("animating")) { // 必须等待报错的动画完成才能提交
               return;
           }

           // 已有错误
           if(tip.hasClass("formerror")) {
               tip.addClass("animating");
               tip.blink(3,function () {
                   tip.removeClass("animating");
               });
               return;
           }

           // 发送前再次检查字数
           forminput.focus();
           forminput.trigger("keyup");

           // 已有错误
           if(tip.hasClass("formerror")) {
               tip.addClass("animating");
               tip.blink(3,function () {
                   tip.removeClass("animating");
               });
               return;
           }


           // 发送请求
           IWB_API.reply(identity,reid,content);

           that.prop("disabled",true);

           tip.html("<span class=\"minisendboxinfo\">" + "提交中，请稍候" + "</span>");

       });

       instance.hide();

       this.instance = instance;
    };
    
    replyForm.prototype = {
        // 初始化回复框
        // 1 转播 2评论 3对话 4 查看转播与评论列表
        init: function (options) {

            var _instance = this.instance;

            _instance.attr("data-type",options.type);
            _instance.show();

            // 清空提示文字
            _instance.find(".topinfo").html("");
            _instance.find("#treplyforminput").val("");
            _instance.find("#replyformtip").removeClass("formerror").removeClass("animating").text("还能输入140字");
            
            // 重置按钮状态
            _instance.find(".replyformsubmit").prop("disabled",false);

            switch (options.type) {

                // 回答问题
                case 1:

                // 默认文字
                if (options.defaultText) { 
                    _instance.find("#treplyforminput").val(options.defaultText);
                }

                // 顶部提示信息
                _instance.find(".topinfo").append([
                    "<li class=\"gray\">"
                    ,"我的答案:"
                    ,"</li>"
                    ].join(""));

                // 按钮文字
                _instance.find(".replyformsubmit").val("提交");

                if (options.reid) {
                    _instance.find("input[name=reid]").val(options.reid);
                }

                // 更新identity，防止串时间线
                _instance.find(".replyformsubmit").attr("data-identity","timelinesubmit"+new Date().getTime());
                break;

            }
        },
        attachTo: function (obj) {
            this.instance.detach();
            obj.append(this.instance);
        },
        getType: function () {
            return parseInt(this.instance.attr("data-type"),10);
        },
        detach: function () {
            this.instance.attr("data-type","");
            this.instance.detach();
        },
        focus: function () {
            this.instance.find("#treplyforminput").focus();
        },
        updateTextCounter: function () {
            this.focus();
            this.instance.find("#treplyforminput").trigger("keyup");
        }
    };

    // 初始化对话框
    var replyform = new replyForm();
    $("body").append(replyform.instance);

    // 访谈页面回答问题
    $(".tanswer").live({
        click: function () {
            var that = $(this);
            var reid = that.attr("data-reid");
            var replyhost = $("#"+reid).parent();
            var hasform = replyhost.find(".replyform").length > 0;
            var type = 1;
            var defaultText="";

            if (hasform && replyform.getType() === type) {
                replyform.detach();
                return;
            }

            replyform.init({
                type: type,
                reid: that.attr("data-reid")
            });

            replyform.attachTo(replyhost);
            replyform.focus();

        }
    });

});

$(function () {
    // styleid 0 大收听按钮 1 小收听按钮
    // type 1 收听按钮 0 取消收听
    var posConfig = {
        0: {
             0:["-52px", "-27px"]
            ,1:["-52px", "0px"]
           }
       ,1: {
             0:["0px", "-20px"]
            ,1:["0px", "0px"]
           }
    };

    $(".iwbFollowControl").live({
        click: function () {
            var self = $(this);
            var styleid = self.attr("data-styleid");
            var type = self.attr("data-type");
            var name = self.attr("data-name");
            switch (type) {
                case "0": // 取消收听
                IWB_API.unfollow("unfollow", name, function (identity ,response) {
                    if (response.ret === 0) { // 取消收听成功
                        self.attr("data-type",1); // 标记按钮为收听按钮
                        self.attr("title","收听");
                        self.animate({
                            "backgroundPosition": posConfig[styleid][self.attr("data-type")].join(" ")
                        } ,500)
                    } else {
                        IWB_DIALOG.modaltipbox("warning","取消收听失败")
                    }
                });
                break;
                case "1": // 收听
                IWB_API.follow("unfollow", name, function (identity ,response) {
                    if (response.ret === 0) { // 收听成功
                        self.attr("data-type",0); // 标记按钮为取消收听按钮
                        self.attr("title","取消收听");
                        self.animate({
                            "backgroundPosition": posConfig[styleid][self.attr("data-type")].join(" ")
                        } ,500);
                    } else {
                        IWB_DIALOG.modaltipbox("warning","收听失败");
                    }
                });
                break;
            }
        } 
    });
});

$(function () {
    var createAddBox = function ( defaultText ) {
        var boxId;
        var boxWidth = 500;
        var boxHeight = 133;
        var boxLeft = ($("body").width() - boxWidth) / 2;
        var boxTop = (document.documentElement.scrollTop || document.body.scrollTop) + 1 /*0.618*/ * (document.documentElement.clientHeight - boxHeight) / 2;
        boxId = IWB_DIALOG._init({
            modal: true
           ,showClose: true
           ,width: boxWidth
           ,height: boxHeight
           ,top: boxTop
           ,left: boxLeft
           ,callback: function (dialog) {
               var textInput = dialog.find("textarea[name=talkboxinput]");
               textInput.focus();
               textInput.val(defaultText);
               textInput.trigger("keyup");
           }
           ,getDOM: function () {
               var talkBox =  ["<div class=\"replyform\" style=\"border:none;text-align:left;\">"
                                  ,"    <ul class=\"top topinfo\">"
                                  ,"        <li class=\"gray\">分享给我的听众</li>"
                                  ,"    </ul>"
                                  ,"    <ul class=\"top\">"
                                  ,"        <li>"
                                  ,"            <textarea name=\"talkboxinput\" id=\"iwbtalkinput\"></textarea>"
                                  ,"        </li>"
                                  ,"    </ul>"
                                  ,"        <div class=\"fleft\">"
                                  ,"            <a href=\"javascript:void(0);\" class=\"talktopic icon_topic\"></a>"
                                  ,"            <a href=\"javascript:void(0);\" class=\"iwbFriendControlBtn icon_at\" data-for=\"#iwbtalkinput\"></a>"
                                  ,"            <a href=\"javascript:void(0);\" data-for=\"#iwbtalkinput\" class=\"iwbEmotesBtn icon_emotion\"></a>"
                                  ,"        </div>"
                                  ,"        <div class=\"fright\">"
                                  ,"            <label id=\"iwbtalktip\" class=\"label\">还能分享140字</label>"
                                  ,"            <input type=\"button\" name=\"sendtalk\" class=\"replyformsubmit button button_blue\" value=\"发送\">"
                                  ,"        </div>"
                                  ,"        <div class=\"fbottom\" style=\"clear:both;\"></div>"
                                  ,"</div>"].join("");
               var talkContentBox; // 对话内容区
               var sendBtn; // 发送按钮
               var topicBtn; // 插入话题按钮
               var tipBox; // 信息提示区

               talkBox = $(talkBox);
               talkContentBox = talkBox.find("textarea[name=talkboxinput]");
               sendBtn = talkBox.find("input[name=sendtalk]");
               topicBtn = talkBox.find(".talktopic");
               tipBox = talkBox.find("#iwbtalktip");

               topicBtn.click(function () {
                   IWB_UTIL.highlightOrInsert("输入话题标题",talkContentBox.get(0));
               });

               talkContentBox.keyup(function () {
                   var msglen = IWB_UTIL.msglen($(this).val());
                   var tip;

                   if (msglen<=140) {
                       tip = "还能输入<big>"+(140-msglen)+"</big>字";
                       sendBtn.removeClass("disable");
                   } else {
                       tip = "超出<big style=\"color:#ff4700;\">"+(Math.abs(140-msglen) > 100?"很多":Math.abs(140-msglen))+"</big>字";
                       sendBtn.addClass("disable");
                   }

                   tipBox.html(tip);
               });

               talkContentBox.trigger('keyup');

               sendBtn.click(function () {
                   var content = talkContentBox.val();
                   var errormsg;

                   sendBtn.prop("disabled",true);

                   // 已有错误发生
                   if (sendBtn.hasClass("disable")) {
                       tipBox.blink(3);
                       sendBtn.prop("disabled",false);
                       return;
                   }

                   // 发送前再次检查字数
                   talkContentBox.focus();
                   talkContentBox.trigger("keyup");

                   if (sendBtn.hasClass("disable")) {
                       tipBox.blink(3);
                       sendBtn.prop("disabled",false);
                       return;
                   }

                   // 
                   if (!talkContentBox.val()) {
                       errormsg = "请填写要分享的内容";
                   }

                   if (errormsg) {
                       IWB_DIALOG.msgbox("warning" ,errormsg ,{
                           showClose: false,
                           modal: true,
                           verticalAlign: "middle",
                           autoClose: {
                               wait: 2000,
                               callback: null
                           }
                       });
                       sendBtn.prop("disabled",false);
                       return;
                   }

                   // 发送对话
                   IWB_API.add("iwbtalk" ,content ,function (identity ,response){

                      if (response.ret === 0) {
                          response.msg = "分享成功";
                      } 

                      IWB_DIALOG.msgbox(response.ret === 0 ? "success" : "warning" ,response.msg ,{
                          showClose: false,
                          modal: true,
                          verticalAlign: "middle",
                          autoClose: {
                              wait: 2000,
                              callback: function () {
                                  if (response.ret !== 0) {
                                      talkContentBox.focus();
                                      talkContentBox.trigger("keyup");
                                      sendBtn.prop("disabled",false);
                                  } else {
                                      IWB_DIALOG._disposeDialog(boxId);
                                  }
                              }
                          }
                      }); // end msgbox

                   });

               });// end sendbtn click

               return talkBox;
           } // end get dom
        }); // end init
    };

    $(".iwbAddBtn").live({
        click: function () {
            var self = $(this);
            var defaultText = self.attr("data-text") || "";
            createAddBox(defaultText);
        }
    });
});
