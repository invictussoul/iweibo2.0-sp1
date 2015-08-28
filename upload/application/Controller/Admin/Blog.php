<?php

/**
 * 微博管理
 *
 * @author Gavin <yaojungang@comsenz.com>
 */
class Controller_Admin_Blog extends Core_Controller_Action
{

    private $_blogObj;
    private $_maskObj;

    public function __construct ($params)
    {
        parent::__construct ($params);
        $this->_blogObj = new Model_Blog();
        $this->_maskObj = new Model_Mask();
    }

    /**
     * 列表
     */
    public function indexAction ()
    {
        if ($this->getParam ("opentid")) {
            $_opentids = (array)$this->getParam ('opentid');
            //屏蔽
            if ($this->getParam ("maskall")) {
                $this->_blogObj->maskBlog ($_opentids);
                $this->_maskObj->addMask ($_opentids);
                $this->showmsg ("屏蔽操作成功");
            }
            //取消屏蔽
            if ($this->getParam ("unmaskall")) {
                $this->_blogObj->unmaskBlog ($_opentids);
                $this->_maskObj->deleteMask ($_opentids);
                $this->showmsg ("取消屏蔽操作成功");
            }
        }
        //查询条件
        $whereArr = array ();
        $conditions = array ();
        if ($this->getSafeParam ('keyword')) {
            $whereArr[] = array ('txt', $this->getSafeParam ('keyword'), 'like');
            $conditions['keyword'] = $this->getSafeParam ('keyword');
            $this->assign ("keyword", $this->getSafeParam ('keyword'));
        }
        if ($this->getSafeParam ('nickname')) {
            $whereArr[] = array ('account', $this->getSafeParam ('nickname'), 'like');
            $conditions['nickname'] = $this->getSafeParam ('nickname');
            $this->assign ("nickname", $this->getSafeParam ('nickname'));
        }
        if (strlen ($this->getSafeParam ('visible')) > 0) {
            $whereArr[] = array ('visible', $this->getSafeParam ('visible'));
            $conditions['visible'] = $this->getSafeParam ('visible');
            $this->assign ("visible", $this->getSafeParam ('visible'));
        }
        if (strlen ($this->getParam ('type')) > 0) {
            $whereArr[] = array ('type', $this->getParam ('type'));
            $conditions['type'] = $this->getParam ('type');
            $this->assign ("type", $this->getParam ('type'));
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
        $blogCount = $this->_blogObj->getCount ($whereArr);
        $this->assign ('blogCount', $blogCount);
        //分页
        $perpage = Core_Config::get ('page_size', 'basic', 20);
        $curpage = $this->getParam ('page') ? intval ($this->getParam ('page')) : 1;
        $_c = Core_Comm_Util::map2str ($conditions, '/', '/');
        $_c = empty ($_c) ? '' : '/' . $_c;
        $mpurl = '/admin/blog/index' . $_c . '/';
        $multipage = $this->multipage ($blogCount, $perpage, $curpage, $mpurl);

        $this->assign ('multipage', $multipage);
        $blogs = $this->_blogObj->queryAll ($whereArr, $_orderby, array ($perpage, $perpage * ($curpage - 1)));
        $this->assign ('blogs', $blogs);
        $this->assign ('blog_state', $this->_blogObj->STATE);
        $this->assign ('blog_type', $this->_blogObj->TYPE);
        $this->display ('admin/blog_index.tpl');
    }

    /**
     * 微博审核
     */
    public function censorAction ()
    {
        if ($this->getParam ("opentid")) {
            $_opentids = (array)$this->getParam ('opentid');
            //屏蔽
            if ($this->getParam ("maskall")) {
                $this->_blogObj->maskBlog ($_opentids);
                $this->_maskObj->addMask ($_opentids);
                $this->showmsg ("屏蔽操作成功");
            }
            //取消屏蔽
            if ($this->getParam ("unmaskall")) {
                $this->_blogObj->unmaskBlog ($_opentids);
                $this->_maskObj->deleteMask ($_opentids);
                $this->showmsg ("取消屏蔽操作成功");
            }
        }
        //查询条件
        $whereArr = array ();
        $conditions = array ();
        if ($this->getSafeParam ('keyword')) {
            $whereArr[] = array ('content', $this->getSafeParam ('keyword'), 'like');
            $conditions['keyword'] = $this->getSafeParam ('keyword');
            $this->assign ("keyword", $this->getSafeParam ('keyword'));
        }
        if ($this->getSafeParam ('nickname')) {
            $whereArr[] = array ('account', $this->getSafeParam ('nickname'), 'like');
            $conditions['nickname'] = $this->getSafeParam ('nickname');
            $this->assign ("nickname", $this->getSafeParam ('nickname'));
        }
        if (strlen ($this->getParam ('type')) > 0) {
            $whereArr[] = array ('type', $this->getParam ('type'));
            $conditions['type'] = $this->getParam ('type');
            $this->assign ("type", $this->getParam ('type'));
        }

        //审核状态
        $_state = strlen ($this->getParam ('state')) > 0 ? intval ($this->getParam ('state')) : 0;
        $whereArr[] = array ('state', $_state);
        $conditions['state'] = $_state;
        $this->assign ("state", $_state);

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
        $blogCount = $this->_blogObj->getCount ($whereArr);
        $this->assign ('blogCount', $blogCount);
        //分页
        $perpage = Core_Config::get ('page_size', 'basic', 20);
        $curpage = $this->getParam ('page') ? intval ($this->getParam ('page')) : 1;
        $_c = Core_Comm_Util::map2str ($conditions, '/', '/');
        $_c = empty ($_c) ? '' : '/' . $_c;
        $mpurl = '/admin/blog/censor' . $_c . '/';
        $multipage = $this->multipage ($blogCount, $perpage, $curpage, $mpurl);

        $this->assign ('multipage', $multipage);
        $blogs = $this->_blogObj->queryAll ($whereArr, $_orderby, array ($perpage, $perpage * ($curpage - 1)));
        $this->assign ('blogs', $blogs);
        $this->assign ('blog_state', $this->_blogObj->STATE);
        $this->assign ('blog_type', $this->_blogObj->TYPE);
        $this->display ('admin/blog_censor.tpl');
    }

}