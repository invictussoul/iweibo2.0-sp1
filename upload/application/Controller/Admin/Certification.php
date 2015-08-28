<?php
/**
 * 认证管理
 *
 * @author lvfeng
 */
class Controller_Admin_Certification extends Core_Controller_Action
{

    /**
     * 认证设置
     */
    public function setupAction ()
    {
        if (trim ($this->getParam ('action')) == 'setup') {
            $certInfo['localauth'] = intval ($this->getParam ('localauth'));
            $certInfo['platformauth'] = intval ($this->getParam ('platformauth'));
            $certInfo['localauthtext'] = trim ($this->getParam ('localauthtext'));
            Model_User_Certification::setCertInfo ($certInfo);
            //触发清除组件缓存
            Model_Componentprocessunit::cleanupAllComponentCache();
            $this->showmsg ('认证设置保存成功');
        }

        $certInfo = Model_User_Certification::getCertInfo ();
        $this->assign ('certInfo', $certInfo);

        $this->display ('admin/certification_setup.tpl');
    }

    /**
     * 认证用户
     */
    public function authAction ()
    {

        $uid = intval ($this->getParam ('uid'));
        $localauth = intval ($this->getParam ('auth'));
        $userauthtext = trim ($this->getParam ('userauthtext'));
        if ($this->getParam ('action') == 'auth' && $localauth == 1) {
            $memberModel = new Model_User_Member();
            $r = $memberModel->editUserInfo (array ('uid' => $uid,'localauth' => $localauth, 'localauthtext' => $userauthtext));
            if ($r) {
                $this->showmsg ('用户认证成功', '/admin/certification/search');
            } else {
                $this->showmsg ('用户认证失败', '/admin/certification/search');
            }
        } elseif ($localauth == 0) {
            $memberModel = new Model_User_Member();
            if ($memberModel->editUserInfo (array ('uid' => $uid, 'localauth' => $localauth, 'localauthtext' => $userauthtext))) {
                $this->showmsg ('取消用户认证成功', '/admin/certification/search');
            } else {
                $this->showmsg ('取消用户认证失败', '/admin/certification/search');
            }
        } else {
            $this->assign ('uid', $uid);
            $this->assign ('auth', $localauth);
            $this->display ('admin/certification_auth.tpl');
        }
    }

    /**
     * 认证用户管理
     */
    public function searchAction ()
    {
        //queryString加密码
        $securecode = 'admincertificationsearch';
        $memberModel = new Model_User_Member();
        $groupModel = new Model_User_Group();
        //取得数据
        $conditionstr = trim ($this->getParam ('conditions'));
        $code = trim ($this->getParam ('code'));
        if (!empty ($code) && strlen ($conditionstr) > 0 && md5 ($conditionstr . $securecode) == $code) {
            $tmpconditions = explode ('||', $conditionstr);
            $conditions['localauth'] = trim ($tmpconditions[0]);
            $conditions['type'] = trim ($tmpconditions[1]);
            $conditions['keyword'] = trim ($tmpconditions[2]);
            $conditions['gid'] = intval ($tmpconditions[3]);
            $conditions['gender'] = intval ($tmpconditions[4]);
            $conditions['province'] = trim ($tmpconditions[5]);
            $conditions['city'] = trim ($tmpconditions[6]);
            $conditions['regdate'] = intval ($tmpconditions[7]);
            $conditions['lastvisit'] = intval ($tmpconditions[8]);
        } else {
        	$conditions['localauth'] = strlen($this->getParam ('localauth')) > 0 ? intval($this->getParam ('localauth')) : 0;
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
        !empty ($conditions['localauth']) && $whereArr[] = array ('localauth', $conditions['localauth']);
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
        $mpurl = '/admin/certification/search/conditions/' . $conditionstr . '/code/' . $md5str . '/';
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

        $this->display ('admin/certification_search.tpl');
    }

}