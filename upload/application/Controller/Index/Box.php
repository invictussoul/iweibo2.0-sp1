<?php

/**
 * iweibo2.0
 * 
 * 信箱模块控制器
 *
 * @author echoyang 
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Box.php 2011/5/20
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Box extends Core_Controller_TAction
{
    const BOX_TYPE_SENDBOX = 0;
    const BOX_TYPE_INBOX = 1;

    //构造函数
    public function preDispatch()
    {
        parent::preDispatch();
        $this->assign('active', 'inbox');
    }

    /**
     * 发私信
     *
     */
    public function addAction()
    {
        $name = $this->getParam("name");                //收信人微博帐号
        $content = trim($this->getParam("content"));        //微博内容
        if (!isset($content, $name))
        {
            $this->exitJson(Core_Comm_Modret::RET_MISS_ARG);
        }
        if (!Core_Comm_Validator::checkT($content))
        {
            $this->exitJson(Core_Comm_Modret::RET_ARG_ERR);
        }
        if (!Core_Comm_Validator::isUserAccount($name))
        {
            $this->exitJson(Core_Comm_Modret::RET_ARG_ERR, '请输入正确用户账户');
        }
        $clientIp = Core_Comm_Util::getClientIp();

        //检查目标用户是否存在
        try
        {
            $userInfo = Core_Open_Api::getClient()->getUserInfo(array("n" => $name));
            $userNameNick = $userInfo["data"]["nick"];
        }
        catch (exception $e)
        {            //帐号不存在api也返回内部错误
            $this->exitJson(Core_Comm_Modret::RET_DEFAULT_ERR, '此用户不存在，请输入你好友当前使用的微博帐号');
        }

        //检查对方是否是我的粉丝
        $checkRet = Core_Open_Api::getClient()->checkFriend(array("n" => $name, "type" => 0));
        $isfans = (bool) $checkRet["data"][$name];
        if ($isfans == false)
        {
            $this->exitJson(Core_Comm_Modret::RET_DEFAULT_ERR, '他/她还没有收听你，暂时不能发私信');
        }
        $p = array(
            "c" => $content
            , "n" => $name
            , "ip" => $clientIp
            , "j" => ""                //经度，忽略
            , "w" => ""                //纬度，忽略
        );
        try
        {
            $postRet = Core_Open_Api::getClient()->postOneMail($p);
        }
        catch (exception $e)
        {
            $this->exitJson(Core_Comm_Modret::RET_DEFAULT_ERR, '私信发送失败');
        }
        empty($postRet['data']['id']) && $this->exitJson(Core_Comm_Modret::RET_DEFAULT_ERR, '私信发送失败');

        //统计代码
        try
        {
            Model_Stat::addStat('pm');
        }
        catch (Exception $e)
        {
            //pass
        }


        //格式化box数据 并返回
        $data = $postRet["data"];
        $data['text'] = $content;
        $data['origtext'] = $content;
        $data['toisvip'] = $userInfo["data"]['isvip'];
        $data['toname'] = $userInfo["data"]['name'];
        $data['tohead'] = $userInfo["data"]['head'];
        $data['tonick'] = $userInfo["data"]['nick'];
        $data['timestamp'] = $data['time'];
        $data['box'] = true;

         //个人认证设置
        $localAuth = Core_Config::get('localauth', 'certification.info');
        $openAuth = Core_Config::get('platformauth', 'certification.info');
        $uInfo = Model_User_Util::getLocalInfo($name);
        !empty($uInfo['nick']) && $data['tonick'] = $uInfo['nick'];//有本地昵称就覆盖
        if($localAuth && !empty($uInfo['localauth']) || $openAuth && !empty($data['toisvip']))
        {
            $data['is_auth'] = true;
        }
        $data['box'] = true; //有此字段，不会format和formatarr不会检验关键字

        $data = Core_Lib_Base::formatT($data, '50');
        $this->assign('box', $data);

        $data = $this->fetch('box/sendeach.tpl');
        $this->exitJson(Core_Comm_Modret::RET_SUCC, '', $data);
    }

    /**
     * 删除私信
     *
     */
    public function delAction()
    {
        $tid = $this->getParam("tid");
        if (!isset($tid))
        {
            $this->exitJson(Core_Comm_Modret::RET_MISS_ARG);
        }
        if (!Core_Comm_Validator::isTId($tid))
        {
            $this->exitJson(Core_Comm_Modret::RET_ARG_ERR);
        }
        Core_Open_Api::getClient()->delOneMail(array("id" => $tid));
        $this->exitJson(Core_Comm_Modret::RET_SUCC);
    }

    /**
     * 收件箱
     *
     */
    public function inboxAction()
    {
        //主栏组件
        $this->assign('mainComponent', Model_Componentprocessunit::getComponentWithHtml(6, 'main'));
        //右栏组件
        $this->assign('rightComponent', Model_Componentprocessunit::getComponentWithHtml(6, 'right'));

        return $this->box(self::BOX_TYPE_INBOX);
    }

    /**
     * 发件箱
     *
     */
    public function sendboxAction()
    {
        //主栏组件
        $this->assign('mainComponent', Model_Componentprocessunit::getComponentWithHtml(6, 'main'));
        //右栏组件
        $this->assign('rightComponent', Model_Componentprocessunit::getComponentWithHtml(6, 'right'));

        return $this->box(self::BOX_TYPE_SENDBOX);
    }

    /**
     * 信箱逻辑
     * @param $type 0 发件箱 1 收件箱
     *
     */
    protected function box($type)
    {
        $f = Core_Comm_Validator::getNumArg($this->getParam("f"), 0, 2, 0);
        $num = Core_Comm_Validator::getNumArg($this->getParam("num"), 1, 20, 20);
        $t = Core_Comm_Validator::getNumArg($this->getParam("t"), 0, PHP_INT_MAX, 0);
        $l = Core_Comm_Validator::getTidArg($this->getParam('lid'), "0");
        $p = array(
            'type' => $type, //0 发件箱 1 收件箱
            'f' => $f, //分页标识（0：第一页，1：向下翻页，2向上翻页）
            'n' => $num, //每次请求记录的条数（1-20条）
            't' => $t, //本页起始时间（第一页 0，继续：根据返回记录时间决定）
            'l' => $l        //lastid
        );


        //信箱内容
        $boxInfo = Core_Open_Api::getClient()->getMailBox($p);
        if(!empty($boxInfo['data']['info']))
        {
            //个人认证设置
            $localAuth = Core_Config::get('localauth', 'certification.info');
            $openAuth = Core_Config::get('platformauth', 'certification.info');

                if($type)//收件箱
                {
                    foreach($boxInfo['data']['info'] AS &$v)
                    {
                        $uInfo = Model_User_Util::getLocalInfo($v['name']);
                        !empty($uInfo['nick']) && $v['nick'] = $uInfo['nick'];//有本地昵称就覆盖
                        if($localAuth && !empty($uInfo['localauth']) || $openAuth && !empty($v['isvip']))
                        {
                        $v['is_auth'] = true;
                        }
                    }
                }else{
                    foreach($boxInfo['data']['info'] AS &$v)
                    {
                        $uInfo = Model_User_Util::getLocalInfo($v['toname']);
                        !empty($uInfo['nick']) && $v['tonick'] = $uInfo['nick'];//有本地昵称就覆盖
                        if($localAuth && !empty($uInfo['localauth']) || $openAuth && !empty($v['toisvip']))
                        {
                        $v['is_auth'] = true;
                        }
                    }
                }
        }

        $boxInfoData = $boxInfo["data"];
        $boxInfoData['box'] = true; //有此字段，不会format和formatarr不会检验关键字
        if (is_array($boxInfoData["info"]))
        {        //设置头像大小
            Core_Lib_Base::formatTArr($boxInfoData["info"], '50');
        }
        $this->assign('boxinfo', $boxInfoData);



        //上一页下一页
        $frontUrl = $nextUrl = "";
        $actionBox = ($type == 0 ? "sendbox" : "inbox");

        $pageInfo = Core_Lib_Base::hasFrontNextPage($f, $boxInfoData["hasnext"]
                        , '/index/box/' . $actionBox
                        , array()
                        , $boxInfoData["info"][0]["timestamp"]
                        , $boxInfoData["info"][(count($boxInfoData["info"]) - 1)]["timestamp"]
                        , $boxInfoData["info"][0]["id"]
                        , $boxInfoData["info"][(count($boxInfoData["info"]) - 1)]["id"]
        );
        $this->assign('pageinfo', $pageInfo);


        $data["screenuser"] = $this->userInfo;
        $type && Core_Lib_Base::clearNewMsgInfo(7);//私信页消息计数
        $template = ($type == 0 ? "sendbox.tpl" : "inbox.tpl");
        $this->display('box/' . $template);
    }

}

?>
