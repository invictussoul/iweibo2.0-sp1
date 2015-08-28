<?php

/**
 * 导航栏管理
 *
 * @author Gavin <yaojungang@comsenz.com>
 */
class Controller_Admin_Nav extends Core_Controller_Action
{

    private $_navObj;

    public function __construct ($params)
    {
        parent::__construct ($params);
        $this->_navObj = new Model_Nav();
    }

    /**
     * 列表
     */
    public function indexAction ()
    {
        //新增
        $_type = strlen ($this->getParam ('type')) > 0 ? intval ($this->getParam ('type')) : 0;
        if ($this->getParam ('newname')) {
            $_newparentids = $this->getParam ('newparentid');
            $_newnames = $this->getParam ('newname');
            $_newactions = $this->getParam ('newaction');
            $_newdisplayorders = $this->getParam ('newdisplayorder');
            $_newlinks = $this->getParam ('newlink');
            foreach ($_newparentids as $i => $_newparentid)
            {
                $_nav = array (
                    'parentid' => intval ($_newparentid),
                    'name' => trim ($_newnames[$i]),
                    'action' => trim ($_newactions[$i]),
                    'type' => $_type,
                    'displayorder' => $_newdisplayorders[$i],
                    'link' => trim ($_newlinks[$i]),
                    'system' => 0,
                    'useable' => 1,
                    'newwindow' => 0
                );
                $this->_navObj->addNav ($_nav);
            }
            //$this->showmsg ('添加成功');
        }
        //删除
        if ($this->getParam ('delete')) {
            $ids = (array)$this->getParam ('delete');
            $this->_navObj->deleteNav ($ids);
            // $this->showmsg ('删除成功');
        }
        //修改
        if ($this->getParam ('name')) {
            $_ids = $this->getParam ('id');
            $_parentids = $this->getParam ('parentid');
            $_names = $this->getParam ('name');
            $_displayorders = $this->getParam ('displayorder');
            $_actions = $this->getParam ('action');
            $_links = $this->getParam ('link');
            $_newwindows = $this->getParam ('newwindow');
            $_useables = $this->getParam ('useable');
            foreach ($_parentids as $i => $_parentid)
            {
                $_nav0 = $this->_navObj->find ($_ids[$i]);
                $_system = intval($_nav0['system']);
                $_nav = array (
                    'id' => intval ($_ids[$i]),
                    'parentid' => intval ($_parentid),
                    'name' => trim ($_names[$i]),
                    'type' => $_type,
                    'displayorder' => $_displayorders[$i],
                    'newwindow' => $_newwindows[$i] && isset ($_newwindows[$i]) && $_newwindows[$i],
                    'useable' => $_names[$i] && isset ($_useables[$i]) && $_useables[$i],
                );
                if ($_system == 0) {
                    $_nav['action'] = trim ($_actions[$i]);
                    $_nav['link'] = trim ($_actions[$i]);
                }
                $this->_navObj->editNav ($_nav);
            }
            $this->showmsg ('修改成功');
        }
        //查询条件
        $whereArr = array ();
        $conditions = array ();
        //类型
        $whereArr[] = array ('type', $_type);
        $conditions['type'] = $_type;
        $this->assign ("type", $_type);
        //总数量
        $count = $this->_navObj->getCount ($whereArr);
        $this->assign ('count', $count);
        //分页
        $perpage = 2000;
        $curpage = $this->getParam ('page') ? intval ($this->getParam ('page')) : 1;
        $_c = Core_Comm_Util::map2str ($conditions, '/', '/');
        $_c = empty ($_c) ? '' : '/' . $_c;
        $mpurl = '/admin/nav/index' . $_c . '/';
        $multipage = $this->multipage ($count, $perpage, $curpage, $mpurl);

        $this->assign ('multipage', $multipage);
        $navs = $this->_navObj->queryAll ($whereArr, 'displayorder ASC', array ($perpage, $perpage * ($curpage - 1)));

        foreach ($navs as $nav)
        {
            if ($nav['parentid']) {
                $subnavlist[$nav['parentid']][] = $nav;
            } else {
                $navlist[$nav['id']] = $nav;
            }
        }
        $this->assign ('navs', $navlist);
        $this->assign ('subnavs', $subnavlist);
        $this->assign ('nav_type', $this->_navObj->TYPE);
        $this->display ('admin/nav_index.tpl');
    }

}