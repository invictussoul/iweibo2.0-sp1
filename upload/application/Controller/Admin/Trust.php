<?php
/**
 * 白名单管理
 *
 * @author lvfeng
 */
class Controller_Admin_Trust extends Core_Controller_Action
{
    /**
     * 用户操作
     */
    public function setupAction ()
    {
    	$memberModel = new Model_User_Member();
        $uid = intval ($this->getParam ('uid'));
        $trust = intval ($this->getParam ('trust'));
        if ($trust == 1) 
        {
            $r = $memberModel->editUserInfo (array ('uid' => $uid,'trust' => $trust));
            if ($r) 
                $this->showmsg ('拉入白名单成功', '/admin/trust/search');
            else
                $this->showmsg ('拉入白名单失败', '/admin/trust/search');
        }
        elseif ($trust == 0) 
        {
            if ($memberModel->editUserInfo (array ('uid' => $uid, 'trust' => $trust))) 
                $this->showmsg ('拖出白名单成功', '/admin/trust/search');
            else
                $this->showmsg ('拖出白名单失败', '/admin/trust/search');
        }
    }

    /**
     * 白名单列表
     */
    public function searchAction ()
    {
        //queryString加密码
        $securecode = 'admintrustsearch';
        $memberModel = new Model_User_Member();
        $groupModel = new Model_User_Group();
        //取得数据
        $conditionstr = trim ($this->getParam ('conditions'));
        $code = trim ($this->getParam ('code'));
        if (!empty ($code) && strlen ($conditionstr) > 0 && md5 ($conditionstr . $securecode) == $code) {
            $tmpconditions = explode ('||', $conditionstr);
            $conditions['trust'] = trim ($tmpconditions[0]);
            $conditions['type'] = trim ($tmpconditions[1]);
            $conditions['keyword'] = trim ($tmpconditions[2]);
            $conditions['gid'] = intval ($tmpconditions[3]);
            $conditions['gender'] = intval ($tmpconditions[4]);
            $conditions['province'] = trim ($tmpconditions[5]);
            $conditions['city'] = trim ($tmpconditions[6]);
            $conditions['regdate'] = intval ($tmpconditions[7]);
            $conditions['lastvisit'] = intval ($tmpconditions[8]);
        } else {
        	$conditions['trust'] = strlen($this->getParam ('trust')) > 0 ? intval($this->getParam ('trust')) : 1;
            $conditions['type'] = trim ($this->getParam ('type'));
            $conditions['keyword'] = trim ($this->getParam ('keyword'));
            $conditions['gid'] = intval ($this->getParam ('gid'));
            $conditions['gender'] = intval ($this->getParam ('gender'));
            $conditions['province'] = trim ($this->getParam ('province'));
            $conditions['city'] = trim ($this->getParam ('city'));
            $conditions['regdate'] = intval ($this->getParam ('regdate'));
            $conditions['lastvisit'] = intval ($this->getParam ('lastvisit'));
        }
        $whereArr = array ();
        !empty ($conditions['trust']) && $whereArr[] = array ('trust', $conditions['trust']);
        !empty ($conditions['keyword']) && $whereArr[] = array ($conditions['type'], $conditions['keyword'], 'LIKE');
        !empty ($conditions['gid']) && $whereArr[] = array ('gid', $conditions['gid']);
        !empty ($conditions['gender']) && $whereArr[] = array ('gender', $conditions['gender']);
        !empty ($conditions['province']) && $whereArr[] = array ('province', $conditions['province']);
        !empty ($conditions['city']) && $whereArr[] = array ('city', $conditions['city']);
        switch ($conditions['regdate'])
        {
            case 1:
                $whereArr[] = array ('regtime', strtotime ('-7 day'), '>');
                break;
            case 2:
                $whereArr[] = array ('regtime', strtotime ('-14 day'), '>');
                break;
            case 3:
                $whereArr[] = array ('regtime', strtotime ('-30 day'), '>');
                break;
            case 4:
                $whereArr[] = array ('regtime', strtotime ('-180 day'), '>');
                break;
            case 5:
                $whereArr[] = array ('regtime', strtotime ('-365 day'), '>');
                break;
            case 6:
                $whereArr[] = array ('regtime', strtotime ('-365 day'), '<=');
                break;
            default :
                break;
        }
        switch ($conditions['lastvisit'])
        {
            case 1:
                $whereArr[] = array ('lastvisit', strtotime ('-7 day'), '>');
                break;
            case 2:
                $whereArr[] = array ('lastvisit', strtotime ('-14 day'), '>');
                break;
            case 3:
                $whereArr[] = array ('lastvisit', strtotime ('-30 day'), '>');
                break;
            case 4:
                $whereArr[] = array ('lastvisit', strtotime ('-180 day'), '>');
                break;
            case 5:
                $whereArr[] = array ('lastvisit', strtotime ('-365 day'), '>');
                break;
            case 6:
                $whereArr[] = array ('lastvisit', strtotime ('-365 day'), '<=');
                break;
            default :
                break;
        }
        //用户数量
        $userCount = $memberModel->getUserCount ($whereArr);
        $this->assign ('userscount', $userCount);
        //分页
        $perpage = Core_Config::get ('page_size', 'basic', 20);
        $curpage = $this->getParam ('page') ? intval ($this->getParam ('page')) : 1;
        $conditionstr = implode ('||', $conditions);
        $md5str = md5 ($conditionstr . $securecode);
        $mpurl = '/admin/trust/search/conditions/' . $conditionstr . '/code/' . $md5str . '/';
        $multipage = $this->multipage ($userCount, $perpage, $curpage, $mpurl);
        $this->assign ('multipage', $multipage);
        //用户列表
        $userList = $memberModel->getUserList ($whereArr, 'uid desc', array ($perpage, $perpage * ($curpage - 1)));
        $this->assign ('users', $userList);
        //组列表
        $groupList = $groupModel->getGroupList (null, 'gid');
        foreach ($groupList as $value)
        {
            $usergroups[$value['gid']] = array ('type' => $value['type'], 'title' => $value['title']);
        }
        $this->assign ('usergroups', $usergroups);
        //查询条件
        $this->assign ('conditions', $conditions);

        $this->display ('admin/trust_search.tpl');
    }

}