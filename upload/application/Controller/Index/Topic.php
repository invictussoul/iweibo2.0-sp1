<?php

/**
 * iweibo2.0
 * 
 * 微博话题模块
 *
 * @author echoyang 
 * @link http://open.t.qq.com/apps/iweibo/
 * @copyright Copyright ? 1998-2011. All Rights Reserved 
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Controller_Index_Topic.php 2011/5/26
 * @package Controller
 * @since 2.0
 */
class Controller_Index_Topic extends Core_Controller_TAction
{

    //Controller 构造函数
    public function preDispatch()
    {
        parent::preDispatch();
        $this->assign('active', 'topic');
    }

    /**
     * 话题主页
     *
     */
    public function indexAction()
    {
        //模版数据
        $data = array(
            "title" => "话题墙"
        );

        //主栏组件
        $this->assign('mainComponent', Model_Componentprocessunit::getComponentWithHtml(8, 'main'));
        //右栏组件
        $this->assign('rightComponent', Model_Componentprocessunit::getComponentWithHtml(8, 'right'));

        $this->assign('data', $data);
        $this->display('topic/index.tpl');
    }

    /**
     * 话题页
     *
     */
    public function showAction()
    {
        $f = Core_Comm_Validator::getNumArg($this->getParam('f'), 0, 4, 0);
        $num = Core_Comm_Validator::getNumArg($this->getParam('num'), 1, 20, 20);
        $pageInfo = $this->getParam('pageinfo');
        $topicName = $this->getParam('k');

        //检测话题名称正确性
        if (!Core_Comm_Validator::isTopicName($topicName))
        {
            $this->error(Core_Comm_Modret::RET_ARG_ERR, '话题名称不正确');
        }

        //检测话题是否被屏蔽
        if (Model_Topic::isMasked($topicName))
        {
            $this->error(Core_Comm_Modret::RET_ARG_ERR, '话题被屏蔽');
        }

        $preUrl = '/topic/show/k/' . Core_Fun::iurlencode($topicName);

        $p = array(
            't' => $topicName, //话题名
            'f' => $f, //分页标识（0：第一页，1：向下翻页，2向上翻页，3最后一页，4最前一页）
            'n' => $num, //每次请求记录的条数（1-20条）
            'p' => $pageInfo     //分页标识（第一页 填空，继续翻页：根据返回的 pageinfo决定）
        );
        try
        {
            $msg = Core_Open_Api::getClient()->getTopic($p);
            Core_Lib_Base::formatTArr($msg["data"]["info"]);
            $this->assign('msglist', $msg["data"]["info"]); //数据
        }
        catch (Core_Api_Exception $e)
        {
            $msg["data"]["totalnum"] = 0;
            //$this->error(Core_Comm_Modret::RET_TOPIC_ERR);
        }

        //上一页下一页
        $frontUrl = $nextUrl = "";
        $hasNext = isset($msg["data"]["hasnext"])?$msg["data"]["hasnext"]:''; //2表示不能往上翻 1 表示不能往下翻，0表示两边都可以翻
        if ($hasNext === 2)
        {

            $nextUrl = Core_Fun::getParaUrl($preUrl, array("f" => 1, "pageinfo" => $msg["data"]["pageinfo"]));
        }
        elseif ($hasNext === 1)
        {
            $frontUrl = Core_Fun::getParaUrl($preUrl, array("f" => 2, "pageinfo" => $msg["data"]["pageinfo"]));
        }
        elseif ($hasNext === 0)
        {
            $nextUrl = Core_Fun::getParaUrl($preUrl, array("f" => 1, "pageinfo" => $msg["data"]["pageinfo"]));
            $frontUrl = Core_Fun::getParaUrl($preUrl, array("f" => 2, "pageinfo" => $msg["data"]["pageinfo"]));
        }
        $pageInfo = array("fronturl" => $frontUrl, "nexturl" => $nextUrl);
        $this->assign('pageinfo', $pageInfo); //分页
        
        //模版数据
        $data = array(
            'title' => '和#' . htmlspecialchars($topicName) . '#话题相关的微博'
            , 'count' => $msg["data"]["totalnum"]//转播评论总数
        );
        $model = new Model_Hottopic;
        $hotlist = $model->getHotTopicByName($topicName);
        $hotlist['picture2'] ? $data['head'] = $hotlist['picture2'] : $data['head'] = '/resource/images/default_head_100.jpg';
        $data['introduction'] = $hotlist['description'];
        $this->assign('data', $data);
        $topicName = empty($topicName)?'':'#' . $topicName . '#';
        $this->assign('defaulttext',$topicName );
        $this->display('topic/show.tpl');
    }

}