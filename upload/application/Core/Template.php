<?php
/**
 * iweibo2.0
 *
 * Template 封装
 *
 * @author Icehu
 * @link http://open.t.qq.com/iweibo
 * @copyright Copyright © 1998-2011. All Rights Reserved
 * @license http://open.t.qq.com/iweibo/license
 * @version $Id: Core_Template.php 2011-06-09 15:53:00Z gionouyang $
 * @package Model
 * @since 2.0
 */
class Core_Template
{
    /**
     * 存储模板变量的全局变量
     * @var array
     */
    private static $_VAR = array();

    /**
     * 以引用的方式，注册一个模板变量
     * 在PHP5中几乎无用
     * @param string $key
     * @param mix $value
     * @author Icehu
     */
    public static function assignvarByref($key, &$value)
    {
        self::$_VAR[$key] = &$value;
    }

    /**
     * 注册一个模板变量
     *
     * @param string $key
     * @param mix $value
     * @author Icehu
     */
    public static function assignvar($key, $value = null)
    {
        if(is_array($key))
        {
            foreach((array)$key as $_k => $_v)
            {
                self::assignvar($_k, $_v);
            }
        }
        else
        {
            self::$_VAR[(string)$key] = $value;
        }
    }

    /**
     * 获取一个已经注册的模板变量
     *
     * @param string $key
     * @param mix $default
     * @return mix
     * @author Icehu
     */
    public static function getvar($key, $default = null)
    {
        if(isset(self::$_VAR[$key]))
        {
            return self::$_VAR[$key];
        }
        else
        {
            if($default)
            {
                return $default;
            }
            else
            {
                return null;
            }
        }
    }

    /**
     * Smarty对象
     * @var Smarty
     */
    private static $SMARTY = null;
    private static $PLUGINSMARTY = null;

    /**
     * 获取Smarty对象并且初始化
     * 只会获取一次
     *
     * @return Smarty
     * @author Icehu
     */
    protected static function getSmarty()
    {
        if(null === self::$SMARTY)
        {
            require_once INCLUDE_PATH . 'Smarty/Smarty.class.php';
            $smarty = new Smarty();
            //一些设置
            $smarty->left_delimiter = '<!--{';
            $smarty->right_delimiter = '}-->';
            $smarty->config_dir = CONFIG_PATH . 'Config';
            $smarty->compile_dir = CACHEDIR . '_templates_c';
            $smarty->cache_dir = CACHEDIR . '_view_c';
            $smarty->register_object('TO', new Core_Template_Object());
            $smarty->template_dir = self::getTemplateDirs();
            //支持 &lt;!--{ 、 }--&gt; 、 -&gt;	在 可见可得的情况下友好
            $smarty->register_prefilter(array(__CLASS__, 'prefilter'));

            if(! file_exists($smarty->compile_dir))
            {
                Core_Fun_File::makeDir($smarty->compile_dir);
            }
            if(! file_exists($smarty->cache_dir))
            {
                Core_Fun_File::makeDir($smarty->cache_dir);
            }
            self::$SMARTY = $smarty;
        }
        return self::$SMARTY;
    }

    protected static function getPluginSmarty($pluginName)
    {
        if(null === self::$PLUGINSMARTY)
        {
            require_once INCLUDE_PATH . 'Smarty/Smarty.class.php';
            $smarty = new Smarty();
            //一些设置
            $smarty->left_delimiter = '<!--{';
            $smarty->right_delimiter = '}-->';
            $smarty->config_dir = CONFIG_PATH . 'Config';
            $smarty->compile_dir = CACHEDIR . '_templates_c';
            $smarty->cache_dir = CACHEDIR . '_view_c';
            $smarty->register_object('TO', new Core_Template_Object());
            $smarty->template_dir = self::getPluginTemplateDirs($pluginName);
            //支持 &lt;!--{ 、 }--&gt; 、 -&gt;	在 可见可得的情况下友好
            $smarty->register_prefilter(array(__CLASS__, 'prefilter'));

            if(! file_exists($smarty->compile_dir))
            {
                Core_Fun_File::makeDir($smarty->compile_dir);
            }
            if(! file_exists($smarty->cache_dir))
            {
                Core_Fun_File::makeDir($smarty->cache_dir);
            }
            self::$PLUGINSMARTY = $smarty;
        }
        return self::$PLUGINSMARTY;
    }

    /**
     * 对Smarty模板编译前的处理
     * 支持 &lt;--{	}--&gt;	--&gt;
     *
     * @param string $source_content
     * @param Smarty $smarty
     * @return string
     * @author Icehu
     */
    public static function prefilter($source_content, &$smarty)
    {
        return preg_replace('/<!--{(.*)}-->/eU', "self::replacegt('\\1')",
        preg_replace(array('/&lt;!--{/', '/}--&gt;/'), array('<!--{', '}-->'), $source_content));
    }

    public static function replacegt($str)
    {
        return '<!--{' . stripcslashes(str_replace('-&gt;', '->', $str)) . '}-->';
    }

    /**
     * 显示/解析模板
     * @param string $resource_name 模板路径
     * @author Icehu
     */
    public static function render($resource_name, $type = false, $seo = true)
    {
        $smarty = self::getSmarty();
        $tDirs = self::getTemplateDirs();
        $compile_id = $tDirs[0];
        $_smarty_results = self::dealSmarty($smarty, $resource_name, $compile_id, $seo);
        if($type)
        {
            return $_smarty_results;
        }
        else
        {
            echo $_smarty_results;
        }
    }

    /**
     * 在插件模块下解析模板
     *
     * @param string $resource_name
     * @param string $pluginName
     * @param bool $type
     * @return string
     * @author Icehu
     */
    public static function renderPlugin($resource_name, $pluginName, $type = false, $seo = true)
    {
        $smarty = self::getPluginSmarty($pluginName);
        $_smarty_results = self::dealSmarty($smarty, $resource_name, $pluginName, $seo);
        if($type)
        {
            return $_smarty_results;
        }
        else
        {
            echo $_smarty_results;
        }
    }

    public static function dealSmarty($smarty, $resource_name, $compile_id, $seo = true)
    {
        foreach(self::$_VAR as $key => $value)
        {
            $smarty->assign($key, $value);
        }

        //使用风格作为 $compile_id
        $_smarty_results = $smarty->fetch($resource_name, null, $compile_id);
        if($seo)
        {
            //todo seo change!
            $_replace[] = "!action=\"/([^\"]*)\"!ieU";
            $_replaceto[] = " 'action=\"' . Core_Template::seoChange('\\1') . '\"';";
            $_replace[] = "!href=\"/([^\"]*)\"!ieU";
            $_replaceto[] = " 'href=\"' . Core_Template::seoChange('\\1') . '\"';";
            //资源自动替换
            $_replace[] = "!href=\'/([^\']*)\'!ieU";
            $_replaceto[] = " 'href=\"' . Core_Template::resourceChange('\\1') . '\"';";
            //src自动替换
            $_replace[] = "!src=\"/([^\"]*)\"!ieU";
            $_replaceto[] = " 'src=\"' . Core_Template::resourceChange('\\1') . '\"';";
            $_smarty_results = preg_replace($_replace, $_replaceto, $_smarty_results);
        }
        return $_smarty_results;
    }

    /**
     * 对Url进行Seo转换
     *
     * @param string $url
     * @return string
     * @author Icehu
     */
    public static function seoChange($url)
    {
        //考虑 admin
        $front = Core_Controller_Front::getInstance();
        $webroot = Core_Controller_Front::getWebRoot();
        if(! $url)
        {
            return $webroot;
        }
        //todo amdinmodel 名称 可以更改
        if(! preg_match('!^/?admin/!', $url) && Core_Config::get('seo', 'basic', 0))
        {
            $ext = Core_Config::get('seoext', 'basic', '');
            //后缀需要以 “.” 开头
            //其它限制还需要吗？如果出现多个 “.” 会引起参数错误，让管理员自己保证吧。
            if(is_array($ext) && $ext[0] != '.')
            {
                $ext = '';
            }
            return $webroot . $url . $ext;
        }
        else
        {
            return $webroot . 'index.php' . Core_Fun::getPathinfoPre() . $url;
        }
    }

    /**
     * 对resource 目录自动加上资源配置目录
     *
     * @param string $url
     * @return string
     * @author Icehu
     */
    public static function resourceChange($url)
    {
		if(preg_match('/^resource\//',$url) || preg_match('/^view\//',$url))
		{
			$resource_path = Core_Config::get('resource_path', 'basic', '/');
			return $resource_path . $url;
		}
		return '/' . $url;
    }

    /**
     * 自己的实例
     * @var Core_Template
     */
    public static $_instance = null;

    /**
     * 获取单例对象
     * @return Core_Template
     * @author Icehu
     */
    public static function getInstance()
    {
        if(null === self::$_instance)
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 私有的构造函数
     * 禁止new
     */
    private function __construct()
    {

    }

    /**
     * 魔术方法钩子
     * 已移动到 Core_Template_*
     * 请不要使用
     * @param string $methodName
     * @return mix
     * @author Icehu
     */
    public function __get($methodName)
    {
        if(preg_match('/_/', $methodName))
        {
            $_temp = array_map('ucfirst', $_t = explode('_', $methodName));
            $_class = 'Core_' . implode('_', array_splice($_temp, 0, - 1));
            $_call = array($_class, array_pop($_t));
            if(class_exists($_call[0], true))
            {
                if(method_exists($_call[0], $_call[1]))
                {
                    return @call_user_func($_call);
                }
            }
        }
        return '';
    }

    /**
     * 获取模板目录
     * @param string $style 风格
     * @return string
     * @author Icehu
     * @todo 考虑用户风格设置
     */
    public static function getTemplateDirs()
    {
        $_return = array();
        $defStyle = 'default'; //默认皮肤
        $userModel = new Model_User_Member();
        $cUser = $userModel->onGetCurrentUser();
        $cUser['uid'] && $userInfo = $userModel->getUserInfoByUid($cUser['uid']);
        //当前用户有自定皮肤且与默认皮肤不同
        if(isset($userInfo) && $userInfo['style'] && $userInfo['style'] != $defStyle)
            $_return[] = TEMPLATE_PATH . $userInfo['style'];
        $_return[] = TEMPLATE_PATH . $defStyle;
        return $_return;
    }

    /**
     * 获取插件的模板目录
     * @param string $pluginName 插件名称
     * @param string $style 风格名称
     * @return string
     * @author Icehu
     * @todo 考虑用户风格设置
     */
    public static function getPluginTemplateDirs($pluginName, $style = '')
    {
        $_return = array(PLUGIN_PATH . ucfirst($pluginName) . '/View');
        $defStyle = 'default'; //默认皮肤
        $userModel = new Model_User_Member();
        $cUser = $userModel->onGetCurrentUser();
        $cUser['uid'] && $userInfo = $userModel->getUserInfoByUid($cUser['uid']);
        //当前用户有自定皮肤且与默认皮肤不同
        if(isset($userInfo) && $userInfo['style'] && $userInfo['style'] != $defStyle)
            $_return[] = TEMPLATE_PATH . $userInfo['style'];
        $_return[] = TEMPLATE_PATH . $defStyle;
        return $_return;
    }

}
