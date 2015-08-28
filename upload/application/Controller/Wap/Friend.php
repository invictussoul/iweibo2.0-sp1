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
 * @version $Id: Controller_Wap_Friend.php 2011/5/20
 * @package Controller
 * @since 2.0
 */
class Controller_Wap_Friend extends Core_Controller_WapAction
{
    const FRIEND_TYPE_FANS = 0;
    const FRIEND_TYPE_IDOL = 1;

    /**
     * 默认
     *
     */
    public function indexAction()
    {
        $this->fansAction();
    }

    /**
     * 收听/取消收听某个用户
     *
     */
    public function followAction()
    {
        //type: 0 取消收听,1 收听 
        $type = Core_Comm_Validator::getNumArg($this->getParam('type'), 0, 1);
        $typeOption = $type ? '收听' : '取消收听';

        $backurl = $this->getParam('backurl');
        empty($backurl) && $backurl = empty($_SERVER['HTTP_REFERER'])?Core_Fun::getUrlroot().'wap':$_SERVER['HTTP_REFERER'];

        //目标用户微博帐号
        $fname = $this->getParam('name');
        empty($fname) && Core_Fun::showmsg('用户账户不能为空', -1); //返回上一页;
        $fname == $this->userInfo['name'] && Core_Fun::showmsg('不能对自己进行收听操作', $backurl); //返回上一页;
        //调用接口model
        $friendObj = Model_Friend::singleton();
        $followFriend = $friendObj->followFriend($this->userInfo, $fname, $type); //收听/取消收听某个用户
        $followFriend || Core_Fun::showmsg($typeOption . '失败', $backurl);
        
        Core_Fun::showmsg('', $backurl, 0); //返回上一页
    }

    /**
     * 我的听众
     * @
     */
    public function fansAction()
    {

        $uname = $this->getParam('uname');
        if (empty($uname) || $uname == $this->userInfo['name'])        //我的听众
        {
            $uname = '';
        }
        else                    //他的听众
        {
            if (!Core_Comm_Validator::isUserAccount($uname))
            {
                Core_Fun::showmsg('用户名格式错误', -1); //返回上一页;
            }
        }
        $template = 'wap/user_fans.tpl';
        $this->getFriend(self::FRIEND_TYPE_FANS, $template, $uname);
    }

    /**
     * 我的收听
     * @
     */
    public function idolAction()
    {

        $uname = $this->getParam('uname');
        if (empty($uname) || $uname == $this->userInfo['name'])        //我的收听
        {
            $uname = '';
        }
        else                    //他的收听
        {
            if (!Core_Comm_Validator::isUserAccount($uname))
            {
                Core_Fun::showmsg('用户名格式错误', -1); //返回上一页;
            }
        }

        $template = 'wap/user_idol.tpl';
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
        $num = Core_Comm_Validator::getNumArg($this->getParam('num'), 1, 30, 10);

        //调用接口model
        $friendObj = Model_Friend::singleton();

        //获取显示用户资料
        if ($name)//guest
        {
            $userInfo = Core_Open_Api::getClient()->getUserInfo(array('n' => $name));
            $userInfo = Core_Lib_Base::formatU($userInfo['data'], 50);
            $preUrl = $type ? '/wap/friend/idol/uname/' . $name : '/wap/friend/fans/uname/' . $name;

            $memberObj = new Model_User_Member; //获取用户id
            $userInfo['uid'] = $memberObj->getUidByName($name);
            $userInfo['pagehost'] = $userInfo['nick'];
        }
        else
        {//me
            $userInfo = Core_Open_Api::getClient()->getUserInfo();
            $userInfo = Core_Lib_Base::formatU($userInfo['data'], 50);
            $preUrl = $type ? '/wap/friend/idol' : '/wap/friend/fans';
            $userInfo['uid'] = $this->userInfo['uid'];
            $userInfo['pagehost'] = '我';

            //我的听众页时候 清空更新
            $type == 0 && $friendObj->cleanFansNum($userInfo['uid']);
        }

        $this->assign('userinfo', $userInfo);
        $this->assign('userurl', Core_Fun::getUserShorturl($userInfo['name']));

        //获取听众信息
        $p = array(
            'n' => $name                //用户名 空表示本人
            , 'num' => $num            //请求个数(1-30)
            , 'start' => $pos        //起始位置
            , 'type' => $type        //0 听众 1 偶像
        );

        //获取我的好友
        $userRet = $friendObj->getMyfriend($p);

        //上一页下一页
        $frontUrl = $nextUrl = '';
        if ($userRet['data']['hasnext'] === 0)
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


        $this->assign('user', $userRet['data']['info']);
        $this->display($template);
    }

}
