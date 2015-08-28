// 话题墙
$(function () {
    var curWallId = window.wallId || 0;
    var curWallOffset = window.wallStart || 0;
    var lastResponse = true;

    var updateWall = function () {
        lastResponse = null;
        IWB_API.wallOne("getWall" ,curWallId ,curWallOffset ,function (identity ,response) {
            lastResponse = response;
            var one;
            var oneHeight;
            var oneImage; // 广播
            var oneImageSrc;
            var oneVideo; // 视频
            var oneVideoSrc;
            if (response.ret === 0 && response.data) {

                one = response.data;
                one = $(one);
                oneImage = one.find(".wallImage");
                oneVideo = one.find(".wallVideo");

                if (oneImage.length>0) {
                    oneImageSrc = oneImage.attr("src");
                    oneImage.attr("src","");
                    oneImage.hide();
                }

                if (oneVideo.length>0) {
                    oneVideoSrc = oneVideo.attr("src");
                    oneVideo.attr("src","");
                    oneVideo.hide();
                }

                $("#walllist").prepend(one);
                oneHeight = one.height(); // 无图片的信息框高度

                one.css({
                    height: "0px"
                });

                one.animate({
                    height: oneHeight
                }, 800 ,function() {
                    one.css({
                        height: "auto"
                    });
                    if (oneImageSrc) {
                        oneImage.attr("src",oneImageSrc);
                        oneImage.show();
                    }
                    if (oneVideoSrc) {
                        oneVideo.attr("src",oneVideoSrc);
                        oneVideo.show();
                    }
                });
                curWallOffset ++;
            }
        });
    };

    setInterval(function () {
        if (!lastResponse) {
            return;
        }
        updateWall();
    }, 5 * 1000);
});
