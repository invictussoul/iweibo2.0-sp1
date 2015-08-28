<?php

/**
 * 工具
 *
 * @author Gavin <yaojungang@comsenz.com>
 */
class Controller_Admin_Tool extends Core_Controller_Action
{

    /**
     * 更新缓存
     */
    public function updatecacheAction ()
    {
        if ($this->getParam ('updatecache')) {
            Core_Cache::updateAllCache ();
            $this->showmsg ('缓存更新成功');
        }
        $this->display ('admin/tool_updatecache.tpl');
    }
    /**
     * 数据调用-推荐用户
     */
     public function datatransferAction ()
    {
        $this->display ('admin/tool_datatransfer.tpl');
    }
    /**
     * 数据调用-微博广播站
     */
     public function datatransferwAction ()
    {
        $this->display ('admin/tool_datatransferw.tpl');
    }
    /**
     * 数据调用-最新微博
     */
     public function datatransfertAction ()
    {
        $this->display ('admin/tool_datatransfert.tpl');
    }

}