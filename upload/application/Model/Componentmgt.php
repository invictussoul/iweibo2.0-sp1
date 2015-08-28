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
class Model_Componentmgt extends Core_Model
{
    /**
     * Database table name.
     * @var (string)
     */
    protected $_tableName = 'component_management';

    /**
     * Database table columns.
     * @var (array), table fields.
     */
    protected $_fields = array(
        'id', 'sitepage', 'column', 'component_type', 'component_status', 'component_sequence', 
        'component_title', 'component_number', 'component_style'
    );

    public function __construct()
    {
        parent::__construct();

        $configs = include CONFIG_PATH . 'component.php';
        $this->_sitePages  =& $configs['sitePage'];
        $this->_components =& $configs['components'];
    }
    
    /**
     * 取得配置文件中默认的各个页面.
     */
    public function getSitepages()
    {
        return $this->_sitePages;
    }

    /**
     * 取得配置文件中默认的各个组件基本信息. 
     */
    public function getComponents()
    {
        return $this->_components;
    }

    /**
     * 根据当前页面和显示的位置, 获取组件列表.
     *
     * @param: (int) $sitePage, 配置的页面.
     * @param: (string) $displayPosition, 配置的显示位置, 'main', 'right'或'both'
     * @param: (string) $status, 组件是否启动的状态. 
     * @return: (mixed) 返回查询到的数据或false.
     */
    public function getComponentSettings($sitePage, $displayPosition = 'both', $status = 'both')
    {
        $sitePage = (int) $sitePage;
        //是否查询指定显示的区域, main为主栏, right为右栏, both包括主栏和右栏, 否则返回false;
        switch (true)
        {
            case 'main'  == $displayPosition:
            case 'right' == $displayPosition:
                $column = " AND `column` = '{$displayPosition}'";
                break;
            case 'both' == $displayPosition:
                $column = '';
                break;
            default:
                return false;
                break;
        }

        //是否查询组件是否启动状态, on和1为启动, off和0为非启动状态, both包括全部, 否则返回false;
        switch (true)
        {
            case 'both' == $status:
                $status = '';
                break;
            case 'on' == $status:
            case 1 == $status:
                $status = " AND `component_status` = 1";
                break;
            case 'off' == $status:
            case 0 == $status:
                $status = " AND `component_status` = 0";
                break;
            default:
                return false;
                break;
        }

        return Core_Db::fetchAll(
            "SELECT `id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`, 
                    `component_title`, `component_number`, `component_style`
             FROM `##__component_management`
             WHERE `sitepage` = {$sitePage}{$column}{$status}
             ORDER BY `component_sequence` ASC, `id`"
        );
    }

    /**
     * 根据指定页面, 显示位置和组件类型, 取得指定的设置. 
     *
     * @param: (int) $sitePage, 配置的页面.
     * @param: (string) $displayPosition, 配置的显示位置, 'main'或'right'.
     * @return: (mixed) 返回查询到的数据或false.
     */
    public function getSpecificComponentSetting($sitePage, $displayPosition, $componentType)
    {
        if (!in_array($displayPosition, array('main', 'right')))
        {
            return false;
        }
        $sitePage = (int) $sitePage;

        return Core_Db::fetchOne(
            "SELECT `id`, `sitepage`, `column`, `component_type`, `component_status`, `component_sequence`,
                    `component_title`, `component_number`, `component_style`
             FROM `##__component_management`
             WHERE `sitepage` = {$sitePage} AND `column` = '{$displayPosition}'
             AND `component_type` = '" . Core_Db::sqlescape($componentType) . "' LIMIT 1"
        );
    }

    /**
     * 保存组件设置. 
     * 
     * @param: (array) $settingData, 包括对应的元素请参考 $this->_fields.
     * @return: (bool), true/false.
     */
    public function saveComponentSetting(array $settingData = array())
    {
        if (!isset($settingData['sitepage']) || !isset($settingData['column'])
                                             || !isset($settingData['component_type']))
        {
            return false;
        }

        $isExistSetting = $this->getSpecificComponentSetting(
            $settingData['sitepage'], $settingData['column'], $settingData['component_type']
        );

        //触发式清除缓存. 
        Model_Componentprocessunit::cleanupComponentCache($settingData['sitepage'], $settingData['column']);
        if ($isExistSetting && isset($isExistSetting['id']))
        {
            $this->_id = $settingData['id'] = (int) $isExistSetting['id'];
            array_walk($settingData, 'mysql_escape_string');
            return $this->update($settingData);
        }
        else
        {
            //当提交的设置没有添加显示条数和样式时, 设置默认值.
            !isset($settingData['component_number']) && $settingData['component_number'] = 20;
            !isset($settingData['component_style']) && $settingData['component_style']   = 1;
            array_walk($settingData, 'mysql_escape_string');
            return $this->add($settingData);
        }
        return true;
    }

    /**
     * 批量包括组件设置. 
     * 
     * @param: (int) $sitePage, 配置的页面.
     * @param: (string) $displayPosition, 配置的显示位置, 'main'或'right'.
     */
    public function batchSaveComponent($sitePage, $displayPosition, array $configsData = array())
    {
        if (!in_array($displayPosition, array('main', 'right')))
        {
            return false;
        }

        foreach ($configsData as $key => $value)
        {
            $configs = array(
                'sitepage'           => (int) $sitePage,
                'column'             => $displayPosition,
                'component_type'     => Core_Db::sqlescape($key),
                'component_status'   => ('on' == $value['status']) ? '1' : '0',
                'component_sequence' => (int) $value['sequence'],
            );
            $this->saveComponentSetting($configs);
        }
        return true;
    }
}
