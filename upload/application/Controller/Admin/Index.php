<?php
/**
 * 后台首页
 *
 * @author Gavin <yaojungang@comsenz.com>
 */
class Controller_Admin_Index extends Core_Controller_Action
{

	public function indexAction()
	{
		$menulist = include CONFIG_PATH . 'menu.php';
		$this->assign('CHARSET', 'UTF-8');
		define('BASESCRIPT', Core_Fun::getPathroot().'admin/');
		$this->assign('mainurl', BASESCRIPT.'index/home');

		$this->assign('menulist', $menulist);
		$this->assign('adminname', $_SESSION['adminname']);
		$this->display('admin/index_index.tpl');
	}

	public function homeAction()
	{
		$this->assign('CHARSET', 'UTF-8');
		$this->assign('BASESCRIPT', 'index');
		$serverinfo = PHP_OS.' / PHP v'.PHP_VERSION;
		$serverinfo .= @ini_get('safe_mode') ? ' Safe Mode' : '';
		$this->assign('serverinfo', $serverinfo);
		$this->assign('fileupload', @ini_get('file_uploads') ? ini_get('upload_max_filesize') : '<font color="red">不能上传</font>');
		$this->assign('magic_quote_gpc',MAGIC_QUOTE_GPC ? 'On' : 'Off');
		$this->assign('allow_url_fopen',ini_get('allow_url_fopen') ? 'On' : 'Off');
		$this->assign('serverinfo', Core_Db::server_info());

		$dbsize = 0;
		$query = Core_Db::query("Show Table Status Like '##__%'");
		while($table = Core_Db::fetchArray($query, MYSQL_ASSOC))
		{
			$dbsize += $table['Data_length'] + $table['Index_length'];
		}
		$dbsize = $dbsize ? Core_Fun::formatBytes($dbsize) : 'unknow';
		$this->assign('dbsize', $dbsize);
        @include(CONFIG_PATH.'version.php');
        $this->assign('version', IWEIBO_VERSION);
        $this->assign('release', IWEIBO_RELEASE);
		$this->display('admin/index_home.tpl');
	}
}