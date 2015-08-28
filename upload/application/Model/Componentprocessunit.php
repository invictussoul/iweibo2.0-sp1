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
class Model_Componentprocessunit
{
    /**
     * 当前使用的模板根目录.
     * @var: (string).
     */
    private $_templateRootPath = 'component';

    /**
     * 当前使用的皮肤对应的具体模板目录. 
     * (根据所选择的皮肤对应于不同的模板.)
     *
     * @var: (string)
     */
    private $_templatePath = '';
    
    /**
     * 缓存的前缀Key.
     * @var: (string)
     */
    public static $cachePrefixKey = '_component/Component.';

    /**
     * 缓存的过期时间. 
     * @var: (string), 默认一天.
     */
    public static $cacheExpire = 86400;

    /**
     * 用于单例的本类实例. 
     * @var: (object of Model_Componentprocessunit)
     */
    private static $_instance = null;

    /**
     * 是否启动组件缓存.
     * @var: (bool), 缓存组件的纯数据缓存与否.
     */
    private $_isCacheComponent = true;

    /**
     * 组件数据Model.
     * @var: (object of Model_Componentmgt)
     */
    private $_componentMgtModel = null;

    private function __construct()
    {
        $this->_componentMgtModel = new Model_Componentmgt();
        
        //使用模板类获取当前所使用的模板目录, 用于后面组件数据组装时检查模板文件是否存在.
        if (empty($this->_templatePath))
        {
            $this->_templatePath = Core_Template::getTemplateDirs();
        }
    }
    
    /**
     * 组件数据和管理单例实例.
     * 
     * @return, 实例化的本类.
     */
    public static function &getInstance()
    {
        if (null === self::$_instance)
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 保存组件缓存
     * 
     * 仅缓存组件的<设置信息>和<组件数据源>, 不保存整合模板后的组件数据.
     * 调用Core_Cache类保存缓存, 当使用文件缓存时, 保存于'pub/cache/_component/'下面.
     * 
     * @param: (int) $sitePage, 缓存的页面代号, link to 'config/component.php'
     * @param: (int) $displayPosition, 缓存的数据显示位置, 主栏/右栏, 
     * @param: (array) $componentData, 缓存的数组数据.
     * @return linkt to Core_Cache::write().
     */
    private function _setComponentCache($sitePage, $displayPosition, array $componentData = array())
    {
        return Core_Cache::write(
            self::$cachePrefixKey . "{$sitePage}.{$displayPosition}", $componentData, self::$cacheExpire
        );
    }
    
    /**
     * 从缓存中读取组件原始数据.
     * 
     * @param: (int) $sitePage, 缓存的页面代号, link to 'config/component.php'
     * @param: (int) $displayPosition, 缓存的数据显示位置, 主栏/右栏, 
     * @return: link to Core_Cache::read().
     */
    private function _getComponentCache($sitePage, $displayPosition)
    {
        return Core_Cache::read(self::$cachePrefixKey . "{$sitePage}.{$displayPosition}");
    }

    /**
     * 清除组件的数据缓存. 
     *
     * @param: (int) $sitePage, 缓存的页面代号, link to 'config/component.php'
     * @param: (int) $displayPosition, 缓存的数据显示位置, 主栏/右栏, 
     * @return: link to Core_Cache::delete().
     */
    public static function cleanupComponentCache($sitePage, $displayPosition)
    {
        return Core_Cache::delete(self::$cachePrefixKey . "{$sitePage}.{$displayPosition}");
    }

    /**
     * 获取模板对应的路径. 
     *
     * 当指定不同的皮肤时, 模板的路径以数组的形式返回, 
     * 当前模板路径应该为返回的数组的第一个元素. 
     *
     * @return: (array) $paths. 
     */
    public static function getComponentTemplatePath()
    {
        $instance =& self::getInstance();
        $paths    = $instance->_templatePath;

        foreach ($paths as $key => &$path)
        {
            $path .= DIRECTORY_SEPARATOR . $instance->_templateRootPath . DIRECTORY_SEPARATOR;
        }
        return (array) $paths;
    }

    /**
     * 清除所有缓存.
     *
     * 当在后台修改了组件界面设置, 或是对组件来源数据更改时, 
     * 调用该静态方法清除相关的数据, (所有相关组件修改, 添加, 或删除, 都会调用该函数.
     *
     * @access: public, static.
     * @return: null.
     */
    public static function cleanupAllComponentCache()
    {
        $instance =& self::getInstance();
        $sitepageCanConfig = $instance->_componentMgtModel->getSitepages();   //获取配置文件中可设置的页面.
        $sitepageCanConfig = array_keys($sitepageCanConfig);
        $columnCanConfig   = $instance->_componentMgtModel->getComponents();   //获取配置文件中可设置的显示区域.
        $columnCanConfig   = array_keys($columnCanConfig);
        foreach ($columnCanConfig as $i => $displayPosition)
        {
            foreach ($sitepageCanConfig as $j => $sitePage)
            {
                self::cleanupComponentCache($sitePage, $displayPosition);

            }
        }
        return null;
    }

    /**
     * 读取组件数据的原始入口.
     *
     * 根据传入的页面ID, 显示的位置和组件是否启动状态获取组件数据. 
     * 同时存入缓存数据中.
     *
     * @access public, static
     * @param: (int) $sitePage, 可配置的页面id, @link to: config/component.php下面配置.
     * @param: (string), $displayPosition, 可以是主栏'main', 右栏'right', 或'both', 默认both.
     * @param: (string), $status, 获取组件数据时依据的组件是否开启状态, 'on'或'off'.
     * @return: (mixed), 组件数据array, 或false.
     */
    public static function getComponentsBySitepage($sitePage, $displayPosition = 'both', $status = 'on')
    {
        $instance =& self::getInstance();
        $sitepageCanConfig = $instance->_componentMgtModel->getSitepages();   //获取配置文件中可设置的页面.
        $sitePage = (int) $sitePage;
        if (!array_key_exists($sitePage, $sitepageCanConfig)
            || !in_array($displayPosition, array('both', 'main', 'right'), true))
        {
            return false;    //传入实参非法.
        }

        //是否从Cache中读取数据.
        if ($instance->_isCacheComponent && ($cache = $instance->_getComponentCache($sitePage, $displayPosition)))
        {
            return $cache;
        }

        //获取可以设置的组件列表.
        $availableComponent = $instance->_componentMgtModel->getComponents();
        //获取已经开启的组件列表. 
        $enabledComponents  = $instance->_componentMgtModel
                                       ->getComponentSettings($sitePage, $displayPosition, $status);
        if (!$enabledComponents)
        {
            return false;  //查询结果目前没有已经开启的组件.
        }

        //拼凑组件数据. 
        $componentData = array();
        foreach ($enabledComponents as $key => $component)
        {
            $column =& $component['column'];
            $componentType =& $component['component_type'];

            if (isset($availableComponent[$column][$componentType]))
            {
                $model = $availableComponent[$column][$componentType]['model'];
                if (!class_exists($model) || !method_exists($model, '__toData'))
                {
                    continue;
                }
                $model = new $model();

                $componentData[] = array(
                    'type'     => $componentType,
                    'desc'     => $availableComponent[$column][$componentType]['type'],
                    'title'    => $component['component_title'],
                    'column'   => $column,
                    'status'   => $component['component_status'],
                    'sitepage' => $component['sitepage'],
                    'sequence' => $component['component_sequence'],
                    'number'   => $component['component_number'],
                    'style'    => $component['component_style'],
                    'contents' => $model->__toData($component['component_number'])
                );
            }
            unset($column, $componentType, $model);
        }
        //设置缓存
        $instance->_setComponentCache($sitePage, $displayPosition, $componentData);
        return $componentData;
    }

    
    /**
     * 获取整合了模板输出的组件数据.
     *
     * 根据模板类Core_Template获取模板数据, 后判断模板是否存在, 整合Html到输出数据中.
     *
     * @access public, static
     * @param: (int) $sitePage, 可配置的页面id, @link to: config/component.php下面配置.
     * @param: (string), $displayPosition, 可以是主栏'main', 右栏'right', 或'both', 默认both.
     * @param: (string), $status, 获取组件数据时依据的组件是否开启状态, 'on'或'off'.
     * @return: (mixed), 组件数据array, 或false.
     */
    public static function getComponentWithTemplate($sitePage, $displayPosition = 'both', $status = 'on')
    {
        $instance =& self::getInstance();
        $componentData = self::getComponentsBySitepage($sitePage, $displayPosition, $status);
        if($componentData)
        {
            foreach ($componentData as $key => &$component)
            {
                $column =& $component['column'];
                $type   =& $component['type'];
                $style  =& $component['style'];

                $templateFile = "{$column}/{$type}_{$style}.tpl";
                foreach ($instance->_templatePath as $key => $path)
                {
                    $path .= DIRECTORY_SEPARATOR . $instance->_templateRootPath . DIRECTORY_SEPARATOR;
                    if (!is_readable($path . $templateFile))
                    {
                        continue;
                    }

                    Core_Template::assignvar('component', $component);
                    $subTemplate = Core_Template::render("component/{$templateFile}", true, false);
                    $component['html'] = $subTemplate;
                }
            }
        }
        unset($column, $type, $style, $subTemplate, $component);
        return $componentData;
    }

    /**
     * 直接获取组件显示的Html代码.
     *
     * 根据getComponentWithTemplate中得到的数据源, 直接返回指定页面和区域的Html代码.
     *
     * @access public, static
     * @param: (int) $sitePage, 可配置的页面id, @link to: config/component.php下面配置.
     * @param: (string), $displayPosition, 可以是主栏'main', 右栏'right', 或'both', 默认both.
     * @return: (string), 组件的Html代码.
     */
    public static function getComponentWithHtml($sitePage, $displayPosition)
    {
        if ($component = self::getComponentWithTemplate($sitePage, $displayPosition))
        {
            $componentHtml = '';
            foreach ($component as $key => $value)
            {
                isset($value['html']) && ($componentHtml .= $value['html']);
            }
            return $componentHtml;
        }
        return '';
    }
}
