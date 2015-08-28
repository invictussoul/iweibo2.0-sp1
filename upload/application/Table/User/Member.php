<?php
/**
 * 用户表类
 *
 * @author lvfeng
 */
class Table_User_Member extends Table_Base
{
	/**
	 * 表名
	 *
	 * @var string
	 */
	public $_table = '##__user_member';

	/**
	 * 字段列表
	 *
	 * @var array
	 */
	public $_fields = array('uid', 'gid', 'username', 'nickname',
							'password', 'salt', 'secques',
							'email', 'gender', 'localauth', 'localauthtext',
							'style', 'oauthtoken', 'oauthtokensecret', 'name',
							'province', 'city', 'mobile',
							'regtime', 'regip', 'lastvisit', 'lastip',
							'homenation', 'homeprovince', 'homecity',
							'birthyear', 'birthmonth', 'birthday',
							'realname', 'privbirth', 'nation', 'newfollowers',
							'occupation', 'homepage', 'summary','fansnum','idolnum','trust'
							);

	/**
	 * 主键字段名
	 *
	 * @var string
	 */
	public $_idkey = 'uid';
	
	public static $tableObject;
	
	/**
	 * 获得表实例
	 *
	 * @return object
	 */
	public static function getInstance()
    {
        if(isset(self::$tableObject))
            return self::$tableObject;
        self::$tableObject = new self();
        return self::$tableObject;
    }
	
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		if(!isset(self::$_data[$this->_table]))
			self::$_data[$this->_table] = array();
	}
}
