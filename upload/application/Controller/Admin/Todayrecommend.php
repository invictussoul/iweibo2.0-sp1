<?php

/**
 * 每日推荐
 *
 * @author Gavin <yaojungang@comsenz.com>
 */
class Controller_Admin_Todayrecommend extends Core_Controller_Action
{

    private $_todayRecommendObj;

    public function __construct ($params)
    {
        parent::__construct ($params);
        $this->_todayRecommendObj = new Model_TodayRecommend();
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
                $displayorder = intval ($value) > 0 ? intval ($value) : 0;
                $this->_todayRecommendObj->editTodayRecommend (array ('id' => $key, 'displayorder' => $displayorder));
            }
        }

        //删除
        if ($this->getParam ('delete')) {
            $ids = (array)$this->getParam ('delete');
            $this->_todayRecommendObj->deleteTodayRecommend ($ids);
            $this->showmsg ('删除成功', "admin/todayrecommend/index");
        }
        $todayRecommends = $this->_todayRecommendObj->getList ("displayorder ASC");
        $this->assign ('todayRecommends', $todayRecommends);
        $this->display ('admin/todayrecommend_index.tpl');
    }

    /**
     * 添加
     */
    public function addAction ()
    {
        if (strlen ($this->getParam ('content')) > 0) {
            $todayRecommend = array ();
            $todayRecommend['content'] = $this->getParam ('content');

            $result = $this->_todayRecommendObj->addTodayRecommend ($todayRecommend);
            if ($result) {
                $this->showmsg ('添加成功', "admin/todayrecommend/index");
            } else {
                $this->showmsg ('添加失败' . $result);
            }
        }

        $this->display ('admin/todayrecommend_form.tpl');
    }

    /**
     * 编辑
     */
    public function editAction ()
    {
        $todayRecommend = $this->_todayRecommendObj->find ((int)$this->getParam ('id'));
        $this->assign ('todayRecommend', $todayRecommend);
        if (strlen ($this->getParam ('content')) > 0) {
            $todayRecommendUpdate = array ();
            $todayRecommendUpdate['id'] = intval ($this->getParam ('id'));
            $todayRecommendUpdate['content'] = $this->getParam ('content');

            $result = $this->_todayRecommendObj->editTodayRecommend ($todayRecommendUpdate);
            if ($result) {
                $this->showmsg ('修改成功', "admin/todayrecommend/index");
            } else {
                $this->showmsg ('修改失败' . $result);
            }
        }

        $this->display ('admin/todayrecommend_form.tpl');
    }

}