<?php

/**
 * 上墙管理
 *
 * @author Gavin <yaojungang@comsenz.com>
 */
class Controller_Admin_Wall extends Core_Controller_Action
{

    private $_topicObj;
    private $_blogObj;

    public function __construct ($params)
    {
        parent::__construct ($params);
        $this->_topicObj = new Model_Topic();
        $this->_blogObj = new Model_Blog();
    }

    /**
     * 上墙话题列表
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
        //只显示上墙话题
        $whereArr[] = array ('wall', 1, '=');
        if ($this->getSafeParam ('keyword')) {
            $whereArr[] = array ('title', $this->getSafeParam ('keyword'), 'like');
            $conditions['keyword'] = $this->getSafeParam ('keyword');
            $this->assign ("keyword", $this->getSafeParam ('keyword'));
        }
        //排序
        if ($this->getParam ('orderby') && $this->getParam ('ordersc')) {
            $_orderbyItem = $this->getParam ('orderby');
            $_orderbyRsc = $this->getParam ('ordersc');
            $_orderbyItem = in_array ($_orderbyItem, array ('wallstarttime', 'wallendtime')) ? $_orderbyItem : null;
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
        $mpurl = '/admin/wall/index' . $_c . '/';
        $multipage = $this->multipage ($topicCount, $perpage, $curpage, $mpurl);

        $this->assign ('multipage', $multipage);
        $topics = $this->_topicObj->queryAll ($whereArr, $_orderby, array ($perpage, $perpage * ($curpage - 1)));
        $this->assign ('topics', $topics);
        $this->display ('admin/wall_index.tpl');
    }

    /**
     * 审核上墙消息列表
     */
    public function censorAction ()
    {
        if ($this->getParam ("opentid")) {
            $_opentids = (array)$this->getParam ('opentid');
            //通过
            if ($this->getParam ("unmaskall")) {
                $this->_blogObj->unLockWallBlog ($_opentids);
                $this->showmsg ("开放消息操作成功");
            }

            //屏蔽
            if ($this->getParam ("maskall")) {
                $this->_blogObj->lockWallBlog ($_opentids);
                $this->showmsg ("屏蔽消息操作成功");
            }
        }

        if ($this->getSafeParam ('keyword')) {
            $_where = 'WHERE topic.title LIKE \'%' . $this->getSafeParam ('keyword') . '%\'';
            $conditions['keyword'] = $this->getSafeParam ('keyword');
            $this->assign ("keyword", $this->getSafeParam ('keyword'));
        } else {
            $_where = 'WHERE 1=1';
        }
        $_orderby = ' ORDER BY `id` DESC';
        //总数量
        $bCount = $this->_blogObj->getBlogByTopicTitleCount ($_where);
        //分页
        $perpage = Core_Config::get ('page_size', 'basic', 20);
        $curpage = $this->getParam ('page') ? intval ($this->getParam ('page')) : 1;
        $_c = Core_Comm_Util::map2str ($conditions, '/', '/');
        $_c = empty ($_c) ? '' : '/' . $_c;
        $mpurl = '/admin/wall/censor' . $_c . '/';
        $multipage = $this->multipage ($bCount, $perpage, $curpage, $mpurl);
        $this->assign ('multipage', $multipage);
        $blogs = $this->_blogObj->getBlogByTopicTitle ($_where . $_orderby, $perpage, $perpage * ($curpage - 1));
        $this->assign ('blogs', $blogs);
        $this->display ('admin/wall_censor.tpl');
    }

    /**
     * 添加
     */
    public function addAction ()
    {
        if (strlen (trim($this->getParam ('title'))) > 0) {
            $_startDate = trim ($this->getParam ('wallstarttime_Date'));
            $_startTime = Core_Fun::mktime (trim ($this->getParam ('wallstarttime_Hour'))
                            , trim ($this->getParam ('wallstarttime_Minute'))
                            , trim ($this->getParam ('wallstarttime_Second'))
                            , substr ($_startDate, 5, 2), substr ($_startDate, 0, 4), substr ($_startDate, 8, 2));

            $_endDate = trim ($this->getParam ('wallendtime_Date'));
            $_endTime = Core_Fun::mktime (trim ($this->getParam ('wallendtime_Hour'))
                            , trim ($this->getParam ('wallendtime_Minute'))
                            , trim ($this->getParam ('wallendtime_Second'))
                            , substr ($_endDate, 5, 2), substr ($_endDate, 0, 4), substr ($_endDate, 8, 2));
            if ($_startTime >= $_endTime) {
                $this->showmsg ('请检查开始时间和结束时间');
            } else {
                $topic = array ();
                $topic['title'] = trim($this->getParam ('title'));
                $topic['wall'] = 1;
                $topic['wallcensor'] = intval ($this->getParam ('wallcensor'));
                $topic['wallstarttime'] = $_startTime;
                $topic['wallendtime'] = $_endTime;
                $result = $this->_topicObj->addTopic ($topic, false);
                if ($result) {
                    $this->showmsg ('添加成功', "admin/wall/index");
                } else {
                    $this->showmsg ('添加失败' . $result);
                }
            }
        }
        $this->display ('admin/wall_form.tpl');
    }

    /**
     * 编辑
     */
    public function editAction ()
    {
        $wall = $this->_topicObj->find ((int)$this->getParam ('tid'));
        $this->assign ('wall', $wall);
        if (strlen ($this->getParam ('submit')) > 0) {
            $_startDate = trim ($this->getParam ('wallstarttime_Date'));
            $_startTime = Core_Fun::mktime (trim ($this->getParam ('wallstarttime_Hour'))
                            , trim ($this->getParam ('wallstarttime_Minute'))
                            , trim ($this->getParam ('wallstarttime_Second'))
                            , substr ($_startDate, 5, 2), substr ($_startDate, 0, 4), substr ($_startDate, 8, 2));

            $_endDate = trim ($this->getParam ('wallendtime_Date'));
            $_endTime = Core_Fun::mktime (trim ($this->getParam ('wallendtime_Hour'))
                            , trim ($this->getParam ('wallendtime_Minute'))
                            , trim ($this->getParam ('wallendtime_Second'))
                            , substr ($_endDate, 5, 2), substr ($_endDate, 0, 4), substr ($_endDate, 8, 2));
            if ($_startTime >= $_endTime) {
                $this->showmsg ('请检查开始时间和结束时间');
            } else {
                $topic = array ();
                $topic['tid'] = intval ($this->getParam ('tid'));
                $topic['wall'] = 1;
                $topic['title'] = $wall['title'];
                $topic['wallcensor'] = intval ($this->getParam ('wallcensor'));
                $topic['wallstarttime'] = $_startTime;
                $topic['wallendtime'] = $_endTime;
                $result = $this->_topicObj->editTopic ($topic, false);
                if ($result) {
                    $this->showmsg ('修改成功', "/admin/wall/index");
                } else {
                    $this->showmsg ('修改失败' . $result);
                }
            }
        }

        $this->display ('admin/wall_form.tpl');
    }

}