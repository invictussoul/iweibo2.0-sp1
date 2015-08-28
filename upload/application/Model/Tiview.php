<?php
/**
 * 微访谈操作类
 * @author reginx<reginx@qq.com>
 * $Id: Tiview.php 3206 2011-06-23 05:07:49Z cyp $
 */
class Model_Tiview extends Core_Model{

	// 访谈表
	var $tTIview = '';
	// 访谈参与表
	var $tTIJoin = '';
	// 访谈内容表
	var $tTIPost = '';

	/**
     * 初始化
     *
     */
	function __construct(){
		parent::__construct();
		$this->tTIview = $this->_prefix.'user_tiview';
		$this->tTIJoin = $this->_prefix.'user_tiview_join';
		$this->tTIPost = $this->_prefix.'user_tiview_post';
	}

	/**
     * 获取分页数据句柄
     *
     * @param String $countSQl
     * @param String $selectSQL
     * @param Integer $pid
     * @param Integer $psize
     * @return Array
     */
	function getLimitList($pid,$psize,$countSQl,$selectSQL){
		$ret = array();
		$ret['pid']       = $pid;
		$ret['psize']     = $psize;
		$ret['query']     = FALSE;
		$pid             = intval($pid) === 0?1:intval($pid);
		$start         = ($pid - 1) * $psize;
		$ret['total']     = Core_Db::getOne($countSQl);
		$ret['maxPage']     = $ret['total'] % $psize === 0 ?($ret['total'] / $psize):(intval($ret['total'] / $psize)+1);
		if($ret['total']     === 0){
			return $ret;
		}
		$ret['query']     = Core_Db::query(sprintf($selectSQL,"limit  $start , $psize"));
		return $ret;
	}


	/**
     * 获取访谈数据列表(支持分页)
     *
     * @param Integer $pid
     * @param Integer $psize
     * @param Array $fields
     * @param String $where
     * @param String $order
     * @return Array
     */
	function getTIviewList($pid=1,$psize=20,$fields=array(),$where='',$order=' id desc'){
		$client = Core_Open_Api::getClient();
		$ret      = array();
		$fields = empty($fields)?'*':'`'.implode('`,`',$fields).'`';
		$where  = $where ==''?' 1=1 ' : $where;
		$ret = $this->getLimitList( $pid, $psize,
		"select count(id) from $this->tTIview where $where ",
		"select $fields from $this->tTIview where $where order by $order %s");
		$ret['data'] = $tmp =  array();
		while($row = Core_Db::fetchArray($ret['query'])){
			if($row['sdate'] < Core_Fun::time() && time() < $row['edate']){
				$row['statusText'] = '进行中';
			}
			if($row['sdate'] > Core_Fun::time()){
				$row['statusText'] = '未开始';
			}
			if($row['edate'] < Core_Fun::time()){
				$row['statusText'] = '已结束';
			}
			$row['sdate']    = date('Y-m-d H:i',$row['sdate']);
			$row['edate']    = date('Y-m-d H:i',$row['edate']);
			$row['dateline']    = date('Y-m-d H:i',$row['dateline']);
			if(isset($row['host'])){
				$tmp = $client->getUserInfo(array('n'=>$row['host']));
				if($tmp['msg'] == 'ok'){
					$row['user'] = Core_Lib_Base::formatU($tmp['data'],50);
				}
			}
			$row['style']     = unserialize($row['style']);
			$ret['data'][]     = $row;
		}
		$ret['size']  = sizeof($ret['data']);
		return $ret;
	}

	/**
     * 获取访谈参与人员列表(支持分页)
     *
     * @param Integer $pid
     * @param Integer $psize
     * @param Array $fields
     * @param String $where
     * @param String $order
     * @return Array
     */
	function getTIJoinList($pid=1,$psize=20,$fields=array(),$where='1=1',$order=' id desc'){
		$ret = array();
		$client = Core_Open_Api::getClient();
		$csql = "select count(id) from $this->tTIJoin where $where ";
		$qsql = "select * from $this->tTIJoin where $where order by $order %s ";
		$ret  = $this->getLimitList($pid,$psize,$csql,$qsql);
		$ret['data'] = array();
		while($row = Core_Db::fetchArray($ret['query'])){
			$ret['data'][strtolower($row['uname'])] = $row;
		}
		$userinfos = Model_User_Util::getInfos(array_keys($ret['data']));
		foreach ($userinfos as &$v){
			if($v['head']){
				$v['head'] .='/50';
			}else{
				$v['head'] = '/resource/images/default_head_50.png';
			}
		}
		$ret['data'] = array_merge($ret['data'],$userinfos);
		return $ret;
	}


	/**
     * 保存微访谈
     *
     * @param Array $tlive
     * @param Array $style
     * @param Array $users
     * @return Boolean
     */
	function saveTIview($tlive=array(),$style=array(),$users=array()){
		$ret = array();
		if(trim($tlive['tname']) == ''){
			$ret['error'] = '请填写微访谈名称!';
		}
		$$tlive['tname'] = Core_Db::sqlescape(htmlspecialchars($tlive['tname']));
		$tlive['direct']  = ($tlive['direct'] === NULL)?1:0;
		if(trim($tlive['desc']) == ''){
			$ret['error'] = '请填写微访谈简介!';
		}
		$tlive['desc'] = Core_Db::sqlescape(htmlspecialchars($tlive['desc']));
		if(intval($tlive['sdate']) === 0 || strtotime($tlive['sdate']) >= strtotime($tlive['edate'])
		|| !preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}(\s*\d{1,2}(\:\d{1,2}){0,2})?$/i',$tlive['sdate'])){
			$ret['error'] = '请填写正确开始时间!';
		}
		if(intval($tlive['edate']) === 0 || strtotime($tlive['sdate']) >= strtotime($tlive['edate'])
		|| !preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}(\s*\d{1,2}(\:\d{1,2}){0,2})?$/i',$tlive['edate'])){
			$ret['error'] = '请填写正确结束时间!';
		}
		if(empty($users[0]) || trim($users[0]) == ''){
			$ret['error'] = '请填写主持人!';
		}
		$users[0] = array_unique(explode(";",preg_replace('/\s+/i',';',$users[0])));
		if(empty($users[1]) || trim($users[1]) == ''){
			$ret['error'] = '请填写嘉宾!';
		}
		$ret['error'] = self::getUser($users[0],$this->_prefix);
		$users[1] = array_unique(explode(";",preg_replace('/\s+/i',';',$users[1])));
		$same = array_uintersect($users[0], $users[1], "strcasecmp");
		if(!empty($same) && is_array($same)){
			$ret['error'] =  "同一帐号只能是主持人或嘉宾! ".implode(',',$same);
			return $ret;
		}
		$ret['error'] = self::getUser($users[1],$this->_prefix);
		if(isset($ret['error']) && !empty($ret['error'])){
			return $ret;
		}
		$tlive['notice'] = 0;
		$tlive['sdate'] = strtotime($tlive['sdate']);
		$tlive['edate'] = strtotime($tlive['edate']);


		// 样式处理
		if(isset($_FILES['outward']) && $_FILES['outward']['error'] === 0){
			$upload = Core_Util_Upload::upload('outward','jpg,png',1024*1024);
			if($upload['code'] != 0){
				return array('error'=>$upload['msg']);
			}else{
				$style['outward'] = $upload['link'];
			}
		}
		// 封面
		if(isset($_FILES['cover']) && $_FILES['cover']['error'] === 0){
			$upload = Core_Util_Upload::upload('cover','jpg,png',1024*1024);
			if($upload['code'] != 0){
				return array('error'=>$upload['msg']);
			}else{
				$style['cover'] = $upload['link'];
			}
		}
		// 背景
		if(isset($_FILES['background']) && $_FILES['background']['error'] === 0){
			$upload = Core_Util_Upload::upload('background','jpg,png',1024*1024);
			if($upload['code'] != 0){
				return array('error'=>$upload['msg']);
			}else{
				$style['background'] = $upload['link'];
			}
		}
		// 颜色代码验证
		if(!preg_match('/^\#[0-9a-zA-Z]{6}$/i',$style['bgcolor'])){
			$style['bgcolor'] = '';
		}
		if(!preg_match('/^\#[0-9a-zA-Z]{6}$/i',$style['linkcolor'])){
			$style['linkcolor'] = '';
		}
		$style['vod']     = '0';
		$style['repeat']  = $style['repeat']?1:0;
		$style         = addslashes(serialize($style));
		if(intval($tlive['id']) === 0){
			$sql = "insert into {$this->tTIview}(tname,notice,style,`desc`,direct,sdate,edate,dateline,`host`)".
			" values('{$tlive['tname']}',{$tlive['notice']},'$style','{$tlive['desc']}',{$tlive['direct']},".
			" {$tlive['sdate']},{$tlive['edate']},".Core_Fun::time().",'{$users[0][0]}')";
			Core_Db::query($sql);
			$tlive['id']  = Core_Db::insertId();
		}else{
			$sql = "update {$this->tTIview} set tname = '{$tlive['tname']}',notice={$tlive['notice']},".
			" style='$style',`desc`='{$tlive['desc']}',direct={$tlive['direct']},sdate={$tlive['sdate']},edate={$tlive['edate']} ,".
			" `host` = '{$users[0][0]}' where id = {$tlive['id']} ";
			Core_Db::query($sql);
		}
		// 新增主持人
		$this->saveTJoin($tlive['id'],$users[0],0);
		// 新增嘉宾
		$this->saveTJoin($tlive['id'],$users[1],1);
		return TRUE;
	}

	/**
     * 验证参与帐号
     *
     * @param unknown_type $user
     * @return unknown
     */
	function getUser(&$user,$prefix){
		for ($i=0;$i<sizeof($user);$i++){
			if(trim($user[$i]) != ''){
				$userInfo = array();
				try{
					$userInfo = Core_Open_Api::getClient()->getUserInfo(array("n" => $user[$i]));
				}catch(exception $e){
					if(!$name){
						return "帐号 {$user[$i]} 不存在!";
					}
				}
				if(empty($userInfo['data']['name'])){// 平台帐号不存在
					if(!$name){
						return "帐号 {$user[$i]} 不存在!";
					}
				}
			}else{
				unset($user[$i]);
			}
		}
		$user = array_unique($user);
	}

	/**
     * 添加/更新参与者(主持人OR嘉宾)
     *
     * @param Integer $tid
     * @param mixed $uname
     * @param Integer $utype 0 主持人 1 嘉宾
     * @return Boolean
     */
	function saveTJoin($tid,$uname,$utype=0){
		Core_Db::query("delete from $this->tTIJoin where tid = '$tid' and utype = $utype");
		$sql = "insert into {$this->tTIJoin}(tid,uname,utype,dateline) values ";
		$dateline = time();
		if(is_array($uname)){
			foreach($uname as $k=>$name){
				if(trim($name) != ''){
					if($k === 0){
						$sql .= "($tid,'".Core_Db::sqlescape(htmlspecialchars($name))."',$utype,$dateline)";
					}else{
						$sql .= ",($tid,'".Core_Db::sqlescape(htmlspecialchars($name))."',$utype,$dateline)";
					}
				}
			}
		}else{
			$sql .= "($tid,'".Core_Db::sqlescape(htmlspecialchars($uname))."',$utype,$dateline)";
		}
		return Core_Db::query($sql);
	}

	/**
     * 新增访谈内容
     *
     * @param String  $uname   用户帐号
     * @param Integer $tid     访谈ID
     * @param String  $message 消息内容
     * @return Boolean
     */
	function addTIPost($uname,$tid,$msgid,$status,$pid=0,$date,$tvip){
		$ret  = FALSE;
		$date = intval($date);
		$status = intval($status);
		$sql  = "insert into {$this->tTIPost} (uname,tid,msgid,pid,dateline,`status`,tuname) ".
		" values('$uname',$tid,$msgid,$pid,$date,$status,'$tvip')";
		if($ret = Core_DB::query($sql)){
			if($pid !== 0){
				// 如果为回答, 则设置问题为已回答
				$ret = Core_DB::query("update $this->tTIPost set reply=1 where msgid = $pid");
			}
		}
		return $ret;
	}


	/**
     * 检验是否为嘉宾/主持人
     *
     * @param unknown_type $tivid
     * @param unknown_type $uname
     * @return unknown
     */
	function isVip($tivid,$uname){
		return Core_Db::getOne("select count(id) from $this->tTIJoin where uname = '$uname' and tid = '$tivid'");
	}


	/**
     * 设置访谈内容状态(0待审核,1通过)
     *
     * @param Integer $tpid
     * @param Integer $status
     * @return Boolean
     */
	function setTIPostStatus($id,$status){
		$status = $status?1:0;
		$id = is_array($id)? " in (".implode(',',Core_Db::sqlescape($id)).") ":" = '".Core_Db::sqlescape($id)."'";
		return Core_Db::query("update {$this->tTIPost} set status = $status where msgid $id ");
	}

	/**
     * 删除访谈
     *
     * @param Integer $tid
     * @return Boolean
     */
	function delTIview($tid){
		$tid = intval($tid);
		return Core_Db::query("delete from {$this->tTIJoin} where tid = '$tid'") &&
		Core_Db::query("delete from {$this->tTIPost} where tid = '$tid'") &&
		Core_Db::query("delete from {$this->tTIview} where id  = '$tid'");
	}

	/**
     * 获取一条微访谈记录
     *
     * @param unknown_type $where
     * @return unknown
     */
	function getTIview($where,$user=FALSE,$dateFormat=true){
		if($tlive = Core_Db::fetchOne("select * from {$this->tTIview} where $where")){
			$tlive = array_map('stripslashes',$tlive);
			if($user){
				$query = $this->getTIJoins($tlive['id'],TRUE);
				while ($user = Core_Db::fetchArray($query)) {
					$tlive['users'][$user['utype']] .= $user['uname']."\n";
				}
			}
			if($dateFormat){
				$tlive['sdate'] = date('Y-m-d H:i',$tlive['sdate']);
				$tlive['edate'] = date('Y-m-d H:i',$tlive['edate']);
			}
			$tlive['style'] = unserialize($tlive['style']);
			return $tlive;
		}
		return FALSE;
	}


	/**
     * 获取参与用户(主持人/嘉宾)
     *
     * @param Integer $tid
     * @param Boolean $query
     * @return Mixed
     */
	function getTIJoins($tid,$query=FALSE){
		$sql = "select uname,utype from {$this->tTIJoin} where tid = '$tid' ";
		return $query?Core_Db::query($sql):Core_Db::fetchAll($sql);
	}



	/**
     * 获取访谈内容
     *
     * @param unknown_type $tid
     * @param unknown_type $pid
     * @param unknown_type $psize
     * @param unknown_type $ol
     */
	function getTIPostList($tid,$pid,$psize=30,$uname=NULL,$ol=TRUE,$status=NULL,$tpid=NULL){
		$ret = $tvs = array();
		$csql = $qsql = '';
		$status = empty($status)&&$status!==0?" 1=1 ":(intval($status) === 1?" status = 1 ":" status = 0 ");
		$tpid   = $tpid === NULL ? "": " and  pid = $tpid ";
		if($tid > 0){
			// 获取某访谈,某用户下,审核通过,的问题
			$where = " where ".(empty($uname)?" 1=1 ":" tuname = '$uname'").
			" and tid = $tid  and  $status $tpid ";
			$csql  = "select count(id) from $this->tTIPost $where";
			$qsql  = "select * from $this->tTIPost $where order by id desc %s";
		}else{
			// 全部 进行中的,须审核内容的 在线访谈
			$where = array();
			if($ol){
				$where = " sdate < ".Core_Fun::time()." and edate > ".Core_Fun::time()." and direct = 0 ";
			}
			$sql   = "select id,tname from $this->tTIview where $where ";
			$query = Core_Db::query($sql);
			while ($row = Core_Db::fetchArray($query)) {
				$tvs[$row['id']] = $row['tname'];
			}
			if(empty($tvs)){
				return $ret;
			}
			$where = " tid in (".implode(',',array_keys($tvs)).") and  $status";
			$csql = "select count(id) from $this->tTIPost where $where";
			$qsql = "select * from $this->tTIPost where $where order by id desc %s";

		}
		$ret = $this->getLimitList($pid,$psize,$csql,$qsql);
		$ret['data'] = $ids =  array();
		while ($row = Core_Db::fetchArray($ret['query'])) {
			$ids[] = $row['msgid'];
			// 回答
			$sql = "select * from $this->tTIPost where pid = '{$row['msgid']}' and status = 1 order by id asc";
			$query = Core_Db::query($sql);
			while($arow = Core_Db::fetchArray($query)){
				$ids[] =  $arow['msgid'];
				$arow['tpid'] = $arow['id'];
				$arow['tstatus'] = $arow['status'];	
				$row['answer'][$arow['msgid']] = $arow;
			}
			$row['tname'] = $tvs[$row['tid']];
			$row['tpid'] = $row['id'];
			$row['tstatus'] = $row['status'];
			$ret['data'][$row['msgid']] = $row;
		}
		if(!empty($ids)){
			$client = Core_Open_Api::getClient();
			$msgs   = $client->getTlineFromIds(array('ids'=>implode(',',$ids)));

			// 格式化
			$temp = array();
			for ($i=0;$i<sizeof($msgs['data']['info']);$i++){
				$temp[$msgs['data']['info'][$i]['id'].''] = Core_Lib_Base::formatT($msgs['data']['info'][$i]);
			}

			foreach ($ret['data'] as $k => &$v){
				if(isset($temp[$k])){
					$v = array_merge($v,$temp[$k]);
					if(!empty($v['answer'])){
						foreach($v['answer'] as $ak => &$av){
							if(isset($temp[$ak])){
								$av = array_merge($av,$temp[$ak]);
							}else{
								unset($ret['data'][$k]['answer'][$ak]);
								$this->delTIPost($ak);
							}
						}
					}
				}else{
					unset($ret['data'][$k]);
					$this->delTIPost($k);
				}
			}
		}
		$ret['size'] = sizeof($ret['data']);
		if($tid>0){
			$ret['tiview'] = core_DB::fetchOne("select id,tname from $this->tTIview where id = '".intval($tid)."'");
		}
		return $ret;
	}

	/**
     * 删除问题及回答
     *
     * @param unknown_type $msgid
     * @return unknown
     */
	function delTIPost($msgid){
		return Core_Db::query("delete from $this->tTIPost where pid = $msgid or msgid = $msgid");
	}
	/**
     * 获取已经回答的内容
     *
     * @param unknown_type $tid
     * @param unknown_type $pid
     * @param unknown_type $psize
     * @param unknown_type $ol
     */
	function getTIPostListByReply($tid,$pid,$psize=30){
		$ret = array();
		// 获取某访谈,某用户下,审核通过,有回答, 的问题
		$where  = " where   tid  = $tid and  status = 1 and pid = 0 and reply = 1";
		$csql = "select count(id) from $this->tTIPost $where";
		$qsql = "select * from $this->tTIPost $where order by id desc %s";
		$ret = $this->getLimitList($pid,$psize,$csql,$qsql);
		$ret['data'] = $ids =  array();

		while ($row = Core_Db::fetchArray($ret['query'])) {
			$ids[] = $row['msgid'];
			$sql = "select * from $this->tTIPost where pid = '{$row['msgid']}' and status = 1 order by id asc";
			$query = Core_Db::query($sql);
			while($arow = Core_Db::fetchArray($query)){
				$ids[] = $arow['msgid'];
				$arow['tpid'] = $arow['id'];
				$arow['tstatus'] = $arow['status'];
				$row['answer'][$arow['msgid']] = $arow;
			}
			$row['tpid'] = $row['id'];
			$row['tstatus'] = $row['status'];
			$ret['data'][$row['msgid']] = $row;
		}

		if(!empty($ids)){
			$client = Core_Open_Api::getClient();
			$msgs   = $client->getTlineFromIds(array('ids'=>implode(',',$ids)));
			// 格式化
			$temp = array();
			for ($i=0;$i<sizeof($msgs['data']['info']);$i++){
				$temp[$msgs['data']['info'][$i]['id'].''] = Core_Lib_Base::formatT($msgs['data']['info'][$i]);
			}

			foreach ($ret['data'] as $k => &$v){
				if(isset($temp[$k])){
					$v = array_merge($v,$temp[$k]);
					if(!empty($v['answer'])){
						foreach($v['answer'] as $ak => &$av){
							if(isset($temp[$ak])){
								$av = array_merge($av,$temp[$ak]);
							}else{
								unset($ret['data'][$k]['answer'][$ak]);
								$this->delTIPost($ak);
							}
						}
					}
				}else{
					unset($ret['data'][$k]);
					$this->delTIPost($k);
				}
			}
		}
		// 问题数
		$ret['size'] = sizeof($ret['data']);
		// 回答数
		$ret['reply'] = Core_Db::getOne("select count(id) from $this->tTIPost where tid = $tid and pid != 0  and status = 1 ");
		$ret['tiview'] = core_DB::fetchOne("select id,tname from $this->tTIview where id = '".intval($tid)."'");
		return $ret;
	}


}