<?php

/**
 * iweibo2.0
 *
 * 微博广播模块
 *
 * @author echoyang
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright ? 1998-2011. All Rights Reserved
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_T.php 2011/5/20
 * @package Controller
 * @since 2.0
 */
class Controller_Index_T extends Core_Controller_TAction
{
    const CREATE = 1;  //原创广播
    const RETWEET = 2;  //转播
    const BOX = 3;   //私信
    const DIALOG = 4;  //对话
    const EMPTYT = 5;  //空回
    const MENTION = 6;  //提到

    /**
     * 广播广播
     * 包括:1 广播 2 转播 3 对话 4 评论
     *
     */
    public function addAction ()
    {
        $type = Core_Comm_Validator::getNumArg ($this->getParam ('type'), 1, 4, 1); //1广播 2 转播(评论并转播) 3 对话 4 评论
        $reId = ($type > 1) ? Core_Comm_Validator::getTidArg ($this->getParam ('reid')) : 0;
        $this->callback = $this->getParam ('callback');  //回调函数
        //消息内容
        $content = $this->getParam ('content');
        $content = str_replace ('#输入话题标题#', '', $content); //此话题无效
        if ($type != 2 && !Core_Comm_Validator::checkT ($content)) {
            Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_T_MISS, '', '', $this->callback);
        }

        $this->contentFilter ($content, $type); //检验过滤内容

        $pic = $this->getPic ();    //获取上传图片
        $music = $this->getMusic ();    //获取音乐
        $video = $this->getParam ('video');  //获取视频
        $clientIp = Core_Comm_Util::getClientIp ();   //客户端ip

        $p = array (
            "type" => $type
            , "c" => $content
            , "ip" => $clientIp
            , "j" => ""    //经度，忽略
            , "w" => ""    //纬度，忽略
            , "r" => $reId   //对话转播id
            , "p" => $pic   //图片参数
            , "audio" => $music   //音乐参数
            , "video" => $video   //视频参数
        );

        try
        {
            $addRet = Core_Open_Api::getClient ()->postOne ($p);
        } catch (Core_Api_Exception $e)
        {
            Core_Fun::iFrameExitJson ($e->getCode (), $e->getMessage (), '', $this->callback);
        } catch (Core_Exception $e)
        {
            Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_API_ARG_ERR, '', '', $this->callback);
        }

        //获取该条微博的信息
        $tId = $addRet["data"]["id"];
        $tInfo = Core_Open_Api::getClient ()->getOne (array ("id" => $tId));

        //检验是否发成功消息
        if (empty ($addRet["data"]["id"]) || empty ($tInfo['data'])) {
            Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_API_ARG_ERR, '', '', $this->callback);
        }

        $data = Core_Lib_Base::formatT ($tInfo["data"], 50, 160);
        $data['originopentid'] = $p['r']; //对话转播id
        $data['ip'] = $clientIp;
        $data['txt'] = $content;

        try
        {
            $this->addLocalBlog ($data); //本地化存储单条消息
            $this->addtStat ($type, $p); //统计代码
        } catch (Core_Exception $e)
        {

        }

        //个人认证设置
        $local = Core_Config::get('localauth', 'certification.info');
        $platform = Core_Config::get('platformauth', 'certification.info');
        $authtype = array('local' => $local, 'platform' => $platform);
        $this->assign('authtype', $authtype);

        if ($this->getParam ('format') == "html") {   //返回html dom
            $this->assign ('msg', $data);
            $this->assign ('user', array ("name" => $this->userInfo['name']));
            $data = $this->fetch ('common/tmsg.tpl');
        }

        Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_SUCC, '', $data, $this->callback);
    }

    /**
     * 检验过滤内容
     *
     */
    public function contentFilter ($content, $type)
    {
        //三个话题以上返回错误
        $wellArray = Model_Blog::getTopicByContent ($content);
        $this->wellNum = is_array ($wellArray) ? count ($wellArray) : 0;
        $this->wellNum > 2 && Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_T_TOPIC_ERR, '', '', $this->callback);

        //白名单用户，无需审核
        if (Model_User_Member::isTrustUser ()) {
            return;
        }

        //包含被锁定话题，禁止发送
        if ($wellArray) {
            foreach ($wellArray as $topic)
            {
                if (Model_Topic::isMasked ($topic)) {
                    Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_T_TOPIC_REFUSE, '', '', $this->callback);
                }
            }
        }


        //包含敏感词语，禁止发送
        $this->_filter = Model_Filter::checkContent ($content);
        if (2 == $this->_filter) {
            Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_T_FILTER_REFUSE, '', '', $this->callback);
        }
        return;
    }

    /**
     * 获取上传图片
     */
    public function getPic ()
    {
        $pic = array ();

        $len = isset ($_FILES["pic"]["size"]) ? intval ($_FILES["pic"]["size"]) : 0;
        if (!empty ($_FILES["pic"]['name']) && ($len > 2000000 || $len < 1)) {//图片最大2M
            Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_PIC_SIZE, '', '', $this->callback);
        }

        $code = Core_Comm_Validator::checkUploadFile ($_FILES["pic"]); //检验图片

        if ($code > 0) {//上传成功
            $fileContent = file_get_contents ($_FILES["pic"]["tmp_name"]);

            $picType = Core_Comm_Util::getFileType (substr ($fileContent, 0, 2)); //图片类型
            if ($picType != "jpg" && $picType != "gif" && $picType != "png") {
                Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_PIC_TYPE, '', '', $this->callback);
            }

            if (!is_uploaded_file ($_FILES["pic"]["tmp_name"])) {//非http post上传失败
                Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_T_UPLOAD_UN_HTTP, '', '', $this->callback);
            }

            $pic = array ($_FILES["pic"]["type"], $_FILES["pic"]["name"], $fileContent); //pic参数是个数组
        } elseif ($code < 0) {//上传失败
            Core_Fun::iFrameExitJson ($code, '', '', $this->callback);
        }

        return $pic;
    }

    /**
     * 获取音乐
     */
    public function getMusic ()
    {
        $music = array ();
        $post_music = $this->getParam ('music');
        if (!empty ($post_music)) {
            $musicInfo = pathinfo ($post_music);
            $musicName = empty ($musicInfo['filename']) ? ( empty ($musicInfo['basename']) ? '' : $musicInfo['basename'] ) : $musicInfo['filename'];
            $music["url"] = $post_music;
            $music["title"] = urldecode ($musicName);
            $music["author"] = '佚名';
        }
        return $music;
    }

    /**
     * 本地化存储单条消息
     *
     */
    public function addLocalBlog ($data)
    {
        if (!empty ($data) && !empty ($data['id'])) {
            Model_Blog::addBlog ($data); //本地化消息
            
            //白名单用户，无需审核
            if (Model_User_Member::isTrustUser ()) {
                return;
            }
            //含敏感词直接进审核箱
            $this->_filter == 1 && Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_T_FILTER_WAIT, '', '', $this->callback);

            //先审后发
            if (Core_Config::get ('censor', 'basic', false)) {
                Core_Fun::iFrameExitJson (Core_Comm_Modret::RET_T_CENSOR, '', '', $this->callback);
            }
        }
    }

    /**
     * 发微博统计代码
     *
     */
    public function addtStat ($type, $p)
    {
        //统计代码
        try
        {
            //1广播 2 转播 3 对话 4 评论
            if (!empty ($type)) {
                $skey = array ();//默认原创文本
                switch ($type)
                {//1原创文本广播 2 转播(评论并转播) 3 对话/对话 4 评论
                    case 2://2 转播
                        $skey[] = 'forward';
                        break;
                    case 3://3 对话/对话
                        $skey[] = 'dialog';
                        break;
                    case 4://4 评论
                        $skey[] = 'comment';
                        break;
                    default: //原创
                        if (!empty ($p['p'])) { //原创图片
                            $skey[] = 'oripic';
                        } elseif (!empty ($p['audio'])) {//原创音乐
                            $skey[] = 'orimp3';
                        } elseif (!empty ($p['video'])) {//原创视频
                            $skey[] = 'orivod';
                        } else {
                            $skey[] = 'oritxt';
                        }
                        break;
                }

                $p['c'] && $atNum = substr_count ($p['c'], '@');
                for ($i = 0; $i < $atNum; $i++)
                {
                    $skey[] = 'alt';
                }

                //话题统计数
                $wellNum = empty ($this->wellNum) ? 0 : $this->wellNum;
                $wellNum > 2 && $wellNum == 2;
                for ($i = 0; $i < $wellNum; $i++)
                {
                    $skey[] = 'topic'; //统计@次数
                }

                foreach ($skey as $v)
                {
                    Model_Stat::addStat ($v);//统计
                }
            }
        } catch (Core_Exception $e)
        {
            //pass
        }
    }

    /**
     * 删除微博广播
     *
     */
    public function delAction ()
    {
        $tid = $this->getParam ('tid');
        if (!Core_Comm_Validator::isTId ($tid)) {
            $this->exitJson (Core_Comm_Modret::RET_MISS_ARG);
        }

        $delRet = Core_Open_Api::getClient ()->delOne (array ("id" => $tid));
        Model_Blog::deleteBlogByOpentid ($tid); #删除本地化消息
        $this->exitJson (Core_Comm_Modret::RET_SUCC);
    }

    /**
     * 单条微博广播页
     *
     */
    public function showtAction ()
    {

        $tId = Core_Comm_Validator::getTidArg ($this->getParam ('tid'));  //目标微博id
        //Pageflag 分页标识（0：第一页，1：向下翻页，2向上翻页）
        $f = Core_Comm_Validator::getNumArg ($this->getParam ('f'), 0, 2, 0);
        //每次请求记录的条数（1-20条）
        $num = Core_Comm_Validator::getNumArg ($this->getParam ('num'), 1, 20, 20);
        //本页起始时间（第一页 0，继续：根据返回记录时间决定）
        $t = Core_Comm_Validator::getNumArg ($this->getParam ('t'), 0, PHP_INT_MAX, 0);
        //起始id,用于结果查询中的定位,上下翻页时才有用
        $l = Core_Comm_Validator::getTidArg ($this->getParam ('lid'), "0");

        //获取该条微博的信息
        $tInfo = Core_Open_Api::getClient ()->getOne (array ("id" => $tId));
        //Core_Lib_Base::getAdminFilterPattern($keyWordPattern, $tPattern, $userPattern);//屏蔽单条微博 todo
        $msg = Core_Lib_Base::formatT ($tInfo['data']);

        //visiblecode大于0 此条T被屏蔽
        if (!empty ($msg['visiblecode'])) {
            $this->error (Core_Comm_Modret::RET_T_UNVISIBLE);
        }
        $this->assign ('msg', $msg);

        //获取单条消息用户信息
        $guestUserInfo = Core_Open_Api::getClient ()->getUserInfo (array ('n' => $tInfo['data']['name']));
        $guestUserInfo = Core_Lib_Base::formatU ($guestUserInfo['data'], 120);

        $this->assign ('guest', $guestUserInfo);

        //转播根微博id
        $rtid = $tInfo["data"]["id"];
        if (!empty ($tInfo["data"]["source"]) && $tInfo["data"]["source"]["type"] == self::RETWEET) { //转播
            $rtid = $tInfo["data"]["source"]["id"];
        }

        //获取单条微博的转播列表
        $p = array (
            "reid" => $rtid
            , "f" => $f
            , "n" => $num
            , "t" => $t
            , "tid" => $l
            , "flag" => 2     //0 转播列表，1评论列表 2 评论与转播列表
        );
        $reTList = Core_Open_Api::getClient ()->getReplay ($p);

        //上一页下一页
        $reTListInfo = $reTList["data"]["info"];
        $reTListCount = count ($reTListInfo);
        $pageInfo = Core_Lib_Base::hasFrontNextPage ($f
                        , $reTList["data"]["hasnext"]
                        , '/index/t/showt'
                        , $this->getParams ()
                        , $reTListInfo[0]["timestamp"]
                        , $reTListInfo[$reTListCount - 1]["timestamp"]
                        , $reTListInfo[0]["id"]
                        , $reTListInfo[$reTListCount - 1]["id"]
        );
        $this->assign ('pageinfo', $pageInfo);

        if (is_array ($reTListInfo)) {
            Core_Lib_Base::formatTArr ($reTListInfo, 50, 160);
            foreach ($reTListInfo as &$t)
            {
                $pos = strpos ($t["text"], "||");
                if ($pos !== false) {
                    $t["text"] = substr ($t["text"], 0, $pos);
                }
            }
        }
        //个人认证设置
        $local = Core_Config::get('localauth', 'certification.info');
        $platform = Core_Config::get('platformauth', 'certification.info');
        $authtype = array('local' => $local, 'platform' => $platform);
        $this->assign('authtype', $authtype);

        $this->assign ('tall', $reTListInfo);
        $this->display ('user/showt.tpl');
    }

    /**
     * 获取新消息更新条数
     * data.home：首页更新数
     * data.private :私信更新数
     * data.fans:听众更新数
     * data.mentions提及我的
     * data.create:首页广播（原创）更新数
     *
     */
    public function newmsginfoAction ()
    {
        $newMsgCount = Core_Open_Api::getClient ()->getUpdate (array ("op" => 0));

        //如果是有本地化关系 好友数覆盖本地数字
        if (!Model_Friend::getFriendSrc ()) {
            $userObj = new Model_User_Member();
            $user = $userObj->getUserInfoByName ($_SESSION['name']);
            $newMsgCount["data"]["fans"] = intval ($user['newfollowers']);

            //$openmsg = Model_Friend::singleton()->getTimeline($p, $this->userInfo['name']);
            //$newMsgCount["data"]["home"] = count($openmsg['data']['info']);
            $newMsgCount["data"]["home"] = 0; //api不支持，先清0
        }
        $this->exitJson (Core_Comm_Modret::RET_SUCC, "", $newMsgCount["data"]);
    }

    /**
     * 清除新消息提示
     * @param op :请求类型 0：只请求更新数，不清除更新数，1：请求更新数，并对更新数清零
     * @param type：5 首页未读消息记数，6 @页消息记数 7 私信页消息计数 8 新增粉丝数 9 首页广播数（原创的）
     *
     */
    public static function clearnewmsginfoAction ()
    {
        $type = $this->getParam ('type');
        if ($type < 5 || $type > 9) {
            return;
        }
        try
        {
            Core_Lib_Base::clearNewMsgInfo ($type);
        } catch (Core_Exception $e)
        {
            $this->exitJson (Core_Comm_Modret::RET_API_ERR);
        }
    }

    /**
     * 获取转播和评论列表
     *
     */
    public function rellistAction ()
    {
        //Pageflag 分页标识（0：第一页，1：向下翻页，2向上翻页）
        $f = Core_Comm_Validator::getNumArg ($this->getParam ('f'), 0, 2, 0);
        //每次请求记录的条数（1-20条）
        $num = Core_Comm_Validator::getNumArg ($this->getParam ('num'), 1, 10, 10);
        //本页起始时间（第一页 0，继续：根据返回记录时间决定）
        $t = Core_Comm_Validator::getNumArg ($this->getParam ('t'), 0, PHP_INT_MAX, 0);
        //目标微博id
        $tId = Core_Comm_Validator::getTidArg ($this->getParam ('tid'));
        //起始id,用于结果查询中的定位,上下翻页时才有用
        $l = Core_Comm_Validator::getTidArg ($this->getParam ('lid'), "0");

        //获取单条微博的转播列表
        $p = array (
            "reid" => $tId
            , "f" => $f
            , "n" => $num
            , "t" => $t
            , "tid" => $l
            , "flag" => 2     //0 转播列表，1评论列表 2 评论与转播列表
        );
        $reTList = Core_Open_Api::getClient ()->getReplay ($p);
        //设置或更新用户名到昵称的映射
        if ($reTList && $reTList['data'] && array_key_exists ('user', $reTList['data'])) {
            Core_Lib_Tutil::setUserMap ($reTList['data']['user']);
        }
        $data = array ();
        $data["data"] = $reTList["data"]; //格式化过的内容
        $data["count"] = $data["data"]["totalnum"]; //转播评论总数
        //只显示本人转播的内容
        if (is_array ($data["data"]["info"])) {
            Core_Lib_Base::formatTArr ($data["data"]["info"]);
            foreach ($data["data"]["info"] as &$t)
            {
                $pos = strpos ($t["text"], "||");
                if ($pos !== false) {
                    $t["text"] = substr ($t["text"], 0, $pos);
                }
            }
        }
        $data['tid'] = $tId;


        //个人认证设置
        $local = Core_Config::get('localauth', 'certification.info');
        $platform = Core_Config::get('platformauth', 'certification.info');
        $platform = $platform ?2:0;
        $data['authtype'] = $local+$platform;// 0 本地平台认证都关闭，1本地认证打开，2平台认证打开，3本地平台认证都打开

        $this->exitJson (Core_Comm_Modret::RET_SUCC, "", $data);
    }

    /**
     * @朋友获取我的好友列表缓存数据
     *
     */
    public function myidollistAction ()
    {
        $data = Model_Idollist::getIdollists ();
        $this->exitJson (Core_Comm_Modret::RET_SUCC, "", $data);
    }

    /**
     * 获取视频信息
     *
     */
    public function videoinfoAction ()
    {
        $data = array ();
        $url = $_POST['url'];
        try
        {
            $url = urldecode ($url);
            $p = array ("url" => $url);
            $videoInfo = Core_Open_Api::getClient ()->getVideoInfo ($p);
            exit ($videoInfo);
        } catch (Core_Exception $e)
        {
            $this->exitJson (Core_Comm_Modret::RET_API_ERR);
        }
    }

    /**
     * 获取音乐信息
     *
     */
    public function musicinfoAction ()
    {
        $w = $this->getParam ('w');
        $p = $this->getParam ('p');
        if (!isset ($w) || $w == "") {
            return;
        }
        if (isset ($p)) {
            $p = intval ($p);
        } else {
            $p = 1;
        }
        $w = urlencode ($w);
        $cgiurl = "http://cgi.music.soso.com/fcgi-bin/fcg_search_xmldata.q";
        $params = array ("uin" => "",
            "w" => $w,
            "p" => $p,
            "perpage" => 5,
            "source" => 10,
            "r" => 1302856367027);
        $querystr = "";
        foreach ($params as $key => $p)
        {
            $querystr.=$key . "=" . $p . "&";
        }
        $querystr.="ie=utf-8";
        $result = file_get_contents ($cgiurl . "?" . $querystr);
        $result = iconv ("GB2312", "UTF-8//IGNORE", $result);
        exit ($result);
    }

    /**
     * 获取短链接 todo
     *
     */
    public function urlfullAction ()
    {
        $url = $this->getParam ('url');
        $url = urldecode ($url);
        $urlInfo = Core_Open_Api::getClient ()->getUrlFull (array ('url' => $url));
        exit ($urlInfo);
    }

    /**
     * 非法举报提交页
     *
     */
    public function reportAction ()
    {
        $tid = $this->getParam ('tid');
        $name = $this->getParam ('name');
        if ($tid) {
            try
            {
                $rRet = Core_Open_Api::getClient ()->getOne (array ('id' => $tid));
                $rRet = Core_Lib_Base::formatT ($rRet['data'], 50);
                $this->assign ('report', $rRet);
            } catch (Core_Exception $e)
            {

            }
        } elseif ($name) {
            try
            {
                $rRet = Core_Open_Api::getClient ()->getUserInfo (array ('n' => $name));
                $rRet = Core_Lib_Base::formatU ($rRet['data'], 50);
                $this->assign ('report', $rRet);
            } catch (Core_Api_Exception $e)
            {

            }
        }
        //数据没有用户 则说明举报数据有问题
        if (empty ($rRet['name'])) {
            $this->assign ('ret', Core_Comm_Modret::getMsg (Core_Comm_Modret::RET_REPORT_ERR));
            $this->display ('common/reportover.tpl');
            exit ();
        }

        $reportType = array (0 => '色情', 1 => '虚假消息', 2 => '政治', 3 => '骚扰', 4 => '含危险链接', 5 => '其他');
        $this->assign ('reporttype', $reportType);
        $this->assign ('site_name', Core_Config::get ('site_name', 'basic')); //网站名称
        $this->display ('common/report.tpl');
    }

    /**
     * 非法举报结束页面
     *
     */
    public function illegalreportAction ()
    {
        $tid = $this->getParam ('tid');
        $name = $this->getParam ('name');
        $type = $this->getParam ('type');
        $reason = $this->getParam ('content');
        $callback = $this->getParam ('callback'); //回调

        if (!empty ($tid) || !empty ($name)) {
            $data = array (
                'name' => $this->userInfo['name'],
                'tid' => $tid,
                'name' => $name,
                'type' => $type,
                'reason' => $reason,
                'time' => time ()
            );
            Model_Report::addReport ($data);
            $this->assign ('ret', Core_Comm_Modret::getMsg (Core_Comm_Modret::RET_REPORT_OK));
        } else {
            $this->assign ('ret', Core_Comm_Modret::getMsg (Core_Comm_Modret::RET_REPORT_LOSS));
        }

        $this->display ('common/reportover.tpl');
    }

    /**
     * 对话详情
     *
     */
    public function dialogAction ()
    {
        //初始化open client
        $client = Core_Open_Api::getClient ();
        //目标微博id
        $tId = Core_Comm_Validator::getTidArg ($this->getParam ("tid"));
        //获取当前用户资料
        $userInfo = $client->getUserInfo ();
        //获取该条微博的信息
        $tInfo = $client->getOne (array ("id" => $tId));
        //模版数据
        $user = Core_Lib_Base::formatU ($userInfo["data"], 120);
        $t = Core_Lib_Base::formatT ($tInfo["data"], 50, 460);
        $msglist = array ($t["source"], $t);
        $this->assign ('listcount', count ($msglist));
        $this->assign ('user', $user);
        $this->assign ('msglist', $msglist);
        $this->display ('user/t_dialog.tpl');
    }

}