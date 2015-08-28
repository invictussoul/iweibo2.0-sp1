<?php

/**
 * 公告管理
 *
 * @author Gavin <yaojungang@comsenz.com>
 */
class  Controller_Admin_Notice extends Core_Controller_Action
{

    private $_noticeObj;

    public function __construct ( $params )
    {
        parent::__construct ($params);
        $this->_noticeObj = new Model_Notice();
    }

    /**
     * 列表
     */
    public function indexAction ()
    {
        //更新顺序
        if ($this->getParam ('displayorder')) {
            $displayorders = $this->getParam ('displayorder');
            foreach ($displayorders as $key => $value)
            {
                $this->_noticeObj->update (array ('id' => $key , 'displayorder' => $value));
            }
        }

        //删除
        if ($this->getParam ('delete')) {
            $ids = (array) $this->getParam ('delete');
            $this->_noticeObj->deleteNotice ($ids);
            $this->showmsg ('删除成功' , "admin/notice/index");
        }
        $notices = $this->_noticeObj->getList ("displayorder ASC");
        $this->assign ('notices' , $notices);
        $this->display ('admin/notice_index.tpl');
    }

    /**
     * 添加
     */
    public function addAction ()
    {
        if (strlen ($this->getParam ('title')) > 0) {
            $notice = array ();
            $notice['title'] = $this->getParam ('title');
            $notice['content'] = $this->getParam ('content');
            $notice['endtime'] = strtotime ($this->getParam ('endtime'));

            $result = $this->_noticeObj->addNotice ($notice);
            if ($result) {
                $this->showmsg ('添加成功' , "admin/notice/index");
            } else {
                $this->showmsg ('添加失败' . $result);
            }
        }

        $this->display ('admin/notice_form.tpl');
    }

    /**
     * 编辑
     */
    public function editAction ()
    {
        $notice = $this->_noticeObj->find ((int) $this->getParam ('id'));
        $this->assign ('notice' , $notice);
        if (strlen ($this->getParam ('title')) > 0) {
            $noticeUpdate = array ();
            $noticeUpdate['id'] = intval ($this->getParam ('id'));
            $noticeUpdate['title'] = htmlspecialchars ($this->getParam ('title'));
            $noticeUpdate['content'] = htmlspecialchars ($this->getParam ('content'));
            $noticeUpdate['endtime'] = strtotime ($this->getParam ('endtime'));

            $result = $this->_noticeObj->editNotice ($noticeUpdate);
            if ($result) {
                $this->showmsg ('修改成功' , "admin/notice/index");
            } else {
                $this->showmsg ('修改失败' . $result);
            }
        }

        $this->display ('admin/notice_form.tpl');
    }

}