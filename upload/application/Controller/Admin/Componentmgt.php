<?php
/**
 * iWeibo
 *
 * LICENSE
 *
 * @homepage: http://open.t.qq.com/apps/iweibo 
 * @category: iWeibo
 * @package: 
 * @copyright:
 * @license 
 * @version: 2.0
 * @author: Bluexchen<Bluexchen@tencent.com>
 */
class Controller_Admin_Componentmgt extends Core_Controller_Action
{
    //组件管理Model.
    private $_componentModel = null;
    //表格验证的错误信息，临时存放在cookie中.
    private $_errorMessage  = array();
    //网站Base Url
    private $_baseUrl = '';

    /**
     * whichpage成员属性对应的页面说明. 
     * 
     * '1': '我的首页'             '2': '广播大厅'
     * '3': '我的广播'             '4': '提到我的'
     * '5': '我的收藏'             '6': '私信'
     * '7': '我收听的人/我的听众'  '8': '游客首页'
     */
    private $_whichpage = null;

    //可配置的页面, 设置于config/component_config.php中. 
    private $_sitePage  = null;
    //基本上组件类型, 设置于config/component_config.php中. 
    private $_components = null;

    public function __construct($params)
    {
        parent::__construct($params);
        $this->_componentModel = new Model_Componentmgt();  //实例化组件配置Model.

        $this->assign('baseUrl', '/');

        //载入可配置的页面以及组件类型. 
        $this->assign('sitePage', $this->_sitePage = $this->_componentModel->getSitepages());
        $this->_components = $this->_componentModel->getComponents();

        //取得配置的页面, 默认第为首页. 
        $this->_whichpage = (int) $this->getParam('whichpage');
        if (!array_key_exists($this->_whichpage, $this->_sitePage))
        {
            $this->_whichpage = 1;
        }
        $this->assign('whichpage', $this->_whichpage);
    }

    /**
     * 主栏默认设置页面.
     */
    public function indexAction()
    {
        $componentsSettings = $this->_getConfigData($this->_whichpage, 'main');

        $this->assign('componentsSettings', $componentsSettings);
        $this->assign('column', 'main');
        $this->display('admin/component_management_index.tpl');
    }
    
    /**
     * 访问admin/componentmgt/main和admin/componentmgt/index调用一致.
     */
    public function mainAction()
    {
        return $this->indexAction();
    }

    /**
     * 根据数据库中的设置以及config/component_config.php中的基本配置
     * 组装成前台可直接调用的数据. 
     * 
     * @param: (int) $sitePage, 配置哪个页面. 
     * @param: (string) $displayPosition, 'main', 或 'right', 表示主栏和右栏.
     * @return: (array), 整后的各个组件的配置. 
     */
    private function _getConfigData($sitePage, $displayPosition)
    {
        $components =& $this->_components[$displayPosition];
        $componentSettings = $this->_componentModel->getComponentSettings($sitePage, $displayPosition);
        if ($componentSettings)
        {
            foreach ($componentSettings as $key => $value)
            {
                if (isset($value['component_status']) && ('1' == $value['component_status']))
                {
                    $componentType = $value['component_type'];
                    if (isset($components[$componentType]))
                    {
                        $components[$componentType] = array_merge($components[$componentType], $value);
                    }
                }
            }
        }
        return $components;
    }

    /**
     * 右栏组件默认页面.
     */
    public function rightAction()
    {
        $componentsSettings = $this->_getConfigData($this->_whichpage, 'right');
        $this->assign('componentsSettings', $componentsSettings);
        $this->assign('column', 'right');
        $this->display('admin/component_management_right.tpl');
    }
    
    /**
     * 批量处理组件设置. 
     * 
     * @desc: 根据表单中提交的checkbox选中设置, 该批量设置仅保存是否启动和顺序设置.
     */
    public function batchsettingAction()
    {
        $action = $this->getParam('action');
        if ('post' === $action)
        {
            $whichpage = $this->getParam('whichpage');
            $column    = $this->getParam('column');
            $configs   = $this->getParam('configs');
            if ($this->_componentModel->batchSaveComponent($whichpage, $column, $configs))
            {
                $this->showmsg(
                    '设置成功，正在返回..', "/admin/componentmgt/{$column}/whichpage/{$whichpage}"
                );
            }
        }
        $this->showmsg(null, "/admin/componentmgt/{$column}/whichpage/{$whichpage}");
    }
    
    /**
     * 组件设置页面默认页
     */
    public function editAction()
    {
        $action = $this->getParam('action');
        if ('post' === $action)
        {
            $this->_saveComponentSetting();
            return;
        }

        $params = $this->getParams();
        $componentToSet = strip_tags(trim($this->getParam('component')));
        $this->assign('component', $componentToSet);
        switch (true)
        {
            //编辑主栏组件.
            case array_key_exists('main', $params):
                $column = 'main';
                break;

            //编辑右栏组件.
            case array_key_exists('right', $params):
                $column = 'right';
                break;

            default: 
                $this->showmsg(null, '/admin/componentmgt/main', 0);
                break;
        }
        //获取该页面在数据库中是否已经存在设定.
        $isExistingSetting = $this->_componentModel->getSpecificComponentSetting(
            $this->_whichpage, $column, $componentToSet
        );

        //设置组件显示的样式路径图片. 
        $componentPath = Model_Componentprocessunit::getComponentTemplatePath();
        $componentPath = array_shift($componentPath);
        $this->assign('componentStylePath', $componentPath);

        $this->assign('column', $column);
        $this->assign('components', $components =& $this->_components[$column]);
        $this->assign('componentSetting', $isExistingSetting);
        $this->display('admin/component_management_edit.tpl');
    }

    /**
     * 保存组件设置. 
     * 
     * @desc: 保存单个组件的设置. 
     */
    private function _saveComponentSetting()
    {
        $componentSetting = array(
            'sitepage'           => strip_tags(trim($this->_whichpage)),
            'column'             => strip_tags(trim($this->getParam('column'))),
            'component_type'     => strip_tags(trim($this->getParam('type'))),
            'component_status'   => ('on' == $this->getParam('status')) ? 1 : 0,
            'component_title'    => strip_tags(trim($this->getParam('title'))),
            'component_number'   => (int) strip_tags(trim($this->getParam('number'))),
            'component_style'    => (int) strip_tags(trim($this->getParam('style')))
        );

        $validateSettings = $this->_validateComponentSetting($componentSetting);
        if (!$validateSettings)
        {
            //验收不通过.
        }

        $this->_componentModel->saveComponentSetting($validateSettings);
        $this->showmsg(
            '组件设置保存成功，正在返回..', "/admin/componentmgt/{$componentSetting['column']}/whichpage/{$this->_whichpage}"
        );
    }

    /**
     * 验证前台传入的表单内容.
     * 
     * @return: (mixed), 如果任一情况不符合, 刚返回false.
     *                   否则返回验证后的数组, 开始和结束时间会被转化为时间戳.
     */
    private function _validateComponentSetting(array $settingData = array())
    {
        //暂时未验证
        return $settingData;
    }
}
