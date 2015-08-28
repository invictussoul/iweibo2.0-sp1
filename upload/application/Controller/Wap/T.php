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
 * @version $Id: Controller_Index_T.php 2011/6/5
 * @package Controller
 * @since 2.0
 */
class Controller_Wap_T extends Core_Controller_WapAction
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
    public function addAction()
    {
        $type = Core_Comm_Validator::getNumArg($this->getParam('type'), 1, 4, 1); //1广播 2 转播(评论并转播) 3 对话 4 评论
        //消息内容
        $content = $this->getParam('content');
        $content = str_replace('#输入话题标题#', '', $content); //此话题无效
        //广播时候，如果选择上传图片,进入上传图片页面
        $addpic = $this->getParam('addpic');
        if ($type == 1 && isset($addpic))
        {
            if(!empty($_SERVER['HTTP_REFERER']))
            {
                $backurl = $_SERVER['HTTP_REFERER'];
                $this->assign('backurl', $backurl);
            }
            $this->assign('content', trim($content));
            $this->display('wap/common/sendpicbox.tpl');
            return;
        }

        $pathroot = Core_Fun::getPathroot(); //项目地址
        //不是原创时候，有对话id
        if ($type > 1)
        {
            $reId = Core_Comm_Validator::getTidArg($this->getParam('reid'));
            $this->backUrl =  '/wap';
        }
        else
        {
            $reId = 0;
            $this->backUrl = '/wap/t/add/addpic';
        }

        //检验文本内容
        $this->contentFilter($content, $type);

        $pic = $this->getPic(); //获取上传图片
        $clientIp = Core_Comm_Util::getClientIp();
        $p = array(
            "type" => $type
            , "c" => $content
            , "ip" => $clientIp     //客户端ip
            , "j" => ""    //经度，忽略
            , "w" => ""    //纬度，忽略
            , "r" => $reId   //对话转播id
            , "p" => $pic   //图片参数
            , "audio" => array()   //音乐参数
            , "video" => ''   //视频参数
        );

        //广播
        try
        {
            $addRet = Core_Open_Api::getClient()->postOne($p);
        }
        catch (Core_Api_Exception $e)
        {
            Core_Fun::showmsg($e->getMessage(), $this->backUrl); //返回上一页;
        }
        catch (Exception $e)
        {
            Core_Fun::showmsg('操作失败', $this->backUrl); //返回上一页;
        }

        //获取该条微博的信息
        $tId = $addRet["data"]["id"];
        $tInfo = Core_Open_Api::getClient()->getOne(array("id" => $tId));


        if (empty($addRet["data"]["id"]) || empty($tInfo['data']))
        {
            Core_Fun::showmsg('操作失败', $this->backUrl); //返回上一页;
        }

        $data = Core_Lib_Base::formatT($tInfo["data"], 50, 160);
        $data['originopentid'] = $p['r']; //对话转播id
        $data['ip'] = $clientIp;
        $data['txt'] = $content;
        $this->addLocalBlog($data); //本地化存储单条消息
        
//        $backurl = $this->getParam('backurl');
//        empty($backurl) && $backurl = Core_Fun::getUrlroot().'wap';     

        $backurl = $this->getParam("backurl");
        empty($backurl) && $backurl = empty($_SERVER['HTTP_REFERER'])?-1:$_SERVER['HTTP_REFERER'];
        Core_Fun::showmsg('', $backurl, 0); //返回上一页
    }

    /**
     * 检验过滤内容
     *
     */
    public function contentFilter($content, $type)
    {
        if ($type != 2 && !Core_Comm_Validator::checkT($content))
        {
            Core_Fun::showmsg('输入内容140字以内,不能为空', $this->backUrl); //返回上一页;
        }


        //三个话题以上返回错误
        $wellArray = Model_Blog::getTopicByContent($content);
        $wellNum = is_array($wellArray) ? count($wellArray) : 0;
        $wellNum > 2 && Core_Fun::showmsg('消息不能多于三条话题', $this->backUrl); //返回上一页;


        //白名单用户，无需审核
        if (Model_User_Member::isTrustUser ()) {
            return;
        }

        //包含被锁定话题，禁止发送
        if($wellArray && is_array($wellArray))
        {
            foreach ($wellArray as $topic)
            {
                if (Model_Topic::isMasked($topic))
                {
                    Core_Fun::showmsg('包含锁定话题', $this->backUrl); //返回上一页;
                }
            }
        }

        //包含敏感词语，禁止发送
        $this->_filter = Model_Filter::checkContent($content);
        if (2 == $this->_filter)
        {
            Core_Fun::showmsg('对不起！您广播的内容包含敏感词，请重新输入', $this->backUrl); //返回上一页;
        }
        return;
    }

    /**
     * 获取上传图片
     */
    public function getPic()
    {
        $pic = array();

        $len = isset($_FILES["pic"]["size"])?intval($_FILES["pic"]["size"]):0;
        if (!empty($_FILES["pic"]['name']) && ($len > 2000000 || $len < 1))//图片最大2M
        {
            Core_Fun::showmsg(Core_Comm_Modret::getMsg(Core_Comm_Modret::RET_PIC_SIZE), $this->backUrl); //返回上一页;
        }

        $code = Core_Comm_Validator::checkUploadFile($_FILES["pic"]); //检验图片

        if ($code > 0)//上传成功
        {
            $fileContent = file_get_contents($_FILES["pic"]["tmp_name"]);

            $picType = Core_Comm_Util::getFileType(substr($fileContent, 0, 2)); //检验图片类型
            if ($picType != "jpg" && $picType != "gif" && $picType != "png")
            {
                Core_Fun::showmsg(Core_Comm_Modret::getMsg(Core_Comm_Modret::RET_PIC_TYPE), $this->backUrl); //返回上一页;
            }

            if (!is_uploaded_file($_FILES["pic"]["tmp_name"]))//非http post上传失败
            {
                Core_Fun::iFrameExitJson(Core_Comm_Modret::RET_T_UPLOAD_UN_HTTP, '', '', $this->callback);
            }

            $pic = array($_FILES["pic"]["type"], $_FILES["pic"]["name"], $fileContent); //pic参数是个数组
        }
        elseif ($code < 0)//上传失败
        {
            Core_Fun::showmsg(Core_Comm_Modret::getMsg($code), $this->backUrl); //返回上一页;
        }

        return $pic;
    }

    /**
     * 本地化存储单条消息
     *
     */
    public function addLocalBlog($data)
    {
         //白名单用户，无需审核
        if (Model_User_Member::isTrustUser ()) {
            return;
        }

        if (!empty($data) && !empty($data['id']))
        {
            Model_Blog::addBlog($data); //本地化消息
            unset($data['originopentid']);

            //含敏感词直接进审核箱
            $this->_filter == 1 && Core_Fun::showmsg('对不起！您广播的内容包含需要审核的关键词，请等待管理员审核', $this->backUrl); //返回上一页;
            //先审后发
            if (Core_Config::get('censor', 'basic', false))
            {
                Core_Fun::showmsg('对不起！您广播的内容需要审核，请等待管理员审核', $this->backUrl); //返回上一页;
            }
        }
        return;
    }

    /**
     * 删除微博广播
     *
     */
    public function delAction()
    {
        $tid = $this->getParam('tid');
        if (!Core_Comm_Validator::isTId($tid))
        {
            Core_Fun::showmsg('丢失消息id', -1); //返回上一页;
        }

        $delRet = Core_Open_Api::getClient()->delOne(array("id" => $tid));
        Model_Blog::deleteBlogByOpentid($tid); #删除本地化消息
        empty($backurl) && $backurl = empty($_SERVER['HTTP_REFERER'])?Core_Fun::getUrlroot().'wap':$_SERVER['HTTP_REFERER'];        
        Core_Fun::showmsg('', $backurl, 0); //返回上一页
    }

    //发送消息页面
    public function showtAction()
    {
        $tid = Core_Comm_Validator::getTidArg($this->getParam('tid'));
        $type = Core_Comm_Validator::getNumArg($this->getParam('type'), 1, 4, 1);
        $this->assign('sendbox', array('tid' => $tid, 'type' => $type));

        //Pageflag 分页标识（0：第一页，1：向下翻页，2向上翻页）
        $f = Core_Comm_Validator::getNumArg($this->getParam('f'), 0, 2, 0);
        //每次请求记录的条数（1-20条）
        $num = Core_Comm_Validator::getNumArg($this->getParam('num'), 1, 20, 10);
        //本页起始时间（第一页 0，继续：根据返回记录时间决定）
        $t = Core_Comm_Validator::getNumArg($this->getParam('t'), 0, PHP_INT_MAX, 0);
        //起始id,用于结果查询中的定位,上下翻页时才有用
        $l = Core_Comm_Validator::getTidArg($this->getParam('lid'), "0");

        //获取该条微博的信息
        $tInfo = Core_Open_Api::getClient()->getOne(array("id" => $tid));

        $msg = Core_Lib_Base::formatT($tInfo['data']);

        //visiblecode大于0 此条T被屏蔽
        if (!empty($msg['visiblecode']))
        {
            Core_Fun::showmsg('此条消息已删除', -1); //返回上一页;
        }
        $this->assign('msg', $msg);


        //转发根微博id
        $rtid = $tInfo["data"]["id"];
        if (!empty($tInfo["data"]["source"]) && $tInfo["data"]["source"]["type"] == self::RETWEET)
        { //转播
            $rtid = $tInfo["data"]["source"]["id"];
        }

        //获取单条微博的转发列表
        $p = array(
            "reid" => $rtid
            , "f" => $f
            , "n" => $num
            , "t" => $t
            , "tid" => $l
            , "flag" => 2     //0 转播列表，1评论列表 2 评论与转播列表
        );
        $reTList = Core_Open_Api::getClient()->getReplay($p);

        //上一页下一页
        $reTListInfo = $reTList["data"]["info"];
        $reTListCount = count($reTListInfo);
        $pageInfo = Core_Lib_Base::hasFrontNextPage($f
                        , $reTList["data"]["hasnext"]
                        , '/wap/t/showt'
                        , $this->getParams()
                        , $reTListInfo[0]["timestamp"]
                        , $reTListInfo[$reTListCount - 1]["timestamp"]
                        , $reTListInfo[0]["id"]
                        , $reTListInfo[$reTListCount - 1]["id"]
        );
        $this->assign('pageinfo', $pageInfo);

        if (is_array($reTListInfo))
        {
            Core_Lib_Base::formatTArr($reTListInfo, 50, 160);
            foreach ($reTListInfo as &$t)
            {
                $pos = strpos($t["text"], "||");
                if ($pos !== false)
                {
                    $t["text"] = substr($t["text"], 0, $pos);
                }
            }
        }
        $this->assign('tall', $reTListInfo);

        if(!empty($_SERVER['HTTP_REFERER']))
        {
            $backurl = $_SERVER['HTTP_REFERER'];
            $this->assign('backurl', $backurl);
        }

        $this->display('wap/common/showt.tpl');
    }

}