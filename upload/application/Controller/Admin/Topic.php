<?php

/**
 * 话题管理
 *
 * @author Gavin <yaojungang@comsenz.com>
 */
class Controller_Admin_Topic extends Core_Controller_Action
{

    private $_topicObj;

    public function __construct ($params)
    {
        parent::__construct ($params);
        $this->_topicObj = new Model_Topic();
    }

    /**
     * 列表
     */
    public function indexAction ()
    {
        if ($this->getParam ("tid")) {
            $_tids = (array)$this->getParam ('tid');
            //开放
            if ($this->getParam ("unlockall")) {
                $this->_topicObj->unLockTopic ($_tids);
                $this->showmsg ("开放话题操作成功");
            }
            //锁定
            if ($this->getParam ("lockall")) {
                $this->_topicObj->lockTopic ($_tids);
                $this->showmsg ("锁定话题操作成功");
            }
        }
        //查询条件
        $whereArr = array ();
        $conditions = array ();
        if ($this->getSafeParam ('keyword')) {
            $whereArr[] = array ('title', $this->getSafeParam ('keyword'), 'like');
            $conditions['keyword'] = $this->getSafeParam ('keyword');
            $this->assign ("keyword", $this->getSafeParam ('keyword'));
        }
        if (strlen ($this->getParam ('state')) > 0) {
            $whereArr[] = array ('state', $this->getParam ('state'));
            $conditions['state'] = $this->getParam ('state');
            $this->assign ("state", $this->getParam ('state'));
        }
       //排序
        if ($this->getParam ('orderby') && $this->getParam ('ordersc')) {
            $_orderbyItem = $this->getParam ('orderby');
            $_orderbyRsc = $this->getParam ('ordersc');
            $_orderbyItem = in_array ($_orderbyItem, array ('mblogs','title')) ? $_orderbyItem : null;
            $_orderbyRsc = in_array ($_orderbyRsc, array ('asc', 'desc')) ? $_orderbyRsc : null;
            if ($_orderbyItem && $_orderbyRsc) {
                $_orderby = $_orderbyItem . ' ' . $_orderbyRsc;
            }
            $conditions['orderby'] = $_orderbyItem;
            $conditions['ordersc'] = $_orderbyRsc;
            $this->assign ("orderby", $_orderbyItem);
            $this->assign ("ordersc", $_orderbyRsc);
        }
        if (!$_orderby) {
            $_orderby = "tid desc";
        }
        //总数量
        $topicCount = $this->_topicObj->getCount ($whereArr);
        //分页
        $perpage = Core_Config::get ('page_size', 'basic', 20);
        $curpage = $this->getParam ('page') ? intval ($this->getParam ('page')) : 1;
        $_c = Core_Comm_Util::map2str ($conditions, '/', '/');
        $_c = empty ($_c) ? '' : '/' . $_c;
        $mpurl = '/admin/topic/index' . $_c . '/';
        $multipage = $this->multipage ($topicCount, $perpage, $curpage, $mpurl);

        $this->assign ('multipage', $multipage);
        $topics = $this->_topicObj->queryAll ($whereArr, $_orderby, array ($perpage, $perpage * ($curpage - 1)));
        $this->assign ('topics', $topics);
        $this->display ('admin/topic_index.tpl');
    }

}