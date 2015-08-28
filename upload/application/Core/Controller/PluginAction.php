<?php

/**
 *
 * Core_Controller_PluginAction 基类
 * 所有Plugin继承自此类
 * 
 * @author Icehu
 */
class Core_Controller_PluginAction extends Core_Controller_Action
{

	/**
     *
     * 调用一个模板并显示
     *
     * @param string $tpl
     * @author Icehu
     */
    public function display ($tpl)
    {
        $this->_setDefaultTplParams ();
        Core_Template::renderPlugin ($tpl,  $this->getControllerName());
    }

}
