<?php

/**
 * iweibo2.0
 * 
 * 微博tag模块
 *
 * @author echoyang 
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright ? 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Tag.php 2011/5/29
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Tag extends Core_Controller_TAction {

    /**
     * @tag首页
     * @param 
     * @return 
     * @author echoyang
     * @time 2011/5/29
     */
    public function indexAction() {
        //用户标签数据
        $tagObj = Model_Tag::singleton();
        $userTag = $tagObj->getTag($this->userInfo['name']); //查找本人的有效tag
        $this->assign('usertag', $userTag);
        //模板选项卡
        $tabArr = array(array('url' => '/setting', 'title' => '个人资料'), array('url' => '/tag', 'title' => '个人标签'));
        $tabbar = Core_Lib_Base::formatTab($tabArr, 1);
        $this->assign('tabbar', $tabbar);

        //显示模板
        $this->display('user/tag.tpl');
    }

    /**
     * @添加
     * @param sting
     * @return 
     * @author echoyang
     * @time 2011/5/29
     */
    public function addAction() {
        $tagObj = Model_Tag::singleton();
        $tagName = trim($this->getParam('tagname'));
        $userName = $this->userInfo['name'];
        empty($userName) && $this->exitJson(Core_Comm_Modret::RET_U_UNLOGIN);
        empty($tagName) && $this->exitJson(Core_Comm_Modret::RET_MISS_ARG, '不能添加空标签');

        //todo 检查标签长度在8个汉字或者24字节内
        //检查标签
        $_filter = Model_Filter::checkContent($tagName); //敏感词检查
        $_filter && $this->exitJson(Core_Comm_Modret::RET_TAG_UNFILTER);

        try {
            $ret = $tagObj->addTag($tagName, $userName);
        } catch (Core_Api_Exception $e) {
            $this->exitJson($e->getCode(), $e->getMessage());
        } catch (Core_Db_Exception $e) {
            $this->exitJson(Core_Comm_Modret::RET_DB_EXCEPTION);
        }

        if (empty($ret) || empty($ret['data'])) {
            $this->exitJson(Core_Comm_Modret::RET_DATA_EXCEPTION);
        }

        //系统标签数加1
        isset($ret['data']['tagname']) && Model_Mb_Tag::updateTagUsenum($ret['data']['tagname'], 1);

        //统计代码
        try {
            Model_Stat::addStat('taguse');
        } catch (Exception $e) {
            //pass
        }


        $this->exitJson(Core_Comm_Modret::RET_SUCC, '', $ret['data']);
    }

    /**
     * @添加
     * @param sting
     * @return 
     * @author echoyang
     * @time 2011/5/29
     */
    public function delAction() {
        //Model_User_TagLocal::delTagByName('啊啊');
        $tagObj = Model_Tag::singleton();
        $tagId = $this->getParam('tagid');
        empty($tagId) && $this->exitJson(Core_Comm_Modret::RET_MISS_ARG);
        try {
            $ret = $tagObj->delTag($tagId);
        } catch (Core_Api_Exception $e) {
            $this->exitJson($e->getCode(), $e->getMessage());
        } catch (Core_Db_Exception $e) {
            $this->exitJson(Core_Comm_Modret::RET_DB_EXCEPTION);
        }

        try {//本地化标签 会返回 tagname 系统标签数-1 
            !empty($ret['data']['tagname']) && Model_Mb_Tag::updateTagUsenum($ret['data']['tagname'], -1);
        } catch (Exception $e) {
            
        }

        $this->exitJson(Core_Comm_Modret::RET_SUCC, '', $ret['data']);
    }

}