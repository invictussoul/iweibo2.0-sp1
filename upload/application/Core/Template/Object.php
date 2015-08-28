<?php

/**
 * Smarty Template Object
 * 注册的Smarty 对象 TO
 * 
 * @author Icehu
 */
class Core_Template_Object
{
	/**
	 * <pre>
	 * Smarty获取配置文件钩子
	 * useage：
	 * <!--{TO->cfg key="key" group="basic"}-->
	 * </pre>
	 * @param array $params	获取的Smarty属性
	 * @return mix
	 * @author Icehu
	 */
	public function cfg($params)
	{
		$key = $params['key'];
		$group = $params['group'] ? $params['group'] : 'basic';
		$default = isset($params['default']) ? $params['default'] : '';
		return Core_Config::get($key, $group, $default);
	}

	public function i18n($params)
	{
		$key = $params['key'];
		$group = $params['group'] ? $params['group'] : 'basic';
		return Core_I18n::get($key, $group);
	}

	/**
	 * <pre>
	 * Smarty获取插件钩子方法
	 * useage：
	 * <!--{TO->hack name="index"}-->
	 * </pre>
	 * @param array $params	获取的Smarty属性
	 * @return string
	 * @author Icehu
	 */
	public function hack($params)
	{
		$name = (string)$params['name'];
		if($name)
		{
			return Core_Fun::pluginHack($name);
		}
	}

}
