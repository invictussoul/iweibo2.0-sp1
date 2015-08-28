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

// 查看下一屏数据以及滚动到顶部
$(function () {
    var ns = $(".nextscreen");
    var nsMore = ns.find(".stext");
    var nsMoreClicked; // 上次调用的加载下一屏数据的时间戳
    var nsLoading = ns.find(".loading");
    var autoloadCounter = 0; // 当前已自动滚屏的次数
    var autoloadMax = 3; // 最多自动滚屏3次
    var onScroll; // 注册滚动条滚动中的事件
    var onScrollEnd; // 注册滚动条到底部的事件
    var updateFootControlPos; // 返回顶部按钮
    var footControls = $("#footcontrol"); // 返回顶部及音乐

    //
    onScroll = function () {
        var windowHeight = document.body.offsetHeight;
        var currentScroll = document.documentElement.scrollTop || document.body.scrollTop;
        var visableHeight = document.documentElement.clientHeight;
        $.each(onScrolling.handlers ,function (handlerindex,handler) {
            handler(windowHeight ,currentScroll ,visableHeight);
        });
        if( (visableHeight + currentScroll) >= windowHeight ){
            $.each(onScrollEnd.handlers ,function (handlerindex,handler) {
                handler();
            });
        }
    };
    
    onScrolling = function ( handler ) { // 滚动中
        if ($.isFunction(handler)) {
            onScrolling.handlers.push(handler);
        }
    };
    
    onScrolling.handlers = [];
    
    onScrollEnd = function ( handler ) { // 滚动条到底部的操作
        if ($.isFunction(handler)) {
            onScrollEnd.handlers.push(handler);
        }
    };
    
    onScrollEnd.handlers = [];
    
    if( window.attachEvent ){
        window.attachEvent("onscroll",onScroll,false);
    }else{
        window.addEventListener('scroll',onScroll,false);
    }
    
    //
    updateFootControlPos = function (total , scrolled, visible) {
        var footControlVisible = footControls.is(":visible");
        var ie6 = $.browser.msie && $.browser.version === "6.0";
        var footerHeight = $(".footer").outerHeight();
        
        if (footControls.length <= 0) {
            return;
        }
        
        if (scrolled <= 0 && footControlVisible) {
            footControls.fadeOut(500);
        } else if (scrolled > 0 && !footControlVisible) {
            footControls.fadeIn(500);
        }
        if (!ie6) {
            if (!footControls.css("position") === "fixed") {
                footControls.css({
                    "position":"fixed"
                });
            }
            footControls.css({
               "bottom": Math.max(0 ,visible + scrolled - (total - footerHeight /*- 4*/))
            });
        } else {
            if (!footControls.css("position") === "absolute") {
                footControls.css({
                    "position": "absolute"
                });
            }
            footControls.css({
                "top": Math.min(visible + scrolled - footControls.height() /*+ 2*/,total - footerHeight - footControls.height() /*+ 1*/) 
            });
        }
    };
    
    onScrolling(updateFootControlPos);
    // $(window).resize(onScroll);
    // 
    onScrollEnd(function () {
        if (autoloadCounter < autoloadMax) {
            nsMore.trigger("click");
            autoloadCounter ++;
        }
    });
    
    nsMore.click(function () {
        var tmain = $("#tmain").last();
        var tmessage = tmain.find(".tmessage");
        var lastMsg = tmessage.last();
        var that = $(this);
        var username;
        var urlusername;
        var ie6 = $.browser.msie && $.browser.version === "6.0";
        var type; // 时间线类型 1 收听的人和自己 的广播  2 自己的广播 3 提到我的广播  4 收藏的广播

        if ( !nsMoreClicked || (new Date().getTime() - nsMoreClicked) > 1500 ) {

            nsMoreClicked = new Date().getTime();

        } else {
            return;
        }

        if (!tmain.length > 0) {
            return;
        }

        if (location.href.match(/\/u\/(\w+)/)) { // 判断网址中的用户名，若网址用户名与登录用户名不匹配，则拉取客人页时间线
            urlusername = location.href.match(/\/u\/(\w+)/)[1];
            if (window.iwbUsername && iwbUsername !== urlusername) {
                username = urlusername;
            }
        }
        
        IWB_API.timelineMore.addObserver(that);

        if (!that.get(0).onResponse) {
            that.get(0).onResponse = function (identity, response) {

                if (identity === "timeline") {

                    if (response.ret === 0) {

                        if (!response.data) {
                            ns.remove();
                            // IE浏览器bug
                            if ($.browser.msie){
                                if (!ie6) {
                                    footControls.css({
                                        bottom: 0
                                    });
                                }
                                onScroll();
                            }
                            return;
                        }

                        var result = $(response.data);

                        result.hover(function () {
                            $(this).css("background-color","#f8f8f8");
                            $(".iwbUsercard").hide();
                        } ,function () {
                            $(this).css("background-color","#ffffff");
                        });

                        $("#tmain > li").last().after(result);

                        // 底部控制条位置
                        if (ie6) {
                            footControls.css({
                                top: document.documentElement.clientHeight + document.documentElement.scrollTop - footControls.height() + 1
                            });
                        } else {
                            footControls.css({
                                bottom: 0
                            });
                        }

                        nsLoading.hide();
                        nsMore.show();
                    }
                }
            };
        }
        
        nsMore.hide();
        nsLoading.show();

        IWB_API.timelineMore("timeline" ,{
             name: username
            ,type: (window.iwbTimelineMoreType || 1)
            ,utype: location.href.match(/\/utype\/(\d+)/) ? location.href.match(/\/utype\/(\d+)/)[1] : null
            ,ctype: location.href.match(/\/ctype\/(\d+)/) ? location.href.match(/\/ctype\/(\d+)/)[1] : null
            ,lid: lastMsg.attr("data-id")
            ,ltime: lastMsg.attr("data-time")
            ,f: 1
        });
    });
    
    window.onScroll = onScroll;
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
