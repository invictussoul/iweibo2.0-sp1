<?php
/**
 * 数据备份恢复 相关模块
 * @author reginx<reginx@qq.com>
 * $Id: Db.php 3213 2011-06-23 07:19:41Z cyp $
 */
class Controller_Admin_Db extends Core_Controller_Action
{

	public function backupAction(){
		// 数据库 备份
		$dbMod = new Model_Db();
		$step = intval($this->getParam('step'));
		if($step === 1){
			$tables = array();
			$tmp = $dbMod->getAllTables(1);
			$tids = $this->getParam('t');
			$tids = is_array($tids)?$tids:array(intval($tids));
			if($this->getParam('type') == 'ALL'){
				$tids = array();
				$cfg = include_once(ROOT . 'config/db.php');
				foreach ($tmp as $k => $v){
					if(preg_match('/'.preg_quote($cfg['dbPrefix']).'/i',$v['name'])){
						$tids[] = $k;
					}
				}
			}
			foreach ($tids as $v){
				$tables[$v] = $tmp[$v];
			}
			$tableStr = '';
			if(!empty($tids)){
				$tableStr = 't/'.implode('/t/',$tids);
			}
			$backupdir = $this->getParam('bd');
			$backupdir = (empty($backupdir)?md5(rand(100000,999999).time()):$backupdir);
			$start = $this->getParam('start');
			$start = empty($start) ? 0:intval($start);
			$tid = $this->getParam('tid');
			$filesize = $this->getParam('fs');
			$filesize  = empty($filesize) ? 2048 : intval($filesize);
			$fn = $this->getParam('fn');
			$fn = empty($fn) ? 1 : intval($fn);
			if($tid===NULL ){
				// 备份所有数据表结构
				$dbMod->sqlDumpTables($tables,$start,$filesize,ROOT.'/data/backup_'.$backupdir.'/');
				$tableStr = '';
				if(!empty($tids)){
					$tableStr = 't/'.implode('/t/',$tids);
				}
				$url = "admin/db/backup/step/1/start/0/tid/".array_shift($tids)."/fs/$filesize/bd/$backupdir/fn/1/$tableStr";
				$this->showmsg("表结构备份完毕 , 开始备份数据 ...",$url,1);
			}else{
				$tid = intval($tid);
				$ret = $dbMod->sqlDumpTableData($tables[$tid],$start,$filesize,ROOT.'/data/backup_'.$backupdir.'/',$fn);
				if($ret['code'] === 'next'){
					if(isset($tables[$tid+1])){
						$url = "admin/db/backup/step/1/tid/".($tid+1)."/fs/$filesize/bd/$backupdir/$tableStr";
						$this->showmsg("{$tables[$tid]['name']} 备份完成, 开始备份 ".$tables[$tid+1]['name']."! ",$url,1);
					}else{
						$this->showmsg("所有备份完成!",'admin/db/backup',3);
					}
				}else{
					if(isset($ret['error'])){
						$this->showmsg($ret['error']);
					}else{
						$url = "admin/db/backup/step/1/start/{$ret[start]}/tid/$tid/fs/$filesize/bd/$backupdir/$tableStr/fn/$ret[fn]";
						$this->showmsg("$ret[name] 已完成 $start - ".($start+300).", 剩余 ".$ret[last]." , 程序将自动运行!",$url,1);
					}
				}
			}
		}else{
			$this->assign('tables',$dbMod->getAllTables(0));
			$this->display('admin/db_backup.tpl');
		}
	}

	/**
	 * 数据恢复
	 *
	 */
	public function restoreAction(){
		$del = $this->getParam('del');
		$sDir = $this->getParam('d');
		$dbMod = new Model_Db();
		if(!empty($del)){
			$delDir = $this->getParam('dir');
			if(empty($delDir)){
				$this->showmsg('请选择文件目录!','admin/db/restore');
			}
			if(!is_array($delDir)){
				$delDir = array($delDir);
			}
			if($dbMod->unlinkFiles($delDir)){
				$this->showmsg('操作成功!','admin/db/restore');
			}else{
				$this->showmsg('操作失败!','admin/db/restore');
			}

		}else if(!empty($sDir)){
			$_SESSION['restore'] = TRUE;
			$pos = $this->getParam('pos');
			$fid = $this->getParam('fid');
			$pos = empty($pos)?0:intval($pos);
			$fid = empty($fid)?0:intval($fid);
			if($curDir = realpath(ROOT.'/data/'.$sDir)){
				$ret = $dbMod->restore($curDir,$fid,$pos);
				if($ret['done']){
					$_SESSION['restore'] = NULL;unset($_SESSION['restore']);
					$this->showmsg('恢复完成!','admin/db/restore',5);
				}
				$url = "admin/db/restore/d/$sDir/fid/$ret[fid]/pos/$ret[pos]";
				if($ret['pos'] === 0){
					$msg = " 已完成 $ret[file] . 即将进行下一步操作...";
				}else{
					$msg = " 正在恢复 $ret[file] , 写入数据 $pos - $ret[pos]  ...";
				}
				$this->showmsg($msg,$url,1);
			}
			$this->showmsg('所选备份不存在','admin/db/restore');
		}else{
			$dbMod = new Model_Db();
			$this->assign('files',$dbMod->getSQLFiles());
			$this->display('admin/db_restore.tpl');
		}
	}

}