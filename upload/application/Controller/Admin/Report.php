<?php

/**
 * 举报管理
 *
 * @author Gavin <yaojungang@comsenz.com>
 */
class Controller_Admin_Report extends Core_Controller_Action
{

    private $_reportObj;
    private $_maskObj;

    public function __construct ($params)
    {
        parent::__construct ($params);
        $this->_reportObj = new Model_Report();
        $this->_maskObj = new Model_Mask();
    }

    /**
     * 列表
     */
    public function indexAction ()
    {
        if ($this->getParam ("id")) {
            $_ids = (array)$this->getParam ('id');
            //标记已处理
            if ($this->getParam ('make_checked_all')) {
                $this->_reportObj->makeChecked ($_ids);
                $this->showmsg ("标记已处理操作成功");
            }
            //删除
            if ($this->getParam ('delete_all')) {
                $this->_reportObj->deleteReport ($_ids);
                $this->showmsg ("删除成功");
            }
        }
        //查询条件
        $whereArr = array ();
        $conditions = array ();
        if ($this->getSafeParam ('keyword')) {
            $whereArr[] = array ('reason', $this->getSafeParam ('keyword'), 'like');
            $conditions['keyword'] = $this->getSafeParam ('keyword');
            $this->assign ("keyword", $this->getSafeParam ('keyword'));
        }
        if ($this->getSafeParam ('username')) {
            $whereArr[] = array ('username', $this->getSafeParam ('username'), 'like');
            $conditions['username'] = $this->getSafeParam ('username');
            $this->assign ("username", $this->getSafeParam ('username'));
        }
        if (strlen ($this->getParam ('state')) > 0) {
            $whereArr[] = array ('state', $this->getParam ('state'));
            $conditions['state'] = $this->getParam ('state');
            $this->assign ("state", $this->getParam ('state'));
        }
        if ($this->getParam ('starttime') && $this->getParam ('endtime')) {
            $_starttime = strtotime ($this->getParam ('starttime'));
            $_endtime = strtotime ($this->getParam ('endtime'));
            $whereArr[] = array ('dateline', $_starttime, '>=');
            $whereArr[] = array ('dateline', $_endtime, '<=');
            $conditions['starttime'] = $this->getParam ('starttime');
            $conditions['endtime'] = $this->getParam ('endtime');
            $this->assign ("starttime", $this->getParam ('starttime'));
            $this->assign ("endtime", $this->getParam ('endtime'));
        }
       //排序
        if ($this->getParam ('orderby') && $this->getParam ('ordersc')) {
            $_orderbyItem = $this->getParam ('orderby');
            $_orderbyRsc = $this->getParam ('ordersc');
            $_orderbyItem = in_array ($_orderbyItem, array ('dateline','account')) ? $_orderbyItem : null;
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
            $_orderby = "id desc";
        }
        //总数量
        $reportCount = $this->_reportObj->getCount ($whereArr);
        $this->assign ('reportCount', $reportCount);
        //分页
        $perpage = Core_Config::get ('page_size', 'basic', 20);
        $curpage = $this->getParam ('page') ? intval ($this->getParam ('page')) : 1;
        $_c = Core_Comm_Util::map2str ($conditions, '/', '/');
        $_c = empty ($_c) ? '' : '/' . $_c;
        $mpurl = '/admin/report/index' . $_c . '/';
        $multipage = $this->multipage ($reportCount, $perpage, $curpage, $mpurl);

        $this->assign ('multipage', $multipage);
        $reports = $this->_reportObj->queryAll ($whereArr, $_orderby, array ($perpage, $perpage * ($curpage - 1)));
        $this->assign ('report_state', $this->_reportObj->STATE);
        $this->assign ('report_type', $this->_reportObj->TYPE);
        $this->assign ('reports', $reports);
        $this->display ('admin/report_index.tpl');
    }

}