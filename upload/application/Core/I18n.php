<?php
class Core_I18n extends Core_Config {
	
	public static $_prefix = 'i18n.';
//	#tested!
	public static function get($key=null , $group='basic') {
		if(is_array($key))
		{
			$group = $key['group'];
			$params = $key['params'];
			$key = $key['key'];
		}
		else if (preg_match('/\|/',$key))
		{
			$a = explode('|',$key);
			$key = $a[1] ? $a[1] : null;
			$group = $a[0] ? $a[0] : ($group ? $group : 'basic');
		}
		$value = parent::get($key,self::_getgroup($group),$key);
		if(isset($params) && is_array($params))
		{
			$v = vsprintf($value, $params);
			if(false === $v)
				return $value;
			else
				return $v;
		}
		return $value;
	}
	#tested
	public static function updatecache( $group='basic' ) {
		parent::update(self::_getgroup($group) );
	}
	#tested
	public static function _getcachefile( $group ) {
		return '_settings/_i18n/' . $group . '.php';
	}
	#tested
	public static function set( $key , $value=null , $group='basic' ) {
		parent::set( $key , $value , self::_getgroup($group) );
	}
	
	#增加、更新配置项
	public static function add($configs=array() , $group='basic') {
		parent::add( $configs , self::_getgroup($group) );
	}
	
	#更新配置到数据库
	public static function update( $group='basic' ) {
		parent::update( self::_getgroup($group) );
	}
	
	public static function _getgroup( $group ) {
		return self::$_prefix . $group;
	}
	
	public function __get( $_key ) {
		$_args = explode('_', $_key);
		if (count($_args) == 1) {
			return self::get(null,$_args[0]);
		}else if (count($_args) >= 2) {
			return self::get(implode('_',array_splice($_args,1)),$_args[0]);
		}
	}
}