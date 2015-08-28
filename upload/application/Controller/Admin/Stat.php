<?php
/**
 * 工具 - 统计 
 * @author reginx<reginx@qq.com>
 * $Id: Stat.php 3133 2011-06-22 08:38:09Z cyp $
 */
class Controller_Admin_Stat extends Core_Controller_Action
{

	/**
	 * 初始化
	 *
	 */
	private function doinit(){
		$limit = $this->getParam('limit');
		$limit = empty($limit)?'all':$limit;
		$this->assign('limit',$limit);
	}

	/**
	 * 统计首页
	 *
	 */
	public function indexAction(){
		$this->doinit();
		$this->assign('stat',Model_Stat::getTotal('user',$this->getParam('limit')));
		$this->display('admin/stat_index.tpl');
	}

	/**
	 * 内容统计
	 *
	 */
	public function contAction(){
		$this->doinit();
		$this->assign('stat', Model_Stat::getTotal('content',$this->getParam('limit')));
		$this->display('admin/stat_index.tpl');
	}

	/**
	 * 互动统计
	 *
	 */
	public function interAction(){
		$this->doinit();
		$this->assign('stat',Model_Stat::getTotal('interaction',$this->getParam('limit')));
		$this->display('admin/stat_index.tpl');
	}

	/**
	 * 趋势图
	 *
	 */
	public function viewAction(){
		$this->doinit();
		$stat = $this->getParam('stat');
		$opts = array();
		foreach ($stat as $v){
			if(is_array($v)){
				$opts = array_merge($v,$opts);
			}
		}
		$sdate = strtotime($this->getParam('sd'));
		$edate = strtotime($this->getParam('ed'));
		$sdate = $sdate ? $sdate:(strtotime(date('Y-m-d',time()))-86400);
		$edate = $edate ? $edate:(strtotime(date('Y-m-d',time()))+86400);
		$sum   = empty($_POST) || $_POST['stat']['sum']?1:0;
		$this->assign('sum',$sum);
		$this->assign('sd',$sdate);
		$this->assign('ed',$edate);
		$opts = empty($opts)?array('login'=>1,'reg'=>1,'wapvisit'=>1,'sum'=>1):$opts;
		$this->assign('stats',array_keys($opts));
		$merge = $this->getParam('merge');
		$merge = empty($merge) ? '':'/merge/1';
		$this->assign('opts',implode("/stat[]/",array_keys($opts)).$merge);
		$this->assign('merge',$merge);
		$opt = Model_Stat::getConfig();
		$this->assign('opt',$opt);
		$slang = Model_Stat::getLang();
		$this->assign('slang',$slang);
		$this->display('admin/stat_view.tpl');
	}

	public function statAction(){
		$stat = $this->getParam('stat[]');
		$stat = is_array($stat)?$stat:array($stat);
		$sum 	= in_array('sum',$stat)?1:0;
		if($sum){ unset($stat[array_search('sum',$stat)]);}

		$sdate = $this->getParam('sd');
		$edate = $this->getParam('ed');
		header("Content-Type:text/xml; charset=utf-8");
		header("Cache-control: private");
		$data = $lang = array();
		$days  = ($edate-$sdate) / 86400; // 天数
		foreach ($stat as $k => $v){
			$data[$v] = Model_Stat::getDayStat($v,$sdate,$edate);
		}

		// 综合概况
		if($sum){
			$data['sum'] = array();
			foreach ($data['login'] as $k=>$v){
				$data['sum'][$k] = $data['login'][$k] + $data['wapvisit'][$k] + $data['reg'][$k];
			}
		}
		// 合并统计
		$merge = $this->getParam('merge');
		if($merge){
			$tmp = array();
			foreach ($data as $k=> $v){
				if($k!='sum'){
					foreach ($v as $sk=>$sv){
						$tmp[$sk] = intval($tmp[$sk]) + $sv;
					}
				}
			}
			$data = array();
			$data['merge'] = $tmp;
			$tmp = NULL;unset($tmp);
		}


		$slang = Model_Stat::getConfig();
		foreach ($slang as $v){
			$lang = array_merge($v,$lang);
		}
		$lang['sum'] = '综合概况';
		$lang['merge'] = '合并结果';
		echo('<?xml version="1.0" encoding="utf-8" ?>'."\n");
		echo('<chart>');
		// 日期
		echo('<xaxis>');
		for ($i=1;$i<=$days;$i++){
			echo(' <value xid="'.$i.'">'.date('md',($sdate + ($i-1) * 86400)).'</value> ');
		}
		echo('</xaxis>');
		$i=0;
		echo('<graphs>');
		foreach($data as $k=>$v){
			echo('<graph gid="'.$i.'" title="'.$lang[$k].'">');
			$j = 1;
			foreach($v as $sv){
				echo('<value xid="'.($j).'">'.$sv.'</value>');
				$j++;
			}
			echo('</graph>');
			$i++;
		}
		echo('</graphs>');
		echo('</chart>');



	}

}