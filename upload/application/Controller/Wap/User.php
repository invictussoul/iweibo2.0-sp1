<?php
/**
 * iweibo2.0
 * 
 * 个人资料控制器
 *
 * @author gionouyang
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright © 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Wap_Setting.php 2011-06-03 14:25:00Z gionouyang $
 * @package Controller
 * @since 2.0
 */
class Controller_Wap_User extends Core_Controller_WapAction
{

    /**
     * 显示个人资料
     * 
     */
    public function indexAction()
    {
        $name = $this->getParam("u");
        $memberModel = new Model_User_Member();
        $localUser = $memberModel->getUserInfoByName($name);
        //初始化open client
        $client = Core_Open_Api::getClient();
        //获取当前用户资料
        $myInfo = $client->getUserInfo(array("n" => $name));
        
        $user = Core_Lib_Base::formatU($myInfo["data"], 100);
        
        if($localUser)
        {
            $user['nick'] = $localUser['nickname'];
            $user['birth_month'] = $localUser['birthmonth'];
            $user['introduction'] = $localUser['summary'];
            $user['sex'] = $localUser['gender'];
        }
        
        $this->assign('user', $user);
        $this->display('wap/user.tpl');
    }

    /**
     * 个人设置
     * 
     */
    public function settingAction()
    {
        $this->display('wap/user_setting.tpl');
    }

    /**
     * 个人设置保存
     * 
     */
    public function saveAction()
    {
        $conditions['uid'] = $this->userInfo['uid'];
        $conditions['nickname'] = $this->getParam('nick');
        $conditions['gender'] = $this->getParam('sex');
        $conditions['birthmonth'] = $this->getParam('month');
        $conditions['birthday'] = $this->getParam('day');
        $conditions['summary'] = $this->getParam('introduction');
        
        $memberModel = new Model_User_Member();
        //将个人信息更新到数据库
        if($memberModel->editUserInfo($conditions))
        {
            $this->showmsg('', 'wap/user/index/u/' . $this->userInfo['name'], 0);
        }
        else
        {
            $this->showmsg('', 'wap/user/setting', 0);
        }
    
    }

    /**
     * 名人列表
     * 
     */
    public function hotAction()
    {
        //获取认证用户
        $userModel = new Model_User_Member();
        $userlist = $userModel->getUserList(array(array('localauth', 1)), null, array(10, 0 * 10));
        $names = array();  
        foreach($userlist as $key => &$value)
        {
            array_push($names, $value["name"]);
        }
        $userlist = Model_User_Util::getInfos($names);

        $this->assign('userlist', $userlist);

        //获取名人推荐
        $model = new Model_Viprecommend();
        $hotUser = $model->__toData();

        $this->assign('hotuser', $hotUser);
        
        $this->display('wap/user_hot.tpl');
    }
}