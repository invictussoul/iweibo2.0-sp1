<?php

/**
 * iweibo2.0
 *
 * 消息操作类
 *
 * @author gionouyang <gionouyang@tencent.com>
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Core_Lib_Base.php 2011-06-09 20:22:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Core_Lib_Base
{

    protected static $hotTopicNum = 10; //热门话题数量

    /**
     * 高亮字符串
     * @param $string 待高亮处理字符串
     * @param $highlight 需要高亮的字符串
     * @
     */

    public static function microtime_float ()
    {
        list($usec, $sec) = explode (" ", microtime ());
        return ((float)$usec + (float)$sec);
    }

    public static function highlight ($string, $highlight)
    {
        $tracn = false; //是否高亮兼容繁体字，搜索单页打开时间会多0.5s左右，此函数效率降低400倍
        //static $t;
        if (!empty ($highlight)) {
            //$start = self::microtime_float();
            $highlightKeywords = preg_split ("/[\s,]+/", $highlight);
            if (isset ($highlightKeywords)) {
                foreach ($highlightKeywords as &$kw)
                {
                    if ($tracn) {
                        $kwTra = Core_Comm_Util::simcn2tracn ($kw); //中文转繁体
                        $kwTra = preg_quote ($kwTra);
                        $kwTra != $kw && $highlightKeywords[] = $kwTra;

                        $kwSim = Core_Comm_Util::simcn2tracn ($kw, false); //繁体转中文
                        $kwSim = preg_quote ($kwSim);
                        $kwSim != $kw && $highlightKeywords[] = $kwSim;
                    }
                    $kw = preg_quote ($kw);
                }

                $highlightKeywords = array_unique ($highlightKeywords);
                $highlightPattern = "-(" . join ("|", $highlightKeywords) . ")-i";
                $highlightReplacement = "<u>$1</u>";
                $string = preg_replace ($highlightPattern, $highlightReplacement, $string);
            }

            //$end = self::microtime_float();
            //$t +=$end-$start;
        }
        return $string;
    }

    /**
     * 格式化单个用户头像
     * @param $t
     * @param $headSize		50/100/120
     * @
     */
    public static function formatHead ($head, $headSize = 50)
    {

        if (!empty ($head)) {
            $head .= "/$headSize";
        } else {
            $head .= '/resource/images/default_head_' . $headSize . '.jpg';
        }
        return $head;
    }

    /**
     * 格式化单个用户信息(from api)
     * @param $t
     * @param $headSize		50/100/120
     * @
     */
    public static function formatU ($u, $headSize = 0, $localTag = true)
    {

        if (in_array ($headSize, array (50, 100, 120))) {
            if (!empty ($u["head"])) {
                $u["head"] .= "/$headSize";
            } else {
                $u["head"] .= '/resource/images/default_head_' . $headSize . '.jpg';
            }
        }
        !isset ($u["timestamp"]) or $u["timestring"] = Core_Lib_Tutil::tTimeFormat ($u["timestamp"]);

        //最近广播
        if (isset ($u["tweet"]) && is_array ($u["tweet"])) {
            foreach ($u["tweet"] as &$t)
            {
                $t["text"] = Core_Lib_Tutil::tContentFormat ($t["text"]);
                $t["timestring"] = Core_Lib_Tutil::tTimeFormat ($t["timestamp"]);
            }
        }

        if (isset ($u["Ismyidol"])) { //模版要求
            $u['isidol'] = $u["Ismyidol"];
        }
        if (isset ($u["ismyidol"])) { //模版要求
            $u['isidol'] = $u["ismyidol"];
        }
        if (isset ($u["Ismyblack"])) { //模版要求
            $u["ismyblack"] = $u["Ismyblack"];
        }
        if (isset ($u["introduction"])) {
            $u["introduction"] = htmlspecialchars ($u["introduction"]);
        }

        $user = Model_User::usersInfo ($u["name"], 4);

        if (!empty ($user[$u["name"]])) {
            $user = $user[$u["name"]];
            !empty ($user['nick']) && $u["nick"] = $user['nick'];
        }

        //如果强制启用本地化标签或者 是本地关系链
        if (0 == Model_Friend::getFriendSrc ()) {
            $userModel = new Model_User_Member();
            $cu = $userModel->onGetCurrentAccessToken ();
            $cuname = $cu['name'];

            //$user = $userModel->getUserInfoByName($u['name']);
            if ($user) {
                $u['fansnum'] = isset ($user['fansnum']) ? $user['fansnum'] : 0;
                $u['idolnum'] = isset ($user['idolnum']) ? $user['idolnum'] : 0;
            } else {
                $u['fansnum'] = 0;
                $u['idolnum'] = 0;
            }
            $u["ismyidol"] = $u["isidol"] = Model_User_FriendLocal::isFollowee ($cuname, $u['name']);

            //$u["ismyfans"] = $u["isfans"] = Model_User_FriendLocal::isFollower($cuname, $u['name']);
        }

        //如果启用本地化标签，则把网络标签替换掉
        if ($localTag && 0 == Model_Tag::getTagSrc ()) {
            $tagObj = Model_Tag::singleton ();
            $u['tag'] = $tagObj->getTag ($u['name']); //查找本人的有效tag
        }
        return $u;
    }

    /**
     * 格式化单个用户信息(from api)
     * @param $tabArr = array(array('url'=>'index.php','title'=>'首页'),array('url'=>'friend.php','title'=>'我的好友'),1)
     * @param $check 选中键值
     * @param $frightHtml 自定义右侧html
     * @
     */
    public static function formatTab ($tabArr = array (), $check = 0, $frightHtml = '')
    {
        $tabStr = '';
        if (is_array ($tabArr) && $tabArr) {
            $check = intval ($check);
            if ($check < 0 || $check > count ($tabArr)) {
                $check = 0;
            }
            $check = Core_Comm_Validator::getNumArg ($check, 0, count ($tabArr), 0);
            $tabArr = array_values ($tabArr);
            $tabStr = '<div class="tabbar"><ul class="tabs">';
            foreach ($tabArr as $k => $v)
            {
                if ($k == $check) {
                    isset ($v['url']) && $tabStr .= '<li class="tab active"><strong>' . $v['title'] . '</strong></li>';
                } else {
                    isset ($v['url']) && isset ($v['title']) &&
                            $tabStr .= '<li class="tab"><a href="' . $v['url'] . '">' . $v['title'] . '</a></li>';
                }
            }
            $tabStr .= '</ul><div class="fright">' . $frightHtml . '</div></div>';
        }
        return $tabStr;
    }

    /**
     * 批量格式化微博广播
     * @param $tArr
     * @param $headSize
     * @param $imageSize
     * @param $blackKeyArr
     * @
     */
    public static function formatTArr (&$tArr, $headSize = 50, $imageSize = 160, $highlight = '', $filterDeleted = false)
    {
        //用户屏蔽名单
        $userMemObj = new Model_User_Member();
        $userBlacklist = $userMemObj->onGetBlacklist ();

        //self::getAdminFilterPattern($keyWordPattern, $tPattern, $userPattern);//屏蔽
        //遍历
        foreach ($tArr as $k => &$t)
        {
            //屏蔽用户
            if ($userBlacklist && in_array ($t['name'], $userBlacklist)) {
                unset ($tArr[$k]);
                continue;
            }

            //过滤已删除的广播，如搜索页
            if ($filterDeleted && self::isDeletedTweet ($t)) {
                unset ($tArr[$k]);
                continue;
            }
            !empty ($tArr['box']) && $t['box'] = true; //检验是否是私信，不过滤
            //$t = self::formatT($t, $headSize, $imageSize, $highlight, $keyWordPattern, $tPattern, $userPattern);
            $t = self::formatT ($t, $headSize, $imageSize, $highlight);
        }

        return;
    }

    /**
     * 是否是已删除的广播
     * @param $taArr
     * @
     */
    private static function isDeletedTweet ($tArr)
    {
        $deletedFlag = !empty ($tArr["status"]) && $tArr["status"] != 0;
        $trimText = trim ($tArr["text"]);
        $hasSource = array_key_exists ("source", $tArr);
        $emptyContent = empty ($trimText) && !$hasSource; //转播内容允许为空
        return $deletedFlag || $emptyContent;
    }

    /**
     * 查询本地库，判断是否显示某条微博
     * @param type $opentid
     */
    public static function isVisibleT ($opentid)
    {
        return!Model_Mask::isMasked ($opentid);
    }

    /**
     * 格式化单条微博广播(from api)
     * @param $t
     * @param $headSize		默认timeline的50
     * @param $imageSize	默认timeline的160
     * @param $keyWordPattern	要屏蔽的关键字正则匹配串
     * @param $tPattern		要屏蔽的微博id正则匹配串
     * @param $userPattern	要屏蔽的微博用户帐号正则匹配串
     * @
     */
    public static function formatT ($t, $headSize = '50', $imageSize = '160', $highlight = '', $keyWordPattern = '', $tPattern = '', $userPattern = '')
    {

        //本地过滤，确定是否能显示(私信不过滤)
        if (empty ($t['box'])) {
            $t['visiblecode'] = 0;
            if (!Model_User_Member::isTrustUser ($t['name'])) {
                if (!self::isVisibleT ($t["id"])) {
                    $t['visiblecode'] = 1;
                    return $t;
                } elseif (Model_Filter::checkContent ($t["text"]) > 1) {
                    $t['visiblecode'] = 1;
                    return $t;
                } elseif (isset($t["source"]["text"]) && Model_Filter::checkContent ($t["source"]["text"]) > 1) {
                    $t['visiblecode'] = 1;
                    return $t;
                }
            }
        }

        $needReplace = false; //该原创广播是否需要被屏蔽
        $referedNeedReplace = false; //引用的广播是否需要屏蔽
        //转播内容处理
        if (!empty ($t["source"])) {
            if (self::isDeletedTweet ($t["source"])) { //转播的消息已在微博平台上被删除
                $t["source"]["text"] = "<font color=\"#999999\">此消息已被删除</font>";
            } else { //正常原文
                if (!empty ($t["source"]["text"])) { //有原文
                    $referedNeedReplace = (!empty ($keyWordPattern) &&
                            preg_match ($keyWordPattern, $t["source"]["text"])) ||
                            (!empty ($tPattern) && preg_match ($tPattern, "a" . $t["source"]["id"] . "a"));
                    if (!$referedNeedReplace) {
                        $t["source"]["text"] = Core_Lib_Tutil::tContentFormat ($t["source"]["origtext"]); //前台使用text字段，后台使用origtext字段
                    } else {
                        $t["source"]["text"] = "<font color=\"#999999\">此消息已被网站管理员屏蔽</font>";
                        $t["source"]["image"] = "";
                    }
                }
            }
            if (!empty ($t["source"]["origtext"])) {
                $t["source"]["origtext"] = ""; //无用
            }
            if (!empty ($t["source"]["head"])) {
                $t["source"]["head"] .= "/$headSize";
            } else {
                $t["source"]["head"] .= '/resource/images/default_head_' . $headSize . '.jpg';
            }
            if (!empty ($t["source"]["image"])) {
                $t["source"]["image"] = $t["source"]["image"][0]; //."/$imageSize";
            }
            $t["source"]["frommobile"] = ($t["source"]["from"] == "手机");
            $t["source"]["timestring"] = Core_Lib_Tutil::tTimeFormat ($t["source"]["timestamp"]);
        }

        //原创内容处理
        if (self::isDeletedTweet ($t)) {
            $t["text"] = "<font color=\"#999999\">此消息已被删除</font>";
        } else {
            $needReplace = (!empty ($keyWordPattern) && preg_match ($keyWordPattern, $t["text"])) ||
                    (!empty ($tPattern) && preg_match ($tPattern, "a" . $t["id"] . "a")); //加a做精确匹配
            if (!$needReplace) {

                $t["text"] = Core_Lib_Tutil::tContentFormat ($t["origtext"]);

                //高亮关键字,只针对原创
                $highlight && $t["text"] = self::highlight ($t["text"], $highlight);
                if ($highlight && !empty ($t['source']["text"])) {
                    $t['source']["text"] = self::highlight ($t['source']["text"], $highlight);
                }
            } else { //需要屏蔽
                $t["text"] = "<font color=\"#999999\">此消息已被网站管理员屏蔽</font>";
                $t["image"] = "";
            }
        }
        
        if (!empty($t["head"])) {
            $t["head"] .= "/$headSize";
        } else {
            isset($t["head"]) && $t["head"] .= '/resource/images/default_head_' . $headSize . '.jpg';
        }
        if (!empty($t["tohead"])) {
            $t["tohead"] .= "/$headSize";
        } else {
             isset($t["tohead"]) && $t["tohead"] = '/resource/images/default_head_' . $headSize . '.jpg';
        }
        if (!empty ($t["image"])) {
            $t["image"] = $t["image"][0]; //."/$imageSize";
        }
        $t["frommobile"] = isset($t["from"])?($t["from"] == "手机"):0;
        $t["timestring"] = Core_Lib_Tutil::tTimeFormat ($t["timestamp"]);

        if (empty ($t['box']))//私信已经处理过用户了
        {
            //本地化
            $user = Model_User_Util::getLocalInfo ($t["name"]);

            !empty ($user['nick']) && $t["nick"] = $user['nick'];
            $t["localauth"] = empty ($user['localauth'])?0:1;

            if (isset($t["source"]) && $t['source']) {
                $sourceuser = Model_User_Util::getLocalInfo ($t['source']["name"]);
                !empty ($sourceuser['nick']) && $t['source']["nick"] = $sourceuser['nick'];
                $t['source']["localauth"] = empty ($sourceuser['localauth']) ? 0 : 1;
            }
        }

        return $t;
    }

    /**
     * 获取我订阅的话题
     * @
     */
    protected static function getMyTopic ($f = 0, $num = 11, $t = 0, $l = '')
    {
        $p = array ('type' => 1, //0 收藏的消息  1 收藏的话题
            'f' => $f, //分页标识（0：第一页，1：向下翻页，2向上翻页）
            'n' => $num, //每次请求记录的条数（1-20条）
            't' => $t, //本页起始时间（第一页 0，继续：根据返回记录时间决定）
            'l' => $l); //当前页最后一条记录，用用精确翻页用


        $myTopic = Core_Open_Api::getClient ()->getFav ($p);
        $myTopicOk = array ();
        if (is_array ($myTopic["data"]["info"])) {
            foreach ($myTopic["data"]["info"] as $var)
            {
                array_push ($myTopicOk, array ("name" => $var["text"], "id" => $var["id"], "count" => $var["tweetnum"],
                    "timestamp" => $var["timestamp"]));
            }
        }
        return $myTopicOk;
    }

    /**
     * 拉取当前用户和访问用户的信息
     * @param $name
     * @return array[当前用户信息,访问用户信息]
     */
    protected static function getUserInfo ($name)
    {
        if (empty ($name)) {
            $userInfo = Core_Open_Api::getClient ()->getUserInfo ();
            return array ($userInfo["data"]["info"], $userInfo["data"]["info"]);
        } else {
            $userInfo = Core_Open_Api::getClient ()->getUserInfo ();
            $p = array ("n" => $name);
            $userInfo2 = Core_Open_Api::getClient ()->getUserInfo ($p);
            return array ($userInfo["data"]["info"], $userInfo2["data"]["info"]);
        }
    }

    /**
     * 是否有前一页后一页
     * @param $pageFlag 分页标识（0：第一页，1：向下翻页，2向上翻页）
     * @param $hasNext	api返回的是否有后一页 0 表示还可拉取 1 已拉取完毕
     * @param $preUrl /model/controllor/action
     * @param $urlPara $this->getParams()
     * @param $frontPageTime 上一页起始时间戳
     * @param $nextPageTime 下一页起始时间戳
     * @param $frontLid
     * @param $nextLid
     * @
     */
    public static function hasFrontNextPage ($pageFlag, $hasNext, $preUrl, $urlPara = array (), $frontPageTime = '', $nextPageTime = '', $frontLid = '', $nextLid = '')
    {
        $frontUrlArg = "";
        $nextUrlArg = "";
        if ($pageFlag == 0 && $hasNext === 0) {
            $frontUrlArg = "";
            $addArgv = array ("f" => 1);
            if (!empty ($nextPageTime)) {
                $addArgv["t"] = $nextPageTime;
            }
            if (!empty ($nextLid)) {
                $addArgv["lid"] = $nextLid;
            }
            $nextUrlArg = $addArgv;
        } elseif ($pageFlag == 1) { //下翻
            $addArgv = array ("f" => 2);
            if (!empty ($frontPageTime)) {
                $addArgv["t"] = $frontPageTime;
            }
            if (!empty ($frontLid)) {
                $addArgv["lid"] = $frontLid;
            }
            $frontUrlArg = $addArgv;
            if ($hasNext === 0) {
                $addArgv = array ("f" => 1);
                if (!empty ($nextPageTime)) {
                    $addArgv["t"] = $nextPageTime;
                }
                if (!empty ($nextLid)) {
                    $addArgv["lid"] = $nextLid;
                }
                $nextUrlArg = $addArgv;
            }
        } elseif ($pageFlag == 2) { //上翻
            $addArgv = array ("f" => 1);
            if (!empty ($nextPageTime)) {
                $addArgv["t"] = $nextPageTime;
            }
            if (!empty ($nextLid)) {
                $addArgv["lid"] = $nextLid;
            }
            $nextUrlArg = $addArgv;
            if ($hasNext === 0) {
                $addArgv = array ("f" => 2);
                if (!empty ($frontPageTime)) {
                    $addArgv["t"] = $frontPageTime;
                }
                if (!empty ($frontLid)) {
                    $addArgv["lid"] = $frontLid;
                }
                $frontUrlArg = $addArgv;
            }
        }

        if (is_array ($urlPara) && $urlPara) {
            $frontUrlArg && $frontUrlArg = array_merge ($urlPara, $frontUrlArg); //
            $nextUrlArg && $nextUrlArg = array_merge ($urlPara, $nextUrlArg);
        }

        $frontUrl = Core_Fun::getParaUrl ($preUrl, $frontUrlArg);
        $nextUrl = Core_Fun::getParaUrl ($preUrl, $nextUrlArg);
        return array ('fronturl' => $frontUrl, 'nexturl' => $nextUrl);
    }

    /**
     * 清除新消息提示
     * @param op :请求类型 0：只请求更新数，不清除更新数，1：请求更新数，并对更新数清零
     * @param type：5 首页未读消息记数，6 @页消息记数 7 私信页消息计数 8 新增粉丝数 9 首页广播数（原创的）
     *
     */
    public static function clearNewMsgInfo ($type)
    {
        if ($type < 5 || $type > 9) {
            return;
        }
        try
        {
            if ($type != 8) { //云端直接情空状态
                Core_Open_Api::getClient ()->getUpdate (array ("op" => 1, "type" => $type));
            } else {
                Model_Friend::singleton ()->cleanFansNum (); //情况新增粉丝数 需要根据配置选择 本地化时候 open和本地都会清除
            }
        } catch (Core_Exception $e)
        {
            return;
        }
    }

}

?>