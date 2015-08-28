<?php

/**
 * iweibo2.0
 * 
 * 微博好友模块
 *
 * @author echoyang 
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright ? 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Friend.php 2011/5/20
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Friend extends Core_Controller_TAction
{
    const FRIEND_TYPE_FANS = 0;
    const FRIEND_TYPE_IDOL = 1;

    /**
     * 收听/取消收听某个用户
     *
     */
    public function followAction()
    {

        //目标用户微博帐号 本地关系链时候此字段为好友id，open关系链时候为好友name
        $fname = $this->getParam('name');
        empty($fname) && $this->exitJson(Core_Comm_Modret::RET_MISS_ARG, '用户账户不能为空');
        ($fname == $this->userInfo['name']) && $this->exitJson(Core_Comm_Modret::RET_MISS_ARG, '不能收听自己');
        //type: 0 取消收听,1 收听 
        $type = Core_Comm_Validator::getNumArg($this->getParam('type'), 0, 1);
        $typeOption = $type ? '收听' : '取消收听';

        //调用接口model
        $friendObj = Model_Friend::singleton();
        $followFriend = $friendObj->followFriend($this->userInfo, $fname, $type); //收听/取消收听某个用户

        $followFriend || $this->exitJson(Core_Comm_Modret::RET_API_INNER_ERR, $typeOption . '失败');

        //统计代码
        if ($type)
        {
            try
            {
                Model_Stat::addStat('addfans');
            }
            catch (Exception $e)
            {
                //pass
            }
        }

        $this->exitJson(Core_Comm_Modret::RET_SUCC);
    }

    /**
     * 我的/他的听众
     * @
     */
    public function followerAction()
    {
        //主栏组件
        $this->assign('mainComponent', Model_Componentprocessunit::getComponentWithHtml(7, 'main'));
        //右栏组件
        $this->assign('rightComponent', Model_Componentprocessunit::getComponentWithHtml(7, 'right'));

        $uname = $this->getParam('uname');
        if (empty($uname) || $uname == $this->userInfo['name'])
        {        //我的听众
            $uname = '';
            $template = 'friend/user_fanslist.tpl';
            Core_Lib_Base::clearNewMsgInfo(8);//我的听众页时候 清空更新
        }
        else
        {                    //他的听众
            if (!Core_Comm_Validator::isUserAccount($uname))
            {
                $this->error(Core_Comm_Modret::RET_ARG_ERR, '用户名格式错误');
            }
            $template = 'friend/guest_fanslist.tpl';
        }
        $this->getFriend(self::FRIEND_TYPE_FANS, $template, $uname);
    }

    /**
     * 我的/他的收听
     * @
     */
    public function followingAction()
    {
        //主栏组件
        $this->assign('mainComponent', Model_Componentprocessunit::getComponentWithHtml(7, 'main'));
        //右栏组件
        $this->assign('rightComponent', Model_Componentprocessunit::getComponentWithHtml(7, 'right'));

        $uname = $this->getParam('uname');
        if (empty($uname) || $uname == $this->userInfo['name'])
        {        //我的收听
            $uname = '';
            $template = 'friend/user_idollist.tpl';
        }
        else
        {                    //他的收听
            if (!Core_Comm_Validator::isUserAccount($uname))
            {
                $this->error(Core_Comm_Modret::RET_ARG_ERR, '用户名格式错误');
            }
            $template = 'friend/guest_idollist.tpl';
        }
        $this->getFriend(self::FRIEND_TYPE_IDOL, $template, $uname);
    }

    /**
     * 获取open关系链
     * @param $type    0 听众 1 偶像
     * @param $name    用户名 空表示本人
     * @param $title
     * @param $template
     * @
     */
    protected function getFriend($type, $template, $name)
    {
        $pos = Core_Comm_Validator::getNumArg($this->getParam('startindex'), 0, PHP_INT_MAX, 0);
        $num = Core_Comm_Validator::getNumArg($this->getParam('num'), 1, 30, 15);

        //调用接口model
        $friendObj = Model_Friend::singleton();

        //获取显示用户资料
        if ($name)
        {//guest

            $preUrl = $type ? '/friend/following/uname/' . $name : '/friend/follower/uname/' . $name;

            $userInfo = Model_User_Util::getFullInfo($name);
            $userInfo['head'] = Core_Lib_Base::formatHead($userInfo['head'],120);            
            $this->assign('guest', $userInfo);
            //拉取偶像列表, 只拉13个
            $p = array("n" => $name, "num" => 12, "start" => 0, "type" => 1);
            $idols = $friendObj->getMyfriend($p);

            $idollist = $idols["data"]["info"];

            foreach ($idollist as &$u)
            {
                $u['head'] = Core_Lib_Base::formatHead($u['head'], 50);
            }
            $this->assign('idollist', $idollist);

            //个人认证设置
            $local = Core_Config::get('localauth', 'certification.info');
            $platform = Core_Config::get('platformauth', 'certification.info');
            $localtext = Core_Config::get('localauthtext', 'certification.info');

            $authInfo = array('local' => $local, 'platform' => $platform, 'localtext' => $localtext);
            $this->assign('auth', $authInfo);

        }
        else
        {//me
            $preUrl = $type ? '/friend/following' : '/friend/follower';
            $userInfo = $this->userInfo;
        }


        $this->assign('userurl', Core_Fun::getUserShorturl($userInfo['name']));

        //获取听众信息
        $p = array(
            'n' => $name                //用户名 空表示本人
            , 'num' => $num            //请求个数(1-30)
            , 'start' => $pos        //起始位置
            , 'type' => $type        //0 听众 1 偶像
        );

        //获取我的好友
        try
        {
            $userRet = $friendObj->getMyfriend($p);//传第二个为true时候,本地关系链时的好友会返回有tag的好友信息。默认本地关系链时的好友不返回tag
            if(is_array($userRet['data']['info']))
            {
                foreach ($userRet['data']['info'] AS &$v)
                {
                    $v['head'] = Core_Lib_Base::formatHead($v['head']);
                }
            }
        }
        catch (Core_Api_Exception $e)
        {//没有好友
            $userRet = array();
        }

        //上一页下一页
        $frontUrl = $nextUrl = '';
        if (isset($userRet['data']['hasnext']) && $userRet['data']['hasnext'] === 0)
        {
            $nextUrl = Core_Fun::getParaUrl($preUrl, array("startindex" => $pos + $num));
        }
        if ($pos > 0)
        {
            $pos = $pos - $num;
            if ($pos < 0)
            {
                $pos = 0;
            }
            $frontUrl = Core_Fun::getParaUrl($preUrl, array('startindex' => $pos));
        }
        $pageInfo = array('fronturl' => $frontUrl, 'nexturl' => $nextUrl);
        $this->assign('pageinfo', $pageInfo);

        //用户数量
        $unum = ($type == 0 ? $userInfo['fansnum'] : $userInfo['idolnum']);
        $this->assign('unum', $unum);

        $this->assign('friends', $userRet['data']['info']);
        $this->display($template);
    }

    /**
     * 检测是否我粉丝或偶像
     * @
     */
    public function checkfAction()
    {
        $name = $this->getParam('name');
        $type = $this->getParam('type'); //type:   0 检测听众，1检测收听的人 2 两种关系都检测

        if (empty($name))
        {
            $this->exitJson(Core_Comm_Modret::RET_MISS_ARG, '用户名为空');
        }

        if (!in_array($type, array(0, 1, 2)))
        {
            $this->exitJson(Core_Comm_Modret::RET_DEFAULT_ERR, '检测类型错误');
        }

        //调用friend model
        $friendObj = Model_Friend::singleton();

        //检查是否是好友
        try
        {
            $checkUser = $friendObj->checkFriend($name, $type);
        }
        catch (Core_Api_Exception $e)
        {
            $checkUser = array();
        }
        //返回成功
        $this->exitJson(Core_Comm_Modret::RET_SUCC, '', $checkUser);
    }


    /**
     * 检测用户是否存在
     * @
     */    
    public function checkuAction()
    {
        //type: 0 检测粉丝，1检测偶像
        $type = $this->getParam('type');
        $name = $this->getParam('name');

        if (!in_array($type, array(1, 0)))
        {
            $this->exitJson(Core_Comm_Modret::RET_MISS_ARG, '检测类型为空');
        }

        if (empty($name))
        {
            $this->exitJson(Core_Comm_Modret::RET_MISS_ARG, '用户名为空');
        }
        if (!Core_Comm_Validator::isUserAccount($name))
        {        //目标用户微博帐号
            $this->exitJson(Core_Comm_Modret::RET_DEFAULT_ERR, '此用户格式错误');
        }

        //调用friend model
        $friendObj = Model_Friend::singleton();

        //检查用户存在与否
        $isUser = $friendObj->checkUser($name);
        $isUser || $this->exitJson(Core_Comm_Modret::RET_DEFAULT_ERR, '此用户不存在');

        //返回成功
        $this->exitJson(Core_Comm_Modret::RET_SUCC);
    }

}
