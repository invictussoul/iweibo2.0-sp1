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
 * @version $Id: Controller_Wap_Box.php 2011/5/20
 * @package Controller
 * @since 2.0
 */
class Controller_Wap_Box extends Core_Controller_WapAction
{
    const BOX_TYPE_SENDBOX = 0;
    const BOX_TYPE_INBOX = 1;

    /**
     * 发私信
     *
     */
    public function addAction()
    {    
        if(!empty($_SERVER['HTTP_REFERER']))
        {
            $backurl = $_SERVER['HTTP_REFERER'];
            $this->assign('backurl', $backurl);
        }
        $toname = trim($this->getParam("toname"));
        $this->assign('toname', $toname);
        $this->display('wap/box_add.tpl');
    }

    /**
     * 发私信
     *
     */
    public function submitAction()
    {
        $name = $this->getParam("name");                //收信人微博帐号
        $content = trim($this->getParam("content"));        //微博内容
        if (!isset($content, $name))
        {
            Core_Fun::showmsg('丢失参数', -1); //返回上一页;
        }
        if (!Core_Comm_Validator::checkT($content))
        {
            Core_Fun::showmsg('内容不能为空', -1); //返回上一页;
        }
        if (!Core_Comm_Validator::isUserAccount($name))
        {
            Core_Fun::showmsg('请输入正确用户账户', -1); //返回上一页;
        }
        $clientIp = Core_Comm_Util::getClientIp();

        //检查目标用户是否存在
        try
        {
            $userInfo = Core_Open_Api::getClient()->getUserInfo(array("n" => $name));
            $userNameNick = $userInfo["data"]["nick"];
        }
        catch (exception $e)            //帐号不存在api也返回内部错误
        {
            Core_Fun::showmsg('此用户不存在，请输入你好友当前使用的微博帐号', -1); //返回上一页;
        }

        //检查对方是否是我的粉丝
        $checkRet = Core_Open_Api::getClient()->checkFriend(array("n" => $name, "type" => 0));
        $isfans = (bool) $checkRet["data"][$name];
        if ($isfans == false)
        {
            Core_Fun::showmsg('她还没有收听你，暂时不能发私信', -1); //返回上一页;
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
            Core_Fun::showmsg('私信发送失败', -1); //返回上一页;
        }
        empty($postRet['data']['id']) && Core_Fun::showmsg('私信发送失败', -1);

        $backurl = $this->getParam("backurl");
        empty($backurl) && $backurl = empty($_SERVER['HTTP_REFERER'])?-1:$_SERVER['HTTP_REFERER'];
        Core_Fun::showmsg('发送成功',$backurl,0);
    }

    /**
     * 删除私信
     *
     */
    public function delAction()
    {
        $tid = $this->getParam("tid");

        if (!isset($tid) || !Core_Comm_Validator::isTId($tid))
        {
            Core_Fun::showmsg('丢失消息id', -1); //返回上一页;
        }
        $delRet = Core_Open_Api::getClient()->delOne(array("id" => $tid));
        $backurl = $this->getParam('backurl');
        empty($backurl) && $backurl = empty($_SERVER['HTTP_REFERER'])?Core_Fun::getUrlroot().'wap':$_SERVER['HTTP_REFERER'];        
        Core_Fun::showmsg('', $backurl, 0); //返回上一页
    }

    /**
     * 收件箱
     *
     */
    public function inAction()
    {
        return $this->box(self::BOX_TYPE_INBOX);
    }

    /**
     * 发件箱
     *
     */
    public function outAction()
    {
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
        $num = Core_Comm_Validator::getNumArg($this->getParam("num"), 1, 20, 10);
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
        $boxInfoData['box'] = true;
        if (is_array($boxInfoData["info"]))        //设置头像大小
        {
            Core_Lib_Base::formatTArr($boxInfoData["info"], '50');
        }
        $this->assign('boxinfo', $boxInfoData);

        //上一页下一页
        $frontUrl = $nextUrl = "";
        $actionBox = ($type == 0 ? "out" : "in");

        $pageInfo = Core_Lib_Base::hasFrontNextPage($f, $boxInfoData["hasnext"]
                        , '/wap/box/' . $actionBox
                        , array()
                        , $boxInfoData["info"][0]["timestamp"]
                        , $boxInfoData["info"][(count($boxInfoData["info"]) - 1)]["timestamp"]
                        , $boxInfoData["info"][0]["id"]
                        , $boxInfoData["info"][(count($boxInfoData["info"]) - 1)]["id"]
        );
        $this->assign('pageinfo', $pageInfo);

        $data["screenuser"] = $this->userInfo;

        Core_Lib_Base::clearNewMsgInfo(7);
        try
        {
            $updateInfo = Core_Open_Api::getClient()->getUpdate(array("op" => 0));
        }
        catch (exception $e)
        {
            $this->error(Core_Comm_Modret::RET_DEFAULT_ERR);
        }
        $template = ($type == 0 ? "wap/box_out.tpl" : "wap/box_in.tpl");
        $this->display($template);
    }

}

?>
