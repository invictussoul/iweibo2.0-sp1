<?php

/**
 * 微博上墙模块
 * @author Gavin <yaojungang@comsenz.com>
 */
class Controller_Index_Wall extends Core_Controller_TAction
{

    private $_topicObj;
    private $_blogObj;

    public function __construct ($params)
    {
        parent::__construct ($params);
        $this->_topicObj = new Model_Topic();
        $this->_blogObj = new Model_Blog();
    }

    public function preDispatch ()
    {
        parent::preDispatch ();
        $this->assign ('active', 'wall');
    }

    /**
     * 上墙话题列表
     */
    public function searchAction ()
    {
         if ($this->getSafeParam ('keyword')) {
            $_title = $this->getSafeParam ('keyword');
            $this->assign ('keyword', $_title);
            $_topic = $this->_topicObj->queryOne ('*', array (array ('title', $_title, '='), array ('wall', 1, '=')));
            //判断是否是上墙话题
            $_topic && $this->_topicObj->isWallTopic ($_topic['tid']) && $topic = $_topic;
        }
        if ($topic) {
            $start = intval ($this->getParam ('start')) > 0 ? intval ($this->getParam ('start')) : 0;
            $limit = intval ($this->getParam ('limit')) > 0 ? intval ($this->getParam ('limit')) : 10;
            $blogs = $this->_blogObj->getBlogByWallTopicId ($topic['tid'], $start, $limit, 50);
            $blogsCount = $this->_blogObj->getBlogByWallTopicIdCount ($topic['tid']);
            if ($blogsCount == 0) {
                $this->assign ('info', '话题名称为:' . $_title . '，的话题上墙微博数为0');
            }

            //分页
            $preUrl = '/wall/search/keyword/' . $_title;
            //上一页下一页
            $frontUrl = $nextUrl = '';
            $hasnext = $start + $limit < $blogsCount ? 1 : 0;
            if ($hasnext === 1) {
                $nextUrl = Core_Fun::getParaUrl ($preUrl, array ("start" => $start + $limit));
            }

            if ($start > 0) {
                $start = $start - $limit;
                if ($start < 0) {
                    $start = 0;
                }
                $frontUrl = Core_Fun::getParaUrl ($preUrl, array ('start' => $start));
            }
            $pageInfo = array ('fronturl' => $frontUrl, 'nexturl' => $nextUrl);
            $this->assign ('pageinfo', $pageInfo);
            $this->assign ('wall_url', Core_Fun::getPathroot () . 'wall/index/id/' . $topic['tid']);
            $this->assign ('blogs', $blogs);
            $this->assign ('topic', $topic);
        } else {
            $_wallTopics_runing = $this->_topicObj->getWallByType (1);
            $_wallTopics_closed = $this->_topicObj->getWallByType (2, 0, 40);
            $this->assign ('wallTopics_runing', $_wallTopics_runing);
            $this->assign ('wallTopics_closed', $_wallTopics_closed);
        }
        if (strlen ($_title) > 0 && !$topic) {
            $this->assign ('info', '没有找到话题名称为:' . $_title . '，且在有效期内的上墙消息');
        }

        $this->display ('wall/search.tpl');
    }

    /**
     * 上墙话题列表
     */
    public function searchTopicAction ()
    {
        //查询条件
        $whereArr = array ();
        $conditions = array ();
        //只显示开放话题
        $whereArr[] = array ('state', 0, '=');
        //只显示上墙话题
        $whereArr[] = array ('wall', 1, '=');
        if ($this->getSafeParam ('keyword')) {
            $whereArr[] = array ('title', $this->getSafeParam ('keyword'), 'like');
            $conditions['keyword'] = $this->getSafeParam ('keyword');
            $this->assign ("keyword", $this->getSafeParam ('keyword'));
        }
        //排序
        if ($this->getParam ('orderby') && $this->getParam ('ordersc')) {
            $_orderby = $this->getParam ('orderby') . ' ' . $this->getParam ('ordersc');
            $conditions['orderby'] = $this->getParam ('orderby');
            $conditions['ordersc'] = $this->getParam ('ordersc');
            $this->assign ("orderby", $this->getParam ('orderby'));
            $this->assign ("ordersc", $this->getParam ('ordersc'));
        } else {
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
        $this->display ('wall/search.tpl');
    }

    /**
     * 上墙话题
     */
    public function indexAction ()
    {
        $tid = intval ($this->getParam ('id'));

        $_isWallTopic = $this->_topicObj->isWallTopic ($tid);
        if (!$_isWallTopic) {
            $this->showmsg ("不是上墙话题", 'wall/search', 0);
        } else {
            $start = intval ($this->getParam ('start')) > 0 ? intval ($this->getParam ('start')) : 0;
            $limit = intval ($this->getParam ('limit')) > 0 ? intval ($this->getParam ('limit')) : 3;
            $blogs = $this->_blogObj->getBlogByWallTopicIdMore ($tid, $start, $limit);
            $this->assign ('topic', $this->_topicObj->getTopicByTid ($tid));
            $this->assign ('totalCount', $this->_blogObj->getBlogByWallTopicIdCount ($tid));
            $this->assign ('blogs', $blogs);
        }
        $this->display ('wall/index.tpl');
    }

    /**
     * 更多
     *
     * @param type $type
     * @param type $name
     */
    public function moreAction ()
    {
        $tid = intval ($this->getParam ('id'));
        $_isWallTopic = $this->_topicObj->isWallTopic ($tid);
        if (!$_isWallTopic) {
            $this->showmsg ("不是上墙话题", '/wall/search', 0);
        } else {
            $start = intval ($this->getParam ('start')) > 0 ? intval ($this->getParam ('start')) : 0;
            $limit = intval ($this->getParam ('limit')) > 0 ? intval ($this->getParam ('limit')) : 1;
            $blogs = $this->_blogObj->getBlogByWallTopicIdMore ($tid, $start, $limit);
            $this->assign ('blogs', $blogs);
            $data = $this->fetch ('wall/wblogs.tpl');
            echo Core_Comm_Modret::getRetJson (Core_Comm_Modret::RET_SUCC, "", $data);
        }
    }

}